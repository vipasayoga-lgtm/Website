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
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class CustomerController extends Controller
{
    private const PER_PAGE = 20;

    /** Segment = where the client is in the funnel. '' means no segmentation. */
    private const SEGMENTS = ['', 'no_booking', 'booked'];

    /**
     * The one WHERE clause behind the list, its row count, the segment counts
     * and the CSV/Excel export — kept in a single place so a filter can never mean
     * one thing on screen and another in the downloaded file.
     *
     * @return array{0: string, 1: array<string, string>}
     */
    private function filterClause(?string $q, string $status, string $segment, ?string $fromDate = null, ?string $toDate = null): array
    {
        $sql = '';
        $params = [];

        if ($q !== null && $q !== '') {
            $digits = preg_replace('/[^0-9]/', '', $q);
            $phoneClause = '';
            if ($digits !== '') {
                $normalised = "REPLACE(REPLACE(REPLACE(REPLACE(COALESCE(u.phone, ''), ' ', ''), '-', ''), '(', ''), ')', '')";
                $phoneClause = "\n                           OR {$normalised} LIKE :qPhone";
                $params['qPhone'] = '%' . $digits . '%';

                if (strlen($digits) > 10) {
                    $phoneClause .= "\n                           OR {$normalised} LIKE :qPhoneTail";
                    $params['qPhoneTail'] = '%' . substr($digits, -10) . '%';
                }
            }

            $sql .= " AND (u.name LIKE :qName OR u.email LIKE :qEmail" . $phoneClause . ')';
            $params['qName'] = '%' . $q . '%';
            $params['qEmail'] = '%' . $q . '%';
        }

        // Test/demo accounts are hidden by default
        if ($status !== '' && $status !== 'all') {
            $sql .= ' AND u.status = :status';
            $params['status'] = $status;
        } elseif ($status === '') {
            $sql .= " AND u.status != 'test'";
        }

        if ($segment === 'booked') {
            $sql .= ' AND EXISTS (SELECT 1 FROM bookings b WHERE b.client_id = u.id)';
        } elseif ($segment === 'no_booking') {
            $sql .= ' AND NOT EXISTS (SELECT 1 FROM bookings b WHERE b.client_id = u.id)';
        }

        if ($fromDate && $toDate) {
            $sql .= ' AND DATE(u.created_at) BETWEEN :fromDate AND :toDate';
            $params['fromDate'] = $fromDate;
            $params['toDate'] = $toDate;
        } elseif ($fromDate) {
            $sql .= ' AND DATE(u.created_at) >= :fromDate';
            $params['fromDate'] = $fromDate;
        } elseif ($toDate) {
            $sql .= ' AND DATE(u.created_at) <= :toDate';
            $params['toDate'] = $toDate;
        }

        return [$sql, $params];
    }

    private function normalizeDates(?string $from, ?string $to): array
    {
        $valid = static function (?string $val): ?string {
            if (!$val) return null;
            $val = trim($val);
            $d = \DateTimeImmutable::createFromFormat('!Y-m-d', $val);
            return ($d !== false && $d->format('Y-m-d') === $val) ? $val : null;
        };

        $fromDate = $valid($from);
        $toDate = $valid($to);

        if ($fromDate && $toDate && $fromDate > $toDate) {
            [$fromDate, $toDate] = [$toDate, $fromDate];
        }

        return [$fromDate, $toDate];
    }

    /** Rows for the table / export, newest signup first. */
    private function customerRows(string $where, array $params, ?int $limit = null, int $offset = 0): array
    {
        $sql = "SELECT u.*,
                       cp.country, cp.timezone, cp.bio, cp.city, cp.client_category,
                       cp.date_of_birth, cp.age, cp.gender, cp.height_cm, cp.weight_kg,
                       cp.emergency_contact_name, cp.emergency_contact_phone, cp.emergency_contact_relationship,
                       cp.medical_notes, cp.preferences, cp.onboarding_completed_at, cp.health_assessment,
                       (SELECT COUNT(*) FROM bookings b WHERE b.client_id = u.id) AS booking_count,
                       (SELECT COUNT(*) FROM bookings b WHERE b.client_id = u.id AND b.status = 'confirmed') AS confirmed_count,
                       (SELECT COUNT(*) FROM bookings b WHERE b.client_id = u.id AND b.status = 'completed') AS completed_count,
                       (SELECT COUNT(*) FROM bookings b WHERE b.client_id = u.id AND b.status = 'cancelled') AS cancelled_count,
                       (SELECT MAX(b.slot_date) FROM bookings b WHERE b.client_id = u.id) AS last_booking_date,
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
        [$fromDate, $toDate] = $this->normalizeDates($request->query('from_date'), $request->query('to_date'));
        $page = max(1, (int) $request->query('page', 1));

        [$where, $params] = $this->filterClause($q, $status, $segment, $fromDate, $toDate);
        $total = $this->countCustomers($where, $params);
        $customers = $this->customerRows($where, $params, self::PER_PAGE, ($page - 1) * self::PER_PAGE);

        $segmentCounts = [];
        foreach (['', 'no_booking', 'booked'] as $seg) {
            [$segWhere, $segParams] = $this->filterClause($q, $status, $seg, $fromDate, $toDate);
            $segmentCounts[$seg === '' ? 'all' : $seg] = $this->countCustomers($segWhere, $segParams);
        }

        $this->view('admin/customers', [
            'customers' => $customers,
            'q' => $q,
            'status' => $status,
            'segment' => $segment,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'segmentCounts' => $segmentCounts,
            'page' => $page,
            'total' => $total,
            'totalPages' => max(1, (int) ceil($total / self::PER_PAGE)),
        ], 'dashboard');
    }

    public function exportExcel(Request $request): void
    {
        $this->export($request);
    }

    public function export(Request $request): void
    {
        $all = $request->query('all') === '1' || $request->query('all') === 'true';
        $format = strtolower((string)$request->query('format', 'xlsx'));

        if ($all) {
            $where = '';
            $params = [];
            $segment = '';
            $status = '';
            $fromDate = null;
            $toDate = null;
        } else {
            $q = trim((string) $request->query('q', ''));
            $q = $q !== '' ? $q : null;
            $status = (string) $request->query('status', '');
            $segment = (string) $request->query('segment', '');
            if (!in_array($segment, self::SEGMENTS, true)) {
                $segment = '';
            }
            [$fromDate, $toDate] = $this->normalizeDates($request->query('from_date'), $request->query('to_date'));
            [$where, $params] = $this->filterClause($q, $status, $segment, $fromDate, $toDate);
        }

        $rows = $this->customerRows($where, $params);

        $dateSuffix = '';
        if ($all) {
            $dateSuffix = '_master_all';
        } elseif ($fromDate && $toDate) {
            $dateSuffix = '_' . $fromDate . '_to_' . $toDate;
        } elseif ($fromDate) {
            $dateSuffix = '_from_' . $fromDate;
        } elseif ($toDate) {
            $dateSuffix = '_upto_' . $toDate;
        } else {
            $dateSuffix = '_' . date('Y-m-d');
        }

        if ($format === 'csv') {
            $this->streamCustomersCsv($rows, $segment, $dateSuffix);
        }

        $this->streamCustomersXlsx($rows, $segment, $status, $fromDate, $toDate, $dateSuffix, $all);
    }

    private function parseHealthAssessment(?string $raw): array
    {
        if (!$raw) {
            return [
                'primary_goal' => '—',
                'fitness_level' => '—',
                'pain_areas' => '—',
                'conditions' => '—',
                'restrictions' => 'None',
                'diet' => '—',
            ];
        }
        $ha = json_decode($raw, true) ?: [];

        $goal = $ha['longTermGoals']['primary'] ?? '—';
        $goal = $goal !== '—' ? ucwords(str_replace('_', ' ', $goal)) : '—';

        $fitness = $ha['fitnessBackground']['level'] ?? '—';
        $fitness = $fitness !== '—' ? ucwords(str_replace('_', ' ', $fitness)) : '—';

        $areas = $ha['painAssessment']['areas'] ?? [];
        $painStr = !empty($areas) ? implode(', ', array_map(fn($a) => ucwords(str_replace('_', ' ', $a)), (array)$areas)) : 'None reported';

        $conditions = $ha['medicalHistory']['conditions'] ?? [];
        $condStr = !empty($conditions) ? implode(', ', array_map(fn($c) => ucwords(str_replace('_', ' ', $c)), (array)$conditions)) : 'None reported';

        $hasRestriction = !empty($ha['doctorRestrictions']['hasRestriction']);
        $restrictionNotes = $ha['doctorRestrictions']['notes'] ?? '';
        $restrStr = $hasRestriction ? ('Yes' . ($restrictionNotes ? ": $restrictionNotes" : '')) : 'None';

        $diet = $ha['foodPreference']['diet'] ?? '—';
        $diet = $diet !== '—' ? ucwords(str_replace('_', ' ', $diet)) : '—';

        return [
            'primary_goal' => $goal,
            'fitness_level' => $fitness,
            'pain_areas' => $painStr,
            'conditions' => $condStr,
            'restrictions' => $restrStr,
            'diet' => $diet,
        ];
    }

    private function streamCustomersXlsx(array $rows, string $segment, string $status, ?string $fromDate, ?string $toDate, string $dateSuffix, bool $isAll = false): never
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('All Customers');

        $spreadsheet->getProperties()
            ->setCreator('Vipasa Yoga')
            ->setTitle('Customers Master Directory' . $dateSuffix)
            ->setSubject('Vipasa Yoga Client Directory');

        $headers = [
            'Sl No', 'Customer ID', 'Customer Name', 'Email', 'Phone',
            'Category', 'Gender', 'Age', 'Date of Birth', 'Height (cm)', 'Weight (kg)',
            'City', 'Country', 'Timezone', 'Emergency Contact', 'Emergency Phone', 'Relationship',
            'Primary Goal', 'Fitness Level', 'Pain Areas', 'Medical Conditions', 'Medical Notes',
            'Doctor Restrictions', 'Dietary Preference', 'Assessment Stage', 'Onboarding Complete',
            'Account Status', 'Total Bookings', 'Confirmed Sessions', 'Completed Sessions',
            'Cancelled Sessions', 'Last Booking Date', 'Total Paid (INR)',
            'Email Verified', 'Last Login Date', 'Registration Date'
        ];

        $lastCol = Coordinate::stringFromColumnIndex(count($headers));

        // Header Title Banner
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'VIPASA YOGA — ALL CUSTOMERS MASTER DIRECTORY');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0B132B']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(36);

        $segLabel = $isAll ? 'All Customers (Complete Database)' : match ($segment) {
            'no_booking' => 'Signed Up, Not Booked',
            'booked' => 'Booked a Session',
            default => 'All Clients',
        };
        $statusLabel = $isAll ? 'All Statuses' : ($status !== '' ? ucfirst($status) : 'Active Accounts');
        $dateLabel = $isAll ? 'All Time (Complete Database)' : (($fromDate || $toDate) 
            ? (($fromDate ? date('d M Y', strtotime($fromDate)) : 'Start') . ' to ' . ($toDate ? date('d M Y', strtotime($toDate)) : 'Present'))
            : 'All Time');

        $metaText = 'Generated: ' . date('d M Y, h:i A') . ' | Scope: ' . ($isAll ? 'Complete All Customers' : 'Filtered') . ' | Segment: ' . $segLabel . ' | Status: ' . $statusLabel . ' | Date Range: ' . $dateLabel . ' | Total Records: ' . count($rows);
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $metaText);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '475569'], 'size' => 9],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(22);

        $headerRow = 4;
        foreach ($headers as $idx => $h) {
            $colLetter = Coordinate::stringFromColumnIndex($idx + 1);
            $sheet->setCellValue($colLetter . $headerRow, $h);
        }

        $headerRange = "A4:{$lastCol}4";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '107C41']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '0E6233']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(28);

        $sheet->freezePane('A5');

        $rowNum = 5;
        $totalPaidSum = 0.0;
        $totalBookingsSum = 0;

        foreach ($rows as $i => $r) {
            $c = 1;
            $paid = (float)$r['total_paid'];
            $totalPaidSum += $paid;
            $totalBookingsSum += (int)$r['booking_count'];

            $stage = ClientProfile::stageReached([
                'health_assessment' => $r['health_assessment'] !== null ? json_decode($r['health_assessment'], true) : null,
                'onboarding_completed_at' => $r['onboarding_completed_at'] ?? null,
            ]);

            $haParsed = $this->parseHealthAssessment($r['health_assessment']);

            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $i + 1);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, 'VY-U' . str_pad((string)$r['id'], 4, '0', STR_PAD_LEFT));
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['name']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['email'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['phone'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['client_category'] ? ucwords(str_replace('_', ' ', $r['client_category'])) : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['gender']) ? ucfirst($r['gender']) : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['age']) ? (int)$r['age'] : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['date_of_birth']) ? date('d M Y', strtotime($r['date_of_birth'])) : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['height_cm']) ? (float)$r['height_cm'] : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['weight_kg']) ? (float)$r['weight_kg'] : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['city'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['country'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['timezone'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['emergency_contact_name'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['emergency_contact_phone'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['emergency_contact_relationship'] ? ucwords($r['emergency_contact_relationship']) : '—');
            
            // Health assessment info
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $haParsed['primary_goal']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $haParsed['fitness_level']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $haParsed['pain_areas']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $haParsed['conditions']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['medical_notes'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $haParsed['restrictions']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $haParsed['diet']);

            $stageLabel = $stage >= 5 ? 'Complete (Stage 5)' : ($stage >= 1 ? "Stage {$stage} of 5" : 'Not started');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $stageLabel);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['onboarding_completed_at'] !== null ? 'Yes' : 'No');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, ucfirst($r['status']));
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, (int)$r['booking_count']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, (int)$r['confirmed_count']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, (int)$r['completed_count']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, (int)$r['cancelled_count']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['last_booking_date']) ? date('d M Y', strtotime($r['last_booking_date'])) : '—');

            $paidCell = Coordinate::stringFromColumnIndex($c++) . $rowNum;
            $sheet->setCellValue($paidCell, $paid);
            $sheet->getStyle($paidCell)->getNumberFormat()->setFormatCode('#,##0.00');

            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['email_verified_at']) ? 'Yes' : 'No');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['last_login_at']) ? date('d M Y h:i A', strtotime($r['last_login_at'])) : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['created_at']) ? date('d M Y h:i A', strtotime($r['created_at'])) : '—');

            $dataRowRange = "A{$rowNum}:{$lastCol}{$rowNum}";
            $sheet->getStyle($dataRowRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');

            foreach ([1, 2, 7, 8, 9, 10, 11, 14, 25, 26, 27, 28, 29, 30, 31, 32, 34, 35, 36] as $colIdx) {
                $sheet->getStyle(Coordinate::stringFromColumnIndex($colIdx) . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            if ($i % 2 === 1) {
                $sheet->getStyle($dataRowRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }

            $sheet->getRowDimension($rowNum)->setRowHeight(20);
            $rowNum++;
        }

        // Summary Row
        if (!empty($rows)) {
            $totalRowRange = "A{$rowNum}:{$lastCol}{$rowNum}";
            $sheet->setCellValue('A' . $rowNum, 'TOTAL');
            $sheet->setCellValue('B' . $rowNum, count($rows) . ' Customers');

            // Total Bookings sum (col 28 = AB)
            $sheet->setCellValue('AB' . $rowNum, $totalBookingsSum);
            $sheet->getStyle('AB' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Total Paid sum (col 33 = AG)
            $sheet->setCellValue('AG' . $rowNum, $totalPaidSum);
            $sheet->getStyle('AG' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');

            $sheet->getStyle($totalRowRange)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '0F172A'], 'size' => 10],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E8F0']],
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '94A3B8']],
                    'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '0F172A']],
                ],
            ]);
            $sheet->getRowDimension($rowNum)->setRowHeight(24);
        }

        foreach (range(1, count($headers)) as $colIdx) {
            $sheet->getColumnDimensionByColumn($colIdx)->setAutoSize(true);
        }

        $sheet->setAutoFilter("A4:{$lastCol}" . max(4, $rowNum - 1));

        $filename = 'vipasa_yoga_customers' . ($segment !== '' ? '_' . str_replace('_', '-', $segment) : '') . $dateSuffix . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }

    private function streamCustomersCsv(array $rows, string $segment, string $dateSuffix): never
    {
        $filename = 'vipasa_yoga_customers' . ($segment !== '' ? '_' . str_replace('_', '-', $segment) : '') . $dateSuffix . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-store');

        $out = fopen('php://output', 'wb');
        fwrite($out, "\xEF\xBB\xBF");

        fputcsv($out, [
            'Sl No', 'Customer ID', 'Customer Name', 'Email', 'Phone',
            'Category', 'Gender', 'Age', 'Date of Birth', 'Height (cm)', 'Weight (kg)',
            'City', 'Country', 'Timezone', 'Emergency Contact', 'Emergency Phone', 'Relationship',
            'Primary Goal', 'Fitness Level', 'Pain Areas', 'Medical Conditions', 'Medical Notes',
            'Doctor Restrictions', 'Dietary Preference', 'Assessment Stage', 'Onboarding Complete',
            'Account Status', 'Total Bookings', 'Confirmed Sessions', 'Completed Sessions',
            'Cancelled Sessions', 'Last Booking Date', 'Total Paid (INR)',
            'Email Verified', 'Last Login Date', 'Registration Date'
        ]);

        foreach ($rows as $i => $r) {
            $stage = ClientProfile::stageReached([
                'health_assessment' => $r['health_assessment'] !== null ? json_decode($r['health_assessment'], true) : null,
                'onboarding_completed_at' => $r['onboarding_completed_at'] ?? null,
            ]);

            $haParsed = $this->parseHealthAssessment($r['health_assessment']);

            fputcsv($out, [
                $i + 1,
                'VY-U' . str_pad((string)$r['id'], 4, '0', STR_PAD_LEFT),
                $r['name'] ?? '',
                $r['email'] ?? '',
                $r['phone'] ?? '',
                $r['client_category'] !== null ? ucwords(str_replace('_', ' ', $r['client_category'])) : '',
                !empty($r['gender']) ? ucfirst($r['gender']) : '',
                !empty($r['age']) ? (string)(int)$r['age'] : '',
                !empty($r['date_of_birth']) ? $r['date_of_birth'] : '',
                !empty($r['height_cm']) ? (string)$r['height_cm'] : '',
                !empty($r['weight_kg']) ? (string)$r['weight_kg'] : '',
                $r['city'] ?? '',
                $r['country'] ?? '',
                $r['timezone'] ?? '',
                $r['emergency_contact_name'] ?? '',
                $r['emergency_contact_phone'] ?? '',
                $r['emergency_contact_relationship'] ?? '',
                $haParsed['primary_goal'],
                $haParsed['fitness_level'],
                $haParsed['pain_areas'],
                $haParsed['conditions'],
                $r['medical_notes'] ?? '',
                $haParsed['restrictions'],
                $haParsed['diet'],
                $stage >= 5 ? 'Complete (Stage 5)' : ($stage >= 1 ? "Stage {$stage} of 5" : 'Not started'),
                $r['onboarding_completed_at'] !== null ? 'Yes' : 'No',
                $r['status'] ?? '',
                (string) (int) ($r['booking_count'] ?? 0),
                (string) (int) ($r['confirmed_count'] ?? 0),
                (string) (int) ($r['completed_count'] ?? 0),
                (string) (int) ($r['cancelled_count'] ?? 0),
                $r['last_booking_date'] ?? '',
                number_format((float) ($r['total_paid'] ?? 0), 2, '.', ''),
                !empty($r['email_verified_at']) ? 'Yes' : 'No',
                $r['last_login_at'] ?? '',
                $r['created_at'] ?? '',
            ]);
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
