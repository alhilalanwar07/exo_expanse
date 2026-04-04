import type { PropsWithChildren } from 'react';
import { SafeAreaView, ScrollView, StyleSheet, View } from 'react-native';

import { colors } from '../theme/colors';

type ScreenContainerProps = PropsWithChildren<{
  scrollable?: boolean;
}>;

export function ScreenContainer({
  children,
  scrollable = true,
}: ScreenContainerProps) {
  const content = <View style={styles.content}>{children}</View>;

  return (
    <SafeAreaView style={styles.safeArea}>
      <View pointerEvents="none" style={styles.backgroundTopGlow} />
      <View pointerEvents="none" style={styles.backgroundBottomGlow} />
      {scrollable ? (
        <ScrollView contentContainerStyle={styles.scrollContent}>{content}</ScrollView>
      ) : (
        content
      )}
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
  content: {
    flex: 1,
    paddingHorizontal: 20,
    paddingVertical: 16,
  },
  backgroundTopGlow: {
    position: 'absolute',
    top: -50,
    right: -20,
    width: 180,
    height: 180,
    borderRadius: 999,
    backgroundColor: `${colors.accent}22`,
  },
  backgroundBottomGlow: {
    position: 'absolute',
    bottom: -60,
    left: -30,
    width: 220,
    height: 220,
    borderRadius: 999,
    backgroundColor: `${colors.accent}16`,
  },
});
