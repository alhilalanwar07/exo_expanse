import { httpRequest } from '../../services/httpClient';
import {
  ApiEndpointError,
  type ApiEndpointErrorCode,
  isSlowNetworkApiError,
  toApiEndpointError,
} from '../../services/apiResponse';
import type {
  AttendanceStatus,
  InvitationStatsResponse,
  InvitationWish,
  InvitationWishesResponse,
  SubmitRsvpPayload,
  SubmitWishPayload,
} from './invitation.types';

type InvitationReadRequestOptions = {
  signal?: AbortSignal;
  timeoutMs?: number;
  retry?: number | false;
  allowSlowNetworkFallback?: boolean;
};

type InvitationWishesRequestOptions = InvitationReadRequestOptions & {
  limit?: number;
  offset?: number;
};

type InvitationMutationResponse = {
  success: boolean;
  message: string;
};

type InvitationOperation = 'stats' | 'wishes' | 'submitRsvp' | 'submitWish';

export type InvitationApiErrorCode = ApiEndpointErrorCode;

export class InvitationApiError extends ApiEndpointError {
  constructor(params: {
    message: string;
    status: number;
    code: InvitationApiErrorCode;
    fieldErrors?: Record<string, string[]>;
    payload?: Record<string, unknown> | null;
    cause?: unknown;
  }) {
    super({
      message: params.message,
      status: params.status,
      code: params.code,
      fieldErrors: params.fieldErrors,
      payload: params.payload,
      cause: params.cause,
    });

    this.name = 'InvitationApiError';
  }
}

export function isInvitationApiError(error: unknown): error is InvitationApiError {
  return error instanceof InvitationApiError;
}

const DEFAULT_ERROR_MESSAGES: Record<InvitationOperation, string> = {
  stats: 'Gagal memuat statistik undangan.',
  wishes: 'Gagal memuat daftar ucapan.',
  submitRsvp: 'Gagal mengirim konfirmasi kehadiran.',
  submitWish: 'Gagal mengirim ucapan.',
};

const NETWORK_ERROR_MESSAGES: Record<InvitationOperation, string> = {
  stats: 'Tidak dapat memuat statistik. Periksa koneksi internet Anda.',
  wishes: 'Tidak dapat memuat ucapan. Periksa koneksi internet Anda.',
  submitRsvp: 'Tidak dapat mengirim konfirmasi karena koneksi bermasalah.',
  submitWish: 'Tidak dapat mengirim ucapan karena koneksi bermasalah.',
};

const TIMEOUT_ERROR_MESSAGES: Record<InvitationOperation, string> = {
  stats: 'Waktu koneksi habis saat memuat statistik undangan.',
  wishes: 'Waktu koneksi habis saat memuat ucapan undangan.',
  submitRsvp: 'Waktu koneksi habis saat mengirim konfirmasi kehadiran.',
  submitWish: 'Waktu koneksi habis saat mengirim ucapan.',
};

function mapInvitationApiError(error: unknown, operation: InvitationOperation): InvitationApiError {
  if (isInvitationApiError(error)) {
    return error;
  }

  const baseError = toApiEndpointError(error, {
    defaultMessage: DEFAULT_ERROR_MESSAGES[operation],
    networkMessage: NETWORK_ERROR_MESSAGES[operation],
    timeoutMessage: TIMEOUT_ERROR_MESSAGES[operation],
    abortedMessage: 'Permintaan dibatalkan.',
    serverErrorMessage: 'Server sedang bermasalah. Silakan coba beberapa saat lagi.',
  });

  return new InvitationApiError({
    message: baseError.message,
    status: baseError.status,
    code: baseError.code,
    fieldErrors: baseError.fieldErrors,
    payload: baseError.payload,
    cause: baseError.cause,
  });
}

const DEFAULT_STATS_RESPONSE: InvitationStatsResponse = {
  total_wishes: 0,
  total_confirmed: 0,
  total_guests: 0,
};

const DEFAULT_WISHES_RESPONSE: InvitationWishesResponse = {
  wishes: [],
  total: 0,
};

const invitationStatsCache = new Map<string, InvitationStatsResponse>();
const invitationWishesCache = new Map<string, InvitationWishesResponse>();

function toInvitationIdentifier(input: string): string {
  const normalized = input.trim();

  if (!normalized) {
    throw new Error('invitationIdentifier wajib diisi.');
  }

  return normalized;
}

function toInvitationBasePath(invitationIdentifier: string) {
  return `/api/invitations/${encodeURIComponent(toInvitationIdentifier(invitationIdentifier))}`;
}

function toQueryString(params: Record<string, string | number | undefined>) {
  const searchParams = new URLSearchParams();

  Object.entries(params).forEach(([key, value]) => {
    if (value === undefined) {
      return;
    }

    searchParams.set(key, String(value));
  });

  const serialized = searchParams.toString();

  return serialized ? `?${serialized}` : '';
}

function toIntegerField(value: unknown, fieldName: string): number {
  if (typeof value === 'number' && Number.isFinite(value)) {
    return Math.max(0, Math.trunc(value));
  }

  if (typeof value === 'string' && value.trim()) {
    const parsed = Number(value);

    if (Number.isFinite(parsed)) {
      return Math.max(0, Math.trunc(parsed));
    }
  }

  throw new Error(`Field "${fieldName}" tidak valid.`);
}

function isObjectRecord(value: unknown): value is Record<string, unknown> {
  return Boolean(value) && typeof value === 'object';
}

function toAttendanceStatus(value: unknown): AttendanceStatus | null {
  if (value === 'confirmed' || value === 'declined') {
    return value;
  }

  return null;
}

function toInvitationWishEntry(rawWish: unknown): InvitationWish | null {
  if (!isObjectRecord(rawWish)) {
    return null;
  }

  const id = toIntegerField(rawWish.id, 'wish.id');
  const name = typeof rawWish.name === 'string' ? rawWish.name.trim() : '';
  const message = typeof rawWish.message === 'string' ? rawWish.message.trim() : '';

  if (!name || !message) {
    return null;
  }

  const initial =
    typeof rawWish.initial === 'string' && rawWish.initial.trim()
      ? rawWish.initial.trim().charAt(0).toUpperCase()
      : name.charAt(0).toUpperCase();

  const time = typeof rawWish.time === 'string' && rawWish.time.trim() ? rawWish.time.trim() : '-';

  return {
    id,
    name,
    message,
    initial,
    time,
    attendance_status: toAttendanceStatus(rawWish.attendance_status),
  };
}

function parseInvitationStatsResponse(raw: unknown): InvitationStatsResponse {
  if (!isObjectRecord(raw)) {
    throw new Error('Format response stats undangan tidak valid.');
  }

  return {
    total_wishes: toIntegerField(raw.total_wishes, 'total_wishes'),
    total_confirmed: toIntegerField(raw.total_confirmed, 'total_confirmed'),
    total_guests: toIntegerField(raw.total_guests, 'total_guests'),
  };
}

function parseInvitationWishesResponse(raw: unknown): InvitationWishesResponse {
  if (!isObjectRecord(raw)) {
    throw new Error('Format response wishes undangan tidak valid.');
  }

  const rawWishes = Array.isArray(raw.wishes) ? raw.wishes : [];
  const wishes = rawWishes
    .map((wish) => {
      try {
        return toInvitationWishEntry(wish);
      } catch {
        return null;
      }
    })
    .filter((wish): wish is InvitationWish => wish !== null);

  const total = raw.total === undefined ? wishes.length : toIntegerField(raw.total, 'total');

  return {
    wishes,
    total: Math.max(total, wishes.length),
  };
}

function parseMutationResponse(raw: unknown): InvitationMutationResponse {
  if (!isObjectRecord(raw)) {
    throw new Error('Format response mutasi undangan tidak valid.');
  }

  return {
    success: raw.success === true,
    message:
      typeof raw.message === 'string' && raw.message.trim()
        ? raw.message
        : 'Permintaan berhasil diproses.',
  };
}

function cloneStatsResponse(response: InvitationStatsResponse): InvitationStatsResponse {
  return {
    total_wishes: response.total_wishes,
    total_confirmed: response.total_confirmed,
    total_guests: response.total_guests,
  };
}

function cloneWishesResponse(response: InvitationWishesResponse): InvitationWishesResponse {
  return {
    total: response.total,
    wishes: response.wishes.map((wish) => ({ ...wish })),
  };
}

function toStatsCacheKey(invitationIdentifier: string): string {
  return toInvitationIdentifier(invitationIdentifier).toLowerCase();
}

function toWishesCacheKey(invitationIdentifier: string, options: { limit?: number; offset?: number }): string {
  const limit = options.limit ?? 'default';
  const offset = options.offset ?? 0;
  return `${toStatsCacheKey(invitationIdentifier)}|limit:${limit}|offset:${offset}`;
}

function isSlowNetworkError(error: unknown): boolean {
  return isSlowNetworkApiError(error);
}

export async function getInvitationStats(
  invitationIdentifier: string,
  options: InvitationReadRequestOptions = {}
) {
  const cacheKey = toStatsCacheKey(invitationIdentifier);
  const allowSlowNetworkFallback = options.allowSlowNetworkFallback ?? true;

  try {
    const response = await httpRequest<unknown>(`${toInvitationBasePath(invitationIdentifier)}/stats`, {
      signal: options.signal,
      timeoutMs: options.timeoutMs ?? 12000,
      retry: options.retry ?? 2,
    });

    const parsed = parseInvitationStatsResponse(response);
    invitationStatsCache.set(cacheKey, cloneStatsResponse(parsed));

    return parsed;
  } catch (error) {
    const mappedError = mapInvitationApiError(error, 'stats');

    if (allowSlowNetworkFallback && isSlowNetworkError(mappedError)) {
      const cached = invitationStatsCache.get(cacheKey);
      return cached ? cloneStatsResponse(cached) : cloneStatsResponse(DEFAULT_STATS_RESPONSE);
    }

    throw mappedError;
  }
}

export async function getInvitationWishes(
  invitationIdentifier: string,
  options: InvitationWishesRequestOptions = {}
) {
  const cacheKey = toWishesCacheKey(invitationIdentifier, options);
  const allowSlowNetworkFallback = options.allowSlowNetworkFallback ?? true;

  const query = toQueryString({
    limit: options.limit,
    offset: options.offset,
  });

  try {
    const response = await httpRequest<unknown>(
      `${toInvitationBasePath(invitationIdentifier)}/wishes${query}`,
      {
        signal: options.signal,
        timeoutMs: options.timeoutMs ?? 12000,
        retry: options.retry ?? 2,
      }
    );

    const parsed = parseInvitationWishesResponse(response);
    invitationWishesCache.set(cacheKey, cloneWishesResponse(parsed));

    return parsed;
  } catch (error) {
    const mappedError = mapInvitationApiError(error, 'wishes');

    if (allowSlowNetworkFallback && isSlowNetworkError(mappedError)) {
      const cached = invitationWishesCache.get(cacheKey);
      return cached ? cloneWishesResponse(cached) : cloneWishesResponse(DEFAULT_WISHES_RESPONSE);
    }

    throw mappedError;
  }
}

export async function submitRsvp(
  invitationIdentifier: string,
  payload: SubmitRsvpPayload
) {
  const normalizedName = payload.name.trim();

  if (!normalizedName) {
    throw new Error('Nama tamu wajib diisi.');
  }

  if (payload.pax <= 0) {
    throw new Error('Jumlah tamu minimal 1.');
  }

  try {
    const response = await httpRequest<unknown>(
      `${toInvitationBasePath(invitationIdentifier)}/rsvp`,
      {
        method: 'POST',
        body: {
          name: normalizedName,
          status: payload.status,
          pax: payload.pax,
        },
      }
    );

    return parseMutationResponse(response);
  } catch (error) {
    throw mapInvitationApiError(error, 'submitRsvp');
  }
}

export async function submitWish(
  invitationIdentifier: string,
  payload: SubmitWishPayload
) {
  const normalizedName = payload.name.trim();
  const normalizedMessage = payload.message.trim();

  if (!normalizedName) {
    throw new Error('Nama pengirim wajib diisi.');
  }

  if (!normalizedMessage) {
    throw new Error('Ucapan wajib diisi.');
  }

  try {
    const response = await httpRequest<unknown>(
      `${toInvitationBasePath(invitationIdentifier)}/wishes`,
      {
        method: 'POST',
        body: {
          name: normalizedName,
          message: normalizedMessage,
        },
      }
    );

    return parseMutationResponse(response);
  } catch (error) {
    throw mapInvitationApiError(error, 'submitWish');
  }
}
