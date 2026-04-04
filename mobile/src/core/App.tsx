import { StatusBar } from 'expo-status-bar';

import { AuthProvider } from '../features/auth/AuthContext';
import { RootNavigator } from '../navigation/RootNavigator';

export function MobileApp() {
  return (
    <AuthProvider>
      <RootNavigator />
      <StatusBar style="dark" />
    </AuthProvider>
  );
}
