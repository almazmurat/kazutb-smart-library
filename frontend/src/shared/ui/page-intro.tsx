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
    <section className="app-panel-strong p-6 md:p-7">
      <div className="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div className="max-w-4xl">
          {eyebrow ? <p className="app-kicker">{eyebrow}</p> : null}
          <h1 className="app-display-title mt-2 max-w-4xl text-3xl font-semibold text-slate-950 md:text-4xl">
            {title}
          </h1>
          <p className="mt-3 max-w-3xl text-sm leading-7 text-slate-600 md:text-base">
            {description}
          </p>
          {badges?.length ? (
            <div className="mt-5 flex flex-wrap gap-2">
              {badges.map((badge) => (
                <span key={badge} className="app-chip">
                  {badge}
                </span>
              ))}
            </div>
          ) : null}
        </div>
        {actions ? (
          <div className="shrink-0 rounded-[24px] border border-white/60 bg-white/86 p-2.5 shadow-[0_16px_34px_rgba(15,23,42,0.08)]">
            {actions}
          </div>
        ) : null}
      </div>
      {children ? (
        <div className="mt-6 border-t border-slate-200 pt-6 md:mt-7 md:pt-7">
          {children}
        </div>
      ) : null}
    </section>
  );
}
