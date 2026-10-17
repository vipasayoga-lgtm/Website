<?php

use App\Core\View;

$pageTitle = 'Pre-Training Health & Safety Intake — Vipasa Yoga';
$pageCss = 'onboarding';
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
                <span class="assessment-meta__step" id="step-label" aria-live="polite">Step 1</span>
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
                <button type="button" class="btn btn-ghost" id="back-btn" style="visibility:hidden">Back</button>
                <button type="button" class="btn btn-primary btn-lg" id="continue-btn" disabled>Continue</button>
            </div>
        </div>

        <div class="assessment-done" id="assessment-done" hidden>
            <span class="assessment-done__icon"><?= View::icon('check', 'icon', 28) ?></span>
            <h2 class="assessment-done__title">Pre-Training Health Details Saved</h2>
            <p class="assessment-done__text">Thank you! Your health and safety information has been securely shared with your instructor.</p>
            <div class="assessment-done__stats" style="grid-template-columns:repeat(2,minmax(0,1fr))">
                <div class="assessment-done__stat">
                    <span class="assessment-done__stat-label">Progress</span>
                    <span class="assessment-done__stat-value">100%</span>
                </div>
                <div class="assessment-done__stat">
                    <span class="assessment-done__stat-label">Practice Focus</span>
                    <span class="assessment-done__stat-value" id="done-level">&mdash;</span>
                </div>
            </div>
            <div class="onboarding-nav" style="justify-content:center">
                <a href="<?= View::e($redirectTo) ?>" class="btn btn-primary btn-lg"><?= $redirectTo === '/dashboard' ? 'Continue to Dashboard' : 'Continue' ?> <?= View::icon('arrow-right', 'icon', 16) ?></a>
            </div>
            <p class="assessment-done__note">Your health and safety details will be reviewed by your instructor prior to your first yoga session.</p>
        </div>
    </div>
</div>
<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

const category = <?= json_encode($category) ?> || 'adult';
const existing = <?= json_encode($profile['health_assessment'] ?? null) ?> || {};

const state = {
    medicalHistory: existing.medicalHistory || { conditions: [], otherConditionNotes: '' },
    healthScreening: existing.healthScreening || {},
    painAssessment: existing.painAssessment || { areas: [] },
    doctorRestrictions: existing.doctorRestrictions || { hasRestriction: null, restrictionTypes: [], notes: '' },
    categorySafety: existing.categorySafety || {},
};

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

const activeConditions = CATEGORY_CONDITIONS[category] || CATEGORY_CONDITIONS.adult;
const activeScreening = CATEGORY_SCREENING[category] || CATEGORY_SCREENING.adult;

const PAIN_AREAS = ['Neck', 'Shoulder', 'Upper Back', 'Lower Back', 'Hip', 'Knee', 'Wrist', 'Elbow', 'Ankle', 'Foot'];
const SEVERITY_SCALE = Array.from({ length: 10 }, (_, i) => ({ value: String(i + 1), label: String(i + 1) }));
const TREATMENTS = ['None', 'Physiotherapy', 'Medication', 'Injections', 'Surgery', 'Other'];

const MEDICATION_TYPES = ['Blood Pressure', 'Thyroid', 'Diabetes / Insulin', 'Painkillers', 'Anxiety / Depression', 'Vitamins / Supplements', 'Cholesterol', 'Other'];
const RESTRICTION_TYPES = ['No heavy lifting', 'No inversions / headstands', 'Low-impact / gentle only', 'Avoid back bends / twists', 'Avoid high heart rate', 'Other'];

const LEGACY_AREA_LABELS = { back: 'Back' };
const LEGACY_SEVERITY = { mild: '3', moderate: '6', severe: '9' };

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

function slug(label) {
    return label.toLowerCase().replace(/\s+/g, '_');
}

function esc(value) {
    return String(value ?? '').replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
}

function areaLabel(area) {
    return PAIN_AREAS.find((p) => slug(p) === area) || LEGACY_AREA_LABELS[area] || area;
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

function wireYesNo(container, name, onChange) {
    container.querySelectorAll(`[data-yn="${name}"]`).forEach((el) => {
        el.addEventListener('click', () => {
            container.querySelectorAll(`[data-yn="${name}"]`).forEach((c) => c.classList.remove('is-selected'));
            el.classList.add('is-selected');
            onChange(el.dataset.value === 'yes');
        });
    });
}

const STEPS = [
    {
        key: 'categorySafety',
        isVisible: () => !!CATEGORY_QUESTIONS[category],
        render(container) {
            const spec = CATEGORY_QUESTIONS[category] || CATEGORY_QUESTIONS.adult;
            const d = state.categorySafety;

            container.innerHTML = `
                <h2 class="profile-section__title" style="margin-bottom:var(--space-2)">${spec.title} (${category.replace('_', ' ').toUpperCase()})</h2>
                <p class="text-muted" style="margin:0 0 var(--space-4)">These questions apply specifically to your profile category, so your instructor can plan safely.</p>
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
            const spec = CATEGORY_QUESTIONS[category] || CATEGORY_QUESTIONS.adult;
            const d = state.categorySafety;
            return spec.fields.every((f) => d[f.key] !== undefined && d[f.key] !== null && d[f.key] !== '');
        },
        save(next) {
            return { step: 'categorySafety', data: state.categorySafety, nextStep: next };
        },
    },
    {
        key: 'medicalHistory',
        isVisible: () => true,
        render(container) {
            const hasOther = state.medicalHistory.conditions.includes('other');
            container.innerHTML = `
                <h2 class="profile-section__title" style="margin-bottom:var(--space-2)">Medical Conditions (${category.replace('_', ' ').toUpperCase()})</h2>
                <p class="text-muted" style="margin:0 0 var(--space-5)">Select anything that applies — or "None of these" if it doesn't.</p>
                <div class="option-grid">${chipGroup('conditions', activeConditions, state.medicalHistory.conditions, null)}${chipGroup('conditions', ['None of these'], state.medicalHistory.conditions, 'none_of_these')}</div>
                <div id="other-condition-box" style="margin-top:var(--space-3);${hasOther ? '' : 'display:none'}">
                    <label style="font-weight:600;font-size:0.85rem;color:var(--color-primary)">Please specify your other medical condition:</label>
                    <input type="text" id="other-condition-input" placeholder="Type your condition here..." value="${esc(state.medicalHistory.otherConditionNotes || '')}">
                </div>
            `;
            wireChipGroup(container, 'conditions', (values) => {
                state.medicalHistory.conditions = values;
                const box = container.querySelector('#other-condition-box');
                if (box) box.style.display = values.includes('other') ? '' : 'none';
                updateContinueEnabled();
            });

            container.querySelector('#other-condition-input')?.addEventListener('input', (e) => {
                state.medicalHistory.otherConditionNotes = e.target.value;
            });
        },
        isComplete: () => state.medicalHistory.conditions.length > 0,
        save(next) {
            return { step: 'medicalHistory', data: state.medicalHistory, nextStep: next };
        },
    },
    {
        key: 'medicalHistoryDetails',
        isVisible: () => state.medicalHistory.conditions.some((c) => c !== 'none_of_these'),
        render(container) {
            const conditions = state.medicalHistory.conditions.filter((c) => c !== 'none_of_these');
            state.medicalHistory.details = state.medicalHistory.details || {};

            container.innerHTML = `
                <h2 class="profile-section__title mb-3">A bit more detail</h2>
                ${conditions.map((c) => {
                    const label = activeConditions.find((m) => slug(m) === c) || c;
                    const d = state.medicalHistory.details[c] || {};
                    const medTypes = d.medicationTypes || [];
                    const resTypes = d.restrictionTypes || [];

                    return `<div class="card mb-4" style="padding:var(--space-4);border-radius:var(--radius-lg)">
                        <div style="font-weight:var(--font-weight-semibold);font-size:1rem;color:var(--color-primary);margin-bottom:var(--space-3)">${esc(label)}</div>
                        
                        <label style="font-weight:600">Since when?</label>
                        <select data-detail="${c}:since" style="margin-bottom:var(--space-3)">
                            <option value="">Select duration</option>
                            <option value="lt_1y" ${d.since === 'lt_1y' ? 'selected' : ''}>Less than 1 year</option>
                            <option value="1_5y" ${d.since === '1_5y' ? 'selected' : ''}>1-5 years</option>
                            <option value="5y_plus" ${d.since === '5y_plus' ? 'selected' : ''}>5+ years</option>
                        </select>

                        <label style="font-weight:600">Currently on medication?</label>
                        ${radioYesNo(`${c}:medication`, d.medication ?? null)}
                        <div id="med-followup-${c}" style="margin-top:var(--space-3);padding:var(--space-3);background:var(--surface-sunken);border-radius:var(--radius-md);${d.medication ? '' : 'display:none'}">
                            <label style="font-weight:600;font-size:0.85rem;color:var(--text-primary)">What medication / tablets are you taking?</label>
                            <div class="option-grid mb-2" style="grid-template-columns:repeat(auto-fit,minmax(140px,1fr))">${chipGroup(`${c}:medicationTypes`, MEDICATION_TYPES, medTypes, null)}</div>
                            <label style="font-size:0.78rem">Type tablet name / specify details:</label>
                            <input type="text" data-detail="${c}:medicationNotes" placeholder="Type medication details..." value="${esc(d.medicationNotes)}">
                        </div>

                        <label style="margin-top:var(--space-3);font-weight:600">Doctor-restricted from physical activity?</label>
                        ${radioYesNo(`${c}:doctorRestriction`, d.doctorRestriction ?? null)}
                        <div id="res-followup-${c}" style="margin-top:var(--space-3);padding:var(--space-3);background:var(--surface-sunken);border-radius:var(--radius-md);${d.doctorRestriction ? '' : 'display:none'}">
                            <label style="font-weight:600;font-size:0.85rem;color:var(--text-primary)">What specific restrictions were advised?</label>
                            <div class="option-grid mb-2" style="grid-template-columns:repeat(auto-fit,minmax(140px,1fr))">${chipGroup(`${c}:restrictionTypes`, RESTRICTION_TYPES, resTypes, null)}</div>
                            <label style="font-size:0.78rem">Type doctor instructions / restriction details:</label>
                            <input type="text" data-detail="${c}:restrictionNotes" placeholder="Type doctor instructions..." value="${esc(d.restrictionNotes)}">
                        </div>

                        <label style="margin-top:var(--space-3);font-weight:600">Notes (optional)</label>
                        <textarea data-detail="${c}:notes" placeholder="Anything else your instructor should know">${esc(d.notes)}</textarea>
                    </div>`;
                }).join('')}
            `;

            conditions.forEach((c) => {
                state.medicalHistory.details[c] = state.medicalHistory.details[c] || {};
                const d = state.medicalHistory.details[c];

                container.querySelector(`[data-detail="${c}:since"]`).addEventListener('change', (e) => {
                    d.since = e.target.value;
                    updateContinueEnabled();
                });

                container.querySelector(`[data-detail="${c}:notes"]`).addEventListener('input', (e) => {
                    d.notes = e.target.value;
                });

                wireYesNo(container, `${c}:medication`, (v) => {
                    d.medication = v;
                    const box = container.querySelector(`#med-followup-${c}`);
                    if (box) box.style.display = v ? '' : 'none';
                    updateContinueEnabled();
                });

                wireChipGroup(container, `${c}:medicationTypes`, (values) => {
                    d.medicationTypes = values;
                    updateContinueEnabled();
                });

                container.querySelector(`[data-detail="${c}:medicationNotes"]`)?.addEventListener('input', (e) => {
                    d.medicationNotes = e.target.value;
                });

                wireYesNo(container, `${c}:doctorRestriction`, (v) => {
                    d.doctorRestriction = v;
                    const box = container.querySelector(`#res-followup-${c}`);
                    if (box) box.style.display = v ? '' : 'none';
                    updateContinueEnabled();
                });

                wireChipGroup(container, `${c}:restrictionTypes`, (values) => {
                    d.restrictionTypes = values;
                    updateContinueEnabled();
                });

                container.querySelector(`[data-detail="${c}:restrictionNotes"]`)?.addEventListener('input', (e) => {
                    d.restrictionNotes = e.target.value;
                });
            });
        },
        isComplete() {
            const conditions = state.medicalHistory.conditions.filter((c) => c !== 'none_of_these');
            const details = state.medicalHistory.details || {};
            return conditions.every((c) => {
                const d = details[c];
                if (!d || !d.since || d.medication === undefined || d.doctorRestriction === undefined) return false;
                return true;
            });
        },
        save(next) {
            return { step: 'medicalHistory', data: state.medicalHistory, nextStep: next };
        },
    },
    {
        key: 'healthScreening',
        isVisible: () => true,
        render(container) {
            container.innerHTML = `
                <h2 class="profile-section__title" style="margin-bottom:var(--space-2)">Health Screening (${category.replace('_', ' ').toUpperCase()})</h2>
                <p class="text-muted" style="margin:0 0 var(--space-4)">Answer every question — a "Yes" opens related options &amp; details.</p>
                ${activeScreening.map((q) => {
                    const answer = state.healthScreening[q.key];
                    const selectedPresets = state.healthScreening[q.key + 'Presets'] || [];
                    return `<div class="screening-row mb-4" style="padding:var(--space-3);border:1px solid var(--border-subtle);border-radius:var(--radius-md)">
                        <span class="screening-row__question" style="font-weight:600">${q.question}</span>
                        ${radioYesNo(q.key, answer ?? null)}
                        <div class="screening-row__followup" data-followup="${q.key}"${answer === true ? '' : ' hidden'} style="margin-top:var(--space-3)">
                            ${q.presets ? `<label style="font-weight:600;font-size:0.82rem">Select relevant type / area:</label>
                            <div class="option-grid mb-2" style="grid-template-columns:repeat(auto-fit,minmax(130px,1fr))">${chipGroup(q.key + 'Presets', q.presets, selectedPresets, null)}</div>` : ''}
                            <label for="note-${q.key}" style="font-weight:600;font-size:0.82rem;color:var(--color-primary)">${q.followUp}</label>
                            <textarea id="note-${q.key}" data-note="${q.key}" placeholder="Type your answer / custom details here...">${esc(state.healthScreening[q.key + 'Notes'])}</textarea>
                        </div>
                    </div>`;
                }).join('')}
            `;

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

                container.querySelector(`[data-note="${q.key}"]`).addEventListener('input', (e) => {
                    state.healthScreening[q.key + 'Notes'] = e.target.value;
                });
            });
        },
        isComplete: () => activeScreening.every((q) => typeof state.healthScreening[q.key] === 'boolean'),
        save(next) {
            return { step: 'healthScreening', data: state.healthScreening, nextStep: next };
        },
    },
    {
        key: 'painAssessment',
        isVisible: () => true,
        render(container) {
            container.innerHTML = `
                <h2 class="profile-section__title" style="margin-bottom:var(--space-2)">Pain Assessment</h2>
                <p class="text-muted" style="margin:0 0 var(--space-5)">Where do you currently experience pain or discomfort, if anywhere?</p>
                <div class="option-grid">${chipGroup('areas', PAIN_AREAS, state.painAssessment.areas, null)}${chipGroup('areas', ['No pain'], state.painAssessment.areas, 'no_pain')}</div>
            `;
            wireChipGroup(container, 'areas', (values) => {
                state.painAssessment.areas = values;
                updateContinueEnabled();
            });
        },
        isComplete: () => state.painAssessment.areas.length > 0,
        save(next) {
            return { step: 'painAssessment', data: state.painAssessment, nextStep: next };
        },
    },
    {
        key: 'painAssessmentDetails',
        isVisible: () => state.painAssessment.areas.some((a) => a !== 'no_pain'),
        render(container) {
            const areas = state.painAssessment.areas.filter((a) => a !== 'no_pain');
            state.painAssessment.details = state.painAssessment.details || {};

            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Tell us about the pain</h2>
                ${areas.map((a) => {
                    const d = state.painAssessment.details[a] || {};
                    const severity = LEGACY_SEVERITY[d.severity] || d.severity;
                    return `<div class="card mb-4" style="padding:var(--space-4)">
                        <div style="font-weight:var(--font-weight-semibold);margin-bottom:var(--space-3)">${esc(areaLabel(a))}</div>
                        <label>Pain severity</label>
                        <div class="severity-scale">${radioGroup(`${a}:severity`, SEVERITY_SCALE, severity)}</div>
                        <div class="severity-scale__hint"><span>1 — barely noticeable</span><span>10 — worst imaginable</span></div>
                        <label style="margin-top:var(--space-3)">Side</label>
                        <div class="option-grid" style="grid-template-columns:repeat(3,minmax(0,1fr));margin-bottom:var(--space-3)">${radioGroup(`${a}:side`, ['Left', 'Right', 'Both'], d.side)}</div>
                        <label>Is it continuous or occasional?</label>
                        <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-bottom:var(--space-3)">${radioGroup(`${a}:pattern`, ['Continuous', 'Occasional'], d.pattern)}</div>
                        <label>Pain while walking?</label>
                        ${radioYesNo(`${a}:walkingPain`, d.walkingPain ?? null)}
                        <label style="margin-top:var(--space-3)">Diagnosed by a doctor?</label>
                        ${radioYesNo(`${a}:doctorDiagnosis`, d.doctorDiagnosis ?? null)}
                        <label style="margin-top:var(--space-3)">Treatment received so far</label>
                        <div class="option-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">${radioGroup(`${a}:treatment`, TREATMENTS, d.treatment)}</div>
                    </div>`;
                }).join('')}
            `;

            areas.forEach((a) => {
                const d = state.painAssessment.details[a] = state.painAssessment.details[a] || {};
                if (LEGACY_SEVERITY[d.severity]) d.severity = LEGACY_SEVERITY[d.severity];
                wireChipGroup(container, `${a}:severity`, (values) => { d.severity = values[0] || null; updateContinueEnabled(); });
                wireChipGroup(container, `${a}:side`, (values) => { d.side = values[0] || null; updateContinueEnabled(); });
                wireChipGroup(container, `${a}:pattern`, (values) => { d.pattern = values[0] || null; updateContinueEnabled(); });
                wireChipGroup(container, `${a}:treatment`, (values) => { d.treatment = values[0] || null; });
                wireYesNo(container, `${a}:walkingPain`, (v) => { d.walkingPain = v; updateContinueEnabled(); });
                wireYesNo(container, `${a}:doctorDiagnosis`, (v) => { d.doctorDiagnosis = v; });
            });
        },
        isComplete() {
            const areas = state.painAssessment.areas.filter((a) => a !== 'no_pain');
            const details = state.painAssessment.details || {};
            return areas.every((a) => {
                const d = details[a];
                return d && d.severity && d.side && d.pattern && d.walkingPain !== undefined;
            });
        },
        save(next) {
            return { step: 'painAssessment', data: state.painAssessment, nextStep: next };
        },
    },
    {
        key: 'doctorRestrictions',
        isVisible: () => true,
        render(container) {
            const d = state.doctorRestrictions;
            const resTypes = d.restrictionTypes || [];
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Doctor Restrictions</h2>
                <label style="font-weight:600">Do you have any doctor-specified restrictions on physical activity?</label>
                ${radioYesNo('hasRestriction', d.hasRestriction)}
                <div id="restriction-notes" style="margin-top:var(--space-3);${d.hasRestriction ? '' : 'display:none'}">
                    <label style="font-weight:600;font-size:0.85rem">What specific restrictions were advised?</label>
                    <div class="option-grid mb-2" style="grid-template-columns:repeat(auto-fit,minmax(140px,1fr))">${chipGroup('doctorRestrictionTypes', RESTRICTION_TYPES, resTypes, null)}</div>
                    <label style="font-weight:600;font-size:0.82rem;color:var(--color-primary)">Type doctor instructions / restriction notes:</label>
                    <textarea data-field="restrictionNotes" placeholder="Type your doctor's specific restrictions here...">${esc(d.notes)}</textarea>
                </div>
            `;
            wireYesNo(container, 'hasRestriction', (v) => {
                state.doctorRestrictions.hasRestriction = v;
                container.querySelector('#restriction-notes').style.display = v ? '' : 'none';
                updateContinueEnabled();
            });
            wireChipGroup(container, 'doctorRestrictionTypes', (values) => {
                state.doctorRestrictions.restrictionTypes = values;
                updateContinueEnabled();
            });
            container.querySelector('[data-field="restrictionNotes"]').addEventListener('input', (e) => {
                state.doctorRestrictions.notes = e.target.value;
            });
        },
        isComplete: () => state.doctorRestrictions.hasRestriction !== null,
        save(next) {
            return { step: 'doctorRestrictions', data: state.doctorRestrictions, nextStep: next };
        },
    },
];

let currentStepIndex = 0;

if (existing.currentStep === 'completed' || (existing.stage && Number(existing.stage) >= 2)) {
    document.querySelector('#assessment-wizard').hidden = true;
    document.querySelector('#assessment-done').hidden = false;
} else if (existing.currentStep) {
    const resumeIdx = STEPS.findIndex((s) => s.key === existing.currentStep);
    if (resumeIdx !== -1) {
        currentStepIndex = resumeIdx;
    }
}

function visibleSteps() {
    return STEPS.filter((s) => s.isVisible());
}

function updateProgress() {
    const active = visibleSteps();
    const total = active.length;
    const currentPos = active.findIndex((s) => s.key === STEPS[currentStepIndex].key) + 1;

    document.querySelector('#step-label').textContent = `Step ${currentPos} of ${total}`;

    const progressEl = document.querySelector('#assessment-progress');
    progressEl.innerHTML = active.map((_, i) => `<div class="assessment-progress__bar${i + 1 <= currentPos ? ' is-active' : ''}"></div>`).join('');
}

function updateContinueEnabled() {
    const step = STEPS[currentStepIndex];
    document.querySelector('#continue-btn').disabled = !step.isComplete();
}

function renderStep() {
    const step = STEPS[currentStepIndex];
    const container = document.querySelector('#step-container');
    step.render(container);
    updateProgress();
    updateContinueEnabled();

    const active = visibleSteps();
    const currentPos = active.findIndex((s) => s.key === step.key);
    document.querySelector('#back-btn').style.visibility = currentPos > 0 ? 'visible' : 'hidden';
}

async function saveCurrentStep(nextStepKey, isFinal = false) {
    const step = STEPS[currentStepIndex];
    const payload = step.save(nextStepKey);
    try {
        await apiPost('/api/health-assessment/step', { ...payload, final: isFinal, stage: 2 });
    } catch (e) {
        console.warn('Auto-save failed:', e);
    }
}

document.querySelector('#continue-btn').addEventListener('click', async () => {
    const active = visibleSteps();
    const currentPos = active.findIndex((s) => s.key === STEPS[currentStepIndex].key);

    if (currentPos < active.length - 1) {
        const nextStep = active[currentPos + 1];
        await saveCurrentStep(nextStep.key, false);
        currentStepIndex = STEPS.findIndex((s) => s.key === nextStep.key);
        renderStep();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
        await saveCurrentStep('completed', true);
        document.querySelector('#assessment-wizard').hidden = true;
        document.querySelector('#assessment-done').hidden = false;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});

document.querySelector('#back-btn').addEventListener('click', () => {
    const active = visibleSteps();
    const currentPos = active.findIndex((s) => s.key === STEPS[currentStepIndex].key);
    if (currentPos > 0) {
        const prevStep = active[currentPos - 1];
        currentStepIndex = STEPS.findIndex((s) => s.key === prevStep.key);
        renderStep();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});

document.querySelector('#save-later-btn').addEventListener('click', async () => {
    await saveCurrentStep(STEPS[currentStepIndex].key, false);
    toast.success('Progress saved! You can finish anytime.');
    setTimeout(() => { window.location.href = '/dashboard'; }, 1000);
});

if (document.querySelector('#assessment-wizard').hidden === false) {
    renderStep();
}
</script>
