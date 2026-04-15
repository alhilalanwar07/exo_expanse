import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useMemo,
  useRef,
  useState,
  type PropsWithChildren,
} from 'react';
import { Platform } from 'react-native';

import {
  exchangeAccessCode,
  isAuthApiError,
  loginWithEmailPassword,
  requestPasswordReset,
  registerMobileAccount,
  refreshMobileSession,
  revokeMobileSession,
  type MobileApiSession,
} from './auth.api';

import { clearAuthSession, readAuthSession, writeAuthSession } from './auth.storage';
import { configureHttpClientAuth } from '../../services/httpClient';
import type {
  AuthContextValue,
  AuthSession,
  ConnectDevicePayload,
  ForgotPasswordPayload,
  ForgotPasswordResult,
  LoginWithPasswordPayload,
  RegisterAccountPayload,
  RegisterAccountResult,
} from './auth.types';

const AuthContext = createContext<AuthContextValue | null>(null);

type AuthState = {
  session: AuthSession | null;
  isHydrating: boolean;
};

const INITIAL_AUTH_STATE: AuthState = {
  session: null,
  isHydrating: true,
};

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

function mapAuthSessionToHttpClientSession(session: AuthSession) {
  return {
    accessToken: session.accessToken,
    refreshToken: session.refreshToken,
    expiresAt: session.expiresAt,
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
  const [authState, setAuthState] = useState<AuthState>(INITIAL_AUTH_STATE);
  const bootstrapRunIdRef = useRef(0);

  const setSession = useCallback((nextSession: AuthSession | null) => {
    setAuthState((prev) => ({
      ...prev,
      session: nextSession,
    }));
  }, []);

  const finishHydration = useCallback((nextSession: AuthSession | null) => {
    setAuthState({
      session: nextSession,
      isHydrating: false,
    });
  }, []);

  useEffect(() => {
    configureHttpClientAuth({
      getSession: async () => {
        const session = await readAuthSession();
        return session ? mapAuthSessionToHttpClientSession(session) : null;
      },
      refreshSession: async (session) => {
        const latestSession = await readAuthSession();

        if (!latestSession || latestSession.refreshToken !== session.refreshToken) {
          return null;
        }

        try {
          const refreshedSession = await refreshMobileSession(latestSession.refreshToken);
          const nextSession = mapApiSessionToAuthSession(refreshedSession);

          await writeAuthSession(nextSession);
          setSession(nextSession);

          return mapAuthSessionToHttpClientSession(nextSession);
        } catch (error) {
          if (
            isAuthApiError(error)
            && (error.code === 'NETWORK_ERROR' || error.code === 'TIMEOUT')
          ) {
            throw error;
          }

          return null;
        }
      },
      clearSession: async () => {
        await clearAuthSession();
        setSession(null);
      },
    });

    return () => {
      configureHttpClientAuth(null);
    };
  }, [setSession]);

  useEffect(() => {
    let isActive = true;
    const bootstrapRunId = ++bootstrapRunIdRef.current;
    const refreshAbortController = new AbortController();

    const safelyFinishHydration = (nextSession: AuthSession | null) => {
      if (!isActive || bootstrapRunId !== bootstrapRunIdRef.current) {
        return;
      }

      finishHydration(nextSession);
    };

    (async () => {
      let nextSession: AuthSession | null = null;

      try {
        nextSession = await readAuthSession();

        if (nextSession && isExpired(nextSession.expiresAt)) {
          try {
            const refreshedSession = await refreshMobileSession(nextSession.refreshToken, {
              signal: refreshAbortController.signal,
            });
            nextSession = mapApiSessionToAuthSession(refreshedSession);
            await writeAuthSession(nextSession);
          } catch (refreshError) {
            if (refreshAbortController.signal.aborted) {
              return;
            }

            // Keep cached session on transient network issues to avoid startup auth flicker.
            if (
              isAuthApiError(refreshError)
              && (refreshError.code === 'NETWORK_ERROR' || refreshError.code === 'TIMEOUT')
            ) {
              safelyFinishHydration(nextSession);
              return;
            }

            await clearAuthSession();
            nextSession = null;
          }
        }

        safelyFinishHydration(nextSession);
      } catch {
        await clearAuthSession();
        safelyFinishHydration(null);
      }
    })();

    return () => {
      isActive = false;
      refreshAbortController.abort();
    };
  }, [finishHydration]);

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
  }, [setSession]);

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
      signal: payload.signal,
    });

    return {
      message: response.message,
      requiresEmailVerification: response.requires_email_verification,
    };
  }, []);

  const requestPasswordResetAction = useCallback(async (payload: ForgotPasswordPayload): Promise<ForgotPasswordResult> => {
    const normalizedEmail = payload.email.trim().toLowerCase();

    if (!normalizedEmail) {
      throw new Error('Email wajib diisi.');
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(normalizedEmail)) {
      throw new Error('Format email tidak valid.');
    }

    const response = await requestPasswordReset({
      email: normalizedEmail,
      signal: payload.signal,
    });

    return {
      message: response.message,
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
      signal: payload.signal,
    });

    const nextSession = mapApiSessionToAuthSession(apiSession);

    await writeAuthSession(nextSession);
    setSession(nextSession);
  }, [setSession]);

  const updateOwnerName = useCallback(async (ownerName: string) => {
    const normalizedOwnerName = ownerName.trim();

    if (!normalizedOwnerName) {
      return;
    }

    setAuthState((previousState) => {
      if (!previousState.session) {
        return previousState;
      }

      const nextSession = {
        ...previousState.session,
        ownerName: normalizedOwnerName,
      };

      void writeAuthSession(nextSession);

      return {
        ...previousState,
        session: nextSession,
      };
    });
  }, []);

  const disconnectDevice = useCallback(async () => {
    if (authState.session?.accessToken) {
      try {
        await revokeMobileSession(authState.session.accessToken);
      } catch {
        // Ignore network error on logout and continue cleaning local session.
      }
    }

    await clearAuthSession();
    setSession(null);
  }, [authState.session, setSession]);

  const value = useMemo<AuthContextValue>(
    () => ({
      session: authState.session,
      isHydrating: authState.isHydrating,
      connectDevice,
      registerAccount,
      requestPasswordReset: requestPasswordResetAction,
      loginWithPassword,
      updateOwnerName,
      disconnectDevice,
    }),
    [authState, connectDevice, registerAccount, requestPasswordResetAction, loginWithPassword, updateOwnerName, disconnectDevice]
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
