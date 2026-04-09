const fallbackApiBaseUrl = 'https://exoinvite.site';

const rawApiBaseUrl = process.env.EXPO_PUBLIC_API_BASE_URL;

function normalizeBaseUrl(url: string): string {
  return url.trim().replace(/\/+$/, '');
}

function isLocalDevelopmentHost(hostname: string): boolean {
  return (
    hostname === 'localhost' ||
    hostname === '127.0.0.1' ||
    hostname === '10.0.2.2' ||
    hostname.endsWith('.local')
  );
}

function resolveApiBaseUrl(): string {
  const candidate =
    rawApiBaseUrl && rawApiBaseUrl.trim().length > 0
      ? rawApiBaseUrl
      : fallbackApiBaseUrl;

  const baseUrl = normalizeBaseUrl(candidate);

  let parsedUrl: URL;

  try {
    parsedUrl = new URL(baseUrl);
  } catch {
    throw new Error('EXPO_PUBLIC_API_BASE_URL tidak valid.');
  }

  const isHttpsUrl = parsedUrl.protocol === 'https:';
  const isAllowedLocalHttp =
    parsedUrl.protocol === 'http:' && isLocalDevelopmentHost(parsedUrl.hostname);

  if (!isHttpsUrl && !isAllowedLocalHttp) {
    throw new Error(
      'EXPO_PUBLIC_API_BASE_URL harus menggunakan HTTPS untuk host non-local.'
    );
  }

  return baseUrl;
}

const apiBaseUrl = resolveApiBaseUrl();

export const env = {
  apiBaseUrl,
};

export const isUsingFallbackApiBaseUrl = env.apiBaseUrl === fallbackApiBaseUrl;
