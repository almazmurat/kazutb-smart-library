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
        actions={
          <span className="app-chip">College + University Campuses</span>
        }
      />

      <div className="grid gap-5 lg:grid-cols-[1.3fr_0.7fr]">
        <form className="app-panel-strong p-6" onSubmit={submitSearch}>
          <p className="app-kicker">Public catalog</p>
          <h2 className="mt-2 text-lg font-semibold text-slate-900">
            Find books across KazUTB campuses
          </h2>
          <p className="mt-3 text-sm leading-7 text-slate-600">
            Start with a broad query, then refine by campus, language, and
            current availability in the results screen.
          </p>

          <div className="mt-5 grid gap-3 md:grid-cols-2">
            <input
              type="text"
              className="app-form-control md:col-span-2"
              placeholder="Title, author, keyword, ISBN"
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
              <option value="">All languages</option>
              <option value="kaz">Kazakh</option>
              <option value="rus">Russian</option>
              <option value="eng">English</option>
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
              <option value="">Any availability</option>
              <option value="available">Available now</option>
              <option value="unavailable">Currently unavailable</option>
            </select>
            <select
              className="app-form-control md:col-span-2"
              value={form.campusCode}
              onChange={(event) =>
                setForm((prev) => ({ ...prev, campusCode: event.target.value }))
              }
            >
              <option value="">All campuses</option>
              <option value="COLLEGE_MAIN">College</option>
              <option value="UNIVERSITY_ECONOMIC">University Economic</option>
              <option value="UNIVERSITY_TECHNOLOGICAL">
                University Technological
              </option>
              <option value="UNIVERSITY_CENTRAL">University Central</option>
            </select>
          </div>

          <div className="mt-5 flex gap-2">
            <button type="submit" className="app-button-primary">
              Search catalog
            </button>
            <Link to="/catalog" className="app-button-secondary">
              Open results page
            </Link>
          </div>
        </form>

        <article className="app-panel p-5">
          <p className="app-kicker">Quick demo flow</p>
          <h2 className="mt-2 text-lg font-semibold text-slate-900">
            Public to detail to librarian review
          </h2>
          <p className="mt-3 text-sm leading-7 text-slate-600">
            1) Search in public catalog. 2) Open book details for location-level
            availability. 3) Open librarian queue to review flagged issues.
          </p>
          <div className="mt-4 space-y-2 text-sm text-slate-600">
            <p>• College</p>
            <p>• University Economic</p>
            <p>• University Technological</p>
            <p>• University Central</p>
          </div>
        </article>
      </div>
    </section>
  );
}
