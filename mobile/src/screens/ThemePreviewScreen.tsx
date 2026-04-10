/**
 * ThemePreviewScreen
 *
 * Full-screen in-app demo viewer. Uses:
 *  - react-native-webview  on iOS/Android
 *  - <iframe>              on Expo Web
 * (handled by InAppBrowser.tsx / InAppBrowser.web.tsx via Metro platform resolution)
 */

import { useState } from 'react';
import {
  View,
  Text,
  Pressable,
  StyleSheet,
  Platform,
} from 'react-native';
import { SafeAreaView, useSafeAreaInsets } from 'react-native-safe-area-context';
import { useNavigation, useRoute } from '@react-navigation/native';
import type { RouteProp } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import { MaterialCommunityIcons, Ionicons } from '@expo/vector-icons';

import { useAppTheme } from '../shared/theme/index';
import { F } from '../shared/theme/fonts';
import { InAppBrowser } from '../shared/components/InAppBrowser';
import type { RootStackParamList } from '../navigation/RootNavigator';

type NavProp = NativeStackNavigationProp<RootStackParamList, 'ThemePreview'>;
type RoutePropType = RouteProp<RootStackParamList, 'ThemePreview'>;

export function ThemePreviewScreen() {
  const navigation = useNavigation<NavProp>();
  const route = useRoute<RoutePropType>();
  const { theme } = useAppTheme();
  const insets = useSafeAreaInsets();

  const { name, previewUrl, isPremium } = route.params;

  const [loading, setLoading] = useState(true);
  const [hasError, setHasError] = useState(false);
  // Key to force-remount the browser on refresh
  const [reloadKey, setReloadKey] = useState(0);

  const s = makeStyles(theme);

  const handleRefresh = () => {
    setHasError(false);
    setLoading(true);
    setReloadKey((k) => k + 1);
  };

  return (
    <View style={[s.root, { paddingBottom: insets.bottom }]}>
      {/* ── Top bar ─────────────────────────────────────── */}
      <SafeAreaView style={s.topBar} edges={['top']}>
        <Pressable
          onPress={() => navigation.goBack()}
          style={({ pressed }) => [s.iconBtn, pressed && s.pressed]}
          hitSlop={10}
        >
          <Ionicons name="arrow-back" size={22} color={theme.onSurface} />
        </Pressable>

        <View style={s.titleWrap}>
          <Text style={s.topBarTitle} numberOfLines={1}>{name}</Text>
          {isPremium && (
            <View style={s.premiumPill}>
              <Text style={s.premiumPillText}>PREMIUM</Text>
            </View>
          )}
        </View>

        <Pressable
          onPress={handleRefresh}
          style={({ pressed }) => [s.iconBtn, pressed && s.pressed]}
          hitSlop={10}
        >
          <Ionicons name="refresh-outline" size={20} color={theme.onSurfaceVariant} />
        </Pressable>
      </SafeAreaView>

      {/* ── Browser ─────────────────────────────────────── */}
      <View style={s.browserWrap}>
        <InAppBrowser
          key={reloadKey}
          uri={previewUrl}
          loading={loading}
          hasError={hasError}
          onLoadStart={() => setLoading(true)}
          onLoadEnd={() => setLoading(false)}
          onError={() => { setLoading(false); setHasError(true); }}
        />
      </View>

      {/* ── Bottom bar ──────────────────────────────────── */}
      <View style={[s.bottomBar, { paddingBottom: Math.max(insets.bottom, 16) }]}>
        <Pressable
          onPress={() => navigation.goBack()}
          style={({ pressed }) => [s.btnGhost, pressed && s.pressed]}
        >
          <Text style={s.btnGhostText}>Kembali</Text>
        </Pressable>

        <Pressable
          onPress={() => {
            // TODO: navigate to invitation picker / apply-theme flow
            navigation.goBack();
          }}
          style={({ pressed }) => [s.btnPrimary, pressed && s.pressed]}
        >
          <MaterialCommunityIcons name="check-circle-outline" size={18} color="#FFFFFF" />
          <Text style={s.btnPrimaryText}>Gunakan Tema</Text>
        </Pressable>
      </View>
    </View>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme']) {
  return StyleSheet.create({
    root: {
      flex: 1,
      backgroundColor: t.background,
    },

    topBar: {
      flexDirection: 'row',
      alignItems: 'center',
      paddingHorizontal: 14,
      paddingBottom: 10,
      borderBottomWidth: 1,
      borderBottomColor: t.outlineVariant,
      backgroundColor: t.surface,
      gap: 10,
    },
    iconBtn: {
      width: 38,
      height: 38,
      borderRadius: 12,
      alignItems: 'center',
      justifyContent: 'center',
      backgroundColor: t.surfaceContainerLow,
    },
    titleWrap: {
      flex: 1,
      flexDirection: 'row',
      alignItems: 'center',
      gap: 8,
    },
    topBarTitle: {
      fontFamily: F.heading,
      fontSize: 16,
      color: t.onSurface,
      flexShrink: 1,
    },
    premiumPill: {
      backgroundColor: '#D93723',
      borderRadius: 6,
      paddingHorizontal: 7,
      paddingVertical: 3,
    },
    premiumPillText: {
      fontFamily: F.labelBold,
      fontSize: 8,
      letterSpacing: 0.5,
      color: '#FFFFFF',
    },

    browserWrap: {
      flex: 1,
      overflow: 'hidden',
    },

    bottomBar: {
      flexDirection: 'row',
      gap: 12,
      paddingHorizontal: 20,
      paddingTop: 14,
      borderTopWidth: 1,
      borderTopColor: t.outlineVariant,
      backgroundColor: t.surface,
    },
    btnGhost: {
      flex: 1,
      height: 52,
      borderRadius: 999,
      borderWidth: 1.5,
      borderColor: t.outlineVariant,
      alignItems: 'center',
      justifyContent: 'center',
    },
    btnGhostText: {
      fontFamily: F.labelBold,
      fontSize: 14,
      color: t.onSurfaceVariant,
    },
    btnPrimary: {
      flex: 2,
      height: 52,
      borderRadius: 999,
      backgroundColor: t.primary,
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 8,
      shadowColor: t.primary,
      shadowOpacity: 0.3,
      shadowRadius: 10,
      shadowOffset: { width: 0, height: 5 },
      elevation: Platform.OS === 'android' ? 6 : 0,
    },
    btnPrimaryText: {
      fontFamily: F.labelBold,
      fontSize: 15,
      color: '#FFFFFF',
    },

    pressed: { opacity: 0.82, transform: [{ scale: 0.97 }] },
  });
}
