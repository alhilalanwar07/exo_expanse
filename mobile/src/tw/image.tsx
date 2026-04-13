import { Image as ExpoImage } from 'expo-image';
import React from 'react';
import { StyleSheet } from 'react-native';
import { useCssElement } from 'react-native-css';

type ClassNameProp = {
  className?: string;
};

function CssImage(props: React.ComponentProps<typeof ExpoImage>) {
  const flattenedStyle = (StyleSheet.flatten(props.style) || {}) as Record<string, unknown>;
  const { objectFit, objectPosition, ...style } = flattenedStyle;

  return (
    <ExpoImage
      {...props}
      contentFit={
        (typeof objectFit === 'string'
          ? objectFit
          : props.contentFit) as React.ComponentProps<typeof ExpoImage>['contentFit']
      }
      contentPosition={
        (typeof objectPosition !== 'undefined'
          ? objectPosition
          : props.contentPosition) as React.ComponentProps<typeof ExpoImage>['contentPosition']
      }
      source={typeof props.source === 'string' ? { uri: props.source } : props.source}
      style={style as React.ComponentProps<typeof ExpoImage>['style']}
    />
  );
}

export type ImageProps = React.ComponentProps<typeof CssImage> & ClassNameProp;

export const Image = (props: ImageProps) => {
  return useCssElement(CssImage, props, { className: 'style' });
};

Image.displayName = 'CSS(Image)';
