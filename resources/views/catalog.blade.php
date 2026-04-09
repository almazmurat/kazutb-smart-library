@php
  $lang = app()->getLocale();
  $catalogTitle = [
    'ru' => 'Каталог книг — Digital Library',
    'kk' => 'Кітаптар каталогы — Digital Library',
    'en' => 'Book catalog — Digital Library',
  ][$lang] ?? 'Каталог книг — Digital Library';
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ $catalogTitle }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Newsreader:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/shell.css">
  <style>
    :root {
      --bg: #f8f9fa;
      --surface: #ffffff;
      --surface-soft: #f3f4f5;
      --border: rgba(195, 198, 209, .55);
      --text: #191c1d;
      --muted: #43474f;
      --blue: #001e40;
      --cyan: #14696d;
      --violet: #453000;
      --pink: #2a1c00;
      --gold: #e9c176;
      --success: #14696d;
      --shadow: 0 12px 32px rgba(25, 28, 29, .04);
      --shadow-soft: 0 6px 16px rgba(25, 28, 29, .03);
      --radius-xl: 8px;
      --radius-lg: 6px;
      --radius-md: 4px;
      --container: 1280px;
    }

    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Manrope', system-ui, sans-serif;
      color: var(--text);
      background: var(--bg);
      background-attachment: fixed;
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
      border-radius: var(--radius-lg);
      padding: 14px 22px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: .25s ease;
      font-weight: 700;
    }

    .btn:hover { transform: none; }
    .btn-primary { color: white; background: linear-gradient(135deg, var(--blue), #003366); box-shadow: var(--shadow-soft); border-radius: 999px; }
    .btn-ghost { background: transparent; border: 1px solid var(--border); color: var(--text); box-shadow: none; }

    .page { padding: 34px 0 70px; }

    .hero {
      background: linear-gradient(180deg, #ffffff 0%, #f3f4f5 100%);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      border-radius: var(--radius-xl);
      padding: 30px;
      margin-bottom: 22px;
      overflow: hidden;
      position: relative;
      color: var(--text);
      transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .16s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .hero::before {
      content: "";
      position: absolute;
      right: -70px;
      top: -70px;
      width: 220px;
      height: 220px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(20,105,109,.10), transparent 72%);
      transition: transform .42s cubic-bezier(0.2, 0.8, 0.2, 1), opacity .16s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .hero:hover {
      transform: translate3d(0, -2px, 0);
      box-shadow: 0 18px 38px rgba(25, 28, 29, .06);
      border-color: rgba(0,30,64,.12);
    }

    .hero:hover::before {
      transform: translate3d(-12px, 10px, 0);
    }

    .eyebrow {
      display: inline-flex;
      padding: 0;
      border-radius: 0;
      background: transparent;
      border: 0;
      color: var(--cyan);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .14em;
      text-transform: uppercase;
    }

    .hero h1 {
      margin: 16px 0 12px;
      font-family: 'Newsreader', Georgia, serif;
      font-size: clamp(34px, 5vw, 58px);
      line-height: .98;
      letter-spacing: -1.8px;
      color: var(--blue);
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
      padding: 8px;
      border-radius: var(--radius-xl);
      background: rgba(255,255,255,.82);
      border: 1px solid rgba(195,198,209,.45);
      box-shadow: var(--shadow-soft);
      transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .16s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .search-wrap:focus-within {
      transform: translate3d(0, -2px, 0);
      box-shadow: 0 18px 38px rgba(25,28,29,.06);
      border-color: rgba(0,30,64,.12);
    }

    .input, .select {
      width: 100%;
      border: 1px solid var(--border);
      background: #fff;
      color: var(--text);
      border-radius: 999px;
      padding: 15px 16px;
      outline: none;
      font: inherit;
      box-shadow: none;
      transition: border-color .16s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .16s cubic-bezier(0.2, 0.8, 0.2, 1), background .16s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .input:focus, .select:focus {
      border-color: rgba(0,30,64,.14);
      box-shadow: 0 0 0 4px rgba(0,30,64,.06);
      background: rgba(255,255,255,.98);
    }

    .layout {
      display: grid;
      grid-template-columns: 320px 1fr;
      gap: 22px;
      align-items: start;
    }

    .card {
      background: rgba(255,255,255,.98);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-soft);
      border-radius: var(--radius-xl);
    }

    .filters {
      padding: 24px;
      position: sticky;
      top: 104px;
    }

    .filter-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .filter-title {
      margin: 0;
      font-size: 22px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .filter-badge {
      font-size: 12px;
      font-weight: 700;
      background: var(--blue);
      color: #fff;
      border-radius: 999px;
      min-width: 22px;
      height: 22px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0 6px;
    }

    .btn-clear-filters {
      font-size: 13px;
      font-weight: 600;
      color: var(--muted);
      background: none;
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 6px 14px;
      cursor: pointer;
      transition: all .2s;
    }
    .btn-clear-filters:hover {
      color: #dc2626;
      border-color: #dc2626;
      background: rgba(220, 38, 38, .04);
    }

    .mobile-filter-toggle {
      display: none;
      width: 100%;
      padding: 14px;
      font-size: 15px;
      font-weight: 700;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: 12px;
      cursor: pointer;
      text-align: center;
      margin-bottom: 12px;
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
      transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1);
      font-family: inherit;
      line-height: 1.4;
    }

    .chip:hover {
      border-color: var(--blue);
      background: rgba(0,30,64,.04);
      transform: translate3d(0, -1px, 0);
      box-shadow: 0 10px 22px rgba(25,28,29,.04);
    }

    .chip.active {
      background: rgba(0,30,64,.07);
      color: var(--blue);
      border-color: rgba(0,30,64,.12);
    }

    .active-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      align-items: center;
      margin-bottom: 16px;
    }
    .active-filter-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 600;
      background: rgba(0,30,64,.07);
      color: var(--blue);
      border: 1px solid rgba(0,30,64,.12);
      cursor: pointer;
      transition: background .15s;
    }
    .active-filter-chip:hover { background: rgba(0,30,64,.12); }
    .active-filter-reset {
      padding: 6px 12px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
      background: none;
      border: none;
      color: var(--muted);
      cursor: pointer;
    }
    .active-filter-reset:hover { color: #334155; }

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
      border-radius: var(--radius-xl);
      background: #fff;
      border: 1px solid var(--border);
      box-shadow: none;
      transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color 0.2s ease, background-color 0.2s ease, box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1);
      cursor: pointer;
      transform-style: preserve-3d;
    }

    .book-card:hover {
      transform: translate3d(0, -3px, 0) rotateX(0.6deg);
      box-shadow: 0 16px 34px rgba(25,28,29,.05);
      border-color: rgba(0,30,64,.12);
      background: rgba(248,249,250,.98);
    }

    .book-preview {
      height: 450px;
      border-radius: var(--radius-xl);
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
      background: rgba(0,30,64,.07);
      color: var(--blue);
    }

    .tag.green {
      background: rgba(20,105,109,.08);
      color: var(--success);
    }

    .tag.subject {
      background: rgba(42,28,0,.07);
      color: var(--violet);
      font-size: 11px;
      padding: 5px 10px;
    }

    .active-subject-banner {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 18px;
      background: linear-gradient(135deg, rgba(0,30,64,.03), rgba(20,105,109,.05));
      border: 1px solid rgba(0,30,64,.10);
      border-radius: 8px;
      margin-bottom: 16px;
      font-size: 14px;
      color: var(--text);
    }

    .active-subject-banner strong {
      color: var(--blue);
    }

    .active-subject-banner .clear-btn {
      margin-left: auto;
      padding: 4px 12px;
      border-radius: 999px;
      border: 1px solid rgba(0,30,64,.12);
      background: #fff;
      font-size: 12px;
      font-weight: 600;
      color: var(--blue);
      cursor: pointer;
    }

    .subject-select {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: #fff;
      font-size: 13px;
      font-family: inherit;
      color: var(--text);
      cursor: pointer;
    }

    .subject-select:focus {
      outline: none;
      border-color: var(--blue);
      box-shadow: 0 0 0 3px rgba(0,30,64,.08);
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
      border-radius: 8px;
      border: 1px solid var(--border);
      background: #fff;
      box-shadow: none;
      font-size: 18px;
      cursor: pointer;
      transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), border-color 0.2s ease, background-color 0.2s ease, color 0.2s ease, box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .icon-btn:hover {
      background: rgba(20,105,109,.06);
      border-color: var(--cyan);
      transform: translate3d(0, -1px, 0);
      box-shadow: 0 10px 22px rgba(25,28,29,.04);
    }

    .icon-btn.shortlisted {
      background: rgba(20,105,109,.08);
      border-color: var(--cyan);
      color: var(--cyan);
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
      border-radius: 8px;
      border: 1px solid var(--border);
      background: #fff;
      box-shadow: var(--shadow-soft);
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
    }

    .page-btn:hover {
      border-color: var(--blue);
      background: rgba(0,30,64,.04);
      transform: translate3d(0, -1px, 0);
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
      .filter-header { display: none; }
      .mobile-filter-toggle { display: block; }
      #filters-body { display: none; }
      #filters-body.open { display: block; }
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
  @include('partials.navbar', ['activePage' => 'catalog'])

  <main class="page">
    <div class="container">
      <section class="hero">
        <div class="eyebrow">{{ ['ru' => 'Каталог университетской библиотеки', 'kk' => 'Университет кітапханасының каталогы', 'en' => 'University library catalog'][$lang] }}</div>
        <h1>{{ ['ru' => 'Найдите нужную книгу, учебник или научное издание', 'kk' => 'Қажетті кітапты, оқулықты немесе ғылыми басылымды табыңыз', 'en' => 'Find the right book, textbook, or scholarly edition'][$lang] }}</h1>
        <p>{{ ['ru' => 'Удобный поиск по фонду библиотеки с фильтрами по категориям, формату, году издания, языку и доступности.', 'kk' => 'Категория, формат, басылым жылы, тіл және қолжетімділік бойынша сүзгілері бар ыңғайлы іздеу.', 'en' => 'Search the library collection with filters for category, format, publication year, language, and availability.'][$lang] }}</p>

        <div class="search-wrap" style="grid-template-columns: 1fr auto;">
          <input class="input" id="search-input" type="text" placeholder="{{ ['ru' => 'Поиск по названию, автору, ISBN или ключевому слову', 'kk' => 'Атауы, авторы, ISBN немесе кілтсөз бойынша іздеу', 'en' => 'Search by title, author, ISBN, or keyword'][$lang] }}" onkeydown="if(event.key==='Enter'){loadCatalog()}" />
          <button class="btn btn-primary" onclick="loadCatalog()">{{ ['ru' => 'Найти', 'kk' => 'Іздеу', 'en' => 'Search'][$lang] }}</button>
        </div>
      </section>

      <section class="layout">
        <aside class="card filters" id="filters-panel">
          <div class="filter-header">
            <h2 class="filter-title">{{ ['ru' => 'Фильтры', 'kk' => 'Сүзгілер', 'en' => 'Filters'][$lang] }} <span id="filter-count-badge" class="filter-badge" style="display:none;"></span></h2>
            <button type="button" class="btn-clear-filters" id="clear-filters-btn" style="display:none;" onclick="clearAllFilters()">✕ {{ ['ru' => 'Сбросить', 'kk' => 'Тазарту', 'en' => 'Reset'][$lang] }}</button>
          </div>
          <button type="button" class="mobile-filter-toggle" id="mobile-filter-toggle" onclick="toggleFilters()">
            🔎 {{ ['ru' => 'Фильтры', 'kk' => 'Сүзгілер', 'en' => 'Filters'][$lang] }} <span id="mobile-filter-count"></span>
          </button>
          <div id="filters-body">

          <div class="filter-group">
            <span class="filter-label">{{ ['ru' => 'Доступность', 'kk' => 'Қолжетімділік', 'en' => 'Availability'][$lang] }}</span>
            <div class="check-list">
              <label class="check-item">
                <span><input type="checkbox" id="filter-available-only" onchange="applyFilters()"> {{ ['ru' => 'Только в наличии', 'kk' => 'Тек қолда барлары', 'en' => 'Available only'][$lang] }}</span>
              </label>
            </div>
          </div>

          <div class="filter-group">
            <span class="filter-label">{{ ['ru' => 'Язык', 'kk' => 'Тіл', 'en' => 'Language'][$lang] }}</span>
            <div class="chips" id="language-chips">
              <button type="button" class="chip active" data-lang="">{{ ['ru' => 'Все', 'kk' => 'Барлығы', 'en' => 'All'][$lang] }}</button>
              <button type="button" class="chip" data-lang="ru">Русский</button>
              <button type="button" class="chip" data-lang="kk">Қазақша</button>
              <button type="button" class="chip" data-lang="en">English</button>
            </div>
          </div>

          <div class="filter-group">
            <span class="filter-label">{{ ['ru' => 'Год издания', 'kk' => 'Басылым жылы', 'en' => 'Publication year'][$lang] }}</span>
            <div class="chips" id="year-chips">
              <button type="button" class="chip active" data-year="">{{ ['ru' => 'Все', 'kk' => 'Барлығы', 'en' => 'All'][$lang] }}</button>
              <button type="button" class="chip" data-year="2025">2025</button>
              <button type="button" class="chip" data-year="2024">2024</button>
              <button type="button" class="chip" data-year="2023">2023</button>
              <button type="button" class="chip" data-year="2020-2022">2020–2022</button>
              <button type="button" class="chip" data-year="older">{{ ['ru' => 'Ранее', 'kk' => 'Ертерек', 'en' => 'Earlier'][$lang] }}</button>
            </div>
          </div>

          <div class="filter-group" id="subject-filter-group">
            <span class="filter-label">{{ ['ru' => 'Направление / Специальность', 'kk' => 'Бағыт / Мамандану', 'en' => 'Track / specialization'][$lang] }}</span>
            <select class="subject-select" id="subject-select" onchange="applyFilters()">
              <option value="">{{ ['ru' => 'Все направления', 'kk' => 'Барлық бағыттар', 'en' => 'All tracks'][$lang] }}</option>
            </select>
          </div>

          </div>{{-- /filters-body --}}

          <button class="btn btn-primary" style="width:100%; margin-top:6px;" onclick="applyFilters()">{{ ['ru' => 'Применить фильтры', 'kk' => 'Сүзгілерді қолдану', 'en' => 'Apply filters'][$lang] }}</button>
        </aside>

        <div class="card results">
          <div id="active-subject-banner"></div>
          <div class="results-top">
            <div>
              <strong id="results-count">{{ ['ru' => 'Найдено 0 книг', 'kk' => '0 кітап табылды', 'en' => 'Found 0 books'][$lang] }}</strong>
              <p>{{ ['ru' => 'Подборка учебной и научной литературы по выбранным параметрам.', 'kk' => 'Таңдалған параметрлер бойынша оқу және ғылыми әдебиеттер топтамасы.', 'en' => 'A filtered selection of course and research literature.'][$lang] }}</p>
            </div>
            <div class="sort-box">
              <span style="color:var(--muted); font-weight:600;">{{ ['ru' => 'Сортировка:', 'kk' => 'Сұрыптау:', 'en' => 'Sort by:'][$lang] }}</span>
              <select class="select" id="sort-select" style="min-width:220px;" onchange="loadCatalog()">
                <option value="popular">{{ ['ru' => 'Сначала популярные', 'kk' => 'Алдымен танымалдары', 'en' => 'Most relevant first'][$lang] }}</option>
                <option value="newest">{{ ['ru' => 'Сначала новые', 'kk' => 'Алдымен жаңалары', 'en' => 'Newest first'][$lang] }}</option>
                <option value="title">{{ ['ru' => 'По названию', 'kk' => 'Атауы бойынша', 'en' => 'By title'][$lang] }}</option>
                <option value="author">{{ ['ru' => 'По автору', 'kk' => 'Автор бойынша', 'en' => 'By author'][$lang] }}</option>
              </select>
            </div>
          </div>

          <div id="active-filters" class="active-filters" style="display:none"></div>
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
    const CATALOG_LANG = @json($lang);
    const CATALOG_I18N_MAP = {
      ru: {
        untitled: 'Без названия', authorMissing: 'Автор не указан', publisherMissing: 'Издатель не указан', languageMissing: 'Не указан',
        copies: 'экз.', unavailable: 'Нет в наличии', author: 'Автор', year: 'Год', language: 'Язык', viewBook: 'Смотреть книгу',
        addShortlist: 'В подборку', removeShortlist: 'Убрать из подборки', updateCatalog: 'Обновляем каталог', noResultBadge: 'Нет результата', noBooks: 'Книги не найдены', tryChange: 'Попробуйте изменить параметры поиска.',
        foundZero: 'Найдено 0 книг', foundMany: 'Найдено {count} книг', retry: 'Повтор', loadError: 'Ошибка загрузки каталога', refreshPage: 'Попробуйте обновить страницу.',
        yearLabel: 'Год', availableOnly: 'В наличии', resetAll: 'Сбросить все', subjectFilter: 'Фильтр по направлению', clear: 'Сбросить',
        faculties: 'Факультеты', departments: 'Кафедры', specializations: 'Специальности', login: 'Войти'
      },
      kk: {
        untitled: 'Атауы жоқ', authorMissing: 'Автор көрсетілмеген', publisherMissing: 'Баспа көрсетілмеген', languageMissing: 'Көрсетілмеген',
        copies: 'дана', unavailable: 'Қолжетімсіз', author: 'Автор', year: 'Жыл', language: 'Тіл', viewBook: 'Кітапты ашу',
        addShortlist: 'Топтамаға қосу', removeShortlist: 'Топтамадан алу', updateCatalog: 'Каталог жаңартылуда', noResultBadge: 'Нәтиже жоқ', noBooks: 'Кітаптар табылмады', tryChange: 'Іздеу параметрлерін өзгертіп көріңіз.',
        foundZero: '0 кітап табылды', foundMany: '{count} кітап табылды', retry: 'Қайталау', loadError: 'Каталогты жүктеу қатесі', refreshPage: 'Бетті жаңартып көріңіз.',
        yearLabel: 'Жыл', availableOnly: 'Қолда бар', resetAll: 'Барлығын тазарту', subjectFilter: 'Бағыт бойынша сүзгі', clear: 'Тазарту',
        faculties: 'Факультеттер', departments: 'Кафедралар', specializations: 'Мамандандырулар', login: 'Кіру'
      },
      en: {
        untitled: 'Untitled', authorMissing: 'Author not specified', publisherMissing: 'Publisher not specified', languageMissing: 'Not specified',
        copies: 'copies', unavailable: 'Unavailable', author: 'Author', year: 'Year', language: 'Language', viewBook: 'Open book',
        addShortlist: 'Add to shortlist', removeShortlist: 'Remove from shortlist', updateCatalog: 'Refreshing catalog', noResultBadge: 'No result', noBooks: 'No books found', tryChange: 'Try adjusting the search parameters.',
        foundZero: 'Found 0 books', foundMany: 'Found {count} books', retry: 'Retry', loadError: 'Catalog load error', refreshPage: 'Try refreshing the page.',
        yearLabel: 'Year', availableOnly: 'Available', resetAll: 'Reset all', subjectFilter: 'Subject filter', clear: 'Clear',
        faculties: 'Faculties', departments: 'Departments', specializations: 'Specializations', login: 'Sign in'
      }
    };
    const CATALOG_I18N = CATALOG_I18N_MAP[CATALOG_LANG] || CATALOG_I18N_MAP.ru;

    function withLang(path) {
      const url = new URL(path, window.location.origin);
      if (CATALOG_LANG !== 'ru' && !url.searchParams.has('lang')) {
        url.searchParams.set('lang', CATALOG_LANG);
      }
      return `${url.pathname}${url.search}`;
    }
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
        title: book.title?.display || book.title?.raw || CATALOG_I18N.untitled,
        author: book.primaryAuthor || CATALOG_I18N.authorMissing,
        publisher: book.publisher?.name || CATALOG_I18N.publisherMissing,
        year: book.publicationYear || '—',
        language: book.language?.raw || book.language?.code || CATALOG_I18N.languageMissing,
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
            <span class="tag ${isAvailable ? 'green' : ''}">${isAvailable ? `${data.available} ${CATALOG_I18N.copies}` : CATALOG_I18N.unavailable}</span>
            ${subjectBadge}
          </div>
          <h3 class="book-title">${escapeHtml(data.title)}</h3>
          <p class="book-desc">${escapeHtml(data.publisher)}</p>
          <div class="book-info">
            <div><span>${CATALOG_I18N.author}</span><span>${escapeHtml(data.author.substring(0, 40))}</span></div>
            <div><span>${CATALOG_I18N.year}</span><span>${escapeHtml(String(data.year))}</span></div>
            <div><span>${CATALOG_I18N.language}</span><span>${escapeHtml(data.language)}</span></div>
          </div>
          <div class="book-actions">
            <button class="btn btn-primary" onclick="event.stopPropagation(); goToBook('${escapeHtml(identifier)}')">${CATALOG_I18N.viewBook}</button>
            <button class="icon-btn ${isShortlisted ? 'shortlisted' : ''}" onclick="event.stopPropagation(); toggleShortlist(this, ${JSON.stringify(JSON.stringify(data))})" title="${isShortlisted ? CATALOG_I18N.removeShortlist : CATALOG_I18N.addShortlist}" aria-label="${isShortlisted ? CATALOG_I18N.removeShortlist : CATALOG_I18N.addShortlist}">${isShortlisted ? '★' : '☆'}</button>
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
        grid.innerHTML = `<div style="grid-column:1/-1; text-align:center; padding:34px 24px; color:var(--muted); background:#fff; border:1px solid var(--border); border-radius:8px;"><div style="display:inline-block;width:32px;height:32px;border:3px solid rgba(195,198,209,.55);border-top-color:var(--blue);border-radius:50%;animation:spin .7s linear infinite;"></div><p style="margin:8px 0 0; font-weight:600; color:var(--blue);">${CATALOG_I18N.updateCatalog}</p></div>`;

        const params = new URLSearchParams();
        if (searchInput.value) params.set('q', searchInput.value);
        params.set('page', currentPage);
        params.set('limit', 12);
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
          grid.innerHTML = `<div style="grid-column:1/-1; text-align:center; padding:40px 24px; color:var(--muted); border:1px solid var(--border); border-radius:8px; background:#fff;"><span style="display:inline-flex;align-items:center;justify-content:center;min-width:88px;height:34px;padding:0 12px;border-radius:999px;background:rgba(0,30,64,.05);color:var(--blue);font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;">${CATALOG_I18N.noResultBadge}</span><p style="margin:12px 0 0; font-weight:700; color:var(--blue);">${CATALOG_I18N.noBooks}</p><p style="margin:4px 0 0;">${CATALOG_I18N.tryChange}</p></div>`;
          resultsCount.textContent = CATALOG_I18N.foundZero;
          document.getElementById('pagination').innerHTML = '';
        } else {
          // Check shortlist state for rendered books, then re-render
          const identifiers = books.map(b => {
            const d = formatBookData(b);
            return d.isbn || d.id;
          }).filter(Boolean);
          await loadShortlistState(identifiers);
          grid.innerHTML = books.map(renderBookCard).join('');
          resultsCount.textContent = CATALOG_I18N.foundMany.replace('{count}', meta.total || books.length);
          totalPages = meta.totalPages || 1;
          renderPagination();
        }
        renderActiveFilters();
      } catch (err) {
        grid.innerHTML = `<div style="grid-column:1/-1; text-align:center; padding:40px 24px; border:1px solid rgba(186,26,26,.16); border-radius:8px; background:rgba(186,26,26,.05);"><span style="display:inline-flex;align-items:center;justify-content:center;min-width:96px;height:34px;padding:0 12px;border-radius:999px;background:rgba(186,26,26,.08);color:#ba1a1a;font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;">${CATALOG_I18N.retry}</span><p style="margin:12px 0 0; font-weight:700; color:#ba1a1a;">${CATALOG_I18N.loadError}</p><p style="margin:4px 0 0; color:#7a2323;">${CATALOG_I18N.refreshPage}</p></div>`;
        console.error(err);
      }
    }

    function renderActiveFilters() {
      const container = document.getElementById('active-filters');
      if (!container) return;
      const chips = [];
      const searchVal = document.getElementById('search-input')?.value?.trim();
      if (searchVal) chips.push({label: `«${searchVal}»`, clear: () => { document.getElementById('search-input').value = ''; }});
      const lang = getActiveLanguage();
      if (lang) { const labels = {ru:'Русский',kk:'Қазақша',en:'English'}; chips.push({label: labels[lang]||lang, clear: () => { document.querySelectorAll('#language-chips .chip').forEach(c => c.classList.toggle('active', c.dataset.lang === '')); }}); }
      const yp = getYearParams();
      if (yp.year_from || yp.year_to) { const activeY = document.querySelector('#year-chips .chip.active'); chips.push({label: activeY?.textContent || CATALOG_I18N.yearLabel, clear: () => { document.querySelectorAll('#year-chips .chip').forEach(c => c.classList.toggle('active', c.dataset.year === '')); }}); }
      const avail = document.getElementById('filter-available-only');
      if (avail?.checked) chips.push({label: CATALOG_I18N.availableOnly, clear: () => { avail.checked = false; }});
      if (activeSubjectLabel) chips.push({label: activeSubjectLabel, clear: () => { activeSubjectId = ''; activeSubjectLabel = ''; }});
      if (chips.length === 0) { container.style.display = 'none'; return; }
      container.style.display = 'flex';
      container.innerHTML = chips.map(c => `<button type="button" class="active-filter-chip" onclick="this._clear()">${escapeHtml(c.label)} ✕</button>`).join('') +
        `<button type="button" class="active-filter-reset" onclick="clearAllFilters()">${CATALOG_I18N.resetAll}</button>`;
      container.querySelectorAll('.active-filter-chip').forEach((el, i) => { el._clear = () => { chips[i].clear(); loadCatalog(); }; });
    }

    function clearAllFilters() {
      document.getElementById('search-input').value = '';
      document.querySelectorAll('#language-chips .chip').forEach(c => c.classList.toggle('active', c.dataset.lang === ''));
      document.querySelectorAll('#year-chips .chip').forEach(c => c.classList.toggle('active', c.dataset.year === ''));
      const avail = document.getElementById('filter-available-only');
      if (avail) avail.checked = false;
      activeSubjectId = ''; activeSubjectLabel = '';
      loadCatalog();
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
      window.location.href = withLang(`/book/${encodeURIComponent(identifier)}`);
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
            btn.title = CATALOG_I18N.addShortlist;
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
            btn.title = CATALOG_I18N.removeShortlist;
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
      updateFilterBadge();
    }

    function clearAllFilters() {
      document.getElementById('filter-available-only').checked = false;
      document.querySelectorAll('#language-chips .chip').forEach((c, i) => {
        c.classList.toggle('active', i === 0);
      });
      document.querySelectorAll('#year-chips .chip').forEach((c, i) => {
        c.classList.toggle('active', i === 0);
      });
      document.getElementById('subject-select').value = '';
      activeSubjectId = '';
      activeSubjectLabel = '';
      applyFilters();
    }

    function countActiveFilters() {
      let count = 0;
      if (document.getElementById('filter-available-only')?.checked) count++;
      if (getActiveLanguage()) count++;
      if (getActiveYear()) count++;
      if (document.getElementById('subject-select')?.value) count++;
      return count;
    }

    function updateFilterBadge() {
      const count = countActiveFilters();
      const badge = document.getElementById('filter-count-badge');
      const clearBtn = document.getElementById('clear-filters-btn');
      const mobileCount = document.getElementById('mobile-filter-count');
      if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-flex' : 'none';
      }
      if (clearBtn) {
        clearBtn.style.display = count > 0 ? 'block' : 'none';
      }
      if (mobileCount) {
        mobileCount.textContent = count > 0 ? `(${count})` : '';
      }
    }

    function toggleFilters() {
      const body = document.getElementById('filters-body');
      body?.classList.toggle('open');
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
      window.location.href = withLang('/login');
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
          <span>${CATALOG_I18N.subjectFilter}: <strong>${escapeHtml(activeSubjectLabel)}</strong></span>
          <button class="clear-btn" onclick="clearSubjectFilter()">✕ ${CATALOG_I18N.clear}</button>
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

        addGroup(CATALOG_I18N.faculties, subjectsData.faculties);
        addGroup(CATALOG_I18N.departments, subjectsData.departments);
        addGroup(CATALOG_I18N.specializations, subjectsData.specializations);

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
    updateFilterBadge();
  </script>
</body>
</html>
