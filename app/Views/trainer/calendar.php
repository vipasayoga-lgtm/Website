<?php

use App\Core\View;

$pageTitle = 'Calendar — Vipasa Yoga';
$pageCss = 'trainer';
$portal = 'trainer';
$active = 'calendar';

$statusMeta = [
    'awaiting_trainer_approval' => ['badge-pending', 'Awaiting you'],
    'confirmed' => ['badge-confirmed', 'Confirmed'],
    'completed' => ['badge-completed', 'Completed'],
];

$monthLabel = date('F Y', mktime(0, 0, 0, $month, 1, $year));
$daysInMonth = (int) date('t', mktime(0, 0, 0, $month, 1, $year));
$firstDayOfWeek = (int) date('N', mktime(0, 0, 0, $month, 1, $year));
$today = date('Y-m-d');

$weekdayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
$dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$maxChips = 3;

$cells = array_fill(0, $firstDayOfWeek - 1, null);
for ($day = 1; $day <= $daysInMonth; $day++) {
    $cells[] = $day;
}
while (count($cells) % 7 !== 0) {
    $cells[] = null;
}
$weeks = array_chunk($cells, 7);

$recurringByDay = [];
foreach ($windows as $window) {
    if ((int) $window['is_recurring'] === 1) {
        $recurringByDay[(int) $window['day_of_week']][] = $window;
    }
}
?>
<div class="container" style="padding-block:var(--space-4) var(--space-8)">

    <div class="trainer-hero-card mb-6">
        <div class="trainer-hero-card__main">
            <div class="trainer-hero-card__avatar">
                <span class="trainer-hero-card__avatar-fallback">
                    <?= View::icon('calendar-month', 'icon', 20) ?>
                </span>
            </div>
            <div>
                <h1 class="trainer-hero-card__title">Calendar &amp; Availability</h1>
                <p class="trainer-hero-card__subtitle">Manage your session calendar, weekly recurring slots, and blocked holiday dates.</p>
            </div>
        </div>
        <div class="trainer-hero-card__actions">
            <div class="flex gap-1">
                <a href="/trainer/calendar?view=month" class="btn btn-sm <?= $view === 'month' ? 'btn-primary' : 'btn-secondary' ?>">Month</a>
                <a href="/trainer/calendar?view=week&date=<?= View::e($anchor) ?>" class="btn btn-sm <?= $view === 'week' ? 'btn-primary' : 'btn-secondary' ?>">Week</a>
                <a href="/trainer/calendar?view=day&date=<?= View::e($anchor) ?>" class="btn btn-sm <?= $view === 'day' ? 'btn-primary' : 'btn-secondary' ?>">Day</a>
            </div>
        </div>
    </div>

    <?php if ($view === 'month'): ?>
    <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
        <a href="/trainer/calendar?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="btn btn-secondary btn-sm flex items-center gap-1">
            <?= View::icon('chevron-left', 'icon', 14) ?>
            <span>Prev</span>
        </a>
        <h2 style="font-size:0.95rem;font-weight:700;margin:0;color:var(--text-primary)"><?= View::e($monthLabel) ?></h2>
        <a href="/trainer/calendar?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="btn btn-secondary btn-sm flex items-center gap-1">
            <span>Next</span>
            <?= View::icon('chevron-right', 'icon', 14) ?>
        </a>
    </div>

    <div class="card mb-6" style="padding:10px;overflow-x:auto;border-radius:var(--radius-lg)">
        <table style="width:100%;border-collapse:collapse;min-width:760px">
            <thead>
                <tr>
                    <?php foreach ($weekdayLabels as $label): ?>
                    <th style="text-align:left;padding:6px 10px;font-size:10.5px;text-transform:uppercase;letter-spacing:0.04em;color:var(--text-secondary);border-bottom:1px solid var(--border-subtle)"><?= View::e($label) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($weeks as $week): ?>
                <tr>
                    <?php foreach ($week as $day):
                        if ($day === null) {
                            echo '<td style="vertical-align:top;padding:6px;border:1px solid var(--border-subtle);background-color:var(--surface-bg-alt)"></td>';
                            continue;
                        }

                        $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $dayBookings = $byDate[$dateKey] ?? [];
                        $count = count($dayBookings);
                        $isToday = $dateKey === $today;
                    ?>
                    <td style="vertical-align:top;padding:6px;border:1px solid var(--border-subtle);min-width:98px;<?= $isToday ? 'background-color:var(--color-primary-soft, var(--surface-bg-alt));' : '' ?>">
                        <div class="flex items-center justify-between" style="margin-bottom:4px">
                            <span style="font-weight:700;font-size:0.78rem"><?= $day ?></span>
                            <?php if ($count > 0): ?>
                            <span class="badge badge-neutral" style="font-size:9px;padding:1px 5px"><?= $count ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($count > 0): ?>
                        <div class="flex flex-col gap-1">
                            <?php foreach (array_slice($dayBookings, 0, $maxChips) as $booking):
                                [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
                                $timeLabel = date('g:i A', strtotime($booking['start_time']));
                            ?>
                            <div style="font-size:9.5px;line-height:1.3" title="<?= View::e($booking['package_name'] . ' — ' . $booking['client_name']) ?>">
                                <span class="text-muted"><?= View::e($timeLabel) ?></span>
                                <span class="badge <?= $badgeClass ?>" style="font-size:8px;padding:0px 4px"><?= View::e($badgeLabel) ?></span>
                            </div>
                            <?php endforeach; ?>
                            <?php if ($count > $maxChips): ?>
                            <div class="text-muted" style="font-size:9px">+<?= $count - $maxChips ?> more</div>
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

    <?php elseif ($view === 'week'): ?>
    <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
        <a href="/trainer/calendar?view=week&date=<?= View::e($weekData['prevAnchor']) ?>" class="btn btn-secondary btn-sm flex items-center gap-1">
            <?= View::icon('chevron-left', 'icon', 14) ?>
            <span>Prev week</span>
        </a>
        <h2 style="font-size:0.95rem;font-weight:700;margin:0"><?= View::e(date('d M', strtotime($weekData['start']))) ?> – <?= View::e(date('d M Y', strtotime($weekData['end']))) ?></h2>
        <a href="/trainer/calendar?view=week&date=<?= View::e($weekData['nextAnchor']) ?>" class="btn btn-secondary btn-sm flex items-center gap-1">
            <span>Next week</span>
            <?= View::icon('chevron-right', 'icon', 14) ?>
        </a>
    </div>
    <div class="trainer-schedule mb-6">
        <?php foreach ($weekData['days'] as $day): ?>
        <div class="card" style="padding:10px 12px;<?= $day['date'] === $today ? 'border-left:3px solid var(--color-primary);' : '' ?>">
            <div class="flex items-center justify-between mb-2">
                <strong style="font-size:0.8125rem"><?= View::e(date('D, d M', strtotime($day['date']))) ?></strong>
                <span class="text-muted" style="font-size:11px"><?= count($day['bookings']) ?> session(s)</span>
            </div>
            <?php if (empty($day['bookings'])): ?>
            <p class="text-muted" style="margin:0;font-size:11px">Nothing scheduled.</p>
            <?php else: ?>
            <div class="flex flex-col gap-1">
                <?php foreach ($day['bookings'] as $booking):
                    [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
                ?>
                <div class="flex items-center justify-between flex-wrap gap-2" style="font-size:11.5px">
                    <span><?= View::e(date('g:i A', strtotime($booking['start_time']))) ?> · <?= View::e($booking['client_name']) ?> · <?= View::e($booking['package_name']) ?></span>
                    <span class="badge <?= $badgeClass ?>" style="font-size:9.5px;padding:2px 6px"><?= View::e($badgeLabel) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
        <a href="/trainer/calendar?view=day&date=<?= View::e($dayData['prevAnchor']) ?>" class="btn btn-secondary btn-sm flex items-center gap-1">
            <?= View::icon('chevron-left', 'icon', 14) ?>
            <span>Prev day</span>
        </a>
        <h2 style="font-size:0.95rem;font-weight:700;margin:0"><?= View::e(date('l, d M Y', strtotime($dayData['date']))) ?></h2>
        <a href="/trainer/calendar?view=day&date=<?= View::e($dayData['nextAnchor']) ?>" class="btn btn-secondary btn-sm flex items-center gap-1">
            <span>Next day</span>
            <?= View::icon('chevron-right', 'icon', 14) ?>
        </a>
    </div>
    <div class="trainer-schedule-list mb-6">
        <?php if (empty($dayData['bookings'])): ?>
        <div class="trainer-empty-state">
            <div class="trainer-empty-state__icon"><?= View::icon('calendar-month', 'icon', 20) ?></div>
            <h3 class="trainer-empty-state__title">Nothing scheduled for this day</h3>
        </div>
        <?php else: ?>
        <?php foreach ($dayData['bookings'] as $booking):
            [$badgeClass, $badgeLabel] = $statusMeta[$booking['status']] ?? ['badge-neutral', ucfirst($booking['status'])];
        ?>
        <div class="trainer-schedule-card">
            <div class="trainer-schedule-card__left">
                <div class="trainer-schedule-card__date-badge">
                    <span><?= View::e(date('g:i A', strtotime($booking['start_time']))) ?> – <?= View::e(date('g:i A', strtotime($booking['end_time']))) ?></span>
                </div>
                <div class="trainer-schedule-card__user-info">
                    <div class="trainer-avatar-chip"><?= View::e(strtoupper(substr($booking['client_name'] ?? 'C', 0, 2))) ?></div>
                    <div>
                        <h4 class="trainer-schedule-card__client-name"><?= View::e($booking['client_name']) ?></h4>
                        <div class="trainer-schedule-card__meta">
                            <span class="trainer-package-pill"><?= View::e($booking['package_name']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="badge <?= $badgeClass ?>"><?= View::e($badgeLabel) ?></span>
                <?php if (in_array($booking['status'], ['confirmed', 'completed'], true)): ?>
                <a href="/trainer/sessions/<?= View::e($booking['booking_ref']) ?>" class="btn btn-secondary btn-sm">View Session</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="trainer-section-block">
        <div class="trainer-section-header">
            <div class="flex items-center gap-2">
                <?= View::icon('clock', 'trainer-section-header__icon', 18) ?>
                <h2 class="trainer-section-header__title">Weekly Availability</h2>
            </div>
        </div>
        <div class="card mb-6" style="padding:14px;border-radius:var(--radius-lg)">
            <form id="availability-form">
                <div class="flex flex-col gap-2">
                    <?php foreach ($dayNames as $dow => $name): $existing = $recurringByDay[$dow][0] ?? null; ?>
                    <div class="flex items-center gap-3 flex-wrap" style="font-size:0.78rem">
                        <label class="flex items-center gap-2" style="min-width:110px;font-weight:600">
                            <input type="checkbox" name="enabled[<?= $dow ?>]" <?= $existing ? 'checked' : '' ?>>
                            <?= View::e($name) ?>
                        </label>
                        <input type="time" name="start[<?= $dow ?>]" class="form-group__control" style="max-width:120px;padding:3px 8px;font-size:0.75rem" value="<?= View::e($existing['start_time'] ?? '09:00') ?>">
                        <span class="text-muted">to</span>
                        <input type="time" name="end[<?= $dow ?>]" class="form-group__control" style="max-width:120px;padding:3px 8px;font-size:0.75rem" value="<?= View::e($existing['end_time'] ?? '17:00') ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="form-group__error" id="availability-error"></div>
                <button type="submit" class="btn btn-primary btn-sm mt-3">Save Availability</button>
            </form>
        </div>
    </div>

    <div class="trainer-section-block">
        <div class="trainer-section-header">
            <div class="flex items-center gap-2">
                <?= View::icon('x', 'trainer-section-header__icon', 18) ?>
                <h2 class="trainer-section-header__title">Blocked Dates &amp; Holidays</h2>
            </div>
        </div>
        <div class="card" style="padding:14px;border-radius:var(--radius-lg)">
            <?php if (empty($blockedDates)): ?>
            <p class="text-muted mb-3" style="font-size:0.75rem">No blocked dates.</p>
            <?php else: ?>
            <div class="flex flex-col gap-2 mb-4">
                <?php foreach ($blockedDates as $blocked): ?>
                <div class="flex items-center justify-between" style="padding:4px 0;border-bottom:1px solid var(--border-subtle);font-size:0.78rem">
                    <span><?= View::e(date('d M Y', strtotime($blocked['blocked_date']))) ?><?= $blocked['reason'] ? ' — ' . View::e($blocked['reason']) : '' ?><?= $blocked['instructor_id'] === null ? ' <span class="text-muted">(platform holiday)</span>' : '' ?></span>
                    <?php if ($blocked['instructor_id'] !== null): ?>
                    <button type="button" class="btn btn-ghost btn-sm unblock-btn" data-id="<?= (int) $blocked['id'] ?>" style="font-size:10.5px;padding:2px 6px">Remove</button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form id="block-date-form" class="flex gap-2 flex-wrap items-end">
                <div class="form-group" style="margin:0">
                    <input type="date" name="blocked_date" class="form-group__control" required style="padding:4px 8px;font-size:0.75rem">
                    <label class="form-group__label">Date</label>
                </div>
                <div class="form-group" style="margin:0;flex:1;min-width:180px">
                    <input type="text" name="reason" class="form-group__control" placeholder=" " maxlength="255" style="padding:4px 8px;font-size:0.75rem">
                    <label class="form-group__label">Reason (optional)</label>
                </div>
                <button type="submit" class="btn btn-secondary btn-sm">Block Date</button>
            </form>
        </div>
    </div>
</div>
<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

document.getElementById('availability-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.target;
    const errorBox = document.getElementById('availability-error');
    errorBox.textContent = '';

    const windows = [];
    for (let dow = 0; dow < 7; dow++) {
        const enabled = form.querySelector(`[name="enabled[${dow}]"]`).checked;
        if (!enabled) continue;
        windows.push({
            day_of_week: dow,
            start_time: form.querySelector(`[name="start[${dow}]"]`).value,
            end_time: form.querySelector(`[name="end[${dow}]"]`).value,
        });
    }

    try {
        await apiPost('/trainer/calendar/availability', { windows });
        toast.success('Availability updated.');
    } catch (err) {
        errorBox.textContent = err.message;
        toast.error(err.message);
    }
});

document.getElementById('block-date-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.target;
    try {
        await apiPost('/trainer/calendar/blocked-dates', {
            blocked_date: form.blocked_date.value,
            reason: form.reason.value.trim(),
        });
        window.location.reload();
    } catch (err) {
        toast.error(err.message);
    }
});

document.querySelectorAll('.unblock-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        btn.classList.add('is-loading');
        btn.disabled = true;
        try {
            await apiPost(`/trainer/calendar/blocked-dates/${btn.dataset.id}/delete`, {});
            window.location.reload();
        } catch (err) {
            toast.error(err.message);
            btn.classList.remove('is-loading');
            btn.disabled = false;
        }
    });
});
</script>
