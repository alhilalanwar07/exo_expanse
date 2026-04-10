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
import { useAppTheme } from '../shared/theme/index';

const TERMS_OF_SERVICE_URL = 'https://exoinvite.site/terms-of-service';
const PRIVACY_POLICY_URL = 'https://exoinvite.site/privacy-policy';

export function WelcomeScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<RootStackParamList>>();
  const { theme } = useAppTheme();
  const { width } = useWindowDimensions();
  const isCompact = width <= 390;
  const s = makeStyles(theme, isCompact);

  const openLink = async (url: string) => {
    const canOpen = await Linking.canOpenURL(url);
    if (canOpen) await Linking.openURL(url);
  };

  const goToMain = async () => {
    await AsyncStorage.setItem('HAS_LAUNCHED', 'true');
    navigation.replace('Main');
  };

  return (
    <SafeAreaView style={s.safeArea} edges={['top', 'bottom']}>
      <ScrollView
        contentContainerStyle={s.scrollContent}
        showsVerticalScrollIndicator={false}
      >
        {/* Brand */}
        <View style={s.brandHeader}>
          <Text style={s.brandName}>Exoinvite</Text>
        </View>

        {/* Illustration */}
        <View style={s.illustrationSection}>
          <View style={s.illustrationCardBack}>
            <View style={s.illustrationPlaceholderBack}>
              <View style={s.illustrationDot} />
              <View style={[s.illustrationDot, { opacity: 0.4 }]} />
            </View>
          </View>
          <View style={s.illustrationCardFront}>
            <View style={s.illustrationPlaceholderFront}>
              <Text style={s.illustrationCardLabel}>✦ Exoinvite</Text>
              <Text style={s.illustrationCardSubLabel}>Digital Atelier</Text>
              <View style={s.illustrationLine} />
              <View style={[s.illustrationLine, { width: '60%', opacity: 0.5 }]} />
            </View>
            <View style={s.illustrationCardOverlay} />
          </View>
          <View style={s.glowBlob} />
        </View>

        {/* Text */}
        <View style={s.textSection}>
          <Text style={s.headline}>Buat Undangan Digital Hitungan Menit</Text>
          <Text style={s.subtitle}>
            Rayakan momen spesial Anda dengan undangan digital yang elegan, interaktif, dan mudah
            dibagikan.
          </Text>
        </View>

        {/* Actions */}
        <View style={s.actionsSection}>
          <Pressable
            onPress={goToMain}
            style={({ pressed }) => [s.primaryButton, pressed && s.buttonPressed]}
          >
            <Text style={s.primaryButtonText}>Mulai Menggunakan Aplikasi</Text>
            <Text style={s.primaryButtonArrow}>→</Text>
          </Pressable>

          <Pressable
            onPress={goToMain}
            style={({ pressed }) => [s.ghostButton, pressed && s.buttonPressed]}
          >
            <Text style={s.ghostButtonText}>Jelajahi Dulu</Text>
          </Pressable>

          <View style={s.dividerRow}>
            <View style={s.dividerLine} />
            <Text style={s.dividerText}>ATAU MASUK DENGAN</Text>
            <View style={s.dividerLine} />
          </View>

          <View style={s.socialRow}>
            <Pressable
              onPress={() => console.log('Google')}
              style={({ pressed }) => [s.socialButton, s.googleButton, pressed && s.buttonPressed]}
            >
              <Text style={[s.socialButtonText, s.googleButtonText]}>G  Google</Text>
            </Pressable>
            <Pressable
              onPress={() => console.log('Apple')}
              style={({ pressed }) => [s.socialButton, s.appleButton, pressed && s.buttonPressed]}
            >
              <Text style={[s.socialButtonText, s.appleButtonText]}>  Apple</Text>
            </Pressable>
          </View>
        </View>

        {/* Legal */}
        <Text style={s.legalText}>
          By continuing, you agree to our{' '}
          <Text onPress={() => void openLink(TERMS_OF_SERVICE_URL)} style={s.legalLink}>
            Terms of Service
          </Text>{' '}
          and{' '}
          <Text onPress={() => void openLink(PRIVACY_POLICY_URL)} style={s.legalLink}>
            Privacy Policy
          </Text>
          .
        </Text>
      </ScrollView>
    </SafeAreaView>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme'], isCompact: boolean) {
  return StyleSheet.create({
    safeArea: { flex: 1, backgroundColor: t.background },
    scrollContent: {
      paddingHorizontal: isCompact ? 18 : 24,
      paddingTop: isCompact ? 14 : 20,
      paddingBottom: isCompact ? 28 : 36,
      alignItems: 'center',
    },

    brandHeader: { alignSelf: 'flex-start', marginBottom: 28 },
    brandName: {
      color: t.primary,
      fontSize: isCompact ? 24 : 28,
      fontFamily: F.display,
      letterSpacing: -0.8,
    },

    illustrationSection: {
      width: '100%',
      height: isCompact ? 240 : 320,
      position: 'relative',
      marginBottom: isCompact ? 28 : 36,
    },
    illustrationCardBack: {
      position: 'absolute',
      left: -8,
      top: 0,
      width: '80%',
      aspectRatio: 3 / 4,
      borderRadius: isCompact ? 22 : 28,
      overflow: 'hidden',
      backgroundColor: t.surfaceContainerHigh,
      transform: [{ rotate: '-3deg' }],
      shadowColor: t.onSurface,
      shadowOpacity: 0.06,
      shadowRadius: 20,
      shadowOffset: { width: 0, height: 8 },
      elevation: 4,
      zIndex: 1,
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
      backgroundColor: t.primary,
      opacity: 0.15,
    },
    illustrationCardFront: {
      position: 'absolute',
      right: -6,
      top: isCompact ? 26 : 36,
      width: '72%',
      aspectRatio: 3 / 4,
      borderRadius: isCompact ? 22 : 28,
      overflow: 'hidden',
      backgroundColor: t.surfaceContainerHighest,
      transform: [{ rotate: '5deg' }],
      borderWidth: 3,
      borderColor: t.surface,
      shadowColor: t.primary,
      shadowOpacity: 0.18,
      shadowRadius: 32,
      shadowOffset: { width: 0, height: 12 },
      elevation: 8,
      zIndex: 2,
    },
    illustrationPlaceholderFront: {
      flex: 1,
      justifyContent: 'center',
      alignItems: 'center',
      padding: 24,
      gap: 8,
    },
    illustrationCardLabel: {
      color: t.primary,
      fontSize: 18,
      fontFamily: F.display,
      letterSpacing: 0.5,
      textAlign: 'center',
    },
    illustrationCardSubLabel: {
      color: t.onSurfaceVariant,
      fontSize: 11,
      fontFamily: F.label,
      letterSpacing: 2,
      textTransform: 'uppercase',
      marginBottom: 14,
    },
    illustrationLine: {
      width: '80%',
      height: 2,
      borderRadius: 1,
      backgroundColor: t.outlineVariant,
      marginTop: 6,
    },
    illustrationCardOverlay: {
      position: 'absolute',
      bottom: 0,
      left: 0,
      right: 0,
      height: '35%',
      backgroundColor: t.primary,
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
      zIndex: 0,
    },

    textSection: {
      width: '100%',
      alignItems: 'center',
      gap: isCompact ? 8 : 12,
      paddingHorizontal: 4,
      marginBottom: isCompact ? 24 : 32,
    },
    headline: {
      color: t.onSurface,
      textAlign: 'center',
      fontSize: isCompact ? 28 : 34,
      lineHeight: isCompact ? 36 : 42,
      fontFamily: F.display,
      letterSpacing: -0.5,
    },
    subtitle: {
      color: t.onSurfaceVariant,
      textAlign: 'center',
      fontSize: isCompact ? 14 : 15,
      lineHeight: isCompact ? 21 : 23,
      fontFamily: F.body,
      paddingHorizontal: isCompact ? 4 : 12,
    },

    actionsSection: {
      width: '100%',
      gap: isCompact ? 11 : 14,
      marginBottom: isCompact ? 22 : 28,
    },
    primaryButton: {
      width: '100%',
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
    },
    primaryButtonText: {
      color: '#FFFFFF',
      fontSize: isCompact ? 15 : 16,
      fontFamily: F.labelBold,
      letterSpacing: 0.2,
    },
    primaryButtonArrow: {
      color: '#FFFFFF',
      fontSize: 18,
      fontFamily: F.labelBold,
    },
    ghostButton: {
      width: '100%',
      height: 52,
      borderRadius: 999,
      backgroundColor: t.surfaceContainerLow,
      alignItems: 'center',
      justifyContent: 'center',
    },
    ghostButtonText: {
      color: t.primary,
      fontSize: isCompact ? 14 : 15,
      fontFamily: F.label,
    },

    dividerRow: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 12,
      marginVertical: 4,
    },
    dividerLine: {
      flex: 1,
      height: 1,
      backgroundColor: t.outlineVariant,
      opacity: 0.4,
    },
    dividerText: {
      color: t.outline,
      fontSize: isCompact ? 9 : 10,
      fontFamily: F.labelBold,
      letterSpacing: 1.2,
    },

    socialRow: { flexDirection: 'row', gap: isCompact ? 10 : 14 },
    socialButton: {
      flex: 1,
      height: 52,
      borderRadius: 14,
      alignItems: 'center',
      justifyContent: 'center',
      borderWidth: 1.2,
    },
    googleButton: {
      backgroundColor: t.googleBg,
      borderColor: t.googleBorder,
    },
    appleButton: {
      backgroundColor: t.appleBg,
      borderColor: t.appleBg,
    },
    socialButtonText: { fontSize: 14, fontFamily: F.labelBold },
    googleButtonText: { color: t.googleText },
    appleButtonText: { color: t.appleText },

    legalText: {
      color: t.outline,
      textAlign: 'center',
      fontSize: isCompact ? 10 : 11,
      lineHeight: isCompact ? 14 : 16,
      fontFamily: F.body,
      paddingHorizontal: 12,
    },
    legalLink: { color: t.primary, fontFamily: F.labelBold },

    buttonPressed: { opacity: 0.88, transform: [{ scale: 0.978 }] },
  });
}
