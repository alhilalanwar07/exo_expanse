/**
 * Centralized typography tokens for "The Digital Atelier" design system.
 *
 * Display / Headlines / Brand → Plus Jakarta Sans (editorial authority)
 * Body / Labels / UI          → Manrope (readable, friendly)
 *
 * Usage:
 *   fontFamily: F.display    → Plus Jakarta Sans ExtraBold (headlines)
 *   fontFamily: F.heading    → Plus Jakarta Sans Bold (section titles)
 *   fontFamily: F.subheading → Plus Jakarta Sans SemiBold
 *   fontFamily: F.uiMedium    → Plus Jakarta Sans Medium (short UI headings)
 *   fontFamily: F.body       → Manrope Regular
 *   fontFamily: F.bodyMedium → Manrope Medium
 *   fontFamily: F.label      → Manrope SemiBold (chips, badges, uppercase labels)
 *   fontFamily: F.labelBold  → Manrope Bold (button text, links, strong labels)
 *
 * Harmony rule:
 * - Plus Jakarta Sans for screen titles and section headings.
 * - Manrope for body copy and interactive UI text (labels/buttons/links).
 */
export const F = {
  // Plus Jakarta Sans — display/headline
  display:    'PlusJakartaSans_ExtraBold',  // weight 800
  heading:    'PlusJakartaSans_Bold',       // weight 700
  subheading: 'PlusJakartaSans_SemiBold',  // weight 600
  uiMedium:   'PlusJakartaSans_Medium',    // weight 500
  // Manrope — body/label
  body:       'Manrope_Regular',           // weight 400
  bodyMedium: 'Manrope_Medium',           // weight 500
  label:      'Manrope_SemiBold',         // weight 600
  labelBold:  'Manrope_Bold',             // weight 700
} as const;
