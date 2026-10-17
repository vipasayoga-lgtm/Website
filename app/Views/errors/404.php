<?php

use App\Core\View;

$pageTitle = 'Page not found — Vipasa Yoga';
$pageCss = 'errors';
?>
<div class="error-page">
    <div class="error-page__glow" aria-hidden="true"></div>
    <div class="error-page__content">
        <div class="error-page__icon"><?= View::icon('search', 'icon', 28) ?></div>
        <div class="error-page__code">404</div>
        <h1 class="error-page__title">Page not found</h1>
        <p class="error-page__message">The page you're looking for doesn't exist or may have moved.</p>
        <div class="error-page__actions">
            <a href="/" class="btn btn-accent btn-lg">Back to Home</a>
            <a href="/services" class="btn btn-secondary btn-lg">Browse Services</a>
        </div>
    </div>
</div>
