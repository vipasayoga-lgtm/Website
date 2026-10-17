<?php

use App\Core\View;

$pageTitle = 'Get started — Vipasa Yoga';
$pageCss = 'onboarding';

$genders = [
    'female' => 'Female',
    'male' => 'Male',
    'non_binary' => 'Non-binary',
    'prefer_not_to_say' => 'Prefer not to say',
];
$categories = [
    'child' => 'Child',
    'teenager' => 'Teenager',
    'adult' => 'Adult',
    'senior_citizen' => 'Senior Citizen',
    'pregnant_woman' => 'Pregnant Woman',
    'postpartum_mother' => 'Postpartum Mother',
];
$relationships = ['Parent', 'Spouse', 'Sibling', 'Child', 'Grandmother', 'Grandfather', 'Friend', 'Other'];
?>
<div class="get-started-page">
    <div class="onboarding-panel get-started-card">
        <a href="/" class="get-started-card__back"><?= View::icon('arrow-left', 'icon', 16) ?> Back to Home</a>
        <div class="get-started-card__header">
            <div class="splash__wordmark" style="color:var(--color-primary);font-size:var(--font-size-lg)">Vipasa Yoga</div>
            <h1 class="get-started-card__title">Let's get you set up</h1>
            <p class="get-started-card__subtitle">Just a few quick details — under a minute, then straight to your dashboard.</p>
        </div>

        <form id="get-started-form">
            <div class="get-started-form__grid">
                <div class="form-group">
                    <input type="tel" id="phone" name="phone" class="form-group__control" placeholder=" " value="<?= View::e($user['phone'] ?? '') ?>" <?= !empty($user['phone']) ? 'disabled' : '' ?> required>
                    <label class="form-group__label" for="phone">Mobile number</label>
                    <div class="form-group__hint"></div>
                    <div class="form-group__error"></div>
                </div>
                <div class="form-group">
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-group__control" placeholder=" " value="<?= View::e($profile['date_of_birth'] ?? '') ?>" <?= !empty($profile['date_of_birth']) ? 'disabled' : '' ?> required>
                    <label class="form-group__label" for="date_of_birth">Date of birth</label>
                    <div class="form-group__hint" id="age-hint"></div>
                    <div class="form-group__error"></div>
                </div>
                <div class="form-group">
                    <input type="text" id="city" name="city" class="form-group__control" placeholder=" " maxlength="100" value="<?= View::e($profile['city'] ?? '') ?>">
                    <label class="form-group__label" for="city">City</label>
                    <div class="form-group__hint"></div>
                    <div class="form-group__error"></div>
                </div>
                <div class="form-group">
                    <input type="number" id="height_cm" name="height_cm" class="form-group__control" placeholder=" " min="30" max="250" value="<?= View::e((string) ($profile['height_cm'] ?? '')) ?>">
                    <label class="form-group__label" for="height_cm">Height (cm)</label>
                    <div class="form-group__hint"></div>
                    <div class="form-group__error"></div>
                </div>
                <div class="form-group">
                    <input type="number" id="weight_kg" name="weight_kg" class="form-group__control" placeholder=" " min="10" max="300" value="<?= View::e((string) ($profile['weight_kg'] ?? '')) ?>">
                    <label class="form-group__label" for="weight_kg">Weight (kg)</label>
                    <div class="form-group__hint"></div>
                    <div class="form-group__error"></div>
                </div>

                <div class="form-section__full">
                    <label class="form-section__label">Gender</label>
                    <div class="option-grid option-grid--4">
                        <?php foreach ($genders as $value => $label): ?>
                        <label class="option-card">
                            <input type="radio" name="gender" value="<?= View::e($value) ?>" class="sr-only" <?= ($profile['gender'] ?? '') === $value ? 'checked' : '' ?>>
                            <span class="option-card__title"><?= View::e($label) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-section__full">
                    <label class="form-section__label">Which best describes you?</label>
                    <div class="option-grid option-grid--6">
                        <?php foreach ($categories as $value => $label): ?>
                        <label class="option-card">
                            <input type="radio" name="client_category" value="<?= View::e($value) ?>" class="sr-only" <?= ($profile['client_category'] ?? '') === $value ? 'checked' : '' ?>>
                            <span class="option-card__title"><?= View::e($label) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-section__full get-started-card__divider">
                    <label class="form-section__label">Emergency contact</label>
                </div>
                <div class="form-group">
                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" class="form-group__control" placeholder=" " maxlength="150" value="<?= View::e($profile['emergency_contact_name'] ?? '') ?>">
                    <label class="form-group__label" for="emergency_contact_name">Contact name</label>
                    <div class="form-group__hint"></div>
                    <div class="form-group__error"></div>
                </div>
                <div class="form-group">
                    <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" class="form-group__control" placeholder=" " value="<?= View::e($profile['emergency_contact_phone'] ?? '') ?>">
                    <label class="form-group__label" for="emergency_contact_phone">Contact phone</label>
                    <div class="form-group__hint"></div>
                    <div class="form-group__error"></div>
                </div>
                <div class="form-group">
                    <select id="emergency_contact_relationship" name="emergency_contact_relationship" class="form-group__control select-control">
                        <option value="">Select relationship</option>
                        <?php foreach ($relationships as $r): ?>
                        <option value="<?= View::e($r) ?>" <?= ($profile['emergency_contact_relationship'] ?? '') === $r ? 'selected' : '' ?>><?= View::e($r) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label class="form-group__label form-group__label--select" for="emergency_contact_relationship">Relationship</label>
                </div>
            </div>

            <div class="onboarding-nav">
                <span></span>
                <button type="submit" class="btn btn-primary btn-lg" id="save-btn">Continue to Dashboard <?= View::icon('arrow-right', 'icon', 16) ?></button>
            </div>
        </form>
    </div>
</div>
<script type="module">
import { apiPut } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';
import { showFieldError, clearFieldError } from '/assets/js/modules/validation.js';

document.querySelectorAll('.option-card').forEach((card) => {
    const input = card.querySelector('input');
    const sync = () => {
        document.querySelectorAll(`input[name="${input.name}"]`).forEach((sibling) => {
            sibling.closest('.option-card').classList.toggle('is-selected', sibling.checked);
        });
    };
    input.addEventListener('change', sync);
    sync();
});

let timezone = 'UTC';
try {
    timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
} catch {
    // ignore — fall back to UTC
}

const dobInput = document.getElementById('date_of_birth');
const ageHint = document.getElementById('age-hint');
const updateAgeHint = () => {
    if (!dobInput.value) { ageHint.textContent = ''; return; }
    const dob = new Date(dobInput.value);
    if (Number.isNaN(dob.getTime())) { ageHint.textContent = ''; return; }
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const hasHadBirthdayThisYear = (today.getMonth() > dob.getMonth()) || (today.getMonth() === dob.getMonth() && today.getDate() >= dob.getDate());
    if (!hasHadBirthdayThisYear) age--;
    ageHint.textContent = age >= 0 ? `Age: ${age}` : '';
};
dobInput.addEventListener('input', updateAgeHint);
updateAgeHint();

const form = document.getElementById('get-started-form');
const saveBtn = document.getElementById('save-btn');
const phoneInput = document.getElementById('phone');
const errorTargets = [phoneInput, dobInput, document.getElementById('emergency_contact_name'), document.getElementById('emergency_contact_phone')];

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    errorTargets.forEach(clearFieldError);

    const selectedGender = form.querySelector('input[name="gender"]:checked');
    const selectedCategory = form.querySelector('input[name="client_category"]:checked');

    saveBtn.classList.add('is-loading');
    saveBtn.disabled = true;

    try {
        const result = await apiPut('/api/profile', {
            name: <?= json_encode($user['name'] ?? '') ?>,
            phone: phoneInput.value.trim(),
            date_of_birth: dobInput.value,
            height_cm: document.getElementById('height_cm').value,
            weight_kg: document.getElementById('weight_kg').value,
            gender: selectedGender ? selectedGender.value : '',
            city: document.getElementById('city').value.trim(),
            client_category: selectedCategory ? selectedCategory.value : '',
            emergency_contact_name: document.getElementById('emergency_contact_name').value.trim(),
            emergency_contact_phone: document.getElementById('emergency_contact_phone').value.trim(),
            emergency_contact_relationship: document.getElementById('emergency_contact_relationship').value,
            timezone,
            complete: true,
        });
        window.location.href = result.data.redirect;
    } catch (err) {
        if (err.errors) {
            Object.entries(err.errors).forEach(([field, messages]) => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) showFieldError(input, messages[0]);
            });
        }
        toast.error(err.message);
        saveBtn.classList.remove('is-loading');
        saveBtn.disabled = false;
    }
});
</script>
