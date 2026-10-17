<?php

use App\Core\View;

$pageTitle = 'Reports — Vipasa Yoga Admin';
$pageCss = 'admin';
$portal = 'admin';
$active = 'reports';

$reportTypes = [
    'revenue' => ['label' => 'Revenue (daily)', 'icon' => 'chart-bar', 'hint' => 'One row per day, bookings and money taken'],
    'bookings' => ['label' => 'Bookings', 'icon' => 'calendar-week', 'hint' => 'Every booking with client, trainer and status'],
    'payments' => ['label' => 'Payments', 'icon' => 'credit-card', 'hint' => 'Gateway transactions and their outcome'],
    'instructor_performance' => ['label' => 'Instructor performance', 'icon' => 'certificate', 'hint' => 'Sessions, revenue and rating per trainer'],
];

$counts = $counts ?? [];

/* Quick ranges, so the common questions don't need two date pickers.
   Keys are the labels; values are [from, to]. */
$today = date('Y-m-d');
$presets = [
    'Last 7 days' => [date('Y-m-d', strtotime('-6 days')), $today],
    'Last 30 days' => [date('Y-m-d', strtotime('-29 days')), $today],
    'This month' => [date('Y-m-01'), $today],
    'This year' => [date('Y-01-01'), $today],
];

$rangeDays = (int) ((strtotime($to) - strtotime($from)) / 86400) + 1;
$exportUrl = static fn (string $type, string $format): string => '/admin/reports/export?' . http_build_query([
    'type' => $type,
    'format' => $format,
    'from' => $from,
    'to' => $to,
]);
?>
<div class="admin-page container" style="max-width:900px">
    <div class="admin-hero" style="margin-bottom:var(--space-3)">
        <h1 class="admin-hero__title" style="margin:0">Reports</h1>
        <p class="admin-hero__subtitle" style="margin:0">Revenue, bookings, payments and trainer performance for any date range.</p>
    </div>

    <div class="report-toolbar">
        <form id="range-form" class="report-range">
            <label class="report-range__field">
                <span>From</span>
                <input type="date" id="from" name="from" class="form-group__control" value="<?= View::e($from) ?>" max="<?= View::e($today) ?>">
            </label>
            <label class="report-range__field">
                <span>To</span>
                <input type="date" id="to" name="to" class="form-group__control" value="<?= View::e($to) ?>" max="<?= View::e($today) ?>">
            </label>
            <button type="submit" class="btn btn-secondary btn-xs">Apply</button>
        </form>

        <div class="report-presets">
            <?php foreach ($presets as $label => [$pFrom, $pTo]):
                $isActive = $pFrom === $from && $pTo === $to;
            ?>
            <a href="/admin/reports?from=<?= View::e($pFrom) ?>&to=<?= View::e($pTo) ?>"
               class="chip<?= $isActive ? ' is-selected' : '' ?>"><?= View::e($label) ?></a>
            <?php endforeach; ?>
        </div>

        <p class="report-toolbar__summary">
            <?= View::e(date('d M Y', strtotime($from))) ?> &ndash; <?= View::e(date('d M Y', strtotime($to))) ?>
            · <?= $rangeDays ?> day<?= $rangeDays === 1 ? '' : 's' ?>
        </p>
    </div>

    <div class="report-list">
        <?php foreach ($reportTypes as $key => $meta):
            $count = $counts[$key] ?? null;
            $isEmpty = $count === 0;
        ?>
        <div class="report-row<?= $isEmpty ? ' is-empty' : '' ?>">
            <span class="report-row__icon"><?= View::icon($meta['icon'], 'icon', 16) ?></span>

            <div class="report-row__text">
                <span class="report-row__label"><?= View::e($meta['label']) ?></span>
                <span class="report-row__hint"><?= View::e($meta['hint']) ?></span>
            </div>

            <!-- Row count for the current range, so an empty export is obvious
                 here rather than after downloading the file. -->
            <span class="report-row__count<?= $isEmpty ? ' is-zero' : '' ?>">
                <?= $count === null ? '&mdash;' : $count . ' row' . ($count === 1 ? '' : 's') ?>
            </span>

            <div class="report-row__actions">
                <a class="btn btn-ghost btn-xs" href="<?= View::e($exportUrl($key, 'print')) ?>" target="_blank" rel="noopener">View</a>
                <a class="btn btn-secondary btn-xs" href="<?= View::e($exportUrl($key, 'csv')) ?>">CSV</a>
                <a class="btn btn-secondary btn-xs" href="<?= View::e($exportUrl($key, 'xlsx')) ?>">Excel</a>
                <a class="btn btn-primary btn-xs" href="<?= View::e($exportUrl($key, 'pdf')) ?>">PDF</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if (array_sum($counts) === 0 && $counts !== []): ?>
    <p class="text-muted" style="font-size:var(--font-size-xs);margin-top:var(--space-3)">
        Nothing recorded in this range — every report would export headers only. Try a wider range.
    </p>
    <?php endif; ?>
</div>
<script type="module">
/* Both dates submit as a GET to /admin/reports so the range lives in the URL
   and stays bookmarkable; the server normalises anything invalid. */
const form = document.getElementById('range-form');
form.addEventListener('submit', (event) => {
    event.preventDefault();
    const from = document.getElementById('from').value;
    const to = document.getElementById('to').value;
    window.location.href = `/admin/reports?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}`;
});
</script>
