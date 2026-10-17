/* =========================================================================
   Vipasa Yoga — App bootstrap
   Wires up truly global chrome (mobile nav toggle) once the DOM is ready.
   Page-specific behavior lives in per-page controller scripts, not here.
   ========================================================================= */

import { initScrollReveal } from './modules/scrollReveal.js';
import { initRipple } from './modules/ripple.js';
import { initPasswordToggles } from './modules/passwordToggle.js';

initRipple();
initPasswordToggles();

function wireMobileNavToggle() {
  document.querySelectorAll('[data-nav-toggle]').forEach((toggleBtn) => {
    const nav = toggleBtn.closest('.nav-glass') ?? document.querySelector('.nav-glass');
    if (!nav) return;

    toggleBtn.addEventListener('click', (event) => {
      event.stopPropagation();
      const isOpen = nav.classList.toggle('is-open');
      toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    nav.querySelectorAll('.nav-glass__link').forEach((link) => {
      link.addEventListener('click', () => {
        nav.classList.remove('is-open');
        toggleBtn.setAttribute('aria-expanded', 'false');
      });
    });

    document.addEventListener('click', (event) => {
      if (!nav.contains(event.target)) {
        nav.classList.remove('is-open');
        toggleBtn.setAttribute('aria-expanded', 'false');
      }
    });
  });
}

function wireProgramCardExpand() {
  document.querySelectorAll('[data-program-more]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const card = btn.closest('.program-card-v2');
      if (!card) return;
      const expanded = card.classList.toggle('is-expanded');
      btn.textContent = expanded ? 'Read Less' : 'Read More';
    });
  });
}

/* Section-level "Show More": toggles .is-collapsed on the grid immediately
   before the button's wrapper. For the gallery, choosing a category filter
   force-expands so matches beyond the collapsed rows aren't clipped. */
function wireShowMore() {
  document.querySelectorAll('[data-show-more]').forEach((btn) => {
    const grid = btn.closest('.section-more')?.previousElementSibling;
    if (!grid) return;

    const revealOverflow = () => {
      grid.querySelectorAll('[data-reveal]:not(.is-revealed)')
        .forEach((el) => el.classList.add('is-revealed'));
    };

    btn.addEventListener('click', () => {
      const collapsed = grid.classList.toggle('is-collapsed');
      btn.textContent = collapsed ? 'Show More' : 'Show Less';
      if (!collapsed) revealOverflow();
    });

    if (grid.id === 'gallery-grid') {
      document.querySelectorAll('.gallery-filters__chip').forEach((chip) => {
        chip.addEventListener('click', () => {
          if (chip.dataset.filter !== 'all' && grid.classList.contains('is-collapsed')) {
            grid.classList.remove('is-collapsed');
            btn.textContent = 'Show Less';
            revealOverflow();
          }
        });
      });
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  wireMobileNavToggle();
  wireProgramCardExpand();
  wireShowMore();
  initScrollReveal();
  initPasswordToggles();
});
