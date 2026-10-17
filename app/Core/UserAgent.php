<?php

namespace App\Core;

/**
 * Minimal, dependency-free User-Agent sniffing for the profile version
 * history's "Browser"/"Device" columns — not meant to be exhaustive, just
 * good enough for an audit trail to be human-readable.
 */
final class UserAgent
{
    public static function browser(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Edg/') => 'Edge',
            str_contains($ua, 'OPR/') || str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'CriOS/') => 'Chrome',
            str_contains($ua, 'FxiOS/') => 'Firefox',
            str_contains($ua, 'Firefox/') => 'Firefox',
            str_contains($ua, 'Chrome/') && !str_contains($ua, 'Chromium') => 'Chrome',
            str_contains($ua, 'Safari/') && !str_contains($ua, 'Chrome') => 'Safari',
            $ua === '' => 'Unknown',
            default => 'Other',
        };
    }

    public static function device(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/iPad|Tablet/i', $ua) => 'Tablet',
            (bool) preg_match('/Mobi|Android.*Mobile|iPhone/i', $ua) => 'Mobile',
            default => 'Desktop',
        };
    }
}
