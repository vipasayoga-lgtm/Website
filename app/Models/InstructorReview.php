<?php

namespace App\Models;

use App\Core\Model;

final class InstructorReview extends Model
{
    protected static string $table = 'instructor_reviews';

    public static function forInstructor(int $instructorId): array
    {
        $sql = 'SELECT r.*, u.name AS client_name, cp.avatar_path AS client_avatar
                FROM instructor_reviews r
                JOIN users u ON u.id = r.client_id
                LEFT JOIN client_profiles cp ON cp.user_id = u.id
                WHERE r.instructor_id = :instructor_id
                ORDER BY r.created_at DESC';
        $stmt = static::db()->prepare($sql);
        $stmt->execute(['instructor_id' => $instructorId]);
        return $stmt->fetchAll();
    }

    public static function allWithDetails(): array
    {
        $sql = 'SELECT r.*,
                       u_client.name AS client_name,
                       u_client.email AS client_email,
                       u_inst.name AS instructor_name,
                       b.booking_ref
                FROM instructor_reviews r
                JOIN users u_client ON u_client.id = r.client_id
                JOIN instructors inst ON inst.id = r.instructor_id
                JOIN users u_inst ON u_inst.id = inst.user_id
                LEFT JOIN bookings b ON b.id = r.booking_id
                ORDER BY r.created_at DESC';
        $stmt = static::db()->query($sql);
        return $stmt->fetchAll();
    }

    public static function approvedForFrontpage(int $limit = 6): array
    {
        $sql = 'SELECT r.*,
                       u_client.name AS client_name,
                       cp.avatar_path AS client_avatar,
                       u_inst.name AS instructor_name
                FROM instructor_reviews r
                JOIN users u_client ON u_client.id = r.client_id
                LEFT JOIN client_profiles cp ON cp.user_id = u_client.id
                JOIN instructors inst ON inst.id = r.instructor_id
                JOIN users u_inst ON u_inst.id = inst.user_id
                WHERE r.is_approved = 1
                ORDER BY r.created_at DESC
                LIMIT :limit';
        $stmt = static::db()->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function toggleApproval(int $id): bool
    {
        $review = self::find($id);
        if (!$review) {
            return false;
        }

        $newStatus = (int) ($review['is_approved'] ?? 0) === 1 ? 0 : 1;
        self::update($id, ['is_approved' => $newStatus]);
        return (bool) $newStatus;
    }

    public static function updateReview(int $id, array $data): bool
    {
        $review = self::find($id);
        if (!$review) {
            return false;
        }

        self::update($id, $data);

        if (isset($data['rating'])) {
            $stmt = static::db()->prepare(
                'SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM instructor_reviews WHERE instructor_id = :id'
            );
            $stmt->execute(['id' => $review['instructor_id']]);
            $stats = $stmt->fetch();

            Instructor::update($review['instructor_id'], [
                'rating_avg' => round((float) $stats['avg_rating'], 2),
                'rating_count' => (int) $stats['review_count'],
            ]);
        }

        return true;
    }

    public static function existsForBooking(int $bookingId): bool
    {
        return self::whereFirst(['booking_id' => $bookingId]) !== null;
    }

    public static function forBooking(int $bookingId): ?array
    {
        return self::whereFirst(['booking_id' => $bookingId]);
    }

    public static function create(array $data): int
    {
        $id = self::insert($data);

        $stmt = static::db()->prepare(
            'SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM instructor_reviews WHERE instructor_id = :id'
        );
        $stmt->execute(['id' => $data['instructor_id']]);
        $stats = $stmt->fetch();

        Instructor::update($data['instructor_id'], [
            'rating_avg' => round((float) $stats['avg_rating'], 2),
            'rating_count' => (int) $stats['review_count'],
        ]);

        return $id;
    }
}
