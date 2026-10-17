<?php

use App\Core\View;

$pageTitle = 'Manage Payments — Vipasa Yoga';
$pageCss = 'admin';
$portal = 'admin';
$active = 'payments';

$statuses = ['pending', 'success', 'failed', 'refunded'];
$statusMeta = [
    'pending' => ['badge-pending', 'Pending Review'],
    'success' => ['badge-confirmed', 'Verified / Success'],
    'failed' => ['badge-cancelled', 'Rejected / Failed'],
    'refunded' => ['badge-neutral', 'Refunded'],
];

$gateways = ['upi', 'razorpay', 'stripe', 'paypal', 'wallet', 'other'];
$gatewayLabels = [
    'upi' => 'UPI QR',
    'razorpay' => 'Razorpay',
    'stripe' => 'Stripe',
    'paypal' => 'PayPal',
    'wallet' => 'Wallet',
    'other' => 'Other',
];
?>
<div class="admin-page container">
    <div class="admin-hero flex items-center justify-between flex-wrap gap-3 mb-4" style="flex-direction:row;text-align:left">
        <div>
            <h1 class="admin-hero__title" style="font-size:1.15rem;font-weight:700;margin:0">Manage Payments</h1>
            <p class="admin-hero__subtitle" style="font-size:0.8rem;color:var(--text-muted);margin:2px 0 0">Review client UPI transfers, verify payments with tick/cancel actions, and sync with trainer schedules.</p>
        </div>
    </div>

    <!-- Structured Filters Container with Generous Spacing -->
    <div class="flex flex-col gap-3 mb-8" style="background:var(--surface-card);padding:var(--space-4) var(--space-5);border-radius:var(--radius-lg);border:1px solid var(--border-subtle)">
        <div class="flex items-center flex-wrap gap-2">
            <span style="font-size:0.8rem;font-weight:600;color:var(--text-muted);min-width:80px">Status:</span>
            <div class="services-filters" style="margin:0">
                <a href="/admin/payments<?= $selectedGateway ? '?gateway=' . urlencode($selectedGateway) : '' ?>" class="chip<?= !$selectedStatus ? ' is-selected' : '' ?>">All statuses</a>
                <?php foreach ($statuses as $status): ?>
                <a href="/admin/payments?status=<?= urlencode($status) ?><?= $selectedGateway ? '&gateway=' . urlencode($selectedGateway) : '' ?>" class="chip<?= $selectedStatus === $status ? ' is-selected' : '' ?>"><?= View::e($statusMeta[$status][1]) ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex items-center flex-wrap gap-2">
            <span style="font-size:0.8rem;font-weight:600;color:var(--text-muted);min-width:80px">Gateway:</span>
            <div class="services-filters" style="margin:0">
                <a href="/admin/payments<?= $selectedStatus ? '?status=' . urlencode($selectedStatus) : '' ?>" class="chip<?= !$selectedGateway ? ' is-selected' : '' ?>">All gateways</a>
                <?php foreach ($gateways as $gateway): ?>
                <a href="/admin/payments?gateway=<?= urlencode($gateway) ?><?= $selectedStatus ? '&status=' . urlencode($selectedStatus) : '' ?>" class="chip<?= $selectedGateway === $gateway ? ' is-selected' : '' ?>"><?= View::e($gatewayLabels[$gateway]) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php if (empty($payments)): ?>
    <div class="empty-state">
        <span class="empty-state__icon"><?= View::icon('credit-card', 'icon', 24) ?></span>
        <p class="empty-state__text" style="margin:0">No payments match this filter.</p>
    </div>
    <?php else: ?>
    <!-- Executive Payments Data Table Format -->
    <div class="table-responsive" style="margin-top:var(--space-4)">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Txn ID / UTR</th>
                    <th>Client</th>
                    <th>Booking Ref</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Proof / Screenshot</th>
                    <th>Date &amp; Time</th>
                    <th>Status</th>
                    <th style="text-align:center;min-width:110px">Actions</th>
                </tr>
            </thead>
            <tbody id="admin-payments-tbody">
                <?php foreach ($payments as $payment):
                    [$badgeClass, $badgeLabel] = $statusMeta[$payment['status']] ?? ['badge-pending', ucfirst($payment['status'])];
                    $dateLabel = date('d M Y, g:i A', strtotime($payment['created_at']));
                    $txnRef = !empty($payment['gateway_txn_id']) ? $payment['gateway_txn_id'] : '#' . $payment['id'];
                    $isPending = $payment['status'] === 'pending';
                ?>
                <tr class="admin-payment-row" id="payment-row-<?= (int) $payment['id'] ?>">
                    <td>
                        <strong style="font-family:monospace;font-size:0.85rem"><?= View::e($txnRef) ?></strong>
                    </td>
                    <td>
                        <div style="font-weight:600"><?= View::e($payment['client_name']) ?></div>
                        <div class="text-muted" style="font-size:0.75rem"><?= View::e($payment['client_email']) ?></div>
                        <?php if (!empty($payment['client_phone'])): ?>
                        <div class="text-muted" style="font-size:0.75rem;color:var(--color-primary)">Tel: <?= View::e($payment['client_phone']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="trainer-ref-tag" style="font-weight:600"><?= View::e($payment['booking_ref']) ?></span>
                    </td>
                    <td><strong><?= View::e($payment['currency']) ?> <?= number_format((float) $payment['amount'], 2) ?></strong></td>
                    <td><span class="chip" style="font-size:0.75rem"><?= View::e($gatewayLabels[$payment['gateway']] ?? ucfirst($payment['gateway'])) ?></span></td>
                    <td>
                        <?php if (!empty($payment['screenshot_path'])): ?>
                        <a href="<?= View::e($payment['screenshot_path']) ?>" target="_blank" rel="noopener" class="btn btn-ghost btn-xs flex items-center gap-1" style="color:var(--color-primary);font-size:0.75rem;padding:3px 6px">
                            <?= View::icon('image', 'icon', 13) ?>
                            <span>View Proof</span>
                        </a>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:0.75rem">No image</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:0.78rem"><?= View::e($dateLabel) ?></td>
                    <td>
                        <span class="badge <?= $badgeClass ?> status-badge"><?= View::e($badgeLabel) ?></span>
                    </td>
                    <td style="text-align:center">
                        <div class="flex items-center justify-center gap-1">
                            <?php if ($isPending): ?>
                            <!-- Tick Icon: Approve Payment & Confirm Booking -->
                            <button type="button" class="btn btn-ghost btn-xs js-approve-admin-payment-btn" data-id="<?= (int) $payment['id'] ?>" data-ref="<?= View::e($payment['booking_ref']) ?>" title="Approve Payment &amp; Send to Trainer" style="color:#059669;background:#ECFDF5;padding:5px 8px;border-radius:6px;border:1px solid #A7F3D0">
                                <?= View::icon('check', 'icon', 16) ?>
                            </button>
                            <!-- Cancel Icon: Reject Payment & Cancel Booking -->
                            <button type="button" class="btn btn-ghost btn-xs js-reject-admin-payment-btn" data-id="<?= (int) $payment['id'] ?>" data-ref="<?= View::e($payment['booking_ref']) ?>" title="Reject Payment &amp; Cancel Booking" style="color:#DC2626;background:#FEF2F2;padding:5px 8px;border-radius:6px;border:1px solid #FCA5A5">
                                <?= View::icon('x', 'icon', 16) ?>
                            </button>
                            <?php endif; ?>
                            <!-- Trash: Delete Record -->
                            <button type="button" class="btn btn-ghost btn-xs js-delete-admin-payment-btn" data-id="<?= (int) $payment['id'] ?>" title="Delete payment record" style="color:var(--color-error,#EF4444);padding:5px 6px;border-radius:6px">
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
    <div class="flex items-center justify-between flex-wrap gap-3 mt-4" id="admin-payments-pagination" style="display:none">
        <div class="text-muted" style="font-size:0.8rem" id="admin-payments-info">Showing 1 to 8 of <?= count($payments) ?> items</div>
        <div class="flex items-center gap-2">
            <button type="button" class="btn btn-secondary btn-sm" id="admin-payments-prev" disabled style="border-radius:var(--radius-md);font-size:0.8rem">&laquo; Previous</button>
            <span style="font-size:0.8rem;font-weight:600;padding-inline:6px" id="admin-payments-page">Page 1</span>
            <button type="button" class="btn btn-secondary btn-sm" id="admin-payments-next" style="border-radius:var(--radius-md);font-size:0.8rem">Next &raquo;</button>
        </div>
    </div>
    <?php endif; ?>
</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

function setupPaymentsPagination(itemsPerPage = 8) {
    const tbody = document.getElementById('admin-payments-tbody');
    if (!tbody) return;
    let rows = Array.from(tbody.querySelectorAll('.admin-payment-row'));
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

        const infoEl = document.getElementById('admin-payments-info');
        const prevBtn = document.getElementById('admin-payments-prev');
        const nextBtn = document.getElementById('admin-payments-next');
        const indicatorEl = document.getElementById('admin-payments-page');
        const wrapper = document.getElementById('admin-payments-pagination');

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

    document.getElementById('admin-payments-prev')?.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            render();
        }
    });

    document.getElementById('admin-payments-next')?.addEventListener('click', () => {
        const totalPages = Math.ceil(rows.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            render();
        }
    });

    render();
}

setupPaymentsPagination(8);

// Admin Tick Button: Approve Payment & Confirm Booking
document.querySelectorAll('.js-approve-admin-payment-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const ref = btn.dataset.ref;
        if (!id) return;
        if (!confirm(`Verify and approve payment for booking ${ref}? This will confirm the booking and send it to the trainer dashboard.`)) {
            return;
        }

        btn.disabled = true;
        btn.classList.add('is-loading');

        try {
            await apiPost(`/admin/payments/${id}/approve`, {});
            toast.success(`Payment #${id} approved! Booking ${ref} confirmed and sent to trainer dashboard.`);
            setTimeout(() => {
                window.location.reload();
            }, 700);
        } catch (err) {
            toast.error(err.message || 'Failed to approve payment.');
            btn.disabled = false;
            btn.classList.remove('is-loading');
        }
    });
});

// Admin Cancel Button: Reject Payment & Cancel Booking
document.querySelectorAll('.js-reject-admin-payment-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const ref = btn.dataset.ref;
        if (!id) return;
        if (!confirm(`Reject payment for booking ${ref}? This will cancel the booking and release the trainer slot.`)) {
            return;
        }

        btn.disabled = true;
        btn.classList.add('is-loading');

        try {
            await apiPost(`/admin/payments/${id}/reject`, {});
            toast.success(`Payment #${id} rejected. Booking ${ref} cancelled.`);
            setTimeout(() => {
                window.location.reload();
            }, 700);
        } catch (err) {
            toast.error(err.message || 'Failed to reject payment.');
            btn.disabled = false;
            btn.classList.remove('is-loading');
        }
    });
});

// Admin Trash Button: Delete Payment
document.querySelectorAll('.js-delete-admin-payment-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const tr = btn.closest('tr');
        if (!id) return;
        if (!confirm(`Are you sure you want to delete payment record #${id}?`)) return;

        btn.disabled = true;
        try {
            await apiPost(`/admin/payments/${id}/delete`, {});
            toast.success('Payment record deleted.');
            if (tr) {
                tr.remove();
                setupPaymentsPagination(8);
            }
        } catch (err) {
            toast.error(err.message || 'Could not delete payment record.');
            btn.disabled = false;
        }
    });
});
</script>
