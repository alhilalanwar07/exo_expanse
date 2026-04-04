import { ActivityIndicator, StyleSheet, Text, View } from 'react-native';

import { ScreenContainer } from '../shared/components/ScreenContainer';
import { colors } from '../shared/theme/colors';

export function LoadingScreen() {
  return (
    <ScreenContainer scrollable={false}>
      <View style={styles.container}>
        <ActivityIndicator size="large" color={colors.accent} />
        <Text style={styles.text}>Menyiapkan sesi aplikasi...</Text>
      </View>
    </ScreenContainer>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    gap: 14,
  },
  text: {
    color: colors.textSecondary,
    fontSize: 14,
  },
});
