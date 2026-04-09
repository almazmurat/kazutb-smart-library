@php
  $lang = app()->getLocale();
  $copy = [
    'ru' => [
      'title' => 'Безопасный вход — Digital Library',
      'eyebrow' => 'Безопасный вход',
      'hero' => 'Вход в портал читателя',
      'lead' => 'Авторизуйтесь, чтобы открыть личный кабинет, проверить выдачи, управлять бронированиями и переходить к контролируемым цифровым материалам.',
      'badges' => ['Единый вход через API', 'Доступ к каталогу 24/7', 'Личный профиль читателя'],
      'formTitle' => 'Авторизация',
      'formSub' => 'Введите логин или email и пароль. После успешного входа вы будете перенаправлены в кабинет читателя.',
      'loginLabel' => 'Логин или Email',
      'loginPlaceholder' => 'Например: student01 или mail@example.com',
      'passwordLabel' => 'Пароль',
      'passwordPlaceholder' => 'Введите пароль',
      'submit' => 'Войти',
      'demoTitle' => 'Быстрый вход',
      'demoSub' => 'Нажмите на карточку для мгновенного входа под выбранной ролью.',
    ],
    'kk' => [
      'title' => 'Қауіпсіз кіру — Digital Library',
      'eyebrow' => 'Қауіпсіз кіру',
      'hero' => 'Оқырман порталына кіру',
      'lead' => 'Жеке кабинетке кіру, берілімдерді тексеру, броньдарды басқару және бақыланатын цифрлық материалдарға өту үшін авторизациядан өтіңіз.',
      'badges' => ['API арқылы бірыңғай кіру', 'Каталогқа 24/7 қолжетімділік', 'Оқырманның жеке профилі'],
      'formTitle' => 'Кіру',
      'formSub' => 'Логин немесе email мен құпиясөзді енгізіңіз. Сәтті кіргеннен кейін оқырман кабинетіне өтесіз.',
      'loginLabel' => 'Логин немесе Email',
      'loginPlaceholder' => 'Мысалы: student01 немесе mail@example.com',
      'passwordLabel' => 'Құпиясөз',
      'passwordPlaceholder' => 'Құпиясөзді енгізіңіз',
      'submit' => 'Кіру',
      'demoTitle' => 'Жедел кіру',
      'demoSub' => 'Таңдалған рөлмен бірден кіру үшін карточканы басыңыз.',
    ],
    'en' => [
      'title' => 'Secure access — Digital Library',
      'eyebrow' => 'Secure access',
      'hero' => 'Sign in to the member portal',
      'lead' => 'Authenticate to open your account, review loans, manage reservations, and move into controlled digital materials.',
      'badges' => ['Single sign-on via API', '24/7 catalog access', 'Personal reader profile'],
      'formTitle' => 'Sign in',
      'formSub' => 'Enter your login or email and password. After a successful sign-in you will be redirected to the reader account.',
      'loginLabel' => 'Login or email',
      'loginPlaceholder' => 'Example: student01 or mail@example.com',
      'passwordLabel' => 'Password',
      'passwordPlaceholder' => 'Enter your password',
      'submit' => 'Sign in',
      'demoTitle' => 'Quick sign-in',
      'demoSub' => 'Choose a card to sign in instantly with the selected role.',
    ],
  ][$lang];
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>{{ $copy['title'] }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Newsreader:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/shell.css">
  <style>
    :root {
      --border: rgba(195, 198, 209, .55);
      --text: #191c1d;
      --muted: #43474f;
      --blue: #001e40;
      --cyan: #14696d;
      --danger: #ba1a1a;
      --success: #14696d;
      --shadow: 0 12px 32px rgba(25, 28, 29, .04);
      --shadow-soft: 0 6px 16px rgba(25, 28, 29, .03);
      --radius-xl: 8px;
      --radius-lg: 6px;
      --container: 1280px;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: 'Manrope', system-ui, sans-serif;
      color: var(--text);
      background: #f8f9fa;
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
      border-radius: var(--radius-lg);
      padding: 12px 18px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
      font-weight: 700;
    }

    .btn:hover { transform: translate3d(0, -1px, 0); }
    .btn-primary { color: white; background: linear-gradient(135deg, var(--blue), #003366); box-shadow: var(--shadow-soft); }
    .btn-ghost { background: transparent; border: 1px solid var(--border); color: var(--text); box-shadow: none; }

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
      background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
      box-shadow: var(--shadow);
      padding: 30px;
      position: relative;
      overflow: hidden;
      transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .panel:hover {
      transform: translate3d(0, -2px, 0);
      box-shadow: 0 18px 38px rgba(25, 28, 29, .06);
      border-color: rgba(0,30,64,.12);
    }

    .promo {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      color: var(--text);
      background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
      border-color: var(--border);
    }

    .promo::after {
      content: '';
      position: absolute;
      right: -60px;
      top: -60px;
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(20,105,109,.10), transparent 70%);
      transition: transform .42s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .promo:hover::after {
      transform: translate3d(-10px, 10px, 0);
    }

    .eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 0;
      border-radius: 0;
      width: fit-content;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: var(--cyan);
      background: transparent;
      border: 0;
    }

    .promo h1 {
      margin: 16px 0 12px;
      font-family: 'Newsreader', Georgia, serif;
      font-size: 38px;
      letter-spacing: -1px;
      line-height: 1.08;
      color: var(--blue);
    }

    .promo p {
      margin: 0;
      color: var(--muted);
      font-size: 16px;
      line-height: 1.75;
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
      background: rgba(255,255,255,.82);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-soft);
      font-size: 13px;
      font-weight: 600;
      color: var(--text);
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
      border: 0;
      border-bottom: 1px solid var(--border);
      background: transparent;
      color: var(--text);
      border-radius: 0;
      padding: 13px 0;
      outline: none;
      font: inherit;
      box-shadow: none;
      transition: border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    .input:focus {
      border-color: var(--blue);
      box-shadow: none;
      background: rgba(255,255,255,.42);
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
      border-radius: 8px;
      background: #fff;
      cursor: pointer;
      transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1);
      text-align: left;
      font: inherit;
      color: var(--text);
    }

    .demo-card:hover {
      border-color: rgba(0,30,64,.16);
      background: rgba(0,30,64,.03);
      transform: translate3d(0, -2px, 0);
      box-shadow: 0 12px 26px rgba(25,28,29,.04);
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
          <span class="eyebrow">{{ $copy['eyebrow'] }}</span>
          <h1>{{ $copy['hero'] }}</h1>
          <p>{{ $copy['lead'] }}</p>
        </div>

        <div class="badge-row">
          @foreach($copy['badges'] as $badge)
            <span class="badge">{{ $badge }}</span>
          @endforeach
        </div>
      </section>

      <section class="panel">
        <h2 class="form-title">{{ $copy['formTitle'] }}</h2>
        <p class="form-sub">{{ $copy['formSub'] }}</p>

        <form id="login-form" novalidate>
          <div class="field">
            <label class="label" for="login">{{ $copy['loginLabel'] }}</label>
            <input class="input" id="login" name="login" type="text" placeholder="{{ $copy['loginPlaceholder'] }}" autocomplete="username" required />
          </div>

          <div class="field">
            <label class="label" for="password">{{ $copy['passwordLabel'] }}</label>
            <input class="input" id="password" name="password" type="password" placeholder="{{ $copy['passwordPlaceholder'] }}" autocomplete="current-password" required />
          </div>

          <button id="submit-btn" type="submit" class="btn btn-primary submit">{{ $copy['submit'] }}</button>
          <div id="form-message" class="message"></div>
        </form>

        @if(!empty($demoEnabled) && !empty($demoIdentities))
        <div class="demo-block" id="demo-login-block">
          <span class="demo-env-badge">⚠ Dev / Demo</span>
          <p class="demo-block-title">{{ $copy['demoTitle'] }}</p>
          <p class="demo-block-subtitle">{{ $copy['demoSub'] }}</p>
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
    const AUTH_LANG = @json($lang);
    const AUTH_I18N_MAP = {!! json_encode([
      'ru' => [
        'authError' => 'Ошибка авторизации',
        'fillFields' => 'Заполните логин/email и пароль.',
        'submitting' => 'Входим...',
        'success' => 'Вход выполнен успешно. Перенаправление...',
        'submitError' => 'Не удалось выполнить вход',
        'submitDefault' => 'Войти',
        'demoSuccess' => 'Быстрый вход выполнен. Перенаправление...',
        'demoError' => 'Ошибка быстрого входа',
      ],
      'kk' => [
        'authError' => 'Кіру қатесі',
        'fillFields' => 'Логин/email мен құпиясөзді толтырыңыз.',
        'submitting' => 'Кіріп жатырмыз...',
        'success' => 'Кіру сәтті өтті. Қайта бағытталуда...',
        'submitError' => 'Кіру мүмкін болмады',
        'submitDefault' => 'Кіру',
        'demoSuccess' => 'Жедел кіру орындалды. Қайта бағытталуда...',
        'demoError' => 'Жедел кіру қатесі',
      ],
      'en' => [
        'authError' => 'Authentication failed',
        'fillFields' => 'Enter both login/email and password.',
        'submitting' => 'Signing in...',
        'success' => 'Sign-in successful. Redirecting...',
        'submitError' => 'Unable to sign in',
        'submitDefault' => 'Sign in',
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
        const message = data?.message || data?.details?.message || AUTH_I18N.authError;
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
        showMessage(AUTH_I18N.fillFields, 'error');
        return;
      }

      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = AUTH_I18N.submitting;
      }

      try {
        await submitLogin(loginValue, passwordValue);
        showMessage(AUTH_I18N.success, 'success');
        window.setTimeout(() => {
          const params = new URLSearchParams(window.location.search);
          const redirectTo = params.get('redirect') || withLang('/account');
          const safeRedirect = redirectTo.startsWith('/') ? redirectTo : withLang('/account');
          window.location.href = safeRedirect;
        }, 350);
      } catch (error) {
        showMessage(error?.message || AUTH_I18N.submitError, 'error');
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = AUTH_I18N.submitDefault;
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

        showMessage(AUTH_I18N.demoSuccess, 'success');
        window.setTimeout(() => {
          const params = new URLSearchParams(window.location.search);
          const redirectTo = params.get('redirect') || withLang('/account');
          const safeRedirect = redirectTo.startsWith('/') ? redirectTo : withLang('/account');
          window.location.href = safeRedirect;
        }, 300);
      } catch (error) {
        showMessage(error?.message || AUTH_I18N.demoError, 'error');
        allCards.forEach(c => c.disabled = false);
      }
    }
  </script>
</body>
</html>
