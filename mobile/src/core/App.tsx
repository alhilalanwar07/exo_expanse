import { StatusBar } from 'expo-status-bar';
import { useFonts } from 'expo-font';
import {
  PlusJakartaSans_400Regular,
  PlusJakartaSans_500Medium,
  PlusJakartaSans_600SemiBold,
  PlusJakartaSans_700Bold,
  PlusJakartaSans_800ExtraBold,
} from '@expo-google-fonts/plus-jakarta-sans';
import {
  Manrope_400Regular,
  Manrope_500Medium,
  Manrope_600SemiBold,
  Manrope_700Bold,
} from '@expo-google-fonts/manrope';
import { useState, useCallback, useEffect } from 'react';
import { View, ActivityIndicator, useColorScheme } from 'react-native';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import AsyncStorage from '@react-native-async-storage/async-storage';

import { AuthProvider } from '../features/auth/AuthContext';
import { RootNavigator } from '../navigation/RootNavigator';
import { ThemeContext, lightPalette, darkPalette } from '../shared/theme/index';

const THEME_KEY = 'APP_THEME_PREFERENCE';

export function MobileApp() {
  const systemScheme = useColorScheme();

  // null = not yet loaded from storage
  const [isDark, setIsDark] = useState<boolean | null>(null);

  // Load persisted preference once on mount
  useEffect(() => {
    AsyncStorage.getItem(THEME_KEY)
      .then((saved) => {
        if (saved === 'dark') setIsDark(true);
        else if (saved === 'light') setIsDark(false);
        else setIsDark(systemScheme === 'dark'); // fallback: system
      })
      .catch(() => setIsDark(systemScheme === 'dark'));
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const toggleTheme = useCallback(() => {
    setIsDark((prev) => {
      const next = !prev;
      void AsyncStorage.setItem(THEME_KEY, next ? 'dark' : 'light');
      return next;
    });
  }, []);

  const [fontsLoaded] = useFonts({
    PlusJakartaSans_Regular: PlusJakartaSans_400Regular,
    PlusJakartaSans_Medium: PlusJakartaSans_500Medium,
    PlusJakartaSans_SemiBold: PlusJakartaSans_600SemiBold,
    PlusJakartaSans_Bold: PlusJakartaSans_700Bold,
    PlusJakartaSans_ExtraBold: PlusJakartaSans_800ExtraBold,
    Manrope_Regular: Manrope_400Regular,
    Manrope_Medium: Manrope_500Medium,
    Manrope_SemiBold: Manrope_600SemiBold,
    Manrope_Bold: Manrope_700Bold,
  });

  // Wait until theme preference is resolved AND fonts are loaded
  if (isDark === null || !fontsLoaded) {
    const bg = systemScheme === 'dark' ? '#0D0815' : '#FFF7FC';
    return (
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: bg }}>
        <ActivityIndicator size="large" color="#630ED4" />
      </View>
    );
  }

  const theme = isDark ? darkPalette : lightPalette;

  return (
    <ThemeContext.Provider value={{ theme, isDark, toggleTheme }}>
      <SafeAreaProvider>
        <AuthProvider>
          <RootNavigator />
          <StatusBar style={isDark ? 'light' : 'dark'} />
        </AuthProvider>
      </SafeAreaProvider>
    </ThemeContext.Provider>
  );
}
