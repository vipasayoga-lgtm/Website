<?php

use App\Core\View;

$pageTitle = 'Trainer Dashboard — Vipasa Yoga';
$pageCss = 'trainer';
$portal = 'trainer';
$active = 'dashboard';

$renderScheduleRow = static function (array $booking, bool $isUrgent = false): void {
    $timeLabel = date('g:i A', strtotime($booking['start_time']));
    $dateLabel = date('d M Y', strtotime($booking['slot_date']));
    $initials = strtoupper(substr($booking['client_name'] ?? 'C', 0, 2));
    $hasMeeting = !empty($booking['meeting_url']);
    ?>
    <div class="trainer-schedule-card <?= $isUrgent ? 'is-urgent' : '' ?>" id="trainer-sched-card-<?= View::e($booking['booking_ref']) ?>">
        <div class="trainer-schedule-card__left">
            <div class="trainer-schedule-card__date-badge">
                <?= View::icon('calendar-week', 'trainer-schedule-card__date-icon', 16) ?>
                <span><?= View::e($dateLabel) ?></span>
                <span class="trainer-schedule-card__dot">·</span>
                <span class="trainer-schedule-card__time"><?= View::e($timeLabel) ?></span>
            </div>
            <div class="trainer-schedule-card__user-info">
                <div class="trainer-avatar-chip"><?= View::e($initials) ?></div>
                <div>
                    <h4 class="trainer-schedule-card__client-name"><?= View::e($booking['client_name']) ?></h4>
                    <div class="trainer-schedule-card__meta flex items-center gap-2 flex-wrap">
                        <span class="trainer-package-pill"><?= View::e($booking['package_name']) ?></span>
                        <span class="trainer-ref-tag">Ref: <?= View::e($booking['booking_ref']) ?></span>
                        <?php if ($booking['status'] === 'confirmed'): ?>
                            <?php if ($hasMeeting): ?>
                            <span class="badge badge-confirmed" style="font-size:0.75rem;padding:2px 8px" title="<?= View::e($booking['meeting_url']) ?>">✓ Meeting Link Sent</span>
                            <?php else: ?>
                            <span class="badge badge-pending" style="font-size:0.75rem;padding:2px 8px;background:#FEF3C7;color:#92400E">⚠️ Link Needed</span>
                            <?php endif; ?>
                        <?php elseif ($booking['status'] === 'completed'): ?>
                            <span class="badge badge-completed" style="font-size:0.75rem;padding:2px 8px">Completed</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="trainer-schedule-card__actions flex items-center gap-2 flex-wrap">
            <?php if ($booking['status'] === 'awaiting_trainer_approval'): ?>
                <a href="/trainer/bookings" class="btn btn-primary btn-sm flex items-center gap-1">
                    <?= View::icon('check', 'icon', 14) ?>
                    <span>Review Booking</span>
                </a>
            <?php elseif ($booking['status'] === 'confirmed'): ?>
                <button type="button" class="btn btn-sm js-send-meeting-link-modal-btn <?= $hasMeeting ? 'btn-secondary' : 'btn-primary' ?>" data-ref="<?= View::e($booking['booking_ref']) ?>" data-client="<?= View::e($booking['client_name']) ?>" data-url="<?= View::e($booking['meeting_url'] ?? '') ?>" data-provider="<?= View::e($booking['meeting_provider'] ?? 'google_meet') ?>" style="<?= $hasMeeting ? '' : 'background:#0D9488;border:none' ?>">
                    <?= View::icon('video', 'icon', 14) ?>
                    <span><?= $hasMeeting ? 'Edit Link' : 'Send Meeting Link' ?></span>
                </button>
                <button type="button" class="btn btn-sm btn-ghost js-trainer-card-complete-btn" data-ref="<?= View::e($booking['booking_ref']) ?>" style="color:#059669;font-weight:600;font-size:0.8rem">
                    ✓ Mark Finished
                </button>
                <a href="/trainer/sessions/<?= View::e($booking['booking_ref']) ?>" class="btn btn-ghost btn-sm" title="Session Notes & Details">
                    Notes
                </a>
            <?php else: ?>
                <a href="/trainer/sessions/<?= View::e($booking['booking_ref']) ?>" class="btn btn-secondary btn-sm flex items-center gap-1">
                    <?= View::icon('file-text', 'icon', 14) ?>
                    <span>Session Notes</span>
                </a>
            <?php endif; ?>
            <?php if (\App\Core\Auth::hasRole('admin')): ?>
            <button type="button" class="btn btn-ghost btn-sm delete-dashboard-booking-btn" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Delete session" style="color:var(--color-error,#EF4444);padding:6px;border-radius:var(--radius-md)">
                <?= View::icon('trash', 'icon', 15) ?>
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php
};
?>
<div class="container" style="padding-block:var(--space-6) var(--space-12)">

    <!-- Hero Header -->
    <div class="trainer-hero-card">
        <div class="trainer-hero-card__main">
            <div class="trainer-hero-card__avatar">
                <?php if (!empty($instructor['avatar_path'])): ?>
                    <img src="<?= View::e($instructor['avatar_path']) ?>" alt="<?= View::e($instructor['name'] ?? 'Trainer') ?>">
                <?php else: ?>
                    <span class="trainer-hero-card__avatar-fallback"><?= View::e(strtoupper(substr($instructor['name'] ?? 'T', 0, 2))) ?></span>
                <?php endif; ?>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="trainer-hero-card__title">Welcome back, <?= View::e(explode(' ', $instructor['name'] ?? '')[0] ?? 'Trainer') ?></h1>
                    <span class="trainer-live-chip">
                        <span class="trainer-live-chip__pulse"></span>
                        Active Portal
                    </span>
                </div>
                <p class="trainer-hero-card__subtitle">Here is your real-time practice schedule, student activity, and performance summary for <?= date('F j, Y') ?>.</p>
            </div>
        </div>
        <div class="trainer-hero-card__actions">
            <a href="/trainer/calendar" class="btn btn-secondary btn-sm flex items-center gap-1">
                <?= View::icon('calendar-month', 'icon', 15) ?>
                <span>Availability</span>
            </a>
            <a href="/trainer/profile" class="btn btn-ghost btn-sm flex items-center gap-1">
                <?= View::icon('user', 'icon', 15) ?>
                <span>Profile</span>
            </a>
        </div>
    </div>

    <!-- Stat Grid -->
    <div class="trainer-stat-grid">
        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--primary">
                <?= View::icon('calendar-week', 'icon', 20) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value"><?= count($todaysClasses) ?></div>
                <div class="trainer-stat-card__label">Today's Classes</div>
            </div>
        </div>

        <div class="trainer-stat-card <?= !empty($awaitingApproval) ? 'is-warning' : '' ?>">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--warning">
                <?= View::icon('clock', 'icon', 20) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value"><?= count($awaitingApproval) ?></div>
                <div class="trainer-stat-card__label">Awaiting Approval</div>
            </div>
            <?php if (!empty($awaitingApproval)): ?>
                <span class="trainer-stat-card__badge">Action Req.</span>
            <?php endif; ?>
        </div>

        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--info">
                <?= View::icon('users', 'icon', 20) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value"><?= (int) $totalStudents ?></div>
                <div class="trainer-stat-card__label">Total Students</div>
            </div>
        </div>

        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--success">
                <?= View::icon('check', 'icon', 20) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value"><?= (int) $completedCount ?></div>
                <div class="trainer-stat-card__label">Completed Sessions</div>
            </div>
        </div>

        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--accent">
                <?= View::icon('wallet', 'icon', 20) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value">₹<?= number_format($totalEarnings, 0) ?></div>
                <div class="trainer-stat-card__label">Total Earnings</div>
            </div>
        </div>

        <div class="trainer-stat-card <?= (int) $medicalAlertCount > 0 ? 'is-danger' : '' ?>">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--danger">
                <?= View::icon('shield', 'icon', 20) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value"><?= (int) $medicalAlertCount ?></div>
                <div class="trainer-stat-card__label">Medical Alerts</div>
            </div>
        </div>

        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--purple">
                <?= View::icon('file-text', 'icon', 20) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value"><?= (int) $pendingNotesCount ?></div>
                <div class="trainer-stat-card__label">Pending Notes</div>
            </div>
        </div>

        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--teal">
                <?= View::icon('chart-bar', 'icon', 20) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value"><?= $attendanceRate !== null ? $attendanceRate . '%' : '—' ?></div>
                <div class="trainer-stat-card__label">Attendance Rate</div>
            </div>
        </div>
    </div>

    <!-- Main Content Layout (70% Schedule / 30% Sidebar Widgets) -->
    <div class="trainer-dashboard-grid">
        <div class="trainer-dashboard-grid__main">

            <?php if (!empty($awaitingApproval)): ?>
            <div class="trainer-section-block">
                <div class="trainer-section-header">
                    <div class="flex items-center gap-2">
                        <?= View::icon('clock', 'trainer-section-header__icon trainer-section-header__icon--warning', 20) ?>
                        <h2 class="trainer-section-header__title">Awaiting Your Approval</h2>
                    </div>
                    <span class="trainer-count-pill trainer-count-pill--warning"><?= count($awaitingApproval) ?></span>
                </div>
                <div class="trainer-schedule-list">
                    <?php foreach ($awaitingApproval as $booking): ?>
                        <?php $renderScheduleRow($booking, true); ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="trainer-section-block">
                <div class="trainer-section-header">
                    <div class="flex items-center gap-2">
                        <?= View::icon('calendar-week', 'trainer-section-header__icon', 20) ?>
                        <h2 class="trainer-section-header__title">Today's Scheduled Classes</h2>
                    </div>
                    <span class="trainer-count-pill"><?= count($todaysClasses) ?></span>
                </div>
                <?php if (empty($todaysClasses)): ?>
                    <div class="trainer-empty-state">
                        <div class="trainer-empty-state__icon">
                            <?= View::icon('calendar-month', 'icon', 24) ?>
                        </div>
                        <h3 class="trainer-empty-state__title">No classes scheduled for today</h3>
                        <p class="trainer-empty-state__text">Take time to rest &amp; prepare for upcoming student bookings.</p>
                        <a href="/trainer/calendar" class="btn btn-secondary btn-sm mt-2">Manage Availability</a>
                    </div>
                <?php else: ?>
                    <div class="trainer-schedule-list" id="todays-list">
                        <?php foreach ($todaysClasses as $booking): ?>
                            <?php $renderScheduleRow($booking); ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="flex items-center justify-between flex-wrap gap-3 mt-3 mb-4" id="todays-pagination-wrapper" style="padding-inline:var(--space-2)">
                        <div class="text-muted" style="font-size:0.85rem" id="todays-pagination-info">Showing 1 to 8 of <?= count($todaysClasses) ?> items</div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="btn btn-secondary btn-sm" id="todays-prev-btn" disabled>&laquo; Previous</button>
                            <span style="font-size:0.85rem;font-weight:600;padding-inline:8px" id="todays-page-indicator">Page 1</span>
                            <button type="button" class="btn btn-secondary btn-sm" id="todays-next-btn">Next &raquo;</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="trainer-section-block">
                <div class="trainer-section-header">
                    <div class="flex items-center gap-2">
                        <?= View::icon('clock', 'trainer-section-header__icon', 20) ?>
                        <h2 class="trainer-section-header__title">Upcoming Confirmed Sessions</h2>
                    </div>
                    <span class="trainer-count-pill"><?= count($upcoming) ?></span>
                </div>
                <?php if (empty($upcoming)): ?>
                    <div class="trainer-empty-state">
                        <div class="trainer-empty-state__icon">
                            <?= View::icon('users', 'icon', 24) ?>
                        </div>
                        <h3 class="trainer-empty-state__title">No upcoming sessions right now</h3>
                        <p class="trainer-empty-state__text">Confirmed student bookings for upcoming dates will appear here.</p>
                    </div>
                <?php else: ?>
                    <div class="trainer-schedule-list" id="upcoming-list">
                        <?php foreach ($upcoming as $booking): ?>
                            <?php $renderScheduleRow($booking); ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="flex items-center justify-between flex-wrap gap-3 mt-3 mb-4" id="upcoming-pagination-wrapper" style="padding-inline:var(--space-2)">
                        <div class="text-muted" style="font-size:0.85rem" id="upcoming-pagination-info">Showing 1 to 8 of <?= count($upcoming) ?> items</div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="btn btn-secondary btn-sm" id="upcoming-prev-btn" disabled>&laquo; Previous</button>
                            <span style="font-size:0.85rem;font-weight:600;padding-inline:8px" id="upcoming-page-indicator">Page 1</span>
                            <button type="button" class="btn btn-secondary btn-sm" id="upcoming-next-btn">Next &raquo;</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Sidebar Widgets -->
        <aside class="trainer-dashboard-grid__sidebar flex flex-col gap-5">

            <!-- Quick Actions Card -->
            <div class="card trainer-widget-card">
                <div class="trainer-widget-card__header">
                    <h3 class="trainer-widget-card__title">
                        <?= View::icon('sliders', 'icon', 18) ?>
                        Quick Actions
                    </h3>
                </div>
                <div class="trainer-widget-card__body flex flex-col gap-2">
                    <a href="/trainer/calendar" class="btn btn-secondary btn-block justify-start gap-2">
                        <?= View::icon('calendar-week', 'icon', 16) ?>
                        Set Weekly Slots
                    </a>
                    <a href="/trainer/bookings" class="btn btn-ghost btn-block justify-start gap-2">
                        <?= View::icon('file-text', 'icon', 16) ?>
                        Manage Bookings
                    </a>
                    <a href="/trainer/students" class="btn btn-ghost btn-block justify-start gap-2">
                        <?= View::icon('users', 'icon', 16) ?>
                        Student Directory
                    </a>
                    <a href="/trainer/earnings" class="btn btn-ghost btn-block justify-start gap-2">
                        <?= View::icon('wallet', 'icon', 16) ?>
                        View Earnings
                    </a>
                </div>
            </div>

            <!-- Instructor Studio Tips Card -->
            <div class="card trainer-widget-card trainer-widget-card--gradient">
                <div class="trainer-widget-card__header">
                    <h3 class="trainer-widget-card__title">
                        <?= View::icon('heart', 'icon', 18) ?>
                        Studio Best Practices
                    </h3>
                </div>
                <div class="trainer-widget-card__body">
                    <ul class="trainer-tips-list">
                        <li>Review medical notes &amp; health assessments before live sessions.</li>
                        <li>Submit session notes promptly to keep student progress updated.</li>
                        <li>Keep recurring weekly availability accurate for continuous bookings.</li>
                    </ul>
                </div>
            </div>

        </aside>
    </div>

</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

function setupDashboardPagination(containerId, infoId, prevBtnId, nextBtnId, indicatorId, wrapperId, itemsPerPage = 8) {
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
        const wrapper = document.getElementById(wrapperId);

        if (infoEl) {
            const visibleCount = Math.min(endIdx, cards.length);
            infoEl.textContent = `Showing ${startIdx + 1} to ${visibleCount} of ${cards.length} items`;
        }

        if (indicatorEl) {
            indicatorEl.textContent = `Page ${currentPage} of ${Math.max(1, totalPages)}`;
        }

        if (prevBtn) prevBtn.disabled = currentPage <= 1;
        if (nextBtn) nextBtn.disabled = currentPage >= totalPages;

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

setupDashboardPagination('todays-list', 'todays-pagination-info', 'todays-prev-btn', 'todays-next-btn', 'todays-page-indicator', 'todays-pagination-wrapper', 8);
setupDashboardPagination('upcoming-list', 'upcoming-pagination-info', 'upcoming-prev-btn', 'upcoming-next-btn', 'upcoming-page-indicator', 'upcoming-pagination-wrapper', 8);

document.querySelectorAll('.delete-dashboard-booking-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        btn.classList.add('is-loading');
        btn.disabled = true;
        try {
            await apiPost(`/trainer/bookings/${btn.dataset.ref}/delete`, {});
            toast.success('Session deleted.');
            window.location.reload();
        } catch (err) {
            toast.error(err.message || 'Failed to delete session.');
            btn.classList.remove('is-loading');
            btn.disabled = false;
        }
    });
});

// Quick Send Meeting Link Modal Logic
const meetingModal = document.getElementById('trainer-meeting-link-modal');
const quickForm = document.getElementById('trainer-quick-meeting-form');
const quickRef = document.getElementById('quick-meeting-ref');
const quickUrl = document.getElementById('quick-meeting-url');
const quickProvider = document.getElementById('quick-meeting-provider');
const clientLabel = document.getElementById('meeting-modal-client-label');
const submitBtn = document.getElementById('submit-quick-meeting-btn');

document.querySelectorAll('.js-send-meeting-link-modal-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
        const ref = btn.dataset.ref;
        const client = btn.dataset.client || 'Student';
        const url = btn.dataset.url || '';
        const provider = btn.dataset.provider || 'google_meet';

        quickRef.value = ref;
        clientLabel.textContent = `For session with ${client} (${ref})`;
        quickUrl.value = url;
        quickProvider.value = provider;

        meetingModal.style.display = 'grid';
    });
});

document.getElementById('close-meeting-link-modal')?.addEventListener('click', () => {
    meetingModal.style.display = 'none';
});
document.getElementById('cancel-meeting-link-modal')?.addEventListener('click', () => {
    meetingModal.style.display = 'none';
});

quickForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const ref = quickRef.value;
    if (!ref) return;

    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';

    try {
        await apiPost(`/trainer/sessions/${ref}/meeting-link`, {
            provider: quickProvider.value,
            url: quickUrl.value.trim(),
        });
        toast.success(`Meeting link sent to student! Student notified.`);
        meetingModal.style.display = 'none';
        setTimeout(() => window.location.reload(), 600);
    } catch (err) {
        toast.error(err.message || 'Failed to send meeting link.');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Link to Student';
    }
});

// Quick Mark Finished on Trainer Dashboard
document.querySelectorAll('.js-trainer-card-complete-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const ref = btn.dataset.ref;
        if (!ref) return;
        if (!confirm(`Mark session ${ref} as finished? This will complete the session and prompt the student for feedback.`)) return;

        btn.disabled = true;
        btn.textContent = 'Finishing...';

        try {
            await apiPost(`/trainer/sessions/${ref}/complete`, {});
            toast.success(`Session ${ref} marked as completed! Feedback requested from student.`);
            setTimeout(() => window.location.reload(), 600);
        } catch (err) {
            toast.error(err.message || 'Failed to complete session.');
            btn.disabled = false;
            btn.textContent = '✓ Mark Finished';
        }
    });
});
</script>

<!-- Quick Send Meeting Link Modal -->
<div id="trainer-meeting-link-modal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.65);backdrop-filter:blur(4px);z-index:9999;place-items:center;padding:16px">
    <div style="background:#fff;border-radius:20px;padding:26px 30px;width:100%;max-width:440px;box-shadow:0 25px 50px -12px rgba(15,23,42,0.25);position:relative;margin:auto">
        <button type="button" id="close-meeting-link-modal" style="position:absolute;top:18px;right:18px;background:none;border:none;font-size:1.4rem;color:#94A3B8;cursor:pointer;line-height:1" aria-label="Close">&times;</button>
        <h3 style="margin:0 0 4px 0;font-size:1.2rem;font-weight:700;color:#0F172A">Send Video Meeting Link</h3>
        <p class="text-muted" id="meeting-modal-client-label" style="font-size:0.85rem;margin:0 0 18px 0">For session with Student</p>
        <form id="trainer-quick-meeting-form">
            <input type="hidden" name="booking_ref" id="quick-meeting-ref">
            <div class="form-group" style="margin-bottom:14px">
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#334155;margin-bottom:6px">Meeting Platform</label>
                <select name="provider" id="quick-meeting-provider" class="form-group__control" style="width:100%;padding:9px 12px;border-radius:10px;border:1px solid #CBD5E1;font-size:0.875rem">
                    <option value="google_meet">Google Meet</option>
                    <option value="zoom">Zoom</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:20px">
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#334155;margin-bottom:6px">Meeting Link URL</label>
                <input type="url" name="url" id="quick-meeting-url" placeholder="https://meet.google.com/xyz-abc or Zoom URL" required style="width:100%;padding:10px 14px;border-radius:10px;border:1px solid #CBD5E1;font-size:0.875rem">
                <small style="color:#64748B;font-size:0.75rem;display:block;margin-top:5px">Once saved, your student will see a "Join Meeting Now" button on their dashboard.</small>
            </div>
            <div class="flex items-center justify-end gap-2">
                <button type="button" class="btn btn-ghost btn-sm" id="cancel-meeting-link-modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" id="submit-quick-meeting-btn" style="background:#0D9488;border:none;padding:9px 20px;border-radius:10px">Send Link to Student</button>
            </div>
        </form>
    </div>
</div>
