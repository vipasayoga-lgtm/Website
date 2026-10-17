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
use App\Models\Package;
use App\Models\User;

final class BookingController extends Controller
{
    private const PER_PAGE = 10;

    public function index(Request $request): void
    {
        $status = $request->query('status') ?: null;
        $page = max(1, (int) $request->query('page', 1));
        $offset = ($page - 1) * self::PER_PAGE;

        $db = Database::connection();

        $countStmt = $db->prepare('SELECT COUNT(*) c FROM bookings b' . ($status ? ' WHERE b.status = :status' : ''));
        $countStmt->execute($status ? ['status' => $status] : []);
        $total = (int) $countStmt->fetch()['c'];

        $sql = 'SELECT b.*, u.name AS client_name, u.email AS client_email,
                       iu.name AS instructor_name, p.name AS package_name
                FROM bookings b
                INNER JOIN users u ON u.id = b.client_id
                INNER JOIN instructors i ON i.id = b.instructor_id
                INNER JOIN users iu ON iu.id = i.user_id
                INNER JOIN packages p ON p.id = b.package_id';

        $params = [];
        if ($status) {
            $sql .= ' WHERE b.status = :status';
            $params['status'] = $status;
        }
        $sql .= sprintf(' ORDER BY b.created_at DESC LIMIT %d OFFSET %d', self::PER_PAGE, $offset);

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
            'page' => $page,
            'totalPages' => max(1, (int) ceil($total / self::PER_PAGE)),
        ], 'dashboard');
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
