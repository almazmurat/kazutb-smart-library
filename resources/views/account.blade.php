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
  <link rel="stylesheet" href="/css/shell.css">
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
      top: 100px;
      right: 24px;
      z-index: 1000;
      min-width: 320px;
      max-width: 440px;
      padding: 16px 20px;
      border-radius: 16px;
      background: #fff;
      box-shadow: 0 20px 50px rgba(15,23,42,.18);
      border: 1px solid var(--border);
      display: flex;
      align-items: flex-start;
      gap: 12px;
      transform: translateX(120%);
      transition: transform .35s cubic-bezier(.4,0,.2,1);
      font-size: 14px;
      line-height: 1.5;
    }
    .toast.visible { transform: translateX(0); }
    .toast.success { border-color: rgba(22,163,74,.3); background: #f0fdf4; }
    .toast.error { border-color: rgba(239,68,68,.3); background: #fef2f2; }
    .toast-icon { font-size: 20px; flex-shrink: 0; margin-top: 1px; }
    .toast-body { flex: 1; }
    .toast-title { font-weight: 700; margin-bottom: 2px; }
    .toast-close {
      background: none; border: none; cursor: pointer;
      font-size: 18px; color: var(--muted); padding: 0; line-height: 1;
    }

    .modal-overlay {
      position: fixed; inset: 0; z-index: 900;
      background: rgba(15,23,42,.35);
      backdrop-filter: blur(4px);
      display: flex; align-items: center; justify-content: center;
      opacity: 0; pointer-events: none;
      transition: opacity .25s ease;
    }
    .modal-overlay.visible { opacity: 1; pointer-events: auto; }
    .modal-box {
      background: #fff;
      border-radius: var(--radius-lg);
      padding: 32px;
      max-width: 420px;
      width: 90%;
      box-shadow: 0 24px 60px rgba(15,23,42,.2);
      text-align: center;
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
  @include('partials.navbar', ['activePage' => ''])

  <main class="page">
    <div class="container">
      <section class="profile-grid">
        <article class="card">
          <div class="profile-head">
            <div id="profile-avatar" class="avatar">{{ strtoupper(substr($sessionUser['name'] ?? 'U', 0, 1)) }}</div>
            <div>
              <h1 id="profile-name" class="profile-name">{{ $sessionUser['name'] ?? 'Гость библиотеки' }}</h1>
              <p id="profile-sub" class="profile-sub">
                Логин: {{ $sessionUser['ad_login'] ?? 'не указан' }}
                · Роль: {{ $sessionUser['role'] ?? 'reader' }}
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
            <p>Текущие выдачи из библиотечного фонда.</p>
          </div>
        </div>

        <div id="book-grid" class="book-grid">
          <div class="loading" style="grid-column: 1 / -1;"><div class="spinner"></div><p style="margin:8px 0 0;">Загрузка выдач...</p></div>
        </div>
      </section>

      <section class="showcase" style="margin-top: 36px;">
        <div class="section-head">
          <div>
            <h2>Мои бронирования</h2>
            <p>Статус ваших бронирований в библиотечной системе.</p>
          </div>
        </div>

        <div id="reservations-grid" class="book-grid">
          <div class="loading" style="grid-column: 1 / -1;"><div class="spinner"></div><p style="margin:8px 0 0;">Загрузка бронирований...</p></div>
        </div>
      </section>

      <section id="workbench-section" class="showcase" style="margin-top: 36px;">
        <div class="section-head">
          <div>
            <h2>📋 Подборка литературы</h2>
            <p>Ваш черновик списка литературы для силлабуса или учебной программы.</p>
          </div>
          <a href="/shortlist" class="btn btn-ghost" style="font-size:14px; padding:10px 18px;">Открыть подборку →</a>
        </div>

        <div id="workbench-loading" style="text-align:center; padding:24px;">
          <div class="spinner"></div>
          <p style="margin:8px 0 0; color:var(--muted); font-size:13px;">Загрузка подборки...</p>
        </div>

        <div id="workbench-empty" style="display:none;">
          <div class="loading" style="text-align:center; border-style:dashed;">
            <span style="font-size:32px;">📚</span>
            <p style="margin:8px 0 0; font-weight:600;">Подборка пуста</p>
            <p style="margin:6px 0 0; color:var(--muted); font-size:13px;">Добавляйте книги из каталога и электронные ресурсы в подборку для подготовки силлабуса.</p>
            <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap; margin-top:14px;">
              <a href="/catalog" style="color:var(--blue); font-weight:600; font-size:14px;">Открыть каталог →</a>
              <a href="/resources" style="color:var(--blue); font-weight:600; font-size:14px;">Электронные ресурсы →</a>
              <a href="/discover" style="color:var(--blue); font-weight:600; font-size:14px;">Поиск по направлениям →</a>
            </div>
          </div>
        </div>

        <div id="workbench-content" style="display:none;">
          <div id="workbench-draft-info" style="margin-bottom:18px;"></div>

          <div class="stats" style="grid-template-columns: repeat(3, 1fr); margin-bottom:18px;">
            <div class="stat">
              <strong id="wb-total">0</strong>
              <span>Всего источников</span>
            </div>
            <div class="stat">
              <strong id="wb-books">0</strong>
              <span>Книги из каталога</span>
            </div>
            <div class="stat">
              <strong id="wb-external">0</strong>
              <span>Электронные ресурсы</span>
            </div>
          </div>

          <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="/shortlist" class="btn btn-primary" style="font-size:14px; padding:12px 20px;">📄 Редактировать и экспортировать</a>
            <a href="/catalog" class="btn btn-ghost" style="font-size:14px; padding:12px 20px;">Добавить из каталога</a>
            <a href="/resources" class="btn btn-ghost" style="font-size:14px; padding:12px 20px;">Электронные ресурсы</a>
            <a href="/for-teachers" class="btn btn-ghost" style="font-size:14px; padding:12px 20px;">Руководство для преподавателей</a>
          </div>
        </div>
      </section>
    </div>
  </main>

  <div id="toast-container"></div>
  <div id="confirm-modal" class="modal-overlay">
    <div class="modal-box">
      <h3 id="modal-title">Подтверждение</h3>
      <p id="modal-message"></p>
      <div class="modal-actions">
        <button id="modal-confirm" class="btn btn-primary">Да, продлить</button>
        <button id="modal-cancel" class="btn btn-ghost">Отмена</button>
      </div>
    </div>
  </div>

  @include('partials.footer')

  <script>
    const ACCOUNT_LOANS_ENDPOINT = '/api/v1/account/loans?status=active';
    const ACCOUNT_RESERVATIONS_ENDPOINT = '/api/v1/account/reservations';
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

    function formatLoanData(loan) {
      const dueDate = loan.dueAt ? new Date(loan.dueAt).toLocaleDateString('ru-RU') : '—';
      const issuedDate = loan.issuedAt ? new Date(loan.issuedAt).toLocaleDateString('ru-RU') : '—';
      const isOverdue = loan.isOverdue === true;

      return {
        id: loan.id || '',
        copyId: loan.copyId || '',
        status: loan.status || 'active',
        dueDate,
        issuedDate,
        isOverdue,
        renewCount: loan.renewCount || 0,
        returnedAt: loan.returnedAt ? new Date(loan.returnedAt).toLocaleDateString('ru-RU') : null,
      };
    }

    function renderLoanCard(loan) {
      const data = formatLoanData(loan);
      const statusLabel = data.isOverdue ? '⚠ Просрочено' : (data.status === 'returned' ? 'Возвращено' : 'Активна');
      const statusColor = data.isOverdue ? '#991b1b' : (data.status === 'returned' ? '#166534' : '#1e40af');
      const canRenew = data.status === 'active' && !data.isOverdue && data.renewCount < 3;

      return `
        <article class="book-card">
          <div class="book-preview" style="${data.isOverdue ? 'background: linear-gradient(180deg, #7f1d1d 0%, #991b1b 100%);' : ''}">
            <small>Экземпляр</small>
            <h3>${escapeHtml(data.copyId.substring(0, 12))}…</h3>
          </div>
          <h3 class="book-title">Выдача #${escapeHtml(data.id.substring(0, 8))}</h3>
          <div class="book-meta" style="color: ${statusColor}; font-weight: 700;">${statusLabel}</div>
          <div class="book-meta">Выдано: ${data.issuedDate}</div>
          <div class="book-meta">Срок: ${data.dueDate}${data.renewCount > 0 ? ` (прод. ${data.renewCount}/3)` : ''}</div>
          ${data.returnedAt ? `<div class="book-meta">Возвращено: ${data.returnedAt}</div>` : ''}
          ${canRenew ? `<button onclick="readerRenew('${escapeHtml(data.id)}')" style="margin-top: 8px; padding: 8px 16px; background: #3b82f6; color: white; border: none; border-radius: 12px; cursor: pointer; font-size: 14px; width: 100%;">Продлить</button>` : ''}
        </article>
      `;
    }

    function renderNoLoansMessage(hasReaderProfile = true) {
      if (!hasReaderProfile) {
        return `
          <div class="loading" style="grid-column: 1 / -1; text-align: center; border-color: rgba(234,179,8,.4); background: #fffbeb;">
            <span style="font-size: 28px;">📋</span>
            <p style="margin: 8px 0 0; font-weight: 600; color: #92400e;">Профиль читателя не найден</p>
            <p style="margin: 4px 0 0; color: #a16207;">Обратитесь к библиотекарю для проверки данных.</p>
          </div>
        `;
      }
      return `
        <div class="loading" style="grid-column: 1 / -1; text-align: center;">
          <span style="font-size: 28px;">📚</span>
          <p style="margin: 8px 0 0; font-weight: 600;">Нет активных выдач</p>
          <p style="margin: 4px 0 0;"><a href="/catalog" style="color: var(--blue); text-decoration: underline;">Перейти в каталог →</a></p>
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
      const confirmed = await showConfirmModal('Продление выдачи', 'Продлить выдачу на 14 дней?');
      if (!confirmed) return;

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
          const newDue = data.data?.dueAt ? new Date(data.data.dueAt).toLocaleDateString('ru-RU') : '—';
          showToast('success', 'Продлено!', `Новый срок: ${newDue}. Продлений: ${data.data?.renewCount || '?'}/3`);
          loadBooks();
        } else {
          showToast('error', 'Ошибка', data.message || data.error || 'Не удалось продлить выдачу');
        }
      } catch (err) {
        showToast('error', 'Ошибка сети', err.message);
      }
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

      if (response.status === 401) { redirectToLogin(); return; }
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
        const response = await fetch(ACCOUNT_LOANS_ENDPOINT, {
          headers: {
            Accept: 'application/json',
          },
        });

        if (response.status === 401) {
          redirectToLogin();
          return;
        }

        if (!response.ok) throw new Error('Ошибка API');

        const payload = await response.json();
        const loans = Array.isArray(payload?.data) ? payload.data : [];
        const hasReaderProfile = !payload?.message?.includes('No linked reader');

        if (!loans.length) {
          grid.innerHTML = renderNoLoansMessage(hasReaderProfile);
          return;
        }

        grid.innerHTML = loans.map(renderLoanCard).join('');
      } catch (error) {
        console.error(error);
        grid.innerHTML = '<div class="loading" style="grid-column: 1 / -1;">Не удалось загрузить данные о выдачах</div>';
      }
    }

    function reservationStatusBadge(status) {
      const map = {
        'PENDING': { label: '⏳ Ожидание', color: '#92400e', bg: '#fef3c7' },
        'READY': { label: '✓ Готово к выдаче', color: '#065f46', bg: '#d1fae5' },
        'FULFILLED': { label: '📚 Выдано', color: '#1e40af', bg: '#dbeafe' },
        'CANCELLED': { label: '✕ Отменено', color: '#991b1b', bg: '#fee2e2' },
        'EXPIRED': { label: '⌛ Истекло', color: '#6b7280', bg: '#f3f4f6' },
      };
      const s = map[status] || { label: status, color: '#6b7280', bg: '#f3f4f6' };
      return `<span style="display:inline-block; padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; color:${s.color}; background:${s.bg};">${s.label}</span>`;
    }

    function renderReservationCard(res) {
      const bookTitle = res.book?.title || 'Книга';
      const isbn = res.book?.isbn || '';
      const year = res.book?.publishYear || '';
      const reservedAt = res.reservedAt ? new Date(res.reservedAt).toLocaleDateString('ru-RU') : '—';
      const expiresAt = res.expiresAt ? new Date(res.expiresAt).toLocaleDateString('ru-RU') : '—';
      const isActive = res.status === 'PENDING' || res.status === 'READY';

      return `
        <article class="book-card">
          <div class="book-preview" style="${isActive ? 'background: linear-gradient(180deg, #0369a1 0%, #0284c7 100%);' : 'background: linear-gradient(180deg, #475569 0%, #64748b 100%); opacity: 0.85;'}">
            <small>Бронь</small>
            <h3 style="font-size: 14px;">${escapeHtml(bookTitle.substring(0, 40))}${bookTitle.length > 40 ? '…' : ''}</h3>
          </div>
          <h3 class="book-title" style="font-size: 15px;">${escapeHtml(bookTitle.substring(0, 60))}${bookTitle.length > 60 ? '…' : ''}</h3>
          <div class="book-meta">${reservationStatusBadge(res.status)}</div>
          ${isbn ? `<div class="book-meta">ISBN: ${escapeHtml(isbn)}</div>` : ''}
          ${year ? `<div class="book-meta">Год: ${year}</div>` : ''}
          <div class="book-meta">Забронировано: ${reservedAt}</div>
          <div class="book-meta">Действует до: ${expiresAt}</div>
          ${res.cancelReasonCode ? `<div class="book-meta" style="color:#991b1b;">Причина: ${escapeHtml(res.cancelReasonCode)}</div>` : ''}
        </article>
      `;
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
          grid.innerHTML = '<div class="loading" style="grid-column: 1 / -1;">У вас нет бронирований.<br><a href="/catalog" style="color: #3b82f6; text-decoration: underline;">Перейти в каталог</a></div>';
          return;
        }

        grid.innerHTML = reservations.map(renderReservationCard).join('');
      } catch (error) {
        console.error(error);
        grid.innerHTML = '<div class="loading" style="grid-column: 1 / -1;">Не удалось загрузить бронирования.</div>';
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
        if (draft.title || draft.notes) {
          let html = '<div style="padding:14px 18px; border-radius:16px; border:1px solid var(--border); background:#fff; box-shadow:var(--shadow-soft);">';
          if (draft.title) {
            html += `<div style="font-weight:700; font-size:16px; margin-bottom:4px;">${escapeHtml(draft.title)}</div>`;
          }
          if (draft.notes) {
            html += `<div style="color:var(--muted); font-size:13px; line-height:1.5;">${escapeHtml(draft.notes)}</div>`;
          }
          html += '</div>';
          draftInfo.innerHTML = html;
        } else {
          draftInfo.innerHTML = '';
        }
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
          loadBooks(),
          loadReservations(),
          loadWorkbench(),
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
