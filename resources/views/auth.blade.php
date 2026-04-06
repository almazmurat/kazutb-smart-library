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
      .promo h1 { font-size: 28px; }
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
          <small>Страница авторизации</small>
        </div>
      </a>

      <div>
        <a href="/" class="btn btn-ghost">На главную</a>
      </div>
    </div>
  </header>

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
      </section>
    </div>
  </main>

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
  </script>
</body>
</html>
