// var staticCacheName = "pwa-v" + new Date().getTime();
var staticCacheName = "restaurantCache";
var filesToCache = [
    "/offline",
    "/css/app.css",
    "/js/app.js",
    "/images/icons/icon-72x72.png",
    "/images/icons/icon-96x96.png",
    "/images/icons/icon-128x128.png",
    "/images/icons/icon-144x144.png",
    "/images/icons/icon-152x152.png",
    "/images/icons/icon-192x192.png",
    "/images/icons/icon-384x384.png",
    "/images/icons/icon-512x512.png"
];

self.addEventListener("install", async e => {
    const cache = await caches.open(staticCacheName);
    await cache.addAll(filesToCache);
    return self.skipWaiting();
});

self.addEventListener("activate", e => {
    self.clients.claim();
});

self.addEventListener("fetch", async e => {
    const req = e.request;
    const url = new URL(req.url);

    if (/^\/storage\//.test(url.pathname)) {
        e.respondWith(networkAndCache(req));
        return;
    }

    if (
        url.origin === location.origin + "/api/limonetikCreateOrderForCard" ||
        url.origin === location.origin + "/api/limonetikCreatePayment" ||
        url.origin === location.origin + "/api/limonetikGetOrder" ||
        url.origin === location.origin + "/api/limonetikChargeOrder" ||
        url.origin === location.origin + "/api/limonetikChargeOrderForCard"
    ) {
        return;
    }

    if (
        url.origin === "https://securetoken.googleapis.com" ||
        url.origin === "https://apis.google.com" ||
        url.origin === "https://www.googleapis.com"
    ) {
        return;
    }

    if (url.origin === location.origin) {
        e.respondWith(cacheFirst(req));
    } else {
        e.respondWith(networkAndCache(req));
    }
});

async function cacheFirst(req) {
    const cache = await caches.open(staticCacheName);
    const cached = await cache.match(req);
    return cached || fetch(req);
}

async function networkAndCache(req) {
    const cache = await caches.open(staticCacheName);
    try {
        const fresh = await fetch(req);
        await cache.put(req, fresh.clone());
        return fresh;
    } catch (e) {
        const cached = await cache.match(req);
        return cached;
    }
}
