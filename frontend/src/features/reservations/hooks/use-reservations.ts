import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import {
  createReservation,
  getMyReservations,
  listReservations,
  cancelReservation,
  updateReservationStatus,
  type ReservationStatus,
} from "../api/reservations-api";

export function useMyReservations(page?: number, limit?: number) {
  return useQuery({
    queryKey: ["my-reservations", page, limit],
    queryFn: () => getMyReservations(page, limit),
  });
}

export function useLibrarianReservationQueue(
  status?: ReservationStatus,
  page?: number,
  limit?: number,
  branchId?: string,
) {
  return useQuery({
    queryKey: ["reservations", status, page, limit, branchId],
    queryFn: () => listReservations(status, page, limit, branchId),
  });
}

export function useCreateReservation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: createReservation,
    onSuccess: () => {
      // Invalidate queries to refresh data
      queryClient.invalidateQueries({
        queryKey: ["my-reservations"],
      });
    },
  });
}

export function useCancelReservation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: cancelReservation,
    onSuccess: () => {
      queryClient.invalidateQueries({
        queryKey: ["my-reservations"],
      });
    },
  });
}

export function useUpdateReservationStatus() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({
      reservationId,
      status,
      notes,
    }: {
      reservationId: string;
      status: ReservationStatus;
      notes?: string;
    }) => updateReservationStatus(reservationId, status, notes),
    onSuccess: () => {
      queryClient.invalidateQueries({
        queryKey: ["reservations"],
      });
    },
  });
}
