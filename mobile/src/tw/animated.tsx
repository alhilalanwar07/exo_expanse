import { Animated as RNAnimated } from 'react-native';

import { View } from './index';

export const Animated = {
  ...RNAnimated,
  View: RNAnimated.createAnimatedComponent(View),
};
