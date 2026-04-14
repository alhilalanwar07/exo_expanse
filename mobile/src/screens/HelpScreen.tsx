import { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  Pressable,
  Linking,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons, MaterialCommunityIcons } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';

import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import type { RootStackParamList } from '../navigation/types';

type NavProp = NativeStackNavigationProp<RootStackParamList, 'Help'>;

const FAQ_ITEMS = [
  {
    q: 'Bagaimana cara membuat undangan digital?',
    a: 'Buka tab Beranda, pilih tema yang diinginkan, lalu tekan "Gunakan Tema". Pilih undangan yang ingin diperbarui atau buat yang baru dari tab Undangan.',
  },
  {
    q: 'Apa perbedaan tema Gratis dan Premium?',
    a: 'Tema Gratis dapat digunakan oleh semua pengguna tanpa biaya tambahan. Tema Premium menawarkan desain eksklusif, animasi lebih kaya, dan fitur galeri foto lebih luas.',
  },
  {
    q: 'Bagaimana cara berbagi link undangan?',
    a: 'Di tab Undangan, tekan tombol "Sebar Undangan" pada kartu undangan yang dipilih. Sistem akan membuka menu berbagi bawaan perangkat Anda.',
  },
  {
    q: 'Apakah data tamu disimpan di aplikasi?',
    a: 'Data tamu disimpan di server kami secara aman. Anda dapat mengelolanya melalui dasbor web di exoinvite.site.',
  },
  {
    q: 'Bagaimana cara menghubungkan perangkat baru?',
    a: 'Buka tab Profil → Perangkat Terhubung, lalu masukkan kode akses yang diperoleh dari dasbor web Anda.',
  },
  {
    q: 'Apakah undangan saya bisa diakses tanpa internet?',
    a: 'Undangan digital memerlukan koneksi internet untuk ditampilkan ke tamu. Namun, draf konten Anda tersimpan lokal di perangkat sehingga bisa diedit secara offline.',
  },
] as const;

export function HelpScreen() {
  const navigation = useNavigation<NavProp>();
  const { theme } = useAppTheme();
  const s = makeStyles(theme);
  const [openIndex, setOpenIndex] = useState<number | null>(null);

  const toggle = (i: number) => setOpenIndex((prev) => (prev === i ? null : i));

  const openLink = async (url: string) => {
    if (await Linking.canOpenURL(url)) await Linking.openURL(url);
  };

  return (
    <SafeAreaView style={s.safeArea} edges={['top']}>
      {/* Header */}
      <View style={s.header}>
        <Pressable
          onPress={() => navigation.goBack()}
          style={({ pressed }) => [s.iconBtn, pressed && s.pressed]}
        >
          <Ionicons name="arrow-back" size={20} color={theme.onSurface} />
        </Pressable>
        <View style={s.headerText}>
          <Text style={s.headerEyebrow}>Pusat Bantuan</Text>
          <Text style={s.headerTitle}>Bantuan</Text>
        </View>
      </View>

      <ScrollView contentContainerStyle={s.scroll} showsVerticalScrollIndicator={false}>
        {/* Hero */}
        <View style={s.hero}>
          <View style={s.heroIcon}>
            <MaterialCommunityIcons name="help-circle-outline" size={36} color={theme.primary} />
          </View>
          <Text style={s.heroTitle}>Ada yang bisa kami bantu?</Text>
          <Text style={s.heroSub}>
            Temukan jawaban dari pertanyaan umum di bawah ini, atau hubungi tim kami langsung.
          </Text>
        </View>

        {/* FAQ */}
        <Text style={s.sectionLabel}>PERTANYAAN UMUM</Text>
        <View style={s.faqCard}>
          {FAQ_ITEMS.map((item, i) => {
            const isOpen = openIndex === i;
            return (
              <View key={i}>
                {i > 0 && <View style={s.faqDivider} />}
                <Pressable
                  style={({ pressed }) => [s.faqRow, pressed && s.pressed]}
                  onPress={() => toggle(i)}
                >
                  <Text style={s.faqQ}>{item.q}</Text>
                  <Ionicons
                    name={isOpen ? 'chevron-up' : 'chevron-down'}
                    size={18}
                    color={theme.outline}
                  />
                </Pressable>
                {isOpen && (
                  <Text style={s.faqA}>{item.a}</Text>
                )}
              </View>
            );
          })}
        </View>

        {/* Contact */}
        <Text style={s.sectionLabel}>HUBUNGI KAMI</Text>
        <View style={s.contactCard}>
          <Pressable
            style={({ pressed }) => [s.contactRow, pressed && s.pressed]}
            onPress={() => void openLink('https://wa.me/6281234567890?text=Halo+Exoinvite%2C+saya+butuh+bantuan.')}
          >
            <View style={[s.contactIcon, s.contactIconWa]}>
              <MaterialCommunityIcons name="whatsapp" size={22} color="#25D366" />
            </View>
            <View style={s.contactText}>
              <Text style={s.contactLabel}>WhatsApp</Text>
              <Text style={s.contactSub}>Chat langsung dengan tim support kami</Text>
            </View>
            <Ionicons name="open-outline" size={16} color={theme.outline} />
          </Pressable>

          <View style={s.faqDivider} />

          <Pressable
            style={({ pressed }) => [s.contactRow, pressed && s.pressed]}
            onPress={() => void openLink('mailto:support@exoinvite.site?subject=Bantuan Aplikasi')}
          >
            <View style={[s.contactIcon, s.contactIconMail]}>
              <MaterialCommunityIcons name="email-outline" size={22} color={theme.primary} />
            </View>
            <View style={s.contactText}>
              <Text style={s.contactLabel}>Email</Text>
              <Text style={s.contactSub}>support@exoinvite.site</Text>
            </View>
            <Ionicons name="open-outline" size={16} color={theme.outline} />
          </Pressable>

          <View style={s.faqDivider} />

          <Pressable
            style={({ pressed }) => [s.contactRow, pressed && s.pressed]}
            onPress={() => void openLink('https://exoinvite.site')}
          >
            <View style={[s.contactIcon, s.contactIconWeb]}>
              <MaterialCommunityIcons name="web" size={22} color={theme.onSurfaceVariant} />
            </View>
            <View style={s.contactText}>
              <Text style={s.contactLabel}>Website</Text>
              <Text style={s.contactSub}>exoinvite.site</Text>
            </View>
            <Ionicons name="open-outline" size={16} color={theme.outline} />
          </Pressable>
        </View>

        <Text style={s.versionText}>Exoinvite Mobile • v1.0.0</Text>
      </ScrollView>
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

    scroll: {
      paddingHorizontal: 16,
      paddingBottom: 40,
      gap: 14,
    },

    hero: {
      alignItems: 'center',
      paddingVertical: 24,
      gap: 8,
    },
    heroIcon: {
      width: 72,
      height: 72,
      borderRadius: 36,
      backgroundColor: t.surfaceContainerLow,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      alignItems: 'center',
      justifyContent: 'center',
      marginBottom: 4,
    },
    heroTitle: {
      fontFamily: F.heading,
      fontSize: 20,
      color: t.onSurface,
      textAlign: 'center',
    },
    heroSub: {
      fontFamily: F.body,
      fontSize: 14,
      color: t.onSurfaceVariant,
      textAlign: 'center',
      lineHeight: 21,
      paddingHorizontal: 8,
    },

    sectionLabel: {
      fontFamily: F.label,
      fontSize: 10,
      letterSpacing: 1,
      textTransform: 'uppercase',
      color: t.onSurfaceVariant,
      marginLeft: 2,
      marginBottom: 4,
    },

    faqCard: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: 20,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      overflow: 'hidden',
    },
    faqRow: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      paddingHorizontal: 16,
      paddingVertical: 14,
      gap: 10,
    },
    faqQ: {
      flex: 1,
      fontFamily: F.labelBold,
      fontSize: 14,
      color: t.onSurface,
      lineHeight: 20,
    },
    faqA: {
      fontFamily: F.body,
      fontSize: 13,
      color: t.onSurfaceVariant,
      lineHeight: 20,
      paddingHorizontal: 16,
      paddingBottom: 14,
    },
    faqDivider: {
      height: 1,
      backgroundColor: t.outlineVariant,
    },

    contactCard: {
      backgroundColor: t.surfaceContainerLow,
      borderRadius: 20,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      overflow: 'hidden',
    },
    contactRow: {
      flexDirection: 'row',
      alignItems: 'center',
      paddingHorizontal: 14,
      paddingVertical: 14,
      gap: 12,
    },
    contactIcon: {
      width: 42,
      height: 42,
      borderRadius: 12,
      alignItems: 'center',
      justifyContent: 'center',
    },
    contactIconWa: { backgroundColor: 'rgba(37,211,102,0.10)' },
    contactIconMail: { backgroundColor: t.surfaceContainerHighest },
    contactIconWeb: { backgroundColor: t.surfaceContainerHighest },
    contactText: { flex: 1, gap: 2 },
    contactLabel: {
      fontFamily: F.labelBold,
      fontSize: 14,
      color: t.onSurface,
    },
    contactSub: {
      fontFamily: F.body,
      fontSize: 12,
      color: t.onSurfaceVariant,
    },

    versionText: {
      fontFamily: F.body,
      fontSize: 11,
      color: t.outline,
      textAlign: 'center',
      marginTop: 8,
    },

    pressed: { opacity: 0.84, transform: [{ scale: 0.97 }] },
  });
}
