<?php

namespace App\Controllers\Api;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\ActivityLog;
use App\Models\ClientProfile;
use App\Models\ProfileEditHistory;

final class HealthAssessmentController extends Controller
{
    private const STEP_KEYS = [
        // Stage 2 — health & safety, before first booking
        'medicalHistory', 'healthScreening', 'painAssessment', 'doctorRestrictions', 'categorySafety',
        // Stage 3 — lifestyle, after first session
        'lifestyle', 'sleep', 'exercise', 'stress', 'yogaExperience', 'goals', 'supportSystem', 'recoveryGoals',
        // Stage 4 — advanced health, before second booking
        'advancedHealth', 'medication', 'surgeries', 'advancedPain', 'fallRisk', 'lifestylePreferences',
        // Stage 5 — final details, complete profile
        'fitnessBackground', 'foodPreference', 'communicationPreference', 'languagePreference', 'longTermGoals',
    ];

    /**
     * Which categorySafety fields each client_category is allowed to submit —
     * every category the intake offers has its own question set, so a client
     * can't post another category's answers. KEEP IN SYNC with
     * CATEGORY_QUESTIONS in Views/onboarding/health-assessment.php; a field
     * missing here fails the whole step save with a 422.
     */
    private const CATEGORY_SAFETY_FIELDS = [
        'child' => [
            'guardianPresent', 'guardianRelationship', 'guardianRelationshipDetails', 'schoolSports', 'growthConcerns',
            'previousYoga', 'screenTime', 'instructionSupport', 'supportDevice', 'supportDeviceDetails',
        ],
        'teenager' => [
            'guardianConsent', 'schoolSports', 'studyHours', 'examStress', 'previousYoga',
            'screenTime', 'studyPosture', 'studyPostureDetails', 'energyLevel', 'groupPreference',
        ],
        'adult' => [
            'workPattern', 'workPatternDetails', 'longSitting', 'startingPace', 'previousYoga', 'screenHours',
            'deskSetup', 'sessionLength', 'caregivingLoad',
        ],
        'senior_citizen' => [
            'fallsHistory', 'mobilityLevel', 'mobilityAidDetails', 'floorTransfer', 'balanceSupport', 'chairPractice',
            'dizzinessOnStanding', 'jointReplacement', 'jointReplacementDetails', 'sensoryDifficulty', 'accompaniedToSession',
        ],
        'pregnant_woman' => [
            'trimester', 'weeksPregnant', 'doctorApproval', 'complications', 'complicationsDetails', 'firstPregnancy',
            'prenatalYogaBefore', 'nauseaOrDizziness', 'pelvicOrBackPain', 'swellingFlagged',
        ],
        'postpartum_mother' => [
            'monthsAfterDelivery', 'deliveryType', 'doctorClearance', 'breastfeeding',
            'pelvicFloorIssues', 'pelvicFloorDetails', 'firstChild', 'incisionPain', 'sleepDisruption',
            'babyCareSupport', 'energyLevel',
        ],
    ];

    public function show(Request $request): void
    {
        $profile = ClientProfile::findByUserId(Auth::id());
        $profile = $profile ? ClientProfile::hydrate($profile) : null;

        $this->success([
            'category' => $profile['client_category'] ?? null,
            'assessment' => $profile['health_assessment'] ?? null,
            'stage' => ClientProfile::stageReached($profile),
        ]);
    }

    /**
     * Auto-save for one conversational step. Every step sends its full
     * topic state at once (see ClientProfile::mergeHealthAssessment) plus
     * the key of whichever step should be resumed next; `final: true` with
     * `stage: N` marks stage N complete once the client's last applicable
     * step for that stage is done. Stage never regresses — a client
     * re-visiting an earlier stage's steps can't drop the stored stage back.
     */
    public function saveStep(Request $request): void
    {
        $userId = Auth::id();
        $stepKey = (string) $request->input('step', '');
        $payload = $request->input('data', []);
        $nextStep = (string) $request->input('nextStep', '');
        $isFinal = (bool) $request->input('final', false);
        $targetStage = (int) $request->input('stage', 2);

        if (!in_array($stepKey, self::STEP_KEYS, true) || !is_array($payload)) {
            $this->fail('Invalid step.', 422);
        }

        $profileBefore = ClientProfile::findByUserId($userId);
        $profileBefore = $profileBefore ? ClientProfile::hydrate($profileBefore) : null;

        if ($stepKey === 'categorySafety') {
            $category = $profileBefore['client_category'] ?? null;
            $allowedFields = self::CATEGORY_SAFETY_FIELDS[$category] ?? [];

            foreach (array_keys($payload) as $field) {
                if (!in_array($field, $allowedFields, true)) {
                    $this->fail('This question does not apply to your category.', 422);
                }
            }
        }

        $oldTopicValue = ($profileBefore['health_assessment'] ?? null)[$stepKey] ?? null;
        $stageBefore = ClientProfile::stageReached($profileBefore);

        $patch = [$stepKey => $payload];
        if ($nextStep !== '') {
            $patch['currentStep'] = $nextStep;
        }
        if ($isFinal) {
            $patch['stage'] = max($stageBefore, $targetStage);
            if (empty($profileBefore['onboarding_completed_at'])) {
                ClientProfile::upsertForUser($userId, ['onboarding_completed_at' => date('Y-m-d H:i:s')]);
            }
        }

        $merged = ClientProfile::mergeHealthAssessment($userId, $patch);

        if (json_encode($oldTopicValue) !== json_encode($payload)) {
            $timezone = (string) $request->input('timezone', $profileBefore['timezone'] ?? 'UTC');
            ProfileEditHistory::record($userId, $userId, 'health_assessment.' . $stepKey, $oldTopicValue, $payload, $request->userAgent(), $timezone);
        }

        if ($isFinal && ($patch['stage'] ?? $stageBefore) > $stageBefore) {
            ActivityLog::log($userId, 'health_assessment_stage_completed', 'user', $userId, ['stage' => $patch['stage']]);
        }

        $this->success(['assessment' => $merged]);
    }
}
