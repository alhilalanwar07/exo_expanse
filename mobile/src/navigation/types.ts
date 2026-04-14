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

export type RootStackParamList = {
  Welcome: undefined;
  Main: undefined;
  AuthChoice:
    | {
        intent?: 'theme' | 'manage';
      }
    | undefined;
  ConnectDevice: undefined;
  Login: undefined;
  Register: undefined;
  ThemePreview: ThemePreviewRouteParams;
  InvitationContentEditor: InvitationContentEditorRouteParams | undefined;
  ApplyTheme: ApplyThemeRouteParams;
  EditProfile: undefined;
  Help: undefined;
};

export type AuthFlowParamList = Pick<
  RootStackParamList,
  'AuthChoice' | 'ConnectDevice' | 'Login' | 'Register'
>;

// Backward-compatible aliases for existing imports.
export type GuestStackParamList = AuthFlowParamList;
export type AppStackParamList = MainTabParamList;
