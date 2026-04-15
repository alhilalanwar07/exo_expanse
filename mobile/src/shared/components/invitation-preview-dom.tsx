"use dom";

import { useEffect, useMemo, useRef, useState } from 'react';

type InvitationPreviewDomProps = {
  uri: string;
  title: string;
  isPremium: boolean;
  reloadKey?: number;
  onPreviewLoadStart?: () => Promise<void> | void;
  onPreviewLoadEnd?: () => Promise<void> | void;
  onPreviewLoadError?: (message: string) => Promise<void> | void;
  dom: import('expo/dom').DOMProps;
};

const LOAD_TIMEOUT_MS = 15000;  // hard error timeout
const OVERLAY_MAX_VISIBLE_MS = 3500; // overlay dismissed even if onLoad never fires (cross-origin iframes)

function toHostLabel(rawUri: string): string {
  try {
    const url = new URL(rawUri);
    return url.host;
  } catch {
    return 'preview';
  }
}

export default function InvitationPreviewDom({
  uri,
  title,
  isPremium,
  reloadKey,
  onPreviewLoadStart,
  onPreviewLoadEnd,
  onPreviewLoadError,
}: InvitationPreviewDomProps) {
  const [internalLoading, setInternalLoading] = useState(true);
  const [fadingOut, setFadingOut] = useState(false);
  const [internalError, setInternalError] = useState<string | null>(null);
  const timeoutRef = useRef<ReturnType<typeof setTimeout> | null>(null);
  const fadeTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);
  const overlayTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);
  const dismissedRef = useRef(false);

  // Always-fresh callback refs — updating these never re-triggers effects
  const onLoadStartRef = useRef(onPreviewLoadStart);
  const onLoadEndRef = useRef(onPreviewLoadEnd);
  const onLoadErrorRef = useRef(onPreviewLoadError);
  onLoadStartRef.current = onPreviewLoadStart;
  onLoadEndRef.current = onPreviewLoadEnd;
  onLoadErrorRef.current = onPreviewLoadError;

  const hostLabel = useMemo(() => toHostLabel(uri), [uri]);

  /** Trigger a smooth fade-out then remove the overlay from DOM */
  const dismissOverlay = (callback?: () => void) => {
    if (dismissedRef.current) return; // prevent double-dismiss → infinite loop
    dismissedRef.current = true;
    // Cancel both timers to stop any pending re-triggers
    if (overlayTimerRef.current) { clearTimeout(overlayTimerRef.current); overlayTimerRef.current = null; }
    if (timeoutRef.current) { clearTimeout(timeoutRef.current); timeoutRef.current = null; }
    setFadingOut(true);
    if (fadeTimerRef.current) clearTimeout(fadeTimerRef.current);
    fadeTimerRef.current = setTimeout(() => {
      setInternalLoading(false);
      setFadingOut(false);
      callback?.();
    }, 420);
  };

  // Only re-run when url/reloadKey changes — callbacks accessed via refs (never in deps)
  useEffect(() => {
    setInternalLoading(true);
    setFadingOut(false);
    setInternalError(null);
    dismissedRef.current = false; // reset guard for new load

    void onLoadStartRef.current?.();

    // Clear any previous timers
    if (timeoutRef.current) clearTimeout(timeoutRef.current);
    if (overlayTimerRef.current) clearTimeout(overlayTimerRef.current);
    if (fadeTimerRef.current) clearTimeout(fadeTimerRef.current);

    // Guaranteed overlay dismiss — cross-origin iframes may never fire onLoad
    overlayTimerRef.current = setTimeout(() => {
      dismissOverlay(() => void onLoadEndRef.current?.());
    }, OVERLAY_MAX_VISIBLE_MS);

    // Hard error timeout if iframe stays blank too long
    timeoutRef.current = setTimeout(() => {
      if (dismissedRef.current) return;
      dismissedRef.current = true;
      setInternalLoading(false);
      setInternalError('Preview membutuhkan waktu terlalu lama untuk dimuat.');
      void onLoadErrorRef.current?.('timeout');
    }, LOAD_TIMEOUT_MS);

    return () => {
      if (timeoutRef.current) clearTimeout(timeoutRef.current);
      if (overlayTimerRef.current) clearTimeout(overlayTimerRef.current);
      if (fadeTimerRef.current) clearTimeout(fadeTimerRef.current);
    };
  }, [uri, reloadKey]); // callbacks via refs — intentionally excluded

  const handleLoad = () => {
    setInternalError(null);
    dismissOverlay(() => void onLoadEndRef.current?.());
  };

  const handleError = () => {
    const message = 'Tidak dapat memuat preview undangan.';
    setInternalError(message);
    dismissOverlay(() => void onLoadErrorRef.current?.(message));
  };

  return (
    <div style={styles.root}>
      <style>{`
        @keyframes invitation-preview-spin {
          from { transform: rotate(0deg); }
          to { transform: rotate(360deg); }
        }
        @keyframes overlay-fade-in {
          from { opacity: 0; }
          to { opacity: 1; }
        }
      `}</style>

      <div style={styles.canvasWrap}>
        <iframe
          key={`${uri}-${reloadKey ?? 0}`}
          src={uri}
          title={`Preview ${title}`}
          onLoad={handleLoad}
          onError={handleError}
          allow="autoplay"
          style={styles.iframe}
        />
      </div>

      <div style={styles.metaBar}>
        <div style={styles.metaLeft}>
          <span style={styles.metaTitle}>{title}</span>
          <span style={styles.metaHost}>{hostLabel}</span>
        </div>
        {isPremium ? <span style={styles.premiumPill}>PREMIUM</span> : null}
      </div>

      {internalLoading ? (
        <div style={{
          ...styles.overlay,
          opacity: fadingOut ? 0 : 1,
          transition: fadingOut ? 'opacity 0.4s ease' : 'none',
          animation: fadingOut ? 'none' : 'overlay-fade-in 0.2s ease',
        }}>
          <div style={styles.spinner} />
          <p style={styles.overlayText}>Memuat preview undangan...</p>
        </div>
      ) : null}

      {internalError ? (
        <div style={styles.errorBanner}>
          <span style={styles.errorText}>{internalError}</span>
        </div>
      ) : null}
    </div>
  );
}

const styles: Record<string, React.CSSProperties> = {
  root: {
    position: 'relative',
    width: '100%',
    height: '100%',
    background: '#0E0B15',
    overflow: 'hidden',
  },
  canvasWrap: {
    position: 'absolute',
    inset: 0,
    padding: 12,
    boxSizing: 'border-box',
    background:
      'radial-gradient(circle at 18% 16%, rgba(124, 58, 237, 0.35), transparent 45%), radial-gradient(circle at 86% 80%, rgba(14, 165, 233, 0.24), transparent 40%), #0E0B15',
  },
  iframe: {
    width: '100%',
    height: '100%',
    border: 'none',
    borderRadius: 16,
    background: '#FFFFFF',
    boxShadow: '0 18px 40px rgba(0, 0, 0, 0.35)',
  },
  metaBar: {
    position: 'absolute',
    top: 20,
    left: 20,
    right: 20,
    display: 'flex',
    justifyContent: 'space-between',
    alignItems: 'center',
    gap: 10,
    padding: '8px 10px',
    borderRadius: 12,
    background: 'rgba(17, 12, 28, 0.66)',
    backdropFilter: 'blur(8px)',
    border: '1px solid rgba(255, 255, 255, 0.12)',
    pointerEvents: 'none',
  },
  metaLeft: {
    minWidth: 0,
    display: 'flex',
    flexDirection: 'column',
    gap: 1,
  },
  metaTitle: {
    color: '#FFFFFF',
    fontFamily: 'Manrope, system-ui, sans-serif',
    fontWeight: 700,
    fontSize: 12,
    lineHeight: '16px',
    whiteSpace: 'nowrap',
    textOverflow: 'ellipsis',
    overflow: 'hidden',
  },
  metaHost: {
    color: 'rgba(255, 255, 255, 0.72)',
    fontFamily: 'Manrope, system-ui, sans-serif',
    fontWeight: 500,
    fontSize: 10,
    lineHeight: '14px',
    letterSpacing: '0.02em',
  },
  premiumPill: {
    padding: '4px 8px',
    borderRadius: 999,
    background: '#D93723',
    color: '#FFFFFF',
    fontFamily: 'Plus Jakarta Sans, system-ui, sans-serif',
    fontWeight: 700,
    fontSize: 9,
    letterSpacing: '0.08em',
  },
  overlay: {
    position: 'absolute',
    inset: 0,
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 12,
    background: 'rgba(14, 11, 21, 0.62)',
    backdropFilter: 'blur(3px)',
  },
  spinner: {
    width: 32,
    height: 32,
    borderRadius: 999,
    border: '3px solid rgba(255,255,255,0.32)',
    borderTopColor: '#C084FC',
    animation: 'invitation-preview-spin 0.9s linear infinite',
  },
  overlayText: {
    margin: 0,
    color: '#FFFFFF',
    fontFamily: 'Manrope, system-ui, sans-serif',
    fontSize: 13,
    fontWeight: 600,
  },
  errorBanner: {
    position: 'absolute',
    left: 16,
    right: 16,
    bottom: 16,
    borderRadius: 10,
    padding: '10px 12px',
    background: 'rgba(186, 26, 26, 0.92)',
    border: '1px solid rgba(255, 255, 255, 0.22)',
  },
  errorText: {
    color: '#FFFFFF',
    fontFamily: 'Manrope, system-ui, sans-serif',
    fontSize: 12,
    fontWeight: 600,
  },
};
