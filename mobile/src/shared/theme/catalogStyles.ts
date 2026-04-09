/**
 * Shared StyleSheet tokens for catalog/grid screens.
 * Used by: PublicHomeScreen, HomeScreen, ThemeCatalogScreen
 *
 * Design System: "The Digital Atelier" (Velvet Bloom 2025)
 */
import { StyleSheet } from 'react-native';
import { F } from './fonts';

// ── Color tokens ────────────────────────────────────────────────────────────
export const C = {
  // Backgrounds / surfaces
  background: '#FFF7FC',
  surfaceContainerLow: '#FEEFFF',
  surfaceContainerHighest: '#F2DBFA',
  surfaceContainer: '#FBE8FF',
  // Typography
  onSurface: '#24162C',
  onSurfaceVariant: '#4A4455',
  outline: '#7B7487',
  outlineVariant: '#CCC3D8',
  // Brand
  primary: '#630ED4',
  primaryContainer: '#7C3AED',
  onPrimary: '#FFFFFF',
  // Badge
  premiumBadgeBg: '#D93723',
  premiumBadgeText: '#FFFFFF',
  freeBadgeBg: 'rgba(255,255,255,0.92)',
  freeBadgeText: '#630ED4',
  // Nav bar
  navBg: '#FFFFFF',
  navBorder: '#EDE7F3',
  navActiveTint: '#5B21B6',
  navActiveWrap: '#DDD4ED',
  navInactiveTint: '#64748B',
  // Misc
  searchBg: '#EBE0F0',
  chipBg: '#EBE0F0',
  chipActiveBg: '#5B21B6',
  chipText: '#2F2540',
  chipTextActive: '#FFFFFF',
  imagePlaceholder: '#D1C4DA',
  emptyCardBg: '#EFE5F4',
  emptyCardBorder: '#E7DBEE',
  emptyTitleColor: '#2B1A41',
  emptySubtitleColor: '#6B5F79',
  cardTitleColor: '#201332',
  cardSubtitleColor: '#70667E',
  accountButtonBg: '#24162C',
  accountButtonBorder: '#130B24',
  warningBg: '#FDF3E2',
  warningBorder: '#F7D8AB',
  warningText: '#9A3412',
  warningIcon: '#B45309',
  ownerCardBg: '#EFE5F4',
  ownerCardBorder: '#E7DBEE',
  ownerTitleColor: '#28163E',
  ownerSubtitleColor: '#5E536E',
  ownerApiColor: '#6D28D9',
} as const;

// ── Typography scale ─────────────────────────────────────────────────────────
export const T = {
  searchFontSize: 16,
  searchFontSizeCompact: 14,
  chipFontSize: 14,
  chipFontSizeCompact: 13,
  cardTitleFontSize: 15,
  cardTitleFontSizeCompact: 14,
  cardSubtitleFontSize: 12,
  cardSubtitleFontSizeCompact: 11,
  badgeFontSize: 9,
} as const;

// ── Shared StyleSheet ─────────────────────────────────────────────────────────
export const catalogStyles = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: C.background,
  },
  container: {
    flex: 1,
  },

  // Account / action button (top-right)
  accountButton: {
    width: 44,
    height: 44,
    borderRadius: 22,
    backgroundColor: C.accountButtonBg,
    alignItems: 'center',
    justifyContent: 'center',
    borderWidth: 1,
    borderColor: C.accountButtonBorder,
  },

  // Scroll content
  scrollContent: {
    paddingHorizontal: 18,
    paddingTop: 16,
    paddingBottom: 140,
  },
  scrollContentCompact: {
    paddingHorizontal: 14,
    paddingTop: 12,
    paddingBottom: 120,
  },

  // Search field
  searchBox: {
    height: 52,
    borderRadius: 16,
    backgroundColor: C.searchBg,
    paddingHorizontal: 16,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,
  },
  searchBoxCompact: {
    height: 46,
    borderRadius: 14,
    paddingHorizontal: 12,
    gap: 8,
  },
  searchInput: {
    flex: 1,
    color: C.onSurface,
    fontSize: T.searchFontSize,
    paddingVertical: 0,
    fontFamily: F.bodyMedium,
  },
  searchInputCompact: {
    fontSize: T.searchFontSizeCompact,
  },

  // Category filter chips (horizontal scroll)
  categoryRow: {
    gap: 10,
    paddingTop: 14,
    paddingBottom: 10,
    paddingRight: 4,
  },
  categoryRowCompact: {
    gap: 8,
    paddingTop: 10,
    paddingBottom: 8,
  },
  categoryChip: {
    height: 38,
    borderRadius: 19,
    backgroundColor: C.chipBg,
    paddingHorizontal: 18,
    alignItems: 'center',
    justifyContent: 'center',
  },
  categoryChipCompact: {
    height: 34,
    borderRadius: 17,
    paddingHorizontal: 14,
  },
  categoryChipActive: {
    backgroundColor: C.chipActiveBg,
  },
  categoryText: {
    color: C.chipText,
    fontSize: T.chipFontSize,
    fontFamily: F.label,
  },
  categoryTextCompact: {
    fontSize: T.chipFontSizeCompact,
  },
  categoryTextActive: {
    color: C.chipTextActive,
    fontFamily: F.labelBold,
  },

  // Theme card grid
  grid: {
    marginTop: 6,
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
    rowGap: 16,
  },
  card: {
    width: '48.2%',
  },
  imageWrap: {
    borderRadius: 18,
    overflow: 'hidden',
    backgroundColor: C.imagePlaceholder,
    aspectRatio: 0.75,
    position: 'relative',
  },
  image: {
    width: '100%',
    height: '100%',
  },
  badge: {
    position: 'absolute',
    top: 10,
    right: 10,
    borderRadius: 8,
    paddingHorizontal: 8,
    paddingVertical: 4,
  },
  premiumBadge: {
    backgroundColor: C.premiumBadgeBg,
  },
  freeBadge: {
    backgroundColor: C.freeBadgeBg,
  },
  badgeLabel: {
    fontSize: T.badgeFontSize,
    fontFamily: F.labelBold,
    letterSpacing: 0.6,
  },
  premiumBadgeLabel: {
    color: C.premiumBadgeText,
  },
  freeBadgeLabel: {
    color: C.freeBadgeText,
  },
  cardTitle: {
    marginTop: 10,
    color: C.cardTitleColor,
    fontSize: T.cardTitleFontSize,
    fontFamily: F.heading,
  },
  cardTitleCompact: {
    marginTop: 8,
    fontSize: T.cardTitleFontSizeCompact,
  },
  cardSubtitle: {
    marginTop: 2,
    color: C.cardSubtitleColor,
    fontSize: T.cardSubtitleFontSize,
    fontFamily: F.bodyMedium,
  },
  cardSubtitleCompact: {
    fontSize: T.cardSubtitleFontSizeCompact,
  },

  // Empty state
  emptyStateCard: {
    width: '100%',
    borderRadius: 18,
    borderWidth: 1,
    borderColor: C.emptyCardBorder,
    backgroundColor: C.emptyCardBg,
    paddingVertical: 28,
    paddingHorizontal: 16,
    alignItems: 'center',
    gap: 8,
  },
  emptyStateCardCompact: {
    borderRadius: 14,
    paddingVertical: 22,
    paddingHorizontal: 12,
    gap: 6,
  },
  emptyStateTitle: {
    color: C.emptyTitleColor,
    fontSize: 15,
    fontFamily: F.heading,
  },
  emptyStateTitleCompact: {
    fontSize: 14,
  },
  emptyStateSubtitle: {
    color: C.emptySubtitleColor,
    fontSize: 13,
    textAlign: 'center',
    lineHeight: 19,
    fontFamily: F.body,
  },
  emptyStateSubtitleCompact: {
    fontSize: 12,
    lineHeight: 17,
  },

  // Bottom navigation (floating pill)
  bottomNavShell: {
    position: 'absolute',
    left: 0,
    right: 0,
    bottom: 0,
    paddingHorizontal: 12,
    paddingBottom: 10,
    backgroundColor: 'transparent',
  },
  bottomNavShellCompact: {
    paddingHorizontal: 10,
    paddingBottom: 8,
  },
  bottomNav: {
    height: 80,
    borderRadius: 26,
    backgroundColor: C.navBg,
    borderWidth: 1,
    borderColor: C.navBorder,
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-around',
    paddingHorizontal: 8,
    shadowColor: '#1A0B2E',
    shadowOpacity: 0.1,
    shadowRadius: 14,
    shadowOffset: { width: 0, height: 4 },
    elevation: 4,
  },
  bottomNavCompact: {
    height: 70,
    borderRadius: 22,
  },
  bottomItem: {
    width: '31%',
    height: 60,
    borderRadius: 16,
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 3,
  },
  bottomItemCompact: {
    height: 54,
    borderRadius: 13,
    gap: 2,
  },
  bottomItemActiveIconWrap: {
    width: 36,
    height: 36,
    borderRadius: 12,
    backgroundColor: C.navActiveWrap,
    alignItems: 'center',
    justifyContent: 'center',
  },
  bottomItemActiveIconWrapCompact: {
    width: 32,
    height: 32,
    borderRadius: 10,
  },
  bottomItemActiveLabel: {
    color: C.navActiveTint,
    fontSize: 10,
    fontFamily: F.labelBold,
    letterSpacing: 0.4,
    textTransform: 'uppercase',
  },
  bottomItemActiveLabelCompact: {
    fontSize: 9,
  },
  bottomItemLabel: {
    color: C.navInactiveTint,
    fontSize: 10,
    fontFamily: F.label,
    letterSpacing: 0.3,
    textTransform: 'uppercase',
  },
  bottomItemLabelCompact: {
    fontSize: 9,
  },

  // Interaction feedback
  buttonPressed: {
    opacity: 0.86,
    transform: [{ scale: 0.985 }],
  },
});
