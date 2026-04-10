import {
  View,
  Text,
  StyleSheet,
  Pressable,
  ScrollView,
  Alert,
  Switch,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import { useAuth } from '../features/auth/AuthContext';
import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import type { GuestStackParamList, AppStackParamList } from '../navigation/types';

type NavigationProp = NativeStackNavigationProp<GuestStackParamList & AppStackParamList>;

const MENU_ITEMS = [
  { label: 'Edit Profil', icon: 'account-edit-outline', route: null },
  { label: 'Undangan Saya', icon: 'card-text-outline', route: 'InvitationHub' },
  { label: 'Perangkat Terhubung', icon: 'devices', route: 'ConnectDevice' },
  { label: 'Bantuan', icon: 'help-circle-outline', route: null },
] as const;

export function ProfileScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { session, disconnectDevice } = useAuth();
  const { theme, isDark, toggleTheme } = useAppTheme();
  const isLoggedIn = !!session;

  const s = makeStyles(theme);

  const handleLogout = async () => {
    try {
      await disconnectDevice();
      Alert.alert('Sukses', 'Anda telah keluar.');
    } catch {
      Alert.alert('Error', 'Gagal memproses logout.');
    }
  };

  return (
    <SafeAreaView style={s.safeArea} edges={['bottom']}>

      <ScrollView contentContainerStyle={s.scrollContent} showsVerticalScrollIndicator={false}>
        {/* Dark Mode Toggle Card */}
        <View style={s.settingCard}>
          <View style={s.settingRow}>
            <View style={s.settingIconWrap}>
              <MaterialCommunityIcons
                name={isDark ? 'weather-night' : 'weather-sunny'}
                size={20}
                color={theme.primary}
              />
            </View>
            <Text style={s.settingLabel}>Mode Gelap</Text>
            <Switch
              value={isDark}
              onValueChange={toggleTheme}
              trackColor={{ false: theme.outlineVariant, true: theme.primaryContainer }}
              thumbColor={isDark ? theme.primary : theme.onSurfaceVariant}
            />
          </View>
        </View>

        {!isLoggedIn ? (
          <View style={s.guestContainer}>
            <View style={s.avatarPlaceholder}>
              <MaterialCommunityIcons name="account-outline" size={48} color={theme.outline} />
            </View>
            <Text style={s.guestTitle}>Belum Masuk</Text>
            <Text style={s.guestText}>Masuk untuk mengelola undangan Anda.</Text>

            <View style={s.guestActionRow}>
              <Pressable
                onPress={() => navigation.navigate('Login')}
                style={({ pressed }) => [s.primaryButton, pressed && s.pressedState]}
              >
                <Text style={s.primaryButtonText}>Masuk</Text>
              </Pressable>

              <Pressable
                onPress={() => navigation.navigate('Register')}
                style={({ pressed }) => [s.outlineButton, pressed && s.pressedState]}
              >
                <Text style={s.outlineButtonText}>Daftar</Text>
              </Pressable>
            </View>
          </View>
        ) : (
          <View style={s.authContainer}>
            {/* Profile Header */}
            <View style={s.profileHeader}>
              <View style={s.userAvatar}>
                <Text style={s.userAvatarText}>
                  {session.ownerName ? session.ownerName.substring(0, 2).toUpperCase() : 'ME'}
                </Text>
              </View>
              <View style={s.userInfo}>
                <Text style={s.userName}>{session.ownerName}</Text>
                <Text style={s.userEmail}>{session.workspaceLabel}</Text>
              </View>
            </View>

            {/* Menu */}
            <View style={s.menuSection}>
              {MENU_ITEMS.map((item) => (
                <Pressable
                  key={item.label}
                  onPress={() => {
                    if (item.route) navigation.navigate(item.route as never);
                  }}
                  style={({ pressed }) => [s.menuRow, pressed && s.menuRowPressed]}
                >
                  <View style={s.menuLeft}>
                    <MaterialCommunityIcons name={item.icon as never} size={20} color={theme.primary} />
                    <Text style={s.menuItemText}>{item.label}</Text>
                  </View>
                  <MaterialCommunityIcons name="chevron-right" size={22} color={theme.outline} />
                </Pressable>
              ))}
            </View>

            {/* Logout */}
            <Pressable
              onPress={handleLogout}
              style={({ pressed }) => [s.logoutButton, pressed && s.pressedState]}
            >
              <MaterialCommunityIcons name="logout" size={20} color="#FFFFFF" />
              <Text style={s.logoutButtonText}>Keluar</Text>
            </Pressable>
          </View>
        )}
      </ScrollView>
    </SafeAreaView>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme']) {
  return StyleSheet.create({
    safeArea: {
      flex: 1,
      backgroundColor: t.background,
    },
    scrollContent: {
      flexGrow: 1,
      paddingHorizontal: 20,
      paddingTop: 16,
      paddingBottom: 100,
      gap: 16,
    },

    // Dark mode toggle
    settingCard: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: 16,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      overflow: 'hidden',
    },
    settingRow: {
      flexDirection: 'row',
      alignItems: 'center',
      paddingVertical: 14,
      paddingHorizontal: 18,
      gap: 14,
    },
    settingIconWrap: {
      width: 36,
      height: 36,
      borderRadius: 10,
      backgroundColor: t.surfaceContainerHighest,
      alignItems: 'center',
      justifyContent: 'center',
    },
    settingLabel: {
      flex: 1,
      fontFamily: F.label,
      fontSize: 15,
      color: t.onSurface,
    },

    // Guest
    guestContainer: {
      flex: 1,
      alignItems: 'center',
      gap: 12,
      paddingTop: 40,
    },
    avatarPlaceholder: {
      width: 88,
      height: 88,
      borderRadius: 44,
      backgroundColor: t.surfaceContainerLow,
      alignItems: 'center',
      justifyContent: 'center',
      borderWidth: 1,
      borderColor: t.outlineVariant,
      marginBottom: 4,
    },
    guestTitle: {
      fontFamily: F.display,
      fontSize: 20,
      color: t.onSurface,
    },
    guestText: {
      fontFamily: F.body,
      fontSize: 14,
      color: t.onSurfaceVariant,
      textAlign: 'center',
      marginBottom: 8,
    },
    guestActionRow: {
      flexDirection: 'row',
      gap: 14,
      width: '100%',
    },
    primaryButton: {
      flex: 1,
      height: 52,
      backgroundColor: t.primary,
      borderRadius: 999,
      alignItems: 'center',
      justifyContent: 'center',
      shadowColor: t.primary,
      shadowOpacity: 0.28,
      shadowRadius: 12,
      shadowOffset: { width: 0, height: 6 },
      elevation: 5,
    },
    primaryButtonText: {
      fontFamily: F.labelBold,
      fontSize: 15,
      color: '#FFFFFF',
    },
    outlineButton: {
      flex: 1,
      height: 52,
      borderWidth: 1.5,
      borderColor: t.primary,
      borderRadius: 999,
      alignItems: 'center',
      justifyContent: 'center',
    },
    outlineButtonText: {
      fontFamily: F.labelBold,
      fontSize: 15,
      color: t.primary,
    },

    // Auth
    authContainer: {
      flex: 1,
      gap: 16,
    },
    profileHeader: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 16,
      backgroundColor: t.surfaceContainerLow,
      padding: 20,
      borderRadius: 20,
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },
    userAvatar: {
      width: 64,
      height: 64,
      borderRadius: 32,
      backgroundColor: t.surfaceContainerHighest,
      alignItems: 'center',
      justifyContent: 'center',
    },
    userAvatarText: {
      fontFamily: F.display,
      fontSize: 22,
      color: t.primary,
      letterSpacing: 1,
    },
    userInfo: {
      flex: 1,
      gap: 4,
    },
    userName: {
      fontFamily: F.heading,
      fontSize: 18,
      color: t.onSurface,
    },
    userEmail: {
      fontFamily: F.body,
      fontSize: 13,
      color: t.onSurfaceVariant,
    },

    // Menu
    menuSection: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: 20,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      overflow: 'hidden',
    },
    menuRow: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      paddingVertical: 16,
      paddingHorizontal: 18,
      borderBottomWidth: 1,
      borderBottomColor: t.outlineVariant,
    },
    menuLeft: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 12,
    },
    menuRowPressed: {
      backgroundColor: t.surfaceContainerHighest,
    },
    menuItemText: {
      fontFamily: F.label,
      fontSize: 15,
      color: t.onSurface,
    },

    // Logout
    logoutButton: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      backgroundColor: '#BA1A1A',
      borderRadius: 999,
      paddingVertical: 16,
      gap: 10,
      shadowColor: '#BA1A1A',
      shadowOpacity: 0.25,
      shadowRadius: 10,
      shadowOffset: { width: 0, height: 4 },
      elevation: 4,
    },
    logoutButtonText: {
      fontFamily: F.labelBold,
      fontSize: 16,
      color: '#FFFFFF',
    },

    pressedState: {
      opacity: 0.82,
      transform: [{ scale: 0.98 }],
    },
  });
}
