import { useI18n } from "@shared/i18n/use-i18n";

export function CategoriesManagementPage() {
  const { t } = useI18n();

  return (
    <section className="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
      <h1 className="text-2xl font-bold text-slate-900">
        {t("catalogCategoriesTitle")}
      </h1>
      <p className="mt-2 text-slate-600">{t("catalogCategoriesDescription")}</p>
      <div className="mt-6 rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">
        {t("catalogScaffoldNote")}
      </div>
    </section>
  );
}
