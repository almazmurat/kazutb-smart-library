interface BookCoverMockProps {
  title: string;
  subtitle?: string | null;
  accent?: string;
  compact?: boolean;
}

function getMonogram(title: string) {
  return title
    .split(/\s+/)
    .slice(0, 2)
    .map((word) => word[0]?.toUpperCase() ?? "")
    .join("")
    .slice(0, 2);
}

export function BookCoverMock({
  title,
  subtitle,
  accent,
  compact = false,
}: BookCoverMockProps) {
  return (
    <div
      className={`relative overflow-hidden rounded-[20px] border border-[rgba(18,59,114,0.22)] bg-[linear-gradient(165deg,#0b2f5f_0%,#164985_68%,#2f6ca7_100%)] text-white ${compact ? "min-h-[240px] p-5" : "min-h-[360px] p-7"}`}
    >
      <div className="absolute inset-0 bg-[linear-gradient(180deg,rgba(4,17,35,0.02)_0%,rgba(4,17,35,0.24)_100%)]" />
      <div className="absolute inset-x-0 top-0 h-1 bg-[linear-gradient(90deg,#c39a3f_0%,#138996_100%)]" />
      <div className="relative flex h-full flex-col justify-between">
        <div className="flex items-start justify-between gap-3">
          <span className="rounded-full border border-white/24 bg-white/10 px-3 py-1 text-[11px] font-medium uppercase tracking-[0.16em] text-white/90">
            KazUTB Library
          </span>
          {accent ? (
            <span className="rounded-full border border-white/20 bg-white/12 px-3 py-1 text-[11px] font-medium text-white/90">
              {accent}
            </span>
          ) : null}
        </div>

        <div>
          <div className="mb-3 text-4xl font-semibold tracking-[0.18em] text-white/20">
            {getMonogram(title)}
          </div>
          <h3
            className={`font-semibold leading-tight text-white ${compact ? "text-xl" : "text-3xl"}`}
          >
            {title}
          </h3>
          {subtitle ? (
            <p
              className={`mt-3 max-w-xs text-white/82 ${compact ? "text-xs leading-5" : "text-sm leading-6"}`}
            >
              {subtitle}
            </p>
          ) : null}
        </div>

        <div className="flex items-center justify-between text-[11px] uppercase tracking-[0.16em] text-white/74">
          <span>Official Edition</span>
          <span>Digital Catalog</span>
        </div>
      </div>
    </div>
  );
}
