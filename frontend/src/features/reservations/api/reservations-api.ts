import axios from "axios";

const API_BASE = "/api/v1";

export type ReservationStatus =
  | "PENDING"
  | "READY"
  | "FULFILLED"
  | "CANCELLED"
  | "EXPIRED";

export interface Reservation {
  id: string;
  status: ReservationStatus;
  reservedAt: string;
  expiresAt?: string;
  processedAt?: string;
  notes?: string;
  userId: string;
  bookId: string;
  libraryBranchId: string;
  processedByUserId?: string;
  createdAt: string;
  updatedAt: string;
}

export interface ListReservationsResponse {
  data: Reservation[];
  meta: {
    total: number;
    page: number;
    limit: number;
    totalPages: number;
  };
}

export async function createReservation(bookId: string): Promise<Reservation> {
  const response = await axios.post(`${API_BASE}/reservations`, {
    bookId,
  });
  return response.data;
}

export async function getMyReservations(
  page?: number,
  limit?: number,
): Promise<ListReservationsResponse> {
  const response = await axios.get(`${API_BASE}/reservations/my`, {
    params: { page, limit },
  });
  return response.data;
}

export async function getReservationById(
  reservationId: string,
): Promise<Reservation> {
  const response = await axios.get(`${API_BASE}/reservations/${reservationId}`);
  return response.data;
}

export async function cancelReservation(
  reservationId: string,
): Promise<Reservation> {
  const response = await axios.patch(
    `${API_BASE}/reservations/${reservationId}/cancel`,
  );
  return response.data;
}

export async function listReservations(
  status?: ReservationStatus,
  page?: number,
  limit?: number,
  branchId?: string,
): Promise<ListReservationsResponse> {
  const response = await axios.get(`${API_BASE}/reservations`, {
    params: { status, page, limit, branchId },
  });
  return response.data;
}

export async function updateReservationStatus(
  reservationId: string,
  status: ReservationStatus,
  notes?: string,
): Promise<Reservation> {
  const response = await axios.patch(
    `${API_BASE}/reservations/${reservationId}/status`,
    { status, notes },
  );
  return response.data;
}
