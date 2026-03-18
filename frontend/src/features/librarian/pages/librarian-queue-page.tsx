import { useState } from "react";
import {
  useLibrarianReservationQueue,
  useUpdateReservationStatus,
} from "@features/reservations/hooks/use-reservations";
import { type ReservationStatus } from "@features/reservations/api/reservations-api";
import { useI18n } from "@shared/i18n/use-i18n";
import { authStore } from "@shared/auth/auth-store";
import type { TranslationKey } from "@shared/i18n/dictionary";

const STATUS_COLORS: Record<
  ReservationStatus,
  { badge: string; label: TranslationKey }
> = {
  PENDING: {
    badge: "bg-yellow-50 text-yellow-800 border-yellow-100",
    label: "librarianQueueStatusPending",
  },
  READY: {
    badge: "bg-blue-50 text-blue-800 border-blue-100",
    label: "librarianQueueStatusReady",
  },
  FULFILLED: {
    badge: "bg-green-50 text-green-800 border-green-100",
    label: "librarianQueueStatusFulfilled",
  },
  CANCELLED: {
    badge: "bg-gray-50 text-gray-800 border-gray-100",
    label: "librarianQueueStatusCancelled",
  },
  EXPIRED: {
    badge: "bg-red-50 text-red-800 border-red-100",
    label: "reservationStatusExpired",
  },
};

export function LibrarianQueuePage() {
  const { t } = useI18n();
  const [page, setPage] = useState(1);
  const [statusFilter, setStatusFilter] = useState<
    ReservationStatus | undefined
  >(undefined);

  const { data, isLoading, error } = useLibrarianReservationQueue(
    statusFilter,
    page,
    20,
  );
  const updateStatusMutation = useUpdateReservationStatus();
  const [selectedNotes, setSelectedNotes] = useState<Record<string, string>>(
    {},
  );

  const handleStatusChange = async (
    reservationId: string,
    newStatus: ReservationStatus,
  ) => {
    try {
      const notes = selectedNotes[reservationId] || undefined;
      await updateStatusMutation.mutateAsync({
        reservationId,
        status: newStatus,
        notes,
      });
      setSelectedNotes((prev) => {
        const updated = { ...prev };
        delete updated[reservationId];
        return updated;
      });
    } catch (err) {
      console.error("Failed to update reservation status:", err);
    }
  };

  if (
    !authStore.isAuthenticated ||
    (authStore.role !== "LIBRARIAN" && authStore.role !== "ADMIN")
  ) {
    return (
      <div className="rounded-xl border border-red-100 bg-white p-8 text-center text-sm text-red-700">
        {t("librarianQueueAccessDenied")}
      </div>
    );
  }

  if (isLoading) {
    return (
      <div className="rounded-xl border border-blue-100 bg-white p-8 text-center text-sm text-slate-600">
        {t("catalogLoading")}
      </div>
    );
  }

  if (error) {
    return (
      <div className="rounded-xl border border-red-100 bg-white p-8 text-center text-sm text-red-700">
        {t("librarianQueueError")}
      </div>
    );
  }

  const reservations = data?.data || [];

  if (reservations.length === 0) {
    return (
      <div className="space-y-4">
        <div className="flex items-center justify-between">
          <h1 className="text-2xl font-semibold text-slate-900">
            {t("librarianQueueTitle")}
          </h1>
        </div>

        <div className="rounded-xl border border-blue-100 bg-white p-8 text-center">
          <p className="text-sm text-slate-600">{t("librarianQueueEmpty")}</p>
        </div>
      </div>
    );
  }

  const totalPages = data?.meta.totalPages || 1;

  return (
    <section className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-semibold text-slate-900">
          {t("librarianQueueTitle")}
        </h1>
        <select
          value={statusFilter || ""}
          onChange={(e) => {
            setStatusFilter((e.target.value as ReservationStatus) || undefined);
            setPage(1);
          }}
          className="rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-700"
        >
          <option value="">{t("librarianQueueFilterAll")}</option>
          <option value="PENDING">{t("librarianQueueStatusPending")}</option>
          <option value="READY">{t("librarianQueueStatusReady")}</option>
          <option value="FULFILLED">
            {t("librarianQueueStatusFulfilled")}
          </option>
          <option value="CANCELLED">
            {t("librarianQueueStatusCancelled")}
          </option>
        </select>
      </div>

      <div className="overflow-x-auto rounded-xl border border-blue-100 bg-white shadow-sm">
        <table className="w-full text-sm">
          <thead>
            <tr className="border-b border-blue-100 bg-blue-50">
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("librarianQueueColumnUser")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("librarianQueueColumnBook")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("librarianQueueColumnStatus")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("librarianQueueColumnDate")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("librarianQueueColumnActions")}
              </th>
            </tr>
          </thead>
          <tbody>
            {reservations.map((reservation) => {
              const statusConfig = STATUS_COLORS[reservation.status];
              const reservedDate = new Date(
                reservation.reservedAt,
              ).toLocaleDateString();

              return (
                <tr
                  key={reservation.id}
                  className="border-b border-slate-100 hover:bg-slate-50"
                >
                  <td className="px-4 py-3 text-slate-900">
                    <div className="font-medium">User Details</div>
                    <div className="text-xs text-slate-600">
                      {reservation.userId}
                    </div>
                  </td>
                  <td className="px-4 py-3 text-slate-900">
                    <div className="font-medium">Book</div>
                    <div className="text-xs text-slate-600">
                      {reservation.bookId}
                    </div>
                  </td>
                  <td className="px-4 py-3">
                    <span
                      className={`inline-block rounded-md border px-2 py-1 text-xs font-medium ${statusConfig.badge}`}
                    >
                      {t(statusConfig.label)}
                    </span>
                  </td>
                  <td className="px-4 py-3 text-slate-600">{reservedDate}</td>
                  <td className="px-4 py-3">
                    <div className="space-x-2 flex">
                      {reservation.status === "PENDING" && (
                        <>
                          <button
                            onClick={() =>
                              handleStatusChange(reservation.id, "READY")
                            }
                            disabled={updateStatusMutation.isPending}
                            className="inline-flex rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700 hover:bg-green-200 disabled:opacity-50"
                          >
                            {t("librarianQueueConfirm")}
                          </button>
                          <button
                            onClick={() =>
                              handleStatusChange(reservation.id, "CANCELLED")
                            }
                            disabled={updateStatusMutation.isPending}
                            className="inline-flex rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-200 disabled:opacity-50"
                          >
                            {t("librarianQueueReject")}
                          </button>
                        </>
                      )}
                      {reservation.status === "READY" && (
                        <button
                          onClick={() =>
                            handleStatusChange(reservation.id, "FULFILLED")
                          }
                          disabled={updateStatusMutation.isPending}
                          className="inline-flex rounded-md bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700 hover:bg-blue-200 disabled:opacity-50"
                        >
                          {t("librarianQueueMarkReady")}
                        </button>
                      )}
                      {!["PENDING", "READY"].includes(reservation.status) && (
                        <span className="text-xs text-slate-400">
                          {t("librarianQueueNoActions")}
                        </span>
                      )}
                    </div>
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>

      {totalPages > 1 && (
        <div className="flex justify-center gap-2">
          <button
            onClick={() => setPage(Math.max(1, page - 1))}
            disabled={page === 1}
            className="rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 disabled:opacity-50"
          >
            {t("catalogPrevious")}
          </button>
          <span className="flex items-center px-3 text-sm text-slate-600">
            {page} {t("catalogOf")} {totalPages}
          </span>
          <button
            onClick={() => setPage(Math.min(totalPages, page + 1))}
            disabled={page === totalPages}
            className="rounded-md border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 disabled:opacity-50"
          >
            {t("catalogNext")}
          </button>
        </div>
      )}
    </section>
  );
}
