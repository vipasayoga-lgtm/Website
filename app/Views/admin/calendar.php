<?php

use App\Core\View;

$pageTitle = 'Calendar — Vipasa Yoga';
$pageCss = 'services';
$portal = 'admin';
$active = 'calendar';

$statusMeta = [
    'pending_payment' => ['badge-pending', 'Pending payment'],
    'awaiting_trainer_approval' => ['badge-pending', 'Awaiting trainer'],
    'confirmed' => ['badge-confirmed', 'Confirmed'],
    'completed' => ['badge-completed', 'Completed'],
    'cancelled' => ['badge-cancelled', 'Cancelled'],
    'rescheduled' => ['badge-pending', 'Rescheduled'],
];

$monthNames = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
];

$periodLabel = $wholeYear ? 'All of ' . $year : $monthNames[$month] . ' ' . $year;
$today = date('Y-m-d');
$totalCount = count($bookings);

/** Keeps the current filters when only one of them changes. */
$calUrl = static function (array $overrides) use ($year, $month, $wholeYear, $instructorId, $status): string {
    $params = [
        'year' => $overrides['year'] ?? $year,
        'month' => array_key_exists('month', $overrides) ? $overrides['month'] : ($wholeYear ? 'all' : $month),
        'instructor_id' => $overrides['instructor_id'] ?? ($instructorId ?: null),
        'status' => $overrides['status'] ?? ($status ?: null),
    ];
    $params = array_filter($params, static fn ($v) => $v !== null && $v !== '');

    return '/admin/calendar?' . http_build_query($params);
};
?>
<div class="container" style="padding-block:var(--space-6) var(--space-12)">
    <div class="services-hero" style="text-align:left;margin:0 0 var(--space-4)">
        <h1 class="services-hero__title" style="margin:0">Calendar</h1>
        <p class="services-hero__subtitle" style="margin:0">Cross-instructor view of bookings — pick any month, or a whole year at once.</p>
    </div>

    <!-- Filters. Plain GET form so the current view is always shareable and
         bookmarkable as a URL; the selects auto-submit on change (script at the
         bottom) and the Apply button still works without JS. -->
    <form method="get" action="/admin/calendar" class="card calendar-filters" id="calendar-filters">
        <label class="calendar-filters__field">
            <span>Year</span>
            <select name="year" class="form-group__control">
                <?php foreach ($years as $y): ?>
                <option value="<?= (int) $y ?>" <?= $y === $year ? 'selected' : '' ?>><?= (int) $y ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="calendar-filters__field">
            <span>Month</span>
            <select name="month" class="form-group__control">
                <option value="all" <?= $wholeYear ? 'selected' : '' ?>>Whole year</option>
                <?php foreach ($monthNames as $num => $name): ?>
                <option value="<?= $num ?>" <?= !$wholeYear && $month === $num ? 'selected' : '' ?>><?= View::e($name) ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="calendar-filters__field">
            <span>Instructor</span>
            <select name="instructor_id" class="form-group__control">
                <option value="">All instructors</option>
                <?php foreach ($instructors as $instructor): ?>
                <option value="<?= (int) $instructor['id'] ?>" <?= $instructorId === (int) $instructor['id'] ? 'selected' : '' ?>><?= View::e($instructor['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="calendar-filters__field">
            <span>Status</span>
            <select name="status" class="form-group__control">
                <option value="">All statuses</option>
                <?php foreach ($statuses as $s): ?>
                <option value="<?= View::e($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= View::e($statusMeta[$s][1] ?? $s) ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <div class="calendar-filters__actions">
            <button type="submit" class="btn btn-secondary btn-xs">Apply</button>
            <a href="/admin/calendar" class="btn btn-ghost btn-xs">Reset</a>
        </div>
    </form>

    <div class="calendar-toolbar">
        <?php if ($wholeYear): ?>
        <a href="<?= View::e($calUrl(['year' => $year - 1])) ?>" class="btn btn-secondary btn-sm">&larr; <?= $year - 1 ?></a>
        <?php else: ?>
        <a href="<?= View::e($calUrl(['month' => $prevMonth, 'year' => $prevYear])) ?>" class="btn btn-secondary btn-sm">&larr; Prev</a>
        <?php endif; ?>

        <div class="calendar-toolbar__period">
            <h2 class="calendar-toolbar__label"><?= View::e($periodLabel) ?></h2>
            <p class="calendar-toolbar__summary">
                <?= $totalCount ?> booking<?= $totalCount === 1 ? '' : 's' ?>
                <?php if ($totalCount > 0): ?> · ₹<?= number_format((float) $revenue, 2) ?> excluding cancelled<?php endif; ?>
                <?php if ($instructorId || $status !== ''): ?> · filtered<?php endif; ?>
            </p>
        </div>

        <?php if ($wholeYear): ?>
        <a href="<?= View::e($calUrl(['year' => $year + 1])) ?>" class="btn btn-secondary btn-sm"><?= $year + 1 ?> &rarr;</a>
        <?php else: ?>
        <a href="<?= View::e($calUrl(['month' => $nextMonth, 'year' => $nextYear])) ?>" class="btn btn-secondary btn-sm">Next &rarr;</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($statusTotals)): ?>
    <div class="calendar-status-row">
        <?php foreach ($statusTotals as $s => $n): ?>
        <span class="badge <?= $statusMeta[$s][0] ?? 'badge-neutral' ?>"><?= View::e($statusMeta[$s][1] ?? $s) ?>: <?= (int) $n ?></span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($wholeYear): ?>
    <!-- Year mode: twelve month tiles instead of one 31-cell grid, each a link
         into that month's day view. -->
    <div class="year-grid card">
        <?php foreach ($monthNames as $num => $name):
            $mCount = (int) ($byMonth[$num]['count'] ?? 0);
            $mRevenue = (float) ($byMonth[$num]['revenue'] ?? 0);
            $isCurrent = $num === (int) date('n') && $year === (int) date('Y');
        ?>
        <a href="<?= View::e($calUrl(['month' => $num])) ?>" class="year-grid__month<?= $mCount > 0 ? ' has-bookings' : '' ?><?= $isCurrent ? ' is-current' : '' ?>">
            <span class="year-grid__name"><?= View::e($name) ?></span>
            <span class="year-grid__count"><?= $mCount ?></span>
            <span class="year-grid__meta"><?= $mCount === 0 ? 'No sessions' : ($mCount === 1 ? '1 session' : $mCount . ' sessions') ?><?= $mRevenue > 0 ? ' · ₹' . number_format($mRevenue, 0) : '' ?></span>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <?php
    $daysInMonth = (int) date('t', mktime(0, 0, 0, $month, 1, $year));
    $firstDayOfWeek = (int) date('N', mktime(0, 0, 0, $month, 1, $year)); // 1 = Mon .. 7 = Sun
    $weekdayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $maxChips = 3;

    // Leading blanks, the days, then trailing blanks so the last week is full.
    $cells = array_fill(0, $firstDayOfWeek - 1, null);
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $cells[] = $day;
    }
    while (count($cells) % 7 !== 0) {
        $cells[] = null;
    }
    $weeks = array_chunk($cells, 7);
    ?>
    <div class="card calendar-card">
        <table class="calendar-grid">
            <thead>
                <tr>
                    <?php foreach ($weekdayLabels as $label): ?>
                    <th><?= View::e($label) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($weeks as $week): ?>
                <tr>
                    <?php foreach ($week as $day):
                        if ($day === null) {
                            echo '<td class="calendar-grid__cell is-empty"></td>';
                            continue;
                        }

                        $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $dayBookings = $byDate[$dateKey] ?? [];
                        $count = count($dayBookings);
                        $isToday = $dateKey === $today;
                    ?>
                    <td class="calendar-grid__cell<?= $isToday ? ' is-today' : '' ?>">
                        <div class="calendar-grid__daytop">
                            <span class="calendar-grid__daynum"><?= $day ?></span>
                            <?php if ($count > 0): ?>
                            <span class="calendar-grid__daycount"><?= $count ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($count > 0): ?>
                        <div class="calendar-grid__events">
                            <?php foreach (array_slice($dayBookings, 0, $maxChips) as $booking):
                                [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
                            ?>
                            <?php
                            /* Status shows as a coloured dot, not a pill: a full
                               badge left ~1 character of room for the client
                               name in a 7-column cell. The label still reaches
                               the admin via the tooltip and the table below. */
                            $eventTitle = $badgeLabel . ' · ' . $booking['package_name']
                                . ' — ' . $booking['client_name'] . ' with ' . $booking['instructor_name'];
                            ?>
                            <a href="#booking-<?= View::e($booking['booking_ref']) ?>" class="calendar-event"
                               title="<?= View::e($eventTitle) ?>">
                                <span class="calendar-event__dot <?= $badgeClass ?>" aria-hidden="true"></span>
                                <span class="calendar-event__time"><?= View::e(date('g:i A', strtotime($booking['start_time']))) ?></span>
                                <span class="calendar-event__who"><?= View::e($booking['client_name']) ?></span>
                            </a>
                            <?php endforeach; ?>
                            <?php if ($count > $maxChips): ?>
                            <span class="calendar-grid__more">+<?= $count - $maxChips ?> more below</span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Full detail for the selected period — the grid/tiles can only show a
         few chips per day, so every booking is also listed in full here. -->
    <h2 class="mt-8 mb-3" style="font-size:var(--font-size-lg)">
        <?= $wholeYear ? 'All bookings in ' . $year : 'All bookings in ' . View::e($periodLabel) ?>
    </h2>

    <?php if ($totalCount === 0): ?>
    <div class="empty-state">
        <span class="empty-state__icon"><?= View::icon('calendar-month', 'icon', 24) ?></span>
        <p class="empty-state__text" style="margin:0">
            No bookings<?= $instructorId || $status !== '' ? ' match these filters' : '' ?> in <?= View::e($periodLabel) ?>.
        </p>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Client</th>
                    <th>Instructor</th>
                    <th>Package</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Ref</th>
                    <th style="text-align:center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking):
                    [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
                ?>
                <tr id="booking-<?= View::e($booking['booking_ref']) ?>">
                    <td><?= View::e(date('D, d M Y', strtotime($booking['slot_date']))) ?></td>
                    <td><?= View::e(date('g:i A', strtotime($booking['start_time']))) ?></td>
                    <td>
                        <strong><?= View::e($booking['client_name']) ?></strong>
                        <div class="text-muted" style="font-size:var(--font-size-xs)"><?= View::e($booking['client_email'] ?? '') ?></div>
                    </td>
                    <td><?= View::e($booking['instructor_name']) ?></td>
                    <td><?= View::e($booking['package_name']) ?></td>
                    <td><?= View::e($booking['currency']) ?> <?= number_format((float) $booking['total_amount'], 2) ?></td>
                    <td><span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span></td>
                    <td style="font-size:var(--font-size-xs)"><?= View::e($booking['booking_ref']) ?></td>
                    <td style="text-align:center">
                        <button type="button" class="btn btn-ghost btn-xs js-delete-admin-booking" data-ref="<?= View::e($booking['booking_ref']) ?>" title="Delete booking" style="color:var(--color-error,#EF4444);padding:4px">
                            <?= View::icon('trash', 'icon', 15) ?>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

/* Changing any filter applies immediately — an admin scanning months shouldn't
   have to change a select and then hunt for Apply. The button stays for
   keyboard/no-JS use. */
document.querySelectorAll('#calendar-filters select').forEach((select) => {
    select.addEventListener('change', () => select.form.submit());
});

document.querySelectorAll('.js-delete-admin-booking').forEach((btn) => {
    btn.addEventListener('click', async () => {
        if (!confirm('Are you sure you want to delete this booking?')) return;
        const ref = btn.dataset.ref;
        const row = btn.closest('tr');
        btn.disabled = true;
        try {
            await apiPost(`/admin/bookings/${ref}/delete`, {});
            toast.success('Booking deleted.');
            if (row) row.remove();
            document.querySelectorAll(`a[href="#booking-${ref}"]`).forEach(chip => chip.remove());
        } catch (err) {
            toast.error(err.message || 'Failed to delete booking.');
            btn.disabled = false;
        }
    });
});
</script>
