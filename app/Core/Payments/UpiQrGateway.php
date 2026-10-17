<?php

namespace App\Core\Payments;

use App\Core\Request;
use App\Models\Setting;

final class UpiQrGateway implements PaymentGateway
{
    public function name(): string
    {
        return 'upi';
    }

    public function isConfigured(): bool
    {
        return true;
    }

    public function createCheckout(int $amountInSmallestUnit, string $currency, string $receipt, string $description): ?array
    {
        return [
            'provider' => 'upi_qr',
            'order_id' => $receipt,
            'amount' => $amountInSmallestUnit,
            'currency' => $currency,
            'name' => Setting::get('upi_name', 'Vijaya Parameswaran'),
            'upi_id' => Setting::get('upi_id', 'vijayaparameshh@oksbi'),
            'phone' => Setting::get('upi_phone', '+91 70127 75990'),
            'qr_image' => Setting::get('upi_qr_image', '/assets/img/payment-qr.jpg'),
            'description' => $description,
        ];
    }

    public function confirmPayment(Request $request): array
    {
        $orderId = (string) $request->input('booking_ref', $request->input('order_id', ''));
        $utrNumber = trim((string) $request->input('utr_number', $request->input('transaction_id', '')));

        if ($orderId === '' || $utrNumber === '') {
            return ['success' => false, 'order_id' => $orderId, 'transaction_id' => null];
        }

        return [
            'success' => true,
            'order_id' => $orderId,
            'transaction_id' => $utrNumber,
        ];
    }
}
