import { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TextInput,
  Pressable,
  ActivityIndicator,
  Alert,
  ScrollView,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import { useAuth } from '../features/auth/AuthContext';
import { httpRequest } from '../services/httpClient';
import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import type { RootStackParamList } from '../navigation/types';

type NavProp = NativeStackNavigationProp<RootStackParamList, 'EditProfile'>;

export function EditProfileScreen() {
  const navigation = useNavigation<NavProp>();
  const { session } = useAuth();
  const { theme } = useAppTheme();
  const s = makeStyles(theme);

  const [name, setName] = useState(session?.ownerName ?? '');
  const [isSaving, setIsSaving] = useState(false);

  const handleSave = async () => {
    const trimmedName = name.trim();
    if (!trimmedName) {
      Alert.alert('Validasi', 'Nama tidak boleh kosong.');
      return;
    }

    setIsSaving(true);
    try {
      await httpRequest('/api/mobile/access/profile', {
        method: 'PATCH',
        authMode: 'required',
        body: { name: trimmedName },
        timeoutMs: 10000,
      });
      Alert.alert('Berhasil', 'Profil Anda telah diperbarui.', [
        { text: 'OK', onPress: () => navigation.goBack() },
      ]);
    } catch {
      Alert.alert('Gagal', 'Tidak dapat menyimpan perubahan. Coba lagi nanti.');
    } finally {
      setIsSaving(false);
    }
  };

  const initials = name.trim()
    ? name.trim().substring(0, 2).toUpperCase()
    : session?.ownerName?.substring(0, 2).toUpperCase() ?? 'ME';

  return (
    <SafeAreaView style={s.safeArea} edges={['top', 'bottom']}>
      <KeyboardAvoidingView
        style={{ flex: 1 }}
        behavior={Platform.OS === 'ios' ? 'padding' : undefined}
      >
        {/* Header */}
        <View style={s.header}>
          <Pressable
            onPress={() => navigation.goBack()}
            style={({ pressed }) => [s.iconBtn, pressed && s.pressed]}
          >
            <Ionicons name="arrow-back" size={20} color={theme.onSurface} />
          </Pressable>
          <View style={s.headerText}>
            <Text style={s.headerEyebrow}>Edit Akun</Text>
            <Text style={s.headerTitle}>Edit Profil</Text>
          </View>
          <Pressable
            onPress={() => void handleSave()}
            disabled={isSaving}
            style={({ pressed }) => [s.saveBtn, pressed && s.pressed, isSaving && s.saveBtnDisabled]}
          >
            {isSaving ? (
              <ActivityIndicator size="small" color="#FFFFFF" />
            ) : (
              <Text style={s.saveBtnText}>Simpan</Text>
            )}
          </Pressable>
        </View>

        <ScrollView contentContainerStyle={s.scrollContent} keyboardShouldPersistTaps="handled">
          {/* Avatar */}
          <View style={s.avatarSection}>
            <View style={s.avatar}>
              <Text style={s.avatarText}>{initials}</Text>
            </View>
            <Text style={s.avatarHint}>Avatar diambil dari inisial nama Anda</Text>
          </View>

          {/* Form */}
          <View style={s.formCard}>
            <Text style={s.formLabel}>INFORMASI AKUN</Text>

            <View style={s.fieldWrap}>
              <View style={s.fieldIconWrap}>
                <MaterialCommunityIcons name="account-outline" size={18} color={theme.primary} />
              </View>
              <View style={s.fieldBody}>
                <Text style={s.fieldLabel}>Nama Lengkap</Text>
                <TextInput
                  style={s.fieldInput}
                  value={name}
                  onChangeText={setName}
                  placeholder="Masukkan nama lengkap..."
                  placeholderTextColor={theme.outline}
                  autoCorrect={false}
                  autoCapitalize="words"
                  returnKeyType="done"
                  onSubmitEditing={() => void handleSave()}
                />
              </View>
            </View>

            <View style={s.divider} />

            <View style={s.fieldWrap}>
              <View style={s.fieldIconWrap}>
                <MaterialCommunityIcons name="shield-key-outline" size={18} color={theme.primary} />
              </View>
              <View style={s.fieldBody}>
                <Text style={s.fieldLabel}>Sesi Perangkat</Text>
                <Text style={s.fieldStaticValue}>{session?.workspaceLabel ?? '-'}</Text>
                <Text style={s.fieldHint}>Gunakan kode akses untuk mengubah sesi.</Text>
              </View>
            </View>
          </View>

          {/* Info note */}
          <View style={s.noteCard}>
            <MaterialCommunityIcons name="information-outline" size={16} color={theme.primary} />
            <Text style={s.noteText}>
              Perubahan nama akan langsung diterapkan pada sesi perangkat Anda saat ini.
            </Text>
          </View>
        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme']) {
  return StyleSheet.create({
    safeArea: { flex: 1, backgroundColor: t.background },

    header: {
      flexDirection: 'row',
      alignItems: 'center',
      paddingHorizontal: 16,
      paddingTop: 8,
      paddingBottom: 10,
      gap: 12,
    },
    iconBtn: {
      width: 38,
      height: 38,
      borderRadius: 12,
      backgroundColor: t.surfaceContainerLow,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      alignItems: 'center',
      justifyContent: 'center',
    },
    headerText: { flex: 1, gap: 1 },
    headerEyebrow: {
      fontFamily: F.label,
      fontSize: 10,
      letterSpacing: 1,
      textTransform: 'uppercase',
      color: t.primary,
    },
    headerTitle: {
      fontFamily: F.heading,
      fontSize: 18,
      color: t.onSurface,
    },
    saveBtn: {
      minWidth: 84,
      height: 38,
      borderRadius: 999,
      backgroundColor: t.primary,
      alignItems: 'center',
      justifyContent: 'center',
      paddingHorizontal: 16,
    },
    saveBtnDisabled: { opacity: 0.55 },
    saveBtnText: {
      fontFamily: F.labelBold,
      fontSize: 13,
      color: '#FFFFFF',
    },

    scrollContent: {
      paddingHorizontal: 16,
      paddingBottom: 40,
      gap: 16,
    },

    avatarSection: {
      alignItems: 'center',
      paddingVertical: 20,
      gap: 8,
    },
    avatar: {
      width: 90,
      height: 90,
      borderRadius: 45,
      backgroundColor: t.surfaceContainerHighest,
      alignItems: 'center',
      justifyContent: 'center',
      borderWidth: 2,
      borderColor: t.primary,
    },
    avatarText: {
      fontFamily: F.display,
      fontSize: 28,
      color: t.primary,
      letterSpacing: 1,
    },
    avatarHint: {
      fontFamily: F.body,
      fontSize: 12,
      color: t.onSurfaceVariant,
    },

    formCard: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: 20,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      padding: 16,
      gap: 12,
    },
    formLabel: {
      fontFamily: F.label,
      fontSize: 10,
      letterSpacing: 1,
      textTransform: 'uppercase',
      color: t.onSurfaceVariant,
      marginLeft: 2,
    },
    fieldWrap: {
      flexDirection: 'row',
      alignItems: 'flex-start',
      gap: 12,
    },
    fieldIconWrap: {
      width: 36,
      height: 36,
      borderRadius: 10,
      backgroundColor: t.surfaceContainerHighest,
      alignItems: 'center',
      justifyContent: 'center',
      marginTop: 2,
    },
    fieldBody: { flex: 1, gap: 4 },
    fieldLabel: {
      fontFamily: F.label,
      fontSize: 11,
      letterSpacing: 0.5,
      textTransform: 'uppercase',
      color: t.onSurfaceVariant,
    },
    fieldInput: {
      fontFamily: F.body,
      fontSize: 15,
      color: t.onSurface,
      borderBottomWidth: 1,
      borderBottomColor: t.outlineVariant,
      paddingVertical: 6,
    },
    fieldStaticValue: {
      fontFamily: F.body,
      fontSize: 14,
      color: t.onSurface,
    },
    fieldHint: {
      fontFamily: F.body,
      fontSize: 11,
      color: t.onSurfaceVariant,
    },
    divider: {
      height: 1,
      backgroundColor: t.outlineVariant,
      marginVertical: 4,
    },

    noteCard: {
      flexDirection: 'row',
      alignItems: 'flex-start',
      gap: 10,
      padding: 14,
      borderRadius: 14,
      backgroundColor: t.surfaceContainerLow,
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },
    noteText: {
      flex: 1,
      fontFamily: F.body,
      fontSize: 12,
      color: t.onSurfaceVariant,
      lineHeight: 18,
    },

    pressed: { opacity: 0.84, transform: [{ scale: 0.97 }] },
  });
}
