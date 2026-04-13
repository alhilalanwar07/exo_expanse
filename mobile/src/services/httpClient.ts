import { env } from '../config/env';

export type HttpMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';

export type HttpErrorCode =
  | 'HTTP_ERROR'
  | 'NETWORK_ERROR'
  | 'TIMEOUT'
  | 'ABORTED'
  | 'INVALID_RESPONSE';

export interface HttpRequestOptions extends Omit<RequestInit, 'method' | 'body'> {
  method?: HttpMethod;
  body?: FormData | object;
  timeoutMs?: number;
  retry?: number | false;
  retryDelayMs?: number;
  authMode?: 'none' | 'required';
  autoRefreshAuth?: boolean;
}

export interface HttpClientAuthSession {
  accessToken: string;
  refreshToken: string;
  expiresAt?: string;
}

export interface HttpClientAuthProvider {
  getSession: () => Promise<HttpClientAuthSession | null>;
  refreshSession: (
    session: HttpClientAuthSession,
    reason: 'expired' | 'unauthorized'
  ) => Promise<HttpClientAuthSession | null>;
  clearSession?: () => Promise<void> | void;
}

export class HttpClientError extends Error {
  readonly status: number;
  readonly code: HttpErrorCode;
  readonly details?: unknown;
  readonly retryable: boolean;

  constructor(params: {
    message: string;
    status: number;
    code: HttpErrorCode;
    details?: unknown;
    retryable?: boolean;
  }) {
    super(params.message);
    this.name = 'HttpClientError';
    this.status = params.status;
    this.code = params.code;
    this.details = params.details;
    this.retryable = params.retryable ?? false;
  }
}

const DEFAULT_TIMEOUT_MS = 15000;
const DEFAULT_RETRY_DELAY_MS = 250;
const DEFAULT_IDEMPOTENT_RETRY_COUNT = 1;
const ACCESS_TOKEN_EXPIRY_LEEWAY_MS = 5000;
const HTTP_DEBUG_LOG_PREFIX = '[http]';
const REDACTED_LOG_VALUE = '[REDACTED]';
const HTTP_DEBUG_MAX_DEPTH = 4;
const HTTP_DEBUG_MAX_ARRAY_ITEMS = 15;
const HTTP_DEBUG_MAX_STRING_LENGTH = 250;

const IDEMPOTENT_METHODS = new Set<HttpMethod>(['GET', 'PUT', 'DELETE']);
const RETRYABLE_STATUS_CODES = new Set([408, 425, 429, 500, 502, 503, 504]);
const SENSITIVE_LOG_KEY_PATTERN =
  /(authorization|token|password|secret|cookie|credential|api[_-]?key|session|access[_-]?code)/i;
const HTTP_DEBUG_LOG_ENABLED = __DEV__ && env.debugHttpLogging;

let httpClientAuthProvider: HttpClientAuthProvider | null = null;
let authRefreshPromise: Promise<HttpClientAuthSession | null> | null = null;

export function configureHttpClientAuth(provider: HttpClientAuthProvider | null): void {
  httpClientAuthProvider = provider;
}

function normalizeHeaders(headers?: HeadersInit): Record<string, string> {
  const requestHeaders: Record<string, string> = {
    Accept: 'application/json',
  };

  if (!headers) {
    return requestHeaders;
  }

  if (headers instanceof Headers) {
    headers.forEach((value, key) => {
      requestHeaders[key] = value;
    });

    return requestHeaders;
  }

  if (Array.isArray(headers)) {
    for (const [key, value] of headers) {
      requestHeaders[key] = value;
    }

    return requestHeaders;
  }

  Object.assign(requestHeaders, headers);
  return requestHeaders;
}

function hasHeader(headers: Record<string, string>, headerName: string): boolean {
  const target = headerName.toLowerCase();
  return Object.keys(headers).some((key) => key.toLowerCase() === target);
}

function getRequestBody(body: FormData | object | undefined): string | FormData | undefined {
  if (!body) {
    return undefined;
  }

  if (body instanceof FormData) {
    return body;
  }

  return JSON.stringify(body);
}

function toHttpMethod(method: HttpMethod | undefined): HttpMethod {
  return method ?? 'GET';
}

function getRetryCount(method: HttpMethod, retry: number | false | undefined): number {
  if (retry === false) {
    return 0;
  }

  if (typeof retry === 'number') {
    return Math.max(0, Math.floor(retry));
  }

  return IDEMPOTENT_METHODS.has(method) ? DEFAULT_IDEMPOTENT_RETRY_COUNT : 0;
}

function getRetryDelayMs(attemptIndex: number, baseDelayMs: number): number {
  const multiplier = 2 ** attemptIndex;
  return baseDelayMs * multiplier;
}

function isHttpClientErrorRetryable(error: HttpClientError): boolean {
  if (error.retryable) {
    return true;
  }

  return error.status > 0 && RETRYABLE_STATUS_CODES.has(error.status);
}

function buildHttpStatusMessage(status: number, statusText: string): string {
  return `HTTP ${status}${statusText ? ` ${statusText}` : ''}`;
}

function shouldRedactLogKey(key: string | undefined): boolean {
  if (!key) {
    return false;
  }

  return SENSITIVE_LOG_KEY_PATTERN.test(key);
}

function redactSensitiveStrings(value: string): string {
  const maskedBearer = value.replace(/Bearer\s+[^\s]+/gi, 'Bearer [REDACTED]');
  const maskedJwt = maskedBearer.replace(
    /\beyJ[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\b/g,
    '[REDACTED]'
  );

  if (maskedJwt.length <= HTTP_DEBUG_MAX_STRING_LENGTH) {
    return maskedJwt;
  }

  return `${maskedJwt.slice(0, HTTP_DEBUG_MAX_STRING_LENGTH)}...<truncated>`;
}

function sanitizeUrlForLog(rawUrl: string): string {
  try {
    const parsed = new URL(rawUrl);

    parsed.searchParams.forEach((value, key) => {
      if (shouldRedactLogKey(key) || shouldRedactLogKey(value)) {
        parsed.searchParams.set(key, REDACTED_LOG_VALUE);
      }
    });

    return parsed.toString();
  } catch {
    return redactSensitiveStrings(rawUrl);
  }
}

function headersToRecord(headers: Headers): Record<string, string> {
  const record: Record<string, string> = {};

  headers.forEach((value, key) => {
    record[key] = value;
  });

  return record;
}

function sanitizeForDebugLog(
  value: unknown,
  keyHint?: string,
  depth = 0,
  seen = new WeakSet<object>()
): unknown {
  if (shouldRedactLogKey(keyHint)) {
    return REDACTED_LOG_VALUE;
  }

  if (value === null || value === undefined) {
    return value;
  }

  if (typeof value === 'string') {
    if (keyHint && keyHint.toLowerCase().includes('url')) {
      return sanitizeUrlForLog(value);
    }

    return redactSensitiveStrings(value);
  }

  if (typeof value === 'number' || typeof value === 'boolean') {
    return value;
  }

  if (value instanceof Error) {
    const errorObject: Record<string, unknown> = {
      name: value.name,
      message: redactSensitiveStrings(value.message),
    };

    if (value instanceof HttpClientError) {
      errorObject.status = value.status;
      errorObject.code = value.code;
      errorObject.retryable = value.retryable;
      errorObject.details = sanitizeForDebugLog(value.details, 'details', depth + 1, seen);
    }

    return errorObject;
  }

  if (typeof FormData !== 'undefined' && value instanceof FormData) {
    return '[FormData]';
  }

  if (value instanceof URL) {
    return sanitizeUrlForLog(value.toString());
  }

  if (Array.isArray(value)) {
    if (depth >= HTTP_DEBUG_MAX_DEPTH) {
      return `[Array(${value.length})]`;
    }

    return value
      .slice(0, HTTP_DEBUG_MAX_ARRAY_ITEMS)
      .map((item) => sanitizeForDebugLog(item, undefined, depth + 1, seen));
  }

  if (typeof value === 'object') {
    if (depth >= HTTP_DEBUG_MAX_DEPTH) {
      return '[Object]';
    }

    if (seen.has(value)) {
      return '[Circular]';
    }

    seen.add(value);

    const objectValue = value as Record<string, unknown>;
    const sanitizedObject: Record<string, unknown> = {};

    for (const [key, nestedValue] of Object.entries(objectValue)) {
      sanitizedObject[key] = sanitizeForDebugLog(nestedValue, key, depth + 1, seen);
    }

    return sanitizedObject;
  }

  return String(value);
}

function buildDebugBodyForLog(body: FormData | object | undefined): unknown {
  if (!body) {
    return null;
  }

  if (typeof FormData !== 'undefined' && body instanceof FormData) {
    return '[FormData]';
  }

  return body;
}

function logHttpDebug(event: string, payload: Record<string, unknown>): void {
  if (!HTTP_DEBUG_LOG_ENABLED) {
    return;
  }

  console.log(`${HTTP_DEBUG_LOG_PREFIX} ${event}`, sanitizeForDebugLog(payload));
}

function isRefreshEndpointPath(path: string): boolean {
  return path.includes('/api/mobile/access/refresh');
}

function isTokenExpired(expiresAt: string | undefined): boolean {
  if (!expiresAt) {
    return false;
  }

  const expiresAtTimestamp = Date.parse(expiresAt);

  if (!Number.isFinite(expiresAtTimestamp)) {
    return false;
  }

  return expiresAtTimestamp <= Date.now() + ACCESS_TOKEN_EXPIRY_LEEWAY_MS;
}

function isValidAuthSession(session: HttpClientAuthSession | null): session is HttpClientAuthSession {
  if (!session) {
    return false;
  }

  return Boolean(session.accessToken && session.refreshToken);
}

async function getConfiguredAuthSession(): Promise<HttpClientAuthSession | null> {
  if (!httpClientAuthProvider) {
    return null;
  }

  try {
    const session = await httpClientAuthProvider.getSession();
    return isValidAuthSession(session) ? session : null;
  } catch {
    return null;
  }
}

async function clearConfiguredAuthSession(): Promise<void> {
  if (!httpClientAuthProvider?.clearSession) {
    return;
  }

  try {
    await httpClientAuthProvider.clearSession();
  } catch {
    // Best-effort cleanup.
  }
}

function toUnauthorizedError(): HttpClientError {
  return new HttpClientError({
    message: 'Unauthorized. Silakan login ulang.',
    status: 401,
    code: 'HTTP_ERROR',
    retryable: false,
  });
}

async function refreshAuthSession(
  session: HttpClientAuthSession,
  reason: 'expired' | 'unauthorized'
): Promise<HttpClientAuthSession | null> {
  if (!httpClientAuthProvider) {
    return null;
  }

  if (authRefreshPromise) {
    return authRefreshPromise;
  }

  authRefreshPromise = (async () => {
    try {
      const refreshedSession = await httpClientAuthProvider.refreshSession(session, reason);

      if (!isValidAuthSession(refreshedSession)) {
        return null;
      }

      return refreshedSession;
    } finally {
      authRefreshPromise = null;
    }
  })();

  return authRefreshPromise;
}

function getFirstValidationError(errors: unknown): string | null {
  if (!errors || typeof errors !== 'object') {
    return null;
  }

  const firstFieldErrors = Object.values(errors as Record<string, unknown>)[0];

  if (Array.isArray(firstFieldErrors) && firstFieldErrors.length > 0) {
    const firstError = firstFieldErrors[0];
    return typeof firstError === 'string' ? firstError : null;
  }

  return null;
}

function extractErrorMessageFromPayload(payload: unknown): string | null {
  if (!payload || typeof payload !== 'object') {
    return null;
  }

  const payloadObject = payload as {
    message?: unknown;
    error?: unknown;
    errors?: unknown;
  };

  if (typeof payloadObject.message === 'string' && payloadObject.message.trim()) {
    return payloadObject.message;
  }

  if (typeof payloadObject.error === 'string' && payloadObject.error.trim()) {
    return payloadObject.error;
  }

  return getFirstValidationError(payloadObject.errors);
}

async function mapHttpError(response: Response): Promise<HttpClientError> {
  const contentType = (response.headers.get('content-type') ?? '').toLowerCase();
  const fallback = buildHttpStatusMessage(response.status, response.statusText);

  if (contentType.includes('application/json')) {
    const payload = await response.json().catch(() => null);
    const payloadMessage = extractErrorMessageFromPayload(payload);

    return new HttpClientError({
      message: payloadMessage ?? fallback,
      status: response.status,
      code: 'HTTP_ERROR',
      details: payload,
      retryable: RETRYABLE_STATUS_CODES.has(response.status),
    });
  }

  const textBody = await response.text().catch(() => '');

  return new HttpClientError({
    message: textBody?.trim() ? textBody : fallback,
    status: response.status,
    code: 'HTTP_ERROR',
    details: textBody,
    retryable: RETRYABLE_STATUS_CODES.has(response.status),
  });
}

function mapUnknownError(error: unknown, didTimeout: boolean): HttpClientError {
  if (error instanceof HttpClientError) {
    return error;
  }

  if (didTimeout) {
    return new HttpClientError({
      message: 'Request timeout. Coba lagi.',
      status: 0,
      code: 'TIMEOUT',
      retryable: true,
    });
  }

  if (error instanceof Error && error.name === 'AbortError') {
    return new HttpClientError({
      message: 'Request dibatalkan.',
      status: 0,
      code: 'ABORTED',
      details: error,
      retryable: false,
    });
  }

  if (error instanceof TypeError) {
    return new HttpClientError({
      message: 'Gagal terhubung ke server. Periksa koneksi internet Anda.',
      status: 0,
      code: 'NETWORK_ERROR',
      details: error,
      retryable: true,
    });
  }

  return new HttpClientError({
    message: error instanceof Error ? error.message : 'Terjadi kesalahan jaringan yang tidak diketahui.',
    status: 0,
    code: 'NETWORK_ERROR',
    details: error,
    retryable: true,
  });
}

function parseResponseAsText(response: Response): Promise<string> {
  return response.text();
}

async function parseSuccessfulResponse<T>(response: Response): Promise<T> {
  if (response.status === 204) {
    return undefined as T;
  }

  const contentType = (response.headers.get('content-type') ?? '').toLowerCase();

  if (contentType.includes('application/json')) {
    try {
      return (await response.json()) as T;
    } catch (error) {
      throw new HttpClientError({
        message: 'Format respons server tidak valid.',
        status: response.status,
        code: 'INVALID_RESPONSE',
        details: error,
        retryable: false,
      });
    }
  }

  return (await parseResponseAsText(response)) as T;
}

function delay(ms: number): Promise<void> {
  return new Promise((resolve) => {
    setTimeout(resolve, ms);
  });
}

export async function httpRequest<T>(
  path: string,
  options: HttpRequestOptions = {}
): Promise<T> {
  const {
    method: methodOption,
    body,
    headers,
    timeoutMs = DEFAULT_TIMEOUT_MS,
    retry,
    retryDelayMs = DEFAULT_RETRY_DELAY_MS,
    authMode = 'none',
    autoRefreshAuth = true,
    signal,
    ...rest
  } = options;

  const method = toHttpMethod(methodOption);
  const retryCount = getRetryCount(method, retry);
  const baseHeaders = normalizeHeaders(headers);
  const requestBody = getRequestBody(body);
  const shouldUseAuth = authMode === 'required' && !isRefreshEndpointPath(path);
  const shouldAutoRefreshAuth = shouldUseAuth && autoRefreshAuth;
  let authSession = shouldUseAuth ? await getConfiguredAuthSession() : null;
  let didRetryAfterUnauthorized = false;
  const requestId = `${method.toLowerCase()}-${Math.random().toString(36).slice(2, 10)}`;
  const requestUrl = `${env.apiBaseUrl}${path}`;

  if (body && !(body instanceof FormData) && !hasHeader(baseHeaders, 'Content-Type')) {
    baseHeaders['Content-Type'] = 'application/json';
  }

  if (shouldUseAuth && !isValidAuthSession(authSession)) {
    await clearConfiguredAuthSession();
    throw toUnauthorizedError();
  }

  let attempt = 0;

  while (true) {
    const attemptNumber = attempt + 1;

    if (shouldUseAuth) {
      if (!isValidAuthSession(authSession)) {
        logHttpDebug('auth.session_missing', {
          requestId,
          method,
          url: requestUrl,
          attempt: attemptNumber,
        });
        await clearConfiguredAuthSession();
        throw toUnauthorizedError();
      }

      if (shouldAutoRefreshAuth && isTokenExpired(authSession.expiresAt)) {
        let refreshedSession: HttpClientAuthSession | null = null;

        logHttpDebug('auth.refresh_start', {
          requestId,
          method,
          url: requestUrl,
          attempt: attemptNumber,
          reason: 'expired',
        });

        try {
          refreshedSession = await refreshAuthSession(authSession, 'expired');
        } catch (refreshError) {
          logHttpDebug('auth.refresh_error', {
            requestId,
            method,
            url: requestUrl,
            attempt: attemptNumber,
            reason: 'expired',
            error: refreshError,
          });
          throw mapUnknownError(refreshError, false);
        }

        if (!isValidAuthSession(refreshedSession)) {
          logHttpDebug('auth.refresh_invalid', {
            requestId,
            method,
            url: requestUrl,
            attempt: attemptNumber,
            reason: 'expired',
          });
          await clearConfiguredAuthSession();
          throw toUnauthorizedError();
        }

        logHttpDebug('auth.refresh_success', {
          requestId,
          method,
          url: requestUrl,
          attempt: attemptNumber,
          reason: 'expired',
        });

        authSession = refreshedSession;
      }
    }

    const timeoutController = new AbortController();
    let didTimeout = false;

    const externalSignal = signal;
    const abortFromExternalSignal = () => {
      timeoutController.abort();
    };

    if (externalSignal) {
      if (externalSignal.aborted) {
        abortFromExternalSignal();
      } else {
        externalSignal.addEventListener('abort', abortFromExternalSignal);
      }
    }

    const timeoutId = setTimeout(() => {
      didTimeout = true;
      timeoutController.abort();
    }, timeoutMs);

    const startedAt = Date.now();

    try {
      const requestHeaders = {
        ...baseHeaders,
      };

      if (shouldUseAuth && authSession?.accessToken) {
        requestHeaders.Authorization = `Bearer ${authSession.accessToken}`;
      }

      logHttpDebug('request_start', {
        requestId,
        method,
        url: requestUrl,
        attempt: attemptNumber,
        timeoutMs,
        retryCount,
        authMode,
        autoRefreshAuth,
        headers: requestHeaders,
        body: buildDebugBodyForLog(body),
      });

      const response = await fetch(requestUrl, {
        ...rest,
        method,
        headers: requestHeaders,
        body: requestBody,
        signal: timeoutController.signal,
      });

      logHttpDebug('response_received', {
        requestId,
        method,
        url: requestUrl,
        attempt: attemptNumber,
        status: response.status,
        ok: response.ok,
        durationMs: Date.now() - startedAt,
        headers: headersToRecord(response.headers),
      });

      if (!response.ok) {
        if (shouldUseAuth && response.status === 401) {
          if (shouldAutoRefreshAuth && !didRetryAfterUnauthorized && isValidAuthSession(authSession)) {
            logHttpDebug('auth.refresh_start', {
              requestId,
              method,
              url: requestUrl,
              attempt: attemptNumber,
              reason: 'unauthorized',
            });

            let refreshedSession: HttpClientAuthSession | null = null;

            try {
              refreshedSession = await refreshAuthSession(authSession, 'unauthorized');
            } catch (refreshError) {
              logHttpDebug('auth.refresh_error', {
                requestId,
                method,
                url: requestUrl,
                attempt: attemptNumber,
                reason: 'unauthorized',
                error: refreshError,
              });

              throw mapUnknownError(refreshError, false);
            }

            if (isValidAuthSession(refreshedSession)) {
              logHttpDebug('auth.refresh_success', {
                requestId,
                method,
                url: requestUrl,
                attempt: attemptNumber,
                reason: 'unauthorized',
              });

              authSession = refreshedSession;
              didRetryAfterUnauthorized = true;
              continue;
            }

            logHttpDebug('auth.refresh_invalid', {
              requestId,
              method,
              url: requestUrl,
              attempt: attemptNumber,
              reason: 'unauthorized',
            });
          }

          await clearConfiguredAuthSession();
          throw toUnauthorizedError();
        }

        throw await mapHttpError(response);
      }

      const parsedResponse = await parseSuccessfulResponse<T>(response);

      logHttpDebug('response_parsed', {
        requestId,
        method,
        url: requestUrl,
        attempt: attemptNumber,
        status: response.status,
        durationMs: Date.now() - startedAt,
        body: parsedResponse,
      });

      return parsedResponse;
    } catch (error) {
      const mappedError = mapUnknownError(error, didTimeout);
      const shouldRetry =
        attempt < retryCount && IDEMPOTENT_METHODS.has(method) && isHttpClientErrorRetryable(mappedError);

      logHttpDebug('request_error', {
        requestId,
        method,
        url: requestUrl,
        attempt: attemptNumber,
        durationMs: Date.now() - startedAt,
        error: mappedError,
        willRetry: shouldRetry,
      });

      if (shouldRetry) {
        const retryDelay = getRetryDelayMs(attempt, retryDelayMs);

        logHttpDebug('request_retry', {
          requestId,
          method,
          url: requestUrl,
          currentAttempt: attemptNumber,
          nextAttempt: attemptNumber + 1,
          retryDelayMs: retryDelay,
          reason: mappedError.code,
        });

        attempt += 1;
        await delay(retryDelay);
        continue;
      }

      throw mappedError;
    } finally {
      clearTimeout(timeoutId);

      if (externalSignal) {
        externalSignal.removeEventListener('abort', abortFromExternalSignal);
      }
    }
  }
}
