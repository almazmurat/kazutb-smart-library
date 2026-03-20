import { type FormEvent, useState } from "react";
import {
  useLoans,
  useIssueLoan,
  useReturnLoan,
} from "../hooks/use-circulation";
import { type LoanStatus } from "../api/circulation-api";
import { useI18n } from "@shared/i18n/use-i18n";
import { authStore } from "@shared/auth/auth-store";
import { PageIntro } from "@shared/ui/page-intro";
import type { TranslationKey } from "@shared/i18n/dictionary";

const STATUS_COLORS: Record<
  LoanStatus,
  { badge: string; label: TranslationKey }
> = {
  ACTIVE: {
    badge: "bg-blue-50 text-blue-800 border-blue-100",
    label: "circulationStatusActive",
  },
  RETURNED: {
    badge: "bg-green-50 text-green-800 border-green-100",
    label: "circulationStatusReturned",
  },
  OVERDUE: {
    badge: "bg-red-50 text-red-800 border-red-100",
    label: "circulationStatusOverdue",
  },
  LOST: {
    badge: "bg-gray-50 text-gray-800 border-gray-100",
    label: "circulationStatusLost",
  },
};

export function CirculationPage() {
  const { t } = useI18n();
  const [page, setPage] = useState(1);
  const [statusFilter, setStatusFilter] = useState<LoanStatus | undefined>(
    undefined,
  );
  const [overdueFilter, setOverdueFilter] = useState(false);
  const [showIssueForm, setShowIssueForm] = useState(false);

  const { data, isLoading, error } = useLoans(
    overdueFilter ? undefined : statusFilter,
    page,
    20,
    undefined,
    overdueFilter || undefined,
  );
  const issueMutation = useIssueLoan();
  const returnMutation = useReturnLoan();

  const [issueUserId, setIssueUserId] = useState("");
  const [issueCopyId, setIssueCopyId] = useState("");
  const [issueDueDate, setIssueDueDate] = useState("");
  const [issueNotes, setIssueNotes] = useState("");
  const [issueMessage, setIssueMessage] = useState<{
    type: "success" | "error";
    text: string;
  } | null>(null);
  const [returnMessage, setReturnMessage] = useState<{
    type: "success" | "error";
    text: string;
  } | null>(null);

  if (
    !authStore.isAuthenticated ||
    (authStore.role !== "LIBRARIAN" && authStore.role !== "ADMIN")
  ) {
    return (
      <div className="app-state-error">{t("circulationAccessDenied")}</div>
    );
  }

  const handleIssueLoan = async (e: FormEvent) => {
    e.preventDefault();
    setIssueMessage(null);
    try {
      await issueMutation.mutateAsync({
        userId: issueUserId.trim(),
        copyId: issueCopyId.trim(),
        dueDate: issueDueDate || undefined,
        notes: issueNotes.trim() || undefined,
      });
      setIssueMessage({ type: "success", text: t("circulationIssueSuccess") });
      setIssueUserId("");
      setIssueCopyId("");
      setIssueDueDate("");
      setIssueNotes("");
      setShowIssueForm(false);
    } catch (err: any) {
      setIssueMessage({
        type: "error",
        text: err.response?.data?.message || t("circulationError"),
      });
    }
  };

  const handleReturn = async (loanId: string) => {
    if (!confirm(t("circulationConfirmReturn"))) return;

    setReturnMessage(null);

    try {
      await returnMutation.mutateAsync({ loanId });
      setReturnMessage({
        type: "success",
        text: t("circulationReturnSuccess"),
      });
    } catch (err: any) {
      setReturnMessage({
        type: "error",
        text: err?.response?.data?.message || t("circulationError"),
      });
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
      <div className="space-y-4">
        <PageIntro
          eyebrow={t("shellOperationsSection")}
          title={t("circulationTitle")}
          description="Сервис книговыдачи временно недоступен. Маршрут работает в безопасном режиме."
          badges={[t("shellSecureLabel"), "Операционная стабильность"]}
        />
        <div className="app-subpanel p-6 text-sm text-slate-700">
          <p className="font-medium">{t("circulationError")}</p>
          <p className="mt-2 text-slate-600">
            Попробуйте обновить страницу или перейдите в другой служебный
            раздел.
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

  const loans = data?.data || [];
  const totalPages = data?.meta.totalPages || 1;

  return (
    <section className="app-page">
      <PageIntro
        eyebrow={t("shellOperationsSection")}
        title={t("circulationTitle")}
        description={t("circulationDescription")}
        badges={[t("shellSecureLabel"), t("overviewStatusOperational")]}
      />

      <div className="app-toolbar">
        <div className="flex flex-wrap items-center gap-3">
          <label className="flex items-center gap-1.5 text-sm text-slate-600">
            <input
              type="checkbox"
              checked={overdueFilter}
              onChange={(e) => {
                setOverdueFilter(e.target.checked);
                setPage(1);
              }}
              className="rounded border-slate-300"
            />
            {t("circulationFilterOverdue")}
          </label>
          <select
            value={statusFilter || ""}
            onChange={(e) => {
              setStatusFilter((e.target.value as LoanStatus) || undefined);
              setOverdueFilter(false);
              setPage(1);
            }}
            className="app-form-control w-auto min-w-[210px]"
          >
            <option value="">{t("circulationFilterAll")}</option>
            <option value="ACTIVE">{t("circulationStatusActive")}</option>
            <option value="RETURNED">{t("circulationStatusReturned")}</option>
            <option value="OVERDUE">{t("circulationStatusOverdue")}</option>
            <option value="LOST">{t("circulationStatusLost")}</option>
          </select>
          <button
            onClick={() => setShowIssueForm(!showIssueForm)}
            className="app-button-primary"
          >
            {t("circulationIssueLoan")}
          </button>
        </div>
        <div className="flex flex-wrap gap-2">
          <span className="app-chip">
            {loans.length} {t("catalogResults")}
          </span>
          {overdueFilter && (
            <span className="app-chip-muted">
              {t("circulationFilterOverdue")}
            </span>
          )}
        </div>
      </div>

      {issueMessage && (
        <div
          className={`px-4 py-3 text-sm ${
            issueMessage.type === "success"
              ? "app-state-success"
              : "app-state-error"
          }`}
        >
          {issueMessage.text}
        </div>
      )}

      {returnMessage && (
        <div
          className={`px-4 py-3 text-sm ${
            returnMessage.type === "success"
              ? "app-state-success"
              : "app-state-error"
          }`}
        >
          {returnMessage.text}
        </div>
      )}

      {showIssueForm && (
        <form onSubmit={handleIssueLoan} className="app-panel-strong p-5">
          <p className="app-kicker">{t("shellOperationsSection")}</p>
          <h2 className="mt-2 text-lg font-semibold text-slate-900">
            {t("circulationIssueFormTitle")}
          </h2>
          <div className="mt-4 grid gap-4 sm:grid-cols-2">
            <div>
              <label className="block text-sm font-medium text-slate-700">
                {t("circulationIssueUserId")}
              </label>
              <input
                type="text"
                required
                value={issueUserId}
                onChange={(e) => setIssueUserId(e.target.value)}
                className="app-form-control mt-1"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-slate-700">
                {t("circulationIssueCopyId")}
              </label>
              <input
                type="text"
                required
                value={issueCopyId}
                onChange={(e) => setIssueCopyId(e.target.value)}
                className="app-form-control mt-1"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-slate-700">
                {t("circulationIssueDueDate")}
              </label>
              <input
                type="date"
                value={issueDueDate}
                onChange={(e) => setIssueDueDate(e.target.value)}
                className="app-form-control mt-1"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-slate-700">
                {t("circulationIssueNotes")}
              </label>
              <input
                type="text"
                value={issueNotes}
                onChange={(e) => setIssueNotes(e.target.value)}
                className="app-form-control mt-1"
              />
            </div>
          </div>
          <div className="mt-4 flex gap-3">
            <button
              type="submit"
              disabled={issueMutation.isPending}
              className="app-button-primary disabled:opacity-50"
            >
              {issueMutation.isPending
                ? t("catalogLoading")
                : t("circulationIssueSubmit")}
            </button>
            <button
              type="button"
              onClick={() => setShowIssueForm(false)}
              className="app-button-secondary"
            >
              {t("circulationIssueCancel")}
            </button>
          </div>
        </form>
      )}

      {loans.length === 0 ? (
        <div className="app-empty-state">
          <p className="text-sm text-slate-600">{t("circulationEmpty")}</p>
        </div>
      ) : (
        <div className="app-table-shell">
          <table className="w-full text-sm">
            <thead className="app-table-head">
              <tr className="border-b border-blue-100/70">
                <th className="px-4 py-3 text-left font-medium text-slate-700">
                  {t("circulationColumnUser")}
                </th>
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
                <th className="px-4 py-3 text-left font-medium text-slate-700">
                  {t("circulationColumnActions")}
                </th>
              </tr>
            </thead>
            <tbody>
              {loans.map((loan) => {
                const statusConfig = STATUS_COLORS[loan.status];
                const loanedDate = new Date(loan.loanedAt).toLocaleDateString();
                const dueDate = new Date(loan.dueDate).toLocaleDateString();
                const returnedDate = loan.returnedAt
                  ? new Date(loan.returnedAt).toLocaleDateString()
                  : "-";
                const isOverdue =
                  loan.status === "ACTIVE" &&
                  new Date(loan.dueDate) < new Date();
                const canReturn =
                  loan.status === "ACTIVE" || loan.status === "OVERDUE";

                return (
                  <tr
                    key={loan.id}
                    className={`border-b border-slate-100/90 hover:bg-slate-50/70 ${isOverdue ? "bg-red-50/30" : ""}`}
                  >
                    <td className="px-4 py-4 text-slate-900">
                      <div className="font-medium">
                        {loan.user?.fullName || "-"}
                      </div>
                      <div className="text-xs text-slate-500">
                        {loan.user?.universityId || loan.userId}
                      </div>
                    </td>
                    <td className="px-4 py-4 text-slate-900">
                      <div className="font-medium">
                        {loan.copy?.book?.title || "-"}
                      </div>
                    </td>
                    <td className="px-4 py-4 text-slate-600">
                      {loan.copy?.inventoryNumber || loan.copyId}
                    </td>
                    <td className="px-4 py-4">
                      <span
                        className={`inline-block rounded-full border px-3 py-1 text-xs font-medium ${
                          isOverdue
                            ? STATUS_COLORS.OVERDUE.badge
                            : statusConfig.badge
                        }`}
                      >
                        {t(
                          isOverdue
                            ? STATUS_COLORS.OVERDUE.label
                            : statusConfig.label,
                        )}
                      </span>
                    </td>
                    <td className="px-4 py-4 text-slate-600">{loanedDate}</td>
                    <td
                      className={`px-4 py-4 ${isOverdue ? "font-medium text-red-700" : "text-slate-600"}`}
                    >
                      {dueDate}
                    </td>
                    <td className="px-4 py-4 text-slate-600">{returnedDate}</td>
                    <td className="px-4 py-4">
                      {canReturn ? (
                        <button
                          onClick={() => handleReturn(loan.id)}
                          disabled={returnMutation.isPending}
                          className="inline-flex rounded-xl bg-green-100 px-3 py-2 text-xs font-medium text-green-700 transition hover:bg-green-200 disabled:opacity-50"
                        >
                          {t("circulationReturnAction")}
                        </button>
                      ) : (
                        <span className="text-xs text-slate-400">
                          {t("circulationNoActions")}
                        </span>
                      )}
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      )}

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
