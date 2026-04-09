export type GuestStackParamList = {
  Welcome: undefined;
  Home: undefined;
  ThemeCatalog: undefined;
  AuthChoice:
    | {
        intent?: 'theme' | 'manage';
      }
    | undefined;
  Login: undefined;
  Register: undefined;
  ConnectDevice: undefined;
  Profile: undefined;
};

export type AppStackParamList = {
  Home: undefined;
  InvitationHub: undefined;
  ConnectDevice: undefined;
  Profile: undefined;
};
