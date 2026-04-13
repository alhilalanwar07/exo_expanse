import { F } from '../shared/theme/fonts';
import { colors } from '../shared/theme/colors';
import { Dimensions } from 'react-native';

const { width, height } = Dimensions.get('window');

export const COLORS = {
  primary: colors.accent,
  primaryLight: colors.accentLight,
  primaryDark: colors.accentDark,
  secondary: colors.info,
  text: colors.textPrimary,
  textLight: colors.textSecondary,
  textMuted: colors.textMuted,
  textOnPrimary: '#FFFFFF',
  background: colors.background,
  surface: colors.surface,
  surfaceMuted: colors.surfaceMuted,
  border: colors.border,
  borderFocus: colors.borderFocus,
  premium: colors.warning,
  success: colors.success,
  danger: colors.danger,
  info: colors.info,
};

export const FONTS = {
  headline: F.display,
  label: F.labelBold,
  body: F.body,
};

export const SIZES = {
  padding: 16,
  radius: 12,
  width,
  height,
};
