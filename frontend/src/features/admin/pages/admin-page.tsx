export function AdminPage() {
  return (
    <section className="app-page">
      <article className="app-panel-strong p-6 md:p-7">
        <p className="app-kicker">Администрирование</p>
        <h1 className="mt-2 text-2xl font-semibold text-slate-900">
          Панель администратора
        </h1>
        <p className="mt-3 max-w-3xl text-sm leading-7 text-slate-600">
          Управление пользователями, ролями и настройками библиотечной системы.
        </p>
      </article>

      <article className="app-panel p-6">
        <div className="grid gap-4 md:grid-cols-3">
          <div className="app-stat-card">
            <p className="app-kicker">Пользователи</p>
            <p className="mt-2 text-sm text-slate-700">
              Контроль учетных записей и назначение ролей.
            </p>
          </div>
          <div className="app-stat-card">
            <p className="app-kicker">Права доступа</p>
            <p className="mt-2 text-sm text-slate-700">
              Управление доступом к рабочим разделам.
            </p>
          </div>
          <div className="app-stat-card">
            <p className="app-kicker">Системные параметры</p>
            <p className="mt-2 text-sm text-slate-700">
              Настройка базовых параметров платформы.
            </p>
          </div>
        </div>
      </article>
    </section>
  );
}
