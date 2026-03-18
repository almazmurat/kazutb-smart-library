import { useState } from "react";
import { useI18n } from "@shared/i18n/use-i18n";
import { useReportsOverview } from "@features/analytics/hooks/use-analytics";

export function ReportsPage() {
  const { t } = useI18n();
  const currentYear = new Date().getFullYear();
  const [selectedYear, setSelectedYear] = useState(currentYear);
  const { data, isLoading, isError } = useReportsOverview(selectedYear);

  const monthNames = t("reportsMonthNames").split(",");

  if (isLoading) {
    return (
      <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p className="text-slate-500">{t("reportsLoading")}</p>
      </section>
    );
  }

  if (isError) {
    return (
      <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p className="text-red-600">{t("reportsError")}</p>
      </section>
    );
  }

  if (!data) {
    return (
      <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p className="text-slate-500">{t("reportsNoData")}</p>
      </section>
    );
  }

  const yearOptions = Array.from({ length: 5 }, (_, i) => currentYear - i);

  return (
    <div className="space-y-6">
      {/* Header + year selector */}
      <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div className="flex items-center justify-between">
          <h1 className="text-2xl font-bold text-slate-900">
            {t("reportsTitle")}
          </h1>
          <div className="flex items-center gap-2">
            <label className="text-sm text-slate-600">
              {t("reportsYear")}:
            </label>
            <select
              className="rounded-md border border-slate-300 px-2 py-1 text-sm"
              value={selectedYear}
              onChange={(e) => setSelectedYear(Number(e.target.value))}
            >
              {yearOptions.map((y) => (
                <option key={y} value={y}>
                  {y}
                </option>
              ))}
            </select>
          </div>
        </div>
      </section>

      {/* Yearly summary cards */}
      <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 className="mb-4 text-lg font-semibold text-slate-900">
          {t("reportsYearly")} — {data.year}
        </h2>
        <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
          <div className="rounded-lg border border-slate-200 p-4">
            <div className="text-sm text-slate-500">{t("reportsLoans")}</div>
            <div className="mt-1 text-xl font-bold text-slate-900">
              {data.yearly.loans}
            </div>
          </div>
          <div className="rounded-lg border border-slate-200 p-4">
            <div className="text-sm text-slate-500">{t("reportsReturns")}</div>
            <div className="mt-1 text-xl font-bold text-slate-900">
              {data.yearly.returns}
            </div>
          </div>
          <div className="rounded-lg border border-slate-200 p-4">
            <div className="text-sm text-slate-500">
              {t("reportsReservations")}
            </div>
            <div className="mt-1 text-xl font-bold text-slate-900">
              {data.yearly.reservations}
            </div>
          </div>
          <div
            className={`rounded-lg border p-4 ${data.yearly.currentOverdue > 0 ? "border-red-300 bg-red-50" : "border-slate-200"}`}
          >
            <div className="text-sm text-slate-500">{t("reportsOverdue")}</div>
            <div
              className={`mt-1 text-xl font-bold ${data.yearly.currentOverdue > 0 ? "text-red-700" : "text-slate-900"}`}
            >
              {data.yearly.currentOverdue}
            </div>
          </div>
        </div>
      </section>

      {/* Monthly breakdown */}
      <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 className="mb-4 text-lg font-semibold text-slate-900">
          {t("reportsMonthly")}
        </h2>
        {data.monthly.length === 0 ? (
          <p className="text-slate-500">{t("reportsNoData")}</p>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm">
              <thead className="border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                  <th className="px-3 py-2">{t("reportsMonth")}</th>
                  <th className="px-3 py-2 text-right">{t("reportsLoans")}</th>
                  <th className="px-3 py-2 text-right">
                    {t("reportsReturns")}
                  </th>
                  <th className="px-3 py-2 text-right">
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
                    <td className="px-3 py-2 font-medium text-slate-700">
                      {monthNames[row.month - 1] || row.month}
                    </td>
                    <td className="px-3 py-2 text-right">{row.loans}</td>
                    <td className="px-3 py-2 text-right">{row.returns}</td>
                    <td className="px-3 py-2 text-right">{row.reservations}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </section>

      {/* Branch summary (global scope only) */}
      {data.branchSummary.length > 0 && (
        <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
          <h2 className="mb-4 text-lg font-semibold text-slate-900">
            {t("reportsBranchSummary")}
          </h2>
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm">
              <thead className="border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                  <th className="px-3 py-2">{t("reportsBranch")}</th>
                  <th className="px-3 py-2 text-right">{t("reportsLoans")}</th>
                  <th className="px-3 py-2 text-right">
                    {t("reportsReturns")}
                  </th>
                  <th className="px-3 py-2 text-right">
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
                    <td className="px-3 py-2 font-medium text-slate-700">
                      {row.branchName}
                    </td>
                    <td className="px-3 py-2 text-right">{row.loans}</td>
                    <td className="px-3 py-2 text-right">{row.returns}</td>
                    <td className="px-3 py-2 text-right">{row.reservations}</td>
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
