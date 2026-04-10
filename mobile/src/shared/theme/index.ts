/**
 * useAppTheme — Dynamic theme hook for "The Digital Atelier" design system.
 * Supports Light & Dark mode via React Native's useColorScheme.
 * Screens can manually override via ThemeContext (optional).
 *
 * Usage:
 *   const t = useAppTheme();
 *   backgroundColor: t.background
 */

import { useContext, createContext, useState, useCallback } from 'react';
import { useColorScheme } from 'react-native';

// ── Palette ──────────────────────────────────────────────────────────────────

export const lightPalette = {
  isDark: false,
  background: '#FFF7FC',
  surface: '#FFF7FC',
  surfaceContainerLow: '#FEEFFF',
  surfaceContainerHigh: '#F8E0FF',
  surfaceContainerHighest: '#F2DBFA',
  surfaceCard: '#FEEFFF',
  primary: '#630ED4',
  primaryContainer: '#7C3AED',
  onPrimary: '#FFFFFF',
  onSurface: '#24162C',
  onSurfaceVariant: '#4A4455',
  outline: '#7B7487',
  outlineVariant: '#CCC3D8',
  fieldBg: '#EDE0FF',
  fieldBgFocused: '#E4D0FA',
  error: '#BA1A1A',
  errorContainer: '#FFDAD6',
  secondary: '#B51C0B',
  teal: '#0D9DAD',
  tealBg: '#E6F7F9',
  amber: '#B45309',
  amberBg: '#FDF3E2',
  amberBorder: '#F7D8AB',
  infoBg: '#EDE0FF',
  infoBorder: '#CCC3D8',
  successIcon: '#1A6B3C',
  successBg: '#D1FAE5',
  successText: '#1A6B3C',
  googleBg: '#FFFFFF',
  googleBorder: '#E2E8F0',
  googleText: '#1A1A2E',
  appleBg: '#111111',
  appleText: '#FFFFFF',
  navBg: '#FFFFFF',
  navBorder: '#EDE7F3',
  navActiveTint: '#5B21B6',
  navActiveWrap: '#DDD4ED',
  navInactiveTint: '#64748B',
  chipBg: '#EBE0F0',
  chipActiveBg: '#5B21B6',
  chipText: '#2F2540',
  searchBg: '#EBE0F0',
  imagePlaceholder: '#D1C4DA',
  cardTitle: '#201332',
  cardSubtitle: '#70667E',
} as const;

export const darkPalette = {
  isDark: true,
  background: '#0D0815',
  surface: '#150E22',
  surfaceContainerLow: '#1E1530',
  surfaceContainerHigh: '#2A1E3F',
  surfaceContainerHighest: '#331F50',
  surfaceCard: '#1E1530',
  primary: '#C084FC',
  primaryContainer: '#7C3AED',
  onPrimary: '#1A0B2E',
  onSurface: '#EDE8F5',
  onSurfaceVariant: '#B9B0CB',
  outline: '#8B7FA3',
  outlineVariant: '#3D3155',
  fieldBg: '#1E1530',
  fieldBgFocused: '#2A1E3F',
  error: '#FFB4AB',
  errorContainer: '#4A1010',
  secondary: '#FF8A80',
  teal: '#4DD0E1',
  tealBg: '#0D2427',
  amber: '#FBBF24',
  amberBg: '#2A1E06',
  amberBorder: '#4A3610',
  infoBg: '#1E1530',
  infoBorder: '#3D3155',
  successIcon: '#4ADE80',
  successBg: '#0A2A1A',
  successText: '#4ADE80',
  googleBg: '#1E1E2E',
  googleBorder: '#3D3155',
  googleText: '#EDE8F5',
  appleBg: '#EDE8F5',
  appleText: '#0D0815',
  navBg: '#150E22',
  navBorder: '#2A1E3F',
  navActiveTint: '#C084FC',
  navActiveWrap: '#2A1E3F',
  navInactiveTint: '#6B6080',
  chipBg: '#1E1530',
  chipActiveBg: '#7C3AED',
  chipText: '#B9B0CB',
  searchBg: '#1E1530',
  imagePlaceholder: '#2A1E3F',
  cardTitle: '#EDE8F5',
  cardSubtitle: '#8B7FA3',
} as const;

export type AppTheme = typeof lightPalette | typeof darkPalette;

// ── Context (for manual override) ────────────────────────────────────────────

export type ThemeContextValue = {
  theme: AppTheme;
  isDark: boolean;
  toggleTheme: () => void;
};

export const ThemeContext = createContext<ThemeContextValue | null>(null);

// ── Hook ─────────────────────────────────────────────────────────────────────

/**
 * Returns the active theme palette + isDark flag + toggleTheme.
 * Priority: ThemeContext override → system color scheme.
 */
export function useAppTheme(): ThemeContextValue {
  const ctx = useContext(ThemeContext);
  const scheme = useColorScheme(); // must be called unconditionally

  if (ctx) return ctx;

  const theme: AppTheme = scheme === 'dark' ? darkPalette : lightPalette;
  // eslint-disable-next-line @typescript-eslint/no-empty-function
  return { theme, isDark: theme.isDark, toggleTheme: () => {} };
}

export { lightPalette as C_LIGHT, darkPalette as C_DARK };
