import { useMemo, useRef } from "react";
import { Link } from "react-router-dom";

import { usePublicCatalog } from "@features/catalog/hooks/use-public-catalog";
import { CatalogSearchItem } from "@features/catalog/api/public-catalog-api";

interface NoveltyItem {
  id: string;
  title: string;
  author: string;
  publisher: string;
  coverClassName: string;
  accentLabel: string;
}

const coverPalette = [
  "platform-novelties-cover-blue",
  "platform-novelties-cover-green",
  "platform-novelties-cover-pink",
  "platform-novelties-cover-white",
  "platform-novelties-cover-frost",
  "platform-novelties-cover-navy",
];

export function PlatformNovelties() {
  const railRef = useRef<HTMLDivElement | null>(null);
  const noveltiesQuery = usePublicCatalog({ page: 1, limit: 12 });

  const items = useMemo<NoveltyItem[]>(() => {
    const fromDb = (noveltiesQuery.data?.data ?? []).slice(0, 8).map(
      (book: CatalogSearchItem, index: number) => ({
        id: book.id,
        title: book.title.display || book.title.raw || "Без названия",
        author: book.primaryAuthor || "Автор не указан",
        publisher: book.publisher?.name || "Издатель не указан",
        coverClassName: coverPalette[index % coverPalette.length],
        accentLabel: (book.language.code || "catalog").toUpperCase(),
      }),
    );

    return fromDb;
  }, [noveltiesQuery.data]);

  const slide = (direction: "prev" | "next") => {
    if (!railRef.current) {
      return;
    }

    railRef.current.scrollBy({
      left: direction === "prev" ? -360 : 360,
      behavior: "smooth",
    });
  };

  return (
    <section className="platform-novelties-section">
      <h2 className="platform-novelties-title">Новинки платформы</h2>

      <div className="platform-novelties-wrap">
        <button
          type="button"
          className="platform-novelties-arrow platform-novelties-arrow-left"
          onClick={() => slide("prev")}
          aria-label="Предыдущие новинки"
        >
          <span aria-hidden>&lt;</span>
        </button>

        <div ref={railRef} className="platform-novelties-rail" role="list">
          {items.length === 0 ? (
            <div className="platform-novelties-item">
              <p className="platform-novelties-item-author">
                Новинки не найдены в базе
              </p>
            </div>
          ) : null}

          {items.map((item: NoveltyItem) => (
            <article key={item.id} className="platform-novelties-item" role="listitem">
              <Link to={`/books/${item.id}`}>
                <div className={`platform-novelties-cover ${item.coverClassName}`}>
                  <span className="platform-novelties-cover-badge">{item.accentLabel}</span>
                  <h3 className="platform-novelties-cover-title">{item.title}</h3>
                </div>

                <div className="platform-novelties-meta">
                  <h4 className="platform-novelties-item-title">{item.title}</h4>
                  <p className="platform-novelties-item-author">{item.author}</p>
                  <p className="platform-novelties-item-publisher">({item.publisher})</p>
                </div>
              </Link>
            </article>
          ))}
        </div>

        <button
          type="button"
          className="platform-novelties-arrow platform-novelties-arrow-right"
          onClick={() => slide("next")}
          aria-label="Следующие новинки"
        >
          <span aria-hidden>&gt;</span>
        </button>
      </div>
    </section>
  );
}
