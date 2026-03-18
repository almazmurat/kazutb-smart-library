import { useEffect, useMemo, useState } from "react";

import { PageIntro } from "@shared/ui/page-intro";
import { useI18n } from "@shared/i18n/use-i18n";

import type {
  DataQualityIssueClass,
  DataQualityFilters,
  DataQualityIssueStatus,
  DataQualitySeverity,
  DataQualityStage,
} from "../types";
import {
  useDataQualityIssue,
  useDataQualityIssues,
  useDataQualitySummary,
} from "../hooks/use-data-quality";

const severityOrder: DataQualitySeverity[] = [
  "CRITICAL",
  "HIGH",
  "MEDIUM",
  "LOW",
];

const issueClassOrder: DataQualityIssueClass[] = [
  "IDENTITY",
  "REFERENTIAL",
  "SEMANTIC",
  "FORMAT",
  "GOVERNANCE",
  "DERIVED",
];

const statusOrder: DataQualityIssueStatus[] = [
  "new",
  "in_review",
  "approved",
  "rejected",
  "fixed",
];

function severityBadgeClass(severity: DataQualitySeverity): string {
  switch (severity) {
    case "CRITICAL":
      return "bg-red-100 text-red-800 border-red-200";
    case "HIGH":
      return "bg-orange-100 text-orange-800 border-orange-200";
    case "MEDIUM":
      return "bg-amber-100 text-amber-800 border-amber-200";
    default:
      return "bg-slate-100 text-slate-700 border-slate-200";
  }
}

export function DataQualityWorkbenchPage() {
  const { t } = useI18n();

  const [stage, setStage] = useState<DataQualityStage>("clean");
  const [severity, setSeverity] = useState<DataQualitySeverity | "ALL">("ALL");
  const [issueClass, setIssueClass] = useState<DataQualityIssueClass | "ALL">(
    "ALL",
  );
  const [status, setStatus] = useState<DataQualityIssueStatus | "ALL">("ALL");
  const [sourceTable, setSourceTable] = useState<string | "ALL">("ALL");
  const [selectedIssueId, setSelectedIssueId] = useState<string | null>(null);

  const filters: DataQualityFilters = {
    stage,
    severity,
    issueClass,
    status,
    sourceTable,
  };

  const summaryQuery = useDataQualitySummary(filters);
  const issuesQuery = useDataQualityIssues(filters);
  const issueDetailQuery = useDataQualityIssue(selectedIssueId);

  const issues = issuesQuery.data?.items ?? [];
  const kpi = summaryQuery.data ?? {
    total: 0,
    critical: 0,
    high: 0,
    autoFixable: 0,
  };

  const sourceTables = useMemo(() => {
    return [...new Set(issues.map((item) => item.sourceTable))].sort();
  }, [issues]);

  useEffect(() => {
    if (!issues.length) {
      setSelectedIssueId(null);
      return;
    }

    if (!selectedIssueId || !issues.some((item) => item.id === selectedIssueId)) {
      setSelectedIssueId(issues[0].id);
    }
  }, [issues, selectedIssueId]);

  const activeIssue = issueDetailQuery.data;
  const isLoading = summaryQuery.isLoading || issuesQuery.isLoading;
  const hasError = summaryQuery.isError || issuesQuery.isError;

  return (
    <div className="space-y-6">
      <PageIntro
        eyebrow={t("shellOperationsSection")}
        title={t("dqWorkbenchTitle")}
        description={t("dqWorkbenchDescription")}
        badges={[
          t("shellSecureLabel"),
          t("dqWorkbenchBadgeMigrationReadiness"),
        ]}
      />

      <section className="app-panel-strong p-6">
        <h2 className="mb-4 app-section-heading">
          {t("dqWorkbenchSummaryTitle")}
        </h2>
        <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
          <div className="app-panel p-4">
            <p className="app-kicker">{t("dqWorkbenchSummaryTotal")}</p>
            <p className="mt-3 text-3xl font-semibold text-slate-900">
              {kpi.total}
            </p>
          </div>
          <div className="app-panel p-4">
            <p className="app-kicker">{t("dqWorkbenchSummaryCritical")}</p>
            <p className="mt-3 text-3xl font-semibold text-red-700">
              {kpi.critical}
            </p>
          </div>
          <div className="app-panel p-4">
            <p className="app-kicker">{t("dqWorkbenchSummaryHigh")}</p>
            <p className="mt-3 text-3xl font-semibold text-orange-700">
              {kpi.high}
            </p>
          </div>
          <div className="app-panel p-4">
            <p className="app-kicker">{t("dqWorkbenchSummaryAutoFixable")}</p>
            <p className="mt-3 text-3xl font-semibold text-slate-900">
              {kpi.autoFixable}
            </p>
          </div>
        </div>
      </section>

      <section className="app-panel-strong p-6">
        <h2 className="mb-4 app-section-heading">
          {t("dqWorkbenchFiltersTitle")}
        </h2>
        <div className="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
          <label className="space-y-1 text-sm">
            <span className="text-slate-600">
              {t("dqWorkbenchFilterStage")}
            </span>
            <select
              className="w-full rounded-xl border border-slate-300 bg-white px-3 py-2"
              value={stage}
              onChange={(event) =>
                setStage(event.target.value as DataQualityStage)
              }
            >
              <option value="raw">{t("dqStageRaw")}</option>
              <option value="clean">{t("dqStageClean")}</option>
              <option value="normalized">{t("dqStageNormalized")}</option>
            </select>
          </label>

          <label className="space-y-1 text-sm">
            <span className="text-slate-600">
              {t("dqWorkbenchFilterSeverity")}
            </span>
            <select
              className="w-full rounded-xl border border-slate-300 bg-white px-3 py-2"
              value={severity}
              onChange={(event) =>
                setSeverity(event.target.value as DataQualitySeverity | "ALL")
              }
            >
              <option value="ALL">{t("dqFilterAll")}</option>
              {severityOrder.map((level) => (
                <option key={level} value={level}>
                  {level}
                </option>
              ))}
            </select>
          </label>

          <label className="space-y-1 text-sm">
            <span className="text-slate-600">
              {t("dqWorkbenchFilterIssueClass")}
            </span>
            <select
              className="w-full rounded-xl border border-slate-300 bg-white px-3 py-2"
              value={issueClass}
              onChange={(event) =>
                setIssueClass(
                  event.target.value as DataQualityIssueClass | "ALL",
                )
              }
            >
              <option value="ALL">{t("dqFilterAll")}</option>
              <option value="IDENTITY">IDENTITY</option>
              <option value="REFERENTIAL">REFERENTIAL</option>
              <option value="SEMANTIC">SEMANTIC</option>
              <option value="FORMAT">FORMAT</option>
              <option value="GOVERNANCE">GOVERNANCE</option>
              <option value="DERIVED">DERIVED</option>
            </select>
          </label>

          <label className="space-y-1 text-sm">
            <span className="text-slate-600">
              {t("dqWorkbenchFilterStatus")}
            </span>
            <select
              className="w-full rounded-xl border border-slate-300 bg-white px-3 py-2"
              value={status}
              onChange={(event) =>
                setStatus(event.target.value as DataQualityIssueStatus | "ALL")
              }
            >
              <option value="ALL">{t("dqFilterAll")}</option>
              <option value="new">{t("dqStatusNew")}</option>
              <option value="in_review">{t("dqStatusInReview")}</option>
              <option value="approved">{t("dqStatusApproved")}</option>
              <option value="rejected">{t("dqStatusRejected")}</option>
              <option value="fixed">{t("dqStatusFixed")}</option>
            </select>
          </label>

          <label className="space-y-1 text-sm md:col-span-2 xl:col-span-4">
            <span className="text-slate-600">{t("dqColumnSource")}</span>
            <select
              className="w-full rounded-xl border border-slate-300 bg-white px-3 py-2"
              value={sourceTable}
              onChange={(event) => setSourceTable(event.target.value)}
            >
              <option value="ALL">{t("dqFilterAll")}</option>
              {sourceTables.map((table) => (
                <option key={table} value={table}>
                  {table}
                </option>
              ))}
            </select>
          </label>
        </div>
      </section>

      {isLoading ? (
        <section className="app-panel p-4 text-sm text-slate-600">
          {t("dqWorkbenchLoading")}
        </section>
      ) : null}

      {hasError ? (
        <section className="app-panel p-4 text-sm text-red-700">
          {t("dqWorkbenchError")}
        </section>
      ) : null}

      <section className="grid gap-6 xl:grid-cols-[1.8fr_1fr]">
        <div className="app-panel-strong p-6">
          <h2 className="mb-4 app-section-heading">
            {t("dqWorkbenchIssueListTitle")}
          </h2>
          <div className="app-table-shell">
            <table className="w-full text-left text-sm">
              <thead className="app-table-head border-b border-slate-200 text-xs uppercase text-slate-500">
                <tr>
                  <th className="px-3 py-3">{t("dqColumnIssue")}</th>
                  <th className="px-3 py-3">{t("dqColumnSeverity")}</th>
                  <th className="px-3 py-3">{t("dqColumnClass")}</th>
                  <th className="px-3 py-3">{t("dqColumnSource")}</th>
                  <th className="px-3 py-3">{t("dqColumnStatus")}</th>
                </tr>
              </thead>
              <tbody>
                {issues.map((issue) => (
                  <tr
                    key={issue.id}
                    className={`border-b border-slate-100 align-top last:border-0 ${selectedIssueId === issue.id ? "bg-slate-50" : ""}`}
                  >
                    <td className="px-3 py-3">
                      <button
                        type="button"
                        className="text-left font-medium text-slate-900 underline-offset-2 hover:underline"
                        onClick={() => setSelectedIssueId(issue.id)}
                      >
                        {issue.id}
                      </button>
                      <p className="mt-1 text-xs text-slate-500">
                        {issue.summary}
                      </p>
                    </td>
                    <td className="px-3 py-3">
                      <span
                        className={`inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold ${severityBadgeClass(issue.severity)}`}
                      >
                        {issue.severity}
                      </span>
                    </td>
                    <td className="px-3 py-3 text-slate-700">
                      {issue.issueClass}
                    </td>
                    <td className="px-3 py-3 text-slate-700">
                      <div>{issue.sourceTable}</div>
                      <div className="text-xs text-slate-500">
                        {issue.sourceRecordKey}
                      </div>
                    </td>
                    <td className="px-3 py-3 text-slate-700">
                      {issue.status}
                      {issue.reviewer ? (
                        <div className="text-xs text-slate-500">
                          {issue.reviewer}
                        </div>
                      ) : null}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
          {issues.length === 0 ? (
            <div className="app-empty-state mt-4 py-6">
              <p className="text-slate-600">{t("dqWorkbenchEmpty")}</p>
            </div>
          ) : null}
        </div>

        <aside className="app-panel-strong p-6">
          <h2 className="mb-4 app-section-heading">
            {t("dqWorkbenchReviewPanelTitle")}
          </h2>
          <p className="mb-4 text-sm leading-6 text-slate-600">
            {t("dqWorkbenchReviewPanelDescription")}
          </p>
          {activeIssue ? (
            <div className="mb-4 space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
              <p className="font-semibold text-slate-900">{activeIssue.id}</p>
              <p>{activeIssue.summary}</p>
              <p>
                <span className="font-medium">{t("dqColumnSource")}: </span>
                {activeIssue.sourceTable} / {activeIssue.sourceRecordKey}
              </p>
              {activeIssue.fieldName ? (
                <p>
                  <span className="font-medium">{t("dqIssueFieldLabel")}: </span>
                  {activeIssue.fieldName}
                </p>
              ) : null}
              {activeIssue.detectionRule ? (
                <p>
                  <span className="font-medium">{t("dqIssueRuleLabel")}: </span>
                  {activeIssue.detectionRule}
                </p>
              ) : null}
            </div>
          ) : (
            <div className="mb-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
              {t("dqWorkbenchSelectIssue")}
            </div>
          )}
          <div className="space-y-3">
            <button
              type="button"
              className="app-button-primary w-full justify-center"
              disabled
            >
              {t("dqActionApproveFix")}
            </button>
            <button
              type="button"
              className="app-button-secondary w-full justify-center"
              disabled
            >
              {t("dqActionRequestRuleChange")}
            </button>
            <button
              type="button"
              className="app-button-secondary w-full justify-center"
              disabled
            >
              {t("dqActionAcceptException")}
            </button>
            <button
              type="button"
              className="app-button-secondary w-full justify-center"
              disabled
            >
              {t("dqActionRejectFix")}
            </button>
          </div>
          <div className="app-subpanel mt-4 p-4 text-sm text-slate-600">
            <p className="font-medium text-slate-800">
              {t("dqWorkbenchAuditNoteTitle")}
            </p>
            <p className="mt-2">{t("dqWorkbenchAuditNoteDescription")}</p>
          </div>
        </aside>
      </section>
    </div>
  );
}
