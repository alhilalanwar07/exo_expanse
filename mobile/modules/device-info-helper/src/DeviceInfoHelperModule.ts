import { requireOptionalNativeModule } from 'expo';
import { Platform } from 'react-native';

import type { DeviceInfoSnapshot } from './DeviceInfoHelper.types';

export type DeviceInfoHelperModuleType = {
  getDeviceInfo(): DeviceInfoSnapshot;
};

const fallbackModule: DeviceInfoHelperModuleType = {
  getDeviceInfo() {
    const platform = Platform.OS === 'ios' || Platform.OS === 'android' ? Platform.OS : 'web';
    const model = platform === 'ios' ? 'iPhone' : platform === 'android' ? 'Android Device' : 'Browser';

    return {
      platform,
      brand: platform === 'ios' ? 'Apple' : platform === 'android' ? 'Android' : 'Browser',
      model,
      osName: platform === 'ios' ? 'iOS' : platform === 'android' ? 'Android' : 'Web',
      osVersion: 'unknown',
      suggestedAlias: `${model} Owner`,
    };
  },
};

const nativeModule = requireOptionalNativeModule<DeviceInfoHelperModuleType>('DeviceInfoHelper');

export default nativeModule ?? fallbackModule;
