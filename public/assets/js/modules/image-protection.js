/**
 * Vipasa Yoga — Image Protection & Anti-Copy System
 * Provides defense-in-depth practical deterrents against casual image downloading,
 * dragging, right-clicking, and mobile long-press saving.
 */

export function initImageProtection() {
    // 1. Mark all existing and dynamically added images with draggable="false"
    function secureImages(root = document) {
        root.querySelectorAll('img').forEach((img) => {
            img.setAttribute('draggable', 'false');
            img.setAttribute('oncontextmenu', 'return false;');
        });
    }

    secureImages();

    // Observe any dynamically inserted images (e.g. lightbox, ajax)
    if ('MutationObserver' in window) {
        const observer = new MutationObserver((mutations) => {
            for (const mutation of mutations) {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) {
                        if (node.tagName === 'IMG') {
                            node.setAttribute('draggable', 'false');
                            node.setAttribute('oncontextmenu', 'return false;');
                        } else {
                            secureImages(node);
                        }
                    }
                });
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });
    }

    // 2. Prevent right-click context menu on images and media containers
    const mediaSelectors = 'img, picture, .gallery-item, .benefit-card, .program-card-v2, .instructor-spotlight, .lightbox__media, [data-protected-media]';

    let toastTimeout = null;
    function showProtectionNotice(x, y) {
        let toast = document.getElementById('vy-protect-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'vy-protect-toast';
            toast.className = 'vy-protect-toast';
            toast.textContent = 'Protected by Vipasa Yoga';
            document.body.appendChild(toast);
        }

        clearTimeout(toastTimeout);
        toast.style.left = `${Math.min(x + 12, window.innerWidth - 220)}px`;
        toast.style.top = `${Math.min(y + 12, window.innerHeight - 60)}px`;
        toast.classList.add('is-visible');

        toastTimeout = setTimeout(() => {
            toast.classList.remove('is-visible');
        }, 1600);
    }

    document.addEventListener('contextmenu', (e) => {
        const target = e.target;
        if (target && target.closest(mediaSelectors)) {
            e.preventDefault();
            e.stopPropagation();
            showProtectionNotice(e.clientX, e.clientY);
            return false;
        }
    }, { capture: true });

    // 3. Prevent dragging images to desktop or other tabs
    document.addEventListener('dragstart', (e) => {
        const target = e.target;
        if (target && (target.tagName === 'IMG' || target.closest(mediaSelectors))) {
            e.preventDefault();
            return false;
        }
    }, { capture: true });

    // 4. Intercept common save hotkeys (Ctrl+S, Cmd+S)
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && (e.key === 's' || e.key === 'S')) {
            // If focused on or hovering an image
            const hovered = document.querySelector(':hover');
            if (hovered && hovered.closest(mediaSelectors)) {
                e.preventDefault();
                showProtectionNotice(window.innerWidth / 2 - 80, window.innerHeight / 2);
                return false;
            }
        }
    });
}
