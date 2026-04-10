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
import { useAppTheme } from '../../shared/theme/index';
import { useAuth } from './AuthContext';
import type { RootStackParamList } from '../../navigation/RootNavigator';

const STRENGTH_COLORS = ['#F97316', '#FBBF24', '#38BDF8', '#22C55E'] as const;

function getPasswordStrength(pw: string) {
  let s = 0;
  if (pw.length >= 8) s++;
  if (/[a-z]/.test(pw) && /[A-Z]/.test(pw)) s++;
  if (/\d/.test(pw)) s++;
  if (/[^A-Za-z0-9]/.test(pw)) s++;
  const labels = ['Lemah', 'Cukup', 'Bagus', 'Kuat'];
  return {
    score: s,
    label: labels[Math.max(0, s - 1)] ?? 'Lemah',
    color: STRENGTH_COLORS[Math.max(0, s - 1)] ?? STRENGTH_COLORS[0],
  };
}

export function RegisterScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<RootStackParamList>>();
  const { registerAccount } = useAuth();
  const { theme } = useAppTheme();
  const { width } = useWindowDimensions();
  const isCompact = width <= 390;
  const s = makeStyles(theme, isCompact);

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

    if (!n || !e || !p || !c) { setNotice('Semua kolom wajib diisi.'); setNoticeVariant('error'); return; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e)) { setNotice('Format email tidak valid.'); setNoticeVariant('error'); return; }
    if (p.length < 8) { setNotice('Password minimal 8 karakter.'); setNoticeVariant('error'); return; }
    if (p !== c) { setNotice('Konfirmasi password tidak cocok.'); setNoticeVariant('error'); return; }
    if (!termsAccepted) { setNotice('Harap setujui Syarat & Ketentuan terlebih dahulu.'); setNoticeVariant('error'); return; }

    try {
      setIsSubmitting(true);
      setNotice(null);
      await registerAccount({ name: n, email: e, password: p });
      navigation.replace('Main');
    } catch {
      setNotice('Pendaftaran gagal. Silakan coba lagi.');
      setNoticeVariant('error');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <SafeAreaView style={s.safeArea} edges={['top']}>
      <Navbar title="Daftar Akun Baru" onBackPress={() => navigation.goBack()} />
      <KeyboardAvoidingView style={s.flex} behavior={Platform.OS === 'ios' ? 'padding' : undefined}>
        <ScrollView
          contentContainerStyle={s.scroll}
          showsVerticalScrollIndicator={false}
          keyboardShouldPersistTaps="handled"
        >
          {/* Header */}
          <View style={s.header}>
            <Text style={s.title}>Daftar Akun Baru</Text>
            <Text style={s.subtitle}>
              Bergabunglah dengan Exoinvite dan mulailah menciptakan momen spesial Anda.
            </Text>
          </View>

          {/* Form */}
          <View style={s.form}>

            {/* Nama */}
            <View style={s.fieldGroup}>
              <Text style={s.label}>NAMA LENGKAP</Text>
              <TextInput
                value={name}
                onChangeText={setName}
                placeholder="Masukkan nama lengkap"
                placeholderTextColor={theme.outline}
                style={s.input}
                autoCapitalize="words"
                autoComplete="name"
                returnKeyType="next"
                selectionColor={theme.primary}
              />
            </View>

            {/* Email */}
            <View style={s.fieldGroup}>
              <Text style={s.label}>ALAMAT EMAIL</Text>
              <TextInput
                value={email}
                onChangeText={setEmail}
                placeholder="contoh@email.com"
                placeholderTextColor={theme.outline}
                style={s.input}
                keyboardType="email-address"
                autoCapitalize="none"
                autoComplete="email"
                returnKeyType="next"
                selectionColor={theme.primary}
              />
            </View>

            {/* Password */}
            <View style={s.fieldGroup}>
              <Text style={s.label}>KATA SANDI</Text>
              <View style={s.passwordWrap}>
                <TextInput
                  value={password}
                  onChangeText={setPassword}
                  placeholder="••••••••"
                  placeholderTextColor={theme.outline}
                  style={[s.input, s.inputPassword]}
                  secureTextEntry={!isPasswordVisible}
                  autoComplete="password"
                  returnKeyType="next"
                  selectionColor={theme.primary}
                />
                <Pressable onPress={() => setIsPasswordVisible((v) => !v)} style={s.eyeBtn} hitSlop={8}>
                  <MaterialCommunityIcons
                    name={isPasswordVisible ? 'eye-off-outline' : 'eye-outline'}
                    size={20}
                    color={theme.outline}
                  />
                </Pressable>
              </View>
              {password.length > 0 ? (
                <View style={s.strengthRow}>
                  {[1, 2, 3, 4].map((slot) => (
                    <View
                      key={slot}
                      style={[s.strengthBar, slot <= strength.score ? { backgroundColor: strength.color } : null]}
                    />
                  ))}
                  <Text style={[s.strengthLabel, { color: strength.color }]}>{strength.label}</Text>
                </View>
              ) : null}
            </View>

            {/* Konfirmasi */}
            <View style={s.fieldGroup}>
              <Text style={s.label}>KONFIRMASI KATA SANDI</Text>
              <View style={s.passwordWrap}>
                <TextInput
                  value={passwordConfirm}
                  onChangeText={setPasswordConfirm}
                  placeholder="••••••••"
                  placeholderTextColor={theme.outline}
                  style={[s.input, s.inputPassword]}
                  secureTextEntry={!isPasswordConfirmVisible}
                  autoComplete="password"
                  returnKeyType="done"
                  selectionColor={theme.primary}
                  onSubmitEditing={handleRegister}
                />
                <Pressable onPress={() => setIsPasswordConfirmVisible((v) => !v)} style={s.eyeBtn} hitSlop={8}>
                  <MaterialCommunityIcons
                    name={isPasswordConfirmVisible ? 'eye-off-outline' : 'eye-outline'}
                    size={20}
                    color={theme.outline}
                  />
                </Pressable>
              </View>
            </View>

            {/* Terms */}
            <Pressable onPress={() => setTermsAccepted((v) => !v)} style={s.termsRow}>
              <View style={[s.checkbox, termsAccepted && s.checkboxChecked]}>
                {termsAccepted ? <MaterialCommunityIcons name="check" size={14} color="#FFFFFF" /> : null}
              </View>
              <Text style={s.termsText}>
                Saya menyetujui{' '}
                <Text style={s.termsLink}>Syarat & Ketentuan</Text>
                {' '}dan{' '}
                <Text style={s.termsLink}>Kebijakan Privasi</Text>
              </Text>
            </Pressable>

            {/* Notice */}
            {notice ? (
              <View style={[s.noticeBox, noticeVariant === 'success' && s.successBox]}>
                <MaterialCommunityIcons
                  name={noticeVariant === 'success' ? 'check-circle-outline' : 'alert-circle-outline'}
                  size={16}
                  color={noticeVariant === 'success' ? theme.successIcon : theme.error}
                />
                <Text style={[s.noticeText, noticeVariant === 'success' && s.successText]}>{notice}</Text>
              </View>
            ) : null}

            {/* CTA */}
            <Pressable
              onPress={handleRegister}
              disabled={isSubmitting}
              style={({ pressed }) => [s.primaryBtn, pressed && s.pressed, isSubmitting && s.disabled]}
            >
              <Text style={s.primaryBtnText}>{isSubmitting ? 'Mendaftar...' : 'Daftar'}</Text>
            </Pressable>

            {/* Divider */}
            <View style={s.dividerRow}>
              <View style={s.dividerLine} />
              <Text style={s.dividerText}>ATAU DAFTAR DENGAN</Text>
              <View style={s.dividerLine} />
            </View>

            {/* Social */}
            <View style={s.socialRow}>
              <Pressable
                onPress={() => console.log('Google')}
                style={({ pressed }) => [s.socialBtn, s.googleBtn, pressed && s.pressed]}
              >
                <MaterialCommunityIcons name="google" size={18} color="#EA4335" />
                <Text style={s.googleText}>Google</Text>
              </Pressable>
              <Pressable
                onPress={() => console.log('Apple')}
                style={({ pressed }) => [s.socialBtn, s.appleBtn, pressed && s.pressed]}
              >
                <MaterialCommunityIcons name="apple" size={18} color={theme.appleText} />
                <Text style={s.appleText}>Apple</Text>
              </Pressable>
            </View>
          </View>

          {/* Footer */}
          <View style={s.footer}>
            <Text style={s.footerText}>Sudah punya akun? </Text>
            <Pressable onPress={() => navigation.navigate('Login')} hitSlop={8}>
              <Text style={s.footerLink}>Masuk</Text>
            </Pressable>
          </View>
        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme'], isCompact: boolean) {
  return StyleSheet.create({
    safeArea: { flex: 1, backgroundColor: t.background },
    flex: { flex: 1 },
    scroll: {
      flexGrow: 1,
      paddingHorizontal: isCompact ? 18 : 24,
      paddingTop: isCompact ? 20 : 28,
      paddingBottom: isCompact ? 28 : 36,
    },

    header: { gap: isCompact ? 8 : 10, marginBottom: isCompact ? 24 : 32 },
    title: {
      color: t.onSurface,
      fontSize: isCompact ? 26 : 30,
      fontFamily: F.display,
      letterSpacing: -0.5,
      lineHeight: isCompact ? 34 : 38,
    },
    subtitle: {
      color: t.onSurfaceVariant,
      fontSize: isCompact ? 13 : 14,
      lineHeight: isCompact ? 20 : 22,
      fontFamily: F.body,
    },

    form: { gap: isCompact ? 14 : 18 },
    fieldGroup: { gap: 7 },
    label: {
      color: t.onSurfaceVariant,
      fontSize: 10,
      fontFamily: F.labelBold,
      letterSpacing: 0.8,
      textTransform: 'uppercase',
    },

    input: {
      backgroundColor: t.fieldBg,
      borderRadius: 14,
      paddingHorizontal: 18,
      paddingVertical: isCompact ? 13 : 16,
      color: t.onSurface,
      fontSize: isCompact ? 14 : 15,
      fontFamily: F.body,
    },
    inputPassword: { paddingRight: 48 },

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

    strengthRow: { flexDirection: 'row', alignItems: 'center', gap: 5, marginTop: 2 },
    strengthBar: {
      flex: 1,
      height: 3,
      borderRadius: 1.5,
      backgroundColor: t.isDark ? 'rgba(255,255,255,0.1)' : 'rgba(204,195,216,0.4)',
    },
    strengthLabel: { fontSize: 10, fontFamily: F.labelBold, width: 36, textAlign: 'right' },

    termsRow: { flexDirection: 'row', alignItems: 'flex-start', gap: 10, marginTop: -2 },
    checkbox: {
      width: 20,
      height: 20,
      borderRadius: 5,
      borderWidth: 1.5,
      borderColor: t.outlineVariant,
      backgroundColor: t.fieldBg,
      alignItems: 'center',
      justifyContent: 'center',
      marginTop: 1,
      flexShrink: 0,
    },
    checkboxChecked: { backgroundColor: t.primary, borderColor: t.primary },
    termsText: { flex: 1, color: t.onSurfaceVariant, fontSize: 12, lineHeight: 18, fontFamily: F.body },
    termsLink: { color: t.primary, fontFamily: F.labelBold },

    noticeBox: {
      flexDirection: 'row',
      alignItems: 'flex-start',
      gap: 8,
      borderRadius: 12,
      borderWidth: 1,
      borderColor: t.errorContainer,
      backgroundColor: t.errorContainer,
      padding: 12,
    },
    successBox: { borderColor: t.successBg, backgroundColor: t.successBg },
    noticeText: { flex: 1, color: t.error, fontSize: 13, lineHeight: 19, fontFamily: F.body },
    successText: { color: t.successText },

    primaryBtn: {
      height: 56,
      borderRadius: 999,
      backgroundColor: t.primary,
      alignItems: 'center',
      justifyContent: 'center',
      shadowColor: t.primary,
      shadowOpacity: 0.28,
      shadowRadius: 16,
      shadowOffset: { width: 0, height: 8 },
      elevation: 6,
      marginTop: 4,
    },
    primaryBtnText: { color: '#FFFFFF', fontSize: isCompact ? 15 : 17, fontFamily: F.labelBold },

    dividerRow: { flexDirection: 'row', alignItems: 'center', gap: 12 },
    dividerLine: { flex: 1, height: 1, backgroundColor: t.outlineVariant, opacity: 0.5 },
    dividerText: { color: t.outline, fontSize: 10, fontFamily: F.labelBold, letterSpacing: 1.2 },

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
    googleBtn: { backgroundColor: t.googleBg, borderWidth: 1, borderColor: t.googleBorder },
    appleBtn: { backgroundColor: t.appleBg },
    googleText: { color: t.googleText, fontSize: 14, fontFamily: F.labelBold },
    appleText: { color: t.appleText, fontSize: 14, fontFamily: F.labelBold },

    footer: {
      flexDirection: 'row',
      justifyContent: 'center',
      alignItems: 'center',
      marginTop: isCompact ? 20 : 28,
    },
    footerText: { color: t.onSurfaceVariant, fontSize: 14, fontFamily: F.body },
    footerLink: { color: t.primary, fontSize: 14, fontFamily: F.labelBold },

    pressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
    disabled: { opacity: 0.6 },
  });
}
