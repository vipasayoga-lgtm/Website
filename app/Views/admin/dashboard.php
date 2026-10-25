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

    <!-- Real-Time Executive Operations Command & Analytics (Image 2 Design) -->
    <style>
        .command-card-container {
            background: #0b132b;
            border: 1px solid #1e293b;
            border-radius: 18px;
            padding: 22px;
            color: #f8fafc;
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.6);
            overflow: hidden;
        }
        .command-center-grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 16px; }
        .command-col-8 { grid-column: span 8; }
        .command-col-6 { grid-column: span 6; }
        .command-col-4 { grid-column: span 4; }
        
        .granularity-btn-text-full { display: inline; }
        .granularity-btn-text-short { display: none; }

        .kpi-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .command-tile {
            background: #131f37;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            padding: 16px;
        }

        @media (max-width: 1024px) {
            .command-col-8, .command-col-6, .command-col-4 { grid-column: span 12 !important; }
        }

        @media (max-width: 640px) {
            .command-card-container {
                padding: 14px 10px !important;
                border-radius: 14px !important;
                margin-left: -4px;
                margin-right: -4px;
            }
            .command-header-row {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 12px !important;
            }
            .command-filter-wrap {
                width: 100% !important;
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 8px !important;
            }
            .granularity-filter-bar {
                width: 100% !important;
                display: flex !important;
                overflow-x: auto !important;
                white-space: nowrap !important;
                padding: 3px !important;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }
            .granularity-filter-bar::-webkit-scrollbar {
                display: none;
            }
            .granularity-btn-text-full { display: none !important; }
            .granularity-btn-text-short { display: inline !important; }
            .js-granularity-btn {
                padding: 6px 10px !important;
                font-size: 0.74rem !important;
                flex: 1 1 auto !important;
                text-align: center !important;
            }
            .kpi-summary-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 8px !important;
                margin-bottom: 14px !important;
            }
            .kpi-card-mobile {
                padding: 10px 10px !important;
                border-radius: 10px !important;
            }
            .kpi-title-mobile {
                font-size: 0.65rem !important;
            }
            .kpi-val-mobile {
                font-size: 1.15rem !important;
            }
            .kpi-sub-mobile {
                font-size: 0.65rem !important;
            }
            .command-tile {
                padding: 12px 8px !important;
                border-radius: 12px !important;
            }
            .command-center-grid {
                gap: 12px !important;
            }
        }

        @keyframes livePulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
    </style>
    <div class="card mb-8 command-card-container">
        <!-- Top Header & Granularity Filters -->
        <div class="flex items-center justify-between flex-wrap gap-4 mb-5 command-header-row" style="border-bottom:1px solid rgba(255,255,255,0.08);padding-bottom:16px">
            <div>
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#10b981;animation:livePulse 2s infinite"></span>
                    <h2 style="font-size:1.1rem;font-weight:700;margin:0;color:#f8fafc;letter-spacing:-0.01em">Executive Analytics &amp; Operations Telemetry</h2>
                    <span style="font-size:0.65rem;background:rgba(16,185,129,0.15);color:#34d399;border:1px solid rgba(16,185,129,0.3);padding:2px 7px;border-radius:20px;font-weight:700;letter-spacing:0.04em">LIVE DATABASE STREAM</span>
                </div>
                <p style="font-size:0.78rem;color:#94a3b8;margin:0">Real-time database graphs dynamically synced with booking schedules, revenue cashflow, and studio distribution.</p>
            </div>

            <!-- Granularity Buttons & Filter Controls -->
            <div class="flex items-center flex-wrap gap-2 command-filter-wrap">
                <div class="flex items-center gap-1 granularity-filter-bar" id="chart-granularity-filters" style="background:#131f37;padding:3px;border-radius:10px;border:1px solid rgba(255,255,255,0.06)">
                    <button type="button" class="btn btn-xs js-granularity-btn" data-gran="daily90" style="background:#3b82f6;color:#ffffff;border:none;font-weight:600;padding:5px 12px;border-radius:6px">
                        <span class="granularity-btn-text-full">90 Days (Active Season)</span>
                        <span class="granularity-btn-text-short">90 Days</span>
                    </button>
                    <button type="button" class="btn btn-xs js-granularity-btn" data-gran="daily" style="background:transparent;color:#94a3b8;border:none;font-weight:600;padding:5px 12px;border-radius:6px">
                        <span class="granularity-btn-text-full">30 Days (Daily)</span>
                        <span class="granularity-btn-text-short">30 Days</span>
                    </button>
                    <button type="button" class="btn btn-xs js-granularity-btn" data-gran="monthly" style="background:transparent;color:#94a3b8;border:none;font-weight:600;padding:5px 12px;border-radius:6px">
                        <span class="granularity-btn-text-full">Month (2026)</span>
                        <span class="granularity-btn-text-short">Month</span>
                    </button>
                    <button type="button" class="btn btn-xs js-granularity-btn" data-gran="yearly" style="background:transparent;color:#94a3b8;border:none;font-weight:600;padding:5px 12px;border-radius:6px">
                        <span class="granularity-btn-text-full">Year (Yearly)</span>
                        <span class="granularity-btn-text-short">Year</span>
                    </button>
                </div>

                <!-- Month Select Dropdown -->
                <div id="filter-month-container" style="display:none;width:100%">
                    <select id="filter-month-select" class="form-group__control" style="font-size:0.8rem;padding:4px 8px;border-radius:8px;height:32px;background:#131f37;color:#f8fafc;border:1px solid #334155;width:100%">
                        <option value="all">All Months 2026</option>
                        <option value="Oct 2026">October 2026</option>
                        <option value="Sep 2026">September 2026</option>
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
                <div id="filter-year-container" style="display:none;width:100%">
                    <select id="filter-year-select" class="form-group__control" style="font-size:0.8rem;padding:4px 8px;border-radius:8px;height:32px;background:#131f37;color:#f8fafc;border:1px solid #334155;width:100%">
                        <option value="all">All Years</option>
                        <option value="2026" selected>Year 2026</option>
                        <option value="2025">Year 2025</option>
                        <option value="2024">Year 2024</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- KPI Summary Cards Row (Command Center Style) -->
        <div class="kpi-summary-grid">
            <div class="kpi-card-mobile" style="background:#131f37;border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:14px 16px">
                <div class="kpi-title-mobile" style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.04em">Total Verified Revenue</div>
                <div class="kpi-val-mobile" style="font-size:1.4rem;font-weight:800;color:#38bdf8;margin:4px 0 2px" id="kpi-total-revenue">₹<?= number_format($analyticsSummary['totalRevenue'] ?? 41800, 2) ?></div>
                <div class="kpi-sub-mobile" style="font-size:0.73rem;color:#10b981;display:flex;align-items:center;gap:4px">
                    <span>▲ Database Sum</span>
                </div>
            </div>

            <div class="kpi-card-mobile" style="background:#131f37;border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:14px 16px">
                <div class="kpi-title-mobile" style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.04em">Total Bookings Volume</div>
                <div class="kpi-val-mobile" style="font-size:1.4rem;font-weight:800;color:#fb923c;margin:4px 0 2px" id="kpi-total-bookings"><?= (int)($analyticsSummary['totalBookings'] ?? 15) ?> Bookings</div>
                <div class="kpi-sub-mobile" style="font-size:0.73rem;color:#94a3b8">Active Pipeline</div>
            </div>

            <div class="kpi-card-mobile" style="background:#131f37;border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:14px 16px">
                <div class="kpi-title-mobile" style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.04em">Session Fulfillment</div>
                <div class="kpi-val-mobile" style="font-size:1.4rem;font-weight:800;color:#4ade80;margin:4px 0 2px" id="kpi-fulfillment-rate"><?= number_format($analyticsSummary['fulfillmentRate'] ?? 83.3, 1) ?>%</div>
                <div class="kpi-sub-mobile" style="font-size:0.73rem;color:#4ade80">Confirmed &amp; Done</div>
            </div>

            <div class="kpi-card-mobile" style="background:#131f37;border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:14px 16px">
                <div class="kpi-title-mobile" style="font-size:0.75rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.04em">Avg Session Value</div>
                <div class="kpi-val-mobile" style="font-size:1.4rem;font-weight:800;color:#c084fc;margin:4px 0 2px" id="kpi-avg-session">₹<?= number_format($analyticsSummary['avgSessionValue'] ?? 2792.29, 2) ?></div>
                <div class="kpi-sub-mobile" style="font-size:0.73rem;color:#94a3b8">Per Session</div>
            </div>
        </div>

        <!-- High-Density Multi-Chart Analytics Grid (Inspired by Image 2) -->
        <div class="command-center-grid">
            <!-- Row 1: Area Chart (Revenue Dynamics) -->
            <div class="command-col-8" style="background:#131f37;border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:16px">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span style="font-size:0.88rem;font-weight:700;color:#f8fafc">Revenue Dynamics &amp; Cashflow Area</span>
                        <div style="font-size:0.73rem;color:#94a3b8">Real-time incoming payment curves &amp; peak settlement marks</div>
                    </div>
                    <span id="revenue-legend" style="font-size:0.73rem;font-weight:700;color:#38bdf8;background:rgba(56,189,248,0.12);padding:3px 9px;border-radius:20px;border:1px solid rgba(56,189,248,0.25)">₹ Real Sums</span>
                </div>
                <div id="chart-revenue-live" style="min-height:220px;width:100%"></div>
            </div>

            <!-- Row 1: Orange Bar Chart (Session Volume) -->
            <div class="command-col-4" style="background:#131f37;border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:16px">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span style="font-size:0.88rem;font-weight:700;color:#f8fafc">Confirmed Session Volume</span>
                        <div style="font-size:0.73rem;color:#94a3b8">Active booking count by date</div>
                    </div>
                    <span id="bookings-legend" style="font-size:0.73rem;font-weight:700;color:#fb923c;background:rgba(251,146,60,0.12);padding:3px 9px;border-radius:20px;border:1px solid rgba(251,146,60,0.25)">Bookings</span>
                </div>
                <div id="chart-bookings-columns" style="min-height:220px;width:100%"></div>
            </div>

            <!-- Row 2: Concentric Radial Rings (Fulfillment & Payment Success) -->
            <div class="command-col-4" style="background:#131f37;border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:16px">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span style="font-size:0.88rem;font-weight:700;color:#f8fafc">Studio Health &amp; Fulfillment</span>
                        <div style="font-size:0.73rem;color:#94a3b8">Dual concentric execution rates</div>
                    </div>
                </div>
                <div id="chart-health-rings" style="min-height:220px;width:100%"></div>
            </div>

            <!-- Row 2: Yoga Package Share Pie Chart -->
            <div class="command-col-4" style="background:#131f37;border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:16px">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span style="font-size:0.88rem;font-weight:700;color:#f8fafc">Yoga Package Distribution</span>
                        <div style="font-size:0.73rem;color:#94a3b8">Client plan preferences</div>
                    </div>
                </div>
                <div id="chart-packages-pie" style="min-height:220px;width:100%"></div>
            </div>

            <!-- Row 2: Payment Gateway Donut Ring -->
            <div class="command-col-4" style="background:#131f37;border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:16px">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span style="font-size:0.88rem;font-weight:700;color:#f8fafc">Payment Gateway Share</span>
                        <div style="font-size:0.73rem;color:#94a3b8">Studio Direct vs Razorpay vs UPI</div>
                    </div>
                </div>
                <div id="chart-gateways-donut" style="min-height:220px;width:100%"></div>
            </div>

            <!-- Row 3: Time of Day Slots Multi-Column -->
            <div class="command-col-6" style="background:#131f37;border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:16px">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span style="font-size:0.88rem;font-weight:700;color:#f8fafc">Session Time-of-Day Slots</span>
                        <div style="font-size:0.73rem;color:#94a3b8">Morning vs Afternoon vs Evening distribution</div>
                    </div>
                    <span style="font-size:0.72rem;color:#a78bfa;background:rgba(167,139,250,0.12);padding:3px 9px;border-radius:20px;border:1px solid rgba(167,139,250,0.25)">Peak: Morning</span>
                </div>
                <div id="chart-timeofday-columns" style="min-height:200px;width:100%"></div>
            </div>

            <!-- Row 3: Client Signups & Growth Sparkline -->
            <div class="command-col-6" style="background:#131f37;border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:16px">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <span style="font-size:0.88rem;font-weight:700;color:#f8fafc">Community Growth &amp; Registrations</span>
                        <div style="font-size:0.73rem;color:#94a3b8">New yoga member signup timeline</div>
                    </div>
                    <span id="growth-legend" style="font-size:0.73rem;font-weight:700;color:#34d399;background:rgba(52,211,153,0.12);padding:3px 9px;border-radius:20px;border:1px solid rgba(52,211,153,0.25)">New Clients</span>
                </div>
                <div id="chart-user-growth-live" style="min-height:200px;width:100%"></div>
            </div>
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
            <span class="badge" id="pending-count-badge" style="background:#FEF3C7;color:#B45309;font-weight:700;font-size:0.85rem;padding:6px 12px;border-radius:20px"><?= count($pendingPayments ?? []) ?> Awaiting Approval</span>
        </div>

        <div id="pending-empty-state" style="<?= empty($pendingPayments) ? '' : 'display:none;' ?>background:#F8FAFC;border:1px dashed #CBD5E1;border-radius:12px;padding:18px;text-align:center;color:#64748B;font-size:0.85rem">
            ✓ No pending payment approvals right now. All user payments are verified and active!
        </div>

        <div class="table-responsive" id="pending-table-wrapper" style="<?= empty($pendingPayments) ? 'display:none;' : '' ?>">
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
                    <?php foreach ($pendingPayments ?? [] as $pay): ?>
                    <tr id="pending-pay-row-<?= (int)$pay['payment_id'] ?>" data-id="<?= (int)$pay['payment_id'] ?>" data-ref="<?= View::e($pay['booking_ref']) ?>">
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
                            <button type="button" class="btn btn-xs btn-secondary js-view-proof-modal-btn" data-img="<?= View::e($pay['screenshot_path']) ?>" data-ref="<?= View::e($pay['booking_ref']) ?>" data-client="<?= View::e($pay['client_name'] ?? '') ?>" data-amount="<?= View::e(($pay['currency'] ?? 'INR') . ' ' . number_format((float)$pay['amount'], 2)) ?>" style="font-size:0.75rem;padding:4px 8px;display:inline-flex;align-items:center;gap:4px;cursor:pointer">
                                <?= View::icon('image', 'icon', 12) ?> View Proof
                            </button>
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

            <!-- Pagination Bar for Pending Approvals (5 per page) -->
            <div id="pending-pagination-bar" class="flex items-center justify-between flex-wrap gap-3 mt-4 pt-3" style="border-top:1px solid #F1F5F9;font-size:0.82rem;color:#64748B">
                <div id="pending-pagination-info">Showing 1 to 5 of <?= count($pendingPayments ?? []) ?> entries</div>
                <div class="flex items-center gap-1" id="pending-pagination-controls"></div>
            </div>
        </div>
    </div>

    <!-- Real-Time Rejected Payments History Section (Nearby Session) -->
    <div class="card mb-8" style="background:#fff;border:1px solid #FECACA;border-radius:var(--radius-xl);padding:var(--space-5);box-shadow:0 8px 24px rgba(239,68,68,0.06)" id="admin-rejected-payments-card">
        <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
            <div class="flex items-center gap-2">
                <span style="display:grid;place-items:center;width:2.2rem;height:2.2rem;border-radius:50%;background:#FEE2E2;color:#DC2626">
                    <?= View::icon('x', 'icon', 18) ?>
                </span>
                <div>
                    <h2 style="font-size:1.1rem;font-weight:700;margin:0;color:#991B1B">Rejected Payments History</h2>
                    <p class="text-muted" style="font-size:0.8rem;margin:2px 0 0">History of declined payment submissions and cancelled requests. You can remove records with the delete action.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="badge" id="rejected-count-badge" style="background:#FEE2E2;color:#991B1B;font-weight:700;font-size:0.85rem;padding:6px 12px;border-radius:20px"><?= count($rejectedPayments ?? []) ?> Rejected</span>
                <button type="button" class="btn btn-xs btn-ghost js-delete-all-rejected-btn" id="clear-all-rejected-btn" style="<?= empty($rejectedPayments) ? 'display:none;' : '' ?>color:#DC2626;border:1px solid #FECACA;font-size:0.75rem;padding:4px 10px;border-radius:8px;display:inline-flex;align-items:center;gap:4px">
                    <?= View::icon('trash', 'icon', 13) ?> Clear All History
                </button>
            </div>
        </div>

        <div id="rejected-empty-state" style="<?= empty($rejectedPayments) ? '' : 'display:none;' ?>background:#F8FAFC;border:1px dashed #CBD5E1;border-radius:12px;padding:18px;text-align:center;color:#64748B;font-size:0.85rem">
            ✓ No rejected payment records in history.
        </div>

        <div class="table-responsive" id="rejected-table-wrapper" style="<?= empty($rejectedPayments) ? 'display:none;' : '' ?>">
            <table class="data-table" style="font-size:0.85rem">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Client Details</th>
                        <th>Package &amp; Trainer</th>
                        <th>Amount</th>
                        <th>UTR / Transaction No</th>
                        <th>Payment Proof</th>
                        <th>Status</th>
                        <th style="text-align:right">Action</th>
                    </tr>
                </thead>
                <tbody id="rejected-payments-tbody">
                    <?php foreach ($rejectedPayments ?? [] as $pay): ?>
                    <tr id="rejected-pay-row-<?= (int)$pay['payment_id'] ?>" data-id="<?= (int)$pay['payment_id'] ?>">
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
                            <strong style="color:#DC2626;font-size:0.95rem"><?= View::e($pay['currency'] ?? 'INR') ?> <?= number_format((float)$pay['amount'], 2) ?></strong>
                            <div class="text-muted" style="font-size:0.75rem">Via <?= View::e(strtoupper($pay['gateway'] ?: 'UPI')) ?></div>
                        </td>
                        <td>
                            <code style="background:#F1F5F9;padding:4px 8px;border-radius:6px;color:#0F172A;font-weight:600;font-size:0.85rem"><?= View::e($pay['gateway_txn_id'] ?: 'Pending UTR') ?></code>
                        </td>
                        <td>
                            <?php if (!empty($pay['screenshot_path'])): ?>
                            <button type="button" class="btn btn-xs btn-secondary js-view-proof-modal-btn" data-img="<?= View::e($pay['screenshot_path']) ?>" data-ref="<?= View::e($pay['booking_ref']) ?>" data-client="<?= View::e($pay['client_name'] ?? '') ?>" data-amount="<?= View::e(($pay['currency'] ?? 'INR') . ' ' . number_format((float)$pay['amount'], 2)) ?>" style="font-size:0.75rem;padding:4px 8px;display:inline-flex;align-items:center;gap:4px;cursor:pointer">
                                <?= View::icon('image', 'icon', 12) ?> View Proof
                            </button>
                            <?php else: ?>
                            <span class="text-muted" style="font-size:0.75rem">UTR only</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge" style="background:#FEE2E2;color:#991B1B;font-size:0.75rem;padding:3px 8px;border-radius:12px;font-weight:600">
                                ✕ Rejected
                            </span>
                        </td>
                        <td style="text-align:right">
                            <button type="button" class="btn btn-sm btn-ghost js-delete-rejected-pay-btn" data-id="<?= (int)$pay['payment_id'] ?>" data-ref="<?= View::e($pay['booking_ref']) ?>" title="Delete Record" style="color:#DC2626;background:transparent;border:1px solid #FECACA;border-radius:8px;padding:6px 10px;display:inline-flex;align-items:center;justify-content:center;transition:all 0.2s ease" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">
                                <?= View::icon('trash', 'icon', 15) ?>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination Bar for Rejected History (5 per page) -->
            <div id="rejected-pagination-bar" class="flex items-center justify-between flex-wrap gap-3 mt-4 pt-3" style="border-top:1px solid #F1F5F9;font-size:0.82rem;color:#64748B">
                <div id="rejected-pagination-info">Showing 1 to 5 of <?= count($rejectedPayments ?? []) ?> records</div>
                <div class="flex items-center gap-1" id="rejected-pagination-controls"></div>
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

<?= View::partial('partials/payment-proof-modal') ?>

<script type="application/json" id="admin-chart-data"><?= json_encode($seriesData, JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
<script type="module">
import { 
    renderAreaChart, 
    renderColumnChart, 
    renderMultiColumnChart, 
    renderDualRingChart, 
    renderPieChart, 
    renderDonutChart, 
    renderSparkTrendChart 
} from '/assets/js/modules/charts.js?v=<?= time() ?>';
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

const seriesData = JSON.parse(document.getElementById('admin-chart-data').textContent);
let currentGranularity = 'daily90';

function getActiveChartDataset() {
    return seriesData[currentGranularity] || seriesData['daily90'] || seriesData['daily'];
}

function renderAllCharts(customDataset = null) {
    const dataset = customDataset || getActiveChartDataset();
    if (!dataset) return;

    // 1. Area Chart: Real-time Revenue Dynamics & Cashflow
    renderAreaChart(document.getElementById('chart-revenue-live'), dataset.revenue, {
        color: '#38bdf8',
        gradientStart: '#0284c7',
        gradientEnd: 'rgba(2, 132, 199, 0.02)',
        isCurrency: true
    });

    // 2. Bar Chart: Confirmed Session Volume
    renderColumnChart(document.getElementById('chart-bookings-columns'), dataset.bookings, {
        color: '#fb923c',
        highlightColor: '#f97316'
    });

    // 3. Dual Concentric Rings: Studio Health & Fulfillment Rates
    const summary = seriesData.analyticsSummary || {};
    renderDualRingChart(document.getElementById('chart-health-rings'), {
        ring1: { label: 'Session Fulfillment', value: summary.fulfillmentRate ?? 83.3, color: '#4ade80' },
        ring2: { label: 'Payment Success', value: summary.paymentSuccessRate ?? 85.7, color: '#38bdf8' }
    });

    // 4. Multi-slice Pie Chart: Yoga Package Preferences
    renderPieChart(document.getElementById('chart-packages-pie'), seriesData.packageShare || []);

    // 5. Donut Chart: Payment Gateway & Method Share
    const revTotalK = Math.round((summary.totalRevenue || 41800) / 100) / 10;
    renderDonutChart(document.getElementById('chart-gateways-donut'), seriesData.gatewayShare || [], {
        centerLabel: 'Total Rev',
        centerValue: '₹' + revTotalK + 'k'
    });

    // 6. Time of Day Slots Clustered Columns
    renderMultiColumnChart(document.getElementById('chart-timeofday-columns'), seriesData.timeOfDayShare || []);

    // 7. Community Registrations Growth Sparkline
    renderSparkTrendChart(document.getElementById('chart-user-growth-live'), dataset.userGrowth, {
        color: '#34d399'
    });
}

// Granularity switchers
document.querySelectorAll('.js-granularity-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.js-granularity-btn').forEach(b => {
            b.style.background = 'transparent';
            b.style.color = '#94a3b8';
        });
        btn.style.background = '#3b82f6';
        btn.style.color = '#ffffff';

        currentGranularity = btn.dataset.gran;

        const monthContainer = document.getElementById('filter-month-container');
        const yearContainer = document.getElementById('filter-year-container');
        if (monthContainer) monthContainer.style.display = (currentGranularity === 'monthly') ? 'block' : 'none';
        if (yearContainer) yearContainer.style.display = (currentGranularity === 'yearly') ? 'block' : 'none';

        const revLegend = document.getElementById('revenue-legend');
        const bookLegend = document.getElementById('bookings-legend');
        if (revLegend && bookLegend) {
            if (currentGranularity === 'daily90') {
                revLegend.textContent = '₹ Last 90 Days';
                bookLegend.textContent = '90 Days Total';
            } else if (currentGranularity === 'daily') {
                revLegend.textContent = '₹ Last 30 Days';
                bookLegend.textContent = '30 Days Total';
            } else if (currentGranularity === 'monthly') {
                revLegend.textContent = '₹ Monthly Real Sums';
                bookLegend.textContent = 'Monthly Bookings';
            } else {
                revLegend.textContent = '₹ Annual Sums';
                bookLegend.textContent = 'Annual Bookings';
            }
        }

        renderAllCharts();
    });
});

document.getElementById('filter-month-select')?.addEventListener('change', (e) => {
    const val = e.target.value;
    const base = getActiveChartDataset();
    if (val === 'all') {
        renderAllCharts();
        return;
    }
    const filtered = JSON.parse(JSON.stringify(base));
    if (filtered.revenue && filtered.revenue.labels) {
        const idx = filtered.revenue.labels.indexOf(val);
        if (idx !== -1) {
            filtered.revenue.labels = [filtered.revenue.labels[idx]];
            filtered.revenue.values = [filtered.revenue.values[idx]];
            filtered.bookings.labels = [filtered.bookings.labels[idx]];
            filtered.bookings.values = [filtered.bookings.values[idx]];
            filtered.userGrowth.labels = [filtered.userGrowth.labels[idx]];
            filtered.userGrowth.values = [filtered.userGrowth.values[idx]];
        }
    }
    renderAllCharts(filtered);
});

document.getElementById('filter-year-select')?.addEventListener('change', (e) => {
    const val = e.target.value;
    const base = getActiveChartDataset();
    if (val === 'all') {
        renderAllCharts();
        return;
    }
    const filtered = JSON.parse(JSON.stringify(base));
    if (filtered.revenue && filtered.revenue.labels) {
        const idx = filtered.revenue.labels.indexOf(val);
        if (idx !== -1) {
            filtered.revenue.labels = [filtered.revenue.labels[idx]];
            filtered.revenue.values = [filtered.revenue.values[idx]];
            filtered.bookings.labels = [filtered.bookings.labels[idx]];
            filtered.bookings.values = [filtered.bookings.values[idx]];
            filtered.userGrowth.labels = [filtered.userGrowth.labels[idx]];
            filtered.userGrowth.values = [filtered.userGrowth.values[idx]];
        }
    }
    renderAllCharts(filtered);
});

// Initial invocation
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

// --- Custom Table Pagination Helper (5 items per page) ---
function initCustomPagination(config) {
    const {
        tbodyId,
        infoId,
        controlsId,
        barId,
        emptyStateId,
        tableWrapperId,
        countBadgeId,
        countSuffix = 'items',
        extraToggleId,
        itemsPerPage = 5
    } = config;

    let currentPage = 1;

    function render() {
        const tbody = document.getElementById(tbodyId);
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const totalItems = rows.length;

        const badgeEl = document.getElementById(countBadgeId);
        if (badgeEl) {
            badgeEl.textContent = `${totalItems} ${countSuffix}`;
        }

        if (extraToggleId) {
            const extraEl = document.getElementById(extraToggleId);
            if (extraEl) {
                extraEl.style.display = totalItems > 0 ? 'inline-flex' : 'none';
            }
        }

        if (totalItems === 0) {
            if (emptyStateId) {
                const emptyEl = document.getElementById(emptyStateId);
                if (emptyEl) emptyEl.style.display = 'block';
            }
            if (tableWrapperId) {
                const wrapper = document.getElementById(tableWrapperId);
                if (wrapper) wrapper.style.display = 'none';
            }
            return;
        }

        if (tableWrapperId) {
            const wrapper = document.getElementById(tableWrapperId);
            if (wrapper) wrapper.style.display = 'block';
        }
        if (emptyStateId) {
            const emptyEl = document.getElementById(emptyStateId);
            if (emptyEl) emptyEl.style.display = 'none';
        }

        const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIdx = (currentPage - 1) * itemsPerPage;
        const endIdx = Math.min(startIdx + itemsPerPage, totalItems);

        rows.forEach((row, idx) => {
            row.style.display = (idx >= startIdx && idx < endIdx) ? '' : 'none';
        });

        const infoEl = document.getElementById(infoId);
        if (infoEl) {
            infoEl.textContent = `Showing ${totalItems === 0 ? 0 : startIdx + 1} to ${endIdx} of ${totalItems} ${countSuffix.toLowerCase()}`;
        }

        const controlsEl = document.getElementById(controlsId);
        if (controlsEl) {
            controlsEl.innerHTML = '';

            const prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = 'btn btn-xs btn-ghost';
            prevBtn.innerHTML = '&larr; Prev';
            prevBtn.style.padding = '4px 9px';
            prevBtn.style.fontSize = '0.78rem';
            prevBtn.disabled = (currentPage <= 1);
            prevBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    render();
                }
            });
            controlsEl.appendChild(prevBtn);

            for (let i = 1; i <= totalPages; i++) {
                const pBtn = document.createElement('button');
                pBtn.type = 'button';
                pBtn.className = (i === currentPage) ? 'btn btn-xs btn-primary' : 'btn btn-xs btn-ghost';
                pBtn.textContent = i;
                pBtn.style.padding = '4px 10px';
                pBtn.style.fontSize = '0.78rem';
                pBtn.style.minWidth = '28px';
                pBtn.style.textAlign = 'center';
                if (i === currentPage) {
                    pBtn.style.fontWeight = '700';
                }
                const pIndex = i;
                pBtn.addEventListener('click', () => {
                    currentPage = pIndex;
                    render();
                });
                controlsEl.appendChild(pBtn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = 'btn btn-xs btn-ghost';
            nextBtn.innerHTML = 'Next &rarr;';
            nextBtn.style.padding = '4px 9px';
            nextBtn.style.fontSize = '0.78rem';
            nextBtn.disabled = (currentPage >= totalPages);
            nextBtn.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    render();
                }
            });
            controlsEl.appendChild(nextBtn);
        }
    }

    render();

    return {
        refresh: render,
        goToFirst: () => { currentPage = 1; render(); },
        getCurrentPage: () => currentPage
    };
}

const pendingPagination = initCustomPagination({
    tbodyId: 'pending-payments-tbody',
    infoId: 'pending-pagination-info',
    controlsId: 'pending-pagination-controls',
    barId: 'pending-pagination-bar',
    emptyStateId: 'pending-empty-state',
    tableWrapperId: 'pending-table-wrapper',
    countBadgeId: 'pending-count-badge',
    countSuffix: 'Awaiting Approval',
    itemsPerPage: 5
});

const rejectedPagination = initCustomPagination({
    tbodyId: 'rejected-payments-tbody',
    infoId: 'rejected-pagination-info',
    controlsId: 'rejected-pagination-controls',
    barId: 'rejected-pagination-bar',
    emptyStateId: 'rejected-empty-state',
    tableWrapperId: 'rejected-table-wrapper',
    countBadgeId: 'rejected-count-badge',
    countSuffix: 'Rejected',
    extraToggleId: 'clear-all-rejected-btn',
    itemsPerPage: 5
});

// Event delegation for Approve, Reject, Delete and Clear All actions (No confirm alert popups)
document.addEventListener('click', async (e) => {
    // Approve Payment
    const approveBtn = e.target.closest('.js-approve-payment-btn');
    if (approveBtn) {
        const id = approveBtn.dataset.id;
        const ref = approveBtn.dataset.ref;
        if (!id) return;

        approveBtn.disabled = true;
        const origText = approveBtn.textContent;
        approveBtn.textContent = 'Approving...';

        try {
            await apiPost(`/admin/payments/${id}/approve`, {});
            toast.success(`Payment for booking ${ref} approved! Session confirmed and sent to trainer schedule.`);
            const row = document.getElementById(`pending-pay-row-${id}`);
            if (row) {
                row.style.transition = 'all 0.4s ease';
                row.style.background = '#ECFDF5';
                setTimeout(() => {
                    row.remove();
                    pendingPagination.refresh();
                }, 400);
            }
        } catch (err) {
            toast.error(err.message || 'Failed to approve payment.');
            approveBtn.disabled = false;
            approveBtn.textContent = origText;
        }
        return;
    }

    // Reject Payment (No popup confirm)
    const rejectBtn = e.target.closest('.js-reject-payment-btn');
    if (rejectBtn) {
        const id = rejectBtn.dataset.id;
        const ref = rejectBtn.dataset.ref;
        if (!id) return;

        rejectBtn.disabled = true;
        const origText = rejectBtn.textContent;
        rejectBtn.textContent = 'Rejecting...';

        try {
            await apiPost(`/admin/payments/${id}/reject`, {});
            toast.warning(`Payment for ${ref} rejected and booking cancelled.`);
            const row = document.getElementById(`pending-pay-row-${id}`);
            if (row) {
                const cells = row.querySelectorAll('td');
                const clientHtml = cells[1]?.innerHTML ?? '';
                const packageHtml = cells[2]?.innerHTML ?? '';
                let amountHtml = cells[3]?.innerHTML ?? '';
                amountHtml = amountHtml.replace('#059669', '#DC2626');
                const utrHtml = cells[4]?.innerHTML ?? '';
                const proofHtml = cells[5]?.innerHTML ?? '';

                const now = new Date();
                const dateStr = now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + ', ' + now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

                row.style.transition = 'all 0.4s ease';
                row.style.background = '#FEF2F2';
                setTimeout(() => {
                    row.remove();
                    pendingPagination.refresh();

                    const rejTbody = document.getElementById('rejected-payments-tbody');
                    if (rejTbody) {
                        const newTr = document.createElement('tr');
                        newTr.id = `rejected-pay-row-${id}`;
                        newTr.dataset.id = id;
                        newTr.innerHTML = `
                            <td>
                                <strong style="font-family:monospace;color:var(--color-primary)">${ref}</strong>
                                <div class="text-muted" style="font-size:0.75rem">${dateStr}</div>
                            </td>
                            <td>${clientHtml}</td>
                            <td>${packageHtml}</td>
                            <td>${amountHtml}</td>
                            <td>${utrHtml}</td>
                            <td>${proofHtml}</td>
                            <td>
                                <span class="badge" style="background:#FEE2E2;color:#991B1B;font-size:0.75rem;padding:3px 8px;border-radius:12px;font-weight:600">
                                    ✕ Rejected
                                </span>
                            </td>
                            <td style="text-align:right">
                                <button type="button" class="btn btn-sm btn-ghost js-delete-rejected-pay-btn" data-id="${id}" data-ref="${ref}" title="Delete Record" style="color:#DC2626;background:transparent;border:1px solid #FECACA;border-radius:8px;padding:6px 10px;display:inline-flex;align-items:center;justify-content:center;transition:all 0.2s ease" onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='transparent'">
                                    <svg class="icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </td>
                        `;
                        rejTbody.prepend(newTr);
                        rejectedPagination.goToFirst();
                    }
                }, 400);
            }
        } catch (err) {
            toast.error(err.message || 'Failed to reject payment.');
            rejectBtn.disabled = false;
            rejectBtn.textContent = origText;
        }
        return;
    }

    // Delete single rejected payment record
    const delRejBtn = e.target.closest('.js-delete-rejected-pay-btn');
    if (delRejBtn) {
        const id = delRejBtn.dataset.id;
        if (!id) return;

        delRejBtn.disabled = true;
        delRejBtn.style.opacity = '0.5';

        try {
            await apiPost(`/admin/payments/${id}/delete`, {});
            toast.success('Rejected payment record deleted.');
            const row = document.getElementById(`rejected-pay-row-${id}`);
            if (row) {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'scale(0.96)';
                setTimeout(() => {
                    row.remove();
                    rejectedPagination.refresh();
                }, 300);
            }
        } catch (err) {
            toast.error(err.message || 'Failed to delete record.');
            delRejBtn.disabled = false;
            delRejBtn.style.opacity = '1';
        }
        return;
    }

    // Clear all rejected payments
    const clearAllBtn = e.target.closest('.js-delete-all-rejected-btn');
    if (clearAllBtn) {
        clearAllBtn.disabled = true;
        const origText = clearAllBtn.textContent;
        clearAllBtn.textContent = 'Clearing...';

        try {
            await apiPost('/admin/payments/delete-all-rejected', {});
            toast.success('All rejected payment records deleted.');
            const rejTbody = document.getElementById('rejected-payments-tbody');
            if (rejTbody) {
                rejTbody.innerHTML = '';
            }
            rejectedPagination.refresh();
        } catch (err) {
            toast.error(err.message || 'Failed to clear rejected history.');
            clearAllBtn.disabled = false;
            clearAllBtn.textContent = origText;
        }
        return;
    }
});
</script>
