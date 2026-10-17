<?php

use App\Core\Auth;
use App\Core\View;

$pageTitle = '403 — Access Denied';
$pageCss = null;

$homeByRole = [
    'admin' => '/admin',
    'instructor' => '/trainer/dashboard',
    'client' => '/dashboard',
];
$user = Auth::user();
$homeHref = $user ? ($homeByRole[$user['role']] ?? '/dashboard') : '/login';
?>
<div class="container" style="min-height:65vh;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;gap:var(--space-4);padding-block:var(--space-12)">
    <div style="font-size:3.5rem;font-weight:800;color:var(--color-primary);line-height:1">403</div>
    <h1 style="font-size:1.5rem;font-weight:700;margin:0">Access Restricted</h1>
    <p class="text-muted" style="max-width:460px;margin:0;font-size:0.9rem">
        <?php if ($user): ?>
        You are currently signed in as <strong><?= View::e($user['name']) ?></strong> (<?= ucfirst(View::e($user['role'])) ?> Account). Admin management pages require an Administrator account.
        <?php else: ?>
        You don't have permission to access this page. Please sign in with an Administrator account.
        <?php endif; ?>
    </p>

    <div class="flex items-center gap-3 flex-wrap justify-center mt-4">
        <?php if ($user): ?>
        <a href="<?= View::e($homeHref) ?>" class="btn btn-primary">Back to Dashboard</a>
        <?php else: ?>
        <a href="/login" class="btn btn-primary">Sign In as Admin</a>
        <?php endif; ?>
    </div>
</div>
