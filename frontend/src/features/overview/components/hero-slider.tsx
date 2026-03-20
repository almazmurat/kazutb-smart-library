import { useEffect, useRef, useState } from "react";

interface Slide {
  eyebrow: string;
  title: string;
  description: string;
  cta: { label: string; to: string; primary?: boolean };
  accent: string; // CSS gradient for the slide background accent
}

const slides: Slide[] = [
  {
    eyebrow: "Цифровая библиотека KazUTB",
    title: "Найдите нужную книгу быстро и удобно",
    description:
      "Более 100 000 наименований литературы по всем научным областям. Фильтрация по автору, теме, году, доступности в филиалах.",
    cta: { label: "Открыть каталог", to: "/catalog", primary: true },
    accent:
      "radial-gradient(circle at 20% 50%, rgba(31,127,134,0.35) 0%, transparent 55%), radial-gradient(circle at 80% 20%, rgba(39,87,143,0.4) 0%, transparent 50%)",
  },
  {
    eyebrow: "Личный кабинет читателя",
    title: "Контролируйте выдачи и бронирования",
    description:
      "История займов, активные резервирования, продление срока — всё в одном месте. Доступно студентам, преподавателям и сотрудникам.",
    cta: { label: "Войти в кабинет", to: "/login", primary: true },
    accent:
      "radial-gradient(circle at 75% 60%, rgba(191,148,64,0.3) 0%, transparent 50%), radial-gradient(circle at 20% 30%, rgba(19,55,103,0.45) 0%, transparent 55%)",
  },
  {
    eyebrow: "Рабочее место библиотекаря",
    title: "Эффективное обслуживание читателей",
    description:
      "Очередь обработки, выдача и приём книг, управление резервированиями и полный доступ к каталогу для корректировки данных.",
    cta: { label: "Служебный вход", to: "/login", primary: true },
    accent:
      "radial-gradient(circle at 60% 70%, rgba(10,35,72,0.5) 0%, transparent 55%), radial-gradient(circle at 15% 25%, rgba(31,127,134,0.3) 0%, transparent 50%)",
  },
  {
    eyebrow: "Аналитика и отчёты",
    title: "Данные для управленческих решений",
    description:
      "Статистика выдач, популярность фондов, загруженность филиалов — наглядные дашборды для администрации и аналитиков.",
    cta: { label: "Смотреть отчёты", to: "/analytics", primary: true },
    accent:
      "radial-gradient(circle at 30% 70%, rgba(191,148,64,0.28) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(19,55,103,0.42) 0%, transparent 55%)",
  },
];

const AUTOPLAY_MS = 5000;

export function HeroSlider() {
  const [current, setCurrent] = useState(0);
  const [paused, setPaused] = useState(false);
  const timerRef = useRef<ReturnType<typeof setInterval> | null>(null);

  const goTo = (index: number) => {
    setCurrent((index + slides.length) % slides.length);
  };

  const prev = () => goTo(current - 1);
  const next = () => goTo(current + 1);

  useEffect(() => {
    if (paused) return;
    timerRef.current = setInterval(() => {
      setCurrent((c) => (c + 1) % slides.length);
    }, AUTOPLAY_MS);
    return () => {
      if (timerRef.current) clearInterval(timerRef.current);
    };
  }, [paused, current]);

  const slide = slides[current];

  return (
    <div
      className="hero-slider"
      onMouseEnter={() => setPaused(true)}
      onMouseLeave={() => setPaused(false)}
    >
      {/* Background accent */}
      <div
        className="hero-slider-bg"
        style={{ background: slide.accent }}
        aria-hidden
      />

      {/* Slide content */}
      <div className="hero-slider-content">
        <p className="hero-slider-eyebrow">{slide.eyebrow}</p>
        <h1 className="hero-slider-title">{slide.title}</h1>
        <p className="hero-slider-desc">{slide.description}</p>
      </div>

      {/* Controls */}
      <div className="hero-slider-controls">
        <button
          type="button"
          className="hero-slider-arrow"
          onClick={prev}
          aria-label="Предыдущий слайд"
        >
          <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none">
            <path d="M15 18L9 12L15 6" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>

        <div className="hero-slider-dots" role="tablist">
          {slides.map((_, i) => (
            <button
              key={i}
              type="button"
              role="tab"
              aria-selected={i === current}
              aria-label={`Слайд ${i + 1}`}
              className={`hero-slider-dot ${i === current ? "hero-slider-dot-active" : ""}`}
              onClick={() => goTo(i)}
            />
          ))}
        </div>

        <button
          type="button"
          className="hero-slider-arrow"
          onClick={next}
          aria-label="Следующий слайд"
        >
          <svg viewBox="0 0 24 24" className="h-5 w-5" fill="none">
            <path d="M9 18L15 12L9 6" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>
      </div>

      {/* Progress bar */}
      {!paused && (
        <div className="hero-slider-progress" key={current}>
          <div className="hero-slider-progress-bar" />
        </div>
      )}
    </div>
  );
}
