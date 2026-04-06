<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Авторизация — Library Hub</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/shell.css">
  <style>
    :root {
      --border: rgba(15, 23, 42, .08);
      --text: #14213d;
      --muted: #64748b;
      --blue: #3b82f6;
      --cyan: #06b6d4;
      --danger: #dc2626;
      --success: #16a34a;
      --shadow: 0 20px 50px rgba(15, 23, 42, .08);
      --shadow-soft: 0 12px 26px rgba(15, 23, 42, .05);
      --radius-xl: 30px;
      --radius-lg: 22px;
      --container: 1650px;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      min-height: 100vh;
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
    }

    .brand small {
      display: block;
      color: var(--muted);
      margin-top: 3px;
      font-weight: 500;
    }

    .btn {
      border: 0;
      cursor: pointer;
      font: inherit;
      border-radius: 16px;
      padding: 12px 18px;
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

    .page {
      min-height: calc(100vh - 84px);
      display: grid;
      place-items: center;
      padding: 38px 0;
    }

    .layout {
      width: min(100%, 1100px);
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .panel {
      border-radius: var(--radius-xl);
      border: 1px solid var(--border);
      background: rgba(255,255,255,.95);
      box-shadow: var(--shadow);
      padding: 30px;
    }

    .promo {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      background:
        radial-gradient(circle at 90% 18%, rgba(245,158,11,.12), transparent 26%),
        radial-gradient(circle at 18% 26%, rgba(59,130,246,.10), transparent 26%),
        linear-gradient(180deg, rgba(255,255,255,.98), rgba(255,255,255,.93));
    }

    .eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 9px 12px;
      border-radius: 999px;
      width: fit-content;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: #1d4ed8;
      background: rgba(59,130,246,.10);
      border: 1px solid rgba(59,130,246,.16);
    }

    .promo h1 {
      margin: 16px 0 12px;
      font-size: 38px;
      letter-spacing: -1px;
      line-height: 1.08;
    }

    .promo p {
      margin: 0;
      color: var(--muted);
      font-size: 16px;
      line-height: 1.7;
      max-width: 470px;
    }

    .badge-row {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 22px;
    }

    .badge {
      padding: 10px 14px;
      border-radius: 999px;
      background: #fff;
      border: 1px solid var(--border);
      box-shadow: var(--shadow-soft);
      font-size: 13px;
      font-weight: 600;
      color: #334155;
    }

    .form-title {
      margin: 0 0 10px;
      font-size: 30px;
      letter-spacing: -.8px;
    }

    .form-sub {
      margin: 0 0 22px;
      color: var(--muted);
      line-height: 1.6;
      font-size: 14px;
    }

    .field { margin-bottom: 14px; }

    .label {
      display: block;
      margin-bottom: 7px;
      font-size: 13px;
      font-weight: 700;
      color: #334155;
    }

    .input {
      width: 100%;
      border: 1px solid var(--border);
      background: #fff;
      color: var(--text);
      border-radius: 14px;
      padding: 13px 14px;
      outline: none;
      font: inherit;
      box-shadow: var(--shadow-soft);
    }

    .input:focus {
      border-color: rgba(59,130,246,.45);
      box-shadow: 0 0 0 4px rgba(59,130,246,.12);
    }

    .submit {
      width: 100%;
      margin-top: 10px;
      padding: 14px 18px;
      font-size: 15px;
    }

    .note {
      margin-top: 12px;
      font-size: 13px;
      color: var(--muted);
      text-align: center;
    }

    .message {
      margin-top: 14px;
      padding: 12px 14px;
      border-radius: 12px;
      font-size: 13px;
      line-height: 1.45;
      display: none;
    }

    .message.error {
      display: block;
      color: var(--danger);
      border: 1px solid rgba(220,38,38,.20);
      background: rgba(220,38,38,.06);
    }

    .message.success {
      display: block;
      color: var(--success);
      border: 1px solid rgba(22,163,74,.20);
      background: rgba(22,163,74,.08);
    }

    @media (max-width: 980px) {
      .layout { grid-template-columns: 1fr; }
      .promo h1 { font-size: 32px; }
    }

    @media (max-width: 640px) {
      .container { width: min(100% - 20px, var(--container)); }
      .panel { padding: 20px; }
      .promo h1 { font-size: 26px; }
      .promo p { font-size: 15px; }
      .input { padding: 14px 16px; min-height: 44px; }
      .btn { min-height: 44px; }
    }

    @media (max-width: 480px) {
      .container { width: min(100% - 16px, var(--container)); }
      .panel { padding: 16px; }
      .promo h1 { font-size: 22px; }
    }

    /* Demo quick-login cards */
    .demo-block {
      margin-top: 24px;
      padding-top: 20px;
      border-top: 1px dashed var(--border);
    }

    .demo-block-title {
      font-size: 13px;
      font-weight: 700;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .06em;
      margin: 0 0 4px;
    }

    .demo-block-subtitle {
      font-size: 12px;
      color: var(--muted);
      margin: 0 0 14px;
      line-height: 1.5;
    }

    .demo-cards {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .demo-card {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 14px;
      border: 1px solid var(--border);
      border-radius: 16px;
      background: #fff;
      cursor: pointer;
      transition: .2s ease;
      text-align: left;
      font: inherit;
      color: var(--text);
    }

    .demo-card:hover {
      border-color: rgba(59,130,246,.35);
      background: rgba(59,130,246,.03);
      transform: translateY(-1px);
      box-shadow: var(--shadow-soft);
    }

    .demo-card:disabled {
      opacity: .5;
      cursor: wait;
    }

    .demo-card-icon {
      font-size: 24px;
      flex-shrink: 0;
    }

    .demo-card-info {
      min-width: 0;
    }

    .demo-card-label {
      font-size: 14px;
      font-weight: 700;
      line-height: 1.2;
    }

    .demo-card-desc {
      font-size: 11px;
      color: var(--muted);
      line-height: 1.35;
      margin-top: 2px;
    }

    .demo-env-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 5px 10px;
      border-radius: 999px;
      font-size: 10px;
      font-weight: 700;
      letter-spacing: .05em;
      text-transform: uppercase;
      background: rgba(245,158,11,.10);
      color: #b45309;
      border: 1px solid rgba(245,158,11,.18);
      margin-bottom: 10px;
    }

    @media (max-width: 640px) {
      .demo-cards { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  @include('partials.navbar', ['activePage' => ''])

  <main class="page">
    <div class="container layout">
      <section class="panel promo">
        <div>
          <span class="eyebrow">Library Hub</span>
          <h1>Вход в личный кабинет библиотеки</h1>
          <p>
            Авторизуйтесь с помощью логина или email, чтобы просматривать доступ к электронным материалам,
            управлять выдачей книг и следить за активными бронями.
          </p>
        </div>

        <div class="badge-row">
          <span class="badge">Единый вход через API</span>
          <span class="badge">Доступ к каталогу 24/7</span>
          <span class="badge">Личный профиль читателя</span>
        </div>
      </section>

      <section class="panel">
        <h2 class="form-title">Авторизация</h2>
        <p class="form-sub">Введите логин или email и пароль. После успешного входа вы будете перенаправлены в кабинет читателя.</p>

        <form id="login-form" novalidate>
          <div class="field">
            <label class="label" for="login">Логин или Email</label>
            <input class="input" id="login" name="login" type="text" placeholder="Например: student01 или mail@example.com" autocomplete="username" required />
          </div>

          <div class="field">
            <label class="label" for="password">Пароль</label>
            <input class="input" id="password" name="password" type="password" placeholder="Введите пароль" autocomplete="current-password" required />
          </div>

          <button id="submit-btn" type="submit" class="btn btn-primary submit">Войти</button>
          <div id="form-message" class="message"></div>
        </form>

        @if(!empty($demoEnabled) && !empty($demoIdentities))
        <div class="demo-block" id="demo-login-block">
          <span class="demo-env-badge">⚠ Dev / Demo</span>
          <p class="demo-block-title">Быстрый вход</p>
          <p class="demo-block-subtitle">Нажмите на карточку для мгновенного входа под выбранной ролью.</p>
          <div class="demo-cards">
            @foreach($demoIdentities as $identity)
            <button class="demo-card" data-demo-slug="{{ $identity['slug'] }}" onclick="demoLogin('{{ $identity['slug'] }}', this)">
              <span class="demo-card-icon">{{ $identity['icon'] }}</span>
              <span class="demo-card-info">
                <span class="demo-card-label">{{ $identity['label'] }}</span>
                <span class="demo-card-desc">{{ $identity['description'] }}</span>
              </span>
            </button>
            @endforeach
          </div>
        </div>
        @endif
      </section>
    </div>
  </main>

  @include('partials.footer')

  <script>
    const AUTH_USER_KEY = 'library.auth.user';

    function getCsrfToken() {
      return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function showMessage(text, type) {
      const el = document.getElementById('form-message');
      if (!el) return;
      el.textContent = text;
      el.className = `message ${type}`;
    }

    function clearMessage() {
      const el = document.getElementById('form-message');
      if (!el) return;
      el.textContent = '';
      el.className = 'message';
    }

    async function submitLogin(loginValue, passwordValue) {
      const payload = {
        password: passwordValue,
        device_name: 'web',
      };

      if (loginValue.includes('@')) {
        payload.email = loginValue;
      } else {
        payload.login = loginValue;
      }

      const response = await fetch('/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-CSRF-TOKEN': getCsrfToken(),
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json().catch(() => ({}));

      if (!response.ok) {
        const message = data?.message || data?.details?.message || 'Ошибка авторизации';
        throw new Error(message);
      }

      const user = data?.user || null;
      if (user) {
        localStorage.setItem(AUTH_USER_KEY, JSON.stringify(user));
      }
    }

    document.getElementById('login-form')?.addEventListener('submit', async (event) => {
      event.preventDefault();
      clearMessage();

      const submitBtn = document.getElementById('submit-btn');
      const loginInput = document.getElementById('login');
      const passwordInput = document.getElementById('password');

      const loginValue = (loginInput?.value || '').trim();
      const passwordValue = (passwordInput?.value || '').trim();

      if (!loginValue || !passwordValue) {
        showMessage('Заполните логин/email и пароль.', 'error');
        return;
      }

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Входим...';
      }

      try {
        await submitLogin(loginValue, passwordValue);
        showMessage('Вход выполнен успешно. Перенаправление...', 'success');
        window.setTimeout(() => {
          const params = new URLSearchParams(window.location.search);
          const redirectTo = params.get('redirect') || '/account';
          // Only allow relative redirects (prevent open redirect)
          const safeRedirect = redirectTo.startsWith('/') ? redirectTo : '/account';
          window.location.href = safeRedirect;
        }, 350);
      } catch (error) {
        showMessage(error?.message || 'Не удалось выполнить вход', 'error');
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = 'Войти';
        }
      }
    });

    async function demoLogin(slug, btn) {
      clearMessage();
      const allCards = document.querySelectorAll('.demo-card');
      allCards.forEach(c => c.disabled = true);

      try {
        const response = await fetch('/api/demo-auth/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
          },
          body: JSON.stringify({ role: slug }),
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
          throw new Error(data?.message || 'Demo login failed');
        }

        const user = data?.user || null;
        if (user) {
          localStorage.setItem(AUTH_USER_KEY, JSON.stringify(user));
        }

        showMessage('Быстрый вход выполнен. Перенаправление...', 'success');
        window.setTimeout(() => {
          const params = new URLSearchParams(window.location.search);
          const redirectTo = params.get('redirect') || '/account';
          const safeRedirect = redirectTo.startsWith('/') ? redirectTo : '/account';
          window.location.href = safeRedirect;
        }, 300);
      } catch (error) {
        showMessage(error?.message || 'Ошибка быстрого входа', 'error');
        allCards.forEach(c => c.disabled = false);
      }
    }
  </script>
</body>
</html>
