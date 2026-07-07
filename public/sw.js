const CACHE = "hris-v2";

self.addEventListener("install", (event) => {
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys.map((key) => {
                    if (key !== CACHE) {
                        return caches.delete(key);
                    }
                }),
            ),
        ),
    );

    self.clients.claim();
});

self.addEventListener("fetch", (event) => {
    // Jangan cache request POST
    if (event.request.method !== "GET") {
        return;
    }

    // Jangan cache halaman login/logout
    const url = new URL(event.request.url);

    if (url.pathname === "/login" || url.pathname === "/logout") {
        return;
    }

    // Cache hanya asset
    if (
        url.pathname.startsWith("/sbadmin") ||
        url.pathname.startsWith("/assets")
    ) {
        event.respondWith(
            caches.open(CACHE).then(async (cache) => {
                const cached = await cache.match(event.request);

                if (cached) {
                    return cached;
                }

                const response = await fetch(event.request);

                cache.put(event.request, response.clone());

                return response;
            }),
        );
    }
});
