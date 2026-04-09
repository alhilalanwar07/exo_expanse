import {
  View,
  Text,
  StyleSheet,
  Pressable,
  ScrollView,
  Alert,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { useNavigation, CommonActions } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import { COLORS, FONTS, SIZES } from '../constants/theme';
import { Navbar } from '../shared/components/Navbar';
import { useAuth } from '../features/auth/AuthContext';
import type { GuestStackParamList, AppStackParamList } from '../navigation/types';

// Merged Navigation Type
type NavigationProp = NativeStackNavigationProp<GuestStackParamList & AppStackParamList>;

export function ProfileScreen() {
  const navigation = useNavigation<NavigationProp>();
  const { session, disconnectDevice } = useAuth();
  const isLoggedIn = !!session;

  const handleLogin = () => {
    navigation.navigate('Login');
  };

  const handleRegister = () => {
    navigation.navigate('Register');
  };

  const handleLogout = async () => {
    try {
      await disconnectDevice();
      Alert.alert('Sukses', 'Anda telah keluar.');
    } catch (error) {
      console.error('Error logging out', error);
      Alert.alert('Error', 'Gagal memproses logout.');
    }
  };

  return (
    <SafeAreaView style={styles.safeArea} edges={['top', 'bottom']}>
      <Navbar title="Profil Saya" />

      <ScrollView contentContainerStyle={styles.scrollContent} showsVerticalScrollIndicator={false}>
        {!isLoggedIn ? (
          <View style={styles.guestContainer}>
            <View style={styles.avatarPlaceholder}>
              <MaterialCommunityIcons name="account-outline" size={48} color={COLORS.textMuted} />
            </View>
            <Text style={styles.guestText}>Masuk untuk mengelola undangan Anda.</Text>
            
            <View style={styles.guestActionRow}>
              <Pressable
                onPress={handleLogin}
                style={({ pressed }) => [
                  styles.primaryButton,
                  pressed && styles.pressedState
                ]}
              >
                <Text style={styles.primaryButtonText}>Masuk</Text>
              </Pressable>

              <Pressable
                onPress={handleRegister}
                style={({ pressed }) => [
                  styles.outlineButton,
                  pressed && styles.pressedState
                ]}
              >
                <Text style={styles.outlineButtonText}>Daftar</Text>
              </Pressable>
            </View>
          </View>
        ) : (
          <View style={styles.authContainer}>
            <View style={styles.profileHeader}>
              <View style={styles.userAvatar}>
                <Text style={styles.userAvatarText}>
                 {session.ownerName ? session.ownerName.substring(0, 2).toUpperCase() : 'ME'}
                </Text>
              </View>
              <View style={styles.userInfo}>
                <Text style={styles.userName}>{session.ownerName}</Text>
                <Text style={styles.userEmail}>{session.workspaceLabel}</Text>
              </View>
            </View>

            <View style={styles.menuSection}>
              {['Edit Profil', 'Undangan Saya', 'Perangkat Terhubung', 'Pengaturan', 'Bantuan'].map((item, index) => (
                <Pressable
                  key={index}
                  onPress={() => {
                    if (item === 'Perangkat Terhubung') {
                      navigation.navigate('ConnectDevice');
                    } else if (item === 'Undangan Saya') {
                      navigation.navigate('InvitationHub');
                    }
                  }}
                  style={({ pressed }) => [
                    styles.menuRow,
                    pressed && styles.menuRowPressed
                  ]}
                >
                  <Text style={styles.menuItemText}>{item}</Text>
                  <MaterialCommunityIcons name="chevron-right" size={24} color={COLORS.textMuted} />
                </Pressable>
              ))}
            </View>

            <Pressable
              onPress={handleLogout}
              style={({ pressed }) => [
                styles.logoutButton,
                pressed && styles.pressedState
              ]}
            >
              <MaterialCommunityIcons name="logout" size={20} color={COLORS.textLight} />
              <Text style={styles.logoutButtonText}>Keluar</Text>
            </Pressable>
          </View>
        )}
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: COLORS.background,
  },
  scrollContent: {
    flexGrow: 1,
    paddingHorizontal: SIZES.padding,
    paddingTop: 16,
    paddingBottom: 100, // accommodate bottom tab
  },
  
  // Guest View Styles
  guestContainer: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    gap: 24,
    paddingTop: 60,
  },
  avatarPlaceholder: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: COLORS.surface,
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 1,
    borderColor: COLORS.border,
    marginBottom: 8,
  },
  guestText: {
    fontFamily: FONTS.body,
    fontSize: 16,
    color: COLORS.text,
    textAlign: 'center',
    marginBottom: 16,
  },
  guestActionRow: {
    flexDirection: 'row',
    gap: 16,
    width: '100%',
  },
  primaryButton: {
    flex: 1,
    height: 52,
    backgroundColor: COLORS.primary,
    borderRadius: SIZES.radius,
    alignItems: 'center',
    justifyContent: 'center',
  },
  primaryButtonText: {
    fontFamily: FONTS.label,
    fontSize: 16,
    color: COLORS.textLight,
  },
  outlineButton: {
    flex: 1,
    height: 52,
    backgroundColor: 'transparent',
    borderWidth: 1.5,
    borderColor: COLORS.primary,
    borderRadius: SIZES.radius,
    alignItems: 'center',
    justifyContent: 'center',
  },
  outlineButtonText: {
    fontFamily: FONTS.label,
    fontSize: 16,
    color: COLORS.primary,
  },

  // Auth View Styles
  authContainer: {
    flex: 1,
    gap: 32,
    paddingTop: 16,
  },
  profileHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 20,
    backgroundColor: COLORS.surface,
    padding: 20,
    borderRadius: SIZES.radius,
    borderWidth: 1,
    borderColor: COLORS.border,
  },
  userAvatar: {
    width: 64,
    height: 64,
    borderRadius: 32,
    backgroundColor: COLORS.primary + '15',
    alignItems: 'center',
    justifyContent: 'center',
  },
  userAvatarText: {
    fontFamily: FONTS.headline,
    fontSize: 22,
    color: COLORS.primary,
    letterSpacing: 1,
  },
  userInfo: {
    flex: 1,
    gap: 4,
  },
  userName: {
    fontFamily: FONTS.headline,
    fontSize: 18,
    color: COLORS.text,
  },
  userEmail: {
    fontFamily: FONTS.body,
    fontSize: 14,
    color: COLORS.textMuted,
  },

  // Menu Styles
  menuSection: {
    backgroundColor: COLORS.surface,
    borderRadius: SIZES.radius,
    borderWidth: 1,
    borderColor: COLORS.border,
    overflow: 'hidden',
  },
  menuRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingVertical: 18,
    paddingHorizontal: 20,
    borderBottomWidth: 1,
    borderBottomColor: COLORS.border,
  },
  menuRowPressed: {
    backgroundColor: COLORS.background,
  },
  menuItemText: {
    fontFamily: FONTS.label,
    fontSize: 15,
    color: COLORS.text,
  },

  // Logout Button
  logoutButton: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: '#BA1A1A', // Error/Alert Color remains semantic
    borderRadius: SIZES.radius,
    paddingVertical: 16,
    gap: 12,
  },
  logoutButtonText: {
    fontFamily: FONTS.label,
    fontSize: 16,
    color: COLORS.textLight,
  },

  // Interaction Feedback
  pressedState: {
    opacity: 0.85,
    transform: [{ scale: 0.98 }],
  },
});
