import { useMemo, useState } from "react";

import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";
import {
  useLibrarianReservationQueue,
  useUpdateReservationStatus,
} from "@features/reservations/hooks/use-reservations";
import type { ReservationStatus } from "@features/reservations/api/reservations-api";

const STATUSES: ReservationStatus[] = [
  "PENDING",
  "READY",
  "FULFILLED",
  "CANCELLED",
  "EXPIRED",
];

const STATUS_LABEL: Record<ReservationStatus, string> = {
  PENDING: "На рассмотрении",
  READY: "Готова к выдаче",
  FULFILLED: "Выдана",
  CANCELLED: "Отменена",
  EXPIRED: "Истекла",
};

export function LibrarianReservationsDeskPage() {
  const { t } = useI18n();
  const [page, setPage] = useState(1);
  const [statusFilter, setStatusFilter] = useState<ReservationStatus | "">("");
  const [statusMessage, setStatusMessage] = useState<string | null>(null);

  const queueQuery = useLibrarianReservationQueue(
    statusFilter || undefined,
    page,
    20,
  );
  const statusMutation = useUpdateReservationStatus();

  const reservations = queueQuery.data?.data ?? [];
  const totalPages = queueQuery.data?.meta.totalPages ?? 1;
  const total = queueQuery.data?.meta.total ?? 0;

  const badge = useMemo(() => {
    if (statusFilter) {
      return `Фильтр: ${STATUS_LABEL[statusFilter]}`;
    }
    return "Все статусы";
  }, [statusFilter]);

  const applyStatus = async (
    reservationId: string,
    status: ReservationStatus,
    message: string,
  ) => {
    setStatusMessage(null);

    try {
      await statusMutation.mutateAsync({
        reservationId,
        status,
      });
      setStatusMessage(message);
    } catch (error) {
      const fallback =
        "Не удалось обновить статус резервирования. Повторите попытку.";
      if (error && typeof error === "object" && "message" in error) {
        setStatusMessage(
          String((error as { message: string }).message) || fallback,
        );
      } else {
        setStatusMessage(fallback);
      }
    }
  };

  if (queueQuery.isLoading) {
    return (
      <section className="app-empty-state text-sm text-slate-600">
        {t("catalogLoading")}
      </section>
    );
  }

  if (queueQuery.isError) {
    return (
      <div className="space-y-4">
        <PageIntro
          eyebrow={t("shellOperationsSection")}
          title="Стол резервирований"
          description="Сервис резервирований временно недоступен. Маршрут работает в безопасном режиме."
          badges={[t("shellSecureLabel"), "Рабочее место библиотекаря"]}
        />
        <div className="app-subpanel p-6 text-sm text-slate-700">
          <p className="font-medium">
            Не удалось загрузить очередь резервирований.
          </p>
          <p className="mt-2 text-[var(--ink-500)]">
            Попробуйте обновить страницу или продолжите работу в других
            разделах.
          </p>
          <button
            type="button"
            className="app-button-primary mt-4"
            onClick={() => window.location.reload()}
          >
            Обновить страницу
          </button>
        </div>
      </div>
    );
  }

  return (
    <section className="app-page">
      <PageIntro
        eyebrow={t("shellOperationsSection")}
        title="Стол резервирований"
        description="Подтверждение, отмена и закрытие резервирований в рамках операционного потока обслуживания."
        badges={[t("shellSecureLabel"), badge]}
      />

      <div className="app-toolbar">
        <div>
          <p className="app-kicker">Текущая очередь</p>
          <p className="mt-2 text-sm text-[var(--ink-500)]">{total} записей</p>
        </div>

        <select
          className="app-form-control w-auto min-w-[220px]"
          value={statusFilter}
          onChange={(event) => {
            setStatusFilter(event.target.value as ReservationStatus | "");
            setPage(1);
          }}
        >
          <option value="">Все статусы</option>
          {STATUSES.map((status) => (
            <option key={status} value={status}>
              {STATUS_LABEL[status]}
            </option>
          ))}
        </select>
      </div>

      {statusMessage ? (
        <div className="app-subpanel p-4 text-sm text-[var(--ink-700)]">
          {statusMessage}
        </div>
      ) : null}

      {reservations.length === 0 ? (
        <div className="app-empty-state">
          <p className="text-sm text-[var(--ink-500)]">
            Активных резервирований не найдено.
          </p>
        </div>
      ) : (
        <div className="app-table-shell">
          <table className="w-full text-sm">
            <thead className="app-table-head">
              <tr className="border-b border-blue-100/70">
                <th className="px-4 py-3 text-left font-medium text-slate-700">
                  Читатель
                </th>
                <th className="px-4 py-3 text-left font-medium text-slate-700">
                  Книга
                </th>
                <th className="px-4 py-3 text-left font-medium text-slate-700">
                  Статус
                </th>
                <th className="px-4 py-3 text-left font-medium text-slate-700">
                  Зарезервирована
                </th>
                <th className="px-4 py-3 text-left font-medium text-slate-700">
                  Действия
                </th>
              </tr>
            </thead>
            <tbody>
              {reservations.map((reservation) => {
                const reservedDate = new Date(
                  reservation.reservedAt,
                ).toLocaleDateString();
                const canConfirm = reservation.status === "PENDING";
                const canFulfill = reservation.status === "READY";
                const canCancel =
                  reservation.status === "PENDING" ||
                  reservation.status === "READY";

                return (
                  <tr
                    key={reservation.id}
                    className="border-b border-slate-100/90 hover:bg-[var(--surface-muted)]"
                  >
                    <td className="px-4 py-4 text-slate-900">
                      <div className="font-medium">
                        {reservation.user?.fullName || reservation.userId}
                      </div>
                    </td>
                    <td className="px-4 py-4 text-slate-900">
                      <div className="font-medium">
                        {reservation.book?.title || reservation.bookId}
                      </div>
                    </td>
                    <td className="px-4 py-4 text-slate-600">
                      {STATUS_LABEL[reservation.status]}
                    </td>
                    <td className="px-4 py-4 text-slate-600">{reservedDate}</td>
                    <td className="px-4 py-4">
                      <div className="flex flex-wrap gap-2">
                        {canConfirm ? (
                          <button
                            type="button"
                            className="app-button-secondary px-3 py-1.5"
                            disabled={statusMutation.isPending}
                            onClick={() =>
                              applyStatus(
                                reservation.id,
                                "READY",
                                "Резервирование подтверждено и отмечено как готовое к выдаче.",
                              )
                            }
                          >
                            Подтвердить
                          </button>
                        ) : null}

                        {canFulfill ? (
                          <button
                            type="button"
                            className="app-button-primary px-3 py-1.5"
                            disabled={statusMutation.isPending}
                            onClick={() =>
                              applyStatus(
                                reservation.id,
                                "FULFILLED",
                                "Резервирование закрыто: книга выдана читателю.",
                              )
                            }
                          >
                            Отметить выданной
                          </button>
                        ) : null}

                        {canCancel ? (
                          <button
                            type="button"
                            className="app-button-secondary px-3 py-1.5"
                            disabled={statusMutation.isPending}
                            onClick={() =>
                              applyStatus(
                                reservation.id,
                                "CANCELLED",
                                "Резервирование отменено.",
                              )
                            }
                          >
                            Отменить
                          </button>
                        ) : null}
                      </div>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      )}

      {totalPages > 1 ? (
        <div className="app-panel flex justify-center gap-2 px-4 py-3">
          <button
            type="button"
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
            type="button"
            onClick={() => setPage(Math.min(totalPages, page + 1))}
            disabled={page === totalPages}
            className="app-button-secondary disabled:opacity-50"
          >
            {t("catalogNext")}
          </button>
        </div>
      ) : null}
    </section>
  );
}
