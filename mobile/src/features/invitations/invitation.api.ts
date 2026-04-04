import { httpRequest } from '../../services/httpClient';
import type {
  InvitationStatsResponse,
  InvitationWishesResponse,
  SubmitRsvpPayload,
  SubmitWishPayload,
} from './invitation.types';

function toInvitationBasePath(invitationIdentifier: string) {
  return `/api/invitations/${encodeURIComponent(invitationIdentifier)}`;
}

function toQueryString(params: Record<string, string | number | undefined>) {
  const searchParams = new URLSearchParams();

  Object.entries(params).forEach(([key, value]) => {
    if (value === undefined) {
      return;
    }

    searchParams.set(key, String(value));
  });

  const serialized = searchParams.toString();

  return serialized ? `?${serialized}` : '';
}

export function getInvitationStats(invitationIdentifier: string) {
  return httpRequest<InvitationStatsResponse>(
    `${toInvitationBasePath(invitationIdentifier)}/stats`
  );
}

export function getInvitationWishes(
  invitationIdentifier: string,
  options: { limit?: number; offset?: number } = {}
) {
  const query = toQueryString({
    limit: options.limit,
    offset: options.offset,
  });

  return httpRequest<InvitationWishesResponse>(
    `${toInvitationBasePath(invitationIdentifier)}/wishes${query}`
  );
}

export function submitRsvp(
  invitationIdentifier: string,
  payload: SubmitRsvpPayload
) {
  return httpRequest<{ success: boolean; message: string }>(
    `${toInvitationBasePath(invitationIdentifier)}/rsvp`,
    {
      method: 'POST',
      body: payload,
    }
  );
}

export function submitWish(
  invitationIdentifier: string,
  payload: SubmitWishPayload
) {
  return httpRequest<{ success: boolean; message: string }>(
    `${toInvitationBasePath(invitationIdentifier)}/wishes`,
    {
      method: 'POST',
      body: payload,
    }
  );
}
