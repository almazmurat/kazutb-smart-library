<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Кабинет читателя — Library Hub</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #f5f7fb;
      --surface: #ffffff;
      --border: rgba(15, 23, 42, .08);
      --text: #14213d;
      --muted: #64748b;
      --blue: #3b82f6;
      --cyan: #06b6d4;
      --success: #16a34a;
      --warning: #f59e0b;
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

    .nav-links a:hover,
    .nav-links a.active {
      color: var(--blue);
    }

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

    .profile-grid {
      display: grid;
      grid-template-columns: 1.05fr .95fr;
      gap: 22px;
      margin-bottom: 24px;
    }

    .card {
      border-radius: var(--radius-xl);
      background: rgba(255,255,255,.94);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      padding: 28px;
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
      border-radius: 18px;
      display: grid;
      place-items: center;
      font-size: 28px;
      font-weight: 800;
      color: white;
      background: linear-gradient(140deg, #1d4ed8, #0891b2);
      box-shadow: 0 16px 30px rgba(14,116,144,.25);
    }

    .profile-name {
      margin: 0;
      font-size: 30px;
      letter-spacing: -.8px;
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
      border-radius: 18px;
      border: 1px solid var(--border);
      background: #fff;
      padding: 16px;
      box-shadow: var(--shadow-soft);
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
      border-radius: 18px;
      padding: 14px 16px;
      border: 1px solid var(--border);
      background: #fff;
      box-shadow: var(--shadow-soft);
      font-size: 14px;
      line-height: 1.55;
    }

    .alert.warning { border-color: rgba(245,158,11,.25); background: rgba(245,158,11,.07); }
    .alert.success { border-color: rgba(22,163,74,.25); background: rgba(22,163,74,.08); }

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
      letter-spacing: -.6px;
    }

    .section-head p {
      margin: 0;
      color: var(--muted);
      font-size: 14px;
    }

    .showcase {
      border-radius: var(--radius-xl);
      background: rgba(255,255,255,.94);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      padding: 26px;
    }

    .book-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 14px;
    }

    .book-card {
      padding: 16px;
      border-radius: 22px;
      background: #fff;
      border: 1px solid var(--border);
      box-shadow: var(--shadow-soft);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .book-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }

    .book-preview {
      height: 190px;
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

    .loading {
      border-radius: 16px;
      border: 1px dashed var(--border);
      background: #fff;
      padding: 24px;
      text-align: center;
      color: var(--muted);
    }

    @media (max-width: 1200px) {
      .profile-grid { grid-template-columns: 1fr; }
      .book-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 900px) {
      .nav-links { display: none; }
      .stats { grid-template-columns: 1fr; }
    }

    @media (max-width: 680px) {
      .container { width: min(100% - 20px, var(--container)); }
      .card,
      .showcase { padding: 20px; }
      .book-grid { grid-template-columns: 1fr; }
      .profile-name { font-size: 24px; }
      .nav-actions .btn { padding: 12px 14px; }
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
          <div>КАЗАХСКИЙ УНИВЕРСИТЕТ ТЕХНОЛОГИИ и БИЗНЕСА</div>
          <small>Личный кабинет читателя</small>
        </div>
      </a>

      <nav class="nav-links">
        <a href="/">Главная</a>
        <a href="/catalog">Каталог</a>
        <a href="/account" class="active">Кабинет</a>
      </nav>

      <div class="nav-actions">
        <a href="/catalog" class="btn btn-ghost">Каталог</a>
        <button id="logout-btn" class="btn btn-primary">Выйти</button>
      </div>
    </div>
  </header>

  <main class="page">
    <div class="container">
      <section class="profile-grid">
        <article class="card">
          <div class="profile-head">
            <div id="profile-avatar" class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
            <div>
              <h1 id="profile-name" class="profile-name">{{ auth()->user()->name ?? 'Гость библиотеки' }}</h1>
              <p id="profile-sub" class="profile-sub">
                Логин: {{ auth()->user()->ad_login ?? 'не указан' }}
                · Роль: {{ auth()->user()->role ?? 'reader' }}
              </p>
            </div>
          </div>

          <div class="stats">
            <div class="stat">
              <strong id="issued-count">0</strong>
              <span>Профиль в библиотеке</span>
            </div>
            <div class="stat">
              <strong id="reserved-count">0</strong>
              <span>Контактов reader</span>
            </div>
            <div class="stat">
              <strong id="history-count">0</strong>
              <span>Открытые review задачи</span>
            </div>
          </div>
        </article>

        <article class="card alerts">
          <div id="account-status-alert" class="alert warning">
            Профиль читателя синхронизируется с реальными библиотечными данными.
          </div>
          <div class="alert success">
            Ваш электронный пропуск активен. Онлайн-доступ к ресурсам библиотеки открыт 24/7.
          </div>
          <div class="alert">
            Для быстрого поиска используйте раздел «Каталог», а для чтения описаний и наличия переходите в карточку книги.
          </div>
        </article>
      </section>

      <section class="showcase">
        <div class="section-head">
          <div>
            <h2>Мои книги</h2>
            <p>Список текущих и рекомендуемых изданий в том же формате карточек, что и в каталоге.</p>
          </div>
        </div>

        <div id="book-grid" class="book-grid">
          <div class="loading" style="grid-column: 1 / -1;">Загрузка книг...</div>
        </div>
      </section>
    </div>
  </main>

  <script>
    const API_ENDPOINT = '/api/v1/catalog-db?limit=6';
    const ACCOUNT_SUMMARY_ENDPOINT = '/api/v1/account/summary';
    const ME_ENDPOINT = '/api/v1/me';
    const AUTH_USER_KEY = 'library.auth.user';

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

      const name = normalizeText(user?.name, 'Гость библиотеки');
      const login = normalizeText(user?.ad_login || user?.login || user?.email, 'не указан');
      const role = normalizeText(user?.role, 'reader');

      const avatar = document.getElementById('profile-avatar');
      const profileName = document.getElementById('profile-name');
      const profileSub = document.getElementById('profile-sub');

      if (avatar) avatar.textContent = name.charAt(0).toUpperCase();
      if (profileName) profileName.textContent = name;
      if (profileSub) profileSub.textContent = `Логин: ${login} · Роль: ${role}`;
    }

    function redirectToLogin() {
      const redirectTo = encodeURIComponent(window.location.pathname + window.location.search);
      window.location.href = `/login?redirect=${redirectTo}`;
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

    function formatBookData(book) {
      const title = normalizeText(book?.title?.display || book?.title?.raw, 'Без названия');
      const author = normalizeText(book?.primaryAuthor, 'Автор не указан');
      const publisher = normalizeText(book?.publisher?.name, 'Издательство');
      const available = Number(book?.copies?.available || 0);
      const isbn = normalizeText(book?.isbn?.raw, '');

      return {
        title,
        author,
        publisher,
        available,
        isbn,
      };
    }

    function buildDueDate(offsetDays) {
      const date = new Date();
      date.setDate(date.getDate() + offsetDays);
      return date.toLocaleDateString('ru-RU');
    }

    function renderBookCard(book, index) {
      const data = formatBookData(book);
      const dueDate = buildDueDate(index + 7);

      return `
        <article class="book-card" onclick="location.href='/book/${encodeURIComponent(data.isbn)}'">
          <div class="book-preview">
            <small>${escapeHtml(data.publisher.substring(0, 15))}</small>
            <h3>${escapeHtml(data.title.substring(0, 28))}</h3>
          </div>
          <h3 class="book-title">${escapeHtml(data.title)}</h3>
          <div class="book-meta">${escapeHtml(data.author)} · до ${dueDate}</div>
        </article>
      `;
    }

    function updateStats(stats) {
      const issuedCount = document.getElementById('issued-count');
      const reservedCount = document.getElementById('reserved-count');
      const historyCount = document.getElementById('history-count');

      if (issuedCount) issuedCount.textContent = String(Number(stats?.readerProfilesFound || 0));
      if (reservedCount) reservedCount.textContent = String(Number(stats?.readerContacts || 0));
      if (historyCount) historyCount.textContent = String(Number(stats?.openReaderReviewTasks || 0));
    }

    function updateStatusAlert(summary) {
      const alertEl = document.getElementById('account-status-alert');
      if (!alertEl) return;

      const linked = summary?.reader?.linked === true;
      const legacyCode = normalizeText(summary?.reader?.legacyCode, 'не указан');
      const primaryEmail = normalizeText(summary?.reader?.primaryEmail, 'не указан');

      if (!linked) {
        alertEl.textContent = 'Профиль читателя пока не связан с библиотечной записью. Обратитесь к библиотекарю для проверки данных.';
        return;
      }

      alertEl.textContent = `Профиль читателя связан. Код: ${legacyCode}. Основной email: ${primaryEmail}.`;
    }

    async function loadAccountSummary() {
      const response = await fetch(ACCOUNT_SUMMARY_ENDPOINT, {
        headers: {
          Accept: 'application/json',
        },
      });

      if (!response.ok) {
        throw new Error('Не удалось загрузить account summary');
      }

      const payload = await response.json().catch(() => ({}));
      const data = payload?.data || {};

      updateStats(data?.stats || {});
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

    async function loadBooks() {
      const grid = document.getElementById('book-grid');
      try {
        const response = await fetch(API_ENDPOINT, {
          headers: {
            Accept: 'application/json',
          },
        });
        if (!response.ok) throw new Error('Ошибка API');

        const payload = await response.json();
        const books = Array.isArray(payload?.data) ? payload.data : [];

        if (!books.length) {
          grid.innerHTML = '<div class="loading" style="grid-column: 1 / -1;">Книги не найдены</div>';
          return;
        }

        grid.innerHTML = books.map(renderBookCard).join('');
      } catch (error) {
        console.error(error);
        grid.innerHTML = '<div class="loading" style="grid-column: 1 / -1;">Не удалось загрузить данные кабинета</div>';
      }
    }

    document.getElementById('logout-btn')?.addEventListener('click', async () => {
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
      window.location.href = '/';
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
          loadBooks(),
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
