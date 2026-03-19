import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";

export function LoginPage() {
  const { t } = useI18n();

  return (
    <section className="app-page">
      <PageIntro
        eyebrow={t("shellSecureLabel")}
        title={t("loginTitle")}
        description={t("loginDescription")}
        badges={[t("shellSecureLabel"), t("roleStudent"), t("roleLibrarian")]}
      />

      <div className="grid gap-5 lg:grid-cols-[1.1fr_0.9fr]">
        <article className="app-panel-strong p-6 md:p-7">
          <p className="app-kicker">Institution Access</p>
          <h2 className="mt-2 app-section-heading">{t("loginTitle")}</h2>
          <p className="mt-3 text-sm leading-7 text-slate-600">
            {t("loginDescription")}
          </p>

          <div className="mt-6 grid gap-3 md:grid-cols-2">
            <div className="app-subpanel p-4">
              <p className="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                Directory
              </p>
              <p className="mt-2 text-sm text-slate-700">
                University account credentials
              </p>
            </div>
            <div className="app-subpanel p-4">
              <p className="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                Security
              </p>
              <p className="mt-2 text-sm text-slate-700">
                Role-based protected modules
              </p>
            </div>
          </div>
        </article>

        <article className="app-panel p-6 md:p-7">
          <p className="app-kicker">Implementation Status</p>
          <div className="mt-4 app-state-warning">{t("loginScaffoldNote")}</div>
          <div className="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm leading-7 text-slate-600">
            Use the main navigation to continue exploring catalog, circulation,
            analytics, and migration readiness modules.
          </div>
        </article>
      </div>
    </section>
  );
}
