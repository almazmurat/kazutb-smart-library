import { useMemo, useState } from "react";

import { PageIntro } from "@shared/ui/page-intro";
import { useI18n } from "@shared/i18n/use-i18n";

import type {
  DataQualityIssue,
  DataQualityIssueClass,
  DataQualitySeverity,
  DataQualityIssueStatus,
  DataQualityStage,
} from "../types";

const demoIssues: DataQualityIssue[] = [
  {
    id: "DQ-2026-0001",
    batchId: "2026-03-batch-001",
    stage: "clean",
    severity: "CRITICAL",
    issueClass: "REFERENTIAL",
    sourceTable: "BOOKSTATES",
    sourceRecordKey: "IDBS:918201",
    branch: "TECHNOLOGICAL_LIBRARY",
    fieldName: "INV_ID",
    status: "new",
    autoFixable: false,
    detectedAt: "2026-03-18",
    summary: "Circulation state references missing inventory item.",
  },
  {
    id: "DQ-2026-0002",
    batchId: "2026-03-batch-001",
    stage: "normalized",
    severity: "HIGH",
    issueClass: "SEMANTIC",
    sourceTable: "BOOKSTATES",
    sourceRecordKey: "IDBS:907742",
    branch: "ECONOMIC_LIBRARY",
    fieldName: "STATE",
    status: "in_review",
    autoFixable: false,
    detectedAt: "2026-03-18",
    reviewer: "Librarian A.",
    summary: "Legacy state code is unmapped to target circulation lifecycle.",
  },
  {
    id: "DQ-2026-0003",
    batchId: "2026-03-batch-001",
    stage: "clean",
    severity: "MEDIUM",
    issueClass: "FORMAT",
    sourceTable: "DOC_VIEW",
    sourceRecordKey: "DOC_ID:795116081",
    branch: "COLLEGE_LIBRARY",
    fieldName: "isbn",
    status: "approved",
    autoFixable: true,
    detectedAt: "2026-03-18",
    reviewer: "Analyst B.",
    summary: "ISBN contains spacing and punctuation noise.",
  },
  {
    id: "DQ-2026-0004",
    batchId: "2026-03-batch-001",
    stage: "normalized",
    severity: "LOW",
    issueClass: "DERIVED",
    sourceTable: "DOC_VIEW",
    sourceRecordKey: "DOC_ID:795116025",
    fieldName: "title",
    status: "fixed",
    autoFixable: true,
    detectedAt: "2026-03-18",
    reviewer: "Librarian C.",
    summary: "Duplicate whitespace in flattened title value.",
  },
];

const severityOrder: DataQualitySeverity[] = [
  "CRITICAL",
  "HIGH",
  "MEDIUM",
  "LOW",
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

  const filtered = useMemo(() => {
    return demoIssues.filter((issue) => {
      const stagePass = issue.stage === stage;
      const severityPass = severity === "ALL" || issue.severity === severity;
      const classPass = issueClass === "ALL" || issue.issueClass === issueClass;
      const statusPass = status === "ALL" || issue.status === status;
      return stagePass && severityPass && classPass && statusPass;
    });
  }, [issueClass, severity, stage, status]);

  const kpi = useMemo(() => {
    const inStage = demoIssues.filter((item) => item.stage === stage);
    return {
      total: inStage.length,
      critical: inStage.filter((item) => item.severity === "CRITICAL").length,
      high: inStage.filter((item) => item.severity === "HIGH").length,
      autoFixable: inStage.filter((item) => item.autoFixable).length,
    };
  }, [stage]);

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
        </div>
      </section>

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
                {filtered.map((issue) => (
                  <tr
                    key={issue.id}
                    className="border-b border-slate-100 align-top last:border-0"
                  >
                    <td className="px-3 py-3">
                      <p className="font-medium text-slate-900">{issue.id}</p>
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
          {filtered.length === 0 ? (
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
          <div className="space-y-3">
            <button
              type="button"
              className="app-button-primary w-full justify-center"
            >
              {t("dqActionApproveFix")}
            </button>
            <button
              type="button"
              className="app-button-secondary w-full justify-center"
            >
              {t("dqActionRequestRuleChange")}
            </button>
            <button
              type="button"
              className="app-button-secondary w-full justify-center"
            >
              {t("dqActionAcceptException")}
            </button>
            <button
              type="button"
              className="app-button-secondary w-full justify-center"
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
