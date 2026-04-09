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
import { useAuth } from './AuthContext';

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
  successIcon: '#1A6B3C',
  infoBg: '#EDE0FF',
  infoBorder: '#CCC3D8',
} as const;

export function ConnectDeviceScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<GuestStackParamList>>();
  const { connectDevice } = useAuth();
  const { width } = useWindowDimensions();
  const isCompactLayout = width <= 390;
  const [accessCode, setAccessCode] = useState('');
  const [deviceAlias, setDeviceAlias] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [codeFocused, setCodeFocused] = useState(false);
  const [aliasFocused, setAliasFocused] = useState(false);

  const handleConnect = async () => {
    if (isSubmitting) {
      return;
    }

    const normalizedAccessCode = accessCode.trim().toUpperCase();
    const normalizedAlias = deviceAlias.trim();

    if (!normalizedAccessCode) {
      setError('Kode akses wajib diisi.');
      return;
    }

    try {
      setIsSubmitting(true);
      setError(null);

      await connectDevice({
        accessCode: normalizedAccessCode,
        deviceAlias: normalizedAlias,
      });
    } catch (connectError) {
      setError(
        connectError instanceof Error
          ? connectError.message
          : 'Gagal menghubungkan perangkat.'
      );
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <SafeAreaView style={styles.safeArea} edges={['top']}>
      <KeyboardAvoidingView
        style={styles.keyboardContainer}
        behavior={Platform.OS === 'ios' ? 'padding' : undefined}
      >
        <Navbar title="Hubungkan Perangkat" onBackPress={() => navigation.goBack()} />
        <ScrollView
          contentContainerStyle={[
            styles.scrollContent,
            isCompactLayout && styles.scrollContentCompact,
          ]}
          showsVerticalScrollIndicator={false}
        >
          <View style={[styles.heroSection, isCompactLayout && styles.heroSectionCompact]}>
            <Text style={styles.eyebrow}>Secure Pairing</Text>
            <Text style={[styles.title, isCompactLayout && styles.titleCompact]}>Hubungkan Perangkat</Text>
            <Text style={[styles.subtitle, isCompactLayout && styles.subtitleCompact]}>
              Masukkan kode akses dari dashboard web owner untuk aktivasi sesi mobile.
            </Text>
          </View>

          <View style={[styles.card, isCompactLayout && styles.cardCompact]}>
          <View style={styles.formGroup}>
            <Text style={[styles.label, isCompactLayout && styles.labelCompact]}>KODE AKSES</Text>
            <TextInput
              value={accessCode}
              onChangeText={(text) => {
                setAccessCode(text.toUpperCase());
                if (error) setError(null);
              }}
              placeholder="EXO-AB12CD34"
              placeholderTextColor={C.outline}
              style={styles.input}
              autoCapitalize="characters"
              autoCorrect={false}
              autoComplete="off"
              selectionColor={C.primary}
            />
            <Text style={[styles.hint, isCompactLayout && styles.hintCompact]}>Contoh format: EXO-AB12CD34</Text>
          </View>

          <View style={styles.formGroup}>
            <Text style={[styles.label, isCompactLayout && styles.labelCompact]}>NAMA PERANGKAT (OPSIONAL)</Text>
            <TextInput
              value={deviceAlias}
              onChangeText={setDeviceAlias}
              placeholder="Contoh: iPhone Nabila"
              placeholderTextColor={C.outline}
              style={styles.input}
              autoCapitalize="words"
              selectionColor={C.primary}
            />
          </View>

          <View style={styles.infoCard}>
            <View style={styles.infoRow}>
              <MaterialCommunityIcons name="check-circle-outline" size={16} color={C.successIcon} />
              <Text style={[styles.infoText, isCompactLayout && styles.infoTextCompact]}>
                Kode akses tersedia di dashboard web owner.
              </Text>
            </View>
            <View style={styles.infoRow}>
              <MaterialCommunityIcons name="check-circle-outline" size={16} color={C.successIcon} />
              <Text style={[styles.infoText, isCompactLayout && styles.infoTextCompact]}>
                Satu kode hanya untuk 1 perangkat aktif.
              </Text>
            </View>
            <View style={styles.infoRow}>
              <MaterialCommunityIcons name="check-circle-outline" size={16} color={C.successIcon} />
              <Text style={[styles.infoText, isCompactLayout && styles.infoTextCompact]}>
                Akses bisa dicabut kapan saja dari dashboard.
              </Text>
            </View>
          </View>

          {error ? (
            <View style={styles.errorBox}>
              <MaterialCommunityIcons name="alert-circle-outline" size={18} color={C.error} />
              <Text style={[styles.errorText, isCompactLayout && styles.errorTextCompact]}>{error}</Text>
            </View>
          ) : null}

          <Pressable
            onPress={handleConnect}
            style={({ pressed }) => [
              styles.button,
              pressed && styles.buttonPressed,
              isSubmitting && styles.buttonDisabled,
            ]}
            disabled={isSubmitting}
          >
            <MaterialCommunityIcons
              name={isSubmitting ? 'progress-clock' : 'link-variant'}
              size={18}
              color={C.onPrimary}
            />
            <Text style={[styles.buttonText, isCompactLayout && styles.buttonTextCompact]}>
              {isSubmitting ? 'Menghubungkan...' : 'Hubungkan Sekarang'}
            </Text>
          </Pressable>
        </View>
        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: C.background },
  keyboardContainer: { flex: 1 },

  scrollContent: {
    flexGrow: 1,
    paddingHorizontal: 24,
    paddingTop: 28,
    paddingBottom: 36,
    gap: 20,
  },
  scrollContentCompact: {
    paddingHorizontal: 18,
    paddingTop: 22,
    paddingBottom: 28,
    gap: 16,
  },

  heroSection: {
    gap: 10,
  },
  heroSectionCompact: { gap: 8 },
  eyebrow: {
    color: C.primary,
    fontSize: 10,
    fontFamily: F.labelBold,
    letterSpacing: 1.2,
    textTransform: 'uppercase',
  },
  title: {
    color: C.onSurface,
    fontSize: 28,
    fontFamily: F.display,
    letterSpacing: -0.4,
    lineHeight: 36,
  },
  titleCompact: { fontSize: 24, lineHeight: 32 },
  subtitle: {
    color: C.onSurfaceVariant,
    fontSize: 14,
    lineHeight: 22,
    fontFamily: F.body,
  },
  subtitleCompact: { fontSize: 13, lineHeight: 20 },

  card: {
    backgroundColor: '#FEEFFF',
    borderRadius: 20,
    padding: 18,
    gap: 16,
  },
  cardCompact: { borderRadius: 16, padding: 14, gap: 12 },

  formGroup: { gap: 8 },
  label: {
    color: C.onSurfaceVariant,
    fontSize: 10,
    fontFamily: F.labelBold,
    letterSpacing: 0.8,
    textTransform: 'uppercase',
  },
  labelCompact: { fontSize: 10 },

  inputShell: {},                   // kept for compatibility (not used)
  inputShellFocused: {},
  inputShellError: {},

  input: {
    backgroundColor: C.fieldBg,
    borderRadius: 14,
    paddingHorizontal: 18,
    paddingVertical: 16,
    color: C.onSurface,
    fontSize: 15,
    letterSpacing: 1.5,
    fontFamily: F.bodyMedium,
  },
  hint: { color: C.outline, fontSize: 11, marginTop: -2, fontFamily: F.body },
  hintCompact: { fontSize: 10 },

  infoCard: {
    borderRadius: 14,
    borderWidth: 1,
    borderColor: C.infoBorder,
    backgroundColor: C.infoBg,
    padding: 14,
    gap: 10,
  },
  infoRow: { flexDirection: 'row', alignItems: 'flex-start', gap: 8 },
  infoText: { flex: 1, color: C.onSurfaceVariant, fontSize: 12, lineHeight: 18, fontFamily: F.body },
  infoTextCompact: { fontSize: 11, lineHeight: 16 },

  errorBox: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    gap: 8,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: C.errorContainer,
    backgroundColor: C.errorContainer,
    padding: 12,
  },
  errorText: { flex: 1, color: C.error, fontSize: 13, lineHeight: 19, fontFamily: F.body },
  errorTextCompact: { fontSize: 12, lineHeight: 17 },

  button: {
    height: 56,
    borderRadius: 999,
    backgroundColor: C.primary,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 8,
    shadowColor: C.primary,
    shadowOpacity: 0.28,
    shadowRadius: 16,
    shadowOffset: { width: 0, height: 8 },
    elevation: 6,
    marginTop: 4,
  },
  buttonPressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
  buttonDisabled: { opacity: 0.6 },
  buttonText: { color: C.onPrimary, fontSize: 16, fontFamily: F.labelBold },
  buttonTextCompact: { fontSize: 15 },
});
