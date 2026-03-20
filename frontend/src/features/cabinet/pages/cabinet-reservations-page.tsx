import { useState } from "react";
import {
  useMyReservations,
  useCancelReservation,
} from "@features/reservations/hooks/use-reservations";
import { type ReservationStatus } from "@features/reservations/api/reservations-api";
import { useI18n } from "@shared/i18n/use-i18n";
import type { TranslationKey } from "@shared/i18n/dictionary";

const STATUS_COLORS: Record<
  ReservationStatus,
  { badge: string; label: TranslationKey }
> = {
  PENDING: {
    badge: "bg-yellow-50 text-yellow-800 border-yellow-100",
    label: "cabinetReservationStatusPending",
  },
  READY: {
    badge: "bg-blue-50 text-blue-800 border-blue-100",
    label: "cabinetReservationStatusReady",
  },
  FULFILLED: {
    badge: "bg-green-50 text-green-800 border-green-100",
    label: "cabinetReservationStatusFulfilled",
  },
  CANCELLED: {
    badge: "bg-gray-50 text-gray-800 border-gray-100",
    label: "cabinetReservationStatusCancelled",
  },
  EXPIRED: {
    badge: "bg-red-50 text-red-800 border-red-100",
    label: "cabinetReservationStatusExpired",
  },
};

export function CabinetReservationsPage() {
  const { t } = useI18n();
  const [page, setPage] = useState(1);
  const { data, isLoading, error } = useMyReservations(page, 10);
  const cancelMutation = useCancelReservation();

  const handleCancel = async (reservationId: string) => {
    if (!confirm(t("cabinetReservationConfirmCancel"))) return;
    try {
      await cancelMutation.mutateAsync(reservationId);
    } catch (err) {
      console.error("Failed to cancel reservation:", err);
    }
  };

  if (isLoading) {
    return (
      <div className="app-empty-state text-sm text-slate-600">
        {t("catalogLoading")}
      </div>
    );
  }

  if (error) {
    return (
      <div className="app-subpanel p-5">
        <p className="font-medium text-[var(--ink-900)]">
          {t("cabinetReservationsError")}
        </p>
        <p className="mt-2 text-sm text-[var(--ink-500)]">
          Данные временно недоступны. Ваши бронирования сохранены — они появятся
          при следующем открытии.
        </p>
        <button
          type="button"
          className="app-button-secondary mt-4"
          onClick={() => window.location.reload()}
        >
          Попробовать снова
        </button>
      </div>
    );
  }

  const reservations = data?.data || [];

  if (reservations.length === 0) {
    return (
      <div className="app-empty-state">
        <p className="font-medium text-[var(--ink-700)]">
          Бронирований пока нет
        </p>
        <p className="mt-1 text-sm text-[var(--ink-500)]">
          {t("cabinetReservationsEmpty")}
        </p>
      </div>
    );
  }

  const totalPages = data?.meta.totalPages || 1;

  return (
    <section className="space-y-5">
      <div className="app-toolbar">
        <div>
          <p className="app-kicker">{t("shellReaderSection")}</p>
          <h2 className="mt-2 app-section-heading text-2xl">
            {t("cabinetReservationsTitle")}
          </h2>
          <p className="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
            {t("cabinetReservationsDescription")}
          </p>
        </div>
        <div className="flex flex-wrap gap-2">
          <span className="app-chip">
            {reservations.length} {t("catalogResults")}
          </span>
          <span className="app-chip-muted">{t("shellSecureLabel")}</span>
        </div>
      </div>

      <div className="app-table-shell">
        <table className="w-full text-sm">
          <thead className="app-table-head">
            <tr className="border-b border-blue-100/70">
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("cabinetReservationColumnBook")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("cabinetReservationColumnStatus")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("cabinetReservationColumnDate")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("cabinetReservationColumnExpiresAt")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("cabinetReservationColumnActions")}
              </th>
            </tr>
          </thead>
          <tbody>
            {reservations.map((reservation) => {
              const statusConfig = STATUS_COLORS[reservation.status];
              const reservedDate = new Date(
                reservation.reservedAt,
              ).toLocaleDateString();
              const expiresDate = reservation.expiresAt
                ? new Date(reservation.expiresAt).toLocaleDateString()
                : "-";
              const canCancel =
                reservation.status === "PENDING" ||
                reservation.status === "READY";

              return (
                <tr
                  key={reservation.id}
                  className="border-b border-slate-100/90 hover:bg-slate-50/70"
                >
                  <td className="px-4 py-4 text-slate-900">
                    <div className="font-medium">
                      {reservation.book?.title || reservation.bookId}
                    </div>
                    <div className="mt-2 flex flex-wrap gap-2 text-xs text-slate-500">
                      <span className="app-chip-muted">
                        {t("commonBranchLabel")}:{" "}
                        {reservation.libraryBranch?.name ||
                          reservation.libraryBranchId}
                      </span>
                    </div>
                  </td>
                  <td className="px-4 py-4">
                    <span
                      className={`inline-block rounded-full border px-3 py-1 text-xs font-medium ${statusConfig.badge}`}
                    >
                      {t(statusConfig.label)}
                    </span>
                  </td>
                  <td className="px-4 py-4 text-slate-600">{reservedDate}</td>
                  <td className="px-4 py-4 text-slate-600">{expiresDate}</td>
                  <td className="px-4 py-4">
                    {canCancel && (
                      <button
                        onClick={() => handleCancel(reservation.id)}
                        disabled={cancelMutation.isPending}
                        className="inline-flex rounded-xl bg-red-100 px-3 py-2 text-xs font-medium text-red-700 transition hover:bg-red-200 disabled:opacity-50"
                      >
                        {cancelMutation.isPending
                          ? t("catalogLoading")
                          : t("cabinetReservationCancel")}
                      </button>
                    )}
                    {!canCancel && (
                      <span className="text-xs text-slate-400">
                        {t("cabinetReservationCancelNotAvailable")}
                      </span>
                    )}
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>

      {totalPages > 1 && (
        <div className="app-panel flex justify-center gap-2 px-4 py-3">
          <button
            onClick={() => setPage(Math.max(1, page - 1))}
            disabled={page === 1}
            className="app-button-secondary disabled:opacity-50"
          >
            {t("catalogPrevious")}
          </button>
          <span className="flex items-center px-3 text-sm text-slate-600">
            {page} {t("catalogOf")} {totalPages}
          </span>
          <button
            onClick={() => setPage(Math.min(totalPages, page + 1))}
            disabled={page === totalPages}
            className="app-button-secondary disabled:opacity-50"
          >
            {t("catalogNext")}
          </button>
        </div>
      )}
    </section>
  );
}
