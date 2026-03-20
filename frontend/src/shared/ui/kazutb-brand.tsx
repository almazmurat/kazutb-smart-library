import { useState } from "react";

interface KazutbBrandProps {
  compact?: boolean;
  subtitle?: string;
}

export function KazutbBrand({
  compact = false,
  subtitle = "Цифровая библиотечная платформа",
}: KazutbBrandProps) {
  const [logoLoadFailed, setLogoLoadFailed] = useState(false);

  return (
    <div className="flex min-w-0 items-center gap-3">
      <span className="app-brand-mark" aria-hidden>
        {!logoLoadFailed ? (
          <img
            src="/assets/images/logo.png"
            alt="KazUTB logo"
            className="h-6 w-6 object-contain"
            onError={() => setLogoLoadFailed(true)}
          />
        ) : (
          <svg viewBox="0 0 32 32" className="h-6 w-6" fill="none">
            <path
              d="M16 3L26 7.6V15.6C26 21.5 21.9 26.8 16 29C10.1 26.8 6 21.5 6 15.6V7.6L16 3Z"
              fill="currentColor"
              fillOpacity="0.12"
              stroke="currentColor"
              strokeWidth="1.4"
            />
            <path
              d="M10.8 10.2H14.2V16.2L19.4 10.2H23L17.2 16.8L23 23H19.2L14.2 17.2V23H10.8V10.2Z"
              fill="currentColor"
            />  
          </svg>
        )}
      </span>
      <span className="min-w-0">
        <span className={`${compact ? "text-sm" : ""} app-brand-name block`}>
          KazUTB Library
        </span>
      </span>
    </div>
  );
}
