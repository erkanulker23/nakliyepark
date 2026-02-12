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

// Bildirim dropdown: çan tıklanınca aç/kapa
function initHeaderNotificationsDropdown() {
  const wrap = document.getElementById('header-notifications-wrap');
  if (!wrap) return;
  const btn = wrap.querySelector('#header-notifications-btn') || document.getElementById('header-notifications-btn');
  const panel = wrap.querySelector('#header-notifications-panel') || document.getElementById('header-notifications-panel');
  if (!btn || !panel) return;

  function isOpen() {
    return !panel.classList.contains('hidden') && !panel.hidden;
  }
  function open() {
    panel.classList.remove('hidden');
    panel.hidden = false;
    btn.setAttribute('aria-expanded', 'true');
  }
  function close() {
    panel.classList.add('hidden');
    panel.hidden = true;
    btn.setAttribute('aria-expanded', 'false');
  }
  function toggle() {
    if (isOpen()) close(); else open();
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

function initHeader() {
  initToolsDropdown();
  initHeaderNotificationsDropdown();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initHeader);
} else {
  initHeader();
}
