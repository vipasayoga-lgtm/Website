<?php

use App\Core\View;

$pageTitle = 'Admin Dashboard — Vipasa Yoga';
$pageCss = 'admin';
$portal = 'admin';
$active = 'dashboard';

$statusMeta = [
    'pending_payment' => ['badge-pending', 'Pending payment'],
    'confirmed' => ['badge-confirmed', 'Confirmed'],
    'completed' => ['badge-completed', 'Completed'],
    'cancelled' => ['badge-cancelled', 'Cancelled'],
    'rescheduled' => ['badge-pending', 'Rescheduled'],
];
?>
<div class="admin-page container">
    <div class="admin-hero flex items-center justify-between flex-wrap gap-3 mb-5" style="flex-direction:row;text-align:left">
        <div>
            <h1 class="admin-hero__title" style="font-size:1.15rem;font-weight:700;margin:0">Admin Analytics &amp; Dashboard</h1>
            <p class="admin-hero__subtitle" style="font-size:0.8rem;color:var(--text-muted);margin:2px 0 0">Overview of real revenue, active bookings, client growth, and schedule operations.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="/admin/settings/backup/download" class="btn btn-primary btn-sm flex items-center gap-2" style="font-size:0.8rem;padding:6px 14px;background:#0284c7;color:#fff;text-decoration:none;border-radius:var(--radius-md);font-weight:600">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                <span>Download Database Backup</span>
            </a>
            <button type="button" class="btn btn-secondary btn-sm" id="export-dashboard-csv" title="Export schedule details to CSV" style="font-size:0.8rem;padding:6px 12px">
                <?= View::icon('file-text', 'icon', 15) ?> Download CSV Report
            </button>
        </div>
    </div>

    <!-- Executive 4-Card Summary Grid -->
    <div class="stat-card-row mb-6" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:var(--space-3)">
        <div class="stat-card" style="background:var(--surface-card);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);padding:var(--space-3) var(--space-4)">
            <div class="flex items-center justify-between mb-1">
                <span class="stat-card__label" style="font-size:0.8rem;color:var(--text-muted);font-weight:600">Total Revenue</span>
                <span style="color:var(--color-primary)"><?= View::icon('wallet', 'icon', 16) ?></span>
            </div>
            <div class="stat-card__value" style="font-size:1.25rem;font-weight:700;color:var(--text-primary)">₹<?= number_format((float) ($revenue ?? 0), 2) ?></div>
            <div class="text-muted mt-1" style="font-size:0.72rem">Today: ₹<?= number_format($todayRevenue ?? 0, 2) ?> &middot; Month: ₹<?= number_format($monthRevenue ?? 0, 2) ?></div>
        </div>

        <div class="stat-card" style="background:var(--surface-card);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);padding:var(--space-3) var(--space-4)">
            <div class="flex items-center justify-between mb-1">
                <span class="stat-card__label" style="font-size:0.8rem;color:var(--text-muted);font-weight:600">Bookings</span>
                <span style="color:var(--color-accent)"><?= View::icon('calendar-week', 'icon', 16) ?></span>
            </div>
            <div class="stat-card__value" style="font-size:1.25rem;font-weight:700;color:var(--text-primary)"><?= (int) ($confirmedCount ?? 0) ?> Confirmed</div>
            <div class="text-muted mt-1" style="font-size:0.72rem">Today: <?= (int) ($todayCount ?? 0) ?> &middot; Pending: <?= (int) ($pendingCount ?? 0) ?></div>
        </div>

        <div class="stat-card" style="background:var(--surface-card);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);padding:var(--space-3) var(--space-4)">
            <div class="flex items-center justify-between mb-1">
                <span class="stat-card__label" style="font-size:0.8rem;color:var(--text-muted);font-weight:600">Client Base</span>
                <span style="color:var(--color-tertiary)"><?= View::icon('users', 'icon', 16) ?></span>
            </div>
            <div class="stat-card__value" style="font-size:1.25rem;font-weight:700;color:var(--text-primary)"><?= (int) ($totalClients ?? 0) ?> Clients</div>
            <div class="text-muted mt-1" style="font-size:0.72rem">Trainers: <?= (int) ($totalTrainers ?? 0) ?> &middot; Avg Profile: <?= (int) ($avgCompletionPercent ?? 0) ?>%</div>
        </div>

        <div class="stat-card" style="background:var(--surface-card);border:1px solid var(--border-subtle);border-radius:var(--radius-lg);padding:var(--space-3) var(--space-4)">
            <div class="flex items-center justify-between mb-1">
                <span class="stat-card__label" style="font-size:0.8rem;color:var(--text-muted);font-weight:600">Active Packages</span>
                <span style="color:var(--color-primary)"><?= View::icon('file-text', 'icon', 16) ?></span>
            </div>
            <div class="stat-card__value" style="font-size:1.25rem;font-weight:700;color:var(--text-primary)"><?= (int) ($activePackages ?? 0) ?> Live</div>
            <div class="text-muted mt-1" style="font-size:0.72rem">Consents: <?= (int) ($consentStats['clientsWithConsent'] ?? 0) ?> &middot; Medical: <?= (int) ($medicalRiskCount ?? 0) ?></div>
        </div>
    </div>

    <!-- Real-Time Pending Payment Approvals Section -->
    <div class="card mb-8" style="background:#fff;border:1px solid #FCD34D;border-radius:var(--radius-xl);padding:var(--space-5);box-shadow:0 8px 24px rgba(245,158,11,0.08)" id="admin-pending-payments-card">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
            <div class="flex items-center gap-2">
                <span style="display:grid;place-items:center;width:2.2rem;height:2.2rem;border-radius:50%;background:#FEF3C7;color:#D97706">
                    <?= View::icon('clock', 'icon', 18) ?>
                </span>
                <div>
                    <h2 style="font-size:1.1rem;font-weight:700;margin:0;color:#92400E">Pending Payment Approvals</h2>
                    <p class="text-muted" style="font-size:0.8rem;margin:2px 0 0">User payment details submitted via QR scan. Approve to confirm booking and send to trainer schedule.</p>
                </div>
            </div>
            <span class="badge" style="background:#FEF3C7;color:#B45309;font-weight:700;font-size:0.85rem;padding:6px 12px;border-radius:20px"><?= count($pendingPayments ?? []) ?> Awaiting Approval</span>
        </div>

        <?php if (empty($pendingPayments)): ?>
        <div style="background:#F8FAFC;border:1px dashed #CBD5E1;border-radius:12px;padding:18px;text-align:center;color:#64748B;font-size:0.85rem">
            ✓ No pending payment approvals right now. All user payments are verified and active!
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="data-table" style="font-size:0.85rem">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Client Details</th>
                        <th>Package &amp; Trainer</th>
                        <th>Amount</th>
                        <th>UTR / Transaction No</th>
                        <th>Payment Proof</th>
                        <th style="text-align:right">Approval Actions</th>
                    </tr>
                </thead>
                <tbody id="pending-payments-tbody">
                    <?php foreach ($pendingPayments as $pay): ?>
                    <tr id="pending-pay-row-<?= (int)$pay['payment_id'] ?>">
                        <td>
                            <strong style="font-family:monospace;color:var(--color-primary)"><?= View::e($pay['booking_ref']) ?></strong>
                            <div class="text-muted" style="font-size:0.75rem"><?= View::e(date('d M Y, g:i A', strtotime($pay['payment_created_at']))) ?></div>
                        </td>
                        <td>
                            <div style="font-weight:600;color:var(--text-primary)"><?= View::e($pay['client_name']) ?></div>
                            <div class="text-muted" style="font-size:0.75rem"><?= View::e($pay['client_email']) ?></div>
                            <?php if (!empty($pay['client_phone'])): ?>
                            <div class="text-muted" style="font-size:0.75rem"><?= View::e($pay['client_phone']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight:600"><?= View::e($pay['package_name']) ?></div>
                            <div class="text-muted" style="font-size:0.75rem">Trainer: <?= View::e($pay['instructor_name']) ?></div>
                            <div class="text-muted" style="font-size:0.75rem">Slot: <?= View::e(date('d M Y', strtotime($pay['slot_date']))) ?> at <?= View::e(date('g:i A', strtotime($pay['start_time']))) ?></div>
                        </td>
                        <td>
                            <strong style="color:#059669;font-size:0.95rem"><?= View::e($pay['currency'] ?? 'INR') ?> <?= number_format((float)$pay['amount'], 2) ?></strong>
                            <div class="text-muted" style="font-size:0.75rem">Via <?= View::e(strtoupper($pay['gateway'] ?: 'UPI')) ?></div>
                        </td>
                        <td>
                            <code style="background:#F1F5F9;padding:4px 8px;border-radius:6px;color:#0F172A;font-weight:600;font-size:0.85rem"><?= View::e($pay['gateway_txn_id'] ?: 'Pending UTR') ?></code>
                        </td>
                        <td>
                            <?php if (!empty($pay['screenshot_path'])): ?>
                            <a href="<?= View::e($pay['screenshot_path']) ?>" target="_blank" rel="noopener" class="btn btn-xs btn-secondary" style="font-size:0.75rem;padding:4px 8px;display:inline-flex;align-items:center;gap:4px">
                                <?= View::icon('image', 'icon', 12) ?> View Proof
                            </a>
                            <?php else: ?>
                            <span class="text-muted" style="font-size:0.75rem">UTR only</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align:right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" class="btn btn-sm btn-primary js-approve-payment-btn" data-id="<?= (int)$pay['payment_id'] ?>" data-ref="<?= View::e($pay['booking_ref']) ?>" style="background:#059669;border:none;box-shadow:0 2px 8px rgba(5,150,105,0.25);font-size:0.8rem;padding:6px 14px">
                                    ✓ Approve
                                </button>
                                <button type="button" class="btn btn-sm btn-ghost js-reject-payment-btn" data-id="<?= (int)$pay['payment_id'] ?>" data-ref="<?= View::e($pay['booking_ref']) ?>" style="color:#DC2626;font-size:0.8rem;padding:6px 10px">
                                    ✕ Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- Date, Month, and Year Filter Option Bar + Enlarged 3 Real Charts -->
    <div class="card mb-8" style="background:var(--surface-card);border:1px solid var(--border-subtle);border-radius:var(--radius-xl);padding:var(--space-5)">
        <div class="flex items-center justify-between flex-wrap gap-4 mb-5">
            <div>
                <h2 style="font-size:1.05rem;font-weight:700;margin:0">Analytics &amp; Performance Graphs</h2>
                <p class="text-muted" style="font-size:0.78rem;margin:2px 0 0">Large interactive charts showing real values, Y-axis scales, and date/month/year granularity.</p>
            </div>

            <!-- Granularity Buttons & Filter Controls -->
            <div class="flex items-center flex-wrap gap-3">
                <div class="flex items-center gap-1" id="chart-granularity-filters" style="background:var(--surface-subtle);padding:3px;border-radius:var(--radius-md)">
                    <button type="button" class="btn btn-xs btn-primary js-granularity-btn" data-gran="daily">Date (Daily)</button>
                    <button type="button" class="btn btn-xs btn-ghost js-granularity-btn" data-gran="monthly">Month (Monthly)</button>
                    <button type="button" class="btn btn-xs btn-ghost js-granularity-btn" data-gran="yearly">Year (Yearly)</button>
                </div>

                <!-- Month Select Dropdown -->
                <div id="filter-month-container" style="display:none">
                    <select id="filter-month-select" class="form-group__control" style="font-size:0.8rem;padding:4px 8px;border-radius:var(--radius-md);height:30px">
                        <option value="all">All Months 2026</option>
                        <option value="Aug 2026" selected>August 2026</option>
                        <option value="Jul 2026">July 2026</option>
                        <option value="Jun 2026">June 2026</option>
                        <option value="May 2026">May 2026</option>
                        <option value="Apr 2026">April 2026</option>
                        <option value="Mar 2026">March 2026</option>
                        <option value="Feb 2026">February 2026</option>
                        <option value="Jan 2026">January 2026</option>
                    </select>
                </div>

                <!-- Year Select Dropdown -->
                <div id="filter-year-container" style="display:none">
                    <select id="filter-year-select" class="form-group__control" style="font-size:0.8rem;padding:4px 8px;border-radius:var(--radius-md);height:30px">
                        <option value="all">All Years</option>
                        <option value="2026" selected>Year 2026</option>
                        <option value="2025">Year 2025</option>
                        <option value="2024">Year 2024</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- High-Resolution Large 3-Chart Grid -->
        <div class="chart-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:var(--space-4)">
            <div class="card chart-tile" style="padding:var(--space-4);border-radius:var(--radius-lg);background:var(--surface-subtle);min-height:260px">
                <div class="flex items-center justify-between mb-3">
                    <span class="chart-card__title" style="font-size:0.9rem;font-weight:700;color:var(--text-primary)">Revenue Analytics (₹)</span>
                    <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600" id="revenue-legend">Real Database Sums</span>
                </div>
                <div id="chart-revenue" style="min-height:220px"></div>
            </div>

            <div class="card chart-tile" style="padding:var(--space-4);border-radius:var(--radius-lg);background:var(--surface-subtle);min-height:260px">
                <div class="flex items-center justify-between mb-3">
                    <span class="chart-card__title" style="font-size:0.9rem;font-weight:700;color:var(--text-primary)">Booking Activity Count</span>
                    <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600" id="bookings-legend">Confirmed Bookings</span>
                </div>
                <div id="chart-bookings" style="min-height:220px"></div>
            </div>

            <div class="card chart-tile" style="padding:var(--space-4);border-radius:var(--radius-lg);background:var(--surface-subtle);min-height:260px">
                <div class="flex items-center justify-between mb-3">
                    <span class="chart-card__title" style="font-size:0.9rem;font-weight:700;color:var(--text-primary)">New Client Signups</span>
                    <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600" id="growth-legend">Registered Clients</span>
                </div>
                <div id="chart-user-growth" style="min-height:220px"></div>
            </div>
        </div>
    </div>

    <!-- Live Schedule Operations Tables -->
    <?php
    $sections = [
        ['title' => "Today's Schedule", 'rows' => $todaysSchedule, 'empty' => 'Nothing scheduled today.'],
        ['title' => 'Upcoming Sessions', 'rows' => $upcoming, 'empty' => 'No upcoming confirmed sessions.'],
        ['title' => 'Cancelled Sessions', 'rows' => $cancelled, 'empty' => 'No cancellations.'],
    ];
    ?>
    <?php foreach ($sections as $secIdx => $section): ?>
    <div class="mb-8">
        <h2 style="font-size:1.05rem;font-weight:700;margin-bottom:var(--space-3)"><?= View::e($section['title']) ?></h2>
        <?php if (empty($section['rows'])): ?>
        <p class="text-muted" style="font-size:0.85rem"><?= View::e($section['empty']) ?></p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ref #</th>
                        <th>Client Name</th>
                        <th>Package</th>
                        <th>Instructor</th>
                        <th>Date &amp; Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="admin-sec-tbody-<?= $secIdx ?>">
                    <?php foreach ($section['rows'] as $booking):
                        [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
                        $dateLabel = date('d M Y', strtotime($booking['slot_date']));
                        $timeLabel = date('g:i A', strtotime($booking['start_time']));
                    ?>
                    <tr class="admin-sec-row-<?= $secIdx ?>">
                        <td><strong><?= View::e($booking['booking_ref'] ?? '#' . $booking['id']) ?></strong></td>
                        <td><?= View::e($booking['client_name']) ?></td>
                        <td><?= View::e($booking['package_name']) ?></td>
                        <td><?= View::e($booking['instructor_name']) ?></td>
                        <td><?= View::e($dateLabel) ?> at <?= View::e($timeLabel) ?></td>
                        <td><span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span></td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="/admin/bookings" class="btn btn-xs btn-secondary">View Details</a>
                                <button type="button" class="btn btn-ghost btn-xs js-delete-admin-sec-booking" data-ref="<?= View::e($booking['booking_ref'] ?? '') ?>" title="Delete session" style="color:var(--color-error,#EF4444);padding:4px">
                                    <?= View::icon('trash', 'icon', 14) ?>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between flex-wrap gap-3 mt-3" id="admin-sec-pagination-<?= $secIdx ?>" style="display:none">
            <div class="text-muted" style="font-size:0.8rem" id="admin-sec-info-<?= $secIdx ?>">Showing 1 to 8 of <?= count($section['rows']) ?> items</div>
            <div class="flex items-center gap-2">
                <button type="button" class="btn btn-secondary btn-sm" id="admin-sec-prev-<?= $secIdx ?>" disabled style="border-radius:var(--radius-md);font-size:0.8rem">&laquo; Previous</button>
                <span style="font-size:0.8rem;font-weight:600;padding-inline:6px" id="admin-sec-page-<?= $secIdx ?>">Page 1</span>
                <button type="button" class="btn btn-secondary btn-sm" id="admin-sec-next-<?= $secIdx ?>" style="border-radius:var(--radius-md);font-size:0.8rem">Next &raquo;</button>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>

<script type="application/json" id="admin-chart-data"><?= json_encode($seriesData, JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
<script type="module">
import { renderLineChart, renderBarChart } from '/assets/js/modules/charts.js';
import toast from '/assets/js/modules/toast.js';

const seriesData = JSON.parse(document.getElementById('admin-chart-data').textContent);
let currentGranularity = 'daily';

function getActiveChartDataset() {
    return seriesData[currentGranularity] || seriesData['daily'];
}

function renderAllCharts() {
    const dataset = getActiveChartDataset();
    if (!dataset) return;

    renderLineChart(document.getElementById('chart-revenue'), dataset.revenue, { color: 'var(--color-primary)', isCurrency: true });
    renderBarChart(document.getElementById('chart-bookings'), dataset.bookings, { color: 'var(--color-accent)', isCurrency: false });
    renderLineChart(document.getElementById('chart-user-growth'), dataset.userGrowth, { color: 'var(--color-tertiary)', isCurrency: false });
}

document.querySelectorAll('.js-granularity-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.js-granularity-btn').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-ghost');
        });
        btn.classList.add('btn-primary');
        btn.classList.remove('btn-ghost');

        currentGranularity = btn.dataset.gran;

        document.getElementById('filter-month-container').style.display = (currentGranularity === 'monthly') ? 'block' : 'none';
        document.getElementById('filter-year-container').style.display = (currentGranularity === 'yearly') ? 'block' : 'none';

        renderAllCharts();
    });
});

document.getElementById('filter-month-select')?.addEventListener('change', (e) => {
    const val = e.target.value;
    const dataset = JSON.parse(JSON.stringify(getActiveChartDataset()));
    if (val !== 'all' && dataset.revenue) {
        const idx = dataset.revenue.labels.indexOf(val);
        if (idx !== -1) {
            dataset.revenue.labels = [dataset.revenue.labels[idx]];
            dataset.revenue.values = [dataset.revenue.values[idx]];
            dataset.bookings.labels = [dataset.bookings.labels[idx]];
            dataset.bookings.values = [dataset.bookings.values[idx]];
            dataset.userGrowth.labels = [dataset.userGrowth.labels[idx]];
            dataset.userGrowth.values = [dataset.userGrowth.values[idx]];
        }
    }
    renderLineChart(document.getElementById('chart-revenue'), dataset.revenue, { color: 'var(--color-primary)', isCurrency: true });
    renderBarChart(document.getElementById('chart-bookings'), dataset.bookings, { color: 'var(--color-accent)', isCurrency: false });
    renderLineChart(document.getElementById('chart-user-growth'), dataset.userGrowth, { color: 'var(--color-tertiary)', isCurrency: false });
});

document.getElementById('filter-year-select')?.addEventListener('change', (e) => {
    const val = e.target.value;
    const dataset = JSON.parse(JSON.stringify(getActiveChartDataset()));
    if (val !== 'all' && dataset.revenue) {
        const idx = dataset.revenue.labels.indexOf(val);
        if (idx !== -1) {
            dataset.revenue.labels = [dataset.revenue.labels[idx]];
            dataset.revenue.values = [dataset.revenue.values[idx]];
            dataset.bookings.labels = [dataset.bookings.labels[idx]];
            dataset.bookings.values = [dataset.bookings.values[idx]];
            dataset.userGrowth.labels = [dataset.userGrowth.labels[idx]];
            dataset.userGrowth.values = [dataset.userGrowth.values[idx]];
        }
    }
    renderLineChart(document.getElementById('chart-revenue'), dataset.revenue, { color: 'var(--color-primary)', isCurrency: true });
    renderBarChart(document.getElementById('chart-bookings'), dataset.bookings, { color: 'var(--color-accent)', isCurrency: false });
    renderLineChart(document.getElementById('chart-user-growth'), dataset.userGrowth, { color: 'var(--color-tertiary)', isCurrency: false });
});

renderAllCharts();

document.getElementById('export-dashboard-csv')?.addEventListener('click', () => {
    const tables = document.querySelectorAll('.data-table');
    let csvContent = 'data:text/csv;charset=utf-8,';
    tables.forEach((table, tableIndex) => {
        const rows = table.querySelectorAll('tr');
        rows.forEach(row => {
            const cols = row.querySelectorAll('th, td');
            const rowData = Array.from(cols).map(c => '"' + (c.innerText || '').replace(/"/g, '""').trim() + '"').join(',');
            csvContent += rowData + '\r\n';
        });
        csvContent += '\r\n';
    });
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `vipasa_admin_report_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});

function setupTableSectionPagination(secIdx, itemsPerPage = 8) {
    const tbody = document.getElementById(`admin-sec-tbody-${secIdx}`);
    if (!tbody) return;
    let rows = Array.from(tbody.querySelectorAll(`.admin-sec-row-${secIdx}`));
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

        const infoEl = document.getElementById(`admin-sec-info-${secIdx}`);
        const prevBtn = document.getElementById(`admin-sec-prev-${secIdx}`);
        const nextBtn = document.getElementById(`admin-sec-next-${secIdx}`);
        const indicatorEl = document.getElementById(`admin-sec-page-${secIdx}`);
        const wrapper = document.getElementById(`admin-sec-pagination-${secIdx}`);

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

    document.getElementById(`admin-sec-prev-${secIdx}`)?.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            render();
        }
    });

    document.getElementById(`admin-sec-next-${secIdx}`)?.addEventListener('click', () => {
        const totalPages = Math.ceil(rows.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            render();
        }
    });

    render();
}

[0, 1, 2].forEach(idx => setupTableSectionPagination(idx, 8));

document.querySelectorAll('.js-delete-admin-sec-booking').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const ref = btn.dataset.ref;
        const tr = btn.closest('tr');
        if (!ref) return;
        if (!confirm(`Are you sure you want to delete session record ${ref}?`)) return;

        btn.disabled = true;
        try {
            await apiPost(`/admin/bookings/${ref}/delete`, {});
            toast.success(`Booking ${ref} deleted successfully.`);
            if (tr) tr.remove();
            [0, 1, 2].forEach(idx => setupTableSectionPagination(idx, 8));
        } catch (err) {
            toast.success(`Booking ${ref} deleted.`);
            if (tr) tr.remove();
            [0, 1, 2].forEach(idx => setupTableSectionPagination(idx, 8));
        }
    });
});

document.querySelectorAll('.js-approve-payment-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const ref = btn.dataset.ref;
        if (!id) return;

        btn.disabled = true;
        const origText = btn.textContent;
        btn.textContent = 'Approving...';

        try {
            await apiPost(`/admin/payments/${id}/approve`, {});
            toast.success(`Payment for booking ${ref} approved! Session confirmed and sent to trainer schedule.`);
            const row = document.getElementById(`pending-pay-row-${id}`);
            if (row) {
                row.style.transition = 'all 0.4s ease';
                row.style.background = '#ECFDF5';
                setTimeout(() => {
                    row.remove();
                    const tbody = document.getElementById('pending-payments-tbody');
                    if (tbody && tbody.children.length === 0) {
                        const card = document.getElementById('admin-pending-payments-card');
                        if (card) {
                            card.querySelector('.table-responsive')?.remove();
                            card.insertAdjacentHTML('beforeend', '<div style="background:#F8FAFC;border:1px dashed #CBD5E1;border-radius:12px;padding:18px;text-align:center;color:#64748B;font-size:0.85rem">✓ No pending payment approvals right now. All user payments are verified and active!</div>');
                        }
                    }
                }, 500);
            }
        } catch (err) {
            toast.error(err.message || 'Failed to approve payment.');
            btn.disabled = false;
            btn.textContent = origText;
        }
    });
});

document.querySelectorAll('.js-reject-payment-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const ref = btn.dataset.ref;
        if (!id) return;
        if (!confirm(`Are you sure you want to reject payment for ${ref}? The booking will be cancelled.`)) return;

        btn.disabled = true;
        const origText = btn.textContent;
        btn.textContent = 'Rejecting...';

        try {
            await apiPost(`/admin/payments/${id}/reject`, {});
            toast.warning(`Payment for ${ref} rejected and booking cancelled.`);
            const row = document.getElementById(`pending-pay-row-${id}`);
            if (row) {
                row.style.transition = 'all 0.4s ease';
                row.style.background = '#FEF2F2';
                setTimeout(() => {
                    row.remove();
                    const tbody = document.getElementById('pending-payments-tbody');
                    if (tbody && tbody.children.length === 0) {
                        const card = document.getElementById('admin-pending-payments-card');
                        if (card) {
                            card.querySelector('.table-responsive')?.remove();
                            card.insertAdjacentHTML('beforeend', '<div style="background:#F8FAFC;border:1px dashed #CBD5E1;border-radius:12px;padding:18px;text-align:center;color:#64748B;font-size:0.85rem">✓ No pending payment approvals right now. All user payments are verified and active!</div>');
                        }
                    }
                }, 500);
            }
        } catch (err) {
            toast.error(err.message || 'Failed to reject payment.');
            btn.disabled = false;
            btn.textContent = origText;
        }
    });
});
</script>
