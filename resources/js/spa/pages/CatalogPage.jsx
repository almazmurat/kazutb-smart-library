import React, { useState, useEffect, useCallback } from 'react';
import { api } from '../lib/api';

export function CatalogPage() {
  const [query, setQuery] = useState('');
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);
  const [total, setTotal] = useState(0);
  const [page, setPage] = useState(1);

  const search = useCallback(async (q, p = 1) => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      if (q.trim()) params.set('q', q.trim());
      params.set('page', String(p));
      params.set('limit', '20');

      const data = await api(`/catalog-db?${params}`);
      const items = Array.isArray(data?.data) ? data.data : [];
      setResults(items);
      setTotal(Number(data?.meta?.total ?? 0));
      setPage(p);
    } catch (err) {
      console.error('Catalog search failed:', err);
      setResults([]);
      setTotal(0);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    search('', 1);
  }, [search]);

  const handleSearch = (e) => {
    e.preventDefault();
    search(query, 1);
  };

  return (
    <div className="page-catalog">
      <div className="page-header">
        <h1 className="page-title">Каталог</h1>
        <p className="page-subtitle">
          {total > 0 ? `${total.toLocaleString('ru-RU')} документов` : 'Поиск по каталогу'}
        </p>
      </div>

      <form className="search-bar" onSubmit={handleSearch}>
        <input
          type="text"
          className="search-input"
          placeholder="Поиск по названию, автору, ISBN…"
          value={query}
          onChange={(e) => setQuery(e.target.value)}
        />
        <button type="submit" className="search-btn" disabled={loading}>
          {loading ? '⏳' : '🔍'} Найти
        </button>
      </form>

      {loading && results.length === 0 && (
        <div className="loading-state">Загрузка каталога…</div>
      )}

      <div className="results-grid">
        {results.map((doc) => {
          const identifier = doc?.isbn?.raw || doc?.id;
          const title = doc?.title?.display || doc?.title?.raw || 'Без названия';
          const subtitle = doc?.title?.subtitle;
          const publicationYear = doc?.publicationYear;
          const languageCode = doc?.language?.code;
          const rawIsbn = doc?.isbn?.raw;
          const availableCopies = Number(doc?.copies?.available ?? NaN);

          return (
            <a
              key={doc?.id || identifier}
              href={identifier ? `/book/${encodeURIComponent(identifier)}` : '/catalog'}
              className="result-card"
            >
              <div className="card-title">{title}</div>
              {subtitle && <div className="card-subtitle">{subtitle}</div>}
              <div className="card-meta">
                {publicationYear && <span className="meta-year">{publicationYear}</span>}
                {languageCode && <span className="meta-lang">{languageCode.toUpperCase()}</span>}
                {rawIsbn && <span className="meta-isbn">ISBN: {rawIsbn}</span>}
              </div>
              {!Number.isNaN(availableCopies) && (
                <div className={`card-availability ${availableCopies > 0 ? 'available' : 'unavailable'}`}>
                  {availableCopies > 0
                    ? `${availableCopies} экз. доступно`
                    : 'Нет в наличии'}
                </div>
              )}
            </a>
          );
        })}
      </div>

      {results.length === 0 && !loading && (
        <div className="empty-state">
          {query ? 'Ничего не найдено. Попробуйте другой запрос.' : 'Начните поиск, чтобы увидеть результаты.'}
        </div>
      )}

      {total > 20 && (
        <div className="pagination">
          <button
            className="page-btn"
            disabled={page <= 1}
            onClick={() => search(query, page - 1)}
          >
            ← Назад
          </button>
          <span className="page-info">
            Стр. {page} из {Math.ceil(total / 20)}
          </span>
          <button
            className="page-btn"
            disabled={page >= Math.ceil(total / 20)}
            onClick={() => search(query, page + 1)}
          >
            Далее →
          </button>
        </div>
      )}
    </div>
  );
}
