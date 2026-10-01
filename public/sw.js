const CACHE_NAME = 'mohagung-web-v2';
const urlsToCache = [
    '/',
    '/dashboard/finances',
    '/manifest.json'
];

self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return Promise.all(
                urlsToCache.map((url) => {
                    return cache.add(url).catch((error) => {
                        console.error('Failed to cache:', url, error);
                    });
                })
            );
        })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        })
    );
    return self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;
    
    event.respondWith(
        fetch(event.request)
            .then((response) => {
                const responseClone = response.clone();
                caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, responseClone);
                });
                return response;
            })
            .catch(() => {
                return caches.match(event.request).then((cacheRes) => {
                    if (cacheRes) return cacheRes;
                    
                    if (event.request.mode === 'navigate') {
                        return caches.match('/dashboard/finances') || caches.match('/');
                    }
                });
            })
    );
});
