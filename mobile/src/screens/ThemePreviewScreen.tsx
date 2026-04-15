/**
 * ThemePreviewScreen
 *
 * Full-screen in-app demo viewer. Uses:
 *  - react-native-webview  on iOS/Android  (InAppBrowser — native WebView)
 *  - "use dom" <iframe>    on Expo Web      (InvitationPreviewDom)
 *
 * On Android the "use dom" approach nests an <iframe> inside a WebView which
 * fails to render cross-origin content — so native platforms use the direct
 * react-native-webview component instead.
 */

import { useEffect, useState } from 'react';
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
import { useAuth } from '../features/auth/AuthContext';
import type { RootStackParamList } from '../navigation/types';

// Only import the DOM component on web — on native it would be unused
const InvitationPreviewDom =
  Platform.OS === 'web'
    ? require('../shared/components/invitation-preview-dom').default
    : null;

type NavProp = NativeStackNavigationProp<RootStackParamList, 'ThemePreview'>;
type RoutePropType = RouteProp<RootStackParamList, 'ThemePreview'>;

export function ThemePreviewScreen() {
  const navigation = useNavigation<NavProp>();
  const route = useRoute<RoutePropType>();
  const { theme } = useAppTheme();
  const { session } = useAuth();
  const insets = useSafeAreaInsets();

  const { name, previewUrl, isPremium } = route.params;

  const [loading, setLoading] = useState(true);
  const [hasError, setHasError] = useState(false);
  // Key to force-remount the browser on refresh
  const [reloadKey, setReloadKey] = useState(0);
  const [isChromeVisible, setIsChromeVisible] = useState(true);

  const s = makeStyles(theme);

  useEffect(() => {
    if (loading || hasError) {
      setIsChromeVisible(true);
      return;
    }

    const timerId = setTimeout(() => {
      setIsChromeVisible(false);
    }, 2200);

    return () => {
      clearTimeout(timerId);
    };
  }, [loading, hasError, reloadKey]);

  const handleRefresh = () => {
    setHasError(false);
    setLoading(true);
    setIsChromeVisible(true);
    setReloadKey((k) => k + 1);
  };

  const handleApplyTheme = () => {
    if (!session) {
      navigation.navigate('AuthChoice', {
        intent: 'theme',
      });

      return;
    }

    navigation.navigate('ApplyTheme', {
      themeId: route.params.id,
      themeName: name,
      isPremium,
    });
  };

  const canApplyTheme = !loading && !hasError;

  const isNative = Platform.OS !== 'web';

  return (
    <View style={s.root}>
      <View style={s.browserWrap}>
        {isNative ? (
          /* Native (Android/iOS): use react-native-webview directly */
          <InAppBrowser
            key={reloadKey}
            uri={previewUrl}
            loading={loading}
            hasError={hasError}
            onLoadStart={() => {
              setLoading(true);
              setHasError(false);
            }}
            onLoadEnd={() => {
              setLoading(false);
            }}
            onError={() => {
              setLoading(false);
              setHasError(true);
            }}
          />
        ) : (
          /* Web: use "use dom" iframe component */
          InvitationPreviewDom && (
            <InvitationPreviewDom
              key={reloadKey}
              uri={previewUrl}
              title={name}
              isPremium={isPremium}
              reloadKey={reloadKey}
              onPreviewLoadStart={() => {
                setLoading(true);
                setHasError(false);
              }}
              onPreviewLoadEnd={() => {
                setLoading(false);
              }}
              onPreviewLoadError={() => {
                setLoading(false);
                setHasError(true);
              }}
              dom={{
                scrollEnabled: false,
                contentInsetAdjustmentBehavior: 'never',
                style: { flex: 1 },
              }}
            />
          )
        )}
      </View>

      {isChromeVisible ? (
        <>
          <SafeAreaView style={s.topChrome} edges={['top']}>
            <View style={s.topChromeInner}>
              <Pressable
                onPress={() => navigation.goBack()}
                style={({ pressed }) => [s.iconBtn, pressed && s.pressed]}
                hitSlop={10}
              >
                <Ionicons name="arrow-back" size={22} color={theme.onSurface} />
              </Pressable>

              <View style={s.titleBadge}>
                <Text style={s.topBarTitle} numberOfLines={1}>{name}</Text>
                {isPremium ? (
                  <View style={s.premiumPill}>
                    <Text style={s.premiumPillText}>PREMIUM</Text>
                  </View>
                ) : null}
              </View>

              <View style={s.topActions}>
                <Pressable
                  onPress={handleRefresh}
                  style={({ pressed }) => [s.iconBtn, pressed && s.pressed]}
                  hitSlop={10}
                >
                  <Ionicons name="refresh-outline" size={20} color={theme.onSurfaceVariant} />
                </Pressable>

                <Pressable
                  onPress={() => setIsChromeVisible(false)}
                  style={({ pressed }) => [s.iconBtn, pressed && s.pressed]}
                  hitSlop={10}
                >
                  <Ionicons name="eye-off-outline" size={19} color={theme.onSurfaceVariant} />
                </Pressable>
              </View>
            </View>
          </SafeAreaView>

          <View style={[s.bottomChrome, { paddingBottom: Math.max(insets.bottom, 16) }]}> 
            <Text style={s.bottomHint}>
              {hasError
                ? 'Preview gagal dimuat. Coba muat ulang terlebih dahulu.'
                : 'Sudah cocok? Terapkan tema ini ke undangan Anda.'}
            </Text>

            <View style={s.bottomActions}>
              <Pressable
                onPress={hasError ? handleRefresh : () => navigation.goBack()}
                style={({ pressed }) => [s.btnGhost, pressed && s.pressed]}
              >
                <Ionicons
                  name={hasError ? 'refresh-outline' : 'arrow-back-outline'}
                  size={18}
                  color={theme.onSurfaceVariant}
                />
                <Text style={s.btnGhostText}>{hasError ? 'Muat Ulang' : 'Kembali'}</Text>
              </Pressable>

              <Pressable
                onPress={handleApplyTheme}
                disabled={!canApplyTheme}
                style={({ pressed }) => [
                  s.btnPrimary,
                  pressed && canApplyTheme && s.pressed,
                  !canApplyTheme && s.btnPrimaryDisabled,
                ]}
              >
                <MaterialCommunityIcons name="check-circle-outline" size={18} color="#FFFFFF" />
                <Text style={s.btnPrimaryText}>
                  {loading ? 'Memuat Preview...' : 'Gunakan Tema'}
                </Text>
              </Pressable>
            </View>
          </View>
        </>
      ) : (
        <SafeAreaView style={s.revealChromeWrap} edges={['top']}>
          <Pressable
            onPress={() => setIsChromeVisible(true)}
            style={({ pressed }) => [s.revealChromeBtn, pressed && s.pressed]}
          >
            <Ionicons name="options-outline" size={16} color={theme.onSurfaceVariant} />
            <Text style={s.revealChromeText}>Kontrol</Text>
          </Pressable>
        </SafeAreaView>
      )}
    </View>
  );
}

function makeStyles(t: ReturnType<typeof useAppTheme>['theme']) {
  return StyleSheet.create({
    root: {
      flex: 1,
      backgroundColor: t.background,
    },

    browserWrap: {
      flex: 1,
      overflow: 'hidden',
    },

    topChrome: {
      position: 'absolute',
      top: 0,
      left: 0,
      right: 0,
      paddingHorizontal: 14,
      zIndex: 20,
    },
    topChromeInner: {
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'space-between',
      paddingHorizontal: 10,
      paddingVertical: 8,
      borderRadius: 16,
      backgroundColor: t.surface,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      shadowColor: '#000000',
      shadowOpacity: 0.12,
      shadowRadius: 18,
      shadowOffset: { width: 0, height: 8 },
      elevation: Platform.OS === 'android' ? 6 : 0,
      gap: 10,
    },
    iconBtn: {
      width: 36,
      height: 36,
      borderRadius: 10,
      alignItems: 'center',
      justifyContent: 'center',
      backgroundColor: t.surfaceContainerLow,
      borderWidth: 1,
      borderColor: t.outlineVariant,
    },
    titleBadge: {
      flex: 1,
      flexDirection: 'row',
      alignItems: 'center',
      justifyContent: 'center',
      gap: 8,
      paddingHorizontal: 6,
    },
    topActions: {
      flexDirection: 'row',
      alignItems: 'center',
      gap: 8,
    },
    topBarTitle: {
      fontFamily: F.labelBold,
      fontSize: 13,
      color: t.onSurface,
      flexShrink: 1,
    },
    premiumPill: {
      backgroundColor: '#D93723',
      borderRadius: 999,
      paddingHorizontal: 8,
      paddingVertical: 4,
    },
    premiumPillText: {
      fontFamily: F.labelBold,
      fontSize: 8,
      letterSpacing: 0.6,
      color: '#FFFFFF',
    },

    bottomChrome: {
      position: 'absolute',
      left: 0,
      right: 0,
      bottom: 0,
      paddingHorizontal: 14,
      paddingTop: 12,
      gap: 10,
      backgroundColor: t.surface,
      borderTopWidth: 1,
      borderTopColor: t.outlineVariant,
      zIndex: 20,
    },
    bottomHint: {
      fontFamily: F.body,
      fontSize: 12,
      color: t.onSurfaceVariant,
      textAlign: 'center',
      paddingHorizontal: 4,
    },
    bottomActions: {
      flexDirection: 'row',
      gap: 10,
    },
    btnGhost: {
      flex: 1,
      height: 50,
      borderRadius: 999,
      borderWidth: 1.5,
      borderColor: t.outlineVariant,
      alignItems: 'center',
      justifyContent: 'center',
      flexDirection: 'row',
      gap: 6,
      backgroundColor: t.surface,
    },
    btnGhostText: {
      fontFamily: F.labelBold,
      fontSize: 14,
      color: t.onSurfaceVariant,
    },
    btnPrimary: {
      flex: 1.7,
      height: 50,
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
    btnPrimaryDisabled: {
      opacity: 0.55,
    },
    btnPrimaryText: {
      fontFamily: F.labelBold,
      fontSize: 14,
      color: '#FFFFFF',
    },

    revealChromeWrap: {
      position: 'absolute',
      top: 0,
      right: 0,
      paddingHorizontal: 14,
      zIndex: 20,
      alignItems: 'flex-end',
    },
    revealChromeBtn: {
      height: 34,
      borderRadius: 999,
      paddingHorizontal: 12,
      backgroundColor: t.surface,
      borderWidth: 1,
      borderColor: t.outlineVariant,
      flexDirection: 'row',
      alignItems: 'center',
      gap: 6,
      shadowColor: '#000000',
      shadowOpacity: 0.1,
      shadowRadius: 12,
      shadowOffset: { width: 0, height: 4 },
      elevation: Platform.OS === 'android' ? 4 : 0,
    },
    revealChromeText: {
      fontFamily: F.label,
      fontSize: 12,
      color: t.onSurfaceVariant,
    },

    pressed: { opacity: 0.82, transform: [{ scale: 0.97 }] },
  });
}
