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
}

export interface LoginWithPasswordPayload {
  email: string;
  password: string;
  deviceAlias?: string;
}

export interface RegisterAccountResult {
  message: string;
  requiresEmailVerification: boolean;
}

export interface AuthContextValue {
  session: AuthSession | null;
  isHydrating: boolean;
  connectDevice: (payload: ConnectDevicePayload) => Promise<void>;
  registerAccount: (payload: RegisterAccountPayload) => Promise<RegisterAccountResult>;
  loginWithPassword: (payload: LoginWithPasswordPayload) => Promise<void>;
  disconnectDevice: () => Promise<void>;
}
