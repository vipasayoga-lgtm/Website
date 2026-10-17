<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

final class PaymentController extends Controller
{
    private const PER_PAGE = 20;

    public function index(Request $request): void
    {
        $status = $request->query('status') ?: null;
        $gateway = $request->query('gateway') ?: null;
        $page = max(1, (int) $request->query('page', 1));
        $offset = ($page - 1) * self::PER_PAGE;

        $db = Database::connection();

        $conditions = [];
        $params = [];
        if ($status) {
            $conditions[] = 'pay.status = :status';
            $params['status'] = $status;
        }
        if ($gateway) {
            $conditions[] = 'pay.gateway = :gateway';
            $params['gateway'] = $gateway;
        }
        $whereSql = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';

        $countStmt = $db->prepare('SELECT COUNT(*) c FROM payments pay' . $whereSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetch()['c'];

        $sql = 'SELECT pay.*, b.booking_ref, b.status AS booking_status, u.name AS client_name, u.email AS client_email, u.phone AS client_phone
                FROM payments pay
                INNER JOIN bookings b ON b.id = pay.booking_id
                INNER JOIN users u ON u.id = b.client_id'
                . $whereSql;
        $sql .= sprintf(' ORDER BY pay.created_at DESC LIMIT %d OFFSET %d', self::PER_PAGE, $offset);

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $payments = $stmt->fetchAll();

        $this->view('admin/payments', [
            'payments' => $payments,
            'selectedStatus' => $status,
            'selectedGateway' => $gateway,
            'page' => $page,
            'totalPages' => max(1, (int) ceil($total / self::PER_PAGE)),
        ], 'dashboard');
    }

    public function approve(Request $request, string $id): void
    {
        $db = Database::connection();
        $stmt = $db->prepare('SELECT pay.*, b.id AS booking_id, b.booking_ref, b.instructor_id, b.package_id, b.client_id, b.slot_date, b.start_time FROM payments pay INNER JOIN bookings b ON b.id = pay.booking_id WHERE pay.id = :id');
        $stmt->execute(['id' => (int) $id]);
        $payment = $stmt->fetch();

        if (!$payment) {
            $this->fail('Payment record not found.', 404);
        }

        \App\Models\Payment::markSuccessById((int) $id);
        \App\Models\Booking::update($payment['booking_id'], ['status' => 'confirmed']);

        \App\Models\ActivityLog::log(\App\Core\Auth::id(), 'payment_approved', 'booking', (int) $payment['booking_id'], [
            'booking_ref' => $payment['booking_ref'],
            'payment_id' => $id,
        ]);

        $instructor = \App\Models\Instructor::findWithName((int) $payment['instructor_id']);
        $package = \App\Models\Package::find((int) $payment['package_id']);
        $dateLabel = date('d M Y', strtotime($payment['slot_date']));
        $timeLabel = date('g:i A', strtotime($payment['start_time']));

        \App\Models\Notification::create(
            (int) $payment['client_id'],
            'booking',
            'Booking confirmed — ' . $payment['booking_ref'],
            'Your payment has been verified by admin. Your session for ' . ($package['name'] ?? 'Session') . ' with ' . ($instructor['name'] ?? 'Instructor') . ' on ' . $dateLabel . ' at ' . $timeLabel . ' is confirmed!'
        );

        if (!empty($instructor['user_id'])) {
            \App\Models\Notification::create(
                (int) $instructor['user_id'],
                'booking',
                'New booking confirmed — ' . $payment['booking_ref'],
                'A new session was booked for ' . $dateLabel . ' at ' . $timeLabel . '. Please attach the meeting link for your student.'
            );
        }

        $booking = \App\Models\Booking::find((int) $payment['booking_id']);
        if ($booking) {
            \App\Core\Mailer::sendPaymentInvoiceEmail($booking, $payment['gateway_txn_id'] ?? ('#' . $id));
        }

        $this->success(null, 'Payment approved and booking confirmed. Sent to trainer schedule.');
    }

    public function reject(Request $request, string $id): void
    {
        $db = Database::connection();
        $stmt = $db->prepare('SELECT pay.*, b.id AS booking_id, b.booking_ref, b.client_id, b.slot_id FROM payments pay INNER JOIN bookings b ON b.id = pay.booking_id WHERE pay.id = :id');
        $stmt->execute(['id' => (int) $id]);
        $payment = $stmt->fetch();

        if (!$payment) {
            $this->fail('Payment record not found.', 404);
        }

        \App\Models\Payment::markFailedById((int) $id);
        \App\Models\Booking::update($payment['booking_id'], ['status' => 'cancelled']);

        if (!empty($payment['slot_id'])) {
            \App\Models\BookingSlot::release((int) $payment['slot_id']);
        }

        \App\Models\ActivityLog::log(\App\Core\Auth::id(), 'payment_rejected', 'booking', (int) $payment['booking_id'], [
            'booking_ref' => $payment['booking_ref'],
            'payment_id' => $id,
        ]);

        \App\Models\Notification::create(
            (int) $payment['client_id'],
            'booking',
            'Payment verification failed — ' . $payment['booking_ref'],
            'Your payment verification for booking ' . $payment['booking_ref'] . ' was rejected by admin. Please contact support.'
        );

        $this->success(null, 'Payment rejected and booking cancelled.');
    }

    public function delete(Request $request, string $id): void
    {
        $db = Database::connection();
        $db->prepare('DELETE FROM payments WHERE id = :id')->execute(['id' => (int) $id]);
        $this->success(null, 'Payment record deleted.');
    }
}
