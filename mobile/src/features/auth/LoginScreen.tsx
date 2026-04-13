import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { useEffect, useRef, useState } from 'react';
import {
  Pressable as RNPressable,
  StyleSheet,
  useWindowDimensions,
} from 'react-native';

import { Navbar } from '../../shared/components/Navbar';
import { ScreenContainer, SCREEN_CONTAINER_LAYOUT } from '../../shared/components/ScreenContainer';
import { F } from '../../shared/theme/fonts';
import { useAppTheme } from '../../shared/theme/index';
import {
  Pressable as TwPressable,
  Text,
  TextInput,
  View,
} from '../../tw';
import { useAuth } from './AuthContext';
import type { RootStackParamList } from '../../navigation/types';

export function LoginScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<RootStackParamList>>();
  const { loginWithPassword } = useAuth();
  const { theme } = useAppTheme();
  const { width } = useWindowDimensions();
  const isCompact = width <= SCREEN_CONTAINER_LAYOUT.compactBreakpoint;
  const s = makeStyles(theme, isCompact);

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [notice, setNotice] = useState<string | null>(null);
  const [isPasswordVisible, setIsPasswordVisible] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const loginRequestAbortRef = useRef<AbortController | null>(null);
  const isMountedRef = useRef(true);

  useEffect(() => {
    return () => {
      isMountedRef.current = false;
      loginRequestAbortRef.current?.abort();
      loginRequestAbortRef.current = null;
    };
  }, []);

  const handleLogin = async () => {
    const e = email.trim().toLowerCase();
    const p = password.trim();

    if (!e || !p) { setNotice('Email dan password wajib diisi.'); return; }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e)) { setNotice('Format email tidak valid.'); return; }
    if (p.length < 8) { setNotice('Password minimal 8 karakter.'); return; }

    const controller = new AbortController();
    loginRequestAbortRef.current?.abort();
    loginRequestAbortRef.current = controller;

    try {
      setIsSubmitting(true);
      setNotice(null);
      await loginWithPassword({ email: e, password: p, signal: controller.signal });

      if (loginRequestAbortRef.current !== controller || controller.signal.aborted) {
        return;
      }

      navigation.replace('Main');
    } catch (err) {
      if (controller.signal.aborted || loginRequestAbortRef.current !== controller) {
        return;
      }

      setNotice(err instanceof Error ? err.message : 'Login gagal. Silakan coba lagi.');
    } finally {
      if (loginRequestAbortRef.current === controller) {
        loginRequestAbortRef.current = null;

        if (isMountedRef.current) {
          setIsSubmitting(false);
        }
      }
    }
  };

  return (
    <ScreenContainer
      header={<Navbar title="Exoinvite" onBackPress={() => navigation.goBack()} />}
      contentGap={0}
      contentStyle={s.content}
      backgroundColor={theme.background}
    >
      <View style={s.header} className="w-full">
        <Text style={s.title}>Masuk ke Akun</Text>
        <Text style={s.subtitle}>
          Silakan masuk untuk melanjutkan rencana perayaan Anda.
        </Text>
      </View>

      <View style={s.form} className="w-full">
        <View style={s.fieldGroup} className="w-full">
          <Text style={s.label}>EMAIL ADDRESS</Text>
          <TextInput
            value={email}
            onChangeText={setEmail}
            placeholder="nama@email.com"
            placeholderTextColor={theme.outline}
            className="h-14 rounded-2xl px-4"
            style={s.input}
            keyboardType="email-address"
            autoCapitalize="none"
            autoCorrect={false}
            autoComplete="email"
            textContentType="emailAddress"
            returnKeyType="next"
            selectionColor={theme.primary}
            accessibilityLabel="Email address"
          />
        </View>

        <View style={s.fieldGroup} className="w-full">
          <Text style={s.label}>PASSWORD</Text>
          <View style={s.passwordWrap} className="relative">
            <TextInput
              value={password}
              onChangeText={setPassword}
              placeholder="••••••••"
              placeholderTextColor={theme.outline}
              className="h-14 rounded-2xl px-4"
              style={[s.input, s.inputPassword]}
              secureTextEntry={!isPasswordVisible}
              autoComplete="password"
              textContentType="password"
              returnKeyType="done"
              selectionColor={theme.primary}
              onSubmitEditing={handleLogin}
              accessibilityLabel="Password"
            />
            <TwPressable
              onPress={() => setIsPasswordVisible((v) => !v)}
              className="absolute right-3 top-0 bottom-0 items-center justify-center"
              style={s.eyeBtn}
              hitSlop={8}
              accessibilityRole="button"
              accessibilityLabel={isPasswordVisible ? 'Sembunyikan password' : 'Tampilkan password'}
            >
              <MaterialCommunityIcons
                name={isPasswordVisible ? 'eye-off-outline' : 'eye-outline'}
                size={20}
                color={theme.outline}
              />
            </TwPressable>
          </View>
          <TwPressable
            onPress={() => console.log('Forgot')}
            className="self-end"
            style={s.forgotWrap}
            hitSlop={8}
            accessibilityRole="button"
          >
            <Text style={s.forgotText}>Lupa Password?</Text>
          </TwPressable>
        </View>

        {notice ? (
          <View style={s.errorBox} className="flex-row">
            <MaterialCommunityIcons name="alert-circle-outline" size={16} color={theme.error} />
            <Text style={s.errorText}>{notice}</Text>
          </View>
        ) : null}

        <RNPressable
          onPress={handleLogin}
          disabled={isSubmitting}
          accessibilityRole="button"
          accessibilityState={{ disabled: isSubmitting }}
          style={({ pressed }) => [s.primaryBtn, pressed && s.pressed, isSubmitting && s.disabled]}
        >
          <View style={s.primaryBtnOverlay} />
          <Text style={s.primaryBtnText}>{isSubmitting ? 'Memproses...' : 'Masuk'}</Text>
        </RNPressable>

        <View style={s.dividerRow} className="flex-row items-center">
          <View style={s.dividerLine} className="flex-1" />
          <Text style={s.dividerText}>ATAU MASUK DENGAN</Text>
          <View style={s.dividerLine} className="flex-1" />
        </View>

        <View style={s.socialRow} className="flex-row">
          <RNPressable
            onPress={() => console.log('Google')}
            style={({ pressed }) => [s.socialBtn, s.googleBtn, pressed && s.pressed]}
            accessibilityRole="button"
            accessibilityLabel="Masuk dengan Google"
          >
            <MaterialCommunityIcons name="google" size={18} color="#EA4335" />
            <Text style={s.googleText}>Google</Text>
          </RNPressable>
          <RNPressable
            onPress={() => console.log('Apple')}
            style={({ pressed }) => [s.socialBtn, s.appleBtn, pressed && s.pressed]}
            accessibilityRole="button"
            accessibilityLabel="Masuk dengan Apple"
          >
            <MaterialCommunityIcons name="apple" size={18} color={theme.appleText} />
            <Text style={s.appleText}>Apple</Text>
          </RNPressable>
        </View>
      </View>

      <View style={s.footer} className="mt-auto flex-row items-center justify-center">
        <Text style={s.footerText}>Belum punya akun? </Text>
        <TwPressable onPress={() => navigation.navigate('Register')} hitSlop={8} accessibilityRole="button">
          <Text style={s.footerLink}>Daftar sekarang</Text>
        </TwPressable>
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
      marginBottom: isCompact ? 34 : 40,
    },
    title: {
      color: t.onSurface,
      fontSize: isCompact ? 36 : 40,
      fontFamily: F.display,
      letterSpacing: -0.9,
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
      color: t.onSurface,
      fontSize: isCompact ? 15 : 16,
      fontFamily: F.body,
    },
    inputPassword: { paddingRight: 48 },

    passwordWrap: {},
    eyeBtn: {
      width: 36,
    },

    forgotWrap: { alignSelf: 'flex-end', marginTop: 2 },
    forgotText: { color: t.primary, fontSize: isCompact ? 14 : 15, fontFamily: F.labelBold },

    errorBox: {
      alignItems: 'flex-start',
      gap: 8,
      borderRadius: 12,
      borderWidth: 1,
      borderColor: t.errorContainer,
      backgroundColor: t.errorContainer,
      padding: 12,
      marginTop: -3,
    },
    errorText: { flex: 1, color: t.error, fontSize: 13, lineHeight: 19, fontFamily: F.body, marginTop: -1 },

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
    primaryBtnText: {
      color: '#FFFFFF',
      fontSize: isCompact ? 17 : 18,
      fontFamily: F.labelBold,
    },

    dividerRow: { gap: 12, marginTop: 2 },
    dividerLine: { height: 1, backgroundColor: t.outlineVariant, opacity: 0.5 },
    dividerText: {
      color: t.outline,
      fontSize: 11,
      fontFamily: F.label,
      letterSpacing: 1.3,
      textTransform: 'uppercase',
    },

    socialRow: { gap: 12 },
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
      paddingTop: isCompact ? 52 : 64,
      paddingBottom: isCompact ? 8 : 12,
    },
    footerText: { color: t.onSurfaceVariant, fontSize: 14, fontFamily: F.body },
    footerLink: { color: t.primary, fontSize: 14, fontFamily: F.labelBold },

    pressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
    disabled: { opacity: 0.6 },
  });
}
