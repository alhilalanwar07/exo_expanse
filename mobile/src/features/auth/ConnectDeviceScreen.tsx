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
import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import type { GuestStackParamList } from '../../navigation/types';
import { Navbar } from '../../shared/components/Navbar';
import { F } from '../../shared/theme/fonts';
import { useAppTheme } from '../../shared/theme/index';
import { useAuth } from './AuthContext';

export function ConnectDeviceScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<GuestStackParamList>>();
  const { connectDevice } = useAuth();
  const { theme } = useAppTheme();
  const { width } = useWindowDimensions();
  const isCompact = width <= 390;
  const s = makeStyles(theme, isCompact);

  const [accessCode, setAccessCode] = useState('');
  const [deviceAlias, setDeviceAlias] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleConnect = async () => {
    if (isSubmitting) return;
    const normalizedAccessCode = accessCode.trim().toUpperCase();
    const normalizedAlias = deviceAlias.trim();

    if (!normalizedAccessCode) { setError('Kode akses wajib diisi.'); return; }

    try {
      setIsSubmitting(true);
      setError(null);
      await connectDevice({ accessCode: normalizedAccessCode, deviceAlias: normalizedAlias });
    } catch (connectError) {
      setError(
        connectError instanceof Error
          ? connectError.message
          : 'Gagal menghubungkan perangkat.',
      );
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <SafeAreaView style={s.safeArea} edges={['top']}>
      <KeyboardAvoidingView style={s.keyboardContainer} behavior={Platform.OS === 'ios' ? 'padding' : undefined}>
        <Navbar title="Hubungkan Perangkat" onBackPress={() => navigation.goBack()} />
        <ScrollView
          contentContainerStyle={s.scrollContent}
          showsVerticalScrollIndicator={false}
        >
          {/* Hero */}
          <View style={s.heroSection}>
            <Text style={s.eyebrow}>Secure Pairing</Text>
            <Text style={s.title}>Hubungkan Perangkat</Text>
            <Text style={s.subtitle}>
              Masukkan kode akses dari dashboard web owner untuk aktivasi sesi mobile.
            </Text>
          </View>

          {/* Card */}
          <View style={s.card}>
            <View style={s.formGroup}>
              <Text style={s.label}>KODE AKSES</Text>
              <TextInput
                value={accessCode}
                onChangeText={(text) => { setAccessCode(text.toUpperCase()); if (error) setError(null); }}
                placeholder="EXO-AB12CD34"
                placeholderTextColor={theme.outline}
                style={s.input}
                autoCapitalize="characters"
                autoCorrect={false}
                autoComplete="off"
                selectionColor={theme.primary}
              />
              <Text style={s.hint}>Contoh format: EXO-AB12CD34</Text>
            </View>

            <View style={s.formGroup}>
              <Text style={s.label}>NAMA PERANGKAT (OPSIONAL)</Text>
              <TextInput
                value={deviceAlias}
                onChangeText={setDeviceAlias}
                placeholder="Contoh: iPhone Nabila"
                placeholderTextColor={theme.outline}
                style={s.input}
                autoCapitalize="words"
                selectionColor={theme.primary}
              />
            </View>

            {/* Info */}
            <View style={s.infoCard}>
              {[
                'Kode akses tersedia di dashboard web owner.',
                'Satu kode hanya untuk 1 perangkat aktif.',
                'Akses bisa dicabut kapan saja dari dashboard.',
              ].map((text) => (
                <View key={text} style={s.infoRow}>
                  <MaterialCommunityIcons name="check-circle-outline" size={16} color={theme.successIcon} />
                  <Text style={s.infoText}>{text}</Text>
                </View>
              ))}
            </View>

            {/* Error */}
            {error ? (
              <View style={s.errorBox}>
                <MaterialCommunityIcons name="alert-circle-outline" size={18} color={theme.error} />
                <Text style={s.errorText}>{error}</Text>
              </View>
            ) : null}

            {/* CTA */}
            <Pressable
              onPress={handleConnect}
              style={({ pressed }) => [s.button, pressed && s.buttonPressed, isSubmitting && s.buttonDisabled]}
              disabled={isSubmitting}
            >
              <MaterialCommunityIcons
                name={isSubmitting ? 'progress-clock' : 'link-variant'}
                size={18}
                color="#FFFFFF"
              />
              <Text style={s.buttonText}>
                {isSubmitting ? 'Menghubungkan...' : 'Hubungkan Sekarang'}
              </Text>
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
    keyboardContainer: { flex: 1 },

    scrollContent: {
      flexGrow: 1,
      paddingHorizontal: isCompact ? 18 : 24,
      paddingTop: isCompact ? 22 : 28,
      paddingBottom: isCompact ? 28 : 36,
      gap: isCompact ? 16 : 20,
    },

    heroSection: { gap: isCompact ? 8 : 10 },
    eyebrow: {
      color: t.primary,
      fontSize: 10,
      fontFamily: F.labelBold,
      letterSpacing: 1.2,
      textTransform: 'uppercase',
    },
    title: {
      color: t.onSurface,
      fontSize: isCompact ? 24 : 28,
      fontFamily: F.display,
      letterSpacing: -0.4,
      lineHeight: isCompact ? 32 : 36,
    },
    subtitle: {
      color: t.onSurfaceVariant,
      fontSize: isCompact ? 13 : 14,
      lineHeight: isCompact ? 20 : 22,
      fontFamily: F.body,
    },

    card: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: isCompact ? 16 : 20,
      padding: isCompact ? 14 : 18,
      gap: isCompact ? 12 : 16,
    },

    formGroup: { gap: 8 },
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
      fontSize: 15,
      letterSpacing: 1.5,
      fontFamily: F.bodyMedium,
    },
    hint: {
      color: t.outline,
      fontSize: isCompact ? 10 : 11,
      marginTop: -2,
      fontFamily: F.body,
    },

    infoCard: {
      borderRadius: 14,
      borderWidth: 1,
      borderColor: t.infoBorder,
      backgroundColor: t.infoBg,
      padding: 14,
      gap: 10,
    },
    infoRow: { flexDirection: 'row', alignItems: 'flex-start', gap: 8 },
    infoText: {
      flex: 1,
      color: t.onSurfaceVariant,
      fontSize: isCompact ? 11 : 12,
      lineHeight: isCompact ? 16 : 18,
      fontFamily: F.body,
    },

    errorBox: {
      flexDirection: 'row',
      alignItems: 'flex-start',
      gap: 8,
      borderRadius: 12,
      borderWidth: 1,
      borderColor: t.errorContainer,
      backgroundColor: t.errorContainer,
      padding: 12,
    },
    errorText: {
      flex: 1,
      color: t.error,
      fontSize: isCompact ? 12 : 13,
      lineHeight: isCompact ? 17 : 19,
      fontFamily: F.body,
    },

    button: {
      height: 56,
      borderRadius: 999,
      backgroundColor: t.primary,
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 8,
      shadowColor: t.primary,
      shadowOpacity: 0.28,
      shadowRadius: 16,
      shadowOffset: { width: 0, height: 8 },
      elevation: 6,
      marginTop: 4,
    },
    buttonPressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
    buttonDisabled: { opacity: 0.6 },
    buttonText: { color: '#FFFFFF', fontSize: isCompact ? 15 : 16, fontFamily: F.labelBold },
  });
}
