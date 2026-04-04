export type AttendanceStatus = 'confirmed' | 'declined';

export interface InvitationStatsResponse {
  total_wishes: number;
  total_confirmed: number;
  total_guests: number;
}

export interface InvitationWish {
  id: number;
  name: string;
  message: string;
  initial: string;
  time: string;
  attendance_status: AttendanceStatus | null;
}

export interface InvitationWishesResponse {
  wishes: InvitationWish[];
  total: number;
}

export interface SubmitRsvpPayload {
  name: string;
  status: AttendanceStatus;
  pax: number;
}

export interface SubmitWishPayload {
  name: string;
  message: string;
}
