/**
 * InAppBrowser — Native (iOS/Android)
 * Uses react-native-webview.
 */
import { View, ActivityIndicator, StyleSheet, Text } from 'react-native';
import { WebView } from 'react-native-webview';
import { MaterialCommunityIcons } from '@expo/vector-icons';

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

export function InAppBrowser({ uri, onLoadStart, onLoadEnd, onError, loading, hasError }: Props) {
  const { theme } = useAppTheme();

  if (hasError) {
    return (
      <View style={[s.center, { backgroundColor: theme.background }]}>
        <MaterialCommunityIcons name="wifi-off" size={52} color={theme.outline} />
        <Text style={[s.errorTitle, { color: theme.onSurface }]}>Gagal Memuat Demo</Text>
        <Text style={[s.errorSub, { color: theme.onSurfaceVariant }]}>Periksa koneksi, lalu tekan refresh.</Text>
      </View>
    );
  }

  return (
    <View style={s.root}>
      <WebView
        source={{ uri }}
        style={{ flex: 1, backgroundColor: theme.background }}
        onLoadStart={onLoadStart}
        onLoadEnd={onLoadEnd}
        onError={onError}
        javaScriptEnabled
        domStorageEnabled
        allowsInlineMediaPlayback
        overScrollMode="never"
      />
      {loading && (
        <View style={[s.overlay, { backgroundColor: theme.background }]}>
          <ActivityIndicator size="large" color={theme.primary} />
          <Text style={[s.loadText, { color: theme.onSurfaceVariant }]}>Memuat demo...</Text>
        </View>
      )}
    </View>
  );
}

const s = StyleSheet.create({
  root: { flex: 1, position: 'relative' },
  center: { flex: 1, alignItems: 'center', justifyContent: 'center', gap: 12, paddingHorizontal: 32 },
  overlay: {
    ...StyleSheet.absoluteFillObject,
    alignItems: 'center',
    justifyContent: 'center',
    gap: 12,
  },
  loadText: { fontFamily: F.body, fontSize: 14 },
  errorTitle: { fontFamily: F.display, fontSize: 20 },
  errorSub: { fontFamily: F.body, fontSize: 14, textAlign: 'center' },
});
