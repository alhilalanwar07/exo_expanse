import { F } from '../shared/theme/fonts';
import { colors } from '../shared/theme/colors';
import { Dimensions } from 'react-native';

const { width, height } = Dimensions.get('window');

export const COLORS = {
  primary: colors.accent,
  secondary: colors.info,
  text: colors.textPrimary,
  textLight: colors.textPrimary,
  textMuted: colors.textMuted,
  background: colors.background,
  surface: colors.surface,
  border: colors.border,
  premium: colors.warning,
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
