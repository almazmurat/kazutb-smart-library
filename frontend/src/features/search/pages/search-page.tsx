import { FormEvent, useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useI18n } from "@shared/i18n/use-i18n";
import { PageIntro } from "@shared/ui/page-intro";

export function SearchPage() {
  const { t } = useI18n();
  const navigate = useNavigate();
  const [form, setForm] = useState({
    q: "",
    language: "",
    campusCode: "",
    availability: "",
  });

  const submitSearch = (event: FormEvent) => {
    event.preventDefault();
    const params = new URLSearchParams();
    if (form.q) params.set("q", form.q);
    if (form.language) params.set("language", form.language);
    if (form.campusCode) params.set("campusCode", form.campusCode);
    if (form.availability) params.set("availability", form.availability);
    params.set("page", "1");
    params.set("limit", "12");

    navigate(`/catalog?${params.toString()}`);
  };

  return (
    <section className="app-page">
      <PageIntro
        eyebrow={t("shellPublicSection")}
        title={t("navSearch")}
        description={t("catalogPublicDescription")}
        badges={[t("shellPublicLabel"), t("catalogFeatureInstitutional")]}
      />

      <div className="grid gap-5 lg:grid-cols-[1fr_0.7fr]">
        <form className="app-panel-strong p-6" onSubmit={submitSearch}>
          <h2 className="app-section-heading">Параметры поиска</h2>

          <div className="mt-5 grid gap-3 md:grid-cols-2">
            <input
              type="text"
              className="app-form-control md:col-span-2"
              placeholder="Название, автор, ключевое слово, ISBN"
              value={form.q}
              onChange={(event) =>
                setForm((prev) => ({ ...prev, q: event.target.value }))
              }
            />
            <select
              className="app-form-control"
              value={form.language}
              onChange={(event) =>
                setForm((prev) => ({ ...prev, language: event.target.value }))
              }
            >
              <option value="">Все языки</option>
              <option value="kaz">Казахский</option>
              <option value="rus">Русский</option>
              <option value="eng">Английский</option>
            </select>
            <select
              className="app-form-control"
              value={form.availability}
              onChange={(event) =>
                setForm((prev) => ({
                  ...prev,
                  availability: event.target.value,
                }))
              }
            >
              <option value="">Любая доступность</option>
              <option value="available">Доступно сейчас</option>
              <option value="unavailable">Временно недоступно</option>
            </select>
            <select
              className="app-form-control md:col-span-2"
              value={form.campusCode}
              onChange={(event) =>
                setForm((prev) => ({ ...prev, campusCode: event.target.value }))
              }
            >
              <option value="">Все кампусы</option>
              <option value="COLLEGE_MAIN">Колледж</option>
              <option value="UNIVERSITY_ECONOMIC">
                Университет Экономический
              </option>
              <option value="UNIVERSITY_TECHNOLOGICAL">
                Университет Технологический
              </option>
              <option value="UNIVERSITY_CENTRAL">
                Университет Центральный
              </option>
            </select>
          </div>

          <div className="mt-5 flex gap-2">
            <button type="submit" className="app-button-primary">
              Найти в каталоге
            </button>
            <Link to="/catalog" className="app-button-secondary">
              Открыть результаты
            </Link>
          </div>
        </form>

        <article className="app-panel p-5 flex flex-col gap-4">
          <div>
            <p className="app-kicker">Советы по поиску</p>
            <h2 className="mt-2 app-section-heading">Как искать эффективно</h2>
          </div>
          <div className="space-y-3 text-sm text-[var(--ink-700)]">
            <div className="app-subpanel p-3">
              <p className="font-medium text-[var(--ink-900)]">
                По названию или автору
              </p>
              <p className="mt-1 text-[var(--ink-500)]">
                Введите часть названия или фамилию автора — система найдёт
                совпадения.
              </p>
            </div>
            <div className="app-subpanel p-3">
              <p className="font-medium text-[var(--ink-900)]">
                Фильтр по доступности
              </p>
              <p className="mt-1 text-[var(--ink-500)]">
                Выберите «Доступно сейчас», чтобы найти книги, которые можно
                получить сегодня.
              </p>
            </div>
            <div className="app-subpanel p-3">
              <p className="font-medium text-[var(--ink-900)]">
                Поиск по кампусу
              </p>
              <p className="mt-1 text-[var(--ink-500)]">
                Укажите свой кампус, чтобы видеть фонды ближайшей библиотеки.
              </p>
            </div>
          </div>
          <div className="mt-auto pt-2 border-t border-[rgba(18,59,114,0.1)]">
            <Link
              to="/catalog"
              className="app-button-secondary text-sm w-full text-center block"
            >
              Открыть весь каталог
            </Link>
          </div>
        </article>
      </div>
    </section>
  );
}
