/**
 * InAppBrowser — Web (Expo Web / browser)
 * Uses an <iframe> — react-native-webview is NOT supported on web.
 * Metro bundler auto-selects this file over InAppBrowser.tsx on web.
 */
import { View, ActivityIndicator, StyleSheet, Text } from 'react-native';
import { MaterialCommunityIcons } from '@expo/vector-icons';
import { useState, useEffect, useRef } from 'react';

import { useAppTheme } from '../theme/index';
import { F } from '../theme/fonts';

type Props = {
  uri: string;
  onLoadStart?: () => void;
  onLoadEnd?: () => void;
  onError?: () => void;
  loading: boolean;
  hasError: boolean;
};

export function InAppBrowser({ uri, onLoadEnd, onError, hasError }: Props) {
  const { theme } = useAppTheme();
  const [internalLoading, setInternalLoading] = useState(true);
  const timeoutRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  // Fallback: if onLoad doesn't fire within 8 s, hide the overlay anyway
  // (happens when the page uses JS-heavy rendering like Livewire).
  useEffect(() => {
    timeoutRef.current = setTimeout(() => {
      setInternalLoading(false);
      onLoadEnd?.();
    }, 8000);

    return () => {
      if (timeoutRef.current) clearTimeout(timeoutRef.current);
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [uri]);

  const handleLoad = () => {
    if (timeoutRef.current) clearTimeout(timeoutRef.current);
    setInternalLoading(false);
    onLoadEnd?.();
  };

  const handleError = () => {
    if (timeoutRef.current) clearTimeout(timeoutRef.current);
    setInternalLoading(false);
    onError?.();
  };

  if (hasError) {
    return (
      <View style={[s.center, { backgroundColor: theme.background }]}>
        <MaterialCommunityIcons name="wifi-off" size={52} color={theme.outline} />
        <Text style={[s.errorTitle, { color: theme.onSurface }]}>Gagal Memuat Demo</Text>
        <Text style={[s.errorSub, { color: theme.onSurfaceVariant }]}>
          Pastikan server berjalan dan dapat diakses.
        </Text>
      </View>
    );
  }

  return (
    <View style={s.root}>
      {/* iframe — valid DOM element on Expo Web (React Native Web renders to the real DOM) */}
      <iframe
        src={uri}
        onLoad={handleLoad}
        onError={handleError}
        title="Pratinjau Tema"
        allow="autoplay"
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          width: '100%',
          height: '100%',
          border: 'none',
          // Make sure the iframe receives pointer events
          pointerEvents: internalLoading ? 'none' : 'auto',
        }}
      />

      {/* Loading overlay — sits on top, then removed once iframe loads */}
      {internalLoading && (
        <View style={[s.overlay, { backgroundColor: theme.background }]}>
          <ActivityIndicator size="large" color={theme.primary} />
          <Text style={[s.loadText, { color: theme.onSurfaceVariant }]}>Memuat demo...</Text>
        </View>
      )}
    </View>
  );
}

const s = StyleSheet.create({
  root: {
    flex: 1,
    position: 'relative',
  },
  center: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    gap: 12,
    paddingHorizontal: 32,
  },
  overlay: {
    ...StyleSheet.absoluteFillObject,
    alignItems: 'center',
    justifyContent: 'center',
    gap: 12,
    zIndex: 10,
  },
  loadText: { fontFamily: F.body, fontSize: 14 },
  errorTitle: { fontFamily: F.display, fontSize: 20 },
  errorSub: { fontFamily: F.body, fontSize: 14, textAlign: 'center' },
});
