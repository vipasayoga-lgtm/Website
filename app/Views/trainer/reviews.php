<?php

use App\Core\View;

$pageTitle = 'Reviews — Vipasa Yoga';
$pageCss = 'trainer';
$portal = 'trainer';
$active = 'reviews';
?>
<div class="container" style="padding-block:var(--space-4) var(--space-8)">

    <div class="trainer-hero-card mb-6">
        <div class="trainer-hero-card__main">
            <div class="trainer-hero-card__avatar">
                <span class="trainer-hero-card__avatar-fallback">
                    <?= View::icon('star', 'icon', 20) ?>
                </span>
            </div>
            <div>
                <h1 class="trainer-hero-card__title">Student Reviews &amp; Feedback</h1>
                <p class="trainer-hero-card__subtitle">Ratings and written feedback submitted by students after sessions.</p>
            </div>
        </div>
    </div>

    <!-- Stat Grid Cards -->
    <div class="trainer-stat-grid mb-6" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr))">
        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--warning">
                <?= View::icon('star', 'icon', 18) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value"><?= $ratingCount > 0 ? number_format($ratingAvg, 1) : 'New' ?></div>
                <div class="trainer-stat-card__label">Average Rating</div>
            </div>
        </div>

        <div class="trainer-stat-card">
            <div class="trainer-stat-card__icon trainer-stat-card__icon--info">
                <?= View::icon('users', 'icon', 18) ?>
            </div>
            <div class="trainer-stat-card__content">
                <div class="trainer-stat-card__value"><?= (int) $ratingCount ?></div>
                <div class="trainer-stat-card__label">Total Reviews</div>
            </div>
        </div>
    </div>

    <?php if (empty($reviews)): ?>
    <div class="trainer-empty-state">
        <div class="trainer-empty-state__icon"><?= View::icon('star', 'icon', 20) ?></div>
        <h3 class="trainer-empty-state__title">No reviews yet</h3>
        <p class="trainer-empty-state__text">Feedback submitted by clients will appear here after completed sessions.</p>
    </div>
    <?php else: ?>
    <div class="flex flex-col gap-3" id="reviews-list">
        <?php foreach ($reviews as $review): ?>
        <div class="trainer-widget-card review-card-item" style="background-color:var(--surface-card)">
            <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <strong style="font-size:0.9rem;color:var(--text-primary)"><?= View::e($review['client_name'] ?? 'Student') ?></strong>
                    <div class="flex items-center gap-1">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                        <span style="color:<?= $i < (int) $review['rating'] ? '#F59E0B' : 'var(--text-muted)' ?>">
                            <?= View::icon('star', 'icon', 14) ?>
                        </span>
                        <?php endfor; ?>
                        <span style="font-size:0.75rem;font-weight:700;margin-left:4px"><?= (int) $review['rating'] ?>.0</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-muted" style="font-size:11px"><?= View::e(date('d M Y', strtotime($review['created_at']))) ?></span>
                    <?php if (\App\Core\Auth::hasRole('admin')): ?>
                    <button type="button" class="btn btn-ghost btn-sm delete-review-btn" data-id="<?= (int) $review['id'] ?>" title="Remove review" style="color:var(--color-error,#EF4444);padding:2px 6px;border-radius:var(--radius-md)">
                        <?= View::icon('trash', 'icon', 14) ?>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($review['review_text'])): ?>
            <p style="margin:0;font-size:0.8125rem;color:var(--text-primary);line-height:1.45"><?= View::e($review['review_text']) ?></p>
            <?php else: ?>
            <p class="text-muted" style="margin:0;font-size:0.78rem">No written feedback provided.</p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="flex items-center justify-between flex-wrap gap-3 mt-4" id="reviews-pagination-wrapper" style="padding-inline:var(--space-2)">
        <div class="text-muted" style="font-size:0.85rem" id="reviews-pagination-info">Showing 1 to 8 of <?= count($reviews) ?> reviews</div>
        <div class="flex items-center gap-2" id="reviews-pagination-controls">
            <button type="button" class="btn btn-secondary btn-sm" id="reviews-prev-btn" disabled>&laquo; Previous</button>
            <span style="font-size:0.85rem;font-weight:600;padding-inline:8px" id="reviews-page-indicator">Page 1</span>
            <button type="button" class="btn btn-secondary btn-sm" id="reviews-next-btn">Next &raquo;</button>
        </div>
    </div>
    <?php endif; ?>
</div>

<script type="module">
import toast from '/assets/js/modules/toast.js';

const container = document.getElementById('reviews-list');
let cards = Array.from(container?.children || []).filter(el => el.classList.contains('review-card-item'));
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

    const infoEl = document.getElementById('reviews-pagination-info');
    const prevBtn = document.getElementById('reviews-prev-btn');
    const nextBtn = document.getElementById('reviews-next-btn');
    const indicatorEl = document.getElementById('reviews-page-indicator');
    const wrapper = document.getElementById('reviews-pagination-wrapper');

    if (infoEl) {
        const visibleCount = Math.min(endIdx, cards.length);
        infoEl.textContent = `Showing ${startIdx + 1} to ${visibleCount} of ${cards.length} reviews`;
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

document.getElementById('reviews-prev-btn')?.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderPagination();
    }
});

document.getElementById('reviews-next-btn')?.addEventListener('click', () => {
    const totalPages = Math.ceil(cards.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderPagination();
    }
});

document.querySelectorAll('.delete-review-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
        const card = btn.closest('.review-card-item');
        if (card) {
            card.remove();
            cards = Array.from(container?.children || []).filter(el => el.classList.contains('review-card-item'));
            renderPagination();
            toast.success('Review removed.');
        }
    });
});

renderPagination();
</script>
