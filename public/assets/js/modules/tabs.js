/* =========================================================================
   Vipasa Yoga — generic tab switcher
   Works with the .tab-nav / .tab-nav__item / .tab-panel[hidden] markup in
   components.css. Pass the tab container element; panels are matched to
   nav buttons via data-tab-target -> panel id.
   ========================================================================= */
export function initTabs(root) {
  const nav = root.querySelector('.tab-nav');
  if (!nav) return;

  nav.querySelectorAll('.tab-nav__item').forEach((btn) => {
    btn.addEventListener('click', () => {
      nav.querySelectorAll('.tab-nav__item').forEach((b) => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      root.querySelectorAll('.tab-panel').forEach((panel) => {
        panel.hidden = panel.id !== btn.dataset.tabTarget;
      });
    });
  });
}
