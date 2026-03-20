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

        <article className="app-panel p-5">
          <h2 className="app-section-heading">Подсказка по сценарию</h2>
          <p className="mt-3 text-sm leading-7 text-[var(--ink-500)]">
            1) Выполните поиск в каталоге. 2) Откройте карточку книги и
            проверьте доступность по локациям. 3) При необходимости перейдите в
            служебные разделы для работы с данными.
          </p>
          <div className="mt-4 space-y-2 text-sm text-[var(--ink-500)]">
            <p>• Колледж</p>
            <p>• Университет Экономический</p>
            <p>• Университет Технологический</p>
            <p>• Университет Центральный</p>
          </div>
        </article>
      </div>
    </section>
  );
}
