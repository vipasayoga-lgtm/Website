<?php

use App\Core\View;

$pageTitle = 'Manage Session — ' . View::e($client['name'] ?? 'Student') . ' — Vipasa Yoga';
$pageCss = 'trainer';
$portal = 'trainer';
$active = 'bookings';

$dateLabel = date('l, d M Y', strtotime($booking['slot_date']));
$timeLabel = date('g:i A', strtotime($booking['start_time'])) . ' – ' . date('g:i A', strtotime($booking['end_time']));

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
$hasAnyAlert = !empty($safety['medicalAlerts']) || !empty($safety['painAreas']) || !empty($safety['restrictions']['hasRestriction']) || !empty($safety['restrictions']['recentFalls']) || !empty($safety['medication']['takingMedication']) || !empty($screening['hasAllergies']) || !empty($screening['advisedNotToExercise']);
?>

<style>
.session-manage-wrap {
    padding-block: var(--space-4) var(--space-8);
    font-size: 0.8125rem;
}
.session-header-bar {
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
    margin-bottom: var(--space-5);
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
.attendance-toggle {
    display: inline-flex;
    gap: 8px;
}
.attendance-btn {
    padding: 6px 16px;
    border-radius: var(--radius-lg);
    font-size: 0.8125rem;
    font-weight: 600;
    border: 1px solid var(--border-subtle);
    background: var(--surface-bg);
    cursor: pointer;
    transition: all 0.15s ease;
}
.attendance-btn.is-active[data-value="present"] {
    background: #ECFDF5;
    color: #047857;
    border-color: #A7F3D0;
}
.attendance-btn.is-active[data-value="absent"] {
    background: #FEF2F2;
    color: #B91C1C;
    border-color: #FCA5A5;
}
.alert-card-sm {
    background: #FFFBEB;
    border: 1px solid #FCD34D;
    border-left: 4px solid #F59E0B;
    border-radius: var(--radius-xl);
    padding: 12px 16px;
    margin-bottom: var(--space-5);
    font-size: 0.8125rem;
}
</style>

<div class="container session-manage-wrap">

    <!-- Header Action Bar -->
    <div class="session-header-bar">
        <div class="flex items-center gap-3">
            <a href="/trainer/bookings" class="btn btn-ghost btn-xs flex items-center gap-1" title="Back to Bookings">
                &larr; Back to Bookings
            </a>
            <div>
                <h1 style="font-size:1.3rem;font-weight:700;margin:0;color:var(--text-primary)"><?= View::e($client['name'] ?? 'Student Session') ?></h1>
                <div class="text-muted" style="font-size:0.8rem;margin-top:2px">
                    <?= View::e($package['name'] ?? 'Session') ?> · 
                    <strong style="color:var(--color-primary)"><?= View::e($dateLabel) ?> at <?= View::e($timeLabel) ?></strong>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <?php if ($booking['status'] === 'confirmed'): ?>
            <button type="button" class="btn btn-sm" id="complete-session-btn" style="background:#059669;color:#fff;font-weight:600;border:none;box-shadow:0 4px 12px rgba(5,150,105,0.25);border-radius:var(--radius-lg);padding:7px 16px">
                ✓ Mark Session Finished
            </button>
            <?php elseif ($booking['status'] === 'completed'): ?>
            <span class="badge badge-completed" style="padding:6px 14px;border-radius:20px;font-size:0.85rem">✓ Session Completed</span>
            <?php endif; ?>
            <a href="/trainer/students/<?= (int) $booking['client_id'] ?>" class="btn btn-secondary btn-sm flex items-center gap-1">
                <?= View::icon('user', 'icon', 14) ?>
                <span>View Full Student Assessment</span>
            </a>
        </div>
    </div>

    <!-- Critical Medical Alerts Banner (Compact UI) -->
    <?php if ($hasAnyAlert): ?>
    <div class="alert-card-sm">
        <div style="font-weight:700;color:#92400E;margin-bottom:4px" class="flex items-center gap-2">
            <?= View::icon('shield', 'icon', 15) ?>
            Medical Alerts &amp; Health Restrictions
        </div>
        <div style="color:#78350F;line-height:1.4">
            <?php if (!empty($safety['medicalAlerts'])): ?>
            <div>• Conditions: <strong><?= View::e($fmt(array_column($safety['medicalAlerts'], 'condition'))) ?></strong></div>
            <?php endif; ?>
            <?php if (!empty($safety['painAreas'])): ?>
            <div>• Pain Areas: <strong><?= View::e($fmt(array_column($safety['painAreas'], 'area'))) ?></strong></div>
            <?php endif; ?>
            <?php if (!empty($safety['restrictions']['hasRestriction'])): ?>
            <div>• Doctor Restriction: <strong><?= View::e($safety['restrictions']['notes'] ?: 'Yes') ?></strong></div>
            <?php endif; ?>
            <?php if (!empty($safety['medication']['takingMedication'])): ?>
            <div>• On Medication: <strong><?= View::e($safety['medication']['list'] ?: 'Yes') ?></strong></div>
            <?php endif; ?>
            <?php if (!empty($screening['hasAllergies'])): ?>
            <div>• Allergies: <strong><?= View::e($screening['hasAllergiesNotes'] ?: 'Yes') ?></strong></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Session Video Meeting Link -->
    <div class="compact-card">
        <h3 class="compact-card__title">
            <?= View::icon('video', 'icon', 16) ?>
            Session Video Meeting Link
        </h3>
        <p class="text-muted" style="margin:0 0 12px 0;font-size:0.8rem">Provide a Zoom or Google Meet URL for your student. This link will be displayed on their user dashboard so they can click and join the session.</p>

        <form id="meeting-link-form" class="flex flex-col gap-3">
            <div class="flex gap-3 flex-wrap items-end">
                <div class="form-group" style="margin:0;min-width:140px">
                    <select name="provider" class="form-group__control" style="font-size:0.8125rem">
                        <option value="google_meet" <?= ($meetingLink['provider'] ?? '') === 'google_meet' ? 'selected' : '' ?>>Google Meet</option>
                        <option value="zoom" <?= ($meetingLink['provider'] ?? '') === 'zoom' ? 'selected' : '' ?>>Zoom</option>
                    </select>
                    <label class="form-group__label">Provider</label>
                </div>
                <div class="form-group" style="margin:0;flex:1;min-width:240px">
                    <input type="url" name="url" class="form-group__control" placeholder="https://meet.google.com/xyz-abc or https://zoom.us/j/123" value="<?= View::e($meetingLink['url'] ?? '') ?>" required style="font-size:0.8125rem">
                    <label class="form-group__label">Meeting URL</label>
                </div>
                <button type="submit" class="btn btn-primary btn-sm" id="save-meeting-link-btn" style="border-radius:var(--radius-lg)">Save Link &amp; Notify Student</button>
            </div>
            <?php if (!empty($meetingLink['url'])): ?>
            <div class="flex items-center gap-2 mt-1" style="font-size:0.8rem;color:#047857">
                <?= View::icon('check', 'icon', 15) ?>
                <strong>Active Meeting Link:</strong> 
                <a href="<?= View::e($meetingLink['url']) ?>" target="_blank" rel="noopener" style="text-decoration:underline;font-family:monospace"><?= View::e($meetingLink['url']) ?></a>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Session Notes & Attendance -->
    <div class="compact-card">
        <h3 class="compact-card__title">
            <?= View::icon('file-text', 'icon', 16) ?>
            Session Notes, Attendance &amp; Student Rating
        </h3>

        <form id="session-form" class="flex flex-col gap-3">
            <div style="margin-bottom:4px">
                <label style="display:block;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);margin-bottom:6px">Attendance</label>
                <div class="attendance-toggle">
                    <label class="attendance-btn <?= ($feedback['attendance'] ?? '') === 'present' ? 'is-active' : '' ?>" data-value="present">
                        <input type="radio" name="attendance" value="present" class="sr-only" <?= ($feedback['attendance'] ?? '') === 'present' ? 'checked' : '' ?>>
                        ✓ Present
                    </label>
                    <label class="attendance-btn <?= ($feedback['attendance'] ?? '') === 'absent' ? 'is-active' : '' ?>" data-value="absent">
                        <input type="radio" name="attendance" value="absent" class="sr-only" <?= ($feedback['attendance'] ?? '') === 'absent' ? 'checked' : '' ?>>
                        ✕ Absent
                    </label>
                </div>
            </div>

            <div class="form-group" style="margin:0">
                <textarea name="session_notes" class="form-group__control" placeholder=" " rows="2" style="font-size:0.8125rem"><?= View::e($feedback['session_notes'] ?? '') ?></textarea>
                <label class="form-group__label">Session notes (private for you)</label>
            </div>

            <div class="form-group" style="margin:0">
                <textarea name="recommendation" class="form-group__control" placeholder=" " rows="2" style="font-size:0.8125rem"><?= View::e($feedback['recommendation'] ?? '') ?></textarea>
                <label class="form-group__label">Recommendations for student</label>
            </div>

            <div class="form-group" style="margin:0">
                <textarea name="homework" class="form-group__control" placeholder=" " rows="2" style="font-size:0.8125rem"><?= View::e($feedback['homework'] ?? '') ?></textarea>
                <label class="form-group__label">Homework / practice for next time</label>
            </div>

            <div class="flex gap-3 flex-wrap items-end" style="margin-top:2px">
                <div class="form-group" style="margin:0;min-width:160px">
                    <select name="rating" class="form-group__control" style="font-size:0.8125rem">
                        <option value="">No rating</option>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                        <option value="<?= $i ?>" <?= (int) ($feedback['rating'] ?? 0) === $i ? 'selected' : '' ?>><?= $i ?> Stars (<?= str_repeat('★', $i) ?>)</option>
                        <?php endfor; ?>
                    </select>
                    <label class="form-group__label">Rate student</label>
                </div>
                <div class="form-group" style="margin:0;flex:1;min-width:240px">
                    <textarea name="feedback_text" class="form-group__control" placeholder=" " rows="1" style="font-size:0.8125rem"><?= View::e($feedback['feedback_text'] ?? '') ?></textarea>
                    <label class="form-group__label">Feedback about student (optional)</label>
                </div>
            </div>

            <div class="form-group__error" id="session-form-error"></div>
            <div style="margin-top:4px">
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg)">Save Session Notes &amp; Attendance</button>
            </div>
        </form>
    </div>

    <!-- Session Resources -->
    <div class="compact-card">
        <h3 class="compact-card__title">
            <?= View::icon('paperclip', 'icon', 16) ?>
            Session Resources &amp; Guides
        </h3>
        <?php if (empty($resources)): ?>
        <p class="text-muted" style="margin:0 0 12px 0;font-size:0.8rem">No resources uploaded yet for this session.</p>
        <?php else: ?>
        <div class="flex flex-col gap-2 mb-3">
            <?php foreach ($resources as $resource): ?>
            <div class="flex items-center justify-between" style="padding:6px 0;border-bottom:1px solid var(--border-subtle)">
                <a href="<?= View::e($resource['file_path']) ?>" target="_blank" rel="noopener" style="font-weight:600;color:var(--color-primary)"><?= View::e($resource['title']) ?></a>
                <span class="text-muted" style="font-size:0.75rem"><?= View::e(date('d M Y', strtotime($resource['created_at']))) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form id="resource-form" class="flex gap-3 flex-wrap items-end">
            <div class="form-group" style="margin:0;flex:1;min-width:200px">
                <input type="text" name="title" class="form-group__control" placeholder=" " maxlength="200" style="font-size:0.8125rem">
                <label class="form-group__label">Title (optional)</label>
            </div>
            <input type="file" name="file" required style="font-size:0.8rem">
            <button type="submit" class="btn btn-secondary btn-sm" style="border-radius:var(--radius-lg)">Upload Resource</button>
        </form>
    </div>

</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

// Attendance button toggles
document.querySelectorAll('.attendance-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.attendance-btn').forEach((b) => b.classList.remove('is-active'));
        btn.classList.add('is-active');
        btn.querySelector('input').checked = true;
    });
});

document.getElementById('meeting-link-form')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.target;
    const btn = document.getElementById('save-meeting-link-btn');
    btn.disabled = true;
    btn.classList.add('is-loading');

    try {
        await apiPost('<?= View::e('/trainer/sessions/' . $booking['booking_ref'] . '/meeting-link') ?>', {
            provider: form.provider.value,
            url: form.url.value.trim(),
        });
        toast.success('Meeting link saved & sent to student.');
        window.location.reload();
    } catch (err) {
        toast.error(err.message);
        btn.disabled = false;
        btn.classList.remove('is-loading');
    }
});

document.getElementById('session-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.target;
    const errorBox = document.getElementById('session-form-error');
    errorBox.textContent = '';

    const attendance = form.querySelector('[name="attendance"]:checked')?.value ?? '';

    try {
        await apiPost('<?= View::e('/trainer/sessions/' . $booking['booking_ref']) ?>', {
            attendance,
            session_notes: form.session_notes.value.trim(),
            recommendation: form.recommendation.value.trim(),
            homework: form.homework.value.trim(),
            rating: form.rating.value,
            feedback_text: form.feedback_text.value.trim(),
        });
        toast.success('Session notes & attendance saved successfully.');
    } catch (err) {
        errorBox.textContent = err.message;
        toast.error(err.message);
    }
});

const completeBtn = document.getElementById('complete-session-btn');
if (completeBtn) {
    completeBtn.addEventListener('click', async () => {
        if (!confirm('Are you sure the yoga meeting/class is finished? This will complete the session and prompt the student to leave their feedback.')) return;
        completeBtn.disabled = true;
        completeBtn.textContent = 'Completing...';

        try {
            await apiPost('<?= View::e('/trainer/sessions/' . $booking['booking_ref'] . '/complete') ?>', {});
            toast.success('Session marked as completed! Feedback requested from student.');
            setTimeout(() => window.location.reload(), 800);
        } catch (err) {
            toast.error(err.message || 'Failed to complete session.');
            completeBtn.disabled = false;
            completeBtn.textContent = '✓ Mark Session Finished';
        }
    });
}
</script>
