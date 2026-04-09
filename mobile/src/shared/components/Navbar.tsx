import { MaterialCommunityIcons } from '@expo/vector-icons';
import type { ReactNode } from 'react';
import { Pressable, StyleSheet, Text, useWindowDimensions, View } from 'react-native';

import { navbarConfig } from '../theme/navbar';
import { F } from '../theme/fonts';

type NavbarProps = {
  title?: string;
  leftElement?: ReactNode; // Custom left element (overrides back button)
  onBackPress?: () => void; // Show back button if provided
  rightElement?: ReactNode; // Custom right element (overrides avatar)
  avatarInitials?: string;
  onAvatarPress?: () => void;
};

export function Navbar({
  title = 'EXOINVITE',
  leftElement,
  onBackPress,
  rightElement,
  avatarInitials,
  onAvatarPress,
}: NavbarProps) {
  const { width } = useWindowDimensions();
  const isCompactLayout = width <= navbarConfig.compactBreakpoint;

  const navbarHeight = isCompactLayout
    ? navbarConfig.height.compact
    : navbarConfig.height.normal;

  const titleFontSize = isCompactLayout
    ? navbarConfig.brand.fontSize.compact
    : navbarConfig.brand.fontSize.normal;

  const iconButtonSize = isCompactLayout
    ? navbarConfig.iconButton.size.compact
    : navbarConfig.iconButton.size.normal;

  const avatarSize = isCompactLayout
    ? navbarConfig.avatar.size.compact
    : navbarConfig.avatar.size.normal;

  // Determine left element
  const renderLeftElement = () => {
    if (leftElement) return leftElement;

    if (onBackPress) {
      return (
        <Pressable
          onPress={onBackPress}
          style={({ pressed }) => [
            styles.iconButton,
            { width: iconButtonSize, height: iconButtonSize },
            pressed && styles.buttonPressed,
          ]}
        >
          <MaterialCommunityIcons
            name="arrow-left"
            size={isCompactLayout ? 22 : 24}
            color={navbarConfig.iconButton.color}
          />
        </Pressable>
      );
    }

    return <View style={{ width: iconButtonSize, height: iconButtonSize }} />;
  };

  // Determine right element
  const renderRightElement = () => {
    if (rightElement) return rightElement;

    if (avatarInitials && onAvatarPress) {
      return (
        <Pressable
          onPress={onAvatarPress}
          style={({ pressed }) => [
            styles.avatarButton,
            {
              width: avatarSize,
              height: avatarSize,
              borderRadius: avatarSize / 2,
            },
            pressed && styles.buttonPressed,
          ]}
        >
          <Text
            style={[
              styles.avatarText,
              {
                fontSize: isCompactLayout ? 11 : 13,
              },
            ]}
          >
            {avatarInitials}
          </Text>
        </Pressable>
      );
    }

    return <View style={{ width: iconButtonSize, height: iconButtonSize }} />;
  };

  return (
    <View
      style={[
        styles.navbar,
        {
          height: navbarHeight,
        },
      ]}
    >
      <View style={styles.container}>
        {/* Left section */}
        {renderLeftElement()}

        {/* Center section: title */}
        <Text
          style={[
            styles.title,
            {
              fontSize: titleFontSize,
              letterSpacing: isCompactLayout ? 0.8 : navbarConfig.brand.letterSpacing,
            },
          ]}
          numberOfLines={1}
        >
          {title}
        </Text>

        {/* Right section */}
        {renderRightElement()}
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  navbar: {
    backgroundColor: navbarConfig.background,
    borderBottomWidth: navbarConfig.borderWidth,
    borderBottomColor: navbarConfig.borderColor,
    justifyContent: 'center',
  },
  container: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: navbarConfig.padding.horizontal,
  },
  title: {
    flex: 1,
    textAlign: 'center',
    fontFamily: F.display,
    color: navbarConfig.brand.color,
    marginHorizontal: 8,
  },
  iconButton: {
    alignItems: 'center',
    justifyContent: 'center',
    borderRadius: 8,
  },
  avatarButton: {
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: navbarConfig.avatar.color,
  },
  avatarText: {
    color: navbarConfig.avatar.textColor,
    fontFamily: F.labelBold,
  },
  buttonPressed: {
    opacity: 0.88,
    transform: [{ scale: 0.97 }],
  },
});
