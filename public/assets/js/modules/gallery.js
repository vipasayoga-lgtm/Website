/* =========================================================================
   Vipasa Yoga — Gallery module (Prime UI Edition)
   Category filter (client-side show/hide + dynamic counters), layout toggles,
   and enhanced dependency-free lightbox with counters & touch swipe support.
   ========================================================================= */

export function initGallery() {
    const grid = document.getElementById('gallery-grid');
    if (!grid) return;

    const items = Array.from(grid.querySelectorAll('.gallery-item'));
    const filterChips = document.querySelectorAll('.gallery-filters__chip');
    const viewButtons = document.querySelectorAll('.gallery-view-toggle__btn');
    const emptyMessage = document.querySelector('.gallery-empty');

    // Update category photo counts dynamically on load
    updateCategoryCounts(items);

    filterChips.forEach((chip) => {
        chip.addEventListener('click', () => {
            filterChips.forEach((c) => c.classList.remove('is-selected'));
            chip.classList.add('is-selected');

            const filter = chip.dataset.filter;
            let visibleCount = 0;

            items.forEach((item) => {
                const matches = filter === 'all' || item.dataset.category === filter;
                item.hidden = !matches;
                if (matches) {
                    visibleCount += 1;
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(12px) scale(0.96)';
                    requestAnimationFrame(() => {
                        item.style.transition = 'all 0.35s cubic-bezier(0.16, 1, 0.3, 1)';
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0) scale(1)';
                    });
                }
            });

            if (emptyMessage) emptyMessage.hidden = visibleCount > 0;
        });
    });

    viewButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            viewButtons.forEach((b) => b.classList.remove('is-active'));
            btn.classList.add('is-active');
            grid.dataset.view = btn.dataset.view;
        });
    });

    initLightbox(items);
}

function updateCategoryCounts(items) {
    const counts = { all: items.length };
    items.forEach((item) => {
        const cat = item.dataset.category;
        if (cat) {
            counts[cat] = (counts[cat] || 0) + 1;
        }
    });

    document.querySelectorAll('[data-count-for]').forEach((el) => {
        const catKey = el.dataset.countFor;
        el.textContent = counts[catKey] ?? 0;
    });
}

function initLightbox(items) {
    const lightbox = document.getElementById('lightbox');
    const imgEl = document.getElementById('lightbox-img');
    const captionEl = document.getElementById('lightbox-caption');
    const currentIdxEl = document.getElementById('lightbox-current-idx');
    const totalIdxEl = document.getElementById('lightbox-total-idx');
    if (!lightbox || !imgEl || !captionEl) return;

    let currentIndex = 0;

    function visibleItems() {
        return items.filter((item) => !item.hidden);
    }

    function show(index) {
        const list = visibleItems();
        if (!list.length) return;
        currentIndex = (index + list.length) % list.length;
        const item = list[currentIndex];
        const img = item.querySelector('img');
        const captionText = item.querySelector('.gallery-item__caption-text')?.textContent
            || item.querySelector('.gallery-item__caption')?.textContent
            || '';

        imgEl.style.opacity = '0.3';
        imgEl.style.transform = 'scale(0.97)';

        setTimeout(() => {
            imgEl.src = img.src;
            imgEl.alt = img.alt;
            captionEl.textContent = captionText;

            if (currentIdxEl) currentIdxEl.textContent = currentIndex + 1;
            if (totalIdxEl) totalIdxEl.textContent = list.length;

            imgEl.style.opacity = '1';
            imgEl.style.transform = 'scale(1)';
        }, 120);
    }

    function open(index) {
        show(index);
        lightbox.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function close() {
        lightbox.hidden = true;
        document.body.style.overflow = '';
    }

    items.forEach((item) => {
        const trigger = item.querySelector('.gallery-item__trigger');
        trigger?.addEventListener('click', () => {
            const list = visibleItems();
            const idx = list.indexOf(item);
            open(idx === -1 ? 0 : idx);
        });
    });

    lightbox.querySelector('[data-lightbox-close]')?.addEventListener('click', close);
    lightbox.querySelector('[data-lightbox-prev]')?.addEventListener('click', () => show(currentIndex - 1));
    lightbox.querySelector('[data-lightbox-next]')?.addEventListener('click', () => show(currentIndex + 1));

    lightbox.addEventListener('click', (event) => {
        if (event.target === lightbox) close();
    });

    // Touch swipe support for mobile lightbox navigation
    let touchStartX = 0;
    let touchEndX = 0;

    lightbox.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    lightbox.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 40) {
            if (diff > 0) show(currentIndex + 1); // swipe left -> next
            else show(currentIndex - 1);         // swipe right -> prev
        }
    }, { passive: true });

    document.addEventListener('keydown', (event) => {
        if (lightbox.hidden) return;
        if (event.key === 'Escape') close();
        if (event.key === 'ArrowLeft') show(currentIndex - 1);
        if (event.key === 'ArrowRight') show(currentIndex + 1);
    });
}

