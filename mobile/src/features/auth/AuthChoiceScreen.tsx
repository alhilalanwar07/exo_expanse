import { useNavigation, useRoute } from '@react-navigation/native';
import type { RouteProp } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { Alert, Pressable, StyleSheet, Text, useWindowDimensions, View } from 'react-native';

import type { AuthFlowParamList } from '../../navigation/types';
import { ScreenContainer, SCREEN_CONTAINER_LAYOUT } from '../../shared/components/ScreenContainer';
import { F } from '../../shared/theme/fonts';
import { useAppTheme } from '../../shared/theme/index';

export function AuthChoiceScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<AuthFlowParamList>>();
  const route = useRoute<RouteProp<AuthFlowParamList, 'AuthChoice'>>();
  const { theme } = useAppTheme();
  const { width } = useWindowDimensions();
  const isCompact = width <= SCREEN_CONTAINER_LAYOUT.compactBreakpoint;
  const s = makeStyles(theme, isCompact);

  const intentLabel =
    route.params?.intent === 'theme'
      ? 'memilih tema terbaik untuk undangan Anda'
      : 'mengelola undangan Anda dengan akun owner';

  const handleSocialAuthPress = (provider: 'Google' | 'Apple') => {
    Alert.alert(
      'Dalam Pengembangan',
      `Masuk dengan ${provider} masih dalam pengembangan.`
    );
  };

  return (
    <ScreenContainer
      contentGap={0}
      contentStyle={s.content}
      backgroundColor={theme.background}
    >
      <View style={s.topBar}>
        <Text style={s.brandName}>Exoinvite</Text>
        <Pressable
          onPress={() => navigation.goBack()}
          style={({ pressed }) => [s.closeBtn, pressed && s.pressed]}
          accessibilityRole="button"
          accessibilityLabel="Kembali"
        >
          <MaterialCommunityIcons name="close" size={22} color={theme.onSurfaceVariant} />
        </Pressable>
      </View>

      <View style={s.heroSection}>
        <View style={s.heroCardBack}>
          <View style={s.heroBackPattern}>
            <View style={s.heroDot} />
            <View style={[s.heroDot, { opacity: 0.45 }]} />
          </View>
        </View>

        <View style={s.heroCardFront}>
          <View style={s.heroFrontContent}>
            <Text style={s.heroFrontLabel}>Owner Access</Text>
            <Text style={s.heroFrontSub}>Digital Atelier</Text>
            <View style={s.heroLine} />
            <View style={[s.heroLine, { width: '62%', opacity: 0.5 }]} />
          </View>
          <View style={s.heroCardOverlay} />
        </View>

        <View style={s.heroGlow} />
      </View>

      <View style={s.topSection}>
        <Text style={s.title}>Akses Akun Owner</Text>
        <Text style={s.subtitle}>
          Untuk {intentLabel}, silakan masuk, buat akun baru, atau gunakan kode akses.
        </Text>
      </View>

      <View style={s.actionsSection}>
        <Pressable
          onPress={() => navigation.navigate('Login')}
          style={({ pressed }) => [s.primaryBtn, pressed && s.pressed]}
          accessibilityRole="button"
          accessibilityLabel="Masuk ke akun"
        >
          <View style={s.primaryBtnOverlay} />
          <Text style={s.primaryBtnText}>Masuk ke Akun</Text>
          <MaterialCommunityIcons name="arrow-right" size={19} color="#FFFFFF" />
        </Pressable>

        <Pressable
          onPress={() => navigation.navigate('Register')}
          style={({ pressed }) => [s.secondaryBtn, pressed && s.pressed]}
          accessibilityRole="button"
          accessibilityLabel="Daftar akun baru"
        >
          <Text style={s.secondaryBtnText}>Daftar Akun Baru</Text>
        </Pressable>

        <Pressable
          onPress={() => navigation.navigate('ConnectDevice')}
          style={({ pressed }) => [s.codeAccessBtn, pressed && s.pressed]}
          accessibilityRole="button"
          accessibilityLabel="Masuk dengan kode akses"
        >
          <MaterialCommunityIcons name="link-variant" size={18} color={theme.amber} />
          <Text style={s.codeAccessText}>Masuk dengan Kode Akses</Text>
        </Pressable>

        <Pressable
          onPress={() => navigation.goBack()}
          style={({ pressed }) => [s.guestBtn, pressed && s.pressed]}
          accessibilityRole="button"
          accessibilityLabel="Continue as Guest"
        >
          <Text style={s.guestBtnText}>Continue as Guest</Text>
        </Pressable>

        <View style={s.dividerRow}>
          <View style={s.dividerLine} />
          <Text style={s.dividerText}>ATAU MASUK DENGAN</Text>
          <View style={s.dividerLine} />
        </View>

        <View style={s.socialRow}>
          <Pressable
            onPress={() => handleSocialAuthPress('Google')}
            style={({ pressed }) => [s.socialBtn, s.googleBtn, pressed && s.pressed]}
            accessibilityRole="button"
            accessibilityLabel="Masuk dengan Google"
          >
            <MaterialCommunityIcons name="google" size={18} color="#EA4335" />
            <Text style={s.googleText}>Google</Text>
          </Pressable>
          <Pressable
            onPress={() => handleSocialAuthPress('Apple')}
            style={({ pressed }) => [s.socialBtn, s.appleBtn, pressed && s.pressed]}
            accessibilityRole="button"
            accessibilityLabel="Masuk dengan Apple"
          >
            <MaterialCommunityIcons name="apple" size={18} color="#FFFFFF" />
            <Text style={s.appleText}>Apple</Text>
          </Pressable>
        </View>
      </View>

      <Text style={s.footnote}>
        Sesi login diamankan per perangkat dan dapat dicabut kapan saja dari dashboard.
      </Text>
    </ScreenContainer>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme'], isCompact: boolean) {
  return StyleSheet.create({
    content: {
      flex: 1,
    },

    topBar: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      marginBottom: isCompact ? 18 : 24,
    },
    closeBtn: {
      width: 40,
      height: 40,
      borderRadius: 999,
      backgroundColor: t.surfaceContainerLow,
      alignItems: 'center',
      justifyContent: 'center',
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },

    heroSection: {
      width: '100%',
      height: isCompact ? 230 : 270,
      position: 'relative',
      marginBottom: isCompact ? 22 : 28,
    },
    heroCardBack: {
      position: 'absolute',
      left: -8,
      top: 0,
      width: '84%',
      aspectRatio: 3 / 4,
      borderRadius: isCompact ? 22 : 26,
      overflow: 'hidden',
      backgroundColor: t.surfaceContainerHigh,
      transform: [{ rotate: '-3deg' }],
      shadowColor: t.onSurface,
      shadowOpacity: 0.08,
      shadowRadius: 20,
      shadowOffset: { width: 0, height: 8 },
      elevation: 4,
      zIndex: 1,
    },
    heroBackPattern: {
      flex: 1,
      alignItems: 'center',
      justifyContent: 'center',
      flexDirection: 'row',
      gap: 10,
    },
    heroDot: {
      width: 46,
      height: 46,
      borderRadius: 23,
      backgroundColor: t.primary,
      opacity: 0.2,
    },
    heroCardFront: {
      position: 'absolute',
      right: -6,
      top: isCompact ? 24 : 34,
      width: '72%',
      aspectRatio: 3 / 4,
      borderRadius: isCompact ? 22 : 26,
      overflow: 'hidden',
      backgroundColor: t.surfaceContainerHighest,
      transform: [{ rotate: '6deg' }],
      borderWidth: 3,
      borderColor: t.surface,
      shadowColor: t.primary,
      shadowOpacity: 0.2,
      shadowRadius: 28,
      shadowOffset: { width: 0, height: 11 },
      elevation: 8,
      zIndex: 2,
    },
    heroFrontContent: {
      flex: 1,
      justifyContent: 'center',
      alignItems: 'center',
      padding: 18,
      gap: 7,
    },
    heroFrontLabel: {
      color: t.primary,
      fontSize: isCompact ? 16 : 17,
      fontFamily: F.display,
      letterSpacing: 0.3,
      textAlign: 'center',
    },
    heroFrontSub: {
      color: t.onSurfaceVariant,
      fontSize: 10,
      fontFamily: F.label,
      letterSpacing: 1.7,
      textTransform: 'uppercase',
      marginBottom: 12,
    },
    heroLine: {
      width: '80%',
      height: 2,
      borderRadius: 1,
      backgroundColor: t.outlineVariant,
      marginTop: 4,
    },
    heroCardOverlay: {
      position: 'absolute',
      left: 0,
      right: 0,
      bottom: 0,
      height: '34%',
      backgroundColor: t.primary,
      opacity: 0.1,
    },
    heroGlow: {
      position: 'absolute',
      bottom: -16,
      left: 4,
      width: 92,
      height: 92,
      borderRadius: 46,
      backgroundColor: '#FFDF9F',
      opacity: 0.33,
      zIndex: 0,
    },

    topSection: { gap: 10, marginBottom: isCompact ? 22 : 28 },
    brandName: {
      color: t.primary,
      fontSize: isCompact ? 24 : 28,
      fontFamily: F.display,
      letterSpacing: -0.8,
    },
    title: {
      color: t.onSurface,
      textAlign: 'center',
      fontSize: isCompact ? 36 : 40,
      fontFamily: F.display,
      letterSpacing: -0.8,
      lineHeight: isCompact ? 44 : 50,
    },
    subtitle: {
      color: t.onSurfaceVariant,
      textAlign: 'center',
      fontSize: isCompact ? 15 : 16,
      lineHeight: isCompact ? 23 : 25,
      fontFamily: F.body,
      paddingHorizontal: isCompact ? 4 : 10,
    },

    actionsSection: {
      width: '100%',
      gap: isCompact ? 12 : 13,
    },
    primaryBtn: {
      flexDirection: 'row',
      width: '100%',
      height: 56,
      borderRadius: 999,
      backgroundColor: t.primary,
      overflow: 'hidden',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 8,
      shadowColor: t.primary,
      shadowOpacity: 0.24,
      shadowRadius: 18,
      shadowOffset: { width: 0, height: 9 },
      elevation: 6,
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
      letterSpacing: 0.2,
    },
    secondaryBtn: {
      width: '100%',
      height: 56,
      borderRadius: 999,
      backgroundColor: t.surfaceContainerLow,
      alignItems: 'center',
      justifyContent: 'center',
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },
    secondaryBtnText: {
      color: t.primary,
      fontSize: isCompact ? 15 : 16,
      fontFamily: F.labelBold,
    },
    codeAccessBtn: {
      height: 56,
      borderRadius: 999,
      borderWidth: 1,
      borderColor: t.amberBorder,
      backgroundColor: t.amberBg,
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 8,
    },
    codeAccessText: {
      color: t.amber,
      fontSize: isCompact ? 13 : 14,
      fontFamily: F.labelBold,
    },
    guestBtn: {
      height: 56,
      borderRadius: 999,
      backgroundColor: t.surfaceContainerLow,
      alignItems: 'center',
      justifyContent: 'center',
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },
    guestBtnText: {
      color: t.primary,
      fontSize: isCompact ? 15 : 16,
      fontFamily: F.labelBold,
    },

    dividerRow: { flexDirection: 'row', alignItems: 'center', gap: 12, marginTop: 6, marginBottom: 2 },
    dividerLine: { flex: 1, height: 1, backgroundColor: t.outlineVariant, opacity: 0.5 },
    dividerText: {
      color: t.outline,
      fontSize: isCompact ? 10 : 11,
      fontFamily: F.label,
      letterSpacing: 1.25,
      textTransform: 'uppercase',
    },

    socialRow: { flexDirection: 'row', gap: isCompact ? 10 : 12 },
    socialBtn: {
      flex: 1,
      height: 56,
      borderRadius: 14,
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 8,
      borderWidth: 1,
    },
    googleBtn: {
      backgroundColor: t.googleBg,
      borderColor: t.googleBorder,
    },
    appleBtn: {
      backgroundColor: '#000000',
      borderColor: '#000000',
    },
    googleText: {
      color: t.googleText,
      fontSize: 14,
      fontFamily: F.labelBold,
    },
    appleText: {
      color: '#FFFFFF',
      fontSize: 14,
      fontFamily: F.labelBold,
    },

    footnote: {
      color: t.outline,
      fontSize: isCompact ? 11 : 12,
      lineHeight: isCompact ? 16 : 18,
      textAlign: 'center',
      marginTop: isCompact ? 24 : 30,
      paddingHorizontal: 10,
      fontFamily: F.body,
    },

    pressed: { opacity: 0.86, transform: [{ scale: 0.98 }] },
  });
}
