<?php

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;

$pageTitle = $pageTitle ?? 'Vipasa Yoga';
$pageCss = $pageCss ?? null;
$user = Auth::user();
$brandHref = match ($user['role'] ?? null) {
    'admin' => '/admin',
    'instructor' => '/trainer/dashboard',
    'client' => '/dashboard',
    default => '/',
};

/* Nav links per role, as href => label. Kept as data rather than inline
   markup so the current-page marker (the saffron underline in
   components.css) can be resolved in one place below. */
$navLinks = match ($user['role'] ?? null) {
    'instructor' => [
        '/trainer/dashboard' => 'Dashboard',
        '/trainer/bookings' => 'Bookings',
        '/trainer/calendar' => 'Calendar',
        '/trainer/profile' => 'Profile',
    ],
    'admin' => [
        '/admin' => 'Admin',
    ],
    default => $user ? [
        '/services' => 'Services',
        '/instructors' => 'Instructors',
        '/dashboard' => 'Dashboard',
        '/dashboard/bookings' => 'My Bookings',
        '/onboarding/profile' => 'Profile',
    ] : [
        '/services' => 'Services',
        '/instructors' => 'Instructors',
    ],
};

/* Which link is the page we're on. A link matches when it equals the request
   path or is a parent of it (/instructors/7 highlights "Instructors"), and
   the longest match wins so /dashboard/bookings marks "My Bookings" instead
   of also matching the shorter "/dashboard". */
$currentPath = rtrim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
if ($currentPath === '') {
    $currentPath = '/';
}
$activeNavHref = null;
foreach (array_keys($navLinks) as $href) {
    if ($currentPath !== $href && !str_starts_with($currentPath, $href . '/')) {
        continue;
    }
    if ($activeNavHref === null || strlen($href) > strlen($activeNavHref)) {
        $activeNavHref = $href;
    }
}
?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= View::e($pageTitle) ?></title>
<meta name="csrf-token" content="<?= View::e(Csrf::token()) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600&family=Inter:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="<?= View::e(View::asset('/assets/css/design-tokens.css')) ?>">
<link rel="stylesheet" href="<?= View::e(View::asset('/assets/css/base.css')) ?>">
<link rel="stylesheet" href="<?= View::e(View::asset('/assets/css/components.css')) ?>">
<?php if ($pageCss): ?>
<link rel="stylesheet" href="<?= View::e(View::asset('/assets/css/pages/' . $pageCss . '.css')) ?>">
<?php endif; ?>
</head>
<body>
<header class="nav-glass no-print">
    <div class="nav-glass__brand">
        <a href="<?= View::e($brandHref) ?>">
            <img src="/assets/img/brand/logo-black.png" alt="Vipasa Yoga" class="nav-glass__brand-logo brand-logo--dark">
            <img src="/assets/img/brand/logo-dark.png" alt="Vipasa Yoga" class="nav-glass__brand-logo brand-logo--light">
        </a>
    </div>
    <button type="button" class="nav-glass__toggle" data-nav-toggle aria-expanded="false" aria-label="Toggle menu">
        <span class="nav-glass__toggle-bar"></span>
        <span class="nav-glass__toggle-bar"></span>
        <span class="nav-glass__toggle-bar"></span>
    </button>
    <nav class="nav-glass__links">
        <?php foreach ($navLinks as $href => $label): ?>
        <a href="<?= View::e($href) ?>"
           class="nav-glass__link<?= $href === $activeNavHref ? ' is-active' : '' ?>"
           <?= $href === $activeNavHref ? 'aria-current="page"' : '' ?>><?= View::e($label) ?></a>
        <?php endforeach; ?>
        <?php if (!$user): ?>
        <div class="nav-glass__mobile-auth" style="display:none;width:100%;margin-top:10px;padding-top:10px;border-top:1px solid var(--border-subtle);flex-direction:column;gap:8px">
            <a href="/login" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center">Log in</a>
            <a href="/signup" class="btn btn-accent btn-sm" style="width:100%;justify-content:center">Get started</a>
        </div>
        <?php endif; ?>
    </nav>
    <div class="nav-glass__actions">
        <?php if ($user): ?>
        <span class="chip"><?= View::e($user['name']) ?></span>
        <form action="/logout" method="post" style="display:inline">
            <input type="hidden" name="csrf_token" value="<?= View::e(Csrf::token()) ?>">
            <button type="submit" class="btn btn-ghost btn-sm">Log out</button>
        </form>
        <?php else: ?>
        <a href="/login" class="btn btn-ghost btn-lg">Log in</a>
        <a href="/signup" class="btn btn-accent btn-lg">Get started <?= View::icon('arrow-right', 'icon', 16) ?></a>
        <?php endif; ?>
    </div>
</header>
<main>
<?= $content ?>
</main>
<script type="module" src="<?= View::e(View::asset('/assets/js/app.js')) ?>"></script>
</body>
</html>
