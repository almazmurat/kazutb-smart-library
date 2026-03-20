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
    {
      to: "/cabinet",
      title: t("navCabinet"),
      description: "Личные бронирования, выдачи и статус книг.",
    },
  ];

  const modules = [
    {
      title: "Каталог и поиск",
      description: "Поиск литературы, фильтры и доступность по филиалам.",
    },
    {
      title: "Кабинет читателя",
      description: "Бронирования, выдачи и история операций пользователя.",
    },
    {
      title: "Рабочее место библиотекаря",
      description: "Очередь замечаний и инструменты корректировки данных.",
    },
  ];

  return (
    <div className="app-page">
      <PageIntro
        eyebrow={t("overviewEyebrow")}
        title={t("overviewTitle")}
        description="Цифровая библиотечная платформа для поиска, обслуживания читателей и операционной работы сотрудников."
        badges={[
          `${t("shellCurrentRole")}: ${t(roleKey[auth.role] ?? "roleGuest")}`,
        ]}
        actions={
          <div className="flex flex-wrap gap-2.5">
            <Link to="/search" className="app-button-primary">
              Открыть поиск
            </Link>
            <Link to="/login" className="app-button-secondary">
              Войти
            </Link>
          </div>
        }
      >
        <p className="text-sm leading-7 text-slate-600">
          Основные сценарии: поиск книги, просмотр карточки, проверка наличия по
          филиалам, работа читателя в кабинете, обработка задач библиотекарем,
          административный и аналитический контроль.
        </p>
      </PageIntro>

      <section className="app-panel p-6 md:p-7">
        <h2 className="app-section-heading">Быстрый доступ</h2>
        <div className="app-card-grid mt-5 md:grid-cols-2 xl:grid-cols-4">
          {quickAccess.map((item) => (
            <Link key={item.to} to={item.to} className="app-feature-card">
              <h3 className="text-base font-semibold text-slate-900">
                {item.title}
              </h3>
              <p className="mt-4 text-sm leading-6 text-slate-600">
                {item.description}
              </p>
            </Link>
          ))}
        </div>
      </section>

      <section className="app-panel p-6 md:p-7">
        <h2 className="app-section-heading">Ключевые разделы</h2>
        <div className="app-card-grid mt-5 md:grid-cols-3">
          {modules.map((module) => (
            <article key={module.title} className="app-stat-card">
              <h3 className="text-base font-semibold text-slate-900">
                {module.title}
              </h3>
              <p className="mt-3 text-sm leading-6 text-slate-600">
                {module.description}
              </p>
            </article>
          ))}
        </div>
      </section>
    </div>
  );
}
