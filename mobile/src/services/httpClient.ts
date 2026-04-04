import { env } from '../config/env';

export type HttpMethod = 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE';

export interface HttpRequestOptions extends Omit<RequestInit, 'method' | 'body'> {
  method?: HttpMethod;
  body?: FormData | object;
}

export async function httpRequest<T>(
  path: string,
  options: HttpRequestOptions = {}
): Promise<T> {
  const { method = 'GET', body, headers, ...rest } = options;

  const requestHeaders: Record<string, string> = {
    Accept: 'application/json',
  };

  if (headers instanceof Headers) {
    headers.forEach((value, key) => {
      requestHeaders[key] = value;
    });
  } else if (Array.isArray(headers)) {
    for (const [key, value] of headers) {
      requestHeaders[key] = value;
    }
  } else if (headers) {
    Object.assign(requestHeaders, headers);
  }

  const requestBody = body instanceof FormData ? body : body ? JSON.stringify(body) : undefined;

  if (body && !(body instanceof FormData)) {
    requestHeaders['Content-Type'] = 'application/json';
  }

  const response = await fetch(`${env.apiBaseUrl}${path}`, {
    method,
    headers: requestHeaders,
    body: requestBody,
    ...rest,
  });

  if (!response.ok) {
    const errorText = await response.text();
    throw new Error(`HTTP ${response.status} ${response.statusText}: ${errorText}`);
  }

  if (response.status === 204) {
    return undefined as T;
  }

  const contentType = response.headers.get('content-type') ?? '';

  if (contentType.includes('application/json')) {
    return (await response.json()) as T;
  }

  return (await response.text()) as T;
}
