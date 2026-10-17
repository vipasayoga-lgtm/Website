<?php

use App\Core\View;

$pageTitle = 'Gallery — Vipasa Yoga Admin';
$pageCss = 'services';
$portal = 'admin';
$active = 'gallery';
?>
<div class="container" style="padding-block:var(--space-4) var(--space-12)">
    <div class="services-hero" style="margin:0 0 var(--space-5);text-align:left">
        <h1 class="services-hero__title" style="margin:0;font-size:1.6rem;font-weight:var(--font-weight-bold)">Gallery Management</h1>
        <p class="services-hero__subtitle" style="margin:4px 0 0;font-size:0.875rem;color:var(--text-muted)">Manage photo gallery items shown on the public site landing page and profile views.</p>
    </div>

    <div class="card mb-6" style="padding:var(--space-5);border-radius:var(--radius-2xl);box-shadow:0 10px 30px -5px rgba(15, 51, 73, 0.08);background:var(--surface-card)">
        <div style="display:flex;align-items:center;gap:var(--space-2);margin-bottom:var(--space-4)">
            <span style="display:grid;place-items:center;width:2rem;height:2rem;border-radius:var(--radius-full);background:var(--vipasa-blue-50);color:var(--vipasa-blue-700)">
                <?= View::icon('image') ?>
            </span>
            <h2 style="font-size:1.05rem;font-weight:var(--font-weight-bold);margin:0">Upload New Photo</h2>
        </div>
        <form id="gallery-upload-form" class="flex gap-4 flex-wrap items-end">
            <div style="flex:1;min-width:200px">
                <label style="display:block;font-size:0.75rem;font-weight:var(--font-weight-semibold);color:var(--text-muted);margin-bottom:4px">Select Image File</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required class="form-group__control" style="font-size:var(--font-size-xs);padding:6px 10px">
            </div>
            <div class="form-group" style="margin:0;flex:1.5;min-width:180px">
                <input type="text" name="caption" class="form-group__control" placeholder=" " maxlength="200">
                <label class="form-group__label">Caption</label>
            </div>
            <div class="form-group" style="margin:0;flex:1;min-width:130px">
                <input type="text" name="category" class="form-group__control" placeholder=" " maxlength="60">
                <label class="form-group__label">Category (e.g. poses)</label>
            </div>
            <div class="form-group" style="margin:0;max-width:90px">
                <input type="number" name="sort_order" class="form-group__control" value="0">
                <label class="form-group__label">Sort Order</label>
            </div>
            <button type="submit" class="btn btn-primary" style="border-radius:var(--radius-lg);padding:0.55rem 1.25rem">Upload</button>
        </form>
        <div class="form-group__error" id="gallery-upload-error"></div>
    </div>

    <?php if (empty($images)): ?>
    <div class="card text-center" style="padding:var(--space-8);border-radius:var(--radius-2xl)">
        <p class="text-muted" style="margin:0">No photos uploaded yet. Use the form above to add your first photo!</p>
    </div>
    <?php else: ?>
    <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
        <h2 style="font-size:1.1rem;font-weight:var(--font-weight-bold);margin:0">All Photos</h2>
        <span class="badge badge-subtle" style="border-radius:var(--radius-full);padding:0.25rem 0.75rem;font-size:0.78rem;font-weight:var(--font-weight-semibold)"><?= count($images) ?> photo<?= count($images) === 1 ? '' : 's' ?></span>
    </div>
    <div class="table-responsive" style="border-radius:var(--radius-xl);box-shadow:0 8px 24px -4px rgba(0,0,0,0.06)">
        <table class="data-table gallery-table">
            <thead>
                <tr>
                    <th style="width:80px">Preview</th>
                    <th>Caption</th>
                    <th>Category</th>
                    <th style="width:90px">Order</th>
                    <th style="width:90px;text-align:center">Visible</th>
                    <th style="width:160px;text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody id="gallery-tbody">
                <?php foreach ($images as $image): ?>
                <tr data-image-row data-id="<?= (int) $image['id'] ?>"<?= (int) $image['is_active'] === 0 ? ' style="opacity:0.55"' : '' ?>>
                    <td>
                        <div class="gallery-thumb-wrapper">
                            <img src="<?= View::e($image['file_path']) ?>" alt="" class="gallery-thumb" loading="lazy">
                        </div>
                    </td>
                    <td>
                        <input type="text" class="table-input" data-field="caption" placeholder="Caption" value="<?= View::e($image['caption'] ?? '') ?>">
                    </td>
                    <td>
                        <input type="text" class="table-input" data-field="category" placeholder="Category" value="<?= View::e($image['category'] ?? '') ?>">
                    </td>
                    <td class="col-order">
                        <input type="number" class="table-input" data-field="sort_order" value="<?= (int) $image['sort_order'] ?>">
                    </td>
                    <td style="text-align:center">
                        <label class="custom-toggle">
                            <input type="checkbox" data-field="is_active" <?= (int) $image['is_active'] === 1 ? 'checked' : '' ?> aria-label="Visible on public gallery">
                            <span class="custom-toggle__slider"></span>
                        </label>
                    </td>
                    <td class="col-actions" style="text-align:right">
                        <button type="button" class="btn btn-secondary btn-xs save-image-btn" style="border-radius:var(--radius-md)">Save</button>
                        <button type="button" class="btn btn-ghost btn-xs text-error delete-image-btn" style="border-radius:var(--radius-md)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="pagination mt-4" id="gallery-pagination"></div>
    <?php endif; ?>
</div>

<style>
.gallery-thumb-wrapper {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid var(--border-subtle);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.gallery-thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}
.gallery-thumb-wrapper:hover .gallery-thumb {
    transform: scale(1.1);
}
.gallery-table td { vertical-align: middle; padding: var(--space-3) var(--space-4); }
.gallery-table .table-input {
    width: 100%;
    padding: 7px 11px;
    border: 1px solid var(--border-default);
    border-radius: var(--radius-md);
    background: var(--surface-card);
    color: var(--text-primary);
    font-size: var(--font-size-sm);
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.gallery-table .table-input:focus {
    border-color: var(--vipasa-blue-500);
    box-shadow: 0 0 0 3px rgba(45, 106, 136, 0.15);
    outline: none;
}
.gallery-table .col-order input { width: 72px; }
.gallery-table .col-actions { white-space: nowrap; }

/* Custom toggle switch */
.custom-toggle {
    position: relative;
    display: inline-block;
    width: 38px;
    height: 22px;
}
.custom-toggle input { opacity: 0; width: 0; height: 0; }
.custom-toggle__slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background-color: var(--gray-300);
    transition: .3s;
    border-radius: 22px;
}
.custom-toggle__slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.3);
}
.custom-toggle input:checked + .custom-toggle__slider {
    background-color: var(--vipasa-blue-600);
}
.custom-toggle input:checked + .custom-toggle__slider:before {
    transform: translateX(16px);
}
</style>

<script type="module">
import { apiPut, apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';

document.getElementById('gallery-upload-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const form = event.target;
    const errorBox = document.getElementById('gallery-upload-error');
    errorBox.textContent = '';

    const formData = new FormData(form);
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    try {
        const response = await fetch('/admin/gallery', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-CSRF-Token': csrf },
            body: formData,
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Upload failed.');
        window.location.reload();
    } catch (err) {
        errorBox.textContent = err.message;
        toast.error(err.message);
    }
});

document.querySelectorAll('.save-image-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const row = btn.closest('[data-image-row]');
        const id = row.dataset.id;
        try {
            await apiPut(`/admin/gallery/${id}`, {
                caption: row.querySelector('[data-field="caption"]').value.trim(),
                category: row.querySelector('[data-field="category"]').value.trim(),
                sort_order: Number(row.querySelector('[data-field="sort_order"]').value || 0),
                is_active: row.querySelector('[data-field="is_active"]').checked,
            });
            row.style.opacity = row.querySelector('[data-field="is_active"]').checked ? '1' : '0.55';
            toast.success('Saved.');
        } catch (err) {
            toast.error(err.message);
        }
    });
});

document.querySelectorAll('.delete-image-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const row = btn.closest('[data-image-row]');
        try {
            await apiPost(`/admin/gallery/${row.dataset.id}/delete`, {});
            row.remove();
            renderGalleryPage(currentPage);
        } catch (err) {
            toast.error(err.message);
        }
    });
});

/* ---- Pagination: 10 photos per page (client-side) ---- */
const PER_PAGE = 10;
const tbody = document.getElementById('gallery-tbody');
const pager = document.getElementById('gallery-pagination');
let currentPage = 1;

function galleryRows() {
    return tbody ? Array.from(tbody.querySelectorAll('tr[data-image-row]')) : [];
}

function renderGalleryPage(page) {
    const rows = galleryRows();
    const pageCount = Math.max(1, Math.ceil(rows.length / PER_PAGE));
    currentPage = Math.min(Math.max(1, page), pageCount);
    rows.forEach((row, i) => {
        row.hidden = Math.floor(i / PER_PAGE) + 1 !== currentPage;
    });
    renderPager(pageCount);
}

function renderPager(pageCount) {
    if (!pager) return;
    if (pageCount <= 1) { pager.innerHTML = ''; return; }

    const btn = (p, label, { active = false, disabled = false } = {}) =>
        `<button type="button" class="pagination__btn${active ? ' is-active' : ''}" data-page="${p}"${disabled ? ' disabled' : ''}>${label}</button>`;

    let html = btn(currentPage - 1, 'Prev', { disabled: currentPage === 1 });
    for (let p = 1; p <= pageCount; p++) html += btn(p, p, { active: p === currentPage });
    html += btn(currentPage + 1, 'Next', { disabled: currentPage === pageCount });

    pager.innerHTML = html;
    pager.querySelectorAll('[data-page]').forEach((b) => {
        b.addEventListener('click', () => renderGalleryPage(Number(b.dataset.page)));
    });
}

if (tbody) renderGalleryPage(1);
</script>
