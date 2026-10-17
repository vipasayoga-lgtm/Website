<?php

use App\Core\View;
use App\Models\ClientProfile;

$pageTitle = 'Manage Customers — Vipasa Yoga';
$pageCss = 'booking';
$portal = 'admin';
$active = 'customers';

$segment = $segment ?? '';
$segmentCounts = $segmentCounts ?? ['all' => 0, 'no_booking' => 0, 'booked' => 0];

/** Preserves the other filters when switching one of them. */
$filterUrl = static function (array $overrides) use ($q, $status, $segment): string {
    $params = array_filter([
        'q' => $overrides['q'] ?? $q,
        'status' => $overrides['status'] ?? $status,
        'segment' => array_key_exists('segment', $overrides) ? $overrides['segment'] : $segment,
        'page' => $overrides['page'] ?? null,
    ], static fn ($v) => $v !== null && $v !== '');

    return '/admin/customers' . ($params ? '?' . http_build_query($params) : '');
};

$segmentTabs = [
    '' => ['All clients', 'Everyone registered on the site.'],
    'no_booking' => ['Signed up, not booked', 'Created an account but has never booked a session.'],
    'booked' => ['Booked a session', 'Has at least one booking on record.'],
];
?>
<div class="container" style="padding-block:var(--space-8) var(--space-16)">
    <div class="flex items-center justify-between flex-wrap gap-3 mb-6">
        <div class="services-hero" style="margin:0;text-align:left">
            <h1 class="services-hero__title" style="margin:0">Manage Customers</h1>
            <p class="services-hero__subtitle" style="margin:0">Browse registered clients, profile details, assessment answers, bookings, and payment history.</p>
        </div>
        <div>
            <!-- Server-side export: sends the current q/status/segment so the file
                 contains every matching client, not just the page on screen. -->
            <a href="/admin/customers/export<?= ($url = $filterUrl([])) !== '/admin/customers' ? substr($url, strlen('/admin/customers')) : '' ?>"
               class="btn btn-secondary btn-sm" id="export-customers-csv">
                <?= View::icon('download', 'icon', 16) ?> Download CSV
            </a>
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

    <form method="get" action="/admin/customers" class="flex gap-3 flex-wrap items-center mb-4" id="customer-search-form">
        <input type="text" name="q" id="customer-search" class="form-group__control" style="flex:1;min-width:240px"
               placeholder="Search by name, phone or email…" value="<?= View::e($q ?? '') ?>" autocomplete="off">
        <input type="hidden" name="status" value="<?= View::e($status ?? '') ?>">
        <input type="hidden" name="segment" value="<?= View::e($segment) ?>">
        <button type="submit" class="btn btn-secondary btn-sm">Search</button>
        <?php if ($q): ?>
        <a href="<?= View::e($filterUrl(['q' => null])) ?>" class="chip">Clear</a>
        <?php endif; ?>
        <span class="text-muted" id="search-status" style="font-size:var(--font-size-xs)" aria-live="polite"></span>
    </form>

    <div class="services-filters mb-6">
        <a href="<?= View::e($filterUrl(['status' => null, 'page' => null])) ?>" class="chip<?= ($status ?? '') === '' ? ' is-selected' : '' ?>">Active customers</a>
        <a href="<?= View::e($filterUrl(['status' => 'test', 'page' => null])) ?>" class="chip<?= ($status ?? '') === 'test' ? ' is-selected' : '' ?>">Test accounts</a>
        <a href="<?= View::e($filterUrl(['status' => 'all', 'page' => null])) ?>" class="chip<?= ($status ?? '') === 'all' ? ' is-selected' : '' ?>">All</a>
    </div>

    <?php if (empty($customers)): ?>
    <p class="text-muted text-center">No customers match this search.</p>
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
                    /* data-search feeds the instant client-side filter below: name,
                       email and a digits-only phone so "9810" matches "+91 9810…". */
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
<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

/* Search behaves two ways at once, deliberately:
   1. Typing filters the rows already on screen instantly (no round trip).
   2. Enter — or the Search button — submits the form so the server searches
      every client, not just this page. The form is a plain GET, so Enter
      already worked; what's new is the instant filter and the row count that
      tells you when the on-screen filter is hiding matches on other pages. */
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
            statusEl.textContent = '';
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

/* Deleting a client is the most destructive action in the panel: their
   bookings cascade away, and payments cascade from those. The server refuses
   anyone with bookings, and the confirm text names the count so the admin
   knows before clicking rather than after. */
document.querySelectorAll('.delete-customer-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const name = btn.dataset.name || 'this client';
        const bookings = Number(btn.dataset.bookings || 0);

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
