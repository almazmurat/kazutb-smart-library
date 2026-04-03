<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Каталог книг — Library Hub</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #f5f7fb;
      --surface: #ffffff;
      --surface-soft: #f8fbff;
      --border: rgba(15, 23, 42, .08);
      --text: #14213d;
      --muted: #64748b;
      --blue: #3b82f6;
      --cyan: #06b6d4;
      --violet: #7c3aed;
      --pink: #ec4899;
      --gold: #d6a85f;
      --success: #16a34a;
      --shadow: 0 20px 50px rgba(15, 23, 42, .08);
      --shadow-soft: 0 12px 26px rgba(15, 23, 42, .05);
      --radius-xl: 30px;
      --radius-lg: 24px;
      --radius-md: 18px;
      --container: 1650px;
    }

    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Inter', system-ui, sans-serif;
      color: var(--text);
      background:
        radial-gradient(circle at 10% 10%, rgba(59,130,246,.08), transparent 18%),
        radial-gradient(circle at 90% 10%, rgba(236,72,153,.06), transparent 16%),
        linear-gradient(180deg, #ffffff 0%, #f7f9fd 42%, #f3f6fb 100%);
    }

    a { color: inherit; text-decoration: none; }
    .container { width: min(100% - 32px, var(--container)); margin: 0 auto; }

    .topbar {
      position: sticky;
      top: 0;
      z-index: 40;
      background: rgba(255,255,255,.82);
      backdrop-filter: blur(18px);
      border-bottom: 1px solid var(--border);
    }

    .nav {
      min-height: 84px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 18px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 14px;
      font-weight: 900;
      letter-spacing: -.3px;
      cursor: pointer;
    }



    .brand small {
      display: block;
      color: var(--muted);
      margin-top: 3px;
      font-weight: 500;
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 24px;
      font-weight: 600;
      color: #334155;
    }

    .nav-links a:hover { color: var(--blue); }

    .nav-actions { display: flex; gap: 12px; }

    .btn {
      border: 0;
      cursor: pointer;
      font: inherit;
      border-radius: 16px;
      padding: 14px 22px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: .25s ease;
      font-weight: 700;
    }

    .btn:hover { transform: translateY(-2px); }
    .btn-primary { color: white; background: linear-gradient(135deg, var(--blue), var(--cyan)); box-shadow: 0 16px 30px rgba(59,130,246,.22); }
    .btn-ghost { background: #fff; border: 1px solid var(--border); color: var(--text); box-shadow: var(--shadow-soft); }

    .page { padding: 34px 0 70px; }

    .hero {
      background: rgba(255,255,255,.92);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      border-radius: var(--radius-xl);
      padding: 30px;
      margin-bottom: 22px;
      overflow: hidden;
      position: relative;
    }

    .hero::before {
      content: "";
      position: absolute;
      right: -80px;
      top: -80px;
      width: 260px;
      height: 260px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(59,130,246,.12), transparent 70%);
    }

    .eyebrow {
      display: inline-flex;
      padding: 10px 14px;
      border-radius: 999px;
      background: rgba(59,130,246,.08);
      border: 1px solid rgba(59,130,246,.10);
      color: var(--blue);
      font-size: 14px;
      font-weight: 700;
    }

    .hero h1 {
      margin: 16px 0 12px;
      font-size: clamp(34px, 5vw, 58px);
      line-height: .98;
      letter-spacing: -1.8px;
    }

    .hero p {
      margin: 0;
      color: var(--muted);
      font-size: 17px;
      line-height: 1.8;
      max-width: 900px;
    }

    .search-wrap {
      display: grid;
      grid-template-columns: 1.2fr .8fr .6fr auto;
      gap: 12px;
      margin-top: 24px;
    }

    .input, .select {
      width: 100%;
      border: 1px solid var(--border);
      background: #fff;
      color: var(--text);
      border-radius: 16px;
      padding: 15px 16px;
      outline: none;
      font: inherit;
      box-shadow: var(--shadow-soft);
    }

    .layout {
      display: grid;
      grid-template-columns: 320px 1fr;
      gap: 22px;
      align-items: start;
    }

    .card {
      background: rgba(255,255,255,.95);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      border-radius: var(--radius-xl);
    }

    .filters {
      padding: 24px;
      position: sticky;
      top: 104px;
    }

    .filter-title {
      margin: 0 0 16px;
      font-size: 22px;
    }

    .filter-group { margin-bottom: 22px; }
    .filter-group:last-child { margin-bottom: 0; }

    .filter-label {
      display: block;
      font-size: 14px;
      font-weight: 800;
      margin-bottom: 12px;
      color: #334155;
    }

    .chips { display: flex; flex-wrap: wrap; gap: 10px; }

    .chip {
      padding: 10px 14px;
      border-radius: 999px;
      background: #fff;
      border: 1px solid var(--border);
      font-size: 14px;
      font-weight: 600;
      color: #334155;
      box-shadow: var(--shadow-soft);
      cursor: pointer;
      transition: all 0.2s;
    }

    .chip:hover {
      border-color: var(--blue);
      background: rgba(59,130,246,.04);
    }

    .chip.active {
      background: rgba(59,130,246,.08);
      color: var(--blue);
      border-color: rgba(59,130,246,.14);
    }

    .check-list { display: grid; gap: 12px; }

    .check-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      color: #334155;
      font-weight: 500;
    }

    .check-item input { accent-color: var(--blue); cursor: pointer; }
    .check-item span:last-child { color: var(--muted); font-size: 13px; }

    .results {
      padding: 24px;
    }

    .results-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      margin-bottom: 18px;
      flex-wrap: wrap;
    }

    .results-top strong { font-size: 22px; }
    .results-top p { margin: 6px 0 0; color: var(--muted); }

    .sort-box {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
    }

    .book-card {
      padding: 18px;
      border-radius: 24px;
      background: #fff;
      border: 1px solid var(--border);
      box-shadow: var(--shadow-soft);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .book-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow);
    }

    .book-preview {
      height: 450px;
      border-radius: 18px;
      padding: 18px;
      display: flex;
      align-items: flex-end;
      position: relative;
      overflow: hidden;
      margin-bottom: 16px;
      background: linear-gradient(180deg, #2d4268 0%, #223758 100%);
    }

    .book-card:nth-child(2) .book-preview,
    .book-card:nth-child(5) .book-preview {
      background: linear-gradient(180deg, #8f1f1f 0%, #6d1111 100%);
    }

    .book-card:nth-child(3) .book-preview,
    .book-card:nth-child(6) .book-preview {
      background: linear-gradient(180deg, #205f43 0%, #134935 100%);
    }

    .book-preview::before {
      content: "";
      position: absolute;
      inset: 0 auto 0 0;
      width: 10px;
      background: rgba(0,0,0,.18);
    }

    .book-preview small {
      position: absolute;
      left: 18px;
      top: 18px;
      color: rgba(255,255,255,.58);
      font-size: 11px;
      letter-spacing: .16em;
      text-transform: uppercase;
      font-weight: 700;
    }

    .book-preview h3 {
      position: relative;
      z-index: 1;
      margin: 0;
      color: #f1d08e;
      font-size: 28px;
      line-height: .98;
      letter-spacing: -.8px;
      max-width: 170px;
    }

    .meta-row {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 10px;
    }

    .tag {
      padding: 8px 12px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 700;
      background: rgba(59,130,246,.08);
      color: var(--blue);
    }

    .tag.green {
      background: rgba(22,163,74,.08);
      color: var(--success);
    }

    .book-title {
      margin: 0 0 8px;
      font-size: 22px;
      line-height: 1.15;
    }

    .book-desc {
      margin: 0 0 14px;
      color: var(--muted);
      line-height: 1.7;
      font-size: 14px;
      min-height: 72px;
    }

    .book-info {
      display: grid;
      gap: 10px;
      margin-bottom: 16px;
    }

    .book-info div {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      font-size: 14px;
      padding-bottom: 10px;
      border-bottom: 1px solid var(--border);
    }

    .book-info div:last-child { border-bottom: 0; padding-bottom: 0; }
    .book-info span:first-child { color: var(--muted); }
    .book-info span:last-child { font-weight: 700; text-align: right; }

    .book-actions {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 10px;
    }

    .icon-btn {
      width: 50px;
      border-radius: 16px;
      border: 1px solid var(--border);
      background: #fff;
      box-shadow: var(--shadow-soft);
      font-size: 18px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .icon-btn:hover {
      background: rgba(59,130,246,.08);
      border-color: var(--blue);
    }

    .pagination {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 24px;
      flex-wrap: wrap;
    }

    .page-btn {
      min-width: 46px;
      height: 46px;
      border-radius: 14px;
      border: 1px solid var(--border);
      background: #fff;
      box-shadow: var(--shadow-soft);
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
    }

    .page-btn:hover {
      border-color: var(--blue);
      background: rgba(59,130,246,.04);
    }

    .page-btn.active {
      color: white;
      border-color: transparent;
      background: linear-gradient(135deg, var(--blue), var(--cyan));
    }

    @media (max-width: 1200px) {
      .grid { grid-template-columns: repeat(2, 1fr); }
      .search-wrap { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 920px) {
      .layout { grid-template-columns: 1fr; }
      .filters { position: static; top: auto; }
      .nav-links, .nav-actions { display: none; }
      .grid, .search-wrap { grid-template-columns: 1fr; }
    }

    @media (max-width: 560px) {
      .container { width: min(100% - 20px, var(--container)); }
      .hero, .filters, .results { padding: 20px; }
      .grid { grid-template-columns: 1fr; }
      .book-actions { grid-template-columns: 1fr; }
      .icon-btn { width: 100%; height: 50px; }
      .search-wrap { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <header class="topbar">
    <div class="container nav">
      <a href="/" class="brand">
        <div class="brand-badge">
          <img src="/logo.png" alt="Logo" style="width:50px; height:50px; object-fit:contain;">
        </div>
        <div>
          <div id="brand-title">КАЗАХСКИЙ УНИВЕРСИТЕТ ТЕХНОЛОГИИ и БИЗНЕСА</div>
          <small id="brand-subtitle">Каталог книг</small>
        </div>
      </a>

      <nav class="nav-links">
        <a href="/">Главная</a>
        <a href="/catalog">Каталог</a>
        <a href="/">Преимущества</a>
        <a href="/">Сервисы</a>
      </nav>

      <div class="nav-actions">
        <button id="header-login-btn" class="btn btn-ghost">Войти</button>
        <a href="/account" id="header-catalog-btn" class="btn btn-primary">Личный кабинет</a>
      </div>
    </div>
  </header>

  <main class="page">
    <div class="container">
      <section class="hero">
        <div class="eyebrow">Каталог университетской библиотеки</div>
        <h1>Найдите нужную книгу, учебник или научное издание</h1>
        <p>Удобный поиск по фонду библиотеки с фильтрами по категориям, формату, году издания, языку и доступности.</p>

        <div class="search-wrap">
          <input class="input" id="search-input" type="text" placeholder="Поиск по названию, автору, ISBN или ключевому слову" />
          <select class="select" id="category-select">
            <option value="">Все категории</option>
            <option value="учебники">Учебники</option>
            <option value="научные">Научные статьи</option>
            <option value="диссертации">Диссертации</option>
            <option value="e-books">Электронные книги</option>
          </select>
          <select class="select" id="format-select">
            <option value="">Все форматы</option>
            <option value="печать">Печатные</option>
            <option value="электронные">Электронные</option>
            <option value="оба">Печатные + PDF</option>
          </select>
          <button class="btn btn-primary" onclick="loadCatalog()">Найти</button>
        </div>
      </section>

      <section class="layout">
        <aside class="card filters">
          <h2 class="filter-title">Фильтры</h2>

          <div class="filter-group">
            <span class="filter-label">Популярные категории</span>
            <div class="chips" id="category-chips">
              <span class="chip active" data-filter="all">Все</span>
              <span class="chip" data-filter="it">IT</span>
              <span class="chip" data-filter="economics">Экономика</span>
              <span class="chip" data-filter="design">Дизайн</span>
              <span class="chip" data-filter="security">Безопасность</span>
              <span class="chip" data-filter="marketing">Маркетинг</span>
            </div>
          </div>

          <div class="filter-group">
            <span class="filter-label">Доступность</span>
            <div class="check-list">
              <label class="check-item">
                <span><input type="checkbox" name="availability" value="available" checked> В наличии</span>
                <span id="availability-count-available">184</span>
              </label>
              <label class="check-item">
                <span><input type="checkbox" name="availability" value="online"> Только онлайн</span>
                <span id="availability-count-online">73</span>
              </label>
              <label class="check-item">
                <span><input type="checkbox" name="availability" value="issued"> На руках</span>
                <span id="availability-count-issued">29</span>
              </label>
            </div>
          </div>

          <div class="filter-group">
            <span class="filter-label">Тип издания</span>
            <div class="check-list">
              <label class="check-item">
                <span><input type="checkbox" name="publication-type" value="textbooks" checked> Учебники</span>
                <span id="type-count-textbooks">126</span>
              </label>
              <label class="check-item">
                <span><input type="checkbox" name="publication-type" value="manuals"> Методические пособия</span>
                <span id="type-count-manuals">84</span>
              </label>
              <label class="check-item">
                <span><input type="checkbox" name="publication-type" value="scientific"> Научные издания</span>
                <span id="type-count-scientific">61</span>
              </label>
              <label class="check-item">
                <span><input type="checkbox" name="publication-type" value="ebooks"> Электронные книги</span>
                <span id="type-count-ebooks">102</span>
              </label>
            </div>
          </div>

          <div class="filter-group">
            <span class="filter-label">Язык</span>
            <div class="chips" id="language-chips">
              <span class="chip active" data-lang="ru">Русский</span>
              <span class="chip" data-lang="kk">Қазақша</span>
              <span class="chip" data-lang="en">English</span>
            </div>
          </div>

          <div class="filter-group">
            <span class="filter-label">Год издания</span>
            <div class="chips" id="year-chips">
              <span class="chip" data-year="2026">2026</span>
              <span class="chip active" data-year="2025">2025</span>
              <span class="chip" data-year="2024">2024</span>
              <span class="chip" data-year="2023">2023</span>
              <span class="chip" data-year="older">Ранее</span>
            </div>
          </div>

          <button class="btn btn-primary" style="width:100%; margin-top:6px;" onclick="applyFilters()">Применить фильтры</button>
        </aside>

        <div class="card results">
          <div class="results-top">
            <div>
              <strong id="results-count">Найдено 0 книг</strong>
              <p>Подборка учебной и научной литературы по выбранным параметрам.</p>
            </div>
            <div class="sort-box">
              <span style="color:var(--muted); font-weight:600;">Сортировка:</span>
              <select class="select" id="sort-select" style="min-width:220px;" onchange="loadCatalog()">
                <option value="popular">Сначала популярные</option>
                <option value="newest">Сначала новые</option>
                <option value="title">По названию</option>
                <option value="author">По автору</option>
              </select>
            </div>
          </div>

          <div class="grid" id="catalog-grid"></div>

          <div class="pagination" id="pagination"></div>
        </div>
      </section>
    </div>
  </main>

  <script>
    const API_ENDPOINT = '/api/v1/catalog-db';
    let currentPage = 1;
    let totalPages = 1;

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function formatBookData(book) {
      return {
        title: book.title?.display || book.title?.raw || 'Без названия',
        author: book.primaryAuthor || 'Автор не указан',
        publisher: book.publisher?.name || 'Издатель не указан',
        year: book.publicationYear || '—',
        language: book.language?.raw || book.language?.code || 'Не указан',
        format: book.copies?.available > 0 ? 'Печатная + PDF' : 'PDF',
        available: book.copies?.available || 0,
        isbn: book.isbn?.raw || '',
        type: 'Учебник'
      };
    }

    function renderBookCard(book) {
      const data = formatBookData(book);
      const isAvailable = data.available > 0;

      return `
        <article class="book-card" onclick="goToBook('${escapeHtml(data.isbn)}')">
          <div class="book-preview">
            <small>${escapeHtml(data.publisher.substring(0, 15))}</small>
            <h3>${escapeHtml(data.title.substring(0, 30))}</h3>
          </div>
          <div class="meta-row">
            <span class="tag">${escapeHtml(data.type)}</span>
            <span class="tag ${isAvailable ? 'green' : ''}">${isAvailable ? 'В наличии' : 'На заказ'}</span>
          </div>
          <h3 class="book-title">${escapeHtml(data.title)}</h3>
          <p class="book-desc">${escapeHtml(data.publisher)}</p>
          <div class="book-info">
            <div><span>Автор</span><span>${escapeHtml(data.author.substring(0, 40))}</span></div>
            <div><span>Год</span><span>${escapeHtml(data.year)}</span></div>
            <div><span>Формат</span><span>${escapeHtml(data.format)}</span></div>
          </div>
          <div class="book-actions">
            <button class="btn btn-primary" onclick="event.stopPropagation(); goToBook('${escapeHtml(data.isbn)}')">Смотреть книгу</button>
            <button class="icon-btn" onclick="event.stopPropagation(); toggleFavorite(this)">♡</button>
          </div>
        </article>
      `;
    }

    async function loadCatalog() {
      const searchInput = document.getElementById('search-input');
      const sortSelect = document.getElementById('sort-select');
      const grid = document.getElementById('catalog-grid');
      const resultsCount = document.getElementById('results-count');

      try {
        grid.innerHTML = '<div style="grid-column:1/-1; text-align:center; padding:40px; color:var(--muted);">Загрузка...</div>';

        const params = new URLSearchParams();
        if (searchInput.value) params.set('q', searchInput.value);
        params.set('page', currentPage);
        params.set('limit', 6);
        params.set('sort', sortSelect.value);

        const response = await fetch(`${API_ENDPOINT}?${params}`, {
          headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('API Error');

        const data = await response.json();
        const books = data.data || [];
        const meta = data.meta || {};

        if (books.length === 0) {
          grid.innerHTML = '<div style="grid-column:1/-1; text-align:center; padding:40px; color:var(--muted);">Книги не найдены</div>';
          resultsCount.textContent = 'Найдено 0 книг';
        } else {
          grid.innerHTML = books.map(renderBookCard).join('');
          resultsCount.textContent = `Найдено ${meta.total || books.length} книг`;
          totalPages = meta.totalPages || 1;
          renderPagination();
        }
      } catch (err) {
        grid.innerHTML = '<div style="grid-column:1/-1; text-align:center; padding:40px; color:red;">Ошибка загрузки каталога</div>';
        console.error(err);
      }
    }

    function renderPagination() {
      const pagination = document.getElementById('pagination');
      if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
      }

      let html = '';
      if (currentPage > 1) {
        html += `<button class="page-btn" onclick="changePage(1)">←</button>`;
      }

      const start = Math.max(1, currentPage - 2);
      const end = Math.min(totalPages, currentPage + 2);

      if (start > 1) {
        html += `<button class="page-btn" onclick="changePage(1)">1</button>`;
        if (start > 2) html += '<span style="color:var(--muted);">...</span>';
      }

      for (let i = start; i <= end; i++) {
        html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
      }

      if (end < totalPages) {
        if (end < totalPages - 1) html += '<span style="color:var(--muted);">...</span>';
        html += `<button class="page-btn" onclick="changePage(${totalPages})">${totalPages}</button>`;
      }

      if (currentPage < totalPages) {
        html += `<button class="page-btn" onclick="changePage(${currentPage + 1})">→</button>`;
      }

      pagination.innerHTML = html;
    }

    function changePage(page) {
      currentPage = page;
      loadCatalog();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function goToBook(isbn) {
      window.location.href = `/book/${encodeURIComponent(isbn)}`;
    }

    function toggleFavorite(btn) {
      btn.textContent = btn.textContent === '♡' ? '♥' : '♡';
      btn.style.color = btn.textContent === '♥' ? 'var(--pink)' : 'inherit';
    }

    function applyFilters() {
      currentPage = 1;
      loadCatalog();
    }

    const ME_ENDPOINT = '/api/v1/me';

    async function updateLoginButtonState() {
      const loginBtn = document.getElementById('header-login-btn');
      if (!loginBtn) return;

      let authenticated = false;
      try {
        const response = await fetch(ME_ENDPOINT, {
          headers: { Accept: 'application/json' },
        });

        if (response.ok) {
          const payload = await response.json().catch(() => ({}));
          authenticated = payload?.authenticated === true;
        }
      } catch (_) {
        authenticated = false;
      }

      loginBtn.style.display = authenticated ? 'none' : 'inline-flex';
      loginBtn.textContent = 'Войти';
    }

    document.getElementById('header-login-btn')?.addEventListener('click', async () => {
      const redirectTo = encodeURIComponent(window.location.pathname + window.location.search);
      window.location.href = `/login?redirect=${redirectTo}`;
    });

    // Setup chip filters
    document.querySelectorAll('.chip').forEach(chip => {
      chip.addEventListener('click', function() {
        this.parentElement.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Initial load
    updateLoginButtonState();
    loadCatalog();
  </script>
</body>
</html>
