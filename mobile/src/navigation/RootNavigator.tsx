import { useEffect, useState } from 'react';
import { View, ActivityIndicator, StyleSheet, Platform } from 'react-native';
import { NavigationContainer, type LinkingOptions } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';

import { useAuth } from '../features/auth/AuthContext';
import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import { AuthChoiceScreen } from '../features/auth/AuthChoiceScreen';
import { ConnectDeviceScreen } from '../features/auth/ConnectDeviceScreen';
import { WelcomeScreen } from '../screens/WelcomeScreen';
import { HomeScreen } from '../screens/HomeScreen';
import { UndanganScreen } from '../screens/UndanganScreen';
import { ProfileScreen } from '../screens/ProfileScreen';
import { LoginScreen } from '../features/auth/LoginScreen';
import { RegisterScreen } from '../features/auth/RegisterScreen';
import { ThemePreviewScreen } from '../screens/ThemePreviewScreen';
import { InvitationContentEditorScreen } from '../screens/InvitationContentEditorScreen';
import { ApplyThemeScreen } from '../screens/ApplyThemeScreen';
import { EditProfileScreen } from '../screens/EditProfileScreen';
import { HelpScreen } from '../screens/HelpScreen';
import { InvitationFormScreen } from '../screens/InvitationFormScreen';
import type { MainTabParamList, RootStackParamList } from './types';

const Tab = createBottomTabNavigator<MainTabParamList>();
const Stack = createNativeStackNavigator<RootStackParamList>();

const linking: LinkingOptions<RootStackParamList> = {
  prefixes: ['exoinvite://', 'https://exoinvite.site'],
  config: {
    screens: {
      ConnectDevice: {
        path: 'connect',
      },
    },
  },
};

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

  const flowNavigatorKey = session ? 'authenticated-flow' : 'guest-flow';

  let initialRouteName: keyof RootStackParamList = 'Main';

  if (!session && !hasLaunched) {
    initialRouteName = 'Welcome';
  }

  return (
    <NavigationContainer linking={linking}>
      <Stack.Navigator
        key={flowNavigatorKey}
        initialRouteName={initialRouteName}
        screenOptions={{
          headerShown: false,
          contentStyle: { backgroundColor: theme.background },
          animation: 'fade',
        }}
      >
        <Stack.Screen
          name="Welcome"
          component={WelcomeScreen}
          options={{
            animation: 'fade_from_bottom',
          }}
        />
        <Stack.Screen
          name="Main"
          component={MainTabs}
          options={{
            gestureEnabled: false,
            animation: 'fade',
          }}
        />
        <Stack.Screen
          name="AuthChoice"
          component={AuthChoiceScreen}
          options={{
            animation: 'slide_from_right',
            animationTypeForReplace: 'push',
          }}
        />
        <Stack.Screen
          name="ConnectDevice"
          component={ConnectDeviceScreen}
          options={{
            animation: 'slide_from_right',
            animationTypeForReplace: 'push',
          }}
        />
        <Stack.Screen
          name="Login"
          component={LoginScreen}
          options={{
            animation: 'slide_from_right',
            animationTypeForReplace: 'push',
          }}
        />
        <Stack.Screen
          name="Register"
          component={RegisterScreen}
          options={{
            animation: 'slide_from_right',
            animationTypeForReplace: 'push',
          }}
        />
        <Stack.Screen
          name="ThemePreview"
          component={ThemePreviewScreen}
          options={{ presentation: 'modal' }}
        />
        <Stack.Screen
          name="InvitationContentEditor"
          component={InvitationContentEditorScreen}
          options={{
            presentation: 'fullScreenModal',
            animation: 'slide_from_bottom',
          }}
        />
        <Stack.Screen
          name="ApplyTheme"
          component={ApplyThemeScreen}
          options={{
            presentation: 'modal',
            animation: 'slide_from_bottom',
          }}
        />
        <Stack.Screen
          name="EditProfile"
          component={EditProfileScreen}
          options={{
            animation: 'slide_from_right',
          }}
        />
        <Stack.Screen
          name="Help"
          component={HelpScreen}
          options={{
            animation: 'slide_from_right',
          }}
        />
        <Stack.Screen
          name="InvitationForm"
          component={InvitationFormScreen}
          options={{
            presentation: 'fullScreenModal',
            animation: 'slide_from_bottom',
          }}
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
