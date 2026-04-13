export type DeviceInfoPlatform = 'ios' | 'android' | 'web';

export type DeviceInfoSnapshot = {
  platform: DeviceInfoPlatform;
  brand: string;
  model: string;
  osName: string;
  osVersion: string;
  suggestedAlias: string;
};
