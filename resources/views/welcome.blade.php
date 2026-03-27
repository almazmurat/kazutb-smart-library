<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Библиотека университета - светлый лендинг</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #f6f8fc;
      --bg-soft: #eef4ff;
      --surface: rgba(255,255,255,.88);
      --surface-2: #ffffff;
      --border: rgba(20, 40, 90, .08);
      --text: #14213d;
      --muted: #64748b;
      --blue: #3b82f6;
      --cyan: #06b6d4;
      --violet: #7c3aed;
      --pink: #ec4899;
      --yellow: #f59e0b;
      --green: #22c55e;
      --shadow: 0 18px 50px rgba(21, 34, 66, .08);
      --shadow-soft: 0 12px 30px rgba(21, 34, 66, .05);
      --radius-xl: 32px;
      --radius-lg: 24px;
      --radius-md: 18px;
      --container: 1650px;
    }

    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      margin: 0;
      font-family: 'Inter', system-ui, sans-serif;
      color: var(--text);
      background:
        radial-gradient(circle at 10% 10%, rgba(59,130,246,.09), transparent 20%),
        radial-gradient(circle at 90% 10%, rgba(236,72,153,.07), transparent 18%),
        radial-gradient(circle at 80% 80%, rgba(6,182,212,.06), transparent 20%),
        linear-gradient(180deg, #ffffff 0%, #f7f9fd 42%, #f3f7fc 100%);
      overflow-x: hidden;
    }

    body::before,
    body::after {
      content: "";
      position: fixed;
      width: 320px;
      height: 320px;
      border-radius: 50%;
      filter: blur(80px);
      z-index: -1;
      opacity: .7;
      pointer-events: none;
    }

    body::before {
      left: -80px;
      top: 120px;
      background: rgba(59,130,246,.12);
    }

    body::after {
      right: -80px;
      bottom: 60px;
      background: rgba(6,182,212,.10);
    }

    a { color: inherit; text-decoration: none; }
    img { display: block; max-width: 100%; }

    .container {
      width: min(100% - 32px, var(--container));
      margin: 0 auto;
    }

    .glass {
      background: linear-gradient(180deg, rgba(255,255,255,.95), rgba(255,255,255,.88));
      border: 1px solid var(--border);
      backdrop-filter: blur(16px);
      box-shadow: var(--shadow);
    }

    .topbar {
      position: sticky;
      top: 0;
      z-index: 50;
      background: rgba(255,255,255,.78);
      backdrop-filter: blur(18px);
      border-bottom: 1px solid rgba(15, 23, 42, .06);
    }

    .nav {
      min-height: 86px;
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
    }

    .brand small {
      display: block;
      margin-top: 3px;
      color: var(--muted);
      font-weight: 500;
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 24px;
      color: #334155;
      font-weight: 600;
    }

    .nav-links a:hover { color: var(--blue); }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }

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

    .btn-primary {
      color: white;
      background: linear-gradient(135deg, var(--blue), var(--cyan));
      box-shadow: 0 16px 30px rgba(59,130,246,.22);
    }

    .btn-secondary {
      color: white;
      background: linear-gradient(135deg, var(--pink), var(--violet));
      box-shadow: 0 16px 30px rgba(124,58,237,.18);
    }

    .btn-ghost {
      background: #fff;
      border: 1px solid rgba(15, 23, 42, .08);
      color: var(--text);
      box-shadow: var(--shadow-soft);
    }

    .mobile-toggle {
      display: none;
      width: 46px;
      height: 46px;
      border-radius: 14px;
      border: 1px solid rgba(15,23,42,.08);
      background: #fff;
      color: var(--text);
      font-size: 20px;
      cursor: pointer;
      box-shadow: var(--shadow-soft);
    }

    .hero {
      padding: 56px 0 30px;
      position: relative;
    }

    .hero-grid {
      display: grid;
      grid-template-columns: 1.1fr .9fr;
      gap: 24px;
      align-items: stretch;
    }

    .hero-main {
      border-radius: var(--radius-xl);
      padding: 38px;
      min-height: 610px;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      background:
        radial-gradient(circle at 85% 18%, rgba(236,72,153,.10), transparent 18%),
        radial-gradient(circle at 20% 30%, rgba(6,182,212,.08), transparent 20%),
        linear-gradient(180deg, rgba(255,255,255,.98), rgba(255,255,255,.92));
    }

    .hero-main::before {
      content: "";
      position: absolute;
      right: -80px;
      top: -80px;
      width: 280px;
      height: 280px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(59,130,246,.18), transparent 70%);
    }

    .eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 14px;
      border-radius: 999px;
      width: fit-content;
      background: rgba(59,130,246,.08);
      border: 1px solid rgba(59,130,246,.10);
      color: var(--blue);
      font-size: 14px;
      font-weight: 700;
    }

    .hero h1 {
      margin: 18px 0 14px;
      font-size: clamp(38px, 6vw, 72px);
      line-height: .98;
      letter-spacing: -2.6px;
      max-width: 760px;
    }

    .hero p {
      margin: 0;
      max-width: 720px;
      color: var(--muted);
      font-size: 18px;
      line-height: 1.8;
    }

    .hero-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 14px;
      margin-top: 30px;
    }

    .hero-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 20px;
    }

    .hero-tags span {
      padding: 10px 14px;
      border-radius: 999px;
      background: #fff;
      border: 1px solid rgba(15,23,42,.06);
      color: #334155;
      font-weight: 600;
      box-shadow: var(--shadow-soft);
    }

    .hero-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
      margin-top: 30px;
    }

    .stat {
      border-radius: 22px;
      padding: 20px;
      background: rgba(255,255,255,.92);
      border: 1px solid rgba(15,23,42,.06);
      box-shadow: var(--shadow-soft);
    }

    .stat strong {
      display: block;
      font-size: 30px;
      margin-bottom: 6px;
      color: var(--text);
    }

    .stat span {
      color: var(--muted);
      font-size: 14px;
      line-height: 1.5;
    }

    .hero-side {
      display: flex;
      flex-direction: column;
    }

    .showcase-card {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      border-radius: var(--radius-xl);
      padding: 24px;
      position: relative;
      background:
        radial-gradient(circle at 90% 20%, rgba(245,158,11,.12), transparent 18%),
        linear-gradient(180deg, rgba(255,255,255,.98), rgba(255,255,255,.92));
    }

    .showcase-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
      margin-top: 18px;
      flex: 1;
      overflow-y: auto;
      min-height: 0;
    }

    .book-card {
      padding: 16px;
      border-radius: 22px;
      background: #fff;
      border: 1px solid rgba(15,23,42,.06);
      box-shadow: var(--shadow-soft);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .book-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }

    .book-preview {
      height: 275px;
      border-radius: 16px;
      padding: 14px;
      display: flex;
      align-items: flex-end;
      position: relative;
      overflow: hidden;
      margin-bottom: 12px;
      background: linear-gradient(180deg, #2d4268 0%, #223758 100%);
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
      left: 14px;
      top: 14px;
      color: rgba(255,255,255,.58);
      font-size: 10px;
      letter-spacing: .16em;
      text-transform: uppercase;
      font-weight: 700;
    }

    .book-preview h3 {
      position: relative;
      z-index: 1;
      margin: 0;
      color: #f1d08e;
      font-size: 16px;
      line-height: 1.05;
      letter-spacing: -.4px;
      font-weight: 700;
    }

    .showcase-grid .book-card:nth-child(2) .book-preview,
    .showcase-grid .book-card:nth-child(5) .book-preview {
      background: linear-gradient(180deg, #8f1f1f 0%, #6d1111 100%);
    }

    .showcase-grid .book-card:nth-child(3) .book-preview,
    .showcase-grid .book-card:nth-child(6) .book-preview {
      background: linear-gradient(180deg, #205f43 0%, #134935 100%);
    }

    .book-title {
      font-size: 14px;
      font-weight: 700;
      margin: 0 0 5px;
      line-height: 1.3;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .book-meta {
      color: var(--muted);
      font-size: 12px;
      line-height: 1.4;
    }

    section { padding: 34px 0; }

    .section-head {
      display: flex;
      align-items: end;
      justify-content: space-between;
      gap: 18px;
      margin-bottom: 24px;
    }

    .section-head h2 {
      margin: 0;
      font-size: clamp(28px, 4vw, 48px);
      letter-spacing: -1.2px;
    }

    .section-head p {
      margin: 10px 0 0;
      color: var(--muted);
      line-height: 1.8;
      max-width: 760px;
    }

    .badge-row {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }

    .badge {
      padding: 10px 14px;
      border-radius: 999px;
      background: #fff;
      border: 1px solid rgba(15,23,42,.06);
      color: #334155;
      font-weight: 700;
      box-shadow: var(--shadow-soft);
    }

    .highlight-strip {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 18px;
    }

    .highlight {
      border-radius: 26px;
      padding: 24px;
      position: relative;
      overflow: hidden;
      background: #fff;
      border: 1px solid rgba(15,23,42,.06);
      box-shadow: var(--shadow);
    }

    .highlight:nth-child(1) { box-shadow: 0 18px 40px rgba(59,130,246,.10); }
    .highlight:nth-child(2) { box-shadow: 0 18px 40px rgba(236,72,153,.09); }
    .highlight:nth-child(3) { box-shadow: 0 18px 40px rgba(245,158,11,.09); }
    .highlight:nth-child(4) { box-shadow: 0 18px 40px rgba(34,197,94,.08); }

    .highlight .icon {
      width: 58px;
      height: 58px;
      border-radius: 18px;
      display: grid;
      place-items: center;
      font-size: 24px;
      margin-bottom: 16px;
      color: #fff;
      background: linear-gradient(135deg, var(--blue), var(--cyan));
    }

    .highlight:nth-child(2) .icon { background: linear-gradient(135deg, var(--pink), var(--violet)); }
    .highlight:nth-child(3) .icon { background: linear-gradient(135deg, var(--yellow), #fb7185); }
    .highlight:nth-child(4) .icon { background: linear-gradient(135deg, var(--green), var(--cyan)); }

    .highlight h3 {
      margin: 0 0 10px;
      font-size: 20px;
    }

    .highlight p {
      margin: 0;
      color: var(--muted);
      line-height: 1.7;
      font-size: 15px;
    }

    .catalog {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
    }

    .catalog-card {
      border-radius: 28px;
      overflow: hidden;
      background: #fff;
      border: 1px solid rgba(15,23,42,.06);
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
    }

    .catalog-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .catalog-media {
      height: 240px;
      position: relative;
      background: linear-gradient(135deg, rgba(59,130,246,.22), rgba(6,182,212,.10)), url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1200&q=80') center/cover;
    }

    .catalog-card:nth-child(2) .catalog-media {
      background: linear-gradient(135deg, rgba(236,72,153,.22), rgba(124,58,237,.10)), url('https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=1200&q=80') center/cover;
    }

    .catalog-card:nth-child(3) .catalog-media {
      background: linear-gradient(135deg, rgba(245,158,11,.20), rgba(34,197,94,.10)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1200&q=80') center/cover;
    }

    .catalog-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, transparent 40%, rgba(20,33,61,.16) 100%);
    }

    .catalog-body {
      padding: 24px;
    }

    .catalog-body h3 {
      margin: 0 0 10px;
      font-size: 24px;
    }

    .catalog-body p {
      margin: 0 0 16px;
      color: var(--muted);
      line-height: 1.75;
    }

    .tags {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .tags span {
      padding: 8px 12px;
      border-radius: 999px;
      background: #f8fbff;
      color: #334155;
      font-size: 13px;
      border: 1px solid rgba(15,23,42,.06);
    }

    .catalog-status {
      margin-top: 12px;
      color: var(--muted);
      font-size: 14px;
      line-height: 1.5;
    }

    .services-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
    }

    .service-big,
    .service-list {
      border-radius: 30px;
      padding: 26px;
      background: #fff;
      border: 1px solid rgba(15,23,42,.06);
      box-shadow: var(--shadow);
    }

    .service-big {
      background:
        radial-gradient(circle at 85% 20%, rgba(6,182,212,.08), transparent 18%),
        linear-gradient(135deg, rgba(59,130,246,.04), rgba(124,58,237,.04)),
        #fff;
    }

    .service-big h3 {
      margin: 14px 0 10px;
      font-size: 30px;
      letter-spacing: -1px;
    }

    .service-big p {
      color: var(--muted);
      line-height: 1.8;
      margin: 0 0 20px;
    }

    .mini-stats {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }

    .mini-stats div {
      border-radius: 18px;
      padding: 16px;
      background: #f8fbff;
      border: 1px solid rgba(15,23,42,.06);
    }

    .mini-stats strong {
      display: block;
      font-size: 24px;
      margin-bottom: 4px;
    }

    .mini-stats span {
      color: var(--muted);
      font-size: 13px;
      line-height: 1.5;
    }

    .service-list {
      display: grid;
      gap: 14px;
    }

    .service-item {
      display: flex;
      gap: 16px;
      padding: 18px;
      border-radius: 22px;
      background: #fff;
      border: 1px solid rgba(15,23,42,.06);
      box-shadow: var(--shadow-soft);
    }

    .service-item .icon {
      width: 54px;
      height: 54px;
      border-radius: 18px;
      display: grid;
      place-items: center;
      background: linear-gradient(135deg, var(--blue), var(--cyan));
      font-size: 22px;
      flex: 0 0 auto;
      color: #fff;
    }

    .service-item h4 {
      margin: 0 0 6px;
      font-size: 18px;
    }

    .service-item p {
      margin: 0;
      color: var(--muted);
      line-height: 1.7;
      font-size: 14px;
    }

    .events {
      display: grid;
      grid-template-columns: 1.05fr .95fr;
      gap: 18px;
    }

    .timeline,
    .news-box {
      border-radius: 30px;
      padding: 26px;
      background: #fff;
      border: 1px solid rgba(15,23,42,.06);
      box-shadow: var(--shadow);
    }

    .event-item {
      display: grid;
      grid-template-columns: 86px 1fr;
      gap: 16px;
      padding: 16px 0;
      border-bottom: 1px solid rgba(15,23,42,.06);
    }

    .event-item:last-child { border-bottom: 0; }

    .date-box {
      border-radius: 20px;
      display: grid;
      place-items: center;
      padding: 10px;
      background: linear-gradient(135deg, rgba(59,130,246,.14), rgba(236,72,153,.10));
      border: 1px solid rgba(59,130,246,.10);
      text-align: center;
    }

    .date-box strong {
      display: block;
      font-size: 30px;
      line-height: 1;
      margin-bottom: 4px;
      color: var(--text);
    }

    .date-box span {
      color: #475569;
      font-size: 13px;
      text-transform: uppercase;
    }

    .event-item h4 {
      margin: 0 0 8px;
      font-size: 19px;
    }

    .event-item p,
    .news-box p {
      margin: 0;
      color: var(--muted);
      line-height: 1.75;
    }

    .news-list {
      display: grid;
      gap: 14px;
      margin-top: 18px;
    }

    .news-item {
      padding: 18px;
      border-radius: 20px;
      background: #f8fbff;
      border: 1px solid rgba(15,23,42,.06);
    }

    .news-item strong {
      display: block;
      margin-bottom: 8px;
    }

    .cta {
      padding-bottom: 70px;
    }

    .cta-box {
      border-radius: 34px;
      padding: 36px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
      background:
        radial-gradient(circle at 20% 20%, rgba(236,72,153,.07), transparent 22%),
        radial-gradient(circle at 80% 30%, rgba(6,182,212,.08), transparent 20%),
        linear-gradient(135deg, rgba(59,130,246,.06), rgba(124,58,237,.05)),
        #fff;
      border: 1px solid rgba(15,23,42,.06);
      box-shadow: var(--shadow);
    }

    .cta-box h2 {
      margin: 0 0 10px;
      font-size: clamp(30px, 4vw, 50px);
      letter-spacing: -1.5px;
    }

    .cta-box p {
      margin: 0;
      color: var(--muted);
      line-height: 1.8;
      max-width: 760px;
    }

    footer {
      border-top: 1px solid rgba(15,23,42,.06);
      background: rgba(255,255,255,.72);
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 1.2fr .8fr .8fr .9fr;
      gap: 20px;
      padding: 28px 0;
    }

    .footer-title {
      margin-bottom: 12px;
      font-size: 18px;
      font-weight: 800;
    }

    .footer-col p,
    .footer-col a {
      display: block;
      color: var(--muted);
      line-height: 1.9;
    }

    .footer-bottom {
      padding: 18px 0 28px;
      border-top: 1px solid rgba(15,23,42,.06);
    }

    .footer-bottom p {
      margin: 0;
      color: var(--muted);
      line-height: 1.8;
    }

    @media (max-width: 1120px) {
      .hero-grid,
      .services-grid,
      .events,
      .footer-grid,
      .highlight-strip,
      .catalog {
        grid-template-columns: 1fr 1fr;
      }

      .hero-grid { grid-template-columns: 1fr; }
      .footer-grid { grid-template-columns: 1fr 1fr; }
      .highlight-strip { grid-template-columns: repeat(2, 1fr); }
      .catalog { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 860px) {
      .nav-links,
      .nav-actions .btn-ghost {
        display: none;
      }

      .mobile-toggle {
        display: inline-grid;
        place-items: center;
      }

      .hero-main,
      .showcase-card,
      .search-card,
      .service-big,
      .service-list,
      .timeline,
      .news-box,
      .cta-box {
        padding: 22px;
      }

      .hero-stats,
      .highlight-strip,
      .catalog,
      .services-grid,
      .events,
      .footer-grid,
      .showcase-grid,
      .mini-stats {
        grid-template-columns: 1fr;
      }

      .cta-box,
      .search-row {
        flex-direction: column;
        align-items: stretch;
      }
    }

    @media (max-width: 560px) {
      .container { width: min(100% - 20px, var(--container)); }
      .nav { min-height: 76px; }
      .brand-badge { width: 44px; height: 44px; border-radius: 15px; }
      .hero { padding-top: 28px; }
      .hero-main { min-height: auto; }
      .hero h1 { letter-spacing: -1.4px; }
      .section-head { flex-direction: column; align-items: start; }
      .btn { width: 100%; }
    }
  </style>
</head>
<body>
  <header class="topbar">
    <div class="container nav">
      <a href="#" class="brand">
        <div class="brand-badge">
          <img src="/logo.png" alt="Logo" style="width:50px; height:50px; object-fit:contain;">
        </div>
        <div>
          <div id="brand-title">
          КАЗАХСКИЙ УНИВЕРСИТЕТ ТЕХНОЛОГИИ и БИЗНЕСА
          </div>
          <small id="brand-subtitle">Современная библиотека университета</small>
        </div>
      </a>

      <nav class="nav-links">
        <a href="/catalog">Каталог</a>
        <a href="#services">Сервисы</a>
        <a href="#events">События</a>
        <a href="#contacts">Контакты</a>
      </nav>

      <div class="nav-actions">
        <button id="header-login-btn" class="btn btn-ghost">Войти</button>
        <a href="/account" id="header-catalog-btn" class="btn btn-primary">Личный кабинет</a>
      </div>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="container hero-grid">
        <div class="hero-main glass">
          <div>
            <h1 id="hero-title">Библиотека нового поколения для студентов, преподавателей и исследователей</h1>
            <p id="hero-description">
              Пространство, где классический библиотечный фонд объединяется с электронным каталогом, научными базами, онлайн-бронированием и быстрым поиском знаний в одном чистом и ярком интерфейсе.
            </p>

            <div class="hero-actions">
              <button id="hero-primary-btn" class="btn btn-primary">Найти книгу</button>
              <button id="hero-secondary-btn" class="btn btn-secondary">Посмотреть новинки</button>
            </div>

            <div id="hero-tags" class="hero-tags">
              <span>Учебная литература</span>
              <span>Научные публикации</span>
              <span>E-books</span>
              <span>Онлайн-доступ 24/7</span>
            </div>
          </div>

          <div id="hero-stats" class="hero-stats"></div>
        </div>

        <div class="hero-side">
          <div class="showcase-card glass">
            <div id="showcase-title" class="eyebrow">Популярные книги</div>
            <div id="showcase-grid" class="showcase-grid">
              <article class="book-card"><div class="book-preview"><small>...</small><h3>...</h3></div><div class="book-title">Загрузка...</div><div class="book-meta"></div></article>
              <article class="book-card"><div class="book-preview"><small>...</small><h3>...</h3></div><div class="book-title">Загрузка...</div><div class="book-meta"></div></article>
              <article class="book-card"><div class="book-preview"><small>...</small><h3>...</h3></div><div class="book-title">Загрузка...</div><div class="book-meta"></div></article>
              <article class="book-card"><div class="book-preview"><small>...</small><h3>...</h3></div><div class="book-title">Загрузка...</div><div class="book-meta"></div></article>
              <article class="book-card"><div class="book-preview"><small>...</small><h3>...</h3></div><div class="book-title">Загрузка...</div><div class="book-meta"></div></article>
              <article class="book-card"><div class="book-preview"><small>...</small><h3>...</h3></div><div class="book-title">Загрузка...</div><div class="book-meta"></div></article>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="catalog">
      <div class="container">
        <div class="section-head">
          <div>
            <h2 id="catalog-title">Каталог знаний</h2>
            <p id="catalog-description">Фонд библиотеки разделен на удобные направления, чтобы пользователь сразу понимал, где искать нужные материалы.</p>
          </div>
        </div>

        <div class="catalog" id="catalog-grid">
        </div>
      </div>
    </section>

    <section id="services">
      <div class="container services-grid">
        <div class="service-big">
          <div id="services-eyebrow" class="eyebrow">Сервисы библиотеки</div>
          <h3 id="services-title">Удобный цифровой опыт для каждого пользователя</h3>
          <p id="services-description">
            Библиотека становится полноценным онлайн-сервисом: поиск, бронирование, доступ к научным базам, сопровождение исследований и помощь в работе с академическими материалами.
          </p>

          <div id="services-mini-stats" class="mini-stats">
            <div>
              <strong>5 мин</strong>
              <span>в среднем до нахождения нужного материала</span>
            </div>
            <div>
              <strong>1 клик</strong>
              <span>до перехода к электронному ресурсу</span>
            </div>
            <div>
              <strong>100%</strong>
              <span>адаптация под мобильные устройства</span>
            </div>
            <div>
              <strong>24/7</strong>
              <span>доступ к каталогу и цифровым разделам</span>
            </div>
          </div>
        </div>

        <div id="services-list" class="service-list">
          <div class="service-item">
            <div class="icon">🔎</div>
            <div>
              <h4>Умный поиск по фонду</h4>
              <p>Поиск по названию, автору, теме, дисциплине и ключевым словам с понятной фильтрацией.</p>
            </div>
          </div>

          <div class="service-item">
            <div class="icon">📦</div>
            <div>
              <h4>Онлайн-бронирование книг</h4>
              <p>Пользователь может заранее забронировать нужную литературу и получить уведомление.</p>
            </div>
          </div>

          <div class="service-item">
            <div class="icon">🧠</div>
            <div>
              <h4>Поддержка исследований</h4>
              <p>Консультации по поиску источников, оформлению списка литературы и научным базам.</p>
            </div>
          </div>

          <div class="service-item">
            <div class="icon">💻</div>
            <div>
              <h4>Удаленный доступ</h4>
              <p>Электронная библиотека и цифровые подписки доступны не только в кампусе, но и онлайн.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="events">
      <div class="container events">
        <div class="timeline">
          <div class="section-head" style="margin-bottom: 8px;">
            <div>
              <h2 id="events-title" style="font-size: 34px;">События библиотеки</h2>
              <p id="events-description">Лекции, презентации новых книг, выставки и семинары для развития академической среды.</p>
            </div>
          </div>

          <div id="timeline-list">
          <div class="event-item">
            <div class="date-box"><strong>12</strong><span>апр</span></div>
            <div>
              <h4>Презентация новых поступлений</h4>
              <p>Обзор новых учебных и научных изданий по актуальным направлениям подготовки.</p>
            </div>
          </div>

          <div class="event-item">
            <div class="date-box"><strong>18</strong><span>апр</span></div>
            <div>
              <h4>Тренинг по поиску научных статей</h4>
              <p>Практика по работе с международными базами данных и цифровыми платформами.</p>
            </div>
          </div>

          <div class="event-item">
            <div class="date-box"><strong>24</strong><span>апр</span></div>
            <div>
              <h4>Лекция по цифровой грамотности</h4>
              <p>Как быстро находить, хранить и использовать академические источники в учебе и исследованиях.</p>
            </div>
          </div>
          </div>
        </div>

        <div class="news-box">
          <div id="news-eyebrow" class="eyebrow">Объявления</div>
          <h3 id="news-title" style="margin:16px 0 10px; font-size:32px;">Актуальная информация</h3>
          <p id="news-description">Все важные обновления: график работы, новые ресурсы, подписки и изменения в доступе к сервисам.</p>

          <div id="news-list" class="news-list">
            <div class="news-item">
              <strong>Открыт доступ к новым электронным базам</strong>
              <span style="color:var(--muted);">Пользователи могут работать с дополнительными цифровыми ресурсами удаленно.</span>
            </div>
            <div class="news-item">
              <strong>Поступили новые книги по IT, бизнесу и дизайну</strong>
              <span style="color:var(--muted);">Обновлен фонд по востребованным направлениям обучения.</span>
            </div>
            <div class="news-item">
              <strong>Изменен порядок выдачи литературы</strong>
              <span style="color:var(--muted);">Актуальный график и правила опубликованы в личном кабинете.</span>
            </div>
          </div>

          <div style="margin-top: 18px;">
            <button id="news-button" class="btn btn-secondary" style="width:100%;">Смотреть все объявления</button>
          </div>
        </div>
      </div>
    </section>

  </main>

  <footer id="contacts">
    <div class="container footer-grid">
      <div class="footer-col">
        <div id="footer-brand-title" class="footer-title">Library Hub</div>
        <p id="footer-brand-description">Светлый современный лендинг университетской библиотеки с чистым визуалом, цифровыми сервисами и удобной навигацией.</p>
      </div>

      <div class="footer-col">
        <div id="footer-sections-title" class="footer-title">Разделы</div>
        <div id="footer-sections-links">
          <a href="/catalog">Каталог</a>
          <a href="#advantages">Преимущества</a>
          <a href="#services">Сервисы</a>
          <a href="#events">События</a>
        </div>
      </div>

      <div class="footer-col">
        <div id="footer-contacts-title" class="footer-title">Контакты</div>
        <div id="footer-contacts-links">
          <a href="#">г. Астана, ул. Примерная, 15</a>
          <a href="tel:+77000000000">+7 (700) 000 00 00</a>
          <a href="mailto:library@university.kz">library@university.kz</a>
        </div>
      </div>

      <div class="footer-col">
        <div id="footer-hours-title" class="footer-title">Режим работы</div>
        <p id="footer-address">г. Астана, ул. Примерная, 15</p>
        <div id="footer-hours-list">
          <p>Пн-Пт: 09:00 - 18:00</p>
          <p>Сб: 10:00 - 14:00</p>
          <p>Вс: выходной</p>
        </div>
      </div>
    </div>

    <div class="container footer-bottom" id="footer-bottom">
      <p>© 2026 Library Hub. Все права защищены.</p>
    </div>
  </footer>

  <script>
    const toggle = document.querySelector('.mobile-toggle');
    const nav = document.querySelector('.nav-links');
    const catalogGrid = document.querySelector('#catalog-grid');
    const showcaseGrid = document.querySelector('#showcase-grid');
    const searchInput = document.querySelector('#catalog-search-input');
    const categorySelect = document.querySelector('#catalog-category-select');
    const searchButton = document.querySelector('#catalog-search-button');
    const catalogStatus = document.querySelector('#catalog-status');

    const CATALOG_API_ENDPOINT = '/api/v1/catalog-external';
    const LANDING_API_ENDPOINT = '/api/v1/landing';

    toggle?.addEventListener('click', () => {
      if (!nav) return;
      const isOpen = getComputedStyle(nav).display !== 'none';
      nav.style.display = isOpen ? 'none' : 'flex';
      nav.style.position = 'absolute';
      nav.style.top = '76px';
      nav.style.left = '10px';
      nav.style.right = '10px';
      nav.style.padding = '16px';
      nav.style.borderRadius = '18px';
      nav.style.flexDirection = 'column';
      nav.style.background = 'rgba(255,255,255,.98)';
      nav.style.border = '1px solid rgba(15,23,42,.08)';
      nav.style.backdropFilter = 'blur(18px)';
      nav.style.boxShadow = '0 20px 40px rgba(15,23,42,.08)';
      nav.style.zIndex = '60';
    });

    function escapeHtml(value) {
      return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');
    }

    function normalizeText(value, fallback = '—') {
      if (value === null || value === undefined) return fallback;

      const normalized = String(value).replace(/\s+/g, ' ').trim();
      return normalized || fallback;
    }

    function setText(id, value) {
      if (!value) return;

      const element = document.getElementById(id);
      if (!element) return;
      element.textContent = value;
    }

    function renderList(containerId, items, itemRenderer) {
      const container = document.getElementById(containerId);
      if (!container) return;
      container.innerHTML = items.map(itemRenderer).join('');
    }

    function applyLandingData(data) {
      if (!data) return;

      setText('brand-title', data?.header?.brand);
      setText('brand-subtitle', data?.header?.subtitle);
      setText('header-login-btn', data?.header?.login_label);

      setText('hero-title', data?.hero?.title);
      setText('hero-description', data?.hero?.description);
      setText('hero-primary-btn', data?.hero?.actions?.primary);
      setText('hero-secondary-btn', data?.hero?.actions?.secondary);
      setText('showcase-title', data?.hero?.showcase_title);
      setText('search-title', data?.hero?.search_title);
      setText('search-description', data?.hero?.search_description);

      if (searchInput && data?.hero?.search_placeholder) {
        searchInput.placeholder = data.hero.search_placeholder;
      }
      setText('catalog-search-button', data?.hero?.search_button);

      if (Array.isArray(data?.hero?.tags)) {
        renderList('hero-tags', data.hero.tags, (tag) => `<span>${escapeHtml(tag)}</span>`);
      }

      if (Array.isArray(data?.hero?.stats)) {
        renderList('hero-stats', data.hero.stats, (item) => `
          <div class="stat">
            <strong>${escapeHtml(item.value)}</strong>
            <span>${escapeHtml(item.label)}</span>
          </div>
        `);
      }

      if (Array.isArray(data?.hero?.showcase) && showcaseGrid) {
        const coverStyles = [
          '',
          'background: linear-gradient(180deg, #8f1f1f 0%, #6d1111 100%); box-shadow: 0 18px 30px rgba(124, 24, 24, .20);',
          'background: linear-gradient(180deg, #205f43 0%, #134935 100%); box-shadow: 0 18px 30px rgba(20, 80, 50, .20);',
        ];
        showcaseGrid.innerHTML = data.hero.showcase.slice(0, 6).map((item, index) => `
          <a href="/book/${encodeURIComponent(item.isbn)}" style="text-decoration: none; color: inherit;">
            <div class="book" style="cursor: pointer;">
              <div class="book-cover" ${coverStyles[index % 3] ? `style="${coverStyles[index % 3]}"` : ''}>
                <div class="book-label">${escapeHtml(item.label)}</div>
                <h4 class="book-name">${escapeHtml(item.name).replaceAll(' ', '<br>')}</h4>
              </div>
              <div class="book-title">${escapeHtml(item.title)}</div>
              <div class="book-meta">${escapeHtml(item.meta)}</div>
            </div>
          </a>
        `).join('');
      }

      setText('advantages-title', data?.advantages?.title);
      setText('advantages-description', data?.advantages?.description);

      if (Array.isArray(data?.advantages?.badges)) {
        renderList('advantages-badges', data.advantages.badges, (badge) => `<span class="badge">${escapeHtml(badge)}</span>`);
      }

      if (Array.isArray(data?.advantages?.items)) {
        renderList('advantages-highlights', data.advantages.items, (item) => `
          <div class="highlight">
            <div class="icon">${escapeHtml(item.icon)}</div>
            <h3>${escapeHtml(item.title)}</h3>
            <p>${escapeHtml(item.description)}</p>
          </div>
        `);
      }

      setText('catalog-title', data?.catalog?.title);
      setText('catalog-description', data?.catalog?.description);

      setText('services-eyebrow', data?.services?.eyebrow);
      setText('services-title', data?.services?.title);
      setText('services-description', data?.services?.description);

      if (Array.isArray(data?.services?.stats)) {
        renderList('services-mini-stats', data.services.stats, (item) => `
          <div>
            <strong>${escapeHtml(item.value)}</strong>
            <span>${escapeHtml(item.label)}</span>
          </div>
        `);
      }

      if (Array.isArray(data?.services?.items)) {
        renderList('services-list', data.services.items, (item) => `
          <div class="service-item">
            <div class="icon">${escapeHtml(item.icon)}</div>
            <div>
              <h4>${escapeHtml(item.title)}</h4>
              <p>${escapeHtml(item.description)}</p>
            </div>
          </div>
        `);
      }

      setText('events-title', data?.events?.title);
      setText('events-description', data?.events?.description);
      setText('news-eyebrow', data?.events?.news_eyebrow);
      setText('news-title', data?.events?.news_title);
      setText('news-description', data?.events?.news_description);
      setText('news-button', data?.events?.news_button);

      if (Array.isArray(data?.events?.timeline)) {
        renderList('timeline-list', data.events.timeline, (item) => `
          <div class="event-item">
            <div class="date-box"><strong>${escapeHtml(item.day)}</strong><span>${escapeHtml(item.month)}</span></div>
            <div>
              <h4>${escapeHtml(item.title)}</h4>
              <p>${escapeHtml(item.description)}</p>
            </div>
          </div>
        `);
      }

      if (Array.isArray(data?.events?.news)) {
        renderList('news-list', data.events.news, (item) => `
          <div class="news-item">
            <strong>${escapeHtml(item.title)}</strong>
            <span style="color:var(--muted);">${escapeHtml(item.description)}</span>
          </div>
        `);
      }

      setText('footer-brand-title', data?.footer?.brand_title);
      setText('footer-brand-description', data?.footer?.brand_description);
      setText('footer-contacts-title', data?.footer?.contacts_title);
      setText('footer-sections-title', data?.footer?.quick_links_title);
      setText('footer-hours-title', data?.footer?.address_title);
      setText('footer-address', data?.footer?.address);

      if (Array.isArray(data?.footer?.hours)) {
        renderList('footer-hours-list', data.footer.hours, (item) => `<p>${escapeHtml(item)}</p>`);
      }

      const contactsContainer = document.getElementById('footer-contacts-links');
      if (contactsContainer && Array.isArray(data?.footer?.contact_items)) {
        contactsContainer.innerHTML = data.footer.contact_items.map((item) => `
          <a href="${escapeHtml(item.href ?? '#')}">${escapeHtml(item.label)}: ${escapeHtml(item.value)}</a>
        `).join('');
      }

      const quickLinksContainer = document.getElementById('footer-sections-links');
      if (quickLinksContainer && Array.isArray(data?.footer?.quick_links)) {
        quickLinksContainer.innerHTML = data.footer.quick_links.map((item) => `
          <a href="${escapeHtml(item.href ?? '#')}">${escapeHtml(item.label)}</a>
        `).join('');
      }

      const footerBottom = document.getElementById('footer-bottom');
      if (footerBottom && Array.isArray(data?.footer?.bottom_lines)) {
        footerBottom.innerHTML = data.footer.bottom_lines
          .map((line) => `<p>${escapeHtml(line)}</p>`)
          .join('');
      }
    }

    function renderCatalog(items) {
      if (!catalogGrid) return;

      if (!items.length) {
        catalogGrid.innerHTML = '<article class="catalog-card"><div class="catalog-body"><h3>Ничего не найдено</h3><p>Попробуйте изменить запрос или категорию, чтобы увидеть результаты поиска по каталогу.</p></div></article>';
        return;
      }

      catalogGrid.innerHTML = items.map((item) => {
        const title = escapeHtml(normalizeText(item?.title?.display || item?.title?.raw, 'Без названия'));
        const author = escapeHtml(normalizeText(item?.primaryAuthor || item?.authors?.[0]?.name, 'Автор не указан'));
        const publisher = escapeHtml(normalizeText(item?.publisher?.name, 'Издатель не указан'));
        const subtitle = escapeHtml(normalizeText(item?.title?.subtitle, 'Без подзаголовка'));
        const isbn = escapeHtml(normalizeText(item?.isbn?.raw));
        const year = escapeHtml(normalizeText(item?.publicationYear));
        const language = escapeHtml(normalizeText(item?.language?.raw || item?.language?.code));
        const available = escapeHtml(normalizeText(item?.copies?.available, '0'));

        return `
          <a href="/book/${encodeURIComponent(isbn)}" style="text-decoration: none; color: inherit; cursor: pointer;">
            <article class="catalog-card">
              <div class="catalog-media"><div class="catalog-overlay"></div></div>
              <div class="catalog-body">
                <h3>${title}</h3>
                <p>${author} · ${publisher}<br>${subtitle}<br>ISBN: ${isbn}</p>
                <div class="tags">
                  <span>${language}</span>
                  <span>${year}</span>
                  <span>Доступно: ${available}</span>
                </div>
              </div>
            </article>
          </a>
        `;
      }).join('');
    }

    function updateCategoryOptions(items) {
      if (!categorySelect) return;

      const categories = [...new Set(items
        .map((item) => normalizeText(item?.language?.raw || item?.language?.code, ''))
        .filter((category) => typeof category === 'string' && category.trim() !== ''))]
        .sort((a, b) => a.localeCompare(b));

      categorySelect.innerHTML = '<option value="all">Все языки</option>';
      categories.forEach((category) => {
        const option = document.createElement('option');
        option.value = category;
        option.textContent = category;
        categorySelect.appendChild(option);
      });
    }

    async function loadCatalog(filters = {}) {
      if (catalogStatus) {
        catalogStatus.textContent = 'Загрузка каталога...';
      }

      try {
        const params = new URLSearchParams();
        if (filters.q) params.set('q', filters.q);
        if (filters.category && filters.category !== 'all') params.set('language', filters.category);
        params.set('page', '1');
        params.set('limit', '6');

        const response = await fetch(`${CATALOG_API_ENDPOINT}?${params.toString()}`, {
          headers: {
            Accept: 'application/json',
          },
        });

        if (!response.ok) {
          throw new Error(`API error: ${response.status}`);
        }

        const payload = await response.json();
        const items = Array.isArray(payload.data) ? payload.data : [];
        renderCatalog(items);

        const total = Number(payload?.meta?.total ?? items.length);
        const currentLimit = Number(payload?.meta?.limit ?? items.length);
        if (catalogStatus) {
          catalogStatus.textContent = `Показано ${items.length || currentLimit} из ${total} книг`;
        }
      } catch (error) {
        renderCatalog([]);
        if (catalogStatus) {
          catalogStatus.textContent = 'Не удалось загрузить каталог. Проверьте доступность API.';
        }
      }
    }

    async function initCatalog() {
      try {
        const response = await fetch(`${CATALOG_API_ENDPOINT}?limit=100`, {
          headers: {
            Accept: 'application/json',
          },
        });

        if (!response.ok) {
          throw new Error(`API error: ${response.status}`);
        }

        const payload = await response.json();
        const items = Array.isArray(payload.data) ? payload.data : [];
        updateCategoryOptions(items);
      } catch (error) {
        if (catalogStatus) {
          catalogStatus.textContent = 'Не удалось загрузить список категорий.';
        }
      }

      await loadCatalog();
    }

    async function loadShowcase() {
      if (!showcaseGrid) return;
      try {
        const response = await fetch(`${CATALOG_API_ENDPOINT}?limit=6`, {
          headers: { Accept: 'application/json' },
        });
        if (!response.ok) return;
        const payload = await response.json();
        const items = Array.isArray(payload.data) ? payload.data : [];
        if (!items.length) return;
        showcaseGrid.innerHTML = items.map((item) => {
          const title = normalizeText(item?.title?.display || item?.title?.raw, '...');
          const author = normalizeText(item?.primaryAuthor, '');
          const publisher = normalizeText(item?.publisher?.name, '...');
          const available = item?.copies?.available || 0;
          const isbn = item?.isbn?.raw || '';
          return `
            <article class="book-card" onclick="location.href='/book/${encodeURIComponent(isbn)}'">
              <div class="book-preview">
                <small>${escapeHtml(publisher.substring(0, 15))}</small>
                <h3>${escapeHtml(title.substring(0, 28))}</h3>
              </div>
              <div class="book-title">${escapeHtml(title)}</div>
              <div class="book-meta">${escapeHtml(author)}${author && available ? ' · ' : ''}${available ? available + ' в наличии' : ''}</div>
            </article>
          `;
        }).join('');
      } catch (e) {
        console.error('Showcase load error', e);
      }
    }

    async function initLanding() {
      try {
        const response = await fetch(LANDING_API_ENDPOINT, {
          headers: {
            Accept: 'application/json',
          },
        });

        if (!response.ok) {
          throw new Error(`API error: ${response.status}`);
        }

        const payload = await response.json();
        applyLandingData(payload);
      } catch (error) {
        console.error('Landing API error', error);
      }
    }

    searchButton?.addEventListener('click', () => {
      loadCatalog({
        q: searchInput?.value?.trim(),
        category: categorySelect?.value,
      });
    });

    searchInput?.addEventListener('keydown', (event) => {
      if (event.key !== 'Enter') return;

      event.preventDefault();
      loadCatalog({
        q: searchInput.value.trim(),
        category: categorySelect?.value,
      });
    });

    categorySelect?.addEventListener('change', () => {
      loadCatalog({
        q: searchInput?.value?.trim(),
        category: categorySelect.value,
      });
    });

    const AUTH_TOKEN_KEY = 'library.auth.token';
    const AUTH_USER_KEY = 'library.auth.user';

    function getAuthToken() {
      return localStorage.getItem(AUTH_TOKEN_KEY) || '';
    }

    function updateLoginButtonState() {
      const loginBtn = document.getElementById('header-login-btn');
      if (!loginBtn) return;
      loginBtn.style.display = getAuthToken() ? 'none' : 'inline-flex';
      loginBtn.textContent = 'Войти';
    }

    document.getElementById('header-login-btn')?.addEventListener('click', async () => {
      const redirectTo = encodeURIComponent(window.location.pathname + window.location.search);
      window.location.href = `/login?redirect=${redirectTo}`;
    });

    document.getElementById('header-catalog-btn')?.addEventListener('click', () => {
      window.location.href = '/catalog';
    });

    document.getElementById('hero-primary-btn')?.addEventListener('click', () => {
      window.location.href = '/catalog';
    });

    updateLoginButtonState();
    initLanding();
    initCatalog();
    loadShowcase();
  </script>
</body>
</html>
