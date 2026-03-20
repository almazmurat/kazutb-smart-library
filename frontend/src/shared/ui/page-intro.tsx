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
      <div className="absolute -left-10 top-0 h-36 w-36 rounded-full bg-[radial-gradient(circle,rgba(36,83,166,0.24),rgba(36,83,166,0))] blur-2xl" />
      <div className="absolute right-0 top-0 h-40 w-40 rounded-full bg-[radial-gradient(circle,rgba(194,141,47,0.2),rgba(194,141,47,0))] blur-2xl" />
      <div className="absolute inset-x-0 top-0 hidden h-24 bg-[linear-gradient(90deg,rgba(16,45,99,0.18),rgba(255,255,255,0))] lg:block" />
      <div className="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div className="max-w-4xl">
          {eyebrow ? <p className="app-kicker">{eyebrow}</p> : null}
          <h1 className="app-display-title mt-2 max-w-4xl text-[2.2rem] font-semibold text-slate-950 md:text-[3.15rem] xl:text-[3.55rem]">
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
        <div className="relative mt-6 border-t border-[rgba(16,45,99,0.08)] pt-6 md:mt-8 md:pt-8">
          {children}
        </div>
      ) : null}
    </section>
  );
}
