import { useState } from 'react';
import { Pressable, StyleSheet, Text, TextInput, View } from 'react-native';

import { ScreenContainer } from '../../shared/components/ScreenContainer';
import { colors } from '../../shared/theme/colors';
import { useAuth } from './AuthContext';

export function ConnectDeviceScreen() {
  const { connectDevice } = useAuth();
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

    if (!accessCode.trim()) {
      setError('Kode akses wajib diisi.');
      return;
    }

    try {
      setIsSubmitting(true);
      setError(null);

      await connectDevice({
        accessCode,
        deviceAlias,
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
    <ScreenContainer>
      <View style={styles.wrapper}>
        <View style={styles.card}>
          <View style={styles.ribbon} />

          <View style={styles.headerSection}>
            <Text style={styles.icon}>🔗</Text>
            <Text style={styles.eyebrow}>Hubung Perangkat</Text>
            <Text style={styles.title}>Gunakan Kode Akses</Text>
            <Text style={styles.subtitle}>
              Masukkan kode akses dari dashboard web untuk menghubungkan perangkat ini.
            </Text>
          </View>

          <View style={styles.formSection}>
            <View style={styles.formGroup}>
              <Text style={styles.label}>Kode Akses</Text>
              <View
                style={[
                  styles.inputWrapper,
                  codeFocused && styles.inputWrapperFocused,
                  error && styles.inputWrapperError,
                ]}
              >
                <Text style={styles.inputIcon}>🎫</Text>
                <TextInput
                  value={accessCode}
                  onChangeText={(text) => {
                    setAccessCode(text);
                    if (error) setError(null);
                  }}
                  onFocus={() => setCodeFocused(true)}
                  onBlur={() => setCodeFocused(false)}
                  placeholder="EXO-AB12CD34"
                  placeholderTextColor={colors.textSecondary}
                  style={styles.input}
                  autoCapitalize="characters"
                  autoCorrect={false}
                />
              </View>
            </View>

            <View style={styles.formGroup}>
              <Text style={styles.label}>Nama Perangkat (Opsional)</Text>
              <View
                style={[
                  styles.inputWrapper,
                  aliasFocused && styles.inputWrapperFocused,
                ]}
              >
                <Text style={styles.inputIcon}>📱</Text>
                <TextInput
                  value={deviceAlias}
                  onChangeText={setDeviceAlias}
                  onFocus={() => setAliasFocused(true)}
                  onBlur={() => setAliasFocused(false)}
                  placeholder="e.g., iPhone 14"
                  placeholderTextColor={colors.textSecondary}
                  style={styles.input}
                  autoCapitalize="words"
                />
              </View>
            </View>
          </View>

          <View style={styles.infoSection}>
            <View style={styles.infoItem}>
              <Text style={styles.infoBullet}>✓</Text>
              <Text style={styles.infoText}>
                Kode akses bisa didapat dari dashboard web Anda
              </Text>
            </View>
            <View style={styles.infoItem}>
              <Text style={styles.infoBullet}>✓</Text>
              <Text style={styles.infoText}>
                Nama perangkat membantu Anda mengelola akses
              </Text>
            </View>
          </View>

          {error ? (
            <View style={styles.errorBox}>
              <Text style={styles.errorIcon}>⚠️</Text>
              <Text style={styles.errorText}>{error}</Text>
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
            <Text style={styles.buttonIcon}>
              {isSubmitting ? '⏳' : '🔐'}
            </Text>
            <Text style={styles.buttonText}>
              {isSubmitting ? 'Menghubungkan...' : 'Hubungkan Perangkat'}
            </Text>
          </Pressable>
        </View>
      </View>
    </ScreenContainer>
  );
}

const styles = StyleSheet.create({
  wrapper: {
    flex: 1,
    justifyContent: 'center',
  },
  card: {
    backgroundColor: colors.surface,
    borderRadius: 24,
    borderWidth: 1,
    borderColor: colors.border,
    padding: 18,
    shadowColor: '#6E4A26',
    shadowOpacity: 0.16,
    shadowRadius: 14,
    shadowOffset: { width: 0, height: 8 },
    elevation: 4,
    overflow: 'hidden',
  },
  ribbon: {
    position: 'absolute',
    top: 0,
    left: 0,
    right: 0,
    height: 7,
    backgroundColor: colors.accent,
  },
  headerSection: {
    alignItems: 'center',
    gap: 10,
    marginBottom: 18,
    paddingTop: 6,
  },
  icon: {
    fontSize: 40,
    marginBottom: 4,
  },
  eyebrow: {
    color: colors.accentDark,
    fontSize: 11,
    fontWeight: '700',
    letterSpacing: 0.8,
    textTransform: 'uppercase',
  },
  title: {
    color: colors.textPrimary,
    fontSize: 26,
    fontWeight: '800',
    textAlign: 'center',
  },
  subtitle: {
    color: colors.textSecondary,
    fontSize: 14,
    lineHeight: 20,
    textAlign: 'center',
  },
  formSection: {
    gap: 14,
    marginBottom: 16,
  },
  formGroup: {
    gap: 8,
  },
  label: {
    color: colors.textPrimary,
    fontSize: 14,
    fontWeight: '700',
  },
  inputWrapper: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: colors.surfaceMuted,
    borderColor: colors.border,
    borderWidth: 1.5,
    borderRadius: 14,
    paddingHorizontal: 14,
    paddingVertical: 2,
    gap: 10,
  },
  inputWrapperFocused: {
    borderColor: colors.accentLight,
    backgroundColor: '#FFF2E1',
  },
  inputWrapperError: {
    borderColor: '#DC2626',
  },
  inputIcon: {
    fontSize: 18,
  },
  input: {
    flex: 1,
    color: colors.textPrimary,
    fontSize: 15,
    paddingVertical: 12,
  },
  infoSection: {
    gap: 8,
    marginBottom: 14,
    backgroundColor: '#FBF5EA',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#EBDCC2',
    padding: 12,
  },
  infoItem: {
    flexDirection: 'row',
    gap: 10,
    alignItems: 'flex-start',
  },
  infoBullet: {
    color: colors.accent,
    fontSize: 13,
    fontWeight: '700',
    marginTop: 2,
  },
  infoText: {
    color: colors.textSecondary,
    fontSize: 13,
    lineHeight: 18,
    flex: 1,
  },
  errorBox: {
    flexDirection: 'row',
    backgroundColor: '#FFF1EC',
    borderColor: '#F9C5B5',
    borderWidth: 1,
    borderRadius: 12,
    padding: 12,
    gap: 10,
    alignItems: 'flex-start',
    marginBottom: 12,
  },
  errorIcon: {
    fontSize: 18,
    marginTop: 2,
  },
  errorText: {
    color: colors.danger,
    fontSize: 13,
    lineHeight: 18,
    flex: 1,
  },
  button: {
    flexDirection: 'row',
    backgroundColor: colors.accent,
    borderRadius: 14,
    paddingVertical: 14,
    paddingHorizontal: 16,
    alignItems: 'center',
    justifyContent: 'center',
    gap: 8,
    shadowColor: '#6E4A26',
    shadowOpacity: 0.2,
    shadowRadius: 6,
    shadowOffset: { width: 0, height: 3 },
    elevation: 3,
  },
  buttonPressed: {
    opacity: 0.85,
    transform: [{ scale: 0.98 }],
  },
  buttonDisabled: {
    opacity: 0.6,
  },
  buttonIcon: {
    fontSize: 18,
  },
  buttonText: {
    color: '#FFFFFF',
    fontSize: 16,
    fontWeight: '700',
  },
});
