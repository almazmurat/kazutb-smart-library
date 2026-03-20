import { useEffect, useMemo, useState } from "react";
import { useNavigate } from "react-router-dom";

import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";
import { useDemoUsers, useLogin } from "../hooks/use-auth";
import { authStore, useAuthState } from "@shared/auth/auth-store";
import { getLandingRouteByRole } from "@shared/auth/role-routing";

export function LoginPage() {
  const { t } = useI18n();
  const navigate = useNavigate();
  const auth = useAuthState();
  const login = useLogin();
  const demoUsers = useDemoUsers();

  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");

  useEffect(() => {
    if (auth.isAuthenticated) {
      navigate(getLandingRouteByRole(auth.role), { replace: true });
    }
  }, [auth.isAuthenticated, auth.role, navigate]);

  const roleOrder = useMemo(
    () => ["STUDENT", "LIBRARIAN", "ADMIN", "ANALYST"],
    [],
  );

  const orderedUsers = useMemo(() => {
    const users = demoUsers.data ?? [];
    return [...users].sort((left, right) => {
      const leftOrder = roleOrder.indexOf(left.role);
      const rightOrder = roleOrder.indexOf(right.role);
      return leftOrder - rightOrder;
    });
  }, [demoUsers.data, roleOrder]);

  const roleMessages: Record<
    string,
    { title: string; summary: string; destination: string }
  > = {
    STUDENT: {
      title: "Читательский интерфейс",
      summary: "Личный кабинет, бронирования и доступ к заказам.",
      destination: "Кабинет",
    },
    LIBRARIAN: {
      title: "Рабочий интерфейс библиотекаря",
      summary: "Очередь задач, обслуживание и корректировка данных.",
      destination: "Очередь проверки",
    },
    ADMIN: {
      title: "Администрирование",
      summary: "Системные разделы, роли и контроль настроек.",
      destination: "Панель администратора",
    },
    ANALYST: {
      title: "Аналитика и отчеты",
      summary: "Показатели, динамика и аналитические отчеты.",
      destination: "Аналитика",
    },
  };

  const submit = () => {
    if (!username.trim() || !password.trim()) {
      return;
    }

    login.mutate(
      {
        username: username.trim(),
        password: password.trim(),
      },
      {
        onSuccess: (session) => {
          navigate(getLandingRouteByRole(session.user.role));
        },
      },
    );
  };

  return (
    <section className="app-page">
      <PageIntro
        eyebrow={t("shellSecureLabel")}
        title="Вход в библиотечную систему"
        description="Выберите учетную запись по роли или выполните вход вручную. После авторизации откроется соответствующий рабочий раздел."
        badges={[
          t("shellSecureLabel"),
          t("roleGuest"),
          t("roleStudent"),
          t("roleLibrarian"),
          t("roleAdmin"),
        ]}
      />

      <div className="grid gap-5 xl:grid-cols-[1.1fr_0.9fr]">
        <article className="app-panel-strong p-6 md:p-7">
          <p className="app-kicker">Учетные записи</p>
          <h2 className="mt-2 app-section-heading">Выбор роли</h2>
          <p className="mt-3 text-sm leading-7 text-slate-600">
            Выберите подготовленную учетную запись или введите логин и пароль
            вручную. Меню и стартовая страница меняются автоматически по роли.
          </p>

          <div className="mt-6 space-y-3">
            <div className="grid gap-2 md:grid-cols-2 xl:grid-cols-4">
              {orderedUsers.map((user) => (
                <button
                  key={user.username}
                  type="button"
                  className="app-feature-card px-4 py-4 text-left"
                  onClick={() => {
                    setUsername(user.username);
                    setPassword(user.password);
                  }}
                >
                  <div className="text-xs uppercase tracking-[0.14em] text-slate-500">
                    {user.role}
                  </div>
                  <div className="mt-2 text-sm font-semibold text-slate-900">
                    {user.username}
                  </div>
                  <p className="mt-2 text-xs leading-5 text-slate-600">
                    {roleMessages[user.role]?.summary}
                  </p>
                  <div className="mt-3 inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-700">
                    Раздел: {roleMessages[user.role]?.destination}
                  </div>
                </button>
              ))}
            </div>

            <label className="space-y-1 text-sm">
              <span className="text-slate-600">Логин</span>
              <input
                className="app-form-control"
                value={username}
                onChange={(event) => setUsername(event.target.value)}
                placeholder="student"
              />
            </label>
            <label className="space-y-1 text-sm">
              <span className="text-slate-600">Пароль</span>
              <input
                type="password"
                className="app-form-control"
                value={password}
                onChange={(event) => setPassword(event.target.value)}
                placeholder="Введите пароль"
              />
            </label>

            <div className="flex flex-wrap gap-2 pt-1">
              <button
                type="button"
                className="app-button-primary"
                disabled={login.isPending}
                onClick={submit}
              >
                {login.isPending ? "Выполняется вход..." : "Войти"}
              </button>
              <button
                type="button"
                className="app-button-secondary"
                onClick={() => {
                  authStore.setGuestMode();
                  navigate("/search");
                }}
              >
                Продолжить как гость
              </button>
            </div>

            {login.isError ? (
              <div className="app-state-error">
                Не удалось выполнить вход. Проверьте логин и пароль.
              </div>
            ) : null}
          </div>
        </article>

        <article className="app-panel p-6 md:p-7">
          <p className="app-kicker">Навигация</p>
          <h2 className="mt-2 app-section-heading">Как начать работу</h2>
          <div className="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm leading-7 text-slate-600">
            1) Выберите роль. 2) Выполните вход. 3) Перейдите в нужные разделы
            из меню. 4) При необходимости смените роль.
          </div>

          <div className="mt-4 space-y-3">
            {demoUsers.isLoading ? (
              <div className="app-empty-state p-4 text-sm text-slate-600">
                Загрузка учетных записей...
              </div>
            ) : null}

            {orderedUsers.map((user) => (
              <div key={user.username} className="app-stat-card">
                <div className="flex flex-wrap items-center justify-between gap-2">
                  <div>
                    <p className="font-semibold text-slate-900">
                      {user.fullName}
                    </p>
                    <p className="text-xs text-slate-500">{user.role}</p>
                  </div>
                  <button
                    type="button"
                    className="app-button-secondary px-3 py-1.5"
                    onClick={() => {
                      setUsername(user.username);
                      setPassword(user.password);
                    }}
                  >
                    Выбрать
                  </button>
                </div>
                <p className="mt-2 text-sm text-slate-700">
                  {user.username} / {user.password}
                </p>
                <p className="mt-1 text-xs text-slate-500">
                  {roleMessages[user.role]?.title}
                </p>
              </div>
            ))}
          </div>

          <div className="mt-4 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
            Разделы по ролям: Гость → Поиск, Студент → Кабинет, Библиотекарь →
            Очередь проверки, Аналитик → Аналитика, Администратор → Панель
            администратора.
          </div>

          <div className="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm leading-7 text-slate-700">
            <p className="font-semibold text-slate-900">
              Рекомендуемая последовательность
            </p>
            <p>1. Поиск книги и просмотр карточки.</p>
            <p>2. Вход библиотекаря и работа с очередью.</p>
            <p>3. Вход администратора и управление системой.</p>
          </div>

          <div className="mt-4 app-flow-step">
            <p className="app-kicker text-white/70">Гостевой доступ</p>
            <h3 className="app-display-title mt-2 text-2xl font-semibold">
              Для публичного каталога вход не требуется
            </h3>
            <p className="mt-3 text-sm leading-7 text-white/82">
              Используйте гостевой режим, если нужно быстро открыть поиск,
              результаты и доступность книг по локациям.
            </p>
            <button
              type="button"
              className="app-button-secondary mt-4 border-white/20 bg-white/12 text-white hover:bg-white/20"
              onClick={() => {
                authStore.setGuestMode();
                navigate("/search");
              }}
            >
              Продолжить как гость
            </button>
          </div>
        </article>
      </div>
    </section>
  );
}
