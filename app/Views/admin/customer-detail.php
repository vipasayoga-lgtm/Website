<?php

use App\Core\View;

$pageTitle = $customer['name'] . ' — Customer Detail — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'admin';
$active = 'customers';

$statusMeta = [
    'pending_payment' => ['badge-pending', 'Pending payment'],
    'confirmed' => ['badge-confirmed', 'Confirmed'],
    'completed' => ['badge-completed', 'Completed'],
    'cancelled' => ['badge-cancelled', 'Cancelled'],
    'rescheduled' => ['badge-pending', 'Rescheduled'],
];

$paymentStatusMeta = [
    'pending' => ['badge-pending', 'Pending'],
    'success' => ['badge-confirmed', 'Success'],
    'failed' => ['badge-cancelled', 'Failed'],
    'refunded' => ['badge-cancelled', 'Refunded'],
];
?>
<div class="container" style="padding-block:var(--space-8) var(--space-16)">
    <p class="mb-4"><a href="/admin/customers">&larr; Back to Customers</a></p>

    <div class="card mb-8" style="padding:var(--space-6);max-width:820px;margin-inline:auto">
        <div class="flex items-center justify-between gap-4 mb-4 flex-wrap">
            <div class="flex items-center gap-4">
                <?php if (!empty($customer['avatar_path'])): ?>
                <img src="<?= View::e($customer['avatar_path']) ?>" alt="" style="width:64px;height:64px;border-radius:50%;object-fit:cover">
                <?php endif; ?>
                <div>
                    <div style="font-weight:var(--font-weight-semibold);font-size:var(--font-size-lg)"><?= View::e($customer['name']) ?></div>
                    <div class="text-muted" style="font-size:var(--font-size-sm)"><?= View::e($customer['email'] ?: '—') ?> · <?= View::e($customer['phone'] ?: '—') ?></div>
                </div>
            </div>
            <a href="/admin/customers/<?= (int) $customer['id'] ?>/health-record" class="btn btn-accent btn-sm">View Health Record</a>
        </div>
        <?php if (!empty($customer['bio'])): ?>
        <p class="text-muted mb-3" style="font-size:var(--font-size-sm)"><?= View::e($customer['bio']) ?></p>
        <?php endif; ?>
        <p class="text-muted" style="font-size:var(--font-size-sm)">
            Age: <?= View::e((string) ($customer['age'] ?? '—')) ?>
            · Gender: <?= View::e($customer['gender'] ?? '—') ?>
            · Category: <?= View::e($customer['client_category'] ? ucwords(str_replace('_', ' ', $customer['client_category'])) : '—') ?>
            · City: <?= View::e($customer['city'] ?: '—') ?>
            · Country: <?= View::e($customer['country'] ?: '—') ?>
            · Timezone: <?= View::e($customer['timezone'] ?: '—') ?>
        </p>
        <p class="text-muted" style="font-size:var(--font-size-sm);margin-top:var(--space-2)">
            Height: <?= View::e($customer['height_cm'] ? $customer['height_cm'] . ' cm' : '—') ?>
            · Weight: <?= View::e($customer['weight_kg'] ? $customer['weight_kg'] . ' kg' : '—') ?>
            · Emergency contact: <?= View::e($customer['emergency_contact_name'] ?: '—') ?><?= $customer['emergency_contact_phone'] ? ' (' . View::e($customer['emergency_contact_phone']) . ')' : '' ?>
            <?= $customer['emergency_contact_relationship'] ? ' · ' . View::e($customer['emergency_contact_relationship']) : '' ?>
        </p>
        <p class="text-muted" style="font-size:var(--font-size-sm);margin-top:var(--space-2)">
            Assessment: <?= ($stage ?? 0) >= 5 ? 'complete' : 'stage ' . (int) ($stage ?? 0) . ' of 5' ?>
            · Profile <?= (int) ($completionPercent ?? 0) ?>% complete
            · <?= !empty($customer['onboarding_completed_at']) ? 'Onboarded ' . date('d M Y', strtotime($customer['onboarding_completed_at'])) : 'Onboarding not finished' ?>
        </p>
        <?php if (!empty($customer['medical_notes'])): ?>
        <p class="text-muted" style="font-size:var(--font-size-sm);margin-top:var(--space-2)">
            <strong>Notes:</strong> <?= View::e($customer['medical_notes']) ?>
        </p>
        <?php endif; ?>
    </div>

    <div style="max-width:820px;margin-inline:auto">
        <?= View::partial('partials/assessment-answers', [
            'assessment' => $customer['health_assessment'] ?? null,
            'heading' => 'Questions & answers',
        ]) ?>
    </div>

    <h2 class="mb-4" style="max-width:820px;margin-inline:auto">Booking History</h2>
    <?php if (empty($bookings)): ?>
    <p class="text-muted text-center mb-8">No bookings for this customer.</p>
    <?php else: ?>
    <div class="flex flex-col gap-4 mb-8" style="max-width:820px;margin-inline:auto">
        <?php foreach ($bookings as $booking):
            [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
            $dateLabel = date('d M Y', strtotime($booking['slot_date']));
            $timeLabel = date('g:i A', strtotime($booking['start_time']));
        ?>
        <div class="card" style="padding:var(--space-5)">
            <div class="flex items-center justify-between mb-2">
                <div style="font-weight:var(--font-weight-semibold)"><?= View::e($booking['package_name']) ?></div>
                <span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span>
            </div>
            <p class="text-muted" style="font-size:var(--font-size-sm)">
                With <?= View::e($booking['instructor_name']) ?> · <?= View::e($dateLabel) ?> at <?= View::e($timeLabel) ?>
                · <?= View::e($booking['currency']) ?> <?= number_format((float) $booking['total_amount'], 2) ?> · Ref <?= View::e($booking['booking_ref']) ?>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <h2 class="mb-4" style="max-width:820px;margin-inline:auto">Payment History</h2>
    <?php if (empty($payments)): ?>
    <p class="text-muted text-center" style="max-width:820px;margin-inline:auto">No payments for this customer.</p>
    <?php else: ?>
    <div class="flex flex-col gap-4" style="max-width:820px;margin-inline:auto">
        <?php foreach ($payments as $payment):
            [$pBadgeClass, $pBadgeLabel] = $paymentStatusMeta[$payment['status']] ?? ['badge-neutral', ucfirst($payment['status'])];
        ?>
        <div class="card" style="padding:var(--space-5)">
            <div class="flex items-center justify-between mb-2">
                <div style="font-weight:var(--font-weight-semibold)"><?= View::e($payment['currency']) ?> <?= number_format((float) $payment['amount'], 2) ?></div>
                <span class="badge <?= $pBadgeClass ?>"><?= View::e($pBadgeLabel) ?></span>
            </div>
            <p class="text-muted" style="font-size:var(--font-size-sm)">
                <?= View::e(ucfirst($payment['gateway'])) ?> · Ref <?= View::e($payment['booking_ref']) ?> · <?= View::e(date('d M Y, g:i A', strtotime($payment['created_at']))) ?>
            </p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
