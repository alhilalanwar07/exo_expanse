import { HttpClientError, type HttpErrorCode } from './httpClient';

export type ApiFieldErrors = Record<string, string[]>;

export interface BackendErrorPayload {
  message?: string;
  error?: string;
  errors?: ApiFieldErrors;
  [key: string]: unknown;
}

export type ApiEndpointErrorCode = HttpErrorCode | 'UNKNOWN';

export class ApiEndpointError extends Error {
  readonly status: number;
  readonly code: ApiEndpointErrorCode;
  readonly fieldErrors?: ApiFieldErrors;
  readonly payload: BackendErrorPayload | null;

  constructor(params: {
    message: string;
    status: number;
    code: ApiEndpointErrorCode;
    fieldErrors?: ApiFieldErrors;
    payload?: BackendErrorPayload | null;
    cause?: unknown;
  }) {
    super(params.message);
    this.name = 'ApiEndpointError';
    this.status = params.status;
    this.code = params.code;
    this.fieldErrors = params.fieldErrors;
    this.payload = params.payload ?? null;
    this.cause = params.cause;
  }
}

export function isApiEndpointError(error: unknown): error is ApiEndpointError {
  return error instanceof ApiEndpointError;
}

function isObjectRecord(value: unknown): value is Record<string, unknown> {
  return typeof value === 'object' && value !== null;
}

function toFieldErrors(rawErrors: unknown): ApiFieldErrors | undefined {
  if (!isObjectRecord(rawErrors)) {
    return undefined;
  }

  const mapped: ApiFieldErrors = {};

  Object.entries(rawErrors).forEach(([key, value]) => {
    if (Array.isArray(value)) {
      mapped[key] = value.filter((entry): entry is string => typeof entry === 'string');
    }
  });

  return Object.keys(mapped).length > 0 ? mapped : undefined;
}

export function parseBackendErrorPayload(details: unknown): BackendErrorPayload | null {
  if (isObjectRecord(details)) {
    return {
      ...details,
      message: typeof details.message === 'string' ? details.message : undefined,
      error: typeof details.error === 'string' ? details.error : undefined,
      errors: toFieldErrors(details.errors),
    };
  }

  if (typeof details !== 'string' || !details.trim()) {
    return null;
  }

  try {
    const parsed = JSON.parse(details) as unknown;
    return parseBackendErrorPayload(parsed);
  } catch {
    return {
      message: details,
    };
  }
}

export function extractFirstBackendFieldError(fieldErrors: ApiFieldErrors | undefined): string | null {
  if (!fieldErrors) {
    return null;
  }

  const firstFieldErrors = Object.values(fieldErrors)[0];

  if (!Array.isArray(firstFieldErrors) || firstFieldErrors.length === 0) {
    return null;
  }

  return firstFieldErrors[0] ?? null;
}

export function resolveBackendErrorMessage(payload: BackendErrorPayload | null): string | null {
  if (!payload) {
    return null;
  }

  const firstFieldError = extractFirstBackendFieldError(payload.errors);

  if (firstFieldError) {
    return firstFieldError;
  }

  if (payload.message?.trim()) {
    return payload.message;
  }

  if (payload.error?.trim()) {
    return payload.error;
  }

  return null;
}

type EndpointErrorContext = {
  payload: BackendErrorPayload | null;
  backendMessage: string | null;
  fieldErrors?: ApiFieldErrors;
};

type ToApiEndpointErrorOptions = {
  defaultMessage: string;
  networkMessage?: string;
  timeoutMessage?: string;
  abortedMessage?: string;
  serverErrorMessage?: string;
  mapHttpError?: (error: HttpClientError, context: EndpointErrorContext) => ApiEndpointError | null;
};

export function toApiEndpointError(
  error: unknown,
  options: ToApiEndpointErrorOptions
): ApiEndpointError {
  if (isApiEndpointError(error)) {
    return error;
  }

  if (error instanceof HttpClientError) {
    const payload = parseBackendErrorPayload(error.details);
    const backendMessage = resolveBackendErrorMessage(payload);
    const fieldErrors = payload?.errors;

    const mappedHttpError = options.mapHttpError?.(error, {
      payload,
      backendMessage,
      fieldErrors,
    });

    if (mappedHttpError) {
      return mappedHttpError;
    }

    if (error.code === 'NETWORK_ERROR') {
      return new ApiEndpointError({
        message: options.networkMessage ?? 'Gagal terhubung ke server. Periksa koneksi internet Anda.',
        status: error.status,
        code: error.code,
        fieldErrors,
        payload,
        cause: error,
      });
    }

    if (error.code === 'TIMEOUT') {
      return new ApiEndpointError({
        message: options.timeoutMessage ?? 'Waktu koneksi habis. Silakan coba lagi.',
        status: error.status,
        code: error.code,
        fieldErrors,
        payload,
        cause: error,
      });
    }

    if (error.code === 'ABORTED') {
      return new ApiEndpointError({
        message: options.abortedMessage ?? 'Permintaan dibatalkan.',
        status: error.status,
        code: error.code,
        fieldErrors,
        payload,
        cause: error,
      });
    }

    if (error.status >= 500 && options.serverErrorMessage) {
      return new ApiEndpointError({
        message: options.serverErrorMessage,
        status: error.status,
        code: error.code,
        fieldErrors,
        payload,
        cause: error,
      });
    }

    return new ApiEndpointError({
      message: backendMessage ?? error.message ?? options.defaultMessage,
      status: error.status,
      code: error.code,
      fieldErrors,
      payload,
      cause: error,
    });
  }

  if (error instanceof Error) {
    return new ApiEndpointError({
      message: error.message || options.defaultMessage,
      status: 0,
      code: 'UNKNOWN',
      payload: null,
      cause: error,
    });
  }

  return new ApiEndpointError({
    message: options.defaultMessage,
    status: 0,
    code: 'UNKNOWN',
    payload: null,
    cause: error,
  });
}

export function isSlowNetworkApiError(error: unknown): boolean {
  if (error instanceof HttpClientError) {
    return error.code === 'TIMEOUT' || error.code === 'NETWORK_ERROR';
  }

  if (isApiEndpointError(error)) {
    return error.code === 'TIMEOUT' || error.code === 'NETWORK_ERROR';
  }

  return false;
}
