import { Link, NavLink, Outlet } from "react-router-dom";
import { authStore } from "@shared/auth/auth-store";
import { LanguageSwitcher } from "@shared/i18n/language-switcher";
import { useI18n } from "@shared/i18n/use-i18n";
import type { TranslationKey } from "@shared/i18n/dictionary";

export function AppShell() {
  const { t } = useI18n();

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
      <header className="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div className="mx-auto max-w-7xl px-4 py-4">
          <div className="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <Link to="/overview" className="flex items-start gap-3">
              <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-100 text-lg font-bold text-primary-700">
                K
              </div>
              <div>
                <div className="text-lg font-semibold text-primary-800">
                  {t("appTitle")}
                </div>
                <p className="mt-1 text-sm text-slate-500">
                  {t("shellSubtitle")}
                </p>
              </div>
            </Link>
            <div className="flex flex-wrap items-center gap-3 lg:justify-end">
              <span className="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-medium text-slate-700">
                {t("shellCurrentRole")}: {t(roleLabelKey[authStore.role])}
              </span>
              <LanguageSwitcher />
              <NavLink
                to="/login"
                className="rounded-md bg-primary-600 px-3 py-2 text-sm font-medium text-white"
              >
                {t("login")}
              </NavLink>
            </div>
          </div>
          <div className="mt-4 grid gap-3 xl:grid-cols-4">
            {navSections.map((section) => (
              <div
                key={section.title}
                className="rounded-xl border border-blue-100 bg-[rgba(248,251,255,0.92)] p-3"
              >
                <div className="mb-2 flex items-center justify-between gap-3">
                  <p className="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                    {section.title}
                  </p>
                  <span className="rounded-full bg-white px-2 py-0.5 text-[11px] font-medium text-slate-600">
                    {section.badge}
                  </span>
                </div>
                <nav className="flex flex-wrap gap-2 text-sm">
                  {section.items.map((item) => (
                    <NavLink
                      key={item.to}
                      to={item.to}
                      className={({ isActive }) =>
                        `rounded-md px-2.5 py-1.5 ${
                          isActive
                            ? "bg-primary-100 text-primary-700"
                            : "text-slate-600 hover:bg-white hover:text-slate-900"
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
      <main className="mx-auto max-w-7xl px-4 py-8">
        <Outlet />
      </main>
    </div>
  );
}
