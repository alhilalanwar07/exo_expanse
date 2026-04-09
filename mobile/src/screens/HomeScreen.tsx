import React, { useState } from 'react';
import {
  View,
  Text,
  TextInput,
  FlatList,
  Pressable,
  StyleSheet,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';

import { COLORS, FONTS, SIZES } from '../constants/theme';

// Mock Data
const CATEGORIES = ['Semua', 'Pernikahan', 'Ulang Tahun', 'Digital', 'Minimalis'];

const MOCK_DATA = [
  { id: '1', title: 'Romantic Rose', status: 'Gratis' },
  { id: '2', title: 'Golden Classic', status: 'Premium' },
  { id: '3', title: 'Minimalist White', status: 'Gratis' },
  { id: '4', title: 'Royal Blue', status: 'Premium' },
  { id: '5', title: 'Neon Party', status: 'Gratis' },
  { id: '6', title: 'Rustic Wood', status: 'Premium' },
];

export function HomeScreen() {
  const [activeCategory, setActiveCategory] = useState('Semua');

  const renderCard = ({ item }: { item: typeof MOCK_DATA[0] }) => {
    const isPremium = item.status === 'Premium';

    return (
      <View style={styles.cardContainer}>
        <View style={styles.imagePlaceholder}>
          <View
            style={[
              styles.badge,
              { backgroundColor: isPremium ? COLORS.premium : COLORS.surface },
            ]}
          >
            <Text
              style={[
                styles.badgeText,
                { color: isPremium ? COLORS.textLight : COLORS.primary },
              ]}
            >
              {item.status}
            </Text>
          </View>
        </View>
        <Text style={styles.cardTitle} numberOfLines={1}>
          {item.title}
        </Text>
      </View>
    );
  };

  return (
    <SafeAreaView style={styles.safeArea} edges={['top']}>
      <View style={styles.container}>
        {/* 1. Header */}
        <View style={styles.headerRow}>
          <Text style={styles.greetingText}>Halo, Budi! 👋</Text>
          <Pressable style={styles.bellIcon}>
            <Ionicons name="notifications-outline" size={24} color={COLORS.text} />
          </Pressable>
        </View>

        {/* 2. Search Bar */}
        <View style={styles.searchContainer}>
          <Ionicons name="search" size={20} color={COLORS.textMuted} style={styles.searchIcon} />
          <TextInput
            style={styles.searchInput}
            placeholder="Cari tema undangan..."
            placeholderTextColor={COLORS.textMuted}
            autoCorrect={false}
          />
        </View>

        {/* 3. Category Chips */}
        <View style={styles.categoriesWrapper}>
          <FlatList
            horizontal
            showsHorizontalScrollIndicator={false}
            data={CATEGORIES}
            keyExtractor={(item) => item}
            contentContainerStyle={styles.categoriesList}
            renderItem={({ item }) => {
              const isActive = item === activeCategory;
              return (
                <Pressable
                  onPress={() => setActiveCategory(item)}
                  style={[
                    styles.chip,
                    isActive ? styles.chipActive : styles.chipInactive,
                  ]}
                >
                  <Text
                    style={[
                      styles.chipText,
                      isActive ? styles.chipTextActive : styles.chipTextInactive,
                    ]}
                  >
                    {item}
                  </Text>
                </Pressable>
              );
            }}
          />
        </View>

        {/* 4. Template Grid */}
        <FlatList
          data={MOCK_DATA}
          keyExtractor={(item) => item.id}
          numColumns={2}
          contentContainerStyle={styles.gridContainer}
          columnWrapperStyle={styles.gridColumnWrapper}
          showsVerticalScrollIndicator={false}
          renderItem={renderCard}
        />
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: COLORS.background,
  },
  container: {
    flex: 1,
  },
  // Header
  headerRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: SIZES.padding,
    paddingTop: SIZES.padding,
    paddingBottom: 12,
  },
  greetingText: {
    fontFamily: FONTS.headline,
    fontSize: 22,
    color: COLORS.text,
  },
  bellIcon: {
    width: 40,
    height: 40,
    borderRadius: 20,
    borderWidth: 1,
    borderColor: COLORS.border,
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: COLORS.surface,
  },
  // Search
  searchContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: COLORS.surface,
    borderWidth: 1,
    borderColor: COLORS.border,
    borderRadius: SIZES.radius,
    marginHorizontal: SIZES.padding,
    paddingHorizontal: 12,
    height: 48,
    marginBottom: 16,
  },
  searchIcon: {
    marginRight: 8,
  },
  searchInput: {
    flex: 1,
    fontFamily: FONTS.body,
    fontSize: 14,
    color: COLORS.text,
  },
  // Categories
  categoriesWrapper: {
    marginBottom: 16,
  },
  categoriesList: {
    paddingHorizontal: SIZES.padding,
    gap: 8,
  },
  chip: {
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 20,
    borderWidth: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  chipActive: {
    backgroundColor: COLORS.primary,
    borderColor: COLORS.primary,
  },
  chipInactive: {
    backgroundColor: 'transparent',
    borderColor: COLORS.border,
  },
  chipText: {
    fontFamily: FONTS.label,
    fontSize: 13,
  },
  chipTextActive: {
    color: COLORS.textLight,
  },
  chipTextInactive: {
    color: COLORS.textMuted,
  },
  // Grid
  gridContainer: {
    paddingHorizontal: SIZES.padding,
    paddingBottom: 100, // 5. Clears bottom tab bar
    gap: 16,
  },
  gridColumnWrapper: {
    gap: 16,
  },
  cardContainer: {
    flex: 1,
  },
  imagePlaceholder: {
    aspectRatio: 0.75, // Tall rectangle
    backgroundColor: COLORS.border,
    borderRadius: SIZES.radius,
    marginBottom: 8,
    position: 'relative',
    overflow: 'hidden',
  },
  badge: {
    position: 'absolute',
    top: 8,
    right: 8,
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 6,
  },
  badgeText: {
    fontFamily: FONTS.label,
    fontSize: 10,
    textTransform: 'uppercase',
  },
  cardTitle: {
    fontFamily: FONTS.label,
    fontSize: 14,
    color: COLORS.text,
  },
});
