import { Link } from "react-router-dom";

import { authStore } from "@shared/auth/auth-store";
import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";
import type { TranslationKey } from "@shared/i18n/dictionary";

export function OverviewPage() {
  const { t } = useI18n();

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

  const nextPhase = [
    t("overviewRoadmapAdmin"),
    t("overviewRoadmapMigration"),
    t("overviewRoadmapDigital"),
  ];

  return (
    <div className="space-y-8">
      <PageIntro
        eyebrow={t("overviewEyebrow")}
        title={t("overviewTitle")}
        description={t("overviewDescription")}
        badges={[
          t("overviewStatScopes"),
          t("overviewStatBranches"),
          t("overviewStatModules"),
          `${t("shellCurrentRole")}: ${t(roleKey[authStore.role] ?? "roleGuest")}`,
        ]}
        actions={
          <div className="flex flex-wrap gap-3">
            <Link
              to="/catalog"
              className="rounded-md bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800"
            >
              {t("overviewHeroPrimary")}
            </Link>
            <Link
              to="/login"
              className="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
              {t("overviewHeroSecondary")}
            </Link>
          </div>
        }
      >
        <div className="grid gap-3 md:grid-cols-3">
          <div className="rounded-xl border border-white/70 bg-white/80 p-4">
            <p className="text-sm font-semibold text-slate-900">
              {t("overviewAudienceTitle")}
            </p>
            <p className="mt-2 text-sm leading-6 text-slate-600">
              {t("overviewAudienceDescription")}
            </p>
          </div>
          <div className="rounded-xl border border-white/70 bg-white/80 p-4">
            <p className="text-sm font-semibold text-slate-900">
              {t("overviewProcessTitle")}
            </p>
            <p className="mt-2 text-sm leading-6 text-slate-600">
              {t("overviewProcessDescription")}
            </p>
          </div>
          <div className="rounded-xl border border-white/70 bg-white/80 p-4">
            <p className="text-sm font-semibold text-slate-900">
              {t("overviewReadinessTitle")}
            </p>
            <p className="mt-2 text-sm leading-6 text-slate-600">
              {t("overviewReadinessDescription")}
            </p>
          </div>
        </div>
      </PageIntro>

      <section className="rounded-2xl border border-blue-100 bg-white p-6 shadow-sm">
        <h2 className="text-xl font-semibold text-slate-900">
          {t("overviewQuickAccessTitle")}
        </h2>
        <div className="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
          {quickAccess.map((item) => (
            <Link
              key={item.to}
              to={item.to}
              className="rounded-xl border border-slate-200 p-4 transition hover:border-blue-300 hover:shadow-sm"
            >
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
              <p className="mt-3 text-sm leading-6 text-slate-600">
                {item.description}
              </p>
            </Link>
          ))}
        </div>
      </section>

      <section className="rounded-2xl border border-blue-100 bg-white p-6 shadow-sm">
        <h2 className="text-xl font-semibold text-slate-900">
          {t("overviewModuleTitle")}
        </h2>
        <div className="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
          {modules.map((module) => (
            <article
              key={module.title}
              className="rounded-xl border border-slate-200 p-4"
            >
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
        <section className="rounded-2xl border border-blue-100 bg-white p-6 shadow-sm">
          <h2 className="text-xl font-semibold text-slate-900">
            {t("overviewRoleTitle")}
          </h2>
          <div className="mt-4 grid gap-4 md:grid-cols-3">
            <article className="rounded-xl border border-slate-200 p-4">
              <h3 className="text-base font-semibold text-slate-900">
                {t("overviewRoleStudentsTitle")}
              </h3>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                {t("overviewRoleStudentsDescription")}
              </p>
            </article>
            <article className="rounded-xl border border-slate-200 p-4">
              <h3 className="text-base font-semibold text-slate-900">
                {t("overviewRoleLibrarianTitle")}
              </h3>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                {t("overviewRoleLibrarianDescription")}
              </p>
            </article>
            <article className="rounded-xl border border-slate-200 p-4">
              <h3 className="text-base font-semibold text-slate-900">
                {t("overviewRoleAdminTitle")}
              </h3>
              <p className="mt-2 text-sm leading-6 text-slate-600">
                {t("overviewRoleAdminDescription")}
              </p>
            </article>
          </div>
        </section>

        <section className="rounded-2xl border border-blue-100 bg-white p-6 shadow-sm">
          <h2 className="text-xl font-semibold text-slate-900">
            {t("overviewBranchTitle")}
          </h2>
          <div className="mt-4 space-y-3 text-sm leading-6 text-slate-600">
            <div className="rounded-xl border border-slate-200 p-4">
              <p className="font-semibold text-slate-900">
                {t("overviewBranchUniversity")}
              </p>
              <p>{t("overviewBranchUniversityDescription")}</p>
            </div>
            <div className="rounded-xl border border-slate-200 p-4">
              <p className="font-semibold text-slate-900">
                {t("overviewBranchCollege")}
              </p>
              <p>{t("overviewBranchCollegeDescription")}</p>
            </div>
          </div>
        </section>
      </div>

      <section className="rounded-2xl border border-blue-100 bg-white p-6 shadow-sm">
        <h2 className="text-xl font-semibold text-slate-900">
          {t("overviewRoadmapTitle")}
        </h2>
        <div className="mt-4 grid gap-4 md:grid-cols-3">
          {nextPhase.map((item) => (
            <div key={item} className="rounded-xl border border-slate-200 p-4">
              <span className="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-medium text-slate-700">
                {t("overviewStatusPlanned")}
              </span>
              <p className="mt-3 text-sm leading-6 text-slate-600">{item}</p>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
}
