import { Link, NavLink, Outlet } from "react-router-dom";
import { useAuthState } from "@shared/auth/auth-store";
import { LanguageSwitcher } from "@shared/i18n/language-switcher";
import { useI18n } from "@shared/i18n/use-i18n";
import type { TranslationKey } from "@shared/i18n/dictionary";
import { useAuthProfile, useLogout } from "@features/auth/hooks/use-auth";
import type { Role } from "@shared/types/role";

interface NavItem {
  to: string;
  label: string;
  public?: boolean;
  roles?: Role[];
}

export function AppShell() {
  const { t } = useI18n();
  const currentYear = new Date().getFullYear();
  const auth = useAuthState();
  const logout = useLogout();

  useAuthProfile(auth.isAuthenticated);

  const roleLabelKey: Record<string, TranslationKey> = {
    GUEST: "roleGuest",
    STUDENT: "roleStudent",
    TEACHER: "roleTeacher",
    LIBRARIAN: "roleLibrarian",
    ADMIN: "roleAdmin",
    ANALYST: "roleAnalyst",
  };

  const navSections: Array<{ title: string; badge: string; items: NavItem[] }> =
    [
      {
        title: t("shellPublicSection"),
        badge: t("shellPublicLabel"),
        items: [
          { to: "/overview", label: t("navOverview"), public: true },
          { to: "/catalog", label: t("navCatalog"), public: true },
          { to: "/search", label: t("navSearch"), public: true },
          { to: "/login", label: "Вход", public: true },
        ],
      },
      {
        title: t("shellReaderSection"),
        badge: t("shellSecureLabel"),
        items: [
          {
            to: "/cabinet",
            label: t("navCabinet"),
            roles: ["STUDENT", "TEACHER", "LIBRARIAN", "ADMIN", "ANALYST"],
          },
        ],
      },
      {
        title: t("shellOperationsSection"),
        badge: t("shellSecureLabel"),
        items: [
          {
            to: "/librarian",
            label: t("navLibrarian"),
            roles: ["LIBRARIAN", "ADMIN"],
          },
          {
            to: "/librarian/circulation",
            label: t("navCirculation"),
            roles: ["LIBRARIAN", "ADMIN"],
          },
          {
            to: "/analytics",
            label: t("navAnalytics"),
            roles: ["LIBRARIAN", "ANALYST", "ADMIN"],
          },
          {
            to: "/reports",
            label: t("navReports"),
            roles: ["LIBRARIAN", "ANALYST", "ADMIN"],
          },
        ],
      },
      {
        title: t("shellAdministrationSection"),
        badge: t("shellSecureLabel"),
        items: [
          {
            to: "/librarian/catalog/books",
            label: t("navCatalogBooksMgmt"),
            roles: ["LIBRARIAN", "ADMIN"],
          },
          {
            to: "/librarian/catalog/authors",
            label: t("navCatalogAuthorsMgmt"),
            roles: ["LIBRARIAN", "ADMIN"],
          },
          {
            to: "/librarian/catalog/categories",
            label: t("navCatalogCategoriesMgmt"),
            roles: ["LIBRARIAN", "ADMIN"],
          },
          {
            to: "/librarian/catalog/copies",
            label: t("navCatalogCopiesMgmt"),
            roles: ["LIBRARIAN", "ADMIN"],
          },
          { to: "/admin", label: t("navAdmin"), roles: ["ADMIN"] },
        ],
      },
    ];

  const visibleSections = navSections
    .map((section) => ({
      ...section,
      items: section.items.filter((item) => {
        if (item.public) {
          return true;
        }
        if (!auth.isAuthenticated) {
          return false;
        }
        return !item.roles || item.roles.includes(auth.role);
      }),
    }))
    .filter((section) => section.items.length > 0);

  const visibleNavItems = visibleSections.flatMap((section) => section.items);

  const primaryWorkspaceHref = !auth.isAuthenticated
    ? "/login"
    : auth.role === "ADMIN"
      ? "/admin"
      : auth.role === "LIBRARIAN"
        ? "/librarian"
        : auth.role === "ANALYST"
          ? "/analytics"
          : "/cabinet";

  return (
    <div className="min-h-screen">
      <header className="border-b border-slate-200 bg-white">
        <div className="app-container py-4">
          <div className="flex flex-wrap items-center justify-between gap-3">
            <Link to="/overview" className="min-w-0">
              <p className="text-lg font-semibold text-slate-900">{t("appTitle")}</p>
              <p className="text-sm text-slate-500">{t("shellSubtitle")}</p>
            </Link>

            <div className="flex flex-wrap items-center gap-2 text-xs text-slate-600">
              <span className="app-chip-muted">
                {t("shellCurrentRole")}: {t(roleLabelKey[auth.role])}
              </span>
              {auth.user ? (
                <span className="app-chip-muted">{auth.user.fullName}</span>
              ) : null}
              <LanguageSwitcher />
              <NavLink to={primaryWorkspaceHref} className="app-button-secondary px-3 py-2">
                {auth.isAuthenticated ? "Раздел" : "Вход"}
              </NavLink>
              {!auth.isAuthenticated ? (
                <NavLink to="/search" className="app-button-secondary px-3 py-2">
                  Поиск
                </NavLink>
              ) : (
                <button
                  type="button"
                  className="app-button-secondary px-3 py-2"
                  disabled={logout.isPending}
                  onClick={() => logout.mutate()}
                >
                  Выйти
                </button>
              )}
            </div>
          </div>

          <nav className="mt-4 flex flex-wrap gap-2 border-t border-slate-100 pt-3">
            {visibleNavItems.map((item) => (
              <NavLink
                key={item.to}
                to={item.to}
                className={({ isActive }) =>
                  `rounded-md px-3 py-1.5 text-sm transition ${
                    isActive
                      ? "bg-slate-900 text-white"
                      : "text-slate-700 hover:bg-slate-100"
                  }`
                }
              >
                {item.label}
              </NavLink>
            ))}
          </nav>
        </div>
      </header>

      <main className="app-container py-6 md:py-8">
        <section className="min-w-0">
          <Outlet />
        </section>
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
