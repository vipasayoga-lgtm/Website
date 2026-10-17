<?php

use App\Core\View;

$pageTitle = 'Your Wellness Profile — Vipasa Yoga';
$pageCss = 'onboarding';
?>
<div class="get-started-page">
    <div class="onboarding-panel get-started-card" id="assessment-card">
        <a href="/dashboard" class="get-started-card__back"><?= View::icon('arrow-left', 'icon', 16) ?> Back to Dashboard</a>
        <div class="get-started-card__header">
            <div class="splash__wordmark" style="color:var(--color-primary);font-size:var(--font-size-lg)">Vipasa Yoga</div>
            <h1 class="get-started-card__title">Your Wellness Profile</h1>
            <p class="get-started-card__subtitle">Now that you've had your first session, a few lifestyle questions help us tailor your practice. Answers save automatically.</p>
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
const existing = <?= json_encode($profile['health_assessment'] ?? null) ?> || {};
const priorPainAreas = (existing.painAssessment && existing.painAssessment.areas) || [];
const hadPain = priorPainAreas.some((a) => a !== 'no_pain');

const state = {
    lifestyle: existing.lifestyle || {},
    sleep: existing.sleep || {},
    exercise: existing.exercise || {},
    stress: existing.stress || {},
    yogaExperience: existing.yogaExperience || {},
    goals: existing.goals || { selected: [] },
    supportSystem: existing.supportSystem || {},
    recoveryGoals: existing.recoveryGoals || {},
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

const GOAL_LABELS = ['Weight Loss', 'Flexibility', 'Strength', 'Pain Relief', 'Meditation', 'Stress Relief', 'Better Sleep', 'Prenatal', 'Postnatal', 'General Wellness'];

const STEPS = [
    {
        key: 'lifestyle',
        isVisible: () => true,
        render(container) {
            const d = state.lifestyle;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Lifestyle</h2>
                <label>Occupation</label>
                <div class="option-grid" style="margin-bottom:var(--space-3)">${chipGroup('occupation', ['Desk job', 'Active job', 'Homemaker', 'Student', 'Retired', 'Other'], d.occupation, false)}</div>
                <label>Do you smoke?</label>
                ${radioYesNo('smoking', d.smoking ?? null)}
                <label style="margin-top:var(--space-3)">Do you drink alcohol?</label>
                ${radioYesNo('alcohol', d.alcohol ?? null)}
            `;
            wireChipGroup(container, 'occupation', (v) => { state.lifestyle.occupation = v[0] || null; updateContinueEnabled(); });
            wireYesNo(container, 'smoking', (v) => { state.lifestyle.smoking = v; updateContinueEnabled(); });
            wireYesNo(container, 'alcohol', (v) => { state.lifestyle.alcohol = v; updateContinueEnabled(); });
        },
        isComplete: () => !!state.lifestyle.occupation && state.lifestyle.smoking !== undefined && state.lifestyle.alcohol !== undefined,
        save(next) { return { step: 'lifestyle', data: state.lifestyle, nextStep: next }; },
    },
    {
        key: 'sleep',
        isVisible: () => true,
        render(container) {
            const d = state.sleep;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Sleep</h2>
                <label>Average hours of sleep</label>
                <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-bottom:var(--space-3)">${chipGroup('hours', ['Less than 5', '5-6', '7-8', '8+'], d.hours, false)}</div>
                <label>Sleep quality</label>
                <div class="option-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">${chipGroup('quality', ['Good', 'Average', 'Poor'], d.quality, false)}</div>
            `;
            wireChipGroup(container, 'hours', (v) => { state.sleep.hours = v[0] || null; updateContinueEnabled(); });
            wireChipGroup(container, 'quality', (v) => { state.sleep.quality = v[0] || null; updateContinueEnabled(); });
        },
        isComplete: () => !!state.sleep.hours && !!state.sleep.quality,
        save(next) { return { step: 'sleep', data: state.sleep, nextStep: next }; },
    },
    {
        key: 'exercise',
        isVisible: () => true,
        render(container) {
            const d = state.exercise;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Exercise</h2>
                <label>Current exercise frequency</label>
                <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-bottom:var(--space-3)">${chipGroup('frequency', ['None', '1-2x per week', '3-4x per week', 'Daily'], d.frequency, false)}</div>
                <label>Preferred intensity</label>
                <div class="option-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">${chipGroup('intensity', ['Light', 'Moderate', 'Intense'], d.intensity, false)}</div>
            `;
            wireChipGroup(container, 'frequency', (v) => { state.exercise.frequency = v[0] || null; updateContinueEnabled(); });
            wireChipGroup(container, 'intensity', (v) => { state.exercise.intensity = v[0] || null; updateContinueEnabled(); });
        },
        isComplete: () => !!state.exercise.frequency && !!state.exercise.intensity,
        save(next) { return { step: 'exercise', data: state.exercise, nextStep: next }; },
    },
    {
        key: 'stress',
        isVisible: () => true,
        render(container) {
            const d = state.stress;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Stress</h2>
                <label>Current stress level</label>
                <div class="option-grid" style="grid-template-columns:repeat(3,minmax(0,1fr));margin-bottom:var(--space-3)">${chipGroup('level', ['Low', 'Moderate', 'High'], d.level, false)}</div>
                <label>Main source (if any)</label>
                <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">${chipGroup('source', ['Work', 'Family', 'Health', 'Finances', 'Other', 'None'], d.source, false)}</div>
            `;
            wireChipGroup(container, 'level', (v) => { state.stress.level = v[0] || null; updateContinueEnabled(); });
            wireChipGroup(container, 'source', (v) => { state.stress.source = v[0] || null; updateContinueEnabled(); });
        },
        isComplete: () => !!state.stress.level && !!state.stress.source,
        save(next) { return { step: 'stress', data: state.stress, nextStep: next }; },
    },
    {
        key: 'yogaExperience',
        isVisible: () => true,
        render(container) {
            const d = state.yogaExperience;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Yoga Experience</h2>
                <label>Prior yoga experience</label>
                <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-bottom:var(--space-3)">${chipGroup('experience', ['None', 'Beginner', 'Intermediate', 'Advanced'], d.experience, false)}</div>
                <label>Have you practiced yoga before?</label>
                ${radioYesNo('practicedBefore', d.practicedBefore ?? null)}
            `;
            wireChipGroup(container, 'experience', (v) => { state.yogaExperience.experience = v[0] || null; updateContinueEnabled(); });
            wireYesNo(container, 'practicedBefore', (v) => { state.yogaExperience.practicedBefore = v; updateContinueEnabled(); });
        },
        isComplete: () => !!state.yogaExperience.experience && state.yogaExperience.practicedBefore !== undefined,
        save(next) { return { step: 'yogaExperience', data: state.yogaExperience, nextStep: next }; },
    },
    {
        key: 'goals',
        isVisible: () => true,
        render(container) {
            container.innerHTML = `
                <h2 class="profile-section__title" style="margin-bottom:var(--space-2)">Yoga Goals</h2>
                <p class="text-muted" style="margin:0 0 var(--space-5)">Select everything that applies.</p>
                <div class="option-grid">${chipGroup('goalsChips', GOAL_LABELS, null, true, state.goals.selected)}</div>
            `;
            wireChipGroup(container, 'goalsChips', (v) => { state.goals.selected = v; updateContinueEnabled(); });
        },
        isComplete: () => (state.goals.selected || []).length > 0,
        save(next) { return { step: 'goals', data: state.goals, nextStep: next }; },
    },
    {
        key: 'goalsDetails',
        isVisible: () => {
            const g = state.goals.selected || [];
            return g.includes('weight_loss') || g.includes('meditation') || g.includes('stress_relief');
        },
        render(container) {
            const g = state.goals.selected || [];
            state.goals.details = state.goals.details || {};
            let body = '';
            if (g.includes('weight_loss')) {
                body += `<div class="card mb-4" style="padding:var(--space-4)">
                    <div style="font-weight:var(--font-weight-semibold);margin-bottom:var(--space-3)">Weight Loss — primary focus</div>
                    <div class="option-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">${chipGroup('weightLossFocus', ['Cardio', 'Strength', 'Both'], state.goals.details.weightLossFocus, false)}</div>
                </div>`;
            }
            if (g.includes('meditation') || g.includes('stress_relief')) {
                body += `<div class="card mb-4" style="padding:var(--space-4)">
                    <div style="font-weight:var(--font-weight-semibold);margin-bottom:var(--space-3)">Preferred meditation style</div>
                    <div class="option-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">${chipGroup('meditationStyle', ['Guided', 'Silent', 'Breathing-focused'], state.goals.details.meditationStyle, false)}</div>
                </div>`;
            }
            container.innerHTML = `<h2 class="profile-section__title mb-3">A bit more about your goals</h2>${body}`;
            if (g.includes('weight_loss')) wireChipGroup(container, 'weightLossFocus', (v) => { state.goals.details.weightLossFocus = v[0] || null; updateContinueEnabled(); });
            if (g.includes('meditation') || g.includes('stress_relief')) wireChipGroup(container, 'meditationStyle', (v) => { state.goals.details.meditationStyle = v[0] || null; updateContinueEnabled(); });
        },
        isComplete() {
            const g = state.goals.selected || [];
            const d = state.goals.details || {};
            if (g.includes('weight_loss') && !d.weightLossFocus) return false;
            if ((g.includes('meditation') || g.includes('stress_relief')) && !d.meditationStyle) return false;
            return true;
        },
        save(next) { return { step: 'goals', data: state.goals, nextStep: next }; },
    },
    {
        key: 'supportSystem',
        isVisible: () => true,
        render(container) {
            const d = state.supportSystem;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Support System</h2>
                <label>Will someone be supporting your practice (attending with you, encouraging you)?</label>
                ${radioYesNo('hasSupport', d.hasSupport ?? null)}
                <div id="support-relationship" style="margin-top:var(--space-3);${d.hasSupport ? '' : 'display:none'}">
                    <label>Their relationship to you (optional)</label>
                    <input type="text" data-field="relationship" placeholder="e.g. Spouse, friend, family member" value="${d.relationship || ''}">
                </div>
            `;
            wireYesNo(container, 'hasSupport', (v) => {
                state.supportSystem.hasSupport = v;
                container.querySelector('#support-relationship').style.display = v ? '' : 'none';
                updateContinueEnabled();
            });
            container.querySelector('[data-field="relationship"]').addEventListener('input', (e) => { state.supportSystem.relationship = e.target.value; });
        },
        isComplete: () => state.supportSystem.hasSupport !== undefined,
        save(next) { return { step: 'supportSystem', data: state.supportSystem, nextStep: next }; },
    },
    {
        key: 'recoveryGoals',
        isVisible: () => ['postpartum_mother', 'senior_citizen'].includes(category) || hadPain,
        render(container) {
            const d = state.recoveryGoals;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Recovery Goals</h2>
                <label>What's your primary recovery goal?</label>
                <div class="option-grid" style="grid-template-columns:repeat(1,minmax(0,1fr))">${chipGroup('primaryGoal', ['Reduce pain', 'Regain strength', 'Improve mobility', 'Full recovery'], d.primaryGoal, false)}</div>
            `;
            wireChipGroup(container, 'primaryGoal', (v) => { state.recoveryGoals.primaryGoal = v[0] || null; updateContinueEnabled(); });
        },
        isComplete: () => !!state.recoveryGoals.primaryGoal,
        save(next) { return { step: 'recoveryGoals', data: state.recoveryGoals, nextStep: next }; },
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
        await apiPost('/api/health-assessment/step', { ...step.save(nextKey), final: isFinal, stage: 3 });
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
        toast.success('Thank you — your wellness profile is updated!');
        window.location.href = '/dashboard';
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
