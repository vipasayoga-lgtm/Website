<?php

use App\Core\View;

$pageTitle = 'Something went wrong — Vipasa Yoga';
$pageCss = 'errors';
$debug = $debug ?? false;
$exceptionMessage = $exceptionMessage ?? '';
$exceptionTrace = $exceptionTrace ?? '';
?>
<div class="error-page">
    <div class="error-page__glow" aria-hidden="true"></div>
    <div class="error-page__content" style="max-width:<?= $debug ? '720px' : '480px' ?>">
        <div class="error-page__icon" style="background:var(--color-error-50);color:var(--color-error-500)"><?= View::icon('x', 'icon', 28) ?></div>
        <div class="error-page__code">500</div>
        <h1 class="error-page__title">Something went wrong</h1>
        <p class="error-page__message">We hit an unexpected error on our end. It's been logged, and we're on it — please try again in a moment.</p>
        <div class="error-page__actions">
            <a href="/" class="btn btn-accent btn-lg">Back to Home</a>
            <a href="/#contact" class="btn btn-secondary btn-lg">Contact Support</a>
        </div>
        <?php if ($debug && $exceptionMessage): ?>
        <div class="error-page__trace"><?= View::e($exceptionMessage) ?><?= $exceptionTrace ? "\n\n" . View::e($exceptionTrace) : '' ?></div>
        <?php endif; ?>
    </div>
</div>
