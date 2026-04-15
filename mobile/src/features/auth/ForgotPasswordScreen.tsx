import { useNavigation, useRoute } from '@react-navigation/native';
import type { RouteProp } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { useEffect, useRef, useState } from 'react';
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

type NoticeVariant = 'error' | 'success';

export function ForgotPasswordScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<RootStackParamList>>();
  const route = useRoute<RouteProp<RootStackParamList, 'ForgotPassword'>>();
  const { requestPasswordReset } = useAuth();
  const { theme } = useAppTheme();
  const { width } = useWindowDimensions();
  const isCompact = width <= SCREEN_CONTAINER_LAYOUT.compactBreakpoint;
  const s = makeStyles(theme, isCompact);

  const [email, setEmail] = useState(route.params?.email?.trim().toLowerCase() ?? '');
  const [notice, setNotice] = useState<string | null>(null);
  const [noticeVariant, setNoticeVariant] = useState<NoticeVariant>('error');
  const [isSubmitting, setIsSubmitting] = useState(false);

  const requestAbortRef = useRef<AbortController | null>(null);
  const isMountedRef = useRef(true);

  useEffect(() => {
    return () => {
      isMountedRef.current = false;
      requestAbortRef.current?.abort();
      requestAbortRef.current = null;
    };
  }, []);

  useEffect(() => {
    const incomingEmail = route.params?.email?.trim().toLowerCase();

    if (!incomingEmail) {
      return;
    }

    setEmail((currentEmail) => (currentEmail.trim() ? currentEmail : incomingEmail));
  }, [route.params?.email]);

  const handleSubmit = async () => {
    const normalizedEmail = email.trim().toLowerCase();

    if (!normalizedEmail) {
      setNotice('Email wajib diisi.');
      setNoticeVariant('error');
      return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(normalizedEmail)) {
      setNotice('Format email tidak valid.');
      setNoticeVariant('error');
      return;
    }

    const controller = new AbortController();
    requestAbortRef.current?.abort();
    requestAbortRef.current = controller;

    try {
      setIsSubmitting(true);
      setNotice(null);

      const result = await requestPasswordReset({
        email: normalizedEmail,
        signal: controller.signal,
      });

      if (requestAbortRef.current !== controller || controller.signal.aborted) {
        return;
      }

      setNotice(result.message);
      setNoticeVariant('success');

      Alert.alert('Permintaan Terkirim', result.message);
    } catch (error) {
      if (requestAbortRef.current !== controller || controller.signal.aborted) {
        return;
      }

      setNotice(error instanceof Error ? error.message : 'Gagal mengirim permintaan reset password.');
      setNoticeVariant('error');
    } finally {
      if (requestAbortRef.current === controller) {
        requestAbortRef.current = null;

        if (isMountedRef.current) {
          setIsSubmitting(false);
        }
      }
    }
  };

  return (
    <ScreenContainer
      header={<Navbar title="Reset Password" onBackPress={() => navigation.goBack()} />}
      contentGap={0}
      contentStyle={s.content}
      backgroundColor={theme.background}
    >
      <View style={s.header}>
        <Text style={s.title}>Lupa Password?</Text>
        <Text style={s.subtitle}>
          Masukkan email akun Anda. Jika terdaftar, kami akan kirim tautan untuk reset password.
        </Text>
      </View>

      <View style={s.form}>
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
            autoCorrect={false}
            autoComplete="email"
            textContentType="emailAddress"
            returnKeyType="done"
            selectionColor={theme.primary}
            onSubmitEditing={handleSubmit}
            accessibilityLabel="Alamat email"
          />
        </View>

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
          onPress={handleSubmit}
          disabled={isSubmitting}
          accessibilityRole="button"
          accessibilityState={{ disabled: isSubmitting }}
          style={({ pressed }) => [s.primaryBtn, pressed && s.pressed, isSubmitting && s.disabled]}
        >
          <View style={s.primaryBtnOverlay} />
          <Text style={s.primaryBtnText}>{isSubmitting ? 'Mengirim...' : 'Kirim Link Reset'}</Text>
        </Pressable>
      </View>

      <View style={s.footer}>
        <Text style={s.footerText}>Sudah ingat password? </Text>
        <Pressable onPress={() => navigation.navigate('Login')} hitSlop={8} accessibilityRole="button">
          <Text style={s.footerLink}>Kembali ke Login</Text>
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
      borderRadius: 14,
      height: 56,
      paddingHorizontal: 18,
      color: t.onSurface,
      fontSize: isCompact ? 15 : 16,
      fontFamily: F.body,
    },

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
      marginTop: 4,
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

    footer: {
      flexDirection: 'row',
      justifyContent: 'center',
      alignItems: 'center',
      marginTop: isCompact ? 32 : 44,
      paddingBottom: isCompact ? 8 : 12,
    },
    footerText: { color: t.onSurfaceVariant, fontSize: 14, fontFamily: F.body },
    footerLink: { color: t.primary, fontSize: 14, fontFamily: F.labelBold },

    pressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
    disabled: { opacity: 0.6 },
  });
}
