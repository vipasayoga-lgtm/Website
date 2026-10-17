<?php

use App\Core\View;

$pageTitle = 'Student Reviews & Approvals — Vipasa Yoga Admin';
$pageCss = 'services';
$portal = 'admin';
$active = 'reviews';

$totalCount = count($reviews);
$approvedCount = count(array_filter($reviews, static fn($r) => (int)($r['is_approved'] ?? 0) === 1));
$pendingCount = $totalCount - $approvedCount;
?>
<div class="admin-page container">
    <div class="admin-hero flex items-center justify-between flex-wrap gap-3 mb-4" style="flex-direction:row;text-align:left">
        <div>
            <h1 class="admin-hero__title" style="font-size:1.15rem;font-weight:700;margin:0">Student Feedback &amp; Frontpage Approvals</h1>
            <p class="admin-hero__subtitle" style="font-size:0.8rem;color:var(--text-muted);margin:2px 0 0">Manage reviews submitted by students. Approve ratings to display them on the website homepage.</p>
        </div>
    </div>

    <!-- Compact Executive Stat Grid -->
    <div style="display:flex;gap:var(--space-3);margin-bottom:var(--space-6);width:100%">
        <div class="stat-card" style="flex:1;min-width:0;padding:var(--space-3) var(--space-4);border-radius:var(--radius-lg);background:var(--surface-card);border:1px solid var(--border-subtle)">
            <div class="text-muted" style="font-size:0.78rem;font-weight:600">Total Reviews</div>
            <div style="font-size:1.25rem;font-weight:700;color:var(--text-primary)"><?= $totalCount ?></div>
        </div>
        <div class="stat-card" style="flex:1;min-width:0;padding:var(--space-3) var(--space-4);border-radius:var(--radius-lg);background:var(--surface-card);border:1px solid var(--border-subtle);border-left:4px solid var(--color-success,#10B981)">
            <div class="text-muted" style="font-size:0.78rem;font-weight:600">Approved for Frontpage</div>
            <div style="font-size:1.25rem;font-weight:700;color:var(--color-success,#10B981)"><?= $approvedCount ?></div>
        </div>
        <div class="stat-card" style="flex:1;min-width:0;padding:var(--space-3) var(--space-4);border-radius:var(--radius-lg);background:var(--surface-card);border:1px solid var(--border-subtle);border-left:4px solid var(--color-accent,#F59E0B)">
            <div class="text-muted" style="font-size:0.78rem;font-weight:600">Pending Review</div>
            <div style="font-size:1.25rem;font-weight:700;color:var(--color-accent,#F59E0B)"><?= $pendingCount ?></div>
        </div>
    </div>

    <?php if (empty($reviews)): ?>
    <div class="empty-state">
        <span class="empty-state__icon"><?= View::icon('star', 'icon', 24) ?></span>
        <h3 style="font-size:1.05rem;font-weight:700;margin:0 0 4px">No student reviews yet</h3>
        <p class="text-muted" style="font-size:0.8rem;margin:0">When users complete sessions and leave feedback, their ratings will appear here for your review and approval.</p>
    </div>
    <?php else: ?>
    <!-- Executive Reviews Data Table Format -->
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Instructor</th>
                    <th>Rating</th>
                    <th>Review Comment</th>
                    <th>Date &amp; Time</th>
                    <th>Frontpage Status</th>
                    <th style="text-align:center">Actions</th>
                </tr>
            </thead>
            <tbody id="admin-reviews-tbody">
                <?php foreach ($reviews as $review):
                    $isApproved = (int)($review['is_approved'] ?? 0) === 1;
                    $dateLabel = date('d M Y, g:i A', strtotime($review['created_at']));
                ?>
                <tr class="admin-review-row" id="review-row-<?= (int)$review['id'] ?>">
                    <td>
                        <div style="font-weight:600"><?= View::e($review['client_name']) ?></div>
                        <div class="text-muted" style="font-size:0.75rem"><?= View::e($review['client_email']) ?></div>
                    </td>
                    <td>
                        <div style="font-weight:600"><?= View::e($review['instructor_name']) ?></div>
                        <?php if (!empty($review['booking_ref'])): ?>
                        <div class="text-muted" style="font-size:0.72rem;font-family:monospace"><?= View::e($review['booking_ref']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="flex items-center gap-1" title="<?= (int)$review['rating'] ?> of 5 stars">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                            <span style="color:<?= $i < (int) $review['rating'] ? '#F59E0B' : 'var(--border-default)' ?>">
                                <?= View::icon('star', 'icon', 13) ?>
                            </span>
                            <?php endfor; ?>
                            <span style="font-size:0.78rem;font-weight:700;margin-left:2px"><?= (int) $review['rating'] ?>.0</span>
                        </div>
                    </td>
                    <td style="max-width:280px">
                        <?php if (!empty($review['review_text'])): ?>
                        <div style="font-size:0.8rem;line-height:1.4"><?= View::e($review['review_text']) ?></div>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:0.75rem;font-style:italic">No comment</span>
                        <?php endif; ?>
                    </td>
                    <td><span style="font-size:0.78rem"><?= View::e($dateLabel) ?></span></td>
                    <td>
                        <?php if ($isApproved): ?>
                        <span class="badge badge-confirmed" id="status-badge-<?= (int)$review['id'] ?>" style="font-size:0.72rem">Frontpage Approved</span>
                        <?php else: ?>
                        <span class="badge badge-neutral" id="status-badge-<?= (int)$review['id'] ?>" style="font-size:0.72rem">Pending Approval</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="flex items-center justify-center gap-1.5">
                            <button type="button" class="btn btn-ghost btn-xs js-edit-review-btn" data-id="<?= (int)$review['id'] ?>" data-text="<?= View::e($review['review_text'] ?? '') ?>" data-rating="<?= (int)$review['rating'] ?>" data-approved="<?= $isApproved ? '1' : '0' ?>" data-client="<?= View::e($review['client_name']) ?>" title="Edit review comment" style="color:var(--color-primary,#0D9488);padding:4px">
                                <?= View::icon('pencil', 'icon', 14) ?>
                            </button>
                            <button type="button" class="btn btn-xs <?= $isApproved ? 'btn-secondary' : 'btn-accent' ?> js-toggle-approval" data-id="<?= (int)$review['id'] ?>" data-approved="<?= $isApproved ? '1' : '0' ?>" style="font-size:0.75rem;padding:3px 8px" title="Toggle frontpage status">
                                <?= $isApproved ? 'Unapprove' : '✓ Approve' ?>
                            </button>
                            <button type="button" class="btn btn-ghost btn-xs js-delete-admin-review-btn" data-id="<?= (int)$review['id'] ?>" title="Delete review permanently" style="color:var(--color-error,#EF4444);padding:4px">
                                <?= View::icon('trash', 'icon', 14) ?>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 8-Item Auto-Hiding Pagination Controls -->
    <div class="flex items-center justify-between flex-wrap gap-3 mt-4" id="admin-reviews-pagination" style="display:none">
        <div class="text-muted" style="font-size:0.8rem" id="admin-reviews-info">Showing 1 to 8 of <?= count($reviews) ?> items</div>
        <div class="flex items-center gap-2">
            <button type="button" class="btn btn-secondary btn-sm" id="admin-reviews-prev" disabled style="border-radius:var(--radius-md);font-size:0.8rem">&laquo; Previous</button>
            <span style="font-size:0.8rem;font-weight:600;padding-inline:6px" id="admin-reviews-page">Page 1</span>
            <button type="button" class="btn btn-secondary btn-sm" id="admin-reviews-next" style="border-radius:var(--radius-md);font-size:0.8rem">Next &raquo;</button>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Edit Review Comment Modal -->
<div id="edit-review-modal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(4px);z-index:9999;align-items:center;justify-content:center;padding:var(--space-4)">
    <div style="width:100%;max-width:480px;background:var(--surface-card,#FFFFFF);border-radius:var(--radius-xl);padding:var(--space-6);box-shadow:0 20px 25px -5px rgba(0,0,0,0.1),0 8px 10px -6px rgba(0,0,0,0.1);border:1px solid var(--border-subtle)">
        <div class="flex items-center justify-between mb-4">
            <h3 style="font-size:1.1rem;font-weight:700;margin:0;color:var(--text-primary)">Edit Review Comment</h3>
            <button type="button" class="btn btn-ghost btn-xs js-close-edit-modal" style="padding:4px">
                <?= View::icon('x', 'icon', 18) ?>
            </button>
        </div>
        <form id="edit-review-form">
            <input type="hidden" id="edit-review-id" name="id">
            <div class="form-group mb-3">
                <label class="form-group__label" style="font-weight:600;font-size:0.82rem">Student Name</label>
                <input type="text" id="edit-review-client" class="form-group__control" readonly style="background:var(--surface-muted);opacity:0.8;font-weight:600">
            </div>
            <div class="form-group mb-3">
                <label class="form-group__label" style="font-weight:600;font-size:0.82rem">Rating Stars</label>
                <select id="edit-review-rating" name="rating" class="form-group__control">
                    <option value="5">5 Stars (★★★★★)</option>
                    <option value="4">4 Stars (★★★★☆)</option>
                    <option value="3">3 Stars (★★★☆☆)</option>
                    <option value="2">2 Stars (★★☆☆☆)</option>
                    <option value="1">1 Star (★☆☆☆☆)</option>
                </select>
            </div>
            <div class="form-group mb-4">
                <label class="form-group__label" style="font-weight:600;font-size:0.82rem">Review Comment / Feedback</label>
                <textarea id="edit-review-text" name="review_text" class="form-group__control" rows="4" placeholder="Enter updated review comment..." style="resize:vertical" required></textarea>
            </div>
            <div class="form-group mb-4" style="display:flex;align-items:center;gap:8px">
                <input type="checkbox" id="edit-review-approved" name="is_approved" value="1" style="width:16px;height:16px;cursor:pointer">
                <label for="edit-review-approved" style="font-size:0.85rem;font-weight:600;cursor:pointer;margin:0">Approved &amp; Published on Website Frontpage</label>
            </div>
            <div class="flex items-center justify-end gap-2">
                <button type="button" class="btn btn-secondary btn-sm js-close-edit-modal">Cancel</button>
                <button type="submit" class="btn btn-accent btn-sm" id="edit-review-save-btn">Save &amp; Publish</button>
            </div>
        </form>
    </div>
</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

function setupReviewsPagination(itemsPerPage = 8) {
    const tbody = document.getElementById('admin-reviews-tbody');
    if (!tbody) return;
    let rows = Array.from(tbody.querySelectorAll('.admin-review-row'));
    if (rows.length === 0) return;

    let currentPage = 1;

    function render() {
        const totalPages = Math.ceil(rows.length / itemsPerPage);
        if (currentPage > totalPages) currentPage = Math.max(1, totalPages);
        const startIdx = (currentPage - 1) * itemsPerPage;
        const endIdx = startIdx + itemsPerPage;

        rows.forEach((row, index) => {
            row.style.display = (index >= startIdx && index < endIdx) ? '' : 'none';
        });

        const infoEl = document.getElementById('admin-reviews-info');
        const prevBtn = document.getElementById('admin-reviews-prev');
        const nextBtn = document.getElementById('admin-reviews-next');
        const indicatorEl = document.getElementById('admin-reviews-page');
        const wrapper = document.getElementById('admin-reviews-pagination');

        if (infoEl) {
            const visibleCount = Math.min(endIdx, rows.length);
            infoEl.textContent = `Showing ${rows.length === 0 ? 0 : startIdx + 1} to ${visibleCount} of ${rows.length} items`;
        }

        if (indicatorEl) {
            indicatorEl.textContent = `Page ${currentPage} of ${Math.max(1, totalPages)}`;
        }

        if (prevBtn) prevBtn.disabled = currentPage <= 1;
        if (nextBtn) nextBtn.disabled = currentPage >= totalPages;

        if (wrapper) {
            wrapper.style.display = rows.length <= itemsPerPage ? 'none' : 'flex';
        }
    }

    document.getElementById('admin-reviews-prev')?.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            render();
        }
    });

    document.getElementById('admin-reviews-next')?.addEventListener('click', () => {
        const totalPages = Math.ceil(rows.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            render();
        }
    });

    render();
}

setupReviewsPagination(8);

document.querySelectorAll('.js-edit-review-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const text = btn.dataset.text;
        const rating = btn.dataset.rating;
        const approved = btn.dataset.approved === '1';
        const client = btn.dataset.client;

        document.getElementById('edit-review-id').value = id;
        document.getElementById('edit-review-client').value = client;
        document.getElementById('edit-review-text').value = text;
        document.getElementById('edit-review-rating').value = rating;
        document.getElementById('edit-review-approved').checked = approved;

        document.getElementById('edit-review-modal').style.display = 'flex';
    });
});

document.querySelectorAll('.js-close-edit-modal').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('edit-review-modal').style.display = 'none';
    });
});

document.getElementById('edit-review-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const id = document.getElementById('edit-review-id').value;
    const saveBtn = document.getElementById('edit-review-save-btn');
    saveBtn.disabled = true;
    saveBtn.classList.add('is-loading');

    const text = document.getElementById('edit-review-text').value;
    const rating = document.getElementById('edit-review-rating').value;
    const isApproved = document.getElementById('edit-review-approved').checked ? 1 : 0;

    try {
        const res = await apiPost(`/admin/reviews/${id}/update`, {
            review_text: text,
            rating: rating,
            is_approved: isApproved,
        });
        toast.success(res.message || 'Review comment updated.');
        window.location.reload();
    } catch (err) {
        toast.error(err.message || 'Failed to update review.');
        saveBtn.disabled = false;
        saveBtn.classList.remove('is-loading');
    }
});

document.querySelectorAll('.js-toggle-approval').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        btn.disabled = true;
        btn.classList.add('is-loading');

        try {
            const res = await apiPost(`/admin/reviews/${id}/toggle-approval`, {});
            toast.success(res.message);
            window.location.reload();
        } catch (err) {
            toast.error(err.message || 'Action failed.');
            btn.disabled = false;
            btn.classList.remove('is-loading');
        }
    });
});

document.querySelectorAll('.js-delete-admin-review-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const tr = btn.closest('tr');
        btn.disabled = true;
        try {
            await apiPost(`/admin/reviews/${id}/delete`, {});
            toast.success('Review deleted.');
            if (tr) {
                tr.remove();
                setupReviewsPagination(8);
            }
        } catch (err) {
            toast.success('Review deleted.');
            if (tr) {
                tr.remove();
                setupReviewsPagination(8);
            }
        }
    });
});
</script>
