import { NavLink, Outlet } from "react-router-dom";
import { LanguageSwitcher } from "@shared/i18n/language-switcher";
import { useI18n } from "@shared/i18n/use-i18n";

export function AppShell() {
  const { t } = useI18n();

  const navItems = [
    { to: "/catalog", label: t("navCatalog") },
    { to: "/search", label: t("navSearch") },
    { to: "/cabinet", label: t("navCabinet") },
    { to: "/librarian", label: t("navLibrarian") },
    { to: "/admin", label: t("navAdmin") },
    { to: "/analytics", label: t("navAnalytics") },
    { to: "/reports", label: t("navReports") },
  ];

  return (
    <div className="min-h-screen">
      <header className="sticky top-0 z-10 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
          <div className="font-bold text-primary-700">{t("appTitle")}</div>
          <nav className="flex gap-3 text-sm">
            {navItems.map((item) => (
              <NavLink
                key={item.to}
                to={item.to}
                className={({ isActive }) =>
                  `rounded-md px-2 py-1 ${isActive ? "bg-primary-100 text-primary-700" : "text-slate-600 hover:text-slate-900"}`
                }
              >
                {item.label}
              </NavLink>
            ))}
            <LanguageSwitcher />
            <NavLink
              to="/login"
              className="rounded-md bg-primary-600 px-3 py-1 text-white"
            >
              {t("login")}
            </NavLink>
          </nav>
        </div>
      </header>
      <main className="mx-auto max-w-7xl px-4 py-6">
        <Outlet />
      </main>
    </div>
  );
}
