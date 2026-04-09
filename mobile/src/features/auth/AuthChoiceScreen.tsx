import { useNavigation, useRoute } from '@react-navigation/native';
import type { RouteProp } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { Pressable, StyleSheet, Text, useWindowDimensions, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';

import type { GuestStackParamList } from '../../navigation/types';
import { F } from '../../shared/theme/fonts';

// ── Design tokens ────────────────────────────────────────────────────────────
const C = {
  background: '#FFF7FC',
  surfaceContainerLow: '#FEEFFF',
  surfaceContainerHighest: '#F2DBFA',
  primary: '#630ED4',
  primaryContainer: '#7C3AED',
  onPrimary: '#FFFFFF',
  onSurface: '#24162C',
  onSurfaceVariant: '#4A4455',
  outline: '#7B7487',
  outlineVariant: '#CCC3D8',
  teal: '#0D9DAD',
  tealBg: '#E6F7F9',
  amber: '#B45309',
  amberBg: '#FDF3E2',
} as const;

export function AuthChoiceScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<GuestStackParamList>>();
  const route = useRoute<RouteProp<GuestStackParamList, 'AuthChoice'>>();
  const { width } = useWindowDimensions();
  const isCompact = width <= 390;

  const intentLabel =
    route.params?.intent === 'theme'
      ? 'melanjutkan pemilihan tema'
      : 'mengelola undangan Anda';

  return (
    <SafeAreaView style={styles.safeArea} edges={['top', 'bottom']}>
      <View style={[styles.container, isCompact && styles.containerCompact]}>

        {/* Back button */}
        <Pressable
          onPress={() => navigation.goBack()}
          style={({ pressed }) => [styles.backBtn, pressed && styles.pressed]}
        >
          <MaterialCommunityIcons name="arrow-left" size={22} color={C.primary} />
        </Pressable>

        {/* Brand + header */}
        <View style={[styles.topSection, isCompact && styles.topSectionCompact]}>
          <Text style={[styles.brandName, isCompact && styles.brandNameCompact]}>Exoinvite</Text>
          <Text style={[styles.title, isCompact && styles.titleCompact]}>Akses Akun Owner</Text>
          <Text style={[styles.subtitle, isCompact && styles.subtitleCompact]}>
            Untuk {intentLabel}, silakan masuk atau buat akun baru.
          </Text>
        </View>

        {/* Options card */}
        <View style={[styles.card, isCompact && styles.cardCompact]}>

          {/* Masuk */}
          <Pressable
            onPress={() => navigation.navigate('Login')}
            style={({ pressed }) => [styles.optionBtn, styles.optionBtnPrimary, pressed && styles.pressed]}
          >
            <View style={[styles.optionIcon, styles.optionIconPrimary]}>
              <MaterialCommunityIcons name="login-variant" size={20} color={C.onPrimary} />
            </View>
            <View style={styles.optionContent}>
              <Text style={[styles.optionTitle, styles.optionTitlePrimary, isCompact && styles.optionTitleCompact]}>
                Masuk
              </Text>
              <Text style={[styles.optionSub, isCompact && styles.optionSubCompact]}>
                Gunakan email dan password Anda
              </Text>
            </View>
            <MaterialCommunityIcons name="chevron-right" size={20} color={C.onPrimary} />
          </Pressable>

          {/* Daftar Akun */}
          <Pressable
            onPress={() => navigation.navigate('Register')}
            style={({ pressed }) => [styles.optionBtn, styles.optionBtnSecondary, pressed && styles.pressed]}
          >
            <View style={[styles.optionIcon, styles.optionIconSecondary]}>
              <MaterialCommunityIcons name="account-plus-outline" size={20} color={C.primary} />
            </View>
            <View style={styles.optionContent}>
              <Text style={[styles.optionTitle, styles.optionTitleSecondary, isCompact && styles.optionTitleCompact]}>
                Daftar Akun
              </Text>
              <Text style={[styles.optionSubSecondary, isCompact && styles.optionSubCompact]}>
                Buat akun baru dalam hitungan detik
              </Text>
            </View>
            <MaterialCommunityIcons name="chevron-right" size={20} color={C.primary} />
          </Pressable>

          {/* Divider */}
          <View style={styles.dividerRow}>
            <View style={styles.dividerLine} />
            <Text style={styles.dividerText}>atau</Text>
            <View style={styles.dividerLine} />
          </View>

          {/* Connect Device */}
          <Pressable
            onPress={() => navigation.navigate('ConnectDevice')}
            style={({ pressed }) => [styles.optionBtn, styles.optionBtnTertiary, pressed && styles.pressed]}
          >
            <View style={[styles.optionIcon, styles.optionIconTertiary]}>
              <MaterialCommunityIcons name="link-variant" size={20} color={C.amber} />
            </View>
            <View style={styles.optionContent}>
              <Text style={[styles.optionTitle, styles.optionTitleTertiary, isCompact && styles.optionTitleCompact]}>
                Masuk dengan Kode Akses
              </Text>
              <Text style={[styles.optionSubTertiary, isCompact && styles.optionSubCompact]}>
                Hubungkan perangkat owner dengan aman
              </Text>
            </View>
            <MaterialCommunityIcons name="chevron-right" size={20} color={C.amber} />
          </Pressable>
        </View>

        {/* Security footnote */}
        <Text style={[styles.footnote, isCompact && styles.footnoteCompact]}>
          🔒 Sesi login diamankan per perangkat dan dapat dicabut kapan saja dari dashboard.
        </Text>
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: C.background },
  container: {
    flex: 1,
    paddingHorizontal: 24,
    paddingTop: 16,
    paddingBottom: 32,
    justifyContent: 'center',
    gap: 28,
  },
  containerCompact: {
    paddingHorizontal: 18,
    paddingBottom: 24,
    gap: 22,
  },

  // Back button
  backBtn: {
    width: 40,
    height: 40,
    borderRadius: 12,
    backgroundColor: C.surfaceContainerHighest,
    alignItems: 'center',
    justifyContent: 'center',
    alignSelf: 'flex-start',
  },

  // Top section
  topSection: { gap: 10 },
  topSectionCompact: { gap: 8 },
  brandName: {
    color: C.primary,
    fontSize: 22,
    fontFamily: F.display,
    letterSpacing: -0.5,
  },
  brandNameCompact: { fontSize: 18 },
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
    fontSize: 15,
    lineHeight: 23,
    fontFamily: F.body,
  },
  subtitleCompact: { fontSize: 14, lineHeight: 21 },

  // Card
  card: {
    backgroundColor: C.surfaceContainerLow,
    borderRadius: 24,
    padding: 16,
    gap: 10,
  },
  cardCompact: { borderRadius: 20, padding: 14, gap: 8 },

  // Option buttons
  optionBtn: {
    flexDirection: 'row',
    borderRadius: 16,
    paddingVertical: 14,
    paddingHorizontal: 14,
    alignItems: 'center',
    gap: 12,
  },
  optionBtnPrimary: { backgroundColor: C.primary },
  optionBtnSecondary: {
    backgroundColor: C.surfaceContainerHighest,
    borderWidth: 1,
    borderColor: C.outlineVariant,
  },
  optionBtnTertiary: {
    backgroundColor: C.amberBg,
    borderWidth: 1,
    borderColor: '#F7D8AB',
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
  optionIconSecondary: { backgroundColor: '#EDE0FF' },
  optionIconTertiary: { backgroundColor: '#FEF3C7' },

  optionContent: { flex: 1, gap: 2 },
  optionTitle: { fontSize: 16, fontFamily: F.heading },
  optionTitleCompact: { fontSize: 15 },
  optionTitlePrimary: { color: C.onPrimary },
  optionTitleSecondary: { color: C.primary },
  optionTitleTertiary: { color: C.amber },

  optionSub: { color: 'rgba(255,255,255,0.8)', fontSize: 12, lineHeight: 17, fontFamily: F.body },
  optionSubSecondary: { color: C.onSurfaceVariant, fontSize: 12, lineHeight: 17, fontFamily: F.body },
  optionSubTertiary: { color: C.amber, fontSize: 12, lineHeight: 17, opacity: 0.8, fontFamily: F.body },
  optionSubCompact: { fontSize: 11, lineHeight: 15 },

  // Divider
  dividerRow: { flexDirection: 'row', alignItems: 'center', gap: 12, marginVertical: 2 },
  dividerLine: { flex: 1, height: 1, backgroundColor: C.outlineVariant, opacity: 0.5 },
  dividerText: { color: C.outline, fontSize: 12, fontFamily: F.label },

  // Footnote
  footnote: {
    color: C.outline,
    fontSize: 12,
    lineHeight: 18,
    textAlign: 'center',
    paddingHorizontal: 8,
    fontFamily: F.body,
  },
  footnoteCompact: { fontSize: 11, lineHeight: 16 },

  pressed: { opacity: 0.86, transform: [{ scale: 0.985 }] },
});
