<?php

namespace App\Models;

use App\Core\Model;

final class ClientProfile extends Model
{
    protected static string $table = 'client_profiles';

    /**
     * The five progressive-profiling stages map to these exact percentages
     * (client spec, not evenly spaced). Stage 1 = Get Started. Stage 2 =
     * health & safety questionnaire, before first booking. Stage 3 =
     * lifestyle, after first session. Stage 4 = advanced health, before
     * second booking. Stage 5 = final details + consent, complete profile.
     * Only stages 1-3 are built so far — this never returns above 65 yet.
     */
    private const STAGE_PERCENTAGES = [1 => 20, 2 => 40, 3 => 65, 4 => 80, 5 => 100];

    public static function findByUserId(int $userId): ?array
    {
        $row = self::whereFirst(['user_id' => $userId]);
        if (!$row) {
            $user = User::find($userId);
            if ($user && $user['role'] === 'client') {
                $id = self::insert([
                    'user_id' => $userId,
                    'timezone' => 'Asia/Kolkata',
                ]);
                $row = self::find($id);
            }
        }

        return $row;
    }

    public static function completionPercent(?array $profile): int
    {
        return self::STAGE_PERCENTAGES[self::stageReached($profile)] ?? 0;
    }

    public static function stageReached(?array $profile): int
    {
        if ($profile === null) {
            return 0;
        }

        $healthAssessment = $profile['health_assessment'] ?? null;
        if (is_string($healthAssessment)) {
            $healthAssessment = json_decode($healthAssessment, true);
        }

        $stageFromAssessment = (int) ($healthAssessment['stage'] ?? 0);
        if ($stageFromAssessment > 0) {
            return max(1, min(5, $stageFromAssessment));
        }

        if (!empty($profile['onboarding_completed_at'])) {
            return 1;
        }

        return 0;
    }

    public static function upsertForUser(int $userId, array $data): void
    {
        if (isset($data['preferences']) && is_array($data['preferences'])) {
            $data['preferences'] = json_encode($data['preferences']);
        }
        if (isset($data['health_assessment']) && is_array($data['health_assessment'])) {
            $data['health_assessment'] = json_encode($data['health_assessment']);
        }

        $existing = self::findByUserId($userId);

        if ($existing) {
            self::update($existing['id'], $data);
            return;
        }

        $data['user_id'] = $userId;
        self::insert($data);
    }

    /**
     * Shallow-merges a patch into the client's health_assessment JSON — each
     * top-level key (medicalHistory, painAssessment, ...) fully replaces its
     * previous value rather than deep-merging, since every conversational
     * step always sends its whole topic's state at once. A recursive merge
     * would corrupt list fields like `conditions`/`areas` (index-by-index
     * merge instead of replace) when a client changes their selection.
     */
    public static function mergeHealthAssessment(int $userId, array $patch): array
    {
        $profile = self::findByUserId($userId);
        $current = [];
        if ($profile && $profile['health_assessment'] !== null) {
            $current = json_decode($profile['health_assessment'], true) ?: [];
        }

        $merged = array_merge($current, $patch);
        self::upsertForUser($userId, ['health_assessment' => $merged]);

        return $merged;
    }

    public static function hydrate(array $row): array
    {
        $row['preferences'] = $row['preferences'] !== null ? json_decode($row['preferences'], true) : null;
        $row['health_assessment'] = $row['health_assessment'] !== null ? json_decode($row['health_assessment'], true) : null;

        return $row;
    }

    /**
     * Exactly what a Trainer is allowed to see about a client — Medical
     * Alerts, Pain Areas, Restrictions, Current Medication, Goals,
     * Emergency Contact, Additional Notes, Profile Completion. Deliberately
     * excludes everything else (bio, lifestyle preferences, food/language
     * preferences, raw health_assessment JSON, etc.) — a Trainer only needs
     * what's required to safely conduct a session, not the full profile
     * Admin sees. Called from both Trainer\StudentController and
     * Trainer\SessionController so the two views can never drift apart on
     * what's exposed.
     */
    public static function safetySummary(?array $profile): array
    {
        $ha = $profile['health_assessment'] ?? [];
        $ha = is_array($ha) ? $ha : [];

        $medicalHistory = $ha['medicalHistory'] ?? [];
        $conditions = array_values(array_filter((array) ($medicalHistory['conditions'] ?? []), static fn ($c) => $c !== 'none_of_these'));
        $painAssessment = $ha['painAssessment'] ?? [];
        $painAreas = array_values(array_filter((array) ($painAssessment['areas'] ?? []), static fn ($a) => $a !== 'no_pain'));
        $doctorRestrictions = $ha['doctorRestrictions'] ?? [];
        $surgeries = $ha['surgeries'] ?? [];
        $fallRisk = $ha['fallRisk'] ?? [];
        $medication = $ha['medication'] ?? [];

        return [
            'medicalAlerts' => array_map(
                static fn ($c) => ['condition' => $c, 'details' => $medicalHistory['details'][$c] ?? []],
                $conditions
            ),
            'painAreas' => array_map(
                static fn ($a) => ['area' => $a, 'details' => $painAssessment['details'][$a] ?? []],
                $painAreas
            ),
            'restrictions' => [
                'hasRestriction' => $doctorRestrictions['hasRestriction'] ?? null,
                'notes' => $doctorRestrictions['notes'] ?? null,
                'surgeries' => $surgeries,
                'recentFalls' => $fallRisk['recentFalls'] ?? null,
            ],
            'medication' => $medication,
            /* Stage-2 yes/no screening — allergies, past injuries, surgery and
               "advised not to exercise". Collected before the first session, so
               it reaches a Trainer earlier than the stage-4 `medication` /
               `surgeries` blocks above. Passed through whole: the assessment
               only ever writes the fixed key set declared in
               Views/onboarding/health-assessment.php (SCREENING_QUESTIONS). */
            'screening' => is_array($ha['healthScreening'] ?? null) ? $ha['healthScreening'] : [],
            'goals' => $ha['goals']['selected'] ?? [],
            'demographics' => [
                'age' => $profile['age'] ?? null,
                'gender' => $profile['gender'] ?? null,
                'height_cm' => $profile['height_cm'] ?? null,
                'weight_kg' => $profile['weight_kg'] ?? null,
                'category' => $profile['client_category'] ?? null,
                'city' => $profile['city'] ?? null,
                'country' => $profile['country'] ?? null,
            ],
            'wellness' => is_array($ha['wellnessProfile'] ?? null) ? $ha['wellnessProfile'] : [],
            'emergencyContact' => [
                'name' => $profile['emergency_contact_name'] ?? null,
                'phone' => $profile['emergency_contact_phone'] ?? null,
                'relationship' => $profile['emergency_contact_relationship'] ?? null,
            ],
            'additionalNotes' => $profile['medical_notes'] ?? null,
            'completionPercent' => self::completionPercent($profile),
        ];
    }
}
