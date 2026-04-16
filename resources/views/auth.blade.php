<!DOCTYPE html>
<html lang="{{ $lang ?? app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $copy['title'] ?? 'Digital Library' }}</title>
  <style>
    :root {
      color-scheme: light;
      --bg: #f6f8fb;
      --card: #ffffff;
      --text: #18212f;
      --muted: #637083;
      --primary: #123a63;
      --accent: #1f7a8c;
      --border: #d8e0ea;
      --danger: #b3261e;
      --success: #13696d;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: Inter, Arial, sans-serif;
      background: linear-gradient(135deg, #eef4fb 0%, var(--bg) 100%);
      color: var(--text);
    }
    .page {
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 24px;
    }
    .card {
      width: min(100%, 920px);
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(18, 58, 99, 0.08);
      display: grid;
      grid-template-columns: 1.05fr .95fr;
      overflow: hidden;
    }
    .panel {
      padding: 40px;
    }
    .panel.brand {
      background: linear-gradient(160deg, var(--primary), #244d7f);
      color: #fff;
    }
    .eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .12em;
      opacity: .9;
      margin-bottom: 16px;
    }
    .eyebrow::before {
      content: '';
      width: 8px;
      height: 8px;
      border-radius: 999px;
      background: #7be0d0;
    }
    h1 {
      margin: 0 0 12px;
      font-size: 34px;
      line-height: 1.15;
    }
    p {
      margin: 0;
      color: inherit;
      line-height: 1.6;
    }
    .brand-note {
      margin-top: 28px;
      padding: 16px;
      border-radius: 14px;
      background: rgba(255,255,255,.12);
    }
    .field { margin-bottom: 16px; }
    .label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .1em;
      margin-bottom: 8px;
      color: var(--muted);
    }
    .input {
      width: 100%;
      padding: 14px 15px;
      border: 1px solid var(--border);
      border-radius: 12px;
      font-size: 15px;
      background: #fff;
    }
    .input:focus {
      outline: 2px solid rgba(31, 122, 140, .18);
      border-color: var(--accent);
    }
    .submit {
      width: 100%;
      border: 0;
      border-radius: 12px;
      padding: 14px 16px;
      background: var(--primary);
      color: #fff;
      font-weight: 700;
      cursor: pointer;
    }
    .submit:disabled { opacity: .7; cursor: wait; }
    .message {
      margin-top: 12px;
      font-size: 14px;
      min-height: 20px;
    }
    .message.error { color: var(--danger); }
    .message.success { color: var(--success); }
    .demo-block {
      margin-top: 18px;
      padding-top: 18px;
      border-top: 1px solid var(--border);
    }
    .demo-env-badge {
      display: inline-block;
      margin-bottom: 8px;
      padding: 4px 8px;
      border-radius: 999px;
      background: #fff3cd;
      color: #7a5600;
      font-size: 11px;
      font-weight: 700;
    }
    .demo-block-title {
      margin: 0 0 4px;
      font-size: 13px;
      font-weight: 700;
    }
    .demo-block-subtitle {
      margin: 0 0 12px;
      font-size: 13px;
      color: var(--muted);
    }
    .demo-cards {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }
    .demo-card {
      border: 1px solid var(--border);
      border-radius: 12px;
      background: #fff;
      padding: 12px;
      text-align: left;
      cursor: pointer;
    }
    .demo-card-label {
      display: block;
      font-weight: 700;
      color: var(--text);
    }
    .demo-card-desc {
      display: block;
      margin-top: 3px;
      font-size: 12px;
      color: var(--muted);
    }
    .support-note {
      margin-top: 18px;
      padding: 14px;
      border-radius: 12px;
      background: #f7fafc;
      border: 1px solid var(--border);
    }
    .support-note strong {
      display: block;
      margin-bottom: 6px;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: .1em;
      color: var(--muted);
    }
    @media (max-width: 800px) {
      .card { grid-template-columns: 1fr; }
      .panel { padding: 24px; }
      .demo-cards { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div class="page">
    <div class="card">
      <section class="panel brand">
        <div class="eyebrow">{{ $copy['eyebrow'] ?? '' }}</div>
        <h1>{{ $copy['hero'] ?? 'Вход в библиотечную систему' }}</h1>
        <p>{{ $copy['lead'] ?? '' }}</p>

        <div class="brand-note">
          <strong>{{ $copy['accessLabel'] ?? 'Контур доступа' }}</strong>
          <p>{{ $copy['accessValue'] ?? '' }}</p>
        </div>
      </section>

      <section class="panel">
        <form id="login-form" method="POST" action="{{ route('login') }}" novalidate>
          @csrf
          <div class="field">
            <label class="label" for="login">{{ $copy['loginLabel'] ?? 'Login or Email' }}</label>
            <input class="input" id="login" name="login" type="text" placeholder="{{ $copy['loginPlaceholder'] ?? '' }}" autocomplete="username" required>
          </div>

          <div class="field">
            <label class="label" for="password">{{ $copy['passwordLabel'] ?? 'Password' }}</label>
            <input class="input" id="password" name="password" type="password" placeholder="{{ $copy['passwordPlaceholder'] ?? '' }}" autocomplete="current-password" required>
          </div>

          <button id="submit-btn" type="submit" class="submit">{{ $copy['submit'] ?? 'Continue' }}</button>
          <div id="form-message" class="message" aria-live="polite"></div>
        </form>

        @if(!empty($demoEnabled) && !empty($demoIdentities))
          <div class="demo-block" id="demo-login-block">
            <span class="demo-env-badge">{{ $copy['demoEnv'] ?? 'Dev / Demo' }}</span>
            <p class="demo-block-title">{{ $copy['demoTitle'] ?? 'Быстрый вход' }}</p>
            <p class="demo-block-subtitle">{{ $copy['demoSub'] ?? '' }}</p>
            <div class="demo-cards">
              @foreach($demoIdentities as $identity)
                <button type="button" class="demo-card" data-demo-slug="{{ $identity['slug'] }}" onclick="demoLogin('{{ $identity['slug'] }}', this)">
                  <span class="demo-card-label">{{ $identity['icon'] ?? '👤' }} {{ $identity['label'] }}</span>
                  <span class="demo-card-desc">{{ $identity['description'] ?? '' }}</span>
                </button>
              @endforeach
            </div>
          </div>
        @endif

        <div class="support-note">
          <strong>{{ $copy['statusLabel'] ?? 'Статус системы' }}</strong>
          <p>{{ $copy['statusValue'] ?? '' }}</p>
        </div>
      </section>
    </div>
  </div>

  <script>
    const AUTH_USER_KEY = 'library.auth.user';
    const AUTH_LANG = @json($lang ?? 'ru');
    const AUTH_I18N_MAP = {!! json_encode([
      'ru' => [
        'authError' => 'Ошибка авторизации',
        'fillFields' => 'Заполните логин/email и пароль.',
        'submitting' => 'Входим...',
        'success' => 'Вход выполнен успешно. Перенаправление...',
        'submitError' => 'Не удалось выполнить вход',
        'submitDefault' => 'Продолжить',
        'demoSuccess' => 'Быстрый вход выполнен. Перенаправление...',
        'demoError' => 'Ошибка быстрого входа',
      ],
      'kk' => [
        'authError' => 'Кіру қатесі',
        'fillFields' => 'Логин/email мен құпиясөзді толтырыңыз.',
        'submitting' => 'Кіріп жатырмыз...',
        'success' => 'Кіру сәтті өтті. Қайта бағытталуда...',
        'submitError' => 'Кіру мүмкін болмады',
        'submitDefault' => 'Жалғастыру',
        'demoSuccess' => 'Жедел кіру орындалды. Қайта бағытталуда...',
        'demoError' => 'Жедел кіру қатесі',
      ],
      'en' => [
        'authError' => 'Authentication failed',
        'fillFields' => 'Enter both login/email and password.',
        'submitting' => 'Signing in...',
        'success' => 'Sign-in successful. Redirecting...',
        'submitError' => 'Unable to sign in',
        'submitDefault' => 'Continue',
        'demoSuccess' => 'Quick sign-in completed. Redirecting...',
        'demoError' => 'Quick sign-in failed',
      ],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
    const AUTH_I18N = AUTH_I18N_MAP[AUTH_LANG] || AUTH_I18N_MAP.ru;

    function withLang(path) {
      const url = new URL(path, window.location.origin);
      if (AUTH_LANG !== 'ru' && !url.searchParams.has('lang')) {
        url.searchParams.set('lang', AUTH_LANG);
      }
      return `${url.pathname}${url.search}`;
    }

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
      const payload = { password: passwordValue, device_name: 'web' };

      if (loginValue.includes('@')) {
        payload.email = loginValue;
      } else {
        payload.login = loginValue;
      }

      const response = await fetch('/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': getCsrfToken(),
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json().catch(() => ({}));
      if (!response.ok) {
        throw new Error(data?.message || AUTH_I18N.authError);
      }

      if (data?.user) {
        localStorage.setItem(AUTH_USER_KEY, JSON.stringify(data.user));
      }
    }

    document.getElementById('login-form')?.addEventListener('submit', async (event) => {
      event.preventDefault();
      clearMessage();

      const submitBtn = document.getElementById('submit-btn');
      const loginValue = (document.getElementById('login')?.value || '').trim();
      const passwordValue = (document.getElementById('password')?.value || '').trim();

      if (!loginValue || !passwordValue) {
        showMessage(AUTH_I18N.fillFields, 'error');
        return;
      }

      submitBtn.disabled = true;
      submitBtn.textContent = AUTH_I18N.submitting;

      try {
        await submitLogin(loginValue, passwordValue);
        showMessage(AUTH_I18N.success, 'success');
        const params = new URLSearchParams(window.location.search);
        const redirectTo = params.get('redirect') || withLang('/account');
        window.setTimeout(() => {
          window.location.href = redirectTo.startsWith('/') ? redirectTo : withLang('/account');
        }, 300);
      } catch (error) {
        showMessage(error?.message || AUTH_I18N.submitError, 'error');
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = AUTH_I18N.submitDefault;
      }
    });

    async function demoLogin(slug, btn) {
      clearMessage();
      const allCards = document.querySelectorAll('.demo-card');
      allCards.forEach(card => card.disabled = true);

      try {
        const response = await fetch('/api/demo-auth/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
          },
          body: JSON.stringify({ role: slug }),
        });

        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
          throw new Error(data?.message || AUTH_I18N.demoError);
        }

        if (data?.user) {
          localStorage.setItem(AUTH_USER_KEY, JSON.stringify(data.user));
        }

        showMessage(AUTH_I18N.demoSuccess, 'success');
        const params = new URLSearchParams(window.location.search);
        const redirectTo = params.get('redirect') || withLang('/account');
        window.setTimeout(() => {
          window.location.href = redirectTo.startsWith('/') ? redirectTo : withLang('/account');
        }, 300);
      } catch (error) {
        showMessage(error?.message || AUTH_I18N.demoError, 'error');
        allCards.forEach(card => card.disabled = false);
      }
    }
  </script>
</body>
</html>
