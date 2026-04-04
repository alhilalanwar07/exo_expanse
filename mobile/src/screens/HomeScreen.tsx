import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { Pressable, StyleSheet, Text, View } from 'react-native';

import { env, isUsingFallbackApiBaseUrl } from '../config/env';
import { useAuth } from '../features/auth/AuthContext';
import type { AppStackParamList } from '../navigation/types';
import { ScreenContainer } from '../shared/components/ScreenContainer';
import { colors } from '../shared/theme/colors';

const setupChecklist = [
  'Tahap 2: aktifkan endpoint exchange token mobile owner',
  'Tahap 3: dashboard owner + daftar undangan',
  'Tahap 4: editor undangan (cover, mempelai, acara, settings)',
  'Tahap 5: tamu, sebar link, dan WhatsApp generator',
];

export function HomeScreen() {
  const navigation = useNavigation<NativeStackNavigationProp<AppStackParamList>>();
  const { session, disconnectDevice } = useAuth();

  return (
    <ScreenContainer>
      <View style={styles.heroCard}>
        <Text style={styles.appLabel}>Exo Expanse Mobile</Text>
        <Text style={styles.title}>{session?.workspaceLabel ?? 'Workspace Owner'}</Text>
        <Text style={styles.subtitle}>Pemilik: {session?.ownerName ?? '-'}</Text>
        <Text style={styles.subtitle}>Perangkat: {session?.deviceAlias ?? '-'}</Text>
        <Text style={styles.subtitle}>Base URL API: {env.apiBaseUrl}</Text>
        {isUsingFallbackApiBaseUrl ? (
          <Text style={styles.warning}>
            Gunakan EXPO_PUBLIC_API_BASE_URL pada file .env agar tidak memakai fallback.
          </Text>
        ) : null}

        <Pressable
          onPress={() => navigation.navigate('InvitationHub')}
          style={({ pressed }) => [styles.primaryButton, pressed && styles.buttonPressed]}
        >
          <Text style={styles.primaryButtonText}>Buka Invitation Hub</Text>
        </Pressable>

        <Pressable
          onPress={disconnectDevice}
          style={({ pressed }) => [styles.secondaryButton, pressed && styles.buttonPressed]}
        >
          <Text style={styles.secondaryButtonText}>Putuskan Perangkat</Text>
        </Pressable>
      </View>

      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Roadmap Mobile Owner</Text>
        {setupChecklist.map((item) => (
          <View key={item} style={styles.itemRow}>
            <View style={styles.dot} />
            <Text style={styles.itemText}>{item}</Text>
          </View>
        ))}
      </View>
    </ScreenContainer>
  );
}

const styles = StyleSheet.create({
  heroCard: {
    backgroundColor: colors.surface,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: colors.border,
    padding: 18,
    gap: 8,
  },
  appLabel: {
    color: colors.accent,
    fontSize: 12,
    fontWeight: '700',
    letterSpacing: 1,
    textTransform: 'uppercase',
  },
  title: {
    color: colors.textPrimary,
    fontSize: 24,
    fontWeight: '800',
  },
  subtitle: {
    color: colors.textSecondary,
    fontSize: 14,
  },
  warning: {
    color: colors.warning,
    fontSize: 13,
    fontWeight: '600',
  },
  primaryButton: {
    marginTop: 8,
    backgroundColor: colors.accent,
    borderRadius: 12,
    paddingVertical: 12,
    alignItems: 'center',
  },
  primaryButtonText: {
    color: '#FFFFFF',
    fontWeight: '700',
    fontSize: 14,
  },
  secondaryButton: {
    marginTop: 4,
    borderColor: colors.accent,
    borderWidth: 1,
    borderRadius: 12,
    paddingVertical: 12,
    alignItems: 'center',
    backgroundColor: '#FFF8EF',
  },
  secondaryButtonText: {
    color: colors.accent,
    fontWeight: '700',
    fontSize: 14,
  },
  buttonPressed: {
    opacity: 0.9,
  },
  section: {
    marginTop: 20,
    backgroundColor: colors.surface,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: colors.border,
    padding: 18,
    gap: 12,
  },
  sectionTitle: {
    color: colors.textPrimary,
    fontSize: 18,
    fontWeight: '700',
  },
  itemRow: {
    flexDirection: 'row',
    gap: 10,
    alignItems: 'flex-start',
  },
  dot: {
    width: 8,
    height: 8,
    borderRadius: 999,
    backgroundColor: colors.accent,
    marginTop: 7,
  },
  itemText: {
    flex: 1,
    color: colors.textPrimary,
    fontSize: 15,
    lineHeight: 22,
  },
});
