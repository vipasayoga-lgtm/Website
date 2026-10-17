<?php

namespace App\Controllers\Api;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Mailer;
use App\Core\Payments\PaymentGatewayFactory;
use App\Core\Request;
use App\Core\View;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Instructor;
use App\Models\Notification;
use App\Models\Package;
use App\Models\Payment;
use App\Models\User;

final class PaymentController extends Controller
{
    public function createOrder(Request $request): void
    {
        $booking = $this->ownedPendingBooking($request);
        $gateway = PaymentGatewayFactory::make();

        if (!$gateway->isConfigured()) {
            $this->fail('Online payments are not configured yet. Please contact support to complete your booking.', 503);
        }

        $amountInSmallestUnit = (int) round(((float) $booking['total_amount']) * 100);
        $checkout = $gateway->createCheckout(
            $amountInSmallestUnit,
            $booking['currency'],
            $booking['booking_ref'],
            'Booking ' . $booking['booking_ref']
        );

        if (!$checkout) {
            $this->fail('Could not start the payment. Please try again.', 502);
        }

        Payment::createPending((int) $booking['id'], $gateway->name(), $checkout['order_id'], (float) $booking['total_amount'], $booking['currency']);

        $this->success($checkout);
    }

    public function verify(Request $request): void
    {
        $ref = (string) $request->input('booking_ref', '');
        $booking = Booking::findByRef($ref);

        if (!$booking || (int) $booking['client_id'] !== Auth::id()) {
            $this->fail('Booking not found.', 404);
        }

        if (in_array($booking['status'], ['confirmed', 'completed'], true)) {
            $this->success(['redirect' => '/booking/' . $booking['booking_ref'] . '/success']);
        }

        $result = PaymentGatewayFactory::make()->confirmPayment($request);

        if (!$result['success']) {
            if ($result['order_id'] !== '') {
                Payment::markFailedByOrderId($result['order_id']);
            }
            ActivityLog::log(Auth::id(), 'payment_failed', 'booking', (int) $booking['id'], ['booking_ref' => $booking['booking_ref']]);
            $this->fail('Payment verification failed.', 422);
        }

        Payment::markSuccessByOrderId($result['order_id'], $result['transaction_id'] ?? $result['order_id']);
        Booking::update($booking['id'], ['status' => 'confirmed']);
        ActivityLog::log(Auth::id(), 'payment_success', 'booking', (int) $booking['id'], [
            'booking_ref' => $booking['booking_ref'],
            'amount' => $booking['total_amount'],
        ]);

        // Create in-app notifications
        $instructor = Instructor::findWithName((int) $booking['instructor_id']);
        $package = Package::find((int) $booking['package_id']);
        $dateLabel = date('d M Y', strtotime($booking['slot_date']));
        $timeLabel = date('g:i A', strtotime($booking['start_time']));

        Notification::create(
            (int) $booking['client_id'],
            'booking',
            'Booking confirmed — ' . $booking['booking_ref'],
            'Your session for ' . ($package['name'] ?? 'Session') . ' with ' . ($instructor['name'] ?? 'Instructor') . ' on ' . $dateLabel . ' at ' . $timeLabel . ' is confirmed!'
        );

        if (!empty($instructor['user_id'])) {
            Notification::create(
                (int) $instructor['user_id'],
                'booking',
                'New booking received — ' . $booking['booking_ref'],
                'A new session was booked for ' . $dateLabel . ' at ' . $timeLabel . '. Please attach the meeting link for your student.'
            );
        }

        Mailer::sendPaymentInvoiceEmail($booking, $result['transaction_id'] ?? $result['order_id']);

        $this->success(['redirect' => '/booking/' . $booking['booking_ref'] . '/success']);
    }

    public function submitProof(Request $request): void
    {
        $ref = (string) $request->input('booking_ref', '');
        $booking = Booking::findByRef($ref);

        if (!$booking || (int) $booking['client_id'] !== Auth::id()) {
            $this->fail('Booking not found.', 404);
        }

        if ($booking['status'] !== 'pending_payment') {
            $this->fail('This booking is not awaiting payment verification.', 422);
        }

        $utrNumber = trim((string) $request->input('utr_number', $request->input('transaction_id', '')));
        if ($utrNumber === '') {
            $this->fail('Please enter the UPI Transaction / UTR reference number.', 422);
        }

        $payerPhone = trim((string) $request->input('payer_phone', ''));
        $screenshotPath = null;

        if (!empty($_FILES['screenshot']) && $_FILES['screenshot']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['screenshot'];
            if ($file['size'] > 10 * 1024 * 1024) {
                $this->fail('Payment screenshot must be smaller than 10MB.', 422);
            }

            $ext = strtolower((string) pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                $this->fail('Screenshot must be a JPG, PNG, or WebP image.', 422);
            }

            $uploadDir = dirname(__DIR__, 3) . '/public/uploads/payment-proofs';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filename = sprintf('proof_%d_%s.%s', $booking['id'], bin2hex(random_bytes(6)), $ext);
            $destination = $uploadDir . '/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $screenshotPath = '/uploads/payment-proofs/' . $filename;
            }
        }

        $extra = [
            'utr' => $utrNumber,
            'payer_phone' => $payerPhone,
            'submitted_at' => date('Y-m-d H:i:s'),
        ];

        Payment::createOrUpdateManualUpi(
            (int) $booking['id'],
            $utrNumber,
            (float) $booking['total_amount'],
            $booking['currency'],
            $screenshotPath,
            $extra
        );

        ActivityLog::log(Auth::id(), 'payment_proof_submitted', 'booking', (int) $booking['id'], [
            'booking_ref' => $booking['booking_ref'],
            'utr' => $utrNumber,
        ]);

        $client = User::find(Auth::id());
        $clientName = $client['name'] ?? 'Client';

        foreach (User::where(['role' => 'admin']) as $admin) {
            Notification::create(
                (int) $admin['id'],
                'payment',
                'Payment submitted — ' . $booking['booking_ref'],
                'Client ' . $clientName . ' submitted UPI payment proof (UTR: ' . $utrNumber . ') for booking ' . $booking['booking_ref'] . '. Please verify in Admin > Payments.'
            );
        }

        $this->success([
            'redirect' => '/booking/confirm/' . $booking['booking_ref'],
        ], 'Payment details submitted successfully! Awaiting admin verification.');
    }

    public function approve(Request $request, int $id): void
    {
        if (!Auth::hasRole('admin')) {
            $this->fail('Only administrators can approve payments.', 403);
        }

        $payment = Payment::find($id);
        if (!$payment) {
            $this->fail('Payment record not found.', 404);
        }

        $booking = Booking::find($payment['booking_id']);
        if (!$booking) {
            $this->fail('Associated booking not found.', 404);
        }

        Payment::markSuccessById($id);
        Booking::update($booking['id'], ['status' => 'confirmed']);

        ActivityLog::log(Auth::id(), 'payment_approved', 'booking', (int) $booking['id'], [
            'booking_ref' => $booking['booking_ref'],
            'payment_id' => $id,
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

        Mailer::sendPaymentInvoiceEmail($booking, $payment['gateway_txn_id'] ?? ('#' . $payment['id']));

        $this->success(null, 'Payment approved and booking confirmed successfully.');
    }

    public function reject(Request $request, int $id): void
    {
        if (!Auth::hasRole('admin')) {
            $this->fail('Only administrators can reject payments.', 403);
        }

        $payment = Payment::find($id);
        if (!$payment) {
            $this->fail('Payment record not found.', 404);
        }

        $booking = Booking::find($payment['booking_id']);
        if (!$booking) {
            $this->fail('Associated booking not found.', 404);
        }

        Payment::markFailedById($id);
        Booking::update($booking['id'], ['status' => 'cancelled']);

        if (!empty($booking['slot_id'])) {
            \App\Models\BookingSlot::release((int) $booking['slot_id']);
        }

        ActivityLog::log(Auth::id(), 'payment_rejected', 'booking', (int) $booking['id'], [
            'booking_ref' => $booking['booking_ref'],
            'payment_id' => $id,
        ]);

        Notification::create(
            (int) $booking['client_id'],
            'booking',
            'Payment verification failed — ' . $booking['booking_ref'],
            'Your payment verification for booking ' . $booking['booking_ref'] . ' could not be verified by admin and has been cancelled. Please contact support.'
        );

        $this->success(null, 'Payment rejected and booking cancelled.');
    }

    public function delete(Request $request, int $id): void
    {
        if (!Auth::hasRole('admin')) {
            $this->fail('Only administrators can delete payment records.', 403);
        }

        $payment = Payment::find($id);
        if (!$payment) {
            $this->fail('Payment record not found.', 404);
        }

        Payment::delete($id);
        $this->success(null, 'Payment record deleted successfully.');
    }

    private function ownedPendingBooking(Request $request): array
    {
        $ref = (string) $request->input('booking_ref', '');
        $booking = Booking::findByRef($ref);

        if (!$booking || (int) $booking['client_id'] !== Auth::id()) {
            $this->fail('Booking not found.', 404);
        }

        if ($booking['status'] !== 'pending_payment') {
            $this->fail('This booking is not awaiting payment.', 422);
        }

        return $booking;
    }
}
