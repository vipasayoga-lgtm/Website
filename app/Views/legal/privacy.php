<?php

use App\Core\View;

$pageTitle = 'Privacy Policy — Vipasa Yoga';
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
        <h1 class="legal-hero__title">Privacy Policy</h1>
        <p class="legal-hero__subtitle">How Vipasa Yoga collects, protects, processes, and respects your personal &amp; health information.</p>
        <span class="legal-hero__updated">
            <?= View::icon('shield-check', 'legal-hero__icon', 14) ?>
            <span>Effective Date: <?= date('F j, Y') ?></span>
        </span>
    </div>

    <div class="legal-layout">
        <!-- Left Side Panel: Quick Navigation & Security Badges -->
        <aside class="legal-side-column legal-side-column--left">
            <div class="legal-side-widget">
                <div class="legal-side-widget__title">On This Page</div>
                <nav class="legal-side-nav">
                    <a href="#introduction" class="legal-side-nav__link">1. Scope</a>
                    <a href="#information-we-collect" class="legal-side-nav__link">2. Data We Collect</a>
                    <a href="#how-we-use-it" class="legal-side-nav__link">3. Data Usage</a>
                    <a href="#health-safeguards" class="legal-side-nav__link">4. Health Safeguards</a>
                    <a href="#sharing-and-third-parties" class="legal-side-nav__link">5. Third Parties</a>
                    <a href="#security" class="legal-side-nav__link">6. Data Security</a>
                    <a href="#data-retention" class="legal-side-nav__link">7. Retention</a>
                    <a href="#your-rights" class="legal-side-nav__link">8. Your Rights</a>
                    <a href="#contact" class="legal-side-nav__link">11. Contact Support</a>
                </nav>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Trust &amp; Privacy Safeguards</div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon">✓</div>
                    <span class="legal-badge-pill__text">256-Bit SSL Encrypted</span>
                </div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon">✓</div>
                    <span class="legal-badge-pill__text">Scoped Medical Visibility</span>
                </div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon">✓</div>
                    <span class="legal-badge-pill__text">Zero Data Selling</span>
                </div>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Support Status</div>
                <div class="legal-status-header">
                    <span class="legal-status-dot"></span>
                    <span>Live Support Active</span>
                </div>
                <p class="legal-side-meta">Daily: 6:00 AM – 10:00 PM IST<br>Email: <?= View::e($contactEmail) ?></p>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Data Compliance</div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon" style="background:rgba(59,130,246,0.12);color:#3B82F6">🛡️</div>
                    <span class="legal-badge-pill__text">GDPR Principles Compliant</span>
                </div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon" style="background:rgba(59,130,246,0.12);color:#3B82F6">🔒</div>
                    <span class="legal-badge-pill__text">Encrypted Health Storage</span>
                </div>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Quick Navigation</div>
                <nav class="legal-side-nav">
                    <a href="/terms-of-service" class="legal-side-nav__link">📜 Terms of Service</a>
                    <a href="/refund-policy" class="legal-side-nav__link">💳 Refund Policy</a>
                    <a href="/instructors" class="legal-side-nav__link">🧘 Explore Trainers</a>
                </nav>
            </div>
        </aside>

        <!-- Main Document Body -->
        <div class="legal-content">
            <section id="introduction">
                <div class="legal-section-header">
                    <span class="legal-section-num">01</span>
                    <h2>Introduction &amp; Scope</h2>
                </div>
                <p>Welcome to Vipasa Yoga ("Vipasa", "we", "us", or "our"). We operate a digital health and live yoga session booking platform connecting clients with certified yoga instructors for interactive 1:1 and group virtual classes. This Privacy Policy governs your use of our platform, website, interactive features, and member services.</p>
                <p>By creating an account, booking a session, or utilizing any portion of our platform, you acknowledge that you have read, understood, and agreed to the data practices described in this document. If you do not agree with our privacy practices, please refrain from accessing or using our services.</p>
            </section>

            <section id="information-we-collect">
                <div class="legal-section-header">
                    <span class="legal-section-num">02</span>
                    <h2>Information We Collect</h2>
                </div>
                <p>To provide safe, personalized, and effective yoga instruction, we collect several categories of information from and about our users:</p>
                
                <h3>A. Information You Provide Directly</h3>
                <ul>
                    <li><strong>Account &amp; Registration Details:</strong> Full name, primary email address, mobile phone number, date of birth, gender, and avatar profile photo.</li>
                    <li><strong>OAuth Profile Data:</strong> If you authenticate via Google Sign-In, we receive your verified name, email address, profile picture URL, and account identifier from Google.</li>
                    <li><strong>Billing &amp; Payment Data:</strong> Payment transaction references, billing currency, card issuer metadata, subscription package details, and receipt history (full credit card numbers are handled securely by PCI-compliant payment gateways and never stored on our servers).</li>
                    <li><strong>Customer Support &amp; Communications:</strong> Records of inquiries, feedback, rating reviews, and support tickets submitted through our platform forms or official email channels.</li>
                </ul>

                <h3>B. Sensitive Health &amp; Physical Assessment Profile</h3>
                <p>Yoga is a physical practice. To ensure participant safety and allow instructors to tailor postures to your body, we collect specialized health and wellness profile information during onboarding and profile updates:</p>
                <ul>
                    <li>Physical capabilities, practice experience level, and yoga goals</li>
                    <li>Medical history alerts, ongoing injuries, past surgeries, or physical restrictions</li>
                    <li>Specific pain areas (e.g., lower back, neck, knees, shoulders)</li>
                    <li>Pregnancy status, trimester details, or postnatal recovery timeline</li>
                    <li>Emergency contact details (name, relationship, phone number)</li>
                </ul>

                <h3>C. Information Collected Automatically</h3>
                <ul>
                    <li><strong>Usage &amp; Telemetry Data:</strong> IP address, device operating system, browser type, page view duration, booking timestamps, and platform navigation flows.</li>
                    <li><strong>Live Session Metadata:</strong> Meeting attendance timestamps, session duration, and connection stability metrics required to audit service delivery.</li>
                </ul>
            </section>

            <section id="how-we-use-it">
                <div class="legal-section-header">
                    <span class="legal-section-num">03</span>
                    <h2>How We Process &amp; Use Your Information</h2>
                </div>
                <p>We process your data strictly for legitimate operational, safety, and service-fulfillment purposes:</p>
                <ul>
                    <li><strong>Personalized Class Matching:</strong> Matching your wellness goals and health restrictions with qualified, certified instructors.</li>
                    <li><strong>Live Class Delivery &amp; Scheduling:</strong> Generating secure video meeting links, handling slot reservations, and sending automated SMS/Email session reminders.</li>
                    <li><strong>Instructor Safety Clearance:</strong> Providing assigned instructors with essential health alerts so they can adapt poses and avoid contraindicated movements during your live class.</li>
                    <li><strong>Financial Transactions &amp; Invoicing:</strong> Processing drop-in session fees, package renewals, generating tax-compliant digital invoices, and managing refund requests.</li>
                    <li><strong>Platform Security &amp; Fraud Prevention:</strong> Protecting user accounts against unauthorized access, monitoring system uptime, and enforcing our Terms of Service.</li>
                    <li><strong>Legal &amp; Regulatory Compliance:</strong> Maintaining required accounting records, tax documentation, and safety audit logs.</li>
                </ul>
            </section>

            <section id="health-safeguards">
                <div class="legal-section-header">
                    <span class="legal-section-num">04</span>
                    <h2>Health Information Safeguards &amp; Access Scoping</h2>
                </div>
                <div class="legal-alert legal-alert--info">
                    <div class="legal-alert__title">Privacy Scoping Protection</div>
                    <p class="legal-alert__text">Your sensitive medical history and health assessment entries are strictly scoped. Instructors only see safety-critical alerts for students enrolled in their immediate upcoming session.</p>
                </div>
                <ul>
                    <li><strong>Scoped Instructor Access:</strong> Assigned instructors only receive safety-relevant notes (e.g., medical alerts, pain points, pregnancy stage, emergency contacts) for students enrolled in their specific upcoming sessions.</li>
                    <li><strong>No Financial or Credential Access:</strong> Instructors have zero access to your billing credentials, payment records, full profile notes, or private account credentials.</li>
                    <li><strong>Versioned Audit Logs:</strong> Every modification to your health profile creates an immutable audit log entry accessible only to system administrators for compliance verification.</li>
                    <li><strong>Client Ownership:</strong> You retain full control to view, update, or refine your health assessment notes at any time from your member dashboard.</li>
                </ul>
            </section>

            <section id="sharing-and-third-parties">
                <div class="legal-section-header">
                    <span class="legal-section-num">05</span>
                    <h2>Third-Party Data Sharing &amp; Processors</h2>
                </div>
                <div class="legal-alert legal-alert--success">
                    <div class="legal-alert__title">Zero Data Sale Guarantee</div>
                    <p class="legal-alert__text">Vipasa Yoga NEVER sells, rents, trades, or monetizes your personal or health data to third-party advertisers or data brokers.</p>
                </div>
                <ul>
                    <li><strong>Video Session Infrastructure:</strong> WebRTC and secure video meeting providers used to host live 1:1 and group classes.</li>
                    <li><strong>Payment Gateways:</strong> PCI-DSS Level 1 compliant processors (such as Stripe and Razorpay) for encrypted payment processing.</li>
                    <li><strong>Cloud Infrastructure &amp; Email Delivery:</strong> Encrypted cloud hosting and transactional email services under strict non-disclosure obligations.</li>
                    <li><strong>Legal Requirements:</strong> Disclosures enforced by valid court orders, subpoenas, or applicable laws, or to protect the physical safety of users and instructors.</li>
                </ul>
            </section>

            <section id="security">
                <div class="legal-section-header">
                    <span class="legal-section-num">06</span>
                    <h2>Data Security &amp; Protection Standards</h2>
                </div>
                <p>We employ robust technical and organizational security measures to protect your personal data:</p>
                <ul>
                    <li><strong>Encryption in Transit:</strong> All data transmitted between your browser and our servers is secured using modern TLS 1.3 / SSL encryption.</li>
                    <li><strong>Data Encryption at Rest:</strong> Sensitive database fields are encrypted at rest using AES-256 standards.</li>
                    <li><strong>Access Restrictions:</strong> Access to personal databases is restricted to authorized operations personnel via multi-factor authentication and role-based permissions.</li>
                </ul>
            </section>

            <section id="data-retention">
                <div class="legal-section-header">
                    <span class="legal-section-num">07</span>
                    <h2>Data Retention &amp; Account Closure</h2>
                </div>
                <p>We retain your personal data for as long as your account remains active or as needed to provide you with seamless booking services. If you request account closure:</p>
                <ul>
                    <li>Your account credentials, profile photo, and active booking records are permanently deactivated.</li>
                    <li>Certain transaction, payment, and tax records are retained for the minimum period mandated by accounting laws (typically 5 to 7 years).</li>
                    <li>De-identified, aggregated usage data may be preserved for internal analytics.</li>
                </ul>
            </section>

            <section id="your-rights">
                <div class="legal-section-header">
                    <span class="legal-section-num">08</span>
                    <h2>Your Rights &amp; Privacy Controls</h2>
                </div>
                <p>Depending on your jurisdiction, you hold fundamental privacy rights regarding your data:</p>
                <ul>
                    <li><strong>Right to Access:</strong> View all personal and health data stored in your account via your member dashboard.</li>
                    <li><strong>Right to Rectification:</strong> Edit or correct inaccurate contact, health, or profile information at any time.</li>
                    <li><strong>Right to Erasure:</strong> Request permanent deletion of your account and associated personal data.</li>
                    <li><strong>Right to Export:</strong> Download a copy of your personal activity and booking history.</li>
                </ul>
            </section>

            <section id="cookies">
                <div class="legal-section-header">
                    <span class="legal-section-num">09</span>
                    <h2>Cookie &amp; Tracking Policy</h2>
                </div>
                <p>Vipasa Yoga uses only essential first-party cookies necessary to maintain secure user sessions, authenticate sign-ins, and remember your visual interface preferences. We do <strong>NOT</strong> utilize invasive third-party ad trackers, cross-site profiling cookies, or behavioral advertising networks.</p>
            </section>

            <section id="childrens-privacy">
                <div class="legal-section-header">
                    <span class="legal-section-num">10</span>
                    <h2>Children's Privacy</h2>
                </div>
                <p>Our platform is intended for users who are at least 18 years of age (or the legal age of majority). Minors under the age of 18 may participate in specialized Kids or Prenatal/Family Yoga sessions only under the direct supervision and account consent of a parent or legal guardian.</p>
            </section>

            <section id="contact">
                <div class="legal-section-header">
                    <span class="legal-section-num">11</span>
                    <h2>Contact Us &amp; Privacy Enquiries</h2>
                </div>
                <p>If you have questions, concerns, or requests regarding this Privacy Policy or how your health and personal data are handled, please contact our Data Privacy Office:</p>
                
                <div class="legal-contact-card">
                    <div class="legal-contact-card__title">Vipasa Yoga Data Protection Office</div>
                    <div class="legal-contact-card__item">
                        <?= View::icon('mail', 'icon', 16) ?>
                        <span>Email: <a href="mailto:<?= View::e($contactEmail) ?>"><?= View::e($contactEmail) ?></a></span>
                    </div>
                    <div class="legal-contact-card__item">
                        <?= View::icon('phone', 'icon', 16) ?>
                        <span>Phone: <?= View::e($contactPhone) ?></span>
                    </div>
                    <div class="legal-contact-card__item">
                        <?= View::icon('map-pin', 'icon', 16) ?>
                        <span>Location: <?= View::e($contactLoc) ?></span>
                    </div>
                </div>
            </section>
        </div>

        <!-- Right Side Panel: Visual Yoga Showcase, Certified Instructors & Support Widgets -->
        <aside class="legal-side-column legal-side-column--right">
            <div class="legal-visual-card">
                <img src="/assets/img/client/philosophy-meditation.webp" alt="Mindful Yoga Practice" class="legal-visual-card__img">
                <div class="legal-visual-card__overlay">
                    <span class="legal-visual-card__tag">VIPASA WELLNESS</span>
                    <p class="legal-visual-card__caption">Safe, tailored practice with verified instructors worldwide.</p>
                </div>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Privacy Assistance</div>
                <p style="font-size:12px;color:var(--text-secondary);margin-bottom:var(--space-3)">Have questions about your health records or account privacy settings?</p>
                <a href="mailto:<?= View::e($contactEmail) ?>" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;font-size:12px">
                    <?= View::icon('mail', 'icon', 14) ?> Contact Privacy DPO
                </a>
            </div>

            <div class="legal-visual-card">
                <img src="/assets/img/client/pose-mountain-sunrise.jpg" alt="Sunrise Yoga Session" class="legal-visual-card__img">
                <div class="legal-visual-card__overlay">
                    <span class="legal-visual-card__tag">HEALTH SAFETY</span>
                    <p class="legal-visual-card__caption">1:1 posture guidance focused on injury prevention.</p>
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
                <img src="/assets/img/client/benefit-recovery.jpg" alt="Yoga Recovery Session" class="legal-visual-card__img">
                <div class="legal-visual-card__overlay">
                    <span class="legal-visual-card__tag">DATA CONFIDENTIALITY</span>
                    <p class="legal-visual-card__caption">Your health logs are kept 100% private to you &amp; your trainer.</p>
                </div>
            </div>
        </aside>
    </div>
</div>
