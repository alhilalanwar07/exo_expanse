import { useState, useEffect, useCallback } from 'react';
import {
  View,
  Text,
  FlatList,
  Pressable,
  StyleSheet,
  TextInput,
  Image,
  ActivityIndicator,
  ScrollView,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import { env } from '../config/env';
import type { RootStackParamList } from '../navigation/types';

// ── Types ─────────────────────────────────────────────────────────────────────

export type ThemeItem = {
  id: number;
  name: string;
  slug: string;
  is_premium: boolean;
  thumbnail_url: string | null;
  preview_url: string | null;
  colors: {
    primary: string | null;
    secondary: string | null;
    accent: string | null;
    background: string | null;
  };
};

const CATEGORIES = ['Semua', 'Gratis', 'Premium'] as const;
type Category = typeof CATEGORIES[number];

// ── API ───────────────────────────────────────────────────────────────────────

async function fetchThemes(): Promise<ThemeItem[]> {
  const res = await fetch(`${env.apiBaseUrl}/api/mobile/themes`, {
    headers: { Accept: 'application/json' },
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  const json = (await res.json()) as { data: ThemeItem[] };
  return json.data;
}

// ── Component ─────────────────────────────────────────────────────────────────

type NavProp = NativeStackNavigationProp<RootStackParamList>;

// ── ThemeCard subcomponent (needs useState → must be a proper component) ─────

type ThemeCardProps = {
  item: ThemeItem;
  onPress: (item: ThemeItem) => void;
  theme: ReturnType<typeof useAppTheme>['theme'];
  s: ReturnType<typeof makeStyles>;
};

function ThemeCard({ item, onPress, theme, s }: ThemeCardProps) {
  const [imgError, setImgError] = useState(false);
  const showPlaceholder = !item.thumbnail_url || imgError;

  return (
    <Pressable
      style={({ pressed }) => [s.card, pressed && s.pressed]}
      onPress={() => onPress(item)}
    >
      {/* Thumbnail */}
      <View style={s.imageWrap}>
        {!showPlaceholder ? (
          <Image
            source={{ uri: item.thumbnail_url ?? undefined }}
            style={s.image}
            resizeMode="cover"
            onError={() => setImgError(true)}
          />
        ) : (
          <View style={s.imagePlaceholder}>
            <MaterialCommunityIcons name="image-outline" size={32} color={theme.outline} />
          </View>
        )}
        {/* Badge */}
        <View style={[s.badge, item.is_premium ? s.badgePremium : s.badgeFree]}>
          <Text style={[s.badgeText, item.is_premium ? s.badgeTextPremium : s.badgeTextFree]}>
            {item.is_premium ? 'PREMIUM' : 'GRATIS'}
          </Text>
        </View>

        {/* Tap overlay hint */}
        <View style={s.tapOverlay}>
          <Ionicons name="eye-outline" size={16} color="#FFFFFF" />
          <Text style={s.tapOverlayText}>Lihat Demo</Text>
        </View>
      </View>

      {/* Name */}
      <Text style={s.cardTitle} numberOfLines={1}>{item.name}</Text>

      {/* Color swatches */}
      {item.colors.primary ? (
        <View style={s.swatchRow}>
          {[item.colors.primary, item.colors.secondary, item.colors.accent]
            .filter(Boolean)
            .slice(0, 3)
            .map((c, i) => (
              <View key={i} style={[s.swatch, { backgroundColor: c as string }]} />
            ))}
        </View>
      ) : null}
    </Pressable>
  );
}

export function HomeScreen() {
  const navigation = useNavigation<NavProp>();
  const { theme } = useAppTheme();
  const s = makeStyles(theme);

  const [themes, setThemes] = useState<ThemeItem[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [hasError, setHasError] = useState(false);
  const [search, setSearch] = useState('');
  const [activeCategory, setActiveCategory] = useState<Category>('Semua');

  const load = useCallback(async () => {
    try {
      setIsLoading(true);
      setHasError(false);
      setThemes(await fetchThemes());
    } catch {
      setHasError(true);
    } finally {
      setIsLoading(false);
    }
  }, []);

  useEffect(() => { void load(); }, [load]);

  const filtered = themes.filter((t) => {
    const matchSearch = t.name.toLowerCase().includes(search.toLowerCase());
    const matchCat =
      activeCategory === 'Semua' ||
      (activeCategory === 'Premium' && t.is_premium) ||
      (activeCategory === 'Gratis' && !t.is_premium);
    return matchSearch && matchCat;
  });

  const openPreview = (item: ThemeItem) => {
    navigation.navigate('ThemePreview', {
      id: item.id,
      name: item.name,
      previewUrl: item.preview_url ?? `${env.apiBaseUrl}/i/demo`,
      isPremium: item.is_premium,
    });
  };

  const renderCard = ({ item }: { item: ThemeItem }) => (
    <ThemeCard item={item} onPress={openPreview} theme={theme} s={s} />
  );

  return (
    <SafeAreaView style={s.safeArea} edges={['top']}>
      {/* ── Header ─────────────────────────────────────── */}
      <View style={s.header}>
        <View>
          <Text style={s.headerEyebrow}>Katalog Tema</Text>
          <Text style={s.headerTitle}>Pilih Tema Undangan</Text>
        </View>
        <Pressable
          style={({ pressed }) => [s.iconBtn, pressed && s.pressed]}
          onPress={() => void load()}
        >
          <Ionicons name="refresh-outline" size={20} color={theme.onSurface} />
        </Pressable>
      </View>

      {/* ── Search ─────────────────────────────────────── */}
      <View style={s.searchWrap}>
        <Ionicons name="search" size={18} color={theme.outline} />
        <TextInput
          style={s.searchInput}
          placeholder="Cari tema..."
          placeholderTextColor={theme.outline}
          value={search}
          onChangeText={setSearch}
          autoCorrect={false}
          returnKeyType="search"
        />
        {search.length > 0 && (
          <Pressable onPress={() => setSearch('')} hitSlop={8}>
            <Ionicons name="close-circle" size={18} color={theme.outline} />
          </Pressable>
        )}
      </View>

      {/* ── Category chips ─────────────────────────────── */}
      <ScrollView
        horizontal
        showsHorizontalScrollIndicator={false}
        contentContainerStyle={s.chipList}
      >
        {CATEGORIES.map((cat) => (
          <Pressable
            key={cat}
            onPress={() => setActiveCategory(cat)}
            style={[s.chip, activeCategory === cat && s.chipActive]}
          >
            <Text style={[s.chipText, activeCategory === cat && s.chipTextActive]}>{cat}</Text>
          </Pressable>
        ))}
      </ScrollView>

      {/* ── Content ────────────────────────────────────── */}
      {isLoading ? (
        <View style={s.stateBox}>
          <ActivityIndicator size="large" color={theme.primary} />
          <Text style={s.stateSub}>Memuat tema...</Text>
        </View>
      ) : hasError ? (
        <View style={s.stateBox}>
          <MaterialCommunityIcons name="wifi-off" size={52} color={theme.outline} />
          <Text style={s.stateTitle}>Gagal Memuat</Text>
          <Text style={s.stateSub}>Periksa koneksi internet Anda.</Text>
          <Pressable
            onPress={() => void load()}
            style={({ pressed }) => [s.retryBtn, pressed && s.pressed]}
          >
            <Text style={s.retryText}>Coba Lagi</Text>
          </Pressable>
        </View>
      ) : (
        <FlatList
          data={filtered}
          keyExtractor={(item) => item.id.toString()}
          numColumns={2}
          contentContainerStyle={s.grid}
          columnWrapperStyle={s.gridRow}
          showsVerticalScrollIndicator={false}
          ListEmptyComponent={
            <View style={s.stateBox}>
              <MaterialCommunityIcons name="magnify-close" size={48} color={theme.outline} />
              <Text style={s.stateTitle}>Tidak Ditemukan</Text>
              <Text style={s.stateSub}>Coba kata kunci lain.</Text>
            </View>
          }
          renderItem={renderCard}
        />
      )}
    </SafeAreaView>
  );
}

// ── Styles ────────────────────────────────────────────────────────────────────

function makeStyles(t: ReturnType<typeof useAppTheme>['theme']) {
  return StyleSheet.create({
    safeArea: { flex: 1, backgroundColor: t.background },

    // Header
    header: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      paddingHorizontal: 18,
      paddingTop: 10,
      paddingBottom: 6,
    },
    headerEyebrow: {
      fontFamily: F.label,
      fontSize: 10,
      letterSpacing: 1,
      textTransform: 'uppercase',
      color: t.primary,
    },
    headerTitle: {
      fontFamily: F.heading,
      fontSize: 21,
      color: t.onSurface,
      marginTop: 2,
    },
    iconBtn: {
      width: 38,
      height: 38,
      borderRadius: 12,
      backgroundColor: t.surfaceContainerLow,
      alignItems: 'center',
      justifyContent: 'center',
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },

    // Search
    searchWrap: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 10,
      backgroundColor: t.searchBg,
      borderRadius: 14,
      marginHorizontal: 18,
      paddingHorizontal: 14,
      height: 46,
      marginBottom: 10,
    },
    searchInput: {
      flex: 1,
      fontFamily: F.body,
      fontSize: 14,
      color: t.onSurface,
      paddingVertical: 0,
    },

    // Chips
    chipList: {
      paddingHorizontal: 18,
      gap: 8,
      paddingBottom: 10,
    },
    chip: {
      height: 34,
      paddingHorizontal: 16,
      borderRadius: 17,
      backgroundColor: t.chipBg,
      alignItems: 'center',
      justifyContent: 'center',
    },
    chipActive: { backgroundColor: t.chipActiveBg },
    chipText: { fontFamily: F.label, fontSize: 13, color: t.chipText },
    chipTextActive: { color: '#FFFFFF' },

    // Grid — bottom padding accounts for floating tab bar (68px height + 16px offset + 16px gap)
    grid: {
      paddingHorizontal: 18,
      paddingBottom: 120,
      paddingTop: 2,
    },
    gridRow: { gap: 14, marginBottom: 14 },

    // Card
    card: { flex: 1 },
    imageWrap: {
      borderRadius: 18,
      overflow: 'hidden',
      backgroundColor: t.imagePlaceholder,
      aspectRatio: 0.62,
    },
    image: { width: '100%', height: '100%' },
    imagePlaceholder: {
      flex: 1,
      alignItems: 'center',
      justifyContent: 'center',
    },
    badge: {
      position: 'absolute',
      top: 8,
      right: 8,
      paddingHorizontal: 8,
      paddingVertical: 4,
      borderRadius: 8,
    },
    badgePremium: { backgroundColor: '#D93723' },
    badgeFree: { backgroundColor: t.isDark ? 'rgba(255,255,255,0.18)' : 'rgba(255,255,255,0.9)' },
    badgeText: { fontFamily: F.labelBold, fontSize: 8, letterSpacing: 0.6 },
    badgeTextPremium: { color: '#FFFFFF' },
    badgeTextFree: { color: t.primary },
    tapOverlay: {
      position: 'absolute',
      bottom: 0,
      left: 0,
      right: 0,
      paddingVertical: 10,
      paddingHorizontal: 12,
      backgroundColor: 'rgba(0,0,0,0.38)',
      flexDirection: 'row',
      alignItems: 'center',
      gap: 5,
    },
    tapOverlayText: {
      fontFamily: F.labelBold,
      fontSize: 11,
      color: '#FFFFFF',
    },
    cardTitle: {
      fontFamily: F.subheading,
      fontSize: 13,
      color: t.cardTitle,
      marginTop: 8,
    },
    swatchRow: { flexDirection: 'row', gap: 4, marginTop: 4 },
    swatch: { width: 12, height: 12, borderRadius: 6 },

    // State screens
    stateBox: {
      flex: 1,
      alignItems: 'center',
      justifyContent: 'center',
      paddingHorizontal: 32,
      gap: 10,
      marginTop: 48,
    },
    stateTitle: { fontFamily: F.heading, fontSize: 18, color: t.onSurface },
    stateSub: { fontFamily: F.body, fontSize: 14, color: t.onSurfaceVariant, textAlign: 'center' },
    retryBtn: {
      marginTop: 6,
      height: 46,
      paddingHorizontal: 28,
      borderRadius: 999,
      backgroundColor: t.primary,
      alignItems: 'center',
      justifyContent: 'center',
    },
    retryText: { fontFamily: F.labelBold, fontSize: 14, color: '#FFFFFF' },

    pressed: { opacity: 0.84, transform: [{ scale: 0.97 }] },
  });
}
