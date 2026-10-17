<?php

use App\Core\View;

$pageTitle = 'My Profile — Vipasa Yoga';
$pageCss = 'trainer';
$portal = 'trainer';
$active = 'profile';
$specialties = $instructor['specialties'] ?? [];
$certificates = $instructor['certificates'] ?? [];
?>
<div class="container" style="padding-block:var(--space-4) var(--space-8)">

    <div class="trainer-hero-card mb-6">
        <div class="trainer-hero-card__main">
            <div class="trainer-hero-card__avatar">
                <?php if (!empty($instructor['avatar_path'])): ?>
                    <img src="<?= View::e($instructor['avatar_path']) ?>" alt="">
                <?php else: ?>
                    <span class="trainer-hero-card__avatar-fallback"><?= View::e(strtoupper(substr($user['name'] ?? 'T', 0, 2))) ?></span>
                <?php endif; ?>
            </div>
            <div>
                <h1 class="trainer-hero-card__title">My Public Profile</h1>
                <p class="trainer-hero-card__subtitle">Update your profile details, specialties, biography, photo gallery, and certificates.</p>
            </div>
        </div>
    </div>

    <!-- Main Profile Card -->
    <div class="card card-glass mb-6" style="padding:18px;border-radius:var(--radius-xl)">
        
        <!-- Avatar Photo Banner -->
        <div class="trainer-profile-avatar-box">
            <img src="<?= View::e($instructor['avatar_path'] ?: '/assets/img/avatar-placeholder.svg') ?>" alt="" class="trainer-profile-avatar-img">
            <div>
                <h4 style="margin:0 0 2px;font-size:0.875rem;font-weight:700;color:var(--text-primary)"><?= View::e($user['name'] ?? 'Trainer Profile') ?></h4>
                <p style="margin:0 0 6px;font-size:11px;color:var(--text-muted)">JPG, PNG or WEBP. Max size 2MB.</p>
                <label class="btn btn-secondary btn-sm" style="cursor:pointer;font-size:11px;padding:3px 9px">
                    <?= View::icon('image', 'icon', 13) ?>
                    <span>Change Photo</span>
                    <input type="file" id="avatar-input" accept="image/jpeg,image/png,image/webp" style="display:none">
                </label>
            </div>
        </div>

        <form id="profile-form">
            <div class="flex gap-3 flex-wrap">
                <div class="trainer-form-field" style="flex:1;min-width:200px">
                    <label class="trainer-form-label" for="profile-name">Full Name</label>
                    <input type="text" id="profile-name" name="name" class="trainer-form-input" required maxlength="150" value="<?= View::e($user['name'] ?? '') ?>">
                </div>
                <div class="trainer-form-field" style="flex:1;min-width:200px">
                    <label class="trainer-form-label" for="profile-phone">Phone Number</label>
                    <input type="text" id="profile-phone" name="phone" class="trainer-form-input" maxlength="20" value="<?= View::e($user['phone'] ?? '') ?>">
                </div>
            </div>

            <div class="trainer-form-field">
                <label class="trainer-form-label" for="profile-headline">Professional Headline</label>
                <input type="text" id="profile-headline" name="headline" class="trainer-form-input" maxlength="200" value="<?= View::e($instructor['headline'] ?? '') ?>" placeholder="e.g. Senior Hatha & Vinyasa Yoga Specialist">
            </div>

            <div class="trainer-form-field">
                <label class="trainer-form-label" for="profile-bio">Biography</label>
                <textarea id="profile-bio" name="bio" class="trainer-form-textarea" rows="3" placeholder="Share your teaching philosophy and experience..."><?= View::e($instructor['bio'] ?? '') ?></textarea>
            </div>

            <div class="flex gap-3 flex-wrap">
                <div class="trainer-form-field" style="flex:1;min-width:140px">
                    <label class="trainer-form-label" for="profile-exp">Experience (Years)</label>
                    <input type="number" id="profile-exp" name="experience_years" class="trainer-form-input" min="0" value="<?= (int) ($instructor['experience_years'] ?? 0) ?>">
                </div>
                <div class="trainer-form-field" style="flex:1;min-width:160px">
                    <label class="trainer-form-label" for="profile-tz">Timezone</label>
                    <input type="text" id="profile-tz" name="timezone" class="trainer-form-input" required value="<?= View::e($instructor['timezone'] ?? 'Asia/Kolkata') ?>">
                </div>
            </div>

            <div class="trainer-form-field">
                <label class="trainer-form-label" for="profile-specialties">Specialties (Comma Separated)</label>
                <input type="text" id="profile-specialties" name="specialties" class="trainer-form-input" value="<?= View::e(implode(', ', $specialties ?: [])) ?>" placeholder="Hatha Yoga, Vinyasa Flow, Pranayama, Meditation">
            </div>

            <div class="trainer-form-field">
                <label class="trainer-form-label" for="profile-certificates">Certificates (Comma Separated)</label>
                <input type="text" id="profile-certificates" name="certificates" class="trainer-form-input" value="<?= View::e(implode(', ', $certificates ?: [])) ?>" placeholder="RYT-500 Certified, Ashtanga Master">
            </div>

            <div id="profile-form-error" style="font-size:11px;color:var(--color-error);margin-bottom:8px"></div>
            
            <button type="submit" class="btn btn-primary btn-sm flex items-center gap-1">
                <?= View::icon('check', 'icon', 14) ?>
                <span>Save Profile Changes</span>
            </button>
        </form>
    </div>

    <!-- Gallery Section -->
    <div class="trainer-section-block">
        <div class="trainer-section-header">
            <div class="flex items-center gap-2">
                <?= View::icon('image', 'trainer-section-header__icon', 18) ?>
                <h2 class="trainer-section-header__title">My Photo Gallery</h2>
            </div>
            <span class="trainer-count-pill"><?= count($gallery) ?></span>
        </div>
        <div class="card card-glass mb-6" style="padding:14px;border-radius:var(--radius-xl)">
            <?php if (empty($gallery)): ?>
                <p class="text-muted mb-3" style="font-size:0.75rem">No gallery photos uploaded yet.</p>
            <?php else: ?>
                <div class="trainer-gallery mb-3" id="trainer-gallery">
                    <?php foreach ($gallery as $image): ?>
                    <div class="trainer-gallery__item" data-id="<?= (int) $image['id'] ?>">
                        <img src="<?= View::e($image['file_path']) ?>" alt="">
                        <?php if (\App\Core\Auth::hasRole('admin')): ?>
                        <button type="button" class="btn btn-ghost btn-sm trainer-gallery__remove delete-gallery-btn" data-id="<?= (int) $image['id'] ?>" title="Remove photo">&times;</button>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <label class="btn btn-secondary btn-sm" style="cursor:pointer;font-size:11px;padding:3px 9px">
                <?= View::icon('plus', 'icon', 13) ?>
                <span>Add Gallery Photo</span>
                <input type="file" id="gallery-input" accept="image/jpeg,image/png,image/webp" style="display:none">
            </label>
        </div>
    </div>

    <!-- Certificates Section -->
    <div class="trainer-section-block">
        <div class="trainer-section-header">
            <div class="flex items-center gap-2">
                <?= View::icon('certificate', 'trainer-section-header__icon', 18) ?>
                <h2 class="trainer-section-header__title">Certificate Documents</h2>
            </div>
            <span class="trainer-count-pill"><?= count($certificateFiles) ?></span>
        </div>
        <div class="card card-glass" style="padding:14px;border-radius:var(--radius-xl)">
            <?php if (empty($certificateFiles)): ?>
            <p class="text-muted mb-3" style="font-size:0.75rem">No certificate documents uploaded yet.</p>
            <?php else: ?>
            <div class="flex flex-col gap-2 mb-3" id="certificate-files-list">
                <?php foreach ($certificateFiles as $file): ?>
                <div class="flex items-center justify-between" style="padding:5px 0;border-bottom:1px solid var(--border-subtle);font-size:0.78rem" data-id="<?= (int) $file['id'] ?>">
                    <div class="flex items-center gap-2">
                        <?= View::icon('file-text', 'icon', 14) ?>
                        <a href="<?= View::e($file['file_path']) ?>" target="_blank" rel="noopener" style="color:var(--color-primary);font-weight:600"><?= View::e($file['title']) ?></a>
                    </div>
                    <?php if (\App\Core\Auth::hasRole('admin')): ?>
                    <button type="button" class="btn btn-ghost btn-sm delete-certificate-btn" data-id="<?= (int) $file['id'] ?>" style="font-size:10.5px;padding:2px 6px">Remove</button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form id="certificate-form" class="flex gap-2 flex-wrap items-end mt-2">
                <div class="trainer-form-field" style="margin:0;flex:1;min-width:180px">
                    <label class="trainer-form-label" for="cert-title">Document Title</label>
                    <input type="text" id="cert-title" name="title" class="trainer-form-input" placeholder="e.g. 500-hr Master Certification" maxlength="200">
                </div>
                <div style="margin:0">
                    <label class="trainer-form-label">File</label>
                    <input type="file" name="file" accept="image/jpeg,image/png,image/webp,application/pdf" required style="font-size:0.75rem">
                </div>
                <button type="submit" class="btn btn-secondary btn-sm flex items-center gap-1">
                    <?= View::icon('download', 'icon', 13) ?>
                    <span>Upload Document</span>
                </button>
            </form>
        </div>
    </div>
</div>
<script type="module">
import { apiPut, apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

document.getElementById('profile-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.target;
    const errorBox = document.getElementById('profile-form-error');
    errorBox.textContent = '';

    const payload = {
        name: form.name.value.trim(),
        phone: form.phone.value.trim(),
        headline: form.headline.value.trim(),
        bio: form.bio.value.trim(),
        experience_years: Number(form.experience_years.value || 0),
        timezone: form.timezone.value.trim(),
        specialties: form.specialties.value.trim(),
        certificates: form.certificates.value.trim(),
    };

    try {
        await apiPut('/trainer/profile', payload);
        toast.success('Profile saved successfully.');
    } catch (err) {
        errorBox.textContent = err.message;
        toast.error(err.message);
    }
});

async function uploadFile(url, fieldName, file) {
    const formData = new FormData();
    formData.append(fieldName, file);
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    const response = await fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'X-CSRF-Token': csrf },
        body: formData,
    });
    const data = await response.json();
    if (!response.ok) throw new Error(data.message || 'Upload failed.');
    return data;
}

document.getElementById('avatar-input').addEventListener('change', async (event) => {
    const file = event.target.files[0];
    if (!file) return;
    try {
        await uploadFile('/trainer/profile/avatar', 'avatar', file);
        window.location.reload();
    } catch (err) {
        toast.error(err.message);
    }
});

document.getElementById('gallery-input').addEventListener('change', async (event) => {
    const file = event.target.files[0];
    if (!file) return;
    try {
        await uploadFile('/trainer/profile/gallery', 'image', file);
        window.location.reload();
    } catch (err) {
        toast.error(err.message);
    }
});

document.querySelectorAll('.delete-gallery-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        try {
            await apiPost(`/trainer/profile/gallery/${btn.dataset.id}/delete`, {});
            btn.closest('[data-id]').remove();
        } catch (err) {
            toast.error(err.message);
        }
    });
});

document.getElementById('certificate-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    try {
        const response = await fetch('/trainer/profile/certificates', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-CSRF-Token': csrf },
            body: formData,
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Upload failed.');
        window.location.reload();
    } catch (err) {
        toast.error(err.message);
    }
});

document.querySelectorAll('.delete-certificate-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        try {
            await apiPost(`/trainer/profile/certificates/${btn.dataset.id}/delete`, {});
            btn.closest('[data-id]').remove();
        } catch (err) {
            toast.error(err.message);
        }
    });
});
</script>
