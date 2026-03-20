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
        title="Demo login and role testing"
        description="Use seeded demo users to test student, librarian, analyst, and admin flows. You can also continue as guest to test public catalog behavior."
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
          <p className="app-kicker">Sign In</p>
          <h2 className="mt-2 app-section-heading">University demo accounts</h2>
          <p className="mt-3 text-sm leading-7 text-slate-600">
            Select one of the prepared accounts or enter credentials manually.
            After login, the menu and landing page adjust automatically by role.
          </p>

          <div className="mt-6 space-y-3">
            <div className="grid gap-2 md:grid-cols-2 xl:grid-cols-4">
              {orderedUsers.map((user) => (
                <button
                  key={user.username}
                  type="button"
                  className="rounded-2xl border border-slate-200 bg-white px-3 py-3 text-left transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-[0_10px_22px_rgba(15,23,42,0.06)]"
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
                </button>
              ))}
            </div>

            <label className="space-y-1 text-sm">
              <span className="text-slate-600">Username</span>
              <input
                className="app-form-control"
                value={username}
                onChange={(event) => setUsername(event.target.value)}
                placeholder="student_demo"
              />
            </label>
            <label className="space-y-1 text-sm">
              <span className="text-slate-600">Password</span>
              <input
                type="password"
                className="app-form-control"
                value={password}
                onChange={(event) => setPassword(event.target.value)}
                placeholder="Student123!"
              />
            </label>

            <div className="flex flex-wrap gap-2 pt-1">
              <button
                type="button"
                className="app-button-primary"
                disabled={login.isPending}
                onClick={submit}
              >
                {login.isPending ? "Signing in..." : "Login"}
              </button>
              <button
                type="button"
                className="app-button-secondary"
                onClick={() => {
                  authStore.setGuestMode();
                  navigate("/search");
                }}
              >
                Continue as guest
              </button>
            </div>

            {login.isError ? (
              <div className="app-state-error">
                Login failed. Check username/password and try again.
              </div>
            ) : null}
          </div>
        </article>

        <article className="app-panel p-6 md:p-7">
          <p className="app-kicker">Demo Accounts</p>
          <h2 className="mt-2 app-section-heading">How to test quickly</h2>
          <div className="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm leading-7 text-slate-600">
            1) Choose a role account below. 2) Login. 3) Use role-specific quick
            links from the top menu. 4) Logout and test another role.
          </div>

          <div className="mt-4 space-y-3">
            {demoUsers.isLoading ? (
              <div className="app-empty-state p-4 text-sm text-slate-600">
                Loading demo accounts...
              </div>
            ) : null}

            {orderedUsers.map((user) => (
              <div
                key={user.username}
                className="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-[0_8px_18px_rgba(15,23,42,0.04)]"
              >
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
                    Use
                  </button>
                </div>
                <p className="mt-2 text-sm text-slate-700">
                  {user.username} / {user.password}
                </p>
              </div>
            ))}
          </div>

          <div className="mt-4 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
            Role landing: Guest → Search, Student → Cabinet, Librarian → Review
            Queue, Analyst → Analytics, Admin → Admin Overview.
          </div>

          <div className="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm leading-7 text-slate-700">
            <p className="font-semibold text-slate-900">
              Suggested live demo order
            </p>
            <p>1. Guest search and book details.</p>
            <p>2. Librarian login and issue queue review.</p>
            <p>3. Admin login and overview navigation.</p>
          </div>
        </article>
      </div>
    </section>
  );
}
