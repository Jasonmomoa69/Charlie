let appCache;
const appCacheKey = "website.com-2021-01-07";
const domain = "https://website.com/";

self.addEventListener("push", (event) => {
  const { subject, body } = event.data.json();
  self.registration.showNotification(subject, { body });
});

self.addEventListener("install", async (event) => {
  console.log("installing", appCacheKey);
  appCache = await caches.open(appCacheKey);
  appCache.addAll([
    "https://s3.tradingview.com/tv.js",
    "https://fonts.googleapis.com/icon?family=Material+Icons",
    "https://www.gstatic.com/firebasejs/8.3.0/firebase-app.js",
    "https://www.gstatic.com/firebasejs/8.3.0/firebase-database.js",
    "https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit",
  ]);
  self.skipWaiting();
});

self.addEventListener("activate", async () => {
  console.log("activating", appCacheKey);
  let allCacheKeys = await caches.keys();
  allCacheKeys.forEach((cacheKey) => {
    if (cacheKey != appCacheKey) caches.delete(cacheKey);
  });
});

self.addEventListener("fetch", (event) => {
  // this callback must be syncronous
  // this callback can alter the response by passing a Response object to event.responseWith
  // this callback can alter the response by passing a callback with one argument to event.responseWith

  return event.respondWith(asyncCallback(event));
});

var asyncCallback = async function ({ request }) {
  // this callback must return a Response object

  const { method, url } = request;
  const { protocol, hostname } = location;

  // // home page
  // if (method === "GET" && url === domain) {
  //   const cacheResponse = await caches.match("/");
  //   return cacheResponse;
  // }

  // // static assets
  // if (method === "GET" && url.startsWith(`${protocol}//${hostname}/assets`)) {
  //   if (self.registration.waiting) {
  //     const fetchResponse = await fetch(request);
  //     const fetchResponseClone = await fetchResponse.clone();
  //     appCache = await caches.open(appCacheKey);
  //     appCache.put(url, fetchResponseClone);
  //     return fetchResponse;
  //   }

  const cacheResponse = await caches.match(url);

  if (cacheResponse === undefined) {
    try {
      const fetchResponse = await fetch(request);
      return fetchResponse;
    } catch (error) {
      console.log(error.message);
    }
    // const fetchResponseClone = await fetchResponse.clone();
    // appCache = await caches.open(appCacheKey);
    // appCache.put(url, fetchResponseClone);
  } else {
    return cacheResponse;
  }
  // }

  // fetch from network
  const fetchResponse = await fetch(request);
  return fetchResponse;
};
