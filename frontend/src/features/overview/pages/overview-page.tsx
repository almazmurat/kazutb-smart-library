import { Link } from "react-router-dom";

import { useAuthState } from "@shared/auth/auth-store";
import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";
import type { TranslationKey } from "@shared/i18n/dictionary";

export function OverviewPage() {
  const { t } = useI18n();
  const auth = useAuthState();

  const roleKey: Record<string, TranslationKey> = {
    GUEST: "roleGuest",
    STUDENT: "roleStudent",
    TEACHER: "roleTeacher",
    LIBRARIAN: "roleLibrarian",
    ADMIN: "roleAdmin",
    ANALYST: "roleAnalyst",
  };

  const quickAccess = [
    {
      to: "/catalog",
      title: t("catalogPublicTitle"),
      description: t("overviewAccessCatalogDescription"),
      secure: false,
    },
    {
      to: "/cabinet",
      title: t("navCabinet"),
      description: t("overviewAccessCabinetDescription"),
      secure: true,
    },
    {
      to: "/librarian",
      title: t("navLibrarian"),
      description: t("overviewAccessLibrarianDescription"),
      secure: true,
    },
    {
      to: "/analytics",
      title: t("navAnalytics"),
      description: t("overviewAccessAnalyticsDescription"),
      secure: true,
    },
    {
      to: "/reports",
      title: t("navReports"),
      description: t("overviewAccessReportsDescription"),
      secure: true,
    },
    {
      to: "/librarian/circulation",
      title: t("navCirculation"),
      description: t("overviewModuleCirculationDescription"),
      secure: true,
    },
  ];

  const demoFlows = [
    {
      to: "/catalog",
      title: t("overviewFlowGuestTitle"),
      description: t("overviewFlowGuestDescription"),
      action: t("overviewFlowGuestAction"),
      secure: false,
    },
    {
      to: "/cabinet",
      title: t("overviewFlowReaderTitle"),
      description: t("overviewFlowReaderDescription"),
      action: t("overviewFlowReaderAction"),
      secure: true,
    },
    {
      to: "/librarian",
      title: t("overviewFlowLibrarianTitle"),
      description: t("overviewFlowLibrarianDescription"),
      action: t("overviewFlowLibrarianAction"),
      secure: true,
    },
    {
      to: "/analytics",
      title: t("overviewFlowAdminTitle"),
      description: t("overviewFlowAdminDescription"),
      action: t("overviewFlowAdminAction"),
      secure: true,
    },
  ];

  const modules = [
    {
      title: t("navCatalog"),
      description: t("overviewModuleCatalogDescription"),
      status: t("overviewStatusOperational"),
    },
    {
      title: t("navLibrarian"),
      description: t("overviewModuleReservationsDescription"),
      status: t("overviewStatusOperational"),
    },
    {
      title: t("navCirculation"),
      description: t("overviewModuleCirculationDescription"),
      status: t("overviewStatusOperational"),
    },
    {
      title: t("navAnalytics"),
      description: t("overviewModuleAnalyticsDescription"),
      status: t("overviewStatusMvp"),
    },
    {
      title: t("navReports"),
      description: t("overviewModuleReportsDescription"),
      status: t("overviewStatusMvp"),
    },
  ];

  const nextPhase = [t("overviewRoadmapAdmin"), t("overviewRoadmapDigital")];

  return (
    <div className="app-page">
      <PageIntro
        eyebrow={t("overviewEyebrow")}
        title={t("overviewTitle")}
        description={t("overviewDescription")}
        badges={[
          t("overviewStatScopes"),
          t("overviewStatBranches"),
          t("overviewStatModules"),
          `${t("shellCurrentRole")}: ${t(roleKey[auth.role] ?? "roleGuest")}`,
        ]}
        actions={
          <div className="flex flex-wrap gap-2.5">
            <Link to="/catalog" className="app-button-primary">
              {t("overviewHeroPrimary")}
            </Link>
            <Link to="/login" className="app-button-secondary">
              {t("overviewHeroSecondary")}
            </Link>
          </div>
        }
      >
        <div className="grid gap-4 xl:grid-cols-[1.15fr_0.85fr]">
          <div className="grid gap-4 md:grid-cols-3">
            <div className="app-stat-card">
              <p className="text-sm font-semibold text-slate-900">
                {t("overviewAudienceTitle")}
              </p>
              <p className="mt-3 text-sm leading-6 text-slate-600">
                {t("overviewAudienceDescription")}
              </p>
            </div>
            <div className="app-stat-card">
              <p className="text-sm font-semibold text-slate-900">
                {t("overviewProcessTitle")}
              </p>
              <p className="mt-3 text-sm leading-6 text-slate-600">
                {t("overviewProcessDescription")}
              </p>
            </div>
            <div className="app-stat-card">
              <p className="text-sm font-semibold text-slate-900">
                {t("overviewReadinessTitle")}
              </p>
              <p className="mt-3 text-sm leading-6 text-slate-600">
                {t("overviewReadinessDescription")}
              </p>
            </div>
          </div>

          <div className="app-flow-step">
            <p className="app-kicker text-white/70">Сценарий работы</p>
            <h2 className="app-display-title mt-2 text-2xl font-semibold">
              Единая точка доступа для читателей и сотрудников
            </h2>
            <p className="mt-3 text-sm leading-7 text-white/82">
              Начните с поиска в каталоге, откройте карточку книги, затем
              перейдите в профильные разделы по роли.
            </p>
            <div className="mt-4 flex flex-wrap gap-2">
              <span className="rounded-full bg-white/12 px-3 py-1 text-xs font-medium text-white">
                Единая навигация
              </span>
              <span className="rounded-full bg-white/12 px-3 py-1 text-xs font-medium text-white">
                Ролевой доступ
              </span>
              <span className="rounded-full bg-white/12 px-3 py-1 text-xs font-medium text-white">
                Рабочие процессы библиотеки
              </span>
            </div>
          </div>
        </div>

        <div className="mt-4">
          <p className="mb-2 text-sm font-semibold text-slate-900">
            {t("overviewCapabilitiesTitle")}
          </p>
          <div className="flex flex-wrap gap-2">
            {[
              t("overviewCapabilityCatalog"),
              t("overviewCapabilityCirculation"),
              t("overviewCapabilityAnalytics"),
            ].map((item) => (
              <span
                key={item}
                className="app-chip border-blue-100 bg-blue-50/90 text-blue-700"
              >
                {item}
              </span>
            ))}
          </div>
        </div>
      </PageIntro>

      <section className="app-panel-strong p-6 md:p-7">
        <div className="flex flex-wrap items-end justify-between gap-4">
          <div>
            <p className="app-kicker">Сценарии</p>
            <h2 className="app-section-heading">
              {t("overviewDemoFlowsTitle")}
            </h2>
          </div>
          <p className="max-w-xl text-sm leading-6 text-slate-600">
            Основные пользовательские сценарии сгруппированы так, чтобы быстро
            переходить от поиска к профильным рабочим разделам.
          </p>
        </div>
        <div className="app-card-grid mt-5 md:grid-cols-2 xl:grid-cols-4">
          {demoFlows.map((flow) => (
            <article
              key={flow.title}
              className="app-feature-card flex h-full flex-col"
            >
              <div className="flex items-center justify-between gap-3">
                <h3 className="text-base font-semibold text-slate-900">
                  {flow.title}
                </h3>
                <span
                  className={`rounded-full px-2.5 py-1 text-[11px] font-medium ${flow.secure ? "bg-slate-100 text-slate-700" : "bg-blue-50 text-blue-700"}`}
                >
                  {flow.secure ? t("shellSecureLabel") : t("shellPublicLabel")}
                </span>
              </div>
              <p className="mt-4 grow text-sm leading-6 text-slate-600">
                {flow.description}
              </p>
              <Link
                to={flow.to}
                className="app-button-secondary mt-5 justify-center border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100"
              >
                {flow.action}
              </Link>
            </article>
          ))}
        </div>
      </section>

      <section className="app-panel p-6 md:p-7">
        <p className="app-kicker">Разделы</p>
        <h2 className="app-section-heading">{t("overviewQuickAccessTitle")}</h2>
        <div className="app-card-grid mt-5 md:grid-cols-2 xl:grid-cols-3">
          {quickAccess.map((item) => (
            <Link key={item.to} to={item.to} className="app-feature-card">
              <div className="flex items-center justify-between gap-3">
                <h3 className="text-base font-semibold text-slate-900">
                  {item.title}
                </h3>
                <span
                  className={`rounded-full px-2.5 py-1 text-[11px] font-medium ${item.secure ? "bg-slate-100 text-slate-700" : "bg-blue-50 text-blue-700"}`}
                >
                  {item.secure ? t("shellSecureLabel") : t("shellPublicLabel")}
                </span>
              </div>
              <p className="mt-4 text-sm leading-6 text-slate-600">
                {item.description}
              </p>
            </Link>
          ))}
        </div>
      </section>

      <section className="app-panel p-6 md:p-7">
        <p className="app-kicker">Модули</p>
        <h2 className="app-section-heading">{t("overviewModuleTitle")}</h2>
        <div className="app-card-grid mt-5 md:grid-cols-2 xl:grid-cols-3">
          {modules.map((module) => (
            <article key={module.title} className="app-stat-card">
              <div className="flex items-center justify-between gap-3">
                <h3 className="text-base font-semibold text-slate-900">
                  {module.title}
                </h3>
                <span className="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-700">
                  {module.status}
                </span>
              </div>
              <p className="mt-3 text-sm leading-6 text-slate-600">
                {module.description}
              </p>
            </article>
          ))}
        </div>
      </section>

      <div className="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <section className="app-panel p-6 md:p-7">
          <p className="app-kicker">Пользователи</p>
          <h2 className="app-section-heading">{t("overviewRoleTitle")}</h2>
          <div className="app-card-grid mt-4 md:grid-cols-3">
            <article className="app-stat-card">
              <h3 className="text-base font-semibold text-slate-900">
                {t("overviewRoleStudentsTitle")}
              </h3>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                {t("overviewRoleStudentsDescription")}
              </p>
            </article>
            <article className="app-stat-card">
              <h3 className="text-base font-semibold text-slate-900">
                {t("overviewRoleLibrarianTitle")}
              </h3>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                {t("overviewRoleLibrarianDescription")}
              </p>
            </article>
            <article className="app-stat-card">
              <h3 className="text-base font-semibold text-slate-900">
                {t("overviewRoleAdminTitle")}
              </h3>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                {t("overviewRoleAdminDescription")}
              </p>
            </article>
          </div>
        </section>

        <section className="app-panel p-6 md:p-7">
          <p className="app-kicker">Локации</p>
          <h2 className="app-section-heading">{t("overviewBranchTitle")}</h2>
          <div className="mt-4 space-y-3 text-sm leading-6 text-slate-600">
            <div className="app-stat-card">
              <p className="font-semibold text-slate-900">
                {t("overviewBranchUniversity")}
              </p>
              <p>{t("overviewBranchUniversityDescription")}</p>
            </div>
            <div className="app-stat-card">
              <p className="font-semibold text-slate-900">
                {t("overviewBranchCollege")}
              </p>
              <p>{t("overviewBranchCollegeDescription")}</p>
            </div>
          </div>
        </section>
      </div>

      <section className="app-panel p-6 md:p-7">
        <p className="app-kicker">Дальнейшее развитие</p>
        <h2 className="app-section-heading">{t("overviewRoadmapTitle")}</h2>
        <div className="app-card-grid mt-4 md:grid-cols-3">
          {nextPhase.map((item, index) => (
            <div key={item} className="app-stat-card">
              <span className="app-chip-muted">
                {t("overviewStatusPlanned")}
              </span>
              <p className="mt-4 text-sm leading-6 text-slate-600">{item}</p>
              <div className="mt-5 text-xs uppercase tracking-[0.16em] text-slate-400">
                0{index + 1}
              </div>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
}
