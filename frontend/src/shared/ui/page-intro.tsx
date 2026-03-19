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
    <section className="app-panel-strong relative overflow-hidden p-6 md:p-8 xl:p-10">
      <div className="absolute inset-x-0 top-0 hidden h-20 bg-[linear-gradient(90deg,rgba(29,79,163,0.16),rgba(255,255,255,0))] lg:block" />
      <div className="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div className="max-w-4xl">
          {eyebrow ? <p className="app-kicker">{eyebrow}</p> : null}
          <h1 className="mt-2 max-w-4xl text-[2rem] font-semibold tracking-tight text-slate-950 md:text-4xl xl:text-[2.85rem]">
            {title}
          </h1>
          <p className="mt-4 max-w-3xl text-[15px] leading-7 text-slate-600 md:text-base">
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
        <div className="relative mt-6 md:mt-8">{children}</div>
      ) : null}
    </section>
  );
}
