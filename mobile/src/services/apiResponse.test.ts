import { describe, expect, it } from '@jest/globals';

import { ApiEndpointError, toApiEndpointError } from './apiResponse';
import { HttpClientError } from './httpClient';

describe('toApiEndpointError', () => {
  it('maps network error with custom network message', () => {
    const httpError = new HttpClientError({
      message: 'Network down',
      status: 0,
      code: 'NETWORK_ERROR',
    });

    const mapped = toApiEndpointError(httpError, {
      defaultMessage: 'Default error',
      networkMessage: 'Tidak dapat terhubung ke server.',
    });

    expect(mapped).toBeInstanceOf(ApiEndpointError);
    expect(mapped.code).toBe('NETWORK_ERROR');
    expect(mapped.message).toBe('Tidak dapat terhubung ke server.');
  });

  it('extracts first validation field error from backend payload', () => {
    const httpError = new HttpClientError({
      message: 'HTTP 422',
      status: 422,
      code: 'HTTP_ERROR',
      details: {
        message: 'Validation failed',
        errors: {
          email: ['Email wajib diisi.'],
        },
      },
    });

    const mapped = toApiEndpointError(httpError, {
      defaultMessage: 'Default error',
    });

    expect(mapped.code).toBe('HTTP_ERROR');
    expect(mapped.message).toBe('Email wajib diisi.');
    expect(mapped.fieldErrors?.email).toEqual(['Email wajib diisi.']);
  });

  it('maps unknown error to default message', () => {
    const mapped = toApiEndpointError('unexpected', {
      defaultMessage: 'Terjadi kesalahan.',
    });

    expect(mapped.code).toBe('UNKNOWN');
    expect(mapped.message).toBe('Terjadi kesalahan.');
  });
});
