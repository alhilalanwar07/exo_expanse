import { httpRequest } from '../../services/httpClient';

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

function getReadableErrorMessage(error: unknown, fallbackMessage: string): string {
  if (!(error instanceof Error)) {
    return fallbackMessage;
  }

  const rawMessage = error.message;
  const jsonStartIndex = rawMessage.indexOf('{');

  if (jsonStartIndex >= 0) {
    const jsonPart = rawMessage.slice(jsonStartIndex);

    try {
      const parsed = JSON.parse(jsonPart) as {
        message?: string;
        errors?: Record<string, string[]>;
      };

      if (parsed.message) {
        return parsed.message;
      }

      if (parsed.errors) {
        const firstFieldErrors = Object.values(parsed.errors)[0];

        if (Array.isArray(firstFieldErrors) && firstFieldErrors.length > 0) {
          return firstFieldErrors[0] ?? fallbackMessage;
        }
      }
    } catch {
      // Keep fallback from raw message when response body is not JSON.
    }
  }

  return rawMessage;
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
    throw new Error(getReadableErrorMessage(error, 'Gagal menukar kode akses.'));
  }
}

export async function refreshMobileSession(refreshToken: string) {
  try {
    const response = await httpRequest<MobileSessionEnvelope>('/api/mobile/access/refresh', {
      method: 'POST',
      body: {
        refresh_token: refreshToken,
      },
    });

    return response.session;
  } catch (error) {
    throw new Error(getReadableErrorMessage(error, 'Session berakhir. Silakan login ulang.'));
  }
}

export function revokeMobileSession(accessToken: string) {
  return httpRequest<{ success: boolean }>('/api/mobile/access/revoke', {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${accessToken}`,
    },
  });
}

export async function registerMobileAccount(payload: {
  name: string;
  email: string;
  password: string;
}) {
  try {
    const response = await httpRequest<RegisterEnvelope>('/api/mobile/auth/register', {
      method: 'POST',
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
    throw new Error(getReadableErrorMessage(error, 'Registrasi gagal.'));
  }
}

export async function loginWithEmailPassword(payload: {
  email: string;
  password: string;
  deviceAlias?: string;
  platform?: 'ios' | 'android' | 'web';
}) {
  try {
    const response = await httpRequest<MobileSessionEnvelope>('/api/mobile/auth/login', {
      method: 'POST',
      body: {
        email: payload.email,
        password: payload.password,
        device_alias: payload.deviceAlias,
        platform: payload.platform,
      },
    });

    return response.session;
  } catch (error) {
    throw new Error(getReadableErrorMessage(error, 'Login gagal.'));
  }
}
