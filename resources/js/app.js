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
  function getDigits(val) {
    return (val || '').replace(/\D/g, '');
  }
  /** En fazla 10 haneye indirir: 90 ile başlıyorsa 90’ı atar, 0 ile başlıyorsa 0’ı atar, fazlaysa son 10’u alır. */
  function toTenDigits(val) {
    const d = getDigits(val);
    if (d.length === 0) return '';
    if (d.length >= 12 && d.startsWith('90')) return d.slice(2, 12);
    if (d.length === 11 && d[0] === '0') return d.slice(1);
    if (d.length > 10) return d.slice(-10);
    return d;
  }
  function formatFromTen(ten) {
    if (ten.length === 0) return '';
    if (ten.length <= 3) return '+90 ' + ten;
    if (ten.length <= 6) return '+90 ' + ten.slice(0, 3) + ' ' + ten.slice(3);
    if (ten.length <= 8) return '+90 ' + ten.slice(0, 3) + ' ' + ten.slice(3, 6) + ' ' + ten.slice(6);
    return '+90 ' + ten.slice(0, 3) + ' ' + ten.slice(3, 6) + ' ' + ten.slice(6, 8) + ' ' + ten.slice(8, 10);
  }
  /** Formatlanmış metinde N. rakamdan sonraki konumu döndürür (boşluk ve +90 dahil). */
  function positionAfterDigits(formatted, digitCount) {
    let digits = 0;
    for (let i = 0; i < formatted.length; i++) {
      if (/\d/.test(formatted[i])) digits++;
      if (digits >= digitCount) return i + 1;
    }
    return formatted.length;
  }
  document.querySelectorAll('[data-phone-mask]').forEach((el) => {
    if (el._phoneMaskInit) return;
    el._phoneMaskInit = true;
    el.setAttribute('inputmode', 'numeric');
    el.setAttribute('autocomplete', 'tel');
    el.addEventListener('input', function () {
      const cursorBefore = this.selectionStart;
      const digitsBeforeCursor = getDigits(this.value.slice(0, cursorBefore)).length;
      const ten = toTenDigits(this.value);
      const formatted = formatFromTen(ten);
      this.value = formatted;
      const newPos = positionAfterDigits(formatted, digitsBeforeCursor);
      this.setSelectionRange(newPos, newPos);
    });
    el.addEventListener('paste', function (e) {
      e.preventDefault();
      const text = (e.clipboardData || window.clipboardData).getData('text');
      const ten = toTenDigits(text);
      this.value = formatFromTen(ten);
    });
    el.addEventListener('keydown', function (e) {
      if (e.key === 'Backspace' || e.key === 'Delete') {
        const start = this.selectionStart;
        const end = this.selectionEnd;
        const digits = getDigits(this.value);
        const digitsBeforeStart = getDigits(this.value.slice(0, start)).length;
        const digitsInSelection = getDigits(this.value.slice(start, end)).length;
        const from = digitsInSelection > 0 ? digitsBeforeStart : (e.key === 'Backspace' ? digitsBeforeStart - 1 : digitsBeforeStart);
        const to = digitsInSelection > 0 ? digitsBeforeStart + digitsInSelection : (e.key === 'Backspace' ? digitsBeforeStart : digitsBeforeStart + 1);
        const targetDigitCount = Math.max(0, from);
        const newTen = digits.slice(0, from) + digits.slice(to);
        this.value = formatFromTen(newTen);
        const newPos = positionAfterDigits(this.value, targetDigitCount);
        this.setSelectionRange(newPos, newPos);
        e.preventDefault();
      }
    });
    const form = el.closest('form');
    if (form && !el.dataset.phoneMaskNoNormalize) {
      form.addEventListener('submit', function () {
        const ten = toTenDigits(el.value);
        if (ten.length === 10) el.value = '0' + ten;
      }, { capture: true });
    }
    if (el.value) el.value = formatFromTen(toTenDigits(el.value));
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
