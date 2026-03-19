export function AdminPage() {
  return (
    <section className="app-page">
      <article className="app-panel-strong p-6 md:p-7">
        <p className="app-kicker">Administration</p>
        <h1 className="mt-2 text-2xl font-semibold text-slate-900">Admin Panel</h1>
        <p className="mt-3 max-w-3xl text-sm leading-7 text-slate-600">
          User management, roles, settings, and migration controls.
        </p>
      </article>

      <article className="app-panel p-6">
        <div className="app-state-warning">
          Feature scaffold is ready. Business logic and UI flows will be implemented in iterative phases.
        </div>
      </article>
    </section>
  );
}