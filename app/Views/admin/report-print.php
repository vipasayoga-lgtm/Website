<?php

use App\Core\View;

$typeLabels = [
    'revenue' => 'Revenue Report',
    'bookings' => 'Bookings Report',
    'payments' => 'Payments Report',
    'instructor_performance' => 'Instructor Performance Report',
];
$title = $typeLabels[$type] ?? 'Report';

$pageTitle = $title . ' — Vipasa Yoga';
$pageCss = 'booking';
?>
<div class="container" style="max-width:960px;padding-block:var(--space-10)">
    <button type="button" class="btn btn-secondary no-print mb-6" onclick="window.print()">Print / Save as PDF</button>

    <div class="card" style="padding:var(--space-8)">
        <div class="flex items-center justify-between mb-6">
            <div class="splash__wordmark">Vipasa Yoga</div>
            <div class="text-right">
                <div style="font-weight:var(--font-weight-semibold)"><?= View::e($title) ?></div>
                <div class="text-muted" style="font-size:var(--font-size-sm)"><?= View::e($from) ?> to <?= View::e($to) ?></div>
            </div>
        </div>

        <?php if (empty($rows)): ?>
        <p class="text-muted">No data for this date range.</p>
        <?php else: ?>
        <div style="overflow-x:auto">
            <table style="width:100%;font-size:var(--font-size-sm)" class="data-table">
                <thead>
                    <tr>
                        <?php foreach ($headers as $h): ?>
                        <th style="text-align:left;padding:var(--space-2) var(--space-3);border-bottom:2px solid var(--border-default)"><?= View::e($h) ?></th>
                        <?php endforeach; ?>
                        <th class="no-print" style="text-align:center;padding:var(--space-2) var(--space-3);border-bottom:2px solid var(--border-default)">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row):
                        $rowRef = end($row);
                    ?>
                    <tr>
                        <?php foreach ($row as $cell): ?>
                        <td style="padding:var(--space-2) var(--space-3);border-bottom:1px solid var(--border-subtle)"><?= View::e((string) $cell) ?></td>
                        <?php endforeach; ?>
                        <td class="no-print" style="padding:var(--space-2) var(--space-3);border-bottom:1px solid var(--border-subtle);text-align:center">
                            <button type="button" class="btn btn-ghost btn-xs js-delete-report-row" data-ref="<?= View::e((string) $rowRef) ?>" title="Delete row" style="color:var(--color-error,#EF4444);padding:4px">
                                <?= View::icon('trash', 'icon', 14) ?>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<script type="module">
document.querySelectorAll('.js-delete-report-row').forEach(btn => {
    btn.addEventListener('click', async () => {
        const ref = btn.dataset.ref;
        const tr = btn.closest('tr');
        btn.disabled = true;
        if (ref && ref.length > 3) {
            try {
                await fetch(`/admin/bookings/${ref}/delete`, { method: 'POST' });
            } catch(e) {}
        }
        if (tr) tr.remove();
    });
});
</script>
