import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { Pressable, StyleSheet, Text, View } from 'react-native';

import type { GuestStackParamList } from '../navigation/types';
import { ScreenContainer } from '../shared/components/ScreenContainer';
import { colors } from '../shared/theme/colors';

const themeSamples = [
  {
    id: 'modern-elegance',
    name: 'Modern Elegance',
    vibe: 'Minimal dan premium',
    icon: '🖤',
    colors: ['#1a1a1a', '#ffffff', '#e5e5e5'],
  },
  {
    id: 'royal-gold',
    name: 'Royal Gold',
    vibe: 'Mewah dan klasik',
    icon: '👑',
    colors: ['#2d1810', '#d4af37', '#3d2817'],
  },
  {
    id: 'sage-garden',
    name: 'Sage Garden',
    vibe: 'Natural dan hangat',
    icon: '🌿',
    colors: ['#5a6e54', '#d4a574', '#a8b89f'],
  },
  {
    id: 'white-blossom',
    name: 'White Blossom',
    vibe: 'Bersih dan romantis',
    icon: '🌸',
    colors: ['#f5f5f5', '#ff6b9d', '#ffd1e8'],
  },
];

export function ThemeCatalogScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<GuestStackParamList>>();

  return (
    <ScreenContainer>
      <View style={styles.headerSection}>
        <Text style={styles.eyebrow}>🎨 Koleksi Premium</Text>
        <Text style={styles.title}>Galeri Tema</Text>
        <Text style={styles.subtitle}>
          Pilih tema yang sempurna untuk undangan Anda. Setiap tema dirancang dengan detail yang memukau.
        </Text>
      </View>

      <View style={styles.themesContainer}>
        {themeSamples.map((theme) => (
          <Pressable
            key={theme.id}
            onPress={() => navigation.navigate('AuthChoice', { intent: 'theme' })}
            style={({ pressed }) => [
              styles.themeCard,
              pressed && styles.themeCardPressed,
            ]}
          >
            <View style={styles.themeColorPreview}>
              <View style={[styles.colorSample, { backgroundColor: theme.colors[0] }]} />
              <View style={[styles.colorSample, { backgroundColor: theme.colors[1] }]} />
              <View style={[styles.colorSample, { backgroundColor: theme.colors[2] }]} />
            </View>
            <Text style={styles.themeIcon}>{theme.icon}</Text>
            <Text style={styles.themeName}>{theme.name}</Text>
            <Text style={styles.themeVibe}>{theme.vibe}</Text>
            <View style={styles.ctaButton}>
              <Text style={styles.ctaButtonText}>Pilih Tema →</Text>
            </View>
          </Pressable>
        ))}
      </View>
    </ScreenContainer>
  );
}

const styles = StyleSheet.create({
  headerSection: {
    marginHorizontal: -20,
    marginTop: -16,
    marginBottom: 28,
    backgroundColor: `${colors.accent}10`,
    paddingHorizontal: 20,
    paddingVertical: 28,
    borderBottomLeftRadius: 24,
    borderBottomRightRadius: 24,
    gap: 10,
  },
  eyebrow: {
    color: colors.accent,
    fontSize: 13,
    fontWeight: '700',
    letterSpacing: 0.5,
  },
  title: {
    color: colors.textPrimary,
    fontSize: 28,
    fontWeight: '800',
  },
  subtitle: {
    color: colors.textSecondary,
    fontSize: 15,
    lineHeight: 22,
  },
  themesContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 12,
    justifyContent: 'space-between',
  },
  themeCard: {
    width: '48%',
    backgroundColor: colors.surface,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: colors.border,
    padding: 14,
    gap: 10,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOpacity: 0.05,
    shadowRadius: 4,
    shadowOffset: { width: 0, height: 2 },
    elevation: 2,
  },
  themeCardPressed: {
    backgroundColor: `${colors.accent}08`,
    borderColor: colors.accent,
    transform: [{ scale: 0.97 }],
  },
  themeColorPreview: {
    width: '100%',
    height: 60,
    flexDirection: 'row',
    gap: 6,
    borderRadius: 10,
    overflow: 'hidden',
    backgroundColor: colors.border,
  },
  colorSample: {
    flex: 1,
  },
  themeIcon: {
    fontSize: 28,
    marginTop: 4,
  },
  themeName: {
    color: colors.textPrimary,
    fontSize: 15,
    fontWeight: '700',
    textAlign: 'center',
  },
  themeVibe: {
    color: colors.textSecondary,
    fontSize: 12,
    textAlign: 'center',
    lineHeight: 16,
  },
  ctaButton: {
    marginTop: 6,
    paddingVertical: 8,
    paddingHorizontal: 12,
    backgroundColor: `${colors.accent}15`,
    borderRadius: 8,
  },
  ctaButtonText: {
    color: colors.accent,
    fontSize: 12,
    fontWeight: '700',
  },
});
