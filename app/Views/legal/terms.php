<?php

use App\Core\View;

$pageTitle = 'Terms of Service — Vipasa Yoga';
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
        <h1 class="legal-hero__title">Terms of Service</h1>
        <p class="legal-hero__subtitle">The binding legal terms governing your access to and use of Vipasa Yoga live sessions and platform services.</p>
        <span class="legal-hero__updated">
            <?= View::icon('file-text', 'legal-hero__icon', 14) ?>
            <span>Effective Date: <?= date('F j, Y') ?></span>
        </span>
    </div>

    <div class="legal-layout">
        <!-- Left Side Panel: Quick Navigation & Legal Compliance Badges -->
        <aside class="legal-side-column legal-side-column--left">
            <div class="legal-side-widget">
                <div class="legal-side-widget__title">On This Page</div>
                <nav class="legal-side-nav">
                    <a href="#acceptance" class="legal-side-nav__link">1. Agreement</a>
                    <a href="#eligibility-and-account" class="legal-side-nav__link">2. Eligibility</a>
                    <a href="#live-sessions" class="legal-side-nav__link">3. Session Conduct</a>
                    <a href="#medical-disclaimer" class="legal-side-nav__link">4. Health Disclaimer</a>
                    <a href="#bookings-and-financials" class="legal-side-nav__link">5. Bookings &amp; Rates</a>
                    <a href="#intellectual-property" class="legal-side-nav__link">6. IP Rights</a>
                    <a href="#acceptable-use" class="legal-side-nav__link">7. Acceptable Use</a>
                    <a href="#limitation-of-liability" class="legal-side-nav__link">8. Liability Limits</a>
                    <a href="#contact" class="legal-side-nav__link">11. Legal Inquiries</a>
                </nav>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Compliance &amp; Terms</div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon">✓</div>
                    <span class="legal-badge-pill__text">Binding Service Terms</span>
                </div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon">✓</div>
                    <span class="legal-badge-pill__text">Certified Instructors</span>
                </div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon">✓</div>
                    <span class="legal-badge-pill__text">Zero Harassment Code</span>
                </div>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Support Hours</div>
                <div class="legal-status-header">
                    <span class="legal-status-dot"></span>
                    <span>Live Support Active</span>
                </div>
                <p class="legal-side-meta">Daily: 6:00 AM – 10:00 PM IST<br>Direct Email: <?= View::e($contactEmail) ?></p>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Security Standards</div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon" style="background:rgba(59,130,246,0.12);color:#3B82F6">🔒</div>
                    <span class="legal-badge-pill__text">256-Bit SSL Encrypted</span>
                </div>
                <div class="legal-badge-pill">
                    <div class="legal-badge-pill__icon" style="background:rgba(59,130,246,0.12);color:#3B82F6">🛡️</div>
                    <span class="legal-badge-pill__text">Strict Privacy Protocol</span>
                </div>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Quick Navigation</div>
                <nav class="legal-side-nav">
                    <a href="/privacy-policy" class="legal-side-nav__link">🔒 Privacy Policy</a>
                    <a href="/refund-policy" class="legal-side-nav__link">💳 Refund Policy</a>
                    <a href="/instructors" class="legal-side-nav__link">🧘 View Trainers</a>
                </nav>
            </div>
        </aside>

        <!-- Main Document Body -->
        <div class="legal-content">
            <section id="acceptance">
                <div class="legal-section-header">
                    <span class="legal-section-num">01</span>
                    <h2>Agreement to Terms</h2>
                </div>
                <p>These Terms of Service ("Terms") constitute a legally binding agreement between you ("User", "Client", or "you") and Vipasa Yoga ("Vipasa", "we", "us", or "our"). These Terms govern your access to and use of our website, mobile interface, booking platform, live 1:1 and group virtual yoga sessions, and related member services.</p>
                <p>By creating an account, booking a session, making a payment, or clicking "I Agree", you explicitly consent to be bound by these Terms and our <a href="/privacy-policy">Privacy Policy</a>. If you do not agree to all of these Terms, you are expressly prohibited from using the platform and must discontinue use immediately.</p>
            </section>

            <section id="eligibility-and-account">
                <div class="legal-section-header">
                    <span class="legal-section-num">02</span>
                    <h2>Account Eligibility &amp; Security</h2>
                </div>
                <p>To register an account and book sessions on Vipasa Yoga, you must fulfill the following requirements:</p>
                <ul>
                    <li><strong>Age Requirement:</strong> You must be at least 18 years old (or the legal age of majority in your jurisdiction) to establish an account independently. Minors under 18 may participate only under the direct supervision and authorization of a parent or legal guardian.</li>
                    <li><strong>Account Authenticity:</strong> Client accounts are registered via email or Google OAuth authentication. You agree to provide true, accurate, and current information during registration and health assessment onboarding.</li>
                    <li><strong>Credential Confidentiality:</strong> You are solely responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. You agree to notify us immediately of any unauthorized account access.</li>
                    <li><strong>Prohibition of Account Sharing:</strong> Each membership account is personal and non-transferable. Sharing account access with third parties to access live classes is strictly prohibited.</li>
                </ul>
            </section>

            <section id="live-sessions">
                <div class="legal-section-header">
                    <span class="legal-section-num">03</span>
                    <h2>Live Sessions &amp; Code of Conduct</h2>
                </div>
                <p>Vipasa Yoga provides real-time, interactive virtual video sessions conducted by certified yoga instructors. To maintain a safe, respectful, and high-quality environment for all participants, you agree to adhere to the following session guidelines:</p>
                <ul>
                    <li><strong>Punctuality &amp; Technical Preparation:</strong> Session join links become active in your member dashboard shortly before the scheduled start time. You are responsible for maintaining a stable internet connection, functional camera, and proper space for practice.</li>
                    <li><strong>Audio/Video Decorum:</strong> Cameras must be enabled during 1:1 sessions so instructors can observe postures and provide safety modifications. For group classes, proper decorum and clothing are required at all times.</li>
                    <li><strong>Prohibition of Unconsented Recording:</strong> Recording, screen-capturing, broadcasting, or distributing video sessions, audio, or instructor commentary is strictly prohibited without explicit written consent from Vipasa Yoga and all participants.</li>
                    <li><strong>Zero-Tolerance Harassment Policy:</strong> Any disruptive, abusive, inappropriate, obscene, or offensive behavior toward instructors, staff, or fellow participants will result in immediate termination of the session and permanent account suspension without refund.</li>
                </ul>
            </section>

            <section id="medical-disclaimer">
                <div class="legal-section-header">
                    <span class="legal-section-num">04</span>
                    <h2>Physical Activity &amp; Medical Disclaimer (Crucial)</h2>
                </div>
                <div class="legal-alert legal-alert--warning">
                    <div class="legal-alert__title">Mandatory Physical Practice &amp; Liability Release Notice</div>
                    <p class="legal-alert__text">Yoga involves physical movement, breath control, stretching, and physical exertion which carry inherent risks of personal injury. Please review this disclaimer carefully before joining any session.</p>
                </div>
                <ul>
                    <li><strong>Not Medical Advice:</strong> Vipasa Yoga and its instructors provide wellness guidance, physical posture instruction, and relaxation techniques. We do <strong>NOT</strong> provide medical diagnosis, medical treatment, or clinical physical therapy. No content or session should be construed as medical advice.</li>
                    <li><strong>Obligation to Consult a Physician:</strong> You are strongly advised to consult a qualified physician or healthcare provider before embarking on any new physical activity or yoga program, particularly if you are pregnant, nursing, recovering from surgery, managing chronic pain, or taking medication.</li>
                    <li><strong>Truthful Health Disclosure:</strong> You agree to accurately declare all medical conditions, physical limitations, injuries, and pain points in your health profile before attending a class.</li>
                    <li><strong>Assumption of Risk &amp; Waiver:</strong> By joining a session, you voluntarily assume all known and unknown risks associated with physical exertion. You hereby release and hold harmless Vipasa Yoga, its founders, employees, and independent instructors from any and all liability, claims, or damages arising from personal injury, illness, or property loss sustained during or after a session.</li>
                </ul>
            </section>

            <section id="bookings-and-financials">
                <div class="legal-section-header">
                    <span class="legal-section-num">05</span>
                    <h2>Bookings, Packages &amp; Subscription Terms</h2>
                </div>
                <ul>
                    <li><strong>Pricing &amp; Currency:</strong> All class fees, single drop-ins, weekly packages, and monthly membership rates are clearly displayed prior to checkout. All payments are charged in the displayed currency.</li>
                    <li><strong>Slot Reservations:</strong> A slot booking is confirmed only upon successful payment authorization or credit deduction from an active package.</li>
                    <li><strong>Package Validity &amp; Expiration:</strong> Session credits included in weekly or monthly packages must be utilized within their specified validity window. Unused session credits automatically expire at the end of the package period unless renewed.</li>
                    <li><strong>Refunds &amp; Cancellations:</strong> Session cancellations, instructor cancellations, and refund eligibility are governed strictly by our dedicated <a href="/refund-policy">Refund Policy</a>.</li>
                </ul>
            </section>

            <section id="intellectual-property">
                <div class="legal-section-header">
                    <span class="legal-section-num">06</span>
                    <h2>Intellectual Property Rights</h2>
                </div>
                <p>The Vipasa Yoga brand, logo, trademark, domain name, website code, instructional graphics, class curricula, and proprietary content are the exclusive intellectual property of Vipasa Yoga and its licensors. You are granted a limited, non-exclusive, non-transferable license to access the platform solely for personal, non-commercial use.</p>
            </section>

            <section id="acceptable-use">
                <div class="legal-section-header">
                    <span class="legal-section-num">07</span>
                    <h2>Acceptable Platform Use &amp; Prohibited Conduct</h2>
                </div>
                <p>You agree not to engage in any prohibited platform activities, including but not limited to:</p>
                <ul>
                    <li>Attempting to reverse-engineer, decompile, or copy platform software code</li>
                    <li>Using automated bots, web scrapers, or scripts to extract user data or instructor schedules</li>
                    <li>Bypassing session link security or sharing join links with non-registered individuals</li>
                    <li>Submitting fraudulent health or identity information during onboarding</li>
                </ul>
            </section>

            <section id="limitation-of-liability">
                <div class="legal-section-header">
                    <span class="legal-section-num">08</span>
                    <h2>Limitation of Liability</h2>
                </div>
                <p>To the maximum extent permitted by applicable law, Vipasa Yoga, its officers, directors, employees, and instructors shall not be liable for any indirect, incidental, special, consequential, or punitive damages—including loss of profits, loss of data, personal injury, or emotional distress—arising out of or in connection with your use of the platform or live sessions.</p>
                <p>In no event shall our total aggregate liability for all claims related to the service exceed the total amount paid by you to Vipasa Yoga in the three (3) months preceding the event giving rise to the claim.</p>
            </section>

            <section id="indemnification">
                <div class="legal-section-header">
                    <span class="legal-section-num">09</span>
                    <h2>Indemnification</h2>
                </div>
                <p>You agree to defend, indemnify, and hold harmless Vipasa Yoga and its officers, directors, employees, contractors, and instructors from and against any third-party claims, liabilities, losses, costs, or expenses (including reasonable legal fees) resulting from your breach of these Terms, your physical participation in sessions, or your violation of any third-party rights.</p>
            </section>

            <section id="modifications-and-governing-law">
                <div class="legal-section-header">
                    <span class="legal-section-num">10</span>
                    <h2>Modifications to Terms &amp; Governing Law</h2>
                </div>
                <p>We reserve the right to modify or replace these Terms at any time. Material changes will be updated on this page with a revised "Effective Date". Your continued use of the platform following the posting of modifications constitutes acceptance of the revised Terms.</p>
                <p>These Terms are governed by and construed in accordance with the laws of India, without regard to its conflict of law principles. Any legal disputes arising hereunder shall be subject to the exclusive jurisdiction of the competent courts in India.</p>
            </section>

            <section id="contact">
                <div class="legal-section-header">
                    <span class="legal-section-num">11</span>
                    <h2>Contact &amp; Legal Questions</h2>
                </div>
                <p>If you have any questions, clarifications, or formal notices regarding these Terms of Service, please contact our Legal &amp; Compliance Office:</p>
                
                <div class="legal-contact-card">
                    <div class="legal-contact-card__title">Vipasa Yoga Legal &amp; Compliance Office</div>
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
                <img src="/assets/img/client/pose-mountain-sunrise.jpg" alt="Sunrise Yoga Session" class="legal-visual-card__img">
                <div class="legal-visual-card__overlay">
                    <span class="legal-visual-card__tag">VIPASA YOGA</span>
                    <p class="legal-visual-card__caption">Flow like a river with certified live 1:1 guidance.</p>
                </div>
            </div>

            <div class="legal-side-widget">
                <div class="legal-side-widget__title">Need Clarification?</div>
                <p style="font-size:12px;color:var(--text-secondary);margin-bottom:var(--space-3)">Our legal team is available to assist with service agreement questions.</p>
                <a href="mailto:<?= View::e($contactEmail) ?>" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;font-size:12px">
                    <?= View::icon('mail', 'icon', 14) ?> Contact Legal Office
                </a>
            </div>

            <div class="legal-visual-card">
                <img src="/assets/img/client/about-journey.webp" alt="Yoga Practice Journey" class="legal-visual-card__img">
                <div class="legal-visual-card__overlay">
                    <span class="legal-visual-card__tag">MINDFUL PRACTICE</span>
                    <p class="legal-visual-card__caption">Personalized live sessions tailored to your pace.</p>
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
                <img src="/assets/img/client/pose-lotus-meditation.jpg" alt="Meditation Practice" class="legal-visual-card__img">
                <div class="legal-visual-card__overlay">
                    <span class="legal-visual-card__tag">INNER HARMONY</span>
                    <p class="legal-visual-card__caption">Breathwork, meditation, and restorative alignment.</p>
                </div>
            </div>
        </aside>
    </div>
</div>
