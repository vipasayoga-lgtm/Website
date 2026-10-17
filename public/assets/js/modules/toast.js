/* =========================================================================
   Vipasa Yoga — Toast notifications
   ========================================================================= */

const ICONS = {
  success: '✓',
  error: '✕',
  warning: '!',
  info: 'i',
};

const LEAVE_DURATION = 300;

function getContainer() {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }
  return container;
}

function removeToast(toastEl) {
  if (!toastEl.isConnected) return;
  toastEl.classList.add('is-leaving');
  setTimeout(() => toastEl.remove(), LEAVE_DURATION);
}

export function showToast({ type = 'info', title, message, duration = 4500 } = {}) {
  const container = getContainer();

  const toastEl = document.createElement('div');
  toastEl.className = `toast toast-${type}`;
  toastEl.setAttribute('role', 'status');

  const iconEl = document.createElement('div');
  iconEl.className = 'toast__icon';
  iconEl.textContent = ICONS[type] ?? ICONS.info;

  const bodyEl = document.createElement('div');
  bodyEl.className = 'toast__body';

  if (title) {
    const titleEl = document.createElement('div');
    titleEl.className = 'toast__title';
    titleEl.textContent = title;
    bodyEl.appendChild(titleEl);
  }

  if (message) {
    const messageEl = document.createElement('div');
    messageEl.className = 'toast__message';
    messageEl.textContent = message;
    bodyEl.appendChild(messageEl);
  }

  const closeEl = document.createElement('button');
  closeEl.type = 'button';
  closeEl.className = 'toast__close';
  closeEl.setAttribute('aria-label', 'Dismiss notification');
  closeEl.innerHTML = '&times;';
  closeEl.addEventListener('click', (e) => {
    e.stopPropagation();
    removeToast(toastEl);
  });

  toastEl.append(iconEl, bodyEl, closeEl);

  // Auto-dismiss with pause on hover or touch
  let dismissTimer = null;
  let remaining = duration;
  let startTime = Date.now();

  function startTimer() {
    if (duration <= 0) return;
    startTime = Date.now();
    dismissTimer = setTimeout(() => removeToast(toastEl), remaining);
  }

  function pauseTimer() {
    if (!dismissTimer) return;
    clearTimeout(dismissTimer);
    dismissTimer = null;
    remaining -= Date.now() - startTime;
    if (remaining < 600) remaining = 600;
  }

  toastEl.addEventListener('mouseenter', pauseTimer);
  toastEl.addEventListener('mouseleave', startTimer);

  // Mobile Touch Gestures: Swipe Up to Dismiss
  let touchStartY = 0;
  let touchStartX = 0;
  let isSwiping = false;

  toastEl.addEventListener('touchstart', (e) => {
    if (e.touches.length === 1) {
      touchStartY = e.touches[0].clientY;
      touchStartX = e.touches[0].clientX;
      isSwiping = true;
      pauseTimer();
    }
  }, { passive: true });

  toastEl.addEventListener('touchmove', (e) => {
    if (!isSwiping || e.touches.length !== 1) return;
    const deltaY = e.touches[0].clientY - touchStartY;
    const deltaX = e.touches[0].clientX - touchStartX;

    // Upward drag towards top of phone screen
    if (deltaY < 0 && Math.abs(deltaY) > Math.abs(deltaX)) {
      toastEl.style.transform = `translateY(${deltaY}px)`;
      toastEl.style.opacity = `${Math.max(0.1, 1 - Math.abs(deltaY) / 100)}`;
    }
  }, { passive: true });

  toastEl.addEventListener('touchend', (e) => {
    if (!isSwiping) return;
    isSwiping = false;
    const touchEndY = e.changedTouches[0].clientY;
    const deltaY = touchEndY - touchStartY;

    if (deltaY < -24) {
      removeToast(toastEl);
    } else {
      toastEl.style.transform = '';
      toastEl.style.opacity = '';
      startTimer();
    }
  }, { passive: true });

  container.appendChild(toastEl);
  startTimer();

  return toastEl;
}

const toast = {
  success: (message, title = 'Success') => showToast({ type: 'success', title, message }),
  error: (message, title = 'Error') => showToast({ type: 'error', title, message }),
  warning: (message, title = 'Warning') => showToast({ type: 'warning', title, message }),
  info: (message, title = 'Info') => showToast({ type: 'info', title, message }),
};

export default toast;
