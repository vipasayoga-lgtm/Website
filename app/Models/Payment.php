<?php

namespace App\Models;

use App\Core\Model;

final class Payment extends Model
{
    protected static string $table = 'payments';

    public static function createPending(int $bookingId, string $gateway, string $orderId, float $amount, string $currency): int
    {
        return self::insert([
            'booking_id' => $bookingId,
            'gateway' => $gateway,
            'gateway_order_id' => $orderId,
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
        ]);
    }

    public static function findByOrderId(string $orderId): ?array
    {
        return self::whereFirst(['gateway_order_id' => $orderId]);
    }

    public static function markSuccessByOrderId(string $orderId, string $paymentId): void
    {
        $payment = self::findByOrderId($orderId);

        if ($payment) {
            self::update($payment['id'], ['status' => 'success', 'gateway_txn_id' => $paymentId]);
        }
    }

    public static function markFailedByOrderId(string $orderId): void
    {
        $payment = self::findByOrderId($orderId);

        if ($payment) {
            self::update($payment['id'], ['status' => 'failed']);
        }
    }

    public static function forBooking(int $bookingId): ?array
    {
        $rows = self::where(['booking_id' => $bookingId], 'id DESC', 1);

        return $rows[0] ?? null;
    }

    public static function createOrUpdateManualUpi(int $bookingId, string $utrNumber, float $amount, string $currency, ?string $screenshotPath = null, array $extra = []): int
    {
        $existing = self::forBooking($bookingId);

        $data = [
            'booking_id' => $bookingId,
            'gateway' => 'upi',
            'gateway_txn_id' => $utrNumber,
            'method' => 'UPI QR',
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
            'raw_response' => !empty($extra) ? json_encode($extra) : null,
        ];

        if ($screenshotPath !== null) {
            $data['screenshot_path'] = $screenshotPath;
        }

        if ($existing && $existing['status'] === 'pending') {
            self::update($existing['id'], $data);
            return (int) $existing['id'];
        }

        return self::insert($data);
    }

    public static function markSuccessById(int $paymentId, ?string $paymentIdOrUtr = null): void
    {
        $data = ['status' => 'success'];
        if ($paymentIdOrUtr !== null && $paymentIdOrUtr !== '') {
            $data['gateway_txn_id'] = $paymentIdOrUtr;
        }
        self::update($paymentId, $data);
    }

    public static function markFailedById(int $paymentId): void
    {
        self::update($paymentId, ['status' => 'failed']);
    }

    public static function forClient(int $clientId): array
    {
        $stmt = static::db()->prepare(
            'SELECT pay.*, b.booking_ref
             FROM payments pay
             INNER JOIN bookings b ON b.id = pay.booking_id
             WHERE b.client_id = :client_id
             ORDER BY pay.created_at DESC'
        );
        $stmt->execute(['client_id' => $clientId]);

        return $stmt->fetchAll();
    }
}
