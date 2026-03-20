import { useState } from "react";

import { useReportsOverview } from "@features/analytics/hooks/use-analytics";
import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";

export function ReportsPage() {
  const { t } = useI18n();
  const currentYear = new Date().getFullYear();
  const [selectedYear, setSelectedYear] = useState(currentYear);
  const reports = useReportsOverview(selectedYear);

  const fallbackData = {
    year: selectedYear,
    scope: "global" as const,
    yearly: {
      loans: 0,
      returns: 0,
      reservations: 0,
      currentOverdue: 0,
    },
    monthly: [],
    branchSummary: [],
  };

  if (reports.isLoading) {
    return (
      <div className="app-page">
        <PageIntro
          eyebrow={t("shellOperationsSection")}
          title={t("reportsTitle")}
          description={t("reportsLoading")}
          badges={[t("shellSecureLabel")]}
        />
        <section className="app-empty-state">
          <p className="text-slate-500">{t("reportsLoading")}</p>
        </section>
      </div>
    );
  }

  const hasError = reports.isError;
  const data = reports.data ?? fallbackData;

  const yearOptions = Array.from(
    { length: 5 },
    (_, index) => currentYear - index,
  );
  const monthNames = t("reportsMonthNames").split(",");

  return (
    <div className="app-page">
      <PageIntro
        eyebrow={t("shellOperationsSection")}
        title={t("reportsTitle")}
        description={
          hasError
            ? "Отчетные данные временно недоступны. Отображаются данные, доступные в данный момент."
            : t("reportsDescription")
        }
        badges={[
          data.scope === "global"
            ? t("dashboardScopeGlobal")
            : t("dashboardScopeBranch"),
          t("shellSecureLabel"),
        ]}
        actions={
          <div className="flex items-center gap-2">
            <label className="text-sm text-[var(--ink-500)]">
              {t("reportsYear")}:
            </label>
            <select
              className="app-form-control w-auto"
              value={selectedYear}
              onChange={(event) => setSelectedYear(Number(event.target.value))}
            >
              {yearOptions.map((year) => (
                <option key={year} value={year}>
                  {year}
                </option>
              ))}
            </select>
          </div>
        }
      />

      {hasError ? (
        <section className="app-state-warning">
          Источник отчетных данных временно недоступен. Показаны последние доступные данные.
        </section>
      ) : null}

      <section className="app-panel-strong p-6">
        <h2 className="mb-4 app-section-heading">
          {t("reportsYearly")} - {data.year}
        </h2>
        <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
          <div className="app-panel p-4">
            <div className="app-kicker">{t("reportsLoans")}</div>
            <div className="mt-3 text-2xl font-semibold text-[var(--ink-900)]">
              {data.yearly.loans}
            </div>
          </div>
          <div className="app-panel p-4">
            <div className="app-kicker">{t("reportsReturns")}</div>
            <div className="mt-3 text-2xl font-semibold text-[var(--ink-900)]">
              {data.yearly.returns}
            </div>
          </div>
          <div className="app-panel p-4">
            <div className="app-kicker">{t("reportsReservations")}</div>
            <div className="mt-3 text-2xl font-semibold text-[var(--ink-900)]">
              {data.yearly.reservations}
            </div>
          </div>
          <div
            className={`app-panel p-4 ${data.yearly.currentOverdue > 0 ? "border-red-300 bg-red-50/90" : ""}`}
          >
            <div className="app-kicker">{t("reportsOverdue")}</div>
            <div
              className={`mt-3 text-2xl font-semibold ${data.yearly.currentOverdue > 0 ? "text-red-700" : "text-slate-900"}`}
            >
              {data.yearly.currentOverdue}
            </div>
          </div>
        </div>
      </section>

      <section className="app-panel-strong p-6">
        <h2 className="mb-4 app-section-heading">{t("reportsMonthly")}</h2>
        {data.monthly.length === 0 ? (
          <div className="app-empty-state py-8">
            <p className="text-[var(--ink-500)]">{t("reportsNoData")}</p>
          </div>
        ) : (
          <div className="app-table-shell">
            <table className="w-full text-left text-sm">
              <thead className="app-table-head border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                  <th className="px-3 py-3">{t("reportsMonth")}</th>
                  <th className="px-3 py-3 text-right">{t("reportsLoans")}</th>
                  <th className="px-3 py-3 text-right">
                    {t("reportsReturns")}
                  </th>
                  <th className="px-3 py-3 text-right">
                    {t("reportsReservations")}
                  </th>
                </tr>
              </thead>
              <tbody>
                {data.monthly.map((row) => (
                  <tr
                    key={row.month}
                    className="border-b border-slate-100 last:border-0"
                  >
                    <td className="px-3 py-3 font-medium text-slate-700">
                      {monthNames[row.month - 1] || row.month}
                    </td>
                    <td className="px-3 py-3 text-right">{row.loans}</td>
                    <td className="px-3 py-3 text-right">{row.returns}</td>
                    <td className="px-3 py-3 text-right">{row.reservations}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </section>

      {data.branchSummary.length > 0 && (
        <section className="app-panel-strong p-6">
          <h2 className="mb-4 app-section-heading">
            {t("reportsBranchSummary")}
          </h2>
          <div className="app-table-shell">
            <table className="w-full text-left text-sm">
              <thead className="app-table-head border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                  <th className="px-3 py-3">{t("reportsBranch")}</th>
                  <th className="px-3 py-3 text-right">{t("reportsLoans")}</th>
                  <th className="px-3 py-3 text-right">
                    {t("reportsReturns")}
                  </th>
                  <th className="px-3 py-3 text-right">
                    {t("reportsReservations")}
                  </th>
                </tr>
              </thead>
              <tbody>
                {data.branchSummary.map((row) => (
                  <tr
                    key={row.branchId}
                    className="border-b border-slate-100 last:border-0"
                  >
                    <td className="px-3 py-3 font-medium text-slate-700">
                      {row.branchName}
                    </td>
                    <td className="px-3 py-3 text-right">{row.loans}</td>
                    <td className="px-3 py-3 text-right">{row.returns}</td>
                    <td className="px-3 py-3 text-right">{row.reservations}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </section>
      )}
    </div>
  );
}
