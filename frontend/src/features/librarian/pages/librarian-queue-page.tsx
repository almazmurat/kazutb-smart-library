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
import {
  getAppReviewEntityTypeLabel,
  getAppReviewFlagStatusLabel,
  getAppReviewIssueCodeLabel,
  getAppReviewSeverityLabel,
} from "@shared/i18n/domain-labels";

const severityOptions = ["CRITICAL", "HIGH", "MEDIUM", "LOW"] as const;

const entityTypeOptions = [
  "document",
  "book_copy",
  "reader",
  "service_point",
  "storage_sigla",
] as const;

const issueCodeOptions = [
  "missing_title",
  "missing_isbn",
  "invalid_isbn",
  "invalid_language_code",
  "missing_author_link",
  "orphan_copy_document",
  "missing_inventory_number",
  "missing_branch_mapping",
  "location_mapping_conflict",
  "location_mapping_requires_confirmation",
  "missing_reader_name",
  "missing_reader_email",
  "reader_location_mapping_requires_confirmation",
] as const;

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
  const { t, locale } = useI18n();
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
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

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

  // useMemo must be called unconditionally — before any early returns
  const suggestedId = useMemo(() => {
    if (!detailQuery.data?.issue?.details) {
      return undefined;
    }
    return extractSuggestionId(detailQuery.data.issue.details);
  }, [detailQuery.data]);

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
      <div className="app-empty-state text-sm text-[var(--ink-500)]">
        {t("catalogLoading")}
      </div>
    );
  }

  if (queueQuery.isError) {
    return (
      <div className="space-y-4">
        <PageIntro
          eyebrow={t("shellOperationsSection")}
          title="Очередь проверки"
          description="Сервис временно недоступен. Попробуйте обновить страницу или вернитесь позже."
          badges={[t("shellSecureLabel"), "Контроль качества данных"]}
        />
        <div className="app-state-warning">
          <p className="font-medium">
            Не удалось загрузить данные очереди проверки.
          </p>
          <p className="mt-2 text-[var(--ink-500)]">
            Система будет снова доступна через несколько минут. Вы можете
            перейти в другие рабочие разделы.
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

  const issues = queueQuery.data?.data || [];
  const campuses = facetsQuery.data?.campuses ?? [];
  const servicePoints = facetsQuery.data?.servicePoints ?? [];
  const detail = detailQuery.data;

  if (issues.length === 0) {
    return (
      <div className="space-y-4">
        <PageIntro
          eyebrow={t("shellOperationsSection")}
          title="Очередь проверки"
          description="По выбранным фильтрам замечаний не найдено."
          badges={[t("shellSecureLabel"), "Контроль качества данных"]}
        />

        <div className="app-empty-state">
          <p className="text-sm text-[var(--ink-500)]">
            {t("librarianQueueEmpty")}
          </p>
        </div>
      </div>
    );
  }

  const totalPages = queueQuery.data?.meta.totalPages || 1;

  return (
    <section className="app-page">
      <PageIntro
        eyebrow={t("shellOperationsSection")}
        title="Очередь проверки библиотекаря"
        description="Проверяйте проблемные записи и выполняйте корректировки по приоритету и локации."
        badges={[t("shellSecureLabel"), "Проверка записей"]}
      />

      <div className="app-toolbar">
        <div>
          <p className="app-kicker">Открытые замечания</p>
          <p className="mt-2 text-sm text-[var(--ink-500)]">
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
            <option value="">Все уровни критичности</option>
            {severityOptions.map((level) => (
              <option key={level} value={level}>
                {getAppReviewSeverityLabel(locale, level)}
              </option>
            ))}
          </select>
          <select
            className="app-form-control"
            value={issueCode}
            onChange={(event) => {
              setIssueCode(event.target.value);
              setPage(1);
            }}
          >
            <option value="">Все коды замечаний</option>
            {issueCodeOptions.map((code) => (
              <option key={code} value={code}>
                {getAppReviewIssueCodeLabel(locale, code)}
              </option>
            ))}
          </select>
          <select
            className="app-form-control"
            value={entityType}
            onChange={(event) => {
              setEntityType(event.target.value);
              setPage(1);
            }}
          >
            <option value="">Все типы сущностей</option>
            {entityTypeOptions.map((option) => (
              <option key={option} value={option}>
                {getAppReviewEntityTypeLabel(locale, option)}
              </option>
            ))}
          </select>
          <select
            value={campusCode}
            onChange={(event) => {
              setCampusCode(event.target.value);
              setPage(1);
            }}
            className="app-form-control"
          >
            <option value="">Все кампусы</option>
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
            <option value="">Все пункты обслуживания</option>
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
          <div className="flex items-center justify-between gap-3 border-b border-[rgba(18,59,114,0.14)] pb-4">
            <div>
              <p className="app-kicker">Текущие задачи</p>
              <h2 className="app-section-heading">Список очереди</h2>
            </div>
            <span className="app-chip-muted">
              {queueQuery.data?.meta.total ?? 0} записей
            </span>
          </div>

          <div className="mt-4 space-y-3">
            {issues.map((issue) => {
              const flaggedDate = new Date(
                issue.flaggedAt,
              ).toLocaleDateString();
              const isSelected = selectedFlagId === issue.flagId;

              return (
                <button
                  key={issue.flagId}
                  type="button"
                  className={`app-queue-card w-full ${isSelected ? "app-queue-card-active" : ""}`}
                  onClick={() => {
                    setStatusMessage(null);
                    setErrorMessage(null);
                    setSelectedFlagId(issue.flagId);
                  }}
                >
                  <div className="flex flex-wrap items-start justify-between gap-3">
                    <div className="text-left">
                      <div className="text-base font-semibold text-slate-950">
                        {getAppReviewIssueCodeLabel(locale, issue.issueCode)}
                      </div>
                      <div className="mt-1 text-xs text-[var(--ink-500)]">
                        {getAppReviewEntityTypeLabel(locale, issue.entityType)}{" "}
                        • {flaggedDate}
                      </div>
                    </div>
                    <span className="app-chip-muted">
                      {getAppReviewSeverityLabel(locale, issue.severity)}
                    </span>
                  </div>

                  <p className="mt-3 text-sm font-medium text-[var(--ink-900)]">
                    {issue.context.title ||
                      issue.values.raw ||
                      "Название не указано"}
                  </p>

                  <div className="mt-3 grid gap-2 text-xs text-[var(--ink-500)] md:grid-cols-2">
                    <div>
                      <span className="font-medium text-[var(--ink-700)]">
                        Кампус:{" "}
                      </span>
                      {(issue.context.campusCodes || [])
                        .map((code) => toReadableLocation(code, locale))
                        .join(", ") || "-"}
                    </div>
                    <div>
                      <span className="font-medium text-[var(--ink-700)]">
                        Пункт обслуживания:{" "}
                      </span>
                      {(issue.context.servicePointCodes || [])
                        .map((code) => toReadableLocation(code, locale))
                        .join(", ") || "-"}
                    </div>
                  </div>

                  <div className="mt-3 rounded-2xl border border-[rgba(18,59,114,0.13)] bg-[var(--surface-muted)] px-3 py-2 text-sm text-[var(--ink-500)]">
                    Предложение: {issue.values.suggested || "Не найдено"}
                  </div>
                </button>
              );
            })}
          </div>
        </section>

        {selectedFlagId && detail ? (
          <article className="app-panel-strong p-5 xl:sticky xl:top-24 xl:h-fit">
            <p className="app-kicker">Рабочая область исправления</p>
            <h3 className="mt-2 text-base font-semibold text-[var(--ink-900)]">
              Детали замечания и действия
            </h3>
            <p className="mt-2 text-sm text-[var(--ink-500)]">
              {getAppReviewIssueCodeLabel(locale, detail.issue.issueCode)} •{" "}
              {getAppReviewSeverityLabel(locale, detail.issue.severity)} •{" "}
              {getAppReviewEntityTypeLabel(locale, detail.issue.entityType)}
            </p>

            <div className="mt-4 grid gap-3 md:grid-cols-2">
              <div className="app-stat-card p-3">
                <p className="text-xs uppercase tracking-[0.14em] text-[var(--ink-500)]">
                  Исходное значение
                </p>
                <p className="mt-2 text-sm text-[var(--ink-900)]">
                  {detail.issue.values.raw || "-"}
                </p>
              </div>
              <div className="app-stat-card p-3">
                <p className="text-xs uppercase tracking-[0.14em] text-[var(--ink-500)]">
                  Текущее нормализованное
                </p>
                <p className="mt-2 text-sm text-[var(--ink-900)]">
                  {detail.issue.values.normalized || "-"}
                </p>
              </div>
              <div className="rounded-xl border border-emerald-200 bg-emerald-50 p-3 md:col-span-2">
                <p className="text-xs uppercase tracking-[0.14em] text-emerald-700">
                  Рекомендуемое исправление
                </p>
                <p className="mt-2 text-sm font-medium text-emerald-900">
                  {detail.issue.values.suggested || "-"}
                </p>
              </div>
            </div>

            <div className="mt-4 grid gap-2 text-sm text-[var(--ink-700)]">
              <p>
                <span className="font-medium">Документ: </span>
                {detail.issue.context.title || "-"}
              </p>
              <p>
                <span className="font-medium">Кампус: </span>
                {(detail.issue.context.campusCodes || [])
                  .map((code) => toReadableLocation(code, locale))
                  .join(", ") || "-"}
              </p>
              <p>
                <span className="font-medium">Статус: </span>
                {getAppReviewFlagStatusLabel(locale, detail.issue.flagStatus)}
              </p>
            </div>

            <div className="mt-4 space-y-3 rounded-[24px] border border-[rgba(18,59,114,0.15)] bg-white/85 p-4">
              <label className="space-y-1 text-sm">
                <span className="text-[var(--ink-500)]">
                  Комментарий (необязательно)
                </span>
                <textarea
                  className="app-form-control min-h-[84px]"
                  value={noteDraft}
                  onChange={(event) => setNoteDraft(event.target.value)}
                  placeholder="Добавьте комментарий"
                />
              </label>

              <label className="space-y-1 text-sm">
                <span className="text-[var(--ink-500)]">
                  Значение для ручного исправления
                </span>
                <input
                  className="app-form-control"
                  value={manualValue}
                  onChange={(event) => setManualValue(event.target.value)}
                  placeholder="Введите новое значение"
                />
              </label>

              <div className="flex flex-wrap gap-2">
                <button
                  type="button"
                  className="app-button-primary"
                  disabled={actionMutation.isPending}
                  onClick={() => {
                    setStatusMessage(null);
                    setErrorMessage(null);
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
                            "Предложение принято, запись обновлена.",
                          );
                          setSelectedFlagId("");
                          setNoteDraft("");
                        },
                        onError: (error: any) => {
                          setErrorMessage(
                            error?.response?.data?.message ||
                              "Не удалось принять предложение.",
                          );
                        },
                      },
                    );
                  }}
                >
                  Принять предложение
                </button>

                <button
                  type="button"
                  className="app-button-secondary"
                  disabled={actionMutation.isPending}
                  onClick={() => {
                    setStatusMessage(null);
                    setErrorMessage(null);
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
                            "Предложение отклонено, замечание закрыто.",
                          );
                          setSelectedFlagId("");
                          setNoteDraft("");
                        },
                        onError: (error: any) => {
                          setErrorMessage(
                            error?.response?.data?.message ||
                              "Не удалось отклонить предложение.",
                          );
                        },
                      },
                    );
                  }}
                >
                  Отклонить предложение
                </button>

                <button
                  type="button"
                  className="app-button-secondary"
                  disabled={actionMutation.isPending || !manualValue.trim()}
                  onClick={() => {
                    setStatusMessage(null);
                    setErrorMessage(null);
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
                            "Ручное исправление применено, запись обновлена.",
                          );
                          setSelectedFlagId("");
                          setManualValue("");
                          setNoteDraft("");
                        },
                        onError: (error: any) => {
                          setErrorMessage(
                            error?.response?.data?.message ||
                              "Не удалось применить ручное исправление.",
                          );
                        },
                      },
                    );
                  }}
                >
                  Применить исправление
                </button>
              </div>

              {actionMutation.isError ? (
                <div className="app-state-error">
                  Не удалось выполнить действие. Проверьте данные и повторите.
                </div>
              ) : null}

              {errorMessage ? (
                <div className="app-state-error">{errorMessage}</div>
              ) : null}

              {statusMessage ? (
                <div className="app-state-success">{statusMessage}</div>
              ) : null}
            </div>

            <p className="mt-4 text-sm text-[var(--ink-700)]">
              Связанные замечания: {(detail.relatedIssues || []).length}
            </p>
          </article>
        ) : (
          <aside className="app-panel flex min-h-[22rem] items-center justify-center p-8 text-center text-sm leading-7 text-[var(--ink-700)]">
            Выберите запись из очереди, чтобы открыть рабочую область
            исправления. Справа отобразятся исходное значение, нормализованное
            значение, предложенное исправление и доступные действия.
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
          <span className="flex items-center px-3 text-sm text-[var(--ink-500)]">
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
