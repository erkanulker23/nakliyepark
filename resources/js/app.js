import './bootstrap';

// PWA: Register Service Worker for "Add to Home Screen"
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
  });
}

// Yardımcı araçlar dropdown: tıklayınca aç/kapa, dışarı tıklayınca kapat
function initToolsDropdown() {
  const wrap = document.getElementById('tools-dropdown-wrap');
  const btn = document.querySelector('.tools-dropdown-btn');
  const panel = document.getElementById('tools-dropdown-menu');
  const chevron = document.querySelector('.tools-dropdown-chevron');
  if (!wrap || !btn || !panel) return;

  function open() {
    panel.hidden = false;
    btn.setAttribute('aria-expanded', 'true');
    if (chevron) chevron.style.transform = 'rotate(180deg)';
  }
  function close() {
    panel.hidden = true;
    btn.setAttribute('aria-expanded', 'false');
    if (chevron) chevron.style.transform = '';
  }
  function toggle() {
    if (panel.hidden) open(); else close();
  }

  btn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    toggle();
  });
  document.addEventListener('click', (e) => {
    if (!wrap.contains(e.target)) close();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') close();
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initToolsDropdown);
} else {
  initToolsDropdown();
}
