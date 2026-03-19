import { Link } from "react-router-dom";
import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";

export function SearchPage() {
  const { t } = useI18n();

  return (
    <section className="app-page">
      <PageIntro
        eyebrow={t("shellPublicSection")}
        title={t("navSearch")}
        description={t("catalogPublicDescription")}
        badges={[t("shellPublicLabel"), t("catalogFeatureInstitutional")]}
        actions={
          <Link
            to="/catalog"
            className="app-button-primary min-w-[220px] justify-center"
          >
            {t("navCatalog")}
          </Link>
        }
      />

      <div className="grid gap-5 lg:grid-cols-3">
        <article className="app-panel p-5">
          <p className="app-kicker">Discovery</p>
          <h2 className="mt-2 text-lg font-semibold text-slate-900">
            Catalog-first Search
          </h2>
          <p className="mt-3 text-sm leading-7 text-slate-600">
            Refined query filtering, branch scope, language selection, and
            metadata-based exploration are available in the catalog page.
          </p>
        </article>

        <article className="app-panel p-5">
          <p className="app-kicker">Current Mode</p>
          <h2 className="mt-2 text-lg font-semibold text-slate-900">
            Institutional Search Shell
          </h2>
          <p className="mt-3 text-sm leading-7 text-slate-600">
            The dedicated route is retained for clear navigation and future
            expansion while current operational search is delivered through
            catalog filters.
          </p>
        </article>

        <article className="app-panel p-5">
          <p className="app-kicker">Navigation</p>
          <h2 className="mt-2 text-lg font-semibold text-slate-900">
            Continue to Catalog
          </h2>
          <p className="mt-3 text-sm leading-7 text-slate-600">
            Use title, author, category, branch, and language controls to find
            relevant books quickly.
          </p>
          <Link to="/catalog" className="app-button-secondary mt-4">
            {t("navCatalog")}
          </Link>
        </article>
      </div>
    </section>
  );
}
