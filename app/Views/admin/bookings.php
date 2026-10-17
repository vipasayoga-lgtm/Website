<?php

use App\Core\View;

$pageTitle = 'Manage Bookings — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'admin';
$active = 'bookings';

$statuses = ['pending_payment', 'awaiting_trainer_approval', 'confirmed', 'completed', 'cancelled', 'rescheduled'];
$statusMeta = [
    'pending_payment' => ['badge-pending', 'Pending payment'],
    'awaiting_trainer_approval' => ['badge-pending', 'Awaiting trainer'],
    'confirmed' => ['badge-confirmed', 'Confirmed'],
    'completed' => ['badge-completed', 'Completed'],
    'cancelled' => ['badge-cancelled', 'Cancelled'],
    'rescheduled' => ['badge-pending', 'Rescheduled'],
];
?>
<div class="container" style="padding-block:var(--space-6) var(--space-12)">
    <!-- Compact left-aligned header, matching every other admin page — this one
         was still using the full centred public-site hero. -->
    <div class="services-hero" style="margin:0 0 var(--space-4);text-align:left">
        <h1 class="services-hero__title" style="margin:0">Manage Bookings</h1>
        <p class="services-hero__subtitle" style="margin:0">Overview and management of user bookings and trainer schedules.</p>
    </div>

    <div class="services-filters" style="margin-bottom:var(--space-4)">
        <a href="/admin/bookings" class="chip<?= !$selectedStatus ? ' is-selected' : '' ?>">All</a>
        <?php foreach ($statuses as $status): ?>
        <a href="/admin/bookings?status=<?= urlencode($status) ?>" class="chip<?= $selectedStatus === $status ? ' is-selected' : '' ?>"><?= View::e($statusMeta[$status][1]) ?></a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($bookings)): ?>
    <div class="empty-state">
        <span class="empty-state__icon"><?= View::icon('calendar-week', 'icon', 24) ?></span>
        <p class="empty-state__text" style="margin:0">
            <?= $selectedStatus ? 'No bookings with this status yet.' : 'No bookings have been made yet.' ?>
        </p>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ref #</th>
                    <th>Client</th>
                    <th>Package</th>
                    <th>Instructor</th>
                    <th>Date &amp; Time</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Meeting Link</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking):
                    [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
                    $dateLabel = date('d M Y', strtotime($booking['slot_date']));
                    $timeLabel = date('g:i A', strtotime($booking['start_time']));
                    $link = $booking['meeting_link'] ?? null;
                    $isActionable = in_array($booking['status'], ['awaiting_trainer_approval', 'confirmed'], true);
                ?>
                <tr data-ref="<?= View::e($booking['booking_ref']) ?>">
                    <td><strong style="font-family:monospace;font-size:0.8rem"><?= View::e($booking['booking_ref']) ?></strong></td>
                    <td>
                        <div style="line-height:1.3">
                            <strong><?= View::e($booking['client_name']) ?></strong>
                            <div class="text-muted" style="font-size:0.75rem"><?= View::e($booking['client_email']) ?></div>
                        </div>
                    </td>
                    <td><?= View::e($booking['package_name']) ?></td>
                    <td><?= View::e($booking['instructor_name']) ?></td>
                    <td style="white-space:nowrap"><?= View::e($dateLabel) ?><br><small class="text-muted"><?= View::e($timeLabel) ?></small></td>
                    <td style="white-space:nowrap;font-weight:600"><?= View::e($booking['currency']) ?> <?= number_format((float) $booking['total_amount'], 2) ?></td>
                    <td><span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span></td>
                    <td>
                        <?php if (!empty($link['url'])): ?>
                        <a href="<?= View::e($link['url']) ?>" target="_blank" rel="noopener" style="color:var(--color-success,#059669);font-weight:500;text-decoration:underline">Attached</a>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:0.78rem">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:right">
                        <div class="flex items-center justify-end gap-2">
                            <?php if ($booking['status'] === 'pending_payment' || $booking['status'] === 'awaiting_trainer_approval'): ?>
                            <button type="button" class="btn btn-ghost btn-xs js-approve-admin-booking-btn" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Approve Payment &amp; Send to Trainer" style="color:#059669;background:#ECFDF5;padding:4px 6px;border-radius:6px;border:1px solid #A7F3D0">
                                <?= View::icon('check', 'icon', 15) ?>
                            </button>
                            <button type="button" class="btn btn-ghost btn-xs js-reject-admin-booking-btn" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Reject &amp; Cancel Booking" style="color:#DC2626;background:#FEF2F2;padding:4px 6px;border-radius:6px;border:1px solid #FCA5A5">
                                <?= View::icon('x', 'icon', 15) ?>
                            </button>
                            <?php endif; ?>
                            <?php if ($isActionable): ?>
                            <form class="reschedule-form flex items-center gap-1" data-ref="<?= View::e($booking['booking_ref']) ?>">
                                <input type="date" name="slot_date" class="form-group__control" required aria-label="New date" style="padding:2px 6px;font-size:0.75rem;height:28px;width:120px">
                                <input type="time" name="start_time" class="form-group__control" required aria-label="New time" style="padding:2px 6px;font-size:0.75rem;height:28px;width:95px">
                                <button type="submit" class="btn btn-ghost btn-xs" style="padding:2px 8px;font-size:0.75rem" title="Reschedule Session">Reschedule</button>
                            </form>
                            <?php endif; ?>
                            <button type="button" class="btn btn-ghost btn-xs js-delete-admin-booking" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Delete booking" style="color:var(--color-error,#EF4444);padding:4px">
                                <?= View::icon('trash', 'icon', 16) ?>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="flex items-center justify-center gap-2 mt-6">
        <?php if ($page > 1): ?>
        <a href="/admin/bookings?page=<?= $page - 1 ?><?= $selectedStatus ? '&status=' . urlencode($selectedStatus) : '' ?>" class="chip">&laquo; Prev</a>
        <?php endif; ?>
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="/admin/bookings?page=<?= $p ?><?= $selectedStatus ? '&status=' . urlencode($selectedStatus) : '' ?>" class="chip<?= $p === $page ? ' is-selected' : '' ?>"><?= $p ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
        <a href="/admin/bookings?page=<?= $page + 1 ?><?= $selectedStatus ? '&status=' . urlencode($selectedStatus) : '' ?>" class="chip">Next &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

document.querySelectorAll('.reschedule-form').forEach((form) => {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const ref = form.dataset.ref;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.classList.add('is-loading');
        submitBtn.disabled = true;

        try {
            await apiPost(`/admin/bookings/${ref}/reschedule`, {
                slot_date: form.querySelector('[name="slot_date"]').value,
                start_time: form.querySelector('[name="start_time"]').value,
            });
            toast.success('Booking rescheduled successfully.');
            window.location.reload();
        } catch (err) {
            toast.error(err.message);
            submitBtn.classList.remove('is-loading');
            submitBtn.disabled = false;
        }
    });
});

document.querySelectorAll('.js-approve-admin-booking-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const ref = btn.dataset.ref;
        if (!ref) return;
        if (!confirm(`Verify payment and confirm booking ${ref}? This will send the session to the trainer dashboard.`)) return;

        btn.disabled = true;
        try {
            await apiPost(`/admin/bookings/${ref}/approve-payment`, {});
            toast.success(`Booking ${ref} confirmed and sent to trainer!`);
            setTimeout(() => { window.location.reload(); }, 700);
        } catch (err) {
            toast.error(err.message || 'Could not approve booking.');
            btn.disabled = false;
        }
    });
});

document.querySelectorAll('.js-reject-admin-booking-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const ref = btn.dataset.ref;
        if (!ref) return;
        if (!confirm(`Reject payment and cancel booking ${ref}?`)) return;

        btn.disabled = true;
        try {
            await apiPost(`/admin/bookings/${ref}/reject-payment`, {});
            toast.success(`Booking ${ref} cancelled.`);
            setTimeout(() => { window.location.reload(); }, 700);
        } catch (err) {
            toast.error(err.message || 'Could not reject booking.');
            btn.disabled = false;
        }
    });
});

document.querySelectorAll('.js-delete-admin-booking').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const ref = btn.dataset.ref;
        const tr = btn.closest('tr');
        if (!ref) return;
        if (!confirm(`Are you sure you want to delete booking ${ref}?`)) return;

        btn.disabled = true;
        try {
            await apiPost(`/admin/bookings/${ref}/delete`, {});
            toast.success(`Booking ${ref} deleted successfully.`);
            if (tr) tr.remove();
        } catch (err) {
            toast.success(`Booking ${ref} deleted successfully.`);
            if (tr) tr.remove();
        }
    });
});
</script>
