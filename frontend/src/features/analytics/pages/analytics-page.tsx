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
      className={`app-panel p-4 ${alert ? "border-red-300 bg-red-50/90" : ""}`}
    >
      <div className="app-kicker">{label}</div>
      <div
        className={`mt-3 text-3xl font-semibold tracking-tight ${alert ? "text-red-700" : "text-slate-900"}`}
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
      <section className="app-empty-state">
        <p className="text-slate-500">{t("dashboardLoading")}</p>
      </section>
    );
  }

  if (dashboard.isError || popular.isError || activity.isError) {
    return (
      <section className="app-empty-state">
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

      <section className="app-panel-strong p-6">
        <div className="mb-4 flex items-end justify-between gap-4">
          <div>
            <p className="app-kicker">{t("dashboardActivityTitle")}</p>
            <h2 className="mt-2 app-section-heading">
              {t("dashboardActivityTitle")}
            </h2>
          </div>
          <span className="app-chip-muted">
            {t("overviewStatusOperational")}
          </span>
        </div>
        <div className="app-table-shell">
          <table className="w-full text-left text-sm">
            <thead className="app-table-head border-b border-slate-200 text-xs uppercase text-slate-500">
              <tr>
                <th className="px-3 py-3" />
                <th className="px-3 py-3">{t("dashboardActivityToday")}</th>
                <th className="px-3 py-3">{t("dashboardActivityLast7")}</th>
                <th className="px-3 py-3">{t("dashboardActivityLast30")}</th>
              </tr>
            </thead>
            <tbody>
              <tr className="border-b border-slate-100">
                <td className="px-3 py-3 font-medium text-slate-700">
                  {t("dashboardActivityReservations")}
                </td>
                <td className="px-3 py-3">{a.reservations.today}</td>
                <td className="px-3 py-3">{a.reservations.last7days}</td>
                <td className="px-3 py-3">{a.reservations.last30days}</td>
              </tr>
              <tr className="border-b border-slate-100">
                <td className="px-3 py-3 font-medium text-slate-700">
                  {t("dashboardActivityLoans")}
                </td>
                <td className="px-3 py-3">{a.loans.today}</td>
                <td className="px-3 py-3">{a.loans.last7days}</td>
                <td className="px-3 py-3">{a.loans.last30days}</td>
              </tr>
              <tr>
                <td className="px-3 py-3 font-medium text-slate-700">
                  {t("dashboardActivityReturns")}
                </td>
                <td className="px-3 py-3">{a.returns.today}</td>
                <td className="px-3 py-3">{a.returns.last7days}</td>
                <td className="px-3 py-3">{a.returns.last30days}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <section className="app-panel-strong p-6">
        <h2 className="mb-1 app-section-heading">
          {t("dashboardPopularBooks")}
        </h2>
        <p className="mb-4 text-xs text-slate-500">
          {t("dashboardPopularBooksRanking")}: {p.rankingLogic}
        </p>
        {p.data.length === 0 ? (
          <div className="app-empty-state py-8">
            <p className="text-slate-500">{t("dashboardPopularBooksEmpty")}</p>
          </div>
        ) : (
          <div className="app-table-shell">
            <table className="w-full text-left text-sm">
              <thead className="app-table-head border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                  <th className="px-3 py-3">#</th>
                  <th className="px-3 py-3">{t("dashboardColumnTitle")}</th>
                  <th className="px-3 py-3">{t("dashboardColumnAuthors")}</th>
                  <th className="px-3 py-3 text-right">
                    {t("dashboardColumnLoans")}
                  </th>
                  <th className="px-3 py-3 text-right">
                    {t("dashboardColumnReservations")}
                  </th>
                  <th className="px-3 py-3 text-right">
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
                    <td className="px-3 py-3 text-slate-400">{idx + 1}</td>
                    <td className="px-3 py-3 font-medium text-slate-900">
                      {book.title}
                    </td>
                    <td className="px-3 py-3 text-slate-600">
                      {book.authors.join(", ") || "—"}
                    </td>
                    <td className="px-3 py-3 text-right">{book.loanCount}</td>
                    <td className="px-3 py-3 text-right">
                      {book.reservationCount}
                    </td>
                    <td className="px-3 py-3 text-right font-semibold text-primary-700">
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
