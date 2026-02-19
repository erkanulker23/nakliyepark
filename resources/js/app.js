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

// Firmam dropdown (nakliyeci girişli): Firmam + Çıkış
function initFirmamDropdown() {
  const wrap = document.getElementById('firmam-dropdown-wrap');
  const btn = document.querySelector('.firmam-dropdown-btn');
  const panel = document.getElementById('firmam-dropdown-menu');
  const chevron = document.querySelector('.firmam-dropdown-chevron');
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

// Türk telefon maskesi: +90 5XX XXX XX XX (tek format, tüm projede)
function initPhoneMask() {
  function toTenDigits(val) {
    const d = (val || '').replace(/\D/g, '');
    if (d.length === 0) return '';
    if (d.length >= 12 && d.startsWith('90')) return d.slice(2, 12);
    if (d.length >= 11 && d[0] === '0') return d.slice(1, 11);
    if (d.length >= 10 && d[0] === '5') return d.slice(0, 10);
    if (d.length > 10) return d.slice(-10);
    return d;
  }
  function formatTurkishPhone(val) {
    const ten = toTenDigits(val);
    if (ten.length === 0) return '';
    if (ten.length <= 3) return '+90 ' + ten;
    if (ten.length <= 6) return '+90 ' + ten.slice(0, 3) + ' ' + ten.slice(3);
    if (ten.length <= 8) return '+90 ' + ten.slice(0, 3) + ' ' + ten.slice(3, 6) + ' ' + ten.slice(6);
    return '+90 ' + ten.slice(0, 3) + ' ' + ten.slice(3, 6) + ' ' + ten.slice(6, 8) + ' ' + ten.slice(8, 10);
  }
  function phoneValueForSubmit(val) {
    const ten = toTenDigits(val);
    if (ten.length !== 10) return val;
    return '0' + ten;
  }
  document.querySelectorAll('[data-phone-mask]').forEach((el) => {
    if (el._phoneMaskInit) return;
    el._phoneMaskInit = true;
    el.setAttribute('inputmode', 'numeric');
    el.setAttribute('autocomplete', 'tel');
    el.addEventListener('input', function () {
      const start = this.selectionStart;
      const prevLen = this.value.length;
      this.value = formatTurkishPhone(this.value);
      const diff = this.value.length - prevLen;
      const newStart = Math.min(Math.max(0, start + diff), this.value.length);
      this.setSelectionRange(newStart, newStart);
    });
    el.addEventListener('paste', function (e) {
      e.preventDefault();
      const text = (e.clipboardData || window.clipboardData).getData('text');
      this.value = formatTurkishPhone(text);
    });
    el.addEventListener('blur', function () {
      const ten = toTenDigits(this.value);
      if (ten.length === 10) this.value = formatTurkishPhone(this.value);
    });
    const form = el.closest('form');
    if (form && !el.dataset.phoneMaskNoNormalize) {
      form.addEventListener('submit', function () {
        const ten = toTenDigits(el.value);
        if (ten.length === 10) el.value = phoneValueForSubmit(el.value);
      }, { capture: true });
    }
    if (el.value) el.value = formatTurkishPhone(el.value);
  });
}

function initHeader() {
  initToolsDropdown();
  initFirmamDropdown();
  initHeaderNotificationsDropdown();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    initHeader();
    initPhoneMask();
  });
} else {
  initHeader();
  initPhoneMask();
}
