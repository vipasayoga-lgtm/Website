<?php

namespace App\Controllers\Trainer;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Models\ClientProfile;
use App\Models\Instructor;

final class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $instructorRow = Instructor::findByUserId(Auth::id());
        $instructorId = (int) $instructorRow['id'];
        $instructor = Instructor::findWithName($instructorId);
        $db = Database::connection();

        $todaysClasses = $this->bookingList($db, $instructorId, "b.slot_date = CURDATE() AND b.status IN ('confirmed','completed')", 'b.start_time ASC');
        $upcoming = $this->bookingList($db, $instructorId, "b.slot_date > CURDATE() AND b.status = 'confirmed'", 'b.slot_date ASC, b.start_time ASC', 10);
        $awaitingApproval = $this->bookingList($db, $instructorId, "b.status = 'awaiting_trainer_approval'", 'b.slot_date ASC, b.start_time ASC');
        $completedCount = $this->countWhere($db, $instructorId, "b.status = 'completed'");
        $totalStudents = $this->countDistinctClients($db, $instructorId);

        $earningsStmt = $db->prepare(
            "SELECT COALESCE(SUM(total_amount), 0) r FROM bookings
             WHERE instructor_id = :id AND status IN ('awaiting_trainer_approval','confirmed','completed')"
        );
        $earningsStmt->execute(['id' => $instructorId]);
        $totalEarnings = (float) $earningsStmt->fetch()['r'];

        $this->view('trainer/dashboard', [
            'instructor' => $instructor,
            'todaysClasses' => $todaysClasses,
            'upcoming' => $upcoming,
            'awaitingApproval' => $awaitingApproval,
            'completedCount' => $completedCount,
            'totalStudents' => $totalStudents,
            'totalEarnings' => $totalEarnings,
            'medicalAlertCount' => $this->medicalAlertCount($todaysClasses, $upcoming),
            'pendingNotesCount' => $this->pendingNotesCount($db, $instructorId),
            'attendanceRate' => $this->attendanceRate($db, $instructorId),
        ], 'dashboard');
    }

    /**
     * How many distinct clients among today's + upcoming sessions have a
     * real medical alert on file (condition, pain area, or restriction) —
     * a quick "who do I need to be careful with" count for the dashboard.
     */
    private function medicalAlertCount(array $todaysClasses, array $upcoming): int
    {
        $clientIds = array_unique(array_map(static fn (array $b) => (int) $b['client_id'], array_merge($todaysClasses, $upcoming)));
        $count = 0;

        foreach ($clientIds as $clientId) {
            $profileRow = ClientProfile::findByUserId($clientId);
            $profile = $profileRow ? ClientProfile::hydrate($profileRow) : null;
            $safety = ClientProfile::safetySummary($profile);

            if (!empty($safety['medicalAlerts']) || !empty($safety['painAreas']) || !empty($safety['restrictions']['hasRestriction'])) {
                $count++;
            }
        }

        return $count;
    }

    /** Completed sessions with no session_feedback row yet — notes still owed. */
    private function pendingNotesCount(\PDO $db, int $instructorId): int
    {
        $stmt = $db->prepare(
            "SELECT COUNT(*) c FROM bookings b
             LEFT JOIN session_feedback sf ON sf.booking_id = b.id
             WHERE b.instructor_id = :id AND b.status = 'completed' AND sf.id IS NULL"
        );
        $stmt->execute(['id' => $instructorId]);

        return (int) $stmt->fetch()['c'];
    }

    /** % of sessions marked "present" among all sessions where attendance was recorded. */
    private function attendanceRate(\PDO $db, int $instructorId): ?int
    {
        $stmt = $db->prepare(
            "SELECT COUNT(*) total, SUM(attendance = 'present') present
             FROM session_feedback WHERE instructor_id = :id AND attendance IS NOT NULL"
        );
        $stmt->execute(['id' => $instructorId]);
        $row = $stmt->fetch();

        if ((int) $row['total'] === 0) {
            return null;
        }

        return (int) round(((int) $row['present'] / (int) $row['total']) * 100);
    }

    private function bookingList(\PDO $db, int $instructorId, string $where, string $orderBy, int $limit = 50): array
    {
        $sql = "SELECT b.*, u.name AS client_name, p.name AS package_name,
                       ml.url AS meeting_url, ml.provider AS meeting_provider
                FROM bookings b
                INNER JOIN users u ON u.id = b.client_id
                INNER JOIN packages p ON p.id = b.package_id
                LEFT JOIN meeting_links ml ON ml.booking_id = b.id
                WHERE b.instructor_id = :instructor_id AND {$where}
                ORDER BY {$orderBy}
                LIMIT " . max(1, $limit);

        $stmt = $db->prepare($sql);
        $stmt->execute(['instructor_id' => $instructorId]);

        return $stmt->fetchAll();
    }

    private function countWhere(\PDO $db, int $instructorId, string $where): int
    {
        $stmt = $db->prepare("SELECT COUNT(*) c FROM bookings b WHERE b.instructor_id = :instructor_id AND {$where}");
        $stmt->execute(['instructor_id' => $instructorId]);

        return (int) $stmt->fetch()['c'];
    }

    private function countDistinctClients(\PDO $db, int $instructorId): int
    {
        $stmt = $db->prepare(
            "SELECT COUNT(DISTINCT client_id) c FROM bookings
             WHERE instructor_id = :id AND status IN ('confirmed','completed')"
        );
        $stmt->execute(['id' => $instructorId]);

        return (int) $stmt->fetch()['c'];
    }
}
