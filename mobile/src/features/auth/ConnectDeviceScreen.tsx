import { MaterialCommunityIcons } from '@expo/vector-icons';
import { useEffect, useState } from 'react';
import {
  Pressable,
  StyleSheet,
  Text,
  TextInput,
  useWindowDimensions,
  View,
} from 'react-native';
import { useNavigation, useRoute } from '@react-navigation/native';
import type { RouteProp } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import type { AuthFlowParamList } from '../../navigation/types';
import { Navbar } from '../../shared/components/Navbar';
import { ScreenContainer, SCREEN_CONTAINER_LAYOUT } from '../../shared/components/ScreenContainer';
import { F } from '../../shared/theme/fonts';
import { useAppTheme } from '../../shared/theme/index';
import DeviceInfoHelper, { type DeviceInfoSnapshot } from '../../../modules/device-info-helper';
import { useAuth } from './AuthContext';

export function ConnectDeviceScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<AuthFlowParamList>>();
  const route = useRoute<RouteProp<AuthFlowParamList, 'ConnectDevice'>>();
  const { connectDevice } = useAuth();
  const { theme } = useAppTheme();
  const { width } = useWindowDimensions();
  const isCompact = width <= SCREEN_CONTAINER_LAYOUT.compactBreakpoint;
  const s = makeStyles(theme, isCompact);

  const [accessCode, setAccessCode] = useState(route.params?.code?.trim().toUpperCase() ?? '');
  const [deviceAlias, setDeviceAlias] = useState('');
  const [deviceInfo, setDeviceInfo] = useState<DeviceInfoSnapshot | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    try {
      const info = DeviceInfoHelper.getDeviceInfo();
      setDeviceInfo(info);
      setDeviceAlias((previousValue) => (previousValue.trim() ? previousValue : info.suggestedAlias));
    } catch {
      // The module fallback handles most cases; silently continue when unavailable.
    }
  }, []);

  useEffect(() => {
    const deepLinkCode = route.params?.code?.trim().toUpperCase();

    if (!deepLinkCode) {
      return;
    }

    setAccessCode((previousValue) => (previousValue.trim() ? previousValue : deepLinkCode));
  }, [route.params?.code]);

  const handleConnect = async () => {
    if (isSubmitting) return;
    const normalizedAccessCode = accessCode.trim().toUpperCase();
    const normalizedAlias = deviceAlias.trim() || deviceInfo?.suggestedAlias?.trim() || '';

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
    <ScreenContainer
      header={<Navbar title="Hubungkan Perangkat" onBackPress={() => navigation.goBack()} />}
      contentGap={0}
      contentStyle={s.content}
      backgroundColor={theme.background}
    >
      <View style={s.heroSection}>
        <Text style={s.eyebrow}>SECURE PAIRING</Text>
        <Text style={s.title}>Hubungkan Perangkat</Text>
        <Text style={s.subtitle}>
          Masukkan kode akses dari dashboard web owner untuk aktivasi sesi mobile.
        </Text>
      </View>

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
            accessibilityLabel="Kode akses"
          />
          <Text style={s.hint}>Contoh format: EXO-AB12CD34</Text>
        </View>

        <View style={s.formGroup}>
          <Text style={s.label}>NAMA PERANGKAT (OPSIONAL)</Text>
          <TextInput
            value={deviceAlias}
            onChangeText={setDeviceAlias}
            placeholder={deviceInfo?.suggestedAlias ?? 'Contoh: iPhone Nabila'}
            placeholderTextColor={theme.outline}
            style={s.input}
            autoCapitalize="words"
            selectionColor={theme.primary}
            accessibilityLabel="Nama perangkat"
          />

          {deviceInfo ? (
            <View style={s.deviceMetaCard}>
              <MaterialCommunityIcons name="cellphone-cog" size={16} color={theme.primary} />
              <View style={s.deviceMetaTextWrap}>
                <Text style={s.deviceMetaTitle}>Perangkat saat ini</Text>
                <Text style={s.deviceMetaText}>
                  {deviceInfo.brand} {deviceInfo.model} • {deviceInfo.osName} {deviceInfo.osVersion}
                </Text>
              </View>
            </View>
          ) : null}
        </View>

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

        {error ? (
          <View style={s.errorBox}>
            <MaterialCommunityIcons name="alert-circle-outline" size={18} color={theme.error} />
            <Text style={s.errorText}>{error}</Text>
          </View>
        ) : null}

        <Pressable
          onPress={handleConnect}
          style={({ pressed }) => [s.button, pressed && s.buttonPressed, isSubmitting && s.buttonDisabled]}
          disabled={isSubmitting}
          accessibilityRole="button"
          accessibilityState={{ disabled: isSubmitting }}
          accessibilityLabel="Hubungkan perangkat"
        >
          <View style={s.buttonOverlay} />
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
    </ScreenContainer>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme'], isCompact: boolean) {
  return StyleSheet.create({
    content: {
      flex: 1,
    },

    heroSection: {
      gap: 10,
      marginTop: isCompact ? 8 : 12,
      marginBottom: isCompact ? 24 : 28,
    },
    eyebrow: {
      color: t.primary,
      fontSize: 11,
      fontFamily: F.label,
      letterSpacing: 0.7,
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

    card: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: isCompact ? 16 : 20,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      padding: isCompact ? 14 : 18,
      gap: isCompact ? 14 : 16,
    },

    formGroup: { gap: 10 },
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
    hint: {
      color: t.outline,
      fontSize: 11,
      marginTop: -2,
      fontFamily: F.body,
    },
    deviceMetaCard: {
      marginTop: 2,
      borderRadius: 12,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      backgroundColor: t.surfaceContainerHighest,
      paddingHorizontal: 10,
      paddingVertical: 9,
      flexDirection: 'row',
      alignItems: 'flex-start',
      gap: 8,
    },
    deviceMetaTextWrap: {
      flex: 1,
      gap: 2,
    },
    deviceMetaTitle: {
      color: t.onSurface,
      fontSize: 11,
      fontFamily: F.label,
      letterSpacing: 0.4,
    },
    deviceMetaText: {
      color: t.onSurfaceVariant,
      fontSize: 12,
      lineHeight: 17,
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
      fontSize: 12,
      lineHeight: 18,
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
      fontSize: 13,
      lineHeight: 19,
      fontFamily: F.body,
    },

    button: {
      height: 56,
      borderRadius: 999,
      backgroundColor: t.primary,
      overflow: 'hidden',
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 8,
      shadowColor: t.primary,
      shadowOpacity: 0.24,
      shadowRadius: 18,
      shadowOffset: { width: 0, height: 9 },
      elevation: 6,
      marginTop: 4,
      borderWidth: 1,
      borderColor: t.primaryContainer,
    },
    buttonOverlay: {
      position: 'absolute',
      top: 0,
      right: 0,
      bottom: 0,
      width: '62%',
      backgroundColor: t.primaryContainer,
      opacity: 0.45,
    },
    buttonPressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
    buttonDisabled: { opacity: 0.6 },
    buttonText: { color: '#FFFFFF', fontSize: isCompact ? 17 : 18, fontFamily: F.labelBold },
  });
}
