<?php

use App\Core\View;

$pageTitle = 'Notifications — Vipasa Yoga';
$pageCss = 'trainer';
$portal = 'trainer';
$active = 'notifications';
?>
<div class="container" style="padding-block:var(--space-4) var(--space-12);max-width:820px">

    <div class="trainer-hero-card mb-4" style="border-radius:var(--radius-xl);box-shadow:0 4px 16px -4px rgba(15, 51, 73, 0.06);background:var(--surface-card);padding:var(--space-4) var(--space-5)">
        <div class="trainer-hero-card__main" style="display:flex;align-items:center;justify-content:space-between;width:100%">
            <div>
                <h1 class="trainer-hero-card__title" style="font-size:1.15rem;font-weight:var(--font-weight-bold);margin:0">Instructor Notifications</h1>
                <p class="trainer-hero-card__subtitle" style="font-size:0.8rem;color:var(--text-muted);margin:2px 0 0">Updates on student bookings, session requests, and studio alerts.</p>
            </div>
            <div class="trainer-hero-card__actions" style="margin-left:auto">
                <span class="badge badge-subtle" style="border-radius:var(--radius-full);padding:0.25rem 0.65rem;font-size:0.75rem;font-weight:var(--font-weight-semibold);background:var(--vipasa-blue-50);color:var(--vipasa-blue-700)"><?= count($notifications) ?> total</span>
            </div>
        </div>
    </div>

    <?php if (empty($notifications)): ?>
    <div class="card text-center" style="padding:var(--space-6) var(--space-4);border-radius:var(--radius-xl);box-shadow:0 4px 16px -4px rgba(0,0,0,0.04);background:var(--surface-card)">
        <h3 style="font-size:0.95rem;font-weight:var(--font-weight-bold);margin:0 0 4px">No notifications yet</h3>
        <p class="text-muted" style="font-size:0.8rem;margin:0;max-width:380px;margin-inline:auto">Student bookings, session reschedules, and payouts will appear here automatically.</p>
    </div>
    <?php else: ?>
    <div class="flex flex-col gap-2" id="trainer-notif-list">
        <?php foreach ($notifications as $notification):
            $dateLabel = date('d M Y, g:i A', strtotime($notification['created_at']));
            $isRead = !empty($notification['is_read']);
        ?>
        <div class="notif-item-card" id="trainer-notif-card-<?= (int)$notification['id'] ?>" data-id="<?= (int)$notification['id'] ?>" style="display:flex;align-items:center;gap:var(--space-3);padding:10px 14px;border-radius:var(--radius-lg);background:var(--surface-card);border:1px solid var(--border-subtle);box-shadow:0 2px 6px -2px rgba(15, 51, 73, 0.04);transition:all 0.2s ease">
            <div class="notif-item-card__icon" style="display:grid;place-items:center;width:2.25rem;height:2.25rem;border-radius:var(--radius-full);background:var(--vipasa-blue-50);color:var(--vipasa-blue-700);flex-shrink:0">
                <?= View::icon('bell', 'icon', 16) ?>
            </div>
            <div class="notif-item-card__body" style="flex:1;min-width:0">
                <div class="flex items-center gap-2 flex-wrap" style="margin-bottom:1px">
                    <span class="notif-item-card__title" style="font-size:0.875rem;font-weight:var(--font-weight-bold);color:var(--text-primary)"><?= View::e($notification['title']) ?></span>
                    <?php if (!$isRead): ?>
                    <span class="badge" style="background:var(--saffron-500);color:#ffffff;font-size:0.65rem;padding:1px 6px;border-radius:var(--radius-full);font-weight:var(--font-weight-semibold)">New</span>
                    <?php endif; ?>
                </div>
                <?php if (!empty($notification['body'])): ?>
                <div class="notif-item-card__text" style="font-size:0.8rem;color:var(--text-muted);line-height:1.35;margin-bottom:2px"><?= View::e($notification['body']) ?></div>
                <?php endif; ?>
                <div class="notif-item-card__meta" style="font-size:0.72rem;color:var(--mist-500);display:flex;align-items:center;gap:4px">
                    <?= View::icon('clock', 'icon', 11) ?>
                    <span><?= View::e($dateLabel) ?></span>
                </div>
            </div>
            <?php if (\App\Core\Auth::hasRole('admin')): ?>
            <div class="notif-item-card__actions" style="margin-left:auto">
                <button type="button" class="btn btn-ghost btn-xs text-error js-delete-trainer-notif" data-id="<?= (int)$notification['id'] ?>" title="Delete notification" style="border-radius:var(--radius-md);padding:4px">
                    <?= View::icon('trash', 'icon', 14) ?>
                </button>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination Controls -->
    <div class="flex items-center justify-between flex-wrap gap-3 mt-4" id="trainer-notif-pagination" style="display:none">
        <div class="text-muted" style="font-size:0.8rem" id="trainer-notif-info">Showing 1 to 8 of <?= count($notifications) ?> notifications</div>
        <div class="flex items-center gap-2">
            <button type="button" class="btn btn-secondary btn-sm" id="trainer-prev-btn" disabled style="border-radius:var(--radius-md);font-size:0.8rem">&laquo; Previous</button>
            <span style="font-size:0.8rem;font-weight:600;padding-inline:6px" id="trainer-page-indicator">Page 1</span>
            <button type="button" class="btn btn-secondary btn-sm" id="trainer-next-btn" style="border-radius:var(--radius-md);font-size:0.8rem">Next &raquo;</button>
        </div>
    </div>
    <?php endif; ?>
</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

const container = document.getElementById('trainer-notif-list');
const cards = Array.from(container?.querySelectorAll('.notif-item-card') || []);
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

    const infoEl = document.getElementById('trainer-notif-info');
    const prevBtn = document.getElementById('trainer-prev-btn');
    const nextBtn = document.getElementById('trainer-next-btn');
    const indicatorEl = document.getElementById('trainer-page-indicator');
    const wrapper = document.getElementById('trainer-notif-pagination');

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

document.getElementById('trainer-prev-btn')?.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderPagination();
    }
});

document.getElementById('trainer-next-btn')?.addEventListener('click', () => {
    const totalPages = Math.ceil(cards.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderPagination();
    }
});

document.querySelectorAll('.js-delete-trainer-notif').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const id = btn.getAttribute('data-id');
        btn.disabled = true;
        try {
            await apiPost(`/api/notifications/${id}/delete`, {});
            toast.success('Notification deleted.');

            const card = document.getElementById(`trainer-notif-card-${id}`);
            if (card) {
                card.remove();
                const idx = cards.findIndex((c) => c.dataset.id === id);
                if (idx >= 0) cards.splice(idx, 1);
            }

            if (cards.length === 0) {
                window.location.reload();
            } else {
                renderPagination();
            }
        } catch (err) {
            toast.error(err.message || 'Failed to delete notification.');
            btn.disabled = false;
        }
    });
});

renderPagination();
</script>
