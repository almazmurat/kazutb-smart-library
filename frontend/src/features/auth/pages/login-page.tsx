import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { LockKeyhole, UserRound } from "lucide-react";

import { useI18n } from "@shared/i18n/use-i18n";
import { useLogin } from "../hooks/use-auth";
import { authStore, useAuthState } from "@shared/auth/auth-store";
import { getLandingRouteByRole } from "@shared/auth/role-routing";
import { KazutbBrand } from "@shared/ui/kazutb-brand";

export function LoginPage() {
  const { t } = useI18n();
  const navigate = useNavigate();
  const auth = useAuthState();
  const login = useLogin();

  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");

  useEffect(() => {
    if (auth.isAuthenticated) {
      navigate(getLandingRouteByRole(auth.role), { replace: true });
    }
  }, [auth.isAuthenticated, auth.role, navigate]);

  const rolePills = ["Студент", "Преподаватель", "Библиотекарь", "Администратор"];

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
    <section className="auth-showcase-page">
      <div className="auth-showcase-shell">
        <article className="auth-showcase-form-pane">
          <div className="auth-showcase-form-wrap">
            <p className="auth-showcase-eyebrow">{t("shellSecureLabel")}</p>
            <h1 className="auth-showcase-title">Авторизация</h1>
            <p className="auth-showcase-subtitle">
              Войдите в цифровую библиотечную платформу KazUTB
            </p>

            <label className="auth-showcase-field-label">Username</label>
            <div className="auth-showcase-input-wrap">
              <UserRound size={16} />
              <input
                className="auth-showcase-input"
                value={username}
                onChange={(event) => setUsername(event.target.value)}
                placeholder="student"
              />
            </div>

            <label className="auth-showcase-field-label">Password</label>
            <div className="auth-showcase-input-wrap">
              <LockKeyhole size={16} />
              <input
                type="password"
                className="auth-showcase-input"
                value={password}
                onChange={(event) => setPassword(event.target.value)}
                placeholder="Введите пароль"
              />
            </div>

            <div className="auth-showcase-actions">
              <button
                type="button"
                className="auth-showcase-login-btn"
                disabled={login.isPending}
                onClick={submit}
              >
                {login.isPending ? "Выполняется вход..." : "Login Now"}
              </button>

              <button
                type="button"
                className="auth-showcase-guest-btn"
                onClick={() => {
                  authStore.setGuestMode();
                  navigate("/search");
                }}
              >
                Продолжить как гость
              </button>
            </div>

            {login.isError ? (
              <div className="app-state-error mt-3">
                Не удалось выполнить вход. Проверьте логин и пароль.
              </div>
            ) : null}

          </div>
        </article>

        <aside className="auth-showcase-visual-pane">
          <div className="auth-showcase-orb auth-showcase-orb-top" aria-hidden />
          <div className="auth-showcase-orb auth-showcase-orb-bottom" aria-hidden />

          <div className="auth-showcase-visual-card">
            <KazutbBrand subtitle="Smart Library" />

            <h2>Единый вход для читателей и сотрудников</h2>
            <p>
              Авторизация открывает персональный кабинет, инструменты
              библиотекаря и аналитические панели в рамках вашей роли.
            </p>

            <div className="auth-showcase-role-pills">
              {rolePills.map((role) => (
                <span key={role} className="auth-showcase-role-pill">
                  {role}
                </span>
              ))}
            </div>
          </div>

          <p className="auth-showcase-visual-footnote">
            Для публичного поиска используйте режим гостя без авторизации.
          </p>
        </aside>
      </div>
    </section>
  );
}
