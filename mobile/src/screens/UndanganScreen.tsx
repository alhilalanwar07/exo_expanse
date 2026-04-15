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
  Share,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useNavigation, useFocusEffect, type CompositeNavigationProp } from '@react-navigation/native';
import type { BottomTabNavigationProp } from '@react-navigation/bottom-tabs';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import { readAuthSession } from '../features/auth/auth.storage';
import { HttpClientError, httpRequest } from '../services/httpClient';
import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import { env } from '../config/env';
import type { MainTabParamList, RootStackParamList } from '../navigation/types';

type UndanganNavigationProp = CompositeNavigationProp<
  BottomTabNavigationProp<MainTabParamList, 'Undangan'>,
  NativeStackNavigationProp<RootStackParamList>
>;

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
  { key: 'guests', icon: 'people-outline' as const, label: 'Tamu' },
  { key: 'rsvp', icon: 'checkmark-circle-outline' as const, label: 'Hadir' },
  { key: 'wishes', icon: 'chatbubbles-outline' as const, label: 'Ucapan' },
] as const;

function formatCompactNumber(value: number): string {
  if (value >= 1_000_000) {
    return `${(value / 1_000_000).toFixed(1).replace(/\.0$/, '')}jt`;
  }

  if (value >= 1_000) {
    return `${(value / 1_000).toFixed(1).replace(/\.0$/, '')}rb`;
  }

  return String(value);
}

function toAttendanceRate(stats: StatsData): number {
  if (stats.guests <= 0) {
    return 0;
  }

  return Math.round((stats.rsvp / stats.guests) * 100);
}

export function UndanganScreen() {
  const navigation = useNavigation<UndanganNavigationProp>();
  const { theme } = useAppTheme();

  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [isLoggedIn, setIsLoggedIn] = useState<boolean>(false);
  const [invitations, setInvitations] = useState<InvitationItem[]>([]);
  const [stats, setStats] = useState<StatsData>({ total: 0, guests: 0, rsvp: 0, wishes: 0 });

  const attendanceRate = toAttendanceRate(stats);
  const latestInvitation = invitations[0] ?? null;

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

          const responseData = await httpRequest<{
            data?: {
              id: string | number;
              title?: string;
              theme?: string;
              theme_name?: string;
              date?: string;
              url?: string;
              slug?: string;
              status?: string;
              thumbnail?: string | null;
            }[];
            stats?: {
              total_undangan?: number;
              total_tamu?: number;
              tamu_hadir?: number;
              total_ucapan?: number;
            };
          }>('/api/mobile/access/invitations', {
            authMode: 'required',
            retry: 2,
            timeoutMs: 12000,
          });

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

          if (error instanceof HttpClientError && error.status === 401) {
            if (isActive) {
              setIsLoggedIn(false);
              setInvitations([]);
            }

            return;
          }

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

  const handleShare = async (item: InvitationItem) => {
    try {
      await Share.share({
        title: item.title,
        message: `🎊 ${item.title}\n\nAnda diundang! Buka link undangan digital kami:\n${item.url}`,
        url: item.url, // iOS only
      });
    } catch {
      Alert.alert('Gagal', 'Tidak dapat membagikan undangan.');
    }
  };

  const renderListHeader = () => (
    <View style={s.listHeaderWrap}>
      <View style={s.heroCard}>
        <Text style={s.heroEyebrow}>Dashboard Undangan</Text>

        <View style={s.heroTopRow}>
          <View style={s.heroTitleWrap}>
            <Text style={s.heroTitle}>Ringkasan Cepat</Text>
            <Text style={s.heroSubtitle}>
              {formatCompactNumber(stats.total)} undangan • {formatCompactNumber(stats.guests)} tamu
            </Text>
          </View>

          <Pressable
            onPress={() => navigation.navigate('InvitationForm', undefined)}
            style={({ pressed }) => [s.heroAddBtn, pressed && s.pressed]}
          >
            <Ionicons name="add" size={20} color="#FFFFFF" />
          </Pressable>
        </View>

        <View style={s.heroMetricsRow}>
          <View style={[s.heroMetricCard, s.heroMetricPrimary]}>
            <Text style={s.heroMetricValue}>{formatCompactNumber(stats.total)}</Text>
            <Text style={s.heroMetricLabel}>Total Undangan</Text>
          </View>

          <View style={s.heroMetricCard}>
            <Text style={s.heroMetricValue}>{attendanceRate}%</Text>
            <Text style={s.heroMetricLabel}>Tingkat Hadir</Text>
          </View>
        </View>

        <View style={s.heroQuickStatsRow}>
          {STAT_ITEMS.map(({ key, icon, label }) => (
            <View key={key} style={s.quickStatItem}>
              <Ionicons name={icon} size={15} color={theme.primary} />
              <Text style={s.quickStatValue}>{formatCompactNumber(stats[key])}</Text>
              <Text style={s.quickStatLabel}>{label}</Text>
            </View>
          ))}
        </View>
      </View>

      {latestInvitation ? (
        <View style={s.latestCard}>
          <View style={s.latestTopRow}>
            <Text style={s.latestEyebrow}>Undangan Terbaru</Text>
            <View style={[s.statusBadge, latestInvitation.status === 'Aktif' ? s.statusActive : s.statusDraft]}>
              <Text style={[s.statusText, latestInvitation.status === 'Aktif' ? s.statusTextActive : s.statusTextDraft]}>
                {latestInvitation.status}
              </Text>
            </View>
          </View>

          <Text style={s.latestTitle} numberOfLines={1}>{latestInvitation.title}</Text>
          <Text style={s.latestMeta} numberOfLines={1}>
            {latestInvitation.theme_name} • {latestInvitation.date}
          </Text>

          <Pressable
            onPress={() =>
              navigation.navigate('InvitationForm', {
                invitationId: Number(latestInvitation.id),
              })
            }
            style={({ pressed }) => [s.latestEditButton, pressed && s.pressed]}
          >
            <Ionicons name="create-outline" size={14} color={theme.primary} />
            <Text style={s.latestEditButtonText}>Edit Undangan</Text>
          </Pressable>
        </View>
      ) : null}

      {invitations.length > 0 ? (
        <Text style={s.sectionHeading}>Daftar Undangan</Text>
      ) : null}
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
        onPress={() => navigation.navigate('InvitationForm', undefined)}
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
      </View>

      <View style={s.cardLinkRow}>
        <Ionicons name="link-outline" size={14} color={theme.outline} />
        <Text style={s.cardUrl} numberOfLines={1}>{item.url}</Text>
      </View>

      <View style={s.cardActionsRow}>
        <Pressable
          style={({ pressed }) => [s.editButton, pressed && s.pressed]}
          onPress={() =>
            navigation.navigate('InvitationForm', {
              invitationId: Number(item.id),
            })
          }
        >
          <Ionicons name="create-outline" size={16} color={theme.primary} />
          <Text style={s.editButtonText}>Edit</Text>
        </Pressable>

        <Pressable
          style={({ pressed }) => [s.sebarButton, pressed && s.pressed]}
          onPress={() => void handleShare(item)}
        >
          <Ionicons name="share-social-outline" size={16} color="#FFFFFF" />
          <Text style={s.sebarButtonText}>Sebar Undangan</Text>
        </Pressable>
      </View>
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
            onPress={() => navigation.navigate('AuthChoice', { intent: 'manage' })}
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
        ListHeaderComponent={renderListHeader}
        ListEmptyComponent={renderEmptyState}
        renderItem={renderCard}
        contentContainerStyle={s.listContent}
        contentInsetAdjustmentBehavior="automatic"
        showsVerticalScrollIndicator={false}
      />
    </SafeAreaView>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme']) {
  return StyleSheet.create({
    safeArea: { flex: 1, backgroundColor: t.background },
    listContent: {
      paddingHorizontal: 18,
      paddingBottom: 96,
    },

    listHeaderWrap: {
      gap: 12,
      marginTop: 10,
      marginBottom: 12,
    },
    heroCard: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: 18,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      padding: 14,
      gap: 12,
    },
    heroEyebrow: {
      fontFamily: F.label,
      fontSize: 10,
      letterSpacing: 1,
      textTransform: 'uppercase',
      color: t.primary,
    },
    heroTopRow: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      gap: 10,
    },
    heroTitleWrap: {
      flex: 1,
      gap: 2,
    },
    heroTitle: {
      fontFamily: F.heading,
      color: t.onSurface,
      fontSize: 21,
      letterSpacing: -0.2,
    },
    heroSubtitle: {
      fontFamily: F.body,
      color: t.onSurfaceVariant,
      fontSize: 13,
    },
    heroAddBtn: {
      width: 36,
      height: 36,
      borderRadius: 18,
      backgroundColor: t.primary,
      alignItems: 'center',
      justifyContent: 'center',
      shadowColor: t.primary,
      shadowOpacity: 0.28,
      shadowRadius: 8,
      shadowOffset: { width: 0, height: 3 },
      elevation: 3,
    },
    heroMetricsRow: {
      flexDirection: 'row',
      gap: 10,
    },
    heroMetricCard: {
      flex: 1,
      borderRadius: 14,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      backgroundColor: t.surface,
      paddingVertical: 10,
      paddingHorizontal: 12,
      gap: 2,
    },
    heroMetricPrimary: {
      backgroundColor: t.surfaceContainerHighest,
    },
    heroMetricValue: {
      fontFamily: F.display,
      color: t.onSurface,
      fontSize: 24,
      lineHeight: 30,
    },
    heroMetricLabel: {
      fontFamily: F.body,
      color: t.onSurfaceVariant,
      fontSize: 12,
    },
    heroQuickStatsRow: {
      flexDirection: 'row',
      gap: 10,
    },
    quickStatItem: {
      flex: 1,
      borderRadius: 12,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      backgroundColor: t.surface,
      alignItems: 'center',
      justifyContent: 'center',
      gap: 2,
      paddingVertical: 8,
      paddingHorizontal: 4,
    },
    quickStatValue: {
      fontFamily: F.labelBold,
      color: t.onSurface,
      fontSize: 14,
    },
    quickStatLabel: {
      fontFamily: F.body,
      color: t.onSurfaceVariant,
      fontSize: 11,
    },

    latestCard: {
      borderRadius: 14,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      backgroundColor: t.surface,
      paddingVertical: 10,
      paddingHorizontal: 12,
      gap: 4,
    },
    latestTopRow: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      gap: 8,
    },
    latestEyebrow: {
      fontFamily: F.label,
      color: t.onSurfaceVariant,
      fontSize: 11,
      letterSpacing: 0.8,
      textTransform: 'uppercase',
    },
    latestTitle: {
      fontFamily: F.heading,
      color: t.onSurface,
      fontSize: 15,
    },
    latestMeta: {
      fontFamily: F.body,
      color: t.onSurfaceVariant,
      fontSize: 12,
    },
    latestEditButton: {
      marginTop: 6,
      height: 34,
      borderRadius: 10,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      backgroundColor: t.surfaceContainerLow,
      alignItems: 'center',
      justifyContent: 'center',
      flexDirection: 'row',
      gap: 6,
      alignSelf: 'flex-start',
      paddingHorizontal: 11,
    },
    latestEditButtonText: {
      fontFamily: F.label,
      color: t.primary,
      fontSize: 12,
    },
    sectionHeading: {
      fontFamily: F.label,
      color: t.onSurfaceVariant,
      fontSize: 11,
      letterSpacing: 0.9,
      textTransform: 'uppercase',
      marginLeft: 2,
      marginTop: 2,
      marginBottom: 2,
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

    // Invitation card
    card: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: 18,
      padding: 14,
      marginBottom: 14,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      gap: 9,
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

    cardLinkRow: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 6,
    },
    cardUrl: {
      flex: 1,
      fontFamily: F.body,
      color: t.isDark ? '#60A5FA' : '#3B82F6',
      fontSize: 12,
    },
    cardActionsRow: {
      flexDirection: 'row',
      gap: 8,
    },
    editButton: {
      flex: 1,
      minHeight: 44,
      borderRadius: 12,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      backgroundColor: t.surface,
      alignItems: 'center',
      justifyContent: 'center',
      flexDirection: 'row',
      gap: 8,
      paddingHorizontal: 10,
    },
    editButtonText: {
      fontFamily: F.label,
      color: t.primary,
      fontSize: 13,
    },
    sebarButton: {
      flex: 1,
      minHeight: 44,
      backgroundColor: t.primary,
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

    pressed: { opacity: 0.84, transform: [{ scale: 0.97 }] },
  });
}
