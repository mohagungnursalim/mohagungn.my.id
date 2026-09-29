const CACHE_NAME = 'mohagungn-pwa-v2';

// Daftar aset statis utama yang ingin langsung di-cache saat instalasi
const urlsToCache = [
    '/',
    '/dashboard',
    '/dashboard/finances',
    '/offline.html' // Buat file HTML sederhana untuk tampilan offline jika diperlukan
];

// 1. Fase Install: Menyimpan aset awal ke cache
self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(urlsToCache);
        })
    );
});

// 2. Fase Activate: Menghapus cache versi lama dan mengambil alih kontrol
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

// 3. Fase Fetch: Strategi Network First dengan Fallback Cache
self.addEventListener('fetch', (event) => {
    // Hanya tangani method GET
    if (event.request.method !== 'GET') return;

    // Abaikan request dari ekstensi browser atau selain http/https
    if (!event.request.url.startsWith('http')) return;

    event.respondWith(
        fetch(event.request)
            .then((networkResponse) => {
                // Jika berhasil terhubung ke internet, kembalikan respon dari network
                return networkResponse;
            })
            .catch(() => {
                // Jika gagal/offline, coba ambil dari cache
                return caches.match(event.request).then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    
                    // Jika yang diminta adalah halaman (navigasi) dan tidak ada di cache, 
                    // arahkan ke halaman offline atau dashboard utama jika tersimpan
                    if (event.request.mode === 'navigate') {
                        return caches.match('/dashboard/finances') || caches.match('/');
                    }
                });
            })
    );
});