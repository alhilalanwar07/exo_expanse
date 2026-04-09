/**
 * Consistent navbar styling across all screens
 */
export const navbarConfig = {
  // Heights
  height: {
    normal: 64,
    compact: 56,
  },
  // Brand/Title
  brand: {
    fontSize: {
      normal: 18,
      compact: 16,
    },
    fontWeight: '800' as const,
    letterSpacing: 1.5,
    color: '#24162C',
  },
  // Icon button
  iconButton: {
    size: {
      normal: 36,
      compact: 32,
    },
    color: '#5B21B6',
    colorAlt: '#101828',
  },
  // Avatar button
  avatar: {
    size: {
      normal: 48,
      compact: 40,
    },
    color: '#1F2937',
    textColor: '#FFFFFF',
    fontSize: 13,
  },
  // Container spacing
  padding: {
    horizontal: 16,
  },
  // Border & background
  background: '#FFFFFF',
  borderColor: '#EDE7F3',
  borderWidth: 1,
  // Responsive breakpoint
  compactBreakpoint: 390,
};
