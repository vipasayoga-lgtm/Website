<?php

use App\Core\View;

$pageTitle = 'Earnings — Vipasa Yoga';
$pageCss = 'trainer';
$portal = 'trainer';
$active = 'earnings';

$statusMeta = [
    'awaiting_trainer_approval' => ['badge-pending', 'Awaiting you'],
    'confirmed' => ['badge-confirmed', 'Confirmed'],
    'completed' => ['badge-completed', 'Completed'],
];
?>
<div class="container" style="padding-block:var(--space-4) var(--space-8)">

    <div class="trainer-hero-card mb-6">
        <div class="trainer-hero-card__main">
            <div class="trainer-hero-card__avatar">
                <span class="trainer-hero-card__avatar-fallback">
                    <?= View::icon('wallet', 'icon', 20) ?>
                </span>
            </div>
            <div>
                <h1 class="trainer-hero-card__title">Earnings &amp; Performance</h1>
                <p class="trainer-hero-card__subtitle">Financial revenue metrics and completed session payouts.</p>
            </div>
        </div>
    </div>

    <!-- Stat Grid Cards -->
    <div class="trainer-stat-grid mb-6">
        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--accent">
                <?= View::icon('wallet', 'icon', 18) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value">₹<?= number_format($totalRevenue, 0) ?></div>
                <div class="trainer-stat-card__label">Total Earnings</div>
            </div>
        </div>

        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--primary">
                <?= View::icon('calendar-week', 'icon', 18) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value"><?= $totalSessions ?></div>
                <div class="trainer-stat-card__label">Total Sessions</div>
            </div>
        </div>

        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--success">
                <?= View::icon('chart-bar', 'icon', 18) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value">₹<?= number_format($monthRevenue, 0) ?></div>
                <div class="trainer-stat-card__label">Last 30 Days</div>
            </div>
        </div>

        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--info">
                <?= View::icon('star', 'icon', 18) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value"><?= $ratingCount > 0 ? number_format($ratingAvg, 1) : 'New' ?></div>
                <div class="trainer-stat-card__label"><?= $ratingCount > 0 ? $ratingCount . ' Review(s)' : 'No Reviews' ?></div>
            </div>
        </div>
    </div>

    <div class="trainer-section-block">
        <div class="trainer-section-header">
            <div class="flex items-center gap-2">
                <?= View::icon('file-text', 'trainer-section-header__icon', 18) ?>
                <h2 class="trainer-section-header__title">Session Payment History</h2>
            </div>
            <span class="trainer-count-pill"><?= count($history) ?></span>
        </div>
        <?php if (empty($history)): ?>
        <div class="trainer-empty-state">
            <div class="trainer-empty-state__icon"><?= View::icon('wallet', 'icon', 20) ?></div>
            <h3 class="trainer-empty-state__title">No paid sessions yet</h3>
            <p class="trainer-empty-state__text">Completed paid sessions will appear in this history list.</p>
        </div>
        <?php else: ?>
        <div class="trainer-schedule-list" id="earnings-list">
            <?php foreach ($history as $row):
                [$badgeClass, $badgeLabel] = $statusMeta[$row['status']] ?? ['badge-neutral', ucfirst($row['status'])];
            ?>
            <div class="trainer-schedule-card">
                <div class="trainer-schedule-card__left">
                    <div class="trainer-schedule-card__date-badge">
                        <?= View::icon('calendar-week', 'trainer-schedule-card__date-icon', 14) ?>
                        <span><?= View::e(date('d M Y', strtotime($row['slot_date']))) ?></span>
                    </div>
                    <div class="trainer-schedule-card__user-info">
                        <div class="trainer-avatar-chip"><?= View::e(strtoupper(substr($row['client_name'] ?? 'C', 0, 2))) ?></div>
                        <div>
                            <h4 class="trainer-schedule-card__client-name"><?= View::e($row['client_name']) ?></h4>
                            <div class="trainer-schedule-card__meta">
                                <span class="trainer-package-pill"><?= View::e($row['package_name']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span>
                    <strong style="font-size:0.85rem;color:var(--text-primary)">₹<?= number_format((float) $row['total_amount'], 2) ?></strong>
                    <?php if (\App\Core\Auth::hasRole('admin')): ?>
                    <button type="button" class="btn btn-ghost btn-sm delete-earning-btn" data-ref="<?= View::e($row['booking_ref'] ?? '') ?>" title="Remove entry" style="color:var(--color-error,#EF4444);padding:6px;border-radius:var(--radius-md)">
                        <?= View::icon('trash', 'icon', 15) ?>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="flex items-center justify-between flex-wrap gap-3 mt-4" id="earnings-pagination-wrapper" style="padding-inline:var(--space-2)">
            <div class="text-muted" style="font-size:0.85rem" id="earnings-pagination-info">Showing 1 to 8 of <?= count($history) ?> items</div>
            <div class="flex items-center gap-2" id="earnings-pagination-controls">
                <button type="button" class="btn btn-secondary btn-sm" id="earnings-prev-btn" disabled>&laquo; Previous</button>
                <span style="font-size:0.85rem;font-weight:600;padding-inline:8px" id="earnings-page-indicator">Page 1</span>
                <button type="button" class="btn btn-secondary btn-sm" id="earnings-next-btn">Next &raquo;</button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script type="module">
import toast from '/assets/js/modules/toast.js';

const container = document.getElementById('earnings-list');
let cards = Array.from(container?.children || []).filter(el => el.classList.contains('trainer-schedule-card'));
const itemsPerPage = 8;
let currentPage = 1;

function renderPagination() {
    if (!container || cards.length === 0) return;
    const totalPages = Math.ceil(cards.length / itemsPerPage);
    if (currentPage > totalPages) currentPage = Math.max(1, totalPages);

    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = startIdx + itemsPerPage;

    cards.forEach((card, index) => {
        card.style.display = (index >= startIdx && index < endIdx) ? '' : 'none';
    });

    const infoEl = document.getElementById('earnings-pagination-info');
    const prevBtn = document.getElementById('earnings-prev-btn');
    const nextBtn = document.getElementById('earnings-next-btn');
    const indicatorEl = document.getElementById('earnings-page-indicator');
    const wrapper = document.getElementById('earnings-pagination-wrapper');

    if (infoEl) {
        const visibleCount = Math.min(endIdx, cards.length);
        infoEl.textContent = `Showing ${startIdx + 1} to ${visibleCount} of ${cards.length} items`;
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

document.getElementById('earnings-prev-btn')?.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderPagination();
    }
});

document.getElementById('earnings-next-btn')?.addEventListener('click', () => {
    const totalPages = Math.ceil(cards.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderPagination();
    }
});

document.querySelectorAll('.delete-earning-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
        const card = btn.closest('.trainer-schedule-card');
        if (card) {
            card.remove();
            cards = Array.from(container?.children || []).filter(el => el.classList.contains('trainer-schedule-card'));
            renderPagination();
            toast.success('Payment entry removed.');
        }
    });
});

renderPagination();
</script>
