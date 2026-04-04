import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { useState } from 'react';
import { Pressable, StyleSheet, Text, TextInput, View } from 'react-native';

import { useAuth } from './AuthContext';
import type { GuestStackParamList } from '../../navigation/types';
import { ScreenContainer } from '../../shared/components/ScreenContainer';
import { colors } from '../../shared/theme/colors';

export function RegisterScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<GuestStackParamList>>();
  const { registerAccount } = useAuth();
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [notice, setNotice] = useState<string | null>(null);
  const [noticeVariant, setNoticeVariant] = useState<'success' | 'error'>('error');
  const [nameFocused, setNameFocused] = useState(false);
  const [emailFocused, setEmailFocused] = useState(false);
  const [passwordFocused, setPasswordFocused] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleRegister = async () => {
    if (!name.trim() || !email.trim() || !password.trim()) {
      setNotice('Nama, email, dan password wajib diisi.');
      setNoticeVariant('error');
      return;
    }

    try {
      setIsSubmitting(true);
      setNotice(null);

      const result = await registerAccount({
        name,
        email,
        password,
      });

      setNoticeVariant('success');
      setNotice(
        result.requiresEmailVerification
          ? `${result.message} Cek inbox atau folder spam, lalu aktivasi akun sebelum login.`
          : result.message
      );

      setPassword('');
    } catch (error) {
      setNoticeVariant('error');
      setNotice(error instanceof Error ? error.message : 'Registrasi gagal. Silakan coba lagi.');
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
            <Text style={styles.icon}>🎉</Text>
            <Text style={styles.title}>Buat Akun Baru</Text>
            <Text style={styles.subtitle}>Daftar untuk mulai membuat undangan digital.</Text>
          </View>

          <View style={styles.formSection}>
            <View style={styles.formGroup}>
              <Text style={styles.label}>Nama Lengkap</Text>
              <View
                style={[
                  styles.inputWrapper,
                  nameFocused && styles.inputWrapperFocused,
                ]}
              >
                <Text style={styles.inputIcon}>👤</Text>
                <TextInput
                  value={name}
                  onChangeText={setName}
                  onFocus={() => setNameFocused(true)}
                  onBlur={() => setNameFocused(false)}
                  placeholder="Masukkan nama lengkap Anda"
                  placeholderTextColor={colors.textSecondary}
                  style={styles.input}
                  autoCapitalize="words"
                />
              </View>
            </View>

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
              <Text style={styles.label}>Password</Text>
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
            <View style={[styles.noticeBox, noticeVariant === 'success' ? styles.noticeBoxSuccess : null]}>
              <Text style={styles.noticeIcon}>ℹ️</Text>
              <Text style={[styles.notice, noticeVariant === 'success' ? styles.noticeSuccess : null]}>{notice}</Text>
            </View>
          ) : null}

          <View style={styles.buttonsSection}>
            <Pressable
              onPress={handleRegister}
              style={({ pressed }) => [
                styles.primaryButton,
                pressed && styles.primaryButtonPressed,
                isSubmitting && styles.buttonDisabled,
              ]}
              disabled={isSubmitting}
            >
              <Text style={styles.primaryButtonText}>{isSubmitting ? 'Mendaftarkan...' : 'Daftar'}</Text>
            </Pressable>

            <View style={styles.loginPrompt}>
              <Text style={styles.loginText}>Sudah punya akun? </Text>
              <Pressable onPress={() => navigation.navigate('Login')}>
                <Text style={styles.loginLink}>Masuk sekarang</Text>
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
    marginBottom: 18,
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
    gap: 14,
  },
  formGroup: {
    gap: 8,
  },
  label: {
    color: colors.textPrimary,
    fontSize: 14,
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
  noticeBoxSuccess: {
    backgroundColor: '#DCFCE7',
    borderColor: '#86EFAC',
  },
  noticeSuccess: {
    color: '#166534',
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
  loginPrompt: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
  },
  loginText: {
    color: colors.textSecondary,
    fontSize: 14,
  },
  loginLink: {
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
