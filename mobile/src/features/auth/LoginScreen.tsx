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
import { useAppTheme } from '../../shared/theme/index';
import { useAuth } from './AuthContext';
import type { RootStackParamList } from '../../navigation/RootNavigator';

export function LoginScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<RootStackParamList>>();
  const { loginWithPassword } = useAuth();
  const { theme } = useAppTheme();
  const { width } = useWindowDimensions();
  const isCompact = width <= 390;
  const s = makeStyles(theme, isCompact);

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
      navigation.replace('Main');
    } catch (err) {
      setNotice(err instanceof Error ? err.message : 'Login gagal. Silakan coba lagi.');
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <SafeAreaView style={s.safeArea} edges={['top']}>
      <Navbar title="EXOINVITE" onBackPress={() => navigation.goBack()} />
      <KeyboardAvoidingView style={s.flex} behavior={Platform.OS === 'ios' ? 'padding' : undefined}>
        <ScrollView
          contentContainerStyle={s.scroll}
          keyboardShouldPersistTaps="handled"
          showsVerticalScrollIndicator={false}
        >
          {/* Header */}
          <View style={s.header}>
            <Text style={s.title}>Masuk ke Akun</Text>
            <Text style={s.subtitle}>
              Silakan masuk untuk melanjutkan rencana perayaan Anda.
            </Text>
          </View>

          {/* Form */}
          <View style={s.form}>
            {/* Email */}
            <View style={s.fieldGroup}>
              <Text style={s.label}>EMAIL ADDRESS</Text>
              <TextInput
                value={email}
                onChangeText={setEmail}
                placeholder="nama@email.com"
                placeholderTextColor={theme.outline}
                style={s.input}
                keyboardType="email-address"
                autoCapitalize="none"
                autoCorrect={false}
                autoComplete="email"
                textContentType="emailAddress"
                returnKeyType="next"
                selectionColor={theme.primary}
              />
            </View>

            {/* Password */}
            <View style={s.fieldGroup}>
              <Text style={s.label}>PASSWORD</Text>
              <View style={s.passwordWrap}>
                <TextInput
                  value={password}
                  onChangeText={setPassword}
                  placeholder="••••••••"
                  placeholderTextColor={theme.outline}
                  style={[s.input, s.inputPassword]}
                  secureTextEntry={!isPasswordVisible}
                  autoComplete="password"
                  textContentType="password"
                  returnKeyType="done"
                  selectionColor={theme.primary}
                  onSubmitEditing={handleLogin}
                />
                <Pressable
                  onPress={() => setIsPasswordVisible((v) => !v)}
                  style={s.eyeBtn}
                  hitSlop={8}
                >
                  <MaterialCommunityIcons
                    name={isPasswordVisible ? 'eye-off-outline' : 'eye-outline'}
                    size={20}
                    color={theme.outline}
                  />
                </Pressable>
              </View>
              <Pressable onPress={() => console.log('Forgot')} style={s.forgotWrap} hitSlop={8}>
                <Text style={s.forgotText}>Lupa Password?</Text>
              </Pressable>
            </View>

            {/* Error */}
            {notice ? (
              <View style={s.errorBox}>
                <MaterialCommunityIcons name="alert-circle-outline" size={16} color={theme.error} />
                <Text style={s.errorText}>{notice}</Text>
              </View>
            ) : null}

            {/* CTA */}
            <Pressable
              onPress={handleLogin}
              disabled={isSubmitting}
              style={({ pressed }) => [s.primaryBtn, pressed && s.pressed, isSubmitting && s.disabled]}
            >
              <Text style={s.primaryBtnText}>{isSubmitting ? 'Memproses...' : 'Masuk'}</Text>
            </Pressable>

            {/* Divider */}
            <View style={s.dividerRow}>
              <View style={s.dividerLine} />
              <Text style={s.dividerText}>ATAU MASUK DENGAN</Text>
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
            <Text style={s.footerText}>Belum punya akun? </Text>
            <Pressable onPress={() => navigation.navigate('Register')} hitSlop={8}>
              <Text style={s.footerLink}>Daftar sekarang</Text>
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
      paddingTop: isCompact ? 24 : 32,
      paddingBottom: isCompact ? 28 : 36,
    },

    header: { gap: isCompact ? 8 : 10, marginBottom: isCompact ? 28 : 36 },
    title: {
      color: t.onSurface,
      fontSize: isCompact ? 26 : 32,
      fontFamily: F.display,
      letterSpacing: -0.5,
      lineHeight: isCompact ? 34 : 40,
    },
    subtitle: {
      color: t.onSurfaceVariant,
      fontSize: isCompact ? 14 : 15,
      lineHeight: isCompact ? 21 : 23,
      fontFamily: F.body,
    },

    form: { gap: isCompact ? 16 : 20 },
    fieldGroup: { gap: 8 },
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

    forgotWrap: { alignSelf: 'flex-end', marginTop: 4 },
    forgotText: { color: t.primary, fontSize: isCompact ? 13 : 14, fontFamily: F.label },

    errorBox: {
      flexDirection: 'row',
      alignItems: 'flex-start',
      gap: 8,
      borderRadius: 12,
      borderWidth: 1,
      borderColor: t.errorContainer,
      backgroundColor: t.errorContainer,
      padding: 12,
      marginTop: -4,
    },
    errorText: { flex: 1, color: t.error, fontSize: 13, lineHeight: 19, fontFamily: F.body },

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
    primaryBtnText: {
      color: '#FFFFFF',
      fontSize: isCompact ? 15 : 17,
      fontFamily: F.labelBold,
    },

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
      marginTop: isCompact ? 24 : 32,
    },
    footerText: { color: t.onSurfaceVariant, fontSize: 14, fontFamily: F.body },
    footerLink: { color: t.secondary, fontSize: 14, fontFamily: F.labelBold },

    pressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
    disabled: { opacity: 0.6 },
  });
}
