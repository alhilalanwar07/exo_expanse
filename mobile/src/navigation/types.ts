export type GuestStackParamList = {
  PublicHome: undefined;
  ThemeCatalog: undefined;
  AuthChoice:
    | {
        intent?: 'theme' | 'manage';
      }
    | undefined;
  Login: undefined;
  Register: undefined;
  ConnectDevice: undefined;
};

export type AppStackParamList = {
  Home: undefined;
  InvitationHub: undefined;
};
