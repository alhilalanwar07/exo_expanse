export type ThemePreviewRouteParams = {
  id: number;
  name: string;
  previewUrl: string;
  isPremium: boolean;
};

export type InvitationContentEditorRouteParams = {
  invitationId?: string;
  invitationTitle?: string;
  initialHtml?: string;
};

export type MainTabParamList = {
  Home: undefined;
  Undangan: undefined;
  Profil: undefined;
};

export type ApplyThemeRouteParams = {
  themeId: number;
  themeName: string;
  isPremium: boolean;
};

export type InvitationFormRouteParams = {
  invitationId?: number;
};

export type RootStackParamList = {
  Welcome: undefined;
  Main: undefined;
  AuthChoice:
    | {
        intent?: 'theme' | 'manage';
      }
    | undefined;
  ConnectDevice:
    | {
        code?: string;
      }
    | undefined;
  Login: undefined;
  ForgotPassword:
    | {
        email?: string;
      }
    | undefined;
  Register: undefined;
  ThemePreview: ThemePreviewRouteParams;
  InvitationContentEditor: InvitationContentEditorRouteParams | undefined;
  InvitationForm: InvitationFormRouteParams | undefined;
  ApplyTheme: ApplyThemeRouteParams;
  EditProfile: undefined;
  Help: undefined;
};

export type AuthFlowParamList = Pick<
  RootStackParamList,
  'AuthChoice' | 'ConnectDevice' | 'Login' | 'ForgotPassword' | 'Register'
>;

// Backward-compatible aliases for existing imports.
export type GuestStackParamList = AuthFlowParamList;
export type AppStackParamList = MainTabParamList;
