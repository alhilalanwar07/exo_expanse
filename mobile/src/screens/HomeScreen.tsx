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
  Modal,
  ScrollView,
  Linking,
  useWindowDimensions,
} from 'react-native';
import { SafeAreaView, useSafeAreaInsets } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';

import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import { env } from '../config/env';

// ── Types ─────────────────────────────────────────────────────────────────────

type ThemeItem = {
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

type ApiResponse = {
  data: ThemeItem[];
};

const CATEGORIES = ['Semua', 'Gratis', 'Premium'] as const;
type Category = typeof CATEGORIES[number];

// ── Helpers ───────────────────────────────────────────────────────────────────

async function fetchThemes(): Promise<ThemeItem[]> {
  const res = await fetch(`${env.apiBaseUrl}/api/mobile/themes`, {
    headers: { Accept: 'application/json' },
  });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  const json = (await res.json()) as ApiResponse;
  return json.data;
}

// ── Component ─────────────────────────────────────────────────────────────────

export function HomeScreen() {
  const { theme } = useAppTheme();
  const { width } = useWindowDimensions();
  const insets = useSafeAreaInsets();
  const isCompact = width <= 390;
  const s = makeStyles(theme, isCompact);

  const [themes, setThemes] = useState<ThemeItem[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [hasError, setHasError] = useState(false);
  const [search, setSearch] = useState('');
  const [activeCategory, setActiveCategory] = useState<Category>('Semua');
  const [selected, setSelected] = useState<ThemeItem | null>(null);

  const load = useCallback(async () => {
    try {
      setIsLoading(true);
      setHasError(false);
      const data = await fetchThemes();
      setThemes(data);
    } catch {
      setHasError(true);
    } finally {
      setIsLoading(false);
    }
  }, []);

  useEffect(() => { void load(); }, [load]);

  // Filter
  const filtered = themes.filter((t) => {
    const matchSearch = t.name.toLowerCase().includes(search.toLowerCase());
    const matchCat =
      activeCategory === 'Semua' ||
      (activeCategory === 'Premium' && t.is_premium) ||
      (activeCategory === 'Gratis' && !t.is_premium);
    return matchSearch && matchCat;
  });

  const renderCard = ({ item }: { item: ThemeItem }) => (
    <Pressable
      style={({ pressed }) => [s.card, pressed && s.cardPressed]}
      onPress={() => setSelected(item)}
    >
      <View style={s.imageWrap}>
        {item.thumbnail_url ? (
          <Image source={{ uri: item.thumbnail_url }} style={s.image} resizeMode="cover" />
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
      </View>
      <Text style={s.cardTitle} numberOfLines={1}>{item.name}</Text>
      {item.colors.primary ? (
        <View style={s.colorRow}>
          {[item.colors.primary, item.colors.secondary, item.colors.accent]
            .filter(Boolean)
            .slice(0, 3)
            .map((c, i) => (
              <View key={i} style={[s.colorDot, { backgroundColor: c as string }]} />
            ))}
        </View>
      ) : null}
    </Pressable>
  );

  return (
    <SafeAreaView style={s.safeArea} edges={['top']}>
      {/* Header */}
      <View style={s.headerRow}>
        <View>
          <Text style={s.headerEyebrow}>Katalog Tema</Text>
          <Text style={s.headerTitle}>Pilih Tema Undangan</Text>
        </View>
        <Pressable style={s.refreshBtn} onPress={() => void load()}>
          <Ionicons name="refresh-outline" size={20} color={theme.onSurface} />
        </Pressable>
      </View>

      {/* Search */}
      <View style={s.searchBox}>
        <Ionicons name="search" size={18} color={theme.outline} />
        <TextInput
          style={s.searchInput}
          placeholder="Cari tema..."
          placeholderTextColor={theme.outline}
          value={search}
          onChangeText={setSearch}
          autoCorrect={false}
        />
        {search.length > 0 && (
          <Pressable onPress={() => setSearch('')} hitSlop={8}>
            <Ionicons name="close-circle" size={18} color={theme.outline} />
          </Pressable>
        )}
      </View>

      {/* Category chips */}
      <ScrollView
        horizontal
        showsHorizontalScrollIndicator={false}
        contentContainerStyle={s.chipRow}
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

      {/* Content */}
      {isLoading ? (
        <View style={s.stateBox}>
          <ActivityIndicator size="large" color={theme.primary} />
          <Text style={s.stateText}>Memuat tema...</Text>
        </View>
      ) : hasError ? (
        <View style={s.stateBox}>
          <MaterialCommunityIcons name="wifi-off" size={48} color={theme.outline} />
          <Text style={s.stateTitle}>Gagal Memuat</Text>
          <Text style={s.stateText}>Periksa koneksi internet Anda.</Text>
          <Pressable onPress={() => void load()} style={({ pressed }) => [s.retryBtn, pressed && s.pressed]}>
            <Text style={s.retryBtnText}>Coba Lagi</Text>
          </Pressable>
        </View>
      ) : (
        <FlatList
          data={filtered}
          keyExtractor={(item) => item.id.toString()}
          numColumns={2}
          contentContainerStyle={[s.grid, { paddingBottom: insets.bottom + 100 }]}
          columnWrapperStyle={s.gridRow}
          showsVerticalScrollIndicator={false}
          renderItem={renderCard}
          ListEmptyComponent={
            <View style={s.stateBox}>
              <MaterialCommunityIcons name="magnify-close" size={48} color={theme.outline} />
              <Text style={s.stateTitle}>Tidak Ditemukan</Text>
              <Text style={s.stateText}>Coba kata kunci lain.</Text>
            </View>
          }
        />
      )}

      {/* Theme Detail Bottom Sheet */}
      {selected ? (
        <ThemeBottomSheet
          item={selected}
          theme={theme}
          onClose={() => setSelected(null)}
        />
      ) : null}
    </SafeAreaView>
  );
}

// ── Bottom Sheet ──────────────────────────────────────────────────────────────

type SheetProps = {
  item: ThemeItem;
  theme: ReturnType<typeof useAppTheme>['theme'];
  onClose: () => void;
};

function ThemeBottomSheet({ item, theme, onClose }: SheetProps) {
  const s = makeSheetStyles(theme);
  const insets = useSafeAreaInsets();

  const openPreview = async () => {
    if (!item.preview_url) return;
    const canOpen = await Linking.canOpenURL(item.preview_url);
    if (canOpen) await Linking.openURL(item.preview_url);
  };

  return (
    <Modal
      visible
      transparent
      animationType="slide"
      statusBarTranslucent
      onRequestClose={onClose}
    >
      {/* Backdrop */}
      <Pressable style={s.backdrop} onPress={onClose} />

      {/* Sheet */}
      <View style={[s.sheet, { paddingBottom: insets.bottom + 20 }]}>
        {/* Handle */}
        <View style={s.handle} />

        {/* Thumbnail preview */}
        <View style={s.previewWrap}>
          {item.thumbnail_url ? (
            <Image source={{ uri: item.thumbnail_url }} style={s.previewImage} resizeMode="cover" />
          ) : (
            <View style={s.previewPlaceholder}>
              <MaterialCommunityIcons name="image-outline" size={48} color={theme.outline} />
            </View>
          )}
          <View style={[s.previewBadge, item.is_premium ? s.badgePremium : s.badgeFree]}>
            <Text style={[s.previewBadgeText, item.is_premium ? s.badgeTextPremium : s.badgeTextFree]}>
              {item.is_premium ? 'PREMIUM' : 'GRATIS'}
            </Text>
          </View>
        </View>

        {/* Info */}
        <View style={s.info}>
          <Text style={s.themeName}>{item.name}</Text>
          {item.colors.primary ? (
            <View style={s.palette}>
              {[item.colors.primary, item.colors.secondary, item.colors.accent, item.colors.background]
                .filter(Boolean)
                .map((c, i) => (
                  <View key={i} style={[s.paletteDot, { backgroundColor: c as string }]} />
                ))}
            </View>
          ) : null}
        </View>

        {/* Actions */}
        <View style={s.actions}>
          <Pressable
            style={({ pressed }) => [s.btnOutline, pressed && s.pressed]}
            onPress={openPreview}
            disabled={!item.preview_url}
          >
            <Ionicons name="eye-outline" size={18} color={theme.primary} />
            <Text style={s.btnOutlineText}>Lihat Demo</Text>
          </Pressable>

          <Pressable
            style={({ pressed }) => [s.btnPrimary, pressed && s.pressed]}
            onPress={() => {
              // TODO: wire to apply theme flow
              onClose();
            }}
          >
            <MaterialCommunityIcons name="check-circle-outline" size={18} color="#FFFFFF" />
            <Text style={s.btnPrimaryText}>Gunakan Tema</Text>
          </Pressable>
        </View>
      </View>
    </Modal>
  );
}

// ── Styles ────────────────────────────────────────────────────────────────────

function makeStyles(t: ReturnType<typeof useAppTheme>['theme'], isCompact: boolean) {
  return StyleSheet.create({
    safeArea: { flex: 1, backgroundColor: t.background },

    // Header
    headerRow: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      paddingHorizontal: 18,
      paddingTop: 12,
      paddingBottom: 8,
    },
    headerEyebrow: {
      fontFamily: F.label,
      fontSize: 10,
      letterSpacing: 1,
      textTransform: 'uppercase',
      color: t.primary,
    },
    headerTitle: {
      fontFamily: F.display,
      fontSize: isCompact ? 20 : 22,
      color: t.onSurface,
      marginTop: 2,
    },
    refreshBtn: {
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
    searchBox: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 10,
      backgroundColor: t.searchBg,
      borderRadius: 14,
      marginHorizontal: 18,
      paddingHorizontal: 14,
      height: 46,
      marginBottom: 12,
    },
    searchInput: {
      flex: 1,
      fontFamily: F.body,
      fontSize: 14,
      color: t.onSurface,
    },

    // Chips
    chipRow: {
      paddingHorizontal: 18,
      gap: 8,
      paddingBottom: 12,
    },
    chip: {
      height: 36,
      paddingHorizontal: 16,
      borderRadius: 18,
      backgroundColor: t.chipBg,
      alignItems: 'center',
      justifyContent: 'center',
    },
    chipActive: { backgroundColor: t.chipActiveBg },
    chipText: { fontFamily: F.label, fontSize: 13, color: t.chipText },
    chipTextActive: { color: '#FFFFFF' },

    // Grid
    grid: { paddingHorizontal: 18, paddingTop: 4 },
    gridRow: { gap: 14, marginBottom: 14 },

    // Card
    card: { flex: 1 },
    cardPressed: { opacity: 0.85, transform: [{ scale: 0.97 }] },
    imageWrap: {
      borderRadius: 18,
      overflow: 'hidden',
      backgroundColor: t.imagePlaceholder,
      aspectRatio: 0.62,
      position: 'relative',
    },
    image: { width: '100%', height: '100%' },
    imagePlaceholder: {
      width: '100%',
      height: '100%',
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
    badgeFree: { backgroundColor: t.isDark ? 'rgba(255,255,255,0.15)' : 'rgba(255,255,255,0.9)' },
    badgeText: { fontFamily: F.labelBold, fontSize: 8, letterSpacing: 0.6 },
    badgeTextPremium: { color: '#FFFFFF' },
    badgeTextFree: { color: t.primary },
    cardTitle: {
      fontFamily: F.heading,
      fontSize: 13,
      color: t.cardTitle,
      marginTop: 8,
    },
    colorRow: { flexDirection: 'row', gap: 4, marginTop: 4 },
    colorDot: { width: 12, height: 12, borderRadius: 6 },

    // State screens
    stateBox: {
      flex: 1,
      alignItems: 'center',
      justifyContent: 'center',
      paddingHorizontal: 32,
      gap: 10,
      paddingTop: 60,
    },
    stateTitle: { fontFamily: F.display, fontSize: 18, color: t.onSurface },
    stateText: { fontFamily: F.body, fontSize: 14, color: t.onSurfaceVariant, textAlign: 'center' },
    retryBtn: {
      marginTop: 8,
      height: 46,
      paddingHorizontal: 28,
      borderRadius: 999,
      backgroundColor: t.primary,
      alignItems: 'center',
      justifyContent: 'center',
    },
    retryBtnText: { fontFamily: F.labelBold, fontSize: 14, color: '#FFFFFF' },

    pressed: { opacity: 0.84, transform: [{ scale: 0.97 }] },
  });
}

function makeSheetStyles(t: ReturnType<typeof useAppTheme>['theme']) {
  return StyleSheet.create({
    backdrop: {
      ...StyleSheet.absoluteFillObject,
      backgroundColor: 'rgba(0,0,0,0.55)',
    },
    sheet: {
      position: 'absolute',
      left: 0,
      right: 0,
      bottom: 0,
      backgroundColor: t.surface,
      borderTopLeftRadius: 28,
      borderTopRightRadius: 28,
      paddingHorizontal: 20,
      paddingTop: 12,
      gap: 16,
      shadowColor: '#000',
      shadowOpacity: 0.4,
      shadowRadius: 24,
      shadowOffset: { width: 0, height: -8 },
      elevation: 20,
    },
    handle: {
      alignSelf: 'center',
      width: 40,
      height: 4,
      borderRadius: 2,
      backgroundColor: t.outlineVariant,
      marginBottom: 4,
    },

    previewWrap: {
      width: '100%',
      aspectRatio: 16 / 9,
      borderRadius: 18,
      overflow: 'hidden',
      backgroundColor: t.imagePlaceholder,
      position: 'relative',
    },
    previewImage: { width: '100%', height: '100%' },
    previewPlaceholder: {
      flex: 1,
      alignItems: 'center',
      justifyContent: 'center',
    },
    previewBadge: {
      position: 'absolute',
      top: 10,
      right: 10,
      paddingHorizontal: 10,
      paddingVertical: 5,
      borderRadius: 8,
    },
    previewBadgeText: { fontFamily: F.labelBold, fontSize: 9, letterSpacing: 0.6 },
    badgePremium: { backgroundColor: '#D93723' },
    badgeFree: { backgroundColor: t.isDark ? 'rgba(255,255,255,0.2)' : 'rgba(255,255,255,0.9)' },
    badgeTextPremium: { color: '#FFFFFF' },
    badgeTextFree: { color: t.primary },

    info: { gap: 8 },
    themeName: { fontFamily: F.display, fontSize: 22, color: t.onSurface },
    palette: { flexDirection: 'row', gap: 6 },
    paletteDot: { width: 20, height: 20, borderRadius: 10 },

    actions: { flexDirection: 'row', gap: 12 },
    btnOutline: {
      flex: 1,
      height: 52,
      borderRadius: 999,
      borderWidth: 1.5,
      borderColor: t.primary,
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 8,
    },
    btnOutlineText: { fontFamily: F.labelBold, fontSize: 14, color: t.primary },
    btnPrimary: {
      flex: 1,
      height: 52,
      borderRadius: 999,
      backgroundColor: t.primary,
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 8,
      shadowColor: t.primary,
      shadowOpacity: 0.3,
      shadowRadius: 10,
      shadowOffset: { width: 0, height: 5 },
      elevation: 5,
    },
    btnPrimaryText: { fontFamily: F.labelBold, fontSize: 14, color: '#FFFFFF' },

    pressed: { opacity: 0.84, transform: [{ scale: 0.97 }] },
  });
}
