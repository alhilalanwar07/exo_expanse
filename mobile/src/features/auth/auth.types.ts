export interface AuthSession {
  workspaceId: string;
  workspaceLabel: string;
  ownerName: string;
  deviceAlias: string | null;
  accessToken: string;
  refreshToken: string;
  connectedAt: string;
  expiresAt: string;
}

export interface ConnectDevicePayload {
  accessCode: string;
  deviceAlias?: string;
}

export interface RegisterAccountPayload {
  name: string;
  email: string;
  password: string;
  signal?: AbortSignal;
}

export interface LoginWithPasswordPayload {
  email: string;
  password: string;
  deviceAlias?: string;
  signal?: AbortSignal;
}

export interface ForgotPasswordPayload {
  email: string;
  signal?: AbortSignal;
}

export interface RegisterAccountResult {
  message: string;
  requiresEmailVerification: boolean;
}

export interface ForgotPasswordResult {
  message: string;
}

export interface AuthContextValue {
  session: AuthSession | null;
  isHydrating: boolean;
  connectDevice: (payload: ConnectDevicePayload) => Promise<void>;
  registerAccount: (payload: RegisterAccountPayload) => Promise<RegisterAccountResult>;
  requestPasswordReset: (payload: ForgotPasswordPayload) => Promise<ForgotPasswordResult>;
  loginWithPassword: (payload: LoginWithPasswordPayload) => Promise<void>;
  updateOwnerName: (ownerName: string) => Promise<void>;
  disconnectDevice: () => Promise<void>;
}
