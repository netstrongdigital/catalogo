const CACHE_NAME = "netlivre-admin-cache-v1";
const urlsToCache = [
  "<?php just_url(); ?>/painel/inicio/",
  "<?php just_url(); ?>/painel/_layout/style.php" // Substituindo arquivos inexistentes por style.php
];

self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(urlsToCache);
    })
  );
});

self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});