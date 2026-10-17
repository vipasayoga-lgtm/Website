<?php

use App\Core\View;

$pageTitle = 'Refund Policy — Vipasa Yoga';
$pageCss = 'legal';
$contactEmail = $contact['email'] ?? 'vipasayoga@gmail.com';
$contactPhone = $contact['phone'] ?? '+91 70127 75990';
$contactLoc = $contact['location'] ?? 'Live online sessions, worldwide';
?>
<div class="legal-page container page-enter">
    <div class="legal-back-nav">
        <a href="javascript:history.length > 1 ? history.back() : window.location.href='/'" class="legal-back-btn">
            <?= View::icon('arrow-left', 'legal-back-icon') ?>
            <span>Back to Website</span>
        </a>
    </div>

    <div class="legal-hero">
        <h1 class="legal-hero__title">Refund &amp; Cancellation Policy</h1>
        <p class="legal-hero__subtitle">Transparent guidelines on cancellations, session rescheduling, instructor guarantees, and refund processing timelines.</p>
        <span class="legal-hero__updated">
            <?= View::icon('rotate-ccw', 'legal-hero__icon', 14) ?>
            <span>Effective Date: <?= date('F j, Y') ?></span>
        </span>
    </div>

    <div class="legal-layout">
        <!-- Left Side Panel: Quick Navigation, Guarantees & Security Badges -->
        <aside class="legal-side-column legal-side-column--left">
            <div class="legal-side-widget">
                <div class="legal-side-widget__title">On This Page</div>
                <nav class="legal-side-nav">
                    <a href="#overview" class="legal-side-nav__link">1. Overview</a>
                    <a href="#cancellations-by-client" class="legal-side-nav__link">2. Cancellation Tiers</a>
                    <a href="#instructor-cancellation" class="legal-side-nav__link">3. Instructor Outages</a>
                    <a href="#packages-and-subscriptions" class="legal-side-nav__link">4. Package Refunds</a>
                    <a href="#processing-timelines" class="legal-side-nav__link">5. Refund Timelines</a>
                    <a href="#how-to-request" class="legal-side-nav__link">6. Submit Request</a>
                    <a href="#contact" class="legal-side-nav__link">7. Customer Support</a>
                </nav>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Refund Guarantees</div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon">✓</div>
                    <span class="legal-badge-pill__text">100% Refund @ 24h+ Notice</span>
                </div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon">✓</div>
                    <span class="legal-badge-pill__text">100% Instructor Outage Protection</span>
                </div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon">✓</div>
                    <span class="legal-badge-pill__text">5-7 Day Bank Processing</span>
                </div>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Support Status</div>
                <div class="legal-status-header">
                    <span class="legal-status-dot"></span>
                    <span>Live Support Active</span>
                </div>
                <p class="legal-side-meta">Mon - Sun: 6:00 AM – 10:00 PM IST<br>Avg Response Time: &lt; 15 Mins</p>
                <a href="mailto:<?= View::e($contactEmail) ?>" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;font-size:11px">
                    <?= View::icon('mail', 'icon', 12) ?> Contact Helpline
                </a>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Bank-Grade Security</div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon" style="background:rgba(59,130,246,0.12);color:#3B82F6">🔒</div>
                    <span class="legal-badge-pill__text">256-Bit SSL Encrypted</span>
                </div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon" style="background:rgba(59,130,246,0.12);color:#3B82F6">💳</div>
                    <span class="legal-badge-pill__text">PCI-DSS Gateway Level 1</span>
                </div>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Quick Links</div>
                <nav class="legal-side-nav">
                    <a href="/terms-of-service" class="legal-side-nav__link">📜 Terms of Service</a>
                    <a href="/privacy-policy" class="legal-side-nav__link">🔒 Privacy Policy</a>
                    <a href="/instructors" class="legal-side-nav__link">🧘 Browse Instructors</a>
                </nav>
            </div>
        </aside>

        <!-- Main Document Body -->
        <div class="legal-content">
            <section id="overview">
                <div class="legal-section-header">
                    <span class="legal-section-num">01</span>
                    <h2>Overview &amp; Our Commitment</h2>
                </div>
                <p>At Vipasa Yoga, we strive to deliver an exceptional, reliable, and personalized live online yoga experience. We value your time and the time reserved by our certified instructors. This Refund &amp; Cancellation Policy details your rights and options regarding single session drop-ins, package subscriptions, cancellation windows, and refund requests.</p>
            </section>

            <section id="cancellations-by-client">
                <div class="legal-section-header">
                    <span class="legal-section-num">02</span>
                    <h2>Client Cancellations &amp; Rescheduling Tiers</h2>
                </div>
                <p>Because live 1:1 and small group sessions reserve an instructor's dedicated time exclusively for you, session cancellations and rescheduling requests are subject to the notice timeframe provided prior to class start time:</p>

                <div class="legal-alert legal-alert--success" style="margin-bottom:var(--space-3)">
                    <div class="legal-alert__title">✓ 24+ Hours Advance Notice: 100% Full Refund or Free Reschedule</div>
                    <p class="legal-alert__text">If you cancel or request a slot reschedule at least 24 hours prior to the scheduled session start time, you are eligible for a 100% full refund to your original payment method OR a 1:1 session credit transfer to any available slot.</p>
                </div>

                <div class="legal-alert legal-alert--warning" style="margin-bottom:var(--space-3)">
                    <div class="legal-alert__title">⚠ 12 to 24 Hours Notice: 50% Refund or Slot Rollover</div>
                    <p class="legal-alert__text">Cancellations submitted between 12 and 24 hours before class start time qualify for a 50% monetary refund OR full rollover of credit to reschedule within the same week.</p>
                </div>

                <div class="legal-alert" style="background:rgba(239,68,68,0.08);border-color:rgba(239,68,68,0.3);border-left:4px solid #EF4444">
                    <div class="legal-alert__title" style="color:#EF4444">✕ Less Than 12 Hours Notice / No-Show: Non-Refundable</div>
                    <p class="legal-alert__text">Cancellations made less than 12 hours before start time, or failure to join the live video session ("No-Show"), are non-refundable as the instructor's time was reserved exclusively for your session.</p>
                </div>
            </section>

            <section id="instructor-cancellation">
                <div class="legal-section-header">
                    <span class="legal-section-num">03</span>
                    <h2>Instructor Cancellations &amp; Platform Outage Protection</h2>
                </div>
                <p>We hold our instructors to the highest standards of punctuality and availability. In the rare event an instructor must cancel or is unable to host a scheduled session:</p>
                <ul>
                    <li><strong>Immediate Guarantee:</strong> You will be offered your choice of a <strong>100% full monetary refund</strong> OR priority booking for a replacement session with your preferred instructor at no additional charge.</li>
                    <li><strong>Technical Outage Protection:</strong> If a session fails to start or is interrupted due to platform technical drop-outs or server failures on our side, an automatic session credit or 100% refund is immediately applied to your account.</li>
                </ul>
            </section>

            <section id="packages-and-subscriptions">
                <div class="legal-section-header">
                    <span class="legal-section-num">04</span>
                    <h2>Packages &amp; Subscription Renewal Refunds</h2>
                </div>
                <p>For weekly, monthly, or multi-session package purchases:</p>
                <ul>
                    <li><strong>Pro-Rata Package Refunds:</strong> Unused session credits within an active package may be refunded on a pro-rata basis if requested within 7 days of package purchase, minus the standard single-session rate for any completed sessions.</li>
                    <li><strong>Expired Credits:</strong> Package credits that reach the end of their stated validity window (e.g., 30 days for monthly packages) without being scheduled are non-refundable.</li>
                </ul>
            </section>

            <section id="processing-timelines">
                <div class="legal-section-header">
                    <span class="legal-section-num">05</span>
                    <h2>Refund Processing Methods &amp; Timelines</h2>
                </div>
                <p>Once a refund request is approved by our billing support team:</p>
                <ul>
                    <li><strong>Original Payment Method:</strong> Refunds are automatically processed back to the credit card, debit card, UPI, or bank account used during original checkout.</li>
                    <li><strong>Processing Duration:</strong> Approved monetary refunds typically post to your account within <strong>5 to 7 business days</strong>, depending on your bank, card network, or payment gateway processing times.</li>
                    <li><strong>Instant Account Credits:</strong> If you select session credit roll-over instead of cash refund, the credit reflects immediately in your member dashboard for booking.</li>
                </ul>
            </section>

            <section id="how-to-request">
                <div class="legal-section-header">
                    <span class="legal-section-num">06</span>
                    <h2>How to Submit a Refund Request</h2>
                </div>
                <p>To request a refund or ask about a specific booking cancellation:</p>
                <ol style="margin:0 0 var(--space-4);padding-left:var(--space-5);display:flex;flex-direction:column;gap:var(--space-2)">
                    <li>Log in to your member dashboard and navigate to <strong>My Bookings</strong> or <strong>Payment History</strong>.</li>
                    <li>Locate the specific booking reference (e.g., <code>#VY-XXXXXX</code>) and click <strong>Cancel Session / Request Refund</strong>.</li>
                    <li>Alternatively, email billing support at <a href="mailto:<?= View::e($contactEmail) ?>"><?= View::e($contactEmail) ?></a> with your booking reference and cancellation reason.</li>
                </ol>
            </section>

            <section id="contact">
                <div class="legal-section-header">
                    <span class="legal-section-num">07</span>
                    <h2>Billing &amp; Refund Support Office</h2>
                </div>
                <p>Our dedicated customer care team is available to assist with refund inquiries, scheduling adjustments, or invoice questions:</p>
                
                <div class="legal-contact-card">
                    <div class="legal-contact-card__title">Vipasa Yoga Billing &amp; Customer Care Office</div>
                    <div class="legal-contact-card__item">
                        <?= View::icon('mail', 'icon', 16) ?>
                        <span>Support Email: <a href="mailto:<?= View::e($contactEmail) ?>"><?= View::e($contactEmail) ?></a></span>
                    </div>
                    <div class="legal-contact-card__item">
                        <?= View::icon('phone', 'icon', 16) ?>
                        <span>Customer Helpline: <?= View::e($contactPhone) ?></span>
                    </div>
                    <div class="legal-contact-card__item">
                        <?= View::icon('map-pin', 'icon', 16) ?>
                        <span>Headquarters: <?= View::e($contactLoc) ?></span>
                    </div>
                </div>
            </section>
        </div>

        <!-- Right Side Panel: Visual Yoga Showcase, Certified Instructors & Support Widgets -->
        <aside class="legal-side-column legal-side-column--right">
            <div class="legal-visual-card">
                <img src="/assets/img/client/benefit-mindfulness.jpg" alt="Restorative Yoga Practice" class="legal-visual-card__img">
                <div class="legal-visual-card__overlay">
                    <span class="legal-visual-card__tag">SESSION FLEXIBILITY</span>
                    <p class="legal-visual-card__caption">Reschedule 1:1 sessions up to 24h prior to class.</p>
                </div>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Billing Support</div>
                <p style="font-size:12px;color:var(--text-secondary);margin-bottom:var(--space-3)">Need assistance with a booking refund or package rollover?</p>
                <a href="mailto:<?= View::e($contactEmail) ?>" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;font-size:12px">
                    <?= View::icon('mail', 'icon', 14) ?> Email Billing Care
                </a>
            </div>

            <div class="legal-visual-card">
                <img src="/assets/img/client/benefit-recovery.jpg" alt="Yoga Recovery Session" class="legal-visual-card__img">
                <div class="legal-visual-card__overlay">
                    <span class="legal-visual-card__tag">GUARANTEED RISK-FREE</span>
                    <p class="legal-visual-card__caption">100% refund guarantee for instructor cancellations.</p>
                </div>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Certified Instructors</div>
                <div class="legal-avatar-stack">
                    <img src="/assets/img/instructors/ananya.jpg" alt="Instructor Ananya" class="legal-avatar-img">
                    <img src="/assets/img/instructors/rahul.jpg" alt="Instructor Rahul" class="legal-avatar-img">
                    <img src="/assets/img/instructors/sarah.jpg" alt="Instructor Sarah" class="legal-avatar-img">
                </div>
                <div class="legal-rating-badge">
                    <span>★★★★★</span>
                    <span>4.9 / 5 Rating</span>
                </div>
                <p style="font-size:11px;color:var(--text-muted);margin-top:var(--space-1)">50+ Certified Yoga Masters worldwide.</p>
            </div>

            <div class="legal-visual-card">
                <img src="/assets/img/client/benefit-flexibility.jpg" alt="1:1 Tailored Sessions" class="legal-visual-card__img">
                <div class="legal-visual-card__overlay">
                    <span class="legal-visual-card__tag">PERSONALIZED CARE</span>
                    <p class="legal-visual-card__caption">Customized live 1:1 routines for your body and goal.</p>
                </div>
            </div>
        </aside>
    </div>
</div>
