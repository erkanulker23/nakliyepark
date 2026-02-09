/**
 * Blog sayfası için modern web teknolojileri
 * - Intersection Observer: Scroll'da reveal animasyonu
 * - content-visibility: Render performansı
 * - prefers-reduced-motion desteği
 */

const prefersReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function initScrollReveal() {
  if (prefersReducedMotion()) return;

  const items = document.querySelectorAll('.blog-reveal');
  if (!items.length) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
          entry.target.style.animationDelay = `${Math.min(i * 60, 300)}ms`;
          entry.target.classList.add('blog-reveal-visible');
          observer.unobserve(entry.target);
        }
      });
    },
    { rootMargin: '0px 0px -30px 0px', threshold: 0 }
  );

  items.forEach((el) => observer.observe(el));
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initScrollReveal);
} else {
  initScrollReveal();
}
