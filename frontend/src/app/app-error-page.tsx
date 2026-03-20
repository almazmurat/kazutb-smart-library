import { Link, isRouteErrorResponse, useRouteError } from "react-router-dom";

export function AppErrorPage() {
  const error = useRouteError();

  const title = isRouteErrorResponse(error)
    ? `${error.status} ${error.statusText}`
    : "Something went wrong";

  const description = isRouteErrorResponse(error)
    ? "This page could not be opened. Use the links below to continue the demo without reloading the whole application."
    : "The application hit an unexpected UI error. A friendly fallback is shown instead of a raw crash screen.";

  return (
    <section className="app-page">
      <div className="app-panel-strong overflow-hidden p-8 md:p-10">
        <p className="app-kicker">Demo Recovery</p>
        <h1 className="mt-2 text-3xl font-semibold tracking-tight text-slate-950 md:text-4xl">
          {title}
        </h1>
        <p className="mt-4 max-w-2xl text-base leading-7 text-slate-600">
          {description}
        </p>

        <div className="mt-6 flex flex-wrap gap-3">
          <Link to="/overview" className="app-button-primary">
            Return to overview
          </Link>
          <Link to="/search" className="app-button-secondary">
            Open search
          </Link>
          <Link to="/login" className="app-button-secondary">
            Open demo login
          </Link>
        </div>
      </div>
    </section>
  );
}
