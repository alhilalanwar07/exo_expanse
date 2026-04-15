import { httpRequest } from '../../services/httpClient';
import {
  ApiEndpointError,
  type ApiEndpointErrorCode,
  type ApiFieldErrors,
  type BackendErrorPayload,
  toApiEndpointError,
} from '../../services/apiResponse';

export interface MobileApiSession {
  workspace_id: string;
  workspace_label: string;
  owner_name: string;
  device_alias: string | null;
  access_token: string;
  refresh_token: string;
  expires_at: string;
  refresh_expires_at: string;
}

interface MobileSessionEnvelope {
  session: MobileApiSession;
}

interface RegisterEnvelope {
  message: string;
  data: {
    name: string;
    email: string;
    requires_email_verification: boolean;
  };
}

interface ForgotPasswordEnvelope {
  message: string;
}

type AuthOperation = 'exchange' | 'login' | 'register' | 'forgotPassword' | 'refresh' | 'revoke';
type ValidationErrors = ApiFieldErrors;

export type AuthApiErrorCode = ApiEndpointErrorCode;

export class AuthApiError extends ApiEndpointError {
  readonly requiresEmailVerification: boolean;

  constructor(params: {
    message: string;
    status: number;
    code: AuthApiErrorCode;
    fieldErrors?: ValidationErrors;
    payload?: BackendErrorPayload | null;
    requiresEmailVerification?: boolean;
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

    this.name = 'AuthApiError';
    this.requiresEmailVerification = params.requiresEmailVerification ?? false;
  }
}

export function isAuthApiError(error: unknown): error is AuthApiError {
  return error instanceof AuthApiError;
}

const DEFAULT_ERROR_MESSAGES: Record<AuthOperation, string> = {
  exchange: 'Gagal menukar kode akses.',
  login: 'Login gagal. Silakan coba lagi.',
  register: 'Registrasi gagal. Silakan coba lagi.',
  forgotPassword: 'Gagal mengirim permintaan reset password.',
  refresh: 'Sesi berakhir. Silakan login ulang.',
  revoke: 'Gagal memutus sesi perangkat.',
};

const NETWORK_ERROR_MESSAGES: Record<AuthOperation, string> = {
  exchange: 'Tidak dapat menghubungi server. Periksa koneksi internet Anda.',
  login: 'Tidak dapat login karena koneksi bermasalah. Periksa internet Anda.',
  register: 'Tidak dapat registrasi karena koneksi bermasalah. Periksa internet Anda.',
  forgotPassword: 'Tidak dapat mengirim reset password karena koneksi bermasalah. Periksa internet Anda.',
  refresh: 'Koneksi terputus saat memperbarui sesi. Silakan coba lagi.',
  revoke: 'Tidak dapat memutus sesi karena koneksi bermasalah.',
};

const TIMEOUT_ERROR_MESSAGES: Record<AuthOperation, string> = {
  exchange: 'Waktu koneksi habis saat menukar kode akses. Silakan coba lagi.',
  login: 'Waktu koneksi habis saat login. Silakan coba lagi.',
  register: 'Waktu koneksi habis saat registrasi. Silakan coba lagi.',
  forgotPassword: 'Waktu koneksi habis saat mengirim reset password. Silakan coba lagi.',
  refresh: 'Waktu koneksi habis saat memperbarui sesi. Silakan login ulang jika perlu.',
  revoke: 'Waktu koneksi habis saat memutus sesi perangkat.',
};

function toAuthApiError(baseError: ApiEndpointError): AuthApiError {
  const requiresEmailVerification = baseError.payload?.requires_email_verification === true;

  return new AuthApiError({
    message: baseError.message,
    status: baseError.status,
    code: baseError.code,
    fieldErrors: baseError.fieldErrors,
    payload: baseError.payload,
    requiresEmailVerification,
    cause: baseError.cause,
  });
}

function mapAuthError(error: unknown, operation: AuthOperation): AuthApiError {
  if (isAuthApiError(error)) {
    return error;
  }

  const baseError = toApiEndpointError(error, {
    defaultMessage: DEFAULT_ERROR_MESSAGES[operation],
    networkMessage: NETWORK_ERROR_MESSAGES[operation],
    timeoutMessage: TIMEOUT_ERROR_MESSAGES[operation],
    abortedMessage: 'Permintaan dibatalkan.',
    serverErrorMessage: 'Server sedang bermasalah. Silakan coba beberapa saat lagi.',
    mapHttpError: (httpError, context) => {
      const requiresEmailVerification = context.payload?.requires_email_verification === true;

      if (operation === 'refresh' && [401, 403, 422].includes(httpError.status)) {
        return new ApiEndpointError({
          message: 'Sesi berakhir. Silakan login ulang.',
          status: httpError.status,
          code: httpError.code,
          fieldErrors: context.fieldErrors,
          payload: context.payload,
          cause: httpError,
        });
      }

      if (operation === 'login' && httpError.status === 403 && requiresEmailVerification) {
        return new ApiEndpointError({
          message: context.backendMessage ?? 'Akun belum aktif. Silakan cek email Anda untuk aktivasi.',
          status: httpError.status,
          code: httpError.code,
          fieldErrors: context.fieldErrors,
          payload: context.payload,
          cause: httpError,
        });
      }

      return null;
    },
  });

  return toAuthApiError(baseError);
}

export async function exchangeAccessCode(payload: {
  accessCode: string;
  deviceAlias?: string;
  platform?: 'ios' | 'android' | 'web';
}) {
  try {
    const response = await httpRequest<MobileSessionEnvelope>('/api/mobile/access/exchange', {
      method: 'POST',
      body: {
        access_code: payload.accessCode,
        device_alias: payload.deviceAlias,
        platform: payload.platform,
      },
    });

    return response.session;
  } catch (error) {
    throw mapAuthError(error, 'exchange');
  }
}

export async function refreshMobileSession(
  refreshToken: string,
  options?: { signal?: AbortSignal }
) {
  try {
    const response = await httpRequest<MobileSessionEnvelope>('/api/mobile/access/refresh', {
      method: 'POST',
      signal: options?.signal,
      body: {
        refresh_token: refreshToken,
      },
    });

    return response.session;
  } catch (error) {
    throw mapAuthError(error, 'refresh');
  }
}

export async function revokeMobileSession(accessToken: string) {
  try {
    return await httpRequest<{ success: boolean }>('/api/mobile/access/revoke', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${accessToken}`,
      },
    });
  } catch (error) {
    throw mapAuthError(error, 'revoke');
  }
}

export async function registerMobileAccount(payload: {
  name: string;
  email: string;
  password: string;
  signal?: AbortSignal;
}) {
  try {
    const response = await httpRequest<RegisterEnvelope>('/api/mobile/auth/register', {
      method: 'POST',
      signal: payload.signal,
      body: {
        name: payload.name,
        email: payload.email,
        password: payload.password,
      },
    });

    return {
      message: response.message,
      requires_email_verification: response.data.requires_email_verification,
    };
  } catch (error) {
    throw mapAuthError(error, 'register');
  }
}

export async function requestPasswordReset(payload: {
  email: string;
  signal?: AbortSignal;
}) {
  try {
    const response = await httpRequest<ForgotPasswordEnvelope>('/api/mobile/auth/forgot-password', {
      method: 'POST',
      signal: payload.signal,
      body: {
        email: payload.email,
      },
    });

    return {
      message: response.message,
    };
  } catch (error) {
    throw mapAuthError(error, 'forgotPassword');
  }
}

export async function loginWithEmailPassword(payload: {
  email: string;
  password: string;
  deviceAlias?: string;
  platform?: 'ios' | 'android' | 'web';
  signal?: AbortSignal;
}) {
  try {
    const response = await httpRequest<MobileSessionEnvelope>('/api/mobile/auth/login', {
      method: 'POST',
      signal: payload.signal,
      body: {
        email: payload.email,
        password: payload.password,
        device_alias: payload.deviceAlias,
        platform: payload.platform,
      },
    });

    return response.session;
  } catch (error) {
    throw mapAuthError(error, 'login');
  }
}
