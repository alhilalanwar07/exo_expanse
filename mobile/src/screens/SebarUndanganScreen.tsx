import { useState, useCallback } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TextInput,
  ScrollView,
  Share,
  Linking,
  ActivityIndicator,
  Alert,
  Pressable,
} from 'react-native';
import { SafeAreaView, useSafeAreaInsets } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useRoute, useNavigation } from '@react-navigation/native';
import type { RouteProp } from '@react-navigation/native';

import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import { httpRequest, HttpClientError } from '../services/httpClient';
import type { RootStackParamList } from '../navigation/types';

type SebarUndanganRouteProp = RouteProp<RootStackParamList, 'SebarUndangan'>;

type Guest = {
  id?: number | string;
  name: string;
  isSaved: boolean;
};

const TEMPLATES = [
  {
    id: 'formal',
    name: 'Formal',
    icon: '🤵',
    content: "Kepada Yth. {nama},\n\nTanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk hadir dan memberikan doa restu pada acara pernikahan kami.\n\nBerikut link undangan kami:\n{link}\n\nMerupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir di acara kami.\n\nTerima kasih.",
  },
  {
    id: 'casual',
    name: 'Kasual',
    icon: '🤗',
    content: "Halo {nama}!\n\nSemoga kabarmu baik ya. Kami ingin mengundangmu untuk hadir di acara resepsi pernikahan kami.\n\nDetail lengkapnya bisa dilihat di link berikut:\n{link}\n\nKehadiranmu akan sangat berarti bagi kami. Sampai jumpa!\n\nTerima kasih.",
  },
];

export function SebarUndanganScreen() {
  const route = useRoute<SebarUndanganRouteProp>();
  const navigation = useNavigation();
  const { theme } = useAppTheme();
  const insets = useSafeAreaInsets();
  const s = makeStyles(theme);

  const { invitationId, invitationTitle, invitationUrl } = route.params;

  const [guests, setGuests] = useState<Guest[]>([]);
  const [newGuestName, setNewGuestName] = useState('');
  const [isLoadingGuests, setIsLoadingGuests] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const [selectedTemplateId, setSelectedTemplateId] = useState<'formal' | 'casual'>('formal');

  const selectedTemplate = TEMPLATES.find((t) => t.id === selectedTemplateId) || TEMPLATES[0];

  const handleAddGuest = () => {
    const name = newGuestName.trim();
    if (!name) return;

    if (guests.some((g) => g.name.toLowerCase() === name.toLowerCase())) {
      Alert.alert('Info', 'Tamu sudah ada di daftar.');
      return;
    }

    setGuests([{ name, isSaved: false }, ...guests]);
    setNewGuestName('');
  };

  const handleRemoveGuest = (index: number) => {
    const updated = [...guests];
    updated.splice(index, 1);
    setGuests(updated);
  };

  const handleLoadExistingGuests = async () => {
    try {
      setIsLoadingGuests(true);
      const response = await httpRequest<{
        data: { id: number; name: string; phone?: string; status: string }[];
      }>(`/api/mobile/access/invitations/${invitationId}/guests`, {
        authMode: 'required',
      });

      const existingData = response.data || [];
      const newGuests: Guest[] = [];

      existingData.forEach((item) => {
        if (!guests.some((g) => g.name.toLowerCase() === item.name.toLowerCase())) {
          newGuests.push({
            id: item.id,
            name: item.name,
            isSaved: true,
          });
        }
      });

      if (newGuests.length > 0) {
        setGuests([...guests, ...newGuests]);
        Alert.alert('Berhasil', `${newGuests.length} tamu berhasil dimuat.`);
      } else {
        Alert.alert('Info', 'Semua tamu sudah termuat.');
      }
    } catch (error) {
      console.error(error);
      Alert.alert('Gagal', 'Tidak dapat memuat daftar tamu.');
    } finally {
      setIsLoadingGuests(false);
    }
  };

  const handleSaveToGuestList = async () => {
    const unsavedGuests = guests.filter((g) => !g.isSaved).map((g) => g.name);
    
    if (unsavedGuests.length === 0) {
      Alert.alert('Info', 'Tidak ada tamu baru untuk disimpan.');
      return;
    }

    try {
      setIsSaving(true);
      
      const payload = {
        names: unsavedGuests.join(','),
      };

      await httpRequest(`/api/mobile/access/invitations/${invitationId}/guests`, {
        method: 'POST',
        authMode: 'required',
        body: payload,
      });

      // Mark all as saved
      setGuests(guests.map((g) => ({ ...g, isSaved: true })));
      Alert.alert('Berhasil', 'Tamu berhasil disimpan ke daftar tamu.');
    } catch (error) {
      Alert.alert('Gagal', 'Gagal menyimpan tamu.');
    } finally {
      setIsSaving(false);
    }
  };

  const generateLink = (name: string) => {
    return `${invitationUrl}?kpd=${encodeURIComponent(name)}`;
  };

  const getMessageContent = (name: string) => {
    const link = generateLink(name);
    return selectedTemplate.content
      .replace(/\{nama\}/g, name)
      .replace(/\{link\}/g, link);
  };

  const handleSendWA = (name: string) => {
    const msg = encodeURIComponent(getMessageContent(name));
    Linking.openURL(`whatsapp://send?text=${msg}`).catch(() => {
      Alert.alert('Error', 'WhatsApp tidak ditemukan di perangkat ini.');
    });
  };

  const handleShare = async (name: string) => {
    try {
      await Share.share({
        message: getMessageContent(name),
      });
    } catch (error) {
      // Ignored
    }
  };

  return (
    <SafeAreaView style={[s.container, { paddingBottom: insets.bottom }]} edges={['top']}>
      {/* Header */}
      <View style={s.header}>
        <Pressable
          onPress={() => navigation.goBack()}
          style={({ pressed }) => [s.backBtn, pressed && s.pressed]}
        >
          <Ionicons name="arrow-back" size={24} color={theme.onSurface} />
        </Pressable>
        <View style={s.headerTextWrap}>
          <Text style={s.headerTitle}>Sebar Undangan</Text>
          <Text style={s.headerSubtitle} numberOfLines={1}>
            {invitationTitle}
          </Text>
        </View>
      </View>

      <ScrollView contentContainerStyle={s.content} showsVerticalScrollIndicator={false}>
        {/* Step 1: Add Guests */}
        <View style={s.card}>
          <View style={s.cardHeader}>
            <View style={[s.stepBadge, { backgroundColor: theme.primaryContainer }]}>
              <Text style={[s.stepNumber, { color: theme.primary }]}>1</Text>
            </View>
            <View>
              <Text style={s.cardTitle}>Tambah Penerima</Text>
              <Text style={s.cardSubtitle}>Masukkan nama tamu undangan</Text>
            </View>
          </View>

          <View style={s.inputRow}>
            <TextInput
              style={s.textInput}
              placeholder="Contoh: Budi Santoso"
              placeholderTextColor={theme.onSurfaceVariant}
              value={newGuestName}
              onChangeText={setNewGuestName}
              onSubmitEditing={handleAddGuest}
              returnKeyType="done"
            />
            <Pressable
              style={({ pressed }) => [s.addBtn, pressed && s.pressed]}
              onPress={handleAddGuest}
            >
              <Ionicons name="add" size={20} color="#fff" />
              <Text style={s.addBtnText}>Tambah</Text>
            </Pressable>
          </View>

          <Pressable
            style={({ pressed }) => [s.loadBtn, pressed && s.pressed]}
            onPress={handleLoadExistingGuests}
            disabled={isLoadingGuests}
          >
            {isLoadingGuests ? (
              <ActivityIndicator size="small" color={theme.onSurfaceVariant} />
            ) : (
              <Ionicons name="people-outline" size={18} color={theme.onSurfaceVariant} />
            )}
            <Text style={s.loadBtnText}>Muat Daftar Tamu Tersimpan</Text>
          </Pressable>

          {/* Guest chips */}
          {guests.length > 0 && (
            <View style={s.chipsWrap}>
              {guests.map((g, index) => (
                <View key={index} style={s.chip}>
                  <Text style={s.chipText}>{g.name}</Text>
                  {g.isSaved && (
                    <Ionicons name="checkmark-circle" size={14} color="#10B981" style={{ marginLeft: 2 }} />
                  )}
                  <Pressable
                    onPress={() => handleRemoveGuest(index)}
                    style={s.chipRemoveBtn}
                    hitSlop={8}
                  >
                    <Ionicons name="close" size={14} color={theme.onSurface} />
                  </Pressable>
                </View>
              ))}
            </View>
          )}
        </View>

        {/* Step 2: Template Selection */}
        <View style={s.card}>
          <View style={s.cardHeader}>
            <View style={[s.stepBadge, { backgroundColor: '#FEF3C7' }]}>
              <Text style={[s.stepNumber, { color: '#D97706' }]}>2</Text>
            </View>
            <View>
              <Text style={s.cardTitle}>Pilih Template Pesan</Text>
              <Text style={s.cardSubtitle}>Format pesan untuk disebar</Text>
            </View>
          </View>

          <View style={s.templatesRow}>
            {TEMPLATES.map((t) => {
              const isSelected = selectedTemplateId === t.id;
              return (
                <Pressable
                  key={t.id}
                  style={[s.templateItem, isSelected && { borderColor: theme.primary, backgroundColor: theme.primaryContainer }]}
                  onPress={() => setSelectedTemplateId(t.id as 'formal' | 'casual')}
                >
                  <Text style={s.templateEmoji}>{t.icon}</Text>
                  <Text style={[s.templateName, isSelected && { color: theme.primary }]}>{t.name}</Text>
                </Pressable>
              );
            })}
          </View>

          <View style={s.previewBox}>
            <Text style={s.previewLabel}>Preview Pesan:</Text>
            <Text style={s.previewContent}>{getMessageContent('Nama Tamu')}</Text>
          </View>
        </View>

        {/* Step 3: Action */}
        <View style={s.card}>
          <View style={s.cardHeader}>
            <View style={[s.stepBadge, { backgroundColor: '#D1FAE5' }]}>
              <Text style={[s.stepNumber, { color: '#059669' }]}>3</Text>
            </View>
            <View>
              <Text style={s.cardTitle}>Generate & Bagikan</Text>
              <Text style={s.cardSubtitle}>Kirim pesan personal ke masing-masing tamu</Text>
            </View>
          </View>

          {guests.length === 0 ? (
            <Text style={s.emptyMsg}>Tambahkan penerima di langkah 1 terlebih dahulu.</Text>
          ) : (
            <>
              {guests.some((g) => !g.isSaved) && (
                <Pressable
                  style={({ pressed }) => [s.saveListBtn, pressed && s.pressed]}
                  onPress={handleSaveToGuestList}
                  disabled={isSaving}
                >
                  <Ionicons name="save-outline" size={18} color={theme.onSurface} />
                  <Text style={s.saveListBtnText}>
                    {isSaving ? 'Menyimpan...' : 'Simpan Tamu Baru ke Database'}
                  </Text>
                </Pressable>
              )}

              <View style={s.guestListWrap}>
                {guests.map((g, index) => (
                  <View key={index} style={s.guestActionItem}>
                    <View style={s.guestActionInfo}>
                      <Text style={s.guestActionName}>{g.name}</Text>
                      <Text style={s.guestActionLink} numberOfLines={1}>{generateLink(g.name)}</Text>
                    </View>
                    <View style={s.guestActions}>
                      <Pressable
                        style={({ pressed }) => [s.actionCirleBtn, { backgroundColor: '#10B981' }, pressed && s.pressed]}
                        onPress={() => handleSendWA(g.name)}
                      >
                        <Ionicons name="logo-whatsapp" size={16} color="#fff" />
                      </Pressable>
                      <Pressable
                        style={({ pressed }) => [s.actionCirleBtn, { backgroundColor: '#3B82F6' }, pressed && s.pressed]}
                        onPress={() => void handleShare(g.name)}
                      >
                        <Ionicons name="share-social" size={16} color="#fff" />
                      </Pressable>
                    </View>
                  </View>
                ))}
              </View>
            </>
          )}
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme']) {
  return StyleSheet.create({
    container: {
      flex: 1,
      backgroundColor: t.background,
    },
    header: {
      flexDirection: 'row',
      alignItems: 'center',
      paddingHorizontal: 16,
      paddingVertical: 12,
      borderBottomWidth: 1,
      borderBottomColor: t.outlineVariant,
      backgroundColor: t.surfaceContainerLow,
    },
    backBtn: {
      padding: 8,
      marginRight: 8,
      marginLeft: -8,
    },
    headerTextWrap: {
      flex: 1,
    },
    headerTitle: {
      fontFamily: F.heading,
      fontSize: 18,
      color: t.onSurface,
    },
    headerSubtitle: {
      fontFamily: F.body,
      fontSize: 13,
      color: t.onSurfaceVariant,
    },
    content: {
      padding: 16,
      gap: 16,
    },
    card: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: 16,
      padding: 16,
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },
    cardHeader: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 12,
      marginBottom: 16,
    },
    stepBadge: {
      width: 36,
      height: 36,
      borderRadius: 18,
      alignItems: 'center',
      justifyContent: 'center',
    },
    stepNumber: {
      fontFamily: F.display,
      fontSize: 18,
    },
    cardTitle: {
      fontFamily: F.heading,
      fontSize: 16,
      color: t.onSurface,
      marginBottom: 2,
    },
    cardSubtitle: {
      fontFamily: F.body,
      fontSize: 12,
      color: t.onSurfaceVariant,
    },
    inputRow: {
      flexDirection: 'row',
      gap: 8,
      marginBottom: 12,
    },
    textInput: {
      flex: 1,
      height: 48,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      borderRadius: 12,
      paddingHorizontal: 14,
      fontFamily: F.body,
      fontSize: 14,
      color: t.onSurface,
      backgroundColor: t.surface,
    },
    addBtn: {
      height: 48,
      paddingHorizontal: 16,
      backgroundColor: t.primary,
      borderRadius: 12,
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 4,
    },
    addBtnText: {
      fontFamily: F.labelBold,
      color: '#fff',
      fontSize: 14,
    },
    loadBtn: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 6,
      paddingVertical: 10,
      backgroundColor: t.surfaceContainerHighest,
      borderRadius: 10,
      marginBottom: 16,
    },
    loadBtnText: {
      fontFamily: F.labelBold,
      color: t.onSurfaceVariant,
      fontSize: 13,
    },
    chipsWrap: {
      flexDirection: 'row',
      flexWrap: 'wrap',
      gap: 8,
      marginTop: 4,
    },
    chip: {
      flexDirection: 'row',
      alignItems: 'center',
      backgroundColor: t.surface,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      paddingVertical: 6,
      paddingHorizontal: 10,
      borderRadius: 20,
      gap: 4,
    },
    chipText: {
      fontFamily: F.label,
      fontSize: 13,
      color: t.onSurface,
    },
    chipRemoveBtn: {
      marginLeft: 4,
      backgroundColor: t.surfaceContainerHighest,
      borderRadius: 10,
      padding: 2,
    },
    templatesRow: {
      flexDirection: 'row',
      gap: 12,
      marginBottom: 16,
    },
    templateItem: {
      flex: 1,
      alignItems: 'center',
      padding: 12,
      borderWidth: 2,
      borderColor: t.outlineVariant,
      borderRadius: 12,
      backgroundColor: t.surface,
    },
    templateEmoji: {
      fontSize: 24,
      marginBottom: 4,
    },
    templateName: {
      fontFamily: F.labelBold,
      fontSize: 13,
      color: t.onSurfaceVariant,
    },
    previewBox: {
      backgroundColor: t.surfaceContainerHighest,
      padding: 12,
      borderRadius: 12,
    },
    previewLabel: {
      fontFamily: F.labelBold,
      fontSize: 11,
      color: t.onSurfaceVariant,
      textTransform: 'uppercase',
      marginBottom: 6,
    },
    previewContent: {
      fontFamily: F.body,
      fontSize: 13,
      color: t.onSurface,
      lineHeight: 20,
    },
    emptyMsg: {
      fontFamily: F.body,
      fontSize: 13,
      color: t.onSurfaceVariant,
      textAlign: 'center',
      paddingVertical: 20,
    },
    saveListBtn: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 6,
      paddingVertical: 12,
      backgroundColor: t.surfaceContainerHighest,
      borderRadius: 12,
      marginBottom: 16,
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },
    saveListBtnText: {
      fontFamily: F.labelBold,
      color: t.onSurface,
      fontSize: 13,
    },
    guestListWrap: {
      gap: 12,
    },
    guestActionItem: {
      flexDirection: 'row',
      alignItems: 'center',
      backgroundColor: t.surface,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      padding: 12,
      borderRadius: 12,
    },
    guestActionInfo: {
      flex: 1,
      marginRight: 10,
    },
    guestActionName: {
      fontFamily: F.labelBold,
      fontSize: 14,
      color: t.onSurface,
      marginBottom: 2,
    },
    guestActionLink: {
      fontFamily: F.body,
      fontSize: 11,
      color: t.primary,
    },
    guestActions: {
      flexDirection: 'row',
      gap: 8,
    },
    actionCirleBtn: {
      width: 36,
      height: 36,
      borderRadius: 18,
      alignItems: 'center',
      justifyContent: 'center',
    },
    pressed: {
      opacity: 0.8,
      transform: [{ scale: 0.98 }],
    },
  });
}
