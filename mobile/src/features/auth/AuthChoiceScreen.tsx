import { useNavigation, useRoute } from '@react-navigation/native';
import type { RouteProp } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { Pressable, StyleSheet, Text, View } from 'react-native';

import type { GuestStackParamList } from '../../navigation/types';
import { ScreenContainer } from '../../shared/components/ScreenContainer';
import { colors } from '../../shared/theme/colors';

export function AuthChoiceScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<GuestStackParamList>>();
  const route = useRoute<RouteProp<GuestStackParamList, 'AuthChoice'>>();

  const intentLabel =
    route.params?.intent === 'theme'
      ? 'melanjutkan pemilihan tema'
      : 'mengelola undangan Anda';

  return (
    <ScreenContainer scrollable={false}>
      <View style={styles.wrapper}>
        <View style={styles.card}>
          <View style={styles.ribbon} />

          <View style={styles.headerSection}>
            <Text style={styles.icon}>🔐</Text>
            <Text style={styles.eyebrow}>Satu Langkah Lagi</Text>
            <Text style={styles.title}>Masuk atau Daftar</Text>
            <Text style={styles.subtitle}>
              Untuk {intentLabel}, silakan masuk atau buat akun baru.
            </Text>
          </View>

          <View style={styles.buttonsSection}>
            <Pressable
              onPress={() => navigation.navigate('Login')}
              style={({ pressed }) => [
                styles.primaryButton,
                pressed && styles.primaryButtonPressed,
              ]}
            >
              <Text style={styles.primaryButtonIcon}>✉️</Text>
              <View style={styles.buttonContent}>
                <Text style={styles.primaryButtonTitle}>Masuk</Text>
                <Text style={styles.primaryButtonSubtitle}>Gunakan email Anda</Text>
              </View>
              <Text style={styles.buttonArrow}>→</Text>
            </Pressable>

            <Pressable
              onPress={() => navigation.navigate('Register')}
              style={({ pressed }) => [
                styles.secondaryButton,
                pressed && styles.secondaryButtonPressed,
              ]}
            >
              <Text style={styles.secondaryButtonIcon}>✨</Text>
              <View style={styles.buttonContent}>
                <Text style={styles.secondaryButtonTitle}>Daftar</Text>
                <Text style={styles.secondaryButtonSubtitle}>Buat akun baru</Text>
              </View>
              <Text style={styles.secondaryButtonArrow}>→</Text>
            </Pressable>

            <View style={styles.divider}>
              <View style={styles.dividerLine} />
              <Text style={styles.dividerText}>atau</Text>
              <View style={styles.dividerLine} />
            </View>

            <Pressable
              onPress={() => navigation.navigate('ConnectDevice')}
              style={({ pressed }) => [
                styles.tertiaryButton,
                pressed && styles.tertiaryButtonPressed,
              ]}
            >
              <Text style={styles.tertiaryButtonIcon}>🔗</Text>
              <View style={styles.buttonContent}>
                <Text style={styles.tertiaryButtonTitle}>Kode Akses Owner</Text>
                <Text style={styles.tertiaryButtonSubtitle}>Hubungkan perangkat</Text>
              </View>
            </Pressable>
          </View>
        </View>
      </View>
    </ScreenContainer>
  );
}

const styles = StyleSheet.create({
  wrapper: {
    flex: 1,
    justifyContent: 'center',
  },
  card: {
    backgroundColor: colors.surface,
    borderRadius: 24,
    borderWidth: 1,
    borderColor: colors.border,
    padding: 18,
    shadowColor: '#6E4A26',
    shadowOpacity: 0.16,
    shadowRadius: 14,
    shadowOffset: { width: 0, height: 8 },
    elevation: 4,
    overflow: 'hidden',
  },
  ribbon: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    height: 7,
    backgroundColor: colors.accent,
  },
  headerSection: {
    alignItems: 'center',
    gap: 12,
    paddingTop: 6,
    marginBottom: 12,
  },
  icon: {
    fontSize: 42,
    marginBottom: 2,
  },
  eyebrow: {
    color: colors.accentDark,
    fontSize: 11,
    fontWeight: '700',
    letterSpacing: 0.8,
    textTransform: 'uppercase',
  },
  title: {
    color: colors.textPrimary,
    fontSize: 26,
    fontWeight: '800',
    textAlign: 'center',
  },
  subtitle: {
    color: colors.textSecondary,
    fontSize: 14,
    textAlign: 'center',
    lineHeight: 20,
  },
  buttonsSection: {
    gap: 12,
  },
  primaryButton: {
    flexDirection: 'row',
    backgroundColor: colors.accent,
    borderRadius: 16,
    paddingVertical: 16,
    paddingHorizontal: 16,
    alignItems: 'center',
    gap: 12,
    shadowColor: '#6E4A26',
    shadowOpacity: 0.16,
    shadowRadius: 6,
    shadowOffset: { width: 0, height: 3 },
    elevation: 3,
  },
  primaryButtonPressed: {
    opacity: 0.85,
    transform: [{ scale: 0.98 }],
  },
  primaryButtonIcon: {
    fontSize: 22,
  },
  buttonContent: {
    flex: 1,
    gap: 2,
  },
  primaryButtonTitle: {
    color: '#FFFFFF',
    fontSize: 16,
    fontWeight: '700',
  },
  primaryButtonSubtitle: {
    color: '#FFFFFF',
    fontSize: 12,
    opacity: 0.88,
  },
  buttonArrow: {
    color: '#FFFFFF',
    fontSize: 18,
    fontWeight: '600',
  },
  secondaryButton: {
    flexDirection: 'row',
    backgroundColor: colors.surfaceMuted,
    borderColor: colors.accent,
    borderWidth: 2,
    borderRadius: 16,
    paddingVertical: 16,
    paddingHorizontal: 16,
    alignItems: 'center',
    gap: 12,
  },
  secondaryButtonPressed: {
    backgroundColor: '#FFF3E4',
  },
  secondaryButtonIcon: {
    fontSize: 22,
  },
  secondaryButtonTitle: {
    color: colors.accent,
    fontSize: 16,
    fontWeight: '700',
  },
  secondaryButtonSubtitle: {
    color: colors.textSecondary,
    fontSize: 12,
  },
  secondaryButtonArrow: {
    color: colors.accentDark,
    fontSize: 18,
    fontWeight: '700',
  },
  divider: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
    marginVertical: 8,
  },
  dividerLine: {
    flex: 1,
    height: 1,
    backgroundColor: colors.border,
  },
  dividerText: {
    color: colors.textSecondary,
    fontSize: 12,
    fontWeight: '600',
  },
  tertiaryButton: {
    flexDirection: 'row',
    backgroundColor: '#F8F4ED',
    borderColor: colors.border,
    borderWidth: 1,
    borderRadius: 16,
    paddingVertical: 14,
    paddingHorizontal: 16,
    alignItems: 'center',
    gap: 12,
  },
  tertiaryButtonPressed: {
    backgroundColor: '#EFE7DA',
  },
  tertiaryButtonIcon: {
    fontSize: 22,
  },
  tertiaryButtonTitle: {
    color: colors.textPrimary,
    fontSize: 15,
    fontWeight: '700',
  },
  tertiaryButtonSubtitle: {
    color: colors.textSecondary,
    fontSize: 12,
  },
});
