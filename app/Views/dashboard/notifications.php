<?php

use App\Core\View;

$pageTitle = 'Notifications — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'client';
$active = 'notifications';

function notifIcon(string $title, string $body): string {
    $t = strtolower($title . ' ' . $body);
    if (str_contains($t, 'booking') || str_contains($t, 'session') || str_contains($t, 'reschedule') || str_contains($t, 'class')) {
        return 'calendar';
    }
    if (str_contains($t, 'payment') || str_contains($t, 'invoice') || str_contains($t, 'paid') || str_contains($t, 'refund')) {
        return 'credit-card';
    }
    if (str_contains($t, 'profile') || str_contains($t, 'account') || str_contains($t, 'welcome')) {
        return 'user';
    }
    return 'bell';
}

function notifCategory(string $title, string $body): string {
    $t = strtolower($title . ' ' . $body);
    if (str_contains($t, 'booking') || str_contains($t, 'session') || str_contains($t, 'reschedule') || str_contains($t, 'class')) {
        return 'booking';
    }
    if (str_contains($t, 'payment') || str_contains($t, 'invoice') || str_contains($t, 'paid') || str_contains($t, 'refund')) {
        return 'payment';
    }
    return 'account';
}
?>
<div class="container" style="padding-block:var(--space-6) var(--space-16);max-width:820px">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3" style="background:var(--surface-card);padding:var(--space-5);border-radius:var(--radius-2xl);box-shadow:0 10px 30px -5px rgba(15, 51, 73, 0.08)">
        <div class="services-hero" style="margin:0;text-align:left">
            <h1 class="services-hero__title" style="margin:0;font-size:1.5rem;font-weight:var(--font-weight-bold)">Activity & Notifications</h1>
            <p class="services-hero__subtitle" style="margin:4px 0 0;font-size:0.875rem;color:var(--text-muted)">Real-time updates regarding your sessions, bookings, and payments.</p>
        </div>
        <div style="display:flex;align-items:center;gap:var(--space-2)">
            <span class="badge badge-subtle" style="border-radius:var(--radius-full);padding:0.35rem 0.85rem;font-size:0.8rem;font-weight:var(--font-weight-semibold);background:var(--vipasa-blue-50);color:var(--vipasa-blue-700)"><?= count($notifications) ?> total</span>
        </div>
    </div>

    <?php if (empty($notifications)): ?>
    <div class="card text-center" style="padding:var(--space-10) var(--space-6);border-radius:var(--radius-2xl);box-shadow:0 10px 30px -5px rgba(0,0,0,0.05);background:var(--surface-card)">
        <div style="display:grid;place-items:center;width:3.5rem;height:3.5rem;margin-inline:auto;margin-bottom:var(--space-3);border-radius:var(--radius-full);background:var(--vipasa-blue-50);color:var(--vipasa-blue-600)">
            <?= View::icon('bell', 'icon', 24) ?>
        </div>
        <h3 style="font-size:1.1rem;font-weight:var(--font-weight-bold);margin:0 0 6px">All caught up!</h3>
        <p class="text-muted" style="font-size:0.875rem;margin:0;max-width:420px;margin-inline:auto">You have no new notifications right now. Session updates and payment receipts will appear here.</p>
    </div>
    <?php else: ?>
    <!-- Category Filter Chips -->
    <div class="flex items-center gap-2 mb-4 flex-wrap" id="notif-filter-chips">
        <button type="button" class="chip is-selected js-notif-filter" data-filter="all" style="border-radius:var(--radius-full)">All Notifications</button>
        <button type="button" class="chip js-notif-filter" data-filter="booking" style="border-radius:var(--radius-full)">Bookings</button>
        <button type="button" class="chip js-notif-filter" data-filter="payment" style="border-radius:var(--radius-full)">Payments</button>
        <button type="button" class="chip js-notif-filter" data-filter="account" style="border-radius:var(--radius-full)">Account</button>
    </div>

    <div class="flex flex-col gap-2" id="notif-list-container">
        <?php foreach ($notifications as $notification):
            $iconName = notifIcon($notification['title'], $notification['body'] ?? '');
            $category = notifCategory($notification['title'], $notification['body'] ?? '');
            $dateLabel = date('d M Y, g:i A', strtotime($notification['created_at']));
            $isRead = !empty($notification['is_read']);
        ?>
        <div class="notif-item-card" id="notif-card-<?= (int)$notification['id'] ?>" data-id="<?= (int)$notification['id'] ?>" data-category="<?= $category ?>" style="display:flex;align-items:center;gap:var(--space-3);padding:10px 14px;border-radius:var(--radius-lg);background:var(--surface-card);border:1px solid var(--border-subtle);box-shadow:0 2px 6px -2px rgba(15, 51, 73, 0.04);transition:all 0.2s ease">
            <div class="notif-item-card__icon" style="display:grid;place-items:center;width:2.25rem;height:2.25rem;border-radius:var(--radius-full);background:<?= $category === 'booking' ? 'var(--saffron-100)' : ($category === 'payment' ? 'var(--vipasa-blue-50)' : 'var(--mist-100)') ?>;color:<?= $category === 'booking' ? 'var(--saffron-700)' : ($category === 'payment' ? 'var(--vipasa-blue-700)' : 'var(--mist-700)') ?>;flex-shrink:0">
                <?= View::icon($iconName, 'icon', 16) ?>
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
                <button type="button" class="btn btn-ghost btn-xs text-error js-delete-notif" data-id="<?= (int)$notification['id'] ?>" title="Delete notification" style="border-radius:var(--radius-md);padding:4px">
                    <?= View::icon('trash', 'icon', 14) ?>
                </button>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination Controls -->
    <div class="flex items-center justify-between flex-wrap gap-3 mt-4" id="notif-pagination-wrapper" style="display:none">
        <div class="text-muted" style="font-size:0.8rem" id="notif-pagination-info">Showing 1 to 8 of <?= count($notifications) ?> notifications</div>
        <div class="flex items-center gap-2">
            <button type="button" class="btn btn-secondary btn-sm" id="notif-prev-btn" disabled style="border-radius:var(--radius-md);font-size:0.8rem">&laquo; Previous</button>
            <span style="font-size:0.8rem;font-weight:600;padding-inline:6px" id="notif-page-indicator">Page 1</span>
            <button type="button" class="btn btn-secondary btn-sm" id="notif-next-btn" style="border-radius:var(--radius-md);font-size:0.8rem">Next &raquo;</button>
        </div>
    </div>
    <?php endif; ?>
</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

const container = document.getElementById('notif-list-container');
let allCards = Array.from(container?.querySelectorAll('.notif-item-card') || []);
let activeFilter = 'all';
const itemsPerPage = 8;
let currentPage = 1;

function filteredCards() {
    if (activeFilter === 'all') return allCards;
    return allCards.filter((card) => card.dataset.category === activeFilter);
}

function renderPagination() {
    if (!container || allCards.length === 0) return;

    const cards = filteredCards();
    const totalPages = Math.max(1, Math.ceil(cards.length / itemsPerPage));
    if (currentPage > totalPages) currentPage = totalPages;

    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = startIdx + itemsPerPage;

    allCards.forEach((card) => {
        card.style.display = 'none';
    });

    cards.forEach((card, index) => {
        if (index >= startIdx && index < endIdx) {
            card.style.display = 'flex';
        }
    });

    const infoEl = document.getElementById('notif-pagination-info');
    const prevBtn = document.getElementById('notif-prev-btn');
    const nextBtn = document.getElementById('notif-next-btn');
    const indicatorEl = document.getElementById('notif-page-indicator');
    const wrapper = document.getElementById('notif-pagination-wrapper');

    if (infoEl) {
        const visibleCount = Math.min(endIdx, cards.length);
        infoEl.textContent = `Showing ${cards.length === 0 ? 0 : startIdx + 1} to ${visibleCount} of ${cards.length} notifications`;
    }

    if (indicatorEl) {
        indicatorEl.textContent = `Page ${currentPage} of ${totalPages}`;
    }

    if (prevBtn) prevBtn.disabled = currentPage <= 1;
    if (nextBtn) nextBtn.disabled = currentPage >= totalPages;

    if (wrapper) {
        wrapper.style.display = cards.length <= itemsPerPage ? 'none' : 'flex';
    }
}

document.querySelectorAll('.js-notif-filter').forEach((chip) => {
    chip.addEventListener('click', () => {
        document.querySelectorAll('.js-notif-filter').forEach((c) => c.classList.remove('is-selected'));
        chip.classList.add('is-selected');
        activeFilter = chip.dataset.filter;
        currentPage = 1;
        renderPagination();
    });
});

document.getElementById('notif-prev-btn')?.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderPagination();
    }
});

document.getElementById('notif-next-btn')?.addEventListener('click', () => {
    const totalPages = Math.ceil(filteredCards().length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderPagination();
    }
});

document.querySelectorAll('.js-delete-notif').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const id = btn.getAttribute('data-id');
        btn.disabled = true;
        try {
            await apiPost(`/api/notifications/${id}/delete`, {});
            toast.success('Notification deleted.');

            const card = document.getElementById(`notif-card-${id}`);
            if (card) {
                card.remove();
                const idx = allCards.findIndex((c) => c.dataset.id === id);
                if (idx >= 0) allCards.splice(idx, 1);
            }

            if (allCards.length === 0) {
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

