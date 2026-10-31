<?php

namespace App\Models;

use App\Core\Model;
use PDOException;

final class BookingSlot extends Model
{
    protected static string $table = 'booking_slots';

    /**
     * Synchronizes booking slots with active bookings in the `bookings` table.
     * Guarantees that any slot claimed by a client (pending_payment, confirmed, etc.)
     * is strictly marked as 'booked' so it can never be double-booked.
     */
    public static function syncBookedSlots(int $instructorId, string $date): void
    {
        $db = static::db();
        $activeStatuses = "('pending_payment', 'awaiting_trainer_approval', 'confirmed', 'completed')";

        // 1. Mark slots as booked if an active booking exists
        $sql = "UPDATE booking_slots bs
                JOIN bookings b ON (
                    bs.id = b.slot_id OR (
                        bs.instructor_id = b.instructor_id 
                        AND bs.slot_date = b.slot_date 
                        AND SUBSTRING(bs.start_time, 1, 5) = SUBSTRING(b.start_time, 1, 5)
                    )
                )
                SET bs.status = 'booked', bs.booking_id = b.id
                WHERE bs.instructor_id = :instructor_id
                  AND bs.slot_date = :slot_date
                  AND b.status IN {$activeStatuses}
                  AND bs.status != 'booked'";
        $stmt = $db->prepare($sql);
        $stmt->execute(['instructor_id' => $instructorId, 'slot_date' => $date]);

        // 2. Release slots if their booking was cancelled or deleted
        $sqlRelease = "UPDATE booking_slots bs
                       LEFT JOIN bookings b ON (
                           bs.id = b.slot_id OR (
                               bs.instructor_id = b.instructor_id 
                               AND bs.slot_date = b.slot_date 
                               AND SUBSTRING(bs.start_time, 1, 5) = SUBSTRING(b.start_time, 1, 5)
                               AND b.status IN {$activeStatuses}
                           )
                       )
                       SET bs.status = 'available', bs.booking_id = NULL
                       WHERE bs.instructor_id = :instructor_id
                         AND bs.slot_date = :slot_date
                         AND bs.status = 'booked'
                         AND b.id IS NULL";
        $stmtRelease = $db->prepare($sqlRelease);
        $stmtRelease->execute(['instructor_id' => $instructorId, 'slot_date' => $date]);
    }

    public static function forDate(int $instructorId, string $date): array
    {
        self::syncBookedSlots($instructorId, $date);

        return self::where(
            ['instructor_id' => $instructorId, 'slot_date' => $date],
            'start_time ASC'
        );
    }

    public static function findAvailable(int $instructorId, string $date): array
    {
        self::syncBookedSlots($instructorId, $date);

        return self::where(
            ['instructor_id' => $instructorId, 'slot_date' => $date, 'status' => 'available'],
            'start_time ASC'
        );
    }

    /**
     * Lazily materializes booking_slots rows for a given instructor+date from their
     * recurring/specific-date availability windows, split into $durationMinutes chunks.
     * Safe to call repeatedly — a unique (instructor_id, slot_date, start_time) key means
     * concurrent generation attempts just collide harmlessly and get ignored.
     */
    public static function ensureGeneratedForDate(int $instructorId, string $date, int $durationMinutes = 60): void
    {
        if (self::count(['instructor_id' => $instructorId, 'slot_date' => $date]) > 0) {
            self::syncBookedSlots($instructorId, $date);
            return;
        }

        foreach (InstructorBlockedDate::forInstructor($instructorId) as $blocked) {
            if ($blocked['blocked_date'] === $date) {
                return;
            }
        }

        $dayOfWeek = (int) date('w', strtotime($date));
        $now = time();

        foreach (InstructorAvailability::forInstructor($instructorId) as $window) {
            $matches = (int) $window['is_recurring'] === 1
                ? (int) $window['day_of_week'] === $dayOfWeek
                : $window['specific_date'] === $date;

            if (!$matches) {
                continue;
            }

            $stepSeconds = max($durationMinutes, 1) * 60;
            $start = strtotime($date . ' ' . $window['start_time']);
            $end = strtotime($date . ' ' . $window['end_time']);

            for ($slotStart = $start; $slotStart + $stepSeconds <= $end; $slotStart += $stepSeconds) {
                if ($slotStart <= $now) {
                    continue;
                }

                try {
                    self::insert([
                        'instructor_id' => $instructorId,
                        'slot_date' => $date,
                        'start_time' => date('H:i:s', $slotStart),
                        'end_time' => date('H:i:s', $slotStart + $stepSeconds),
                        'status' => 'available',
                    ]);
                } catch (PDOException) {
                    // another request generated this exact slot first — fine, it exists now.
                }
            }
        }

        self::syncBookedSlots($instructorId, $date);
    }

    public static function lockSlot(int $slotId, int $bookingId): bool
    {
        // Conditional UPDATE guarantees only one concurrent request can claim the slot;
        // rowCount() of 0 means someone else already booked it, so the caller must reject/rollback.
        $stmt = static::db()->prepare(
            "UPDATE booking_slots SET status = 'booked', booking_id = :booking_id WHERE id = :id AND status = 'available'"
        );
        $stmt->execute(['booking_id' => $bookingId, 'id' => $slotId]);

        return $stmt->rowCount() > 0;
    }

    public static function release(int $slotId): void
    {
        self::update($slotId, ['status' => 'available', 'booking_id' => null]);
    }
}
