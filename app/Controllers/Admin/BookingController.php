<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Mailer;
use App\Core\Request;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\Instructor;
use App\Models\MeetingLink;
use App\Models\Notification;
use App\Models\Package;
use App\Models\User;
use PDO;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class BookingController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request): void
    {
        $status = $request->query('status') ?: null;
        [$fromDate, $toDate] = $this->normalizeDates($request->query('from_date'), $request->query('to_date'));
        $dateType = $request->query('date_type') === 'created_at' ? 'created_at' : 'slot_date';
        $page = max(1, (int) $request->query('page', 1));
        $offset = ($page - 1) * self::PER_PAGE;

        $db = Database::connection();

        $where = [];
        $params = [];

        if ($status) {
            $where[] = 'b.status = :status';
            $params['status'] = $status;
        }

        $dateCol = ($dateType === 'created_at') ? 'DATE(b.created_at)' : 'b.slot_date';

        if ($fromDate && $toDate) {
            $where[] = "$dateCol BETWEEN :from_date AND :to_date";
            $params['from_date'] = $fromDate;
            $params['to_date'] = $toDate;
        } elseif ($fromDate) {
            $where[] = "$dateCol >= :from_date";
            $params['from_date'] = $fromDate;
        } elseif ($toDate) {
            $where[] = "$dateCol <= :to_date";
            $params['to_date'] = $toDate;
        }

        $whereSql = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

        $countStmt = $db->prepare('SELECT COUNT(*) c FROM bookings b' . $whereSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetch()['c'];

        $sql = 'SELECT b.*, u.name AS client_name, u.email AS client_email,
                       iu.name AS instructor_name, p.name AS package_name
                FROM bookings b
                INNER JOIN users u ON u.id = b.client_id
                INNER JOIN instructors i ON i.id = b.instructor_id
                INNER JOIN users iu ON iu.id = i.user_id
                INNER JOIN packages p ON p.id = b.package_id'
                . $whereSql
                . sprintf(' ORDER BY b.slot_date DESC, b.start_time DESC LIMIT %d OFFSET %d', self::PER_PAGE, $offset);

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $bookings = $stmt->fetchAll();

        foreach ($bookings as &$booking) {
            $booking['meeting_link'] = MeetingLink::forBooking((int) $booking['id']);
        }
        unset($booking);

        $this->view('admin/bookings', [
            'bookings' => $bookings,
            'selectedStatus' => $status,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'dateType' => $dateType,
            'total' => $total,
            'page' => $page,
            'totalPages' => max(1, (int) ceil($total / self::PER_PAGE)),
        ], 'dashboard');
    }

    public function exportExcel(Request $request): void
    {
        $all = $request->query('all') === '1' || $request->query('all') === 'true';
        $status = $all ? null : ($request->query('status') ?: null);
        [$fromDate, $toDate] = $all ? [null, null] : $this->normalizeDates($request->query('from_date'), $request->query('to_date'));
        $dateType = $request->query('date_type') === 'created_at' ? 'created_at' : 'slot_date';
        $format = strtolower((string)$request->query('format', 'xlsx'));

        $db = Database::connection();

        $where = [];
        $params = [];

        if ($status) {
            $where[] = 'b.status = :status';
            $params['status'] = $status;
        }

        $dateCol = ($dateType === 'created_at') ? 'DATE(b.created_at)' : 'b.slot_date';

        if ($fromDate && $toDate) {
            $where[] = "$dateCol BETWEEN :from_date AND :to_date";
            $params['from_date'] = $fromDate;
            $params['to_date'] = $toDate;
        } elseif ($fromDate) {
            $where[] = "$dateCol >= :from_date";
            $params['from_date'] = $fromDate;
        } elseif ($toDate) {
            $where[] = "$dateCol <= :to_date";
            $params['to_date'] = $toDate;
        }

        $whereSql = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

        $sql = 'SELECT b.*, 
                       u.name AS client_name, u.email AS client_email, u.phone AS client_phone,
                       u.status AS client_account_status, u.created_at AS client_registered_at,
                       cp.client_category, cp.gender AS client_gender, cp.age AS client_age,
                       cp.date_of_birth AS client_dob, cp.city AS client_city, cp.country AS client_country,
                       cp.timezone AS client_profile_timezone, cp.emergency_contact_name,
                       cp.emergency_contact_phone, cp.emergency_contact_relationship, cp.medical_notes,
                       iu.name AS instructor_name, iu.email AS instructor_email, iu.phone AS instructor_phone,
                       p.name AS package_name, p.sessions_count, p.duration_minutes,
                       m.url AS meeting_link,
                       pay.gateway AS payment_gateway, pay.method AS payment_method,
                       pay.gateway_txn_id AS payment_txn_id, pay.gateway_order_id AS payment_order_id,
                       pay.amount AS payment_amount, pay.status AS payment_status,
                       pay.created_at AS payment_date
                FROM bookings b
                INNER JOIN users u ON u.id = b.client_id
                LEFT JOIN client_profiles cp ON cp.user_id = u.id
                LEFT JOIN instructors i ON i.id = b.instructor_id
                LEFT JOIN users iu ON iu.id = i.user_id
                LEFT JOIN packages p ON p.id = b.package_id
                LEFT JOIN meeting_links m ON m.id = (SELECT MAX(m2.id) FROM meeting_links m2 WHERE m2.booking_id = b.id)
                LEFT JOIN payments pay ON pay.id = (SELECT MAX(p2.id) FROM payments p2 WHERE p2.booking_id = b.id)'
                . $whereSql . ' ORDER BY b.slot_date DESC, b.start_time DESC';

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            $this->streamBookingsCsv($rows, $dateSuffix);
        }

        $this->streamBookingsXlsx($rows, $status, $fromDate, $toDate, $dateSuffix, $all);
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

    private function streamBookingsXlsx(array $rows, ?string $status, ?string $fromDate, ?string $toDate, string $dateSuffix, bool $isAll = false): never
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('All Bookings');

        $spreadsheet->getProperties()
            ->setCreator('Vipasa Yoga')
            ->setTitle('Bookings Report' . $dateSuffix)
            ->setSubject('Vipasa Yoga Client Bookings Master Report');

        $headers = [
            'Sl No', 'Booking Ref', 'Session Date', 'Session Time', 'Client Timezone',
            'Booking Status', 'Client Name', 'Client Email', 'Client Phone', 'Client Category',
            'Client Gender', 'Client Age', 'Client City', 'Client Country', 'Emergency Contact',
            'Emergency Phone', 'Medical Notes', 'Package Name', 'Sessions Count', 'Duration (Mins)',
            'Instructor Name', 'Instructor Email', 'Instructor Phone', 'Base Price (INR)',
            'Discount (INR)', 'Tax / GST (INR)', 'Total Amount (INR)', 'Currency',
            'Payment Status', 'Payment Gateway', 'Payment Method', 'Transaction ID / UTR',
            'Gateway Order ID', 'Payment Date', 'Meeting Link', 'Client Notes',
            'Booking Created Date', 'Last Updated Date'
        ];

        $lastCol = Coordinate::stringFromColumnIndex(count($headers));

        // Header Title Banner
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', 'VIPASA YOGA — ALL BOOKINGS MASTER REPORT');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 14],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '107C41']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(36);

        $statusLabel = $status ? ucwords(str_replace('_', ' ', $status)) : 'All Statuses';
        $dateLabel = $isAll ? 'All Time (Complete Database)' : (($fromDate || $toDate) 
            ? (($fromDate ? date('d M Y', strtotime($fromDate)) : 'Start') . ' to ' . ($toDate ? date('d M Y', strtotime($toDate)) : 'Present'))
            : 'All Dates');

        $metaText = 'Generated: ' . date('d M Y, h:i A') . ' | Scope: ' . ($isAll ? 'Complete All Bookings' : 'Filtered') . ' | Status: ' . $statusLabel . ' | Date Range: ' . $dateLabel . ' | Total Records: ' . count($rows);
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
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0B132B']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '334155']]]
        ]);
        $sheet->getRowDimension(4)->setRowHeight(28);

        $sheet->freezePane('A5');

        $rowNum = 5;
        $totalBase = 0.0;
        $totalDiscount = 0.0;
        $totalTax = 0.0;
        $totalAmount = 0.0;

        foreach ($rows as $i => $r) {
            $c = 1;
            $basePrice = (float)($r['price'] ?? 0);
            $disc = (float)($r['discount_amount'] ?? 0);
            $tax = (float)($r['tax_amount'] ?? 0);
            $amt = (float)($r['total_amount'] ?? 0);

            $totalBase += $basePrice;
            $totalDiscount += $disc;
            $totalTax += $tax;
            $totalAmount += $amt;

            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $i + 1);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['booking_ref']);
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['slot_date']) ? date('d M Y', strtotime($r['slot_date'])) : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, (!empty($r['start_time']) && !empty($r['end_time'])) ? (date('h:i A', strtotime($r['start_time'])) . ' - ' . date('h:i A', strtotime($r['end_time']))) : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['client_timezone'] ?: 'Asia/Kolkata');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, ucwords(str_replace('_', ' ', $r['status'] ?? '—')));
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['client_name'] ?? '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['client_email'] ?? '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['client_phone'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['client_category'] ? ucwords(str_replace('_', ' ', $r['client_category'])) : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['client_gender']) ? ucfirst($r['client_gender']) : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['client_age']) ? (int)$r['client_age'] : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['client_city'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['client_country'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['emergency_contact_name'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['emergency_contact_phone'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['medical_notes'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['package_name'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, (int)($r['sessions_count'] ?? 1));
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, (int)($r['duration_minutes'] ?? 60));
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['instructor_name'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['instructor_email'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['instructor_phone'] ?: '—');

            // Financials
            $baseCell = Coordinate::stringFromColumnIndex($c++) . $rowNum;
            $sheet->setCellValue($baseCell, $basePrice);
            $sheet->getStyle($baseCell)->getNumberFormat()->setFormatCode('#,##0.00');

            $discCell = Coordinate::stringFromColumnIndex($c++) . $rowNum;
            $sheet->setCellValue($discCell, $disc);
            $sheet->getStyle($discCell)->getNumberFormat()->setFormatCode('#,##0.00');

            $taxCell = Coordinate::stringFromColumnIndex($c++) . $rowNum;
            $sheet->setCellValue($taxCell, $tax);
            $sheet->getStyle($taxCell)->getNumberFormat()->setFormatCode('#,##0.00');

            $amtCell = Coordinate::stringFromColumnIndex($c++) . $rowNum;
            $sheet->setCellValue($amtCell, $amt);
            $sheet->getStyle($amtCell)->getNumberFormat()->setFormatCode('#,##0.00');

            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['currency'] ?: 'INR');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, ucwords($r['payment_status'] ?: 'Pending'));
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, strtoupper($r['payment_gateway'] ?: 'UPI'));
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, ucwords($r['payment_method'] ?: 'UPI / QR'));
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['payment_txn_id'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['payment_order_id'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['payment_date']) ? date('d M Y h:i A', strtotime($r['payment_date'])) : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['meeting_link'] ?: 'Pending');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, $r['notes'] ?: '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['created_at']) ? date('d M Y h:i A', strtotime($r['created_at'])) : '—');
            $sheet->setCellValue(Coordinate::stringFromColumnIndex($c++) . $rowNum, !empty($r['updated_at']) ? date('d M Y h:i A', strtotime($r['updated_at'])) : '—');

            $dataRowRange = "A{$rowNum}:{$lastCol}{$rowNum}";
            $sheet->getStyle($dataRowRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');
            
            foreach ([1, 2, 3, 4, 5, 6, 11, 12, 19, 20, 28, 29, 30, 31, 34, 37, 38] as $centerCol) {
                $sheet->getStyle(Coordinate::stringFromColumnIndex($centerCol) . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            if ($i % 2 === 1) {
                $sheet->getStyle($dataRowRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }

            $sheet->getRowDimension($rowNum)->setRowHeight(20);
            $rowNum++;
        }

        // Summary Total Row
        if (!empty($rows)) {
            $totalRowRange = "A{$rowNum}:{$lastCol}{$rowNum}";
            $sheet->setCellValue('A' . $rowNum, 'TOTAL');
            $sheet->setCellValue('B' . $rowNum, count($rows) . ' Bookings');

            // Base price total (col 24 = X)
            $sheet->setCellValue('X' . $rowNum, $totalBase);
            $sheet->getStyle('X' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');

            // Discount total (col 25 = Y)
            $sheet->setCellValue('Y' . $rowNum, $totalDiscount);
            $sheet->getStyle('Y' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');

            // Tax total (col 26 = Z)
            $sheet->setCellValue('Z' . $rowNum, $totalTax);
            $sheet->getStyle('Z' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');

            // Total amount (col 27 = AA)
            $sheet->setCellValue('AA' . $rowNum, $totalAmount);
            $sheet->getStyle('AA' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');

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

        $filename = 'vipasa_yoga_bookings' . $dateSuffix . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        (new Xlsx($spreadsheet))->save('php://output');
        exit;
    }

    private function streamBookingsCsv(array $rows, string $dateSuffix): never
    {
        $filename = 'vipasa_yoga_bookings' . $dateSuffix . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $out = fopen('php://output', 'wb');
        fwrite($out, "\xEF\xBB\xBF");

        fputcsv($out, [
            'Sl No', 'Booking Ref', 'Session Date', 'Session Time', 'Client Timezone',
            'Booking Status', 'Client Name', 'Client Email', 'Client Phone', 'Client Category',
            'Client Gender', 'Client Age', 'Client City', 'Client Country', 'Emergency Contact',
            'Emergency Phone', 'Medical Notes', 'Package Name', 'Sessions Count', 'Duration (Mins)',
            'Instructor Name', 'Instructor Email', 'Instructor Phone', 'Base Price (INR)',
            'Discount (INR)', 'Tax / GST (INR)', 'Total Amount (INR)', 'Currency',
            'Payment Status', 'Payment Gateway', 'Payment Method', 'Transaction ID / UTR',
            'Gateway Order ID', 'Payment Date', 'Meeting Link', 'Client Notes',
            'Booking Created Date', 'Last Updated Date'
        ]);

        foreach ($rows as $i => $r) {
            fputcsv($out, [
                $i + 1,
                $r['booking_ref'],
                $r['slot_date'] ?? '',
                ($r['start_time'] ?? '') . ' - ' . ($r['end_time'] ?? ''),
                $r['client_timezone'] ?: 'Asia/Kolkata',
                ucwords(str_replace('_', ' ', $r['status'] ?? '')),
                $r['client_name'] ?? '',
                $r['client_email'] ?? '',
                $r['client_phone'] ?: '',
                $r['client_category'] ? ucwords(str_replace('_', ' ', $r['client_category'])) : '',
                !empty($r['client_gender']) ? ucfirst($r['client_gender']) : '',
                !empty($r['client_age']) ? (string)(int)$r['client_age'] : '',
                $r['client_city'] ?: '',
                $r['client_country'] ?: '',
                $r['emergency_contact_name'] ?: '',
                $r['emergency_contact_phone'] ?: '',
                $r['medical_notes'] ?: '',
                $r['package_name'] ?? '',
                (string)($r['sessions_count'] ?? 1),
                (string)($r['duration_minutes'] ?? 60),
                $r['instructor_name'] ?? '',
                $r['instructor_email'] ?: '',
                $r['instructor_phone'] ?: '',
                number_format((float)($r['price'] ?? 0), 2, '.', ''),
                number_format((float)($r['discount_amount'] ?? 0), 2, '.', ''),
                number_format((float)($r['tax_amount'] ?? 0), 2, '.', ''),
                number_format((float)($r['total_amount'] ?? 0), 2, '.', ''),
                $r['currency'] ?: 'INR',
                $r['payment_status'] ?: 'pending',
                $r['payment_gateway'] ?: 'upi',
                $r['payment_method'] ?: 'UPI / QR',
                $r['payment_txn_id'] ?: '',
                $r['payment_order_id'] ?: '',
                $r['payment_date'] ?? '',
                $r['meeting_link'] ?: '',
                $r['notes'] ?: '',
                $r['created_at'] ?? '',
                $r['updated_at'] ?? '',
            ]);
        }

        fclose($out);
        exit;
    }

    public function saveMeetingLink(Request $request, string $ref): void
    {
        $this->fail('Meeting links must be attached and sent directly by the assigned trainer from their trainer dashboard.', 403);
    }

    public function reschedule(Request $request, string $ref): void
    {
        $booking = Booking::findByRef($ref);

        if (!$booking) {
            $this->fail('Booking not found.', 404);
        }

        if (!in_array($booking['status'], ['awaiting_trainer_approval', 'confirmed'], true)) {
            $this->fail('Only awaiting-approval or confirmed bookings can be rescheduled.', 422);
        }

        $slotDate = (string) $request->input('slot_date', '');
        $startTime = (string) $request->input('start_time', '');

        if ($slotDate === '' || $startTime === '') {
            $this->fail('Please choose a new date and time.', 422);
        }

        $package = Package::find($booking['package_id']);
        BookingSlot::ensureGeneratedForDate((int) $booking['instructor_id'], $slotDate, (int) $package['duration_minutes']);

        $newSlot = null;
        foreach (BookingSlot::findAvailable((int) $booking['instructor_id'], $slotDate) as $candidate) {
            if (substr($candidate['start_time'], 0, 5) === substr($startTime, 0, 5)) {
                $newSlot = $candidate;
                break;
            }
        }

        if (!$newSlot) {
            $this->fail('That time slot is not available for this instructor.', 409);
        }

        if (!BookingSlot::lockSlot((int) $newSlot['id'], (int) $booking['id'])) {
            $this->fail('That time slot was just booked by someone else. Please choose another.', 409);
        }

        $oldSlotId = $booking['slot_id'];

        Booking::update((int) $booking['id'], [
            'slot_id' => $newSlot['id'],
            'slot_date' => $newSlot['slot_date'],
            'start_time' => $newSlot['start_time'],
            'end_time' => $newSlot['end_time'],
        ]);

        if ($oldSlotId) {
            BookingSlot::release((int) $oldSlotId);
        }

        ActivityLog::log(Auth::id(), 'booking_rescheduled', 'booking', (int) $booking['id'], [
            'booking_ref' => $booking['booking_ref'],
            'new_date' => $newSlot['slot_date'],
            'new_time' => $newSlot['start_time'],
        ]);

        $this->sendRescheduleEmail($booking, $newSlot);

        $this->success(null, 'Booking rescheduled.');
    }

    public function delete(Request $request, string $ref): void
    {
        $booking = Booking::findByRef($ref);

        if (!$booking) {
            $this->fail('Booking not found.', 404);
        }

        $db = Database::connection();
        $db->prepare('DELETE FROM bookings WHERE booking_ref = :ref')->execute(['ref' => $ref]);

        ActivityLog::log(Auth::id(), 'booking_deleted', 'booking', (int) $booking['id'], ['booking_ref' => $ref]);

        $this->success(null, 'Booking deleted successfully.');
    }

    public function approvePayment(Request $request, string $ref): void
    {
        $booking = Booking::findByRef($ref);
        if (!$booking) {
            $this->fail('Booking not found.', 404);
        }

        $payment = \App\Models\Payment::forBooking((int) $booking['id']);
        if ($payment) {
            \App\Models\Payment::markSuccessById((int) $payment['id']);
        } else {
            \App\Models\Payment::insert([
                'booking_id' => $booking['id'],
                'gateway' => 'upi',
                'amount' => $booking['total_amount'],
                'currency' => $booking['currency'],
                'status' => 'success',
            ]);
        }

        Booking::update($booking['id'], ['status' => 'confirmed']);

        ActivityLog::log(Auth::id(), 'payment_approved', 'booking', (int) $booking['id'], [
            'booking_ref' => $ref,
        ]);

        $instructor = Instructor::findWithName((int) $booking['instructor_id']);
        $package = Package::find((int) $booking['package_id']);
        $dateLabel = date('d M Y', strtotime($booking['slot_date']));
        $timeLabel = date('g:i A', strtotime($booking['start_time']));

        Notification::create(
            (int) $booking['client_id'],
            'booking',
            'Booking confirmed — ' . $booking['booking_ref'],
            'Your payment has been verified by admin. Your session for ' . ($package['name'] ?? 'Session') . ' with ' . ($instructor['name'] ?? 'Instructor') . ' on ' . $dateLabel . ' at ' . $timeLabel . ' is confirmed!'
        );

        if (!empty($instructor['user_id'])) {
            Notification::create(
                (int) $instructor['user_id'],
                'booking',
                'New booking confirmed — ' . $booking['booking_ref'],
                'A new session was booked for ' . $dateLabel . ' at ' . $timeLabel . '. Please attach the meeting link for your student.'
            );
        }

        Mailer::sendPaymentInvoiceEmail($booking, $payment['gateway_txn_id'] ?? ('#' . $booking['id']));

        $this->success(null, 'Payment approved and booking confirmed. Sent to trainer schedule.');
    }

    public function rejectPayment(Request $request, string $ref): void
    {
        $booking = Booking::findByRef($ref);
        if (!$booking) {
            $this->fail('Booking not found.', 404);
        }

        $payment = \App\Models\Payment::forBooking((int) $booking['id']);
        if ($payment) {
            \App\Models\Payment::markFailedById((int) $payment['id']);
        }

        Booking::update($booking['id'], ['status' => 'cancelled']);

        if (!empty($booking['slot_id'])) {
            BookingSlot::release((int) $booking['slot_id']);
        }

        ActivityLog::log(Auth::id(), 'payment_rejected', 'booking', (int) $booking['id'], [
            'booking_ref' => $ref,
        ]);

        Notification::create(
            (int) $booking['client_id'],
            'booking',
            'Payment verification failed — ' . $booking['booking_ref'],
            'Your payment verification for booking ' . $booking['booking_ref'] . ' was rejected by admin. Please contact support.'
        );

        $this->success(null, 'Payment rejected and booking cancelled.');
    }

    private function sendRescheduleEmail(array $booking, array $newSlot): void
    {
        $client = User::find((int) $booking['client_id']);

        if (!$client || !$client['email']) {
            return;
        }

        $instructor = Instructor::findWithName((int) $booking['instructor_id']);
        $clientName = htmlspecialchars($client['name'], ENT_QUOTES);
        $ref = htmlspecialchars($booking['booking_ref'], ENT_QUOTES);
        $instructorName = htmlspecialchars($instructor['name'] ?? 'your instructor', ENT_QUOTES);
        $dateLabel = htmlspecialchars(date('l, d M Y', strtotime($newSlot['slot_date'])), ENT_QUOTES);
        $timeLabel = htmlspecialchars(date('g:i A', strtotime($newSlot['start_time'])), ENT_QUOTES);

        Mailer::send($client['email'], 'Booking rescheduled — ' . $booking['booking_ref'], <<<HTML
            <p>Hi {$clientName},</p>
            <p>Your session with {$instructorName} (Reference: {$ref}) has been rescheduled to:</p>
            <p><strong>{$dateLabel} at {$timeLabel}</strong></p>
            HTML);
    }
}
