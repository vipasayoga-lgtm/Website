<?php

use App\Core\View;

$pageTitle = 'Complete Your Profile — Vipasa Yoga';
$pageCss = 'onboarding';
?>
<div class="get-started-page">
    <div class="onboarding-panel get-started-card" id="assessment-card">
        <a href="/dashboard" class="get-started-card__back"><?= View::icon('arrow-left', 'icon', 16) ?> Back to Dashboard</a>
        <div class="get-started-card__header">
            <div class="splash__wordmark" style="color:var(--color-primary);font-size:var(--font-size-lg)">Vipasa Yoga</div>
            <h1 class="get-started-card__title">Complete Your Profile</h1>
            <p class="get-started-card__subtitle">Just a few final details, then a quick review before you're all set.</p>
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

const existing = <?= json_encode($profile['health_assessment'] ?? null) ?> || {};
const category = <?= json_encode($category) ?>;

const state = {
    fitnessBackground: existing.fitnessBackground || {},
    foodPreference: existing.foodPreference || {},
    communicationPreference: existing.communicationPreference || {},
    languagePreference: existing.languagePreference || {},
    longTermGoals: existing.longTermGoals || {},
    consent: { statement1: false, statement2: false, statement3: false },
};

function slug(label) {
    return label.toLowerCase().replace(/\s+/g, '_');
}

function chipGroup(name, options, selectedValue) {
    return options.map((label) => {
        const value = slug(label);
        const isSelected = selectedValue === value;
        return `<label class="option-card${isSelected ? ' is-selected' : ''}" data-chip="${name}" data-value="${value}">
            <span class="option-card__title">${label}</span>
        </label>`;
    }).join('');
}

function wireChipGroup(container, name, onChange) {
    container.querySelectorAll(`[data-chip="${name}"]`).forEach((chip) => {
        chip.addEventListener('click', () => {
            const group = container.querySelectorAll(`[data-chip="${name}"]`);
            group.forEach((c) => c.classList.remove('is-selected'));
            chip.classList.add('is-selected');
            onChange(chip.dataset.value);
        });
    });
}

const STEPS = [
    {
        key: 'fitnessBackground',
        isVisible: () => true,
        render(container) {
            const d = state.fitnessBackground;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Fitness Background</h2>
                <label>How would you describe your fitness background?</label>
                <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-bottom:var(--space-3)">${chipGroup('level', ['None', 'Casual', 'Regular', 'Athlete'], d.level)}</div>
                <label>Any other physical activities you do? (optional)</label>
                <input type="text" data-field="other" placeholder="e.g. Running, swimming, cycling" value="${d.other || ''}">
            `;
            wireChipGroup(container, 'level', (v) => { state.fitnessBackground.level = v; updateContinueEnabled(); });
            container.querySelector('[data-field="other"]').addEventListener('input', (e) => { state.fitnessBackground.other = e.target.value; });
        },
        isComplete: () => !!state.fitnessBackground.level,
        save(next) { return { step: 'fitnessBackground', data: state.fitnessBackground, nextStep: next }; },
    },
    {
        key: 'foodPreference',
        isVisible: () => true,
        render(container) {
            const d = state.foodPreference;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Food Preference</h2>
                <label>What best describes your diet?</label>
                <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-bottom:var(--space-3)">${chipGroup('diet', ['Vegetarian', 'Vegan', 'Non-vegetarian', 'Eggetarian', 'Other'], d.diet)}</div>
                <label>Any dietary restrictions? (optional)</label>
                <input type="text" data-field="restrictions" placeholder="e.g. Gluten-free, nut allergy" value="${d.restrictions || ''}">
            `;
            wireChipGroup(container, 'diet', (v) => { state.foodPreference.diet = v; updateContinueEnabled(); });
            container.querySelector('[data-field="restrictions"]').addEventListener('input', (e) => { state.foodPreference.restrictions = e.target.value; });
        },
        isComplete: () => !!state.foodPreference.diet,
        save(next) { return { step: 'foodPreference', data: state.foodPreference, nextStep: next }; },
    },
    {
        key: 'communicationPreference',
        isVisible: () => true,
        render(container) {
            const d = state.communicationPreference;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Communication Preference</h2>
                <label>How would you like us to reach you?</label>
                <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">${chipGroup('channel', ['Email', 'Phone', 'WhatsApp', 'In-app only'], d.channel)}</div>
            `;
            wireChipGroup(container, 'channel', (v) => { state.communicationPreference.channel = v; updateContinueEnabled(); });
        },
        isComplete: () => !!state.communicationPreference.channel,
        save(next) { return { step: 'communicationPreference', data: state.communicationPreference, nextStep: next }; },
    },
    {
        key: 'languagePreference',
        isVisible: () => true,
        render(container) {
            const d = state.languagePreference;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Language Preference</h2>
                <label>Which language do you prefer?</label>
                <div class="option-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">${chipGroup('language', ['English', 'Hindi', 'Tamil', 'Other'], d.language)}</div>
            `;
            wireChipGroup(container, 'language', (v) => { state.languagePreference.language = v; updateContinueEnabled(); });
        },
        isComplete: () => !!state.languagePreference.language,
        save(next) { return { step: 'languagePreference', data: state.languagePreference, nextStep: next }; },
    },
    {
        key: 'longTermGoals',
        isVisible: () => true,
        render(container) {
            const d = state.longTermGoals;
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Long-Term Goals</h2>
                <label>What's your long-term wellness goal?</label>
                <div class="option-grid" style="grid-template-columns:repeat(1,minmax(0,1fr));margin-bottom:var(--space-3)">${chipGroup('primary', ['Maintain fitness', 'Chronic pain management', 'Mental wellness', 'Spiritual practice', 'Weight management', 'Other'], d.primary)}</div>
                <label>Anything else you'd like your instructor to know? (optional)</label>
                <textarea data-field="notes" placeholder="Share anything else that matters to your practice">${d.notes || ''}</textarea>
            `;
            wireChipGroup(container, 'primary', (v) => { state.longTermGoals.primary = v; updateContinueEnabled(); });
            container.querySelector('[data-field="notes"]').addEventListener('input', (e) => { state.longTermGoals.notes = e.target.value; });
        },
        isComplete: () => !!state.longTermGoals.primary,
        save(next) { return { step: 'longTermGoals', data: state.longTermGoals, nextStep: next }; },
    },
    {
        key: 'review',
        isVisible: () => true,
        render(container) {
            const rows = [
                ['Category', category ? category.replace(/_/g, ' ') : '—'],
                ['Fitness background', state.fitnessBackground.level || '—'],
                ['Diet', state.foodPreference.diet || '—'],
                ['Preferred contact', state.communicationPreference.channel || '—'],
                ['Preferred language', state.languagePreference.language || '—'],
                ['Long-term goal', (state.longTermGoals.primary || '—').replace(/_/g, ' ')],
            ];
            container.innerHTML = `
                <h2 class="profile-section__title" style="margin-bottom:var(--space-2)">Review</h2>
                <p class="text-muted" style="margin:0 0 var(--space-5)">Please confirm this looks right before continuing.</p>
                <div class="card" style="padding:var(--space-4)">
                    ${rows.map(([label, value]) => `<div class="flex items-center justify-between" style="padding-block:var(--space-2);border-bottom:1px solid var(--border-subtle)"><span class="text-muted">${label}</span><span style="font-weight:var(--font-weight-semibold);text-transform:capitalize">${value}</span></div>`).join('')}
                </div>
            `;
        },
        isComplete: () => true,
        save(next) { return { step: 'longTermGoals', data: state.longTermGoals, nextStep: next }; },
    },
    {
        key: 'consent',
        isVisible: () => true,
        render(container) {
            const c = state.consent;
            const statements = [
                ['statement1', 'I confirm that the information I provided is true.'],
                ['statement2', 'I understand Yoga is not medical treatment.'],
                ['statement3', 'I agree to inform Vipasa if my health changes.'],
            ];
            container.innerHTML = `
                <h2 class="profile-section__title mb-3">Consent</h2>
                <div class="card" style="padding:var(--space-4)">
                    ${statements.map(([key, text]) => `
                        <label class="flex items-center gap-2" style="margin-bottom:var(--space-3)">
                            <input type="checkbox" data-consent="${key}" ${c[key] ? 'checked' : ''}>
                            <span>${text}</span>
                        </label>
                    `).join('')}
                </div>
            `;
            statements.forEach(([key]) => {
                container.querySelector(`[data-consent="${key}"]`).addEventListener('change', (e) => {
                    state.consent[key] = e.target.checked;
                    updateContinueEnabled();
                });
            });
        },
        isComplete: () => state.consent.statement1 && state.consent.statement2 && state.consent.statement3,
        save() { return null; },
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
    const payload = step.save(nextKey);
    if (!payload) return;
    try {
        await apiPost('/api/health-assessment/step', { ...payload, final: isFinal, stage: 5 });
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

    if (isLast) {
        // Final step is Consent — record it explicitly, then mark the
        // profile's last data topic (longTermGoals) as stage 5 complete.
        try {
            let timezone = 'UTC';
            try { timezone = Intl.DateTimeFormat().resolvedOptions().timeZone; } catch { /* fall back to UTC */ }
            await apiPost('/api/consent', { accepted: true, timezone });
            await apiPost('/api/health-assessment/step', { step: 'longTermGoals', data: state.longTermGoals, nextStep: 'consent', final: true, stage: 5 });
        } catch (err) {
            toast.error(err.message || 'Could not save — please check your connection.');
            continueBtn.disabled = false;
            return;
        }
        toast.success('Your profile is 100% complete — thank you!');
        window.location.href = '/dashboard';
        return;
    }

    try {
        await persistCurrentStep(nextKey, false);
    } catch {
        continueBtn.disabled = false;
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
