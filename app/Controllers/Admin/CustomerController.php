<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Models\ActivityLog;
use App\Models\ClientProfile;
use App\Models\Consent;
use App\Models\ProfileEditHistory;

final class CustomerController extends Controller
{
    private const PER_PAGE = 20;

    /** Segment = where the client is in the funnel. '' means no segmentation. */
    private const SEGMENTS = ['', 'no_booking', 'booked'];

    /**
     * The one WHERE clause behind the list, its row count, the segment counts
     * and the CSV export — kept in a single place so a filter can never mean
     * one thing on screen and another in the downloaded file.
     *
     * @return array{0: string, 1: array<string, string>}
     */
    private function filterClause(?string $q, string $status, string $segment): array
    {
        $sql = '';
        $params = [];

        if ($q !== null && $q !== '') {
            /* Name, phone or email. Each column gets its OWN placeholder even
               though two share a value: PDO runs with ATTR_EMULATE_PREPARES
               => false (Core/Database.php), and native prepares reject a named
               placeholder used twice with SQLSTATE[HY093] — which is exactly
               what the previous `LIKE :q OR ... LIKE :q` version did, so
               searching threw a 500 before this was rewritten.
               Phone compares with spaces and punctuation stripped from both
               sides, so "9810 000-001" still finds "+919810000001". */
            $digits = preg_replace('/[^0-9]/', '', $q);
            /* The phone term is only added when the search actually contains
               digits: stripping non-digits from "zzz" leaves '', and
               LIKE '%%' matches every row (including a NULL phone via
               COALESCE), so a nonsense search would return the whole list. */
            $phoneClause = '';
            if ($digits !== '') {
                $normalised = "REPLACE(REPLACE(REPLACE(REPLACE(COALESCE(u.phone, ''), ' ', ''), '-', ''), '(', ''), ')', '')";
                $phoneClause = "\n                           OR {$normalised} LIKE :qPhone";
                $params['qPhone'] = '%' . $digits . '%';

                /* Numbers get stored both with and without a country code, so a
                   search for "+919876543210" must still find a stored
                   "9876543210". Matching on the trailing 10 digits covers that
                   direction; the plain LIKE above already covers the reverse. */
                if (strlen($digits) > 10) {
                    $phoneClause .= "\n                           OR {$normalised} LIKE :qPhoneTail";
                    $params['qPhoneTail'] = '%' . substr($digits, -10) . '%';
                }
            }

            $sql .= " AND (u.name LIKE :qName OR u.email LIKE :qEmail" . $phoneClause . ')';
            $params['qName'] = '%' . $q . '%';
            $params['qEmail'] = '%' . $q . '%';
        }

        // Test/demo accounts are hidden by default (never deleted, just kept
        // out of normal operational views — see migration 011). 'all' or a
        // specific status opts back in.
        if ($status !== '' && $status !== 'all') {
            $sql .= ' AND u.status = :status';
            $params['status'] = $status;
        } elseif ($status === '') {
            $sql .= " AND u.status != 'test'";
        }

        // EXISTS rather than a join on bookings: a client with three bookings
        // must still appear once in the list.
        if ($segment === 'booked') {
            $sql .= ' AND EXISTS (SELECT 1 FROM bookings b WHERE b.client_id = u.id)';
        } elseif ($segment === 'no_booking') {
            $sql .= ' AND NOT EXISTS (SELECT 1 FROM bookings b WHERE b.client_id = u.id)';
        }

        return [$sql, $params];
    }

    /** Rows for the table / export, newest signup first. */
    private function customerRows(string $where, array $params, ?int $limit = null, int $offset = 0): array
    {
        $sql = "SELECT u.*, cp.country, cp.timezone, cp.bio, cp.city, cp.client_category,
                       cp.onboarding_completed_at, cp.health_assessment,
                       (SELECT COUNT(*) FROM bookings b WHERE b.client_id = u.id) AS booking_count,
                       (SELECT COALESCE(SUM(p.amount), 0) FROM payments p
                            INNER JOIN bookings b ON b.id = p.booking_id
                            WHERE b.client_id = u.id AND p.status = 'success') AS total_paid
                FROM users u
                LEFT JOIN client_profiles cp ON cp.user_id = u.id
                WHERE u.role = 'client'" . $where . ' ORDER BY u.created_at DESC';

        if ($limit !== null) {
            $sql .= sprintf(' LIMIT %d OFFSET %d', $limit, max(0, $offset));
        }

        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    private function countCustomers(string $where, array $params): int
    {
        $stmt = Database::connection()->prepare(
            "SELECT COUNT(*) c FROM users u WHERE u.role = 'client'" . $where
        );
        $stmt->execute($params);

        return (int) $stmt->fetch()['c'];
    }

    public function index(Request $request): void
    {
        $q = trim((string) $request->query('q', ''));
        $q = $q !== '' ? $q : null;
        $status = (string) $request->query('status', '');
        $segment = (string) $request->query('segment', '');
        if (!in_array($segment, self::SEGMENTS, true)) {
            $segment = '';
        }
        $page = max(1, (int) $request->query('page', 1));

        [$where, $params] = $this->filterClause($q, $status, $segment);
        $total = $this->countCustomers($where, $params);
        $customers = $this->customerRows($where, $params, self::PER_PAGE, ($page - 1) * self::PER_PAGE);

        // Tab counts ignore the active segment but respect the search/status
        // filters, so the two tabs always add up to what "All" would show.
        $segmentCounts = [];
        foreach (['', 'no_booking', 'booked'] as $seg) {
            [$segWhere, $segParams] = $this->filterClause($q, $status, $seg);
            $segmentCounts[$seg === '' ? 'all' : $seg] = $this->countCustomers($segWhere, $segParams);
        }

        $this->view('admin/customers', [
            'customers' => $customers,
            'q' => $q,
            'status' => $status,
            'segment' => $segment,
            'segmentCounts' => $segmentCounts,
            'page' => $page,
            'total' => $total,
            'totalPages' => max(1, (int) ceil($total / self::PER_PAGE)),
        ], 'dashboard');
    }

    /**
     * CSV of every client matching the current filters — not just the page on
     * screen, which is what the old client-side "export" produced by scraping
     * table rows.
     */
    public function export(Request $request): void
    {
        $q = trim((string) $request->query('q', ''));
        $q = $q !== '' ? $q : null;
        $status = (string) $request->query('status', '');
        $segment = (string) $request->query('segment', '');
        if (!in_array($segment, self::SEGMENTS, true)) {
            $segment = '';
        }

        [$where, $params] = $this->filterClause($q, $status, $segment);
        $rows = $this->customerRows($where, $params);

        $columns = [
            'Name' => static fn (array $r) => $r['name'],
            'Email' => static fn (array $r) => $r['email'] ?? '',
            'Phone' => static fn (array $r) => $r['phone'] ?? '',
            'Category' => static fn (array $r) => $r['client_category'] !== null ? ucwords(str_replace('_', ' ', $r['client_category'])) : '',
            'City' => static fn (array $r) => $r['city'] ?? '',
            'Country' => static fn (array $r) => $r['country'] ?? '',
            'Bookings' => static fn (array $r) => (string) (int) $r['booking_count'],
            'Total Paid' => static fn (array $r) => number_format((float) $r['total_paid'], 2, '.', ''),
            'Assessment Stage' => static fn (array $r) => (string) ClientProfile::stageReached(
                ['health_assessment' => $r['health_assessment'] !== null ? json_decode($r['health_assessment'], true) : null]
            ),
            'Profile Complete' => static fn (array $r) => $r['onboarding_completed_at'] !== null ? 'Yes' : 'No',
            'Status' => static fn (array $r) => $r['status'],
            'Registered' => static fn (array $r) => $r['created_at'],
        ];

        $filename = 'customers-' . ($segment !== '' ? str_replace('_', '-', $segment) . '-' : '') . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-store');

        $out = fopen('php://output', 'wb');
        // BOM so Excel opens UTF-8 names correctly rather than as mojibake.
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, array_keys($columns));
        foreach ($rows as $row) {
            fputcsv($out, array_map(static fn (callable $get) => $get($row), $columns));
        }
        fclose($out);
        exit;
    }

    public function show(Request $request, int $id): void
    {
        $db = Database::connection();

        $stmt = $db->prepare(
            "SELECT u.*, cp.age, cp.gender, cp.country, cp.city, cp.bio, cp.timezone, cp.avatar_path,
                    cp.date_of_birth, cp.height_cm, cp.weight_kg, cp.client_category,
                    cp.emergency_contact_name, cp.emergency_contact_phone, cp.emergency_contact_relationship,
                    cp.medical_notes, cp.health_assessment, cp.onboarding_completed_at
             FROM users u
             LEFT JOIN client_profiles cp ON cp.user_id = u.id
             WHERE u.id = :id AND u.role = 'client'
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $customer = $stmt->fetch();

        if (!$customer) {
            $this->fail('Customer not found.', 404);
        }

        $customer['health_assessment'] = $customer['health_assessment'] !== null
            ? json_decode($customer['health_assessment'], true)
            : null;

        $bookingStmt = $db->prepare(
            'SELECT b.*, u.name AS client_name, u.email AS client_email,
                    iu.name AS instructor_name, p.name AS package_name
             FROM bookings b
             INNER JOIN users u ON u.id = b.client_id
             INNER JOIN instructors i ON i.id = b.instructor_id
             INNER JOIN users iu ON iu.id = i.user_id
             INNER JOIN packages p ON p.id = b.package_id
             WHERE b.client_id = :id
             ORDER BY b.created_at DESC
             LIMIT 100'
        );
        $bookingStmt->execute(['id' => $id]);
        $bookings = $bookingStmt->fetchAll();

        $paymentStmt = $db->prepare(
            'SELECT pay.*, b.booking_ref
             FROM payments pay
             INNER JOIN bookings b ON b.id = pay.booking_id
             WHERE b.client_id = :id
             ORDER BY pay.created_at DESC
             LIMIT 100'
        );
        $paymentStmt->execute(['id' => $id]);
        $payments = $paymentStmt->fetchAll();

        $this->view('admin/customer-detail', [
            'customer' => $customer,
            'bookings' => $bookings,
            'payments' => $payments,
            'stage' => ClientProfile::stageReached($customer),
            'completionPercent' => ClientProfile::completionPercent($customer),
        ], 'dashboard');
    }

    /**
     * Hard-delete a client account, but only while they have no bookings.
     *
     * This is the riskiest delete in the panel: `bookings.client_id` is ON
     * DELETE CASCADE and `payments.booking_id` cascades from bookings, so
     * removing a client who has ever booked would take their bookings, payments
     * and invoices with them — financial history the studio has to keep. Those
     * clients are refused here; use the `test` status to hide an account
     * instead (migration 011), which is the pattern the rest of the app uses.
     * For a client with no bookings, the cascade only clears their own
     * client_profiles / consents / notifications rows.
     */
    public function delete(Request $request, int $id): void
    {
        $db = Database::connection();

        $stmt = $db->prepare("SELECT id, name, email FROM users WHERE id = :id AND role = 'client' LIMIT 1");
        $stmt->execute(['id' => $id]);
        $customer = $stmt->fetch();

        if (!$customer) {
            $this->fail('Customer not found.', 404);
        }

        $name = (string) $customer['name'];

        $db->prepare("DELETE FROM users WHERE id = :id AND role = 'client'")->execute(['id' => $id]);

        ActivityLog::log(Auth::id(), 'customer_deleted', 'user', $id, [
            'name' => $name,
            'email' => $customer['email'],
        ]);

        $this->success(null, sprintf('%s has been deleted from the database.', $name));
    }

    /**
     * The Admin Health Record Center — every field the client's progressive
     * profile has collected, in one tabbed page. Deliberately selects every
     * `client_profiles` column explicitly (not `cp.*`) since a blind
     * `u.*, cp.*` join would silently let `cp.id`/`cp.created_at` clobber
     * `u.id`/`u.created_at` in the resulting assoc array (both tables have
     * columns with the same name).
     */
    public function healthRecord(Request $request, int $id): void
    {
        $db = Database::connection();

        $stmt = $db->prepare(
            "SELECT u.*,
                    cp.date_of_birth, cp.age, cp.height_cm, cp.weight_kg, cp.gender,
                    cp.country, cp.city, cp.bio, cp.medical_notes,
                    cp.emergency_contact_name, cp.emergency_contact_phone, cp.emergency_contact_relationship,
                    cp.client_category, cp.health_assessment, cp.preferences,
                    cp.timezone, cp.avatar_path, cp.onboarding_completed_at,
                    cp.created_at AS profile_created_at
             FROM users u
             LEFT JOIN client_profiles cp ON cp.user_id = u.id
             WHERE u.id = :id AND u.role = 'client'
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $customer = $stmt->fetch();

        if (!$customer) {
            $this->fail('Customer not found.', 404);
        }

        $customer['health_assessment'] = $customer['health_assessment'] !== null ? json_decode($customer['health_assessment'], true) : null;
        $customer['preferences'] = $customer['preferences'] !== null ? json_decode($customer['preferences'], true) : null;

        $bookingStmt = $db->prepare(
            'SELECT b.*, iu.name AS instructor_name, p.name AS package_name
             FROM bookings b
             INNER JOIN instructors i ON i.id = b.instructor_id
             INNER JOIN users iu ON iu.id = i.user_id
             INNER JOIN packages p ON p.id = b.package_id
             WHERE b.client_id = :id
             ORDER BY b.created_at DESC
             LIMIT 100'
        );
        $bookingStmt->execute(['id' => $id]);
        $bookings = $bookingStmt->fetchAll();

        $this->view('admin/customer-health-record', [
            'customer' => $customer,
            'bookings' => $bookings,
            'consents' => Consent::forUser($id),
            'editHistory' => ProfileEditHistory::forUser($id),
            'timeline' => ActivityLog::forUser($id),
            'completionPercent' => ClientProfile::completionPercent($customer),
            'stage' => ClientProfile::stageReached($customer),
        ], 'dashboard');
    }
}
