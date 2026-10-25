<?php

use App\Core\View;
use App\Models\ClientProfile;

$pageTitle = 'Manage Customers — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'admin';
$active = 'customers';

$segment = $segment ?? '';
$segmentCounts = $segmentCounts ?? ['all' => 0, 'no_booking' => 0, 'booked' => 0];
$fromDate = $fromDate ?? null;
$toDate = $toDate ?? null;

/** Preserves the other filters when switching one of them. */
$filterUrl = static function (array $overrides) use ($q, $status, $segment, $fromDate, $toDate): string {
    $params = array_filter([
        'q' => $overrides['q'] ?? $q,
        'status' => $overrides['status'] ?? $status,
        'segment' => array_key_exists('segment', $overrides) ? $overrides['segment'] : $segment,
        'from_date' => array_key_exists('from_date', $overrides) ? $overrides['from_date'] : $fromDate,
        'to_date' => array_key_exists('to_date', $overrides) ? $overrides['to_date'] : $toDate,
        'page' => $overrides['page'] ?? null,
    ], static fn ($v) => $v !== null && $v !== '');

    return '/admin/customers' . ($params ? '?' . http_build_query($params) : '');
};

$exportExcelUrl = static function () use ($q, $status, $segment, $fromDate, $toDate): string {
    $params = array_filter([
        'q' => $q,
        'status' => $status,
        'segment' => $segment,
        'from_date' => $fromDate,
        'to_date' => $toDate,
    ], static fn ($v) => $v !== null && $v !== '');

    return '/admin/customers/export-excel' . ($params ? '?' . http_build_query($params) : '');
};

$segmentTabs = [
    '' => ['All clients', 'Everyone registered on the site.'],
    'no_booking' => ['Signed up, not booked', 'Created an account but has never booked a session.'],
    'booked' => ['Booked a session', 'Has at least one booking on record.'],
];
?>
<style>
.btn-excel {
    background: #107C41;
    color: #ffffff !important;
    border: none;
    border-radius: 8px;
    padding: 7px 16px;
    font-weight: 600;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(16, 124, 65, 0.25);
    text-decoration: none !important;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.btn-excel:hover,
.btn-excel:focus-visible {
    background: #0E6B37;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(16, 124, 65, 0.35);
}

.excel-modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 999999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
    background: rgba(11, 19, 43, 0.78);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
.excel-modal-backdrop.is-active {
    display: flex;
}
.excel-modal-card {
    background: #ffffff;
    border-radius: 18px;
    max-width: 490px;
    width: 100%;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.5);
    border: 1px solid #E2E8F0;
    overflow: hidden;
    transform: scale(0.96) translateY(8px);
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.excel-modal-backdrop.is-active .excel-modal-card {
    transform: scale(1) translateY(0);
}

@media (max-width: 768px) {
    .customers-header-wrap {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    .customers-header-actions {
        width: 100% !important;
    }
    .customers-header-actions .btn-excel,
    .customers-header-actions .btn {
        flex: 1 1 auto !important;
        justify-content: center !important;
    }
    #customer-search-form {
        flex-direction: column !important;
        align-items: stretch !important;
    }
}
</style>

<div class="container" style="padding-block:var(--space-8) var(--space-16)">
    <!-- Header with Title and Download Excel Actions -->
    <div class="flex items-center justify-between flex-wrap gap-3 mb-6 customers-header-wrap">
        <div class="services-hero" style="margin:0;text-align:left">
            <h1 class="services-hero__title" style="margin:0">Manage Customers</h1>
            <p class="services-hero__subtitle" style="margin:4px 0 0">Browse registered clients, profile details, assessment answers, bookings, and payment history.</p>
        </div>

        <!-- Excel Export Actions -->
        <div class="customers-header-actions flex items-center gap-2 flex-wrap">
            <a href="/admin/customers/export-excel?all=1" class="btn-excel" id="export-customers-excel" title="Download all customers with complete details as an Excel Spreadsheet (.xlsx)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="8" y1="13" x2="16" y2="13"></line>
                    <line x1="8" y1="17" x2="16" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span>Download Excel</span>
            </a>

            <button type="button" id="btn-open-excel-date-modal" class="btn btn-secondary btn-sm" style="display:inline-flex;align-items:center;gap:6px;height:35px;border-radius:8px;font-size:0.82rem;font-weight:600;padding:6px 12px;" title="Pick custom registration dates to export Excel sheet">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>Download by Date</span>
            </button>
        </div>
    </div>

    <!-- Funnel segments — the two states an admin actually asks about, plus All. -->
    <div class="customer-segments mb-5">
        <?php foreach ($segmentTabs as $key => [$label, $hint]): ?>
        <a href="<?= View::e($filterUrl(['segment' => $key, 'page' => null])) ?>"
           class="customer-segment<?= $segment === $key ? ' is-active' : '' ?>"
           <?= $segment === $key ? 'aria-current="page"' : '' ?>>
            <span class="customer-segment__label"><?= View::e($label) ?></span>
            <span class="customer-segment__count"><?= (int) ($segmentCounts[$key === '' ? 'all' : $key] ?? 0) ?></span>
            <span class="customer-segment__hint"><?= View::e($hint) ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Search & Date Filter Bar -->
    <div class="bookings-filter-card" style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:14px;padding:14px 18px;margin-bottom:var(--space-4);box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <form method="get" action="/admin/customers" class="flex gap-3 flex-wrap items-center" id="customer-search-form" style="margin:0;">
            <input type="text" name="q" id="customer-search" class="form-group__control" style="flex:1;min-width:240px;height:34px;padding:6px 10px;font-size:0.85rem;"
                   placeholder="Search by name, phone or email…" value="<?= View::e($q ?? '') ?>" autocomplete="off">
            <input type="hidden" name="status" value="<?= View::e($status ?? '') ?>">
            <input type="hidden" name="segment" value="<?= View::e($segment) ?>">

            <!-- Date Range Controls -->
            <div class="flex items-center gap-1.5" style="font-size:0.8rem;color:#475569;font-weight:600;">
                <span>From:</span>
                <input type="date" name="from_date" id="filter-from-date" value="<?= View::e($fromDate ?? '') ?>" class="form-group__control" style="padding:4px 8px;font-size:0.8rem;height:34px;width:130px;border-radius:6px;border:1px solid #CBD5E1">
            </div>

            <div class="flex items-center gap-1.5" style="font-size:0.8rem;color:#475569;font-weight:600;">
                <span>To:</span>
                <input type="date" name="to_date" id="filter-to-date" value="<?= View::e($toDate ?? '') ?>" class="form-group__control" style="padding:4px 8px;font-size:0.8rem;height:34px;width:130px;border-radius:6px;border:1px solid #CBD5E1">
            </div>

            <button type="submit" class="btn btn-secondary btn-sm" style="height:34px;padding:4px 14px;font-weight:600;font-size:0.8rem;">Filter</button>

            <?php if ($q || $fromDate || $toDate): ?>
            <a href="<?= View::e($filterUrl(['q' => null, 'from_date' => null, 'to_date' => null])) ?>" class="btn btn-ghost btn-sm" style="font-size:0.78rem;color:#EF4444;padding:4px 8px;display:inline-flex;align-items:center;gap:4px;" title="Clear search and date filters">
                <?= View::icon('x', 'icon', 12) ?> Clear
            </a>
            <?php endif; ?>
        </form>

        <!-- Quick Date Presets and Count Display -->
        <div style="margin-top:12px;padding-top:10px;border-top:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;font-size:0.78rem;">
            <div class="flex items-center gap-1.5 flex-wrap">
                <span style="color:#64748B;font-weight:500;">Registration Presets:</span>
                <button type="button" class="btn btn-ghost btn-xs js-customer-date-preset" data-preset="today" style="font-size:0.75rem;padding:2px 8px;border-radius:6px;background:#F8FAFC;border:1px solid #E2E8F0;">Today</button>
                <button type="button" class="btn btn-ghost btn-xs js-customer-date-preset" data-preset="this-week" style="font-size:0.75rem;padding:2px 8px;border-radius:6px;background:#F8FAFC;border:1px solid #E2E8F0;">This Week</button>
                <button type="button" class="btn btn-ghost btn-xs js-customer-date-preset" data-preset="this-month" style="font-size:0.75rem;padding:2px 8px;border-radius:6px;background:#F8FAFC;border:1px solid #E2E8F0;">This Month</button>
                <button type="button" class="btn btn-ghost btn-xs js-customer-date-preset" data-preset="all-time" style="font-size:0.75rem;padding:2px 8px;border-radius:6px;background:#F8FAFC;border:1px solid #E2E8F0;">All Time</button>
            </div>

            <div style="color:#475569;">
                <?php if (!empty($fromDate) || !empty($toDate)): ?>
                <span style="background:#EFF6FF;color:#1D4ED8;padding:3px 10px;border-radius:999px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                    📅 Registered: <?= View::e($fromDate ? date('d M Y', strtotime($fromDate)) : 'Start') ?> — <?= View::e($toDate ? date('d M Y', strtotime($toDate)) : 'Present') ?> (<strong><?= (int)$total ?></strong> records)
                </span>
                <?php else: ?>
                <span class="text-muted" id="search-status" style="font-size:var(--font-size-xs)" aria-live="polite">Total: <strong><?= (int)$total ?></strong> clients</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Status Filters: Active, Test, All -->
    <div class="services-filters mb-6">
        <a href="<?= View::e($filterUrl(['status' => null, 'page' => null])) ?>" class="chip<?= ($status ?? '') === '' ? ' is-selected' : '' ?>">Active customers</a>
        <a href="<?= View::e($filterUrl(['status' => 'test', 'page' => null])) ?>" class="chip<?= ($status ?? '') === 'test' ? ' is-selected' : '' ?>">Test accounts</a>
        <a href="<?= View::e($filterUrl(['status' => 'all', 'page' => null])) ?>" class="chip<?= ($status ?? '') === 'all' ? ' is-selected' : '' ?>">All</a>
    </div>

    <?php if (empty($customers)): ?>
    <p class="text-muted text-center" style="padding:40px 16px;">No customers match this search or selected date range.</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="data-table" id="customers-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Category</th>
                    <th>Assessment</th>
                    <th>Bookings</th>
                    <th>Total Paid</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer):
                    $stage = ClientProfile::stageReached([
                        'health_assessment' => $customer['health_assessment'] !== null ? json_decode($customer['health_assessment'], true) : null,
                    ]);
                    $bookingCount = (int) $customer['booking_count'];
                    $searchable = strtolower(trim(
                        ($customer['name'] ?? '') . ' ' . ($customer['email'] ?? '') . ' '
                        . ($customer['phone'] ?? '') . ' ' . preg_replace('/\D/', '', (string) ($customer['phone'] ?? ''))
                    ));
                ?>
                <tr data-search="<?= View::e($searchable) ?>">
                    <td><strong><?= View::e($customer['name']) ?></strong></td>
                    <td><?= View::e($customer['email'] ?: '—') ?></td>
                    <td><?= View::e($customer['phone'] ?: '—') ?></td>
                    <td><?= View::e($customer['client_category'] ? ucwords(str_replace('_', ' ', $customer['client_category'])) : '—') ?></td>
                    <td>
                        <?php if ($stage >= 5): ?>
                        <span class="badge badge-confirmed">Complete</span>
                        <?php elseif ($stage >= 2): ?>
                        <span class="badge badge-pending">Stage <?= (int) $stage ?> of 5</span>
                        <?php else: ?>
                        <span class="badge badge-cancelled">Not started</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $bookingCount ?></td>
                    <td>₹<?= number_format((float) $customer['total_paid'], 2) ?></td>
                    <td>
                        <?php if ($customer['status'] === 'test'): ?>
                        <span class="badge badge-pending">TEST</span>
                        <?php else: ?>
                        <span class="badge badge-confirmed">Active</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="/admin/customers/<?= (int) $customer['id'] ?>" class="btn btn-xs btn-secondary">View Profile</a>
                            <button type="button" class="btn btn-xs btn-ghost admin-tile__delete delete-customer-btn"
                                    data-id="<?= (int) $customer['id'] ?>" data-name="<?= View::e($customer['name']) ?>"
                                    data-bookings="<?= $bookingCount ?>"
                                    title="Delete client" aria-label="Delete <?= View::e($customer['name']) ?>">
                                <?= View::icon('trash', 'icon', 14) ?>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <p class="text-muted mt-3" style="font-size:var(--font-size-xs)" id="table-summary">
        Showing <?= count($customers) ?> of <?= (int) ($total ?? count($customers)) ?> matching client(s).
    </p>

    <?php if ($totalPages > 1): ?>
    <div class="flex items-center justify-center gap-3 mt-8">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="<?= View::e($filterUrl(['page' => $p])) ?>" class="chip<?= $p === $page ? ' is-selected' : '' ?>"><?= $p ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<!-- =========================================================================
     Download Customers by Custom Date Modal
     ========================================================================= -->
<div id="excel-date-modal" class="excel-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="excel-modal-title">
    <div class="excel-modal-card">
        <div style="padding:16px 20px;background:#107C41;color:#ffffff;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="8" y1="13" x2="16" y2="13"></line>
                    <line x1="8" y1="17" x2="16" y2="17"></line>
                </svg>
                <div>
                    <h3 id="excel-modal-title" style="margin:0;font-size:1.05rem;font-weight:700;line-height:1.2;">Download Customers Excel</h3>
                    <p style="margin:2px 0 0;font-size:0.75rem;opacity:0.9;">Export client directory spreadsheet (.xlsx)</p>
                </div>
            </div>
            <button type="button" id="close-excel-modal-btn" style="background:transparent;border:none;color:#ffffff;font-size:1.5rem;line-height:1;cursor:pointer;opacity:0.85;padding:0 4px;" title="Close">&times;</button>
        </div>

        <form method="GET" action="/admin/customers/export-excel" style="padding:20px;">
            <?php if ($q): ?>
            <input type="hidden" name="q" value="<?= View::e($q) ?>">
            <?php endif; ?>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:#334155;margin-bottom:5px;">Registration From</label>
                    <input type="date" name="from_date" id="modal-from-date" value="<?= View::e($fromDate ?? '') ?>" class="form-group__control" style="width:100%;padding:7px 10px;font-size:0.85rem;border-radius:8px;border:1px solid #CBD5E1;">
                </div>
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:#334155;margin-bottom:5px;">Registration To</label>
                    <input type="date" name="to_date" id="modal-to-date" value="<?= View::e($toDate ?? '') ?>" class="form-group__control" style="width:100%;padding:7px 10px;font-size:0.85rem;border-radius:8px;border:1px solid #CBD5E1;">
                </div>
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;color:#334155;margin-bottom:5px;">Funnel Segment</label>
                <select name="segment" class="form-group__control" style="width:100%;padding:7px 10px;font-size:0.85rem;border-radius:8px;border:1px solid #CBD5E1;">
                    <option value="" <?= $segment === '' ? 'selected' : '' ?>>All Clients</option>
                    <option value="no_booking" <?= $segment === 'no_booking' ? 'selected' : '' ?>>Signed up, not booked</option>
                    <option value="booked" <?= $segment === 'booked' ? 'selected' : '' ?>>Booked a session</option>
                </select>
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;color:#334155;margin-bottom:5px;">Account Status</label>
                <select name="status" class="form-group__control" style="width:100%;padding:7px 10px;font-size:0.85rem;border-radius:8px;border:1px solid #CBD5E1;">
                    <option value="" <?= ($status ?? '') === '' ? 'selected' : '' ?>>Active accounts (Default)</option>
                    <option value="all" <?= ($status ?? '') === 'all' ? 'selected' : '' ?>>All accounts (including test)</option>
                    <option value="test" <?= ($status ?? '') === 'test' ? 'selected' : '' ?>>Test accounts only</option>
                </select>
            </div>

            <div style="display:flex;align-items:center;justify-content:flex-end;gap:10px;padding-top:14px;border-top:1px solid #E2E8F0;">
                <button type="button" id="cancel-excel-modal-btn" class="btn btn-secondary btn-sm" style="padding:7px 14px;font-size:0.82rem;border-radius:8px;">Cancel</button>
                <button type="submit" class="btn-excel" style="padding:7px 18px;font-size:0.85rem;border-radius:8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    <span>Download Excel Sheet</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

// Date Presets Handler
document.querySelectorAll('.js-customer-date-preset').forEach((btn) => {
    btn.addEventListener('click', () => {
        const preset = btn.dataset.preset;
        const fromInput = document.getElementById('filter-from-date');
        const toInput = document.getElementById('filter-to-date');
        const form = document.getElementById('customer-search-form');
        const now = new Date();

        const formatDate = (d) => {
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        if (preset === 'today') {
            const todayStr = formatDate(now);
            fromInput.value = todayStr;
            toInput.value = todayStr;
        } else if (preset === 'this-week') {
            const dayOfWeek = now.getDay() || 7;
            const monday = new Date(now);
            monday.setDate(now.getDate() - dayOfWeek + 1);
            const sunday = new Date(monday);
            sunday.setDate(monday.getDate() + 6);
            fromInput.value = formatDate(monday);
            toInput.value = formatDate(sunday);
        } else if (preset === 'this-month') {
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            fromInput.value = formatDate(firstDay);
            toInput.value = formatDate(lastDay);
        } else if (preset === 'all-time') {
            fromInput.value = '';
            toInput.value = '';
        }

        form.submit();
    });
});

// Download by Date Modal Controls
const excelModal = document.getElementById('excel-date-modal');
const openModalBtn = document.getElementById('btn-open-excel-date-modal');
const closeModalBtn = document.getElementById('close-excel-modal-btn');
const cancelModalBtn = document.getElementById('cancel-excel-modal-btn');

function openExcelModal() {
    if (!excelModal) return;
    excelModal.classList.add('is-active');
    document.body.style.overflow = 'hidden';
}

function closeExcelModal() {
    if (!excelModal) return;
    excelModal.classList.remove('is-active');
    document.body.style.overflow = '';
}

openModalBtn?.addEventListener('click', openExcelModal);
closeModalBtn?.addEventListener('click', closeExcelModal);
cancelModalBtn?.addEventListener('click', closeExcelModal);

excelModal?.addEventListener('click', (e) => {
    if (e.target === excelModal) closeExcelModal();
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && excelModal?.classList.contains('is-active')) {
        closeExcelModal();
    }
});

// Search instant filter
(function () {
    const input = document.getElementById('customer-search');
    const table = document.getElementById('customers-table');
    const statusEl = document.getElementById('search-status');
    if (!input) return;

    const rows = table ? Array.from(table.querySelectorAll('tbody tr')) : [];
    const totalPages = <?= (int) ($totalPages ?? 1) ?>;

    function applyFilter() {
        const term = input.value.trim().toLowerCase();
        const digits = term.replace(/\D/g, '');
        let shown = 0;

        rows.forEach((row) => {
            const haystack = row.dataset.search || '';
            const match = term === ''
                || haystack.includes(term)
                || (digits.length >= 3 && haystack.includes(digits));
            row.hidden = !match;
            if (match) shown++;
        });

        if (term === '') {
            statusEl.textContent = 'Total: <?= (int)$total ?> clients';
        } else if (shown === 0) {
            statusEl.textContent = totalPages > 1
                ? 'No match on this page — press Enter to search all clients.'
                : 'No match.';
        } else {
            statusEl.textContent = `${shown} match${shown === 1 ? '' : 'es'} on this page`
                + (totalPages > 1 ? ' — press Enter to search all pages.' : '');
        }
    }

    input.addEventListener('input', applyFilter);
    if (input.value.trim() !== '') applyFilter();
})();

// Deleting client handler
document.querySelectorAll('.delete-customer-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const name = btn.dataset.name || 'this client';
        const bookings = Number(btn.dataset.bookings || 0);

        if (!confirm(`Are you sure you want to delete ${name}?`)) return;

        btn.classList.add('is-loading');
        btn.disabled = true;
        try {
            const res = await apiPost(`/admin/customers/${btn.dataset.id}/delete`, {});
            toast.success(res?.message ?? 'Client deleted.');
            window.location.reload();
        } catch (err) {
            toast.error(err.message);
            btn.classList.remove('is-loading');
            btn.disabled = false;
        }
    });
});
</script>
