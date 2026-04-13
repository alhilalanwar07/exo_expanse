import type { PropsWithChildren, ReactNode } from 'react';
import {
  KeyboardAvoidingView,
  Platform,
  ScrollView,
  type StyleProp,
  StyleSheet,
  useWindowDimensions,
  type ViewStyle,
  View,
} from 'react-native';
import { SafeAreaView, useSafeAreaInsets } from 'react-native-safe-area-context';

import { colors } from '../theme/colors';

export const SCREEN_CONTAINER_LAYOUT = {
  compactBreakpoint: 390,
  horizontalPadding: { compact: 18, regular: 24 },
  topPadding: { compact: 16, regular: 22 },
  bottomPadding: { compact: 18, regular: 24 },
  defaultContentGap: 16,
  defaultMaxContentWidth: 420,
} as const;

type ScreenContainerProps = PropsWithChildren<{
  scrollable?: boolean;
  keyboardVerticalOffset?: number;
  contentGap?: number;
  maxContentWidth?: number;
  keyboardBehavior?: 'height' | 'position' | 'padding';
  header?: ReactNode;
  backgroundColor?: string;
  showBackgroundEffects?: boolean;
  contentStyle?: StyleProp<ViewStyle>;
  scrollContentStyle?: StyleProp<ViewStyle>;
}>;

export function ScreenContainer({
  children,
  scrollable = true,
  keyboardVerticalOffset = 0,
  contentGap = SCREEN_CONTAINER_LAYOUT.defaultContentGap,
  maxContentWidth = SCREEN_CONTAINER_LAYOUT.defaultMaxContentWidth,
  keyboardBehavior,
  header,
  backgroundColor = colors.background,
  showBackgroundEffects = true,
  contentStyle,
  scrollContentStyle,
}: ScreenContainerProps) {
  const insets = useSafeAreaInsets();
  const { width } = useWindowDimensions();
  const isCompact = width <= SCREEN_CONTAINER_LAYOUT.compactBreakpoint;

  const horizontalPadding = isCompact
    ? SCREEN_CONTAINER_LAYOUT.horizontalPadding.compact
    : SCREEN_CONTAINER_LAYOUT.horizontalPadding.regular;
  const topPadding = isCompact
    ? SCREEN_CONTAINER_LAYOUT.topPadding.compact
    : SCREEN_CONTAINER_LAYOUT.topPadding.regular;
  const baseBottomPadding = isCompact
    ? SCREEN_CONTAINER_LAYOUT.bottomPadding.compact
    : SCREEN_CONTAINER_LAYOUT.bottomPadding.regular;
  const bottomPadding = Math.max(baseBottomPadding, insets.bottom + 12);

  const contentPaddingStyle = {
    paddingHorizontal: horizontalPadding,
    paddingTop: topPadding,
    paddingBottom: bottomPadding,
  };

  const contentInnerStyle = {
    maxWidth: maxContentWidth,
    gap: contentGap,
  };

  return (
    <SafeAreaView style={[styles.safeArea, { backgroundColor }]} edges={['top', 'left', 'right']}>
      {showBackgroundEffects ? (
        <>
          <View pointerEvents="none" style={styles.backgroundTopGlow} />
          <View pointerEvents="none" style={styles.backgroundMiddleGlow} />
          <View pointerEvents="none" style={styles.backgroundBottomGlow} />
          <View pointerEvents="none" style={styles.backgroundGrid} />
        </>
      ) : null}

      {header}

      <KeyboardAvoidingView
        style={styles.keyboardWrapper}
        behavior={keyboardBehavior ?? (Platform.OS === 'ios' ? 'padding' : undefined)}
        keyboardVerticalOffset={keyboardVerticalOffset}
      >
        {scrollable ? (
          <ScrollView
            contentContainerStyle={[styles.scrollContent, contentPaddingStyle, scrollContentStyle]}
            contentInsetAdjustmentBehavior="automatic"
            automaticallyAdjustKeyboardInsets
            keyboardDismissMode={Platform.OS === 'ios' ? 'interactive' : 'on-drag'}
            keyboardShouldPersistTaps="handled"
            showsVerticalScrollIndicator={false}
          >
            <View style={[styles.contentInner, contentInnerStyle, contentStyle]}>{children}</View>
          </ScrollView>
        ) : (
          <View style={[styles.content, contentPaddingStyle]}>
            <View style={[styles.contentInner, styles.contentInnerFill, contentInnerStyle, contentStyle]}>{children}</View>
          </View>
        )}
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: {
    flex: 1,
    backgroundColor: colors.background,
  },
  scrollContent: {
    flexGrow: 1,
  },
  keyboardWrapper: {
    flex: 1,
  },
  content: {
    flex: 1,
  },
  contentInner: {
    width: '100%',
    alignSelf: 'center',
  },
  contentInnerFill: {
    flex: 1,
  },
  backgroundTopGlow: {
    position: 'absolute',
    top: -160,
    right: -120,
    width: 340,
    height: 340,
    borderRadius: 999,
    backgroundColor: 'rgba(244, 63, 94, 0.22)',
  },
  backgroundMiddleGlow: {
    position: 'absolute',
    top: '36%',
    left: '44%',
    width: 220,
    height: 220,
    borderRadius: 999,
    backgroundColor: 'rgba(56, 189, 248, 0.12)',
  },
  backgroundBottomGlow: {
    position: 'absolute',
    bottom: -180,
    left: -120,
    width: 420,
    height: 420,
    borderRadius: 999,
    backgroundColor: 'rgba(251, 191, 36, 0.16)',
  },
  backgroundGrid: {
    ...StyleSheet.absoluteFillObject,
    opacity: 1,
    backgroundColor: 'rgba(148, 163, 184, 0.04)',
  },
});
