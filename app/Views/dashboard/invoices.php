<?php

use App\Core\View;

$pageTitle = 'Invoices — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'client';
$active = 'invoices';
?>
<div class="container" style="padding-block:var(--space-8) var(--space-16)">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div class="services-hero" style="margin:0;text-align:left">
            <h1 class="services-hero__title" style="margin:0">Invoices</h1>
            <p class="services-hero__subtitle" style="margin:0">View or print the official invoice for any confirmed booking.</p>
        </div>
        <div class="flex gap-3">
            <a href="/dashboard/bookings" class="btn btn-secondary btn-sm">My Bookings</a>
            <a href="/dashboard/payments" class="btn btn-secondary btn-sm">Payment History</a>
        </div>
    </div>

    <?php if (empty($bookings)): ?>
    <div class="empty-state" style="max-width:720px;margin-inline:auto">
        <span class="empty-state__icon"><?= View::icon('file-text', 'icon', 22) ?></span>
        <p class="empty-state__text">No invoices yet — invoices become available once a booking is confirmed.</p>
    </div>
    <?php else: ?>
    <div class="dashboard-table-container mb-4">
        <table class="dashboard-table" id="invoices-table">
            <thead>
                <tr>
                    <th>Issued Date</th>
                    <th>Booking Ref</th>
                    <th>Package</th>
                    <th>Instructor</th>
                    <th>Total Amount</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody id="invoices-tbody">
                <?php foreach ($bookings as $booking):
                    $dateLabel = date('d M Y', strtotime($booking['slot_date']));
                ?>
                <tr data-ref="<?= View::e($booking['booking_ref']) ?>">
                    <td>
                        <div style="font-weight:var(--font-weight-medium)"><?= View::e($dateLabel) ?></div>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-weight:600;color:var(--color-primary)"><?= View::e($booking['booking_ref']) ?></span>
                    </td>
                    <td>
                        <div style="font-weight:var(--font-weight-semibold)"><?= View::e($booking['package']['name'] ?? 'Package') ?></div>
                    </td>
                    <td>
                        <span class="text-muted"><?= View::e($booking['instructor']['name'] ?? 'Instructor') ?></span>
                    </td>
                    <td>
                        <div style="font-weight:var(--font-weight-semibold);color:var(--color-primary)">
                            <?= View::e($booking['currency']) ?> <?= number_format((float) $booking['total_amount'], 2) ?>
                        </div>
                    </td>
                    <td style="text-align:right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/booking/<?= View::e($booking['booking_ref']) ?>/invoice" class="btn btn-primary btn-xs" target="_blank" rel="noopener" title="View/Print Invoice">
                                <?= View::icon('file-text', 'icon', 12) ?> View / Print
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls (10 items per page) -->
    <div class="flex items-center justify-between flex-wrap gap-3" id="pagination-wrapper" style="padding-inline:var(--space-2)">
        <div class="text-muted" style="font-size:0.85rem" id="pagination-info">Showing 1 to 10 of <?= count($bookings) ?> items</div>
        <div class="flex items-center gap-2" id="pagination-controls">
            <button type="button" class="btn btn-secondary btn-sm" id="prev-page-btn" disabled>&laquo; Previous</button>
            <span style="font-size:0.85rem;font-weight:600;padding-inline:8px" id="page-num-indicator">Page 1</span>
            <button type="button" class="btn btn-secondary btn-sm" id="next-page-btn">Next &raquo;</button>
        </div>
    </div>
    <?php endif; ?>
</div>

<script type="module">
const tbody = document.getElementById('invoices-tbody');
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

renderPagination();
</script>
