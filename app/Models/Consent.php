<?php

namespace App\Models;

use App\Core\Model;

final class Consent extends Model
{
    protected static string $table = 'consents';

    /** The current wording of the three canonical consent statements shown to a client. */
    public const CURRENT_VERSION = 'v1';

    public static function record(int $userId, ?int $bookingId, string $ipAddress, string $userAgent, string $timezone): int
    {
        return self::insert([
            'user_id' => $userId,
            'booking_id' => $bookingId,
            'consent_version' => self::CURRENT_VERSION,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'timezone' => $timezone,
        ]);
    }

    public static function existsForBooking(int $userId, int $bookingId): bool
    {
        return self::count(['user_id' => $userId, 'booking_id' => $bookingId]) > 0;
    }

    public static function forUser(int $userId): array
    {
        return self::where(['user_id' => $userId], 'id DESC');
    }
}
