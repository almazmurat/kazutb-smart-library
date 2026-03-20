import { useState } from "react";
import {
  useAppReviewIssueDetail,
  useAppReviewQueue,
  usePublicCatalogFilters,
} from "@features/catalog/hooks/use-public-catalog";
import { useI18n } from "@shared/i18n/use-i18n";
import { authStore } from "@shared/auth/auth-store";
import { PageIntro } from "@shared/ui/page-intro";

export function LibrarianQueuePage() {
  const { t } = useI18n();
  const [page, setPage] = useState(1);
  const [severity, setSeverity] = useState<string>("");
  const [issueCode, setIssueCode] = useState<string>("");
  const [entityType, setEntityType] = useState<string>("");
  const [campusCode, setCampusCode] = useState<string>("");
  const [servicePointCode, setServicePointCode] = useState<string>("");
  const [selectedFlagId, setSelectedFlagId] = useState<string>("");

  const queueQuery = useAppReviewQueue({
    page,
    limit: 20,
    severity: severity || undefined,
    issueCode: issueCode || undefined,
    entityType: entityType || undefined,
    campusCode: campusCode || undefined,
    servicePointCode: servicePointCode || undefined,
  });
  const facetsQuery = usePublicCatalogFilters();
  const detailQuery = useAppReviewIssueDetail(selectedFlagId);

  if (
    !authStore.isAuthenticated ||
    (authStore.role !== "LIBRARIAN" && authStore.role !== "ADMIN")
  ) {
    return (
      <div className="app-state-error">{t("librarianQueueAccessDenied")}</div>
    );
  }

  if (queueQuery.isLoading) {
    return (
      <div className="app-empty-state text-sm text-slate-600">
        {t("catalogLoading")}
      </div>
    );
  }

  if (queueQuery.isError) {
    return <div className="app-state-error">{t("librarianQueueError")}</div>;
  }

  const issues = queueQuery.data?.data || [];
  const campuses = facetsQuery.data?.campuses ?? [];
  const servicePoints = facetsQuery.data?.servicePoints ?? [];

  if (issues.length === 0) {
    return (
      <div className="space-y-4">
        <PageIntro
          eyebrow={t("shellOperationsSection")}
          title="Librarian review queue"
          description="No issues found for current filters."
          badges={[t("shellSecureLabel"), "Data quality review"]}
        />

        <div className="app-empty-state">
          <p className="text-sm text-slate-600">{t("librarianQueueEmpty")}</p>
        </div>
      </div>
    );
  }

  const totalPages = queueQuery.data?.meta.totalPages || 1;

  return (
    <section className="app-page">
      <PageIntro
        eyebrow={t("shellOperationsSection")}
        title="Librarian review queue"
        description="Review flagged rows from the new app-review endpoint and prioritize manual correction by severity and location."
        badges={[t("shellSecureLabel"), "App review API"]}
      />

      <div className="app-toolbar">
        <div>
          <p className="app-kicker">Open issues</p>
          <p className="mt-2 text-sm text-slate-600">
            {queueQuery.data?.meta.total ?? 0} {t("catalogResults")}
          </p>
        </div>
        <div className="grid gap-2 md:grid-cols-3 lg:grid-cols-5">
          <select
            value={severity}
            onChange={(event) => {
              setSeverity(event.target.value);
              setPage(1);
            }}
            className="app-form-control"
          >
            <option value="">All severities</option>
            <option value="CRITICAL">CRITICAL</option>
            <option value="HIGH">HIGH</option>
            <option value="MEDIUM">MEDIUM</option>
            <option value="LOW">LOW</option>
          </select>
          <input
            className="app-form-control"
            placeholder="Issue code"
            value={issueCode}
            onChange={(event) => {
              setIssueCode(event.target.value);
              setPage(1);
            }}
          />
          <input
            className="app-form-control"
            placeholder="Entity type"
            value={entityType}
            onChange={(event) => {
              setEntityType(event.target.value);
              setPage(1);
            }}
          />
          <select
            value={campusCode}
            onChange={(event) => {
              setCampusCode(event.target.value);
              setPage(1);
            }}
            className="app-form-control"
          >
            <option value="">All campuses</option>
            {campuses.map((campus) => (
              <option key={campus.value} value={campus.value}>
                {campus.label}
              </option>
            ))}
          </select>
          <select
            value={servicePointCode}
            onChange={(event) => {
              setServicePointCode(event.target.value);
              setPage(1);
            }}
            className="app-form-control"
          >
            <option value="">All service points</option>
            {servicePoints.map((servicePoint) => (
              <option key={servicePoint.value} value={servicePoint.value}>
                {servicePoint.label}
              </option>
            ))}
          </select>
        </div>
      </div>

      <div className="app-table-shell overflow-x-auto">
        <table className="w-full text-sm">
          <thead className="app-table-head">
            <tr className="border-b border-blue-100/70">
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                Issue
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                Context
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                Severity
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                Flagged at
              </th>
              <th className="px-4 py-3 text-left font-medium text-slate-700">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            {issues.map((issue) => {
              const flaggedDate = new Date(
                issue.flaggedAt,
              ).toLocaleDateString();

              return (
                <tr
                  key={issue.flagId}
                  className="border-b border-slate-100/90 hover:bg-slate-50/70"
                >
                  <td className="px-4 py-4 text-slate-900">
                    <div className="font-medium">{issue.issueCode}</div>
                    <div className="text-xs text-slate-600">
                      {issue.entityType} • {issue.entityId}
                    </div>
                  </td>
                  <td className="px-4 py-4 text-slate-900">
                    <div className="font-medium">
                      {issue.context.title || "-"}
                    </div>
                    <div className="mt-2 text-xs text-slate-500">
                      Campus: {issue.context.campusCodes.join(", ") || "-"}
                    </div>
                    <div className="mt-1 text-xs text-slate-500">
                      Service point:{" "}
                      {issue.context.servicePointCodes.join(", ") || "-"}
                    </div>
                  </td>
                  <td className="px-4 py-4">
                    <span className="app-chip-muted">{issue.severity}</span>
                  </td>
                  <td className="px-4 py-4 text-slate-600">{flaggedDate}</td>
                  <td className="px-4 py-4">
                    <button
                      className="app-button-secondary px-3 py-1.5"
                      onClick={() => setSelectedFlagId(issue.flagId)}
                    >
                      View detail
                    </button>
                  </td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>

      {selectedFlagId && detailQuery.data ? (
        <article className="app-panel p-5">
          <h3 className="text-base font-semibold text-slate-900">
            Selected issue detail
          </h3>
          <p className="mt-2 text-sm text-slate-600">
            {detailQuery.data.issue.issueCode} •{" "}
            {detailQuery.data.issue.severity}
          </p>
          <p className="mt-2 text-sm text-slate-700">
            Suggested value: {detailQuery.data.issue.values.suggested || "-"}
          </p>
          <p className="mt-1 text-sm text-slate-700">
            Related issues: {detailQuery.data.relatedIssues.length}
          </p>
        </article>
      ) : null}

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
