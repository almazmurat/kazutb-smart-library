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
      if (q.trim()) params.set('query', q.trim());
      params.set('page', p);
      params.set('per_page', 20);

      const data = await api(`/catalog/search?${params}`);
      setResults(data.data || []);
      setTotal(data.meta?.total || 0);
      setPage(p);
    } catch (err) {
      console.error('Catalog search failed:', err);
      setResults([]);
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
        {results.map((doc) => (
          <a
            key={doc.id}
            href={`/book/${doc.isbn_normalized || doc.isbn_raw || doc.id}`}
            className="result-card"
          >
            <div className="card-title">{doc.title_display || doc.title_raw || 'Без названия'}</div>
            {doc.subtitle_raw && (
              <div className="card-subtitle">{doc.subtitle_raw}</div>
            )}
            <div className="card-meta">
              {doc.publication_year && <span className="meta-year">{doc.publication_year}</span>}
              {doc.language_code && <span className="meta-lang">{doc.language_code.toUpperCase()}</span>}
              {doc.isbn_normalized && <span className="meta-isbn">ISBN: {doc.isbn_normalized}</span>}
            </div>
            {doc.available_copies !== undefined && (
              <div className={`card-availability ${doc.available_copies > 0 ? 'available' : 'unavailable'}`}>
                {doc.available_copies > 0
                  ? `${doc.available_copies} экз. доступно`
                  : 'Нет в наличии'}
              </div>
            )}
          </a>
        ))}
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
