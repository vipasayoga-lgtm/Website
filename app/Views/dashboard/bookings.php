<?php

use App\Core\View;

$pageTitle = 'My Bookings — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'client';
$active = 'bookings';

$statusMeta = [
    'pending_payment' => ['badge-pending', 'Pending payment'],
    'awaiting_trainer_approval' => ['badge-pending', 'Awaiting confirmation'],
    'confirmed' => ['badge-confirmed', 'Confirmed'],
    'completed' => ['badge-completed', 'Completed'],
    'cancelled' => ['badge-cancelled', 'Cancelled'],
    'rescheduled' => ['badge-pending', 'Rescheduled'],
];

$allBookings = array_merge($upcoming, $history);
?>
<div class="container" style="padding-block:var(--space-8) var(--space-16)">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div class="services-hero" style="margin:0;text-align:left">
            <h1 class="services-hero__title" style="margin:0">My Bookings</h1>
            <p class="services-hero__subtitle" style="margin:0">Your upcoming classes, complete session history, and bookings.</p>
        </div>
        <div class="flex gap-3">
            <a href="/dashboard/favorites" class="btn btn-secondary btn-sm">Favorites</a>
            <a href="/dashboard/payments" class="btn btn-secondary btn-sm">Payment History</a>
            <a href="/dashboard/invoices" class="btn btn-secondary btn-sm">Invoices</a>
        </div>
    </div>

    <?php if (empty($allBookings)): ?>
    <div class="empty-state" style="max-width:720px;margin-inline:auto">
        <span class="empty-state__icon"><?= View::icon('calendar', 'icon', 22) ?></span>
        <p class="empty-state__text">You haven't booked a session yet. <a href="/dashboard/book" style="color:var(--color-primary);text-decoration:underline">Browse services</a> to get started.</p>
    </div>
    <?php else: ?>
    
    <div class="dashboard-table-container mb-4">
        <table class="dashboard-table" id="bookings-table">
            <thead>
                <tr>
                    <th>Date &amp; Time</th>
                    <th>Ref</th>
                    <th>Package &amp; Instructor</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody id="bookings-tbody">
                <?php foreach ($allBookings as $booking):
                    [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
                    $dateLabel = date('d M Y', strtotime($booking['slot_date']));
                    $timeLabel = date('g:i A', strtotime($booking['start_time']));
                    $isFuture = strtotime($booking['slot_date'] . ' ' . $booking['start_time']) > time();
                    $canCancel = $isFuture && in_array($booking['status'], ['pending_payment', 'awaiting_trainer_approval', 'confirmed'], true);
                    $hasInvoice = in_array($booking['status'], ['awaiting_trainer_approval', 'confirmed', 'completed'], true);
                ?>
                <tr data-ref="<?= View::e($booking['booking_ref']) ?>">
                    <td>
                        <div style="font-weight:var(--font-weight-medium);white-space:nowrap"><?= View::e($dateLabel) ?></div>
                        <div class="text-muted" style="font-size:0.8rem"><?= View::e($timeLabel) ?></div>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-weight:600;color:var(--color-primary)"><?= View::e($booking['booking_ref']) ?></span>
                    </td>
                    <td>
                        <div style="font-weight:var(--font-weight-semibold);color:var(--text-primary)"><?= View::e($booking['package']['name'] ?? 'Package') ?></div>
                        <div class="text-muted" style="font-size:0.8rem">With <?= View::e($booking['instructor']['name'] ?? 'Instructor') ?></div>
                    </td>
                    <td>
                        <div style="font-weight:var(--font-weight-semibold);color:var(--text-primary)">
                            <?= View::e($booking['currency']) ?> <?= number_format((float) $booking['total_amount'], 2) ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span>
                    </td>
                    <td style="text-align:right">
                        <div class="flex items-center justify-end gap-2 flex-wrap">
                            <?php if ($booking['status'] === 'pending_payment'): ?>
                            <a href="/booking/confirm/<?= View::e($booking['booking_ref']) ?>" class="btn btn-accent btn-xs">Pay Now</a>
                            <?php endif; ?>

                            <?php if (!empty($booking['meeting_link']) && $booking['status'] === 'confirmed'): ?>
                            <a href="/booking/<?= View::e($booking['booking_ref']) ?>/join" class="btn btn-accent btn-xs">Join Session</a>
                            <?php endif; ?>

                            <?php if ($hasInvoice): ?>
                            <a href="/booking/<?= View::e($booking['booking_ref']) ?>/invoice" class="btn btn-secondary btn-xs" target="_blank" rel="noopener" title="View Invoice">
                                <?= View::icon('file-text', 'icon', 12) ?> Invoice
                            </a>
                            <?php endif; ?>

                            <?php if ($booking['status'] === 'confirmed' && $isFuture && !$booking['has_pending_reschedule']): ?>
                            <button type="button" class="btn btn-ghost btn-xs reschedule-btn" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Reschedule">
                                Reschedule
                            </button>
                            <?php endif; ?>

                            <?php if ($booking['can_rate']): ?>
                            <button type="button" class="btn btn-primary btn-xs rate-btn" data-ref="<?= View::e($booking['booking_ref']) ?>" data-instructor="<?= View::e($booking['instructor']['name'] ?? 'your instructor') ?>">★ Rate &amp; Feedback</button>
                            <?php endif; ?>

                            <?php if (in_array($booking['status'], ['completed', 'confirmed'], true)): ?>
                            <a href="/dashboard/book" class="btn btn-secondary btn-xs" title="Book your next session">Book Next Session</a>
                            <?php endif; ?>

                            <?php if ($canCancel && \App\Core\Auth::hasRole('admin')): ?>
                            <button type="button" class="btn btn-ghost btn-xs text-error cancel-booking-btn" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Cancel Booking">
                                <?= View::icon('trash', 'icon', 13) ?>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls (10 items per page) -->
    <div class="flex items-center justify-between flex-wrap gap-3" id="bookings-pagination-wrapper" style="padding-inline:var(--space-2)">
        <div class="text-muted" style="font-size:0.85rem" id="bookings-pagination-info">Showing 1 to 10 of <?= count($allBookings) ?> bookings</div>
        <div class="flex items-center gap-2">
            <button type="button" class="btn btn-secondary btn-sm" id="bookings-prev-btn" disabled>&laquo; Previous</button>
            <span style="font-size:0.85rem;font-weight:600;padding-inline:8px" id="bookings-page-indicator">Page 1</span>
            <button type="button" class="btn btn-secondary btn-sm" id="bookings-next-btn">Next &raquo;</button>
        </div>
    </div>

    <?php endif; ?>
</div>

<div class="modal-overlay hidden" id="rate-modal-overlay" style="display:grid;place-items:center;padding:16px">
    <div class="review-modal-card" style="background:#fff;border-radius:24px;padding:28px 32px;width:100%;max-width:440px;box-shadow:0 25px 50px -12px rgba(15,23,42,0.25);position:relative;margin:auto">
        <button type="button" id="rate-modal-close" class="modal__close" style="position:absolute;top:20px;right:20px;background:none;border:none;font-size:1.4rem;color:#94A3B8;cursor:pointer;line-height:1" aria-label="Close">&times;</button>
        
        <h2 style="font-size:1.35rem;font-weight:700;color:#0F172A;margin:0 0 4px 0">Leave a review</h2>
        <p style="font-size:0.85rem;color:#64748B;margin:0 0 20px 0">How was your experience today with <span id="rate-modal-instructor" style="font-weight:600;color:#0F172A"></span>?</p>

        <form id="rate-form">
            <input type="hidden" name="rating" id="review-rating-input" value="5">
            
            <!-- Interactive 5 Gold Star Rating -->
            <div class="flex items-center justify-center gap-2 mb-5" id="star-rating-container" style="cursor:pointer">
                <?php for ($s = 1; $s <= 5; $s++): ?>
                <span class="star-rating-icon" data-star="<?= $s ?>" style="font-size:2.25rem;color:#F59E0B;transition:transform 0.15s ease;user-select:none">★</span>
                <?php endfor; ?>
            </div>

            <!-- Comment Field -->
            <div style="margin-bottom:20px">
                <label style="display:block;font-size:0.8125rem;font-weight:600;color:#334155;margin-bottom:6px">Your comment</label>
                <textarea name="review_text" class="form-group__control" rows="3" placeholder="Everything was smooth and friendly — I'll be back soon!" style="width:100%;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:12px;font-size:0.85rem;color:#0F172A;resize:none;outline:none;line-height:1.4"></textarea>
            </div>

            <div class="form-group__error" id="rate-form-error" style="margin-bottom:12px"></div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between gap-3">
                <button type="button" class="btn" id="rate-modal-cancel" style="background:#F1F5F9;color:#475569;font-weight:600;font-size:0.85rem;padding:10px 20px;border-radius:12px;border:none;cursor:pointer">Not now</button>
                <button type="submit" class="btn" id="rate-submit-btn" style="background:linear-gradient(135deg, #0D9488 0%, #059669 100%);color:#fff;font-weight:600;font-size:0.85rem;padding:10px 24px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(13,148,136,0.3)">Publish review</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay hidden" id="reschedule-modal-overlay">
    <div class="modal">
        <div class="modal__header">
            <div class="modal__title">Request Reschedule</div>
            <button type="button" class="modal__close" id="reschedule-modal-close" aria-label="Close">&times;</button>
        </div>
        <form id="reschedule-form">
            <div class="modal__body flex flex-col gap-4">
                <p class="text-muted" style="margin:0;font-size:var(--font-size-sm)">Your instructor will review and approve or decline this request.</p>
                <div class="form-group" style="margin:0">
                    <input type="date" name="slot_date" class="form-group__control" required>
                    <label class="form-group__label">New date</label>
                </div>
                <div class="form-group" style="margin:0">
                    <input type="time" name="start_time" class="form-group__control" required>
                    <label class="form-group__label">New time</label>
                </div>
                <div class="form-group__error" id="reschedule-form-error"></div>
            </div>
            <div class="modal__footer">
                <button type="button" class="btn btn-ghost" id="reschedule-modal-cancel">Cancel</button>
                <button type="submit" class="btn btn-primary">Send Request</button>
            </div>
        </form>
    </div>
</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

const tbody = document.getElementById('bookings-tbody');
const rows = Array.from(tbody?.querySelectorAll('tr') || []);
const itemsPerPage = 8;
let currentPage = 1;
let totalPages = Math.ceil(rows.length / itemsPerPage);

function renderPagination() {
    if (!tbody || rows.length === 0) return;
    
    totalPages = Math.ceil(rows.length / itemsPerPage);
    if (currentPage > totalPages) currentPage = Math.max(1, totalPages);

    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = startIdx + itemsPerPage;

    rows.forEach((row, index) => {
        if (index >= startIdx && index < endIdx) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    const infoEl = document.getElementById('bookings-pagination-info');
    const prevBtn = document.getElementById('bookings-prev-btn');
    const nextBtn = document.getElementById('bookings-next-btn');
    const indicatorEl = document.getElementById('bookings-page-indicator');

    if (infoEl) {
        const visibleCount = Math.min(endIdx, rows.length);
        infoEl.textContent = `Showing ${rows.length === 0 ? 0 : startIdx + 1} to ${visibleCount} of ${rows.length} bookings`;
    }

    if (indicatorEl) {
        indicatorEl.textContent = `Page ${currentPage} of ${Math.max(1, totalPages)}`;
    }

    if (prevBtn) prevBtn.disabled = currentPage <= 1;
    if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
}

document.getElementById('bookings-prev-btn')?.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderPagination();
    }
});

document.getElementById('bookings-next-btn')?.addEventListener('click', () => {
    if (currentPage < totalPages) {
        currentPage++;
        renderPagination();
    }
});

document.querySelectorAll('.cancel-booking-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        btn.classList.add('is-loading');
        btn.disabled = true;

        try {
            await apiPost(`/api/bookings/${btn.dataset.ref}/cancel`, {});
            toast.success('Booking cancelled.');
            window.location.reload();
        } catch (err) {
            toast.error(err.message);
            btn.classList.remove('is-loading');
            btn.disabled = false;
        }
    });
});

const rateOverlay = document.getElementById('rate-modal-overlay');
const rateForm = document.getElementById('rate-form');
const rateErrorBox = document.getElementById('rate-form-error');
const starContainer = document.getElementById('star-rating-container');
const ratingInput = document.getElementById('review-rating-input');
const stars = Array.from(starContainer?.querySelectorAll('.star-rating-icon') || []);
let ratingRef = null;

function setStarRating(val) {
    if (ratingInput) ratingInput.value = val;
    stars.forEach((star, index) => {
        if (index < val) {
            star.style.color = '#F59E0B';
            star.style.transform = 'scale(1.15)';
        } else {
            star.style.color = '#CBD5E1';
            star.style.transform = 'scale(1)';
        }
    });
}

stars.forEach((star) => {
    star.addEventListener('mouseenter', () => {
        const val = Number(star.dataset.star);
        stars.forEach((s, idx) => {
            s.style.color = idx < val ? '#F59E0B' : '#CBD5E1';
            s.style.transform = idx < val ? 'scale(1.15)' : 'scale(1)';
        });
    });
    star.addEventListener('click', () => {
        const val = Number(star.dataset.star);
        setStarRating(val);
    });
});

starContainer?.addEventListener('mouseleave', () => {
    const val = Number(ratingInput?.value || 5);
    setStarRating(val);
});

function closeRateModal() {
    rateOverlay.classList.add('hidden');
    rateForm.reset();
    setStarRating(5);
    rateErrorBox.textContent = '';
    ratingRef = null;
}

document.querySelectorAll('.rate-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
        ratingRef = btn.dataset.ref;
        document.getElementById('rate-modal-instructor').textContent = btn.dataset.instructor;
        setStarRating(5);
        rateOverlay.classList.remove('hidden');
    });
});

document.getElementById('rate-modal-close').addEventListener('click', closeRateModal);
document.getElementById('rate-modal-cancel').addEventListener('click', closeRateModal);
rateOverlay?.addEventListener('click', (event) => { if (event.target === rateOverlay) closeRateModal(); });

rateForm?.addEventListener('submit', async (event) => {
    event.preventDefault();
    rateErrorBox.textContent = '';
    const ratingVal = Number(ratingInput.value || 5);

    try {
        await apiPost(`/api/bookings/${ratingRef}/review`, {
            rating: ratingVal,
            review_text: rateForm.review_text.value.trim(),
        });
        toast.success('Thanks for your feedback! Your review has been submitted for admin approval.');
        closeRateModal();
        window.location.reload();
    } catch (err) {
        rateErrorBox.textContent = err.message;
        toast.error(err.message);
    }
});

const rescheduleOverlay = document.getElementById('reschedule-modal-overlay');
const rescheduleForm = document.getElementById('reschedule-form');
const rescheduleErrorBox = document.getElementById('reschedule-form-error');
let rescheduleRef = null;

function closeRescheduleModal() {
    rescheduleOverlay.classList.add('hidden');
    rescheduleForm.reset();
    rescheduleErrorBox.textContent = '';
    rescheduleRef = null;
}

document.querySelectorAll('.reschedule-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
        rescheduleRef = btn.dataset.ref;
        rescheduleOverlay.classList.remove('hidden');
    });
});

document.getElementById('reschedule-modal-close').addEventListener('click', closeRescheduleModal);
document.getElementById('reschedule-modal-cancel').addEventListener('click', closeRescheduleModal);
rescheduleOverlay?.addEventListener('click', (event) => { if (event.target === rescheduleOverlay) closeRescheduleModal(); });

rescheduleForm?.addEventListener('submit', async (event) => {
    event.preventDefault();
    rescheduleErrorBox.textContent = '';

    try {
        await apiPost(`/api/bookings/${rescheduleRef}/request-reschedule`, {
            slot_date: rescheduleForm.slot_date.value,
            start_time: rescheduleForm.start_time.value,
        });
        toast.success('Reschedule request sent.');
        window.location.reload();
    } catch (err) {
        rescheduleErrorBox.textContent = err.message;
        toast.error(err.message);
    }
});

renderPagination();
</script>
