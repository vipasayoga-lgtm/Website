<?php

use App\Core\View;

$pageTitle = 'Settings — Vipasa Yoga Admin';
$pageCss = 'admin';
$portal = 'admin';
$active = 'settings';
?>
<div class="admin-page container">
    <div class="admin-hero flex items-center justify-between flex-wrap gap-3 mb-4" style="flex-direction:row;text-align:left">
        <div>
            <h1 class="admin-hero__title" style="font-size:1.15rem;font-weight:700;margin:0">Platform Settings</h1>
            <p class="admin-hero__subtitle" style="font-size:0.8rem;color:var(--text-muted);margin:2px 0 0">Configure platform-wide settings for payments, email delivery, authentication, and general site defaults.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/admin/settings/backup/download" class="btn btn-primary btn-sm flex items-center gap-2" style="background:#0284c7;color:#ffffff;text-decoration:none;padding:9px 18px;border-radius:var(--radius-md);font-weight:600;font-size:0.85rem;box-shadow:0 4px 12px rgba(2,132,199,0.25)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                <span>Download Database Backup</span>
            </a>
        </div>
    </div>

    <div class="admin-settings" id="settings-tabs">
        <!-- Clean Tab Nav with wrap so all tabs are visible -->
        <div class="tab-nav clean-tab-nav" style="display:flex;flex-wrap:wrap;gap:6px;border-bottom:1px solid var(--border-subtle);margin-bottom:20px;padding-bottom:6px">
            <button type="button" class="tab-nav__item is-active" data-tab-target="tab-general" style="font-size:0.85rem;padding:8px 16px;border-radius:var(--radius-md)">General</button>
            <button type="button" class="tab-nav__item" data-tab-target="tab-website" style="font-size:0.85rem;padding:8px 16px;border-radius:var(--radius-md)">Website &amp; Theme</button>
            <button type="button" class="tab-nav__item" data-tab-target="tab-google" style="font-size:0.85rem;padding:8px 16px;border-radius:var(--radius-md)">Google Login</button>
            <button type="button" class="tab-nav__item" data-tab-target="tab-payments" style="font-size:0.85rem;padding:8px 16px;border-radius:var(--radius-md)">Payment Gateway</button>
            <button type="button" class="tab-nav__item" data-tab-target="tab-mail" style="font-size:0.85rem;padding:8px 16px;border-radius:var(--radius-md)">SMTP / Email</button>
            <button type="button" class="tab-nav__item" data-tab-target="tab-security" style="font-size:0.85rem;padding:8px 16px;border-radius:var(--radius-md)">Security</button>
            <button type="button" class="tab-nav__item" data-tab-target="tab-backup" style="font-size:0.85rem;padding:8px 16px;border-radius:var(--radius-md);background:#f0fdf4;color:#166534;font-weight:600;border:1px solid #bbf7d0">📥 Database &amp; Backup</button>
        </div>

        <!-- General Tab -->
        <div id="tab-general" class="tab-panel admin-panel card" style="background:#ffffff;border:1px solid var(--border-subtle);padding:24px;border-radius:var(--radius-xl)">
            <h2 style="font-size:1.05rem;font-weight:700;margin:0 0 18px;color:var(--text-primary)">General Platform Settings</h2>
            <form id="settings-form">
                <div class="flex flex-col gap-4">
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Site Name</label>
                        <input type="text" name="site_name" class="form-group__control" required maxlength="150" value="<?= View::e((string) $settings['site_name']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Tax Percent (%)</label>
                        <input type="number" name="tax_percent" class="form-group__control" required min="0" step="0.01" value="<?= View::e((string) $settings['tax_percent']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Default Currency</label>
                        <input type="text" name="default_currency" class="form-group__control" required maxlength="3" style="text-transform:uppercase;width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)" value="<?= View::e((string) $settings['default_currency']) ?>">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Default Timezone</label>
                        <input type="text" name="default_timezone" class="form-group__control" required maxlength="60" value="<?= View::e((string) $settings['default_timezone']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div class="text-error" id="settings-form-error" style="font-size:0.8rem"></div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit" class="btn btn-primary btn-sm" style="padding:9px 20px;border-radius:var(--radius-md);font-weight:600">Save General Settings</button>
                </div>
            </form>
        </div>

        <!-- Website & Theme Tab -->
        <div id="tab-website" class="tab-panel admin-panel card" style="background:#ffffff;border:1px solid var(--border-subtle);padding:24px;border-radius:var(--radius-xl)" hidden>
            <h2 style="font-size:1.05rem;font-weight:700;margin:0 0 18px;color:var(--text-primary)">Website Branding &amp; Social Links</h2>
            <div class="flex items-center gap-4 mb-6 pb-4" style="border-bottom:1px solid var(--border-subtle)">
                <?php if ($settings['site_logo_path']): ?>
                <img src="<?= View::e($settings['site_logo_path']) ?>" alt="Site Logo" style="height:42px;object-fit:contain">
                <?php else: ?>
                <span class="text-muted" style="font-size:0.8rem">No logo uploaded yet.</span>
                <?php endif; ?>
                <label class="btn btn-secondary btn-sm" style="cursor:pointer;border-radius:var(--radius-md);font-size:0.8rem">
                    Upload Logo
                    <input type="file" id="logo-input" accept="image/jpeg,image/png,image/webp,image/svg+xml" style="display:none">
                </label>
            </div>
            <form id="website-form">
                <div class="flex flex-col gap-4">
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Facebook URL</label>
                        <input type="text" name="social_facebook" class="form-group__control" value="<?= View::e($settings['social_facebook']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Instagram URL</label>
                        <input type="text" name="social_instagram" class="form-group__control" value="<?= View::e($settings['social_instagram']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">X / Twitter URL</label>
                        <input type="text" name="social_twitter" class="form-group__control" value="<?= View::e($settings['social_twitter']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">YouTube URL</label>
                        <input type="text" name="social_youtube" class="form-group__control" value="<?= View::e($settings['social_youtube']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Default Language</label>
                        <select name="default_language" class="form-group__control" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                            <option value="en" <?= $settings['default_language'] === 'en' ? 'selected' : '' ?>>English</option>
                            <option value="hi" <?= $settings['default_language'] === 'hi' ? 'selected' : '' ?>>Hindi</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit" class="btn btn-primary btn-sm" style="padding:9px 20px;border-radius:var(--radius-md);font-weight:600">Save Website Settings</button>
                </div>
            </form>
        </div>

        <!-- Google Login Tab -->
        <div id="tab-google" class="tab-panel admin-panel card" style="background:#ffffff;border:1px solid var(--border-subtle);padding:24px;border-radius:var(--radius-xl)" hidden>
            <h2 style="font-size:1.05rem;font-weight:700;margin:0 0 18px;color:var(--text-primary)">Google OAuth Credentials</h2>
            <form id="google-form">
                <div class="flex flex-col gap-4">
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Google Client ID</label>
                        <input type="text" name="client_id" class="form-group__control" value="<?= View::e($settings['google_client_id']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Google Client Secret</label>
                        <input type="password" name="client_secret" class="form-group__control" placeholder="<?= $settings['google_client_secret_set'] ? '••••••••  (leave blank to keep current secret)' : 'Enter Client Secret' ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Redirect URI</label>
                        <input type="text" name="redirect_uri" class="form-group__control" value="<?= View::e($settings['google_redirect_uri']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit" class="btn btn-primary btn-sm" style="padding:9px 20px;border-radius:var(--radius-md);font-weight:600">Save Google Credentials</button>
                </div>
            </form>
        </div>

        <!-- Payment Gateway Tab -->
        <div id="tab-payments" class="tab-panel admin-panel card" style="background:#ffffff;border:1px solid var(--border-subtle);padding:24px;border-radius:var(--radius-xl)" hidden>
            <h2 style="font-size:1.05rem;font-weight:700;margin:0 0 18px;color:var(--text-primary)">Payment Gateway Configuration</h2>
            <form id="payments-form">
                <div class="flex flex-col gap-4">
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Active Payment Gateway</label>
                        <select name="payment_gateway" class="form-group__control" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                            <option value="upi_qr" <?= in_array($settings['payment_gateway'], ['upi_qr', 'upi'], true) ? 'selected' : '' ?>>UPI QR Code (Manual Scan &amp; Pay)</option>
                            <option value="razorpay" <?= $settings['payment_gateway'] === 'razorpay' ? 'selected' : '' ?>>Razorpay</option>
                            <option value="stripe" <?= $settings['payment_gateway'] === 'stripe' ? 'selected' : '' ?>>Stripe</option>
                        </select>
                    </div>
                    <div style="background:var(--surface-bg-alt,#f8fafc);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);padding:16px">
                        <h4 style="font-size:0.9rem;font-weight:700;margin:0 0 12px;color:var(--text-primary)">UPI QR Configuration</h4>
                        <div class="flex flex-col gap-3">
                            <div>
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">UPI ID (VPA)</label>
                                <input type="text" name="upi_id" class="form-group__control" value="<?= View::e($settings['upi_id'] ?? 'vijayaparameshh@oksbi') ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                            </div>
                            <div>
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Payee Display Name</label>
                                <input type="text" name="upi_name" class="form-group__control" value="<?= View::e($settings['upi_name'] ?? 'Vijaya Parameswaran') ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                            </div>
                            <div>
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">WhatsApp Screenshot Number</label>
                                <input type="text" name="upi_phone" class="form-group__control" value="<?= View::e($settings['upi_phone'] ?? '+91 70127 75990') ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <img src="/assets/img/payment-qr.jpg" alt="Active QR Code" style="width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid var(--border-subtle)">
                                <span class="text-muted" style="font-size:0.75rem">Active QR Image: <code>/assets/img/payment-qr.jpg</code></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Razorpay Key ID</label>
                        <input type="text" name="razorpay_key_id" class="form-group__control" value="<?= View::e($settings['razorpay_key_id']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Razorpay Key Secret</label>
                        <input type="password" name="razorpay_key_secret" class="form-group__control" placeholder="<?= $settings['razorpay_key_secret_set'] ? '••••••••  (leave blank to keep current secret)' : 'Enter Key Secret' ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Stripe Public Key</label>
                        <input type="text" name="stripe_public_key" class="form-group__control" value="<?= View::e($settings['stripe_public_key']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Stripe Secret Key</label>
                        <input type="password" name="stripe_secret_key" class="form-group__control" placeholder="<?= $settings['stripe_secret_key_set'] ? '••••••••  (leave blank to keep current secret)' : 'Enter Secret Key' ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit" class="btn btn-primary btn-sm" style="padding:9px 20px;border-radius:var(--radius-md);font-weight:600">Save Payment Credentials</button>
                </div>
            </form>
        </div>

        <!-- SMTP / Email Tab -->
        <div id="tab-mail" class="tab-panel admin-panel card" style="background:#ffffff;border:1px solid var(--border-subtle);padding:24px;border-radius:var(--radius-xl)" hidden>
            <h2 style="font-size:1.05rem;font-weight:700;margin:0 0 18px;color:var(--text-primary)">SMTP / Email Configuration</h2>
            <form id="mail-form">
                <div class="flex flex-col gap-4">
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Mail Driver</label>
                        <select name="mail_driver" class="form-group__control" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                            <option value="log" <?= $settings['mail_driver'] === 'log' ? 'selected' : '' ?>>Log to file (dev)</option>
                            <option value="smtp" <?= $settings['mail_driver'] === 'smtp' ? 'selected' : '' ?>>SMTP</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">SMTP Host</label>
                        <input type="text" name="mail_host" class="form-group__control" value="<?= View::e($settings['mail_host']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">SMTP Port</label>
                        <input type="number" name="mail_port" class="form-group__control" value="<?= View::e((string) $settings['mail_port']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">SMTP Username</label>
                        <input type="text" name="mail_username" class="form-group__control" value="<?= View::e($settings['mail_username']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">SMTP Password</label>
                        <input type="password" name="mail_password" class="form-group__control" placeholder="<?= $settings['mail_password_set'] ? '••••••••  (leave blank to keep current password)' : 'Enter SMTP Password' ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">From Address</label>
                        <input type="text" name="mail_from_address" class="form-group__control" value="<?= View::e($settings['mail_from_address']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">From Name</label>
                        <input type="text" name="mail_from_name" class="form-group__control" value="<?= View::e($settings['mail_from_name']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit" class="btn btn-primary btn-sm" style="padding:9px 20px;border-radius:var(--radius-md);font-weight:600">Save SMTP Settings</button>
                </div>
            </form>
        </div>

        <!-- Security Tab -->
        <div id="tab-security" class="tab-panel admin-panel card" style="background:#ffffff;border:1px solid var(--border-subtle);padding:24px;border-radius:var(--radius-xl)" hidden>
            <h2 style="font-size:1.05rem;font-weight:700;margin:0 0 18px;color:var(--text-primary)">Security &amp; Session Configuration</h2>
            <form id="security-form">
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Session Lifetime (Minutes)</label>
                    <input type="number" name="session_lifetime_minutes" class="form-group__control" min="5" value="<?= View::e((string) $settings['session_lifetime_minutes']) ?>" style="width:100%;border-radius:var(--radius-md);padding:9px 12px;border:1px solid var(--border-default)">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit" class="btn btn-primary btn-sm" style="padding:9px 20px;border-radius:var(--radius-md);font-weight:600">Save Security Settings</button>
                </div>
            </form>
        </div>

        <!-- Database & Backup Tab -->
        <div id="tab-backup" class="tab-panel admin-panel card" style="background:#ffffff;border:1px solid var(--border-subtle);padding:24px;border-radius:var(--radius-xl)" hidden>
            <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                <div>
                    <h2 style="font-size:1.05rem;font-weight:700;margin:0;color:var(--text-primary)">Database Management &amp; Backups</h2>
                    <p style="font-size:0.8rem;color:var(--text-muted);margin:3px 0 0">Safeguard your yoga studio data with instant SQL exports and automated daily snapshots.</p>
                </div>
                <a href="/admin/settings/backup/download" class="btn btn-primary btn-sm flex items-center gap-2" style="padding:9px 18px;border-radius:var(--radius-md);text-decoration:none;font-weight:600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Download Instant SQL Backup
                </a>
            </div>

            <!-- Database Info Cards -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:14px;margin-bottom:20px">
                <div style="background:var(--surface-sunken,#f8fafc);border:1px solid var(--border-subtle);border-radius:var(--radius-md);padding:14px">
                    <span style="font-size:0.75rem;color:var(--text-muted);display:block;text-transform:uppercase;letter-spacing:0.5px">Active Database</span>
                    <strong style="font-size:1rem;color:var(--text-primary);display:block;margin-top:4px"><?= View::e((string)($dbInfo['database'] ?? 'vipasa_yoga')) ?></strong>
                </div>
                <div style="background:var(--surface-sunken,#f8fafc);border:1px solid var(--border-subtle);border-radius:var(--radius-md);padding:14px">
                    <span style="font-size:0.75rem;color:var(--text-muted);display:block;text-transform:uppercase;letter-spacing:0.5px">Database Host</span>
                    <strong style="font-size:1rem;color:var(--text-primary);display:block;margin-top:4px"><?= View::e((string)($dbInfo['host'] ?? '127.0.0.1')) ?></strong>
                </div>
                <div style="background:var(--surface-sunken,#f8fafc);border:1px solid var(--border-subtle);border-radius:var(--radius-md);padding:14px">
                    <span style="font-size:0.75rem;color:var(--text-muted);display:block;text-transform:uppercase;letter-spacing:0.5px">Total Tables</span>
                    <strong style="font-size:1rem;color:var(--primary,#0284c7);display:block;margin-top:4px"><?= (int)($dbInfo['tables'] ?? 0) ?> Tables</strong>
                </div>
            </div>

            <!-- Stored Backups -->
            <div style="margin-top:20px">
                <h3 style="font-size:0.9rem;font-weight:700;margin:0 0 10px;color:var(--text-primary)">Stored Local Backups (Auto-Retained in storage/backups/)</h3>
                <?php if (!empty($dbInfo['recent_backups'])): ?>
                <div style="border:1px solid var(--border-subtle);border-radius:var(--radius-md);overflow:hidden">
                    <table style="width:100%;border-collapse:collapse;font-size:0.8rem">
                        <thead>
                            <tr style="background:var(--surface-sunken,#f8fafc);border-bottom:1px solid var(--border-subtle);text-align:left">
                                <th style="padding:10px 14px;color:var(--text-muted);font-weight:600">Filename</th>
                                <th style="padding:10px 14px;color:var(--text-muted);font-weight:600">Size</th>
                                <th style="padding:10px 14px;color:var(--text-muted);font-weight:600">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dbInfo['recent_backups'] as $b): ?>
                            <tr style="border-bottom:1px solid var(--border-subtle)">
                                <td style="padding:10px 14px;font-family:monospace;color:var(--text-primary)"><?= View::e($b['name']) ?></td>
                                <td style="padding:10px 14px;color:var(--text-muted)"><?= View::e($b['size']) ?></td>
                                <td style="padding:10px 14px;color:var(--text-muted)"><?= View::e($b['time']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p style="font-size:0.8rem;color:var(--text-muted);margin:0">No local backups stored yet. Click "Download Instant SQL Backup" above to generate a snapshot.</p>
                <?php endif; ?>
            </div>

            <!-- Cron setup info -->
            <div style="margin-top:24px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:var(--radius-md);padding:14px 16px">
                <h4 style="font-size:0.85rem;font-weight:700;color:#166534;margin:0 0 6px">Automated Daily Backups (cPanel Cron Command)</h4>
                <p style="font-size:0.8rem;color:#15803d;margin:0 0 8px">To automate daily backups at midnight on your production server, add this Cron Job in cPanel or your Linux server:</p>
                <code style="display:block;background:#ffffff;border:1px solid #86efac;padding:8px 12px;border-radius:4px;font-size:0.75rem;color:#14532d;word-break:break-all">0 0 * * * /usr/bin/php <?= dirname(__DIR__, 2) ?>/bin/backup-database.php &gt; /dev/null 2&gt;&amp;1</code>
            </div>
        </div>
    </div>
</div>

<script type="module">
import { initTabs } from '/assets/js/modules/tabs.js';
import { apiPut } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

initTabs(document.getElementById('settings-tabs'));

const form = document.getElementById('settings-form');
const errorBox = document.getElementById('settings-form-error');

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    errorBox.textContent = '';

    const payload = {
        site_name: form.site_name.value.trim(),
        tax_percent: Number(form.tax_percent.value),
        default_currency: form.default_currency.value.trim().toUpperCase(),
        default_timezone: form.default_timezone.value.trim(),
    };

    try {
        await apiPut('/admin/settings', payload);
        toast.success('Settings updated.');
    } catch (err) {
        errorBox.textContent = err.message;
        toast.error(err.message);
    }
});

document.getElementById('website-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const f = event.target;
    try {
        await apiPut('/admin/settings/website', {
            social_facebook: f.social_facebook.value.trim(),
            social_instagram: f.social_instagram.value.trim(),
            social_twitter: f.social_twitter.value.trim(),
            social_youtube: f.social_youtube.value.trim(),
            default_language: f.default_language.value,
        });
        toast.success('Website settings updated.');
    } catch (err) {
        toast.error(err.message);
    }
});

document.getElementById('logo-input').addEventListener('change', async (event) => {
    const file = event.target.files[0];
    if (!file) return;
    const formData = new FormData();
    formData.append('logo', file);
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    try {
        const response = await fetch('/admin/settings/logo', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-CSRF-Token': csrf },
            body: formData,
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Upload failed.');
        window.location.reload();
    } catch (err) {
        toast.error(err.message);
    }
});

document.getElementById('google-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const f = event.target;
    try {
        await apiPut('/admin/settings/google', {
            client_id: f.client_id.value.trim(),
            client_secret: f.client_secret.value,
            redirect_uri: f.redirect_uri.value.trim(),
        });
        toast.success('Google settings updated.');
        f.client_secret.value = '';
    } catch (err) {
        toast.error(err.message);
    }
});

document.getElementById('payments-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const f = event.target;
    try {
        await apiPut('/admin/settings/payments', {
            payment_gateway: f.payment_gateway.value,
            upi_id: f.upi_id.value.trim(),
            upi_name: f.upi_name.value.trim(),
            upi_phone: f.upi_phone.value.trim(),
            razorpay_key_id: f.razorpay_key_id.value.trim(),
            razorpay_key_secret: f.razorpay_key_secret.value,
            stripe_public_key: f.stripe_public_key.value.trim(),
            stripe_secret_key: f.stripe_secret_key.value,
        });
        toast.success('Payment settings updated.');
        f.razorpay_key_secret.value = '';
        f.stripe_secret_key.value = '';
    } catch (err) {
        toast.error(err.message);
    }
});

document.getElementById('mail-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const f = event.target;
    try {
        await apiPut('/admin/settings/mail', {
            mail_driver: f.mail_driver.value,
            mail_host: f.mail_host.value.trim(),
            mail_port: Number(f.mail_port.value),
            mail_username: f.mail_username.value.trim(),
            mail_password: f.mail_password.value,
            mail_from_address: f.mail_from_address.value.trim(),
            mail_from_name: f.mail_from_name.value.trim(),
        });
        toast.success('SMTP settings updated.');
        f.mail_password.value = '';
    } catch (err) {
        toast.error(err.message);
    }
});

document.getElementById('security-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const f = event.target;
    try {
        await apiPut('/admin/settings/security', {
            session_lifetime_minutes: Number(f.session_lifetime_minutes.value),
        });
        toast.success('Security settings updated.');
    } catch (err) {
        toast.error(err.message);
    }
});
</script>
