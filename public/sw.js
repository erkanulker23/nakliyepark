const CACHE_NAME = 'nakliyepark-v3';
const urlsToCache = ['/css/app.css', '/build/assets/app.js', '/build/assets/app.css'];

// Sayfa (HTML) istekleri asla cache'lenmez: giriş durumu her istekte sunucudan gelir
function isDocumentRequest(request) {
  return request.mode === 'navigate' || request.destination === 'document';
}

// Panel sayfaları: her zaman ağdan
function isPanelUrl(url) {
  try {
    const path = new URL(url).pathname;
    return path === '/admin' || path.startsWith('/admin/') ||
           path === '/nakliyeci' || path.startsWith('/nakliyeci/') ||
           path === '/musteri' || path.startsWith('/musteri/');
  } catch (_) { return false; }
}

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) =>
      Promise.allSettled(urlsToCache.map((url) => cache.add(url).catch(() => {})))
    ).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
    ).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;
  const url = event.request.url;

  // Sayfa istekleri: her zaman ağdan, cache yok (oturum tutarlılığı)
  if (isDocumentRequest(event.request) || isPanelUrl(url)) {
    event.respondWith(fetch(event.request));
    return;
  }

  // Sadece statik asset'ler (js, css, resim) cache'lenir
  event.respondWith(
    caches.match(event.request).then((cached) => {
      if (cached) return cached;
      return fetch(event.request).then((response) => {
        if (response.ok && url.startsWith(self.location.origin)) {
          const clone = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone)).catch(() => {});
        }
        return response;
      });
    })
  );
});
