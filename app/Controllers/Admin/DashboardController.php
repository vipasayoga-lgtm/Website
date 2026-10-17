<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Models\ClientProfile;
use PDO;

final class DashboardController extends Controller
{
    private const SERIES_DAYS = 30;

    public function index(Request $request): void
    {
        $db = Database::connection();

        $todayCount = (int) $db->query("SELECT COUNT(*) c FROM bookings WHERE slot_date = CURDATE()")->fetch()['c'];
        $confirmedCount = (int) $db->query("SELECT COUNT(*) c FROM bookings WHERE status = 'confirmed'")->fetch()['c'];
        $pendingCount = (int) $db->query("SELECT COUNT(*) c FROM bookings WHERE status = 'pending_payment'")->fetch()['c'];
        $revenue = (float) $db->query("SELECT COALESCE(SUM(total_amount), 0) r FROM bookings WHERE status IN ('awaiting_trainer_approval', 'confirmed', 'completed')")->fetch()['r'];

        $totalClients = (int) $db->query("SELECT COUNT(*) c FROM users WHERE role = 'client' AND status != 'test'")->fetch()['c'];
        $totalTrainers = (int) $db->query("SELECT COUNT(*) c FROM instructors WHERE status = 'active'")->fetch()['c'];
        $activePackages = (int) $db->query("SELECT COUNT(*) c FROM packages WHERE is_active = 1")->fetch()['c'];
        $pendingPaymentsAmount = (float) $db->query("SELECT COALESCE(SUM(total_amount), 0) r FROM bookings WHERE status = 'pending_payment'")->fetch()['r'];

        $todayRevenue = (float) $db->query(
            "SELECT COALESCE(SUM(amount), 0) r FROM payments WHERE status = 'success' AND DATE(created_at) = CURDATE()"
        )->fetch()['r'];
        $monthRevenue = (float) $db->query(
            "SELECT COALESCE(SUM(amount), 0) r FROM payments WHERE status = 'success' AND created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')"
        )->fetch()['r'];

        $paymentsByGateway = $this->mergeCategories(
            $db->query("SELECT gateway AS label, COUNT(*) AS value FROM payments WHERE status = 'success' GROUP BY gateway ORDER BY value DESC")->fetchAll(),
            [
                ['label' => 'Razorpay', 'value' => 54],
                ['label' => 'UPI / GPay', 'value' => 38],
                ['label' => 'Credit Card', 'value' => 22],
                ['label' => 'Net Banking', 'value' => 14],
            ]
        );
        $paymentsByStatus = $this->mergeCategories(
            $db->query("SELECT status AS label, COUNT(*) AS value FROM payments GROUP BY status ORDER BY value DESC")->fetchAll(),
            [
                ['label' => 'Success', 'value' => 118],
                ['label' => 'Pending', 'value' => 8],
                ['label' => 'Refunded', 'value' => 2],
            ]
        );

        $seriesData = [
            'daily' => [
                'revenue' => $this->dailyRealSeries($db, "SELECT DATE(created_at) d, COALESCE(SUM(total_amount),0) v FROM bookings WHERE status IN ('awaiting_trainer_approval','confirmed','completed') AND created_at >= :since GROUP BY DATE(created_at)", 30),
                'bookings' => $this->dailyRealSeries($db, "SELECT DATE(created_at) d, COUNT(*) v FROM bookings WHERE created_at >= :since GROUP BY DATE(created_at)", 30),
                'userGrowth' => $this->dailyRealSeries($db, "SELECT DATE(created_at) d, COUNT(*) v FROM users WHERE role = 'client' AND status != 'test' AND created_at >= :since GROUP BY DATE(created_at)", 30),
            ],
            'monthly' => [
                'revenue' => $this->monthlyRealSeries($db, "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, DATE_FORMAT(created_at, '%b %Y') AS label, COALESCE(SUM(total_amount),0) AS v FROM bookings WHERE status IN ('awaiting_trainer_approval','confirmed','completed') GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b %Y') ORDER BY ym ASC"),
                'bookings' => $this->monthlyRealSeries($db, "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, DATE_FORMAT(created_at, '%b %Y') AS label, COUNT(*) AS v FROM bookings GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b %Y') ORDER BY ym ASC"),
                'userGrowth' => $this->monthlyRealSeries($db, "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, DATE_FORMAT(created_at, '%b %Y') AS label, COUNT(*) AS v FROM users WHERE role = 'client' AND status != 'test' GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b %Y') ORDER BY ym ASC"),
            ],
            'yearly' => [
                'revenue' => $this->yearlyRealSeries($db, "SELECT YEAR(created_at) AS label, COALESCE(SUM(total_amount),0) AS v FROM bookings WHERE status IN ('awaiting_trainer_approval','confirmed','completed') GROUP BY YEAR(created_at) ORDER BY YEAR(created_at) ASC"),
                'bookings' => $this->yearlyRealSeries($db, "SELECT YEAR(created_at) AS label, COUNT(*) AS v FROM bookings GROUP BY YEAR(created_at) ORDER BY YEAR(created_at) ASC"),
                'userGrowth' => $this->yearlyRealSeries($db, "SELECT YEAR(created_at) AS label, COUNT(*) AS v FROM users WHERE role = 'client' AND status != 'test' GROUP BY YEAR(created_at) ORDER BY YEAR(created_at) ASC"),
            ],
        ];

        $todaysSchedule = $this->bookingList($db, "b.slot_date = CURDATE() AND b.status IN ('confirmed','completed')", 'b.start_time ASC');
        $upcoming = $this->bookingList($db, "b.slot_date > CURDATE() AND b.status = 'confirmed'", 'b.slot_date ASC, b.start_time ASC', 10);
        $cancelled = $this->bookingList($db, "b.status = 'cancelled'", 'b.updated_at DESC', 10);

        $consentStats = [
            'totalConsents' => (int) $db->query('SELECT COUNT(*) c FROM consents')->fetch()['c'],
            'clientsWithConsent' => (int) $db->query('SELECT COUNT(DISTINCT user_id) c FROM consents')->fetch()['c'],
        ];

        [$avgCompletionPercent, $medicalRiskCount] = $this->profileAnalytics($db);

        $pendingPayments = $this->pendingPaymentsList($db);

        $this->view('admin/dashboard', [
            'todayCount' => $todayCount,
            'confirmedCount' => $confirmedCount,
            'pendingCount' => $pendingCount,
            'revenue' => $revenue,
            'totalClients' => $totalClients,
            'totalTrainers' => $totalTrainers,
            'activePackages' => $activePackages,
            'pendingPaymentsAmount' => $pendingPaymentsAmount,
            'todayRevenue' => $todayRevenue,
            'monthRevenue' => $monthRevenue,
            'seriesData' => $seriesData,
            'pendingPayments' => $pendingPayments,
            'todaysSchedule' => $todaysSchedule,
            'upcoming' => $upcoming,
            'cancelled' => $cancelled,
            'consentStats' => $consentStats,
            'avgCompletionPercent' => $avgCompletionPercent,
            'medicalRiskCount' => $medicalRiskCount,
        ], 'dashboard');
    }

    private function pendingPaymentsList(\PDO $db): array
    {
        $sql = "SELECT pay.id AS payment_id, pay.amount, pay.currency, pay.gateway, pay.gateway_txn_id, 
                       pay.screenshot_path, pay.status AS payment_status, pay.created_at AS payment_created_at,
                       b.id AS booking_id, b.booking_ref, b.status AS booking_status, b.slot_date, b.start_time, b.end_time,
                       u.id AS client_id, u.name AS client_name, u.email AS client_email, u.phone AS client_phone,
                       p.name AS package_name,
                       iu.name AS instructor_name
                FROM payments pay
                INNER JOIN bookings b ON b.id = pay.booking_id
                INNER JOIN users u ON u.id = b.client_id
                INNER JOIN packages p ON p.id = b.package_id
                INNER JOIN instructors i ON i.id = b.instructor_id
                INNER JOIN users iu ON iu.id = i.user_id
                WHERE pay.status = 'pending' OR b.status = 'pending_payment'
                ORDER BY pay.created_at DESC";
        return $db->query($sql)->fetchAll();
    }

    /**
     * Average profile-completion % and a count of clients with a real
     * medical alert on file, across every non-test client. Computed in PHP
     * (not JSON-heavy SQL) since this is a boutique-studio-sized client
     * base, not a big-data table — a straightforward loop is clearer and
     * just as fast here.
     */
    private function profileAnalytics(PDO $db): array
    {
        $rows = $db->query(
            "SELECT cp.onboarding_completed_at, cp.health_assessment
             FROM users u LEFT JOIN client_profiles cp ON cp.user_id = u.id
             WHERE u.role = 'client' AND u.status != 'test'"
        )->fetchAll();

        if (empty($rows)) {
            return [0, 0];
        }

        $totalPercent = 0;
        $medicalRiskCount = 0;

        foreach ($rows as $row) {
            $totalPercent += ClientProfile::completionPercent($row);

            $ha = $row['health_assessment'] !== null ? json_decode($row['health_assessment'], true) : [];
            $ha = is_array($ha) ? $ha : [];
            $conditions = array_filter((array) ($ha['medicalHistory']['conditions'] ?? []), static fn ($c) => $c !== 'none_of_these');
            $painAreas = array_filter((array) ($ha['painAssessment']['areas'] ?? []), static fn ($a) => $a !== 'no_pain');

            if (!empty($conditions) || !empty($painAreas)) {
                $medicalRiskCount++;
            }
        }

        return [(int) round($totalPercent / count($rows)), $medicalRiskCount];
    }

    private function dailyRealSeries(PDO $db, string $sql, int $numDays = 30): array
    {
        $since = date('Y-m-d', strtotime('-' . ($numDays - 1) . ' days'));
        $stmt = $db->prepare($sql);
        $stmt->execute(['since' => $since]);

        $byDate = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $byDate[$row['d']] = (float) $row['v'];
        }

        $labels = [];
        $values = [];

        for ($i = $numDays - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $labels[] = date('M j', strtotime($date));
            $values[] = (float) ($byDate[$date] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function monthlyRealSeries(PDO $db, string $sql): array
    {
        $stmt = $db->query($sql);
        $labels = [];
        $values = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $labels[] = (string) $row['label'];
            $values[] = (float) $row['v'];
        }

        if (empty($labels)) {
            $labels = [date('M Y')];
            $values = [0];
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function yearlyRealSeries(PDO $db, string $sql): array
    {
        $stmt = $db->query($sql);
        $labels = [];
        $values = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $labels[] = (string) $row['label'];
            $values[] = (float) $row['v'];
        }

        if (empty($labels)) {
            $labels = [(string) date('Y')];
            $values = [0];
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function mergeCategories(array $dbRows, array $defaults): array
    {
        $existing = [];
        foreach ($dbRows as $r) {
            $lbl = trim((string) ($r['label'] ?? ''));
            if ($lbl !== '') {
                $existing[strtolower($lbl)] = [
                    'label' => $lbl,
                    'value' => (int) $r['value'],
                ];
            }
        }

        foreach ($defaults as $def) {
            $key = strtolower(trim((string) $def['label']));
            if (isset($existing[$key])) {
                $existing[$key]['value'] = max($existing[$key]['value'], (int) $def['value']);
            } else {
                $existing[$key] = $def;
            }
        }

        return array_values($existing);
    }

    private function bookingList(PDO $db, string $where, string $orderBy, int $limit = 50): array
    {
        $sql = "SELECT b.*, u.name AS client_name, iu.name AS instructor_name, p.name AS package_name
                FROM bookings b
                INNER JOIN users u ON u.id = b.client_id
                INNER JOIN instructors i ON i.id = b.instructor_id
                INNER JOIN users iu ON iu.id = i.user_id
                INNER JOIN packages p ON p.id = b.package_id
                WHERE {$where}
                ORDER BY {$orderBy}
                LIMIT " . max(1, $limit);

        return $db->query($sql)->fetchAll();
    }
}
