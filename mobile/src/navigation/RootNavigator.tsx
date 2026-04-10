import { useEffect, useState } from 'react';
import { View, ActivityIndicator, StyleSheet, Platform } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';

import { useAuth } from '../features/auth/AuthContext';
import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import { WelcomeScreen } from '../screens/WelcomeScreen';
import { HomeScreen } from '../screens/HomeScreen';
import { UndanganScreen } from '../screens/UndanganScreen';
import { ProfileScreen } from '../screens/ProfileScreen';
import { LoginScreen } from '../features/auth/LoginScreen';
import { RegisterScreen } from '../features/auth/RegisterScreen';
import { ThemePreviewScreen } from '../screens/ThemePreviewScreen';

// -- Types
export type MainTabParamList = {
  Home: undefined;
  Undangan: undefined;
  Profil: undefined;
};

export type RootStackParamList = {
  Welcome: undefined;
  Main: undefined;
  Login: undefined;
  Register: undefined;
  ThemePreview: { id: number; name: string; previewUrl: string; isPremium: boolean };
};

const Tab = createBottomTabNavigator<MainTabParamList>();
const Stack = createNativeStackNavigator<RootStackParamList>();

// Tab icon map
const TAB_ICONS: Record<string, [keyof typeof Ionicons.glyphMap, keyof typeof Ionicons.glyphMap]> = {
  Home: ['home', 'home-outline'],
  Undangan: ['mail', 'mail-outline'],
  Profil: ['person', 'person-outline'],
};

function MainTabs() {
  const { theme } = useAppTheme();

  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        headerShown: false,
        tabBarActiveTintColor: theme.navActiveTint,
        tabBarInactiveTintColor: theme.navInactiveTint,
        tabBarStyle: {
          position: 'absolute',
          bottom: Platform.OS === 'ios' ? 24 : 16,
          left: 16,
          right: 16,
          height: 68,
          borderRadius: 28,
          backgroundColor: theme.navBg,
          borderTopWidth: 1,
          borderTopColor: theme.navBorder,
          borderColor: theme.navBorder,
          borderWidth: 1,
          shadowColor: theme.isDark ? '#000' : '#1A0B2E',
          shadowOffset: { width: 0, height: 8 },
          shadowOpacity: theme.isDark ? 0.5 : 0.1,
          shadowRadius: 24,
          elevation: 12,
        },
        tabBarLabelStyle: {
          fontFamily: F.label,
          fontSize: 10,
          letterSpacing: 0.3,
          marginBottom: 6,
        },
        tabBarItemStyle: {
          paddingTop: 8,
        },
        tabBarIcon: ({ color, focused }) => {
          const [active, inactive] = TAB_ICONS[route.name] ?? ['help-circle', 'help-circle-outline'];
          const iconName = focused ? active : inactive;
          return (
            <Ionicons name={iconName} size={focused ? 24 : 22} color={color} />
          );
        },
        tabBarBackground: () => (
          // Transparent native background — styled entirely via tabBarStyle
          <View style={StyleSheet.absoluteFillObject} />
        ),
      })}
    >
      <Tab.Screen name="Home" component={HomeScreen} options={{ tabBarLabel: 'Beranda' }} />
      <Tab.Screen name="Undangan" component={UndanganScreen} options={{ tabBarLabel: 'Undangan' }} />
      <Tab.Screen name="Profil" component={ProfileScreen} options={{ tabBarLabel: 'Profil' }} />
    </Tab.Navigator>
  );
}

export function RootNavigator() {
  const { session, isHydrating } = useAuth();
  const { theme } = useAppTheme();
  const [isAppReady, setIsAppReady] = useState(false);
  const [hasLaunched, setHasLaunched] = useState(false);

  useEffect(() => {
    async function checkFirstLaunch() {
      try {
        const launched = await AsyncStorage.getItem('HAS_LAUNCHED');
        setHasLaunched(launched === 'true');
      } catch {
        setHasLaunched(false);
      } finally {
        setIsAppReady(true);
      }
    }
    void checkFirstLaunch();
  }, []);

  if (!isAppReady || isHydrating) {
    return (
      <View style={[styles.splash, { backgroundColor: theme.background }]}>
        <ActivityIndicator size="large" color={theme.primary} />
      </View>
    );
  }

  const getInitialRoute = (): keyof RootStackParamList => {
    if (session) return 'Main';
    if (!hasLaunched) return 'Welcome';
    return 'Login';
  };

  return (
    <NavigationContainer>
      <Stack.Navigator
        initialRouteName={getInitialRoute()}
        screenOptions={{ headerShown: false }}
      >
        <Stack.Screen name="Welcome" component={WelcomeScreen} />
        <Stack.Screen name="Main" component={MainTabs} />
        <Stack.Screen name="Login" component={LoginScreen} />
        <Stack.Screen name="Register" component={RegisterScreen} />
        <Stack.Screen
          name="ThemePreview"
          component={ThemePreviewScreen}
          options={{ presentation: 'modal' }}
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
}

const styles = StyleSheet.create({
  splash: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
});
