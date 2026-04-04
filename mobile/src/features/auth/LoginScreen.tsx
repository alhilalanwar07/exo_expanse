import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { useState } from 'react';
import { Pressable, StyleSheet, Text, TextInput, View } from 'react-native';

import { useAuth } from './AuthContext';
import type { GuestStackParamList } from '../../navigation/types';
import { ScreenContainer } from '../../shared/components/ScreenContainer';
import { colors } from '../../shared/theme/colors';

export function LoginScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<GuestStackParamList>>();
  const { loginWithPassword } = useAuth();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [notice, setNotice] = useState<string | null>(null);
  const [emailFocused, setEmailFocused] = useState(false);
  const [passwordFocused, setPasswordFocused] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleLogin = async () => {
    if (!email.trim() || !password.trim()) {
      setNotice('Email dan password wajib diisi.');
      return;
    }

    try {
      setIsSubmitting(true);
      setNotice(null);

      await loginWithPassword({
        email,
        password,
      });
    } catch (error) {
      setNotice(error instanceof Error ? error.message : 'Login gagal. Silakan coba lagi.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <ScreenContainer>
      <View style={styles.wrapper}>
        <View style={styles.card}>
          <View style={styles.ribbon} />

          <View style={styles.headerSection}>
            <Text style={styles.icon}>👋</Text>
            <Text style={styles.title}>Selamat Datang Kembali</Text>
            <Text style={styles.subtitle}>Masuk ke akun Anda untuk melanjutkan.</Text>
          </View>

          <View style={styles.formSection}>
            <View style={styles.formGroup}>
              <Text style={styles.label}>Email</Text>
              <View
                style={[
                  styles.inputWrapper,
                  emailFocused && styles.inputWrapperFocused,
                ]}
              >
                <Text style={styles.inputIcon}>✉️</Text>
                <TextInput
                  value={email}
                  onChangeText={setEmail}
                  onFocus={() => setEmailFocused(true)}
                  onBlur={() => setEmailFocused(false)}
                  placeholder="nama@example.com"
                  placeholderTextColor={colors.textSecondary}
                  style={styles.input}
                  keyboardType="email-address"
                  autoCapitalize="none"
                  autoCorrect={false}
                />
              </View>
            </View>

            <View style={styles.formGroup}>
              <View style={styles.labelRow}>
                <Text style={styles.label}>Password</Text>
                <Pressable onPress={() => {}}>
                  <Text style={styles.forgotLink}>Lupa?</Text>
                </Pressable>
              </View>
              <View
                style={[
                  styles.inputWrapper,
                  passwordFocused && styles.inputWrapperFocused,
                ]}
              >
                <Text style={styles.inputIcon}>🔒</Text>
                <TextInput
                  value={password}
                  onChangeText={setPassword}
                  onFocus={() => setPasswordFocused(true)}
                  onBlur={() => setPasswordFocused(false)}
                  placeholder="Minimal 8 karakter"
                  placeholderTextColor={colors.textSecondary}
                  style={styles.input}
                  secureTextEntry
                />
              </View>
            </View>
          </View>

          {notice ? (
            <View style={styles.noticeBox}>
              <Text style={styles.noticeIcon}>ℹ️</Text>
              <Text style={styles.notice}>{notice}</Text>
            </View>
          ) : null}

          <View style={styles.buttonsSection}>
            <Pressable
              onPress={handleLogin}
              style={({ pressed }) => [
                styles.primaryButton,
                pressed && styles.primaryButtonPressed,
                isSubmitting && styles.buttonDisabled,
              ]}
              disabled={isSubmitting}
            >
              <Text style={styles.primaryButtonText}>{isSubmitting ? 'Memproses...' : 'Masuk'}</Text>
            </Pressable>

            <View style={styles.signupPrompt}>
              <Text style={styles.signupText}>Belum punya akun? </Text>
              <Pressable onPress={() => navigation.navigate('Register')}>
                <Text style={styles.signupLink}>Daftar sekarang</Text>
              </Pressable>
            </View>

            <Pressable
              onPress={() => navigation.navigate('ConnectDevice')}
              style={({ pressed }) => [
                styles.tertiaryButton,
                pressed && styles.tertiaryButtonPressed,
              ]}
            >
              <Text style={styles.tertiaryButtonText}>Masuk dengan Kode Akses</Text>
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
    marginBottom: 20,
    paddingTop: 6,
  },
  icon: {
    fontSize: 40,
    marginBottom: 4,
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
  },
  formSection: {
    gap: 18,
  },
  formGroup: {
    gap: 8,
  },
  label: {
    color: colors.textPrimary,
    fontSize: 14,
    fontWeight: '700',
  },
  labelRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  forgotLink: {
    color: colors.accentDark,
    fontSize: 12,
    fontWeight: '700',
  },
  inputWrapper: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: colors.surfaceMuted,
    borderColor: colors.border,
    borderWidth: 1.5,
    borderRadius: 14,
    paddingHorizontal: 14,
    paddingVertical: 2,
    gap: 10,
  },
  inputWrapperFocused: {
    borderColor: colors.accentLight,
    backgroundColor: '#FFF2E1',
  },
  inputIcon: {
    fontSize: 18,
  },
  input: {
    flex: 1,
    color: colors.textPrimary,
    fontSize: 15,
    paddingVertical: 12,
  },
  noticeBox: {
    flexDirection: 'row',
    backgroundColor: '#FFF6E6',
    borderColor: '#F3D8A8',
    borderWidth: 1,
    borderRadius: 12,
    padding: 12,
    gap: 10,
    alignItems: 'flex-start',
  },
  noticeIcon: {
    fontSize: 18,
    marginTop: 2,
  },
  notice: {
    color: '#7A5A2F',
    fontSize: 13,
    lineHeight: 18,
    flex: 1,
  },
  buttonsSection: {
    gap: 12,
  },
  primaryButton: {
    backgroundColor: colors.accent,
    borderRadius: 14,
    paddingVertical: 14,
    alignItems: 'center',
    shadowColor: '#6E4A26',
    shadowOpacity: 0.2,
    shadowRadius: 6,
    shadowOffset: { width: 0, height: 3 },
    elevation: 3,
  },
  primaryButtonPressed: {
    opacity: 0.85,
    transform: [{ scale: 0.98 }],
  },
  primaryButtonText: {
    color: '#FFFFFF',
    fontSize: 16,
    fontWeight: '700',
  },
  signupPrompt: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
  },
  signupText: {
    color: colors.textSecondary,
    fontSize: 14,
  },
  signupLink: {
    color: colors.accent,
    fontSize: 14,
    fontWeight: '700',
  },
  tertiaryButton: {
    backgroundColor: '#F8F4ED',
    borderColor: colors.border,
    borderWidth: 1,
    borderRadius: 14,
    paddingVertical: 12,
    alignItems: 'center',
  },
  tertiaryButtonPressed: {
    backgroundColor: '#EFE7DA',
  },
  tertiaryButtonText: {
    color: colors.accent,
    fontSize: 14,
    fontWeight: '700',
  },
  buttonDisabled: {
    opacity: 0.65,
  },
});
