"use dom";

import { useCallback, useEffect, useMemo, useRef, useState } from 'react';

type RichEditorPayload = {
  html: string;
  text: string;
  wordCount: number;
};

type SaveResult = {
  success?: boolean;
  message?: string;
};

type InvitationRichEditorDomProps = {
  title?: string;
  initialHtml?: string;
  placeholder?: string;
  onChangeHtml?: (payload: RichEditorPayload) => Promise<void> | void;
  onRequestSave?: (payload: RichEditorPayload) => Promise<SaveResult | void> | SaveResult | void;
  dom: import('expo/dom').DOMProps;
};

const DEFAULT_PLACEHOLDER = 'Tulis konten undangan di sini...';

function toText(html: string): string {
  const parser = document.createElement('div');
  parser.innerHTML = html;

  return (parser.textContent ?? '').replace(/\s+/g, ' ').trim();
}

function toPayload(html: string): RichEditorPayload {
  const text = toText(html);

  return {
    html,
    text,
    wordCount: text ? text.split(/\s+/).length : 0,
  };
}

export default function InvitationRichEditorDom({
  title,
  initialHtml,
  placeholder,
  onChangeHtml,
  onRequestSave,
}: InvitationRichEditorDomProps) {
  const editorRef = useRef<HTMLDivElement | null>(null);
  const onChangeRef = useRef(onChangeHtml);
  const onSaveRef = useRef(onRequestSave);

  const [isSaving, setIsSaving] = useState(false);
  const [statusMessage, setStatusMessage] = useState<string | null>(null);
  const [wordCount, setWordCount] = useState(0);

  useEffect(() => {
    onChangeRef.current = onChangeHtml;
  }, [onChangeHtml]);

  useEffect(() => {
    onSaveRef.current = onRequestSave;
  }, [onRequestSave]);

  useEffect(() => {
    const editor = editorRef.current;

    if (!editor) {
      return;
    }

    editor.innerHTML = initialHtml && initialHtml.trim() ? initialHtml : '<p></p>';

    const payload = toPayload(editor.innerHTML);
    setWordCount(payload.wordCount);
    void onChangeRef.current?.(payload);
  }, [initialHtml]);

  const emitChange = useCallback(() => {
    const editor = editorRef.current;

    if (!editor) {
      return;
    }

    const payload = toPayload(editor.innerHTML);
    setWordCount(payload.wordCount);
    setStatusMessage(null);
    void onChangeRef.current?.(payload);
  }, []);

  const applyCommand = useCallback((command: string, value?: string) => {
    const editor = editorRef.current;

    if (!editor) {
      return;
    }

    editor.focus();
    document.execCommand(command, false, value);
    emitChange();
  }, [emitChange]);

  const handleSave = useCallback(async () => {
    const editor = editorRef.current;

    if (!editor || isSaving) {
      return;
    }

    const payload = toPayload(editor.innerHTML);

    setIsSaving(true);
    setStatusMessage('Menyimpan draft...');

    try {
      const result = await onSaveRef.current?.(payload);
      const message = result?.message ?? 'Draft konten berhasil disimpan.';
      setStatusMessage(message);
    } catch (error) {
      const message = error instanceof Error ? error.message : 'Gagal menyimpan draft konten.';
      setStatusMessage(message);
    } finally {
      setIsSaving(false);
    }
  }, [isSaving]);

  const headerTitle = useMemo(() => {
    if (title && title.trim()) {
      return title.trim();
    }

    return 'Konten Undangan';
  }, [title]);

  return (
    <div style={styles.root}>
      <style>{`
        .invite-rich-editor {
          min-height: 320px;
          height: 100%;
          width: 100%;
          outline: none;
          border-radius: 14px;
          border: 1px solid rgba(255, 255, 255, 0.18);
          background: rgba(255, 255, 255, 0.97);
          color: #151515;
          padding: 14px;
          box-sizing: border-box;
          font-family: Manrope, system-ui, sans-serif;
          font-size: 14px;
          line-height: 1.7;
        }

        .invite-rich-editor:focus {
          border-color: rgba(79, 70, 229, 0.45);
          box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.16);
        }

        .invite-rich-editor:empty:before {
          content: attr(data-placeholder);
          color: rgba(15, 23, 42, 0.46);
          pointer-events: none;
        }

        .invite-rich-editor h2,
        .invite-rich-editor h3 {
          margin: 0.2em 0 0.45em;
          line-height: 1.35;
          color: #111827;
        }

        .invite-rich-editor p {
          margin: 0.35em 0;
        }

        .invite-rich-editor ul,
        .invite-rich-editor ol {
          margin: 0.4em 0;
          padding-left: 1.3em;
        }
      `}</style>

      <div style={styles.shell}>
        <div style={styles.headRow}>
          <div style={styles.titleWrap}>
            <span style={styles.eyebrow}>Rich Editor</span>
            <span style={styles.title}>{headerTitle}</span>
          </div>

          <div style={styles.wordBadge}>{wordCount} kata</div>
        </div>

        <div style={styles.toolbar}>
          <button type="button" style={styles.toolButton} onClick={() => applyCommand('bold')}>
            B
          </button>
          <button type="button" style={styles.toolButton} onClick={() => applyCommand('italic')}>
            I
          </button>
          <button type="button" style={styles.toolButton} onClick={() => applyCommand('underline')}>
            U
          </button>
          <button type="button" style={styles.toolButton} onClick={() => applyCommand('formatBlock', 'H2')}>
            H2
          </button>
          <button type="button" style={styles.toolButton} onClick={() => applyCommand('formatBlock', 'P')}>
            P
          </button>
          <button type="button" style={styles.toolButton} onClick={() => applyCommand('insertUnorderedList')}>
            • List
          </button>
          <button type="button" style={styles.toolButton} onClick={() => applyCommand('insertOrderedList')}>
            1. List
          </button>
        </div>

        <div style={styles.editorCanvas}>
          <div
            ref={editorRef}
            contentEditable
            suppressContentEditableWarning
            className="invite-rich-editor"
            data-placeholder={placeholder ?? DEFAULT_PLACEHOLDER}
            onInput={emitChange}
            onBlur={emitChange}
          />
        </div>

        <div style={styles.footer}>
          <span style={styles.footerHint}>Konten otomatis sinkron ke layar native.</span>
          <button
            type="button"
            style={{
              ...styles.saveButton,
              opacity: isSaving ? 0.75 : 1,
              cursor: isSaving ? 'default' : 'pointer',
            }}
            onClick={handleSave}
            disabled={isSaving}
          >
            {isSaving ? 'Menyimpan...' : 'Simpan Draft'}
          </button>
        </div>

        {statusMessage ? <p style={styles.statusText}>{statusMessage}</p> : null}
      </div>
    </div>
  );
}

const styles: Record<string, React.CSSProperties> = {
  root: {
    width: '100%',
    height: '100%',
    background:
      'radial-gradient(circle at 15% 15%, rgba(14, 165, 233, 0.18), transparent 42%), radial-gradient(circle at 85% 85%, rgba(251, 146, 60, 0.2), transparent 40%), #0F172A',
    padding: 12,
    boxSizing: 'border-box',
  },
  shell: {
    height: '100%',
    display: 'flex',
    flexDirection: 'column',
    gap: 10,
    borderRadius: 16,
    padding: 12,
    boxSizing: 'border-box',
    background: 'rgba(15, 23, 42, 0.78)',
    border: '1px solid rgba(148, 163, 184, 0.28)',
  },
  headRow: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    gap: 8,
  },
  titleWrap: {
    minWidth: 0,
    display: 'flex',
    flexDirection: 'column',
    gap: 2,
  },
  eyebrow: {
    color: 'rgba(226, 232, 240, 0.8)',
    fontFamily: 'Manrope, system-ui, sans-serif',
    fontSize: 10,
    letterSpacing: '0.08em',
    textTransform: 'uppercase',
    fontWeight: 700,
  },
  title: {
    color: '#FFFFFF',
    fontFamily: 'Plus Jakarta Sans, system-ui, sans-serif',
    fontSize: 13,
    fontWeight: 700,
    whiteSpace: 'nowrap',
    textOverflow: 'ellipsis',
    overflow: 'hidden',
  },
  wordBadge: {
    borderRadius: 999,
    padding: '6px 10px',
    background: 'rgba(15, 23, 42, 0.6)',
    border: '1px solid rgba(226, 232, 240, 0.28)',
    color: '#E2E8F0',
    fontFamily: 'Manrope, system-ui, sans-serif',
    fontSize: 11,
    fontWeight: 700,
    whiteSpace: 'nowrap',
  },
  toolbar: {
    display: 'flex',
    flexWrap: 'wrap',
    gap: 6,
  },
  toolButton: {
    border: '1px solid rgba(148, 163, 184, 0.35)',
    borderRadius: 10,
    padding: '6px 10px',
    background: 'rgba(255, 255, 255, 0.08)',
    color: '#E2E8F0',
    fontFamily: 'Manrope, system-ui, sans-serif',
    fontSize: 12,
    fontWeight: 700,
    cursor: 'pointer',
  },
  editorCanvas: {
    flex: 1,
    minHeight: 0,
  },
  footer: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    gap: 10,
  },
  footerHint: {
    color: 'rgba(226, 232, 240, 0.82)',
    fontFamily: 'Manrope, system-ui, sans-serif',
    fontSize: 11,
  },
  saveButton: {
    border: 'none',
    borderRadius: 999,
    background: '#0EA5E9',
    color: '#FFFFFF',
    padding: '8px 14px',
    fontFamily: 'Plus Jakarta Sans, system-ui, sans-serif',
    fontSize: 12,
    fontWeight: 700,
  },
  statusText: {
    margin: 0,
    color: '#BAE6FD',
    fontFamily: 'Manrope, system-ui, sans-serif',
    fontSize: 11,
    fontWeight: 600,
  },
};