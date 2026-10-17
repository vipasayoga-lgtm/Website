<?php

namespace App\Models;

use App\Core\Model;

final class Booking extends Model
{
    protected static string $table = 'bookings';

    public static function findByRef(string $ref): ?array
    {
        return self::findBy('booking_ref', $ref);
    }

    public static function generateUniqueRef(): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        do {
            $suffix = '';
            for ($i = 0; $i < 8; $i++) {
                $suffix .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
            $ref = 'VY' . $suffix;
        } while (self::findByRef($ref) !== null);

        return $ref;
    }

    public static function forClient(int $clientId): array
    {
        return self::where(['client_id' => $clientId], 'created_at DESC');
    }

    public static function autoExpirePastConfirmedBookings(): void
    {
        // Mark past confirmed sessions whose end time has passed as completed
        $sql = "UPDATE bookings 
                SET status = 'completed' 
                WHERE status = 'confirmed' 
                  AND CONCAT(slot_date, ' ', end_time) < NOW()";
        static::db()->exec($sql);
    }

    public static function findUnreviewedCompletedForClient(int $clientId): ?array
    {
        self::autoExpirePastConfirmedBookings();

        $sql = "SELECT b.*, iu.name AS instructor_name, i.avatar_path AS instructor_avatar, p.name AS package_name
                FROM bookings b
                JOIN instructors i ON i.id = b.instructor_id
                JOIN users iu ON iu.id = i.user_id
                JOIN packages p ON p.id = b.package_id
                LEFT JOIN instructor_reviews r ON r.booking_id = b.id
                WHERE b.client_id = :client_id
                  AND b.status = 'completed'
                  AND r.id IS NULL
                ORDER BY b.slot_date DESC, b.start_time DESC
                LIMIT 1";
        $stmt = static::db()->prepare($sql);
        $stmt->execute(['client_id' => $clientId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findLatestCompletedForClient(int $clientId): ?array
    {
        $sql = "SELECT b.*, iu.name AS instructor_name, p.name AS package_name
                FROM bookings b
                JOIN instructors i ON i.id = b.instructor_id
                JOIN users iu ON iu.id = i.user_id
                JOIN packages p ON p.id = b.package_id
                WHERE b.client_id = :client_id
                  AND b.status = 'completed'
                ORDER BY b.slot_date DESC, b.start_time DESC
                LIMIT 1";
        $stmt = static::db()->prepare($sql);
        $stmt->execute(['client_id' => $clientId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}

