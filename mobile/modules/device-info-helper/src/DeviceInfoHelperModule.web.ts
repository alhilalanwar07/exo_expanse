import { NativeModule, registerWebModule } from 'expo';

import type { DeviceInfoSnapshot } from './DeviceInfoHelper.types';

function detectWebOs(userAgent: string): Pick<DeviceInfoSnapshot, 'osName' | 'osVersion'> {
  if (/Android/i.test(userAgent)) {
    const version = userAgent.match(/Android\s([\d.]+)/i)?.[1] ?? 'unknown';
    return { osName: 'Android', osVersion: version };
  }

  if (/iPhone|iPad|iPod/i.test(userAgent)) {
    const rawVersion = userAgent.match(/OS\s([\d_]+)/i)?.[1] ?? 'unknown';
    return { osName: 'iOS', osVersion: rawVersion.replace(/_/g, '.') };
  }

  if (/Windows/i.test(userAgent)) {
    return { osName: 'Windows', osVersion: 'unknown' };
  }

  if (/Mac OS X/i.test(userAgent)) {
    const rawVersion = userAgent.match(/Mac OS X\s([\d_]+)/i)?.[1] ?? 'unknown';
    return { osName: 'macOS', osVersion: rawVersion.replace(/_/g, '.') };
  }

  return { osName: 'Web', osVersion: 'unknown' };
}

function detectWebModel(userAgent: string, platform: string): string {
  if (/iPhone/i.test(userAgent)) {
    return 'iPhone';
  }

  if (/iPad/i.test(userAgent)) {
    return 'iPad';
  }

  if (/Android/i.test(userAgent)) {
    return 'Android Device';
  }

  if (platform && platform.trim()) {
    return platform.trim();
  }

  return 'Browser';
}

class DeviceInfoHelperModule extends NativeModule {
  getDeviceInfo(): DeviceInfoSnapshot {
    const nav = typeof navigator !== 'undefined' ? navigator : null;
    const userAgent = nav?.userAgent ?? '';
    const platform = nav?.platform ?? '';
    const model = detectWebModel(userAgent, platform);
    const osInfo = detectWebOs(userAgent);

    return {
      platform: 'web',
      brand: osInfo.osName === 'iOS' ? 'Apple' : 'Browser',
      model,
      osName: osInfo.osName,
      osVersion: osInfo.osVersion,
      suggestedAlias: model === 'Browser' ? 'Browser Owner' : `${model} Owner`,
    };
  }
}

export default registerWebModule(DeviceInfoHelperModule, 'DeviceInfoHelper');
