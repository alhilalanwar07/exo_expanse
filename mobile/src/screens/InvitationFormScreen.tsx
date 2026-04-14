import { useState, useEffect, useCallback, useRef } from 'react';
import type { ReactNode } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  TextInput,
  Pressable,
  Switch,
  ActivityIndicator,
  Alert,
  KeyboardAvoidingView,
  Platform,
  Image,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { useNavigation, useRoute } from '@react-navigation/native';
import type { RouteProp } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import * as ImagePicker from 'expo-image-picker';

import { httpRequest, HttpClientError } from '../services/httpClient';
import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import type { RootStackParamList } from '../navigation/types';

type NavProp   = NativeStackNavigationProp<RootStackParamList, 'InvitationForm'>;
type RoutePropT = RouteProp<RootStackParamList, 'InvitationForm'>;

// ── Types ─────────────────────────────────────────────────────────────────────

type InvitationFormData = {
  title: string; slug: string; cover_title: string; cover_subtitle: string;
  type: 'wedding' | 'birthday' | 'other';
  name_order: 'groom_first' | 'bride_first';
  groom_name: string; groom_nickname: string; groom_father: string;
  groom_mother: string; groom_instagram: string;
  bride_name: string; bride_nickname: string; bride_father: string;
  bride_mother: string; bride_instagram: string;
  event_type: 'both' | 'akad_only' | 'resepsi_only';
  akad_date: string; akad_time: string; akad_venue: string;
  akad_address: string; akad_maps_link: string;
  resepsi_date: string; resepsi_time: string; resepsi_venue: string;
  resepsi_address: string; resepsi_maps_link: string;
  welcome_message: string; quran_verse: string;
  countdown_enabled: boolean; rsvp_enabled: boolean;
  wishes_enabled: boolean; gallery_enabled: boolean; music_enabled: boolean;
};

type LoveStory = { date: string; title: string; description: string };
type GalleryPhoto = { id: number; url: string };
type Guest = { id: number; name: string; phone: string; status: string };
type Photos = { cover: string | null; groom: string | null; bride: string | null };

const INITIAL_FORM: InvitationFormData = {
  title: '', slug: '', cover_title: '', cover_subtitle: 'The Wedding of',
  type: 'wedding', name_order: 'groom_first',
  groom_name: '', groom_nickname: '', groom_father: '', groom_mother: '', groom_instagram: '',
  bride_name: '', bride_nickname: '', bride_father: '', bride_mother: '', bride_instagram: '',
  event_type: 'both',
  akad_date: '', akad_time: '08:00', akad_venue: '', akad_address: '', akad_maps_link: '',
  resepsi_date: '', resepsi_time: '11:00', resepsi_venue: '', resepsi_address: '', resepsi_maps_link: '',
  welcome_message: 'Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami.',
  quran_verse: 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu isteri-isteri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya. (QS. Ar-Rum: 21)',
  countdown_enabled: true, rsvp_enabled: true, wishes_enabled: true, gallery_enabled: true, music_enabled: false,
};

const TABS = [
  { key: 'cover',    label: 'Cover',    icon: 'image-outline' as const },
  { key: 'mempelai', label: 'Mempelai', icon: 'heart-outline' as const },
  { key: 'acara',    label: 'Acara',    icon: 'calendar-outline' as const },
  { key: 'foto',     label: 'Foto',     icon: 'camera-outline' as const },
  { key: 'tamu',     label: 'Tamu',     icon: 'people-outline' as const },
  { key: 'fitur',    label: 'Fitur',    icon: 'settings-outline' as const },
] as const;
type TabKey = typeof TABS[number]['key'];

// ── Helpers ───────────────────────────────────────────────────────────────────

function slugify(t: string) {
  return t.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '').replace(/-+/g, '-').replace(/^-|-$/g, '');
}

async function pickImage() {
  const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();
  if (status !== 'granted') {
    Alert.alert('Izin Diperlukan', 'Izinkan akses galeri untuk upload foto.');
    return null;
  }
  const result = await ImagePicker.launchImageLibraryAsync({
    mediaTypes: ['images'],
    allowsEditing: true,
    quality: 0.8,
  });
  return result.canceled ? null : result.assets[0];
}

async function buildPhotoFormDataAsync(uri: string, fieldName = 'photo'): Promise<FormData> {
  const fd = new FormData();
  if (Platform.OS === 'web') {
    // On web, fetch the blob from the URI (data URL or blob URL)
    const response = await fetch(uri);
    const blob = await response.blob();
    const ext = blob.type.includes('png') ? 'png' : 'jpg';
    fd.append(fieldName, blob, `photo.${ext}`);
  } else {
    const ext = uri.split('.').pop()?.toLowerCase() ?? 'jpg';
    const mime = ext === 'png' ? 'image/png' : 'image/jpeg';
    // React Native FormData accepts the {uri,type,name} shape
    fd.append(fieldName, { uri, type: mime, name: `photo.${ext}` } as unknown as Blob);
  }
  return fd;
}

// ── Reusable UI components ────────────────────────────────────────────────────

type ThemeRef = ReturnType<typeof useAppTheme>['theme'];

function SectionTitle({ children, theme, first }: { children: ReactNode; theme: ThemeRef; first?: boolean }) {
  return (
    <Text style={{ fontFamily: F.subheading, fontSize: 11, color: theme.primary, letterSpacing: 0.8, textTransform: 'uppercase', marginBottom: 8, marginTop: first ? 4 : 22 }}>
      {children}
    </Text>
  );
}

function Field({
  label, value, onChangeText, placeholder, multiline, keyboardType = 'default',
  autoCapitalize = 'sentences', hint, error, theme,
}: {
  label: string; value: string; onChangeText: (v: string) => void;
  placeholder?: string; multiline?: boolean;
  keyboardType?: 'default' | 'url' | 'email-address';
  autoCapitalize?: 'none' | 'sentences' | 'words';
  hint?: string; error?: string; theme: ThemeRef;
}) {
  return (
    <View style={{ marginBottom: 14 }}>
      <Text style={{ fontFamily: F.label, fontSize: 13, color: error ? '#ef4444' : theme.onSurface, marginBottom: 6 }}>
        {label}
      </Text>
      <TextInput
        style={{
          backgroundColor: theme.surfaceContainerLow,
          borderRadius: 12,
          borderWidth: 1.5,
          borderColor: error ? '#ef4444' : theme.outlineVariant,
          paddingHorizontal: 14,
          paddingVertical: 11,
          fontFamily: F.body,
          fontSize: 14,
          color: theme.onSurface,
          ...(multiline ? { minHeight: 80, textAlignVertical: 'top', paddingTop: 11 } : {}),
        }}
        value={value}
        onChangeText={onChangeText}
        placeholder={placeholder}
        placeholderTextColor={theme.onSurfaceVariant + '60'}
        multiline={multiline}
        numberOfLines={multiline ? 3 : 1}
        keyboardType={keyboardType}
        autoCapitalize={autoCapitalize}
      />
      {error
        ? <Text style={{ fontFamily: F.body, fontSize: 11, color: '#ef4444', marginTop: 4 }}>{error}</Text>
        : hint
          ? <Text style={{ fontFamily: F.body, fontSize: 11, color: theme.onSurfaceVariant, marginTop: 4 }}>{hint}</Text>
          : null}
    </View>
  );
}

function Toggle({ label, desc, value, onToggle, theme }: {
  label: string; desc?: string; value: boolean; onToggle: (v: boolean) => void; theme: ThemeRef;
}) {
  return (
    <View style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', paddingVertical: 14, borderBottomWidth: StyleSheet.hairlineWidth, borderBottomColor: theme.outlineVariant }}>
      <View style={{ flex: 1, marginRight: 12 }}>
        <Text style={{ fontFamily: F.subheading, fontSize: 14, color: theme.onSurface }}>{label}</Text>
        {desc ? <Text style={{ fontFamily: F.body, fontSize: 12, color: theme.onSurfaceVariant, marginTop: 2 }}>{desc}</Text> : null}
      </View>
      <Switch value={value} onValueChange={onToggle}
        trackColor={{ false: theme.outlineVariant, true: theme.primary + 'AA' }}
        thumbColor={value ? theme.primary : theme.surfaceContainerHigh} />
    </View>
  );
}

function Segment<T extends string>({ options, value, onChange, theme }: {
  options: { value: T; label: string }[]; value: T; onChange: (v: T) => void; theme: ThemeRef;
}) {
  return (
    <View style={{ flexDirection: 'row', borderRadius: 12, borderWidth: 1, borderColor: theme.outlineVariant, overflow: 'hidden', marginBottom: 14 }}>
      {options.map((o) => (
        <Pressable key={o.value} onPress={() => onChange(o.value)}
          style={{ flex: 1, paddingVertical: 10, alignItems: 'center', backgroundColor: value === o.value ? theme.primary : theme.surfaceContainerLow }}>
          <Text style={{ fontFamily: F.labelBold, fontSize: 12, color: value === o.value ? '#fff' : theme.onSurfaceVariant }}>
            {o.label}
          </Text>
        </Pressable>
      ))}
    </View>
  );
}

function PhotoBox({ uri, label, onPick, onRemove, uploading, theme }: {
  uri: string | null; label: string; onPick: () => void; onRemove?: () => void;
  uploading?: boolean; theme: ThemeRef;
}) {
  return (
    <View style={{ alignItems: 'center', marginBottom: 16 }}>
      <Text style={{ fontFamily: F.label, fontSize: 12, color: theme.onSurfaceVariant, marginBottom: 8 }}>{label}</Text>
      {uri
        ? (
          <View>
            <Image source={{ uri }} style={{ width: 100, height: 100, borderRadius: 50, borderWidth: 3, borderColor: theme.primary }} />
            {onRemove && (
              <Pressable onPress={onRemove}
                style={{ position: 'absolute', top: -4, right: -4, width: 24, height: 24, borderRadius: 12, backgroundColor: '#ef4444', alignItems: 'center', justifyContent: 'center' }}>
                <Ionicons name="close" size={14} color="#fff" />
              </Pressable>
            )}
          </View>
        )
        : (
          <Pressable onPress={uploading ? undefined : onPick}
            style={{ width: 100, height: 100, borderRadius: 50, backgroundColor: theme.surfaceContainerLow, borderWidth: 2, borderColor: theme.outlineVariant, borderStyle: 'dashed', alignItems: 'center', justifyContent: 'center' }}>
            {uploading
              ? <ActivityIndicator color={theme.primary} />
              : <Ionicons name="camera-outline" size={28} color={theme.onSurfaceVariant} />}
          </Pressable>
        )}
      {!uri && (
        <Pressable onPress={uploading ? undefined : onPick} style={{ marginTop: 6, paddingHorizontal: 12, paddingVertical: 5, backgroundColor: theme.primary + '20', borderRadius: 8 }}>
          <Text style={{ fontFamily: F.labelBold, fontSize: 11, color: theme.primary }}>
            {uploading ? 'Mengupload...' : '📷 Pilih Foto'}
          </Text>
        </Pressable>
      )}
      {uri && (
        <Pressable onPress={uploading ? undefined : onPick} style={{ marginTop: 6, paddingHorizontal: 12, paddingVertical: 5, backgroundColor: theme.surfaceContainerLow, borderRadius: 8 }}>
          <Text style={{ fontFamily: F.label, fontSize: 11, color: theme.onSurfaceVariant }}>
            {uploading ? 'Mengupload...' : 'Ganti Foto'}
          </Text>
        </Pressable>
      )}
    </View>
  );
}

// ── Main Screen ───────────────────────────────────────────────────────────────

export function InvitationFormScreen() {
  const navigation = useNavigation<NavProp>();
  const route = useRoute<RoutePropT>();
  const { theme } = useAppTheme();
  const s = makeStyles(theme);

  const invitationId = route.params?.invitationId;
  const isEdit = Boolean(invitationId);

  const [activeTab, setActiveTab] = useState<TabKey>('cover');
  const [form, setForm] = useState<InvitationFormData>(INITIAL_FORM);
  const [photos, setPhotos] = useState<Photos>({ cover: null, groom: null, bride: null });
  const [gallery, setGallery] = useState<GalleryPhoto[]>([]);
  const [loveStory, setLoveStory] = useState<LoveStory[]>([]);
  const [guests, setGuests] = useState<Guest[]>([]);
  const [guestInput, setGuestInput] = useState('');

  const [loading, setLoading] = useState(isEdit);
  const [saving, setSaving] = useState(false);
  const [publishing, setPublishing] = useState(false);
  const [isPublished, setIsPublished] = useState(false);
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [uploading, setUploading] = useState<string | null>(null); // 'cover'|'groom'|'bride'|'gallery'
  const [addingGuests, setAddingGuests] = useState(false);

  // savedId tracks the invitation ID after first save (needed for photo uploads on new)
  const [savedId, setSavedId] = useState<number | null>(invitationId ?? null);
  const slugEdited = useRef(false);

  // ── Load ───────────────────────────────────────────────────────────────────

  const loadInvitation = useCallback(async () => {
    if (!invitationId) return;
    try {
      setLoading(true);
      const res = await httpRequest<{ data: Record<string, unknown> }>(
        `/api/mobile/access/invitations/${invitationId}`,
        { method: 'GET', authMode: 'required' },
      );
      const d = res.data;
      const cs = (d.custom_styles as Record<string, unknown>) ?? {};
      setPhotos({
        cover: (d.cover_photo as string | null) ?? null,
        groom: (d.groom_photo as string | null) ?? null,
        bride: (d.bride_photo as string | null) ?? null,
      });
      setGallery((d.gallery_photos as GalleryPhoto[]) ?? []);
      setLoveStory((d.love_story as LoveStory[]) ?? []);
      setIsPublished(Boolean(d.is_published));
      setSavedId(Number(d.id));
      slugEdited.current = true;
      setForm({
        title:          (d.title as string) ?? '',
        slug:           (d.slug as string) ?? '',
        cover_title:    (cs.cover_title as string) ?? '',
        cover_subtitle: (cs.cover_subtitle as string) ?? 'The Wedding of',
        type:           'wedding',
        name_order:     ((cs.name_order as string) ?? 'groom_first') as InvitationFormData['name_order'],
        groom_name:     (d.groom_name as string) ?? '',
        groom_nickname: (d.groom_nickname as string) ?? '',
        groom_father:   (d.groom_father as string) ?? '',
        groom_mother:   (d.groom_mother as string) ?? '',
        groom_instagram:(d.instagram_groom as string) ?? '',
        bride_name:     (d.bride_name as string) ?? '',
        bride_nickname: (d.bride_nickname as string) ?? '',
        bride_father:   (d.bride_father as string) ?? '',
        bride_mother:   (d.bride_mother as string) ?? '',
        bride_instagram:(d.instagram_bride as string) ?? '',
        event_type:     ((cs.event_type as string) ?? 'both') as InvitationFormData['event_type'],
        akad_date:      ((d.akad_date as string) ?? '').slice(0, 10),
        akad_time:      (d.akad_time as string) ?? '08:00',
        akad_venue:     (d.akad_venue as string) ?? '',
        akad_address:   (d.akad_address as string) ?? '',
        akad_maps_link: (d.akad_maps_link as string) ?? '',
        resepsi_date:   ((d.resepsi_date as string) ?? '').slice(0, 10),
        resepsi_time:   (d.resepsi_time as string) ?? '11:00',
        resepsi_venue:  (d.resepsi_venue as string) ?? '',
        resepsi_address:(d.resepsi_address as string) ?? '',
        resepsi_maps_link: (d.resepsi_maps_link as string) ?? '',
        welcome_message: (cs.welcome_message as string) ?? INITIAL_FORM.welcome_message,
        quran_verse:    (cs.quran_verse as string) ?? INITIAL_FORM.quran_verse,
        countdown_enabled: (cs.countdown_enabled as boolean) ?? true,
        rsvp_enabled:   (d.enable_rsvp as boolean) ?? true,
        wishes_enabled: (d.enable_wishes as boolean) ?? true,
        gallery_enabled:(d.enable_gallery as boolean) ?? true,
        music_enabled:  Boolean(d.background_music),
      });
    } catch (e) {
      Alert.alert('Error', e instanceof HttpClientError ? e.message : 'Gagal memuat data.');
      navigation.goBack();
    } finally {
      setLoading(false);
    }
  }, [invitationId, navigation]);

  const loadGuests = useCallback(async (id: number) => {
    try {
      const res = await httpRequest<{ data: Guest[] }>(
        `/api/mobile/access/invitations/${id}/guests`,
        { method: 'GET', authMode: 'required' },
      );
      setGuests(res.data ?? []);
    } catch { /* silent */ }
  }, []);

  useEffect(() => { void loadInvitation(); }, [loadInvitation]);
  useEffect(() => {
    if (activeTab === 'tamu' && savedId) void loadGuests(savedId);
  }, [activeTab, savedId, loadGuests]);

  // ── Form helpers ───────────────────────────────────────────────────────────

  const set = <K extends keyof InvitationFormData>(key: K) => (val: InvitationFormData[K]) => {
    setForm(p => ({ ...p, [key]: val }));
    if (errors[key]) setErrors(p => { const n = { ...p }; delete n[key]; return n; });
  };

  const setTitle = (v: string) => {
    set('title')(v);
    if (!slugEdited.current) set('slug')(slugify(v));
  };

  // ── Save / Publish ─────────────────────────────────────────────────────────

  const validate = () => {
    const e: Record<string, string> = {};
    if (!form.title.trim()) e.title = 'Judul undangan wajib diisi.';
    if (!form.groom_name.trim()) e.groom_name = 'Nama mempelai pria wajib diisi.';
    if (form.type === 'wedding' && !form.bride_name.trim()) e.bride_name = 'Nama mempelai wanita wajib diisi.';
    setErrors(e);
    if (Object.keys(e).length) {
      if (e.title || e.slug) setActiveTab('cover');
      else if (e.groom_name || e.bride_name) setActiveTab('mempelai');
      return false;
    }
    return true;
  };

  const buildPayload = () => ({
    title: form.title,
    slug: form.slug || slugify(form.title),
    groom_name: form.groom_name, groom_nickname: form.groom_nickname,
    groom_father: form.groom_father, groom_mother: form.groom_mother,
    instagram_groom: form.groom_instagram,
    bride_name: form.bride_name, bride_nickname: form.bride_nickname,
    bride_father: form.bride_father, bride_mother: form.bride_mother,
    instagram_bride: form.bride_instagram,
    akad_date: form.akad_date || null, akad_time: form.akad_time,
    akad_venue: form.akad_venue, akad_address: form.akad_address, akad_maps_link: form.akad_maps_link,
    resepsi_date: form.resepsi_date || null, resepsi_time: form.resepsi_time,
    resepsi_venue: form.resepsi_venue, resepsi_address: form.resepsi_address, resepsi_maps_link: form.resepsi_maps_link,
    enable_rsvp: form.rsvp_enabled, enable_wishes: form.wishes_enabled, enable_gallery: form.gallery_enabled,
    love_story: loveStory,
    custom_styles: {
      event_type: form.event_type, name_order: form.name_order,
      cover_title: form.cover_title, cover_subtitle: form.cover_subtitle,
      welcome_message: form.welcome_message, quran_verse: form.quran_verse,
      countdown_enabled: form.countdown_enabled,
    },
  });

  const handleSave = async () => {
    if (!validate()) return;
    try {
      setSaving(true);
      if (savedId) {
        await httpRequest(`/api/mobile/access/invitations/${savedId}`,
          { method: 'PATCH', body: buildPayload(), authMode: 'required' });
      } else {
        const res = await httpRequest<{ data: { id: number } }>(
          '/api/mobile/access/invitations',
          { method: 'POST', body: buildPayload(), authMode: 'required' });
        setSavedId(res.data.id);
      }
      Alert.alert('✅ Berhasil', savedId ? 'Undangan berhasil diperbarui!' : 'Undangan berhasil dibuat! Anda sekarang dapat upload foto.');
      if (!savedId) setActiveTab('foto');
    } catch (e) {
      Alert.alert('Gagal Menyimpan', e instanceof HttpClientError ? e.message : 'Terjadi kesalahan.');
    } finally {
      setSaving(false);
    }
  };

  const handlePublish = async () => {
    if (!savedId) {
      Alert.alert('Simpan Dulu', 'Simpan undangan terlebih dahulu sebelum mempublikasikan.');
      return;
    }
    try {
      setPublishing(true);
      const res = await httpRequest<{ is_published: boolean; message: string }>(
        `/api/mobile/access/invitations/${savedId}/publish`,
        { method: 'POST', authMode: 'required' });
      setIsPublished(res.is_published);
      Alert.alert('✅ Berhasil', res.message);
    } catch (e) {
      Alert.alert('Gagal', e instanceof HttpClientError ? e.message : 'Terjadi kesalahan.');
    } finally {
      setPublishing(false);
    }
  };

  // ── Photo upload ───────────────────────────────────────────────────────────

  const requireSavedId = () => {
    if (!savedId) {
      Alert.alert('Simpan Dulu', 'Simpan undangan terlebih dahulu sebelum upload foto.');
      return false;
    }
    return true;
  };

  const handlePickCover = async () => {
    if (!requireSavedId()) return;
    const asset = await pickImage();
    if (!asset) return;
    // Show local preview immediately
    setPhotos(p => ({ ...p, cover: asset.uri }));
    try {
      setUploading('cover');
      const fd = await buildPhotoFormDataAsync(asset.uri);
      const res = await httpRequest<{ url: string }>(
        `/api/mobile/access/invitations/${savedId}/cover-photo`,
        { method: 'POST', body: fd, authMode: 'required', timeoutMs: 30000 });
      setPhotos(p => ({ ...p, cover: res.url }));
    } catch (e) {
      Alert.alert('Upload Gagal', e instanceof HttpClientError ? e.message : 'Gagal upload foto cover.');
    } finally {
      setUploading(null);
    }
  };

  const handlePickGroom = async () => {
    if (!requireSavedId()) return;
    const asset = await pickImage();
    if (!asset) return;
    setPhotos(p => ({ ...p, groom: asset.uri }));
    try {
      setUploading('groom');
      const fd = await buildPhotoFormDataAsync(asset.uri);
      const res = await httpRequest<{ url: string }>(
        `/api/mobile/access/invitations/${savedId}/groom-photo`,
        { method: 'POST', body: fd, authMode: 'required', timeoutMs: 30000 });
      setPhotos(p => ({ ...p, groom: res.url }));
    } catch (e) {
      Alert.alert('Upload Gagal', e instanceof HttpClientError ? e.message : 'Gagal upload foto mempelai pria.');
    } finally {
      setUploading(null);
    }
  };

  const handlePickBride = async () => {
    if (!requireSavedId()) return;
    const asset = await pickImage();
    if (!asset) return;
    setPhotos(p => ({ ...p, bride: asset.uri }));
    try {
      setUploading('bride');
      const fd = await buildPhotoFormDataAsync(asset.uri);
      const res = await httpRequest<{ url: string }>(
        `/api/mobile/access/invitations/${savedId}/bride-photo`,
        { method: 'POST', body: fd, authMode: 'required', timeoutMs: 30000 });
      setPhotos(p => ({ ...p, bride: res.url }));
    } catch (e) {
      Alert.alert('Upload Gagal', e instanceof HttpClientError ? e.message : 'Gagal upload foto mempelai wanita.');
    } finally {
      setUploading(null);
    }
  };

  const handlePickGallery = async () => {
    if (!requireSavedId()) return;
    const asset = await pickImage();
    if (!asset) return;
    // Add local preview immediately with temp id
    const tempId = Date.now();
    setGallery(p => [...p, { id: tempId, url: asset.uri }]);
    try {
      setUploading('gallery');
      const fd = await buildPhotoFormDataAsync(asset.uri);
      const res = await httpRequest<{ id: number; url: string }>(
        `/api/mobile/access/invitations/${savedId}/photos`,
        { method: 'POST', body: fd, authMode: 'required', timeoutMs: 30000 });
      // Replace temp entry with real id from server
      setGallery(p => p.map(x => x.id === tempId ? { id: res.id, url: res.url } : x));
    } catch (e) {
      // Remove temp entry on failure
      setGallery(p => p.filter(x => x.id !== tempId));
      Alert.alert('Upload Gagal', e instanceof HttpClientError ? e.message : 'Gagal upload foto galeri.');
    } finally {
      setUploading(null);
    }
  };

  const handleDeleteGallery = (photoId: number) => {
    Alert.alert('Hapus Foto', 'Hapus foto ini dari galeri?', [
      { text: 'Batal', style: 'cancel' },
      {
        text: 'Hapus', style: 'destructive', onPress: async () => {
          try {
            await httpRequest(`/api/mobile/access/invitations/${savedId}/photos/${photoId}`,
              { method: 'DELETE', authMode: 'required' });
            setGallery(p => p.filter(x => x.id !== photoId));
          } catch { Alert.alert('Gagal', 'Tidak dapat menghapus foto.'); }
        },
      },
    ]);
  };

  // ── Guest management ───────────────────────────────────────────────────────

  const handleAddGuests = async () => {
    if (!savedId) {
      Alert.alert('Simpan Dulu', 'Simpan undangan terlebih dahulu sebelum menambahkan tamu.');
      return;
    }
    if (!guestInput.trim()) return;
    try {
      setAddingGuests(true);
      await httpRequest(`/api/mobile/access/invitations/${savedId}/guests`,
        { method: 'POST', body: { names: guestInput }, authMode: 'required' });
      setGuestInput('');
      await loadGuests(savedId);
    } catch (e) {
      Alert.alert('Gagal', e instanceof HttpClientError ? e.message : 'Gagal menambahkan tamu.');
    } finally {
      setAddingGuests(false);
    }
  };

  const handleDeleteGuest = (guest: Guest) => {
    Alert.alert('Hapus Tamu', `Hapus "${guest.name}" dari daftar tamu?`, [
      { text: 'Batal', style: 'cancel' },
      {
        text: 'Hapus', style: 'destructive', onPress: async () => {
          try {
            await httpRequest(`/api/mobile/access/invitations/${savedId}/guests/${guest.id}`,
              { method: 'DELETE', authMode: 'required' });
            setGuests(p => p.filter(g => g.id !== guest.id));
          } catch { Alert.alert('Gagal', 'Tidak dapat menghapus tamu.'); }
        },
      },
    ]);
  };

  // ── Love Story helpers ─────────────────────────────────────────────────────

  const addLoveStory = () => setLoveStory(p => [...p, { date: '', title: '', description: '' }]);
  const setLS = (i: number, key: keyof LoveStory, val: string) =>
    setLoveStory(p => p.map((s, idx) => idx === i ? { ...s, [key]: val } : s));
  const removeLS = (i: number) => setLoveStory(p => p.filter((_, idx) => idx !== i));

  // ── Tab renders ────────────────────────────────────────────────────────────

  const renderCover = () => (
    <ScrollView style={s.tabContent} showsVerticalScrollIndicator={false} keyboardShouldPersistTaps="handled">
      <SectionTitle theme={theme}>Informasi Dasar</SectionTitle>
      <Field label="Judul Undangan *" value={form.title} onChangeText={setTitle}
        placeholder="Pernikahan Andi & Rina" error={errors.title} theme={theme} />
      <Field label="URL Slug" value={form.slug} onChangeText={v => { slugEdited.current = true; set('slug')(slugify(v)); }}
        placeholder="pernikahan-andi-rina" autoCapitalize="none"
        hint={`exoinvite.site/i/${form.slug || '...'}`} theme={theme} />
      <SectionTitle theme={theme}>Tampilan Cover</SectionTitle>
      <Field label="Sub-teks Cover" value={form.cover_subtitle} onChangeText={set('cover_subtitle')} placeholder="The Wedding of" theme={theme} />
      <Field label="Teks Utama Cover" value={form.cover_title} onChangeText={set('cover_title')} placeholder="Andi & Rina" theme={theme} />
      {!savedId && (
        <View style={{ marginTop: 20, padding: 14, backgroundColor: theme.primary + '15', borderRadius: 12, borderWidth: 1, borderColor: theme.primary + '40' }}>
          <Text style={{ fontFamily: F.subheading, fontSize: 13, color: theme.primary }}>💡 Upload Foto</Text>
          <Text style={{ fontFamily: F.body, fontSize: 12, color: theme.onSurfaceVariant, marginTop: 4 }}>
            Simpan undangan dahulu. Setelah tersimpan, tab Foto akan aktif untuk upload foto cover & mempelai.
          </Text>
        </View>
      )}
      <View style={{ height: 32 }} />
    </ScrollView>
  );

  const renderMempelai = () => (
    <ScrollView style={s.tabContent} showsVerticalScrollIndicator={false} keyboardShouldPersistTaps="handled">
      <SectionTitle theme={theme}>Jenis Undangan</SectionTitle>
      <Segment options={[{ value: 'wedding', label: 'Pernikahan' }, { value: 'birthday', label: 'Ulang Tahun' }, { value: 'other', label: 'Lainnya' }]}
        value={form.type} onChange={set('type')} theme={theme} />
      {form.type === 'wedding' && (
        <>
          <SectionTitle theme={theme}>Urutan Nama</SectionTitle>
          <Segment options={[{ value: 'groom_first', label: '🤵 Pria Dahulu' }, { value: 'bride_first', label: '👰 Wanita Dahulu' }]}
            value={form.name_order} onChange={set('name_order')} theme={theme} />
        </>
      )}

      {/* Mempelai Pria */}
      <SectionTitle theme={theme}>{form.type === 'wedding' ? '🤵 Mempelai Pria' : '🎂 Pemilik Acara'}</SectionTitle>
      <View style={{ backgroundColor: theme.surfaceContainerLow, borderRadius: 16, padding: 14, borderWidth: 1, borderColor: theme.outlineVariant }}>
        <Field label="Nama Lengkap *" value={form.groom_name} onChangeText={set('groom_name')} placeholder="Andi Prasetyo, S.Kom" error={errors.groom_name} theme={theme} />
        <Field label="Nama Panggilan" value={form.groom_nickname} onChangeText={set('groom_nickname')} placeholder="Andi" theme={theme} />
        <Field label="Nama Ayah" value={form.groom_father} onChangeText={set('groom_father')} placeholder="Bpk. Budiman" theme={theme} />
        <Field label="Nama Ibu" value={form.groom_mother} onChangeText={set('groom_mother')} placeholder="Ibu Siti" theme={theme} />
        <Field label="Instagram (@)" value={form.groom_instagram} onChangeText={set('groom_instagram')} placeholder="andi.prasetyo" autoCapitalize="none" theme={theme} />
      </View>

      {/* Mempelai Wanita */}
      {form.type === 'wedding' && (
        <>
          <SectionTitle theme={theme}>👰 Mempelai Wanita</SectionTitle>
          <View style={{ backgroundColor: theme.surfaceContainerLow, borderRadius: 16, padding: 14, borderWidth: 1, borderColor: theme.outlineVariant }}>
            <Field label="Nama Lengkap *" value={form.bride_name} onChangeText={set('bride_name')} placeholder="Rina Wulandari, S.Pd" error={errors.bride_name} theme={theme} />
            <Field label="Nama Panggilan" value={form.bride_nickname} onChangeText={set('bride_nickname')} placeholder="Rina" theme={theme} />
            <Field label="Nama Ayah" value={form.bride_father} onChangeText={set('bride_father')} placeholder="Bpk. Ahmad" theme={theme} />
            <Field label="Nama Ibu" value={form.bride_mother} onChangeText={set('bride_mother')} placeholder="Ibu Dewi" theme={theme} />
            <Field label="Instagram (@)" value={form.bride_instagram} onChangeText={set('bride_instagram')} placeholder="rina.wulandari" autoCapitalize="none" theme={theme} />
          </View>
        </>
      )}
      <View style={{ height: 32 }} />
    </ScrollView>
  );

  const renderAcara = () => (
    <ScrollView style={s.tabContent} showsVerticalScrollIndicator={false} keyboardShouldPersistTaps="handled">
      <SectionTitle theme={theme}>Jenis Acara</SectionTitle>
      <Segment
        options={[{ value: 'both', label: 'Akad + Resepsi' }, { value: 'akad_only', label: 'Akad Saja' }, { value: 'resepsi_only', label: 'Resepsi Saja' }]}
        value={form.event_type} onChange={set('event_type')} theme={theme} />

      {form.event_type !== 'resepsi_only' && (
        <>
          <SectionTitle theme={theme}>🕌 Akad Nikah</SectionTitle>
          <View style={{ backgroundColor: '#fef3c7', borderRadius: 16, padding: 14, borderWidth: 1, borderColor: '#fcd34d' }}>
            <Field label="Tanggal" value={form.akad_date} onChangeText={set('akad_date')} placeholder="2026-06-15" autoCapitalize="none" hint="Format: YYYY-MM-DD" theme={theme} />
            <Field label="Waktu" value={form.akad_time} onChangeText={set('akad_time')} placeholder="08:00" autoCapitalize="none" theme={theme} />
            <Field label="Nama Tempat" value={form.akad_venue} onChangeText={set('akad_venue')} placeholder="Masjid Al-Ikhlas" theme={theme} />
            <Field label="Alamat Lengkap" value={form.akad_address} onChangeText={set('akad_address')} placeholder="Jl. Merdeka No. 123" multiline theme={theme} />
            <Field label="Link Google Maps" value={form.akad_maps_link} onChangeText={set('akad_maps_link')} placeholder="https://maps.app.goo.gl/..." keyboardType="url" autoCapitalize="none" theme={theme} />
          </View>
        </>
      )}

      {form.event_type !== 'akad_only' && (
        <>
          <SectionTitle theme={theme}>🎊 Resepsi</SectionTitle>
          <View style={{ backgroundColor: '#dcfce7', borderRadius: 16, padding: 14, borderWidth: 1, borderColor: '#86efac' }}>
            <Field label="Tanggal" value={form.resepsi_date} onChangeText={set('resepsi_date')} placeholder="2026-06-15" autoCapitalize="none" hint="Format: YYYY-MM-DD" theme={theme} />
            <Field label="Waktu" value={form.resepsi_time} onChangeText={set('resepsi_time')} placeholder="11:00" autoCapitalize="none" theme={theme} />
            <Field label="Nama Tempat" value={form.resepsi_venue} onChangeText={set('resepsi_venue')} placeholder="Gedung Serbaguna Mawar" theme={theme} />
            <Field label="Alamat Lengkap" value={form.resepsi_address} onChangeText={set('resepsi_address')} placeholder="Jl. Mawar No. 456" multiline theme={theme} />
            <Field label="Link Google Maps" value={form.resepsi_maps_link} onChangeText={set('resepsi_maps_link')} placeholder="https://maps.app.goo.gl/..." keyboardType="url" autoCapitalize="none" theme={theme} />
          </View>
        </>
      )}

      {/* Love Story */}
      <SectionTitle theme={theme}>📖 Kisah Cinta</SectionTitle>
      {loveStory.map((ls, i) => (
        <View key={i} style={{ backgroundColor: theme.surfaceContainerLow, borderRadius: 14, padding: 12, marginBottom: 10, borderWidth: 1, borderColor: theme.outlineVariant }}>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 8 }}>
            <Text style={{ fontFamily: F.labelBold, fontSize: 13, color: theme.primary }}>Momen #{i + 1}</Text>
            <Pressable onPress={() => removeLS(i)}>
              <Ionicons name="trash-outline" size={16} color="#ef4444" />
            </Pressable>
          </View>
          <View style={{ flexDirection: 'row', gap: 10 }}>
            <View style={{ width: 70 }}>
              <Field label="Tahun" value={ls.date} onChangeText={v => setLS(i, 'date', v)} placeholder="2022" autoCapitalize="none" theme={theme} />
            </View>
            <View style={{ flex: 1 }}>
              <Field label="Judul Momen" value={ls.title} onChangeText={v => setLS(i, 'title', v)} placeholder="Pertama Bertemu" theme={theme} />
            </View>
          </View>
          <Field label="Cerita Singkat" value={ls.description} onChangeText={v => setLS(i, 'description', v)} placeholder="Ceritakan momen ini..." multiline theme={theme} />
        </View>
      ))}
      <Pressable onPress={addLoveStory} style={{ flexDirection: 'row', alignItems: 'center', gap: 6, paddingVertical: 10 }}>
        <Ionicons name="add-circle-outline" size={20} color={theme.primary} />
        <Text style={{ fontFamily: F.labelBold, fontSize: 13, color: theme.primary }}>Tambah Kisah</Text>
      </Pressable>
      <View style={{ height: 32 }} />
    </ScrollView>
  );

  const renderFoto = () => {
    // When no savedId yet, show a clear empty state instead of invisible locked sections
    if (!savedId) {
      return (
        <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center', paddingHorizontal: 32, paddingBottom: 40 }}>
          <View style={{ width: 80, height: 80, borderRadius: 40, backgroundColor: theme.primary + '20', alignItems: 'center', justifyContent: 'center', marginBottom: 16 }}>
            <Ionicons name="camera-outline" size={36} color={theme.primary} />
          </View>
          <Text style={{ fontFamily: F.heading, fontSize: 18, color: theme.onSurface, textAlign: 'center' }}>Upload Foto</Text>
          <Text style={{ fontFamily: F.body, fontSize: 14, color: theme.onSurfaceVariant, textAlign: 'center', marginTop: 8, lineHeight: 22 }}>
            Simpan undangan terlebih dahulu, lalu Anda bisa upload foto cover, foto mempelai, dan galeri.
          </Text>
          <Pressable onPress={() => { void handleSave(); }}
            style={{ marginTop: 24, backgroundColor: theme.primary, paddingHorizontal: 28, paddingVertical: 13, borderRadius: 14, flexDirection: 'row', gap: 8, alignItems: 'center' }}>
            <Ionicons name="save-outline" size={18} color="#fff" />
            <Text style={{ fontFamily: F.labelBold, fontSize: 14, color: '#fff' }}>Simpan Sekarang</Text>
          </Pressable>
        </View>
      );
    }

    return (
      <ScrollView style={s.tabContent} showsVerticalScrollIndicator={false} contentContainerStyle={{ paddingTop: 8, paddingBottom: 32 }}>
        {/* Cover Photo */}
        <SectionTitle theme={theme} first>🖼️ Foto Cover</SectionTitle>
        <Pressable onPress={handlePickCover}
          style={{ width: '100%', height: 180, borderRadius: 16, overflow: 'hidden', backgroundColor: theme.surfaceContainerLow, borderWidth: 2, borderColor: photos.cover ? theme.primary : theme.outlineVariant, alignItems: 'center', justifyContent: 'center' }}>
          {photos.cover
            ? <Image source={{ uri: photos.cover }} style={{ width: '100%', height: '100%' }} resizeMode="cover" />
            : uploading === 'cover'
              ? <ActivityIndicator size="large" color={theme.primary} />
              : (
                <>
                  <View style={{ width: 56, height: 56, borderRadius: 28, backgroundColor: theme.primary + '20', alignItems: 'center', justifyContent: 'center' }}>
                    <Ionicons name="image-outline" size={28} color={theme.primary} />
                  </View>
                  <Text style={{ fontFamily: F.labelBold, fontSize: 13, color: theme.onSurface, marginTop: 10 }}>Tap untuk upload foto cover</Text>
                  <Text style={{ fontFamily: F.body, fontSize: 11, color: theme.onSurfaceVariant, marginTop: 3 }}>Maks. 5MB (JPG/PNG)</Text>
                </>
              )}
          {photos.cover && (
            <View style={{ position: 'absolute', bottom: 10, right: 10, backgroundColor: theme.primary, paddingHorizontal: 12, paddingVertical: 6, borderRadius: 20, flexDirection: 'row', gap: 4, alignItems: 'center' }}>
              <Ionicons name="camera" size={12} color="#fff" />
              <Text style={{ fontFamily: F.labelBold, fontSize: 11, color: '#fff' }}>Ganti</Text>
            </View>
          )}
          {uploading === 'cover' && (
            <View style={{ position: 'absolute', inset: 0, backgroundColor: '#00000030', alignItems: 'center', justifyContent: 'center' } as unknown as object} />
          )}
        </Pressable>

        {/* Mempelai Photos */}
        <SectionTitle theme={theme}>👫 Foto Mempelai</SectionTitle>
        <View style={{ flexDirection: 'row', justifyContent: 'space-around', paddingVertical: 4 }}>
          <PhotoBox uri={photos.groom} label="🤵 Mempelai Pria"
            onPick={handlePickGroom} uploading={uploading === 'groom'} theme={theme} />
          <PhotoBox uri={photos.bride} label="👰 Mempelai Wanita"
            onPick={handlePickBride} uploading={uploading === 'bride'} theme={theme} />
        </View>

        {/* Gallery Photos */}
        <SectionTitle theme={theme}>📷 Galeri Foto</SectionTitle>
        <View style={{ flexDirection: 'row', flexWrap: 'wrap', gap: 8, marginBottom: 8 }}>
          {gallery.map(p => (
            <Pressable key={p.id} onPress={() => handleDeleteGallery(p.id)}
              style={{ width: '30%', aspectRatio: 1, borderRadius: 12, overflow: 'hidden', position: 'relative' }}>
              <Image source={{ uri: p.url }} style={{ width: '100%', height: '100%' }} resizeMode="cover" />
              <View style={{ position: 'absolute', top: 4, right: 4, width: 22, height: 22, borderRadius: 11, backgroundColor: '#ef4444', alignItems: 'center', justifyContent: 'center' }}>
                <Ionicons name="close" size={12} color="#fff" />
              </View>
            </Pressable>
          ))}
          <Pressable onPress={handlePickGallery}
            style={{ width: '30%', aspectRatio: 1, borderRadius: 12, borderWidth: 2, borderColor: theme.outlineVariant, alignItems: 'center', justifyContent: 'center', backgroundColor: theme.surfaceContainerLow }}>
            {uploading === 'gallery'
              ? <ActivityIndicator color={theme.primary} />
              : <Ionicons name="add" size={28} color={theme.onSurfaceVariant} />}
          </Pressable>
        </View>
        {gallery.length === 0 && (
          <Text style={{ fontFamily: F.body, fontSize: 12, color: theme.onSurfaceVariant, textAlign: 'center', marginBottom: 16, marginTop: 4 }}>
            Belum ada foto galeri. Tap + untuk upload.
          </Text>
        )}
      </ScrollView>
    );
  };

  const renderTamu = () => {
    if (!savedId) {
      return (
        <View style={{ flex: 1, alignItems: 'center', justifyContent: 'center', paddingHorizontal: 32, paddingBottom: 40 }}>
          <View style={{ width: 80, height: 80, borderRadius: 40, backgroundColor: theme.primary + '20', alignItems: 'center', justifyContent: 'center', marginBottom: 16 }}>
            <Ionicons name="people-outline" size={36} color={theme.primary} />
          </View>
          <Text style={{ fontFamily: F.heading, fontSize: 18, color: theme.onSurface, textAlign: 'center' }}>Daftar Tamu</Text>
          <Text style={{ fontFamily: F.body, fontSize: 14, color: theme.onSurfaceVariant, textAlign: 'center', marginTop: 8, lineHeight: 22 }}>
            Simpan undangan terlebih dahulu, lalu Anda bisa menambahkan dan mengelola daftar tamu undangan.
          </Text>
          <Pressable onPress={() => { void handleSave(); }}
            style={{ marginTop: 24, backgroundColor: theme.primary, paddingHorizontal: 28, paddingVertical: 13, borderRadius: 14, flexDirection: 'row', gap: 8, alignItems: 'center' }}>
            <Ionicons name="save-outline" size={18} color="#fff" />
            <Text style={{ fontFamily: F.labelBold, fontSize: 14, color: '#fff' }}>Simpan Sekarang</Text>
          </Pressable>
        </View>
      );
    }

    return (
      <ScrollView style={s.tabContent} showsVerticalScrollIndicator={false} keyboardShouldPersistTaps="handled" contentContainerStyle={{ paddingTop: 8, paddingBottom: 32 }}>
        <SectionTitle theme={theme} first>✏️ Tambah Tamu Cepat</SectionTitle>
        <Text style={{ fontFamily: F.body, fontSize: 12, color: theme.onSurfaceVariant, marginBottom: 8 }}>
          Pisahkan nama tamu dengan koma atau enter
        </Text>
        <TextInput
          style={{ backgroundColor: theme.surfaceContainerLow, borderRadius: 14, borderWidth: 1.5, borderColor: theme.outlineVariant, paddingHorizontal: 14, paddingVertical: 11, fontFamily: F.body, fontSize: 14, color: theme.onSurface, minHeight: 90, textAlignVertical: 'top' }}
          value={guestInput}
          onChangeText={setGuestInput}
          placeholder="Bapak Budi, Ibu Siti, Pak Ahmad, Bu Rina"
          placeholderTextColor={theme.onSurfaceVariant + '60'}
          multiline
        />
        <Pressable onPress={() => void handleAddGuests()}
          style={{ marginTop: 10, backgroundColor: theme.primary, borderRadius: 12, paddingVertical: 12, alignItems: 'center', flexDirection: 'row', justifyContent: 'center', gap: 6 }}>
          {addingGuests
            ? <ActivityIndicator size="small" color="#fff" />
            : <Ionicons name="person-add-outline" size={18} color="#fff" />}
          <Text style={{ fontFamily: F.labelBold, fontSize: 14, color: '#fff' }}>
            {addingGuests ? 'Menambahkan...' : 'Tambah Tamu'}
          </Text>
        </Pressable>

        <SectionTitle theme={theme}>👥 Daftar Tamu ({guests.length})</SectionTitle>
        {guests.length === 0
          ? <Text style={{ fontFamily: F.body, fontSize: 13, color: theme.onSurfaceVariant, textAlign: 'center', paddingVertical: 24 }}>Belum ada tamu. Tambahkan di atas.</Text>
          : guests.map(g => (
            <View key={g.id} style={{ flexDirection: 'row', alignItems: 'center', paddingVertical: 12, borderBottomWidth: StyleSheet.hairlineWidth, borderBottomColor: theme.outlineVariant }}>
              <View style={{ width: 36, height: 36, borderRadius: 18, backgroundColor: theme.primary + '20', alignItems: 'center', justifyContent: 'center', marginRight: 12 }}>
                <Text style={{ fontFamily: F.labelBold, fontSize: 14, color: theme.primary }}>
                  {g.name.charAt(0).toUpperCase()}
                </Text>
              </View>
              <View style={{ flex: 1 }}>
                <Text style={{ fontFamily: F.subheading, fontSize: 14, color: theme.onSurface }}>{g.name}</Text>
                <Text style={{ fontFamily: F.body, fontSize: 11, color: g.status === 'confirmed' ? '#22c55e' : g.status === 'declined' ? '#ef4444' : theme.onSurfaceVariant }}>
                  {g.status === 'confirmed' ? '✓ Hadir' : g.status === 'declined' ? '✗ Tidak Hadir' : '⌛ Menunggu'}
                </Text>
              </View>
              <Pressable onPress={() => handleDeleteGuest(g)} hitSlop={8}>
                <Ionicons name="trash-outline" size={18} color="#ef4444" />
              </Pressable>
            </View>
          ))}
      </ScrollView>
    );
  };

  const renderFitur = () => (
    <ScrollView style={s.tabContent} showsVerticalScrollIndicator={false} keyboardShouldPersistTaps="handled">
      <SectionTitle theme={theme}>💬 Pesan</SectionTitle>
      <Field label="Pesan Sambutan" value={form.welcome_message} onChangeText={set('welcome_message')} multiline placeholder="Pesan pembuka undangan..." theme={theme} />
      <Field label="Ayat / Kutipan" value={form.quran_verse} onChangeText={set('quran_verse')} multiline placeholder="Ayat Al-Quran atau kutipan..." theme={theme} />

      <SectionTitle theme={theme}>⚡ Fitur Aktif</SectionTitle>
      <Toggle label="Countdown" desc="Hitung mundur menuju hari acara" value={form.countdown_enabled} onToggle={set('countdown_enabled')} theme={theme} />
      <Toggle label="RSVP Konfirmasi" desc="Tamu dapat konfirmasi kehadiran" value={form.rsvp_enabled} onToggle={set('rsvp_enabled')} theme={theme} />
      <Toggle label="Ucapan & Doa" desc="Tamu dapat menulis ucapan" value={form.wishes_enabled} onToggle={set('wishes_enabled')} theme={theme} />
      <Toggle label="Gallery Foto" desc="Tampilkan galeri foto pernikahan" value={form.gallery_enabled} onToggle={set('gallery_enabled')} theme={theme} />
      <Toggle label="Musik Latar" desc="Musik background undangan digital" value={form.music_enabled} onToggle={set('music_enabled')} theme={theme} />
      <View style={{ height: 32 }} />
    </ScrollView>
  );

  // ── Main render ────────────────────────────────────────────────────────────

  if (loading) {
    return (
      <SafeAreaView style={[s.safe, { justifyContent: 'center', alignItems: 'center' }]} edges={['top']}>
        <ActivityIndicator size="large" color={theme.primary} />
        <Text style={{ fontFamily: F.body, fontSize: 14, color: theme.onSurfaceVariant, marginTop: 12 }}>Memuat undangan...</Text>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={s.safe} edges={['top']}>
      <KeyboardAvoidingView style={{ flex: 1 }} behavior={Platform.OS === 'ios' ? 'padding' : undefined}>
        {/* Header */}
        <View style={s.header}>
          <Pressable style={s.backBtn} onPress={() => navigation.goBack()}>
            <Ionicons name="arrow-back" size={22} color={theme.onSurface} />
          </Pressable>
          <View style={{ flex: 1 }}>
            <Text style={s.headerTitle}>{isEdit ? 'Edit Undangan' : 'Buat Undangan'}</Text>
            {form.title ? <Text style={s.headerSub} numberOfLines={1}>{form.title}</Text> : null}
          </View>
          {savedId && (
            <Pressable style={[s.publishBtn, { backgroundColor: isPublished ? '#22c55e' : theme.primary + 'DD' }, publishing && { opacity: 0.6 }]}
              onPress={() => void handlePublish()} disabled={publishing}>
              {publishing
                ? <ActivityIndicator size="small" color="#fff" />
                : <Text style={s.publishBtnText}>{isPublished ? '✅ Aktif' : '🚀 Publish'}</Text>}
            </Pressable>
          )}
          <Pressable style={[s.saveBtn, saving && { opacity: 0.6 }]}
            onPress={() => void handleSave()} disabled={saving}>
            {saving
              ? <ActivityIndicator size="small" color="#fff" />
              : <Text style={s.saveBtnText}>Simpan</Text>}
          </Pressable>
        </View>

        {/* ── Tab Bar — scrollable, fixed height ──────── */}
        <View style={{ height: 50, backgroundColor: theme.surface, borderBottomWidth: StyleSheet.hairlineWidth, borderBottomColor: theme.outlineVariant }}>
          <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={{ paddingHorizontal: 4, alignItems: 'stretch' }}>
          {TABS.map(tab => {
            const active = activeTab === tab.key;
            const hasErr = (tab.key === 'cover' && (errors.title || errors.slug)) || (tab.key === 'mempelai' && (errors.groom_name || errors.bride_name));
            return (
              <Pressable key={tab.key} onPress={() => setActiveTab(tab.key)}
                style={{ alignItems: 'center', paddingHorizontal: 14, paddingVertical: 10, gap: 3, position: 'relative', borderBottomWidth: 2, borderBottomColor: active ? theme.primary : 'transparent' }}>
                <Ionicons name={tab.icon} size={17} color={active ? theme.primary : hasErr ? '#ef4444' : theme.onSurfaceVariant} />
                <Text style={{ fontFamily: active ? F.labelBold : F.label, fontSize: 11, color: active ? theme.primary : hasErr ? '#ef4444' : theme.onSurfaceVariant }}>
                  {tab.label}
                </Text>
              </Pressable>
            );
          })}
          </ScrollView>
        </View>

        {/* Tab content */}
        {activeTab === 'cover'    && renderCover()}
        {activeTab === 'mempelai' && renderMempelai()}
        {activeTab === 'acara'    && renderAcara()}
        {activeTab === 'foto'     && renderFoto()}
        {activeTab === 'tamu'     && renderTamu()}
        {activeTab === 'fitur'    && renderFitur()}
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

// ── Styles ────────────────────────────────────────────────────────────────────

function makeStyles(t: ThemeRef) {
  return StyleSheet.create({
    safe:          { flex: 1, backgroundColor: t.background },
    header:        { flexDirection: 'row', alignItems: 'center', paddingHorizontal: 12, paddingVertical: 12, borderBottomWidth: StyleSheet.hairlineWidth, borderBottomColor: t.outlineVariant, gap: 8 },
    backBtn:       { width: 36, height: 36, borderRadius: 10, backgroundColor: t.surfaceContainerLow, alignItems: 'center', justifyContent: 'center' },
    headerTitle:   { fontFamily: F.heading, fontSize: 15, color: t.onSurface },
    headerSub:     { fontFamily: F.body, fontSize: 11, color: t.onSurfaceVariant },
    saveBtn:       { backgroundColor: t.primary, paddingHorizontal: 14, paddingVertical: 8, borderRadius: 10, minWidth: 64, alignItems: 'center' },
    saveBtnText:   { fontFamily: F.labelBold, fontSize: 13, color: '#fff' },
    publishBtn:    { paddingHorizontal: 12, paddingVertical: 8, borderRadius: 10, minWidth: 72, alignItems: 'center' },
    publishBtnText:{ fontFamily: F.labelBold, fontSize: 12, color: '#fff' },
    tabContent:    { flex: 1, paddingHorizontal: 16, paddingTop: 2 },
  });
}

