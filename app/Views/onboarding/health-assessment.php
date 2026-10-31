<?php

use App\Core\View;

$pageTitle = 'Pre-Training Health & Safety Intake — Vipasa Yoga';
$pageCss = 'onboarding';

$targetBookingUrl = ($redirectTo && $redirectTo !== '/dashboard') ? $redirectTo : '/instructors';
?>
<div class="get-started-page">
    <div class="onboarding-panel get-started-card" id="assessment-card">
        <a href="/dashboard" class="get-started-card__back"><?= View::icon('arrow-left', 'icon', 16) ?> Back to Dashboard</a>
        <div class="get-started-card__header">
            <div class="splash__wordmark" style="color:var(--color-primary);font-size:var(--font-size-lg)">Vipasa Yoga</div>
            <h1 class="get-started-card__title">Pre-Training Health &amp; Safety Intake</h1>
            <p class="get-started-card__subtitle">We collect your health, lifestyle, and safety details before starting training so your instructor can personalize your yoga practice.</p>
        </div>

        <div id="assessment-wizard">
            <div class="assessment-meta">
                <span class="assessment-meta__step" id="step-label" aria-live="polite">Step 1 of 5</span>
                <span class="assessment-meta__time"><?= View::icon('clock', 'icon', 13) ?> Takes 3&ndash;5 minutes</span>
                <button type="button" class="assessment-meta__save" id="save-later-btn">Save &amp; Continue Later</button>
            </div>

            <div class="assessment-progress" id="assessment-progress" aria-hidden="true"></div>

            <div class="assessment-trust">
                <span class="assessment-trust__badge"><?= View::icon('shield', 'icon', 12) ?> Privacy &amp; Security</span>
                <span class="assessment-trust__badge"><?= View::icon('lock', 'icon', 12) ?> Data Encryption</span>
            </div>

            <div id="step-container"></div>

            <div class="onboarding-nav">
                <button type="button" class="btn btn-secondary" id="back-btn" style="visibility:hidden">
                    <?= View::icon('arrow-left', 'icon', 15) ?> Back
                </button>
                <button type="button" class="btn btn-primary btn-lg" id="continue-btn" disabled>
                    Continue <?= View::icon('arrow-right', 'icon', 15) ?>
                </button>
            </div>
        </div>

        <div class="assessment-done" id="assessment-done" hidden>
            <span class="assessment-done__icon"><?= View::icon('check', 'icon', 28) ?></span>
            <h2 class="assessment-done__title">Health &amp; Safety Intake Completed</h2>
            <p class="assessment-done__text">Thank you! Your health and safety information has been securely recorded and shared with your instructor.</p>
            <div class="assessment-done__stats" style="grid-template-columns:repeat(2,minmax(0,1fr))">
                <div class="assessment-done__stat">
                    <span class="assessment-done__stat-label">Status</span>
                    <span class="assessment-done__stat-value" style="color:var(--color-success)">Ready to Book</span>
                </div>
                <div class="assessment-done__stat">
                    <span class="assessment-done__stat-label">Profile Category</span>
                    <span class="assessment-done__stat-value" id="done-level">&mdash;</span>
                </div>
            </div>
            <div class="onboarding-nav" style="justify-content:center;gap:12px;flex-wrap:wrap">
                <a href="<?= View::e($targetBookingUrl) ?>" class="btn btn-primary btn-lg" id="book-class-cta">
                    Book a Yoga Class Now <?= View::icon('arrow-right', 'icon', 16) ?>
                </a>
                <button type="button" class="btn btn-secondary" id="re-edit-btn">Review &amp; Edit Answers</button>
                <a href="/dashboard" class="btn btn-ghost">Dashboard</a>
            </div>
            <p class="assessment-done__note">Your health and safety details will be reviewed by your instructor prior to your first yoga session.</p>
        </div>
    </div>
</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

const initialCategory = <?= json_encode($category) ?> || null;
const existing = <?= json_encode($profile['health_assessment'] ?? null) ?> || {};
const bookingRedirectUrl = <?= json_encode($targetBookingUrl) ?>;

const CATEGORIES = [
    {
        key: 'adult',
        title: 'Adult (Men & Women)',
        badge: 'General Wellness & Strength',
        icon: '🧘',
        desc: 'For working adults balancing daily routine, desk hours, flexibility, posture, and stress relief.',
    },
    {
        key: 'child',
        title: 'Child / Kids Yoga',
        badge: 'Ages 5–12 • Guardian Accompanied',
        icon: '🧒',
        desc: 'For young learners with parent/guardian guidance, school sports & healthy posture.',
    },
    {
        key: 'teenager',
        title: 'Teenager (Student)',
        badge: 'Ages 13–19 • School & Sports',
        icon: '🎒',
        desc: 'For students managing study posture, desk sitting, sports activities, and exam stress.',
    },
    {
        key: 'pregnant_woman',
        title: 'Prenatal (Expecting Mother)',
        badge: 'Trimester-Safe • Doctor Approved',
        icon: '🤰',
        desc: 'Gentle, safe pregnancy yoga adapted for each trimester to support maternal comfort and birth prep.',
    },
    {
        key: 'postpartum_mother',
        title: 'Postnatal (New Mother Recovery)',
        badge: 'Core & Pelvic Floor Recovery',
        icon: '🤱',
        desc: 'Safe postpartum rehabilitation focusing on pelvic floor strengthening, gentle core, and energy renewal.',
    },
    {
        key: 'senior_citizen',
        title: 'Senior Citizen (Grandparent / Elderly)',
        badge: 'Gentle & Chair-Assisted Yoga',
        icon: '🧓',
        desc: 'Gentle movement, chair support, joint mobility, balance exercises, and safe floor transitions.',
    },
];

const CATEGORY_CONDITIONS = {
    child: ['Asthma', 'Allergies', 'Scoliosis / Posture Issue', 'Growth Pains', 'Epilepsy', 'ADHD / Hyperactivity', 'Type 1 Diabetes', 'Recent Surgery', 'Other'],
    teenager: ['Asthma', 'Migraine', 'Scoliosis / Back Pain', 'Thyroid Issue', 'High / Low BP', 'Epilepsy', 'Anxiety / Stress', 'Recent Surgery', 'Other'],
    adult: ['Diabetes', 'Heart Disease', 'Asthma', 'Thyroid', 'High BP', 'Low BP', 'Migraine', 'Vertigo', 'Arthritis', 'Epilepsy', 'Recent Surgery', 'Other'],
    senior_citizen: ['High BP', 'Heart Condition', 'Arthritis / Joint Pain', 'Osteoporosis / Bone Density', 'Balance Issues / Vertigo', 'Diabetes', 'Spine / Disc Issue', 'Cataract / Vision Issue', 'Recent Surgery', 'Other'],
    pregnant_woman: ['Gestational Diabetes', 'Pre-eclampsia / High BP', 'Low BP', 'Thyroid Condition', 'Pelvic Girdle Pain', 'Lower Back Pain', 'Asthma', 'Recent Surgery', 'Other'],
    postpartum_mother: ['Diastasis Recti', 'Pelvic Floor Dysfunction', 'C-Section Recovery', 'High / Low BP', 'Thyroid Issue', 'Postpartum Anxiety / Stress', 'Back Pain', 'Recent Surgery', 'Other'],
};

const CATEGORY_SCREENING = {
    child: [
        { key: 'hadSurgery', question: 'Has the child undergone any surgery?', followUp: 'What surgery, and roughly when?', presets: ['Abdominal', 'ENT / Tonsil', 'Fracture / Bone', 'Eye / Dental', 'Other'] },
        { key: 'takingMedication', question: 'Is the child currently taking any medication?', followUp: 'Which medication, and what for?', presets: ['Asthma Inhaler', 'Allergy Tablets', 'Daily Supplements', 'Other'] },
        { key: 'hadInjuries', question: 'Has the child had any sports or playground injuries?', followUp: 'Which injury, and how long ago?', presets: ['Sprain', 'Fracture', 'Dislocation', 'Muscle Tear', 'Other'] },
        { key: 'hasAllergies', question: 'Does the child have any allergies?', followUp: 'What is the child allergic to?', presets: ['Food / Peanut / Milk', 'Dust / Pollen', 'Medication / Drug', 'Skin / Latex', 'Other'] },
        { key: 'advisedNotToExercise', question: 'Has a doctor or pediatrician advised the child not to exercise?', followUp: 'Who advised this, and why?' },
    ],
    teenager: [
        { key: 'hadSurgery', question: 'Have you undergone any surgery?', followUp: 'What surgery, and roughly when?', presets: ['Spine / Scoliosis', 'Joint / Knee', 'Abdominal', 'Other'] },
        { key: 'takingMedication', question: 'Are you currently taking any medication?', followUp: 'Which medication, and what for?', presets: ['Asthma Inhaler', 'Acne Treatment', 'Allergy Tablets', 'Vitamins / Supplements', 'Other'] },
        { key: 'hadInjuries', question: 'Have you had any sports or school injuries?', followUp: 'Which injury, and how long ago?', presets: ['Sprain / Ligament', 'Fracture', 'Dislocation', 'Muscle Strain', 'Other'] },
        { key: 'hasAllergies', question: 'Do you have any allergies?', followUp: 'What are you allergic to?', presets: ['Food / Nuts', 'Dust / Pollen', 'Medication / Drug', 'Skin / Latex', 'Other'] },
        { key: 'advisedNotToExercise', question: 'Has a doctor ever advised you not to exercise?', followUp: 'Who advised this, and why?' },
    ],
    adult: [
        { key: 'hadSurgery', question: 'Have you undergone surgery?', followUp: 'What surgery, and roughly when?', presets: ['Abdominal / C-Section', 'Joint Replacement', 'Spine / Back', 'Cardiac / Heart', 'Gallbladder / Appendix', 'Other'] },
        { key: 'takingMedication', question: 'Are you currently taking any medication?', followUp: 'Which medication, and what for?', presets: ['BP Tablets', 'Thyroid Medication', 'Diabetes / Insulin', 'Pain Relievers', 'Vitamins / Supplements', 'Other'] },
        { key: 'hadInjuries', question: 'Have you had any injuries?', followUp: 'Which injury, and how long ago?', presets: ['Sprain / Ligament', 'Fracture', 'Dislocation', 'Muscle Tear', 'Other'] },
        { key: 'hasAllergies', question: 'Do you have any allergies?', followUp: 'What are you allergic to?', presets: ['Skin / Latex', 'Dust / Pollen', 'Medication / Drug', 'Food', 'Other'] },
        { key: 'advisedNotToExercise', question: 'Has a doctor ever advised you not to exercise?', followUp: 'Who advised this, and why?' },
    ],
    senior_citizen: [
        { key: 'hadSurgery', question: 'Have you undergone any surgery or joint replacement?', followUp: 'What surgery, and roughly when?', presets: ['Knee / Hip Replacement', 'Cataract / Eye', 'Spine / Back', 'Cardiac / Pacemaker', 'Other'] },
        { key: 'takingMedication', question: 'Are you currently taking any daily medication?', followUp: 'Which medication, and what for?', presets: ['BP Medication', 'Heart / Blood Thinner', 'Diabetes / Insulin', 'Joint Pain Reliever', 'Other'] },
        { key: 'hadInjuries', question: 'Have you had any falls, fractures, or joint injuries?', followUp: 'Which injury, and how long ago?', presets: ['Hip / Femur Fracture', 'Wrist Fracture', 'Sprain / Fall Injury', 'Other'] },
        { key: 'hasAllergies', question: 'Do you have any drug or environmental allergies?', followUp: 'What are you allergic to?', presets: ['Medication / Drug', 'Dust / Pollen', 'Skin / Latex', 'Food', 'Other'] },
        { key: 'advisedNotToExercise', question: 'Has a doctor advised you to limit or avoid physical activity?', followUp: 'Who advised this, and why?' },
    ],
    pregnant_woman: [
        { key: 'hadSurgery', question: 'Have you undergone any past surgeries (including prior C-Section)?', followUp: 'What surgery, and when?', presets: ['Prior C-Section', 'Abdominal / Pelvic', 'Spine / Back', 'Other'] },
        { key: 'takingMedication', question: 'Are you taking any prenatal supplements or medication?', followUp: 'Which medication, and what for?', presets: ['Prenatal Vitamins / Folic Acid', 'Iron / Calcium', 'BP Medication', 'Thyroid Medication', 'Other'] },
        { key: 'hadInjuries', question: 'Have you had any back, pelvic, or joint injuries?', followUp: 'Which injury, and how long ago?', presets: ['Pelvic Joint Strain', 'Lower Back Injury', 'Ankle / Knee Sprain', 'Other'] },
        { key: 'hasAllergies', question: 'Do you have any allergies?', followUp: 'What are you allergic to?', presets: ['Food', 'Medication / Drug', 'Skin / Latex', 'Dust / Pollen', 'Other'] },
        { key: 'advisedNotToExercise', question: 'Has your obstetrician / doctor advised bed rest or no exercise?', followUp: 'What restrictions were given?' },
    ],
    postpartum_mother: [
        { key: 'hadSurgery', question: 'Did you have a C-section or recent surgery?', followUp: 'What type of delivery / surgery and date?', presets: ['C-Section Delivery', 'Vaginal Tear / Repair', 'Abdominal Surgery', 'Other'] },
        { key: 'takingMedication', question: 'Are you currently taking any medication or supplements?', followUp: 'Which medication, and what for?', presets: ['Postnatal Vitamins', 'Iron / Calcium', 'Pain Relievers', 'Thyroid Medication', 'Other'] },
        { key: 'hadInjuries', question: 'Have you experienced pelvic, back, or wrist strain?', followUp: 'Which injury or pain area?', presets: ['Lower Back Strain', 'Pelvic Floor Strain', 'Wrist / De Quervain\'s', 'Other'] },
        { key: 'hasAllergies', question: 'Do you have any allergies?', followUp: 'What are you allergic to?', presets: ['Food', 'Medication / Drug', 'Skin / Latex', 'Other'] },
        { key: 'advisedNotToExercise', question: 'Has your doctor flagged any delay in exercise clearance?', followUp: 'What did your doctor advise?' },
    ],
};

const CATEGORY_QUESTIONS = {
    child: {
        title: 'Guardian & school details',
        fields: [
            { key: 'guardianPresent', type: 'yesno', label: 'Will a parent or guardian be present during the session?' },
            { key: 'guardianRelationship', type: 'choice', options: ['Mother', 'Father', 'Grandmother', 'Grandfather', 'Grandparent', 'Other'], label: 'Who will accompany the child?', hasWriteIn: true, writeInKey: 'guardianRelationshipDetails', writeInPlaceholder: 'Please specify relationship if Other...' },
            { key: 'schoolSports', type: 'yesno', label: 'Does the child take part in school sports or PE?' },
            { key: 'growthConcerns', type: 'yesno', label: 'Has a doctor raised any growth or posture concerns?' },
            { key: 'previousYoga', type: 'yesno', label: 'Has the child practised yoga before?' },
            { key: 'screenTime', type: 'choice', options: ['Under 1h', '1-3h', '3-5h', 'Over 5h'], label: 'Screen time on a typical day' },
            { key: 'instructionSupport', type: 'yesno', label: 'Does the child need extra support to follow group instructions?' },
            { key: 'supportDevice', type: 'yesno', label: 'Does the child use glasses, braces, or any support device?', hasWriteIn: true, writeInKey: 'supportDeviceDetails', writeInPlaceholder: 'Please specify support device (e.g. Glasses, Braces)...' },
        ],
    },
    teenager: {
        title: 'School & guardian consent',
        fields: [
            { key: 'guardianConsent', type: 'yesno', label: 'Has a parent or guardian agreed to these sessions?' },
            { key: 'schoolSports', type: 'yesno', label: 'Do you take part in school or college sports?' },
            { key: 'studyHours', type: 'choice', options: ['Under 2', '2-4', '4-6', '6+'], label: 'Hours a day sitting to study' },
            { key: 'examStress', type: 'yesno', label: 'Are you in an exam or high-pressure period right now?' },
            { key: 'previousYoga', type: 'yesno', label: 'Have you practised yoga before?' },
            { key: 'screenTime', type: 'choice', options: ['Under 1h', '1-3h', '3-5h', 'Over 5h'], label: 'Screen time outside study' },
            { key: 'studyPosture', type: 'yesno', label: 'Do you get neck or back ache from studying?', hasWriteIn: true, writeInKey: 'studyPostureDetails', writeInPlaceholder: 'Please describe posture issues or ache location...' },
            { key: 'energyLevel', type: 'choice', options: ['Low', 'Moderate', 'High'], label: 'How is your energy through the day?' },
            { key: 'groupPreference', type: 'choice', options: ['Alone', 'Small group', 'Either'], label: 'Would you rather practise alone or in a group?' },
        ],
    },
    adult: {
        title: 'Working day & starting pace',
        fields: [
            { key: 'workPattern', type: 'choice', options: ['Desk-based', 'On your feet', 'Shift work', 'Physically demanding', 'Other'], label: 'Which best describes your working day?', hasWriteIn: true, writeInKey: 'workPatternDetails', writeInPlaceholder: 'Please describe your work pattern...' },
            { key: 'longSitting', type: 'yesno', label: 'Do you sit for six hours or more on a typical day?' },
            { key: 'startingPace', type: 'choice', options: ['Gentle', 'Moderate', 'Challenging'], label: 'What pace would you like to start at?' },
            { key: 'previousYoga', type: 'yesno', label: 'Have you practised yoga before?' },
            { key: 'screenHours', type: 'choice', options: ['Under 4', '4-8', '8-12', 'Over 12'], label: 'Screen hours on a typical day' },
            { key: 'deskSetup', type: 'yesno', label: 'Do you work from a proper desk and chair?' },
            { key: 'sessionLength', type: 'choice', options: ['15 min', '30 min', '45 min', '60 min'], label: 'How long can you practise in one session?' },
            { key: 'caregivingLoad', type: 'yesno', label: 'Do you have caregiving duties that affect your schedule?' },
        ],
    },
    senior_citizen: {
        title: 'Mobility & balance',
        fields: [
            { key: 'fallsHistory', type: 'yesno', label: 'Any history of falls or near-falls?' },
            { key: 'mobilityLevel', type: 'choice', options: ['Independent', 'Needs assistance', 'Uses a mobility aid'], label: 'Mobility level', hasWriteIn: true, writeInKey: 'mobilityAidDetails', writeInPlaceholder: 'Please specify mobility aid (e.g. Walker, Cane)...' },
            { key: 'floorTransfer', type: 'yesno', label: 'Can you get down to and up from the floor comfortably?' },
            { key: 'balanceSupport', type: 'yesno', label: 'Do you need a chair or wall for balance work?' },
            { key: 'chairPractice', type: 'yesno', label: 'Would you prefer seated or chair-based practice?' },
            { key: 'dizzinessOnStanding', type: 'yesno', label: 'Do you feel dizzy when standing up?' },
            { key: 'jointReplacement', type: 'yesno', label: 'Any joint replacement or implant?', hasWriteIn: true, writeInKey: 'jointReplacementDetails', writeInPlaceholder: 'Please specify replacement (e.g. Knee, Hip)...' },
            { key: 'sensoryDifficulty', type: 'choice', options: ['None', 'Hearing', 'Vision', 'Both'], label: 'Any hearing or vision difficulty following instructions?' },
            { key: 'accompaniedToSession', type: 'yesno', label: 'Will someone accompany you to sessions?' },
        ],
    },
    pregnant_woman: {
        title: 'Pregnancy safety',
        fields: [
            { key: 'trimester', type: 'choice', options: ['1st', '2nd', '3rd'], label: 'Trimester' },
            { key: 'weeksPregnant', type: 'number', min: 1, max: 45, label: 'Weeks pregnant' },
            { key: 'doctorApproval', type: 'yesno', label: 'Has your doctor approved yoga practice?' },
            { key: 'complications', type: 'yesno', label: 'Has your doctor flagged any complications this pregnancy?', hasWriteIn: true, writeInKey: 'complicationsDetails', writeInPlaceholder: 'Please specify complications (e.g. High BP, Low Placenta)...' },
            { key: 'firstPregnancy', type: 'yesno', label: 'Is this your first pregnancy?' },
            { key: 'prenatalYogaBefore', type: 'yesno', label: 'Have you attended prenatal yoga before?' },
            { key: 'nauseaOrDizziness', type: 'yesno', label: 'Are you experiencing nausea or dizziness?' },
            { key: 'pelvicOrBackPain', type: 'yesno', label: 'Any pelvic or lower-back pain?' },
            { key: 'swellingFlagged', type: 'yesno', label: 'Has your doctor flagged swelling in your hands or feet?' },
        ],
    },
    postpartum_mother: {
        title: 'Postpartum recovery',
        fields: [
            { key: 'monthsAfterDelivery', type: 'number', min: 0, max: 36, label: 'Months after delivery' },
            { key: 'deliveryType', type: 'choice', options: ['Vaginal', 'C-section'], label: 'Delivery type' },
            { key: 'doctorClearance', type: 'yesno', label: 'Has your doctor cleared you for exercise?' },
            { key: 'breastfeeding', type: 'yesno', label: 'Are you currently breastfeeding?' },
            { key: 'pelvicFloorIssues', type: 'yesno', label: 'Any diagnosed pelvic floor or abdominal separation?', hasWriteIn: true, writeInKey: 'pelvicFloorDetails', writeInPlaceholder: 'Please specify details (e.g. Diastasis Recti, Prolapse)...' },
            { key: 'firstChild', type: 'yesno', label: 'Is this your first child?' },
            { key: 'incisionPain', type: 'yesno', label: 'Any pain at the incision or perineal area?' },
            { key: 'sleepDisruption', type: 'choice', options: ['Rarely', 'Some nights', 'Most nights'], label: 'Is your sleep disrupted at night?' },
            { key: 'babyCareSupport', type: 'yesno', label: 'Do you have help with baby care during session times?' },
            { key: 'energyLevel', type: 'choice', options: ['Low', 'Moderate', 'High'], label: 'How is your energy through the day?' },
        ],
    },
};

const PAIN_AREAS = ['Neck', 'Shoulder', 'Upper Back', 'Lower Back', 'Hip', 'Knee', 'Wrist', 'Elbow', 'Ankle', 'Foot'];
const SEVERITY_SCALE = Array.from({ length: 10 }, (_, i) => ({ value: String(i + 1), label: String(i + 1) }));
const TREATMENTS = ['None', 'Physiotherapy', 'Medication', 'Injections', 'Surgery', 'Other'];
const MEDICATION_TYPES = ['Blood Pressure', 'Thyroid', 'Diabetes / Insulin', 'Painkillers', 'Anxiety / Depression', 'Vitamins / Supplements', 'Cholesterol', 'Other'];
const RESTRICTION_TYPES = ['No heavy lifting', 'No inversions / headstands', 'Low-impact / gentle only', 'Avoid back bends / twists', 'Avoid high heart rate', 'Other'];
const LEGACY_AREA_LABELS = { back: 'Back' };
const LEGACY_SEVERITY = { mild: '3', moderate: '6', severe: '9' };

// Initial state setup
const state = {
    category: initialCategory || existing.categorySelection?.category || existing.category || null,
    categorySafety: existing.categorySafety || {},
    medicalHistory: existing.medicalHistory || { conditions: [], otherConditionNotes: '', details: {} },
    healthScreening: existing.healthScreening || {},
    painAssessment: existing.painAssessment || { areas: [], details: {} },
    doctorRestrictions: existing.doctorRestrictions || { hasRestriction: null, restrictionTypes: [], notes: '' },
};

function slug(label) {
    return String(label || '').toLowerCase().replace(/\s+/g, '_');
}

function esc(value) {
    return String(value ?? '').replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
}

function areaLabel(area) {
    return PAIN_AREAS.find((p) => slug(p) === area) || LEGACY_AREA_LABELS[area] || area;
}

function getCategoryObj(key) {
    return CATEGORIES.find((c) => c.key === key) || CATEGORIES[0];
}

function getActiveConditions() {
    return CATEGORY_CONDITIONS[state.category] || CATEGORY_CONDITIONS.adult;
}

function getActiveScreening() {
    return CATEGORY_SCREENING[state.category] || CATEGORY_SCREENING.adult;
}

function chipGroup(name, options, selected, exclusiveValue) {
    return options.map((opt) => {
        const value = typeof opt === 'string' ? slug(opt) : opt.value;
        const label = typeof opt === 'string' ? opt : opt.label;
        const isSelected = selected.includes(value);
        return `<label class="option-card${isSelected ? ' is-selected' : ''}" data-chip="${name}" data-value="${value}" data-exclusive="${value === exclusiveValue ? '1' : '0'}">
            <span class="option-card__title">${label}</span>
        </label>`;
    }).join('');
}

function radioGroup(name, options, current) {
    return options.map((opt) => {
        const value = typeof opt === 'string' ? slug(opt) : opt.value;
        const label = typeof opt === 'string' ? opt : opt.label;
        return `<label class="option-card${String(current ?? '') === value ? ' is-selected' : ''}" data-chip="${name}" data-value="${value}" data-exclusive="1">
            <span class="option-card__title">${label}</span>
        </label>`;
    }).join('');
}

function wireChipGroup(container, name, onChange) {
    container.querySelectorAll(`[data-chip="${name}"]`).forEach((chip) => {
        chip.addEventListener('click', () => {
            const value = chip.dataset.value;
            const exclusive = chip.dataset.exclusive === '1';
            const group = container.querySelectorAll(`[data-chip="${name}"]`);

            if (exclusive) {
                group.forEach((c) => c.classList.remove('is-selected'));
                chip.classList.add('is-selected');
            } else {
                chip.classList.toggle('is-selected');
                container.querySelector(`[data-chip="${name}"][data-exclusive="1"]`)?.classList.remove('is-selected');
            }

            onChange(Array.from(group).filter((c) => c.classList.contains('is-selected')).map((c) => c.dataset.value));
        });
    });
}

function radioYesNo(name, current) {
    return `<div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">
        <label class="option-card${current === true ? ' is-selected' : ''}" data-yn="${name}" data-value="yes"><span class="option-card__title">Yes</span></label>
        <label class="option-card${current === false ? ' is-selected' : ''}" data-yn="${name}" data-value="no"><span class="option-card__title">No</span></label>
    </div>`;
}

function wireYesNo(container, name, onChange) {
    container.querySelectorAll(`[data-yn="${name}"]`).forEach((el) => {
        el.addEventListener('click', () => {
            container.querySelectorAll(`[data-yn="${name}"]`).forEach((c) => c.classList.remove('is-selected'));
            el.classList.add('is-selected');
            onChange(el.dataset.value === 'yes');
        });
    });
}

function categoryFieldMarkup(field, value, detailsValue) {
    let markup = '';

    if (field.type === 'yesno') {
        markup = radioYesNo(field.key, value ?? null);
    } else if (field.type === 'choice') {
        const columns = Math.min(field.options.length, 3);
        markup = `<div class="option-grid" style="grid-template-columns:repeat(${columns},minmax(0,1fr))">
            ${radioGroup(field.key, field.options.map((o) => ({ value: o, label: o })), value)}
        </div>`;
    } else {
        const min = field.min !== undefined ? ` min="${field.min}"` : '';
        const max = field.max !== undefined ? ` max="${field.max}"` : '';
        markup = `<input type="number" data-field="${field.key}"${min}${max} value="${esc(value)}">`;
    }

    if (field.hasWriteIn && field.writeInKey) {
        const isVisible = field.type === 'yesno'
            ? value === true
            : (value === 'Other' || value === 'other' || !!value);

        markup += `<div id="writein-box-${field.key}" style="margin-top:var(--space-2);${isVisible ? '' : 'display:none'}">
            <label style="font-size:0.8rem;font-weight:600;color:var(--color-primary)">Type your specific answer / details:</label>
            <input type="text" data-writein="${field.writeInKey}" placeholder="${field.writeInPlaceholder || 'Type your answer here...'}" value="${esc(detailsValue)}">
        </div>`;
    }

    return markup;
}

// 5 WIZARD STEPS
const STEPS = [
    // Step 1: Category Selection
    {
        key: 'categorySelection',
        title: 'Participant Category',
        render(container) {
            container.innerHTML = `
                <h2 class="profile-section__title" style="margin-bottom:var(--space-2);font-size:1.15rem">Who is this yoga practice for?</h2>
                <p class="text-muted" style="margin:0 0 var(--space-4)">Please select who will be participating in the sessions. All health, safety, and routine questions will adapt specifically to this profile.</p>
                <div class="category-picker-grid">
                    ${CATEGORIES.map((cat) => {
                        const isSelected = state.category === cat.key;
                        return `<div class="category-card${isSelected ? ' is-selected' : ''}" data-category-choice="${cat.key}">
                            <div class="category-card__check">&#10003;</div>
                            <div class="category-card__header">
                                <span class="category-card__icon">${cat.icon}</span>
                                <div>
                                    <div class="category-card__title">${cat.title}</div>
                                </div>
                            </div>
                            <span class="category-card__badge">${cat.badge}</span>
                            <p class="category-card__desc">${cat.desc}</p>
                        </div>`;
                    }).join('')}
                </div>
            `;

            container.querySelectorAll('[data-category-choice]').forEach((card) => {
                card.addEventListener('click', () => {
                    const picked = card.dataset.categoryChoice;
                    state.category = picked;
                    container.querySelectorAll('[data-category-choice]').forEach((c) => c.classList.remove('is-selected'));
                    card.classList.add('is-selected');
                    updateContinueEnabled();
                });
            });
        },
        isComplete() {
            return !!state.category;
        },
        save(next) {
            return {
                step: 'categorySelection',
                data: { category: state.category },
                category: state.category,
                nextStep: next,
            };
        },
    },

    // Step 2: Category-Specific Lifestyle & Routine
    {
        key: 'categorySafety',
        title: 'Lifestyle & Routine',
        render(container) {
            const currentCat = state.category || 'adult';
            const spec = CATEGORY_QUESTIONS[currentCat] || CATEGORY_QUESTIONS.adult;
            const catObj = getCategoryObj(currentCat);
            const d = state.categorySafety;

            container.innerHTML = `
                <div style="display:flex;align-items:center;gap:var(--space-2);margin-bottom:var(--space-1)">
                    <span style="font-size:1.4rem">${catObj.icon}</span>
                    <h2 class="profile-section__title" style="font-size:1.15rem">${spec.title} (${catObj.title.toUpperCase()})</h2>
                </div>
                <p class="text-muted" style="margin:0 0 var(--space-4)">These questions apply specifically to your selected profile category so your instructor can prepare safely.</p>
                ${spec.fields.map((f) => `<div class="screening-row mb-4" style="padding:var(--space-3);border:1px solid var(--border-subtle);border-radius:var(--radius-md)">
                    <span class="screening-row__question" style="font-weight:600">${f.label}</span>
                    ${categoryFieldMarkup(f, d[f.key], f.writeInKey ? d[f.writeInKey] : '')}
                </div>`).join('')}
            `;

            spec.fields.forEach((f) => {
                const toggleWriteIn = (show) => {
                    if (!f.hasWriteIn || !f.writeInKey) return;
                    const box = container.querySelector(`#writein-box-${f.key}`);
                    if (box) box.style.display = show ? '' : 'none';
                };

                if (f.type === 'yesno') {
                    wireYesNo(container, f.key, (v) => {
                        d[f.key] = v;
                        toggleWriteIn(v);
                        updateContinueEnabled();
                    });
                } else if (f.type === 'choice') {
                    wireChipGroup(container, f.key, (values) => {
                        const val = values[0] || null;
                        d[f.key] = val;
                        toggleWriteIn(val === 'Other' || val === 'other' || !!val);
                        updateContinueEnabled();
                    });
                } else {
                    container.querySelector(`[data-field="${f.key}"]`)?.addEventListener('input', (e) => {
                        d[f.key] = e.target.value;
                        updateContinueEnabled();
                    });
                }

                if (f.hasWriteIn && f.writeInKey) {
                    container.querySelector(`[data-writein="${f.writeInKey}"]`)?.addEventListener('input', (e) => {
                        d[f.writeInKey] = e.target.value;
                    });
                }
            });
        },
        isComplete() {
            const currentCat = state.category || 'adult';
            const spec = CATEGORY_QUESTIONS[currentCat] || CATEGORY_QUESTIONS.adult;
            const d = state.categorySafety;
            return spec.fields.every((f) => d[f.key] !== undefined && d[f.key] !== null && d[f.key] !== '');
        },
        save(next) {
            return {
                step: 'categorySafety',
                data: state.categorySafety,
                category: state.category,
                nextStep: next,
            };
        },
    },

    // Step 3: Medical Conditions & Health Screening
    {
        key: 'medicalHistory',
        title: 'Medical History & Screening',
        render(container) {
            const activeConditions = getActiveConditions();
            const activeScreening = getActiveScreening();
            const hasOther = state.medicalHistory.conditions.includes('other');
            const catObj = getCategoryObj(state.category);

            container.innerHTML = `
                <h2 class="profile-section__title" style="margin-bottom:var(--space-2);font-size:1.15rem">Medical Conditions &amp; Screening (${catObj.title})</h2>
                <p class="text-muted" style="margin:0 0 var(--space-4)">Select any conditions that apply — or "None of these" if none apply.</p>
                
                <label style="font-weight:600;font-size:0.88rem;display:block;margin-bottom:var(--space-2)">1. Known Medical Conditions</label>
                <div class="option-grid mb-3">
                    ${chipGroup('conditions', activeConditions, state.medicalHistory.conditions, null)}
                    ${chipGroup('conditions', ['None of these'], state.medicalHistory.conditions, 'none_of_these')}
                </div>
                
                <div id="other-condition-box" style="margin-bottom:var(--space-4);padding:var(--space-3);background:var(--surface-sunken);border-radius:var(--radius-md);${hasOther ? '' : 'display:none'}">
                    <label style="font-weight:600;font-size:0.82rem;color:var(--color-primary);display:block;margin-bottom:4px">Please specify other medical condition:</label>
                    <input type="text" id="other-condition-input" placeholder="Type your condition here..." value="${esc(state.medicalHistory.otherConditionNotes || '')}">
                </div>

                <div id="condition-followups-container"></div>

                <div style="margin-top:var(--space-5);padding-top:var(--space-4);border-top:1px solid var(--border-subtle)">
                    <label style="font-weight:600;font-size:0.88rem;display:block;margin-bottom:var(--space-1)">2. General Health Screening</label>
                    <p class="text-muted" style="margin:0 0 var(--space-3);font-size:0.78rem">Answer each question — selecting "Yes" opens quick follow-up details.</p>
                    
                    ${activeScreening.map((q) => {
                        const answer = state.healthScreening[q.key];
                        const selectedPresets = state.healthScreening[q.key + 'Presets'] || [];
                        return `<div class="screening-row mb-3" style="padding:var(--space-3);border:1px solid var(--border-subtle);border-radius:var(--radius-md)">
                            <span class="screening-row__question" style="font-weight:600">${q.question}</span>
                            ${radioYesNo(q.key, answer ?? null)}
                            <div class="screening-row__followup" data-followup="${q.key}"${answer === true ? '' : ' hidden'} style="margin-top:var(--space-3)">
                                ${q.presets ? `<label style="font-weight:600;font-size:0.8rem">Select relevant type / area:</label>
                                <div class="option-grid mb-2" style="grid-template-columns:repeat(auto-fit,minmax(130px,1fr))">${chipGroup(q.key + 'Presets', q.presets, selectedPresets, null)}</div>` : ''}
                                <label for="note-${q.key}" style="font-weight:600;font-size:0.8rem;color:var(--color-primary)">${q.followUp}</label>
                                <textarea id="note-${q.key}" data-note="${q.key}" placeholder="Type details here...">${esc(state.healthScreening[q.key + 'Notes'])}</textarea>
                            </div>
                        </div>`;
                    }).join('')}
                </div>
            `;

            function renderConditionFollowups() {
                const followupsEl = container.querySelector('#condition-followups-container');
                const selected = state.medicalHistory.conditions.filter((c) => c !== 'none_of_these');
                state.medicalHistory.details = state.medicalHistory.details || {};

                if (selected.length === 0) {
                    followupsEl.innerHTML = '';
                    return;
                }

                followupsEl.innerHTML = `
                    <div style="margin-block:var(--space-3);padding:var(--space-3);background:var(--surface-sunken);border-radius:var(--radius-md)">
                        <span style="font-weight:600;font-size:0.84rem;color:var(--color-primary);display:block;margin-bottom:var(--space-2)">Details for selected conditions:</span>
                        ${selected.map((c) => {
                            const label = activeConditions.find((m) => slug(m) === c) || c;
                            const d = state.medicalHistory.details[c] || {};
                            const medTypes = d.medicationTypes || [];
                            const resTypes = d.restrictionTypes || [];

                            return `<div class="card mb-3" style="padding:var(--space-3);border:1px solid var(--border-subtle);border-radius:var(--radius-md)">
                                <div style="font-weight:600;font-size:0.9rem;color:var(--color-primary);margin-bottom:var(--space-2)">${esc(label)}</div>
                                
                                <label style="font-size:0.8rem;font-weight:600">Since when?</label>
                                <select data-detail="${c}:since" style="margin-bottom:var(--space-2)">
                                    <option value="">Select duration</option>
                                    <option value="lt_1y" ${d.since === 'lt_1y' ? 'selected' : ''}>Less than 1 year</option>
                                    <option value="1_5y" ${d.since === '1_5y' ? 'selected' : ''}>1–5 years</option>
                                    <option value="5y_plus" ${d.since === '5y_plus' ? 'selected' : ''}>5+ years</option>
                                </select>

                                <label style="font-size:0.8rem;font-weight:600">Currently taking medication?</label>
                                ${radioYesNo(`${c}:medication`, d.medication ?? null)}
                                <div id="med-followup-${c}" style="margin-top:var(--space-2);padding:var(--space-2);background:var(--surface-card);border-radius:var(--radius-sm);${d.medication ? '' : 'display:none'}">
                                    <label style="font-size:0.75rem;font-weight:600">Medication type / details:</label>
                                    <div class="option-grid mb-1" style="grid-template-columns:repeat(auto-fit,minmax(120px,1fr))">${chipGroup(`${c}:medicationTypes`, MEDICATION_TYPES, medTypes, null)}</div>
                                    <input type="text" data-detail="${c}:medicationNotes" placeholder="Type tablet name or dose..." value="${esc(d.medicationNotes)}">
                                </div>
                            </div>`;
                        }).join('')}
                    </div>
                `;

                selected.forEach((c) => {
                    const d = state.medicalHistory.details[c] = state.medicalHistory.details[c] || {};
                    followupsEl.querySelector(`[data-detail="${c}:since"]`)?.addEventListener('change', (e) => {
                        d.since = e.target.value;
                        updateContinueEnabled();
                    });
                    wireYesNo(followupsEl, `${c}:medication`, (v) => {
                        d.medication = v;
                        const box = followupsEl.querySelector(`#med-followup-${c}`);
                        if (box) box.style.display = v ? '' : 'none';
                        updateContinueEnabled();
                    });
                    wireChipGroup(followupsEl, `${c}:medicationTypes`, (values) => {
                        d.medicationTypes = values;
                        updateContinueEnabled();
                    });
                    followupsEl.querySelector(`[data-detail="${c}:medicationNotes"]`)?.addEventListener('input', (e) => {
                        d.medicationNotes = e.target.value;
                    });
                });
            }

            wireChipGroup(container, 'conditions', (values) => {
                state.medicalHistory.conditions = values;
                const box = container.querySelector('#other-condition-box');
                if (box) box.style.display = values.includes('other') ? '' : 'none';
                renderConditionFollowups();
                updateContinueEnabled();
            });

            container.querySelector('#other-condition-input')?.addEventListener('input', (e) => {
                state.medicalHistory.otherConditionNotes = e.target.value;
            });

            activeScreening.forEach((q) => {
                wireYesNo(container, q.key, (v) => {
                    state.healthScreening[q.key] = v;
                    container.querySelector(`[data-followup="${q.key}"]`).hidden = !v;
                    updateContinueEnabled();
                });

                if (q.presets) {
                    wireChipGroup(container, q.key + 'Presets', (values) => {
                        state.healthScreening[q.key + 'Presets'] = values;
                        updateContinueEnabled();
                    });
                }

                container.querySelector(`[data-note="${q.key}"]`)?.addEventListener('input', (e) => {
                    state.healthScreening[q.key + 'Notes'] = e.target.value;
                });
            });

            renderConditionFollowups();
        },
        isComplete() {
            const hasConditions = state.medicalHistory.conditions.length > 0;
            const nonNoneConditions = state.medicalHistory.conditions.filter((c) => c !== 'none_of_these');
            const detailsValid = nonNoneConditions.every((c) => {
                const d = state.medicalHistory.details?.[c];
                return d && d.since && d.medication !== undefined;
            });
            const activeScreening = getActiveScreening();
            const screeningDone = activeScreening.every((q) => typeof state.healthScreening[q.key] === 'boolean');
            return hasConditions && detailsValid && screeningDone;
        },
        save(next) {
            return {
                step: 'medicalHistory',
                data: state.medicalHistory,
                category: state.category,
                nextStep: next,
            };
        },
    },

    // Step 4: Pain Assessment & Doctor Restrictions
    {
        key: 'painAssessment',
        title: 'Pain & Doctor Restrictions',
        render(container) {
            container.innerHTML = `
                <h2 class="profile-section__title" style="margin-bottom:var(--space-2);font-size:1.15rem">Pain Assessment &amp; Restrictions</h2>
                <p class="text-muted" style="margin:0 0 var(--space-4)">Where do you currently experience discomfort, joint stiffness, or physical pain?</p>
                
                <label style="font-weight:600;font-size:0.88rem;display:block;margin-bottom:var(--space-2)">1. Pain or Discomfort Areas</label>
                <div class="option-grid mb-3">
                    ${chipGroup('areas', PAIN_AREAS, state.painAssessment.areas, null)}
                    ${chipGroup('areas', ['No pain'], state.painAssessment.areas, 'no_pain')}
                </div>

                <div id="pain-details-container"></div>

                <div style="margin-top:var(--space-5);padding-top:var(--space-4);border-top:1px solid var(--border-subtle)">
                    <label style="font-weight:600;font-size:0.88rem;display:block;margin-bottom:var(--space-2)">2. Doctor-Specified Physical Restrictions</label>
                    <p class="text-muted" style="margin:0 0 var(--space-3);font-size:0.78rem">Do you have any medical advice to avoid certain postures, weights, or movements?</p>
                    ${radioYesNo('hasRestriction', state.doctorRestrictions.hasRestriction)}
                    
                    <div id="restriction-notes" style="margin-top:var(--space-3);${state.doctorRestrictions.hasRestriction ? '' : 'display:none'}">
                        <label style="font-weight:600;font-size:0.82rem;display:block;margin-bottom:4px">What specific restrictions were advised?</label>
                        <div class="option-grid mb-2" style="grid-template-columns:repeat(auto-fit,minmax(140px,1fr))">
                            ${chipGroup('doctorRestrictionTypes', RESTRICTION_TYPES, state.doctorRestrictions.restrictionTypes || [], null)}
                        </div>
                        <label style="font-weight:600;font-size:0.8rem;color:var(--color-primary);display:block;margin-bottom:4px">Doctor instructions / notes:</label>
                        <textarea data-field="restrictionNotes" placeholder="Type doctor's instructions or restrictions...">${esc(state.doctorRestrictions.notes)}</textarea>
                    </div>
                </div>
            `;

            function renderPainDetails() {
                const detailsContainer = container.querySelector('#pain-details-container');
                const areas = state.painAssessment.areas.filter((a) => a !== 'no_pain');
                state.painAssessment.details = state.painAssessment.details || {};

                if (areas.length === 0) {
                    detailsContainer.innerHTML = '';
                    return;
                }

                detailsContainer.innerHTML = `
                    <div style="margin-block:var(--space-3);padding:var(--space-3);background:var(--surface-sunken);border-radius:var(--radius-md)">
                        <span style="font-weight:600;font-size:0.84rem;color:var(--color-primary);display:block;margin-bottom:var(--space-2)">Details for selected pain areas:</span>
                        ${areas.map((a) => {
                            const d = state.painAssessment.details[a] || {};
                            const severity = LEGACY_SEVERITY[d.severity] || d.severity;
                            return `<div class="card mb-3" style="padding:var(--space-3);border:1px solid var(--border-subtle);border-radius:var(--radius-md)">
                                <div style="font-weight:600;font-size:0.9rem;margin-bottom:var(--space-2)">${esc(areaLabel(a))}</div>
                                
                                <label style="font-size:0.78rem;font-weight:600">Pain severity (1–10)</label>
                                <div class="severity-scale" style="margin-bottom:4px">${radioGroup(`${a}:severity`, SEVERITY_SCALE, severity)}</div>
                                <div class="severity-scale__hint" style="margin-bottom:var(--space-2)"><span>1 — mild</span><span>10 — severe</span></div>

                                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:8px;margin-bottom:var(--space-2)">
                                    <div>
                                        <label style="font-size:0.78rem;font-weight:600">Side</label>
                                        <div class="option-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">${radioGroup(`${a}:side`, ['Left', 'Right', 'Both'], d.side)}</div>
                                    </div>
                                    <div>
                                        <label style="font-size:0.78rem;font-weight:600">Pattern</label>
                                        <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">${radioGroup(`${a}:pattern`, ['Continuous', 'Occasional'], d.pattern)}</div>
                                    </div>
                                </div>

                                <label style="font-size:0.78rem;font-weight:600">Discomfort while walking?</label>
                                ${radioYesNo(`${a}:walkingPain`, d.walkingPain ?? null)}
                            </div>`;
                        }).join('')}
                    </div>
                `;

                areas.forEach((a) => {
                    const d = state.painAssessment.details[a] = state.painAssessment.details[a] || {};
                    wireChipGroup(detailsContainer, `${a}:severity`, (values) => { d.severity = values[0] || null; updateContinueEnabled(); });
                    wireChipGroup(detailsContainer, `${a}:side`, (values) => { d.side = values[0] || null; updateContinueEnabled(); });
                    wireChipGroup(detailsContainer, `${a}:pattern`, (values) => { d.pattern = values[0] || null; updateContinueEnabled(); });
                    wireYesNo(detailsContainer, `${a}:walkingPain`, (v) => { d.walkingPain = v; updateContinueEnabled(); });
                });
            }

            wireChipGroup(container, 'areas', (values) => {
                state.painAssessment.areas = values;
                renderPainDetails();
                updateContinueEnabled();
            });

            wireYesNo(container, 'hasRestriction', (v) => {
                state.doctorRestrictions.hasRestriction = v;
                container.querySelector('#restriction-notes').style.display = v ? '' : 'none';
                updateContinueEnabled();
            });

            wireChipGroup(container, 'doctorRestrictionTypes', (values) => {
                state.doctorRestrictions.restrictionTypes = values;
                updateContinueEnabled();
            });

            container.querySelector('[data-field="restrictionNotes"]')?.addEventListener('input', (e) => {
                state.doctorRestrictions.notes = e.target.value;
            });

            renderPainDetails();
        },
        isComplete() {
            const hasAreas = state.painAssessment.areas.length > 0;
            const areas = state.painAssessment.areas.filter((a) => a !== 'no_pain');
            const detailsValid = areas.every((a) => {
                const d = state.painAssessment.details?.[a];
                return d && d.severity && d.side && d.pattern && d.walkingPain !== undefined;
            });
            const restrictionsDone = state.doctorRestrictions.hasRestriction !== null;
            return hasAreas && detailsValid && restrictionsDone;
        },
        save(next) {
            return {
                step: 'painAssessment',
                data: state.painAssessment,
                category: state.category,
                nextStep: next,
            };
        },
    },

    // Step 5: Review & Confirmation
    {
        key: 'reviewConfirmation',
        title: 'Review & Confirmation',
        render(container) {
            const catObj = getCategoryObj(state.category);
            const spec = CATEGORY_QUESTIONS[state.category] || CATEGORY_QUESTIONS.adult;
            const catAnswers = state.categorySafety;

            // Medical Conditions summary
            const conditionsList = state.medicalHistory.conditions.length > 0
                ? (state.medicalHistory.conditions.includes('none_of_these') ? 'None reported' : state.medicalHistory.conditions.map((c) => {
                    const l = getActiveConditions().find((m) => slug(m) === c) || c;
                    return c === 'other' && state.medicalHistory.otherConditionNotes ? `Other (${state.medicalHistory.otherConditionNotes})` : l;
                }).join(', '))
                : 'Not specified';

            // Screening summary
            const activeScreening = getActiveScreening();
            const screeningSummary = activeScreening.map((q) => {
                const ans = state.healthScreening[q.key];
                const note = state.healthScreening[q.key + 'Notes'];
                return {
                    label: q.question,
                    value: ans === true ? `Yes${note ? ` (${note})` : ''}` : (ans === false ? 'No' : '—'),
                };
            });

            // Pain areas summary
            const painAreasList = state.painAssessment.areas.includes('no_pain') || state.painAssessment.areas.length === 0
                ? 'No pain or discomfort reported'
                : state.painAssessment.areas.map((a) => {
                    const d = state.painAssessment.details?.[a] || {};
                    return `${areaLabel(a)}${d.severity ? ` (Severity ${d.severity}/10)` : ''}`;
                }).join(', ');

            // Doctor restrictions summary
            const restrictionsSummary = state.doctorRestrictions.hasRestriction === true
                ? (state.doctorRestrictions.notes || (state.doctorRestrictions.restrictionTypes?.join(', ') || 'Yes (Restricted)'))
                : (state.doctorRestrictions.hasRestriction === false ? 'No restrictions' : '—');

            container.innerHTML = `
                <h2 class="profile-section__title" style="margin-bottom:var(--space-2);font-size:1.15rem">Review &amp; Confirm Your Details</h2>
                <p class="text-muted" style="margin:0 0 var(--space-4)">Please review your answers below. If you need to make any changes, click "Edit" on that section. Once confirmed, you can book your yoga class.</p>
                
                <div class="review-summary">
                    <!-- Section 1: Profile Category -->
                    <div class="review-section-card">
                        <div class="review-section-card__head">
                            <div class="review-section-card__title">
                                <span style="font-size:1.2rem">${catObj.icon}</span>
                                <span>Profile Category: <strong>${catObj.title}</strong></span>
                            </div>
                            <button type="button" class="review-edit-btn" data-jump-step="0">Edit Category</button>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px">
                            <span class="review-badge">${catObj.badge}</span>
                            <span style="font-size:0.78rem;color:var(--text-muted)">${catObj.desc}</span>
                        </div>
                    </div>

                    <!-- Section 2: Lifestyle & Practice Routine -->
                    <div class="review-section-card">
                        <div class="review-section-card__head">
                            <div class="review-section-card__title">
                                <span>${spec.title}</span>
                            </div>
                            <button type="button" class="review-edit-btn" data-jump-step="1">Edit Routine</button>
                        </div>
                        <div class="review-list">
                            ${spec.fields.map((f) => {
                                let val = catAnswers[f.key];
                                if (val === true) val = 'Yes';
                                else if (val === false) val = 'No';
                                else if (val === null || val === undefined || val === '') val = '—';
                                if (f.writeInKey && catAnswers[f.writeInKey]) {
                                    val += ` (${catAnswers[f.writeInKey]})`;
                                }
                                return `<div class="review-item">
                                    <span class="review-item__label">${f.label}</span>
                                    <span class="review-item__value">${esc(val)}</span>
                                </div>`;
                            }).join('')}
                        </div>
                    </div>

                    <!-- Section 3: Medical Conditions & Screening -->
                    <div class="review-section-card">
                        <div class="review-section-card__head">
                            <div class="review-section-card__title">
                                <span>Medical History &amp; Health Screening</span>
                            </div>
                            <button type="button" class="review-edit-btn" data-jump-step="2">Edit Medical</button>
                        </div>
                        <div class="review-list">
                            <div class="review-item" style="grid-column:1/-1">
                                <span class="review-item__label">Reported Medical Conditions</span>
                                <span class="review-item__value"><strong>${esc(conditionsList)}</strong></span>
                            </div>
                            ${screeningSummary.map((s) => `<div class="review-item">
                                <span class="review-item__label">${s.label}</span>
                                <span class="review-item__value">${esc(s.value)}</span>
                            </div>`).join('')}
                        </div>
                    </div>

                    <!-- Section 4: Pain & Physical Restrictions -->
                    <div class="review-section-card">
                        <div class="review-section-card__head">
                            <div class="review-section-card__title">
                                <span>Pain Assessment &amp; Restrictions</span>
                            </div>
                            <button type="button" class="review-edit-btn" data-jump-step="3">Edit Pain/Restrictions</button>
                        </div>
                        <div class="review-list">
                            <div class="review-item">
                                <span class="review-item__label">Pain or Discomfort Areas</span>
                                <span class="review-item__value"><strong>${esc(painAreasList)}</strong></span>
                            </div>
                            <div class="review-item">
                                <span class="review-item__label">Doctor Physical Restrictions</span>
                                <span class="review-item__value">${esc(restrictionsSummary)}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="background:var(--color-primary-soft);border:1px solid var(--color-primary);border-radius:var(--radius-md);padding:var(--space-3);margin-top:var(--space-4);text-align:center">
                    <p style="margin:0;font-size:0.82rem;color:var(--text-primary);font-weight:500">
                        &#10004; All details verified? Click <strong>Confirm &amp; Book Yoga Class</strong> below to finish intake and schedule your session.
                    </p>
                </div>
            `;

            container.querySelectorAll('[data-jump-step]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const stepIdx = parseInt(btn.dataset.jumpStep, 10);
                    if (!isNaN(stepIdx) && stepIdx >= 0 && stepIdx < STEPS.length) {
                        currentStepIndex = stepIdx;
                        renderStep();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
            });
        },
        isComplete() {
            return true;
        },
        save(next) {
            return {
                step: 'reviewConfirmation',
                data: { confirmed: true, category: state.category },
                category: state.category,
                nextStep: next,
            };
        },
    },
];

let currentStepIndex = 0;

// If user already completed or at stage 2+, show completed screen by default
if (existing.currentStep === 'completed' || (existing.stage && Number(existing.stage) >= 2)) {
    document.querySelector('#assessment-wizard').hidden = true;
    document.querySelector('#assessment-done').hidden = false;
    const catObj = getCategoryObj(state.category || 'adult');
    document.querySelector('#done-level').textContent = catObj.title;
} else if (state.category) {
    // If category already picked, resume at categorySafety or medicalHistory
    const resumeIdx = STEPS.findIndex((s) => s.key === existing.currentStep);
    if (resumeIdx !== -1) {
        currentStepIndex = resumeIdx;
    }
}

function updateProgress() {
    const total = STEPS.length;
    const currentPos = currentStepIndex + 1;

    document.querySelector('#step-label').textContent = `Step ${currentPos} of ${total}`;

    const progressEl = document.querySelector('#assessment-progress');
    progressEl.innerHTML = STEPS.map((_, i) =>
        `<div class="assessment-progress__dot${i < currentStepIndex ? ' is-done' : ''}${i === currentStepIndex ? ' is-current' : ''}"></div>`
    ).join('');
}

function updateContinueEnabled() {
    const step = STEPS[currentStepIndex];
    const canContinue = step.isComplete();
    document.querySelector('#continue-btn').disabled = !canContinue;
}

function renderStep() {
    const step = STEPS[currentStepIndex];
    const container = document.querySelector('#step-container');
    step.render(container);
    updateProgress();
    updateContinueEnabled();

    // Back button visibility
    const backBtn = document.querySelector('#back-btn');
    if (currentStepIndex > 0) {
        backBtn.style.visibility = 'visible';
        backBtn.style.display = 'inline-flex';
    } else {
        backBtn.style.visibility = 'hidden';
        backBtn.style.display = 'none';
    }

    // Continue / Submit button label
    const continueBtn = document.querySelector('#continue-btn');
    if (currentStepIndex === STEPS.length - 1) {
        continueBtn.innerHTML = 'Confirm &amp; Book Yoga Class &rarr;';
    } else {
        continueBtn.innerHTML = 'Continue &rarr;';
    }
}

async function saveCurrentStep(nextStepKey, isFinal = false) {
    const step = STEPS[currentStepIndex];
    const payload = step.save(nextStepKey);
    try {
        await apiPost('/api/health-assessment/step', {
            ...payload,
            category: state.category,
            final: isFinal,
            stage: 2,
        });

        // Also ensure healthScreening and doctorRestrictions are saved when passing steps 3 & 4
        if (step.key === 'medicalHistory' && state.healthScreening) {
            await apiPost('/api/health-assessment/step', {
                step: 'healthScreening',
                data: state.healthScreening,
                category: state.category,
                final: false,
                stage: 2,
            });
        }
        if (step.key === 'painAssessment' && state.doctorRestrictions) {
            await apiPost('/api/health-assessment/step', {
                step: 'doctorRestrictions',
                data: state.doctorRestrictions,
                category: state.category,
                final: false,
                stage: 2,
            });
        }
    } catch (e) {
        console.warn('Auto-save step notice:', e);
    }
}

// Next / Continue button handler
document.querySelector('#continue-btn').addEventListener('click', async () => {
    const continueBtn = document.querySelector('#continue-btn');
    continueBtn.disabled = true;

    if (currentStepIndex < STEPS.length - 1) {
        const nextStep = STEPS[currentStepIndex + 1];
        await saveCurrentStep(nextStep.key, false);
        currentStepIndex++;
        renderStep();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
        // Final Review Step Submission
        continueBtn.textContent = 'Saving & Booking...';
        await saveCurrentStep('completed', true);
        toast.success('Health intake complete! Redirecting to yoga booking...');
        
        setTimeout(() => {
            window.location.href = bookingRedirectUrl;
        }, 800);
    }
});

// Back button handler
document.querySelector('#back-btn').addEventListener('click', () => {
    if (currentStepIndex > 0) {
        currentStepIndex--;
        renderStep();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});

// Save & Continue Later handler
document.querySelector('#save-later-btn').addEventListener('click', async () => {
    await saveCurrentStep(STEPS[currentStepIndex].key, false);
    toast.success('Progress saved! You can finish anytime.');
    setTimeout(() => { window.location.href = '/dashboard'; }, 1000);
});

// Re-edit answers handler from completed screen
document.querySelector('#re-edit-btn')?.addEventListener('click', () => {
    document.querySelector('#assessment-done').hidden = true;
    document.querySelector('#assessment-wizard').hidden = false;
    currentStepIndex = 4; // Start at review page so user sees all answers
    renderStep();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Initial boot
if (document.querySelector('#assessment-wizard').hidden === false) {
    renderStep();
}
</script>
