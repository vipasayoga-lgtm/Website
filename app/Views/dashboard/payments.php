<?php

use App\Core\View;

$pageTitle = 'Payment History — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'client';
$active = 'payments';

$statusMeta = [
    'pending' => ['badge-pending', 'Pending'],
    'success' => ['badge-confirmed', 'Success'],
    'failed' => ['badge-cancelled', 'Failed'],
    'refunded' => ['badge-neutral', 'Refunded'],
];
?>
<div class="container" style="padding-block:var(--space-8) var(--space-16)">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div class="services-hero" style="margin:0;text-align:left">
            <h1 class="services-hero__title" style="margin:0">Payment History</h1>
            <p class="services-hero__subtitle" style="margin:0">Every payment attempt across your bookings.</p>
        </div>
        <div class="flex gap-3">
            <a href="/dashboard/bookings" class="btn btn-secondary btn-sm">My Bookings</a>
            <a href="/dashboard/invoices" class="btn btn-secondary btn-sm">Invoices</a>
        </div>
    </div>

    <?php if (empty($payments)): ?>
    <div class="empty-state" style="max-width:720px;margin-inline:auto">
        <span class="empty-state__icon"><?= View::icon('credit-card', 'icon', 22) ?></span>
        <p class="empty-state__text">No payments yet — they'll appear here once you make a booking.</p>
    </div>
    <?php else: ?>
    <div class="dashboard-table-container mb-4">
        <table class="dashboard-table" id="payments-table">
            <thead>
                <tr>
                    <th>Date &amp; Time</th>
                    <th>Booking Ref</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody id="payments-tbody">
                <?php foreach ($payments as $payment):
                    [$badgeClass, $badgeLabel] = $statusMeta[$payment['status']] ?? ['badge-neutral', ucfirst($payment['status'])];
                    $dateLabel = date('d M Y, g:i A', strtotime($payment['created_at']));
                ?>
                <tr data-id="<?= View::e($payment['id']) ?>">
                    <td>
                        <div style="font-weight:var(--font-weight-medium)"><?= View::e($dateLabel) ?></div>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-weight:600;color:var(--color-primary)"><?= View::e($payment['booking_ref']) ?></span>
                    </td>
                    <td>
                        <span class="text-muted"><?= View::e(ucfirst($payment['gateway'])) ?></span>
                    </td>
                    <td>
                        <div style="font-weight:var(--font-weight-semibold);color:var(--text-primary)">
                            <?= View::e($payment['currency']) ?> <?= number_format((float) $payment['amount'], 2) ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span>
                    </td>
                    <td style="text-align:right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/booking/<?= View::e($payment['booking_ref']) ?>/invoice" class="btn btn-secondary btn-xs" target="_blank" rel="noopener" title="View Invoice">
                                <?= View::icon('file-text', 'icon', 12) ?> Invoice
                            </a>
                            <?php if (\App\Core\Auth::hasRole('admin')): ?>
                            <button type="button" class="btn btn-ghost btn-xs text-error delete-payment-btn" data-id="<?= View::e($payment['id']) ?>" title="Delete Payment Record">
                                <?= View::icon('trash', 'icon', 13) ?>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls (10 records per page) -->
    <div class="flex items-center justify-between flex-wrap gap-3" id="pagination-wrapper" style="padding-inline:var(--space-2)">
        <div class="text-muted" style="font-size:0.85rem" id="pagination-info">Showing 1 to 10 of <?= count($payments) ?> items</div>
        <div class="flex items-center gap-2" id="pagination-controls">
            <button type="button" class="btn btn-secondary btn-sm" id="prev-page-btn" disabled>&laquo; Previous</button>
            <span style="font-size:0.85rem;font-weight:600;padding-inline:8px" id="page-num-indicator">Page 1</span>
            <button type="button" class="btn btn-secondary btn-sm" id="next-page-btn">Next &raquo;</button>
        </div>
    </div>
    <?php endif; ?>
</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

const tbody = document.getElementById('payments-tbody');
const rows = Array.from(tbody?.querySelectorAll('tr') || []);
const itemsPerPage = 8;
let currentPage = 1;
const totalPages = Math.ceil(rows.length / itemsPerPage);

function renderPagination() {
    if (!tbody || rows.length === 0) return;
    
    const startIdx = (currentPage - 1) * itemsPerPage;
    const endIdx = startIdx + itemsPerPage;

    rows.forEach((row, index) => {
        if (index >= startIdx && index < endIdx) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    const infoEl = document.getElementById('pagination-info');
    const prevBtn = document.getElementById('prev-page-btn');
    const nextBtn = document.getElementById('next-page-btn');
    const indicatorEl = document.getElementById('page-num-indicator');

    if (infoEl) {
        const visibleCount = Math.min(endIdx, rows.length);
        infoEl.textContent = `Showing ${rows.length === 0 ? 0 : startIdx + 1} to ${visibleCount} of ${rows.length} items`;
    }

    if (indicatorEl) {
        indicatorEl.textContent = `Page ${currentPage} of ${Math.max(1, totalPages)}`;
    }

    if (prevBtn) prevBtn.disabled = currentPage <= 1;
    if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
}

document.getElementById('prev-page-btn')?.addEventListener('click', () => {
    if (currentPage > 1) {
        currentPage--;
        renderPagination();
    }
});

document.getElementById('next-page-btn')?.addEventListener('click', () => {
    if (currentPage < totalPages) {
        currentPage++;
        renderPagination();
    }
});

document.querySelectorAll('.delete-payment-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        btn.disabled = true;

        try {
            await apiPost(`/api/payments/${id}/delete`, {});
            toast.success('Payment record deleted.');
            btn.closest('tr')?.remove();
            
            // Update row array & re-render pagination
            const idx = rows.findIndex((r) => r.dataset.id === id);
            if (idx >= 0) rows.splice(idx, 1);
            
            if (rows.length === 0) {
                window.location.reload();
            } else {
                renderPagination();
            }
        } catch (err) {
            toast.error(err.message || 'Could not delete payment record.');
            btn.disabled = false;
        }
    });
});

renderPagination();
</script>
