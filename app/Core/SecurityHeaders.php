<?php

namespace App\Core;

final class SecurityHeaders
{
    public static function apply(): void
    {
        if (headers_sent() || PHP_SAPI === 'cli') {
            return;
        }

        // Clickjacking protection — disallow embedding in third-party iframes
        header('X-Frame-Options: SAMEORIGIN');

        // MIME-sniffing prevention
        header('X-Content-Type-Options: nosniff');

        // Cross-Site Scripting (XSS) filter
        header('X-XSS-Protection: 1; mode=block');

        // Referrer policy privacy protection
        header('Referrer-Policy: strict-origin-when-cross-origin');

        // Restrict sensitive browser APIs
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

        // Content Security Policy — protect frame ancestors and disallow unsafe plugin objects
        header("Content-Security-Policy: frame-ancestors 'self'; object-src 'none';");

        // HTTP Strict Transport Security (HSTS) when running over HTTPS
        if (self::isHttps()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }

    private static function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443)
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    }
}
