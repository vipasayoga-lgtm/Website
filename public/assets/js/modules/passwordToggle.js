/* =========================================================================
   Vipasa Yoga — Password Visibility Toggle Module
   Guarantees click interaction across static and dynamic password inputs.
   ========================================================================= */

const EYE_ICON_SVG = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
const EYE_OFF_ICON_SVG = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;

export function initPasswordToggles() {
  // 1. Inject toggle buttons into any .form-group with input[type="password"] if missing
  document.querySelectorAll('.form-group input[type="password"], .form-group input[type="text"][data-is-password="true"]').forEach((input) => {
    const parentGroup = input.closest('.form-group');
    if (!parentGroup) return;

    input.setAttribute('data-is-password', 'true');

    if (!parentGroup.querySelector('.password-toggle')) {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'password-toggle';
      btn.innerHTML = input.type === 'text' ? EYE_OFF_ICON_SVG : EYE_ICON_SVG;
      btn.setAttribute('aria-label', input.type === 'text' ? 'Hide password' : 'Show password');
      btn.setAttribute('title', input.type === 'text' ? 'Hide password' : 'Show password');
      parentGroup.appendChild(btn);
    }
  });
}

// 2. Global Delegated Click Handler — works for both static & dynamically injected elements
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.password-toggle, [data-password-toggle]');
  if (!btn) return;

  e.preventDefault();
  e.stopPropagation();

  const targetId = btn.dataset.passwordToggle || btn.getAttribute('aria-controls');
  let input = targetId ? document.getElementById(targetId) : null;

  if (!input) {
    const parentGroup = btn.closest('.form-group') || btn.parentElement;
    input = parentGroup ? parentGroup.querySelector('input[type="password"], input[data-is-password="true"]') : null;
  }

  if (input) {
    const currentlyText = input.type === 'text';
    const nextType = currentlyText ? 'password' : 'text';

    input.type = nextType;
    input.setAttribute('data-is-password', 'true');

    btn.innerHTML = nextType === 'text' ? EYE_OFF_ICON_SVG : EYE_ICON_SVG;
    btn.setAttribute('aria-label', nextType === 'text' ? 'Hide password' : 'Show password');
    btn.setAttribute('title', nextType === 'text' ? 'Hide password' : 'Show password');

    // Refocus input smoothly
    input.focus();
  }
});
