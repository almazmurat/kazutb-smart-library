import { Link } from "react-router-dom";

import { useAuthState } from "@shared/auth/auth-store";
import { useI18n } from "@shared/i18n/use-i18n";
import { HeroSlider } from "../components/hero-slider";
import { PlatformNovelties } from "../components/platform-novelties";

export function OverviewPage() {
  const { t } = useI18n();
  const auth = useAuthState();

  const workspaceHref =
    !auth.isAuthenticated
      ? "/login"
      : auth.role === "ADMIN"
        ? "/admin"
        : auth.role === "LIBRARIAN"
          ? "/librarian"
          : auth.role === "ANALYST"
            ? "/analytics"
            : "/cabinet";

  const guestQuickAccess = [
    {
      to: "/search",
      title: t("navSearch"),
      description: "Поиск по названию, автору, ISBN и доступности.",
    },
    {
      to: "/catalog",
      title: t("navCatalog"),
      description: "Результаты каталога и карточки книг.",
    },
    {
      to: "/login",
      title: "Вход в систему",
      description: "Авторизация для личного кабинета и служебных разделов.",
    },
  ];

  const authQuickAccess = [
    {
      to: "/search",
      title: t("navSearch"),
      description: "Поиск по названию, автору, ISBN и доступности.",
    },
    {
      to: "/catalog",
      title: t("navCatalog"),
      description: "Результаты каталога и карточки книг.",
    },
    {
      to: workspaceHref,
      title: "Рабочий раздел",
      description: "Перейти в персональный рабочий раздел.",
    },
  ];

  const quickAccess = auth.isAuthenticated ? authQuickAccess : guestQuickAccess;

  return (
    <div className="app-page">
      <HeroSlider />
      <PlatformNovelties />

      <section className="app-panel p-6 md:p-7">
        <h2 className="app-section-heading">Быстрый доступ</h2>
        <div className="app-card-grid mt-5 md:grid-cols-2 xl:grid-cols-3">
          {quickAccess.map((item) => (
            <Link key={item.to} to={item.to} className="app-feature-card">
              <h3 className="text-base font-semibold text-[var(--ink-900)]">
                {item.title}
              </h3>
              <p className="mt-4 text-sm leading-6 text-[var(--ink-500)]">
                {item.description}
              </p>
            </Link>
          ))}
        </div>
      </section>
    </div>
  );
}
