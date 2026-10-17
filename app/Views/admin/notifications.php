<?php

use App\Core\View;

$pageTitle = 'Notifications — Vipasa Yoga Admin';
$pageCss = 'services';
$portal = 'admin';
$active = 'notifications';
?>
<div class="container" style="padding-block:var(--space-6) var(--space-16);max-width:820px">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3" style="background:var(--surface-card);padding:var(--space-5);border-radius:var(--radius-2xl);box-shadow:0 10px 30px -5px rgba(15, 51, 73, 0.08)">
        <div class="services-hero" style="margin:0;text-align:left">
            <h1 class="services-hero__title" style="margin:0;font-size:1.5rem;font-weight:var(--font-weight-bold)">Broadcast & Notifications</h1>
            <p class="services-hero__subtitle" style="margin:4px 0 0;font-size:0.875rem;color:var(--text-muted)">Send target in-app notifications to clients, instructors, or individual users.</p>
        </div>
        <button type="button" class="btn btn-primary" id="add-notification-btn" style="border-radius:var(--radius-lg);padding:0.6rem 1.25rem">+ Send Notification</button>
    </div>

    <?php if (empty($notifications)): ?>
    <div class="card text-center" style="padding:var(--space-10) var(--space-6);border-radius:var(--radius-2xl);box-shadow:0 10px 30px -5px rgba(0,0,0,0.05);background:var(--surface-card)">
        <div style="display:grid;place-items:center;width:3.5rem;height:3.5rem;margin-inline:auto;margin-bottom:var(--space-3);border-radius:var(--radius-full);background:var(--vipasa-blue-50);color:var(--vipasa-blue-600)">
            <?= View::icon('bell', 'icon', 24) ?>
        </div>
        <h3 style="font-size:1.1rem;font-weight:var(--font-weight-bold);margin:0 0 6px">No notifications sent yet</h3>
        <p class="text-muted" style="font-size:0.875rem;margin:0;max-width:420px;margin-inline:auto">Click the "+ Send Notification" button above to send announcements or individual messages.</p>
    </div>
    <?php else: ?>
    <div class="flex flex-col gap-2" id="admin-notif-list">
        <?php foreach ($notifications as $notification): ?>
        <div class="notif-card-compact" id="admin-notif-<?= (int)$notification['id'] ?>" data-id="<?= (int)$notification['id'] ?>" style="display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:var(--radius-md);background:var(--surface-card);border:1px solid var(--border-subtle);box-shadow:0 1px 3px rgba(0,0,0,0.03);transition:all 0.15s ease">
            <div class="notif-card-compact__icon" style="display:grid;place-items:center;width:28px;height:28px;border-radius:var(--radius-full);background:var(--vipasa-blue-50);color:var(--vipasa-blue-700);flex-shrink:0">
                <?= View::icon('bell', 'icon', 13) ?>
            </div>
            <div class="flex items-center gap-2 flex-1" style="min-width:0;overflow:hidden">
                <span style="font-size:0.8rem;font-weight:700;color:var(--text-primary);white-space:nowrap;flex-shrink:0"><?= View::e($notification['title']) ?></span>
                <span class="badge badge-subtle" style="font-size:0.68rem;padding:1px 6px;border-radius:var(--radius-full);white-space:nowrap;flex-shrink:0">To: <?= View::e($notification['user_name'] ?? 'User #' . $notification['user_id']) ?></span>
                <?php if ($notification['is_read']): ?>
                <span class="badge badge-confirmed" style="font-size:0.65rem;padding:1px 6px;border-radius:var(--radius-full);white-space:nowrap;flex-shrink:0">Read</span>
                <?php else: ?>
                <span class="badge badge-pending" style="font-size:0.65rem;padding:1px 6px;border-radius:var(--radius-full);white-space:nowrap;flex-shrink:0">Unread</span>
                <?php endif; ?>
                <?php if (!empty($notification['body'])): ?>
                <span class="text-muted" style="font-size:0.78rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;flex:1">— <?= View::e($notification['body']) ?></span>
                <?php endif; ?>
            </div>
            <div class="flex items-center gap-3" style="flex-shrink:0;margin-left:auto">
                <span class="text-muted" style="font-size:0.72rem;display:flex;align-items:center;gap:3px;white-space:nowrap">
                    <?= View::icon('clock', 'icon', 11) ?>
                    <?= date('d M Y, g:i A', strtotime($notification['created_at'])) ?>
                </span>
                <button type="button" class="btn btn-ghost btn-xs text-error js-delete-admin-notif" data-id="<?= (int)$notification['id'] ?>" title="Delete notification" style="color:var(--color-error,#EF4444);padding:3px">
                    <?= View::icon('trash', 'icon', 14) ?>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination Controls -->
    <div class="flex items-center justify-between flex-wrap gap-3 mt-4" id="admin-notif-pagination" style="display:none">
        <div class="text-muted" style="font-size:0.8rem" id="admin-notif-info">Showing 1 to 8 of <?= count($notifications) ?> notifications</div>
        <div class="flex items-center gap-2">
            <button type="button" class="btn btn-secondary btn-sm" id="admin-prev-btn" disabled style="border-radius:var(--radius-md);font-size:0.8rem">&laquo; Previous</button>
            <span style="font-size:0.8rem;font-weight:600;padding-inline:6px" id="admin-page-indicator">Page 1</span>
            <button type="button" class="btn btn-secondary btn-sm" id="admin-next-btn" style="border-radius:var(--radius-md);font-size:0.8rem">Next &raquo;</button>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal-overlay" id="notification-modal-overlay" style="display:none;backdrop-filter:blur(8px);background:rgba(15, 23, 42, 0.45)">
    <div class="modal card" style="border-radius:var(--radius-xl);box-shadow:0 20px 40px -10px rgba(0,0,0,0.2);max-width:500px;width:100%;background:#ffffff;border:1px solid var(--border-subtle);overflow:hidden">
        <div class="modal__header flex items-center justify-between" style="padding:16px 20px;border-bottom:1px solid var(--border-subtle);background:var(--surface-subtle,#f8fafc)">
            <div class="modal__title" style="font-size:1.05rem;font-weight:700;color:var(--text-primary)">Send Notification</div>
            <button type="button" class="modal__close" id="notification-modal-close" aria-label="Close" style="font-size:1.4rem;color:var(--text-muted);cursor:pointer;background:none;border:none;padding:0 4px">&times;</button>
        </div>
        <form id="notification-form" style="padding:20px">
            <div class="modal__body flex flex-col gap-4">
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Target Audience</label>
                    <select name="target" id="notification-target" class="form-group__control" style="width:100%;border-radius:var(--radius-md);padding:8px 12px">
                        <option value="user">Specific user</option>
                        <option value="all_clients">All clients</option>
                        <option value="all_trainers">All trainers</option>
                    </select>
                </div>
                <div id="notification-user-group">
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Select Recipient User</label>
                    <select name="user_id" id="notification-user" class="form-group__control" style="width:100%;border-radius:var(--radius-md);padding:8px 12px">
                        <option value="" disabled selected>Select a user…</option>
                        <?php foreach ($users as $user): ?>
                        <option value="<?= (int) $user['id'] ?>"><?= View::e($user['name']) ?> (<?= View::e($user['email'] ?? $user['role']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Notification Title</label>
                    <input type="text" name="title" class="form-group__control" placeholder="e.g., Session Reminder or Profile Update" required maxlength="200" style="width:100%;border-radius:var(--radius-md);padding:8px 12px">
                </div>
                <div>
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:var(--text-muted);margin-bottom:6px">Message Body (Optional)</label>
                    <textarea name="body" class="form-group__control" placeholder="Type your notification message here..." rows="3" style="width:100%;border-radius:var(--radius-md);padding:8px 12px;resize:vertical"></textarea>
                </div>
                <div class="text-error" id="notification-form-error" style="font-size:0.8rem;margin-top:2px"></div>
            </div>
            <div class="modal__footer flex items-center justify-end gap-2" style="margin-top:20px;padding-top:14px;border-top:1px solid var(--border-subtle)">
                <button type="button" class="btn btn-ghost btn-sm" id="notification-modal-cancel">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" id="notification-submit-btn" style="border-radius:var(--radius-md);padding:8px 18px;font-weight:600">Send Now</button>
            </div>
        </form>
    </div>
</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

const overlay = document.getElementById('notification-modal-overlay');
const form = document.getElementById('notification-form');
const submitBtn = document.getElementById('notification-submit-btn');
const errorBox = document.getElementById('notification-form-error');

function openModal() {
    form.reset();
    errorBox.textContent = '';
    syncUserGroup();
    overlay.style.display = 'flex';
}

function closeModal() {
    overlay.style.display = 'none';
}

document.getElementById('add-notification-btn').addEventListener('click', openModal);
document.getElementById('notification-modal-close').addEventListener('click', closeModal);
document.getElementById('notification-modal-cancel').addEventListener('click', closeModal);
overlay.addEventListener('click', (e) => { if (e.target === overlay) closeModal(); });

const targetSelect = document.getElementById('notification-target');
const userGroup = document.getElementById('notification-user-group');

function syncUserGroup() {
    const isSpecificUser = targetSelect.value === 'user';
    userGroup.style.display = isSpecificUser ? '' : 'none';
    form.user_id.required = isSpecificUser;
}
targetSelect.addEventListener('change', syncUserGroup);
syncUserGroup();

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    errorBox.textContent = '';
    submitBtn.classList.add('is-loading');
    submitBtn.disabled = true;

    const target = form.target.value;
    const payload = {
        target,
        title: form.title.value.trim(),
        body: form.body.value.trim(),
    };
    if (target === 'user') {
        payload.user_id = Number(form.user_id.value);
    }

    try {
        const result = await apiPost('/admin/notifications', payload);
        toast.success(result.message || 'Notification sent.');
        closeModal();
        window.location.reload();
    } catch (err) {
        errorBox.textContent = err.message || 'Failed to send notification.';
        toast.error(err.message || 'Failed to send notification.');
    } finally {
        submitBtn.classList.remove('is-loading');
        submitBtn.disabled = false;
    }
});

const container = document.getElementById('admin-notif-list');
const cards = Array.from(container?.querySelectorAll('.notif-card-compact') || []);
const itemsPerPage = 8;
let currentPage = 1;

function renderPagination() {
    if (!container || cards.length === 0) return;

    const totalPages = Math.ceil(cards.length / itemsPerPage);
    if (currentPage > totalPages) currentPage = Math.max(1, totalPages);

    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = startIdx + itemsPerPage;

    cards.forEach((card, index) => {
        card.style.display = (index >= startIdx && index < endIdx) ? 'flex' : 'none';
    });

    const infoEl = document.getElementById('admin-notif-info');
    const prevBtn = document.getElementById('admin-prev-btn');
    const nextBtn = document.getElementById('admin-next-btn');
    const indicatorEl = document.getElementById('admin-page-indicator');
    const wrapper = document.getElementById('admin-notif-pagination');

    if (infoEl) {
        const visibleCount = Math.min(endIdx, cards.length);
        infoEl.textContent = `Showing ${cards.length === 0 ? 0 : startIdx + 1} to ${visibleCount} of ${cards.length} notifications`;
    }

    if (indicatorEl) {
        indicatorEl.textContent = `Page ${currentPage} of ${Math.max(1, totalPages)}`;
    }

    if (prevBtn) prevBtn.disabled = currentPage <= 1;
    if (nextBtn) nextBtn.disabled = currentPage >= totalPages;

    if (wrapper) {
        wrapper.style.display = cards.length <= itemsPerPage ? 'none' : 'flex';
    }
}

document.getElementById('admin-prev-btn')?.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderPagination();
    }
});

document.getElementById('admin-next-btn')?.addEventListener('click', () => {
    const totalPages = Math.ceil(cards.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderPagination();
    }
});

document.querySelectorAll('.js-delete-admin-notif').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.getAttribute('data-id');
        const card = document.getElementById(`admin-notif-${id}`);
        btn.disabled = true;
        try {
            await apiPost(`/admin/notifications/${id}/delete`, {});
            toast.success('Notification deleted.');
            if (card) card.remove();
            const idx = cards.findIndex((c) => c.getAttribute('data-id') === id);
            if (idx >= 0) cards.splice(idx, 1);
            if (cards.length === 0) {
                window.location.reload();
            } else {
                renderPagination();
            }
        } catch (e) {
            toast.error(e.message || 'Failed to delete notification.');
            btn.disabled = false;
        }
    });
});

renderPagination();
</script>
