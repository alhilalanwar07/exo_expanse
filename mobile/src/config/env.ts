const fallbackApiBaseUrl = 'https://exoinvite.site';

const rawApiBaseUrl = process.env.EXPO_PUBLIC_API_BASE_URL;
const rawDebugHttpLogging = process.env.EXPO_PUBLIC_DEBUG_HTTP_LOGGING;
const ENV_VAR_NAME = 'EXPO_PUBLIC_API_BASE_URL';

type ApiBaseUrlSource = 'environment' | 'fallback';

function normalizeBaseUrl(url: string): string {
  return url.trim().replace(/\/+$/, '');
}

function buildInvalidApiBaseUrlError(params: {
  reason: string;
  value: string;
  source: ApiBaseUrlSource;
}): Error {
  const sourceLabel =
    params.source === 'environment'
      ? `process.env.${ENV_VAR_NAME}`
      : 'fallbackApiBaseUrl (src/config/env.ts)';

  return new Error(
    [
      `[env] ${ENV_VAR_NAME} tidak valid.`,
      `Alasan: ${params.reason}`,
      `Nilai saat ini: "${params.value}" (sumber: ${sourceLabel})`,
      'Perbaikan cepat:',
      `1. Isi ${ENV_VAR_NAME} dengan URL absolut.`,
      '2. Gunakan HTTPS untuk host non-local.',
      '3. HTTP hanya diizinkan untuk localhost, 127.0.0.1, 10.0.2.2, atau *.local.',
      `4. Restart Expo dev server setelah mengubah .env (contoh: npx expo start --clear).`,
      'Contoh nilai valid:',
      '- https://api.exoinvite.site',
      '- http://10.0.2.2:8000',
      '- http://localhost:8000',
    ].join('\n')
  );
}

function isLocalDevelopmentHost(hostname: string): boolean {
  return (
    hostname === 'localhost' ||
    hostname === '127.0.0.1' ||
    hostname === '10.0.2.2' ||
    hostname.endsWith('.local')
  );
}

function readBooleanEnvValue(rawValue: string | undefined): boolean {
  if (!rawValue) {
    return false;
  }

  switch (rawValue.trim().toLowerCase()) {
    case '1':
    case 'true':
    case 'yes':
    case 'on':
      return true;
    default:
      return false;
  }
}

function resolveApiBaseUrl(): string {
  const hasRawApiBaseUrl = Boolean(rawApiBaseUrl && rawApiBaseUrl.trim().length > 0);
  const source: ApiBaseUrlSource = hasRawApiBaseUrl ? 'environment' : 'fallback';
  const candidate = hasRawApiBaseUrl ? rawApiBaseUrl! : fallbackApiBaseUrl;

  const baseUrl = normalizeBaseUrl(candidate);

  if (!baseUrl) {
    throw buildInvalidApiBaseUrlError({
      reason: 'URL kosong.',
      value: candidate,
      source,
    });
  }

  let parsedUrl: URL;

  try {
    parsedUrl = new URL(baseUrl);
  } catch {
    throw buildInvalidApiBaseUrlError({
      reason: 'Harus berupa URL absolut dengan protocol (http/https).',
      value: baseUrl,
      source,
    });
  }

  const isHttpOrHttpsProtocol = parsedUrl.protocol === 'http:' || parsedUrl.protocol === 'https:';

  if (!isHttpOrHttpsProtocol) {
    throw buildInvalidApiBaseUrlError({
      reason: `Protocol ${parsedUrl.protocol} tidak didukung.`,
      value: baseUrl,
      source,
    });
  }

  const isHttpsUrl = parsedUrl.protocol === 'https:';
  const isAllowedLocalHttp =
    parsedUrl.protocol === 'http:' && isLocalDevelopmentHost(parsedUrl.hostname);

  if (!isHttpsUrl && !isAllowedLocalHttp) {
    throw buildInvalidApiBaseUrlError({
      reason: 'Host non-local wajib menggunakan HTTPS.',
      value: baseUrl,
      source,
    });
  }

  if (parsedUrl.search || parsedUrl.hash) {
    throw buildInvalidApiBaseUrlError({
      reason: 'URL tidak boleh mengandung query string (?) atau hash (#).',
      value: baseUrl,
      source,
    });
  }

  return baseUrl;
}

const apiBaseUrl = resolveApiBaseUrl();
const debugHttpLogging = readBooleanEnvValue(rawDebugHttpLogging);

export const env = {
  apiBaseUrl,
  debugHttpLogging,
};

export const isUsingFallbackApiBaseUrl = env.apiBaseUrl === fallbackApiBaseUrl;
