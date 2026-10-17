<?php

use App\Core\View;

$pageTitle = 'Instructors — Vipasa Yoga';
$pageCss = 'instructors';
$portal = 'client';
$active = 'instructors';
// Base path for the list's own links (filters, "All"). Defaults to the public
// route; the dashboard-embedded version passes '/dashboard/instructors' so
// filtering keeps the client inside the dashboard shell.
$instructorsBase = $instructorsBase ?? '/instructors';
?>
<div class="instructors-page container page-enter">
    <div class="services-hero">
        <h1 class="services-hero__title">Find Your Instructor</h1>
        <p class="services-hero__subtitle">Browse experienced instructors and view their availability.</p>
    </div>

    <?php if (!empty($serviceTypes)): ?>
    <div class="services-filters">
        <a href="<?= View::e($instructorsBase) ?><?= $selectedPackageId ? '?package=' . urlencode((string) $selectedPackageId) : '' ?>" class="chip<?= !$selectedServiceTypeId ? ' is-selected' : '' ?>">All</a>
        <?php foreach ($serviceTypes as $serviceType): ?>
        <a href="<?= View::e($instructorsBase) ?>?service=<?= (int) $serviceType['id'] ?><?= $selectedPackageId ? '&package=' . urlencode((string) $selectedPackageId) : '' ?>" class="chip<?= (string) $selectedServiceTypeId === (string) $serviceType['id'] ? ' is-selected' : '' ?>"><?= View::e($serviceType['name']) ?></a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($instructors)): ?>
    <p class="text-muted text-center">No instructors match this selection yet.</p>
    <?php else: ?>
    <div class="instructor-grid">
        <?php foreach ($instructors as $i => $instructor):
            $link = ($instructorsBase ?? '/instructors') . '/' . (int) $instructor['id'] . ($selectedPackageId ? '?package=' . urlencode((string) $selectedPackageId) : '');
            $servicePkgId = !empty($selectedServiceTypeId) ? (\App\Models\Package::forServiceType((int) $selectedServiceTypeId)[0]['id'] ?? null) : null;
            $bookPackageId = $selectedPackageId ?: ($servicePkgId ?: $defaultPackageId);
            $bookNowLink = $bookPackageId
                ? '/booking/schedule?instructor_id=' . (int) $instructor['id'] . '&package_id=' . urlencode((string) $bookPackageId)
                : null;
            $rating = (float) ($instructor['rating_avg'] ?? 0);
            $fullStars = (int) round($rating);
            $specialties = $instructor['specialties'] ?? [];
            $displayName = $instructor['name'] ?? ($instructor['headline'] ?: 'Yoga Instructor');
            $initials = mb_strtoupper(implode('', array_map(static fn ($w) => mb_substr($w, 0, 1), array_slice(preg_split('/\s+/', trim($displayName)), 0, 2))));
            $accentPalette = ['var(--color-primary)', 'var(--color-accent)', 'var(--color-tertiary)'];
            $accentSoftPalette = ['var(--color-primary-soft)', 'var(--color-accent-soft)', 'var(--color-tertiary-soft)'];
            $accentIndex = (int) $instructor['id'] % 3;
        ?>
        <div class="instructor-card" data-reveal data-reveal-delay="<?= ($i % 3) + 1 ?>">
            <div class="instructor-card__avatar">
                <?php if (!empty($instructor['avatar_path'])): ?>
                <img src="<?= View::e($instructor['avatar_path']) ?>" alt="" loading="lazy">
                <?php else: ?>
                <div class="flex items-center justify-center" style="width:100%;height:100%;background:<?= $accentSoftPalette[$accentIndex] ?>;color:<?= $accentPalette[$accentIndex] ?>;font-weight:var(--font-weight-semibold);font-size:var(--font-size-lg)">
                    <?= View::e($initials ?: 'YG') ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="instructor-card__name"><?= View::e($displayName) ?></div>
            <div class="instructor-card__specialty"><?= View::e($instructor['headline'] ?: '') ?><?= (int) $instructor['experience_years'] > 0 ? ' · ' . (int) $instructor['experience_years'] . ' yrs' : '' ?></div>
            <?php if ((int) ($instructor['rating_count'] ?? 0) > 0): ?>
            <div class="rating-stars">
                <?php for ($i = 0; $i < 5; $i++): ?>
                <?= View::icon('star', 'star' . ($i < $fullStars ? '' : ' is-empty')) ?>
                <?php endfor; ?>
                <span class="rating-stars__value"><?= number_format($rating, 1) ?></span>
                <span class="rating-stars__count">(<?= (int) $instructor['rating_count'] ?>)</span>
            </div>
            <?php else: ?>
            <span class="badge">New</span>
            <?php endif; ?>
            <?php if (!empty($specialties)): ?>
            <div class="instructor-card__tags">
                <?php foreach (array_slice($specialties, 0, 3) as $tag): ?>
                <span class="chip"><?= View::e((string) $tag) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="instructor-card__footer">
                <?php if (isset($instructor['effective_price'])): ?>
                <span class="card__title"><?= number_format((float) $instructor['effective_price'], 2) ?></span>
                <?php else: ?>
                <span></span>
                <?php endif; ?>
                <div class="flex gap-2 items-center">
                    <a href="<?= View::e($link) ?>" class="btn btn-ghost btn-sm">Profile</a>
                    <?php if ($bookNowLink): ?>
                    <a href="<?= View::e($bookNowLink) ?>" class="btn btn-accent btn-sm">Book Now</a>
                    <?php endif; ?>
                    <?php if (\App\Core\Auth::hasRole('admin')): ?>
                    <button type="button" class="btn btn-ghost btn-sm js-delete-instructor-card" data-id="<?= (int) $instructor['id'] ?>" title="Remove card" style="color:var(--color-error,#EF4444);padding:6px;border-radius:var(--radius-md)">
                        <?= View::icon('trash', 'icon', 15) ?>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script type="module">
import toast from '/assets/js/modules/toast.js';

document.querySelectorAll('.js-delete-instructor-card').forEach((btn) => {
    btn.addEventListener('click', () => {
        const card = btn.closest('.instructor-card');
        if (card) {
            card.remove();
            toast.success('Instructor card removed from view.');
        }
    });
});
</script>
