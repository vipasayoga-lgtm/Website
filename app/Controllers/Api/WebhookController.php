<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Setting;

final class WebhookController extends Controller
{
    public function razorpay(Request $request): void
    {
        $rawPayload = file_get_contents('php://input') ?: '';
        $signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

        $config = (require dirname(__DIR__, 2) . '/Config/app.php')['payments']['razorpay'];
        $webhookSecret = Setting::get('razorpay_webhook_secret', $config['key_secret'] ?? '');

        if ($webhookSecret !== '' && $signature !== '') {
            $expectedSignature = hash_hmac('sha256', $rawPayload, $webhookSecret);
            if (!hash_equals($expectedSignature, $signature)) {
                $this->fail('Invalid Razorpay signature.', 400);
            }
        }

        $data = json_decode($rawPayload, true);
        if (!$data || empty($data['event'])) {
            $this->success(['status' => 'ignored']);
        }

        $event = $data['event'];
        $entity = $data['payload']['payment']['entity'] ?? [];

        if (in_array($event, ['payment.captured', 'order.paid'], true)) {
            $orderId = (string) ($entity['order_id'] ?? '');
            $paymentId = (string) ($entity['id'] ?? '');

            if ($orderId !== '') {
                Payment::markSuccessByOrderId($orderId, $paymentId);

                $payment = Payment::findByOrderId($orderId);
                if ($payment) {
                    Booking::update($payment['booking_id'], [
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                    ]);
                }
            }
        } elseif ($event === 'payment.failed') {
            $orderId = (string) ($entity['order_id'] ?? '');
            if ($orderId !== '') {
                Payment::markFailedByOrderId($orderId);
            }
        }

        $this->success(['status' => 'processed']);
    }

    public function stripe(Request $request): void
    {
        $rawPayload = file_get_contents('php://input') ?: '';
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        $config = (require dirname(__DIR__, 2) . '/Config/app.php')['payments']['stripe'];
        $webhookSecret = Setting::get('stripe_webhook_secret', $config['webhook_secret'] ?? '');

        if ($webhookSecret !== '' && $sigHeader !== '') {
            $timestamp = null;
            $signature = null;

            foreach (explode(',', $sigHeader) as $item) {
                $parts = explode('=', trim($item), 2);
                if (count($parts) === 2) {
                    if ($parts[0] === 't') {
                        $timestamp = $parts[1];
                    } elseif ($parts[0] === 'v1') {
                        $signature = $parts[1];
                    }
                }
            }

            if ($timestamp && $signature) {
                $signedPayload = "{$timestamp}.{$rawPayload}";
                $expectedSignature = hash_hmac('sha256', $signedPayload, $webhookSecret);
                if (!hash_equals($expectedSignature, $signature)) {
                    $this->fail('Invalid Stripe signature.', 400);
                }
            }
        }

        $data = json_decode($rawPayload, true);
        if (!$data || empty($data['type'])) {
            $this->success(['status' => 'ignored']);
        }

        $type = $data['type'];
        $object = $data['data']['object'] ?? [];

        if (in_array($type, ['checkout.session.completed', 'payment_intent.succeeded'], true)) {
            $orderId = (string) ($object['id'] ?? '');
            $bookingRef = (string) ($object['metadata']['booking_ref'] ?? '');

            if ($orderId !== '') {
                Payment::markSuccessByOrderId($orderId, $orderId);
            }

            if ($bookingRef !== '') {
                $booking = Booking::findByRef($bookingRef);
                if ($booking) {
                    Booking::update($booking['id'], [
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                    ]);

                    $payment = Payment::forBooking($booking['id']);
                    if ($payment) {
                        Payment::update($payment['id'], ['status' => 'success', 'gateway_txn_id' => $orderId]);
                    }
                }
            }
        } elseif (in_array($type, ['payment_intent.payment_failed', 'charge.failed'], true)) {
            $orderId = (string) ($object['id'] ?? '');
            if ($orderId !== '') {
                Payment::markFailedByOrderId($orderId);
            }
        }

        $this->success(['status' => 'processed']);
    }
}
