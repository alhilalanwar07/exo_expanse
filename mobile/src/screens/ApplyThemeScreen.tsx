import { useState, useCallback } from 'react';
import {
  View,
  Text,
  StyleSheet,
  FlatList,
  Pressable,
  ActivityIndicator,
  Alert,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { useNavigation, useRoute, useFocusEffect } from '@react-navigation/native';
import type { RouteProp } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import { httpRequest, HttpClientError } from '../services/httpClient';
import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import type { RootStackParamList } from '../navigation/types';

type NavProp = NativeStackNavigationProp<RootStackParamList, 'ApplyTheme'>;
type RoutePropType = RouteProp<RootStackParamList, 'ApplyTheme'>;

type InvitationOption = {
  id: string | number;
  title: string;
  theme_name: string;
  date: string;
  status: string;
};

export function ApplyThemeScreen() {
  const navigation = useNavigation<NavProp>();
  const route = useRoute<RoutePropType>();
  const { theme } = useAppTheme();
  const s = makeStyles(theme);

  const { themeId, themeName, isPremium } = route.params;

  const [invitations, setInvitations] = useState<InvitationOption[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [applying, setApplying] = useState<string | null>(null);

  useFocusEffect(
    useCallback(() => {
      let isActive = true;
      const fetchInvitations = async () => {
        try {
          setIsLoading(true);
          const res = await httpRequest<{ data?: {
            id: string | number;
            title?: string;
            theme?: string;
            theme_name?: string;
            date?: string;
            status?: string;
          }[] }>('/api/mobile/access/invitations', {
            authMode: 'required',
            retry: 1,
            timeoutMs: 10000,
          });
          if (isActive) {
            setInvitations(
              (res.data ?? []).map((item) => ({
                id: item.id,
                title: item.title ?? 'Undangan',
                theme_name: item.theme ?? item.theme_name ?? '-',
                date: item.date ?? 'TBA',
                status: item.status ?? 'Draf',
              }))
            );
          }
        } catch (err) {
          if (err instanceof HttpClientError && err.status === 401) {
            if (isActive) {
              Alert.alert('Sesi Berakhir', 'Masuk kembali untuk melanjutkan.', [
                { text: 'OK', onPress: () => navigation.navigate('AuthChoice', { intent: 'theme' }) },
              ]);
            }
          } else if (isActive) {
            Alert.alert('Gagal Memuat', 'Tidak bisa mengambil daftar undangan.');
          }
        } finally {
          if (isActive) setIsLoading(false);
        }
      };
      void fetchInvitations();
      return () => { isActive = false; };
    }, [navigation])
  );

  const handleApply = async (invitationId: string | number) => {
    setApplying(String(invitationId));
    try {
      await httpRequest(`/api/mobile/access/invitations/${invitationId}/theme`, {
        method: 'PATCH',
        authMode: 'required',
        body: { theme_id: themeId },
        timeoutMs: 10000,
      });
      Alert.alert(
        'Tema Diterapkan! ✨',
        `Tema "${themeName}" berhasil diterapkan ke undangan.`,
        [{ text: 'Lihat Undangan', onPress: () => navigation.navigate('Main') }]
      );
    } catch (err) {
      const msg =
        err instanceof HttpClientError
          ? err.status === 403
            ? 'Tema premium ini membutuhkan langganan aktif.'
            : 'Gagal menerapkan tema. Coba lagi.'
          : 'Terjadi kesalahan jaringan.';
      Alert.alert('Gagal', msg);
    } finally {
      setApplying(null);
    }
  };

  const renderItem = ({ item }: { item: InvitationOption }) => {
    const isApplying = applying === String(item.id);
    return (
      <Pressable
        style={({ pressed }) => [s.card, pressed && s.pressed]}
        onPress={() => void handleApply(item.id)}
        disabled={applying !== null}
      >
        <View style={s.cardLeft}>
          <View style={s.cardIcon}>
            <Ionicons name="mail-open-outline" size={20} color={theme.primary} />
          </View>
          <View style={s.cardText}>
            <Text style={s.cardTitle} numberOfLines={1}>{item.title}</Text>
            <Text style={s.cardSub} numberOfLines={1}>
              {item.theme_name} • {item.date}
            </Text>
          </View>
        </View>
        <View style={s.cardRight}>
          <View style={[s.statusBadge, item.status === 'Aktif' ? s.badgeActive : s.badgeDraft]}>
            <Text style={[s.statusText, item.status === 'Aktif' ? s.statusTextActive : s.statusTextDraft]}>
              {item.status}
            </Text>
          </View>
          {isApplying ? (
            <ActivityIndicator size="small" color={theme.primary} />
          ) : (
            <Ionicons name="chevron-forward" size={18} color={theme.outline} />
          )}
        </View>
      </Pressable>
    );
  };

  return (
    <SafeAreaView style={s.safeArea} edges={['top']}>
      {/* Header */}
      <View style={s.header}>
        <Pressable
          onPress={() => navigation.goBack()}
          style={({ pressed }) => [s.iconBtn, pressed && s.pressed]}
        >
          <Ionicons name="arrow-back" size={20} color={theme.onSurface} />
        </Pressable>
        <View style={s.headerText}>
          <Text style={s.headerEyebrow}>Terapkan Tema</Text>
          <Text style={s.headerTitle} numberOfLines={1}>{themeName}</Text>
        </View>
        {isPremium && (
          <View style={s.premiumBadge}>
            <Text style={s.premiumBadgeText}>PREMIUM</Text>
          </View>
        )}
      </View>

      {/* Info Banner */}
      <View style={s.infoBanner}>
        <MaterialCommunityIcons name="palette-outline" size={18} color={theme.primary} />
        <Text style={s.infoText}>
          Pilih undangan yang ingin menggunakan tema ini. Tampilan akan langsung diperbarui.
        </Text>
      </View>

      {/* List */}
      {isLoading ? (
        <View style={s.stateBox}>
          <ActivityIndicator size="large" color={theme.primary} />
          <Text style={s.stateSub}>Memuat undangan...</Text>
        </View>
      ) : invitations.length === 0 ? (
        <View style={s.stateBox}>
          <MaterialCommunityIcons name="email-outline" size={52} color={theme.outline} />
          <Text style={s.stateTitle}>Belum Ada Undangan</Text>
          <Text style={s.stateSub}>Buat undangan terlebih dahulu di tab Undangan.</Text>
          <Pressable
            style={({ pressed }) => [s.primaryBtn, pressed && s.pressed]}
            onPress={() => navigation.navigate('Main')}
          >
            <Text style={s.primaryBtnText}>Ke Halaman Undangan</Text>
          </Pressable>
        </View>
      ) : (
        <FlatList
          data={invitations}
          keyExtractor={(item) => String(item.id)}
          contentContainerStyle={s.list}
          showsVerticalScrollIndicator={false}
          renderItem={renderItem}
        />
      )}
    </SafeAreaView>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme']) {
  return StyleSheet.create({
    safeArea: { flex: 1, backgroundColor: t.background },

    header: {
      flexDirection: 'row',
      alignItems: 'center',
      paddingHorizontal: 16,
      paddingTop: 8,
      paddingBottom: 10,
      gap: 12,
    },
    iconBtn: {
      width: 38,
      height: 38,
      borderRadius: 12,
      backgroundColor: t.surfaceContainerLow,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      alignItems: 'center',
      justifyContent: 'center',
    },
    headerText: { flex: 1, gap: 1 },
    headerEyebrow: {
      fontFamily: F.label,
      fontSize: 10,
      letterSpacing: 1,
      textTransform: 'uppercase',
      color: t.primary,
    },
    headerTitle: {
      fontFamily: F.heading,
      fontSize: 18,
      color: t.onSurface,
    },
    premiumBadge: {
      backgroundColor: '#D93723',
      borderRadius: 999,
      paddingHorizontal: 10,
      paddingVertical: 5,
    },
    premiumBadgeText: {
      fontFamily: F.labelBold,
      fontSize: 9,
      color: '#FFFFFF',
      letterSpacing: 0.8,
    },

    infoBanner: {
      flexDirection: 'row',
      alignItems: 'flex-start',
      gap: 10,
      marginHorizontal: 16,
      marginBottom: 12,
      padding: 12,
      borderRadius: 14,
      backgroundColor: t.surfaceContainerLow,
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },
    infoText: {
      flex: 1,
      fontFamily: F.body,
      fontSize: 13,
      color: t.onSurfaceVariant,
      lineHeight: 19,
    },

    list: {
      paddingHorizontal: 16,
      paddingBottom: 32,
      gap: 10,
    },

    card: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: 16,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      paddingHorizontal: 14,
      paddingVertical: 12,
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      gap: 10,
    },
    cardLeft: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 12,
      flex: 1,
    },
    cardIcon: {
      width: 40,
      height: 40,
      borderRadius: 10,
      backgroundColor: t.surfaceContainerHighest,
      alignItems: 'center',
      justifyContent: 'center',
    },
    cardText: { flex: 1, gap: 2 },
    cardTitle: {
      fontFamily: F.heading,
      fontSize: 14,
      color: t.onSurface,
    },
    cardSub: {
      fontFamily: F.body,
      fontSize: 12,
      color: t.onSurfaceVariant,
    },
    cardRight: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 8,
    },
    statusBadge: {
      paddingHorizontal: 8,
      paddingVertical: 3,
      borderRadius: 8,
    },
    badgeActive: { backgroundColor: t.isDark ? 'rgba(74,222,128,0.15)' : '#D1FAE5' },
    badgeDraft: { backgroundColor: t.surfaceContainerHighest },
    statusText: { fontFamily: F.labelBold, fontSize: 10 },
    statusTextActive: { color: t.successIcon },
    statusTextDraft: { color: t.onSurfaceVariant },

    stateBox: {
      flex: 1,
      alignItems: 'center',
      justifyContent: 'center',
      paddingHorizontal: 32,
      gap: 10,
    },
    stateTitle: { fontFamily: F.heading, fontSize: 18, color: t.onSurface },
    stateSub: {
      fontFamily: F.body,
      fontSize: 14,
      color: t.onSurfaceVariant,
      textAlign: 'center',
    },
    primaryBtn: {
      marginTop: 8,
      height: 48,
      paddingHorizontal: 24,
      borderRadius: 999,
      backgroundColor: t.primary,
      alignItems: 'center',
      justifyContent: 'center',
    },
    primaryBtnText: {
      fontFamily: F.labelBold,
      fontSize: 14,
      color: '#FFFFFF',
    },

    pressed: { opacity: 0.84, transform: [{ scale: 0.97 }] },
  });
}
