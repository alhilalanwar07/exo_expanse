import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { useMemo, useState } from 'react';
import {
  KeyboardAvoidingView,
  Platform,
  Pressable,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  useWindowDimensions,
  View,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

import { Navbar } from '../../shared/components/Navbar';
import { F } from '../../shared/theme/fonts';
import { useAuth } from './AuthContext';
import type { GuestStackParamList } from '../../navigation/types';

// ── Design tokens ────────────────────────────────────────────────────────────
const C = {
  background: '#FFF7FC',
  fieldBg: '#EDE0FF',
  primary: '#630ED4',
  onPrimary: '#FFFFFF',
  onSurface: '#24162C',
  onSurfaceVariant: '#4A4455',
  outline: '#7B7487',
  outlineVariant: '#CCC3D8',
  error: '#BA1A1A',
  errorContainer: '#FFDAD6',
  loginLinkColor: '#630ED4',
  googleBg: '#FFFFFF',
  googleBorder: '#E2E8F0',
  googleText: '#1A1A2E',
  appleBg: '#111111',
  appleText: '#FFFFFF',
  strengthColors: ['#F97316', '#FBBF24', '#38BDF8', '#22C55E'],
  strengthBg: 'rgba(204,195,216,0.4)',
} as const;

function getPasswordStrength(pw: string) {
  let s = 0;
  if (pw.length >= 8) s++;
  if (/[a-z]/.test(pw) && /[A-Z]/.test(pw)) s++;
  if (/\d/.test(pw)) s++;
  if (/[^A-Za-z0-9]/.test(pw)) s++;
  const labels = ['Lemah', 'Cukup', 'Bagus', 'Kuat'];
  return { score: s, label: labels[Math.max(0, s - 1)] ?? 'Lemah', color: C.strengthColors[Math.max(0, s - 1)] ?? C.strengthColors[0] };
}

export function RegisterScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<GuestStackParamList>>();
  const { registerAccount } = useAuth();
  const { width } = useWindowDimensions();
  const isCompact = width <= 390;

  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirm, setPasswordConfirm] = useState('');
  const [termsAccepted, setTermsAccepted] = useState(false);
  const [notice, setNotice] = useState<string | null>(null);
  const [noticeVariant, setNoticeVariant] = useState<'error' | 'success'>('error');
  const [isPasswordVisible, setIsPasswordVisible] = useState(false);
  const [isPasswordConfirmVisible, setIsPasswordConfirmVisible] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const strength = useMemo(() => getPasswordStrength(password.trim()), [password]);

  const handleRegister = async () => {
    const n = name.trim();
    const e = email.trim().toLowerCase();
    const p = password.trim();
    const c = passwordConfirm.trim();

    if (!n || !e || !p || !c) {
      setNotice('Semua kolom wajib diisi.'); setNoticeVariant('error'); return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e)) {
      setNotice('Format email tidak valid.'); setNoticeVariant('error'); return;
    }
    if (p.length < 8) {
      setNotice('Password minimal 8 karakter.'); setNoticeVariant('error'); return;
    }
    if (p !== c) {
      setNotice('Konfirmasi password tidak cocok.'); setNoticeVariant('error'); return;
    }
    if (!termsAccepted) {
      setNotice('Harap setujui Syarat & Ketentuan terlebih dahulu.'); setNoticeVariant('error'); return;
    }

    try {
      setIsSubmitting(true);
      setNotice(null);
      await registerAccount({ name: n, email: e, password: p });
    } catch {
      setNotice('Pendaftaran gagal. Silakan coba lagi.');
      setNoticeVariant('error');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <SafeAreaView style={styles.safeArea} edges={['top']}>
      <Navbar title="Daftar Akun Baru" onBackPress={() => navigation.goBack()} />
      <KeyboardAvoidingView style={styles.flex} behavior={Platform.OS === 'ios' ? 'padding' : undefined}>
        <ScrollView
          contentContainerStyle={[styles.scroll, isCompact && styles.scrollCompact]}
          showsVerticalScrollIndicator={false}
          keyboardShouldPersistTaps="handled"
        >
          {/* ── Page Header ─────────────────── */}
          <View style={[styles.header, isCompact && styles.headerCompact]}>
            <Text style={[styles.title, isCompact && styles.titleCompact]}>Daftar Akun Baru</Text>
            <Text style={[styles.subtitle, isCompact && styles.subtitleCompact]}>
              Bergabunglah dengan Exoinvite dan mulailah menciptakan momen spesial Anda.
            </Text>
          </View>

          {/* ── Form ────────────────────────── */}
          <View style={[styles.form, isCompact && styles.formCompact]}>

            {/* Nama Lengkap */}
            <View style={styles.fieldGroup}>
              <Text style={styles.label}>NAMA LENGKAP</Text>
              <TextInput
                value={name}
                onChangeText={setName}
                placeholder="Masukkan nama lengkap"
                placeholderTextColor={C.outline}
                style={[styles.input, isCompact && styles.inputCompact]}
                autoCapitalize="words"
                autoComplete="name"
                returnKeyType="next"
                selectionColor={C.primary}
              />
            </View>

            {/* Alamat Email */}
            <View style={styles.fieldGroup}>
              <Text style={styles.label}>ALAMAT EMAIL</Text>
              <TextInput
                value={email}
                onChangeText={setEmail}
                placeholder="contoh@email.com"
                placeholderTextColor={C.outline}
                style={[styles.input, isCompact && styles.inputCompact]}
                keyboardType="email-address"
                autoCapitalize="none"
                autoComplete="email"
                returnKeyType="next"
                selectionColor={C.primary}
              />
            </View>

            {/* Kata Sandi */}
            <View style={styles.fieldGroup}>
              <Text style={styles.label}>KATA SANDI</Text>
              <View style={styles.passwordWrap}>
                <TextInput
                  value={password}
                  onChangeText={setPassword}
                  placeholder="••••••••"
                  placeholderTextColor={C.outline}
                  style={[styles.input, styles.inputPassword, isCompact && styles.inputCompact]}
                  secureTextEntry={!isPasswordVisible}
                  autoComplete="password"
                  returnKeyType="next"
                  selectionColor={C.primary}
                />
                <Pressable
                  onPress={() => setIsPasswordVisible((v) => !v)}
                  style={styles.eyeBtn}
                  hitSlop={8}
                >
                  <MaterialCommunityIcons
                    name={isPasswordVisible ? 'eye-off-outline' : 'eye-outline'}
                    size={20}
                    color={C.outline}
                  />
                </Pressable>
              </View>
              {/* Password strength bars */}
              {password.length > 0 ? (
                <View style={styles.strengthRow}>
                  {[1, 2, 3, 4].map((slot) => (
                    <View
                      key={slot}
                      style={[
                        styles.strengthBar,
                        slot <= strength.score ? { backgroundColor: strength.color } : null,
                      ]}
                    />
                  ))}
                  <Text style={[styles.strengthLabel, { color: strength.color }]}>
                    {strength.label}
                  </Text>
                </View>
              ) : null}
            </View>

            {/* Konfirmasi Kata Sandi */}
            <View style={styles.fieldGroup}>
              <Text style={styles.label}>KONFIRMASI KATA SANDI</Text>
              <View style={styles.passwordWrap}>
                <TextInput
                  value={passwordConfirm}
                  onChangeText={setPasswordConfirm}
                  placeholder="••••••••"
                  placeholderTextColor={C.outline}
                  style={[styles.input, styles.inputPassword, isCompact && styles.inputCompact]}
                  secureTextEntry={!isPasswordConfirmVisible}
                  autoComplete="password"
                  returnKeyType="done"
                  selectionColor={C.primary}
                  onSubmitEditing={handleRegister}
                />
                <Pressable
                  onPress={() => setIsPasswordConfirmVisible((v) => !v)}
                  style={styles.eyeBtn}
                  hitSlop={8}
                >
                  <MaterialCommunityIcons
                    name={isPasswordConfirmVisible ? 'eye-off-outline' : 'eye-outline'}
                    size={20}
                    color={C.outline}
                  />
                </Pressable>
              </View>
            </View>

            {/* Terms checkbox */}
            <Pressable
              onPress={() => setTermsAccepted((v) => !v)}
              style={styles.termsRow}
            >
              <View style={[styles.checkbox, termsAccepted && styles.checkboxChecked]}>
                {termsAccepted ? (
                  <MaterialCommunityIcons name="check" size={14} color={C.onPrimary} />
                ) : null}
              </View>
              <Text style={styles.termsText}>
                Saya menyetujui{' '}
                <Text style={styles.termsLink}>Syarat & Ketentuan</Text>
                {' '}dan{' '}
                <Text style={styles.termsLink}>Kebijakan Privasi</Text>
              </Text>
            </Pressable>

            {/* Notice */}
            {notice ? (
              <View style={[styles.noticeBox, noticeVariant === 'success' && styles.successBox]}>
                <MaterialCommunityIcons
                  name={noticeVariant === 'success' ? 'check-circle-outline' : 'alert-circle-outline'}
                  size={16}
                  color={noticeVariant === 'success' ? '#1A6B3C' : C.error}
                />
                <Text style={[styles.noticeText, noticeVariant === 'success' && styles.successText]}>
                  {notice}
                </Text>
              </View>
            ) : null}

            {/* Primary CTA */}
            <Pressable
              onPress={handleRegister}
              disabled={isSubmitting}
              style={({ pressed }) => [
                styles.primaryBtn,
                pressed && styles.pressed,
                isSubmitting && styles.disabled,
              ]}
            >
              <Text style={[styles.primaryBtnText, isCompact && styles.primaryBtnTextCompact]}>
                {isSubmitting ? 'Mendaftar...' : 'Daftar'}
              </Text>
            </Pressable>

            {/* Divider */}
            <View style={styles.dividerRow}>
              <View style={styles.dividerLine} />
              <Text style={styles.dividerText}>ATAU DAFTAR DENGAN</Text>
              <View style={styles.dividerLine} />
            </View>

            {/* Social */}
            <View style={styles.socialRow}>
              <Pressable
                onPress={() => console.log('Google')}
                style={({ pressed }) => [styles.socialBtn, styles.googleBtn, pressed && styles.pressed]}
              >
                <MaterialCommunityIcons name="google" size={18} color="#EA4335" />
                <Text style={styles.googleText}>Google</Text>
              </Pressable>
              <Pressable
                onPress={() => console.log('Apple')}
                style={({ pressed }) => [styles.socialBtn, styles.appleBtn, pressed && styles.pressed]}
              >
                <MaterialCommunityIcons name="apple" size={18} color={C.appleText} />
                <Text style={styles.appleText}>Apple</Text>
              </Pressable>
            </View>
          </View>

          {/* ── Footer ──────────────────────── */}
          <View style={[styles.footer, isCompact && styles.footerCompact]}>
            <Text style={styles.footerText}>Sudah punya akun? </Text>
            <Pressable onPress={() => navigation.navigate('Login')} hitSlop={8}>
              <Text style={styles.footerLink}>Masuk</Text>
            </Pressable>
          </View>
        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: C.background },
  flex: { flex: 1 },

  scroll: {
    flexGrow: 1,
    paddingHorizontal: 24,
    paddingTop: 28,
    paddingBottom: 36,
  },
  scrollCompact: {
    paddingHorizontal: 18,
    paddingTop: 20,
    paddingBottom: 28,
  },

  header: { gap: 10, marginBottom: 32 },
  headerCompact: { gap: 8, marginBottom: 24 },
  title: {
    color: C.onSurface,
    fontSize: 30,
    fontFamily: F.display,
    letterSpacing: -0.5,
    lineHeight: 38,
  },
  titleCompact: { fontSize: 26, lineHeight: 34 },
  subtitle: {
    color: C.onSurfaceVariant,
    fontSize: 14,
    lineHeight: 22,
    fontFamily: F.body,
  },
  subtitleCompact: { fontSize: 13, lineHeight: 20 },

  form: { gap: 18 },
  formCompact: { gap: 14 },

  fieldGroup: { gap: 7 },
  label: {
    color: C.onSurfaceVariant,
    fontSize: 10,
    fontFamily: F.labelBold,
    letterSpacing: 0.8,
    textTransform: 'uppercase',
  },

  input: {
    backgroundColor: C.fieldBg,
    borderRadius: 14,
    paddingHorizontal: 18,
    paddingVertical: 16,
    color: C.onSurface,
    fontSize: 15,
    fontFamily: F.body,
  },
  inputCompact: { paddingVertical: 13, fontSize: 14 },
  inputPassword: { flex: 1, paddingRight: 48 },

  passwordWrap: { position: 'relative' },
  eyeBtn: {
    position: 'absolute',
    right: 14,
    top: 0,
    bottom: 0,
    justifyContent: 'center',
    alignItems: 'center',
    width: 36,
  },

  strengthRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 5,
    marginTop: 2,
  },
  strengthBar: {
    flex: 1,
    height: 3,
    borderRadius: 1.5,
    backgroundColor: C.strengthBg,
  },
  strengthLabel: {
    fontSize: 10,
    fontFamily: F.labelBold,
    width: 36,
    textAlign: 'right',
  },

  // Terms
  termsRow: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    gap: 10,
    marginTop: -2,
  },
  checkbox: {
    width: 20,
    height: 20,
    borderRadius: 5,
    borderWidth: 1.5,
    borderColor: C.outlineVariant,
    backgroundColor: C.fieldBg,
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 1,
    flexShrink: 0,
  },
  checkboxChecked: {
    backgroundColor: C.primary,
    borderColor: C.primary,
  },
  termsText: {
    flex: 1,
    color: C.onSurfaceVariant,
    fontSize: 12,
    lineHeight: 18,
    fontFamily: F.body,
  },
  termsLink: { color: C.primary, fontFamily: F.labelBold },

  // Notice
  noticeBox: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    gap: 8,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: C.errorContainer,
    backgroundColor: C.errorContainer,
    padding: 12,
  },
  successBox: {
    borderColor: '#D1FAE5',
    backgroundColor: '#D1FAE5',
  },
  noticeText: { flex: 1, color: C.error, fontSize: 13, lineHeight: 19 },
  successText: { color: '#1A6B3C' },

  // Primary CTA
  primaryBtn: {
    height: 56,
    borderRadius: 999,
    backgroundColor: C.primary,
    alignItems: 'center',
    justifyContent: 'center',
    shadowColor: C.primary,
    shadowOpacity: 0.28,
    shadowRadius: 16,
    shadowOffset: { width: 0, height: 8 },
    elevation: 6,
    marginTop: 4,
  },
  primaryBtnText: { color: C.onPrimary, fontSize: 17, fontFamily: F.labelBold },
  primaryBtnTextCompact: { fontSize: 15 },

  // Divider
  dividerRow: { flexDirection: 'row', alignItems: 'center', gap: 12 },
  dividerLine: { flex: 1, height: 1, backgroundColor: C.outlineVariant, opacity: 0.5 },
  dividerText: { color: C.outline, fontSize: 10, fontFamily: F.labelBold, letterSpacing: 1.2 },

  // Social
  socialRow: { flexDirection: 'row', gap: 12 },
  socialBtn: {
    flex: 1,
    height: 52,
    borderRadius: 14,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 8,
  },
  googleBtn: { backgroundColor: C.googleBg, borderWidth: 1, borderColor: C.googleBorder },
  appleBtn: { backgroundColor: C.appleBg },
  googleText: { color: C.googleText, fontSize: 14, fontFamily: F.labelBold },
  appleText: { color: C.appleText, fontSize: 14, fontFamily: F.labelBold },

  // Footer
  footer: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 28,
  },
  footerCompact: { marginTop: 20 },
  footerText: { color: C.onSurfaceVariant, fontSize: 14, fontFamily: F.body },
  footerLink: { color: C.loginLinkColor, fontSize: 14, fontFamily: F.labelBold },

  pressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
  disabled: { opacity: 0.6 },
});
