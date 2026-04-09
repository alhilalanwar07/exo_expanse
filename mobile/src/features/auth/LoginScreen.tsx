import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { useState } from 'react';
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

// ── Design tokens: "Digital Atelier" light palette ──────────────────────────
const C = {
  background: '#FFF7FC',
  surface: '#FFF7FC',
  fieldBg: '#EDE0FF',          // lavender field — matches stitch
  fieldBgFocused: '#E4D0FA',
  primary: '#630ED4',
  primaryContainer: '#7C3AED',
  onPrimary: '#FFFFFF',
  onSurface: '#24162C',
  onSurfaceVariant: '#4A4455',
  outline: '#7B7487',
  outlineVariant: '#CCC3D8',
  error: '#BA1A1A',
  errorContainer: '#FFDAD6',
  secondary: '#B51C0B',        // "Daftar sekarang" accent
  forgotColor: '#630ED4',
  googleBg: '#FFFFFF',
  googleBorder: '#E2E8F0',
  googleText: '#1A1A2E',
  appleBg: '#111111',
  appleText: '#FFFFFF',
} as const;

export function LoginScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<GuestStackParamList>>();
  const { loginWithPassword } = useAuth();
  const { width } = useWindowDimensions();
  const isCompact = width <= 390;

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [notice, setNotice] = useState<string | null>(null);
  const [isPasswordVisible, setIsPasswordVisible] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleLogin = async () => {
    const e = email.trim().toLowerCase();
    const p = password.trim();

    if (!e || !p) { setNotice('Email dan password wajib diisi.'); return; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e)) { setNotice('Format email tidak valid.'); return; }
    if (p.length < 8) { setNotice('Password minimal 8 karakter.'); return; }

    try {
      setIsSubmitting(true);
      setNotice(null);
      await loginWithPassword({ email: e, password: p });
    } catch (err) {
      setNotice(err instanceof Error ? err.message : 'Login gagal. Silakan coba lagi.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <SafeAreaView style={styles.safeArea} edges={['top']}>
      <Navbar title="EXOINVITE" onBackPress={() => navigation.goBack()} />
      <KeyboardAvoidingView style={styles.flex} behavior={Platform.OS === 'ios' ? 'padding' : undefined}>
        <ScrollView
          contentContainerStyle={[styles.scroll, isCompact && styles.scrollCompact]}
          keyboardShouldPersistTaps="handled"
          showsVerticalScrollIndicator={false}
        >
          {/* ── Page Header ─────────────────── */}
          <View style={[styles.header, isCompact && styles.headerCompact]}>
            <Text style={[styles.title, isCompact && styles.titleCompact]}>Masuk ke Akun</Text>
            <Text style={[styles.subtitle, isCompact && styles.subtitleCompact]}>
              Silakan masuk untuk melanjutkan rencana perayaan Anda.
            </Text>
          </View>

          {/* ── Form ────────────────────────── */}
          <View style={[styles.form, isCompact && styles.formCompact]}>

            {/* Email */}
            <View style={styles.fieldGroup}>
              <Text style={styles.label}>EMAIL ADDRESS</Text>
              <TextInput
                value={email}
                onChangeText={setEmail}
                placeholder="nama@email.com"
                placeholderTextColor={C.outline}
                style={[styles.input, isCompact && styles.inputCompact]}
                keyboardType="email-address"
                autoCapitalize="none"
                autoCorrect={false}
                autoComplete="email"
                textContentType="emailAddress"
                returnKeyType="next"
                selectionColor={C.primary}
              />
            </View>

            {/* Password */}
            <View style={styles.fieldGroup}>
              <Text style={styles.label}>PASSWORD</Text>
              <View style={styles.passwordWrap}>
                <TextInput
                  value={password}
                  onChangeText={setPassword}
                  placeholder="••••••••"
                  placeholderTextColor={C.outline}
                  style={[styles.input, styles.inputPassword, isCompact && styles.inputCompact]}
                  secureTextEntry={!isPasswordVisible}
                  autoComplete="password"
                  textContentType="password"
                  returnKeyType="done"
                  selectionColor={C.primary}
                  onSubmitEditing={handleLogin}
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
              <Pressable
                onPress={() => console.log('Forgot password')}
                style={styles.forgotWrap}
                hitSlop={8}
              >
                <Text style={[styles.forgotText, isCompact && styles.forgotTextCompact]}>
                  Lupa Password?
                </Text>
              </Pressable>
            </View>

            {/* Error */}
            {notice ? (
              <View style={styles.errorBox}>
                <MaterialCommunityIcons name="alert-circle-outline" size={16} color={C.error} />
                <Text style={styles.errorText}>{notice}</Text>
              </View>
            ) : null}

            {/* Primary CTA */}
            <Pressable
              onPress={handleLogin}
              disabled={isSubmitting}
              style={({ pressed }) => [
                styles.primaryBtn,
                pressed && styles.pressed,
                isSubmitting && styles.disabled,
              ]}
            >
              <Text style={[styles.primaryBtnText, isCompact && styles.primaryBtnTextCompact]}>
                {isSubmitting ? 'Memproses...' : 'Masuk'}
              </Text>
            </Pressable>

            {/* Divider */}
            <View style={styles.dividerRow}>
              <View style={styles.dividerLine} />
              <Text style={styles.dividerText}>ATAU MASUK DENGAN</Text>
              <View style={styles.dividerLine} />
            </View>

            {/* Social auth */}
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
            <Text style={styles.footerText}>Belum punya akun? </Text>
            <Pressable onPress={() => navigation.navigate('Register')} hitSlop={8}>
              <Text style={styles.footerLink}>Daftar sekarang</Text>
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
    paddingTop: 32,
    paddingBottom: 36,
  },
  scrollCompact: {
    paddingHorizontal: 18,
    paddingTop: 24,
    paddingBottom: 28,
  },

  // Header
  header: { gap: 10, marginBottom: 36 },
  headerCompact: { gap: 8, marginBottom: 28 },
  title: {
    color: C.onSurface,
    fontSize: 32,
    fontFamily: F.display,
    letterSpacing: -0.5,
    lineHeight: 40,
  },
  titleCompact: { fontSize: 26, lineHeight: 34 },
  subtitle: {
    color: C.onSurfaceVariant,
    fontSize: 15,
    lineHeight: 23,
    fontFamily: F.body,
  },
  subtitleCompact: { fontSize: 14, lineHeight: 21 },

  // Form
  form: { gap: 20 },
  formCompact: { gap: 16 },

  fieldGroup: { gap: 8 },
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

  forgotWrap: { alignSelf: 'flex-end', marginTop: 4 },
  forgotText: { color: C.forgotColor, fontSize: 14, fontFamily: F.label },
  forgotTextCompact: { fontSize: 13 },

  // Error
  errorBox: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    gap: 8,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: C.errorContainer,
    backgroundColor: C.errorContainer,
    padding: 12,
    marginTop: -4,
  },
  errorText: { flex: 1, color: C.error, fontSize: 13, lineHeight: 19 },

  // Primary button
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
  dividerText: {
    color: C.outline,
    fontSize: 10,
    fontFamily: F.labelBold,
    letterSpacing: 1.2,
  },

  // Social buttons
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
    marginTop: 32,
  },
  footerCompact: { marginTop: 24 },
  footerText: { color: C.onSurfaceVariant, fontSize: 14, fontFamily: F.body },
  footerLink: { color: C.secondary, fontSize: 14, fontFamily: F.labelBold },

  // Interaction
  pressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
  disabled: { opacity: 0.6 },
});
