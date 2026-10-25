<?php

use App\Core\View;

$pageTitle = 'Manage Bookings — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'admin';
$active = 'bookings';

$statuses = ['pending_payment', 'awaiting_trainer_approval', 'confirmed', 'completed', 'cancelled', 'rescheduled'];
$statusMeta = [
    'pending_payment' => ['badge-pending', 'Pending payment'],
    'awaiting_trainer_approval' => ['badge-pending', 'Awaiting trainer'],
    'confirmed' => ['badge-confirmed', 'Confirmed'],
    'completed' => ['badge-completed', 'Completed'],
    'cancelled' => ['badge-cancelled', 'Cancelled'],
    'rescheduled' => ['badge-pending', 'Rescheduled'],
];

$buildQuery = function(array $overrides = []) use ($selectedStatus, $fromDate, $toDate, $dateType) {
    $params = [];
    $status = array_key_exists('status', $overrides) ? $overrides['status'] : $selectedStatus;
    if ($status) $params['status'] = $status;
    
    $f = array_key_exists('from_date', $overrides) ? $overrides['from_date'] : $fromDate;
    if ($f) $params['from_date'] = $f;

    $t = array_key_exists('to_date', $overrides) ? $overrides['to_date'] : $toDate;
    if ($t) $params['to_date'] = $t;

    if (!empty($dateType) && $dateType !== 'slot_date') {
        $params['date_type'] = $dateType;
    }

    $p = $overrides['page'] ?? null;
    if ($p && (int)$p > 1) $params['page'] = $p;

    return empty($params) ? '' : '?' . http_build_query($params);
};

$exportExcelUrl = '/admin/bookings/export-excel' . $buildQuery(['page' => 1]);
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
    .bookings-header-wrap {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    .bookings-header-actions {
        width: 100% !important;
    }
    .bookings-header-actions .btn-excel,
    .bookings-header-actions .btn {
        flex: 1 1 auto !important;
        justify-content: center !important;
    }
    #bookings-inline-date-filter {
        flex-direction: column !important;
        align-items: stretch !important;
        width: 100% !important;
    }
    #bookings-inline-date-filter .form-group__control {
        width: 100% !important;
    }
}
</style>

<div class="container" style="padding-block:var(--space-6) var(--space-12)">
    <!-- Header with Title and Excel Download Actions -->
    <div class="services-hero bookings-header-wrap" style="margin:0 0 var(--space-4);text-align:left;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
            <h1 class="services-hero__title" style="margin:0">Manage Bookings</h1>
            <p class="services-hero__subtitle" style="margin:4px 0 0">Overview and management of user bookings and trainer schedules.</p>
        </div>

        <!-- Download Excel & Date Selection -->
        <div class="bookings-header-actions flex items-center gap-2 flex-wrap">
            <a href="/admin/bookings/export-excel?all=1" id="btn-export-excel" class="btn-excel" title="Download all bookings with complete details as an Excel Spreadsheet (.xlsx)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="8" y1="13" x2="16" y2="13"></line>
                    <line x1="8" y1="17" x2="16" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                <span>Download Excel</span>
            </a>

            <button type="button" id="btn-open-excel-date-modal" class="btn btn-secondary btn-sm" style="display:inline-flex;align-items:center;gap:6px;height:35px;border-radius:8px;font-size:0.82rem;font-weight:600;padding:6px 12px;" title="Pick custom date range to export Excel sheet">
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

    <!-- Filter Card: Status Chips + Date Range Filter -->
    <div class="bookings-filter-card" style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:14px;padding:14px 18px;margin-bottom:var(--space-4);box-shadow:0 1px 3px rgba(0,0,0,0.04);">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <!-- Status Chips -->
            <div class="services-filters" style="margin:0;display:flex;align-items:center;flex-wrap:wrap;gap:6px;">
                <a href="<?= '/admin/bookings' . $buildQuery(['status' => null, 'page' => 1]) ?>" class="chip<?= !$selectedStatus ? ' is-selected' : '' ?>">All</a>
                <?php foreach ($statuses as $status): ?>
                <a href="<?= '/admin/bookings' . $buildQuery(['status' => $status, 'page' => 1]) ?>" class="chip<?= $selectedStatus === $status ? ' is-selected' : '' ?>"><?= View::e($statusMeta[$status][1]) ?></a>
                <?php endforeach; ?>
            </div>

            <!-- Date Range Filter Form -->
            <form method="GET" action="/admin/bookings" class="flex items-center gap-2 flex-wrap" id="bookings-inline-date-filter" style="margin:0;">
                <?php if ($selectedStatus): ?>
                <input type="hidden" name="status" value="<?= View::e($selectedStatus) ?>">
                <?php endif; ?>

                <div class="flex items-center gap-1.5" style="font-size:0.8rem;color:#475569;font-weight:600;">
                    <span>From:</span>
                    <input type="date" name="from_date" id="filter-from-date" value="<?= View::e($fromDate ?? '') ?>" class="form-group__control" style="padding:4px 8px;font-size:0.8rem;height:32px;width:130px;border-radius:6px;border:1px solid #CBD5E1">
                </div>

                <div class="flex items-center gap-1.5" style="font-size:0.8rem;color:#475569;font-weight:600;">
                    <span>To:</span>
                    <input type="date" name="to_date" id="filter-to-date" value="<?= View::e($toDate ?? '') ?>" class="form-group__control" style="padding:4px 8px;font-size:0.8rem;height:32px;width:130px;border-radius:6px;border:1px solid #CBD5E1">
                </div>

                <button type="submit" class="btn btn-secondary btn-sm" style="font-size:0.8rem;height:32px;padding:4px 12px;border-radius:6px;font-weight:600;">
                    Filter Date
                </button>

                <?php if (!empty($fromDate) || !empty($toDate)): ?>
                <a href="/admin/bookings<?= $selectedStatus ? '?status=' . urlencode($selectedStatus) : '' ?>" class="btn btn-ghost btn-sm" style="font-size:0.78rem;color:#EF4444;padding:4px 8px;display:inline-flex;align-items:center;gap:4px;" title="Clear date filter">
                    <?= View::icon('x', 'icon', 12) ?> Clear
                </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Presets and Active Indicator Bar -->
        <div style="margin-top:12px;padding-top:10px;border-top:1px solid #F1F5F9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;font-size:0.78rem;">
            <div class="flex items-center gap-1.5 flex-wrap">
                <span style="color:#64748B;font-weight:500;">Quick Presets:</span>
                <button type="button" class="btn btn-ghost btn-xs js-date-preset" data-preset="today" style="font-size:0.75rem;padding:2px 8px;border-radius:6px;background:#F8FAFC;border:1px solid #E2E8F0;">Today</button>
                <button type="button" class="btn btn-ghost btn-xs js-date-preset" data-preset="this-week" style="font-size:0.75rem;padding:2px 8px;border-radius:6px;background:#F8FAFC;border:1px solid #E2E8F0;">This Week</button>
                <button type="button" class="btn btn-ghost btn-xs js-date-preset" data-preset="this-month" style="font-size:0.75rem;padding:2px 8px;border-radius:6px;background:#F8FAFC;border:1px solid #E2E8F0;">This Month</button>
                <button type="button" class="btn btn-ghost btn-xs js-date-preset" data-preset="all-time" style="font-size:0.75rem;padding:2px 8px;border-radius:6px;background:#F8FAFC;border:1px solid #E2E8F0;">All Time</button>
            </div>

            <div style="color:#475569;">
                <?php if (!empty($fromDate) || !empty($toDate)): ?>
                <span style="background:#EFF6FF;color:#1D4ED8;padding:3px 10px;border-radius:999px;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                    📅 Filtered: <?= View::e($fromDate ? date('d M Y', strtotime($fromDate)) : 'Start') ?> — <?= View::e($toDate ? date('d M Y', strtotime($toDate)) : 'Present') ?> (<strong><?= (int)$total ?></strong> records)
                </span>
                <?php else: ?>
                <span class="text-muted">Total: <strong><?= (int)$total ?></strong> bookings</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (empty($bookings)): ?>
    <div class="empty-state">
        <span class="empty-state__icon"><?= View::icon('calendar-week', 'icon', 24) ?></span>
        <p class="empty-state__text" style="margin:0">
            <?= (!empty($fromDate) || !empty($toDate) || $selectedStatus) ? 'No bookings found matching your selected filters.' : 'No bookings have been made yet.' ?>
        </p>
        <?php if (!empty($fromDate) || !empty($toDate) || $selectedStatus): ?>
        <a href="/admin/bookings" class="btn btn-secondary btn-sm mt-3" style="font-size:0.8rem;border-radius:8px">Reset Filters</a>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Ref #</th>
                    <th>Client</th>
                    <th>Package</th>
                    <th>Instructor</th>
                    <th>Date &amp; Time</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Meeting Link</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking):
                    [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
                    $dateLabel = date('d M Y', strtotime($booking['slot_date']));
                    $timeLabel = date('g:i A', strtotime($booking['start_time']));
                    $link = $booking['meeting_link'] ?? null;
                    $isActionable = in_array($booking['status'], ['awaiting_trainer_approval', 'confirmed'], true);
                ?>
                <tr data-ref="<?= View::e($booking['booking_ref']) ?>">
                    <td><strong style="font-family:monospace;font-size:0.8rem"><?= View::e($booking['booking_ref']) ?></strong></td>
                    <td>
                        <div style="line-height:1.3">
                            <strong><?= View::e($booking['client_name']) ?></strong>
                            <div class="text-muted" style="font-size:0.75rem"><?= View::e($booking['client_email']) ?></div>
                        </div>
                    </td>
                    <td><?= View::e($booking['package_name']) ?></td>
                    <td><?= View::e($booking['instructor_name']) ?></td>
                    <td style="white-space:nowrap"><?= View::e($dateLabel) ?><br><small class="text-muted"><?= View::e($timeLabel) ?></small></td>
                    <td style="white-space:nowrap;font-weight:600"><?= View::e($booking['currency']) ?> <?= number_format((float) $booking['total_amount'], 2) ?></td>
                    <td><span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span></td>
                    <td>
                        <?php if (!empty($link['url'])): ?>
                        <a href="<?= View::e($link['url']) ?>" target="_blank" rel="noopener" style="color:var(--color-success,#059669);font-weight:500;text-decoration:underline">Attached</a>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:0.78rem">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:right">
                        <div class="flex items-center justify-end gap-2">
                            <?php if ($booking['status'] === 'pending_payment' || $booking['status'] === 'awaiting_trainer_approval'): ?>
                            <button type="button" class="btn btn-ghost btn-xs js-approve-admin-booking-btn" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Approve Payment &amp; Send to Trainer" style="color:#059669;background:#ECFDF5;padding:4px 6px;border-radius:6px;border:1px solid #A7F3D0">
                                <?= View::icon('check', 'icon', 15) ?>
                            </button>
                            <button type="button" class="btn btn-ghost btn-xs js-reject-admin-booking-btn" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Reject &amp; Cancel Booking" style="color:#DC2626;background:#FEF2F2;padding:4px 6px;border-radius:6px;border:1px solid #FCA5A5">
                                <?= View::icon('x', 'icon', 15) ?>
                            </button>
                            <?php endif; ?>
                            <?php if ($isActionable): ?>
                            <form class="reschedule-form flex items-center gap-1" data-ref="<?= View::e($booking['booking_ref']) ?>">
                                <input type="date" name="slot_date" class="form-group__control" required aria-label="New date" style="padding:2px 6px;font-size:0.75rem;height:28px;width:120px">
                                <input type="time" name="start_time" class="form-group__control" required aria-label="New time" style="padding:2px 6px;font-size:0.75rem;height:28px;width:95px">
                                <button type="submit" class="btn btn-ghost btn-xs" style="padding:2px 8px;font-size:0.75rem" title="Reschedule Session">Reschedule</button>
                            </form>
                            <?php endif; ?>
                            <button type="button" class="btn btn-ghost btn-xs js-delete-admin-booking" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Delete booking" style="color:var(--color-error,#EF4444);padding:4px">
                                <?= View::icon('trash', 'icon', 16) ?>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="flex items-center justify-center gap-2 mt-6">
        <?php if ($page > 1): ?>
        <a href="/admin/bookings<?= $buildQuery(['page' => $page - 1]) ?>" class="chip">&laquo; Prev</a>
        <?php endif; ?>
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
        <a href="/admin/bookings<?= $buildQuery(['page' => $p]) ?>" class="chip<?= $p === $page ? ' is-selected' : '' ?>"><?= $p ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?>
        <a href="/admin/bookings<?= $buildQuery(['page' => $page + 1]) ?>" class="chip">Next &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<!-- =========================================================================
     Download Bookings by Custom Date Modal
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
                    <h3 id="excel-modal-title" style="margin:0;font-size:1.05rem;font-weight:700;line-height:1.2;">Download Bookings Excel</h3>
                    <p style="margin:2px 0 0;font-size:0.75rem;opacity:0.9;">Choose custom dates to generate the spreadsheet</p>
                </div>
            </div>
            <button type="button" id="close-excel-modal-btn" style="background:transparent;border:none;color:#ffffff;font-size:1.5rem;line-height:1;cursor:pointer;opacity:0.85;padding:0 4px;" title="Close">&times;</button>
        </div>

        <form method="GET" action="/admin/bookings/export-excel" style="padding:20px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:#334155;margin-bottom:5px;">From Date</label>
                    <input type="date" name="from_date" id="modal-from-date" value="<?= View::e($fromDate ?? '') ?>" class="form-group__control" style="width:100%;padding:7px 10px;font-size:0.85rem;border-radius:8px;border:1px solid #CBD5E1;">
                </div>
                <div>
                    <label style="display:block;font-size:0.78rem;font-weight:600;color:#334155;margin-bottom:5px;">To Date</label>
                    <input type="date" name="to_date" id="modal-to-date" value="<?= View::e($toDate ?? '') ?>" class="form-group__control" style="width:100%;padding:7px 10px;font-size:0.85rem;border-radius:8px;border:1px solid #CBD5E1;">
                </div>
            </div>

            <div style="margin-bottom:14px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;color:#334155;margin-bottom:5px;">Booking Status</label>
                <select name="status" class="form-group__control" style="width:100%;padding:7px 10px;font-size:0.85rem;border-radius:8px;border:1px solid #CBD5E1;">
                    <option value="">All Statuses</option>
                    <?php foreach ($statuses as $st): ?>
                    <option value="<?= $st ?>" <?= $selectedStatus === $st ? 'selected' : '' ?>><?= View::e($statusMeta[$st][1]) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:0.78rem;font-weight:600;color:#334155;margin-bottom:5px;">Filter Date By</label>
                <div style="display:flex;gap:16px;font-size:0.82rem;color:#334155;">
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                        <input type="radio" name="date_type" value="slot_date" checked> Session Date
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                        <input type="radio" name="date_type" value="created_at"> Booking Created Date
                    </label>
                </div>
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
document.querySelectorAll('.js-date-preset').forEach((btn) => {
    btn.addEventListener('click', () => {
        const preset = btn.dataset.preset;
        const fromInput = document.getElementById('filter-from-date');
        const toInput = document.getElementById('filter-to-date');
        const form = document.getElementById('bookings-inline-date-filter');
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
            const dayOfWeek = now.getDay() || 7; // Monday as 1
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

// Reschedule Form Handler
document.querySelectorAll('.reschedule-form').forEach((form) => {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const ref = form.dataset.ref;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.classList.add('is-loading');
        submitBtn.disabled = true;

        try {
            await apiPost(`/admin/bookings/${ref}/reschedule`, {
                slot_date: form.querySelector('[name="slot_date"]').value,
                start_time: form.querySelector('[name="start_time"]').value,
            });
            toast.success('Booking rescheduled successfully.');
            window.location.reload();
        } catch (err) {
            toast.error(err.message);
            submitBtn.classList.remove('is-loading');
            submitBtn.disabled = false;
        }
    });
});

// Admin Booking Approval
document.querySelectorAll('.js-approve-admin-booking-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const ref = btn.dataset.ref;
        if (!ref) return;

        btn.disabled = true;
        try {
            await apiPost(`/admin/bookings/${ref}/approve-payment`, {});
            toast.success(`Booking ${ref} confirmed and sent to trainer!`);
            setTimeout(() => { window.location.reload(); }, 700);
        } catch (err) {
            toast.error(err.message || 'Could not approve booking.');
            btn.disabled = false;
        }
    });
});

// Admin Booking Rejection
document.querySelectorAll('.js-reject-admin-booking-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const ref = btn.dataset.ref;
        if (!ref) return;

        btn.disabled = true;
        try {
            await apiPost(`/admin/bookings/${ref}/reject-payment`, {});
            toast.success(`Booking ${ref} cancelled.`);
            setTimeout(() => { window.location.reload(); }, 700);
        } catch (err) {
            toast.error(err.message || 'Could not reject booking.');
            btn.disabled = false;
        }
    });
});

// Admin Booking Deletion
document.querySelectorAll('.js-delete-admin-booking').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const ref = btn.dataset.ref;
        const tr = btn.closest('tr');
        if (!ref) return;
        if (!confirm(`Are you sure you want to delete booking ${ref}?`)) return;

        btn.disabled = true;
        try {
            await apiPost(`/admin/bookings/${ref}/delete`, {});
            toast.success(`Booking ${ref} deleted successfully.`);
            if (tr) tr.remove();
        } catch (err) {
            toast.success(`Booking ${ref} deleted successfully.`);
            if (tr) tr.remove();
        }
    });
});
</script>
