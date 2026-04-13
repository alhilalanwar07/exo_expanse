import {
  View,
  Text,
  StyleSheet,
  Platform,
  Pressable,
  Alert,
  Switch,
} from 'react-native';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { useNavigation, type CompositeNavigationProp } from '@react-navigation/native';
import type { BottomTabNavigationProp } from '@react-navigation/bottom-tabs';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import { useAuth } from '../features/auth/AuthContext';
import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import { ScreenContainer } from '../shared/components/ScreenContainer';
import type { MainTabParamList, RootStackParamList } from '../navigation/types';

type NavigationProp = CompositeNavigationProp<
  BottomTabNavigationProp<MainTabParamList, 'Profil'>,
  NativeStackNavigationProp<RootStackParamList>
>;

type ProfileMenuIcon = keyof typeof MaterialCommunityIcons.glyphMap;
type ProfileMenuRoute = 'Undangan' | 'ConnectDevice';

const MENU_ITEMS = [
  { label: 'Edit Profil', icon: 'account-edit-outline', route: null },
  { label: 'Undangan Saya', icon: 'card-text-outline', route: 'Undangan' },
  { label: 'Perangkat Terhubung', icon: 'devices', route: 'ConnectDevice' },
  { label: 'Bantuan', icon: 'help-circle-outline', route: null },
] as const satisfies ReadonlyArray<{
  label: string;
  icon: ProfileMenuIcon;
  route: ProfileMenuRoute | null;
}>;

export function ProfileScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { session, disconnectDevice } = useAuth();
  const { theme, isDark, toggleTheme } = useAppTheme();
  const insets = useSafeAreaInsets();
  const isLoggedIn = !!session;
  const bottomClearance = insets.bottom + (Platform.OS === 'ios' ? 96 : 88);

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
    <ScreenContainer
      contentGap={0}
      contentStyle={s.content}
      scrollContentStyle={{ paddingBottom: bottomClearance }}
      backgroundColor={theme.background}
      showBackgroundEffects={false}
    >
      <View style={s.body}>
        <View style={s.headerBlock}>
          <Text style={s.headerEyebrow}>Ruang Pribadi</Text>
          <Text style={s.headerTitle}>Profil & Pengaturan</Text>
          <Text style={s.headerSubtitle}>
            {isLoggedIn
              ? 'Kelola akun, perangkat, dan preferensi tampilan Anda.'
              : 'Masuk untuk mengelola undangan dan perangkat Anda.'}
          </Text>
        </View>

        <View style={s.settingCard}>
          <View style={s.settingIconWrap}>
            <MaterialCommunityIcons
              name={isDark ? 'weather-night' : 'weather-sunny'}
              size={20}
              color={theme.primary}
            />
          </View>
          <View style={s.settingTextWrap}>
            <Text style={s.settingLabel}>Mode Gelap</Text>
            <Text style={s.settingHint}>Sesuaikan tampilan aplikasi sesuai kenyamanan Anda.</Text>
          </View>
          <Switch
            value={isDark}
            onValueChange={toggleTheme}
            trackColor={{ false: theme.outlineVariant, true: theme.primaryContainer }}
            thumbColor={isDark ? theme.primary : theme.onSurfaceVariant}
          />
        </View>

        {!isLoggedIn ? (
          <View style={s.guestCard}>
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
            <View style={s.profileCard}>
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

              <View style={s.metaRow}>
                <View style={s.metaPill}>
                  <Text style={s.metaPillText}>Owner Session</Text>
                </View>
                {session.deviceAlias ? (
                  <Text style={s.metaDevice}>{session.deviceAlias}</Text>
                ) : null}
              </View>
            </View>

            <View style={s.sectionCard}>
              <Text style={s.sectionLabel}>Akses Cepat</Text>

              <View style={s.menuSection}>
                {MENU_ITEMS.map((item, index) => (
                  <Pressable
                    key={item.label}
                    onPress={() => {
                      if (item.route) {
                        navigation.navigate(item.route);
                      }
                    }}
                    disabled={!item.route}
                    style={({ pressed }) => [
                      s.menuRow,
                      index === MENU_ITEMS.length - 1 && s.menuRowLast,
                      !item.route && s.menuRowMuted,
                      pressed && item.route && s.menuRowPressed,
                    ]}
                  >
                    <View style={s.menuLeft}>
                      <MaterialCommunityIcons name={item.icon} size={20} color={theme.primary} />
                      <Text style={s.menuItemText}>{item.label}</Text>
                    </View>
                    <MaterialCommunityIcons
                      name={item.route ? 'chevron-right' : 'clock-outline'}
                      size={20}
                      color={theme.outline}
                    />
                  </Pressable>
                ))}
              </View>
            </View>

            <Pressable
              onPress={handleLogout}
              style={({ pressed }) => [s.logoutButton, pressed && s.pressedState]}
            >
              <MaterialCommunityIcons name="logout" size={20} color="#FFFFFF" />
              <Text style={s.logoutButtonText}>Keluar</Text>
            </Pressable>
          </View>
        )}
      </View>
    </ScreenContainer>
  );
}

  function makeStyles(t: ReturnType<typeof useAppTheme>['theme']) {
    return StyleSheet.create({
      content: {
        flex: 1,
      },
      body: {
        flex: 1,
        gap: 14,
      },

      headerBlock: {
        gap: 8,
        paddingTop: 6,
        paddingHorizontal: 2,
      },
      headerEyebrow: {
        fontFamily: F.label,
        fontSize: 10,
        letterSpacing: 1,
        textTransform: 'uppercase',
        color: t.primary,
      },
      headerTitle: {
        fontFamily: F.heading,
        fontSize: 22,
        color: t.onSurface,
        letterSpacing: -0.3,
      },
      headerSubtitle: {
        fontFamily: F.body,
        fontSize: 14,
        lineHeight: 21,
        color: t.onSurfaceVariant,
      },

      settingCard: {
        backgroundColor: t.surfaceContainerLow,
        borderRadius: 16,
        borderWidth: 1,
        borderColor: t.outlineVariant,
        paddingHorizontal: 14,
        paddingVertical: 13,
        flexDirection: 'row',
        alignItems: 'center',
        gap: 12,
      },
      settingIconWrap: {
        width: 36,
        height: 36,
        borderRadius: 10,
        backgroundColor: t.surfaceContainerHighest,
        alignItems: 'center',
        justifyContent: 'center',
      },
      settingTextWrap: {
        flex: 1,
        gap: 2,
      },
      settingLabel: {
        fontFamily: F.labelBold,
        fontSize: 15,
        color: t.onSurface,
      },
      settingHint: {
        fontFamily: F.body,
        fontSize: 12,
        color: t.onSurfaceVariant,
      },

      guestCard: {
        backgroundColor: t.surfaceContainerLow,
        borderRadius: 20,
        borderWidth: 1,
        borderColor: t.outlineVariant,
        alignItems: 'center',
        gap: 10,
        paddingHorizontal: 20,
        paddingTop: 28,
        paddingBottom: 22,
      },
      avatarPlaceholder: {
        width: 88,
        height: 88,
        borderRadius: 44,
        backgroundColor: t.surfaceContainerHighest,
        alignItems: 'center',
        justifyContent: 'center',
        borderWidth: 1,
        borderColor: t.outlineVariant,
        marginBottom: 2,
      },
      guestTitle: {
        fontFamily: F.heading,
        fontSize: 21,
        color: t.onSurface,
      },
      guestText: {
        fontFamily: F.body,
        fontSize: 14,
        lineHeight: 21,
        color: t.onSurfaceVariant,
        textAlign: 'center',
        marginBottom: 8,
      },
      guestActionRow: {
        flexDirection: 'row',
        gap: 12,
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
        shadowOpacity: 0.26,
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
        backgroundColor: t.surface,
      },
      outlineButtonText: {
        fontFamily: F.labelBold,
        fontSize: 15,
        color: t.primary,
      },

      authContainer: {
        gap: 14,
      },
      profileCard: {
        backgroundColor: t.surfaceContainerLow,
        borderRadius: 20,
        borderWidth: 1,
        borderColor: t.outlineVariant,
        padding: 18,
        gap: 12,
      },
      profileHeader: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 14,
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
        gap: 3,
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
      metaRow: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        gap: 10,
      },
      metaPill: {
        backgroundColor: t.surfaceContainerHighest,
        borderRadius: 999,
        borderWidth: 1,
        borderColor: t.outlineVariant,
        paddingHorizontal: 12,
        paddingVertical: 6,
      },
      metaPillText: {
        fontFamily: F.label,
        fontSize: 11,
        letterSpacing: 0.7,
        textTransform: 'uppercase',
        color: t.primary,
      },
      metaDevice: {
        flex: 1,
        textAlign: 'right',
        fontFamily: F.body,
        fontSize: 12,
        color: t.onSurfaceVariant,
      },

      sectionCard: {
        backgroundColor: t.surfaceContainerLow,
        borderRadius: 20,
        borderWidth: 1,
        borderColor: t.outlineVariant,
        padding: 16,
        gap: 12,
      },
      sectionLabel: {
        fontFamily: F.label,
        fontSize: 11,
        letterSpacing: 0.9,
        textTransform: 'uppercase',
        color: t.onSurfaceVariant,
        marginLeft: 2,
      },
      menuSection: {
        backgroundColor: t.surface,
        borderRadius: 14,
        borderWidth: 1,
        borderColor: t.outlineVariant,
        overflow: 'hidden',
      },
      menuRow: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'space-between',
        paddingVertical: 14,
        paddingHorizontal: 14,
        borderBottomWidth: 1,
        borderBottomColor: t.outlineVariant,
      },
      menuRowLast: {
        borderBottomWidth: 0,
      },
      menuRowMuted: {
        opacity: 0.65,
      },
      menuLeft: {
        flexDirection: 'row',
        alignItems: 'center',
        gap: 10,
      },
      menuRowPressed: {
        backgroundColor: t.surfaceContainerHighest,
      },
      menuItemText: {
        fontFamily: F.label,
        fontSize: 15,
        color: t.onSurface,
      },

      logoutButton: {
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: '#BA1A1A',
        borderRadius: 999,
        paddingVertical: 15,
        gap: 10,
        shadowColor: '#BA1A1A',
        shadowOpacity: 0.24,
        shadowRadius: 10,
        shadowOffset: { width: 0, height: 4 },
        elevation: Platform.OS === 'android' ? 4 : 0,
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
