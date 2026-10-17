<?php

use App\Core\View;

$pageTitle = $customer['name'] . ' — Complete Health Record & Assessment Q&A — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'admin';
$active = 'customers';

$ha = $customer['health_assessment'] ?? [];
$ha = is_array($ha) ? $ha : [];

$knownLabels = [
    'lt_1y' => 'Less than 1 year',
    '1_5y' => '1-5 years',
    '5y_plus' => '5+ years',
];

$fmt = static function ($value) use ($knownLabels) {
    if ($value === null || $value === '' || $value === []) {
        return '—';
    }
    if (is_bool($value)) {
        return $value ? 'Yes' : 'No';
    }
    if (is_array($value)) {
        return implode(', ', array_map(static fn ($v) => $knownLabels[$v] ?? ucwords(str_replace('_', ' ', (string) $v)), $value));
    }
    return $knownLabels[$value] ?? ucwords(str_replace('_', ' ', (string) $value));
};

$statusMeta = [
    'pending_payment' => ['badge-pending', 'Pending payment'],
    'awaiting_trainer_approval' => ['badge-pending', 'Awaiting approval'],
    'confirmed' => ['badge-confirmed', 'Confirmed'],
    'completed' => ['badge-completed', 'Completed'],
    'cancelled' => ['badge-cancelled', 'Cancelled'],
    'rescheduled' => ['badge-pending', 'Rescheduled'],
];

$medicalHistory = $ha['medicalHistory'] ?? [];
$conditions = array_filter((array) ($medicalHistory['conditions'] ?? []), static fn ($c) => $c !== 'none_of_these');
$painAssessment = $ha['painAssessment'] ?? [];
$painAreas = array_filter((array) ($painAssessment['areas'] ?? []), static fn ($a) => $a !== 'no_pain');
$doctorRestrictions = $ha['doctorRestrictions'] ?? [];
$fallRisk = $ha['fallRisk'] ?? [];
$medication = $ha['medication'] ?? [];
$screening = $ha['healthScreening'] ?? [];
$catSafety = $ha['categorySafety'] ?? [];
$haLifestyle = $ha['lifestyle'] ?? [];
$haSleep = $ha['sleep'] ?? [];
$haExercise = $ha['exercise'] ?? [];
$haStress = $ha['stress'] ?? [];
$haYogaExp = $ha['yogaExperience'] ?? [];
$haAdvanced = $ha['advancedHealth'] ?? [];

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

$alerts = [];
if (!empty($conditions)) {
    $alerts[] = 'Medical conditions on file: ' . $fmt($conditions);
}
if (!empty($painAreas)) {
    $alerts[] = 'Reported pain areas: ' . $fmt($painAreas);
}
if (!empty($doctorRestrictions['hasRestriction'])) {
    $alerts[] = 'Doctor-specified activity restriction on file.';
}
if (!empty($fallRisk['recentFalls'])) {
    $alerts[] = 'Recent falls or near-falls reported.';
}
if (!empty($medication['takingMedication'])) {
    $alerts[] = 'Currently taking medication' . (!empty($medication['list']) ? ': ' . View::e($medication['list']) : '.');
}
if (!empty($screening['advisedNotToExercise'])) {
    $alerts[] = 'Advised not to exercise by doctor' . (!empty($screening['advisedNotToExerciseNotes']) ? ': ' . View::e($screening['advisedNotToExerciseNotes']) : '.');
}

$catKey = strtolower((string)($customer['client_category'] ?? 'adult'));
$catQuestions = $categoryQuestionMap[$catKey] ?? $categoryQuestionMap['adult'];
?>

<style>
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
@media print {
    .dash-sidebar, .dash-topbar, .no-print, .dash-sidebar__mobile-overlay {
        display: none !important;
    }
    body, .dash-shell__main, .dash-shell__content {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .card, .alert-card {
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

<div class="container" style="padding-block:var(--space-6) var(--space-12)">

    <div class="print-only-header">
        <h2 style="margin:0;font-size:1.5rem;color:#0F3349">Vipasa Yoga — Admin Customer Health Record &amp; Assessment Report</h2>
        <p style="margin:4px 0 0;font-size:0.85rem;color:#666">Customer: <?= View::e($customer['name']) ?> (<?= View::e($customer['email']) ?>) · Generated on <?= date('d M Y, g:i A') ?></p>
    </div>

    <div class="no-print mb-4 flex items-center justify-between flex-wrap gap-3">
        <p style="margin:0"><a href="/admin/customers/<?= (int) $customer['id'] ?>">&larr; Back to <?= View::e($customer['name']) ?></a></p>
        <button type="button" class="btn btn-primary btn-sm flex items-center gap-1" onclick="window.print()">
            <?= View::icon('file-text', 'icon', 14) ?>
            <span>Download Assessment Report</span>
        </button>
    </div>

    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <div>
            <h1 style="margin:0;font-size:var(--font-size-xl)"><?= View::e($customer['name']) ?> — Health Record &amp; Q&amp;A Intake</h1>
            <p class="text-muted" style="margin:2px 0 0"><?= View::e($customer['email'] ?: '—') ?> · <?= View::e($customer['phone'] ?: '—') ?></p>
        </div>
        <div class="flex items-center gap-2">
            <span class="badge badge-confirmed">Stage <?= (int) $stage ?> — <?= View::e($stageLabels[$stage] ?? 'Intake Active') ?></span>
            <span class="badge badge-pending"><?= (int) $completionPercent ?>% complete</span>
        </div>
    </div>

    <?php if (!empty($alerts)): ?>
    <div class="card mb-6" style="padding:var(--space-4);border-left:4px solid var(--color-accent);background:var(--color-accent-soft)">
        <div class="flex items-center gap-2 mb-2">
            <?= View::icon('shield', 'icon') ?>
            <strong>Medical &amp; Safety Alerts</strong>
        </div>
        <ul style="margin:0;padding-left:var(--space-5)">
            <?php foreach ($alerts as $alert): ?>
            <li><?= View::e($alert) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- STAGE 1: Registration & First Login Profile Wizard Questions & Answers -->
    <div class="card mb-6" style="padding:var(--space-5)">
        <h3 style="margin-top:0;margin-bottom:12px">Stage 1: Registration &amp; First Login Profile Wizard (Questions &amp; Answers)</h3>
        <div class="flex flex-col gap-2">
            <div class="qa-box">
                <div class="qa-box__question">Q: What is your full name, email address, and mobile number?</div>
                <div class="qa-box__answer">
                    Answer: <strong><?= View::e($customer['name']) ?></strong> (<?= View::e($customer['email']) ?> · Mobile: <?= View::e($customer['phone'] ?: '—') ?>)
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: When did you register and last log in to your account?</div>
                <div class="qa-box__answer">
                    Answer: Registered: <strong><?= !empty($customer['created_at']) ? View::e(date('d M Y', strtotime($customer['created_at']))) : '—' ?></strong> · Last active: <strong><?= !empty($customer['last_login_at']) ? View::e(date('d M Y, g:i A', strtotime($customer['last_login_at']))) : 'Recently Active' ?></strong>
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: Which category best describes you?</div>
                <div class="qa-box__answer">
                    Answer: <strong><?= View::e($fmt($customer['client_category'])) ?></strong>
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: What is your date of birth, age, and gender?</div>
                <div class="qa-box__answer">
                    Answer: Date of Birth: <strong><?= View::e($customer['date_of_birth'] ?? '—') ?></strong> · Age: <strong><?= View::e((string)($customer['age'] ?? '—')) ?> yrs</strong> · Gender: <strong><?= View::e($fmt($customer['gender'])) ?></strong>
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: What is your height, weight, and location?</div>
                <div class="qa-box__answer">
                    Answer: Height: <strong><?= View::e($customer['height_cm'] ? $customer['height_cm'] . ' cm' : '—') ?></strong> · Weight: <strong><?= View::e($customer['weight_kg'] ? $customer['weight_kg'] . ' kg' : '—') ?></strong> · City/Country: <strong><?= View::e(implode(', ', array_filter([$customer['city'] ?? null, $customer['country'] ?? null])) ?: '—') ?></strong>
                </div>
            </div>

            <?php if (!empty($customer['bio'])): ?>
            <div class="qa-box">
                <div class="qa-box__question">Q: What is your short personal bio or background?</div>
                <div class="qa-box__answer">
                    Answer: <strong><?= View::e($customer['bio']) ?></strong>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- STAGE 2: Pre-Booking Health & Safety Questionnaire (Asked BEFORE Booking Session) -->
    <div class="card mb-6" style="padding:var(--space-5)">
        <h3 style="margin-top:0;margin-bottom:12px">Stage 2: Health &amp; Safety Intake Questionnaire (Asked BEFORE Booking Session)</h3>

        <div class="flex flex-col gap-2">

            <!-- Question 1: Undergone Surgery -->
            <div class="qa-box">
                <div class="qa-box__question">Q: Have you undergone surgery?</div>
                <div class="qa-box__answer">
                    Answer: 
                    <?php 
                    $surgeryVal = $screening['hadSurgery'] ?? null;
                    if ($surgeryVal === true || $surgeryVal === 'yes' || $surgeryVal === '1'): ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Yes</strong>
                        <?php if (!empty($screening['hadSurgeryNotes'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Details: <?= View::e($screening['hadSurgeryNotes']) ?>
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
                    $medVal = $medication['takingMedication'] ?? $screening['takingMedication'] ?? null;
                    if ($medVal === true || $medVal === 'yes' || $medVal === '1'): ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Yes</strong>
                        <?php if (!empty($medication['list']) || !empty($screening['takingMedicationNotes'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Medications: <?= View::e($medication['list'] ?? $screening['takingMedicationNotes']) ?>
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
                    $injVal = $screening['hadInjuries'] ?? null;
                    if ($injVal === true || $injVal === 'yes' || $injVal === '1'): ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Yes</strong>
                        <?php if (!empty($screening['hadInjuriesNotes'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Injury Details: <?= View::e($screening['hadInjuriesNotes']) ?>
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
                    $allVal = $screening['hasAllergies'] ?? null;
                    if ($allVal === true || $allVal === 'yes' || $allVal === '1'): ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Yes</strong>
                        <?php if (!empty($screening['hasAllergiesNotes'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Allergies: <?= View::e($screening['hasAllergiesNotes']) ?>
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
                    $advVal = $screening['advisedNotToExercise'] ?? null;
                    if ($advVal === true || $advVal === 'yes' || $advVal === '1'): ?>
                        <strong style="color:var(--color-error, #EF4444)">Yes</strong>
                        <?php if (!empty($screening['advisedNotToExerciseNotes'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Reason / Advice: <?= View::e($screening['advisedNotToExerciseNotes']) ?>
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
                    $docVal = $doctorRestrictions['hasRestriction'] ?? null;
                    if ($docVal === true || $docVal === 'yes' || $docVal === '1'): ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Yes</strong>
                        <?php if (!empty($doctorRestrictions['notes'])): ?>
                        <div style="margin-top:2px;color:var(--text-primary)">
                            Doctor Notes: <?= View::e($doctorRestrictions['notes']) ?>
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
                    <?php if (empty($conditions)): ?>
                        <strong style="color:var(--color-success, #10B981)">None Reported</strong>
                    <?php else: ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Reported Conditions:</strong>
                        <div class="flex flex-col gap-1 mt-1">
                            <?php foreach ($conditions as $c): $d = ($medicalHistory['details'] ?? [])[$c] ?? []; ?>
                            <div>
                                • <strong><?= View::e($fmt($c)) ?></strong>
                                <?php if (!empty($d['since'])): ?> (Since: <?= View::e($fmt($d['since'])) ?>)<?php endif; ?>
                                <?php if (!empty($d['medication'])): ?> (Medication: <?= View::e($fmt($d['medication'])) ?>)<?php endif; ?>
                                <?php if (!empty($d['doctorRestriction'])): ?> (Doctor Restriction: <?= View::e($fmt($d['doctorRestriction'])) ?>)<?php endif; ?>
                                <?php if (!empty($d['notes'])): ?> — Notes: <?= View::e($d['notes']) ?><?php endif; ?>
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
                    <?php if (empty($painAreas)): ?>
                        <strong style="color:var(--color-success, #10B981)">No Pain Reported</strong>
                    <?php else: ?>
                        <strong style="color:var(--color-accent, #F59E0B)">Pain Reported in Body Areas:</strong>
                        <div class="flex flex-col gap-1 mt-1">
                            <?php foreach ($painAreas as $a): $pd = ($painAssessment['details'] ?? [])[$a] ?? []; ?>
                            <div>
                                • <strong><?= View::e($fmt($a)) ?></strong> — Severity Level: <?= View::e($pd['severity'] ?? 'N/A') ?> / 10, Side: <?= View::e($fmt($pd['side'] ?? 'Center')) ?>, Walking Pain: <?= View::e($fmt($pd['walkingPain'] ?? null)) ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Category-Specific Health Questionnaire Answers (Explicit Q&A) -->
    <div class="card mb-6" style="padding:var(--space-5)">
        <h3 style="margin-top:0;margin-bottom:12px">Category-Specific Health Questionnaire (<?= View::e($fmt($catKey)) ?> Q&amp;A)</h3>
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
    <div class="card mb-6" style="padding:var(--space-5)">
        <h3 style="margin-top:0;margin-bottom:12px">Stage 3: Wellness Profile &amp; Practice Lifestyle Questionnaire (Questions &amp; Answers)</h3>
        <div class="flex flex-col gap-2">
            <div class="qa-box">
                <div class="qa-box__question">Q: What are your primary yoga &amp; wellness practice goals?</div>
                <div class="qa-box__answer">Answer: <strong><?= View::e($fmt($ha['goals']['selected'] ?? [])) ?></strong></div>
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
                    Answer: <strong><?= View::e($fmt($haSleep['hours'] ?? null)) ?> hrs</strong> · Quality: <strong><?= View::e($fmt($haSleep['quality'] ?? null)) ?></strong>
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
                    Answer: Stress Level: <strong><?= View::e($fmt($haStress['level'] ?? null)) ?></strong>
                    <?php if (!empty($haStress['source'])): ?> · Main stressor: <strong><?= View::e($fmt($haStress['source'])) ?></strong><?php endif; ?>
                </div>
            </div>

            <div class="qa-box">
                <div class="qa-box__question">Q: Have you practised yoga before &amp; what is your experience level?</div>
                <div class="qa-box__answer">
                    Answer: <strong><?= View::e($fmt($haYogaExp['experience'] ?? $haYogaExp['level'] ?? null)) ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- STAGE 4: Advanced Health Checks & Medical Updates (Q&A) -->
    <?php if (!empty($haAdvanced)): ?>
    <div class="card mb-6" style="padding:var(--space-5)">
        <h3 style="margin-top:0;margin-bottom:12px">Stage 4: Advanced Health Check &amp; Updates (Questions &amp; Answers)</h3>
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
    <div class="card mb-6" style="padding:var(--space-5)">
        <h3 style="margin-top:0;margin-bottom:12px">Stage 5: Emergency Contact &amp; Instructor Special Notes</h3>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-item__label">Emergency Contact Name</span>
                <span class="detail-item__value"><?= View::e($customer['emergency_contact_name'] ?: '—') ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-item__label">Contact Phone</span>
                <span class="detail-item__value"><?= View::e($customer['emergency_contact_phone'] ?: '—') ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-item__label">Relationship</span>
                <span class="detail-item__value"><?= View::e($customer['emergency_contact_relationship'] ?: '—') ?></span>
            </div>
        </div>
        <?php if (!empty($customer['medical_notes'])): ?>
        <div style="margin-top:12px;padding-top:10px;border-top:1px solid var(--border-subtle)">
            <span class="detail-item__label">Special Instructor Notes</span>
            <div style="font-size:0.85rem;color:var(--text-primary);margin-top:2px"><?= View::e($customer['medical_notes']) ?></div>
        </div>
        <?php endif; ?>
    </div>

</div>
