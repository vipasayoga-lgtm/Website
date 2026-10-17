<?php

use App\Core\View;

$pageTitle = 'Advanced Health Check-In — Vipasa Yoga';
$pageCss = 'onboarding';
?>
<div class="get-started-page">
    <div class="onboarding-panel get-started-card" id="assessment-card">
        <a href="/dashboard" class="get-started-card__back"><?= View::icon('arrow-left', 'icon', 16) ?> Back to Dashboard</a>
        <div class="get-started-card__header">
            <div class="splash__wordmark" style="color:var(--color-primary);font-size:var(--font-size-lg)">Vipasa Yoga</div>
            <h1 class="get-started-card__title">Advanced Health Check-In</h1>
            <p class="get-started-card__subtitle">A quick update before your next session — one question at a time, saved automatically.</p>
        </div>

        <div class="assessment-progress" id="assessment-progress" aria-hidden="true"></div>

        <div id="step-container"></div>

        <div class="onboarding-nav">
            <button type="button" class="btn btn-ghost" id="back-btn" style="visibility:hidden">Back</button>
            <button type="button" class="btn btn-primary btn-lg" id="continue-btn" disabled>Continue</button>
        </div>
    </div>
</div>
<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

const category = <?= json_encode($category) ?>;
const redirectTo = <?= json_encode($redirectTo) ?>;
const existing = <?= json_encode($profile['health_assessment'] ?? null) ?> || {};
const priorConditions = (existing.medicalHistory && existing.medicalHistory.conditions) || [];
const hadConditions = priorConditions.some((c) => c !== 'none_of_these');

const state = {
    advancedHealth: existing.advancedHealth || {},
    medication: existing.medication || {},
    surgeries: existing.surgeries || {},
    advancedPain: existing.advancedPain || {},
    fallRisk: existing.fallRisk || {},
    lifestylePreferences: existing.lifestylePreferences || {},
};

function slug(label) {
    return label.toLowerCase().replace(/\s+/g, '_');
}

function chipGroup(name, options, selectedValue, multi, selectedList) {
    return options.map((label) => {
        const value = slug(label);
        const isSelected = multi ? (selectedList || []).includes(value) : selectedValue === value;
        return `<label class="option-card${isSelected ? ' is-selected' : ''}" data-chip="${name}" data-value="${value}" data-multi="${multi ? '1' : '0'}">
            <span class="option-card__title">${label}</span>
        </label>`;
    }).join('');
}

function wireChipGroup(container, name, onChange) {
    container.querySelectorAll(`[data-chip="${name}"]`).forEach((chip) => {
        chip.addEventListener('click', () => {
            const group = container.querySelectorAll(`[data-chip="${name}"]`);
            const multi = chip.dataset.multi === '1';

            if (multi) {
                chip.classList.toggle('is-selected');
            } else {
                group.forEach((c) => c.classList.remove('is-selected'));
                chip.classList.add('is-selected');
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

const PAIN_AREAS = ['Neck', 'Shoulder', 'Back', 'Lower Back', 'Hip', 'Knee', 'Wrist', 'Elbow', 'Ankle'];

const STEPS = [
    {
        key: 'advancedHealth',
        isVisible: () => hadConditions,
        render(container) {
            const d = state.advancedHealth;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Health Update</h2>
                <label>Has anything changed with your health since your last check-in?</label>
                ${radioYesNo('changed', d.changed ?? null)}
                <div id="advanced-health-notes" style="margin-top:var(--space-3);${d.changed ? '' : 'display:none'}">
                    <label>What's changed?</label>
                    <textarea data-field="notes" placeholder="Tell us what's new">${d.notes || ''}</textarea>
                </div>
            `;
            wireYesNo(container, 'changed', (v) => {
                state.advancedHealth.changed = v;
                container.querySelector('#advanced-health-notes').style.display = v ? '' : 'none';
                updateContinueEnabled();
            });
            container.querySelector('[data-field="notes"]').addEventListener('input', (e) => { state.advancedHealth.notes = e.target.value; });
        },
        isComplete: () => state.advancedHealth.changed !== undefined,
        save(next) { return { step: 'advancedHealth', data: state.advancedHealth, nextStep: next }; },
    },
    {
        key: 'medication',
        isVisible: () => true,
        render(container) {
            const d = state.medication;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Medication</h2>
                <label>Are you currently taking any medication?</label>
                ${radioYesNo('takingMedication', d.takingMedication ?? null)}
                <div id="medication-list" style="margin-top:var(--space-3);${d.takingMedication ? '' : 'display:none'}">
                    <label>Which medication(s)?</label>
                    <input type="text" data-field="list" placeholder="e.g. Metformin, Levothyroxine" value="${d.list || ''}">
                </div>
                <label style="margin-top:var(--space-3)">Any recent changes to your medication?</label>
                ${radioYesNo('recentChange', d.recentChange ?? null)}
            `;
            wireYesNo(container, 'takingMedication', (v) => {
                state.medication.takingMedication = v;
                container.querySelector('#medication-list').style.display = v ? '' : 'none';
                updateContinueEnabled();
            });
            wireYesNo(container, 'recentChange', (v) => { state.medication.recentChange = v; updateContinueEnabled(); });
            container.querySelector('[data-field="list"]').addEventListener('input', (e) => { state.medication.list = e.target.value; });
        },
        isComplete: () => state.medication.takingMedication !== undefined && state.medication.recentChange !== undefined,
        save(next) { return { step: 'medication', data: state.medication, nextStep: next }; },
    },
    {
        key: 'surgeries',
        isVisible: () => true,
        render(container) {
            const d = state.surgeries;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Surgeries</h2>
                <label>Have you had any surgeries?</label>
                ${radioYesNo('hadSurgery', d.hadSurgery ?? null)}
                <div id="surgery-details" style="margin-top:var(--space-3);${d.hadSurgery ? '' : 'display:none'}">
                    <label>Type of surgery</label>
                    <input type="text" data-field="type" placeholder="e.g. Knee arthroscopy" value="${d.type || ''}" style="margin-bottom:var(--space-3)">
                    <label>How long ago?</label>
                    <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">${chipGroup('howLongAgo', ['Less than 6 months', '6-12 months', '1-2 years', '2+ years'], d.howLongAgo, false)}</div>
                </div>
            `;
            wireYesNo(container, 'hadSurgery', (v) => {
                state.surgeries.hadSurgery = v;
                container.querySelector('#surgery-details').style.display = v ? '' : 'none';
                updateContinueEnabled();
            });
            container.querySelector('[data-field="type"]').addEventListener('input', (e) => { state.surgeries.type = e.target.value; });
            wireChipGroup(container, 'howLongAgo', (v) => { state.surgeries.howLongAgo = v[0] || null; updateContinueEnabled(); });
        },
        isComplete() {
            if (state.surgeries.hadSurgery === undefined) return false;
            if (state.surgeries.hadSurgery && !state.surgeries.howLongAgo) return false;
            return true;
        },
        save(next) { return { step: 'surgeries', data: state.surgeries, nextStep: next }; },
    },
    {
        key: 'advancedPain',
        isVisible: () => true,
        render(container) {
            const d = state.advancedPain;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Pain Update</h2>
                <label>Since your last check-in, how has your pain changed?</label>
                <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-bottom:var(--space-3)">${chipGroup('painChange', ['Improved', 'Same', 'Worse', 'No pain'], d.painChange, false)}</div>
                <label>Any new pain areas? (optional)</label>
                <div class="option-grid">${chipGroup('newAreas', PAIN_AREAS, null, true, d.newAreas)}</div>
            `;
            wireChipGroup(container, 'painChange', (v) => { state.advancedPain.painChange = v[0] || null; updateContinueEnabled(); });
            wireChipGroup(container, 'newAreas', (v) => { state.advancedPain.newAreas = v; });
        },
        isComplete: () => !!state.advancedPain.painChange,
        save(next) { return { step: 'advancedPain', data: state.advancedPain, nextStep: next }; },
    },
    {
        key: 'fallRisk',
        isVisible: () => true,
        render(container) {
            const d = state.fallRisk;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Fall Risk</h2>
                <label>Have you had any falls or near-falls recently?</label>
                ${radioYesNo('recentFalls', d.recentFalls ?? null)}
            `;
            wireYesNo(container, 'recentFalls', (v) => { state.fallRisk.recentFalls = v; updateContinueEnabled(); });
        },
        isComplete: () => state.fallRisk.recentFalls !== undefined,
        save(next) { return { step: 'fallRisk', data: state.fallRisk, nextStep: next }; },
    },
    {
        key: 'lifestylePreferences',
        isVisible: () => true,
        render(container) {
            const d = state.lifestylePreferences;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Lifestyle Preferences</h2>
                <label>Preferred class time</label>
                <div class="option-grid" style="grid-template-columns:repeat(3,minmax(0,1fr));margin-bottom:var(--space-3)">${chipGroup('classTime', ['Morning', 'Afternoon', 'Evening'], d.classTime, false)}</div>
                <label>Preferred class type</label>
                <div class="option-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">${chipGroup('classType', ['Group', 'Private', 'Either'], d.classType, false)}</div>
            `;
            wireChipGroup(container, 'classTime', (v) => { state.lifestylePreferences.classTime = v[0] || null; updateContinueEnabled(); });
            wireChipGroup(container, 'classType', (v) => { state.lifestylePreferences.classType = v[0] || null; updateContinueEnabled(); });
        },
        isComplete: () => !!state.lifestylePreferences.classTime && !!state.lifestylePreferences.classType,
        save(next) { return { step: 'lifestylePreferences', data: state.lifestylePreferences, nextStep: next }; },
    },
    {
        key: 'advancedRecommendations',
        isVisible: () => true,
        render(container) {
            const recs = [];
            if (state.advancedPain.painChange === 'worse') recs.push('Consider a gentler pace this week — let your instructor know before your session.');
            if (state.fallRisk.recentFalls) recs.push('We’ll flag balance-support options with your instructor.');
            if (state.lifestylePreferences.classType === 'private') recs.push('We’ll prioritize private session availability for you.');
            if (recs.length === 0) recs.push('Everything looks steady — your instructor will keep tailoring sessions to your goals.');

            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Your Recommendations</h2>
                <div class="card" style="padding:var(--space-4)">
                    <ul style="margin:0;padding-left:var(--space-5)">${recs.map((r) => `<li style="margin-bottom:var(--space-2)">${r}</li>`).join('')}</ul>
                </div>
            `;
        },
        isComplete: () => true,
        save() { return { step: 'lifestylePreferences', data: state.lifestylePreferences, nextStep: 'advancedRecommendations' }; },
    },
];

const container = document.getElementById('step-container');
const continueBtn = document.getElementById('continue-btn');
const backBtn = document.getElementById('back-btn');
const progressEl = document.getElementById('assessment-progress');

function visibleSteps() {
    return STEPS.filter((s) => s.isVisible());
}

let currentIndex = 0;

function renderProgress() {
    const steps = visibleSteps();
    progressEl.innerHTML = steps.map((_, i) => `<span class="assessment-progress__dot${i <= currentIndex ? ' is-done' : ''}${i === currentIndex ? ' is-current' : ''}"></span>`).join('');
}

function updateContinueEnabled() {
    const steps = visibleSteps();
    continueBtn.disabled = !steps[currentIndex].isComplete();
}

function renderStep() {
    const steps = visibleSteps();
    if (currentIndex >= steps.length) currentIndex = steps.length - 1;
    steps[currentIndex].render(container);
    backBtn.style.visibility = currentIndex === 0 ? 'hidden' : 'visible';
    continueBtn.textContent = currentIndex === steps.length - 1 ? 'Finish' : 'Continue';
    updateContinueEnabled();
    renderProgress();
}

async function persistCurrentStep(nextKey, isFinal) {
    const steps = visibleSteps();
    const step = steps[currentIndex];
    try {
        await apiPost('/api/health-assessment/step', { ...step.save(nextKey), final: isFinal, stage: 4 });
    } catch (err) {
        toast.error(err.message || 'Could not save — please check your connection.');
        throw err;
    }
}

continueBtn.addEventListener('click', async () => {
    const steps = visibleSteps();
    const isLast = currentIndex === steps.length - 1;
    const nextKey = isLast ? steps[currentIndex].key : steps[currentIndex + 1].key;

    continueBtn.disabled = true;
    try {
        await persistCurrentStep(nextKey, isLast);
    } catch {
        continueBtn.disabled = false;
        return;
    }

    if (isLast) {
        toast.success('Thanks — your advanced health check-in is complete!');
        window.location.href = redirectTo;
        return;
    }

    currentIndex++;
    renderStep();
});

backBtn.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
        renderStep();
    }
});

const savedStepKey = existing.currentStep;
if (savedStepKey) {
    const steps = visibleSteps();
    const idx = steps.findIndex((s) => s.key === savedStepKey);
    if (idx >= 0) currentIndex = idx;
}

renderStep();
</script>
