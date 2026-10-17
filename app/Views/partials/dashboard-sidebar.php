<?php

use App\Core\View;

/** @var string $portal */
/** @var string $active */

$navByPortal = [
    'admin' => [
        'dashboard' => ['/admin', 'Dashboard', 'home'],
        'bookings' => ['/admin/bookings', 'Bookings', 'calendar-week'],
        'calendar' => ['/admin/calendar', 'Calendar', 'calendar-month'],
        'packages' => ['/admin/packages', 'Packages', 'sliders'],
        'instructors' => ['/admin/instructors', 'Trainers', 'users'],
        'customers' => ['/admin/customers', 'Clients', 'user'],
        'payments' => ['/admin/payments', 'Payments', 'credit-card'],
        'reviews' => ['/admin/reviews', 'Student Reviews', 'star'],
        'reports' => ['/admin/reports', 'Reports', 'file-text'],
        'content' => ['/admin/content', 'Website Content', 'file-text'],
        'gallery' => ['/admin/gallery', 'Gallery', 'image'],
        'notifications' => ['/admin/notifications', 'Notifications', 'bell'],
        'settings' => ['/admin/settings', 'Settings', 'settings'],
    ],
    'trainer' => [
        'dashboard' => ['/trainer/dashboard', 'Dashboard', 'home'],
        'bookings' => ['/trainer/bookings', 'Bookings', 'calendar-week'],
        'calendar' => ['/trainer/calendar', 'Calendar', 'calendar-month'],
        'students' => ['/trainer/students', 'Students', 'users'],
        'earnings' => ['/trainer/earnings', 'Earnings', 'wallet'],
        'reviews' => ['/trainer/reviews', 'Reviews', 'star'],
        'notifications' => ['/trainer/notifications', 'Notifications', 'bell'],
        'profile' => ['/trainer/profile', 'Profile', 'user'],
    ],
    'client' => [
        'dashboard' => ['/dashboard', 'Dashboard', 'home'],
        'services' => ['/dashboard/book', 'Book a Session', 'calendar-week'],
        'instructors' => ['/dashboard/instructors', 'Instructors', 'users'],
        'bookings' => ['/dashboard/bookings', 'My Bookings', 'calendar-month'],
        'favorites' => ['/dashboard/favorites', 'Favorites', 'heart'],
        'payments' => ['/dashboard/payments', 'Payments', 'credit-card'],
        'invoices' => ['/dashboard/invoices', 'Invoices', 'file-text'],
        'notifications' => ['/dashboard/notifications', 'Notifications', 'bell'],
        'profile' => ['/onboarding/profile', 'Profile', 'user'],
    ],
];

$navLinks = $navByPortal[$portal] ?? [];
$brandLabel = match ($portal) {
    'admin' => 'Vipasa Admin',
    'trainer' => 'Vipasa Teach',
    default => 'Vipasa Yoga',
};
?>
<aside class="dash-sidebar" data-dash-sidebar>
    <a href="<?= $portal === 'admin' ? '/admin' : ($portal === 'trainer' ? '/trainer/dashboard' : '/dashboard') ?>" class="dash-sidebar__brand" aria-label="<?= View::e($brandLabel) ?>">
        <img src="/assets/img/brand/logo-symbol-light.png" alt="<?= View::e($brandLabel) ?>" class="dash-sidebar__brand-logo">
    </a>
    <nav class="dash-sidebar__nav">
        <?php foreach ($navLinks as $key => [$href, $label, $iconName]): ?>
        <a href="<?= View::e($href) ?>" class="dash-sidebar__link<?= $active === $key ? ' is-active' : '' ?>" aria-label="<?= View::e($label) ?>" data-tooltip="<?= View::e($label) ?>">
            <span class="dash-sidebar__icon"><?= View::icon($iconName, 'icon', 20) ?></span>
            <span class="dash-sidebar__label"><?= View::e($label) ?></span>
        </a>
        <?php endforeach; ?>
    </nav>
    <button type="button" class="dash-sidebar__collapse" data-sidebar-collapse aria-label="Collapse sidebar">
        <?= View::icon('chevron-left', 'icon', 18) ?>
    </button>
</aside>
<button type="button" class="dash-sidebar__mobile-overlay" data-sidebar-overlay aria-hidden="true"></button>
