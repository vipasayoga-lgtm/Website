<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use PDO;

final class CalendarController extends Controller
{
    private const STATUSES = [
        'pending_payment', 'awaiting_trainer_approval', 'confirmed',
        'completed', 'cancelled', 'rescheduled',
    ];

    public function index(Request $request): void
    {
        $db = Database::connection();

        $year = (int) $request->query('year', date('Y'));
        if ($year < 1970 || $year > 2100) {
            $year = (int) date('Y');
        }

        /* month = '' means "whole year": the admin picks a year and sees every
           booking in it rather than having to step through twelve months. */
        $monthParam = trim((string) $request->query('month', date('n')));
        $wholeYear = $monthParam === '' || $monthParam === 'all';
        $month = $wholeYear ? null : (int) $monthParam;
        if (!$wholeYear && ($month < 1 || $month > 12)) {
            $month = (int) date('n');
        }

        $instructorId = (int) $request->query('instructor_id', 0);
        $status = (string) $request->query('status', '');
        if (!in_array($status, self::STATUSES, true)) {
            $status = '';
        }

        if ($wholeYear) {
            $rangeStart = sprintf('%04d-01-01', $year);
            $rangeEnd = sprintf('%04d-12-31', $year);
        } else {
            $daysInMonth = (int) date('t', mktime(0, 0, 0, $month, 1, $year));
            $rangeStart = sprintf('%04d-%02d-01', $year, $month);
            $rangeEnd = sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth);
        }

        $where = 'b.slot_date BETWEEN :range_start AND :range_end';
        $params = ['range_start' => $rangeStart, 'range_end' => $rangeEnd];

        if ($instructorId > 0) {
            $where .= ' AND b.instructor_id = :instructor_id';
            $params['instructor_id'] = $instructorId;
        }
        if ($status !== '') {
            $where .= ' AND b.status = :status';
            $params['status'] = $status;
        }

        $stmt = $db->prepare(
            'SELECT b.*, u.name AS client_name, u.email AS client_email,
                    iu.name AS instructor_name, p.name AS package_name
             FROM bookings b
             INNER JOIN users u ON u.id = b.client_id
             INNER JOIN instructors i ON i.id = b.instructor_id
             INNER JOIN users iu ON iu.id = i.user_id
             INNER JOIN packages p ON p.id = b.package_id
             WHERE ' . $where . '
             ORDER BY b.slot_date ASC, b.start_time ASC'
        );
        $stmt->execute($params);
        $bookings = $stmt->fetchAll();

        $byDate = [];
        $byMonth = array_fill(1, 12, ['count' => 0, 'revenue' => 0.0]);
        $statusTotals = [];
        $revenue = 0.0;

        foreach ($bookings as $booking) {
            $byDate[$booking['slot_date']][] = $booking;

            $m = (int) date('n', strtotime($booking['slot_date']));
            $byMonth[$m]['count']++;

            // Cancelled bookings stay visible on the calendar but must not be
            // counted as money taken.
            if ($booking['status'] !== 'cancelled') {
                $amount = (float) $booking['total_amount'];
                $byMonth[$m]['revenue'] += $amount;
                $revenue += $amount;
            }

            $statusTotals[$booking['status']] = ($statusTotals[$booking['status']] ?? 0) + 1;
        }

        $prevMonth = $nextMonth = null;
        $prevYear = $year - 1;
        $nextYear = $year + 1;
        if (!$wholeYear) {
            $prevMonth = $month - 1;
            $prevYear = $year;
            if ($prevMonth < 1) {
                $prevMonth = 12;
                $prevYear = $year - 1;
            }
            $nextMonth = $month + 1;
            $nextYear = $year;
            if ($nextMonth > 12) {
                $nextMonth = 1;
                $nextYear = $year + 1;
            }
        }

        $this->view('admin/calendar', [
            'byDate' => $byDate,
            'byMonth' => $byMonth,
            'bookings' => $bookings,
            'statusTotals' => $statusTotals,
            'revenue' => $revenue,
            'month' => $month,
            'year' => $year,
            'wholeYear' => $wholeYear,
            'prevMonth' => $prevMonth,
            'prevYear' => $prevYear,
            'nextMonth' => $nextMonth,
            'nextYear' => $nextYear,
            'instructorId' => $instructorId,
            'status' => $status,
            'statuses' => self::STATUSES,
            'instructors' => $this->instructorOptions($db),
            'years' => $this->yearOptions($db, $year),
        ], 'dashboard');
    }

    /** @return array<int, array{id: int, name: string}> */
    private function instructorOptions(PDO $db): array
    {
        return $db->query(
            'SELECT i.id, u.name
             FROM instructors i
             INNER JOIN users u ON u.id = i.user_id
             ORDER BY u.name ASC'
        )->fetchAll();
    }

    /**
     * Years worth offering in the picker: every year that actually has a
     * booking, plus a window around today so an admin can always jump to a
     * quiet year (e.g. 2025) even when nothing is booked in it, and always
     * including whichever year is currently selected.
     *
     * @return array<int, int>
     */
    private function yearOptions(PDO $db, int $selectedYear): array
    {
        $rows = $db->query('SELECT DISTINCT YEAR(slot_date) y FROM bookings WHERE slot_date IS NOT NULL')->fetchAll();
        $years = array_map(static fn (array $r) => (int) $r['y'], $rows);

        $currentYear = (int) date('Y');
        for ($y = $currentYear - 3; $y <= $currentYear + 1; $y++) {
            $years[] = $y;
        }
        $years[] = $selectedYear;

        $years = array_values(array_unique(array_filter($years, static fn (int $y) => $y >= 1970 && $y <= 2100)));
        rsort($years);

        return $years;
    }
}
