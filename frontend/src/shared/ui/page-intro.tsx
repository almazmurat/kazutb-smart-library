import { ReactNode } from "react";

interface PageIntroProps {
  eyebrow?: string;
  title: string;
  description: string;
  badges?: string[];
  actions?: ReactNode;
  children?: ReactNode;
}

export function PageIntro({
  eyebrow,
  title,
  description,
  badges,
  actions,
  children,
}: PageIntroProps) {
  return (
    <section className="overflow-hidden rounded-2xl border border-blue-100 bg-[linear-gradient(135deg,rgba(29,79,163,0.12),rgba(255,255,255,0.98)_48%)] p-6 shadow-sm">
      <div className="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
          {eyebrow ? (
            <p className="text-xs uppercase tracking-[0.18em] text-primary-700">
              {eyebrow}
            </p>
          ) : null}
          <h1 className="mt-2 text-3xl font-semibold tracking-tight text-slate-900">
            {title}
          </h1>
          <p className="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
            {description}
          </p>
          {badges?.length ? (
            <div className="mt-4 flex flex-wrap gap-2">
              {badges.map((badge) => (
                <span
                  key={badge}
                  className="rounded-full border border-blue-200 bg-white/80 px-3 py-1 text-xs font-medium text-primary-800"
                >
                  {badge}
                </span>
              ))}
            </div>
          ) : null}
        </div>
        {actions ? <div className="shrink-0">{actions}</div> : null}
      </div>
      {children ? <div className="mt-6">{children}</div> : null}
    </section>
  );
}
