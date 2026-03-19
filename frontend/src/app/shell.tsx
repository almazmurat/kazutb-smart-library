import { Link, NavLink, Outlet } from "react-router-dom";
import { authStore } from "@shared/auth/auth-store";
import { LanguageSwitcher } from "@shared/i18n/language-switcher";
import { useI18n } from "@shared/i18n/use-i18n";
import type { TranslationKey } from "@shared/i18n/dictionary";

export function AppShell() {
  const { t } = useI18n();
  const currentYear = new Date().getFullYear();

  const roleLabelKey: Record<string, TranslationKey> = {
    GUEST: "roleGuest",
    STUDENT: "roleStudent",
    TEACHER: "roleTeacher",
    LIBRARIAN: "roleLibrarian",
    ADMIN: "roleAdmin",
    ANALYST: "roleAnalyst",
  };

  const navSections = [
    {
      title: t("shellPublicSection"),
      badge: t("shellPublicLabel"),
      items: [
        { to: "/overview", label: t("navOverview") },
        { to: "/catalog", label: t("navCatalog") },
        { to: "/search", label: t("navSearch") },
      ],
    },
    {
      title: t("shellReaderSection"),
      badge: t("shellSecureLabel"),
      items: [{ to: "/cabinet", label: t("navCabinet") }],
    },
    {
      title: t("shellOperationsSection"),
      badge: t("shellSecureLabel"),
      items: [
        { to: "/librarian", label: t("navLibrarian") },
        { to: "/librarian/circulation", label: t("navCirculation") },
        { to: "/analytics", label: t("navAnalytics") },
        { to: "/reports", label: t("navReports") },
        {
          to: "/migration/data-quality",
          label: t("navDataQualityWorkbench"),
        },
      ],
    },
    {
      title: t("shellAdministrationSection"),
      badge: t("shellSecureLabel"),
      items: [
        { to: "/librarian/catalog/books", label: t("navCatalogBooksMgmt") },
        {
          to: "/librarian/catalog/authors",
          label: t("navCatalogAuthorsMgmt"),
        },
        {
          to: "/librarian/catalog/categories",
          label: t("navCatalogCategoriesMgmt"),
        },
        { to: "/librarian/catalog/copies", label: t("navCatalogCopiesMgmt") },
        { to: "/admin", label: t("navAdmin") },
      ],
    },
  ];

  return (
    <div className="min-h-screen">
      <header className="sticky top-0 z-20 border-b border-white/70 bg-[rgba(247,250,255,0.9)] shadow-[0_8px_26px_rgba(15,23,42,0.08)] backdrop-blur-xl">
        <div className="border-b border-white/80 bg-[linear-gradient(90deg,rgba(232,240,255,0.82),rgba(255,255,255,0.6))]">
          <div className="app-container flex flex-wrap items-center justify-between gap-2 py-2.5 text-xs text-slate-600">
            <span className="font-medium text-slate-700">
              KazUTB Smart Library Platform
            </span>
            <div className="flex flex-wrap items-center gap-2">
              <span className="app-chip-muted">
                {t("shellCurrentRole")}: {t(roleLabelKey[authStore.role])}
              </span>
              <LanguageSwitcher />
            </div>
          </div>
        </div>

        <div className="app-container py-4">
          <div className="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <Link to="/overview" className="flex items-start gap-4">
              <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(160deg,#0b3d91,#2d64bc)] text-lg font-bold text-white shadow-[0_14px_30px_rgba(29,79,163,0.24)]">
                K
              </div>
              <div className="max-w-xl">
                <div className="text-lg font-semibold tracking-tight text-primary-900">
                  {t("appTitle")}
                </div>
                <p className="mt-1 text-sm leading-6 text-slate-500">
                  {t("shellSubtitle")}
                </p>
              </div>
            </Link>
            <div className="flex flex-wrap items-center gap-3 lg:justify-end">
              <NavLink to="/login" className="app-button-primary">
                {t("login")}
              </NavLink>
            </div>
          </div>

          <div className="mt-4 grid gap-3 lg:grid-cols-2 xl:grid-cols-4">
            {navSections.map((section) => (
              <div
                key={section.title}
                className="rounded-[24px] border border-white/70 bg-[linear-gradient(180deg,rgba(255,255,255,0.98),rgba(245,249,255,0.92))] p-4 shadow-[0_14px_30px_rgba(15,23,42,0.06)]"
              >
                <div className="mb-2 flex items-center justify-between gap-3">
                  <p className="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                    {section.title}
                  </p>
                  <span className="app-chip-muted px-2.5 py-1 text-[11px]">
                    {section.badge}
                  </span>
                </div>
                <nav className="flex flex-wrap gap-1.5 text-sm">
                  {section.items.map((item) => (
                    <NavLink
                      key={item.to}
                      to={item.to}
                      className={({ isActive }) =>
                        `rounded-xl px-3 py-2 transition duration-200 ${
                          isActive
                            ? "bg-[linear-gradient(135deg,rgba(232,240,255,0.96),rgba(245,248,255,0.98))] text-primary-700 shadow-[inset_0_0_0_1px_rgba(29,79,163,0.12)]"
                            : "text-slate-600 hover:bg-white hover:text-slate-900 hover:shadow-[0_6px_18px_rgba(15,23,42,0.08)]"
                        }`
                      }
                    >
                      {item.label}
                    </NavLink>
                  ))}
                </nav>
              </div>
            ))}
          </div>
        </div>
      </header>
      <main className="app-container py-7 md:py-9">
        <Outlet />
      </main>

      <footer className="app-footer">
        <div className="app-container py-5 md:py-6">
          <div className="flex flex-col gap-3 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
            <p className="font-medium text-slate-700">{t("appTitle")}</p>
            <p className="text-slate-500">{t("shellSubtitle")}</p>
            <p className="text-slate-500">{currentYear}</p>
          </div>
        </div>
      </footer>
    </div>
  );
}
