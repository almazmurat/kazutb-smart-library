<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Каталог книг — Library Hub</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/shell.css">
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

    .tag.subject {
      background: rgba(124,58,237,.08);
      color: var(--violet);
      font-size: 11px;
      padding: 5px 10px;
    }

    .active-subject-banner {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 18px;
      background: linear-gradient(135deg, rgba(124,58,237,.06), rgba(59,130,246,.06));
      border: 1px solid rgba(124,58,237,.15);
      border-radius: 14px;
      margin-bottom: 16px;
      font-size: 14px;
      color: var(--text);
    }

    .active-subject-banner strong {
      color: var(--violet);
    }

    .active-subject-banner .clear-btn {
      margin-left: auto;
      padding: 4px 12px;
      border-radius: 999px;
      border: 1px solid rgba(124,58,237,.2);
      background: #fff;
      font-size: 12px;
      font-weight: 600;
      color: var(--violet);
      cursor: pointer;
    }

    .subject-select {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid var(--border);
      border-radius: 12px;
      background: #fff;
      font-size: 13px;
      font-family: inherit;
      color: var(--text);
      cursor: pointer;
    }

    .subject-select:focus {
      outline: none;
      border-color: var(--violet);
      box-shadow: 0 0 0 3px rgba(124,58,237,.1);
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
      background: rgba(124,58,237,.08);
      border-color: var(--violet);
    }

    .icon-btn.shortlisted {
      background: rgba(124,58,237,.1);
      border-color: var(--violet);
      color: var(--violet);
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
    }

    @media (max-width: 920px) {
      .layout { grid-template-columns: 1fr; }
      .filters { position: static; top: auto; }
      .nav-links, .nav-actions { display: none; }
      .grid { grid-template-columns: 1fr; }
      .search-wrap { grid-template-columns: 1fr auto !important; }
      .mobile-toggle { display: inline-grid; place-items: center; min-width: 44px; min-height: 44px; }
    }

    @media (max-width: 560px) {
      .container { width: min(100% - 20px, var(--container)); }
      .hero, .filters, .results { padding: 18px; }
      .grid { grid-template-columns: 1fr; }
      .book-actions { grid-template-columns: 1fr; }
      .icon-btn { width: 100%; height: 50px; min-height: 44px; }
      .search-wrap { grid-template-columns: 1fr; }
      .nav { min-height: 64px; }
      .brand-text { font-size: 13px; }
      .brand-text small { font-size: 10.5px; }
      .hero h1 { font-size: 24px; }
    }

    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
<body>
  <header class="topbar">
    <div class="container nav">
      <a href="/" class="brand">
        <div class="brand-badge">
          <img src="/logo.png" alt="Logo" class="logo-img">
        </div>
        <div>
          <div id="brand-title">КАЗАХСКИЙ УНИВЕРСИТЕТ ТЕХНОЛОГИИ и БИЗНЕСА</div>
          <small id="brand-subtitle">Каталог книг</small>
        </div>
      </a>

      <nav class="nav-links">
        <a href="/">Главная</a>
        <a href="/catalog" class="active">Каталог</a>
        <a href="/resources">Ресурсы</a>
        <a href="/services">Сервисы</a>
        <a href="/news">Новости</a>
        <a href="/about">О библиотеке</a>
        <a href="/contacts">Контакты</a>
      </nav>

      <div class="nav-actions">
        @if(session('library.user'))
          <a href="/account" class="btn btn-ghost">Кабинет</a>
          <button type="button" class="btn btn-primary" id="shared-logout-btn">Выйти</button>
        @else
          <a href="/login" class="btn btn-ghost">Войти</a>
          <a href="/account" class="btn btn-primary">Личный кабинет</a>
        @endif
      </div>
    </div>
  </header>

  <main class="page">
    <div class="container">
      <section class="hero">
        <div class="eyebrow">Каталог университетской библиотеки</div>
        <h1>Найдите нужную книгу, учебник или научное издание</h1>
        <p>Удобный поиск по фонду библиотеки с фильтрами по категориям, формату, году издания, языку и доступности.</p>

        <div class="search-wrap" style="grid-template-columns: 1fr auto;">
          <input class="input" id="search-input" type="text" placeholder="Поиск по названию, автору, ISBN или ключевому слову" onkeydown="if(event.key==='Enter'){loadCatalog()}" />
          <button class="btn btn-primary" onclick="loadCatalog()">Найти</button>
        </div>
      </section>

      <section class="layout">
        <aside class="card filters">
          <h2 class="filter-title">Фильтры</h2>

          <div class="filter-group">
            <span class="filter-label">Доступность</span>
            <div class="check-list">
              <label class="check-item">
                <span><input type="checkbox" id="filter-available-only" onchange="applyFilters()"> Только в наличии</span>
              </label>
            </div>
          </div>

          <div class="filter-group">
            <span class="filter-label">Язык</span>
            <div class="chips" id="language-chips">
              <span class="chip active" data-lang="">Все</span>
              <span class="chip" data-lang="ru">Русский</span>
              <span class="chip" data-lang="kk">Қазақша</span>
              <span class="chip" data-lang="en">English</span>
            </div>
          </div>

          <div class="filter-group">
            <span class="filter-label">Год издания</span>
            <div class="chips" id="year-chips">
              <span class="chip active" data-year="">Все</span>
              <span class="chip" data-year="2025">2025</span>
              <span class="chip" data-year="2024">2024</span>
              <span class="chip" data-year="2023">2023</span>
              <span class="chip" data-year="2020-2022">2020–2022</span>
              <span class="chip" data-year="older">Ранее</span>
            </div>
          </div>

          <div class="filter-group" id="subject-filter-group">
            <span class="filter-label">Направление / Специальность</span>
            <select class="subject-select" id="subject-select" onchange="applyFilters()">
              <option value="">Все направления</option>
            </select>
          </div>

          <button class="btn btn-primary" style="width:100%; margin-top:6px;" onclick="applyFilters()">Применить фильтры</button>
        </aside>

        <div class="card results">
          <div id="active-subject-banner"></div>
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

  @include('partials.footer')

  <script>
    const API_ENDPOINT = '/api/v1/catalog-db';
    const SHORTLIST_API = '/api/v1/shortlist';
    let currentPage = 1;
    let totalPages = 1;
    let activeSubjectId = '';
    let activeSubjectLabel = '';
    let subjectsData = null;
    let shortlistState = {};

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function getActiveLanguage() {
      const active = document.querySelector('#language-chips .chip.active');
      return active ? active.dataset.lang || '' : '';
    }

    function getActiveYear() {
      const active = document.querySelector('#year-chips .chip.active');
      return active ? active.dataset.year || '' : '';
    }

    function getYearParams() {
      const year = getActiveYear();
      if (!year) return {};
      if (year === 'older') return { year_to: 2019 };
      if (year.includes('-')) {
        const [from, to] = year.split('-');
        return { year_from: from, year_to: to };
      }
      return { year_from: year, year_to: year };
    }

    function formatBookData(book) {
      const classification = Array.isArray(book.classification) ? book.classification : [];
      const specialization = classification.find(c => c.sourceKind === 'specialization');
      const department = classification.find(c => c.sourceKind === 'department');
      return {
        title: book.title?.display || book.title?.raw || 'Без названия',
        author: book.primaryAuthor || 'Автор не указан',
        publisher: book.publisher?.name || 'Издатель не указан',
        year: book.publicationYear || '—',
        language: book.language?.raw || book.language?.code || 'Не указан',
        format: book.copies?.available > 0 ? 'Печатная + PDF' : 'PDF',
        available: book.copies?.available || 0,
        total: book.copies?.total || 0,
        isbn: book.isbn?.raw || '',
        id: book.id || '',
        specialization: specialization ? specialization.label : '',
        department: department ? department.label : '',
        subjectId: specialization ? specialization.id : (department ? department.id : ''),
      };
    }

    function renderBookCard(book) {
      const data = formatBookData(book);
      const isAvailable = data.available > 0;
      const identifier = data.isbn || data.id;
      const subjectBadge = data.specialization
        ? `<span class="tag subject" title="${escapeHtml(data.specialization)}">${escapeHtml(data.specialization.length > 25 ? data.specialization.substring(0, 25) + '…' : data.specialization)}</span>`
        : (data.department ? `<span class="tag subject" title="${escapeHtml(data.department)}">${escapeHtml(data.department.length > 25 ? data.department.substring(0, 25) + '…' : data.department)}</span>` : '');

      const isShortlisted = shortlistState[identifier] || false;

      return `
        <article class="book-card" onclick="goToBook('${escapeHtml(identifier)}')">
          <div class="book-preview">
            <small>${escapeHtml(data.publisher.substring(0, 15))}</small>
            <h3>${escapeHtml(data.title.substring(0, 30))}</h3>
          </div>
          <div class="meta-row">
            <span class="tag">${escapeHtml(String(data.year))}</span>
            <span class="tag ${isAvailable ? 'green' : ''}">${isAvailable ? data.available + ' экз.' : 'Нет в наличии'}</span>
            ${subjectBadge}
          </div>
          <h3 class="book-title">${escapeHtml(data.title)}</h3>
          <p class="book-desc">${escapeHtml(data.publisher)}</p>
          <div class="book-info">
            <div><span>Автор</span><span>${escapeHtml(data.author.substring(0, 40))}</span></div>
            <div><span>Год</span><span>${escapeHtml(String(data.year))}</span></div>
            <div><span>Язык</span><span>${escapeHtml(data.language)}</span></div>
          </div>
          <div class="book-actions">
            <button class="btn btn-primary" onclick="event.stopPropagation(); goToBook('${escapeHtml(identifier)}')">Смотреть книгу</button>
            <button class="icon-btn ${isShortlisted ? 'shortlisted' : ''}" onclick="event.stopPropagation(); toggleShortlist(this, ${JSON.stringify(JSON.stringify(data))})" title="${isShortlisted ? 'Убрать из подборки' : 'В подборку'}">${isShortlisted ? '★' : '☆'}</button>
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
        grid.innerHTML = '<div style="grid-column:1/-1; text-align:center; padding:40px; color:var(--muted);"><div style="display:inline-block;width:32px;height:32px;border:3px solid #e5e7eb;border-top-color:var(--blue);border-radius:50%;animation:spin .7s linear infinite;"></div><p style="margin:8px 0 0;">Загрузка...</p></div>';

        const params = new URLSearchParams();
        if (searchInput.value) params.set('q', searchInput.value);
        params.set('page', currentPage);
        params.set('limit', 6);
        params.set('sort', sortSelect.value);

        const lang = getActiveLanguage();
        if (lang) params.set('language', lang);

        const yearParams = getYearParams();
        if (yearParams.year_from) params.set('year_from', yearParams.year_from);
        if (yearParams.year_to) params.set('year_to', yearParams.year_to);

        const availableOnly = document.getElementById('filter-available-only');
        if (availableOnly && availableOnly.checked) params.set('available_only', '1');

        if (activeSubjectId) params.set('subject_id', activeSubjectId);

        const response = await fetch(`${API_ENDPOINT}?${params}`, {
          headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('API Error');

        const data = await response.json();
        const books = data.data || [];
        const meta = data.meta || {};

        if (books.length === 0) {
          grid.innerHTML = '<div style="grid-column:1/-1; text-align:center; padding:48px; color:var(--muted); border:1px dashed #e2e8f0; border-radius:16px; background:#f8fafc;"><span style="font-size:32px;">🔍</span><p style="margin:12px 0 0; font-weight:600; color:#334155;">Книги не найдены</p><p style="margin:4px 0 0;">Попробуйте изменить параметры поиска.</p></div>';
          resultsCount.textContent = 'Найдено 0 книг';
          document.getElementById('pagination').innerHTML = '';
        } else {
          // Check shortlist state for rendered books, then re-render
          const identifiers = books.map(b => {
            const d = formatBookData(b);
            return d.isbn || d.id;
          }).filter(Boolean);
          await loadShortlistState(identifiers);
          grid.innerHTML = books.map(renderBookCard).join('');
          resultsCount.textContent = `Найдено ${meta.total || books.length} книг`;
          totalPages = meta.totalPages || 1;
          renderPagination();
        }
      } catch (err) {
        grid.innerHTML = '<div style="grid-column:1/-1; text-align:center; padding:48px; border:1px solid #fca5a5; border-radius:16px; background:#fef2f2;"><span style="font-size:32px;">⚠️</span><p style="margin:12px 0 0; font-weight:600; color:#991b1b;">Ошибка загрузки каталога</p><p style="margin:4px 0 0; color:#b91c1c;">Попробуйте обновить страницу.</p></div>';
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
        html += `<button class="page-btn" onclick="changePage(${currentPage - 1})">←</button>`;
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

    function goToBook(identifier) {
      if (!identifier) return;
      window.location.href = `/book/${encodeURIComponent(identifier)}`;
    }

    async function toggleShortlist(btn, dataJson) {
      const data = JSON.parse(dataJson);
      const identifier = data.isbn || data.id;
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

      if (shortlistState[identifier]) {
        // Remove
        try {
          const res = await fetch(`${SHORTLIST_API}/${encodeURIComponent(identifier)}`, {
            method: 'DELETE',
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
            credentials: 'same-origin',
          });
          if (res.ok) {
            shortlistState[identifier] = false;
            btn.textContent = '☆';
            btn.classList.remove('shortlisted');
            btn.title = 'В подборку';
          }
        } catch (e) { console.error(e); }
      } else {
        // Add
        try {
          const res = await fetch(SHORTLIST_API, {
            method: 'POST',
            headers: {
              Accept: 'application/json',
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
            },
            credentials: 'same-origin',
            body: JSON.stringify({
              identifier: identifier,
              title: data.title,
              author: data.author,
              publisher: data.publisher,
              year: String(data.year || ''),
              language: data.language,
              isbn: data.isbn,
              available: data.available,
              total: data.total,
            }),
          });
          if (res.ok || res.status === 201 || res.status === 409) {
            shortlistState[identifier] = true;
            btn.textContent = '★';
            btn.classList.add('shortlisted');
            btn.title = 'Убрать из подборки';
          }
        } catch (e) { console.error(e); }
      }
    }

    async function loadShortlistState(identifiers) {
      if (!identifiers.length) return;
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      try {
        const res = await fetch(`${SHORTLIST_API}/check`, {
          method: 'POST',
          headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
          },
          credentials: 'same-origin',
          body: JSON.stringify({ identifiers }),
        });
        if (res.ok) {
          const json = await res.json();
          shortlistState = { ...shortlistState, ...(json.data || {}) };
        }
      } catch (e) { console.warn('Shortlist check failed:', e); }
    }

    function applyFilters() {
      currentPage = 1;
      loadCatalog();
    }

    @if(session('library.user'))
    document.getElementById('shared-logout-btn')?.addEventListener('click', async () => {
      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        await fetch('/api/v1/logout', {
          method: 'POST',
          headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
        });
      } catch (_) {}
      localStorage.removeItem('library.auth.user');
      window.location.href = '/login';
    });
    @endif

    // Chip filter behavior — click activates chip and reloads catalog
    document.querySelectorAll('#language-chips .chip, #year-chips .chip').forEach(chip => {
      chip.addEventListener('click', function() {
        this.parentElement.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        applyFilters();
      });
    });

    // Read URL params for deep-linking from discovery pages
    (function() {
      const urlParams = new URLSearchParams(window.location.search);
      const urlQ = urlParams.get('q');
      const urlSort = urlParams.get('sort');
      const urlSubjectId = urlParams.get('subject_id');
      const urlSubjectLabel = urlParams.get('subject_label');
      if (urlQ && document.getElementById('search-input')) {
        document.getElementById('search-input').value = urlQ;
      }
      if (urlSort && document.getElementById('sort-select')) {
        document.getElementById('sort-select').value = urlSort;
      }
      if (urlSubjectId) {
        activeSubjectId = urlSubjectId;
        activeSubjectLabel = urlSubjectLabel ? decodeURIComponent(urlSubjectLabel) : '';
      }
    })();

    function updateSubjectBanner() {
      const banner = document.getElementById('active-subject-banner');
      if (!banner) return;
      if (activeSubjectId && activeSubjectLabel) {
        banner.innerHTML = `<div class="active-subject-banner">
          <span>📚</span>
          <span>Фильтр по направлению: <strong>${escapeHtml(activeSubjectLabel)}</strong></span>
          <button class="clear-btn" onclick="clearSubjectFilter()">✕ Сбросить</button>
        </div>`;
      } else {
        banner.innerHTML = '';
      }
    }

    function clearSubjectFilter() {
      activeSubjectId = '';
      activeSubjectLabel = '';
      const sel = document.getElementById('subject-select');
      if (sel) sel.value = '';
      updateSubjectBanner();
      const url = new URL(window.location);
      url.searchParams.delete('subject_id');
      url.searchParams.delete('subject_label');
      history.replaceState(null, '', url.toString());
      applyFilters();
    }

    async function loadSubjects() {
      try {
        const res = await fetch('/api/v1/subjects', { headers: { Accept: 'application/json' } });
        if (!res.ok) return;
        subjectsData = await res.json();
        const sel = document.getElementById('subject-select');
        if (!sel) return;

        const addGroup = (label, items) => {
          if (!items || items.length === 0) return;
          const group = document.createElement('optgroup');
          group.label = label;
          items.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = `${item.label} (${item.documentCount})`;
            opt.dataset.label = item.label;
            group.appendChild(opt);
          });
          sel.appendChild(group);
        };

        addGroup('Факультеты', subjectsData.faculties);
        addGroup('Кафедры', subjectsData.departments);
        addGroup('Специальности', subjectsData.specializations);

        if (activeSubjectId) {
          sel.value = activeSubjectId;
          if (!activeSubjectLabel) {
            const opt = sel.querySelector(`option[value="${activeSubjectId}"]`);
            if (opt) activeSubjectLabel = opt.dataset.label;
          }
        }
        updateSubjectBanner();
      } catch (e) {
        console.warn('Failed to load subjects:', e);
      }
    }

    // Handle subject select change
    document.getElementById('subject-select')?.addEventListener('change', function() {
      activeSubjectId = this.value;
      const opt = this.options[this.selectedIndex];
      activeSubjectLabel = opt && opt.dataset ? (opt.dataset.label || '') : '';
      updateSubjectBanner();
    });

    // Initial load
    loadSubjects();
    loadCatalog();
  </script>
</body>
</html>
