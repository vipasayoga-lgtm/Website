<?php

use App\Core\View;

$pageTitle = 'Booking & Payment Confirmation';
$pageCss = 'booking';
$portal = 'client';
$active = 'services';

$hasPendingProof = !empty($payment) && $payment['status'] === 'pending';
$isConfirmed = in_array($booking['status'], ['confirmed', 'completed'], true);

$statusBadgeClass = match (true) {
    $isConfirmed => 'badge-confirmed',
    $hasPendingProof => 'badge-pending',
    $booking['status'] === 'cancelled' => 'badge-cancelled',
    default => 'badge-pending',
};

$statusLabel = match (true) {
    $isConfirmed => 'Confirmed',
    $hasPendingProof => 'Payment Verification Pending',
    $booking['status'] === 'cancelled' => 'Cancelled',
    default => 'Pending payment',
};

$dateLabel = date('l, d M Y', strtotime($booking['slot_date']));
$timeLabel = date('g:i A', strtotime($booking['start_time'])) . ' – ' . date('g:i A', strtotime($booking['end_time']));
$currency = $booking['currency'];

$upi = $upiDetails ?? [
    'id' => 'vijayaparameshh@oksbi',
    'name' => 'Vijaya Parameswaran',
    'phone' => '+91 70127 75990',
    'qr_image' => '/assets/img/payment-qr.jpg',
];

$waNumberClean = preg_replace('/\D+/', '', $upi['phone']);
$waMessage = 'Hi Vijaya, I have completed the UPI payment for booking ' . $booking['booking_ref'] . ' (Amount: ' . $currency . ' ' . number_format((float) $booking['total_amount'], 2) . '). Here is my payment screenshot for verification.';
$waUrl = 'https://wa.me/' . $waNumberClean . '?text=' . urlencode($waMessage);
?>

<style>
/* =========================================================================
   Innovative & Trending Checkout UI Styles
   ========================================================================= */
.checkout-wrapper {
    max-width: 1040px;
    margin: 0 auto;
    padding: var(--space-4) var(--space-2) var(--space-12);
}

.checkout-header {
    text-align: center;
    margin-bottom: 28px;
}

.checkout-header__icon {
    display: inline-grid;
    place-items: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: <?= $isConfirmed ? '#DCFCE7' : ($hasPendingProof ? '#FEF3C7' : '#E0F2FE') ?>;
    color: <?= $isConfirmed ? '#15803D' : ($hasPendingProof ? '#B45309' : '#0369A1') ?>;
    margin-bottom: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
}

.checkout-grid {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 28px;
    align-items: start;
}

@media (max-width: 960px) {
    .checkout-grid {
        grid-template-columns: 1fr;
        max-width: 620px;
        margin-inline: auto;
    }
}

/* ---- Left Column: Summary Card -------------------------------------- */
.summary-card {
    background: #ffffff;
    border: 1px solid var(--border-subtle, #e2e8f0);
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05);
}

.summary-card__title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-primary, #0f172a);
    margin: 0 0 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.session-instructor-box {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px;
    background: var(--surface-bg-alt, #f8fafc);
    border: 1px solid var(--border-subtle, #e2e8f0);
    border-radius: 14px;
    margin-bottom: 18px;
}

.session-instructor-box img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.session-meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    color: var(--text-muted, #64748b);
    background: #ffffff;
    padding: 4px 10px;
    border-radius: 999px;
    border: 1px solid #e2e8f0;
    margin-top: 6px;
}

/* Price Breakdown */
.checkout-price-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.88rem;
    margin-bottom: 18px;
}

.checkout-price-table td {
    padding: 8px 0;
    color: var(--text-muted, #64748b);
}

.checkout-price-table td:last-child {
    text-align: right;
    font-weight: 600;
    color: var(--text-primary, #0f172a);
}

.checkout-total-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.12) 100%);
    border: 1px solid rgba(16, 185, 129, 0.35);
    padding: 14px 18px;
    border-radius: 14px;
    margin-top: 14px;
}

.checkout-total-banner__label {
    font-size: 0.88rem;
    font-weight: 600;
    color: #065f46;
}

.checkout-total-banner__val {
    font-size: 1.3rem;
    font-weight: 800;
    color: #047857;
}

/* Trust Badges */
.trust-badge-row {
    margin-top: 20px;
    padding-top: 18px;
    border-top: 1px dashed var(--border-subtle, #e2e8f0);
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.8rem;
    color: #475569;
}

.trust-item svg {
    color: #10b981;
    flex-shrink: 0;
}

/* ---- Right Column: Payment & Proof Card ------------------------------ */
.payment-main-card {
    background: #ffffff;
    border: 1px solid var(--border-subtle, #e2e8f0);
    border-radius: 20px;
    padding: 28px 24px;
    box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05);
}

.payee-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding-bottom: 18px;
    border-bottom: 1px solid var(--border-subtle, #f1f5f9);
    margin-bottom: 20px;
}

.payee-identity {
    display: flex;
    align-items: center;
    gap: 12px;
}

.payee-avatar-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1B4965 0%, #0D9488 100%);
    color: #ffffff;
    display: grid;
    place-items: center;
    font-weight: 700;
    font-size: 1.1rem;
    box-shadow: 0 4px 12px rgba(27, 73, 101, 0.25);
}

.upi-id-pill-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 0.825rem;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    color: #334155;
}

.copy-btn-modern {
    border: none;
    background: #e2e8f0;
    cursor: pointer;
    color: #0f172a;
    font-weight: 600;
    font-size: 0.75rem;
    padding: 3px 8px;
    border-radius: 6px;
    transition: all 0.15s ease;
}

.copy-btn-modern:hover {
    background: #0284c7;
    color: #ffffff;
}

/* QR Code Display Center */
.qr-stage {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin-bottom: 24px;
}

.qr-frame-glow {
    position: relative;
    padding: 14px;
    background: #ffffff;
    border-radius: 20px;
    border: 2px solid #e2e8f0;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
    max-width: 260px;
    width: 100%;
    margin-bottom: 14px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.qr-frame-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
}

.qr-frame-glow img {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 12px;
}

/* Supported Apps Pill Row */
.supported-apps-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 4px;
}

.app-chip {
    font-size: 0.72rem;
    font-weight: 600;
    color: #64748b;
    background: #f1f5f9;
    padding: 3px 10px;
    border-radius: 999px;
}

/* Step timeline */
.steps-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 24px;
}

.step-card-modern {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 14px;
    font-size: 0.835rem;
    line-height: 1.45;
}

.step-card-modern__number {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #1B4965;
    color: #ffffff;
    display: grid;
    place-items: center;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
}

.wa-cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #25D366;
    color: #ffffff !important;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.84rem;
    padding: 8px 16px;
    border-radius: 10px;
    margin-top: 8px;
    box-shadow: 0 4px 14px rgba(37, 211, 102, 0.3);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.wa-cta-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(37, 211, 102, 0.4);
}

/* =========================================================================
   CLEAN FORM STYLES (ABSOLUTELY NO OVERLAPPING LABELS)
   ========================================================================= */
.pay-form-group {
    margin-bottom: 18px;
    display: flex;
    flex-direction: column;
}

/* Normal static block label placed cleanly above input */
.pay-field-label {
    display: block !important;
    position: static !important;
    transform: none !important;
    font-size: 0.84rem !important;
    font-weight: 600 !important;
    color: #1e293b !important;
    margin-bottom: 6px !important;
    pointer-events: auto !important;
    line-height: 1.3 !important;
}

.pay-field-label span.req {
    color: #ef4444;
    margin-left: 2px;
}

.pay-input-control {
    width: 100%;
    display: block;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.88rem;
    color: #0f172a;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    box-sizing: border-box;
}

.pay-input-control:focus {
    border-color: #0284c7;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
}

.pay-input-control::placeholder {
    color: #94a3b8;
    opacity: 1;
}

.pay-field-hint {
    font-size: 0.74rem;
    color: #64748b;
    margin-top: 5px;
    line-height: 1.3;
}

/* Innovative File Dropzone Box */
.pay-dropzone {
    position: relative;
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s ease;
}

.pay-dropzone:hover, .pay-dropzone.dragover {
    border-color: #0284c7;
    background: #f0f9ff;
}

.pay-dropzone input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
}

.pay-dropzone__content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.pay-dropzone__icon {
    color: #0284c7;
    margin-bottom: 2px;
}

.pay-dropzone__title {
    font-size: 0.825rem;
    font-weight: 600;
    color: #1e293b;
}

.pay-dropzone__sub {
    font-size: 0.72rem;
    color: #64748b;
}

.pay-dropzone__selected-preview {
    display: none;
    align-items: center;
    gap: 10px;
    background: #ffffff;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 6px 12px;
    margin-top: 8px;
    font-size: 0.8rem;
    color: #15803d;
    font-weight: 600;
}

/* Submit Action Button */
.pay-submit-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: linear-gradient(135deg, #0d9488 0%, #059669 100%);
    color: #ffffff !important;
    border: none;
    border-radius: 12px;
    padding: 13px 24px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(13, 148, 136, 0.35);
    transition: transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease;
    margin-top: 6px;
}

.pay-submit-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(13, 148, 136, 0.45);
}

.pay-submit-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}

/* State 2 Pending Card */
.pending-status-card {
    background: #fffbeb;
    border: 1.5px solid #fde68a;
    border-radius: 18px;
    padding: 24px;
}
</style>

<div class="checkout-wrapper">
    <!-- Top Header & Reference -->
    <div class="checkout-header">
        <div class="checkout-header__icon">
            <?= View::icon($isConfirmed ? 'check' : ($hasPendingProof ? 'clock' : 'calendar-week'), 'icon', 30) ?>
        </div>
        <h1 style="font-size:1.6rem;font-weight:800;color:var(--text-primary,#0f172a);margin:0 0 6px">
            <?= $isConfirmed ? 'Booking Confirmed' : ($hasPendingProof ? 'Payment Under Verification' : 'Booking & Payment Confirmation') ?>
        </h1>
        <p style="font-size:0.875rem;color:var(--text-muted,#64748b);margin:0 0 8px">
            Booking Reference: <strong style="font-family:monospace;color:var(--color-primary,#1B4965);font-size:1rem"><?= View::e($booking['booking_ref']) ?></strong>
        </p>
        <span class="badge <?= $statusBadgeClass ?>" style="font-size:0.8rem;padding:4px 14px;border-radius:999px">
            <?= $statusLabel ?>
        </span>
    </div>

    <!-- Wide 2-Column Checkout Layout -->
    <div class="checkout-grid">
        
        <!-- =================================================================
             COLUMN 1: Session Details & Pricing Summary
             ================================================================= -->
        <div class="summary-card">
            <div class="summary-card__title">
                <span>Session Summary</span>
                <span class="badge badge-secondary" style="font-size:0.75rem"><?= View::e($package['name']) ?></span>
            </div>

            <!-- Instructor & Slot Info -->
            <div class="session-instructor-box">
                <?php if (!empty($instructor['avatar_path'])): ?>
                    <img src="<?= View::e($instructor['avatar_path']) ?>" alt="<?= View::e($instructor['name']) ?>">
                <?php else: ?>
                    <div style="width:50px;height:50px;border-radius:50%;background:#e2e8f0;display:grid;place-items:center;font-weight:700;color:#475569">
                        <?= View::e(substr($instructor['name'] ?? 'T', 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <div style="font-weight:700;font-size:0.95rem;color:#0f172a"><?= View::e($instructor['name']) ?></div>
                    <div style="font-size:0.8rem;color:#64748b">Certified Yoga Instructor</div>
                    <div class="session-meta-pill">
                        <?= View::icon('calendar-week', 'icon', 13) ?>
                        <span><?= View::e($dateLabel) ?></span>
                    </div>
                </div>
            </div>

            <div style="font-size:0.82rem;color:#475569;margin-bottom:14px;padding:8px 12px;background:#f8fafc;border-radius:8px">
                🕒 <strong>Time:</strong> <?= View::e($timeLabel) ?> (<?= View::e($booking['client_timezone']) ?>)
            </div>

            <!-- Price Breakdown Table -->
            <table class="checkout-price-table">
                <tbody>
                    <tr>
                        <td>Package Price</td>
                        <td><?= View::e($currency) ?> <?= number_format((float) $booking['price'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>Platform Tax (5%)</td>
                        <td><?= View::e($currency) ?> <?= number_format((float) $booking['tax_amount'], 2) ?></td>
                    </tr>
                    <?php if ((float) $booking['discount_amount'] > 0): ?>
                    <tr style="color:#059669">
                        <td>Promo Discount</td>
                        <td>&minus; <?= View::e($currency) ?> <?= number_format((float) $booking['discount_amount'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Total Banner -->
            <div class="checkout-total-banner">
                <span class="checkout-total-banner__label">Total Payable</span>
                <span class="checkout-total-banner__val"><?= View::e($currency) ?> <?= number_format((float) $booking['total_amount'], 2) ?></span>
            </div>

            <!-- Trust & Guarantee -->
            <div class="trust-badge-row">
                <div class="trust-item">
                    <?= View::icon('check', 'icon', 15) ?>
                    <span>100% Secure &amp; Verified UPI Payment</span>
                </div>
                <div class="trust-item">
                    <?= View::icon('check', 'icon', 15) ?>
                    <span>Direct live video session with instructor</span>
                </div>
                <div class="trust-item">
                    <?= View::icon('check', 'icon', 15) ?>
                    <span>Instant booking confirmation after verification</span>
                </div>
            </div>

            <!-- Need Help -->
            <div style="margin-top:20px;padding:12px;background:#f8fafc;border-radius:10px;font-size:0.78rem;color:#64748b;text-align:center">
                Need help with payment? WhatsApp us: <strong style="color:#0f172a"><?= View::e($upi['phone']) ?></strong>
            </div>
        </div>

        <!-- =================================================================
             COLUMN 2: Payment Gateway / QR Code / Proof Form
             ================================================================= -->
        <div>
            <?php if ($isConfirmed): ?>
                <!-- State 1: Already Confirmed -->
                <div class="payment-main-card" style="text-align:center;padding:36px 24px">
                    <div style="display:inline-grid;place-items:center;width:64px;height:64px;border-radius:50%;background:#dcfce7;color:#15803d;margin-bottom:14px">
                        <?= View::icon('check', 'icon', 32) ?>
                    </div>
                    <h2 style="font-size:1.3rem;font-weight:700;color:#0f172a;margin:0 0 8px">Your Session is Confirmed!</h2>
                    <p style="font-size:0.88rem;color:#64748b;margin:0 0 24px;line-height:1.5">
                        Your payment has been successfully approved. You can join the session directly from your dashboard or view your meeting link below.
                    </p>
                    <div class="flex flex-col gap-3">
                        <a href="/booking/<?= View::e($booking['booking_ref']) ?>/join" class="btn btn-accent btn-lg" style="border-radius:12px;font-weight:700">
                            <?= View::icon('video', 'icon', 18) ?>
                            <span>Join Video Meeting Now</span>
                        </a>
                        <a href="/dashboard/bookings" class="btn btn-secondary">
                            View All My Bookings
                        </a>
                    </div>
                </div>

            <?php elseif ($hasPendingProof): ?>
                <!-- State 2: Payment Proof Submitted & Pending Verification -->
                <div class="pending-status-card">
                    <div class="flex items-center gap-3" style="margin-bottom:12px">
                        <div style="width:36px;height:36px;border-radius:50%;background:#fef3c7;display:grid;place-items:center;color:#b45309;flex-shrink:0">
                            <?= View::icon('clock', 'icon', 20) ?>
                        </div>
                        <div>
                            <h3 style="margin:0;font-size:1.05rem;font-weight:700;color:#92400e">Verification in Progress</h3>
                            <div style="font-size:0.8rem;color:#b45309">Admin is verifying your transaction details</div>
                        </div>
                    </div>

                    <p style="font-size:0.85rem;color:#78350f;line-height:1.5;margin:0 0 16px">
                        Thank you! We received your payment reference. Once verified by our team, your booking will become <strong>Confirmed</strong> and your video link will appear immediately on your dashboard.
                    </p>

                    <div style="background:#ffffff;border:1px solid #fde68a;border-radius:12px;padding:14px;margin-bottom:18px;font-size:0.85rem">
                        <div style="margin-bottom:6px">
                            <span style="color:#64748b">Submitted UTR / Reference:</span>
                            <strong style="font-family:monospace;color:#1B4965;font-size:0.95rem;margin-left:6px"><?= View::e($payment['gateway_txn_id']) ?></strong>
                        </div>
                        <div style="margin-bottom:6px">
                            <span style="color:#64748b">Amount:</span>
                            <strong style="color:#059669;margin-left:6px"><?= View::e($currency) ?> <?= number_format((float) $booking['total_amount'], 2) ?></strong>
                        </div>
                        <?php if (!empty($payment['screenshot_path'])): ?>
                        <div>
                            <span style="color:#64748b">Payment Proof Screenshot:</span>
                            <a href="<?= View::e($payment['screenshot_path']) ?>" target="_blank" rel="noopener" style="color:#0284c7;text-decoration:underline;font-weight:600;margin-left:6px">View Uploaded Image</a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <a href="<?= View::e($waUrl) ?>" target="_blank" rel="noopener" class="wa-cta-btn">
                            <span>💬 Send on WhatsApp</span>
                        </a>
                        <button type="button" class="btn btn-ghost btn-sm" id="toggle-resubmit-btn" style="font-size:0.8rem;color:#64748b">
                            Edit / Re-enter UTR
                        </button>
                    </div>

                    <!-- Collapsible Re-submission Form -->
                    <div id="resubmit-form-container" style="display:none;margin-top:18px;padding:16px;background:#ffffff;border-radius:12px;border:1px solid #cbd5e1">
                        <h4 style="margin:0 0 12px;font-size:0.9rem;font-weight:700">Update Reference Details</h4>
                        <form id="upi-resubmit-form" enctype="multipart/form-data">
                            <input type="hidden" name="booking_ref" value="<?= View::e($booking['booking_ref']) ?>">
                            <div class="pay-form-group">
                                <label class="pay-field-label">Correct 12-Digit UTR / Transaction No.</label>
                                <input type="text" name="utr_number" class="pay-input-control" required value="<?= View::e($payment['gateway_txn_id']) ?>">
                            </div>
                            <div class="pay-form-group">
                                <label class="pay-field-label">New Screenshot (Optional)</label>
                                <input type="file" name="screenshot" accept="image/*" class="pay-input-control" style="font-size:0.8rem;padding:6px">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm btn-block" id="resubmit-btn" style="margin-top:8px">Update Payment Details</button>
                        </form>
                    </div>

                    <div style="margin-top:16px;text-align:center">
                        <a href="/dashboard/bookings" class="btn btn-secondary btn-block">Back to My Bookings</a>
                    </div>
                </div>

            <?php else: ?>
                <!-- State 3: Pay via UPI QR Scan -->
                <div class="payment-main-card">
                    
                    <!-- Payee Identity Header -->
                    <div class="payee-header">
                        <div class="payee-identity">
                            <div class="payee-avatar-circle">
                                <?= View::e(substr($upi['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:1.05rem;color:#0f172a"><?= View::e($upi['name']) ?></div>
                                <div style="font-size:0.75rem;color:#059669;font-weight:600;display:flex;align-items:center;gap:4px">
                                    <span>✓ Verified Yoga Merchant</span>
                                </div>
                            </div>
                        </div>
                        <div class="upi-id-pill-modern">
                            <span id="upi-id-text"><?= View::e($upi['id']) ?></span>
                            <button type="button" class="copy-btn-modern" id="copy-upi-btn" title="Copy UPI ID">Copy</button>
                        </div>
                    </div>

                    <!-- QR Code Frame Stage -->
                    <div class="qr-stage">
                        <div class="qr-frame-glow">
                            <img src="<?= View::e($upi['qr_image']) ?>" alt="UPI QR Code - Scan to Pay" id="upi-qr-img">
                        </div>
                        <div class="supported-apps-row">
                            <span class="app-chip">Google Pay</span>
                            <span class="app-chip">PhonePe</span>
                            <span class="app-chip">Paytm</span>
                            <span class="app-chip">BHIM</span>
                            <span class="app-chip">Cred</span>
                            <span class="app-chip">Any UPI</span>
                        </div>
                    </div>

                    <!-- 3 Clean Step Cards -->
                    <div class="steps-container">
                        <div class="step-card-modern">
                            <div class="step-card-modern__number">1</div>
                            <div>
                                <strong>Scan &amp; Pay Exact Amount:</strong> Open your UPI app, scan the QR code above, and pay <strong><?= View::e($currency) ?> <?= number_format((float) $booking['total_amount'], 2) ?></strong>.
                            </div>
                        </div>
                        <div class="step-card-modern">
                            <div class="step-card-modern__number">2</div>
                            <div>
                                <strong>Send Screenshot to WhatsApp:</strong> Send your payment receipt screenshot to our studio helpline at <strong><?= View::e($upi['phone']) ?></strong>.
                                <div>
                                    <a href="<?= View::e($waUrl) ?>" target="_blank" rel="noopener" class="wa-cta-btn">
                                        <span>💬 Send Screenshot to WhatsApp</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="step-card-modern">
                            <div class="step-card-modern__number">3</div>
                            <div>
                                <strong>Enter Details Below:</strong> Provide your 12-digit UTR reference number so admin can verify and activate your session.
                            </div>
                        </div>
                    </div>

                    <!-- =========================================================
                         PROOF SUBMISSION FORM (NO OVERLAPPING LABELS)
                         ========================================================= -->
                    <form id="upi-proof-form" enctype="multipart/form-data">
                        <input type="hidden" name="booking_ref" value="<?= View::e($booking['booking_ref']) ?>">

                        <!-- Field 1: UTR Number -->
                        <div class="pay-form-group">
                            <label class="pay-field-label" for="utr_number">
                                UPI Transaction / UTR Number <span class="req">*</span>
                            </label>
                            <input type="text" id="utr_number" name="utr_number" class="pay-input-control" required placeholder="e.g. 428938172938 (12-digit UTR)">
                            <div class="pay-field-hint">
                                Found in your Google Pay, PhonePe, or Paytm receipt as 'UPI Ref No.', 'UTR', or 'Txn ID'.
                            </div>
                        </div>

                        <!-- Field 2: Innovative Screenshot Dropzone -->
                        <div class="pay-form-group">
                            <label class="pay-field-label" for="screenshot">
                                Upload Payment Screenshot <span style="font-size:0.75rem;color:#64748b;font-weight:normal">(Optional)</span>
                            </label>
                            <div class="pay-dropzone" id="screenshot-dropzone">
                                <input type="file" id="screenshot" name="screenshot" accept="image/jpeg,image/png,image/webp">
                                <div class="pay-dropzone__content">
                                    <div class="pay-dropzone__icon">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                    </div>
                                    <div class="pay-dropzone__title" id="dropzone-title">Click to upload or drag &amp; drop screenshot</div>
                                    <div class="pay-dropzone__sub">Supports JPG, PNG, or WebP (max 10MB)</div>
                                    <div class="pay-dropzone__selected-preview" id="dropzone-preview">
                                        <span>✓ File Selected:</span>
                                        <span id="dropzone-filename" style="font-family:monospace"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="pay-field-hint">
                                Fast-tracks your approval when you attach your payment proof screenshot.
                            </div>
                        </div>

                        <!-- Field 3: Sender Phone / WhatsApp -->
                        <div class="pay-form-group">
                            <label class="pay-field-label" for="payer_phone">
                                Sender Phone / WhatsApp Number
                            </label>
                            <input type="tel" id="payer_phone" name="payer_phone" class="pay-input-control" placeholder="Mobile number used for UPI" value="<?= View::e($client['phone'] ?? '') ?>">
                            <div class="pay-field-hint">
                                Used by admin to verify your transaction if needed.
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="pay-submit-btn" id="submit-proof-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Submit Payment Proof &amp; Confirm</span>
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="module">
import toast from '/assets/js/modules/toast.js';

// Copy UPI ID with interactive feedback
const copyBtn = document.getElementById('copy-upi-btn');
const upiIdText = document.getElementById('upi-id-text');

if (copyBtn && upiIdText) {
    copyBtn.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(upiIdText.textContent.trim());
            copyBtn.textContent = 'Copied!';
            copyBtn.style.background = '#059669';
            copyBtn.style.color = '#ffffff';
            setTimeout(() => {
                copyBtn.textContent = 'Copy';
                copyBtn.style.background = '';
                copyBtn.style.color = '';
            }, 2000);
            toast.success('UPI ID copied to clipboard!');
        } catch {
            toast.info('UPI ID: ' + upiIdText.textContent.trim());
        }
    });
}

// File dropzone preview handler
const fileInput = document.getElementById('screenshot');
const previewBox = document.getElementById('dropzone-preview');
const filenameSpan = document.getElementById('dropzone-filename');
const dropzoneTitle = document.getElementById('dropzone-title');

if (fileInput && previewBox && filenameSpan) {
    fileInput.addEventListener('change', () => {
        if (fileInput.files && fileInput.files[0]) {
            const f = fileInput.files[0];
            filenameSpan.textContent = f.name + ' (' + Math.round(f.size / 1024) + ' KB)';
            previewBox.style.display = 'inline-flex';
            if (dropzoneTitle) dropzoneTitle.textContent = 'Change screenshot file';
        }
    });
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

async function handleProofSubmit(form, submitBtn) {
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span>Submitting proof...</span>';

    try {
        const formData = new FormData(form);
        const response = await fetch('/api/payments/submit-proof', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-Token': getCsrfToken(),
            },
            credentials: 'same-origin',
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data?.message || 'Failed to submit payment details.');
        }

        toast.success(data?.message || 'Payment submitted successfully! Admin will verify.');
        setTimeout(() => {
            window.location.reload();
        }, 800);
    } catch (err) {
        toast.error(err.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

const proofForm = document.getElementById('upi-proof-form');
if (proofForm) {
    proofForm.addEventListener('submit', (e) => {
        e.preventDefault();
        handleProofSubmit(proofForm, document.getElementById('submit-proof-btn'));
    });
}

const toggleResubmitBtn = document.getElementById('toggle-resubmit-btn');
const resubmitContainer = document.getElementById('resubmit-form-container');
if (toggleResubmitBtn && resubmitContainer) {
    toggleResubmitBtn.addEventListener('click', () => {
        resubmitContainer.style.display = resubmitContainer.style.display === 'none' ? 'block' : 'none';
    });
}

const resubmitForm = document.getElementById('upi-resubmit-form');
if (resubmitForm) {
    resubmitForm.addEventListener('submit', (e) => {
        e.preventDefault();
        handleProofSubmit(resubmitForm, document.getElementById('resubmit-btn'));
    });
}
</script>
