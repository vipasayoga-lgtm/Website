<?php

use App\Core\View;

$pageTitle = 'Your profile — Vipasa Yoga';
$pageCss = 'onboarding';
$portal = 'client';
$active = 'profile';

$genders = [
    'female' => 'Female',
    'male' => 'Male',
    'non_binary' => 'Non-binary',
    'prefer_not_to_say' => 'Prefer not to say',
];
$timezones = [
    'UTC', 'America/New_York', 'America/Chicago', 'America/Denver', 'America/Los_Angeles',
    'America/Sao_Paulo', 'Europe/London', 'Europe/Paris', 'Europe/Berlin', 'Europe/Moscow',
    'Africa/Cairo', 'Africa/Johannesburg', 'Asia/Dubai', 'Asia/Kolkata', 'Asia/Bangkok',
    'Asia/Shanghai', 'Asia/Tokyo', 'Asia/Singapore', 'Australia/Sydney', 'Pacific/Auckland',
];
$currentTimezone = $profile['timezone'] ?? null;
$initials = mb_strtoupper(mb_substr($user['name'] ?? 'Y', 0, 1));
$isGoogleOnly = !empty($user['google_id']) && empty($user['password_hash']);
?>
<div class="container" style="padding-block:var(--space-4) var(--space-8)">
    <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
        <div class="services-hero" style="margin:0;text-align:left">
            <h1 class="services-hero__title" style="margin:0;font-size:1.5rem;font-weight:var(--font-weight-bold)">Your Profile</h1>
            <p class="services-hero__subtitle" style="margin:4px 0 0;font-size:0.875rem;color:var(--text-muted)">Manage your personal details, health preferences, and account security.</p>
        </div>
    </div>
    <div>
        <div class="onboarding-panel onboarding-panel--profile" style="border-radius:var(--radius-2xl);box-shadow:0 10px 30px -5px rgba(15, 51, 73, 0.08);background:var(--surface-card)">
            <!-- Profile Header -->
            <div class="profile-header" style="display:flex;align-items:center;gap:var(--space-4);padding-bottom:var(--space-5);border-bottom:1px solid var(--border-subtle);margin-bottom:var(--space-5)">
                <div class="avatar avatar-xl" id="avatar-preview" style="background:var(--vipasa-blue-50);color:var(--vipasa-blue-700);font-weight:var(--font-weight-bold);font-size:1.5rem;border-radius:var(--radius-full);width:4.5rem;height:4.5rem;display:grid;place-items:center;box-shadow:0 4px 14px rgba(0,0,0,0.1)">
                    <?php if (!empty($profile['avatar_path'])): ?>
                    <img src="<?= View::e($profile['avatar_path']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius-full)">
                    <?php else: ?>
                    <span><?= View::e($initials) ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="profile-header__name" style="font-size:1.2rem;font-weight:var(--font-weight-bold);margin:0"><?= View::e($user['name'] ?? 'Your Profile') ?></p>
                    <p class="profile-header__email" style="font-size:0.85rem;color:var(--text-muted);margin:2px 0 var(--space-2)"><?= View::e($user['email'] ?? '') ?></p>
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn btn-secondary btn-xs" id="avatar-upload-btn" style="border-radius:var(--radius-md)">Upload photo</button>
                        <input type="file" id="avatar-input" accept="image/jpeg,image/png,image/webp" hidden>
                        <span class="text-muted" style="font-size:0.75rem">JPG, PNG, WEBP (Max 2MB)</span>
                    </div>
                </div>
            </div>

            <form id="profile-form">
                <!-- Basic Information -->
                <div class="profile-section">
                    <div class="profile-section__header" style="display:flex;align-items:center;gap:var(--space-2);margin-bottom:var(--space-4)">
                        <?= View::icon('user') ?>
                        <h2 class="profile-section__title" style="font-size:1.05rem;font-weight:var(--font-weight-bold);margin:0">Basic Information</h2>
                    </div>
                    <div class="form-section__grid">
                        <div class="form-group">
                            <input type="text" id="name" name="name" class="form-group__control" placeholder=" " required minlength="2" maxlength="150" value="<?= View::e($user['name'] ?? '') ?>">
                            <label class="form-group__label" for="name">Full name</label>
                            <div class="form-group__error"></div>
                        </div>
                        <div class="form-group">
                            <input type="email" id="email" class="form-group__control" placeholder=" " value="<?= View::e($user['email'] ?? '') ?>" disabled style="opacity:0.75;background:var(--surface-bg-alt)">
                            <label class="form-group__label" for="email">Email address</label>
                        </div>
                        <div class="form-group">
                            <input type="tel" id="phone" name="phone" class="form-group__control" placeholder=" " value="<?= View::e($user['phone'] ?? '') ?>">
                            <label class="form-group__label" for="phone">Phone number</label>
                            <div class="form-group__error"></div>
                        </div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="profile-section" style="margin-top:var(--space-6)">
                    <div class="profile-section__header" style="display:flex;align-items:center;gap:var(--space-2);margin-bottom:var(--space-4)">
                        <?= View::icon('map-pin') ?>
                        <h2 class="profile-section__title" style="font-size:1.05rem;font-weight:var(--font-weight-bold);margin:0">Personal Details</h2>
                    </div>
                    <div class="form-section__grid">
                        <div class="form-group">
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-group__control" placeholder=" " value="<?= View::e($profile['date_of_birth'] ?? '') ?>">
                            <label class="form-group__label" for="date_of_birth">Date of birth</label>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-group__control" placeholder=" " value="<?= View::e((string) ($profile['age'] ?? '')) ?>" disabled style="opacity:0.75;background:var(--surface-bg-alt)">
                            <label class="form-group__label">Age (years)</label>
                        </div>
                        <div class="form-group">
                            <input type="number" id="height_cm" name="height_cm" class="form-group__control" placeholder=" " min="30" max="250" value="<?= View::e((string) ($profile['height_cm'] ?? '')) ?>">
                            <label class="form-group__label" for="height_cm">Height (cm)</label>
                            <div class="form-group__error"></div>
                        </div>
                        <div class="form-group">
                            <input type="number" id="weight_kg" name="weight_kg" class="form-group__control" placeholder=" " min="10" max="300" value="<?= View::e((string) ($profile['weight_kg'] ?? '')) ?>">
                            <label class="form-group__label" for="weight_kg">Weight (kg)</label>
                            <div class="form-group__error"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" id="country" name="country" class="form-group__control" placeholder=" " maxlength="100" value="<?= View::e($profile['country'] ?? '') ?>">
                            <label class="form-group__label" for="country">Country</label>
                            <div class="form-group__error"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" id="city" name="city" class="form-group__control" placeholder=" " maxlength="100" value="<?= View::e($profile['city'] ?? '') ?>">
                            <label class="form-group__label" for="city">City</label>
                            <div class="form-group__error"></div>
                        </div>
                        <div class="form-section__full">
                            <label style="font-size:0.85rem;font-weight:var(--font-weight-semibold);color:var(--text-primary);margin-bottom:var(--space-2);display:block">Gender</label>
                            <div class="option-grid">
                                <?php foreach ($genders as $value => $label): ?>
                                <label class="option-card" style="border-radius:var(--radius-lg)">
                                    <input type="radio" name="gender" value="<?= View::e($value) ?>" class="sr-only" <?= ($profile['gender'] ?? '') === $value ? 'checked' : '' ?>>
                                    <span class="option-card__title"><?= View::e($label) ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="profile-section" style="margin-top:var(--space-6)">
                    <div class="profile-section__header" style="display:flex;align-items:center;gap:var(--space-2);margin-bottom:var(--space-4)">
                        <?= View::icon('sliders') ?>
                        <h2 class="profile-section__title" style="font-size:1.05rem;font-weight:var(--font-weight-bold);margin:0">Preferences & Bio</h2>
                    </div>
                    <div class="form-section__grid">
                        <div class="form-section__full">
                            <label for="timezone" style="font-size:0.85rem;font-weight:var(--font-weight-semibold);margin-bottom:4px;display:block">Timezone</label>
                            <select id="timezone" name="timezone" class="form-group__control" required style="border-radius:var(--radius-lg)">
                                <?php foreach ($timezones as $tz): ?>
                                <option value="<?= View::e($tz) ?>" <?= $currentTimezone === $tz ? 'selected' : '' ?>><?= View::e($tz) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-section__full">
                            <label for="bio" style="font-size:0.85rem;font-weight:var(--font-weight-semibold);margin-bottom:4px;display:block">Short Bio</label>
                            <textarea id="bio" name="bio" class="form-group__control" maxlength="200" rows="3" placeholder="A sentence or two about yourself and your yoga goals..." style="border-radius:var(--radius-lg)"><?= View::e($profile['bio'] ?? '') ?></textarea>
                            <div class="form-group__hint" style="font-size:0.75rem;margin-top:4px"><span id="bio-count">0</span>/200 characters</div>
                        </div>
                    </div>
                </div>

                <!-- Health Notes -->
                <div class="profile-section" style="margin-top:var(--space-6)">
                    <div class="profile-section__header" style="display:flex;align-items:center;gap:var(--space-2);margin-bottom:var(--space-4)">
                        <?= View::icon('heart') ?>
                        <h2 class="profile-section__title" style="font-size:1.05rem;font-weight:var(--font-weight-bold);margin:0">Health Notes</h2>
                    </div>
                    <div class="form-section__grid">
                        <div class="form-section__full">
                            <label for="medical_notes" style="font-size:0.85rem;font-weight:var(--font-weight-semibold);margin-bottom:4px;display:block">Medical & Health Notes <span class="text-muted" style="font-weight:normal">(Private, shared only with your booked trainer)</span></label>
                            <textarea id="medical_notes" name="medical_notes" class="form-group__control" maxlength="2000" rows="3" placeholder="Injuries, medical conditions, or special accommodations your instructor should be aware of..." style="border-radius:var(--radius-lg)"><?= View::e($profile['medical_notes'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="profile-section" style="margin-top:var(--space-6)">
                    <div class="profile-section__header" style="display:flex;align-items:center;gap:var(--space-2);margin-bottom:var(--space-4)">
                        <?= View::icon('phone') ?>
                        <h2 class="profile-section__title" style="font-size:1.05rem;font-weight:var(--font-weight-bold);margin:0">Emergency Contact</h2>
                    </div>
                    <div class="form-section__grid">
                        <div class="form-group">
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name" class="form-group__control" placeholder=" " maxlength="150" value="<?= View::e($profile['emergency_contact_name'] ?? '') ?>">
                            <label class="form-group__label" for="emergency_contact_name">Contact name</label>
                            <div class="form-group__error"></div>
                        </div>
                        <div class="form-group">
                            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" class="form-group__control" placeholder=" " value="<?= View::e($profile['emergency_contact_phone'] ?? '') ?>">
                            <label class="form-group__label" for="emergency_contact_phone">Contact phone</label>
                            <div class="form-group__error"></div>
                        </div>
                        <div class="form-section__full">
                            <label for="emergency_contact_relationship" style="font-size:0.85rem;font-weight:var(--font-weight-semibold);margin-bottom:4px;display:block">Relationship</label>
                            <select id="emergency_contact_relationship" name="emergency_contact_relationship" class="form-group__control" style="border-radius:var(--radius-lg)">
                                <option value="">Select one...</option>
                                <?php foreach (['Parent', 'Spouse', 'Sibling', 'Child', 'Friend', 'Other'] as $r): ?>
                                <option value="<?= View::e($r) ?>" <?= ($profile['emergency_contact_relationship'] ?? '') === $r ? 'selected' : '' ?>><?= View::e($r) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="onboarding-nav" style="margin-top:var(--space-8);display:flex;justify-content:flex-end">
                    <button type="submit" class="btn btn-primary" id="save-btn" style="padding:0.65rem 1.75rem;border-radius:var(--radius-lg)">Save Profile Details</button>
                </div>
            </form>
    </div>
</div>
<script type="module">
import { apiPut, apiPost } from '/assets/js/modules/api.js';
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

const timezoneSelect = document.getElementById('timezone');
if (!<?= json_encode($currentTimezone !== null) ?>) {
    try {
        const detected = Intl.DateTimeFormat().resolvedOptions().timeZone;
        if ([...timezoneSelect.options].some((opt) => opt.value === detected)) {
            timezoneSelect.value = detected;
        }
    } catch {
        // ignore — fall back to the default selected option
    }
}

const bioInput = document.getElementById('bio');
const bioCount = document.getElementById('bio-count');
const updateBioCount = () => { bioCount.textContent = String(bioInput.value.length); };
bioInput.addEventListener('input', updateBioCount);
updateBioCount();

const avatarBtn = document.getElementById('avatar-upload-btn');
const avatarInput = document.getElementById('avatar-input');
const avatarPreview = document.getElementById('avatar-preview');

avatarBtn.addEventListener('click', () => avatarInput.click());
avatarInput.addEventListener('change', async () => {
    const file = avatarInput.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('avatar', file);

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const response = await fetch('/api/profile/avatar', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: { 'X-CSRF-Token': csrfToken },
        });
        const result = await response.json();
        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Upload failed.');
        }
        avatarPreview.innerHTML = `<img src="${result.data.avatar_path}" alt="">`;
        toast.success('Photo updated.');
    } catch (err) {
        toast.error(err.message);
    }
});

const form = document.getElementById('profile-form');
const saveBtn = document.getElementById('save-btn');
const nameInput = document.getElementById('name');
const phoneInput = document.getElementById('phone');
const emergencyNameInput = document.getElementById('emergency_contact_name');
const emergencyPhoneInput = document.getElementById('emergency_contact_phone');

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    [nameInput, phoneInput, emergencyNameInput, emergencyPhoneInput].forEach(clearFieldError);
    saveBtn.classList.add('is-loading');
    saveBtn.disabled = true;

    const selectedGender = form.querySelector('input[name="gender"]:checked');

    try {
        const result = await apiPut('/api/profile', {
            name: nameInput.value.trim(),
            phone: phoneInput.value.trim(),
            height_cm: document.getElementById('height_cm').value,
            weight_kg: document.getElementById('weight_kg').value,
            gender: selectedGender ? selectedGender.value : '',
            country: document.getElementById('country').value.trim(),
            city: document.getElementById('city').value.trim(),
            timezone: timezoneSelect.value,
            bio: bioInput.value.trim(),
            medical_notes: document.getElementById('medical_notes').value.trim(),
            emergency_contact_name: emergencyNameInput.value.trim(),
            emergency_contact_phone: emergencyPhoneInput.value.trim(),
            emergency_contact_relationship: document.getElementById('emergency_contact_relationship').value,
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
