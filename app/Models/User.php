<?php

namespace App\Models;

use App\Core\Model;

final class User extends Model
{
    protected static string $table = 'users';

    public static function existsWithEmailOrPhone(?string $email, ?string $phone): bool
    {
        if ($email && self::findBy('email', $email)) {
            return true;
        }

        if ($phone && self::findBy('phone', $phone)) {
            return true;
        }

        return false;
    }

    public static function existsWithEmailOrPhoneExceptUser(?string $email, ?string $phone, int $excludeUserId): bool
    {
        $db = \App\Core\Database::connection();
        if ($email) {
            $stmt = $db->prepare('SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1');
            $stmt->execute(['email' => $email, 'id' => $excludeUserId]);
            if ($stmt->fetch()) {
                return true;
            }
        }

        if ($phone) {
            $stmt = $db->prepare('SELECT id FROM users WHERE phone = :phone AND id != :id LIMIT 1');
            $stmt->execute(['phone' => $phone, 'id' => $excludeUserId]);
            if ($stmt->fetch()) {
                return true;
            }
        }

        return false;
    }

    public static function markEmailVerified(int $userId): void
    {
        self::update($userId, ['email_verified_at' => date('Y-m-d H:i:s')]);
    }

    public static function setPassword(int $userId, string $password): void
    {
        self::update($userId, ['password_hash' => password_hash($password, PASSWORD_BCRYPT)]);
    }

    public static function findByGoogleId(string $googleId): ?array
    {
        return self::findBy('google_id', $googleId);
    }

    public static function create(string $name, string $email, ?string $phone, string $password): int
    {
        return self::insert([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role' => 'client',
            'status' => 'active',
            'email_verified_at' => null,
        ]);
    }

    public static function createFromGoogle(string $name, string $email, string $googleId): int
    {
        return self::insert([
            'name' => $name,
            'email' => $email,
            'google_id' => $googleId,
            'password_hash' => null,
            'role' => 'client',
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function linkGoogleId(int $userId, string $googleId): void
    {
        self::update($userId, ['google_id' => $googleId]);
    }
}
