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

      <div className="grid gap-5 xl:grid-cols-[1fr_0.9fr]">
        <article className="app-panel-strong p-6 md:p-7">
          <h2 className="app-section-heading">Вход</h2>
          <div className="mt-5 space-y-3">
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
          <p className="app-kicker">Быстрый выбор</p>
          <h2 className="mt-2 app-section-heading">Учетные записи по ролям</h2>
          <div className="mt-4 space-y-2">
            {demoUsers.isLoading ? (
              <div className="app-subpanel p-4 text-sm text-slate-600">
                Загрузка учетных записей...
              </div>
            ) : null}

            {orderedUsers.map((user) => {
              const displayName = user.fullName.replace(/^Demo\s+/, "");
              return (
                <div key={user.username} className="app-stat-card">
                  <div className="flex flex-wrap items-center justify-between gap-2">
                    <div>
                      <p className="font-semibold text-slate-900">
                        {displayName}
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
              );
            })}
          </div>
          <div className="mt-5 app-subpanel p-4 text-sm text-slate-700">
            Для публичного поиска можно продолжить без входа.
            <div className="mt-3">
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
          </div>
        </article>
      </div>
    </section>
  );
}
