<?php

namespace App\Models;

use App\Core\Model;

final class Instructor extends Model
{
    protected static string $table = 'instructors';

    public static function findByUserId(int $userId): ?array
    {
        $row = self::whereFirst(['user_id' => $userId]);
        if (!$row) {
            $user = User::find($userId);
            if ($user && $user['role'] === 'instructor') {
                $id = self::insert([
                    'user_id' => $userId,
                    'headline' => 'Certified Yoga Trainer',
                    'bio' => 'Dedicated to guiding students in their wellness journey.',
                    'experience_years' => 1,
                    'status' => 'active',
                ]);
                $row = self::find($id);
            }
        }

        return $row;
    }

    public static function searchActive(array $filters = []): array
    {
        $params = ['status' => 'active'];
        $sql = 'SELECT DISTINCT instructors.*, users.name FROM instructors INNER JOIN users ON users.id = instructors.user_id';

        if (!empty($filters['package_id'])) {
            $sql .= ' LEFT JOIN instructor_services ON instructor_services.instructor_id = instructors.id AND instructor_services.package_id = :package_id';
            $sql .= ' INNER JOIN packages ON packages.id = :package_id_pkg AND packages.is_active = 1';
            $sql .= ' WHERE instructors.status = :status AND (instructor_services.is_active IS NULL OR instructor_services.is_active = 1)';
            $params['package_id'] = (int) $filters['package_id'];
            $params['package_id_pkg'] = (int) $filters['package_id'];
        } elseif (!empty($filters['service_type_id'])) {
            $sql .= ' INNER JOIN packages ON packages.service_type_id = :service_type_id AND packages.is_active = 1';
            $sql .= ' LEFT JOIN instructor_services ON instructor_services.instructor_id = instructors.id AND instructor_services.package_id = packages.id';
            $sql .= ' WHERE instructors.status = :status AND (instructor_services.is_active IS NULL OR instructor_services.is_active = 1)';
            $params['service_type_id'] = (int) $filters['service_type_id'];
        } else {
            $sql .= ' WHERE instructors.status = :status';
        }

        $stmt = static::db()->prepare($sql);
        $stmt->execute($params);

        return array_map(static fn (array $row) => self::hydrate($row), $stmt->fetchAll());
    }

    public static function adminList(): array
    {
        $stmt = static::db()->query(
            'SELECT instructors.*, users.name, users.email, users.phone
             FROM instructors
             INNER JOIN users ON users.id = instructors.user_id
             ORDER BY instructors.status ASC, users.name ASC'
        );

        return array_map(static fn (array $row): array => self::hydrate($row), $stmt->fetchAll());
    }

    public static function findWithName(int $id): ?array
    {
        $stmt = static::db()->prepare(
            'SELECT instructors.*, users.name FROM instructors
             INNER JOIN users ON users.id = instructors.user_id
             WHERE instructors.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? self::hydrate($row) : null;
    }

    public static function hydrate(array $row): array
    {
        $row['certificates'] = $row['certificates'] !== null ? json_decode($row['certificates'], true) : null;
        $row['specialties'] = $row['specialties'] !== null ? json_decode($row['specialties'], true) : null;

        return $row;
    }
}
