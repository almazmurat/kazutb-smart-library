import { useI18n } from "@shared/i18n/use-i18n";

export function AuthorsManagementPage() {
  const { t } = useI18n();

  return (
    <section className="app-panel-strong p-6 md:p-7">
      <p className="app-kicker">Catalog Management</p>
      <h1 className="mt-2 text-2xl font-semibold text-slate-900">
        {t("catalogAuthorsTitle")}
      </h1>
      <p className="mt-3 text-sm leading-7 text-slate-600">
        {t("catalogAuthorsDescription")}
      </p>
      <div className="app-state-warning mt-6">{t("catalogScaffoldNote")}</div>
    </section>
  );
}
