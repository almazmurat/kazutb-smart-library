@php
  $lang = app()->getLocale();
  $accountTitle = [
    'ru' => 'Кабинет читателя — Digital Library',
    'kk' => 'Оқырман кабинеті — Digital Library',
    'en' => 'Reader account — Digital Library',
  ][$lang] ?? 'Кабинет читателя — Digital Library';
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ $accountTitle }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Newsreader:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/shell.css">
  <style>
    :root {
      --bg: #f8f9fa;
      --surface: #ffffff;
      --border: rgba(195, 198, 209, .55);
      --text: #191c1d;
      --muted: #43474f;
      --blue: #001e40;
      --cyan: #14696d;
      --success: #14696d;
      --warning: #5d4201;
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
      background: #f8f9fa;
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

    .nav-links a:hover,
    .nav-links a.active {
      color: var(--blue);
    }

    .nav-actions { display: flex; gap: 12px; }

    .btn {
      border: 0;
      cursor: pointer;
      font: inherit;
      border-radius: 6px;
      padding: 12px 18px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .2s ease, color .2s ease, border-color .2s ease, box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1);
      font-weight: 700;
      font-size: 13px;
    }

    .btn:hover { transform: translate3d(0, -1px, 0); }
    .btn-primary { color: white; background: linear-gradient(135deg, var(--blue), #003366); box-shadow: var(--shadow-soft); }
    .btn-ghost { background: transparent; border: 1px solid var(--border); color: var(--text); box-shadow: none; }

    .page { padding: 34px 0 70px; }

    .profile-grid {
      display: grid;
      grid-template-columns: 1.05fr .95fr;
      gap: 22px;
      margin-bottom: 24px;
    }

    .card {
      border-radius: var(--radius-xl);
      background: rgba(255,255,255,.98);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-soft);
      padding: 28px;
      position: relative;
      overflow: hidden;
      transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .card:hover {
      transform: translate3d(0, -2px, 0);
      box-shadow: 0 16px 34px rgba(25, 28, 29, .05);
      border-color: rgba(0,30,64,.12);
    }

    .profile-head {
      display: flex;
      align-items: center;
      gap: 18px;
      margin-bottom: 20px;
    }

    .avatar {
      width: 72px;
      height: 72px;
      border-radius: 8px;
      display: grid;
      place-items: center;
      font-size: 28px;
      font-weight: 800;
      color: white;
      background: linear-gradient(140deg, #17354d, #2f6966);
      box-shadow: 0 16px 30px rgba(23,53,77,.18);
    }

    .profile-name {
      margin: 0;
      font-size: 32px;
      letter-spacing: -.03em;
      font-family: 'Newsreader', Georgia, serif;
      font-weight: 600;
      color: var(--blue);
    }

    .profile-sub {
      margin: 6px 0 0;
      color: var(--muted);
      font-size: 14px;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }

    .stat {
      border-radius: var(--radius-lg);
      border: 1px solid var(--border);
      background: #fff;
      padding: 16px;
      box-shadow: none;
      transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .stat:hover {
      transform: translate3d(0, -2px, 0);
      box-shadow: 0 12px 26px rgba(25, 28, 29, .04);
      border-color: rgba(20,105,109,.18);
    }

    .stat strong {
      display: block;
      font-size: 28px;
      margin-bottom: 6px;
    }

    .stat span {
      font-size: 13px;
      color: var(--muted);
    }

    .alerts {
      display: grid;
      gap: 12px;
    }

    .alert {
      border-radius: var(--radius-lg);
      padding: 14px 16px;
      border: 1px solid var(--border);
      background: #fff;
      box-shadow: none;
      font-size: 14px;
      line-height: 1.65;
    }

    .alert.warning { border-color: rgba(93,66,1,.18); background: rgba(93,66,1,.06); color: #5d4201; }
    .alert.success { border-color: rgba(20,105,109,.18); background: rgba(20,105,109,.06); color: #14696d; }

    .section-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 14px;
    }

    .section-head h2 {
      margin: 0;
      font-size: 28px;
      letter-spacing: -.03em;
      font-family: 'Newsreader', Georgia, serif;
      font-weight: 600;
      color: var(--blue);
    }

    .section-head p {
      margin: 0;
      color: var(--muted);
      font-size: 14px;
    }

    .showcase {
      border-radius: var(--radius-xl);
      background: rgba(255,255,255,.98);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-soft);
      padding: 24px;
      transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .showcase:hover {
      transform: translate3d(0, -2px, 0);
      box-shadow: 0 16px 34px rgba(25, 28, 29, .05);
      border-color: rgba(0,30,64,.12);
    }

    .book-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 14px;
    }

    .book-card {
      padding: 16px;
      border-radius: var(--radius-lg);
      background: #fff;
      border: 1px solid var(--border);
      box-shadow: none;
      transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), background 0.2s ease, border-color 0.2s ease, box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1);
      cursor: pointer;
      transform-style: preserve-3d;
    }

    .book-card:hover {
      transform: translate3d(0, -3px, 0) rotateX(0.5deg);
      background: rgba(243,244,245,.96);
      border-color: rgba(20,105,109,.22);
      box-shadow: 0 14px 28px rgba(25, 28, 29, .05);
    }

    .book-preview {
      height: 190px;
      border-radius: var(--radius-lg);
      padding: 14px;
      display: flex;
      align-items: flex-end;
      position: relative;
      overflow: hidden;
      margin-bottom: 12px;
      background: linear-gradient(180deg, #003366 0%, #001e40 100%);
    }

    .book-preview::before {
      content: "";
      position: absolute;
      inset: 0 auto 0 0;
      width: 6px;
      background: rgba(255,255,255,.12);
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
      font-size: 17px;
      line-height: 1.06;
      letter-spacing: -.4px;
      font-weight: 700;
    }

    .book-grid .book-card:nth-child(2) .book-preview,
    .book-grid .book-card:nth-child(5) .book-preview {
      background: linear-gradient(180deg, #8f1f1f 0%, #6d1111 100%);
    }

    .book-grid .book-card:nth-child(3) .book-preview,
    .book-grid .book-card:nth-child(6) .book-preview {
      background: linear-gradient(180deg, #205f43 0%, #134935 100%);
    }

    .book-title {
      font-size: 16px;
      font-weight: 600;
      font-family: 'Newsreader', Georgia, serif;
      margin: 0 0 5px;
      line-height: 1.3;
      color: var(--blue);
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

    .loading {
      border-radius: 8px;
      border: 1px dashed var(--border);
      background: #fff;
      padding: 24px;
      text-align: center;
      color: var(--muted);
    }

    @keyframes spin { to { transform: rotate(360deg); } }
    .spinner {
      display: inline-block;
      width: 32px; height: 32px;
      border: 3px solid #e5e7eb;
      border-top-color: var(--blue);
      border-radius: 50%;
      animation: spin .7s linear infinite;
    }

    .toast {
      position: fixed;
      top: 92px;
      right: 24px;
      z-index: 1000;
      min-width: 320px;
      max-width: 440px;
      padding: 14px 16px;
      border-radius: 8px;
      background: #fff;
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
      display: flex;
      align-items: flex-start;
      gap: 12px;
      transform: translateX(120%);
      transition: transform .35s cubic-bezier(.4,0,.2,1);
      font-size: 14px;
      line-height: 1.6;
    }
    .toast.visible { transform: translateX(0); }
    .toast.success { border-color: rgba(20,105,109,.22); background: rgba(20,105,109,.06); }
    .toast.error { border-color: rgba(186,26,26,.22); background: rgba(186,26,26,.06); }
    .toast-icon { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
    .toast-body { flex: 1; }
    .toast-title { font-weight: 700; margin-bottom: 2px; }
    .toast-close {
      background: none; border: none; cursor: pointer;
      font-size: 18px; color: var(--muted); padding: 0; line-height: 1;
    }

    .modal-overlay {
      position: fixed; inset: 0; z-index: 900;
      background: rgba(25,28,29,.18);
      backdrop-filter: blur(8px);
      display: flex; align-items: center; justify-content: center;
      opacity: 0; pointer-events: none;
      transition: opacity .25s ease;
    }
    .modal-overlay.visible { opacity: 1; pointer-events: auto; }
    .modal-box {
      background: #fff;
      border-radius: 8px;
      padding: 28px;
      max-width: 420px;
      width: 90%;
      box-shadow: var(--shadow);
      text-align: center;
      border: 1px solid var(--border);
    }
    .modal-box h3 { margin: 0 0 8px; font-size: 20px; }
    .modal-box p { margin: 0 0 24px; color: var(--muted); font-size: 15px; }
    .modal-actions { display: flex; gap: 12px; justify-content: center; }
    .modal-actions .btn { min-width: 120px; padding: 12px 20px; font-size: 14px; }

    @media (max-width: 1200px) {
      .profile-grid { grid-template-columns: 1fr; }
      .book-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
      .stats { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 900px) {
      .nav-links { display: none; }
      .stats { grid-template-columns: 1fr; }
      .mobile-toggle { display: inline-grid; place-items: center; min-width: 44px; min-height: 44px; }
    }

    @media (max-width: 680px) {
      .container { width: min(100% - 20px, var(--container)); }
      .card,
      .showcase { padding: 20px; }
      .book-grid { grid-template-columns: 1fr; }
      .profile-name { font-size: 24px; }
      .nav-actions .btn { padding: 12px 16px; min-height: 44px; }
      .nav { min-height: 64px; }
    }

    @media (max-width: 480px) {
      .container { width: min(100% - 16px, var(--container)); }
      .card,
      .showcase { padding: 16px; }
      .profile-name { font-size: 20px; }
      .brand-text { font-size: 13px; }
      .brand-text small { font-size: 10.5px; }
    }
  </style>
</head>
<body>
  @include('partials.navbar', ['activePage' => 'account'])

  <main class="page">
    <div class="container">
      <section class="profile-grid">
        <article class="card">
          <div class="profile-head">
            <div id="profile-avatar" class="avatar">{{ strtoupper(substr($sessionUser['name'] ?? 'U', 0, 1)) }}</div>
            <div>
              <h1 id="profile-name" class="profile-name">{{ $sessionUser['name'] ?? ['ru' => 'Гость библиотеки', 'kk' => 'Кітапхана қонағы', 'en' => 'Library guest'][$lang] }}</h1>
              <p id="profile-sub" class="profile-sub">
                @php
                  $profileType = $sessionUser['profile_type'] ?? null;
                  $role = $sessionUser['role'] ?? 'reader';
                  $roleLabel = match(true) {
                    $profileType === 'teacher' => ['ru' => '📚 Преподаватель', 'kk' => '📚 Оқытушы', 'en' => '📚 Faculty'][$lang],
                    $profileType === 'student' => ['ru' => '🎓 Студент', 'kk' => '🎓 Студент', 'en' => '🎓 Student'][$lang],
                    $role === 'librarian' => ['ru' => '📖 Библиотекарь', 'kk' => '📖 Кітапханашы', 'en' => '📖 Librarian'][$lang],
                    $role === 'admin' => ['ru' => '🛡️ Администратор', 'kk' => '🛡️ Әкімші', 'en' => '🛡️ Administrator'][$lang],
                    default => ['ru' => '👤 Читатель', 'kk' => '👤 Оқырман', 'en' => '👤 Reader'][$lang],
                  };
                @endphp
                {{ $sessionUser['ad_login'] ?? ['ru' => 'не указан', 'kk' => 'көрсетілмеген', 'en' => 'not specified'][$lang] }}
                · {{ $roleLabel }}
              </p>
            </div>
          </div>

          <div class="stats">
            <div class="stat">
              <strong id="active-loans-count">—</strong>
              <span>{{ ['ru' => 'Активных выдач', 'kk' => 'Белсенді берілімдер', 'en' => 'Active loans'][$lang] }}</span>
            </div>
            <div class="stat">
              <strong id="overdue-loans-count" style="color:inherit;">—</strong>
              <span>{{ ['ru' => 'Просрочено', 'kk' => 'Мерзімі өткен', 'en' => 'Overdue'][$lang] }}</span>
            </div>
            <div class="stat">
              <strong id="due-soon-loans-count" style="color:inherit;">—</strong>
              <span>{{ ['ru' => 'Скоро сдавать', 'kk' => 'Жақында тапсыру', 'en' => 'Due soon'][$lang] }}</span>
            </div>
            <div class="stat">
              <strong id="returned-loans-count">—</strong>
              <span>{{ ['ru' => 'Возвращено', 'kk' => 'Қайтарылған', 'en' => 'Returned'][$lang] }}</span>
            </div>
          </div>
        </article>

        <article class="card alerts">
          <div id="account-status-alert" class="alert warning">
            {{ ['ru' => 'Профиль читателя синхронизируется с реальными библиотечными данными.', 'kk' => 'Оқырман профилі нақты кітапхана деректерімен синхрондалуда.', 'en' => 'The reader profile is syncing with live library data.'][$lang] }}
          </div>
          <div class="alert success">
            {{ ['ru' => 'Ваш электронный пропуск активен. Онлайн-доступ к ресурсам библиотеки открыт 24/7.', 'kk' => 'Электрондық рұқсатыңыз белсенді. Кітапхана ресурстарына онлайн қолжетімділік 24/7 ашық.', 'en' => 'Your digital pass is active. Online access to library resources is open 24/7.'][$lang] }}
          </div>
          <div class="alert">
            {{ ['ru' => 'Для быстрого поиска используйте раздел «Каталог», а для чтения описаний и наличия переходите в карточку книги.', 'kk' => 'Жылдам іздеу үшін «Каталог» бөлімін пайдаланыңыз, ал сипаттама мен қолжетімділікті көру үшін кітап жазбасын ашыңыз.', 'en' => 'Use the catalog for fast discovery, then open the book record to review details and availability.'][$lang] }}
          </div>
        </article>
      </section>

      <section class="showcase">
        <div class="section-head">
          <div>
            <h2>📚 {{ ['ru' => 'Мои книги', 'kk' => 'Менің кітаптарым', 'en' => 'My books'][$lang] }}</h2>
            <p>{{ ['ru' => 'Текущие выдачи из библиотечного фонда.', 'kk' => 'Кітапхана қорынан алынған ағымдағы берілімдер.', 'en' => 'Current loans from the library collection.'][$lang] }}</p>
          </div>
          <div id="loan-tabs" style="display:flex; gap:8px; flex-wrap:wrap;">
            <button class="btn btn-ghost loan-tab active" data-status="active" onclick="switchLoanTab('active')" style="font-size:13px; padding:8px 16px;">{{ ['ru' => 'Активные', 'kk' => 'Белсенді', 'en' => 'Active'][$lang] }}</button>
            <button class="btn btn-ghost loan-tab" data-status="returned" onclick="switchLoanTab('returned')" style="font-size:13px; padding:8px 16px;">{{ ['ru' => 'Возвращённые', 'kk' => 'Қайтарылған', 'en' => 'Returned'][$lang] }}</button>
            <button class="btn btn-ghost loan-tab" data-status="all" onclick="switchLoanTab('all')" style="font-size:13px; padding:8px 16px;">{{ ['ru' => 'Все', 'kk' => 'Барлығы', 'en' => 'All'][$lang] }}</button>
          </div>
        </div>

        <div id="book-grid" class="book-grid">
          <div class="loading" style="grid-column: 1 / -1;"><div class="spinner"></div><p style="margin:8px 0 0;">{{ ['ru' => 'Загрузка выдач...', 'kk' => 'Берілімдер жүктелуде...', 'en' => 'Loading loans...'][$lang] }}</p></div>
        </div>
      </section>

      <section class="showcase" style="margin-top: 36px;">
        <div class="section-head">
          <div>
            <h2>{{ ['ru' => 'Мои бронирования', 'kk' => 'Менің броньдарым', 'en' => 'My reservations'][$lang] }}</h2>
            <p>{{ ['ru' => 'Статус ваших бронирований в библиотечной системе.', 'kk' => 'Кітапхана жүйесіндегі броньдарыңыздың күйі.', 'en' => 'The status of your reservations in the library system.'][$lang] }}</p>
          </div>
        </div>

        <div id="reservations-grid" class="book-grid">
          <div class="loading" style="grid-column: 1 / -1;"><div class="spinner"></div><p style="margin:8px 0 0;">{{ ['ru' => 'Загрузка бронирований...', 'kk' => 'Броньдар жүктелуде...', 'en' => 'Loading reservations...'][$lang] }}</p></div>
        </div>
      </section>

      @if(($sessionUser['profile_type'] ?? null) === 'teacher')
      <section id="workbench-section" class="showcase" style="margin-top: 36px;">
        <div class="section-head">
          <div>
            <h2>📋 {{ ['ru' => 'Подборка литературы', 'kk' => 'Әдебиеттер топтамасы', 'en' => 'Shortlist'][$lang] }}</h2>
            <p>{{ ['ru' => 'Ваш черновик списка литературы для силлабуса или учебной программы.', 'kk' => 'Силлабус немесе оқу бағдарламасына арналған әдебиеттер тізімінің жұмыс нұсқасы.', 'en' => 'Your working draft of sources for a syllabus or teaching plan.'][$lang] }}</p>
          </div>
          <a href="{{ $lang === 'ru' ? '/shortlist' : '/shortlist?lang=' . $lang }}" class="btn btn-ghost" style="font-size:14px; padding:10px 18px;">{{ ['ru' => 'Открыть подборку →', 'kk' => 'Топтаманы ашу →', 'en' => 'Open shortlist →'][$lang] }}</a>
        </div>

        <div id="workbench-loading" style="text-align:center; padding:24px;">
          <div class="spinner"></div>
          <p style="margin:8px 0 0; color:var(--muted); font-size:13px;">{{ ['ru' => 'Загрузка подборки...', 'kk' => 'Топтама жүктелуде...', 'en' => 'Loading shortlist...'][$lang] }}</p>
        </div>

        <div id="workbench-empty" style="display:none;">
          <div class="loading" style="text-align:center; border-style:dashed;">
            <span style="font-size:32px;">📚</span>
            <p style="margin:8px 0 0; font-weight:600;">{{ ['ru' => 'Подборка пуста', 'kk' => 'Топтама бос', 'en' => 'The shortlist is empty'][$lang] }}</p>
            <p style="margin:6px 0 0; color:var(--muted); font-size:13px;">{{ ['ru' => 'Добавляйте книги из каталога и электронные ресурсы в подборку для подготовки силлабуса.', 'kk' => 'Силлабус дайындау үшін каталогтан кітаптар мен электрондық ресурстарды топтамаға қосыңыз.', 'en' => 'Add catalog titles and electronic resources to build the syllabus list.'][$lang] }}</p>
            <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap; margin-top:14px;">
              <a href="{{ $lang === 'ru' ? '/catalog' : '/catalog?lang=' . $lang }}" style="color:var(--blue); font-weight:600; font-size:14px;">{{ ['ru' => 'Открыть каталог →', 'kk' => 'Каталогты ашу →', 'en' => 'Open catalog →'][$lang] }}</a>
              <a href="{{ $lang === 'ru' ? '/resources' : '/resources?lang=' . $lang }}" style="color:var(--blue); font-weight:600; font-size:14px;">{{ ['ru' => 'Электронные ресурсы →', 'kk' => 'Электрондық ресурстар →', 'en' => 'Resources →'][$lang] }}</a>
              <a href="{{ $lang === 'ru' ? '/discover' : '/discover?lang=' . $lang }}" style="color:var(--blue); font-weight:600; font-size:14px;">{{ ['ru' => 'Поиск по направлениям →', 'kk' => 'Бағыттар бойынша іздеу →', 'en' => 'Browse subjects →'][$lang] }}</a>
            </div>
          </div>
        </div>

        <div id="workbench-content" style="display:none;">
          <div id="workbench-draft-info" style="margin-bottom:18px;"></div>

          <div class="stats" style="grid-template-columns: repeat(3, 1fr); margin-bottom:18px;">
            <div class="stat">
              <strong id="wb-total">0</strong>
              <span>{{ ['ru' => 'Всего источников', 'kk' => 'Барлық дереккөз', 'en' => 'Total sources'][$lang] }}</span>
            </div>
            <div class="stat">
              <strong id="wb-books">0</strong>
              <span>{{ ['ru' => 'Книги из каталога', 'kk' => 'Каталог кітаптары', 'en' => 'Catalog books'][$lang] }}</span>
            </div>
            <div class="stat">
              <strong id="wb-external">0</strong>
              <span>{{ ['ru' => 'Электронные ресурсы', 'kk' => 'Электрондық ресурстар', 'en' => 'E-resources'][$lang] }}</span>
            </div>
          </div>

          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ $lang === 'ru' ? '/shortlist' : '/shortlist?lang=' . $lang }}" class="btn btn-primary" style="font-size:14px; padding:12px 20px;">{{ ['ru' => '📄 Редактировать и экспортировать', 'kk' => '📄 Өңдеу және экспорттау', 'en' => '📄 Edit and export'][$lang] }}</a>
            <a href="{{ $lang === 'ru' ? '/catalog' : '/catalog?lang=' . $lang }}" class="btn btn-ghost" style="font-size:14px; padding:12px 20px;">{{ ['ru' => 'Добавить из каталога', 'kk' => 'Каталогтан қосу', 'en' => 'Add from catalog'][$lang] }}</a>
            <a href="{{ $lang === 'ru' ? '/resources' : '/resources?lang=' . $lang }}" class="btn btn-ghost" style="font-size:14px; padding:12px 20px;">{{ ['ru' => 'Электронные ресурсы', 'kk' => 'Электрондық ресурстар', 'en' => 'Resources'][$lang] }}</a>
            <a href="{{ $lang === 'ru' ? '/discover' : '/discover?lang=' . $lang }}" class="btn btn-ghost" style="font-size:14px; padding:12px 20px;">{{ ['ru' => 'Поиск по направлениям', 'kk' => 'Бағыттар бойынша іздеу', 'en' => 'Browse subjects'][$lang] }}</a>
          </div>
        </div>
      </section>
      @else
      {{-- Student/reader quick actions --}}
      <section class="showcase" style="margin-top: 36px;">
        <div class="section-head">
          <div>
            <h2>📚 {{ ['ru' => 'Быстрые действия', 'kk' => 'Жылдам әрекеттер', 'en' => 'Quick actions'][$lang] }}</h2>
            <p>{{ ['ru' => 'Основные возможности для читателя.', 'kk' => 'Оқырманға арналған негізгі мүмкіндіктер.', 'en' => 'Core actions for the reader portal.'][$lang] }}</p>
          </div>
        </div>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:14px;">
          <a href="{{ $pageLang === 'ru' ? '/catalog' : '/catalog?lang=' . $pageLang }}" class="card" style="text-decoration:none; padding:20px; text-align:center;">
            <span style="font-size:28px; display:block; margin-bottom:8px;">🔎</span>
            <strong style="display:block; margin-bottom:4px;">{{ ['ru' => 'Каталог', 'kk' => 'Каталог', 'en' => 'Catalog'][$lang] }}</strong>
            <span style="color:var(--muted); font-size:13px;">{{ ['ru' => 'Поиск литературы', 'kk' => 'Әдебиеттерді іздеу', 'en' => 'Search the collection'][$lang] }}</span>
          </a>
          <a href="{{ $pageLang === 'ru' ? '/resources' : '/resources?lang=' . $pageLang }}" class="card" style="text-decoration:none; padding:20px; text-align:center;">
            <span style="font-size:28px; display:block; margin-bottom:8px;">🌐</span>
            <strong style="display:block; margin-bottom:4px;">{{ ['ru' => 'Электронные ресурсы', 'kk' => 'Электрондық ресурстар', 'en' => 'Resources'][$lang] }}</strong>
            <span style="color:var(--muted); font-size:13px;">{{ ['ru' => 'Базы данных и e-библиотеки', 'kk' => 'Дерекқорлар мен e-кітапханалар', 'en' => 'Databases and e-libraries'][$lang] }}</span>
          </a>
          <a href="{{ $pageLang === 'ru' ? '/shortlist' : '/shortlist?lang=' . $pageLang }}" class="card" style="text-decoration:none; padding:20px; text-align:center;">
            <span style="font-size:28px; display:block; margin-bottom:8px;">📋</span>
            <strong style="display:block; margin-bottom:4px;">{{ ['ru' => 'Подборка', 'kk' => 'Топтама', 'en' => 'Shortlist'][$lang] }}</strong>
            <span style="color:var(--muted); font-size:13px;">{{ ['ru' => 'Избранные книги', 'kk' => 'Таңдаулы кітаптар', 'en' => 'Saved titles'][$lang] }}</span>
          </a>
          @if(($sessionUser['profile_type'] ?? null) === 'teacher')
          <a href="{{ $pageLang === 'ru' ? '/discover' : '/discover?lang=' . $pageLang }}" class="card" style="text-decoration:none; padding:20px; text-align:center;">
            <span style="font-size:28px; display:block; margin-bottom:8px;">🎓</span>
            <strong style="display:block; margin-bottom:4px;">{{ ['ru' => 'По направлениям', 'kk' => 'Бағыттар бойынша', 'en' => 'Subjects'][$lang] }}</strong>
            <span style="color:var(--muted); font-size:13px;">{{ ['ru' => 'Навигация по темам и дисциплинам', 'kk' => 'Тақырыптар мен пәндер бойынша навигация', 'en' => 'Navigate by themes and disciplines'][$lang] }}</span>
          </a>
          @endif
          <a href="{{ $pageLang === 'ru' ? '/contacts' : '/contacts?lang=' . $pageLang }}" class="card" style="text-decoration:none; padding:20px; text-align:center;">
            <span style="font-size:28px; display:block; margin-bottom:8px;">📞</span>
            <strong style="display:block; margin-bottom:4px;">{{ ['ru' => 'Контакты', 'kk' => 'Байланыс', 'en' => 'Contacts'][$lang] }}</strong>
            <span style="color:var(--muted); font-size:13px;">{{ ['ru' => 'Связь с библиотекой', 'kk' => 'Кітапханамен байланыс', 'en' => 'Reach the library'][$lang] }}</span>
          </a>
        </div>
      </section>
      @endif
    </div>
  </main>

  <div id="toast-container"></div>
  <div id="confirm-modal" class="modal-overlay">
    <div class="modal-box">
      <h3 id="modal-title">{{ ['ru' => 'Подтверждение', 'kk' => 'Растау', 'en' => 'Confirmation'][$lang] }}</h3>
      <p id="modal-message"></p>
      <div class="modal-actions">
        <button id="modal-confirm" class="btn btn-primary">{{ ['ru' => 'Да, продлить', 'kk' => 'Иә, ұзарту', 'en' => 'Yes, renew'][$lang] }}</button>
        <button id="modal-cancel" class="btn btn-ghost">{{ ['ru' => 'Отмена', 'kk' => 'Бас тарту', 'en' => 'Cancel'][$lang] }}</button>
      </div>
    </div>
  </div>

  @include('partials.footer')

  <script>
    const ACCOUNT_LOANS_ENDPOINT = '/api/v1/account/loans';
    const ACCOUNT_LOANS_SUMMARY_ENDPOINT = '/api/v1/account/loans/summary';
    const ACCOUNT_RESERVATIONS_ENDPOINT = '/api/v1/account/reservations';
    const ACCOUNT_SUMMARY_ENDPOINT = '/api/v1/account/summary';
    const ME_ENDPOINT = '/api/v1/me';
    const AUTH_USER_KEY = 'library.auth.user';
    const ACCOUNT_LANG = @json($lang);
    const ACCOUNT_DATE_LOCALE = ACCOUNT_LANG === 'kk' ? 'kk-KZ' : ACCOUNT_LANG === 'en' ? 'en-US' : 'ru-RU';
    const ACCOUNT_I18N_MAP = {!! json_encode([
      'ru' => [
        'guest' => 'Гость библиотеки', 'notSpecified' => 'не указан', 'loginRole' => 'Логин: {login} · Роль: {role}',
        'overdue' => 'Просрочено', 'dueSoon' => 'Срок скоро', 'returned' => 'Возвращено', 'active' => 'Активно',
        'renewals' => 'Продлений: {count}/{max}', 'renew14' => 'Продлить на 14 дней', 'book' => 'Книга', 'inventory' => 'Инв. №', 'issued' => 'Выдано', 'due' => 'Срок', 'returnedOn' => 'Возвращено',
        'profileMissingTitle' => 'Профиль читателя не найден', 'profileMissingBody' => 'Обратитесь к библиотекарю для привязки вашего аккаунта к читательскому профилю.',
        'noActiveTitle' => 'Нет активных выдач', 'noActiveBody' => 'У вас сейчас нет книг на руках.', 'noReturnedTitle' => 'Нет истории возвратов', 'noReturnedBody' => 'Возвращённые книги будут отображаться здесь.', 'noAllTitle' => 'Нет записей о выдачах', 'noAllBody' => 'Когда вы возьмёте книгу, она появится здесь.',
        'openCatalog' => 'Перейти в каталог →', 'renewLoanTitle' => 'Продление выдачи', 'renewLoanMessage' => 'Продлить выдачу на 14 дней? Оставшиеся продления уменьшатся на 1.', 'renewFail' => 'Не удалось продлить выдачу', 'renewBlocked' => 'Продление невозможно', 'networkError' => 'Ошибка сети',
        'legacyMissing' => 'Профиль читателя пока не связан с библиотечной записью. Обратитесь к библиотекарю для проверки данных.', 'summaryError' => 'Не удалось загрузить сводку кабинета', 'apiError' => 'Ошибка API', 'loadLoansError' => 'Не удалось загрузить данные о выдачах',
        'reservationLabel' => 'Бронь', 'year' => 'Год', 'reservedAt' => 'Забронировано', 'validUntil' => 'Действует до', 'reason' => 'Причина', 'cancelConfirm' => 'Вы уверены, что хотите отменить это бронирование?', 'cancelFail' => 'Не удалось отменить бронирование.', 'cancelNetwork' => 'Ошибка сети. Попробуйте ещё раз.', 'loadReservationsError' => 'Не удалось загрузить бронирования.',
        'noReservationsTitle' => 'Нет активных бронирований', 'noReservationsBody' => 'Используйте каталог, чтобы забронировать издание, когда экземпляр станет доступен.', 'openCatalogPlain' => 'Открыть каталог →'
      ],
      'kk' => [
        'guest' => 'Кітапхана қонағы', 'notSpecified' => 'көрсетілмеген', 'loginRole' => 'Логин: {login} · Рөл: {role}',
        'overdue' => 'Мерзімі өткен', 'dueSoon' => 'Жақында тапсыру', 'returned' => 'Қайтарылған', 'active' => 'Белсенді',
        'renewals' => 'Ұзартулар: {count}/{max}', 'renew14' => '14 күнге ұзарту', 'book' => 'Кітап', 'inventory' => 'Инв. №', 'issued' => 'Берілді', 'due' => 'Мерзімі', 'returnedOn' => 'Қайтарылды',
        'profileMissingTitle' => 'Оқырман профилі табылмады', 'profileMissingBody' => 'Аккаунтыңызды оқырман профиліне байланыстыру үшін кітапханашыға жүгініңіз.',
        'noActiveTitle' => 'Белсенді берілім жоқ', 'noActiveBody' => 'Қазір қолыңызда кітап жоқ.', 'noReturnedTitle' => 'Қайтарым тарихы жоқ', 'noReturnedBody' => 'Қайтарылған кітаптар осында көрсетіледі.', 'noAllTitle' => 'Берілім жазбалары жоқ', 'noAllBody' => 'Кітап алған кезде ол осында пайда болады.',
        'openCatalog' => 'Каталогқа өту →', 'renewLoanTitle' => 'Берілімді ұзарту', 'renewLoanMessage' => 'Берілімді 14 күнге ұзартасыз ба? Қалған ұзартулар 1-ге азаяды.', 'renewFail' => 'Берілімді ұзарту мүмкін болмады', 'renewBlocked' => 'Ұзарту мүмкін емес', 'networkError' => 'Желі қатесі',
        'legacyMissing' => 'Оқырман профилі әлі кітапхана жазбасымен байланыспаған. Деректерді тексеру үшін кітапханашыға жүгініңіз.', 'summaryError' => 'Кабинет деректерін жүктеу мүмкін болмады', 'apiError' => 'API қатесі', 'loadLoansError' => 'Берілім деректерін жүктеу мүмкін болмады',
        'reservationLabel' => 'Бронь', 'year' => 'Жыл', 'reservedAt' => 'Брондалған', 'validUntil' => 'Жарамды мерзімі', 'reason' => 'Себеп', 'cancelConfirm' => 'Бұл броньды шынымен тоқтатқыңыз келе ме?', 'cancelFail' => 'Броньды тоқтату мүмкін болмады.', 'cancelNetwork' => 'Желі қатесі. Қайта көріңіз.', 'loadReservationsError' => 'Броньдарды жүктеу мүмкін болмады.',
        'noReservationsTitle' => 'Белсенді броньдар жоқ', 'noReservationsBody' => 'Дана қолжетімді болғанда оны брондау үшін каталогты пайдаланыңыз.', 'openCatalogPlain' => 'Каталогты ашу →'
      ],
      'en' => [
        'guest' => 'Library guest', 'notSpecified' => 'not specified', 'loginRole' => 'Login: {login} · Role: {role}',
        'overdue' => 'Overdue', 'dueSoon' => 'Due soon', 'returned' => 'Returned', 'active' => 'Active',
        'renewals' => 'Renewals: {count}/{max}', 'renew14' => 'Renew for 14 days', 'book' => 'Book', 'inventory' => 'Inv. No.', 'issued' => 'Issued', 'due' => 'Due', 'returnedOn' => 'Returned',
        'profileMissingTitle' => 'Reader profile not found', 'profileMissingBody' => 'Contact a librarian to link your account to a library reader profile.',
        'noActiveTitle' => 'No active loans', 'noActiveBody' => 'You do not have any books on loan right now.', 'noReturnedTitle' => 'No return history', 'noReturnedBody' => 'Returned books will appear here.', 'noAllTitle' => 'No loan records yet', 'noAllBody' => 'When you borrow a book it will appear here.',
        'openCatalog' => 'Open catalog →', 'renewLoanTitle' => 'Renew loan', 'renewLoanMessage' => 'Renew this loan for 14 days? Remaining renewals will decrease by 1.', 'renewFail' => 'Unable to renew the loan', 'renewBlocked' => 'Renewal unavailable', 'networkError' => 'Network error',
        'legacyMissing' => 'The reader profile is not yet linked to a library record. Contact a librarian to verify the data.', 'summaryError' => 'Unable to load the account summary', 'apiError' => 'API error', 'loadLoansError' => 'Unable to load loan data',
        'reservationLabel' => 'Reservation', 'year' => 'Year', 'reservedAt' => 'Reserved at', 'validUntil' => 'Valid until', 'reason' => 'Reason', 'cancelConfirm' => 'Are you sure you want to cancel this reservation?', 'cancelFail' => 'Unable to cancel the reservation.', 'cancelNetwork' => 'Network error. Please try again.', 'loadReservationsError' => 'Unable to load reservations.',
        'noReservationsTitle' => 'No active reservations', 'noReservationsBody' => 'Use the catalog to reserve a title when a copy becomes available.', 'openCatalogPlain' => 'Open catalog →'
      ]
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
    const ACCOUNT_I18N = ACCOUNT_I18N_MAP[ACCOUNT_LANG] || ACCOUNT_I18N_MAP.ru;

    function withLang(path) {
      const url = new URL(path, window.location.origin);
      if (ACCOUNT_LANG !== 'ru' && !url.searchParams.has('lang')) {
        url.searchParams.set('lang', ACCOUNT_LANG);
      }
      return `${url.pathname}${url.search}`;
    }
    let currentLoanTab = 'active';

    function getCsrfToken() {
      return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function normalizeText(value, fallback = '') {
      if (!value || typeof value !== 'string') return fallback;
      return value.trim() || fallback;
    }

    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function getAuthUser() {
      const raw = localStorage.getItem(AUTH_USER_KEY);
      if (!raw) return null;

      try {
        return JSON.parse(raw);
      } catch (_) {
        return null;
      }
    }

    function updateProfileFromAuth(user) {
      if (!user) return;

      const name = normalizeText(user?.name, ACCOUNT_I18N.guest);
      const login = normalizeText(user?.ad_login || user?.login || user?.email, ACCOUNT_I18N.notSpecified);
      const role = normalizeText(user?.role, 'reader');

      const avatar = document.getElementById('profile-avatar');
      const profileName = document.getElementById('profile-name');
      const profileSub = document.getElementById('profile-sub');

      if (avatar) avatar.textContent = name.charAt(0).toUpperCase();
      if (profileName) profileName.textContent = name;
      if (profileSub) profileSub.textContent = ACCOUNT_I18N.loginRole.replace('{login}', login).replace('{role}', role);
    }

    function redirectToLogin() {
      const redirectTo = encodeURIComponent(window.location.pathname + window.location.search);
      window.location.href = withLang(`/login?redirect=${redirectTo}`);
    }

    async function getSessionUser() {
      const response = await fetch(ME_ENDPOINT, {
        headers: {
          Accept: 'application/json',
        },
      });

      if (!response.ok) {
        return null;
      }

      const payload = await response.json().catch(() => ({}));
      if (payload?.authenticated !== true || !payload?.user) {
        return null;
      }

      return payload.user;
    }

    function formatLoanData(loan) {
      const dueDate = loan.dueAt ? new Date(loan.dueAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : '—';
      const issuedDate = loan.issuedAt ? new Date(loan.issuedAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : '—';
      const isOverdue = loan.isOverdue === true;
      const isDueSoon = loan.isDueSoon === true;

      return {
        id: loan.id || '',
        copyId: loan.copyId || '',
        status: loan.status || 'active',
        dueDate,
        issuedDate,
        isOverdue,
        isDueSoon,
        renewCount: loan.renewCount || 0,
        maxRenewals: loan.maxRenewals || 3,
        canRenew: loan.canRenew === true,
        renewBlockReason: loan.renewBlockReason || null,
        returnedAt: loan.returnedAt ? new Date(loan.returnedAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : null,
        book: loan.book || {},
      };
    }

    function loanStatusBadge(data) {
      if (data.isOverdue) return `<span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800;letter-spacing:.04em;color:#ba1a1a;background:rgba(186,26,26,.08);border:1px solid rgba(186,26,26,.16);">${ACCOUNT_I18N.overdue}</span>`;
      if (data.isDueSoon) return `<span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800;letter-spacing:.04em;color:#5d4201;background:rgba(93,66,1,.08);border:1px solid rgba(93,66,1,.16);">${ACCOUNT_I18N.dueSoon}</span>`;
      if (data.status === 'returned') return `<span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800;letter-spacing:.04em;color:#14696d;background:rgba(20,105,109,.08);border:1px solid rgba(20,105,109,.16);">${ACCOUNT_I18N.returned}</span>`;
      return `<span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800;letter-spacing:.04em;color:#001e40;background:rgba(0,30,64,.05);border:1px solid rgba(195,198,209,.55);">${ACCOUNT_I18N.active}</span>`;
    }

    function renderLoanCard(loan) {
      const data = formatLoanData(loan);
      const bookTitle = data.book?.title || null;
      const bookAuthor = data.book?.author || null;
      const bookIsbn = data.book?.isbn || null;
      const invNumber = data.book?.inventoryNumber || null;
      const canRenew = data.canRenew === true;
      const maxRenewals = data.maxRenewals || 3;
      const renewBlockReason = data.renewBlockReason || null;

      const gradientColor = data.isOverdue
        ? 'background: linear-gradient(180deg, #7b1f1f 0%, #5e1515 100%);'
        : (data.isDueSoon
          ? 'background: linear-gradient(180deg, #6a4c12 0%, #5d4201 100%);'
          : (data.status === 'returned'
            ? 'background: linear-gradient(180deg, #59616c 0%, #43474f 100%); opacity: 0.88;'
            : ''));

      const displayTitle = bookTitle
        ? escapeHtml(bookTitle.substring(0, 60)) + (bookTitle.length > 60 ? '…' : '')
        : `${ACCOUNT_I18N.book} ${escapeHtml(data.copyId.substring(0, 12))}…`;

      const previewTitle = bookTitle
        ? escapeHtml(bookTitle.substring(0, 40)) + (bookTitle.length > 40 ? '…' : '')
        : `#${escapeHtml(data.id.substring(0, 8))}`;

      const renewProgress = data.status === 'active'
        ? `<div class="book-meta" style="font-size:12px; color:var(--muted);">${ACCOUNT_I18N.renewals.replace('{count}', data.renewCount).replace('{max}', maxRenewals)}</div>`
        : '';

      let renewSection = '';
      if (canRenew) {
        renewSection = `<button id="renew-btn-${escapeHtml(data.id)}" onclick="readerRenew('${escapeHtml(data.id)}')" style="margin-top:8px;padding:10px 14px;background:linear-gradient(135deg,#001e40,#003366);color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;font-weight:700;width:100%;transition:opacity .2s;">${ACCOUNT_I18N.renew14}</button>`;
      } else if (data.status === 'active' && renewBlockReason) {
        renewSection = `<div style="margin-top:8px;padding:8px 12px;background:rgba(186,26,26,.06);border:1px solid rgba(186,26,26,.16);border-radius:6px;font-size:12px;color:#ba1a1a;text-align:center;">${escapeHtml(renewBlockReason)}</div>`;
      }

      return `
        <article class="book-card">
          <div class="book-preview" style="${gradientColor}">
            <small>${bookAuthor ? escapeHtml(bookAuthor.substring(0, 25)) : ACCOUNT_I18N.book}</small>
            <h3 style="font-size: 14px;">${previewTitle}</h3>
          </div>
          <h3 class="book-title" style="font-size: 15px;">${displayTitle}</h3>
          <div class="book-meta">${loanStatusBadge(data)}</div>
          ${bookAuthor ? `<div class="book-meta" style="font-style:italic;">${escapeHtml(bookAuthor)}</div>` : ''}
          ${bookIsbn ? `<div class="book-meta">ISBN: ${escapeHtml(bookIsbn)}</div>` : ''}
          ${invNumber ? `<div class="book-meta">${ACCOUNT_I18N.inventory}: ${escapeHtml(invNumber)}</div>` : ''}
          <div class="book-meta">${ACCOUNT_I18N.issued}: ${data.issuedDate}</div>
          <div class="book-meta" style="${data.isOverdue ? 'color:#991b1b; font-weight:600;' : (data.isDueSoon ? 'color:#92400e; font-weight:600;' : '')}">${ACCOUNT_I18N.due}: ${data.dueDate}</div>
          ${renewProgress}
          ${data.returnedAt ? `<div class="book-meta" style="color:#065f46;">${ACCOUNT_I18N.returnedOn}: ${data.returnedAt}</div>` : ''}
          ${renewSection}
        </article>
      `;
    }

    function renderNoLoansMessage(hasReaderProfile = true, tab = 'active') {
      if (!hasReaderProfile) {
        return `
          <div class="loading" style="grid-column: 1 / -1; text-align: center; border-color: rgba(234,179,8,.4); background: #fffbeb;">
            <span style="font-size: 28px;">📋</span>
            <p style="margin: 8px 0 0; font-weight: 600; color: #92400e;">${ACCOUNT_I18N.profileMissingTitle}</p>
            <p style="margin: 4px 0 0; color: #a16207; font-size:13px;">${ACCOUNT_I18N.profileMissingBody}</p>
          </div>
        `;
      }
      const messages = {
        active: { icon: '📚', title: ACCOUNT_I18N.noActiveTitle, sub: ACCOUNT_I18N.noActiveBody },
        returned: { icon: '✓', title: ACCOUNT_I18N.noReturnedTitle, sub: ACCOUNT_I18N.noReturnedBody },
        all: { icon: '📖', title: ACCOUNT_I18N.noAllTitle, sub: ACCOUNT_I18N.noAllBody },
      };
      const m = messages[tab] || messages.active;
      return `
        <div class="loading" style="grid-column: 1 / -1; text-align: center;">
          <span style="font-size: 28px;">${m.icon}</span>
          <p style="margin: 8px 0 0; font-weight: 600;">${m.title}</p>
          <p style="margin: 4px 0 0; font-size:13px; color:var(--muted);">${m.sub}</p>
          <p style="margin: 8px 0 0;"><a href="${withLang('/catalog')}" style="color: var(--blue); text-decoration: underline; font-size:14px;">${ACCOUNT_I18N.openCatalog}</a></p>
        </div>
      `;
    }

    function showToast(type, title, message, duration = 4500) {
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      toast.className = `toast ${type}`;
      const icon = type === 'success' ? '✓' : '✕';
      toast.innerHTML = `
        <span class="toast-icon">${icon}</span>
        <div class="toast-body">
          <div class="toast-title">${title}</div>
          <div>${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">×</button>
      `;
      container.appendChild(toast);
      requestAnimationFrame(() => toast.classList.add('visible'));
      setTimeout(() => { toast.classList.remove('visible'); setTimeout(() => toast.remove(), 400); }, duration);
    }

    function showConfirmModal(title, message) {
      return new Promise(resolve => {
        const overlay = document.getElementById('confirm-modal');
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-message').textContent = message;
        overlay.classList.add('visible');
        const confirm = document.getElementById('modal-confirm');
        const cancel = document.getElementById('modal-cancel');
        function cleanup(result) {
          overlay.classList.remove('visible');
          confirm.replaceWith(confirm.cloneNode(true));
          cancel.replaceWith(cancel.cloneNode(true));
          resolve(result);
        }
        confirm.addEventListener('click', () => cleanup(true), { once: true });
        cancel.addEventListener('click', () => cleanup(false), { once: true });
        overlay.addEventListener('click', e => { if (e.target === overlay) cleanup(false); }, { once: true });
      });
    }

    async function readerRenew(loanId) {
      const confirmed = await showConfirmModal(ACCOUNT_I18N.renewLoanTitle, ACCOUNT_I18N.renewLoanMessage);
      if (!confirmed) return;

      const btn = document.getElementById(`renew-btn-${loanId}`);
      if (btn) {
        btn.disabled = true;
        btn.style.opacity = '0.6';
        btn.textContent = ACCOUNT_LANG === 'kk' ? '⏳ Ұзартылуда…' : ACCOUNT_LANG === 'en' ? '⏳ Renewing…' : '⏳ Продление…';
      }

      try {
        const resp = await fetch(`/api/v1/account/loans/${loanId}/renew`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
          },
          body: JSON.stringify({})
        });

        if (resp.status === 401) { redirectToLogin(); return; }

        const data = await resp.json();

        if (resp.ok && data.success) {
          const newDue = data.data?.dueAt ? new Date(data.data.dueAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : '—';
          const renewsLeft = (data.data?.maxRenewals || 3) - (data.data?.renewCount || 0);
          const toastTitle = ACCOUNT_LANG === 'kk' ? '✓ Ұзартылды!' : ACCOUNT_LANG === 'en' ? '✓ Renewed!' : '✓ Продлено!';
          const toastMessage = ACCOUNT_LANG === 'kk'
            ? `Жаңа мерзім: ${newDue}. Қалған ұзартулар: ${renewsLeft}`
            : ACCOUNT_LANG === 'en'
              ? `New due date: ${newDue}. Renewals left: ${renewsLeft}`
              : `Новый срок: ${newDue}. Осталось продлений: ${renewsLeft}`;
          showToast('success', toastTitle, toastMessage);
          loadBooks();
          loadLoanSummary();
        } else {
          const errorMsg = data.message || data.error || ACCOUNT_I18N.renewFail;
          showToast('error', ACCOUNT_I18N.renewBlocked, errorMsg);
          if (btn) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.textContent = `🔄 ${ACCOUNT_I18N.renew14}`;
          }
        }
      } catch (err) {
        showToast('error', ACCOUNT_I18N.networkError, err.message);
        if (btn) {
          btn.disabled = false;
          btn.style.opacity = '1';
          btn.textContent = `🔄 ${ACCOUNT_I18N.renew14}`;
        }
      }
    }

    function updateStats(loanSummary) {
      const activeEl = document.getElementById('active-loans-count');
      const overdueEl = document.getElementById('overdue-loans-count');
      const dueSoonEl = document.getElementById('due-soon-loans-count');
      const returnedEl = document.getElementById('returned-loans-count');

      if (activeEl) activeEl.textContent = String(loanSummary?.activeLoans ?? 0);
      if (overdueEl) {
        const overdue = loanSummary?.overdueLoans ?? 0;
        overdueEl.textContent = String(overdue);
        overdueEl.style.color = overdue > 0 ? '#991b1b' : 'inherit';
      }
      if (dueSoonEl) {
        const dueSoon = loanSummary?.dueSoonLoans ?? 0;
        dueSoonEl.textContent = String(dueSoon);
        dueSoonEl.style.color = dueSoon > 0 ? '#92400e' : 'inherit';
      }
      if (returnedEl) returnedEl.textContent = String(loanSummary?.returnedLoans ?? 0);
    }

    function updateStatusAlert(summary) {
      const alertEl = document.getElementById('account-status-alert');
      if (!alertEl) return;

      const linked = summary?.reader?.linked === true;
      const legacyCode = normalizeText(summary?.reader?.legacyCode, ACCOUNT_I18N.notSpecified);
      const primaryEmail = normalizeText(summary?.reader?.primaryEmail, ACCOUNT_I18N.notSpecified);

      if (!linked) {
        alertEl.textContent = ACCOUNT_I18N.legacyMissing;
        return;
      }

      alertEl.textContent = ACCOUNT_LANG === 'kk'
        ? `Оқырман профилі байланыстырылған. Код: ${legacyCode}. Негізгі email: ${primaryEmail}.`
        : ACCOUNT_LANG === 'en'
          ? `Reader profile linked. Code: ${legacyCode}. Primary email: ${primaryEmail}.`
          : `Профиль читателя связан. Код: ${legacyCode}. Основной email: ${primaryEmail}.`;
    }

    async function loadAccountSummary() {
      const response = await fetch(ACCOUNT_SUMMARY_ENDPOINT, {
        headers: {
          Accept: 'application/json',
        },
      });

      if (response.status === 401) { redirectToLogin(); return; }
      if (!response.ok) {
        throw new Error(ACCOUNT_I18N.summaryError);
      }

      const payload = await response.json().catch(() => ({}));
      const data = payload?.data || {};

      updateStatusAlert(data);

      const sessionUser = data?.user || null;
      const readerName = normalizeText(data?.reader?.fullName);
      if (sessionUser && readerName) {
        updateProfileFromAuth({
          ...sessionUser,
          name: readerName,
        });
      }
    }

    function switchLoanTab(status) {
      currentLoanTab = status;
      document.querySelectorAll('.loan-tab').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.status === status);
        if (btn.dataset.status === status) {
          btn.style.background = 'var(--blue)';
          btn.style.color = '#fff';
          btn.style.borderColor = 'var(--blue)';
        } else {
          btn.style.background = '';
          btn.style.color = '';
          btn.style.borderColor = '';
        }
      });
      loadBooks(status);
    }

    async function loadBooks(tab) {
      tab = tab || currentLoanTab;
      const grid = document.getElementById('book-grid');
      grid.innerHTML = `<div class="loading" style="grid-column: 1 / -1;"><div class="spinner"></div><p style="margin:8px 0 0;">${({ ru: 'Загрузка выдач...', kk: 'Берілімдер жүктелуде...', en: 'Loading loans...' })[ACCOUNT_LANG]}</p></div>`;
      try {
        const statusParam = tab === 'all' ? '' : `?status=${tab}`;
        const response = await fetch(`${ACCOUNT_LOANS_ENDPOINT}${statusParam}`, {
          headers: { Accept: 'application/json' },
        });

        if (response.status === 401) { redirectToLogin(); return; }
        if (!response.ok) throw new Error(ACCOUNT_I18N.apiError);

        const payload = await response.json();
        const loans = Array.isArray(payload?.data) ? payload.data : [];
        const hasReaderProfile = !payload?.message?.includes('No linked reader');

        if (!loans.length) {
          grid.innerHTML = renderNoLoansMessage(hasReaderProfile, tab);
          return;
        }

        grid.innerHTML = loans.map(renderLoanCard).join('');
      } catch (error) {
        console.error(error);
        grid.innerHTML = `<div class="loading" style="grid-column: 1 / -1;">${ACCOUNT_I18N.loadLoansError}</div>`;
      }
    }

    async function loadLoanSummary() {
      try {
        const response = await fetch(ACCOUNT_LOANS_SUMMARY_ENDPOINT, {
          headers: { Accept: 'application/json' },
        });
        if (response.ok) {
          const payload = await response.json();
          updateStats(payload?.data || {});
        }
      } catch (e) { /* silent */ }
    }

    function reservationStatusBadge(status) {
      const labels = {
        PENDING: ACCOUNT_LANG === 'kk' ? '⏳ Күтілуде' : ACCOUNT_LANG === 'en' ? '⏳ Pending' : '⏳ Ожидание',
        READY: ACCOUNT_LANG === 'kk' ? '✓ Беруге дайын' : ACCOUNT_LANG === 'en' ? '✓ Ready for pickup' : '✓ Готово к выдаче',
        FULFILLED: ACCOUNT_LANG === 'kk' ? '📚 Берілді' : ACCOUNT_LANG === 'en' ? '📚 Issued' : '📚 Выдано',
        CANCELLED: ACCOUNT_LANG === 'kk' ? '✕ Бас тартылды' : ACCOUNT_LANG === 'en' ? '✕ Cancelled' : '✕ Отменено',
        EXPIRED: ACCOUNT_LANG === 'kk' ? '⌛ Мерзімі аяқталды' : ACCOUNT_LANG === 'en' ? '⌛ Expired' : '⌛ Истекло',
      };
      const map = {
        'PENDING': { label: labels.PENDING, color: '#92400e', bg: '#fef3c7' },
        'READY': { label: labels.READY, color: '#065f46', bg: '#d1fae5' },
        'FULFILLED': { label: labels.FULFILLED, color: '#1e40af', bg: '#dbeafe' },
        'CANCELLED': { label: labels.CANCELLED, color: '#991b1b', bg: '#fee2e2' },
        'EXPIRED': { label: labels.EXPIRED, color: '#6b7280', bg: '#f3f4f6' },
      };
      const s = map[status] || { label: status, color: '#6b7280', bg: '#f3f4f6' };
      return `<span style="display:inline-block; padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; color:${s.color}; background:${s.bg};">${s.label}</span>`;
    }

    function renderReservationCard(res) {
      const bookTitle = res.book?.title || ACCOUNT_I18N.book;
      const isbn = res.book?.isbn || '';
      const year = res.book?.publishYear || '';
      const reservedAt = res.reservedAt ? new Date(res.reservedAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : '—';
      const expiresAt = res.expiresAt ? new Date(res.expiresAt).toLocaleDateString(ACCOUNT_DATE_LOCALE) : '—';
      const isActive = res.status === 'PENDING' || res.status === 'READY';
      const canCancel = isActive;

      return `
        <article class="book-card" data-reservation-id="${escapeHtml(res.id)}">
          <div class="book-preview" style="${isActive ? 'background: linear-gradient(180deg, #0369a1 0%, #0284c7 100%);' : 'background: linear-gradient(180deg, #475569 0%, #64748b 100%); opacity: 0.85;'}">
            <small>${ACCOUNT_I18N.reservationLabel}</small>
            <h3 style="font-size: 14px;">${escapeHtml(bookTitle.substring(0, 40))}${bookTitle.length > 40 ? '…' : ''}</h3>
          </div>
          <h3 class="book-title" style="font-size: 15px;">${escapeHtml(bookTitle.substring(0, 60))}${bookTitle.length > 60 ? '…' : ''}</h3>
          <div class="book-meta">${reservationStatusBadge(res.status)}</div>
          ${isbn ? `<div class="book-meta">ISBN: ${escapeHtml(isbn)}</div>` : ''}
          ${year ? `<div class="book-meta">${ACCOUNT_I18N.year}: ${year}</div>` : ''}
          <div class="book-meta">${ACCOUNT_I18N.reservedAt}: ${reservedAt}</div>
          <div class="book-meta">${ACCOUNT_I18N.validUntil}: ${expiresAt}</div>
          ${res.cancelReasonCode ? `<div class="book-meta" style="color:#991b1b;">${ACCOUNT_I18N.reason}: ${escapeHtml(res.cancelReasonCode)}</div>` : ''}
          ${canCancel ? `<button class="btn btn-ghost" onclick="cancelReservation('${escapeHtml(res.id)}')" style="margin-top:10px; font-size:13px; padding:8px 16px; color:#991b1b; border-color:#fecaca; width:100%;">${ACCOUNT_LANG === 'kk' ? '✕ Броньды тоқтату' : ACCOUNT_LANG === 'en' ? '✕ Cancel reservation' : '✕ Отменить бронь'}</button>` : ''}
        </article>
      `;
    }

    async function cancelReservation(reservationId) {
      if (!confirm(ACCOUNT_I18N.cancelConfirm)) return;

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      try {
        const res = await fetch(`/api/v1/account/reservations/${encodeURIComponent(reservationId)}/cancel`, {
          method: 'POST',
          headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
          credentials: 'same-origin',
        });

        const json = await res.json();
        if (res.ok && json.success) {
          loadReservations();
        } else {
          alert(json.message || ACCOUNT_I18N.cancelFail);
        }
      } catch (e) {
        alert(ACCOUNT_I18N.cancelNetwork);
      }
    }

    async function loadReservations() {
      const grid = document.getElementById('reservations-grid');
      try {
        const response = await fetch(ACCOUNT_RESERVATIONS_ENDPOINT, {
          headers: { Accept: 'application/json' },
        });

        if (response.status === 401) {
          redirectToLogin();
          return;
        }

        if (!response.ok) throw new Error('Ошибка API');

        const payload = await response.json();
        const reservations = Array.isArray(payload?.data) ? payload.data : [];

        if (!reservations.length) {
          grid.innerHTML = `<div class="loading" style="grid-column:1 / -1;text-align:center;background:#fff;border-style:solid;"><p style="margin:0 0 8px;font-weight:700;color:var(--blue);">${ACCOUNT_I18N.noReservationsTitle}</p><p style="margin:0 0 10px;font-size:13px;color:#5d6972;">${ACCOUNT_I18N.noReservationsBody}</p><a href="${withLang('/catalog')}" style="color:#001e40;text-decoration:underline;font-size:14px;">${ACCOUNT_I18N.openCatalogPlain}</a></div>`;
          return;
        }

        grid.innerHTML = reservations.map(renderReservationCard).join('');
      } catch (error) {
        console.error(error);
        grid.innerHTML = `<div class="loading" style="grid-column: 1 / -1;">${ACCOUNT_I18N.loadReservationsError}</div>`;
      }
    }

    async function loadWorkbench() {
      const loading = document.getElementById('workbench-loading');
      const empty = document.getElementById('workbench-empty');
      const content = document.getElementById('workbench-content');

      try {
        const response = await fetch('/api/v1/shortlist/summary', {
          headers: { Accept: 'application/json' },
        });

        if (!response.ok) throw new Error('Shortlist summary failed');

        const payload = await response.json();
        const data = payload?.data || {};

        loading.style.display = 'none';

        if ((data.total || 0) === 0) {
          empty.style.display = 'block';
          content.style.display = 'none';
          return;
        }

        empty.style.display = 'none';
        content.style.display = 'block';

        document.getElementById('wb-total').textContent = data.total || 0;
        document.getElementById('wb-books').textContent = data.books || 0;
        document.getElementById('wb-external').textContent = data.external || 0;

        const draftInfo = document.getElementById('workbench-draft-info');
        const draft = data.draft || {};
        let html = '';

        if (draft.persistent) {
          const savedLabel = ACCOUNT_LANG === 'kk' ? 'Аккаунтқа сақталған' : ACCOUNT_LANG === 'en' ? 'Saved to account' : 'Сохранено в аккаунт';
          html += `<div style="margin-bottom:8px;"><span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:999px;font-size:11px;font-weight:800;letter-spacing:.04em;background:rgba(20,105,109,.08);color:#14696d;border:1px solid rgba(20,105,109,.16);">${savedLabel}</span></div>`;
        }

        if (draft.title || draft.notes) {
          html += '<div style="padding:14px 18px; border-radius:8px; border:1px solid var(--border); background:#fff; box-shadow:none;">';
          if (draft.title) {
            html += `<div style="font-weight:700; font-size:16px; margin-bottom:4px;">${escapeHtml(draft.title)}</div>`;
          }
          if (draft.notes) {
            html += `<div style="color:var(--muted); font-size:13px; line-height:1.5;">${escapeHtml(draft.notes)}</div>`;
          }
          html += '</div>';
        }

        draftInfo.innerHTML = html;
      } catch (err) {
        loading.style.display = 'none';
        empty.style.display = 'block';
        console.error('Workbench load error:', err);
      }
    }

    (document.getElementById('logout-btn') || document.getElementById('shared-logout-btn'))?.addEventListener('click', async () => {
      try {
        await fetch('/api/v1/logout', {
          method: 'POST',
          headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
          },
        });
      } catch (_) {
        // Best-effort logout: local cleanup still happens below.
      }

      localStorage.removeItem(AUTH_USER_KEY);
      window.location.href = '/login';
    });

    (async () => {
      const sessionUser = await getSessionUser();

      if (sessionUser) {
        localStorage.setItem(AUTH_USER_KEY, JSON.stringify(sessionUser));
        updateProfileFromAuth(sessionUser);
        await Promise.all([
          loadAccountSummary().catch((error) => {
            console.error(error);
          }),
          loadBooks('active'),
          loadLoanSummary(),
          loadReservations(),
          document.getElementById('workbench-section') ? loadWorkbench() : Promise.resolve(),
        ]);
        return;
      }

      // Temporary transition fallback: if stale local user exists, clear it and force real session login.
      if (getAuthUser()) {
        localStorage.removeItem(AUTH_USER_KEY);
      }

      redirectToLogin();
    })();
  </script>
</body>
</html>
