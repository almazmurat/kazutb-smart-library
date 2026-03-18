import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";
import {
  useDashboard,
  usePopularBooks,
  useActivity,
} from "../hooks/use-analytics";

function SummaryCard({
  label,
  value,
  alert,
}: {
  label: string;
  value: number;
  alert?: boolean;
}) {
  return (
    <div
      className={`rounded-lg border p-4 ${alert ? "border-red-300 bg-red-50" : "border-slate-200 bg-white"}`}
    >
      <div className="text-sm text-slate-500">{label}</div>
      <div
        className={`mt-1 text-2xl font-bold ${alert ? "text-red-700" : "text-slate-900"}`}
      >
        {value}
      </div>
    </div>
  );
}

export function AnalyticsPage() {
  const { t } = useI18n();
  const dashboard = useDashboard();
  const popular = usePopularBooks(10);
  const activity = useActivity();

  if (dashboard.isLoading || popular.isLoading || activity.isLoading) {
    return (
      <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p className="text-slate-500">{t("dashboardLoading")}</p>
      </section>
    );
  }

  if (dashboard.isError || popular.isError || activity.isError) {
    return (
      <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p className="text-red-600">{t("dashboardError")}</p>
      </section>
    );
  }

  const d = dashboard.data!;
  const p = popular.data!;
  const a = activity.data!;

  return (
    <div className="space-y-6">
      <PageIntro
        eyebrow={t("shellOperationsSection")}
        title={t("dashboardTitle")}
        description={t("dashboardDescription")}
        badges={[
          d.scope === "global"
            ? t("dashboardScopeGlobal")
            : t("dashboardScopeBranch"),
          t("shellSecureLabel"),
        ]}
      />

      {/* Summary cards */}
      <div className="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
        <SummaryCard label={t("dashboardTotalBooks")} value={d.totalBooks} />
        <SummaryCard label={t("dashboardTotalCopies")} value={d.totalCopies} />
        <SummaryCard
          label={t("dashboardActiveReservations")}
          value={d.activeReservations}
        />
        <SummaryCard label={t("dashboardActiveLoans")} value={d.activeLoans} />
        <SummaryCard
          label={t("dashboardOverdueLoans")}
          value={d.overdueLoans}
          alert={d.overdueLoans > 0}
        />
        <SummaryCard label={t("dashboardTotalUsers")} value={d.totalUsers} />
      </div>

      {/* Activity table */}
      <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 className="mb-4 text-lg font-semibold text-slate-900">
          {t("dashboardActivityTitle")}
        </h2>
        <div className="overflow-x-auto">
          <table className="w-full text-left text-sm">
            <thead className="border-b border-slate-200 text-xs uppercase text-slate-500">
              <tr>
                <th className="px-3 py-2" />
                <th className="px-3 py-2">{t("dashboardActivityToday")}</th>
                <th className="px-3 py-2">{t("dashboardActivityLast7")}</th>
                <th className="px-3 py-2">{t("dashboardActivityLast30")}</th>
              </tr>
            </thead>
            <tbody>
              <tr className="border-b border-slate-100">
                <td className="px-3 py-2 font-medium text-slate-700">
                  {t("dashboardActivityReservations")}
                </td>
                <td className="px-3 py-2">{a.reservations.today}</td>
                <td className="px-3 py-2">{a.reservations.last7days}</td>
                <td className="px-3 py-2">{a.reservations.last30days}</td>
              </tr>
              <tr className="border-b border-slate-100">
                <td className="px-3 py-2 font-medium text-slate-700">
                  {t("dashboardActivityLoans")}
                </td>
                <td className="px-3 py-2">{a.loans.today}</td>
                <td className="px-3 py-2">{a.loans.last7days}</td>
                <td className="px-3 py-2">{a.loans.last30days}</td>
              </tr>
              <tr>
                <td className="px-3 py-2 font-medium text-slate-700">
                  {t("dashboardActivityReturns")}
                </td>
                <td className="px-3 py-2">{a.returns.today}</td>
                <td className="px-3 py-2">{a.returns.last7days}</td>
                <td className="px-3 py-2">{a.returns.last30days}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      {/* Popular books */}
      <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 className="mb-1 text-lg font-semibold text-slate-900">
          {t("dashboardPopularBooks")}
        </h2>
        <p className="mb-4 text-xs text-slate-400">
          {t("dashboardPopularBooksRanking")}: {p.rankingLogic}
        </p>
        {p.data.length === 0 ? (
          <p className="text-slate-500">{t("dashboardPopularBooksEmpty")}</p>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm">
              <thead className="border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                  <th className="px-3 py-2">#</th>
                  <th className="px-3 py-2">{t("dashboardColumnTitle")}</th>
                  <th className="px-3 py-2">{t("dashboardColumnAuthors")}</th>
                  <th className="px-3 py-2 text-right">
                    {t("dashboardColumnLoans")}
                  </th>
                  <th className="px-3 py-2 text-right">
                    {t("dashboardColumnReservations")}
                  </th>
                  <th className="px-3 py-2 text-right">
                    {t("dashboardColumnScore")}
                  </th>
                </tr>
              </thead>
              <tbody>
                {p.data.map((book, idx) => (
                  <tr
                    key={book.id}
                    className="border-b border-slate-100 last:border-0"
                  >
                    <td className="px-3 py-2 text-slate-400">{idx + 1}</td>
                    <td className="px-3 py-2 font-medium text-slate-900">
                      {book.title}
                    </td>
                    <td className="px-3 py-2 text-slate-600">
                      {book.authors.join(", ") || "—"}
                    </td>
                    <td className="px-3 py-2 text-right">{book.loanCount}</td>
                    <td className="px-3 py-2 text-right">
                      {book.reservationCount}
                    </td>
                    <td className="px-3 py-2 text-right font-semibold text-primary-700">
                      {book.score}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </section>
    </div>
  );
}
