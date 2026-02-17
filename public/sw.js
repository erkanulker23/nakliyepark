const CACHE_NAME = 'nakliyepark-v2';
const urlsToCache = ['/', '/css/app.css', '/build/assets/app.js', '/build/assets/app.css'];

// Panel sayfaları: hiç cache'lenmez ve cache'den sunulmaz (her zaman güncel layout)
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

  if (isPanelUrl(url)) {
    event.respondWith(fetch(event.request));
    return;
  }

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
