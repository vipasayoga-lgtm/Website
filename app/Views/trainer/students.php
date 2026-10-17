<?php

use App\Core\View;

$pageTitle = 'My Students — Vipasa Yoga';
$pageCss = 'trainer';
$portal = 'trainer';
$active = 'students';
?>
<div class="container" style="padding-block:var(--space-4) var(--space-8)">

    <div class="trainer-hero-card mb-6">
        <div class="trainer-hero-card__main">
            <div class="trainer-hero-card__avatar">
                <span class="trainer-hero-card__avatar-fallback">
                    <?= View::icon('users', 'icon', 20) ?>
                </span>
            </div>
            <div>
                <h1 class="trainer-hero-card__title">Student Directory</h1>
                <p class="trainer-hero-card__subtitle">Overview of students who have taken confirmed sessions with you.</p>
            </div>
        </div>
        <div class="trainer-hero-card__actions">
            <span class="trainer-count-pill"><?= count($students) ?> Students</span>
        </div>
    </div>

    <?php if (empty($students)): ?>
    <div class="trainer-empty-state">
        <div class="trainer-empty-state__icon"><?= View::icon('users', 'icon', 20) ?></div>
        <h3 class="trainer-empty-state__title">No students found</h3>
        <p class="trainer-empty-state__text">Students who book sessions with you will be listed here.</p>
    </div>
    <?php else: ?>
    <div class="trainer-schedule-list" id="students-list">
        <?php foreach ($students as $student): ?>
        <div class="trainer-schedule-card">
            <div class="trainer-schedule-card__left">
                <div class="trainer-avatar-chip">
                    <?= View::e(strtoupper(substr($student['name'] ?? 'S', 0, 2))) ?>
                </div>
                <div>
                    <h4 class="trainer-schedule-card__client-name"><?= View::e($student['name']) ?></h4>
                    <div class="trainer-schedule-card__meta">
                        <span class="trainer-package-pill"><?= (int) $student['session_count'] ?> session(s) completed</span>
                        <span class="trainer-ref-tag">Last: <?= View::e(date('d M Y', strtotime($student['last_session']))) ?></span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="/trainer/students/<?= (int) $student['id'] ?>" class="btn btn-secondary btn-sm flex items-center gap-1">
                    <?= View::icon('user', 'icon', 14) ?>
                    <span>View Details</span>
                </a>
                <?php if (\App\Core\Auth::hasRole('admin')): ?>
                <button type="button" class="btn btn-ghost btn-sm delete-student-btn" data-id="<?= (int) $student['id'] ?>" title="Remove student" style="color:var(--color-error,#EF4444);padding:6px;border-radius:var(--radius-md)">
                    <?= View::icon('trash', 'icon', 15) ?>
                </button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="flex items-center justify-between flex-wrap gap-3 mt-4" id="students-pagination-wrapper" style="padding-inline:var(--space-2)">
        <div class="text-muted" style="font-size:0.85rem" id="students-pagination-info">Showing 1 to 8 of <?= count($students) ?> students</div>
        <div class="flex items-center gap-2" id="students-pagination-controls">
            <button type="button" class="btn btn-secondary btn-sm" id="students-prev-btn" disabled>&laquo; Previous</button>
            <span style="font-size:0.85rem;font-weight:600;padding-inline:8px" id="students-page-indicator">Page 1</span>
            <button type="button" class="btn btn-secondary btn-sm" id="students-next-btn">Next &raquo;</button>
        </div>
    </div>
    <?php endif; ?>
</div>

<script type="module">
import toast from '/assets/js/modules/toast.js';

const container = document.getElementById('students-list');
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

    const infoEl = document.getElementById('students-pagination-info');
    const prevBtn = document.getElementById('students-prev-btn');
    const nextBtn = document.getElementById('students-next-btn');
    const indicatorEl = document.getElementById('students-page-indicator');
    const wrapper = document.getElementById('students-pagination-wrapper');

    if (infoEl) {
        const visibleCount = Math.min(endIdx, cards.length);
        infoEl.textContent = `Showing ${startIdx + 1} to ${visibleCount} of ${cards.length} students`;
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

document.getElementById('students-prev-btn')?.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderPagination();
    }
});

document.getElementById('students-next-btn')?.addEventListener('click', () => {
    const totalPages = Math.ceil(cards.length / itemsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderPagination();
    }
});

document.querySelectorAll('.delete-student-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
        const card = btn.closest('.trainer-schedule-card');
        if (card) {
            card.remove();
            cards = Array.from(container?.children || []).filter(el => el.classList.contains('trainer-schedule-card'));
            renderPagination();
            toast.success('Student removed from directory view.');
        }
    });
});

renderPagination();
</script>
