const OFFLINE_VERSION = 1;
const CACHE_NAME = "v1";
const RESOURCES = [
    '/gleichungen/offline.html',
    '/gleichungen/manifest.json',
    '/gleichungen/student/task_new_teacher.php',
    '/gleichungen/images/num0.png',
    '/gleichungen/images/num1.png',
    '/gleichungen/images/num2.png',
    '/gleichungen/images/num3.png',
    '/gleichungen/images/num4.png',
    '/gleichungen/images/num5.png',
    '/gleichungen/images/num6.png',
    '/gleichungen/images/num7.png',
    '/gleichungen/images/num8.png',
    '/gleichungen/images/num9.png',
    '/gleichungen/images/del.png',
    '/gleichungen/style/bg-sibi.png',
    '/gleichungen/style/main.css',
    '/gleichungen/style/style_mobile.css',
    '/gleichungen/include_math/display_functions.js',
    '/gleichungen/include_math/display.js',
    '/gleichungen/include_math/display_constans.js',
    '/gleichungen/include_math/solve.js',
    '/gleichungen/include_math/tex-svg.js',
    '/gleichungen/include/jquery-3.2.1.min.js',
    'https://unpkg.com/mathjs@8.1.0/lib/browser/math.js',
    'https://polyfill.io/v3/polyfill.min.js'
    
];


// Customize this with a different URL if needed.
const OFFLINE_URL = "/gleichungen/offline.html";
const addResourcesToCache = async (resources) => {
  const cache = await caches.open(CACHE_NAME);
  await cache.addAll(resources);
};

const putInCache = async (request, response) => {
  const cache = await caches.open(CACHE_NAME);
  await cache.put(request, response);
};

const cacheFirst = async ({ request, preloadResponsePromise, fallbackUrl }) => {
  // First try to get the resource from the cache
  const responseFromCache = await caches.match(request);
  if (responseFromCache && RESOURCES.includes(request)) {
    return responseFromCache;
  }

  // Next try to use the preloaded response, if it's there
  const preloadResponse = await preloadResponsePromise;
  if (preloadResponse) {
    console.info('using preload response', preloadResponse);
    putInCache(request, preloadResponse.clone());
    return preloadResponse;
  }

  // Next try to get the resource from the network
  try {
    const responseFromNetwork = await fetch(request);
    // response may be used only once
    // we need to save clone to put one copy in cache
    // and serve second one
    if (RESOURCES.includes(request)){
      putInCache(request, responseFromNetwork.clone());
    }
    return responseFromNetwork;
  } catch (error) {
    const fallbackResponse = await caches.match(OFFLINE_URL);
    if (fallbackResponse) {
      return fallbackResponse;
    }
    // when even the fallback response is not available,
    // there is nothing we can do, but we must always
    // return a Response object
  }
};

const enableNavigationPreload = async () => {
  if (self.registration.navigationPreload) {
    // Enable navigation preloads!
    await self.registration.navigationPreload.enable();
  }
};

self.addEventListener('activate', (event) => {
  event.waitUntil(enableNavigationPreload());
});

self.addEventListener('install', (event) => {
  event.waitUntil(
    addResourcesToCache(RESOURCES)
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    cacheFirst({
      request: event.request,
      preloadResponsePromise: event.preloadResponse,
      fallbackUrl: OFFLINE_URL,
    })
  );
});