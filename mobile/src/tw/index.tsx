import React from 'react';
import {
  Animated,
  Pressable as RNPressable,
  ScrollView as RNScrollView,
  StyleSheet,
  Text as RNText,
  TextInput as RNTextInput,
  TouchableHighlight as RNTouchableHighlight,
  View as RNView,
} from 'react-native';
import {
  useCssElement,
  useNativeVariable as useFunctionalVariable,
} from 'react-native-css';

type ClassNameProp = {
  className?: string;
};

type ContentContainerClassNameProp = {
  contentContainerClassName?: string;
};

type ContentClassNameProp = {
  contentClassName?: string;
};

export const useCSSVariable =
  process.env.EXPO_OS !== 'web'
    ? useFunctionalVariable
    : (variable: string) => `var(${variable})`;

export type ViewProps = React.ComponentProps<typeof RNView> & ClassNameProp;

export const View = (props: ViewProps) => {
  return useCssElement(RNView, props, { className: 'style' });
};

View.displayName = 'CSS(View)';

export type TextProps = React.ComponentProps<typeof RNText> & ClassNameProp;

export const Text = (props: TextProps) => {
  return useCssElement(RNText, props, { className: 'style' });
};

Text.displayName = 'CSS(Text)';

export type ScrollViewProps = React.ComponentProps<typeof RNScrollView> &
  ClassNameProp &
  ContentContainerClassNameProp;

export const ScrollView = (props: ScrollViewProps) => {
  return useCssElement(RNScrollView, props, {
    className: 'style',
    contentContainerClassName: 'contentContainerStyle',
  });
};

ScrollView.displayName = 'CSS(ScrollView)';

export type PressableProps = React.ComponentProps<typeof RNPressable> & ClassNameProp;

export const Pressable = (props: PressableProps) => {
  return useCssElement(RNPressable, props, { className: 'style' });
};

Pressable.displayName = 'CSS(Pressable)';

export type TextInputProps = React.ComponentProps<typeof RNTextInput> & ClassNameProp;

export const TextInput = (props: TextInputProps) => {
  return useCssElement(RNTextInput, props, { className: 'style' });
};

TextInput.displayName = 'CSS(TextInput)';

export type AnimatedScrollViewProps = React.ComponentProps<typeof Animated.ScrollView> &
  ClassNameProp &
  ContentClassNameProp &
  ContentContainerClassNameProp;

export const AnimatedScrollView = (props: AnimatedScrollViewProps) => {
  return useCssElement(Animated.ScrollView, props, {
    className: 'style',
    contentClassName: 'contentContainerStyle',
    contentContainerClassName: 'contentContainerStyle',
  });
};

function InternalTouchableHighlight(props: React.ComponentProps<typeof RNTouchableHighlight>) {
  const flattenedStyle =
    (StyleSheet.flatten(props.style) || {}) as Record<string, unknown>;
  const { underlayColor, ...style } = flattenedStyle;

  return (
    <RNTouchableHighlight
      underlayColor={typeof underlayColor === 'string' ? underlayColor : undefined}
      {...props}
      style={style as React.ComponentProps<typeof RNTouchableHighlight>['style']}
    />
  );
}

export type TouchableHighlightProps = React.ComponentProps<typeof RNTouchableHighlight> &
  ClassNameProp;

export const TouchableHighlight = (props: TouchableHighlightProps) => {
  return useCssElement(InternalTouchableHighlight, props, { className: 'style' });
};

TouchableHighlight.displayName = 'CSS(TouchableHighlight)';
