import { useMemo, useState } from "react";
import {
  useAppReviewAction,
  useAppReviewIssueDetail,
  useAppReviewQueue,
  usePublicCatalogFilters,
} from "@features/catalog/hooks/use-public-catalog";
import { useI18n } from "@shared/i18n/use-i18n";
import { useAuthState } from "@shared/auth/auth-store";
import { PageIntro } from "@shared/ui/page-intro";
import { toReadableLocation } from "@shared/catalog/location-labels";

function extractSuggestionId(
  details: Record<string, unknown>,
): string | undefined {
  const snake = details.suggestion_id;
  const camel = details.suggestionId;
  const value = typeof snake === "string" ? snake : camel;
  return typeof value === "string" && value.trim() ? value.trim() : undefined;
}

function getManualFieldByEntityType(entityType: string): string {
  if (entityType === "document") {
    return "title_display";
  }
  if (entityType === "book_copy") {
    return "inventory_number_normalized";
  }
  if (entityType === "reader") {
    return "full_name_normalized";
  }
  return "title_display";
}

export function LibrarianQueuePage() {
  const { t } = useI18n();
  const auth = useAuthState();
  const [page, setPage] = useState(1);
  const [severity, setSeverity] = useState<string>("");
  const [issueCode, setIssueCode] = useState<string>("");
  const [entityType, setEntityType] = useState<string>("");
  const [campusCode, setCampusCode] = useState<string>("");
  const [servicePointCode, setServicePointCode] = useState<string>("");
  const [selectedFlagId, setSelectedFlagId] = useState<string>("");
  const [noteDraft, setNoteDraft] = useState("");
  const [manualValue, setManualValue] = useState("");
  const [statusMessage, setStatusMessage] = useState<string | null>(null);

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
  const actionMutation = useAppReviewAction();

  if (
    !auth.isAuthenticated ||
    (auth.role !== "LIBRARIAN" && auth.role !== "ADMIN")
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
  const detail = detailQuery.data;

  const suggestedId = useMemo(() => {
    if (!detail?.issue?.details) {
      return undefined;
    }
    return extractSuggestionId(detail.issue.details);
  }, [detail]);

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

      <div className="grid gap-5 xl:grid-cols-[1.2fr_0.8fr]">
        <section className="app-panel p-4 md:p-5">
          <div className="flex items-center justify-between gap-3 border-b border-slate-200/80 pb-4">
            <div>
              <p className="app-kicker">Open Work</p>
              <h2 className="app-section-heading">Queue list</h2>
            </div>
            <span className="app-chip-muted">
              {queueQuery.data?.meta.total ?? 0} items
            </span>
          </div>

          <div className="mt-4 space-y-3">
            {issues.map((issue) => {
              const flaggedDate = new Date(issue.flaggedAt).toLocaleDateString();
              const isSelected = selectedFlagId === issue.flagId;

              return (
                <button
                  key={issue.flagId}
                  type="button"
                  className={`app-queue-card w-full ${isSelected ? "app-queue-card-active" : ""}`}
                  onClick={() => setSelectedFlagId(issue.flagId)}
                >
                  <div className="flex flex-wrap items-start justify-between gap-3">
                    <div className="text-left">
                      <div className="text-base font-semibold text-slate-950">
                        {issue.issueCode}
                      </div>
                      <div className="mt-1 text-xs text-slate-500">
                        {issue.entityType} • {flaggedDate}
                      </div>
                    </div>
                    <span className="app-chip-muted">{issue.severity}</span>
                  </div>

                  <p className="mt-3 text-sm font-medium text-slate-900">
                    {issue.context.title || issue.values.raw || "No title context"}
                  </p>

                  <div className="mt-3 grid gap-2 text-xs text-slate-600 md:grid-cols-2">
                    <div>
                      <span className="font-medium text-slate-700">Campus: </span>
                      {(issue.context.campusCodes || [])
                        .map((code) => toReadableLocation(code))
                        .join(", ") || "-"}
                    </div>
                    <div>
                      <span className="font-medium text-slate-700">Service point: </span>
                      {issue.context.servicePointCodes.join(", ") || "-"}
                    </div>
                  </div>

                  <div className="mt-3 rounded-2xl bg-white/80 px-3 py-2 text-sm text-slate-600">
                    Suggested: {issue.values.suggested || "No suggestion"}
                  </div>
                </button>
              );
            })}
          </div>
        </section>

        {selectedFlagId && detail ? (
          <article className="app-panel-strong p-5 xl:sticky xl:top-24 xl:h-fit">
            <p className="app-kicker">Correction Workspace</p>
            <h3 className="mt-2 text-base font-semibold text-slate-900">
              Issue detail and review action
            </h3>
            <p className="mt-2 text-sm text-slate-600">
              {detail.issue.issueCode} • {detail.issue.severity} •{" "}
              {detail.issue.entityType}
            </p>

            <div className="mt-4 grid gap-3 md:grid-cols-2">
              <div className="app-stat-card p-3">
                <p className="text-xs uppercase tracking-[0.14em] text-slate-500">
                  Raw value
                </p>
                <p className="mt-2 text-sm text-slate-900">
                  {detail.issue.values.raw || "-"}
                </p>
              </div>
              <div className="app-stat-card p-3">
                <p className="text-xs uppercase tracking-[0.14em] text-slate-500">
                  Current normalized
                </p>
                <p className="mt-2 text-sm text-slate-900">
                  {detail.issue.values.normalized || "-"}
                </p>
              </div>
              <div className="rounded-xl border border-emerald-200 bg-emerald-50 p-3 md:col-span-2">
                <p className="text-xs uppercase tracking-[0.14em] text-emerald-700">
                  Suggested corrected value
                </p>
                <p className="mt-2 text-sm font-medium text-emerald-900">
                  {detail.issue.values.suggested || "-"}
                </p>
              </div>
            </div>

            <div className="mt-4 grid gap-2 text-sm text-slate-700">
              <p>
                <span className="font-medium">Document: </span>
                {detail.issue.context.title || "-"}
              </p>
              <p>
                <span className="font-medium">Campus: </span>
                {(detail.issue.context.campusCodes || [])
                  .map((code) => toReadableLocation(code))
                  .join(", ") || "-"}
              </p>
              <p>
                <span className="font-medium">Flag status: </span>
                {detail.issue.flagStatus}
              </p>
            </div>

            <div className="mt-4 space-y-3 rounded-[24px] border border-slate-200 bg-white/75 p-4">
              <label className="space-y-1 text-sm">
                <span className="text-slate-600">Note (optional)</span>
                <textarea
                  className="app-form-control min-h-[84px]"
                  value={noteDraft}
                  onChange={(event) => setNoteDraft(event.target.value)}
                  placeholder="Add short librarian note"
                />
              </label>

              <label className="space-y-1 text-sm">
                <span className="text-slate-600">Manual correction value</span>
                <input
                  className="app-form-control"
                  value={manualValue}
                  onChange={(event) => setManualValue(event.target.value)}
                  placeholder="Type replacement value for manual edit"
                />
              </label>

              <div className="flex flex-wrap gap-2">
                <button
                  type="button"
                  className="app-button-primary"
                  disabled={actionMutation.isPending}
                  onClick={() => {
                    setStatusMessage(null);
                    actionMutation.mutate(
                      {
                        flagId: selectedFlagId,
                        payload: {
                          action: "accept_suggestion",
                          suggestionId: suggestedId,
                          note: noteDraft || undefined,
                        },
                      },
                      {
                        onSuccess: () => {
                          setStatusMessage(
                            "Suggestion accepted and issue updated.",
                          );
                          setSelectedFlagId("");
                          setNoteDraft("");
                        },
                      },
                    );
                  }}
                >
                  Accept suggestion
                </button>

                <button
                  type="button"
                  className="app-button-secondary"
                  disabled={actionMutation.isPending}
                  onClick={() => {
                    setStatusMessage(null);
                    actionMutation.mutate(
                      {
                        flagId: selectedFlagId,
                        payload: {
                          action: "reject_suggestion",
                          suggestionId: suggestedId,
                          note: noteDraft || undefined,
                        },
                      },
                      {
                        onSuccess: () => {
                          setStatusMessage(
                            "Suggestion rejected and issue closed.",
                          );
                          setSelectedFlagId("");
                          setNoteDraft("");
                        },
                      },
                    );
                  }}
                >
                  Reject suggestion
                </button>

                <button
                  type="button"
                  className="app-button-secondary"
                  disabled={actionMutation.isPending || !manualValue.trim()}
                  onClick={() => {
                    setStatusMessage(null);
                    actionMutation.mutate(
                      {
                        flagId: selectedFlagId,
                        payload: {
                          action: "manual_edit",
                          fieldName: getManualFieldByEntityType(
                            detail.issue.entityType,
                          ),
                          manualValue: manualValue.trim(),
                          suggestionId: suggestedId,
                          note: noteDraft || undefined,
                        },
                      },
                      {
                        onSuccess: () => {
                          setStatusMessage(
                            "Manual correction applied and issue updated.",
                          );
                          setSelectedFlagId("");
                          setManualValue("");
                          setNoteDraft("");
                        },
                      },
                    );
                  }}
                >
                  Apply manual edit
                </button>
              </div>

              {actionMutation.isError ? (
                <div className="app-state-error">
                  Action failed. Please check input and try again.
                </div>
              ) : null}

              {statusMessage ? (
                <div className="app-state-success">{statusMessage}</div>
              ) : null}
            </div>

            <p className="mt-4 text-sm text-slate-700">
              Related issues: {detail.relatedIssues.length}
            </p>
          </article>
        ) : (
          <aside className="app-flow-step flex min-h-[22rem] items-center justify-center p-8 text-center text-sm leading-7 text-white/88">
            Select a queue item to open the correction workspace. The right-hand
            panel will show raw value, normalized value, suggested correction,
            and the action buttons used in the live librarian demo.
          </aside>
        )}
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
