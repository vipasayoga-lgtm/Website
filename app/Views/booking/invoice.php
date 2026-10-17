<?php

use App\Core\View;

$pageTitle = 'Invoice ' . $booking['booking_ref'] . ' — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'client';
$active = 'invoices';

$dateLabel = date('d M Y', strtotime($booking['slot_date']));
$timeLabel = date('g:i A', strtotime($booking['start_time'])) . ' – ' . date('g:i A', strtotime($booking['end_time']));
$issuedLabel = date('d M Y', strtotime($booking['updated_at']));
?>
<div class="container" style="max-width:680px;margin:0 auto;padding-block:var(--space-6);display:flex;flex-direction:column;align-items:center;width:100%">
    <div class="flex items-center justify-between mb-4 no-print" style="width:100%;max-width:640px">
        <a href="/dashboard/invoices" class="btn btn-ghost btn-sm">
            <?= View::icon('arrow-left', 'icon', 14) ?> Back to Invoices
        </a>
        <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">
            <?= View::icon('download', 'icon', 14) ?> Print / Save as PDF
        </button>
    </div>

    <div class="card invoice-card" style="width:100%;max-width:640px;margin:0 auto;padding:var(--space-8);border-radius:var(--radius-xl);border:1px solid var(--border-subtle);background:var(--surface-card);box-shadow:var(--shadow-md)">
        <div class="flex items-center justify-between mb-8" style="border-bottom:1px solid var(--border-subtle);padding-bottom:var(--space-4)">
            <div>
                <div class="splash__wordmark" style="color:var(--color-primary);font-size:1.5rem">Vipasa Yoga</div>
                <p class="text-muted mb-0" style="font-size:0.85rem">Official Payment Receipt &amp; Invoice</p>
            </div>
            <div class="text-right">
                <div style="font-weight:var(--font-weight-semibold);font-size:1.1rem;color:var(--color-primary)">INVOICE</div>
                <div style="font-weight:600;font-size:0.9rem"><?= View::e($booking['booking_ref']) ?></div>
                <div class="text-muted" style="font-size:0.82rem">Issued <?= View::e($issuedLabel) ?></div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6" style="background:var(--surface-sunken);padding:var(--space-4);border-radius:var(--radius-lg)">
            <div>
                <div class="text-muted" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.05em;font-weight:600">INSTRUCTOR</div>
                <div style="font-weight:600;font-size:1rem;color:var(--color-primary)"><?= View::e($instructor['name'] ?? 'Yoga Instructor') ?></div>
            </div>
            <div class="text-right">
                <div class="text-muted" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.05em;font-weight:600">SESSION DATE &amp; TIME</div>
                <div style="font-weight:600;font-size:0.95rem"><?= View::e($dateLabel) ?></div>
                <div class="text-muted" style="font-size:0.85rem"><?= View::e($timeLabel) ?></div>
            </div>
        </div>

        <table class="price-breakdown mb-6" style="width:100%">
            <tbody>
                <tr>
                    <td style="font-weight:500"><?= View::e($package['name']) ?></td>
                    <td class="text-right" style="font-weight:600"><?= View::e($booking['currency']) ?> <?= number_format((float) $booking['price'], 2) ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Tax (GST)</td>
                    <td class="text-right text-muted"><?= View::e($booking['currency']) ?> <?= number_format((float) $booking['tax_amount'], 2) ?></td>
                </tr>
                <?php if ((float) $booking['discount_amount'] > 0): ?>
                <tr class="is-discount">
                    <td style="color:var(--color-accent)">Discount Applied</td>
                    <td class="text-right" style="color:var(--color-accent)">&minus; <?= View::e($booking['currency']) ?> <?= number_format((float) $booking['discount_amount'], 2) ?></td>
                </tr>
                <?php endif; ?>
                <tr class="price-breakdown__total" style="border-top:2px solid var(--border-color);font-size:1.1rem">
                    <td style="font-weight:700">Total Paid</td>
                    <td class="text-right" style="font-weight:700;color:var(--color-primary)"><?= View::e($booking['currency']) ?> <?= number_format((float) $booking['total_amount'], 2) ?></td>
                </tr>
            </tbody>
        </table>

        <div style="border-top:1px dashed var(--border-subtle);padding-top:var(--space-4);margin-top:var(--space-4)">
            <?php if ($payment): ?>
            <p class="text-muted mb-1" style="font-size:0.85rem"><strong>Payment Transaction ID:</strong> <?= View::e($payment['gateway_txn_id'] ?? $payment['gateway_order_id'] ?? '—') ?></p>
            <?php endif; ?>

            <?php if ($meetingLink): ?>
            <p class="text-muted mb-0" style="font-size:0.85rem"><strong>Session Link:</strong> <a href="<?= View::e($meetingLink['url']) ?>" target="_blank" style="color:var(--color-primary);text-decoration:underline"><?= View::e($meetingLink['url']) ?></a></p>
            <?php endif; ?>
        </div>
    </div>
</div>
