<?php

namespace App\Models;

use App\Core\Model;

final class Setting extends Model
{
    protected static string $table = 'settings';
    protected static string $primaryKey = 'setting_key';

    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $row = static::db()->prepare('SELECT setting_value FROM settings WHERE setting_key = :key');
            $row->execute(['key' => $key]);
            $result = $row->fetch();

            if ($result && $result['setting_value'] !== null && $result['setting_value'] !== '') {
                return $result['setting_value'];
            }
        } catch (\Throwable $e) {
            // Fallback to default if table/DB not reachable
        }

        return $default;
    }

    public static function set(string $key, string $value): void
    {
        $stmt = static::db()->prepare(
            'INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
        );
        $stmt->execute(['key' => $key, 'value' => $value]);
    }
}
