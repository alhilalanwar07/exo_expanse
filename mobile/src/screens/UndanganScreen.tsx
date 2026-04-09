import React, { useState, useEffect, useCallback } from 'react';
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
import * as SecureStore from 'expo-secure-store';
import { Ionicons } from '@expo/vector-icons';
import { useNavigation, useFocusEffect } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import { COLORS, FONTS, SIZES } from '../constants/theme';
import { env } from '../config/env';

type RootStackParamList = {
  Login: undefined;
  Home: undefined;
};

// Define structure of the data
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

export function UndanganScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<RootStackParamList>>();

  // State Management
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [isLoggedIn, setIsLoggedIn] = useState<boolean>(false);
  const [invitations, setInvitations] = useState<InvitationItem[]>([]);
  const [stats, setStats] = useState<StatsData>({ total: 0, guests: 0, rsvp: 0, wishes: 0 });

  useFocusEffect(
    useCallback(() => {
      let isActive = true;

      const fetchData = async () => {
        try {
          setIsLoading(true);

          // 1. Check if token exists in SecureStore
          // Note: Looking for our session payload stringified
          const sessionRaw = await SecureStore.getItemAsync('exo.mobile.auth.session');
          
          if (!sessionRaw) {
            // 2. NO token
            if (isActive) {
              setIsLoggedIn(false);
              setIsLoading(false);
            }
            return;
          }

          // Parse session for the token
          let token = '';
          try {
            const session = JSON.parse(sessionRaw);
            token = session.accessToken;
          } catch (e) {
            token = sessionRaw; // fallback if just token was stored
          }

          if (!token) {
            if (isActive) {
              setIsLoggedIn(false);
              setIsLoading(false);
            }
            return;
          }

          // 3. Token exists
          if (isActive) {
            setIsLoggedIn(true);
          }

          // API call mimicking axios with fetch
          const response = await fetch(`${env.apiBaseUrl}/api/mobile/access/invitations`, {
            headers: {
              'Authorization': `Bearer ${token}`,
              'Accept': 'application/json',
            },
          });

          if (!response.ok) {
            throw new Error(`API returned status ${response.status}`);
          }

          const responseData = await response.json();

          if (isActive) {
            // 4. Populate array and stats
            const list = (responseData.data || []).map((item: any) => ({
              id: item.id,
              title: item.title || 'Undangan',
              theme_name: item.theme || item.theme_name || 'Tema',
              date: item.date || 'TBA',
              url: item.url || `${env.apiBaseUrl}/i/${item.slug || ''}`,
              status: item.status || 'Draf',
              thumbnail: item.thumbnail || null,
            }));

            setInvitations(list);

            if (responseData.stats) {
              setStats({
                total: responseData.stats.total_undangan || 0,
                guests: responseData.stats.total_tamu || 0,
                rsvp: responseData.stats.tamu_hadir || 0,
                wishes: responseData.stats.total_ucapan || 0,
              });
            }
          }
        } catch (error) {
          // 5. Catch block
          console.error('Error fetching invitations data:', error);
          if (isActive) {
            Alert.alert('Gagal Memuat', 'Terjadi kesalahan saat mengambil data undangan.');
            setInvitations([]);
          }
        } finally {
          // 6. Finally block
          if (isActive) {
            setIsLoading(false);
          }
        }
      };

      fetchData();

      return () => {
        isActive = false;
      };
    }, [])
  );

  const renderEmptyState = () => (
    <View style={styles.stateContainer}>
      <Ionicons name="mail-unread-outline" size={60} color={COLORS.border} style={styles.iconSpaced} />
      <Text style={styles.mutedText}>Belum ada undangan yang dibuat.</Text>
      <Pressable 
        style={styles.primaryButton}
        onPress={() => navigation.navigate('Home')}
      >
        <Text style={styles.primaryButtonText}>Buat Undangan Baru</Text>
      </Pressable>
    </View>
  );

  const renderListHeader = () => (
    <View style={styles.statsContainer}>
      {/* Total Undangan */}
      <View style={styles.statCard}>
        <Ionicons name="mail" size={24} color={COLORS.primary} />
        <Text style={styles.statNumber}>{stats.total}</Text>
        <Text style={styles.statLabel}>Total Undangan</Text>
      </View>

      {/* Total Tamu */}
      <View style={styles.statCard}>
        <Ionicons name="people" size={24} color={COLORS.primary} />
        <Text style={styles.statNumber}>{stats.guests}</Text>
        <Text style={styles.statLabel}>Total Tamu</Text>
      </View>

      {/* Tamu Hadir */}
      <View style={styles.statCard}>
        <Ionicons name="checkmark-circle" size={24} color={COLORS.primary} />
        <Text style={styles.statNumber}>{stats.rsvp}</Text>
        <Text style={styles.statLabel}>Tamu Hadir</Text>
      </View>

      {/* Total Ucapan */}
      <View style={styles.statCard}>
        <Ionicons name="chatbubbles" size={24} color={COLORS.primary} />
        <Text style={styles.statNumber}>{stats.wishes}</Text>
        <Text style={styles.statLabel}>Total Ucapan</Text>
      </View>
    </View>
  );

  const renderInvitationCard = ({ item }: { item: InvitationItem }) => (
    <View style={styles.cardContainer}>
      {/* Row 1 / Header */}
      <View style={styles.cardHeader}>
        {item.thumbnail ? (
          <Image source={{ uri: item.thumbnail }} style={styles.avatar} />
        ) : (
          <View style={styles.avatarFallback}>
            <Ionicons name="images-outline" size={20} color={COLORS.primary} />
          </View>
        )}
        <View style={styles.cardContent}>
          <Text style={styles.cardTitle} numberOfLines={1}>{item.title}</Text>
          <Text style={styles.cardSubtitle} numberOfLines={1}>
            {item.theme_name} • {item.date}
          </Text>
        </View>
        <Pressable style={styles.cardOptions}>
          <Ionicons name="ellipsis-vertical" size={20} color={COLORS.textMuted} />
        </Pressable>
      </View>

      {/* Row 2 / URL */}
      <Text style={styles.cardUrl} numberOfLines={1}>
        {item.url}
      </Text>

      {/* Row 3 / Action */}
      <Pressable style={styles.sebarButton}>
        <Text style={styles.sebarButtonText}>Sebar Undangan</Text>
      </Pressable>
    </View>
  );

  // View States Based on Conditions
  if (isLoading) {
    return (
      <SafeAreaView style={styles.safeArea} edges={['top']}>
        <View style={styles.stateContainer}>
          <ActivityIndicator size="large" color={COLORS.primary} />
        </View>
      </SafeAreaView>
    );
  }

  if (!isLoggedIn) {
    return (
      <SafeAreaView style={styles.safeArea} edges={['top']}>
        <View style={styles.stateContainer}>
          <Ionicons name="lock-closed-outline" size={60} color={COLORS.border} style={styles.iconSpaced} />
          <Text style={styles.mutedText}>Masuk untuk mengelola undangan Anda</Text>
          <Pressable 
            style={styles.primaryButton}
            onPress={() => navigation.navigate('Login')}
          >
            <Text style={styles.primaryButtonText}>Masuk Sekarang</Text>
          </Pressable>
        </View>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.safeArea} edges={['top']}>
      <FlatList
        data={invitations}
        keyExtractor={(item) => item.id.toString()}
        ListHeaderComponent={invitations.length > 0 ? renderListHeader : null}
        ListEmptyComponent={renderEmptyState}
        renderItem={renderInvitationCard}
        contentContainerStyle={styles.listContentContainer}
        showsVerticalScrollIndicator={false}
      />

      {/* Floating Action Button (FAB) */}
      <Pressable 
        style={styles.fab} 
        onPress={() => navigation.navigate('Home')}
      >
        <Ionicons name="add" size={24} color="#FFFFFF" />
      </Pressable>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: COLORS.background,
  },
  stateContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: SIZES.padding,
  },
  iconSpaced: {
    marginBottom: 16,
  },
  mutedText: {
    fontFamily: FONTS.body,
    color: COLORS.textMuted,
    fontSize: 16,
    textAlign: 'center',
    marginBottom: 24,
  },
  primaryButton: {
    backgroundColor: COLORS.primary,
    paddingHorizontal: 32,
    paddingVertical: 14,
    borderRadius: SIZES.radius,
  },
  primaryButtonText: {
    fontFamily: FONTS.label,
    color: '#ffffff',
    fontSize: 16,
  },

  // FlatList Configuration
  listContentContainer: {
    paddingBottom: 120, // Clear floating tab bar & FAB
    paddingHorizontal: SIZES.padding,
  },

  // Stats Component
  statsContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
    marginBottom: 20,
    marginTop: 20, // add a bit of space from the top
  },
  statCard: {
    width: '48%',
    backgroundColor: COLORS.surface,
    borderRadius: SIZES.radius,
    padding: 15,
    marginBottom: 15,
    borderColor: COLORS.border,
    borderWidth: 1,
  },
  statNumber: {
    fontFamily: FONTS.headline,
    color: COLORS.text,
    fontSize: 24,
    marginTop: 10,
    marginBottom: 4,
  },
  statLabel: {
    fontFamily: FONTS.body,
    color: COLORS.textMuted,
    fontSize: 12,
  },

  // Invitation Component
  cardContainer: {
    backgroundColor: COLORS.surface,
    borderRadius: SIZES.radius,
    padding: 15,
    marginBottom: 15,
    borderWidth: 1,
    borderColor: COLORS.border,
  },
  cardHeader: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  avatar: {
    width: 40,
    height: 40,
    borderRadius: 8,
  },
  avatarFallback: {
    width: 40,
    height: 40,
    borderRadius: 8,
    backgroundColor: COLORS.primary + '20', // subtle tint
    justifyContent: 'center',
    alignItems: 'center',
  },
  cardContent: {
    flex: 1,
    marginLeft: 10,
  },
  cardTitle: {
    fontFamily: FONTS.label,
    color: COLORS.text,
    fontSize: 15,
  },
  cardSubtitle: {
    fontFamily: FONTS.body,
    color: COLORS.textMuted,
    fontSize: 12,
    marginTop: 2,
  },
  cardOptions: {
    paddingHorizontal: 4,
    paddingVertical: 8,
  },
  cardUrl: {
    fontFamily: FONTS.body,
    color: '#3B82F6', // Blue shade for links
    fontSize: 12,
    marginTop: 10,
  },
  sebarButton: {
    backgroundColor: COLORS.primary,
    padding: 12,
    borderRadius: 8,
    marginTop: 15,
    alignItems: 'center',
  },
  sebarButtonText: {
    fontFamily: FONTS.label,
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
    backgroundColor: COLORS.primary,
    justifyContent: 'center',
    alignItems: 'center',
    elevation: 5,
    shadowColor: '#000000',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 4,
  },
});
