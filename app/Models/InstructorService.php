<?php

namespace App\Models;

use App\Core\Model;

final class InstructorService extends Model
{
    protected static string $table = 'instructor_services';

    public static function effectivePrice(int $instructorId, int $packageId): ?float
    {
        $stmt = static::db()->prepare(
            'SELECT instructor_services.price_override, instructor_services.is_active, packages.price, packages.is_active AS package_is_active
             FROM packages
             LEFT JOIN instructor_services ON instructor_services.package_id = packages.id AND instructor_services.instructor_id = :instructor_id
             WHERE packages.id = :package_id
             LIMIT 1'
        );
        $stmt->execute(['instructor_id' => $instructorId, 'package_id' => $packageId]);
        $row = $stmt->fetch();

        if (!$row || !(int) $row['package_is_active']) {
            return null;
        }

        if ($row['is_active'] !== null && !(int) $row['is_active']) {
            return null;
        }

        return $row['price_override'] !== null ? (float) $row['price_override'] : (float) $row['price'];
    }
}
