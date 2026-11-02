<?php

use App\Core\View;
use App\Models\Setting;

$contactEmail = $contact['email'] ?? 'vipasayoga@gmail.com';
$instagramUrl = Setting::get('social_instagram', 'https://www.instagram.com/vipasayoga?cplk=emt5emUxdHY2NWVo&utm_source=qr');
$facebookUrl = Setting::get('social_facebook', 'https://www.facebook.com/share/1KTuGJAxaL/?mibextid=wwXIfr');
$youtubeUrl = Setting::get('social_youtube', 'https://youtube.com/@vipasayoga?si=NeqboKH4S-6jHi4n');
?>
<footer class="site-footer">
    <div class="container site-footer__grid">
        <div class="site-footer__brand">
            <a href="/" class="site-footer__brand-link" aria-label="Vipasa Yoga — Flow Like a River">
                <img src="/assets/img/brand/logo-full-dark.png" alt="Vipasa Yoga" class="site-footer__brand-logo brand-logo--dark">
                <img src="/assets/img/brand/logo-full-light.png" alt="Vipasa Yoga" class="site-footer__brand-logo brand-logo--light">
            </a>
            <p class="site-footer__tagline">Live yoga sessions with certified instructors, booked in minutes.</p>
            <div class="site-footer__social">
                <a class="site-footer__social-link" href="<?= View::e($instagramUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>
                </a>
                <a class="site-footer__social-link" href="<?= View::e($facebookUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </a>
                <a class="site-footer__social-link" href="<?= View::e($youtubeUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="4"/><polygon points="10 9 15 12 10 15" fill="currentColor" stroke="none"/></svg>
                </a>
            </div>
        </div>
        <div class="site-footer__nav">
            <nav class="site-footer__col">
                <h3 class="site-footer__col-title">Explore</h3>
                <a class="site-footer__link" href="/#about">About</a>
                <a class="site-footer__link" href="/#programs">Programs</a>
                <a class="site-footer__link" href="/services">Packages</a>
                <a class="site-footer__link" href="/instructors">Instructors</a>
                <a class="site-footer__link" href="/#gallery">Gallery</a>
                <a class="site-footer__link" href="/#faq">FAQ</a>
            </nav>
            <nav class="site-footer__col">
                <h3 class="site-footer__col-title">Account</h3>
                <a class="site-footer__link" href="/login">Log in</a>
                <a class="site-footer__link" href="/signup">Sign up</a>
            </nav>
            <nav class="site-footer__col">
                <h3 class="site-footer__col-title">Support</h3>
                <a class="site-footer__link" href="/#faq">FAQ</a>
                <a class="site-footer__link" href="/#contact">Contact Us</a>
                <a class="site-footer__link" href="/privacy-policy">Privacy Policy</a>
                <a class="site-footer__link" href="/terms-of-service">Terms of Service</a>
                <a class="site-footer__link" href="/refund-policy">Refund Policy</a>
            </nav>
            <nav class="site-footer__col">
                <h3 class="site-footer__col-title">Contact</h3>
                <a class="site-footer__link" href="/#contact">Get in touch</a>
                <a class="site-footer__link" href="mailto:<?= View::e($contactEmail) ?>"><?= View::e($contactEmail) ?></a>
            </nav>
        </div>
    </div>
    <div class="site-footer__bottom">
        <div class="container site-footer__bottom-inner">
            <p class="site-footer__copyright">&copy; <?= date('Y') ?> Vipasa Yoga. All rights reserved.</p>
        </div>
    </div>
</footer>

