import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import {
  Linking,
  Pressable,
  ScrollView,
  StyleSheet,
  Text,
  useWindowDimensions,
  View,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

import AsyncStorage from '@react-native-async-storage/async-storage';
import type { RootStackParamList } from '../navigation/RootNavigator';
import { F } from '../shared/theme/fonts';

const TERMS_OF_SERVICE_URL = 'https://exoinvite.site/terms-of-service';
const PRIVACY_POLICY_URL = 'https://exoinvite.site/privacy-policy';

// Design tokens from "The Digital Atelier" system
const C = {
  background: '#FFF7FC',
  surface: '#FFF7FC',
  surfaceContainerLow: '#FEEFFF',
  surfaceContainerHigh: '#F8E0FF',
  surfaceContainerHighest: '#F2DBFA',
  primary: '#630ED4',
  primaryContainer: '#7C3AED',
  onPrimary: '#FFFFFF',
  onSurface: '#24162C',
  onSurfaceVariant: '#4A4455',
  outline: '#7B7487',
  outlineVariant: '#CCC3D8',
  secondary: '#B51C0B',
  onSecondary: '#FFFFFF',
};

export function WelcomeScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<RootStackParamList>>();
  const { width } = useWindowDimensions();
  const isCompact = width <= 390;

  const openLink = async (url: string) => {
    const canOpen = await Linking.canOpenURL(url);
    if (canOpen) await Linking.openURL(url);
  };

  return (
    <SafeAreaView style={styles.safeArea} edges={['top', 'bottom']}>
      <ScrollView
        contentContainerStyle={[styles.scrollContent, isCompact && styles.scrollContentCompact]}
        showsVerticalScrollIndicator={false}
      >
        {/* Brand Header */}
        <View style={styles.brandHeader}>
          <Text style={[styles.brandName, isCompact && styles.brandNameCompact]}>Exoinvite</Text>
        </View>

        {/* Illustration: Asymmetric Editorial Cards */}
        <View style={[styles.illustrationSection, isCompact && styles.illustrationSectionCompact]}>
          {/* Back card - rotated left */}
          <View style={[styles.illustrationCardBack, isCompact && styles.illustrationCardBackCompact]}>
            <View style={styles.illustrationPlaceholderBack}>
              <View style={styles.illustrationDot} />
              <View style={[styles.illustrationDot, { backgroundColor: C.primaryContainer, opacity: 0.5 }]} />
            </View>
          </View>

          {/* Front card - rotated right, overlaps */}
          <View style={[styles.illustrationCardFront, isCompact && styles.illustrationCardFrontCompact]}>
            <View style={styles.illustrationPlaceholderFront}>
              {/* Decorative lines simulating invitation card */}
              <Text style={styles.illustrationCardLabel}>✦ Exoinvite</Text>
              <Text style={styles.illustrationCardSubLabel}>Digital Atelier</Text>
              <View style={styles.illustrationLine} />
              <View style={[styles.illustrationLine, { width: '60%', opacity: 0.5 }]} />
            </View>
            <View style={styles.illustrationCardOverlay} />
          </View>

          {/* Decorative glow blob */}
          <View style={styles.glowBlob} />
        </View>

        {/* Text Content */}
        <View style={[styles.textSection, isCompact && styles.textSectionCompact]}>
          <Text style={[styles.headline, isCompact && styles.headlineCompact]}>
            Buat Undangan Digital Hitungan Menit
          </Text>
          <Text style={[styles.subtitle, isCompact && styles.subtitleCompact]}>
            Rayakan momen spesial Anda dengan undangan digital yang elegan, interaktif, dan mudah
            dibagikan.
          </Text>
        </View>

        {/* Action Buttons */}
        <View style={[styles.actionsSection, isCompact && styles.actionsSectionCompact]}>
          {/* Primary CTA */}
          <Pressable
            onPress={async () => {
              await AsyncStorage.setItem('HAS_LAUNCHED', 'true');
              navigation.replace('Main');
            }}
            style={({ pressed }) => [styles.primaryButton, pressed && styles.buttonPressed]}
          >
            <Text style={[styles.primaryButtonText, isCompact && styles.primaryButtonTextCompact]}>
              Mulai Menggunakan Aplikasi
            </Text>
            <Text style={styles.primaryButtonArrow}>→</Text>
          </Pressable>

          {/* Ghost / Guest CTA */}
          <Pressable
            onPress={async () => {
              await AsyncStorage.setItem('HAS_LAUNCHED', 'true');
              navigation.replace('Main');
            }}
            style={({ pressed }) => [styles.ghostButton, pressed && styles.buttonPressed]}
          >
            <Text style={[styles.ghostButtonText, isCompact && styles.ghostButtonTextCompact]}>
              Jelajahi Dulu
            </Text>
          </Pressable>

          {/* Divider */}
          <View style={styles.dividerRow}>
            <View style={styles.dividerLine} />
            <Text style={[styles.dividerText, isCompact && styles.dividerTextCompact]}>
              ATAU MASUK DENGAN
            </Text>
            <View style={styles.dividerLine} />
          </View>

          {/* Social Auth */}
          <View style={[styles.socialRow, isCompact && styles.socialRowCompact]}>
            <Pressable
              onPress={() => console.log('Google')}
              style={({ pressed }) => [
                styles.socialButton,
                styles.googleButton,
                pressed && styles.buttonPressed,
              ]}
            >
              <Text style={[styles.socialButtonText, styles.googleButtonText]}>G  Google</Text>
            </Pressable>
            <Pressable
              onPress={() => console.log('Apple')}
              style={({ pressed }) => [
                styles.socialButton,
                styles.appleButton,
                pressed && styles.buttonPressed,
              ]}
            >
              <Text style={[styles.socialButtonText, styles.appleButtonText]}>  Apple</Text>
            </Pressable>
          </View>
        </View>

        {/* Legal */}
        <Text style={[styles.legalText, isCompact && styles.legalTextCompact]}>
          By continuing, you agree to our{' '}
          <Text onPress={() => void openLink(TERMS_OF_SERVICE_URL)} style={styles.legalLink}>
            Terms of Service
          </Text>{' '}
          and{' '}
          <Text onPress={() => void openLink(PRIVACY_POLICY_URL)} style={styles.legalLink}>
            Privacy Policy
          </Text>
          .
        </Text>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: C.background,
  },
  scrollContent: {
    paddingHorizontal: 24,
    paddingTop: 20,
    paddingBottom: 36,
    alignItems: 'center',
  },
  scrollContentCompact: {
    paddingHorizontal: 18,
    paddingTop: 14,
    paddingBottom: 28,
  },

  // Brand Header
  brandHeader: {
    alignSelf: 'flex-start',
    marginBottom: 28,
  },
  brandName: {
    color: C.primary,
    fontSize: 28,
    fontFamily: F.display,
    letterSpacing: -0.8,
  },
  brandNameCompact: {
    fontSize: 24,
  },

  // Illustration Section
  illustrationSection: {
    width: '100%',
    height: 320,
    position: 'relative',
    marginBottom: 36,
  },
  illustrationSectionCompact: {
    height: 240,
    marginBottom: 28,
  },
  illustrationCardBack: {
    position: 'absolute',
    left: -8,
    top: 0,
    width: '80%',
    aspectRatio: 3 / 4,
    borderRadius: 28,
    overflow: 'hidden',
    backgroundColor: C.surfaceContainerHigh,
    transform: [{ rotate: '-3deg' }],
    shadowColor: C.onSurface,
    shadowOpacity: 0.06,
    shadowRadius: 20,
    shadowOffset: { width: 0, height: 8 },
    elevation: 4,
    zIndex: 1,
  },
  illustrationCardBackCompact: {
    borderRadius: 22,
  },
  illustrationPlaceholderBack: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    gap: 12,
    flexDirection: 'row',
  },
  illustrationDot: {
    width: 48,
    height: 48,
    borderRadius: 24,
    backgroundColor: C.primary,
    opacity: 0.15,
  },
  illustrationCardFront: {
    position: 'absolute',
    right: -6,
    top: 36,
    width: '72%',
    aspectRatio: 3 / 4,
    borderRadius: 28,
    overflow: 'hidden',
    backgroundColor: C.surfaceContainerHighest,
    transform: [{ rotate: '5deg' }],
    borderWidth: 3,
    borderColor: C.surface,
    shadowColor: C.primary,
    shadowOpacity: 0.18,
    shadowRadius: 32,
    shadowOffset: { width: 0, height: 12 },
    elevation: 8,
    zIndex: 2,
  },
  illustrationCardFrontCompact: {
    borderRadius: 22,
    top: 26,
  },
  illustrationPlaceholderFront: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 24,
    gap: 8,
  },
  illustrationCardLabel: {
    color: C.primary,
    fontSize: 18,
    fontWeight: '800',
    letterSpacing: 0.5,
    textAlign: 'center',
  },
  illustrationCardSubLabel: {
    color: C.onSurfaceVariant,
    fontSize: 11,
    fontWeight: '500',
    letterSpacing: 2,
    textTransform: 'uppercase',
    marginBottom: 14,
  },
  illustrationLine: {
    width: '80%',
    height: 2,
    borderRadius: 1,
    backgroundColor: C.outlineVariant,
    marginTop: 6,
  },
  illustrationCardOverlay: {
    position: 'absolute',
    bottom: 0,
    left: 0,
    right: 0,
    height: '35%',
    backgroundColor: C.primary,
    opacity: 0.08,
  },
  glowBlob: {
    position: 'absolute',
    bottom: -16,
    left: 0,
    width: 96,
    height: 96,
    borderRadius: 48,
    backgroundColor: '#FFDF9F',
    opacity: 0.35,
    // blur not available in RN without extra lib; we just use opacity
    zIndex: 0,
  },

  // Text
  textSection: {
    width: '100%',
    alignItems: 'center',
    gap: 12,
    paddingHorizontal: 4,
    marginBottom: 32,
  },
  textSectionCompact: {
    gap: 8,
    marginBottom: 24,
  },
  headline: {
    color: C.onSurface,
    textAlign: 'center',
    fontSize: 34,
    lineHeight: 42,
    fontFamily: F.display,
    letterSpacing: -0.5,
  },
  headlineCompact: {
    fontSize: 28,
    lineHeight: 36,
  },
  subtitle: {
    color: C.onSurfaceVariant,
    textAlign: 'center',
    fontSize: 15,
    lineHeight: 23,
    fontFamily: F.body,
    paddingHorizontal: 12,
  },
  subtitleCompact: {
    fontSize: 14,
    lineHeight: 21,
    paddingHorizontal: 4,
  },

  // Actions
  actionsSection: {
    width: '100%',
    gap: 14,
    marginBottom: 28,
  },
  actionsSectionCompact: {
    gap: 11,
    marginBottom: 22,
  },
  primaryButton: {
    width: '100%',
    height: 56,
    borderRadius: 999,
    backgroundColor: C.primary,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 8,
    shadowColor: C.primary,
    shadowOpacity: 0.22,
    shadowRadius: 16,
    shadowOffset: { width: 0, height: 8 },
    elevation: 6,
  },
  primaryButtonText: {
    color: C.onPrimary,
    fontSize: 16,
    fontFamily: F.labelBold,
    letterSpacing: 0.2,
  },
  primaryButtonTextCompact: {
    fontSize: 15,
  },
  primaryButtonArrow: {
    color: C.onPrimary,
    fontSize: 18,
    fontWeight: '700',
  },
  ghostButton: {
    width: '100%',
    height: 52,
    borderRadius: 999,
    backgroundColor: C.surfaceContainerLow,
    alignItems: 'center',
    justifyContent: 'center',
  },
  ghostButtonText: {
    color: C.primary,
    fontSize: 15,
    fontFamily: F.label,
  },
  ghostButtonTextCompact: {
    fontSize: 14,
  },

  // Divider
  dividerRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 12,
    marginVertical: 4,
  },
  dividerLine: {
    flex: 1,
    height: 1,
    backgroundColor: C.outlineVariant,
    opacity: 0.4,
  },
  dividerText: {
    color: C.outline,
    fontSize: 10,
    fontWeight: '700',
    letterSpacing: 1.2,
  },
  dividerTextCompact: {
    fontSize: 9,
    letterSpacing: 1,
  },

  // Social
  socialRow: {
    flexDirection: 'row',
    gap: 14,
  },
  socialRowCompact: {
    gap: 10,
  },
  socialButton: {
    flex: 1,
    height: 52,
    borderRadius: 14,
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 1.2,
  },
  googleButton: {
    backgroundColor: '#FFFFFF',
    borderColor: C.outlineVariant,
  },
  appleButton: {
    backgroundColor: '#111111',
    borderColor: '#111111',
  },
  socialButtonText: {
    fontSize: 14,
    fontWeight: '700',
  },
  googleButtonText: {
    color: '#1F2937',
  },
  appleButtonText: {
    color: '#FFFFFF',
  },

  // Legal
  legalText: {
    color: '#9B8FAD',
    textAlign: 'center',
    fontSize: 11,
    lineHeight: 16,
    fontFamily: F.body,
    paddingHorizontal: 12,
  },
  legalTextCompact: {
    fontSize: 10,
    lineHeight: 14,
  },
  legalLink: {
    color: C.primary,
    fontWeight: '700',
  },

  // Interaction
  buttonPressed: {
    opacity: 0.88,
    transform: [{ scale: 0.978 }],
  },
});
