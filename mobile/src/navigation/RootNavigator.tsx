import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';

import { AuthChoiceScreen } from '../features/auth/AuthChoiceScreen';
import { ConnectDeviceScreen } from '../features/auth/ConnectDeviceScreen';
import { LoginScreen } from '../features/auth/LoginScreen';
import { RegisterScreen } from '../features/auth/RegisterScreen';
import { useAuth } from '../features/auth/AuthContext';
import { HomeScreen } from '../screens/HomeScreen';
import { InvitationHubScreen } from '../screens/InvitationHubScreen';
import { LoadingScreen } from '../screens/LoadingScreen';
import { PublicHomeScreen } from '../screens/PublicHomeScreen';
import { ThemeCatalogScreen } from '../screens/ThemeCatalogScreen';
import { colors } from '../shared/theme/colors';
import type { AppStackParamList, GuestStackParamList } from './types';

const GuestStack = createNativeStackNavigator<GuestStackParamList>();
const AppStack = createNativeStackNavigator<AppStackParamList>();

function GuestNavigator() {
  return (
    <GuestStack.Navigator
      screenOptions={{
        headerStyle: { backgroundColor: colors.background },
        headerShadowVisible: false,
        headerTitleStyle: { color: colors.textPrimary },
      }}
    >
      <GuestStack.Screen
        name="PublicHome"
        component={PublicHomeScreen}
        options={{ title: 'Exo Expanse' }}
      />
      <GuestStack.Screen
        name="ThemeCatalog"
        component={ThemeCatalogScreen}
        options={{ title: 'Pilih Tema' }}
      />
      <GuestStack.Screen
        name="AuthChoice"
        component={AuthChoiceScreen}
        options={{ title: 'Masuk atau Daftar' }}
      />
      <GuestStack.Screen name="Login" component={LoginScreen} options={{ title: 'Login' }} />
      <GuestStack.Screen
        name="Register"
        component={RegisterScreen}
        options={{ title: 'Register' }}
      />
      <GuestStack.Screen
        name="ConnectDevice"
        component={ConnectDeviceScreen}
        options={{ title: 'Hubungkan Perangkat' }}
      />
    </GuestStack.Navigator>
  );
}

function AppNavigator() {
  return (
    <AppStack.Navigator
      screenOptions={{
        headerStyle: { backgroundColor: colors.background },
        headerShadowVisible: false,
        headerTitleStyle: { color: colors.textPrimary },
      }}
    >
      <AppStack.Screen name="Home" component={HomeScreen} options={{ title: 'Beranda' }} />
      <AppStack.Screen
        name="InvitationHub"
        component={InvitationHubScreen}
        options={{ title: 'Invitation Hub' }}
      />
    </AppStack.Navigator>
  );
}

export function RootNavigator() {
  const { session, isHydrating } = useAuth();

  if (isHydrating) {
    return <LoadingScreen />;
  }

  return <NavigationContainer>{session ? <AppNavigator /> : <GuestNavigator />}</NavigationContainer>;
}
