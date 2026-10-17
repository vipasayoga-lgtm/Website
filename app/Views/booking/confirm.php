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
.upi-pay-card {
    background: var(--surface-card, #ffffff);
    border: 1px solid var(--border-subtle, #e2e8f0);
    border-radius: var(--radius-2xl, 20px);
    padding: 24px 20px;
    margin-top: 24px;
    box-shadow: 0 10px 30px -5px rgba(15, 51, 73, 0.06);
    text-align: center;
}
.upi-qr-frame {
    display: inline-block;
    padding: 12px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    border: 2px solid var(--border-subtle, #f1f5f9);
    margin-bottom: 16px;
    max-width: 280px;
    width: 100%;
}
.upi-qr-frame img {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 12px;
}
.upi-payee-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--text-primary, #0f172a);
    margin: 0 0 4px;
}
.upi-id-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--surface-bg-alt, #f8fafc);
    border: 1px solid var(--border-subtle, #e2e8f0);
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 0.85rem;
    color: var(--text-muted, #475569);
    margin-bottom: 12px;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
}
.upi-amount-banner {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.12) 100%);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #047857;
    padding: 10px 16px;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    margin: 8px auto 16px;
    max-width: 320px;
}
.upi-step-box {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    text-align: left;
    background: var(--surface-bg-alt, #f8fafc);
    border: 1px solid var(--border-subtle, #e2e8f0);
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 12px;
    font-size: 0.835rem;
}
.upi-step-badge {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: var(--color-primary, #1B4965);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
}
.wa-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #25D366;
    color: #ffffff !important;
    border: none;
    padding: 9px 18px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.wa-action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(37, 211, 102, 0.35);
}
.pending-notice-card {
    background: #FFFBEB;
    border: 1px solid #FCD34D;
    border-radius: 16px;
    padding: 20px;
    margin-top: 24px;
    text-align: left;
}
.copy-btn {
    border: none;
    background: transparent;
    cursor: pointer;
    color: var(--color-primary, #1B4965);
    font-weight: 600;
    font-size: 0.75rem;
    padding: 2px 6px;
    border-radius: 4px;
}
.copy-btn:hover {
    background: rgba(0,0,0,0.05);
}
</style>

<div class="container booking-page">
    <div class="booking-success">
        <div class="booking-success__icon">
            <?= View::icon($isConfirmed ? 'check' : ($hasPendingProof ? 'clock' : 'calendar-week'), 'icon', 28) ?>
        </div>
        <h1><?= $isConfirmed ? 'Booking Confirmed' : ($hasPendingProof ? 'Payment Under Verification' : 'Complete Your Booking') ?></h1>
        <p class="text-muted">Reference <strong><?= View::e($booking['booking_ref']) ?></strong></p>
        <span class="badge <?= $statusBadgeClass ?>"><?= $statusLabel ?></span>
    </div>

    <div class="booking-summary" style="max-width:580px;margin-inline:auto;">
        <div class="booking-summary__title">Session details</div>

        <div class="booking-summary__item">
            <div class="booking-summary__item-image">
                <?php if (!empty($instructor['avatar_path'])): ?>
                <img src="<?= View::e($instructor['avatar_path']) ?>" alt="">
                <?php endif; ?>
            </div>
            <div>
                <div class="booking-summary__item-name"><?= View::e($instructor['name']) ?></div>
                <div class="booking-summary__item-meta"><?= View::e($package['name']) ?></div>
                <div class="booking-summary__item-meta"><?= View::e($dateLabel) ?> · <?= View::e($timeLabel) ?> (<?= View::e($booking['client_timezone']) ?>)</div>
            </div>
        </div>

        <table class="price-breakdown">
            <tbody>
                <tr>
                    <td>Package price</td>
                    <td><?= View::e($currency) ?> <?= number_format((float) $booking['price'], 2) ?></td>
                </tr>
                <tr>
                    <td>Tax</td>
                    <td><?= View::e($currency) ?> <?= number_format((float) $booking['tax_amount'], 2) ?></td>
                </tr>
                <?php if ((float) $booking['discount_amount'] > 0): ?>
                <tr class="is-discount">
                    <td>Discount</td>
                    <td>&minus; <?= View::e($currency) ?> <?= number_format((float) $booking['discount_amount'], 2) ?></td>
                </tr>
                <?php endif; ?>
                <tr class="price-breakdown__total">
                    <td>Total payable</td>
                    <td><?= View::e($currency) ?> <?= number_format((float) $booking['total_amount'], 2) ?></td>
                </tr>
            </tbody>
        </table>

        <?php if (!empty($booking['notes'])): ?>
        <p class="form-group__hint">Note: <?= View::e($booking['notes']) ?></p>
        <?php endif; ?>

        <?php if ($isConfirmed): ?>
            <!-- State 1: Confirmed -->
            <div class="booking-summary__cta" style="margin-top:24px">
                <a href="/booking/<?= View::e($booking['booking_ref']) ?>/join" class="btn btn-accent btn-block btn-lg" style="margin-bottom:8px">
                    <?= View::icon('video', 'icon', 18) ?>
                    <span>Join Session / View Meeting Link</span>
                </a>
                <a href="/booking/<?= View::e($booking['booking_ref']) ?>/success" class="btn btn-secondary btn-block">View Receipt &amp; Details</a>
            </div>

        <?php elseif ($hasPendingProof): ?>
            <!-- State 2: Client Submitted UTR, Waiting for Admin Verification -->
            <div class="pending-notice-card">
                <div class="flex items-center gap-2" style="color:#92400E;font-weight:700;font-size:0.95rem;margin-bottom:8px">
                    <?= View::icon('clock', 'icon', 18) ?>
                    Payment Submitted &mdash; Awaiting Admin Verification
                </div>
                <p style="color:#78350F;font-size:0.85rem;line-height:1.5;margin:0 0 12px">
                    We have received your payment details. Our admin team is verifying your transaction. Once verified, your booking will be confirmed and your instructor will attach your video meeting link.
                </p>
                <div style="background:#ffffff;border-radius:10px;padding:12px;font-size:0.82rem;margin-bottom:14px;border:1px solid #FCD34D">
                    <div><strong>Submitted UTR / Txn No:</strong> <code style="font-size:0.85rem;color:var(--color-primary,#1B4965)"><?= View::e($payment['gateway_txn_id']) ?></code></div>
                    <div style="margin-top:4px"><strong>Amount:</strong> <?= View::e($currency) ?> <?= number_format((float) $booking['total_amount'], 2) ?></div>
                    <?php if (!empty($payment['screenshot_path'])): ?>
                    <div style="margin-top:4px">
                        <strong>Screenshot:</strong> 
                        <a href="<?= View::e($payment['screenshot_path']) ?>" target="_blank" rel="noopener" style="color:var(--color-primary,#1B4965);text-decoration:underline">View Uploaded Image</a>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="flex items-center justify-between flex-wrap gap-2">
                    <a href="<?= View::e($waUrl) ?>" target="_blank" rel="noopener" class="wa-action-btn">
                        <span>💬 Send Screenshot on WhatsApp</span>
                    </a>
                    <button type="button" class="btn btn-ghost btn-sm" id="toggle-resubmit-btn" style="font-size:0.8rem">
                        Edit / Re-enter UTR
                    </button>
                </div>
            </div>

            <!-- Optional Collapsible Re-submission Form -->
            <div id="resubmit-form-container" style="display:none;margin-top:16px;padding:16px;background:var(--surface-bg-alt);border-radius:12px;border:1px solid var(--border-subtle)">
                <h4 style="margin:0 0 10px;font-size:0.9rem">Update Payment Reference</h4>
                <form id="upi-resubmit-form" enctype="multipart/form-data">
                    <input type="hidden" name="booking_ref" value="<?= View::e($booking['booking_ref']) ?>">
                    <div class="form-group" style="margin-bottom:10px">
                        <label class="form-group__label">Correct UTR / Transaction Reference Number</label>
                        <input type="text" name="utr_number" class="form-group__control" required value="<?= View::e($payment['gateway_txn_id']) ?>" placeholder="Enter 12-digit UTR">
                    </div>
                    <div class="form-group" style="margin-bottom:10px">
                        <label class="form-group__label">Attach New Screenshot (Optional)</label>
                        <input type="file" name="screenshot" accept="image/*" class="form-group__control" style="font-size:0.8rem;padding:6px">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm btn-block" id="resubmit-btn">Update Details</button>
                </form>
            </div>

            <div class="booking-summary__cta" style="margin-top:20px">
                <a href="/dashboard/bookings" class="btn btn-secondary btn-block">Go to My Bookings</a>
            </div>

        <?php else: ?>
            <!-- State 3: Pay via UPI QR Scan -->
            <div class="upi-pay-card">
                <div class="upi-payee-title"><?= View::e($upi['name']) ?></div>
                <div class="upi-id-pill">
                    <span id="upi-id-text"><?= View::e($upi['id']) ?></span>
                    <button type="button" class="copy-btn" id="copy-upi-btn" title="Copy UPI ID">Copy</button>
                </div>

                <div class="upi-qr-frame">
                    <img src="<?= View::e($upi['qr_image']) ?>" alt="UPI QR Code - Scan to Pay" id="upi-qr-img">
                </div>

                <div class="upi-amount-banner">
                    Amount to Pay: <strong><?= View::e($currency) ?> <?= number_format((float) $booking['total_amount'], 2) ?></strong>
                </div>

                <p class="text-muted" style="font-size:0.82rem;margin:0 0 18px">
                    Scan with <strong>Google Pay, PhonePe, Paytm, BHIM</strong>, or any UPI app.
                </p>

                <!-- 3 Easy Steps -->
                <div class="upi-step-box">
                    <div class="upi-step-badge">1</div>
                    <div>
                        <strong>Scan QR &amp; Pay:</strong> Open your UPI app, scan the photo above, and complete the payment of <strong><?= View::e($currency) ?> <?= number_format((float) $booking['total_amount'], 2) ?></strong>.
                    </div>
                </div>

                <div class="upi-step-box">
                    <div class="upi-step-badge">2</div>
                    <div>
                        <strong>Send Screenshot to WhatsApp:</strong> Send your payment screenshot to our helpline number <strong><?= View::e($upi['phone']) ?></strong>.
                        <div style="margin-top:8px">
                            <a href="<?= View::e($waUrl) ?>" target="_blank" rel="noopener" class="wa-action-btn">
                                <span>💬 Send Screenshot to WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="upi-step-box">
                    <div class="upi-step-badge">3</div>
                    <div>
                        <strong>Enter Transaction Number Below:</strong> Provide your UPI Reference / UTR Number so admin can verify and activate your session.
                    </div>
                </div>

                <!-- Proof Submission Form -->
                <form id="upi-proof-form" enctype="multipart/form-data" style="margin-top:20px;text-align:left">
                    <input type="hidden" name="booking_ref" value="<?= View::e($booking['booking_ref']) ?>">

                    <div class="form-group" style="margin-bottom:14px">
                        <label class="form-group__label" for="utr_number" style="font-weight:600;font-size:0.835rem">
                            UPI Transaction / UTR Number <span style="color:#ef4444">*</span>
                        </label>
                        <input type="text" id="utr_number" name="utr_number" class="form-group__control" required placeholder="e.g. 428938172938 (12-digit UTR)" style="font-size:0.9rem">
                        <small class="text-muted" style="font-size:0.75rem;display:block;margin-top:4px">
                            Found in your Google Pay / UPI receipt as 'UPI Ref No.', 'UTR', or 'Txn ID'.
                        </small>
                    </div>

                    <div class="form-group" style="margin-bottom:14px">
                        <label class="form-group__label" for="screenshot" style="font-weight:600;font-size:0.835rem">
                            Upload Payment Screenshot (Optional)
                        </label>
                        <input type="file" id="screenshot" name="screenshot" accept="image/jpeg,image/png,image/webp" class="form-group__control" style="font-size:0.8rem;padding:7px 10px">
                        <small class="text-muted" style="font-size:0.75rem;display:block;margin-top:4px">
                            JPG, PNG, or WebP screenshot of your payment receipt (max 10MB).
                        </small>
                    </div>

                    <div class="form-group" style="margin-bottom:18px">
                        <label class="form-group__label" for="payer_phone" style="font-weight:600;font-size:0.835rem">
                            Sender Phone / WhatsApp Number
                        </label>
                        <input type="tel" id="payer_phone" name="payer_phone" class="form-group__control" placeholder="Mobile number used to pay" value="<?= View::e($client['phone'] ?? '') ?>">
                    </div>

                    <button type="submit" class="btn btn-accent btn-block btn-lg" id="submit-proof-btn" style="border-radius:12px;font-weight:700">
                        <?= View::icon('check', 'icon', 18) ?>
                        <span>Submit Payment Proof</span>
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<script type="module">
import toast from '/assets/js/modules/toast.js';

const copyBtn = document.getElementById('copy-upi-btn');
const upiIdText = document.getElementById('upi-id-text');

if (copyBtn && upiIdText) {
    copyBtn.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(upiIdText.textContent.trim());
            copyBtn.textContent = 'Copied!';
            copyBtn.style.color = '#059669';
            setTimeout(() => {
                copyBtn.textContent = 'Copy';
                copyBtn.style.color = '';
            }, 2000);
            toast.success('UPI ID copied to clipboard!');
        } catch {
            toast.info('UPI ID: ' + upiIdText.textContent.trim());
        }
    });
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

async function handleProofSubmit(form, submitBtn) {
    submitBtn.classList.add('is-loading');
    submitBtn.disabled = true;

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
        submitBtn.classList.remove('is-loading');
        submitBtn.disabled = false;
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
