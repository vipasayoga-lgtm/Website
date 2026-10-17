<?php

use App\Core\View;

$pageTitle = View::e($client['name']) . ' — Complete Onboarding & Health Assessment Q&A';
$pageCss = 'trainer';
$portal = 'trainer';
$active = 'students';

$statusMeta = [
    'awaiting_trainer_approval' => ['badge-pending', 'Awaiting you'],
    'confirmed' => ['badge-confirmed', 'Confirmed'],
    'completed' => ['badge-completed', 'Completed'],
    'cancelled' => ['badge-cancelled', 'Cancelled'],
    'rescheduled' => ['badge-pending', 'Rescheduled'],
];

$fmt = static function ($value) {
    if ($value === null || $value === '' || $value === []) {
        return '—';
    }
    if (is_bool($value)) {
        return $value ? 'Yes' : 'No';
    }
    if (is_array($value)) {
        return implode(', ', array_map(static fn ($v) => ucwords(str_replace('_', ' ', (string) $v)), $value));
    }
    return ucwords(str_replace('_', ' ', (string) $value));
};

$screening = $safety['screening'] ?? [];
$demographics = $safety['demographics'] ?? [];
$wellness = $safety['wellness'] ?? [];
$emergency = $safety['emergencyContact'] ?? [];
$raw = $rawAssessment ?? [];

$haScreening = $raw['healthScreening'] ?? [];
$haMedical = $raw['medicalHistory'] ?? [];
$haPain = $raw['painAssessment'] ?? [];
$haLifestyle = $raw['lifestyle'] ?? [];
$haSleep = $raw['sleep'] ?? [];
$haExercise = $raw['exercise'] ?? [];
$haStress = $raw['stress'] ?? [];
$haYogaExp = $raw['yogaExperience'] ?? [];
$haMedication = $raw['medication'] ?? [];
$haAdvanced = $raw['advancedHealth'] ?? [];

$categoryQuestionMap = [
    'adult' => [
        'workPattern' => 'Which best describes your working day?',
        'longSitting' => 'Do you sit for six hours or more on a typical day?',
        'startingPace' => 'What pace would you like to start at?',
        'previousYoga' => 'Have you practised yoga before?',
        'screenHours' => 'Screen hours on a typical day?',
        'deskSetup' => 'Do you work from a proper desk and chair?',
        'sessionLength' => 'How long can you practise in one session?',
        'caregivingLoad' => 'Do you have caregiving duties that affect your schedule?',
    ],
    'senior_citizen' => [
        'fallsHistory' => 'Any history of falls or near-falls?',
        'mobilityLevel' => 'What is your mobility level?',
        'floorTransfer' => 'Can you get down to and up from the floor comfortably?',
        'balanceSupport' => 'Do you need a chair or wall for balance work?',
        'chairPractice' => 'Would you prefer seated or chair-based practice?',
        'dizzinessOnStanding' => 'Do you feel dizzy when standing up?',
        'jointReplacement' => 'Any joint replacement or implant?',
        'sensoryDifficulty' => 'Any hearing or vision difficulty following instructions?',
        'accompaniedToSession' => 'Will someone accompany you to sessions?',
    ],
    'pregnant_woman' => [
        'trimester' => 'Which trimester are you currently in?',
        'weeksPregnant' => 'How many weeks pregnant are you?',
        'doctorApproval' => 'Has your doctor approved yoga practice?',
        'complications' => 'Has your doctor flagged any complications this pregnancy?',
        'firstPregnancy' => 'Is this your first pregnancy?',
        'prenatalYogaBefore' => 'Have you attended prenatal yoga before?',
        'nauseaOrDizziness' => 'Are you experiencing nausea or dizziness?',
        'pelvicOrBackPain' => 'Any pelvic or lower-back pain?',
        'swellingFlagged' => 'Has your doctor flagged swelling in your hands or feet?',
    ],
    'postpartum_mother' => [
        'monthsAfterDelivery' => 'How many months after delivery are you?',
        'deliveryType' => 'What was your delivery type?',
        'doctorClearance' => 'Has your doctor cleared you for exercise?',
        'breastfeeding' => 'Are you currently breastfeeding?',
        'pelvicFloorIssues' => 'Any diagnosed pelvic floor or abdominal separation?',
        'firstChild' => 'Is this your first child?',
        'incisionPain' => 'Any pain at the incision or perineal area?',
        'sleepDisruption' => 'Is your sleep disrupted at night?',
        'babyCareSupport' => 'Do you have help with baby care during session times?',
        'energyLevel' => 'How is your energy through the day?',
    ],
    'child' => [
        'guardianPresent' => 'Will a parent or guardian be present during the session?',
        'guardianRelationship' => 'Who will accompany the child?',
        'schoolSports' => 'Does the child take part in school sports or PE?',
        'growthConcerns' => 'Has a doctor raised any growth or posture concerns?',
        'previousYoga' => 'Has the child practised yoga before?',
        'screenTime' => 'Screen time on a typical day?',
        'instructionSupport' => 'Does the child need extra support to follow group instructions?',
        'supportDevice' => 'Does the child use glasses, braces, or any support device?',
    ],
    'teenager' => [
        'guardianConsent' => 'Has a parent or guardian agreed to these sessions?',
        'schoolSports' => 'Do you take part in school or college sports?',
        'studyHours' => 'Hours a day sitting to study?',
        'examStress' => 'Are you in an exam or high-pressure period right now?',
        'previousYoga' => 'Have you practised yoga before?',
        'screenTime' => 'Screen time outside study?',
        'studyPosture' => 'Do you get neck or back ache from studying?',
        'energyLevel' => 'How is your energy through the day?',
        'groupPreference' => 'Would you rather practise alone or in a group?',
    ],
];

$hasAnyAlert = !empty($safety['medicalAlerts']) || !empty($safety['painAreas']) || !empty($safety['restrictions']['hasRestriction']) || !empty($safety['restrictions']['recentFalls']) || !empty($safety['medication']['takingMedication']) || !empty($screening['hasAllergies']) || !empty($screening['advisedNotToExercise']) || !empty($haScreening['hasAllergies']) || !empty($haScreening['advisedNotToExercise']);
?>

<style>
.student-detail-wrap {
    padding-block: var(--space-4) var(--space-8);
    font-size: 0.8125rem;
}
.student-header-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: var(--space-4);
    background: var(--surface-card);
    padding: 16px 24px;
    border-radius: var(--radius-2xl);
    box-shadow: 0 10px 30px -5px rgba(15, 51, 73, 0.06);
    border: 1px solid var(--border-subtle);
    margin-bottom: var(--space-6);
}
.compact-card {
    background: var(--surface-card);
    border-radius: var(--radius-xl);
    padding: 16px 20px;
    border: 1px solid var(--border-subtle);
    box-shadow: 0 4px 16px -4px rgba(15, 51, 73, 0.04);
    margin-bottom: var(--space-5);
}
.compact-card__title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-top: 0;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.qa-box {
    background: var(--bg-subtle, #F9FAFB);
    padding: 10px 14px;
    border-radius: var(--radius-md);
    border: 1px solid var(--border-subtle);
    margin-bottom: 8px;
}
.qa-box__question {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 2px;
}
.qa-box__answer {
    font-size: 0.85rem;
}
.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px 20px;
}
.detail-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.detail-item__label {
    font-size: 0.725rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
}
.detail-item__value {
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--text-primary);
    word-break: break-word;
}
.alert-card {
    background: #FFFBEB;
    border: 1px solid #FCD34D;
    border-left: 4px solid #F59E0B;
    border-radius: var(--radius-xl);
    padding: 14px 18px;
    margin-bottom: var(--space-5);
}
.alert-card__title {
    font-size: 0.875rem;
    font-weight: 700;
    color: #92400E;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

@media print {
    .dash-sidebar, .dash-topbar, .no-print, .dash-sidebar__mobile-overlay {
        display: none !important;
    }
    body, .dash-shell__main, .dash-shell__content {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .student-detail-wrap {
        padding: 0 !important;
    }
    .compact-card, .student-header-bar, .alert-card {
        box-shadow: none !important;
        border: 1px solid #ccc !important;
        page-break-inside: avoid;
    }
    .print-only-header {
        display: block !important;
        margin-bottom: 20px;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
    }
}
.print-only-header {
    display: none;
}
</style>

<div class="container student-detail-wrap">

    <div class="print-only-header">
        <h2 style="margin:0;font-size:1.5rem;color:#0F3349">Vipasa Yoga — All Onboarding &amp; Pre-Booking Assessment Report</h2>
        <p style="margin:4px 0 0;font-size:0.85rem;color:#666">Student: <?= View::e($client['name']) ?> (<?= View::e($client['email']) ?>) · Generated on <?= date('d M Y, g:i A') ?></p>
    </div>

    <!-- Header Action Bar -->
    <div class="student-header-bar no-print">
        <div class="flex items-center gap-3">
            <a href="/trainer/students" class="btn btn-ghost btn-xs flex items-center gap-1" title="Back to Students">
                &larr; Back to Students
            </a>
            <div>
                <h1 style="font-size:1.35rem;font-weight:700;margin:0;color:var(--text-primary)"><?= View::e($client['name']) ?></h1>
                <div class="text-muted" style="font-size:0.8rem;margin-top:2px">
                    <?= View::e($client['email'] ?? '') ?>
                    <?= !empty($client['phone']) ? ' · ' . View::e($client['phone']) : '' ?>
                    <?= !empty($demographics['category']) ? ' · <span class="chip" style="font-size:10px;padding:2px 8px">' . View::e($fmt($demographics['category'])) . '</span>' : '' ?>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right" style="font-size:0.75rem">
                <span class="text-muted">Profile Completion:</span>
                <strong style="color:var(--color-primary)"><?= (int) $safety['completionPercent'] ?>%</strong>
            </div>
            <button type="button" class="btn btn-primary btn-sm flex items-center gap-1" onclick="window.print()" style="border-radius:var(--radius-lg)">
                <?= View::icon('file-text', 'icon', 14) ?>
                <span>Download Assessment Report</span>
            </button>
        </div>
    </div>

    <!-- Critical Medical & Health Safety Alerts -->
    <?php if ($hasAnyAlert): ?>
    <div class="alert-card">
        <div class="alert-card__title">
            <?= View::icon('shield', 'icon', 16) ?>
            Critical Medical &amp; Health Safety Alerts
        </div>
        <div class="flex flex-col gap-2" style="font-size:0.8125rem;color:#78350F">
            <?php foreach ($safety['medicalAlerts'] as $m): ?>
            <div>
                <strong><?= View::e($fmt($m['condition'])) ?></strong>: medication: <?= View::e($fmt($m['details']['medication'] ?? null)) ?>, doctor restriction: <?= View::e($fmt($m['details']['doctorRestriction'] ?? null)) ?><?= !empty($m['details']['notes']) ? ' — Notes: ' . View::e($m['details']['notes']) : '' ?>
            </div>
            <?php endforeach; ?>
            <?php if (!empty($safety['restrictions']['hasRestriction'])): ?>
            <div><strong>Doctor Restriction on File:</strong> <?= !empty($safety['restrictions']['notes']) ? View::e($safety['restrictions']['notes']) : 'Yes (medical clearance required)' ?></div>
            <?php endif; ?>
            <?php if (!empty($safety['restrictions']['recentFalls'])): ?>
            <div><strong>Fall Risk Alert:</strong> Recent falls or balance issues reported.</div>
            <?php endif; ?>
            <?php if (!empty($safety['medication']['takingMedication']) || !empty($haScreening['takingMedication'])): ?>
            <div><strong>Active Medication:</strong> <?= View::e($safety['medication']['list'] ?? $haScreening['takingMedicationNotes'] ?? 'Yes') ?></div>
            <?php endif; ?>
            <?php if (!empty($screening['advisedNotToExercise']) || !empty($haScreening['advisedNotToExercise'])): ?>
            <div><strong>Exercise Restriction:</strong> Advised not to exercise <?= !empty($screening['advisedNotToExerciseNotes']) ? ' — ' . View::e($screening['advisedNotToExerciseNotes']) : (!empty($haScreening['advisedNotToExerciseNotes']) ? ' — ' . View::e($haScreening['advisedNotToExerciseNotes']) : '') ?></div>
            <?php endif; ?>
            <?php if (!empty($screening['hasAllergies']) || !empty($haScreening['hasAllergies'])): ?>
            <div><strong>Allergies Reported:</strong> <?= View::e($screening['hasAllergiesNotes'] ?? $haScreening['hasAllergiesNotes'] ?? 'Yes') ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- STAGE 1: Registration & First Login Onboarding Wizard Questions & Answers -->
    <div class="compact-card">
        <h3 class="compact-card__title">
            <?= View::icon('user', 'icon', 16) ?>
            Stage 1: Registration &amp; First Login Profile Wizard (Questions &amp; Answers)
        </h3>
        <div class="flex flex-col gap-2">
            <div class="qa-box">
                <div class="qa-box__question">Q: What is your full name, email address, and mobile number?</div>
                <div class="qa-box__answer">
                    Answer: <strong><?= View::e($client['name']) ?></strong> (<?= View::e($client['email']) ?> · Mobile: <?= View::e($client['phone'] ?: '—') ?>)
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: When did you register and last log in to your account?</div>
                <div class="qa-box__answer">
                    Answer: Registered: <strong><?= !empty($client['created_at']) ? View::e(date('d M Y', strtotime($client['created_at']))) : '—' ?></strong> · Last active: <strong><?= !empty($client['last_login_at']) ? View::e(date('d M Y, g:i A', strtotime($client['last_login_at']))) : 'Recently Active' ?></strong>
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: Which category best describes you?</div>
                <div class="qa-box__answer">
                    Answer: <strong><?= View::e($fmt($demographics['category'] ?? $profile['client_category'] ?? null)) ?></strong>
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: What is your date of birth, age, and gender?</div>
                <div class="qa-box__answer">
                    Answer: Date of Birth: <strong><?= View::e($profile['date_of_birth'] ?? '—') ?></strong> · Age: <strong><?= View::e($demographics['age'] ? $demographics['age'] . ' yrs' : '—') ?></strong> · Gender: <strong><?= View::e($fmt($demographics['gender'] ?? $profile['gender'] ?? null)) ?></strong>
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: What is your height, weight, and location?</div>
                <div class="qa-box__answer">
                    Answer: Height: <strong><?= View::e($demographics['height_cm'] ? $demographics['height_cm'] . ' cm' : '—') ?></strong> · Weight: <strong><?= View::e($demographics['weight_kg'] ? $demographics['weight_kg'] . ' kg' : '—') ?></strong> · City/Country: <strong><?= View::e(implode(', ', array_filter([$demographics['city'] ?? $profile['city'] ?? null, $demographics['country'] ?? $profile['country'] ?? null])) ?: '—') ?></strong>
                </div>
            </div>

            <?php if (!empty($profile['bio'])): ?>
            <div class="qa-box">
                <div class="qa-box__question">Q: What is your short personal bio or background?</div>
                <div class="qa-box__answer">
                    Answer: <strong><?= View::e($profile['bio']) ?></strong>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- STAGE 2: Pre-Booking Health & Safety Questionnaire (Asked BEFORE Booking Session) -->
    <div class="compact-card">
        <h3 class="compact-card__title">
            <?= View::icon('sliders', 'icon', 16) ?>
            Stage 2: Health &amp; Safety Intake Questionnaire (Asked BEFORE Booking Session)
        </h3>

        <div class="flex flex-col gap-2">

            <!-- Question 1: Undergone Surgery -->
            <div class="qa-box">
                <div class="qa-box__question">Q: Have you undergone surgery?</div>
                <div class="qa-box__answer">
                    Answer: 
                    <?php 
                    $surgeryVal = $haScreening['hadSurgery'] ?? $screening['hadSurgery'] ?? null;
                    if ($surgeryVal === true || $surgeryVal === 'yes' || $surgeryVal === '1' || !empty($safety['restrictions']['surgeries']['hadSurgery'])): ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Yes</strong>
                        <?php if (!empty($haScreening['hadSurgeryNotes']) || !empty($safety['restrictions']['surgeries']['type'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Details: <?= View::e($haScreening['hadSurgeryNotes'] ?? $safety['restrictions']['surgeries']['type']) ?>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <strong style="color:var(--color-success, #10B981)">No</strong>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Question 2: Taking Medication -->
            <div class="qa-box">
                <div class="qa-box__question">Q: Are you currently taking any medication?</div>
                <div class="qa-box__answer">
                    Answer: 
                    <?php 
                    $medVal = $safety['medication']['takingMedication'] ?? $haScreening['takingMedication'] ?? $screening['takingMedication'] ?? null;
                    if ($medVal === true || $medVal === 'yes' || $medVal === '1'): ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Yes</strong>
                        <?php if (!empty($safety['medication']['list']) || !empty($haScreening['takingMedicationNotes'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Medications: <?= View::e($safety['medication']['list'] ?? $haScreening['takingMedicationNotes']) ?>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <strong style="color:var(--color-success, #10B981)">No</strong>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Question 3: Had Injuries -->
            <div class="qa-box">
                <div class="qa-box__question">Q: Have you had any injuries?</div>
                <div class="qa-box__answer">
                    Answer: 
                    <?php 
                    $injVal = $haScreening['hadInjuries'] ?? $screening['hadInjuries'] ?? null;
                    if ($injVal === true || $injVal === 'yes' || $injVal === '1'): ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Yes</strong>
                        <?php if (!empty($haScreening['hadInjuriesNotes']) || !empty($screening['hadInjuriesNotes'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Injury Details: <?= View::e($haScreening['hadInjuriesNotes'] ?? $screening['hadInjuriesNotes']) ?>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <strong style="color:var(--color-success, #10B981)">No</strong>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Question 4: Has Allergies -->
            <div class="qa-box">
                <div class="qa-box__question">Q: Do you have any allergies?</div>
                <div class="qa-box__answer">
                    Answer: 
                    <?php 
                    $allVal = $haScreening['hasAllergies'] ?? $screening['hasAllergies'] ?? null;
                    if ($allVal === true || $allVal === 'yes' || $allVal === '1'): ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Yes</strong>
                        <?php if (!empty($haScreening['hasAllergiesNotes']) || !empty($screening['hasAllergiesNotes'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Allergies: <?= View::e($haScreening['hasAllergiesNotes'] ?? $screening['hasAllergiesNotes']) ?>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <strong style="color:var(--color-success, #10B981)">No</strong>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Question 5: Advised Not to Exercise -->
            <div class="qa-box">
                <div class="qa-box__question">Q: Has a doctor ever advised you not to exercise?</div>
                <div class="qa-box__answer">
                    Answer: 
                    <?php 
                    $advVal = $haScreening['advisedNotToExercise'] ?? $screening['advisedNotToExercise'] ?? null;
                    if ($advVal === true || $advVal === 'yes' || $advVal === '1'): ?>
                        <strong style="color:var(--color-error, #EF4444)">Yes</strong>
                        <?php if (!empty($haScreening['advisedNotToExerciseNotes']) || !empty($screening['advisedNotToExerciseNotes'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Reason / Advice: <?= View::e($haScreening['advisedNotToExerciseNotes'] ?? $screening['advisedNotToExerciseNotes']) ?>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <strong style="color:var(--color-success, #10B981)">No</strong>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Question 6: Doctor Restrictions -->
            <div class="qa-box">
                <div class="qa-box__question">Q: Do you have any doctor restrictions or exercise limitations?</div>
                <div class="qa-box__answer">
                    Answer: 
                    <?php 
                    $docVal = $safety['restrictions']['hasRestriction'] ?? null;
                    if ($docVal === true || $docVal === 'yes' || $docVal === '1'): ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Yes</strong>
                        <?php if (!empty($safety['restrictions']['notes'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Doctor Notes: <?= View::e($safety['restrictions']['notes']) ?>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <strong style="color:var(--color-success, #10B981)">No</strong>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Question 7: Medical Conditions History -->
            <div class="qa-box">
                <div class="qa-box__question">Q: Do you have any diagnosed medical conditions (e.g. High BP, Heart, Diabetes, Asthma, Spine)?</div>
                <div class="qa-box__answer">
                    Answer: 
                    <?php if (empty($safety['medicalAlerts'])): ?>
                        <strong style="color:var(--color-success, #10B981)">None Reported</strong>
                    <?php else: ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Reported Conditions:</strong>
                        <div class="flex flex-col gap-1 mt-1">
                            <?php foreach ($safety['medicalAlerts'] as $m): ?>
                            <div>
                                • <strong><?= View::e($fmt($m['condition'])) ?></strong>
                                <?php if (!empty($m['details']['medication'])): ?> (Medication: <?= View::e($fmt($m['details']['medication'])) ?>)<?php endif; ?>
                                <?php if (!empty($m['details']['doctorRestriction'])): ?> (Doctor Restriction: <?= View::e($fmt($m['details']['doctorRestriction'])) ?>)<?php endif; ?>
                                <?php if (!empty($m['details']['notes'])): ?> — Notes: <?= View::e($m['details']['notes']) ?><?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Question 8: Pain & Discomfort Assessment -->
            <div class="qa-box">
                <div class="qa-box__question">Q: Are you experiencing pain or physical discomfort in any body areas?</div>
                <div class="qa-box__answer">
                    Answer: 
                    <?php if (empty($safety['painAreas'])): ?>
                        <strong style="color:var(--color-success, #10B981)">No Pain Reported</strong>
                    <?php else: ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Pain Reported in Body Areas:</strong>
                        <div class="flex flex-col gap-1 mt-1">
                            <?php foreach ($safety['painAreas'] as $p): ?>
                            <div>
                                • <strong><?= View::e($fmt($p['area'])) ?></strong> — Severity Level: <?= View::e($fmt($p['details']['severity'] ?? 'N/A')) ?> / 10, Side: <?= View::e($fmt($p['details']['side'] ?? 'Center')) ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Category-Specific Health Questionnaire Answers (Explicit Q&A) -->
    <?php 
    $catKey = strtolower((string)($demographics['category'] ?? $profile['client_category'] ?? 'adult'));
    $catQuestions = $categoryQuestionMap[$catKey] ?? $categoryQuestionMap['adult'];
    $catSafety = $raw['categorySafety'] ?? [];
    ?>
    <div class="compact-card">
        <h3 class="compact-card__title">
            <?= View::icon('user', 'icon', 16) ?>
            Category-Specific Health Questionnaire (<?= View::e($fmt($catKey)) ?> Q&amp;A)
        </h3>
        <div class="flex flex-col gap-2">
            <?php foreach ($catQuestions as $qFieldKey => $qQuestionText):
                $answerVal = $catSafety[$qFieldKey] ?? null;
            ?>
            <div class="qa-box">
                <div class="qa-box__question">Q: <?= View::e($qQuestionText) ?></div>
                <div class="qa-box__answer">
                    Answer: <strong><?= View::e($fmt($answerVal)) ?></strong>
                    <?php 
                    $detailsKey = $qFieldKey . 'Details';
                    if (!empty($catSafety[$detailsKey])): ?>
                    <div style="margin-top:2px;color:var(--text-primary)">
                        Details: <?= View::e($catSafety[$detailsKey]) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- STAGE 3: Wellness Profile & Practice Lifestyle Questionnaire (Q&A) -->
    <div class="compact-card">
        <h3 class="compact-card__title">
            <?= View::icon('calendar-week', 'icon', 16) ?>
            Stage 3: Wellness Profile &amp; Practice Lifestyle Questionnaire (Questions &amp; Answers)
        </h3>
        <div class="flex flex-col gap-2">
            <div class="qa-box">
                <div class="qa-box__question">Q: What are your primary yoga &amp; wellness practice goals?</div>
                <div class="qa-box__answer">Answer: <strong><?= View::e($fmt($safety['goals'] ?? $raw['goals']['selected'] ?? null)) ?></strong></div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: What is your occupation &amp; working pattern?</div>
                <div class="qa-box__answer">
                    Answer: <strong><?= View::e($fmt($haLifestyle['occupation'] ?? '—')) ?></strong>
                    <?php if (isset($haLifestyle['smoking'])): ?> · Smoke: <strong><?= $haLifestyle['smoking'] ? 'Yes' : 'No' ?></strong><?php endif; ?>
                    <?php if (isset($haLifestyle['alcohol'])): ?> · Alcohol: <strong><?= $haLifestyle['alcohol'] ? 'Yes' : 'No' ?></strong><?php endif; ?>
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: What are your average sleep hours &amp; sleep quality?</div>
                <div class="qa-box__answer">
                    Answer: <strong><?= View::e($fmt($haSleep['hours'] ?? null)) ?> hrs</strong> · Quality: <strong><?= View::e($fmt($haSleep['quality'] ?? $wellness['sleepQuality'] ?? null)) ?></strong>
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: What is your current exercise frequency &amp; preferred intensity?</div>
                <div class="qa-box__answer">
                    Answer: Frequency: <strong><?= View::e($fmt($haExercise['frequency'] ?? null)) ?></strong> · Intensity: <strong><?= View::e($fmt($haExercise['intensity'] ?? null)) ?></strong>
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: What is your stress level &amp; main stressor?</div>
                <div class="qa-box__answer">
                    Answer: Stress Level: <strong><?= View::e($fmt($haStress['level'] ?? $wellness['stressLevel'] ?? null)) ?></strong>
                    <?php if (!empty($haStress['stressor'])): ?> · Main stressor: <strong><?= View::e($fmt($haStress['stressor'])) ?></strong><?php endif; ?>
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: Have you practised yoga before &amp; what is your experience level?</div>
                <div class="qa-box__answer">
                    Answer: <strong><?= View::e($fmt($haYogaExp['level'] ?? $wellness['experience'] ?? $wellness['yogaExperience'] ?? null)) ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- STAGE 4: Advanced Health Checks & Medical Updates (Q&A) -->
    <?php if (!empty($haAdvanced)): ?>
    <div class="compact-card">
        <h3 class="compact-card__title">
            <?= View::icon('file-text', 'icon', 16) ?>
            Stage 4: Advanced Health Check &amp; Updates (Questions &amp; Answers)
        </h3>
        <div class="flex flex-col gap-2">
            <div class="qa-box">
                <div class="qa-box__question">Q: Has anything changed with your health status since your last check-in?</div>
                <div class="qa-box__answer">
                    Answer: <strong><?= !empty($haAdvanced['changed']) ? 'Yes — ' . View::e($haAdvanced['notes'] ?? 'Updated') : 'No changes reported' ?></strong>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- STAGE 5: Emergency Contact & Special Instructor Notes -->
    <div class="compact-card">
        <h3 class="compact-card__title">
            <?= View::icon('heart', 'icon', 16) ?>
            Stage 5: Emergency Contact &amp; Instructor Special Notes
        </h3>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-item__label">Emergency Contact Name</span>
                <span class="detail-item__value"><?= View::e($emergency['name'] ?: '—') ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-item__label">Contact Phone</span>
                <span class="detail-item__value"><?= View::e($emergency['phone'] ?: '—') ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-item__label">Relationship</span>
                <span class="detail-item__value"><?= View::e($emergency['relationship'] ?: '—') ?></span>
            </div>
        </div>
        <?php if (!empty($safety['additionalNotes'])): ?>
        <div style="margin-top:12px;padding-top:10px;border-top:1px solid var(--border-subtle)">
            <span class="detail-item__label">Special Instructor Notes</span>
            <div style="font-size:0.8rem;color:var(--text-primary);margin-top:2px"><?= View::e($safety['additionalNotes']) ?></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Complete Session History -->
    <div class="compact-card">
        <h3 class="compact-card__title">
            <?= View::icon('clock', 'icon', 16) ?>
            Session History with You (<?= count($history) ?>)
        </h3>
        <?php if (empty($history)): ?>
        <p class="text-muted mb-0">No booking history recorded for this student.</p>
        <?php else: ?>
        <div class="dashboard-table-container">
            <table class="dashboard-table" style="font-size:0.8125rem">
                <thead>
                    <tr>
                        <th>Date &amp; Time</th>
                        <th>Ref</th>
                        <th>Package</th>
                        <th>Status</th>
                        <th style="text-align:right" class="no-print">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $booking):
                        [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
                    ?>
                    <tr>
                        <td>
                            <strong style="white-space:nowrap"><?= View::e(date('d M Y', strtotime($booking['slot_date']))) ?></strong>
                            <div class="text-muted" style="font-size:0.75rem"><?= View::e(date('g:i A', strtotime($booking['start_time']))) ?></div>
                        </td>
                        <td><span style="font-family:monospace;font-weight:600;color:var(--color-primary)"><?= View::e($booking['booking_ref']) ?></span></td>
                        <td><?= View::e($booking['package_name']) ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span></td>
                        <td style="text-align:right" class="no-print">
                            <?php if (in_array($booking['status'], ['confirmed', 'completed'], true)): ?>
                            <a href="/trainer/sessions/<?= View::e($booking['booking_ref']) ?>" class="btn btn-secondary btn-xs">Manage Session</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- Past Trainer Session Notes -->
    <?php if (!empty($feedback)): ?>
    <div class="compact-card">
        <h3 class="compact-card__title">
            <?= View::icon('file-text', 'icon', 16) ?>
            Your Past Session Notes &amp; Recommendations
        </h3>
        <div class="flex flex-col gap-3">
            <?php foreach ($feedback as $entry): ?>
            <div style="background:var(--bg-subtle, #F9FAFB);padding:12px 16px;border-radius:var(--radius-lg);border-left:3px solid var(--color-primary)">
                <div class="flex items-center justify-between mb-1" style="font-size:0.75rem">
                    <span class="text-muted"><?= View::e(date('d M Y, g:i A', strtotime($entry['created_at']))) ?></span>
                    <div>
                        <?= $entry['attendance'] ? '<span class="badge badge-subtle" style="font-size:10px">' . ucfirst($entry['attendance']) . '</span>' : '' ?>
                        <?= $entry['rating'] ? '<span style="color:#F59E0B;font-weight:bold;margin-left:6px">★ ' . (int)$entry['rating'] . '/5</span>' : '' ?>
                    </div>
                </div>
                <?php if (!empty($entry['session_notes'])): ?>
                <div style="font-size:0.8125rem;color:var(--text-primary);line-height:1.4"><strong>Notes:</strong> <?= View::e($entry['session_notes']) ?></div>
                <?php endif; ?>
                <?php if (!empty($entry['recommendation'])): ?>
                <div style="font-size:0.8125rem;color:var(--color-primary);line-height:1.4;margin-top:4px"><strong>Recommendation:</strong> <?= View::e($entry['recommendation']) ?></div>
                <?php endif; ?>
                <?php if (!empty($entry['homework'])): ?>
                <div style="font-size:0.8125rem;color:var(--text-muted);line-height:1.4;margin-top:4px"><strong>Homework:</strong> <?= View::e($entry['homework']) ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>
