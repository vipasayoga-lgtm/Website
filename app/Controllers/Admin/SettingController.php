<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Env;
use App\Core\Request;
use App\Models\ActivityLog;
use App\Models\Setting;

final class SettingController extends Controller
{
    private const MAX_LOGO_BYTES = 2 * 1024 * 1024;
    private const ALLOWED_LOGO_MIMES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/svg+xml' => 'svg'];

    public function page(Request $request): void
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'Vipasa Yoga'),
            'tax_percent' => Setting::get('tax_percent', 5),
            'default_currency' => Setting::get('default_currency', 'INR'),
            'default_timezone' => Setting::get('default_timezone', 'Asia/Kolkata'),
            'site_logo_path' => Setting::get('site_logo_path', ''),
            'social_facebook' => Setting::get('social_facebook', ''),
            'social_instagram' => Setting::get('social_instagram', ''),
            'social_twitter' => Setting::get('social_twitter', ''),
            'social_youtube' => Setting::get('social_youtube', ''),
            'default_language' => Setting::get('default_language', 'en'),
            'google_client_id' => Setting::get('google_client_id', ''),
            'google_client_secret_set' => Setting::get('google_client_secret', '') !== '',
            'google_redirect_uri' => Setting::get('google_redirect_uri', ''),
            'payment_gateway' => Setting::get('payment_gateway', 'upi_qr'),
            'upi_id' => Setting::get('upi_id', 'vijayaparameshh@oksbi'),
            'upi_name' => Setting::get('upi_name', 'Vijaya Parameswaran'),
            'upi_phone' => Setting::get('upi_phone', '+91 70127 75990'),
            'upi_qr_image' => Setting::get('upi_qr_image', '/assets/img/payment-qr.jpg'),
            'razorpay_key_id' => Setting::get('razorpay_key_id', ''),
            'razorpay_key_secret_set' => Setting::get('razorpay_key_secret', '') !== '',
            'stripe_public_key' => Setting::get('stripe_public_key', ''),
            'stripe_secret_key_set' => Setting::get('stripe_secret_key', '') !== '',
            'mail_driver' => Setting::get('mail_driver', 'log'),
            'mail_host' => Setting::get('mail_host', ''),
            'mail_port' => Setting::get('mail_port', 587),
            'mail_username' => Setting::get('mail_username', ''),
            'mail_password_set' => Setting::get('mail_password', '') !== '',
            'mail_from_address' => Setting::get('mail_from_address', 'no-reply@vipasa.demo'),
            'mail_from_name' => Setting::get('mail_from_name', 'Vipasa Yoga'),
            'session_lifetime_minutes' => Setting::get('session_lifetime_minutes', 120),
        ];

        $backupDir = dirname(__DIR__, 3) . '/storage/backups';
        $recentBackups = [];
        $tableCount = 0;
        try {
            $db = \App\Core\Database::connection();
            $tableCount = (int) $db->query('SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE()')->fetchColumn();
        } catch (\Throwable $e) {}

        if (is_dir($backupDir)) {
            $files = glob($backupDir . '/*.sql');
            if ($files) {
                usort($files, fn($a, $b) => filemtime($b) <=> filemtime($a));
                foreach (array_slice($files, 0, 8) as $f) {
                    $recentBackups[] = [
                        'name' => basename($f),
                        'size' => round(filesize($f) / 1024, 2) . ' KB',
                        'time' => date('Y-m-d H:i:s', filemtime($f)),
                    ];
                }
            }
        }

        $dbInfo = [
            'database' => Env::get('DB_NAME', 'vipasa_yoga'),
            'host' => Env::get('DB_HOST', '127.0.0.1'),
            'tables' => $tableCount,
            'recent_backups' => $recentBackups,
        ];

        $this->view('admin/settings', [
            'settings' => $settings,
            'dbInfo' => $dbInfo,
        ], 'dashboard');
    }

    public function save(Request $request): void
    {
        $validator = $this->validate($request, $this->rules());

        if ($validator->fails()) {
            $this->fail($validator->firstError() ?? 'Validation failed.', 422, $validator->errors());
        }

        $values = [
            'site_name' => (string) $request->input('site_name'),
            'tax_percent' => (string) $request->input('tax_percent'),
            'default_currency' => strtoupper((string) $request->input('default_currency')),
            'default_timezone' => (string) $request->input('default_timezone'),
        ];

        foreach ($values as $key => $value) {
            Setting::set($key, $value);
        }

        Env::update([
            'APP_NAME' => $values['site_name'],
            'TAX_PERCENT' => $values['tax_percent'],
            'DEFAULT_CURRENCY' => $values['default_currency'],
            'APP_TIMEZONE' => $values['default_timezone'],
        ]);

        ActivityLog::log(Auth::id(), 'settings_updated', 'setting', null, $values);

        $this->success(null, 'Settings updated.');
    }

    public function saveWebsite(Request $request): void
    {
        $values = [
            'social_facebook' => trim((string) $request->input('social_facebook', '')),
            'social_instagram' => trim((string) $request->input('social_instagram', '')),
            'social_twitter' => trim((string) $request->input('social_twitter', '')),
            'social_youtube' => trim((string) $request->input('social_youtube', '')),
            'default_language' => trim((string) $request->input('default_language', 'en')) ?: 'en',
        ];

        foreach ($values as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::log(Auth::id(), 'settings_updated', 'setting', null, ['section' => 'website']);
        $this->success(null, 'Website settings updated.');
    }

    public function uploadLogo(Request $request): void
    {
        if (empty($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
            $this->fail('Please choose a logo file.', 422);
        }

        $file = $_FILES['logo'];
        $mime = mime_content_type($file['tmp_name']);

        if (!isset(self::ALLOWED_LOGO_MIMES[$mime])) {
            $this->fail('Please upload a JPG, PNG, WEBP, or SVG image.', 422);
        }

        if ($file['size'] > self::MAX_LOGO_BYTES) {
            $this->fail('Logo must be smaller than 2MB.', 422);
        }

        $dir = dirname(__DIR__, 3) . '/public/assets/img/site';

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = 'logo-' . time() . '.' . self::ALLOWED_LOGO_MIMES[$mime];
        $destination = $dir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->fail('Could not save the uploaded logo. Please try again.', 500);
        }

        $existing = Setting::get('site_logo_path', '');
        if ($existing) {
            $oldPath = dirname(__DIR__, 3) . '/public' . $existing;
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        $publicPath = '/assets/img/site/' . $filename;
        Setting::set('site_logo_path', $publicPath);
        ActivityLog::log(Auth::id(), 'settings_updated', 'setting', null, ['section' => 'logo']);

        $this->success(['logo_path' => $publicPath], 'Logo updated.');
    }

    public function saveGoogle(Request $request): void
    {
        $clientId = trim((string) $request->input('client_id', ''));
        $redirectUri = trim((string) $request->input('redirect_uri', ''));
        $secret = trim((string) $request->input('client_secret', ''));

        Setting::set('google_client_id', $clientId);
        Setting::set('google_redirect_uri', $redirectUri);

        $envUpdates = [
            'GOOGLE_CLIENT_ID' => $clientId,
            'GOOGLE_REDIRECT_URI' => $redirectUri,
        ];

        if ($secret !== '') {
            Setting::set('google_client_secret', $secret);
            $envUpdates['GOOGLE_CLIENT_SECRET'] = $secret;
        }

        Env::update($envUpdates);

        ActivityLog::log(Auth::id(), 'settings_updated', 'setting', null, ['section' => 'google']);
        $this->success(null, 'Google login settings updated.');
    }

    public function savePayments(Request $request): void
    {
        $gateway = (string) $request->input('payment_gateway', 'upi_qr');
        $upiId = trim((string) $request->input('upi_id', 'vijayaparameshh@oksbi'));
        $upiName = trim((string) $request->input('upi_name', 'Vijaya Parameswaran'));
        $upiPhone = trim((string) $request->input('upi_phone', '+91 70127 75990'));
        $razorpayKey = trim((string) $request->input('razorpay_key_id', ''));
        $stripePublic = trim((string) $request->input('stripe_public_key', ''));
        $razorpaySecret = trim((string) $request->input('razorpay_key_secret', ''));
        $stripeSecret = trim((string) $request->input('stripe_secret_key', ''));

        Setting::set('payment_gateway', $gateway);
        Setting::set('upi_id', $upiId);
        Setting::set('upi_name', $upiName);
        Setting::set('upi_phone', $upiPhone);
        Setting::set('razorpay_key_id', $razorpayKey);
        Setting::set('stripe_public_key', $stripePublic);

        $envUpdates = [
            'PAYMENT_GATEWAY' => $gateway,
            'RAZORPAY_KEY_ID' => $razorpayKey,
            'STRIPE_PUBLIC_KEY' => $stripePublic,
        ];

        if ($razorpaySecret !== '') {
            Setting::set('razorpay_key_secret', $razorpaySecret);
            $envUpdates['RAZORPAY_KEY_SECRET'] = $razorpaySecret;
        }

        if ($stripeSecret !== '') {
            Setting::set('stripe_secret_key', $stripeSecret);
            $envUpdates['STRIPE_SECRET_KEY'] = $stripeSecret;
        }

        Env::update($envUpdates);

        ActivityLog::log(Auth::id(), 'settings_updated', 'setting', null, ['section' => 'payments']);
        $this->success(null, 'Payment gateway settings updated.');
    }

    public function saveMail(Request $request): void
    {
        $driver = (string) $request->input('mail_driver', 'log');
        $host = trim((string) $request->input('mail_host', ''));
        $port = (string) (int) $request->input('mail_port', 587);
        $username = trim((string) $request->input('mail_username', ''));
        $password = (string) $request->input('mail_password', '');
        $fromAddress = trim((string) $request->input('mail_from_address', ''));
        $fromName = trim((string) $request->input('mail_from_name', ''));

        Setting::set('mail_driver', $driver);
        Setting::set('mail_host', $host);
        Setting::set('mail_port', $port);
        Setting::set('mail_username', $username);
        Setting::set('mail_from_address', $fromAddress);
        Setting::set('mail_from_name', $fromName);

        $envUpdates = [
            'MAIL_DRIVER' => $driver,
            'MAIL_HOST' => $host,
            'MAIL_PORT' => $port,
            'MAIL_USERNAME' => $username,
            'MAIL_FROM_ADDRESS' => $fromAddress,
            'MAIL_FROM_NAME' => $fromName,
        ];

        if ($password !== '') {
            Setting::set('mail_password', $password);
            $envUpdates['MAIL_PASSWORD'] = $password;
        }

        Env::update($envUpdates);

        ActivityLog::log(Auth::id(), 'settings_updated', 'setting', null, ['section' => 'smtp']);
        $this->success(null, 'SMTP settings updated.');
    }

    public function saveSecurity(Request $request): void
    {
        $minutes = max(5, (int) $request->input('session_lifetime_minutes', 120));
        Setting::set('session_lifetime_minutes', (string) $minutes);

        Env::update([
            'SESSION_LIFETIME' => (string) $minutes,
        ]);

        ActivityLog::log(Auth::id(), 'settings_updated', 'setting', null, ['section' => 'security']);
        $this->success(null, 'Security settings updated.');
    }

    public function downloadBackup(Request $request): void
    {
        $backupDir = dirname(__DIR__, 3) . '/storage/backups';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $dbName = Env::get('DB_NAME', 'vipasa_yoga');
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "vipasa_yoga_backup_{$timestamp}.sql";
        $filePath = $backupDir . '/' . $filename;

        try {
            $db = \App\Core\Database::connection();
            $tables = $db->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);

            $sqlDump = "-- ============================================================\n";
            $sqlDump .= "-- Vipasa Yoga Database Backup\n";
            $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $sqlDump .= "-- Database: {$dbName}\n";
            $sqlDump .= "-- ============================================================\n\n";
            $sqlDump .= "SET FOREIGN_KEY_CHECKS = 0;\n";
            $sqlDump .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n";

            foreach ($tables as $table) {
                $createStmt = $db->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
                $createTableSql = $createStmt['Create Table'] ?? '';

                $sqlDump .= "-- ------------------------------------------------------------\n";
                $sqlDump .= "-- Structure for table `{$table}`\n";
                $sqlDump .= "-- ------------------------------------------------------------\n";
                $sqlDump .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $sqlDump .= $createTableSql . ";\n\n";

                $rows = $db->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    $sqlDump .= "-- Data for table `{$table}` (" . count($rows) . " rows)\n";
                    $sqlDump .= "INSERT INTO `{$table}` VALUES \n";

                    $rowSqls = [];
                    foreach ($rows as $row) {
                        $values = array_map(function ($val) use ($db) {
                            if ($val === null) {
                                return 'NULL';
                            }
                            return $db->quote((string)$val);
                        }, array_values($row));
                        $rowSqls[] = '(' . implode(', ', $values) . ')';
                    }

                    $sqlDump .= implode(",\n", $rowSqls) . ";\n\n";
                }
            }

            $sqlDump .= "SET FOREIGN_KEY_CHECKS = 1;\n";

            file_put_contents($filePath, $sqlDump);

            ActivityLog::log(Auth::id(), 'backup_downloaded', 'database', null, ['filename' => $filename]);

            header('Content-Description: File Transfer');
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . strlen($sqlDump));
            echo $sqlDump;
            exit;
        } catch (\Throwable $e) {
            $this->fail('Failed to generate backup: ' . $e->getMessage(), 500);
        }
    }

    private function rules(): array
    {
        return [
            'site_name' => 'required|max:150',
            'tax_percent' => 'required|numeric|min:0',
            'default_currency' => 'required|max:3',
            'default_timezone' => 'required|max:60',
        ];
    }
}
