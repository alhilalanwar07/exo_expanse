import { useState, useCallback } from 'react';
import {
  View,
  Text,
  StyleSheet,
  FlatList,
  ActivityIndicator,
  Pressable,
  Image,
  Alert,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useNavigation, useFocusEffect } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import { readAuthSession } from '../features/auth/auth.storage';
import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import { env } from '../config/env';

type RootStackParamList = {
  Login: undefined;
  Home: undefined;
};

type InvitationItem = {
  id: string | number;
  title: string;
  theme_name: string;
  date: string;
  url: string;
  status: string;
  thumbnail: string | null;
};

type StatsData = {
  total: number;
  guests: number;
  rsvp: number;
  wishes: number;
};

const STAT_ITEMS = [
  { key: 'total', icon: 'mail' as const, label: 'Total Undangan' },
  { key: 'guests', icon: 'people' as const, label: 'Total Tamu' },
  { key: 'rsvp', icon: 'checkmark-circle' as const, label: 'Tamu Hadir' },
  { key: 'wishes', icon: 'chatbubbles' as const, label: 'Total Ucapan' },
] as const;

export function UndanganScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<RootStackParamList>>();
  const { theme } = useAppTheme();

  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [isLoggedIn, setIsLoggedIn] = useState<boolean>(false);
  const [invitations, setInvitations] = useState<InvitationItem[]>([]);
  const [stats, setStats] = useState<StatsData>({ total: 0, guests: 0, rsvp: 0, wishes: 0 });

  const s = makeStyles(theme);

  useFocusEffect(
    useCallback(() => {
      let isActive = true;

      const fetchData = async () => {
        try {
          setIsLoading(true);
          const session = await readAuthSession();

          if (!session?.accessToken) {
            if (isActive) { setIsLoggedIn(false); setIsLoading(false); }
            return;
          }

          if (isActive) setIsLoggedIn(true);

          const response = await fetch(`${env.apiBaseUrl}/api/mobile/access/invitations`, {
            headers: {
              Authorization: `Bearer ${session.accessToken}`,
              Accept: 'application/json',
            },
          });

          if (!response.ok) throw new Error(`API returned status ${response.status}`);

          const responseData = await response.json() as {
            data?: Array<{
              id: string | number;
              title?: string;
              theme?: string;
              theme_name?: string;
              date?: string;
              url?: string;
              slug?: string;
              status?: string;
              thumbnail?: string | null;
            }>;
            stats?: {
              total_undangan?: number;
              total_tamu?: number;
              tamu_hadir?: number;
              total_ucapan?: number;
            };
          };

          if (isActive) {
            console.log(`✅ API Connected: ${responseData.data?.length ?? 0} undangan dimuat.`);

            const list: InvitationItem[] = (responseData.data ?? []).map((item) => ({
              id: item.id,
              title: item.title ?? 'Undangan',
              theme_name: item.theme ?? item.theme_name ?? 'Tema',
              date: item.date ?? 'TBA',
              url: item.url ?? `${env.apiBaseUrl}/i/${item.slug ?? ''}`,
              status: item.status ?? 'Draf',
              thumbnail: item.thumbnail ?? null,
            }));

            setInvitations(list);

            if (responseData.stats) {
              setStats({
                total: responseData.stats.total_undangan ?? 0,
                guests: responseData.stats.total_tamu ?? 0,
                rsvp: responseData.stats.tamu_hadir ?? 0,
                wishes: responseData.stats.total_ucapan ?? 0,
              });
            }
          }
        } catch (error) {
          console.error('Error fetching invitations:', error);
          if (isActive) {
            Alert.alert('Gagal Memuat', 'Terjadi kesalahan saat mengambil data undangan.');
            setInvitations([]);
          }
        } finally {
          if (isActive) setIsLoading(false);
        }
      };

      void fetchData();
      return () => { isActive = false; };
    }, [])
  );

  const renderListHeader = () => (
    <View style={s.statsGrid}>
      {STAT_ITEMS.map(({ key, icon, label }) => (
        <View key={key} style={s.statCard}>
          <View style={s.statIconWrap}>
            <Ionicons name={icon} size={20} color={theme.primary} />
          </View>
          <Text style={s.statNumber}>{stats[key]}</Text>
          <Text style={s.statLabel}>{label}</Text>
        </View>
      ))}
    </View>
  );

  const renderEmptyState = () => (
    <View style={s.stateContainer}>
      <View style={s.emptyIconWrap}>
        <Ionicons name="mail-unread-outline" size={40} color={theme.primary} />
      </View>
      <Text style={s.emptyTitle}>Belum Ada Undangan</Text>
      <Text style={s.emptySubtitle}>Buat undangan digital pertama Anda sekarang.</Text>
      <Pressable
        onPress={() => navigation.navigate('Home')}
        style={({ pressed }) => [s.primaryButton, pressed && s.pressed]}
      >
        <Text style={s.primaryButtonText}>Buat Undangan Baru</Text>
      </Pressable>
    </View>
  );

  const renderCard = ({ item }: { item: InvitationItem }) => (
    <View style={s.card}>
      <View style={s.cardHeader}>
        {item.thumbnail ? (
          <Image source={{ uri: item.thumbnail }} style={s.avatar} />
        ) : (
          <View style={s.avatarFallback}>
            <Ionicons name="images-outline" size={18} color={theme.primary} />
          </View>
        )}
        <View style={s.cardContent}>
          <Text style={s.cardTitle} numberOfLines={1}>{item.title}</Text>
          <Text style={s.cardSubtitle} numberOfLines={1}>
            {item.theme_name} • {item.date}
          </Text>
        </View>
        <View style={[s.statusBadge, item.status === 'Aktif' ? s.statusActive : s.statusDraft]}>
          <Text style={[s.statusText, item.status === 'Aktif' ? s.statusTextActive : s.statusTextDraft]}>
            {item.status}
          </Text>
        </View>
        <Pressable style={s.menuBtn} hitSlop={8}>
          <Ionicons name="ellipsis-vertical" size={18} color={theme.outline} />
        </Pressable>
      </View>

      <Text style={s.cardUrl} numberOfLines={1}>{item.url}</Text>

      <Pressable
        style={({ pressed }) => [s.sebarButton, pressed && s.pressed]}
        onPress={() => console.log('Sebar', item.url)}
      >
        <Ionicons name="share-social-outline" size={16} color="#FFFFFF" />
        <Text style={s.sebarButtonText}>Sebar Undangan</Text>
      </Pressable>
    </View>
  );

  // Loading state
  if (isLoading) {
    return (
      <SafeAreaView style={s.safeArea} edges={['top']}>
        <View style={s.stateContainer}>
          <ActivityIndicator size="large" color={theme.primary} />
        </View>
      </SafeAreaView>
    );
  }

  // Guest state
  if (!isLoggedIn) {
    return (
      <SafeAreaView style={s.safeArea} edges={['top']}>
        <View style={s.stateContainer}>
          <View style={s.emptyIconWrap}>
            <Ionicons name="lock-closed-outline" size={40} color={theme.primary} />
          </View>
          <Text style={s.emptyTitle}>Akses Diperlukan</Text>
          <Text style={s.emptySubtitle}>Masuk untuk mengelola undangan Anda.</Text>
          <Pressable
            onPress={() => navigation.navigate('Login')}
            style={({ pressed }) => [s.primaryButton, pressed && s.pressed]}
          >
            <Text style={s.primaryButtonText}>Masuk Sekarang</Text>
          </Pressable>
        </View>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={s.safeArea} edges={['top']}>
      <FlatList
        data={invitations}
        keyExtractor={(item) => item.id.toString()}
        ListHeaderComponent={invitations.length > 0 ? renderListHeader : null}
        ListEmptyComponent={renderEmptyState}
        renderItem={renderCard}
        contentContainerStyle={s.listContent}
        showsVerticalScrollIndicator={false}
      />

      {/* FAB */}
      <Pressable
        style={({ pressed }) => [s.fab, pressed && s.pressed]}
        onPress={() => navigation.navigate('Home')}
      >
        <Ionicons name="add" size={26} color="#FFFFFF" />
      </Pressable>
    </SafeAreaView>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme']) {
  return StyleSheet.create({
    safeArea: { flex: 1, backgroundColor: t.background },
    listContent: {
      paddingHorizontal: 18,
      paddingBottom: 120,
    },

    // State screens (loading / guest / empty)
    stateContainer: {
      flex: 1,
      justifyContent: 'center',
      alignItems: 'center',
      paddingHorizontal: 32,
      gap: 12,
    },
    emptyIconWrap: {
      width: 80,
      height: 80,
      borderRadius: 40,
      backgroundColor: t.surfaceContainerLow,
      alignItems: 'center',
      justifyContent: 'center',
      borderWidth: 1,
      borderColor: t.outlineVariant,
      marginBottom: 4,
    },
    emptyTitle: {
      fontFamily: F.display,
      fontSize: 20,
      color: t.onSurface,
      textAlign: 'center',
    },
    emptySubtitle: {
      fontFamily: F.body,
      fontSize: 14,
      color: t.onSurfaceVariant,
      textAlign: 'center',
      lineHeight: 21,
      marginBottom: 8,
    },
    primaryButton: {
      height: 52,
      paddingHorizontal: 28,
      borderRadius: 999,
      backgroundColor: t.primary,
      alignItems: 'center',
      justifyContent: 'center',
      shadowColor: t.primary,
      shadowOpacity: 0.28,
      shadowRadius: 12,
      shadowOffset: { width: 0, height: 6 },
      elevation: 5,
    },
    primaryButtonText: {
      fontFamily: F.labelBold,
      color: '#FFFFFF',
      fontSize: 15,
    },

    // Stats grid
    statsGrid: {
      flexDirection: 'row',
      flexWrap: 'wrap',
      justifyContent: 'space-between',
      marginTop: 20,
      marginBottom: 8,
      gap: 12,
    },
    statCard: {
      width: '47%',
      backgroundColor: t.surfaceContainerLow,
      borderRadius: 16,
      padding: 16,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      gap: 6,
    },
    statIconWrap: {
      width: 36,
      height: 36,
      borderRadius: 10,
      backgroundColor: t.surfaceContainerHighest,
      alignItems: 'center',
      justifyContent: 'center',
    },
    statNumber: {
      fontFamily: F.display,
      color: t.onSurface,
      fontSize: 26,
      marginTop: 4,
    },
    statLabel: {
      fontFamily: F.body,
      color: t.onSurfaceVariant,
      fontSize: 12,
    },

    // Invitation card
    card: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: 18,
      padding: 16,
      marginBottom: 14,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      gap: 10,
    },
    cardHeader: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 10,
    },
    avatar: {
      width: 42,
      height: 42,
      borderRadius: 10,
    },
    avatarFallback: {
      width: 42,
      height: 42,
      borderRadius: 10,
      backgroundColor: t.surfaceContainerHighest,
      justifyContent: 'center',
      alignItems: 'center',
    },
    cardContent: { flex: 1, gap: 2 },
    cardTitle: {
      fontFamily: F.heading,
      color: t.onSurface,
      fontSize: 15,
    },
    cardSubtitle: {
      fontFamily: F.body,
      color: t.onSurfaceVariant,
      fontSize: 12,
    },
    statusBadge: {
      paddingHorizontal: 9,
      paddingVertical: 4,
      borderRadius: 8,
    },
    statusActive: { backgroundColor: t.isDark ? 'rgba(74,222,128,0.15)' : '#D1FAE5' },
    statusDraft: { backgroundColor: t.surfaceContainerHighest },
    statusText: { fontSize: 11, fontFamily: F.labelBold },
    statusTextActive: { color: t.successIcon },
    statusTextDraft: { color: t.onSurfaceVariant },
    menuBtn: { paddingHorizontal: 4, paddingVertical: 6 },

    cardUrl: {
      fontFamily: F.body,
      color: t.isDark ? '#60A5FA' : '#3B82F6',
      fontSize: 12,
    },
    sebarButton: {
      backgroundColor: t.primary,
      paddingVertical: 12,
      borderRadius: 12,
      alignItems: 'center',
      justifyContent: 'center',
      flexDirection: 'row',
      gap: 8,
      shadowColor: t.primary,
      shadowOpacity: 0.2,
      shadowRadius: 8,
      shadowOffset: { width: 0, height: 4 },
      elevation: 3,
    },
    sebarButtonText: {
      fontFamily: F.labelBold,
      color: '#FFFFFF',
      fontSize: 14,
    },

    // FAB
    fab: {
      position: 'absolute',
      bottom: 90,
      right: 20,
      width: 56,
      height: 56,
      borderRadius: 28,
      backgroundColor: t.primary,
      justifyContent: 'center',
      alignItems: 'center',
      shadowColor: t.primary,
      shadowOffset: { width: 0, height: 6 },
      shadowOpacity: 0.35,
      shadowRadius: 10,
      elevation: 8,
    },

    pressed: { opacity: 0.84, transform: [{ scale: 0.97 }] },
  });
}
