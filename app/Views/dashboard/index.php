<?php

use App\Core\View;

$pageTitle = 'My Dashboard — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'client';
$active = 'dashboard';
$user = \App\Core\Auth::user();
$firstName = explode(' ', $user['name'] ?? 'there')[0];
?>
<div class="container" style="padding-block:var(--space-6) var(--space-12)">

    <?php if (!empty($nextSession['meeting_link'])): ?>
    <div class="alert alert-success mb-6 flex items-center justify-between flex-wrap gap-3" style="background:linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%);border:1px solid #10B981;border-radius:var(--radius-xl);padding:14px 20px;box-shadow:0 4px 14px rgba(16,185,129,0.15)">
        <div class="flex items-center gap-3">
            <div style="display:grid;place-items:center;width:2.5rem;height:2.5rem;border-radius:var(--radius-full);background:#10B981;color:#fff">
                <?= View::icon('video', 'icon', 20) ?>
            </div>
            <div>
                <div style="font-weight:700;color:#065F46;font-size:0.95rem">Meeting Link Received from Trainer!</div>
                <div style="font-size:0.85rem;color:#047857">Your trainer sent the meeting link for your upcoming session with <strong><?= View::e($nextSession['instructor']['name'] ?? 'your instructor') ?></strong> on <?= View::e(date('d M Y, g:i A', strtotime($nextSession['slot_date'] . ' ' . $nextSession['start_time']))) ?>.</div>
            </div>
        </div>
        <a href="/booking/<?= View::e($nextSession['booking_ref']) ?>/join" class="btn btn-primary btn-sm flex items-center gap-1" style="background:#059669;border:none;box-shadow:0 4px 12px rgba(5,150,105,0.3)">
            <?= View::icon('video', 'icon', 15) ?>
            <span>Join Meeting Now</span>
        </a>
    </div>
    <?php endif; ?>

    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
        <div class="services-hero" style="margin:0;text-align:left">
            <h1 class="services-hero__title" style="margin:0;font-size:1.5rem">Welcome back, <?= View::e($firstName) ?> 👋</h1>
            <p class="services-hero__subtitle" style="margin:2px 0 0;font-style:italic;font-size:0.875rem">"<?= View::e($wellnessQuote) ?>"</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/dashboard/book" class="btn btn-accent btn-sm">
                <?= View::icon('calendar-week', 'icon', 16) ?> Book a Session
            </a>
            <a href="/dashboard/bookings" class="btn btn-secondary btn-sm">
                <?= View::icon('calendar-month', 'icon', 16) ?> My Bookings
            </a>
        </div>
    </div>

    <?php
    $stage = $completionPercent['stage'];
    if ($stage < 2) {
        $completionCopy = 'Complete your health & safety questionnaire so your instructor can keep you safe in every session.';
        $completionCta = ['label' => 'Start Assessment', 'href' => '/onboarding/health-assessment'];
    } elseif ($stage === 2 && $completedCount < 1) {
        $completionCopy = "Nice work — you're all set for your first session.";
        $completionCta = null;
    } elseif ($stage === 2) {
        $completionCopy = 'Your first session is done! A few lifestyle questions help us tailor your practice.';
        $completionCta = ['label' => 'Continue Profile', 'href' => '/onboarding/wellness-profile'];
    } elseif ($stage === 4) {
        $completionCopy = 'One last step — a few final details to complete your profile.';
        $completionCta = ['label' => 'Complete Profile', 'href' => '/onboarding/complete-profile'];
    } else {
        $completionCopy = 'Your profile is fully complete!';
        $completionCta = null;
    }
    ?>
    <?php if ($completionPercent['percent'] < 100 && $completionCta !== null): ?>
    <div class="card mb-6" style="padding:12px 16px" id="profile-completion-card">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-2">
            <div>
                <span style="font-weight:var(--font-weight-semibold);font-size:0.85rem">Profile Completion</span>
                <span class="text-muted" style="font-size:0.8rem;margin-left:8px"><?= View::e($completionCopy) ?></span>
            </div>
            <?php if ($completionCta): ?>
            <a href="<?= View::e($completionCta['href']) ?>" class="btn btn-accent btn-xs"><?= View::e($completionCta['label']) ?></a>
            <?php endif; ?>
        </div>
        <div class="progress-bar" style="height:6px">
            <div class="progress-bar__fill" style="width:<?= (int) $completionPercent['percent'] ?>%"></div>
        </div>
    </div>
    <?php endif; ?>

    <div class="stat-card-row mb-6">
        <div class="stat-card" style="padding:12px 16px">
            <div class="stat-card__label">Upcoming sessions</div>
            <div class="stat-card__value" style="font-size:1.4rem"><?= (int) $upcomingCount ?></div>
        </div>
        <div class="stat-card" style="padding:12px 16px">
            <div class="stat-card__label">Completed classes</div>
            <div class="stat-card__value" style="font-size:1.4rem"><?= (int) $completedCount ?></div>
        </div>
        <div class="stat-card" style="padding:12px 16px">
            <div class="stat-card__label">Favorite trainers</div>
            <div class="stat-card__value" style="font-size:1.4rem"><?= (int) $favoriteCount ?></div>
        </div>
        <div class="stat-card" style="padding:12px 16px">
            <div class="stat-card__label">Weekly streak</div>
            <div class="stat-card__value" style="font-size:1.4rem"><?= (int) $weekStreak ?> <?= $weekStreak === 1 ? 'wk' : 'wks' ?></div>
        </div>
    </div>

    <!-- Main Booking Workflow Section -->
    <div class="card mb-6" style="padding:16px 20px">
        <div class="flex items-center justify-between mb-3">
            <h2 style="font-size:1rem;font-weight:var(--font-weight-semibold);margin:0">Next Upcoming Session</h2>
            <?php if ($nextSession !== null): ?>
            <a href="/dashboard/bookings" class="btn btn-secondary btn-xs">All Bookings</a>
            <?php endif; ?>
        </div>

        <?php if ($nextSession === null): ?>
            <?php if (!empty($latestCompleted)): ?>
            <div class="flex items-center justify-between flex-wrap gap-4" style="padding:16px 20px;background:#F0FDFA;border:1px solid #99F6E4;border-radius:var(--radius-md);border-left:4px solid #0D9488">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="badge badge-completed" style="font-size:0.75rem;padding:2px 8px">Session Finished</span>
                        <strong style="color:#0F766E;font-size:0.95rem">Your session with <?= View::e($latestCompleted['instructor_name']) ?> is complete!</strong>
                    </div>
                    <p style="margin:2px 0 0;font-size:0.85rem;color:#115E59">To continue your practice and access upcoming live video classes, please book and complete payment for your next session.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/dashboard/book" class="btn btn-accent btn-sm flex items-center gap-1" style="background:linear-gradient(135deg, #0D9488 0%, #059669 100%);color:#fff;border:none;box-shadow:0 4px 12px rgba(13,148,136,0.25)">
                        <?= View::icon('wallet', 'icon', 15) ?>
                        <span>Book &amp; Pay Next Session</span>
                    </a>
                    <a href="/dashboard/bookings" class="btn btn-secondary btn-sm">My Bookings</a>
                </div>
            </div>
            <?php else: ?>
            <div class="flex items-center justify-between flex-wrap gap-4" style="padding:12px;background:var(--surface-sunken);border-radius:var(--radius-md)">
                <div>
                    <p class="text-muted" style="margin:0;font-size:0.875rem">No session currently booked. Explore top instructors and choose your preferred session time.</p>
                </div>
                <a href="/dashboard/book" class="btn btn-primary btn-sm">Explore &amp; Book Now</a>
            </div>
            <?php endif; ?>
        <?php else:
            $dateLabel = date('l, d M Y', strtotime($nextSession['slot_date']));
            $timeLabel = date('g:i A', strtotime($nextSession['start_time']));
        ?>
        <div class="flex items-center justify-between flex-wrap gap-3" style="padding:14px 18px;background:var(--surface-sunken);border-radius:var(--radius-md);border-left:4px solid var(--color-primary)">
            <div>
                <div style="font-weight:var(--font-weight-semibold);font-size:0.95rem;color:var(--color-primary)">
                    <?= View::e($nextSession['package']['name'] ?? 'Session') ?>
                </div>
                <p class="text-muted" style="margin:2px 0 0;font-size:0.85rem">
                    With <strong><?= View::e($nextSession['instructor']['name'] ?? 'Instructor') ?></strong> · <?= View::e($dateLabel) ?> at <?= View::e($timeLabel) ?>
                </p>
                <?php if (!empty($nextSession['meeting_link'])): ?>
                <div class="flex items-center gap-2 mt-2" style="font-size:0.8rem;color:#047857;font-weight:600">
                    <?= View::icon('video', 'icon', 14) ?>
                    Meeting Link Available: <a href="/booking/<?= View::e($nextSession['booking_ref']) ?>/join" target="_blank" rel="noopener" style="text-decoration:underline;color:#047857"><?= View::e($nextSession['meeting_link']['url'] ?? $nextSession['meeting_link']) ?></a>
                </div>
                <?php endif; ?>
            </div>
            <div class="flex items-center gap-2">
                <?php if (!empty($nextSession['meeting_link'])): ?>
                <a href="/booking/<?= View::e($nextSession['booking_ref']) ?>/join" class="btn btn-accent btn-sm flex items-center gap-1" style="background:linear-gradient(135deg, #0D9488 0%, #059669 100%);color:#fff;border:none">
                    <?= View::icon('video', 'icon', 14) ?> Join Video Session
                </a>
                <?php endif; ?>
                <a href="/dashboard/bookings" class="btn btn-secondary btn-sm">View Booking Details</a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- In-dashboard insights: book a session + browse instructors without leaving -->
    <div class="dash-insights mb-6">
        <div class="card" style="padding:16px 20px">
            <div class="flex items-center justify-between mb-3">
                <h2 style="font-size:1rem;font-weight:var(--font-weight-semibold);margin:0">Book a Session</h2>
                <a href="/dashboard/book" class="btn btn-secondary btn-xs">Browse all</a>
            </div>
            <?php if (empty($featuredPackages)): ?>
            <p class="text-muted" style="margin:0;font-size:0.85rem">No sessions available to book right now.</p>
            <?php else: ?>
            <div class="dash-insights__list">
                <?php foreach ($featuredPackages as $pkg): ?>
                <div class="dash-mini-row">
                    <div class="dash-mini-row__body">
                        <div class="dash-mini-row__title"><?= View::e($pkg['name']) ?></div>
                        <div class="dash-mini-row__meta">
                            <?= (int) $pkg['duration_minutes'] ?> min<?php if ((int) $pkg['sessions_count'] > 1): ?> · <?= (int) $pkg['sessions_count'] ?> sessions<?php endif; ?> · <?= View::e($pkg['currency'] ?? 'INR') ?> <?= number_format((float) $pkg['price'], 0) ?>
                        </div>
                    </div>
                    <a href="/dashboard/instructors?package=<?= (int) $pkg['id'] ?>" class="btn btn-accent btn-xs">Book</a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="card" style="padding:16px 20px">
            <div class="flex items-center justify-between mb-3">
                <h2 style="font-size:1rem;font-weight:var(--font-weight-semibold);margin:0">Instructors</h2>
                <a href="/dashboard/instructors" class="btn btn-secondary btn-xs">View all</a>
            </div>
            <?php if (empty($featuredInstructors)): ?>
            <p class="text-muted" style="margin:0;font-size:0.85rem">No instructors available yet.</p>
            <?php else: ?>
            <div class="dash-insights__list">
                <?php foreach ($featuredInstructors as $ins):
                    $insName = $ins['name'] ?? 'Instructor';
                    $insInitials = mb_strtoupper(mb_substr(trim($insName), 0, 1));
                ?>
                <div class="dash-mini-row">
                    <div class="dash-mini-row__avatar">
                        <?php if (!empty($ins['avatar_path'])): ?>
                        <img src="<?= View::e($ins['avatar_path']) ?>" alt="" loading="lazy">
                        <?php else: ?>
                        <span><?= View::e($insInitials ?: 'YG') ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="dash-mini-row__body">
                        <div class="dash-mini-row__title"><?= View::e($insName) ?></div>
                        <div class="dash-mini-row__meta">
                            <?= View::e($ins['headline'] ?: 'Yoga Instructor') ?><?php if ((int) ($ins['rating_count'] ?? 0) > 0): ?> · ★ <?= number_format((float) $ins['rating_avg'], 1) ?><?php endif; ?>
                        </div>
                    </div>
                    <a href="/dashboard/instructors/<?= (int) $ins['id'] ?>" class="btn btn-ghost btn-xs">Profile</a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($goals) || $healthSummary['conditionsCount'] > 0 || $healthSummary['painAreasCount'] > 0): ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:var(--space-4);margin-bottom:var(--space-6)">
        <?php if (!empty($goals)): ?>
        <div class="card" style="padding:14px">
            <h3 style="margin:0 0 8px;font-size:0.875rem;font-weight:var(--font-weight-semibold)">Your Wellness Goals</h3>
            <div class="flex gap-1-5 flex-wrap">
                <?php foreach ($goals as $goal): ?>
                <span class="chip" style="font-size:0.75rem;padding:2px 8px"><?= View::e(ucwords(str_replace('_', ' ', $goal))) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($healthSummary['conditionsCount'] > 0 || $healthSummary['painAreasCount'] > 0): ?>
        <div class="card" style="padding:14px">
            <h3 style="margin:0 0 4px;font-size:0.875rem;font-weight:var(--font-weight-semibold)">Health Summary</h3>
            <p class="text-muted" style="margin:0 0 8px;font-size:0.78rem"><?= (int) $healthSummary['conditionsCount'] ?> condition(s) & <?= (int) $healthSummary['painAreasCount'] ?> pain area(s) shared with instructors.</p>
            <a href="/onboarding/profile" class="btn btn-secondary btn-xs">Health Info</a>
        </div>
        <?php endif; ?>

        <?php if (!empty($recommendations)): ?>
        <div class="card" style="padding:14px">
            <h3 style="margin:0 0 4px;font-size:0.875rem;font-weight:var(--font-weight-semibold)">Recommended Sessions</h3>
            <p class="text-muted" style="margin:0 0 8px;font-size:0.78rem"><?= View::e(implode(', ', $recommendations)) ?></p>
            <a href="/services" class="btn btn-secondary btn-xs">Browse Matching</a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php if (!empty($completionPercent['justReached'])): ?>
<script type="module">
import { fireConfetti } from '/assets/js/modules/confetti.js';
import toast from '/assets/js/modules/toast.js';

fireConfetti();
toast.success('Nice progress — your profile is <?= (int) $completionPercent['percent'] ?>% complete!');
</script>
<?php endif; ?>

<?php if (!empty($unreviewedBooking)): ?>
<!-- Persistent Mandatory Feedback Modal Popup -->
<div id="mandatory-feedback-modal" style="display:grid;place-items:center;padding:16px;z-index:9999;position:fixed;inset:0;background:rgba(15,23,42,0.68);backdrop-filter:blur(5px)">
    <div style="background:#fff;border-radius:24px;padding:30px 32px;width:100%;max-width:450px;box-shadow:0 25px 50px -12px rgba(15,23,42,0.3);position:relative;margin:auto">
        <button type="button" id="close-feedback-modal" style="position:absolute;top:18px;right:18px;background:none;border:none;font-size:1.4rem;color:#94A3B8;cursor:pointer;line-height:1" aria-label="Close">&times;</button>

        <div style="text-align:center;margin-bottom:18px">
            <div style="display:inline-grid;place-items:center;width:3.2rem;height:3.2rem;border-radius:50%;background:#FEF3C7;color:#D97706;margin-bottom:10px">
                <?= View::icon('star', 'icon', 24) ?>
            </div>
            <h2 style="font-size:1.35rem;font-weight:700;color:#0F172A;margin:0 0 6px 0">How was your session?</h2>
            <p style="font-size:0.85rem;color:#64748B;margin:0">
                Your session for <strong><?= View::e($unreviewedBooking['package_name']) ?></strong> with <strong><?= View::e($unreviewedBooking['instructor_name']) ?></strong> is finished. Please leave your rating!
            </p>
        </div>

        <form id="mandatory-rate-form">
            <input type="hidden" name="booking_ref" id="mandatory-booking-ref" value="<?= View::e($unreviewedBooking['booking_ref']) ?>">
            <input type="hidden" name="rating" id="mandatory-rating-input" value="5">

            <!-- Interactive 5 Gold Stars -->
            <div class="flex items-center justify-center gap-2 mb-4" id="mandatory-star-container" style="cursor:pointer">
                <?php for ($s = 1; $s <= 5; $s++): ?>
                <span class="js-star-item" data-star="<?= $s ?>" style="font-size:2.5rem;color:#F59E0B;transition:transform 0.15s ease;user-select:none">★</span>
                <?php endfor; ?>
            </div>

            <div style="margin-bottom:18px">
                <label style="display:block;font-size:0.8125rem;font-weight:600;color:#334155;margin-bottom:6px">Your comment &amp; review</label>
                <textarea name="review_text" id="mandatory-review-text" class="form-group__control" rows="3" placeholder="Tell us how the class went! Instructor guidance, pacing, and overall experience..." style="width:100%;background:#F8FAFC;border:1px solid #CBD5E1;border-radius:12px;padding:12px;font-size:0.85rem;color:#0F172A;resize:none;outline:none;line-height:1.4"></textarea>
            </div>

            <div id="mandatory-rate-error" style="margin-bottom:12px;color:#EF4444;font-size:0.8rem"></div>

            <div class="flex items-center justify-between gap-3">
                <button type="button" class="btn" id="postpone-feedback-btn" style="background:#F1F5F9;color:#64748B;font-weight:600;font-size:0.85rem;padding:10px 18px;border-radius:12px;border:none;cursor:pointer">Remind Later</button>
                <button type="submit" class="btn" id="mandatory-submit-btn" style="background:linear-gradient(135deg, #0D9488 0%, #059669 100%);color:#fff;font-weight:600;font-size:0.85rem;padding:10px 24px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 4px 14px rgba(13,148,136,0.3)">Submit Feedback</button>
            </div>
        </form>
    </div>
</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

const modal = document.getElementById('mandatory-feedback-modal');
const form = document.getElementById('mandatory-rate-form');
const ratingInput = document.getElementById('mandatory-rating-input');
const stars = document.querySelectorAll('.js-star-item');
const errorBox = document.getElementById('mandatory-rate-error');
const submitBtn = document.getElementById('mandatory-submit-btn');

function updateStars(val) {
    stars.forEach(s => {
        const starNum = parseInt(s.dataset.star, 10);
        s.style.color = starNum <= val ? '#F59E0B' : '#CBD5E1';
    });
}

stars.forEach(s => {
    s.addEventListener('click', () => {
        const val = parseInt(s.dataset.star, 10);
        ratingInput.value = val;
        updateStars(val);
    });
    s.addEventListener('mouseenter', () => {
        const val = parseInt(s.dataset.star, 10);
        updateStars(val);
    });
});

document.getElementById('mandatory-star-container')?.addEventListener('mouseleave', () => {
    updateStars(parseInt(ratingInput.value, 10));
});

document.getElementById('close-feedback-modal')?.addEventListener('click', () => {
    modal.style.display = 'none';
});
document.getElementById('postpone-feedback-btn')?.addEventListener('click', () => {
    modal.style.display = 'none';
});

form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const ref = document.getElementById('mandatory-booking-ref').value;
    const rating = parseInt(ratingInput.value, 10);
    const reviewText = document.getElementById('mandatory-review-text').value.trim();

    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
    errorBox.textContent = '';

    try {
        await apiPost(`/api/bookings/${ref}/review`, {
            rating,
            review_text: reviewText,
        });
        toast.success('Thank you! Your feedback has been submitted successfully.');
        modal.remove();
        setTimeout(() => window.location.reload(), 800);
    } catch (err) {
        errorBox.textContent = err.message || 'Failed to submit review.';
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit Feedback';
    }
});
</script>
<?php endif; ?>

