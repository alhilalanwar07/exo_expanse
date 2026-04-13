import AsyncStorage from '@react-native-async-storage/async-storage';
import * as SecureStore from 'expo-secure-store';
import { Platform } from 'react-native';

import type { AuthSession } from './auth.types';

const AUTH_SESSION_KEY = 'exo.mobile.auth.session';
const AUTH_SESSION_FALLBACK_KEY = 'exo.mobile.auth.session.fallback';

const IOS_SECURE_STORE_OPTIONS: SecureStore.SecureStoreOptions | undefined =
  Platform.OS === 'ios'
    ? { keychainAccessible: SecureStore.AFTER_FIRST_UNLOCK_THIS_DEVICE_ONLY }
    : undefined;

function isValidDateString(value: unknown): value is string {
  return typeof value === 'string' && Number.isFinite(Date.parse(value));
}

function isAuthSession(value: unknown): value is AuthSession {
  if (!value || typeof value !== 'object') {
    return false;
  }

  const session = value as Partial<AuthSession>;

  return (
    typeof session.workspaceId === 'string'
    && typeof session.workspaceLabel === 'string'
    && typeof session.ownerName === 'string'
    && (typeof session.deviceAlias === 'string' || session.deviceAlias === null)
    && typeof session.accessToken === 'string'
    && typeof session.refreshToken === 'string'
    && isValidDateString(session.connectedAt)
    && isValidDateString(session.expiresAt)
  );
}

async function getAsyncValue(key: string): Promise<string | null> {
  try {
    return await AsyncStorage.getItem(key);
  } catch {
    return null;
  }
}

async function setAsyncValue(key: string, value: string): Promise<boolean> {
  try {
    await AsyncStorage.setItem(key, value);
    return true;
  } catch {
    return false;
  }
}

async function removeAsyncValue(key: string): Promise<void> {
  try {
    await AsyncStorage.removeItem(key);
  } catch {
    // Keep silent: cleanup should not break auth flow.
  }
}

async function getSecureValue(key: string): Promise<string | null> {
  try {
    return await SecureStore.getItemAsync(key);
  } catch {
    return null;
  }
}

async function setSecureValue(key: string, value: string): Promise<boolean> {
  try {
    await SecureStore.setItemAsync(key, value, IOS_SECURE_STORE_OPTIONS);
    return true;
  } catch {
    return false;
  }
}

async function removeSecureValue(key: string): Promise<void> {
  try {
    await SecureStore.deleteItemAsync(key);
  } catch {
    // Keep silent: cleanup should not break auth flow.
  }
}

async function removeNativeFallbackCopies(): Promise<void> {
  await removeAsyncValue(AUTH_SESSION_FALLBACK_KEY);
  await removeAsyncValue(AUTH_SESSION_KEY);
}

async function getStoredValue(): Promise<string | null> {
  if (Platform.OS === 'web') {
    return getAsyncValue(AUTH_SESSION_KEY);
  }

  const secureValue = await getSecureValue(AUTH_SESSION_KEY);

  if (secureValue) {
    // If secure storage is available, remove plaintext fallback copies.
    await removeNativeFallbackCopies();
    return secureValue;
  }

  // Read dedicated fallback key first, then legacy key for backwards compatibility.
  const fallbackValue =
    (await getAsyncValue(AUTH_SESSION_FALLBACK_KEY))
    ?? (await getAsyncValue(AUTH_SESSION_KEY));

  if (!fallbackValue) {
    return null;
  }

  const didMigrateToSecureStore = await setSecureValue(AUTH_SESSION_KEY, fallbackValue);

  if (didMigrateToSecureStore) {
    await removeNativeFallbackCopies();
    return fallbackValue;
  }

  // Keep fallback in dedicated key and remove legacy copy to limit duplication.
  await setAsyncValue(AUTH_SESSION_FALLBACK_KEY, fallbackValue);
  await removeAsyncValue(AUTH_SESSION_KEY);

  return fallbackValue;
}

async function setStoredValue(value: string): Promise<void> {
  if (Platform.OS === 'web') {
    await setAsyncValue(AUTH_SESSION_KEY, value);

    return;
  }

  const didWriteSecureStore = await setSecureValue(AUTH_SESSION_KEY, value);

  if (didWriteSecureStore) {
    await removeNativeFallbackCopies();
    return;
  }

  await setAsyncValue(AUTH_SESSION_FALLBACK_KEY, value);
  await removeAsyncValue(AUTH_SESSION_KEY);
}

async function removeStoredValue(): Promise<void> {
  if (Platform.OS === 'web') {
    await removeAsyncValue(AUTH_SESSION_KEY);

    return;
  }

  await removeSecureValue(AUTH_SESSION_KEY);
  await removeNativeFallbackCopies();
}

export async function readAuthSession(): Promise<AuthSession | null> {
  const raw = await getStoredValue();

  if (!raw) {
    return null;
  }

  try {
    const parsed = JSON.parse(raw) as unknown;

    if (!isAuthSession(parsed)) {
      await removeStoredValue();
      return null;
    }

    return parsed;
  } catch {
    await removeStoredValue();
    return null;
  }
}

export async function writeAuthSession(session: AuthSession): Promise<void> {
  await setStoredValue(JSON.stringify(session));
}

export async function clearAuthSession(): Promise<void> {
  await removeStoredValue();
}
