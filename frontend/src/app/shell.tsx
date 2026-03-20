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
      <header className="sticky top-0 z-20 border-b border-white/70 bg-[rgba(251,248,241,0.88)] shadow-[0_8px_24px_rgba(16,24,40,0.08)] backdrop-blur-xl">
        <div className="app-container flex flex-wrap items-center justify-between gap-4 py-4">
          <Link to="/overview" className="flex min-w-0 items-center gap-4">
            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[linear-gradient(160deg,#102d63,#356dc8)] text-lg font-bold text-white shadow-[0_14px_30px_rgba(16,45,99,0.22)]">
              K
            </div>
            <div className="min-w-0">
              <div className="app-display-title truncate text-xl font-semibold text-slate-950">
                {t("appTitle")}
              </div>
              <p className="truncate text-sm text-slate-500">
                {t("shellSubtitle")}
              </p>
            </div>
          </Link>

          <div className="flex flex-wrap items-center gap-2 text-xs text-slate-600">
            <span className="app-chip-muted">
              {t("shellCurrentRole")}: {t(roleLabelKey[auth.role])}
            </span>
            {auth.user ? (
              <span className="app-chip-muted">{auth.user.fullName}</span>
            ) : (
              <span className="app-chip-muted">Гостевой доступ</span>
            )}
            <LanguageSwitcher />
          </div>
        </div>
      </header>

      <main className="app-container py-6 md:py-8">
        <div className="grid gap-6 xl:grid-cols-[18rem_minmax(0,1fr)]">
          <aside className="space-y-4 xl:sticky xl:top-24 xl:self-start">
            <section className="app-sidebar-card overflow-hidden p-5">
              <p className="app-kicker">Рабочее пространство</p>
              <h2 className="app-display-title mt-2 text-2xl font-semibold text-slate-950">
                {auth.isAuthenticated
                  ? (auth.user?.fullName ?? t(roleLabelKey[auth.role]))
                  : "Начните с поиска или выполните вход"}
              </h2>
              <p className="mt-3 text-sm leading-6 text-slate-600">
                Используйте разделы слева для перехода между поиском,
                читательским кабинетом и служебными интерфейсами.
              </p>

              <div className="mt-4 grid gap-3 text-sm text-slate-700">
                <div className="app-stat-card">
                  <p className="app-kicker">Текущая роль</p>
                  <p className="mt-2 text-lg font-semibold text-slate-950">
                    {auth.isAuthenticated
                      ? t(roleLabelKey[auth.role])
                      : t("roleGuest")}
                  </p>
                </div>
                <div className="app-stat-card">
                  <p className="app-kicker">Быстрый маршрут</p>
                  <p className="mt-2 leading-6 text-slate-600">
                    Поиск, карточка книги, личный кабинет и служебные разделы.
                  </p>
                </div>
              </div>

              <div className="mt-4 flex flex-wrap gap-2">
                <NavLink
                  to={primaryWorkspaceHref}
                  className="app-button-primary"
                >
                  {auth.isAuthenticated
                    ? "Открыть рабочий раздел"
                    : "Открыть вход"}
                </NavLink>
                {!auth.isAuthenticated ? (
                  <NavLink to="/search" className="app-button-secondary">
                    Поиск как гость
                  </NavLink>
                ) : (
                  <button
                    type="button"
                    className="app-button-secondary"
                    disabled={logout.isPending}
                    onClick={() => logout.mutate()}
                  >
                    Выйти
                  </button>
                )}
              </div>
            </section>

            {visibleSections.map((section) => (
              <section key={section.title} className="app-sidebar-card p-4">
                <div className="mb-3 flex items-center justify-between gap-3">
                  <p className="app-kicker text-slate-500">{section.title}</p>
                  <span className="app-chip-muted px-2.5 py-1 text-[11px]">
                    {section.badge}
                  </span>
                </div>
                <nav className="grid gap-2">
                  {section.items.map((item) => (
                    <NavLink
                      key={item.to}
                      to={item.to}
                      className={({ isActive }) =>
                        `rounded-2xl px-3.5 py-3 text-sm transition duration-200 ${
                          isActive
                            ? "bg-[linear-gradient(135deg,rgba(16,45,99,0.96),rgba(53,109,200,0.92))] text-white shadow-[0_18px_32px_rgba(16,45,99,0.18)]"
                            : "border border-transparent bg-white/78 text-slate-700 hover:border-[rgba(16,45,99,0.12)] hover:bg-white"
                        }`
                      }
                    >
                      {item.label}
                    </NavLink>
                  ))}
                </nav>
              </section>
            ))}
          </aside>

          <section className="min-w-0">
            <Outlet />
          </section>
        </div>
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
