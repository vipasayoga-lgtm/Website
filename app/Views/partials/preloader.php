<?php

use App\Models\Setting;
use App\Core\View;

$customLogo = Setting::get('site_logo_path', '');
$logoUrl = !empty($customLogo) ? $customLogo : '/assets/img/brand/loginlogo-gold.png';
?>
<div class="site-preloader" id="sitePreloader" aria-hidden="true">
    <div class="site-preloader__card">
        <div class="site-preloader__logo-wrap">
            <div class="site-preloader__logo-glow"></div>
            <div class="site-preloader__logo-box">
                <img src="<?= View::e($logoUrl) ?>" alt="Vipasa Yoga" class="site-preloader__logo-img">
            </div>
        </div>
        <div class="site-preloader__wordmark">Vipasa Yoga</div>
        <div class="site-preloader__bar">
            <div class="site-preloader__bar-fill" id="sitePreloaderFill"></div>
        </div>
    </div>
</div>
<script>
(function() {
    var loader = document.getElementById('sitePreloader');
    var fill = document.getElementById('sitePreloaderFill');
    if (!loader) return;

    var start = Date.now();
    var duration = 900;

    function step() {
        var elapsed = Date.now() - start;
        var progress = Math.min(elapsed / duration, 1);
        if (fill) fill.style.width = Math.floor(progress * 100) + '%';
        if (progress < 1) {
            requestAnimationFrame(step);
        } else {
            loader.classList.add('is-done');
            document.body.classList.add('page-entered');
            setTimeout(function() {
                if (loader.parentNode) loader.parentNode.removeChild(loader);
            }, 500);
        }
    }
    requestAnimationFrame(step);
})();
</script>
