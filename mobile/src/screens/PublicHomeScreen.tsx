import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { Pressable, StyleSheet, Text, View } from 'react-native';

import type { GuestStackParamList } from '../navigation/types';
import { ScreenContainer } from '../shared/components/ScreenContainer';
import { colors } from '../shared/theme/colors';

export function PublicHomeScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<GuestStackParamList>>();

  return (
    <ScreenContainer>
      <View style={styles.heroSection}>
        <View style={styles.heroGradient}>
          <View style={styles.heroContent}>
            <Text style={styles.eyebrow}>✨ Exo Expanse</Text>
            <Text style={styles.title}>Undangan Digital Elegan</Text>
            <Text style={styles.subtitle}>
              Platform modern untuk membuat dan mengelola undangan digital dengan tema yang memukau.
            </Text>
          </View>
        </View>
      </View>

      <View style={styles.actionsContainer}>
        <Pressable
          onPress={() => navigation.navigate('ThemeCatalog')}
          style={({ pressed }) => [
            styles.primaryButton,
            pressed && styles.primaryButtonPressed,
          ]}
        >
          <Text style={styles.primaryButtonIcon}>🎨</Text>
          <View style={styles.buttonContent}>
            <Text style={styles.primaryButtonTitle}>Jelajahi Tema</Text>
            <Text style={styles.primaryButtonSubtitle}>Lihat koleksi tema premium kami</Text>
          </View>
        </Pressable>

        <Pressable
          onPress={() => navigation.navigate('AuthChoice', { intent: 'manage' })}
          style={({ pressed }) => [
            styles.secondaryButton,
            pressed && styles.secondaryButtonPressed,
          ]}
        >
          <Text style={styles.secondaryButtonIcon}>📋</Text>
          <View style={styles.buttonContent}>
            <Text style={styles.secondaryButtonTitle}>Kelola Undangan</Text>
            <Text style={styles.secondaryButtonSubtitle}>Manage undangan Anda</Text>
          </View>
        </Pressable>
      </View>

      <View style={styles.featureContainer}>
        <View style={styles.featureItem}>
          <Text style={styles.featureIcon}>⚡</Text>
          <Text style={styles.featureTitle}>Cepat & Mudah</Text>
          <Text style={styles.featureText}>Buat undangan dalam hitungan menit</Text>
        </View>
        <View style={styles.featureItem}>
          <Text style={styles.featureIcon}>🎯</Text>
          <Text style={styles.featureTitle}>Personal</Text>
          <Text style={styles.featureText}>Customize sesuai gaya Anda</Text>
        </View>
        <View style={styles.featureItem}>
          <Text style={styles.featureIcon}>📱</Text>
          <Text style={styles.featureTitle}>Digital First</Text>
          <Text style={styles.featureText}>Sempurna untuk mobile</Text>
        </View>
      </View>
    </ScreenContainer>
  );
}

const styles = StyleSheet.create({
  heroSection: {
    marginHorizontal: -20,
    marginTop: -16,
    marginBottom: 32,
    overflow: 'hidden',
  },
  heroGradient: {
    backgroundColor: `${colors.accent}14`,
    borderBottomLeftRadius: 28,
    borderBottomRightRadius: 28,
    paddingHorizontal: 20,
    paddingVertical: 40,
    borderWidth: 1,
    borderColor: `${colors.accent}28`,
  },
  heroContent: {
    gap: 12,
  },
  eyebrow: {
    color: colors.accent,
    fontSize: 13,
    fontWeight: '700',
    letterSpacing: 0.5,
  },
  title: {
    color: colors.textPrimary,
    fontSize: 32,
    fontWeight: '800',
    lineHeight: 40,
  },
  subtitle: {
    color: colors.textSecondary,
    fontSize: 15,
    lineHeight: 24,
    marginTop: 4,
  },
  actionsContainer: {
    gap: 12,
    marginBottom: 32,
  },
  primaryButton: {
    flexDirection: 'row',
    backgroundColor: colors.accent,
    borderRadius: 16,
    paddingVertical: 18,
    paddingHorizontal: 16,
    alignItems: 'center',
    gap: 14,
    shadowColor: colors.accent,
    shadowOpacity: 0.25,
    shadowRadius: 8,
    shadowOffset: { width: 0, height: 4 },
    elevation: 4,
  },
  primaryButtonPressed: {
    opacity: 0.85,
    transform: [{ scale: 0.98 }],
  },
  primaryButtonIcon: {
    fontSize: 28,
  },
  buttonContent: {
    flex: 1,
    gap: 2,
  },
  primaryButtonTitle: {
    color: '#FFFFFF',
    fontSize: 16,
    fontWeight: '700',
  },
  primaryButtonSubtitle: {
    color: '#FFFFFF',
    fontSize: 13,
    opacity: 0.85,
  },
  secondaryButton: {
    flexDirection: 'row',
    borderColor: colors.accent,
    borderWidth: 2,
    backgroundColor: '#FFF8EF',
    borderRadius: 16,
    paddingVertical: 18,
    paddingHorizontal: 16,
    alignItems: 'center',
    gap: 14,
  },
  secondaryButtonPressed: {
    backgroundColor: '#FFFAF3',
    opacity: 0.8,
  },
  secondaryButtonIcon: {
    fontSize: 28,
  },
  secondaryButtonTitle: {
    color: colors.accent,
    fontSize: 16,
    fontWeight: '700',
  },
  secondaryButtonSubtitle: {
    color: colors.textSecondary,
    fontSize: 13,
  },
  featureContainer: {
    flexDirection: 'row',
    gap: 12,
  },
  featureItem: {
    flex: 1,
    backgroundColor: colors.surface,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: colors.border,
    padding: 16,
    alignItems: 'center',
    gap: 8,
  },
  featureIcon: {
    fontSize: 28,
    marginBottom: 4,
  },
  featureTitle: {
    color: colors.textPrimary,
    fontSize: 13,
    fontWeight: '700',
    textAlign: 'center',
  },
  featureText: {
    color: colors.textSecondary,
    fontSize: 11,
    textAlign: 'center',
    lineHeight: 15,
  },
});
