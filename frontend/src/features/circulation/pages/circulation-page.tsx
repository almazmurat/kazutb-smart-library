import { useState } from "react";
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

  // Issue form state
  const [issueUserId, setIssueUserId] = useState("");
  const [issueCopyId, setIssueCopyId] = useState("");
  const [issueDueDate, setIssueDueDate] = useState("");
  const [issueNotes, setIssueNotes] = useState("");
  const [issueMessage, setIssueMessage] = useState<{
    type: "success" | "error";
    text: string;
  } | null>(null);

  if (
    !authStore.isAuthenticated ||
    (authStore.role !== "LIBRARIAN" && authStore.role !== "ADMIN")
  ) {
    return (
      <div className="rounded-xl border border-red-100 bg-white p-8 text-center text-sm text-red-700">
        {t("circulationAccessDenied")}
      </div>
    );
  }

  const handleIssueLoan = async (e: React.FormEvent) => {
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
    try {
      await returnMutation.mutateAsync({ loanId });
    } catch (err) {
      console.error("Failed to return loan:", err);
    }
  };

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
        {t("circulationError")}
      </div>
    );
  }

  const loans = data?.data || [];
  const totalPages = data?.meta.totalPages || 1;

  return (
    <section className="space-y-4">
      <PageIntro
        eyebrow={t("shellOperationsSection")}
        title={t("circulationTitle")}
        description={t("circulationDescription")}
        badges={[t("shellSecureLabel")]}
      />

      <div className="flex flex-wrap items-center justify-between gap-3">
        <div className="flex items-center gap-3">
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
            className="rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-700"
          >
            <option value="">{t("circulationFilterAll")}</option>
            <option value="ACTIVE">{t("circulationStatusActive")}</option>
            <option value="RETURNED">{t("circulationStatusReturned")}</option>
            <option value="OVERDUE">{t("circulationStatusOverdue")}</option>
            <option value="LOST">{t("circulationStatusLost")}</option>
          </select>
          <button
            onClick={() => setShowIssueForm(!showIssueForm)}
            className="rounded-md bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800"
          >
            {t("circulationIssueLoan")}
          </button>
        </div>
      </div>

      {issueMessage && (
        <div
          className={`rounded-md px-4 py-3 text-sm ${
            issueMessage.type === "success"
              ? "border border-green-100 bg-green-50 text-green-900"
              : "border border-red-100 bg-red-50 text-red-900"
          }`}
        >
          {issueMessage.text}
        </div>
      )}

      {showIssueForm && (
        <form
          onSubmit={handleIssueLoan}
          className="rounded-xl border border-blue-100 bg-white p-5 shadow-sm"
        >
          <h2 className="text-lg font-semibold text-slate-900">
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
                className="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
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
                className="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
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
                className="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
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
                className="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
              />
            </div>
          </div>
          <div className="mt-4 flex gap-3">
            <button
              type="submit"
              disabled={issueMutation.isPending}
              className="rounded-md bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800 disabled:opacity-50"
            >
              {issueMutation.isPending
                ? t("catalogLoading")
                : t("circulationIssueSubmit")}
            </button>
            <button
              type="button"
              onClick={() => setShowIssueForm(false)}
              className="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
              {t("circulationIssueCancel")}
            </button>
          </div>
        </form>
      )}

      {loans.length === 0 ? (
        <div className="rounded-xl border border-blue-100 bg-white p-8 text-center">
          <p className="text-sm text-slate-600">{t("circulationEmpty")}</p>
        </div>
      ) : (
        <div className="overflow-x-auto rounded-xl border border-blue-100 bg-white shadow-sm">
          <table className="w-full text-sm">
            <thead>
              <tr className="border-b border-blue-100 bg-blue-50">
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
                    className={`border-b border-slate-100 hover:bg-slate-50 ${isOverdue ? "bg-red-50/30" : ""}`}
                  >
                    <td className="px-4 py-3 text-slate-900">
                      <div className="font-medium">
                        {loan.user?.fullName || "-"}
                      </div>
                      <div className="text-xs text-slate-500">
                        {loan.user?.universityId || loan.userId}
                      </div>
                    </td>
                    <td className="px-4 py-3 text-slate-900">
                      <div className="font-medium">
                        {loan.copy?.book?.title || "-"}
                      </div>
                    </td>
                    <td className="px-4 py-3 text-slate-600">
                      {loan.copy?.inventoryNumber || loan.copyId}
                    </td>
                    <td className="px-4 py-3">
                      <span
                        className={`inline-block rounded-md border px-2 py-1 text-xs font-medium ${
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
                    <td className="px-4 py-3 text-slate-600">{loanedDate}</td>
                    <td
                      className={`px-4 py-3 ${isOverdue ? "font-medium text-red-700" : "text-slate-600"}`}
                    >
                      {dueDate}
                    </td>
                    <td className="px-4 py-3 text-slate-600">{returnedDate}</td>
                    <td className="px-4 py-3">
                      {canReturn ? (
                        <button
                          onClick={() => handleReturn(loan.id)}
                          disabled={returnMutation.isPending}
                          className="inline-flex rounded-md bg-green-100 px-3 py-1 text-xs font-medium text-green-700 hover:bg-green-200 disabled:opacity-50"
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
