import { Link, isRouteErrorResponse, useRouteError } from "react-router-dom";

export function AppErrorPage() {
  const error = useRouteError();

  const title = isRouteErrorResponse(error)
    ? `${error.status} ${error.statusText}`
    : "Произошла ошибка";

  const description = isRouteErrorResponse(error)
    ? "Не удалось открыть страницу. Перейдите в другой раздел по кнопкам ниже."
    : "В интерфейсе произошла непредвиденная ошибка. Откройте нужный раздел снова.";

  return (
    <section className="app-page">
      <div className="app-panel-strong overflow-hidden p-8 md:p-10">
        <p className="app-kicker">Восстановление</p>
        <h1 className="mt-2 text-3xl font-semibold tracking-tight text-slate-950 md:text-4xl">
          {title}
        </h1>
        <p className="mt-4 max-w-2xl text-base leading-7 text-slate-600">
          {description}
        </p>

        <div className="mt-6 flex flex-wrap gap-3">
          <Link to="/overview" className="app-button-primary">
            На главную
          </Link>
          <Link to="/search" className="app-button-secondary">
            Открыть поиск
          </Link>
          <Link to="/login" className="app-button-secondary">
            Войти в систему
          </Link>
        </div>
      </div>
    </section>
  );
}
