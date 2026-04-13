import { useCallback, useEffect, useMemo, useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  Pressable,
  StyleSheet,
  Text,
  View,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { Ionicons } from '@expo/vector-icons';
import { useNavigation, useRoute } from '@react-navigation/native';
import type { RouteProp } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import InvitationRichEditorDom from '../shared/components/invitation-rich-editor-dom';
import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import type { RootStackParamList } from '../navigation/types';

type RichEditorPayload = {
  html: string;
  text: string;
  wordCount: number;
};

type DraftRecord = RichEditorPayload & {
  updatedAt: string;
};

type NavigationProp = NativeStackNavigationProp<RootStackParamList, 'InvitationContentEditor'>;
type RoutePropType = RouteProp<RootStackParamList, 'InvitationContentEditor'>;

function stripHtml(html: string): string {
  return html
    .replace(/<style[\s\S]*?<\/style>/gi, ' ')
    .replace(/<[^>]+>/g, ' ')
    .replace(/&nbsp;/g, ' ')
    .replace(/\s+/g, ' ')
    .trim();
}

function toPayloadFromHtml(html: string): RichEditorPayload {
  const text = stripHtml(html);

  return {
    html,
    text,
    wordCount: text ? text.split(/\s+/).length : 0,
  };
}

function escapeHtml(value: string): string {
  return value
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

function makeTemplate(title: string): string {
  const safeTitle = escapeHtml(title.trim() || 'Undangan Kami');

  return [
    `<h2>${safeTitle}</h2>`,
    '<p>Assalamu\'alaikum Wr. Wb.</p>',
    '<p>Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk hadir pada acara kami.</p>',
    '<p><strong>Mempelai:</strong> Putra &amp; Putri</p>',
    '<ul><li>Akad Nikah: Sabtu, 09.00 WIB</li><li>Resepsi: Sabtu, 11.00 WIB</li></ul>',
    '<p>Merupakan suatu kehormatan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir.</p>',
    '<p>Wassalamu\'alaikum Wr. Wb.</p>',
  ].join('');
}

function formatSavedTime(iso: string | null): string {
  if (!iso) {
    return '-';
  }

  const date = new Date(iso);

  if (Number.isNaN(date.getTime())) {
    return '-';
  }

  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit',
  }).format(date);
}

export function InvitationContentEditorScreen() {
  const navigation = useNavigation<NavigationProp>();
  const route = useRoute<RoutePropType>();
  const { theme } = useAppTheme();
  const s = makeStyles(theme);

  const invitationTitle = route.params?.invitationTitle?.trim() || 'Undangan Saya';
  const invitationId = route.params?.invitationId;

  const fallbackTemplate = useMemo(() => {
    if (route.params?.initialHtml && route.params.initialHtml.trim()) {
      return route.params.initialHtml;
    }

    return makeTemplate(invitationTitle);
  }, [invitationTitle, route.params?.initialHtml]);

  const draftStorageKey = useMemo(() => {
    const suffix = invitationId && invitationId.trim() ? invitationId.trim() : 'draft';
    return `INVITATION_CONTENT_DRAFT:${suffix}`;
  }, [invitationId]);

  const [editorKey, setEditorKey] = useState(0);
  const [isBootstrapping, setIsBootstrapping] = useState(true);
  const [seedHtml, setSeedHtml] = useState(fallbackTemplate);
  const [latestPayload, setLatestPayload] = useState<RichEditorPayload>(() =>
    toPayloadFromHtml(fallbackTemplate)
  );
  const [statusMessage, setStatusMessage] = useState<string | null>(null);
  const [lastSavedAt, setLastSavedAt] = useState<string | null>(null);
  const [hasUnsavedChanges, setHasUnsavedChanges] = useState(false);

  useEffect(() => {
    let isMounted = true;

    const hydrateDraft = async () => {
      try {
        setIsBootstrapping(true);

        const raw = await AsyncStorage.getItem(draftStorageKey);

        if (!raw) {
          if (isMounted) {
            setSeedHtml(fallbackTemplate);
            setLatestPayload(toPayloadFromHtml(fallbackTemplate));
            setStatusMessage('Template awal siap digunakan.');
          }

          return;
        }

        const parsed = JSON.parse(raw) as Partial<DraftRecord>;

        if (!isMounted) {
          return;
        }

        if (typeof parsed.html === 'string' && parsed.html.trim()) {
          setSeedHtml(parsed.html);
          setLatestPayload(toPayloadFromHtml(parsed.html));
          setStatusMessage('Draft lokal berhasil dimuat.');
        } else {
          setSeedHtml(fallbackTemplate);
          setLatestPayload(toPayloadFromHtml(fallbackTemplate));
        }

        if (typeof parsed.updatedAt === 'string') {
          setLastSavedAt(parsed.updatedAt);
        }
      } catch {
        if (isMounted) {
          setSeedHtml(fallbackTemplate);
          setLatestPayload(toPayloadFromHtml(fallbackTemplate));
          setStatusMessage('Draft gagal dimuat, menggunakan template default.');
        }
      } finally {
        if (isMounted) {
          setIsBootstrapping(false);
        }
      }
    };

    void hydrateDraft();

    return () => {
      isMounted = false;
    };
  }, [draftStorageKey, fallbackTemplate]);

  const onEditorChange = useCallback(async (payload: RichEditorPayload) => {
    setLatestPayload(payload);
    setHasUnsavedChanges(true);
    setStatusMessage(null);
  }, []);

  const saveDraft = useCallback(async (payload?: RichEditorPayload) => {
    const nextPayload = payload ?? latestPayload;
    const nextRecord: DraftRecord = {
      ...nextPayload,
      updatedAt: new Date().toISOString(),
    };

    try {
      await AsyncStorage.setItem(draftStorageKey, JSON.stringify(nextRecord));
      setSeedHtml(nextPayload.html);
      setLastSavedAt(nextRecord.updatedAt);
      setHasUnsavedChanges(false);
      setStatusMessage('Draft tersimpan di perangkat ini.');

      return {
        success: true,
        message: 'Draft tersimpan di perangkat ini.',
      };
    } catch (error) {
      const message = error instanceof Error ? error.message : 'Gagal menyimpan draft konten.';
      setStatusMessage(message);
      throw new Error(message);
    }
  }, [draftStorageKey, latestPayload]);

  const handleResetTemplate = useCallback(() => {
    Alert.alert('Reset Konten', 'Kembalikan konten ke template awal?', [
      {
        text: 'Batal',
        style: 'cancel',
      },
      {
        text: 'Reset',
        style: 'destructive',
        onPress: () => {
          const fallbackPayload = toPayloadFromHtml(fallbackTemplate);
          setSeedHtml(fallbackTemplate);
          setLatestPayload(fallbackPayload);
          setHasUnsavedChanges(true);
          setStatusMessage('Template awal diterapkan kembali.');
          setEditorKey((value) => value + 1);
        },
      },
    ]);
  }, [fallbackTemplate]);

  const handleSaveFromHeader = useCallback(() => {
    void saveDraft();
  }, [saveDraft]);

  return (
    <SafeAreaView style={s.safeArea} edges={['top', 'bottom']}>
      <View style={s.header}>
        <Pressable
          onPress={() => navigation.goBack()}
          style={({ pressed }) => [s.iconButton, pressed && s.pressed]}
        >
          <Ionicons name="arrow-back" size={20} color={theme.onSurface} />
        </Pressable>

        <View style={s.headerTextWrap}>
          <Text style={s.headerEyebrow}>Editor Konten</Text>
          <Text style={s.headerTitle} numberOfLines={1}>{invitationTitle}</Text>
        </View>

        <Pressable
          onPress={handleSaveFromHeader}
          style={({ pressed }) => [s.saveHeaderButton, pressed && s.pressed]}
        >
          <Text style={s.saveHeaderText}>Simpan</Text>
        </Pressable>
      </View>

      <View style={s.metaRow}>
        <Text style={s.metaText}>{latestPayload.wordCount} kata</Text>
        <Text style={s.metaDot}>•</Text>
        <Text style={s.metaText}>{hasUnsavedChanges ? 'Perubahan belum disimpan' : 'Draft sinkron'}</Text>
      </View>

      <View style={s.metaRow}>
        <Text style={s.metaText}>Simpan terakhir: {formatSavedTime(lastSavedAt)}</Text>
      </View>

      <View style={s.editorFrame}>
        {isBootstrapping ? (
          <View style={s.loadingWrap}>
            <ActivityIndicator size="large" color={theme.primary} />
            <Text style={s.loadingText}>Menyiapkan editor...</Text>
          </View>
        ) : (
          <InvitationRichEditorDom
            key={editorKey}
            title={invitationTitle}
            initialHtml={seedHtml}
            placeholder="Tulis ucapan pembuka, detail acara, dan catatan tambahan di sini..."
            onChangeHtml={onEditorChange}
            onRequestSave={saveDraft}
            dom={{
              scrollEnabled: false,
              contentInsetAdjustmentBehavior: 'never',
              style: { flex: 1 },
            }}
          />
        )}
      </View>

      <View style={s.footerRow}>
        <Pressable
          onPress={handleResetTemplate}
          style={({ pressed }) => [s.resetButton, pressed && s.pressed]}
        >
          <Ionicons name="refresh-outline" size={16} color={theme.onSurfaceVariant} />
          <Text style={s.resetButtonText}>Reset Template</Text>
        </Pressable>
      </View>

      {statusMessage ? (
        <View style={s.noticeWrap}>
          <Text style={s.noticeText}>{statusMessage}</Text>
        </View>
      ) : null}
    </SafeAreaView>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme']) {
  return StyleSheet.create({
    safeArea: {
      flex: 1,
      backgroundColor: t.background,
      paddingHorizontal: 14,
      paddingTop: 8,
      paddingBottom: 10,
      gap: 8,
    },
    header: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 10,
    },
    iconButton: {
      width: 38,
      height: 38,
      borderRadius: 12,
      alignItems: 'center',
      justifyContent: 'center',
      backgroundColor: t.surfaceContainerLow,
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },
    headerTextWrap: {
      flex: 1,
      gap: 1,
    },
    headerEyebrow: {
      fontFamily: F.label,
      fontSize: 10,
      letterSpacing: 0.9,
      textTransform: 'uppercase',
      color: t.onSurfaceVariant,
    },
    headerTitle: {
      fontFamily: F.heading,
      fontSize: 18,
      color: t.onSurface,
    },
    saveHeaderButton: {
      minWidth: 86,
      height: 38,
      borderRadius: 999,
      alignItems: 'center',
      justifyContent: 'center',
      backgroundColor: t.primary,
      paddingHorizontal: 14,
    },
    saveHeaderText: {
      fontFamily: F.labelBold,
      fontSize: 13,
      color: '#FFFFFF',
    },
    metaRow: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 8,
      paddingHorizontal: 4,
    },
    metaDot: {
      fontFamily: F.body,
      color: t.outline,
      fontSize: 14,
    },
    metaText: {
      fontFamily: F.body,
      color: t.onSurfaceVariant,
      fontSize: 12,
    },
    editorFrame: {
      flex: 1,
      borderRadius: 18,
      overflow: 'hidden',
      borderWidth: 1,
      borderColor: t.outlineVariant,
      backgroundColor: t.surface,
    },
    loadingWrap: {
      flex: 1,
      alignItems: 'center',
      justifyContent: 'center',
      gap: 10,
      backgroundColor: t.surfaceContainerLow,
    },
    loadingText: {
      fontFamily: F.body,
      fontSize: 13,
      color: t.onSurfaceVariant,
    },
    footerRow: {
      flexDirection: 'row',
      justifyContent: 'flex-end',
      paddingTop: 2,
    },
    resetButton: {
      height: 38,
      borderRadius: 999,
      paddingHorizontal: 14,
      alignItems: 'center',
      justifyContent: 'center',
      flexDirection: 'row',
      gap: 7,
      backgroundColor: t.surfaceContainerLow,
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },
    resetButtonText: {
      fontFamily: F.label,
      color: t.onSurfaceVariant,
      fontSize: 12,
    },
    noticeWrap: {
      borderRadius: 12,
      paddingHorizontal: 12,
      paddingVertical: 10,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      backgroundColor: t.surfaceContainerLow,
    },
    noticeText: {
      fontFamily: F.body,
      fontSize: 12,
      color: t.onSurface,
    },
    pressed: {
      opacity: 0.86,
      transform: [{ scale: 0.98 }],
    },
  });
}