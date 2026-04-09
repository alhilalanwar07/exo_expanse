# Auth Screens Inconsistencies Analysis

## Critical Findings

### 🎨 1. BACKGROUND COLOR THEME (BLOCKING ISSUE)
**Severity: CRITICAL** - These screens use fundamentally different themes

| Screen | Background | Theme |
|--------|-----------|-------|
| **AuthChoiceScreen** | `#050B18` | Dark theme (uses colors.amber, colors.accent) |
| **LoginScreen** | `#F5F0F8` | Light theme (uses hex colors) |
| **RegisterScreen** | `#F5F0F8` | Light theme (uses hex colors) |
| **ConnectDeviceScreen** | `colors.background` | Appears dark (uses colors.textPrimary, colors.danger) |

**Impact**: Users see completely different visual themes moving between auth screens.

---

## 2. SPACING & PADDING INCONSISTENCIES

### Content Container Padding:
```
AuthChoiceScreen:
- Standard: paddingHorizontal: 16px, paddingTop: 20px
- Compact: paddingHorizontal: 14px, paddingTop: 16px

LoginScreen:
- Standard: paddingHorizontal: 22px, paddingTop: 22px
- Compact: paddingHorizontal: 18px, paddingTop: 18px

RegisterScreen:
- Standard: paddingHorizontal: 20px, paddingTop: 24px
- Compact: paddingHorizontal: 16px, paddingTop: 20px

ConnectDeviceScreen:
- Standard: paddingHorizontal: 16px, paddingTop: 20px (through scrollContent)
- Compact: paddingHorizontal: 14px, paddingTop: 16px
```

### Gap Between Sections:
```
AuthChoiceScreen.wrapper: gap: 20px (compact: 16px)
LoginScreen.headerSection: NO explicit gap between title/subtitle
RegisterScreen.headerSection: gap: 8px (compact: 6px)
ConnectDeviceScreen.heroSection: gap: 8px (compact: 6px)
```

### Form Group Spacing:
```
AuthChoiceScreen: N/A (no form groups)
LoginScreen: gap: 18px between form groups
RegisterScreen: gap: 14px (compact: 12px)
ConnectDeviceScreen: gap: 8px margin/gap
```

---

## 3. TYPOGRAPHY INCONSISTENCIES

### Title Sizes:
```
AuthChoiceScreen:
- fontSize: 30px, fontWeight: 800
- Compact: fontSize: 26px

LoginScreen:
- fontSize: 54px, fontWeight: 800
- Compact: fontSize: 42px

RegisterScreen:
- fontSize: 48px, fontWeight: 800, lineHeight: 56px, letterSpacing: -0.5
- Compact: fontSize: 36px, lineHeight: 44px, letterSpacing: -0.3

ConnectDeviceScreen:
- fontSize: 30px, fontWeight: 800
- Compact: fontSize: 26px
```
**Delta**: 24px difference between LoginScreen and others (54px vs 30px)

### Subtitle Sizes:
```
AuthChoiceScreen: 14px, lineHeight: 21px
LoginScreen: 25px, lineHeight: 36px (!!! extremely large)
RegisterScreen: 16px, lineHeight: 24px, fontWeight: 500
ConnectDeviceScreen: 14px, lineHeight: 20px
```

### Label Capitalization Patterns:
```
LoginScreen:
- "EMAIL ADDRESS" (UPPERCASE with letterSpacing: 0.8)
- "PASSWORD" (UPPERCASE with letterSpacing: 0.8)

RegisterScreen:
- "NAMA LENGKAP" (UPPERCASE with letterSpacing: 1)
- "EMAIL ADDRESS" (UPPERCASE with letterSpacing: 1)
- "PASSWORD" (UPPERCASE with letterSpacing: 1)
- "KONFIRMASI PASSWORD" (UPPERCASE with letterSpacing: 1)

ConnectDeviceScreen:
- "Kode Akses" (Title Case, no letterSpacing)
- "Nama Perangkat (Opsional)" (Title Case, no letterSpacing)

Eyebrow/Badge text:
- ConnectDeviceScreen: "Secure Pairing" (Title case)
- AuthChoiceScreen badge: "Exo Expanse" (Title case)
```

### Label Font Sizes:
```
LoginScreen: 14px, fontWeight: 700, letterSpacing: 0.8
RegisterScreen: 12px, fontWeight: 700, letterSpacing: 1 (compact: 11px)
ConnectDeviceScreen: 13px, fontWeight: 700 (compact: 12px)
```

---

## 4. COMPONENT STRUCTURE DIFFERENCES

### Input Container Wrapping:

**LoginScreen** (Simple approach):
```tsx
<View style={[styles.inputContainer, emailFocused && styles.inputContainerFocused]}>
  <TextInput style={styles.input} />
</View>
```
- No icon wrapper
- min height: 54px
- paddingHorizontal: 14px

**RegisterScreen** (Icon included):
```tsx
<View style={[styles.inputContainer, nameFocused && styles.inputContainerFocused]}>
  <MaterialCommunityIcons name="account-outline" size={20} />
  <TextInput style={styles.input} />
</View>
```
- Icon included (size: 20px)
- min height: 52px
- paddingHorizontal: 12px
- gap: 8px between icon and input

**ConnectDeviceScreen** (Named "inputShell"):
```tsx
<View style={[styles.inputShell, codeFocused && styles.inputShellFocused]}>
  <MaterialCommunityIcons name="key-outline" size={19} />
  <TextInput style={styles.input} />
</View>
```
- Icon present (size: 19px)
- minHeight: 48px
- paddingHorizontal: 12px
- gap: 10px

### Button Styling:

**AuthChoiceScreen** (Row layout with chevron):
```tsx
styles.primaryButton: {
  flexDirection: 'row',
  backgroundColor: colors.accent,
  borderRadius: 14,
  paddingVertical: 13,
  paddingHorizontal: 15,
  gap: 12,
}
```

**LoginScreen** (Centered pill button):
```tsx
styles.primaryButton: {
  height: 56,
  borderRadius: 28,
  backgroundColor: '#5B21B6',
  alignItems: 'center',
  justifyContent: 'center',
  elevation: 6,
}
```

**RegisterScreen** (Icon + text):
```tsx
styles.primaryButton: {
  height: 54,
  borderRadius: 12,
  backgroundColor: '#5B21B6',
  flexDirection: 'row',
  alignItems: 'center',
  justifyContent: 'center',
  gap: 8,
  elevation: 6,
}
```

**ConnectDeviceScreen** (Icon + text):
```tsx
styles.button: {
  height: 48,
  borderRadius: 14,
  backgroundColor: colors.accent,
  flexDirection: 'row',
  alignItems: 'center',
  justifyContent: 'center',
  gap: 8,
  elevation: 7,
}
```

---

## 5. BORDER RADIUS INCONSISTENCIES

### Input Fields:
```
LoginScreen: 14px
RegisterScreen: 12px (compact: 10px)
ConnectDeviceScreen: 12px
AuthChoiceScreen: N/A (no inputs)
```

### Cards/Containers:
```
AuthChoiceScreen card: 20px (compact: 16px)
ConnectDeviceScreen card: 20px (compact: 16px)
LoginScreen: N/A (no card)
RegisterScreen: N/A (no card)
```

### Buttons:
```
AuthChoiceScreen buttons: 14px
LoginScreen primary: 28px (pill shape)
RegisterScreen primary: 12px (compact: 10px)
ConnectDeviceScreen button: 14px
```

### Other:
```
LoginScreen social buttons: 16px
RegisterScreen success button: 12px (compact: 10px)
ConnectDeviceScreen info card: 12px
RegisterScreen notice box: 12px (compact: 10px)
```

---

## 6. COLOR USAGE PATTERNS

### Background Colors:
```
AuthChoiceScreen: #050B18 (dark)
LoginScreen: #F5F0F8 (light lavender)
RegisterScreen: #F5F0F8 (light lavender)
ConnectDeviceScreen: colors.background (theme var)
```

### Text Colors:
```
AuthChoiceScreen titles: colors.textPrimary
LoginScreen titles: #211135 (dark purple)
RegisterScreen titles: #211135 (dark purple)
ConnectDeviceScreen titles: colors.textPrimary

LoginScreen subtitles: #4D445A (medium gray)
RegisterScreen subtitles: #5B5365 (medium gray)
```

### Input Background Colors:
```
LoginScreen: #E6D4F0 (light purple), focused: #EADBF3
RegisterScreen: #E6D4F0 (light purple), focused: #EADBF3
ConnectDeviceScreen: colors.surfaceMuted, focused: rgba(15, 23, 42, 0.96)
```

### Label Colors:
```
LoginScreen: #5A5268
RegisterScreen: #5A5268
ConnectDeviceScreen: colors.textSecondary (theme var)
```

### Primary Button Colors:
```
LoginScreen: #5B21B6 (purple)
RegisterScreen: #5B21B6 (purple)
ConnectDeviceScreen: colors.accent (theme var)
AuthChoiceScreen: colors.accent (theme var)
```

---

## 7. CARD/CONTAINER STYLING

### AuthChoiceScreen Card:
```
borderRadius: 20px (compact: 16px)
borderWidth: 1
borderColor: colors.border
padding: 18px (compact: 14px)
gap: 12px (compact: 10px)
shadowColor: #020617
shadowOpacity: 0.45
shadowRadius: 16
elevation: 10
```

### ConnectDeviceScreen Card:
```
borderRadius: 20px (compact: 16px)
borderWidth: 1
borderColor: colors.border
padding: 18px (compact: 14px)
gap: 14px (compact: 11px)
shadowColor: #020617
shadowOpacity: 0.42
shadowRadius: 16
elevation: 9
```

### ConnectDeviceScreen Info Card (Different):
```
borderRadius: 12px
borderWidth: 1
borderColor: rgba(56, 189, 248, 0.28)
backgroundColor: rgba(56, 189, 248, 0.09)
padding: 12px
gap: 10px
```

### LoginScreen/RegisterScreen (No dedicated card):
- Use `formSection` with gap between inputs
- FormSection in LoginScreen: gap: 18px
- FormSection in RegisterScreen: gap: 14px (compact: 12px)

---

## 8. SHADOW/ELEVATION PATTERNS

### AuthChoiceScreen:
```
Card elevation: 10, shadowOpacity: 0.45, shadowRadius: 16
Button (primary): Part of card, no individual shadow
```

### LoginScreen:
```
Primary button: elevation: 6, shadowOpacity: 0.25, shadowRadius: 10
```

### RegisterScreen:
```
Primary button: elevation: 6, shadowOpacity: 0.28, shadowRadius: 10
Success button: No shadow
```

### ConnectDeviceScreen:
```
Button: elevation: 7, shadowOpacity: 0.32, shadowRadius: 10
```

---

## 9. BUTTON PRESSED STATE CONSISTENCY

### All Screens Use Similar Pattern:
```
buttonPressed: {
  opacity: 0.88 (or 0.9),
  transform: [{ scale: 0.98 (or 0.985) }],
}
```

**Minor Delta**:
- AuthChoiceScreen: opacity 0.9, scale 0.985
- LoginScreen: opacity 0.88, scale 0.985
- RegisterScreen: opacity 0.88, scale 0.98
- ConnectDeviceScreen: opacity 0.88, scale 0.98

---

## 10. DIVIDER/SEPARATOR STYLING

### LoginScreen Separator:
```
separatorRow: {
  flexDirection: 'row',
  alignItems: 'center',
  gap: 12,
  marginTop: 16,
}
separatorLine: {
  flex: 1,
  height: 1,
  backgroundColor: '#DCD5E5',
}
separatorText: {
  color: '#777086',
  fontSize: 14,
  letterSpacing: 1,
  fontWeight: '500',
}
```

### AuthChoiceScreen Divider:
```
divider: {
  flexDirection: 'row',
  alignItems: 'center',
  gap: 10,
  marginVertical: 2,
}
dividerLine: {
  flex: 1,
  height: 1,
  backgroundColor: colors.border,
}
dividerText: {
  color: colors.textMuted,
  fontSize: 12,
  fontWeight: '600',
}
```

**Differences**: marginVertical vs marginTop, gap 12 vs 10, fontSize 14 vs 12, letterSpacing 1 vs 0

---

## SUMMARY TABLE: Spacing Distribution

| Element | AuthChoice | Login | Register | Connect |
|---------|-----------|-------|----------|---------|
| Container padding (h/v) | 16/20 | 22/22 | 20/24 | 16/20 |
| Section gap | 20 | - | 8 | 8 |
| Form group gap | - | 18 | 14 | 8 |
| Card padding | 18 | - | - | 18 |
| Button height | 13v | 56px | 54px | 48px |
| Input height | - | 54px | 52px | 48px |
| Label font size | - | 14px | 12px | 13px |
| Title font size | 30px | 54px | 48px | 30px |

---

## WHAT NEEDS TO BE UNIFIED

### Priority 1 (Must Fix):
1. ✅ **Background theme** - Choose dark or light consistently
2. ✅ **Input field styling** - Unified height, border-radius, padding
3. ✅ **Button styling** - Unified height, border-radius, shadow
4. ✅ **Typography scale** - Title, subtitle, label sizes
5. ✅ **Spacing/gap values** - Consistent throughout

### Priority 2 (Should Fix):
6. ✅ **Label capitalization** - UPPERCASE or Title Case
7. ✅ **Form group structure** - Icon placement, gap
8. ✅ **Card borders** - Border radius consistency
9. ✅ **Color palette** - Use theme colors vs hex

### Priority 3 (Nice to Have):
10. ✅ **Shadow/elevation** - Standardize shadow values
11. ✅ **Divider styling** - Consistent separator patterns
12. ✅ **Button pressed state** - Fine-tune opacity/scale

