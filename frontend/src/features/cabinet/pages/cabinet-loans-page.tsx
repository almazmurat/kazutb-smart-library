import { useState } from "react";
import { useMyLoans } from "@features/circulation/hooks/use-circulation";
import { type LoanStatus } from "@features/circulation/api/circulation-api";
import { useI18n } from "@shared/i18n/use-i18n";
import type { TranslationKey } from "@shared/i18n/dictionary";

const STATUS_COLORS: Record<
  LoanStatus,
  { badge: string; label: TranslationKey }
> = {
  ACTIVE: {
    badge: "bg-blue-50 text-blue-800 border-blue-100",
    label: "cabinetLoanStatusActive",
  },
  RETURNED: {
    badge: "bg-green-50 text-green-800 border-green-100",
    label: "cabinetLoanStatusReturned",
  },
  OVERDUE: {
    badge: "bg-red-50 text-red-800 border-red-100",
    label: "cabinetLoanStatusOverdue",
  },
  LOST: {
    badge: "bg-gray-50 text-gray-800 border-gray-100",
    label: "cabinetLoanStatusLost",
  },
};

export function CabinetLoansPage() {
  const { t } = useI18n();
  const [page, setPage] = useState(1);
  const { data, isLoading, error } = useMyLoans(page, 10);

  if (isLoading) {
    return (
      <div className="app-empty-state text-sm text-slate-600">
        {t("catalogLoading")}
      </div>
    );
  }

  if (error) {
    return (
      <div className="app-state-error">
        {t("cabinetLoansError")}
      </div>
    );
  }

  const loans = data?.data || [];

  if (loans.length === 0) {
    return (
      <div className="app-empty-state">
        <p className="text-sm text-slate-600">{t("cabinetLoansEmpty")}</p>
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
            {t("cabinetLoansTitle")}
          </h2>
          <p className="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
            {t("cabinetLoansDescription")}
          </p>
        </div>
        <div className="flex flex-wrap gap-2">
          <span className="app-chip">
            {loans.length} {t("catalogResults")}
          </span>
          <span className="app-chip-muted">{t("shellSecureLabel")}</span>
        </div>
      </div>

      <div className="app-table-shell">
        <table className="w-full text-sm">
          <thead className="app-table-head">
            <tr className="border-b border-blue-100/70">
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("circulationColumnBook")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("circulationColumnCopy")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("circulationColumnStatus")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("circulationColumnLoanedAt")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("circulationColumnDueDate")}
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                {t("circulationColumnReturnedAt")}
              </th>
            </tr>
          </thead>
          <tbody>
            {loans.map((loan) => {
              const isOverdue =
                loan.status === "ACTIVE" && new Date(loan.dueDate) < new Date();
              const statusConfig =
                STATUS_COLORS[isOverdue ? "OVERDUE" : loan.status];

              return (
                <tr
                  key={loan.id}
                  className={`border-b border-slate-100/90 hover:bg-slate-50/70 ${isOverdue ? "bg-red-50/30" : ""}`}
                >
                  <td className="px-4 py-4 text-slate-900 font-medium">
                    {loan.copy?.book?.title || "-"}
                  </td>
                  <td className="px-4 py-4 text-slate-600">
                    {loan.copy?.inventoryNumber || loan.copyId}
                  </td>
                  <td className="px-4 py-4">
                    <span
                      className={`inline-block rounded-full border px-3 py-1 text-xs font-medium ${statusConfig.badge}`}
                    >
                      {t(statusConfig.label)}
                    </span>
                  </td>
                  <td className="px-4 py-4 text-slate-600">
                    {new Date(loan.loanedAt).toLocaleDateString()}
                  </td>
                  <td
                    className={`px-4 py-4 ${isOverdue ? "font-medium text-red-700" : "text-slate-600"}`}
                  >
                    {new Date(loan.dueDate).toLocaleDateString()}
                  </td>
                  <td className="px-4 py-4 text-slate-600">
                    {loan.returnedAt
                      ? new Date(loan.returnedAt).toLocaleDateString()
                      : "-"}
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
