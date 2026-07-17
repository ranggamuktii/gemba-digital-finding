const CACHE_NAME = 'gdfms-cache-v1';
const urlsToCache = [
  '/',
  '/offline.html',
  '/build/manifest.json'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  // Hanya intercept GET request
  if (event.request.method !== 'GET') return;

  event.respondWith(
    fetch(event.request).catch(() => {
      return caches.match(event.request).then(response => {
        if (response) {
          return response;
        }
        // Jika offline dan me-request HTML, kembalikan halaman offline (jika ada)
        if (event.request.headers.get('accept').includes('text/html')) {
          return caches.match('/offline.html');
        }
      });
    })
  );
});
