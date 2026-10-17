<?php

use App\Core\View;

$pageTitle = 'My Bookings — Vipasa Yoga';
$pageCss = 'trainer';
$portal = 'trainer';
$active = 'bookings';

$statusMeta = [
    'confirmed' => ['badge-confirmed', 'Confirmed'],
    'completed' => ['badge-completed', 'Completed'],
    'cancelled' => ['badge-cancelled', 'Cancelled'],
    'rescheduled' => ['badge-pending', 'Rescheduled'],
];
?>
<div class="container" style="padding-block:var(--space-4) var(--space-8)">

    <div class="trainer-hero-card mb-6">
        <div class="trainer-hero-card__main">
            <div class="trainer-hero-card__avatar">
                <span class="trainer-hero-card__avatar-fallback">
                    <?= View::icon('file-text', 'icon', 20) ?>
                </span>
            </div>
            <div>
                <h1 class="trainer-hero-card__title">Manage Bookings</h1>
                <p class="trainer-hero-card__subtitle">View your confirmed student bookings, health assessments, and attach meeting links.</p>
            </div>
        </div>
        <div class="trainer-hero-card__actions">
            <a href="/trainer/calendar" class="btn btn-secondary btn-sm flex items-center gap-1">
                <?= View::icon('calendar-week', 'icon', 14) ?>
                <span>Calendar</span>
            </a>
        </div>
    </div>

    <?php if (!empty($rescheduleRequests)): ?>
    <div class="trainer-section-block">
        <div class="trainer-section-header">
            <div class="flex items-center gap-2">
                <?= View::icon('clock', 'trainer-section-header__icon trainer-section-header__icon--warning', 18) ?>
                <h2 class="trainer-section-header__title">Reschedule Requests</h2>
            </div>
            <span class="trainer-count-pill trainer-count-pill--warning"><?= count($rescheduleRequests) ?></span>
        </div>
        <div class="trainer-schedule-list mb-6">
            <?php foreach ($rescheduleRequests as $reschedule): ?>
            <div class="trainer-schedule-card is-urgent" data-reschedule-row data-id="<?= (int) $reschedule['id'] ?>">
                <div class="trainer-schedule-card__left">
                    <div class="trainer-avatar-chip">
                        <?= View::e(strtoupper(substr($reschedule['client_name'] ?? 'C', 0, 2))) ?>
                    </div>
                    <div>
                        <h4 class="trainer-schedule-card__client-name"><?= View::e($reschedule['client_name']) ?></h4>
                        <div class="trainer-schedule-card__meta">
                            <span class="trainer-package-pill"><?= View::e($reschedule['package_name']) ?></span>
                            <span class="trainer-ref-tag">Ref: <?= View::e($reschedule['booking_ref']) ?></span>
                        </div>
                        <div class="mt-1" style="font-size:11px;color:var(--text-secondary)">
                            Current: <strong><?= View::e(date('d M Y, g:i A', strtotime($reschedule['slot_date'] . ' ' . $reschedule['start_time']))) ?></strong>
                            &rarr; Requested: <strong style="color:var(--color-primary)"><?= View::e(date('d M Y, g:i A', strtotime($reschedule['requested_slot_date'] . ' ' . $reschedule['requested_start_time']))) ?></strong>
                        </div>
                    </div>
                </div>
                <div class="trainer-schedule-card__actions flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm approve-reschedule-btn" data-id="<?= (int) $reschedule['id'] ?>">Approve</button>
                    <button type="button" class="btn btn-ghost btn-sm decline-reschedule-btn" data-id="<?= (int) $reschedule['id'] ?>">Decline</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="trainer-section-block">
        <div class="trainer-section-header">
            <div class="flex items-center gap-2">
                <?= View::icon('check', 'trainer-section-header__icon', 18) ?>
                <h2 class="trainer-section-header__title">Confirmed &amp; Completed Sessions</h2>
            </div>
            <span class="trainer-count-pill"><?= count($confirmed) ?></span>
        </div>
        <?php if (empty($confirmed)): ?>
        <div class="trainer-empty-state mb-6">
            <div class="trainer-empty-state__icon">
                <?= View::icon('calendar-month', 'icon', 20) ?>
            </div>
            <h3 class="trainer-empty-state__title">No confirmed sessions yet</h3>
            <p class="trainer-empty-state__text">Confirmed student bookings will show up here once approved.</p>
        </div>
        <?php else: ?>
        <div class="trainer-schedule-list mb-4" id="confirmed-list">
            <?php foreach ($confirmed as $booking):
                [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
            ?>
            <div class="trainer-schedule-card">
                <div class="trainer-schedule-card__left">
                    <div class="trainer-schedule-card__date-badge">
                        <?= View::icon('calendar-week', 'trainer-schedule-card__date-icon', 14) ?>
                        <span><?= View::e(date('d M Y', strtotime($booking['slot_date']))) ?></span>
                        <span class="trainer-schedule-card__dot">·</span>
                        <span class="trainer-schedule-card__time"><?= View::e(date('g:i A', strtotime($booking['start_time']))) ?></span>
                    </div>
                    <div class="trainer-schedule-card__user-info">
                        <div class="trainer-avatar-chip"><?= View::e(strtoupper(substr($booking['client_name'] ?? 'C', 0, 2))) ?></div>
                        <div>
                            <h4 class="trainer-schedule-card__client-name"><?= View::e($booking['client_name']) ?></h4>
                            <div class="trainer-schedule-card__meta flex items-center gap-2 flex-wrap" style="margin-top:2px">
                                <span class="trainer-package-pill"><?= View::e($booking['package']['name'] ?? 'Session') ?></span>
                                <span class="trainer-ref-tag">Ref: <?= View::e($booking['booking_ref']) ?></span>
                                <?php if (!empty($booking['client_email'])): ?>
                                <span class="text-muted" style="font-size:0.75rem"><?= View::e($booking['client_email']) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                <a href="/trainer/students/<?= (int) $booking['client_id'] ?>" class="btn btn-xs flex items-center gap-1" style="border-radius:var(--radius-lg);padding:5px 12px;font-size:0.78rem;font-weight:600;background:#F0FDF4;color:#15803D;border:1px solid #BBF7D0" title="View student medical notes & health assessment">
                                    <?= View::icon('shield', 'icon', 13) ?>
                                    <span>Health Assessment</span>
                                </a>
                                <a href="/trainer/sessions/<?= View::e($booking['booking_ref']) ?>" class="btn btn-primary btn-xs flex items-center gap-1" style="border-radius:var(--radius-lg);padding:5px 12px;font-size:0.78rem;font-weight:600;background:var(--vipasa-blue-600,#2563EB);color:#ffffff;border:none;box-shadow:0 2px 5px rgba(37,99,235,0.2)" title="Send Zoom / Google Meet link to student">
                                    <?= View::icon('video', 'icon', 13) ?>
                                    <span>Send Meeting Link</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="trainer-schedule-card__actions flex items-center gap-2 flex-wrap">
                    <span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span>
                    <?php if (!empty($booking['meeting_link']['url'])): ?>
                        <span class="badge" style="background:#ECFDF5;color:#047857;border:1px solid #A7F3D0;font-size:0.75rem">✓ Link Sent</span>
                    <?php else: ?>
                        <span class="badge" style="background:#FFFBEB;color:#B45309;border:1px solid #FDE68A;font-size:0.75rem">Link Pending</span>
                    <?php endif; ?>
                    <a href="/trainer/sessions/<?= View::e($booking['booking_ref']) ?>" class="btn btn-secondary btn-sm">Manage Session</a>
                    <?php if (\App\Core\Auth::hasRole('admin')): ?>
                    <button type="button" class="btn btn-ghost btn-sm delete-booking-btn" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Delete session" style="color:var(--color-error,#EF4444);padding:6px;border-radius:var(--radius-md)">
                        <?= View::icon('trash', 'icon', 15) ?>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="flex items-center justify-between flex-wrap gap-3 mt-3 mb-6" id="confirmed-pagination-wrapper" style="padding-inline:var(--space-2)">
            <div class="text-muted" style="font-size:0.85rem" id="confirmed-pagination-info">Showing 1 to 8 of <?= count($confirmed) ?> sessions</div>
            <div class="flex items-center gap-2" id="confirmed-pagination-controls">
                <button type="button" class="btn btn-secondary btn-sm" id="confirmed-prev-btn" disabled>&laquo; Previous</button>
                <span style="font-size:0.85rem;font-weight:600;padding-inline:8px" id="confirmed-page-indicator">Page 1</span>
                <button type="button" class="btn btn-secondary btn-sm" id="confirmed-next-btn">Next &raquo;</button>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($other)): ?>
    <div class="trainer-section-block">
        <div class="trainer-section-header">
            <div class="flex items-center gap-2">
                <?= View::icon('x', 'trainer-section-header__icon', 18) ?>
                <h2 class="trainer-section-header__title">Cancelled Sessions</h2>
            </div>
            <span class="trainer-count-pill"><?= count($other) ?></span>
        </div>
        <div class="trainer-schedule-list mb-4" id="cancelled-list">
            <?php foreach ($other as $booking):
                [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
            ?>
            <div class="trainer-schedule-card">
                <div class="trainer-schedule-card__left">
                    <div class="trainer-schedule-card__date-badge">
                        <?= View::icon('calendar-week', 'trainer-schedule-card__date-icon', 14) ?>
                        <span><?= View::e(date('d M Y', strtotime($booking['slot_date']))) ?></span>
                    </div>
                    <div class="trainer-schedule-card__user-info">
                        <div class="trainer-avatar-chip"><?= View::e(strtoupper(substr($booking['client_name'] ?? 'C', 0, 2))) ?></div>
                        <div>
                            <h4 class="trainer-schedule-card__client-name"><?= View::e($booking['client_name']) ?></h4>
                            <div class="trainer-schedule-card__meta">
                                <span class="trainer-package-pill"><?= View::e($booking['package']['name'] ?? 'Session') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span>
                    <?php if (\App\Core\Auth::hasRole('admin')): ?>
                    <button type="button" class="btn btn-ghost btn-sm delete-booking-btn" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Delete session" style="color:var(--color-error,#EF4444);padding:6px;border-radius:var(--radius-md)">
                        <?= View::icon('trash', 'icon', 15) ?>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="flex items-center justify-between flex-wrap gap-3 mt-3 mb-6" id="cancelled-pagination-wrapper" style="padding-inline:var(--space-2)">
            <div class="text-muted" style="font-size:0.85rem" id="cancelled-pagination-info">Showing 1 to 8 of <?= count($other) ?> sessions</div>
            <div class="flex items-center gap-2" id="cancelled-pagination-controls">
                <button type="button" class="btn btn-secondary btn-sm" id="cancelled-prev-btn" disabled>&laquo; Previous</button>
                <span style="font-size:0.85rem;font-weight:600;padding-inline:8px" id="cancelled-page-indicator">Page 1</span>
                <button type="button" class="btn btn-secondary btn-sm" id="cancelled-next-btn">Next &raquo;</button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

function setupListPagination(containerId, infoId, prevBtnId, nextBtnId, indicatorId, itemsPerPage = 8) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const cards = Array.from(container.children).filter(el => el.classList.contains('trainer-schedule-card'));
    if (cards.length === 0) return;

    let currentPage = 1;
    const totalPages = Math.ceil(cards.length / itemsPerPage);

    function render() {
        if (currentPage > totalPages) currentPage = Math.max(1, totalPages);
        const startIdx = (currentPage - 1) * itemsPerPage;
        const endIdx = startIdx + itemsPerPage;

        cards.forEach((card, index) => {
            card.style.display = (index >= startIdx && index < endIdx) ? '' : 'none';
        });

        const infoEl = document.getElementById(infoId);
        const prevBtn = document.getElementById(prevBtnId);
        const nextBtn = document.getElementById(nextBtnId);
        const indicatorEl = document.getElementById(indicatorId);

        if (infoEl) {
            const visibleCount = Math.min(endIdx, cards.length);
            infoEl.textContent = `Showing ${startIdx + 1} to ${visibleCount} of ${cards.length} items`;
        }

        if (indicatorEl) {
            indicatorEl.textContent = `Page ${currentPage} of ${Math.max(1, totalPages)}`;
        }

        if (prevBtn) prevBtn.disabled = currentPage <= 1;
        if (nextBtn) nextBtn.disabled = currentPage >= totalPages;

        const wrapper = document.getElementById(containerId.replace('-list', '-pagination-wrapper'));
        if (wrapper) {
            wrapper.style.display = cards.length <= itemsPerPage ? 'none' : 'flex';
        }
    }

    document.getElementById(prevBtnId)?.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            render();
        }
    });

    document.getElementById(nextBtnId)?.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            render();
        }
    });

    render();
}

setupListPagination('awaiting-list', 'awaiting-pagination-info', 'awaiting-prev-btn', 'awaiting-next-btn', 'awaiting-page-indicator', 8);
setupListPagination('confirmed-list', 'confirmed-pagination-info', 'confirmed-prev-btn', 'confirmed-next-btn', 'confirmed-page-indicator', 8);
setupListPagination('cancelled-list', 'cancelled-pagination-info', 'cancelled-prev-btn', 'cancelled-next-btn', 'cancelled-page-indicator', 8);

document.querySelectorAll('.accept-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        btn.classList.add('is-loading');
        btn.disabled = true;
        try {
            await apiPost(`/trainer/bookings/${btn.dataset.ref}/accept`, {});
            toast.success('Booking confirmed.');
            window.location.reload();
        } catch (err) {
            toast.error(err.message);
            btn.classList.remove('is-loading');
            btn.disabled = false;
        }
    });
});

document.querySelectorAll('.reject-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        btn.classList.add('is-loading');
        btn.disabled = true;
        try {
            await apiPost(`/trainer/bookings/${btn.dataset.ref}/reject`, {});
            toast.success('Booking rejected.');
            window.location.reload();
        } catch (err) {
            toast.error(err.message);
            btn.classList.remove('is-loading');
            btn.disabled = false;
        }
    });
});

document.querySelectorAll('.approve-reschedule-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        btn.classList.add('is-loading');
        btn.disabled = true;
        try {
            await apiPost(`/trainer/reschedule-requests/${btn.dataset.id}/approve`, {});
            toast.success('Reschedule approved.');
            window.location.reload();
        } catch (err) {
            toast.error(err.message);
            btn.classList.remove('is-loading');
            btn.disabled = false;
        }
    });
});

document.querySelectorAll('.decline-reschedule-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        btn.classList.add('is-loading');
        btn.disabled = true;
        try {
            await apiPost(`/trainer/reschedule-requests/${btn.dataset.id}/decline`, {});
            toast.success('Reschedule request declined.');
            window.location.reload();
        } catch (err) {
            toast.error(err.message);
            btn.classList.remove('is-loading');
            btn.disabled = false;
        }
    });
});

document.querySelectorAll('.delete-booking-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        btn.classList.add('is-loading');
        btn.disabled = true;
        try {
            await apiPost(`/trainer/bookings/${btn.dataset.ref}/delete`, {});
            toast.success('Booking deleted successfully.');
            window.location.reload();
        } catch (err) {
            toast.error(err.message || 'Failed to delete booking.');
            btn.classList.remove('is-loading');
            btn.disabled = false;
        }
    });
});
</script>
