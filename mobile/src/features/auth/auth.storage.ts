import AsyncStorage from '@react-native-async-storage/async-storage';

import type { AuthSession } from './auth.types';

const AUTH_SESSION_KEY = 'exo.mobile.auth.session';

export async function readAuthSession(): Promise<AuthSession | null> {
  try {
    const raw = await AsyncStorage.getItem(AUTH_SESSION_KEY);

    if (!raw) {
      return null;
    }

    return JSON.parse(raw) as AuthSession;
  } catch {
    return null;
  }
}

export async function writeAuthSession(session: AuthSession): Promise<void> {
  await AsyncStorage.setItem(AUTH_SESSION_KEY, JSON.stringify(session));
}

export async function clearAuthSession(): Promise<void> {
  await AsyncStorage.removeItem(AUTH_SESSION_KEY);
}
