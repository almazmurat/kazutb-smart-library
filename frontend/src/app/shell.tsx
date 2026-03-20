import { Link, NavLink, Outlet, useLocation } from "react-router-dom";
import { useAuthState } from "@shared/auth/auth-store";
import { useI18n } from "@shared/i18n/use-i18n";
import { supportedLocales } from "@shared/i18n/config";
import { useAuthProfile, useLogout } from "@features/auth/hooks/use-auth";
import type { Role } from "@shared/types/role";
import { KazutbBrand } from "@shared/ui/kazutb-brand";

interface NavItem {
  to: string;
  label: string;
  public?: boolean;
  onlyGuest?: boolean;
  roles?: Role[];
}

export function AppShell() {
  const location = useLocation();
  const { t, locale, setLocale } = useI18n();
  const currentYear = new Date().getFullYear();
  const auth = useAuthState();
  const logout = useLogout();
  const isAuthPage = location.pathname === "/login";

  useAuthProfile(auth.isAuthenticated);

  const allNavItems: NavItem[] = [
    { to: "/overview", label: t("navOverview"), public: true },
    { to: "/catalog", label: t("navCatalog"), public: true },
    { to: "/search", label: t("navSearch"), public: true },
    { to: "/cabinet", label: t("navCabinet"), roles: ["STUDENT", "TEACHER", "LIBRARIAN", "ADMIN", "ANALYST"] },
    { to: "/librarian", label: t("navLibrarian"), roles: ["LIBRARIAN", "ADMIN"] },
    { to: "/librarian/circulation", label: t("navCirculation"), roles: ["LIBRARIAN", "ADMIN"] },
    { to: "/analytics", label: t("navAnalytics"), roles: ["LIBRARIAN", "ANALYST", "ADMIN"] },
    { to: "/admin", label: t("navAdmin"), roles: ["ADMIN"] },
    { to: "/login", label: "Вход", public: true, onlyGuest: true },
  ];

  const visibleNavItems = allNavItems.filter((item) => {
    if (item.onlyGuest) return !auth.isAuthenticated;
    if (item.public) return true;
    if (!auth.isAuthenticated) return false;
    return !item.roles || item.roles.includes(auth.role);
  });

  return (
    <div className="min-h-screen">
      {!isAuthPage && (
        <header className="app-shell-header app-shell-header-hero">
          <div className="app-shell-topline" />
          <div className="app-shell-backdrop" aria-hidden />
          <div className="app-container py-3 md:py-4">
            <div className="app-shell-toolbar">
              <Link to="/overview" className="app-shell-brand-link shrink-0">
                <KazutbBrand subtitle={t("shellSubtitle")} />
              </Link>

              <nav className="app-shell-center-nav">
                {visibleNavItems.map((item) => (
                  <NavLink
                    key={item.to}
                    to={item.to}
                    className={({ isActive }: { isActive: boolean }) =>
                      isActive ? "app-shell-nav-link app-shell-nav-link-active" : "app-shell-nav-link"
                    }
                  >
                    {item.label}
                  </NavLink>
                ))}

                <span className="app-shell-nav-divider" aria-hidden />

                {supportedLocales.map((loc) => (
                  <button
                    key={loc}
                    type="button"
                    className={locale === loc ? "app-shell-nav-link app-shell-nav-link-active" : "app-shell-nav-link"}
                    onClick={() => setLocale(loc)}
                  >
                    {loc.toUpperCase()}
                  </button>
                ))}

                {auth.isAuthenticated && (
                  <button
                    type="button"
                    className="app-shell-nav-link"
                    disabled={logout.isPending}
                    onClick={() => logout.mutate()}
                  >
                    Выйти
                  </button>
                )}
              </nav>
            </div>
          </div>
        </header>
      )}

      <main
        className={
          isAuthPage
            ? "app-container min-h-screen flex items-center py-4 md:py-6"
            : "app-container py-6 md:py-8"
        }
      >
        <section className="min-w-0">
          <Outlet />
        </section>
      </main>

      {!isAuthPage && (
        <footer className="app-footer">
          <div className="app-container py-5 md:py-6">
            <div className="flex flex-col gap-3 text-sm md:flex-row md:items-center md:justify-between">
              <KazutbBrand compact subtitle={t("shellSubtitle")} />
              <p className="text-[var(--ink-500)]">{currentYear}</p>
            </div>
          </div>
        </footer>
      )}
    </div>
  );
}
