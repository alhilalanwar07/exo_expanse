const fallbackApiBaseUrl = 'http://localhost:8000';

const rawApiBaseUrl = process.env.EXPO_PUBLIC_API_BASE_URL;

export const env = {
  apiBaseUrl:
    rawApiBaseUrl && rawApiBaseUrl.trim().length > 0
      ? rawApiBaseUrl.trim()
      : fallbackApiBaseUrl,
};

export const isUsingFallbackApiBaseUrl = env.apiBaseUrl === fallbackApiBaseUrl;
