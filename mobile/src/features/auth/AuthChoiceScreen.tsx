import { useNavigation, useRoute } from '@react-navigation/native';
import type { RouteProp } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { Pressable, StyleSheet, Text, useWindowDimensions, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

import type { GuestStackParamList } from '../../navigation/types';
import { F } from '../../shared/theme/fonts';
import { useAppTheme } from '../../shared/theme/index';

export function AuthChoiceScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<GuestStackParamList>>();
  const route = useRoute<RouteProp<GuestStackParamList, 'AuthChoice'>>();
  const { theme } = useAppTheme();
  const { width } = useWindowDimensions();
  const isCompact = width <= 390;
  const s = makeStyles(theme, isCompact);

  const intentLabel =
    route.params?.intent === 'theme'
      ? 'melanjutkan pemilihan tema'
      : 'mengelola undangan Anda';

  return (
    <SafeAreaView style={s.safeArea} edges={['top', 'bottom']}>
      <View style={s.container}>

        {/* Back */}
        <Pressable
          onPress={() => navigation.goBack()}
          style={({ pressed }) => [s.backBtn, pressed && s.pressed]}
        >
          <MaterialCommunityIcons name="arrow-left" size={22} color={theme.primary} />
        </Pressable>

        {/* Header */}
        <View style={s.topSection}>
          <Text style={s.brandName}>Exoinvite</Text>
          <Text style={s.title}>Akses Akun Owner</Text>
          <Text style={s.subtitle}>
            Untuk {intentLabel}, silakan masuk atau buat akun baru.
          </Text>
        </View>

        {/* Options card */}
        <View style={s.card}>

          {/* Masuk */}
          <Pressable
            onPress={() => navigation.navigate('Login')}
            style={({ pressed }) => [s.optionBtn, s.optionBtnPrimary, pressed && s.pressed]}
          >
            <View style={[s.optionIcon, s.optionIconPrimary]}>
              <MaterialCommunityIcons name="login-variant" size={20} color="#FFFFFF" />
            </View>
            <View style={s.optionContent}>
              <Text style={[s.optionTitle, s.optionTitlePrimary]}>Masuk</Text>
              <Text style={s.optionSub}>Gunakan email dan password Anda</Text>
            </View>
            <MaterialCommunityIcons name="chevron-right" size={20} color="#FFFFFF" />
          </Pressable>

          {/* Daftar */}
          <Pressable
            onPress={() => navigation.navigate('Register')}
            style={({ pressed }) => [s.optionBtn, s.optionBtnSecondary, pressed && s.pressed]}
          >
            <View style={[s.optionIcon, s.optionIconSecondary]}>
              <MaterialCommunityIcons name="account-plus-outline" size={20} color={theme.primary} />
            </View>
            <View style={s.optionContent}>
              <Text style={[s.optionTitle, s.optionTitleSecondary]}>Daftar Akun</Text>
              <Text style={s.optionSubSecondary}>Buat akun baru dalam hitungan detik</Text>
            </View>
            <MaterialCommunityIcons name="chevron-right" size={20} color={theme.primary} />
          </Pressable>

          {/* Divider */}
          <View style={s.dividerRow}>
            <View style={s.dividerLine} />
            <Text style={s.dividerText}>atau</Text>
            <View style={s.dividerLine} />
          </View>

          {/* Kode Akses */}
          <Pressable
            onPress={() => navigation.navigate('ConnectDevice')}
            style={({ pressed }) => [s.optionBtn, s.optionBtnTertiary, pressed && s.pressed]}
          >
            <View style={[s.optionIcon, s.optionIconTertiary]}>
              <MaterialCommunityIcons name="link-variant" size={20} color={theme.amber} />
            </View>
            <View style={s.optionContent}>
              <Text style={[s.optionTitle, s.optionTitleTertiary]}>Masuk dengan Kode Akses</Text>
              <Text style={s.optionSubTertiary}>Hubungkan perangkat owner dengan aman</Text>
            </View>
            <MaterialCommunityIcons name="chevron-right" size={20} color={theme.amber} />
          </Pressable>
        </View>

        {/* Footnote */}
        <Text style={s.footnote}>
          🔒 Sesi login diamankan per perangkat dan dapat dicabut kapan saja dari dashboard.
        </Text>
      </View>
    </SafeAreaView>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme'], isCompact: boolean) {
  return StyleSheet.create({
    safeArea: { flex: 1, backgroundColor: t.background },
    container: {
      flex: 1,
      paddingHorizontal: isCompact ? 18 : 24,
      paddingTop: 16,
      paddingBottom: isCompact ? 24 : 32,
      justifyContent: 'center',
      gap: isCompact ? 22 : 28,
    },

    backBtn: {
      width: 40,
      height: 40,
      borderRadius: 12,
      backgroundColor: t.surfaceContainerHighest,
      alignItems: 'center',
      justifyContent: 'center',
      alignSelf: 'flex-start',
    },

    topSection: { gap: isCompact ? 8 : 10 },
    brandName: {
      color: t.primary,
      fontSize: isCompact ? 18 : 22,
      fontFamily: F.display,
      letterSpacing: -0.5,
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
      fontSize: isCompact ? 14 : 15,
      lineHeight: isCompact ? 21 : 23,
      fontFamily: F.body,
    },

    card: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: isCompact ? 20 : 24,
      padding: isCompact ? 14 : 16,
      gap: isCompact ? 8 : 10,
    },

    optionBtn: {
      flexDirection: 'row',
      borderRadius: 16,
      paddingVertical: 14,
      paddingHorizontal: 14,
      alignItems: 'center',
      gap: 12,
    },
    optionBtnPrimary: { backgroundColor: t.primary },
    optionBtnSecondary: {
      backgroundColor: t.surfaceContainerHighest,
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },
    optionBtnTertiary: {
      backgroundColor: t.amberBg,
      borderWidth: 1,
      borderColor: t.amberBorder,
    },

    optionIcon: {
      width: 40,
      height: 40,
      borderRadius: 12,
      alignItems: 'center',
      justifyContent: 'center',
      flexShrink: 0,
    },
    optionIconPrimary: { backgroundColor: 'rgba(255,255,255,0.22)' },
    optionIconSecondary: { backgroundColor: t.isDark ? 'rgba(192,132,252,0.15)' : '#EDE0FF' },
    optionIconTertiary: { backgroundColor: t.isDark ? 'rgba(251,191,36,0.15)' : '#FEF3C7' },

    optionContent: { flex: 1, gap: 2 },
    optionTitle: {
      fontSize: isCompact ? 15 : 16,
      fontFamily: F.heading,
    },
    optionTitlePrimary: { color: '#FFFFFF' },
    optionTitleSecondary: { color: t.primary },
    optionTitleTertiary: { color: t.amber },

    optionSub: { color: 'rgba(255,255,255,0.8)', fontSize: isCompact ? 11 : 12, lineHeight: 17, fontFamily: F.body },
    optionSubSecondary: { color: t.onSurfaceVariant, fontSize: isCompact ? 11 : 12, lineHeight: 17, fontFamily: F.body },
    optionSubTertiary: { color: t.amber, fontSize: isCompact ? 11 : 12, lineHeight: 17, opacity: 0.85, fontFamily: F.body },

    dividerRow: { flexDirection: 'row', alignItems: 'center', gap: 12, marginVertical: 2 },
    dividerLine: { flex: 1, height: 1, backgroundColor: t.outlineVariant, opacity: 0.5 },
    dividerText: { color: t.outline, fontSize: 12, fontFamily: F.label },

    footnote: {
      color: t.outline,
      fontSize: isCompact ? 11 : 12,
      lineHeight: isCompact ? 16 : 18,
      textAlign: 'center',
      paddingHorizontal: 8,
      fontFamily: F.body,
    },

    pressed: { opacity: 0.86, transform: [{ scale: 0.985 }] },
  });
}
