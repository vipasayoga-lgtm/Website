<?php

use App\Core\View;

$pageTitle = 'Join Session — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'client';
$active = 'bookings';

$dateLabel = date('l, d M Y', strtotime($booking['slot_date']));
$timeLabel = date('g:i A', strtotime($booking['start_time']));
$instructorName = $instructor['name'] ?? 'your instructor';
?>
<div class="container" style="padding-block:var(--space-6) var(--space-16);max-width:760px">
    <p class="mb-4"><a href="/dashboard/bookings">&larr; Back to My Bookings</a></p>

    <!-- Join Session Card (Matching Image 1) -->
    <div class="card mb-6" style="padding:28px 32px;border-radius:20px;box-shadow:0 10px 30px -5px rgba(15, 51, 73, 0.08);background:#fff;border:1px solid var(--border-subtle)">
        <h1 style="margin-top:0;font-size:1.5rem;font-weight:700;color:#0F3349;margin-bottom:6px">Join Session</h1>
        <p class="text-muted" style="margin:0 0 20px 0;font-size:0.95rem">With <?= View::e($instructorName) ?> · <?= View::e($dateLabel) ?> at <?= View::e($timeLabel) ?></p>

        <?php if ($booking['status'] === 'completed'): ?>
        <div style="background:#F0FDFA;border:1px solid #99F6E4;border-radius:14px;padding:20px;text-align:left;margin-bottom:12px">
            <div class="flex items-center gap-2 mb-2" style="color:#0F766E;font-weight:700;font-size:1.05rem">
                <?= View::icon('check', 'icon', 20) ?>
                This Session is Completed
            </div>
            <p style="color:#115E59;font-size:0.875rem;line-height:1.5;margin:0 0 16px">
                Thank you for practicing with Vipasa Yoga! This session has finished. To join future live classes and access new sessions, please choose a package and complete payment.
            </p>
            <div class="flex items-center gap-3 flex-wrap">
                <a href="/dashboard/book" class="btn" style="background:#0D9488;color:#fff;font-weight:600;padding:10px 22px;border-radius:12px;text-decoration:none;box-shadow:0 4px 12px rgba(13,148,136,0.25)">
                    <?= View::icon('calendar-week', 'icon', 15) ?> Book &amp; Pay Next Session
                </a>
                <a href="/dashboard/bookings" class="btn btn-secondary btn-sm" style="padding:10px 18px;border-radius:12px">
                    My Bookings
                </a>
            </div>
        </div>

        <?php elseif (!$meetingLink): ?>
        <div style="background:#FFFBEB;border:1px solid #FCD34D;border-radius:12px;padding:14px 18px;color:#92400E;font-size:0.875rem">
            <strong>Meeting Link Pending:</strong> Live video — Coming soon. Your instructor hasn't shared a meeting link for this session yet.
        </div>

        <?php elseif ($hasConsent): ?>
        <p style="margin:0 0 16px 0;font-size:0.9rem;color:#475569">You're all set — your consent is already on file for this session.</p>
        <a href="<?= View::e($meetingLink['url']) ?>" class="btn" target="_blank" rel="noopener" style="background:#EA580C;color:#fff;font-weight:600;padding:12px 28px;border-radius:12px;display:inline-flex;align-items:center;gap:8px;box-shadow:0 4px 14px rgba(234,88,12,0.3);text-decoration:none">
            <?= View::icon('video', 'icon', 16) ?> Join Session
        </a>

        <?php else: ?>
        <p style="margin:0 0 12px 0;font-size:0.9rem;color:#475569">Before joining, please confirm the following safety items:</p>
        <div id="consent-box" class="card" style="padding:16px;background:#F8FAFC;border-radius:12px;margin-bottom:16px;border:1px solid #E2E8F0">
            <label class="flex items-center gap-2 mb-3" style="font-size:0.85rem">
                <input type="checkbox" data-consent="s1">
                <span>I confirm that the information I provided is true.</span>
            </label>
            <label class="flex items-center gap-2 mb-3" style="font-size:0.85rem">
                <input type="checkbox" data-consent="s2">
                <span>I understand Yoga is not medical treatment.</span>
            </label>
            <label class="flex items-center gap-2" style="margin-bottom:0;font-size:0.85rem">
                <input type="checkbox" data-consent="s3">
                <span>I agree to inform Vipasa if my health changes.</span>
            </label>
        </div>
        <button type="button" class="btn" id="consent-join-btn" disabled style="background:#EA580C;color:#fff;font-weight:600;padding:12px 28px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(234,88,12,0.3)">I Agree &amp; Join Session</button>
        <div class="form-group__error" id="consent-error" style="margin-top:12px"></div>
        <?php endif; ?>
    </div>

    <!-- Session Rating & Review Section (Shown after joining or completing session) -->
    <div class="card" style="padding:24px 30px;border-radius:20px;box-shadow:0 10px 30px -5px rgba(15, 51, 73, 0.05);background:#fff;border:1px solid var(--border-subtle)">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-3">
            <div>
                <h3 style="margin:0;font-size:1.15rem;font-weight:700;color:#0F172A">Session Feedback &amp; Rating</h3>
                <p class="text-muted" style="margin:2px 0 0;font-size:0.85rem">How was your session with <?= View::e($instructorName) ?>?</p>
            </div>
            <?php if (!empty($existingReview)): ?>
            <span class="badge badge-confirmed" style="padding:4px 12px;border-radius:20px;font-size:0.8rem">✓ Review Submitted for Admin Approval</span>
            <?php endif; ?>
        </div>

        <?php if (!empty($existingReview)): ?>
        <div style="background:#F8FAFC;padding:16px 20px;border-radius:14px;border:1px solid #E2E8F0" class="mt-3">
            <div class="flex items-center gap-1 mb-2">
                <?php for ($star = 1; $star <= 5; $star++): ?>
                <span style="font-size:1.25rem;color:<?= $star <= (int)$existingReview['rating'] ? '#F59E0B' : '#CBD5E1' ?>">★</span>
                <?php endfor; ?>
                <strong style="font-size:0.9rem;margin-left:6px;color:#0F172A"><?= (int)$existingReview['rating'] ?>.0 / 5</strong>
                <span class="text-muted" style="font-size:0.75rem;margin-left:auto"><?= date('d M Y, g:i A', strtotime($existingReview['created_at'])) ?></span>
            </div>
            <?php if (!empty($existingReview['review_text'])): ?>
            <p style="margin:0;font-size:0.875rem;color:#334155;line-height:1.5">&ldquo;<?= View::e($existingReview['review_text']) ?>&rdquo;</p>
            <?php else: ?>
            <p class="text-muted" style="margin:0;font-size:0.85rem;font-style:italic">Rated <?= (int)$existingReview['rating'] ?> stars without written comment.</p>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div style="background:#F0FDFA;border:1px solid #99F6E4;border-radius:14px;padding:16px 20px" class="flex items-center justify-between flex-wrap gap-3 mt-3">
            <div>
                <div style="font-weight:600;color:#0F766E;font-size:0.9rem">Finished your class?</div>
                <p style="margin:2px 0 0;font-size:0.8rem;color:#115E59">Leave a 5-star rating and feedback for <?= View::e($instructorName) ?>. Approved reviews are displayed on the homepage!</p>
            </div>
            <button type="button" class="btn" id="open-rate-modal-btn" style="background:linear-gradient(135deg, #0D9488 0%, #059669 100%);color:#fff;font-weight:600;font-size:0.85rem;padding:10px 20px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(13,148,136,0.3)">
                ★ Rate &amp; Leave a Review
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Leave a Review Modal Overlay (Exact Image 2 Format) -->
<div class="modal-overlay hidden" id="rate-modal-overlay" style="display:grid;place-items:center;padding:16px;z-index:9999">
    <div class="review-modal-card" style="background:#fff;border-radius:24px;padding:28px 32px;width:100%;max-width:440px;box-shadow:0 25px 50px -12px rgba(15,23,42,0.25);position:relative;margin:auto">
        <button type="button" id="rate-modal-close" style="position:absolute;top:20px;right:20px;background:none;border:none;font-size:1.4rem;color:#94A3B8;cursor:pointer;line-height:1" aria-label="Close">&times;</button>
        
        <h2 style="font-size:1.35rem;font-weight:700;color:#0F172A;margin:0 0 4px 0">Leave a review</h2>
        <p style="font-size:0.85rem;color:#64748B;margin:0 0 20px 0">How was your experience today?</p>

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

            <div class="form-group__error" id="rate-form-error" style="margin-bottom:12px;color:#EF4444;font-size:0.8rem"></div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between gap-3">
                <button type="button" class="btn" id="rate-modal-cancel" style="background:#F1F5F9;color:#475569;font-weight:600;font-size:0.85rem;padding:10px 20px;border-radius:12px;border:none;cursor:pointer">Not now</button>
                <button type="submit" class="btn" id="rate-submit-btn" style="background:linear-gradient(135deg, #0D9488 0%, #059669 100%);color:#fff;font-weight:600;font-size:0.85rem;padding:10px 24px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(13,148,136,0.3)">Publish review</button>
            </div>
        </form>
    </div>
</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

// Consent Checkbox Handler
const box = document.getElementById('consent-box');
const joinBtn = document.getElementById('consent-join-btn');
const errorBox = document.getElementById('consent-error');
const checkboxes = box ? box.querySelectorAll('[data-consent]') : [];

if (box && joinBtn) {
    function updateEnabled() {
        joinBtn.disabled = !Array.from(checkboxes).every((c) => c.checked);
    }
    checkboxes.forEach((c) => c.addEventListener('change', updateEnabled));

    joinBtn.addEventListener('click', async () => {
        joinBtn.disabled = true;
        errorBox.textContent = '';

        let timezone = 'UTC';
        try { timezone = Intl.DateTimeFormat().resolvedOptions().timeZone; } catch { /* fall back */ }

        try {
            await apiPost('/api/consent', {
                accepted: true,
                booking_ref: <?= json_encode($booking['booking_ref']) ?>,
                timezone,
            });
            toast.success('Consent recorded — opening your session.');
            window.open(<?= json_encode($meetingLink['url'] ?? '') ?>, '_blank', 'noopener');
            window.location.reload();
        } catch (err) {
            errorBox.textContent = err.message;
            toast.error(err.message);
            joinBtn.disabled = false;
        }
    });
}

// Review & Star Rating Modal Handler
const rateOverlay = document.getElementById('rate-modal-overlay');
const rateForm = document.getElementById('rate-form');
const openRateBtn = document.getElementById('open-rate-modal-btn');
const closeRateBtn = document.getElementById('rate-modal-close');
const cancelRateBtn = document.getElementById('rate-modal-cancel');
const starContainer = document.getElementById('star-rating-container');
const ratingInput = document.getElementById('review-rating-input');
const rateErrorBox = document.getElementById('rate-form-error');
const stars = Array.from(starContainer?.querySelectorAll('.star-rating-icon') || []);

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
    if (rateOverlay) rateOverlay.classList.add('hidden');
    if (rateForm) rateForm.reset();
    setStarRating(5);
    if (rateErrorBox) rateErrorBox.textContent = '';
}

openRateBtn?.addEventListener('click', () => {
    setStarRating(5);
    rateOverlay?.classList.remove('hidden');
});

closeRateBtn?.addEventListener('click', closeRateModal);
cancelRateBtn?.addEventListener('click', closeRateModal);
rateOverlay?.addEventListener('click', (event) => { if (event.target === rateOverlay) closeRateModal(); });

rateForm?.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (rateErrorBox) rateErrorBox.textContent = '';
    const ratingVal = Number(ratingInput.value || 5);

    try {
        await apiPost(`/api/bookings/<?= View::e($booking['booking_ref']) ?>/review`, {
            rating: ratingVal,
            review_text: rateForm.review_text.value.trim(),
        });
        toast.success('Thanks for your feedback! Your review has been submitted for admin approval.');
        closeRateModal();
        window.location.reload();
    } catch (err) {
        if (rateErrorBox) rateErrorBox.textContent = err.message;
        toast.error(err.message);
    }
});
</script>
