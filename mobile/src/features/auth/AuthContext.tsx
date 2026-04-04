import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useMemo,
  useState,
  type PropsWithChildren,
} from 'react';
import { Platform } from 'react-native';

import {
  exchangeAccessCode,
  loginWithEmailPassword,
  registerMobileAccount,
  refreshMobileSession,
  revokeMobileSession,
  type MobileApiSession,
} from './auth.api';

import { clearAuthSession, readAuthSession, writeAuthSession } from './auth.storage';
import type {
  AuthContextValue,
  AuthSession,
  ConnectDevicePayload,
  LoginWithPasswordPayload,
  RegisterAccountPayload,
  RegisterAccountResult,
} from './auth.types';

const AuthContext = createContext<AuthContextValue | null>(null);

function mapApiSessionToAuthSession(apiSession: MobileApiSession): AuthSession {
  return {
    workspaceId: apiSession.workspace_id,
    workspaceLabel: apiSession.workspace_label,
    ownerName: apiSession.owner_name,
    deviceAlias: apiSession.device_alias ?? null,
    accessToken: apiSession.access_token,
    refreshToken: apiSession.refresh_token,
    connectedAt: new Date().toISOString(),
    expiresAt: apiSession.expires_at,
  };
}

function isExpired(isoDateTime: string): boolean {
  return new Date(isoDateTime).getTime() <= Date.now();
}

function detectPlatform(): 'ios' | 'android' | 'web' {
  if (Platform.OS === 'ios') {
    return 'ios';
  }

  if (Platform.OS === 'android') {
    return 'android';
  }

  return 'web';
}

export function AuthProvider({ children }: PropsWithChildren) {
  const [session, setSession] = useState<AuthSession | null>(null);
  const [isHydrating, setIsHydrating] = useState(true);

  useEffect(() => {
    let isMounted = true;

    (async () => {
      let nextSession = await readAuthSession();

      if (nextSession && isExpired(nextSession.expiresAt)) {
        try {
          const refreshedSession = await refreshMobileSession(nextSession.refreshToken);
          nextSession = mapApiSessionToAuthSession(refreshedSession);
          await writeAuthSession(nextSession);
        } catch {
          await clearAuthSession();
          nextSession = null;
        }
      }

      if (isMounted) {
        setSession(nextSession);
        setIsHydrating(false);
      }
    })();

    return () => {
      isMounted = false;
    };
  }, []);

  const connectDevice = useCallback(async (payload: ConnectDevicePayload) => {
    const normalizedAccessCode = payload.accessCode.trim().toUpperCase();
    const normalizedDeviceAlias = payload.deviceAlias?.trim() || 'iPhone Owner';

    if (!normalizedAccessCode) {
      throw new Error('Kode akses wajib diisi.');
    }

    if (!/^[A-Z0-9-]{6,64}$/.test(normalizedAccessCode)) {
      throw new Error('Format kode akses tidak valid.');
    }

    const apiSession = await exchangeAccessCode({
      accessCode: normalizedAccessCode,
      deviceAlias: normalizedDeviceAlias,
      platform: detectPlatform(),
    });

    const nextSession = mapApiSessionToAuthSession(apiSession);

    await writeAuthSession(nextSession);
    setSession(nextSession);
  }, []);

  const registerAccount = useCallback(async (payload: RegisterAccountPayload): Promise<RegisterAccountResult> => {
    const normalizedName = payload.name.trim();
    const normalizedEmail = payload.email.trim().toLowerCase();
    const normalizedPassword = payload.password.trim();

    if (!normalizedName || !normalizedEmail || !normalizedPassword) {
      throw new Error('Nama, email, dan password wajib diisi.');
    }

    const response = await registerMobileAccount({
      name: normalizedName,
      email: normalizedEmail,
      password: normalizedPassword,
    });

    return {
      message: response.message,
      requiresEmailVerification: response.requires_email_verification,
    };
  }, []);

  const loginWithPassword = useCallback(async (payload: LoginWithPasswordPayload) => {
    const normalizedEmail = payload.email.trim().toLowerCase();
    const normalizedPassword = payload.password.trim();
    const normalizedDeviceAlias = payload.deviceAlias?.trim() || 'iPhone Owner';

    if (!normalizedEmail || !normalizedPassword) {
      throw new Error('Email dan password wajib diisi.');
    }

    const apiSession = await loginWithEmailPassword({
      email: normalizedEmail,
      password: normalizedPassword,
      deviceAlias: normalizedDeviceAlias,
      platform: detectPlatform(),
    });

    const nextSession = mapApiSessionToAuthSession(apiSession);

    await writeAuthSession(nextSession);
    setSession(nextSession);
  }, []);

  const disconnectDevice = useCallback(async () => {
    if (session?.accessToken) {
      try {
        await revokeMobileSession(session.accessToken);
      } catch {
        // Ignore network error on logout and continue cleaning local session.
      }
    }

    await clearAuthSession();
    setSession(null);
  }, [session]);

  const value = useMemo<AuthContextValue>(
    () => ({
      session,
      isHydrating,
      connectDevice,
      registerAccount,
      loginWithPassword,
      disconnectDevice,
    }),
    [session, isHydrating, connectDevice, registerAccount, loginWithPassword, disconnectDevice]
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  const context = useContext(AuthContext);

  if (!context) {
    throw new Error('useAuth harus dipakai di dalam AuthProvider.');
  }

  return context;
}
