import { useState } from "react";
import {
  useLibrarianReservationQueue,
  useUpdateReservationStatus,
} from "@features/reservations/hooks/use-reservations";
import { type ReservationStatus } from "@features/reservations/api/reservations-api";
import { useI18n } from "@shared/i18n/use-i18n";
import { authStore } from "@shared/auth/auth-store";
import { PageIntro } from "@shared/ui/page-intro";
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
    label: "librarianQueueStatusExpired",
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
    return <div className="app-state-error">{t("librarianQueueAccessDenied")}</div>;
  }

  if (isLoading) {
    return <div className="app-empty-state text-sm text-slate-600">{t("catalogLoading")}</div>;
  }

  if (error) {
    return <div className="app-state-error">{t("librarianQueueError")}</div>;
  }

  const reservations = data?.data || [];

  if (reservations.length === 0) {
    return (
      <div className="space-y-4">
        <PageIntro
          eyebrow={t("shellOperationsSection")}
          title={t("librarianQueueTitle")}
          description={t("librarianQueueDescription")}
          badges={[t("shellSecureLabel"), t("overviewStatusOperational")]}
        />

        <div className="app-empty-state">
          <p className="text-sm text-slate-600">{t("librarianQueueEmpty")}</p>
        </div>
      </div>
    );
  }

  const totalPages = data?.meta.totalPages || 1;

  return (
    <section className="app-page">
      <PageIntro
        eyebrow={t("shellOperationsSection")}
        title={t("librarianQueueTitle")}
        description={t("librarianQueueDescription")}
        badges={[t("shellSecureLabel"), t("overviewStatusOperational")]}
      />

      <div className="app-toolbar">
        <div>
          <p className="app-kicker">{t("librarianQueueTitle")}</p>
          <p className="mt-2 text-sm text-slate-600">
            {reservations.length} {t("catalogResults")}
          </p>
        </div>
        <select
          value={statusFilter || ""}
          onChange={(e) => {
            setStatusFilter((e.target.value as ReservationStatus) || undefined);
            setPage(1);
          }}
          className="app-form-control w-auto min-w-[210px]"
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

      <div className="app-table-shell">
        <table className="w-full text-sm">
          <thead className="app-table-head">
            <tr className="border-b border-blue-100/70">
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
                  className="border-b border-slate-100/90 hover:bg-slate-50/70"
                >
                  <td className="px-4 py-4 text-slate-900">
                    <div className="font-medium">
                      {reservation.user?.fullName || reservation.userId}
                    </div>
                    <div className="text-xs text-slate-600">
                      {reservation.user?.universityId || reservation.userId}
                    </div>
                    <div className="mt-2 text-xs text-slate-500">
                      <span className="app-chip-muted">
                        {t("commonBranchLabel")}: {reservation.libraryBranch?.name || reservation.libraryBranchId}
                      </span>
                    </div>
                  </td>
                  <td className="px-4 py-4 text-slate-900">
                    <div className="font-medium">
                      {reservation.book?.title || reservation.bookId}
                    </div>
                    <div className="mt-2 text-xs text-slate-500">
                      <span className="app-chip-muted">
                        {reservation.copy?.inventoryNumber || "-"}
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
                  <td className="px-4 py-4">
                    <div className="flex flex-wrap gap-2">
                      {reservation.status === "PENDING" && (
                        <>
                          <button
                            onClick={() =>
                              handleStatusChange(reservation.id, "READY")
                            }
                            disabled={updateStatusMutation.isPending}
                            className="inline-flex rounded-xl bg-green-100 px-3 py-2 text-xs font-medium text-green-700 transition hover:bg-green-200 disabled:opacity-50"
                          >
                            {t("librarianQueueConfirm")}
                          </button>
                          <button
                            onClick={() =>
                              handleStatusChange(reservation.id, "CANCELLED")
                            }
                            disabled={updateStatusMutation.isPending}
                            className="inline-flex rounded-xl bg-red-100 px-3 py-2 text-xs font-medium text-red-700 transition hover:bg-red-200 disabled:opacity-50"
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
                          className="inline-flex rounded-xl bg-blue-100 px-3 py-2 text-xs font-medium text-blue-700 transition hover:bg-blue-200 disabled:opacity-50"
                        >
                          {t("librarianQueueMarkReady")}
                        </button>
                      )}
                      {!['PENDING', 'READY'].includes(reservation.status) && (
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
