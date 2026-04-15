import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { useEffect, useMemo, useRef, useState } from 'react';
import {
  Alert,
  Pressable,
  StyleSheet,
  Text,
  TextInput,
  useWindowDimensions,
  View,
} from 'react-native';

import { Navbar } from '../../shared/components/Navbar';
import { ScreenContainer, SCREEN_CONTAINER_LAYOUT } from '../../shared/components/ScreenContainer';
import { F } from '../../shared/theme/fonts';
import { useAppTheme } from '../../shared/theme/index';
import { useAuth } from './AuthContext';
import type { RootStackParamList } from '../../navigation/types';

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
  const isCompact = width <= SCREEN_CONTAINER_LAYOUT.compactBreakpoint;
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
  const registerRequestAbortRef = useRef<AbortController | null>(null);
  const isMountedRef = useRef(true);

  useEffect(() => {
    return () => {
      isMountedRef.current = false;
      registerRequestAbortRef.current?.abort();
      registerRequestAbortRef.current = null;
    };
  }, []);

  const strength = useMemo(() => getPasswordStrength(password.trim()), [password]);

  const handleSocialAuthPress = (provider: 'Google' | 'Apple') => {
    Alert.alert(
      'Dalam Pengembangan',
      `Daftar dengan ${provider} masih dalam pengembangan.`
    );
  };

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

    const controller = new AbortController();
    registerRequestAbortRef.current?.abort();
    registerRequestAbortRef.current = controller;

    try {
      setIsSubmitting(true);
      setNotice(null);
      const result = await registerAccount({ name: n, email: e, password: p, signal: controller.signal });

      if (registerRequestAbortRef.current !== controller || controller.signal.aborted) {
        return;
      }

      const successMessage = result.message || 'Registrasi berhasil. Silakan login untuk melanjutkan.';

      if (isMountedRef.current) {
        setNotice(successMessage);
        setNoticeVariant('success');
      }

      Alert.alert('Registrasi Berhasil', successMessage, [
        {
          text: 'Lanjut ke Login',
          onPress: () => navigation.replace('Login'),
        },
      ]);
    } catch (err) {
      if (controller.signal.aborted || registerRequestAbortRef.current !== controller) {
        return;
      }

      setNotice(err instanceof Error ? err.message : 'Pendaftaran gagal. Silakan coba lagi.');
      setNoticeVariant('error');
    } finally {
      if (registerRequestAbortRef.current === controller) {
        registerRequestAbortRef.current = null;

        if (isMountedRef.current) {
          setIsSubmitting(false);
        }
      }
    }
  };

  return (
    <ScreenContainer
      header={<Navbar title="Daftar Akun Baru" onBackPress={() => navigation.goBack()} />}
      contentGap={0}
      contentStyle={s.content}
      backgroundColor={theme.background}
    >
      <View style={s.header}>
        <Text style={s.title}>Daftar Akun Baru</Text>
        <Text style={s.subtitle}>
          Bergabunglah dengan Exoinvite dan mulailah menciptakan momen spesial Anda.
        </Text>
      </View>

      <View style={s.form}>
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
            accessibilityLabel="Nama lengkap"
          />
        </View>

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
            accessibilityLabel="Alamat email"
          />
        </View>

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
              accessibilityLabel="Kata sandi"
            />
            <Pressable
              onPress={() => setIsPasswordVisible((v) => !v)}
              style={s.eyeBtn}
              hitSlop={8}
              accessibilityRole="button"
              accessibilityLabel={isPasswordVisible ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'}
            >
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
              accessibilityLabel="Konfirmasi kata sandi"
            />
            <Pressable
              onPress={() => setIsPasswordConfirmVisible((v) => !v)}
              style={s.eyeBtn}
              hitSlop={8}
              accessibilityRole="button"
              accessibilityLabel={isPasswordConfirmVisible ? 'Sembunyikan konfirmasi kata sandi' : 'Tampilkan konfirmasi kata sandi'}
            >
              <MaterialCommunityIcons
                name={isPasswordConfirmVisible ? 'eye-off-outline' : 'eye-outline'}
                size={20}
                color={theme.outline}
              />
            </Pressable>
          </View>
        </View>

        <Pressable
          onPress={() => setTermsAccepted((v) => !v)}
          style={s.termsRow}
          accessibilityRole="checkbox"
          accessibilityState={{ checked: termsAccepted }}
          accessibilityLabel="Setujui syarat dan kebijakan privasi"
        >
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

        <Pressable
          onPress={handleRegister}
          disabled={isSubmitting}
          accessibilityRole="button"
          accessibilityState={{ disabled: isSubmitting }}
          style={({ pressed }) => [s.primaryBtn, pressed && s.pressed, isSubmitting && s.disabled]}
        >
          <View style={s.primaryBtnOverlay} />
          <Text style={s.primaryBtnText}>{isSubmitting ? 'Mendaftar...' : 'Daftar'}</Text>
        </Pressable>
      </View>

      <View style={s.dividerRow}>
        <View style={s.dividerLine} />
        <Text style={s.dividerText}>ATAU DAFTAR DENGAN</Text>
        <View style={s.dividerLine} />
      </View>

      <View style={s.socialRow}>
        <Pressable
          onPress={() => handleSocialAuthPress('Google')}
          style={({ pressed }) => [s.socialBtn, s.googleBtn, pressed && s.pressed]}
          accessibilityRole="button"
          accessibilityLabel="Daftar dengan Google"
        >
          <MaterialCommunityIcons name="google" size={18} color="#EA4335" />
          <Text style={s.googleText}>Google</Text>
        </Pressable>
        <Pressable
          onPress={() => handleSocialAuthPress('Apple')}
          style={({ pressed }) => [s.socialBtn, s.appleBtn, pressed && s.pressed]}
          accessibilityRole="button"
          accessibilityLabel="Daftar dengan Apple"
        >
          <MaterialCommunityIcons name="apple" size={18} color={theme.appleText} />
          <Text style={s.appleText}>Apple</Text>
        </Pressable>
      </View>

      <View style={s.footer}>
        <Text style={s.footerText}>Sudah punya akun? </Text>
        <Pressable onPress={() => navigation.navigate('Login')} hitSlop={8} accessibilityRole="button">
          <Text style={s.footerLink}>Masuk</Text>
        </Pressable>
      </View>
    </ScreenContainer>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme'], isCompact: boolean) {
  return StyleSheet.create({
    content: {
      flex: 1,
    },

    header: {
      gap: 10,
      marginTop: isCompact ? 8 : 12,
      marginBottom: isCompact ? 32 : 38,
    },
    title: {
      color: t.onSurface,
      fontSize: isCompact ? 36 : 40,
      fontFamily: F.display,
      letterSpacing: -0.8,
      lineHeight: isCompact ? 44 : 50,
    },
    subtitle: {
      color: t.onSurfaceVariant,
      fontSize: isCompact ? 15 : 16,
      lineHeight: isCompact ? 23 : 25,
      fontFamily: F.body,
    },

    form: { gap: isCompact ? 18 : 20 },
    fieldGroup: { gap: 10 },
    label: {
      color: t.onSurfaceVariant,
      fontSize: 11,
      fontFamily: F.label,
      letterSpacing: 0.7,
      textTransform: 'uppercase',
      marginLeft: 2,
    },

    input: {
      backgroundColor: t.surfaceContainerHighest,
      borderRadius: 14,
      height: 56,
      paddingHorizontal: 18,
      color: t.onSurface,
      fontSize: isCompact ? 15 : 16,
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

    strengthRow: { flexDirection: 'row', alignItems: 'center', gap: 6, marginTop: 2 },
    strengthBar: {
      flex: 1,
      height: 4,
      borderRadius: 2,
      backgroundColor: t.isDark ? 'rgba(255,255,255,0.1)' : 'rgba(204,195,216,0.4)',
    },
    strengthLabel: { fontSize: 11, fontFamily: F.labelBold, width: 42, textAlign: 'right' },

    termsRow: { flexDirection: 'row', alignItems: 'flex-start', gap: 10, marginTop: -2, paddingHorizontal: 1 },
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
    termsText: { flex: 1, color: t.onSurfaceVariant, fontSize: 12, lineHeight: 18, fontFamily: F.body, marginTop: 1 },
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
      marginTop: -2,
    },
    successBox: { borderColor: t.successBg, backgroundColor: t.successBg },
    noticeText: { flex: 1, color: t.error, fontSize: 13, lineHeight: 19, fontFamily: F.body },
    successText: { color: t.successText },

    primaryBtn: {
      height: 56,
      borderRadius: 999,
      backgroundColor: t.primary,
      overflow: 'hidden',
      alignItems: 'center',
      justifyContent: 'center',
      shadowColor: t.primary,
      shadowOpacity: 0.24,
      shadowRadius: 18,
      shadowOffset: { width: 0, height: 9 },
      elevation: 6,
      marginTop: 6,
      borderWidth: 1,
      borderColor: t.primaryContainer,
    },
    primaryBtnOverlay: {
      position: 'absolute',
      top: 0,
      right: 0,
      bottom: 0,
      width: '62%',
      backgroundColor: t.primaryContainer,
      opacity: 0.45,
    },
    primaryBtnText: { color: '#FFFFFF', fontSize: isCompact ? 17 : 18, fontFamily: F.labelBold },

    dividerRow: { flexDirection: 'row', alignItems: 'center', gap: 12, marginTop: isCompact ? 34 : 40, marginBottom: 12 },
    dividerLine: { flex: 1, height: 1, backgroundColor: t.outlineVariant, opacity: 0.5 },
    dividerText: {
      color: t.outline,
      fontSize: 11,
      fontFamily: F.label,
      letterSpacing: 1.3,
      textTransform: 'uppercase',
    },

    socialRow: { flexDirection: 'row', gap: 12 },
    socialBtn: {
      flex: 1,
      height: 56,
      borderRadius: 14,
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 8,
    },
    googleBtn: {
      backgroundColor: t.surfaceContainerLow,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      opacity: t.isDark ? 0.95 : 1,
    },
    appleBtn: { backgroundColor: '#121212' },
    googleText: { color: t.googleText, fontSize: 14, fontFamily: F.labelBold },
    appleText: { color: t.appleText, fontSize: 14, fontFamily: F.labelBold },

    footer: {
      flexDirection: 'row',
      justifyContent: 'center',
      alignItems: 'center',
      marginTop: isCompact ? 34 : 44,
      paddingBottom: isCompact ? 8 : 12,
    },
    footerText: { color: t.onSurfaceVariant, fontSize: 14, fontFamily: F.body },
    footerLink: { color: t.primary, fontSize: 14, fontFamily: F.labelBold },

    pressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
    disabled: { opacity: 0.6 },
  });
}
