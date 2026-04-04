import { useMemo, useState } from 'react';
import { Pressable, StyleSheet, Text, TextInput, View } from 'react-native';

import {
  getInvitationStats,
  getInvitationWishes,
  submitRsvp,
  submitWish,
} from '../features/invitations/invitation.api';
import type {
  AttendanceStatus,
  InvitationStatsResponse,
  InvitationWish,
} from '../features/invitations/invitation.types';
import { useAuth } from '../features/auth/AuthContext';
import { ScreenContainer } from '../shared/components/ScreenContainer';
import { colors } from '../shared/theme/colors';

const defaultInvitationIdentifier = 'demo';

export function InvitationHubScreen() {
  const { session } = useAuth();

  const [invitationIdentifier, setInvitationIdentifier] = useState(defaultInvitationIdentifier);
  const [stats, setStats] = useState<InvitationStatsResponse | null>(null);
  const [wishes, setWishes] = useState<InvitationWish[]>([]);
  const [isLoadingData, setIsLoadingData] = useState(false);
  const [dataError, setDataError] = useState<string | null>(null);

  const [rsvpName, setRsvpName] = useState(session?.ownerName ?? '');
  const [rsvpStatus, setRsvpStatus] = useState<AttendanceStatus>('confirmed');
  const [rsvpPax, setRsvpPax] = useState('1');
  const [isSubmittingRsvp, setIsSubmittingRsvp] = useState(false);

  const [wishName, setWishName] = useState(session?.ownerName ?? '');
  const [wishMessage, setWishMessage] = useState('Selamat menempuh hidup baru, semoga bahagia selalu.');
  const [isSubmittingWish, setIsSubmittingWish] = useState(false);

  const invitationPathInfo = useMemo(
    () => `/api/invitations/${invitationIdentifier.trim() || '{id-atau-slug}'}`,
    [invitationIdentifier]
  );

  const loadInvitationData = async () => {
    const normalizedIdentifier = invitationIdentifier.trim();

    if (!normalizedIdentifier) {
      setDataError('Isi ID atau slug invitation terlebih dahulu.');
      return;
    }

    try {
      setIsLoadingData(true);
      setDataError(null);

      const [statsResponse, wishesResponse] = await Promise.all([
        getInvitationStats(normalizedIdentifier),
        getInvitationWishes(normalizedIdentifier, { limit: 5, offset: 0 }),
      ]);

      setStats(statsResponse);
      setWishes(wishesResponse.wishes);
    } catch (error) {
      setDataError(error instanceof Error ? error.message : 'Gagal mengambil data invitation.');
    } finally {
      setIsLoadingData(false);
    }
  };

  const handleSubmitRsvp = async () => {
    const normalizedIdentifier = invitationIdentifier.trim();

    if (!normalizedIdentifier) {
      setDataError('Isi ID atau slug invitation terlebih dahulu.');
      return;
    }

    try {
      setIsSubmittingRsvp(true);
      setDataError(null);

      await submitRsvp(normalizedIdentifier, {
        name: rsvpName,
        status: rsvpStatus,
        pax: Number(rsvpPax || '0'),
      });

      await loadInvitationData();
    } catch (error) {
      setDataError(error instanceof Error ? error.message : 'Gagal mengirim RSVP.');
    } finally {
      setIsSubmittingRsvp(false);
    }
  };

  const handleSubmitWish = async () => {
    const normalizedIdentifier = invitationIdentifier.trim();

    if (!normalizedIdentifier) {
      setDataError('Isi ID atau slug invitation terlebih dahulu.');
      return;
    }

    try {
      setIsSubmittingWish(true);
      setDataError(null);

      await submitWish(normalizedIdentifier, {
        name: wishName,
        message: wishMessage,
      });

      await loadInvitationData();
    } catch (error) {
      setDataError(error instanceof Error ? error.message : 'Gagal mengirim ucapan.');
    } finally {
      setIsSubmittingWish(false);
    }
  };

  return (
    <ScreenContainer>
      <View style={styles.card}>
        <Text style={styles.sectionLabel}>Data Source</Text>
        <Text style={styles.sectionTitle}>Invitation Identifier</Text>
        <TextInput
          value={invitationIdentifier}
          onChangeText={setInvitationIdentifier}
          style={styles.input}
          placeholder="Contoh: demo atau 1"
          placeholderTextColor={colors.textSecondary}
          autoCapitalize="none"
        />
        <Text style={styles.hint}>Path API: {invitationPathInfo}</Text>
        <Pressable
          onPress={loadInvitationData}
          disabled={isLoadingData}
          style={({ pressed }) => [styles.button, pressed && styles.buttonPressed, isLoadingData && styles.buttonDisabled]}
        >
          <Text style={styles.buttonText}>{isLoadingData ? 'Memuat...' : 'Muat Stats + Wishes'}</Text>
        </Pressable>
        {dataError ? <Text style={styles.errorText}>{dataError}</Text> : null}
      </View>

      <View style={styles.card}>
        <Text style={styles.sectionTitle}>Ringkasan</Text>
        <Text style={styles.statText}>Total Wishes: {stats?.total_wishes ?? '-'}</Text>
        <Text style={styles.statText}>Total Confirmed Pax: {stats?.total_confirmed ?? '-'}</Text>
        <Text style={styles.statText}>Total Guests: {stats?.total_guests ?? '-'}</Text>
      </View>

      <View style={styles.card}>
        <Text style={styles.sectionTitle}>Kirim RSVP</Text>
        <TextInput
          value={rsvpName}
          onChangeText={setRsvpName}
          style={styles.input}
          placeholder="Nama tamu"
          placeholderTextColor={colors.textSecondary}
        />
        <View style={styles.row}>
          <Pressable
            onPress={() => setRsvpStatus('confirmed')}
            style={[styles.pillButton, rsvpStatus === 'confirmed' && styles.pillButtonActive]}
          >
            <Text
              style={[
                styles.pillButtonText,
                rsvpStatus === 'confirmed' && styles.pillButtonTextActive,
              ]}
            >
              Confirmed
            </Text>
          </Pressable>
          <Pressable
            onPress={() => setRsvpStatus('declined')}
            style={[styles.pillButton, rsvpStatus === 'declined' && styles.pillButtonActive]}
          >
            <Text
              style={[
                styles.pillButtonText,
                rsvpStatus === 'declined' && styles.pillButtonTextActive,
              ]}
            >
              Declined
            </Text>
          </Pressable>
        </View>
        <TextInput
          value={rsvpPax}
          onChangeText={setRsvpPax}
          style={styles.input}
          placeholder="Pax"
          placeholderTextColor={colors.textSecondary}
          keyboardType="number-pad"
        />
        <Pressable
          onPress={handleSubmitRsvp}
          disabled={isSubmittingRsvp}
          style={({ pressed }) => [
            styles.buttonSecondary,
            pressed && styles.buttonPressed,
            isSubmittingRsvp && styles.buttonDisabled,
          ]}
        >
          <Text style={styles.buttonSecondaryText}>
            {isSubmittingRsvp ? 'Mengirim...' : 'Kirim RSVP'}
          </Text>
        </Pressable>
      </View>

      <View style={styles.card}>
        <Text style={styles.sectionTitle}>Kirim Ucapan</Text>
        <TextInput
          value={wishName}
          onChangeText={setWishName}
          style={styles.input}
          placeholder="Nama"
          placeholderTextColor={colors.textSecondary}
        />
        <TextInput
          value={wishMessage}
          onChangeText={setWishMessage}
          style={[styles.input, styles.multilineInput]}
          placeholder="Ucapan"
          placeholderTextColor={colors.textSecondary}
          multiline
        />
        <Pressable
          onPress={handleSubmitWish}
          disabled={isSubmittingWish}
          style={({ pressed }) => [
            styles.buttonSecondary,
            pressed && styles.buttonPressed,
            isSubmittingWish && styles.buttonDisabled,
          ]}
        >
          <Text style={styles.buttonSecondaryText}>
            {isSubmittingWish ? 'Mengirim...' : 'Kirim Ucapan'}
          </Text>
        </Pressable>
      </View>

      <View style={styles.card}>
        <Text style={styles.sectionTitle}>Wishes Terbaru</Text>
        {wishes.length === 0 ? (
          <Text style={styles.emptyText}>Belum ada data wishes.</Text>
        ) : (
          wishes.map((wish) => (
            <View key={wish.id} style={styles.wishItem}>
              <Text style={styles.wishName}>
                {wish.name} • {wish.time}
              </Text>
              <Text style={styles.wishMessage}>{wish.message}</Text>
            </View>
          ))
        )}
      </View>
    </ScreenContainer>
  );
}

const styles = StyleSheet.create({
  card: {
    backgroundColor: colors.surface,
    borderColor: colors.border,
    borderWidth: 1,
    borderRadius: 16,
    padding: 16,
    marginBottom: 14,
    gap: 10,
  },
  sectionLabel: {
    color: colors.accent,
    fontSize: 12,
    fontWeight: '700',
    textTransform: 'uppercase',
    letterSpacing: 0.8,
  },
  sectionTitle: {
    color: colors.textPrimary,
    fontSize: 18,
    fontWeight: '700',
  },
  input: {
    backgroundColor: '#FCFBF9',
    borderColor: colors.border,
    borderWidth: 1,
    borderRadius: 12,
    paddingHorizontal: 12,
    paddingVertical: 10,
    color: colors.textPrimary,
    fontSize: 15,
  },
  multilineInput: {
    minHeight: 90,
    textAlignVertical: 'top',
  },
  hint: {
    color: colors.textSecondary,
    fontSize: 12,
  },
  button: {
    backgroundColor: colors.accent,
    borderRadius: 12,
    paddingHorizontal: 14,
    paddingVertical: 12,
    alignItems: 'center',
  },
  buttonSecondary: {
    borderColor: colors.accent,
    borderWidth: 1,
    borderRadius: 12,
    paddingHorizontal: 14,
    paddingVertical: 12,
    alignItems: 'center',
    backgroundColor: '#FFF8EF',
  },
  buttonPressed: {
    opacity: 0.9,
  },
  buttonDisabled: {
    opacity: 0.55,
  },
  buttonText: {
    color: '#FFFFFF',
    fontWeight: '700',
    fontSize: 14,
  },
  buttonSecondaryText: {
    color: colors.accent,
    fontWeight: '700',
    fontSize: 14,
  },
  errorText: {
    color: '#B91C1C',
    fontSize: 13,
  },
  statText: {
    color: colors.textPrimary,
    fontSize: 15,
  },
  row: {
    flexDirection: 'row',
    gap: 8,
  },
  pillButton: {
    flex: 1,
    borderRadius: 999,
    borderWidth: 1,
    borderColor: colors.border,
    paddingVertical: 10,
    alignItems: 'center',
    backgroundColor: '#FFFFFF',
  },
  pillButtonActive: {
    borderColor: colors.accent,
    backgroundColor: '#FBEEDB',
  },
  pillButtonText: {
    color: colors.textSecondary,
    fontSize: 13,
    fontWeight: '600',
  },
  pillButtonTextActive: {
    color: colors.accent,
  },
  emptyText: {
    color: colors.textSecondary,
    fontSize: 14,
  },
  wishItem: {
    borderTopWidth: 1,
    borderTopColor: colors.border,
    paddingTop: 10,
    gap: 4,
  },
  wishName: {
    color: colors.textPrimary,
    fontSize: 14,
    fontWeight: '700',
  },
  wishMessage: {
    color: colors.textSecondary,
    fontSize: 14,
    lineHeight: 20,
  },
});
