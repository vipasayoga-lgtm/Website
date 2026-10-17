<?php

use App\Models\Setting;
use App\Core\View;

$pageTitle = 'Vipasa Yoga — Welcome';
$pageCss = 'splash';

$customLogo = Setting::get('site_logo_path', '');
$logoUrl = $logoUrl ?? (!empty($customLogo) ? $customLogo : '/assets/img/brand/loginlogo-exact.png');
$redirectTo = $redirectTo ?? '/home';
?>
<div class="splash" id="splash">
    <div class="splash__bg-orbs" aria-hidden="true">
        <span class="splash__orb splash__orb--1"></span>
        <span class="splash__orb splash__orb--2"></span>
        <span class="splash__orb splash__orb--3"></span>
    </div>

    <div class="splash__card">
        <div class="splash__logo-wrap">
            <div class="splash__logo-glow"></div>
            <div class="splash__logo-box">
                <img src="<?= View::e($logoUrl) ?>" alt="Vipasa Yoga" class="splash__logo-img">
            </div>
        </div>

        <div class="splash__text">
            <h1 class="splash__wordmark">Vipasa Yoga</h1>
            <p class="splash__tagline">Breathe. Move. Belong.</p>
        </div>

        <div class="splash__loader-wrap">
            <div class="splash__loader">
                <div class="splash__loader-fill" id="splashLoaderFill"></div>
            </div>
            <div class="splash__loader-meta">
                <span class="splash__loader-status">Preparing your sanctuary...</span>
                <span class="splash__loader-percent" id="splashPercent">0%</span>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var redirectTo = <?= json_encode($redirectTo) ?>;
    var splash = document.getElementById('splash');
    var fill = document.getElementById('splashLoaderFill');
    var percentEl = document.getElementById('splashPercent');
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var duration = prefersReducedMotion ? 300 : 1500;
    var startTime = null;

    function animateProgress(timestamp) {
        if (!startTime) startTime = timestamp;
        var elapsed = timestamp - startTime;
        var progress = Math.min(elapsed / duration, 1);
        
        var eased = progress < 0.5 ? 2 * progress * progress : 1 - Math.pow(-2 * progress + 2, 2) / 2;
        var pct = Math.floor(eased * 100);

        if (fill) fill.style.width = pct + '%';
        if (percentEl) percentEl.textContent = pct + '%';

        if (progress < 1) {
            requestAnimationFrame(animateProgress);
        } else {
            setTimeout(function () {
                splash.classList.add('is-leaving');
                setTimeout(function () {
                    if (redirectTo) {
                        window.location.href = redirectTo;
                    }
                }, prefersReducedMotion ? 50 : 450);
            }, 180);
        }
    }

    requestAnimationFrame(animateProgress);
})();
</script>
