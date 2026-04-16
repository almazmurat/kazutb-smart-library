@php
  $lang = app()->getLocale();
  $copy = [
    'ru' => [
      'title' => 'Безопасный вход — Digital Library',
      'brand' => 'KazUTB Digital Library',
      'eyebrow' => 'Защищённый институциональный доступ',
      'hero' => 'Вход в библиотечную систему',
      'lead' => 'Авторизуйтесь, чтобы открыть личный кабинет, проверить выдачи, управлять бронированиями и переходить к контролируемым цифровым материалам.',
      'loginLabel' => 'Логин или Email',
      'loginPlaceholder' => 'Например: student01 или mail@example.com',
      'passwordLabel' => 'Пароль',
      'passwordPlaceholder' => 'Введите пароль',
      'submit' => 'Продолжить',
      'eyebrow' => 'Защищённый институциональный доступ',
      'accessValue' => 'Сессия продолжается внутри библиотеки, а проверка учётных данных идёт через CRM API.',
      'footerLegal' => '© 2024 КазТБУ Digital Library. Все права защищены.',
    ],
    'kk' => [
      'title' => 'Қауіпсіз кіру — Digital Library',
      'brand' => 'KazUTB Digital Library',
      'eyebrow' => 'Қауіпсіз институционалдық қолжетімділік',
      'hero' => 'Кітапхана жүйесіне кіру',
      'lead' => 'Жеке кабинетке кіру, берілімдерді тексеру, броньдарды басқару және бақыланатын цифрлық материалдарға өту үшін авторизациядан өтіңіз.',
      'loginLabel' => 'Логин немесе Email',
      'loginPlaceholder' => 'Мысалы: student01 немесе mail@example.com',
      'passwordLabel' => 'Құпиясөз',
      'passwordPlaceholder' => 'Құпиясөзді енгізіңіз',
      'submit' => 'Жалғастыру',
      'eyebrow' => 'Қауіпсіз институционалдық қолжетімділік',
      'accessValue' => 'Сессия кітапхана ішінде жалғасады, ал тіркелгі деректерін тексеру CRM API арқылы жүреді.',
      'footerLegal' => '© 2024 КазТБУ Digital Library. Барлық құқықтар қорғалған.',
    ],
    'en' => [
      'title' => 'Secure access — Digital Library',
      'brand' => 'KazUTB Digital Library',
      'eyebrow' => 'Secure institutional access',
      'hero' => 'Sign in to the library system',
      'lead' => 'Authenticate to open your account, review loans, manage reservations, and move into controlled digital materials.',
      'loginLabel' => 'Login or email',
      'loginPlaceholder' => 'Example: student01 or mail@example.com',
      'passwordLabel' => 'Password',
      'passwordPlaceholder' => 'Enter your password',
      'submit' => 'Continue',
      'eyebrow' => 'Secure institutional access',
      'accessValue' => 'The session stays inside the library interface while credentials are verified through the CRM API.',
      'footerLegal' => '© 2024 KazUTB Digital Library. All rights reserved.',
    ],
  ][$lang];
@endphp
@php
  $lang = app()->getLocale();
  $withLang = static function (string $path) use ($lang): string {
    return $lang === 'ru' ? $path : $path.(str_contains($path, '?') ? '&' : '?').'lang='.$lang;
  };
  $copy = [
    'ru' => [
      'title' => 'Безопасный вход — Digital Library',
      'brand' => 'KazUTB Digital Library',
      'eyebrow' => 'Защищённый институциональный доступ',
      'hero' => 'Вход в библиотечную систему',
      'lead' => 'Авторизуйтесь, чтобы открыть личный кабинет, проверить выдачи, управлять бронированиями и переходить к контролируемым цифровым материалам.',
      'ssoBanner' => 'Университетский шлюз единого входа',
      'divider' => 'или используйте учётные данные',
      'formTitle' => 'Войти',
      'formSub' => 'Используйте действующий университетский логин или email и пароль. После успешного входа вы будете перенаправлены в кабинет читателя.',
      'loginLabel' => 'Логин или Email',
      'loginPlaceholder' => 'Например: student01 или mail@example.com',
      'passwordLabel' => 'Пароль',
      'passwordPlaceholder' => 'Введите пароль',
      'submit' => 'Продолжить',
      'statusLabel' => 'Статус системы',
      'statusValue' => 'Контур CRM-аутентификации: онлайн',
      'accessLabel' => 'Контур доступа',
      'accessValue' => 'Сессия продолжается внутри библиотеки, а проверка учётных данных идёт через CRM API.',
      'footerLegal' => '© 2024 КазТБУ Digital Library. Все права защищены.',
      'footerMeta' => 'Institutional Resource Center v4.2.0',
      'footerLinks' => [
        ['label' => 'Контакты', 'href' => '/contacts'],
        ['label' => 'Ресурсы', 'href' => '/resources'],
        ['label' => 'Каталог', 'href' => '/catalog'],
      ],
      'demoTitle' => 'Быстрый вход',
      'demoSub' => 'Нажмите на карточку для мгновенного входа под выбранной ролью.',
      'demoEnv' => 'Dev / Demo',
    ],
    'kk' => [
      'title' => 'Қауіпсіз кіру — Digital Library',
      'brand' => 'KazUTB Digital Library',
      'eyebrow' => 'Қауіпсіз институционалдық қолжетімділік',
      'hero' => 'Кітапхана жүйесіне кіру',
      'lead' => 'Жеке кабинетке кіру, берілімдерді тексеру, броньдарды басқару және бақыланатын цифрлық материалдарға өту үшін авторизациядан өтіңіз.',
      'ssoBanner' => 'Университеттің бірыңғай кіру шлюзі',
      'divider' => 'немесе есептік деректерді қолданыңыз',
      'formTitle' => 'Кіру',
      @include('partials.navbar', ['activePage' => 'login'])
      <main class="min-h-screen flex flex-col md:flex-row">
        <!-- Visual Sidebar: Institutional Guidance & Note -->
        <section class="hidden md:flex md:w-5/12 lg:w-1/2 bg-primary relative overflow-hidden flex-col justify-between p-16 text-on-primary">
          <div class="absolute inset-0 opacity-20">
            <img alt="Atmospheric library interior" class="w-full h-full object-cover" src="/images/login-bg.jpg" />
            <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary-container to-transparent"></div>
          </div>
          <div class="relative z-10">
            <div class="flex items-center gap-3 mb-12">
              <span class="material-symbols-outlined text-secondary-fixed text-3xl" data-icon="account_balance">account_balance</span>
              <span class="text-xl font-serif italic tracking-tight">{{ $copy['brand'] }}</span>
            </div>
            <div class="max-w-md">
              <h2 class="text-5xl font-headline mb-8 leading-tight">{{ $copy['hero'] }}</h2>
              <p class="text-on-primary-container text-lg font-body leading-relaxed mb-12">{{ $copy['lead'] }}</p>
            </div>
          </div>
          <div class="relative z-10 space-y-8">
            <div class="flex items-start gap-4">
              <span class="material-symbols-outlined text-secondary mt-1" data-icon="verified_user">verified_user</span>
              <div>
                <h4 class="font-bold text-sm uppercase tracking-widest text-secondary-fixed mb-2">{{ $copy['eyebrow'] }}</h4>
                <p class="text-on-primary-container text-sm leading-snug">{{ $copy['accessValue'] }}</p>
              </div>
            </div>
            <div class="flex items-start gap-4">
              <span class="material-symbols-outlined text-secondary mt-1" data-icon="help">help</span>
              <div>
                <h4 class="font-bold text-sm uppercase tracking-widest text-secondary-fixed mb-2">Support</h4>
                <p class="text-on-primary-container text-sm leading-snug">support@kazutb.edu.kz</p>
              </div>
            </div>
          </div>
        </section>
        <!-- Access Form Section -->
        <section class="flex-1 flex flex-col justify-center bg-surface-container-lowest p-8 md:p-24 relative">
          <div class="md:hidden mb-12 flex items-center gap-3">
            <span class="material-symbols-outlined text-secondary text-2xl" data-icon="account_balance">account_balance</span>
            <span class="text-lg font-serif italic text-primary">{{ $copy['brand'] }}</span>
          </div>
          <div class="max-w-md w-full mx-auto">
            <header class="mb-12">
              <h1 class="text-4xl md:text-5xl font-headline text-primary mb-4 tracking-tight">{{ $copy['hero'] }}</h1>
              <p class="text-on-surface-variant font-body">{{ $copy['lead'] }}</p>
            </header>
            <form class="space-y-8" method="POST" action="{{ route('login') }}">
              @csrf
              <div class="space-y-2">
                <label class="block text-xs font-bold tracking-widest uppercase text-outline" for="login">{{ $copy['loginLabel'] }}</label>
                <div class="relative group">
                  <input class="w-full bg-surface-container-highest border-0 border-b border-outline-variant/20 py-4 px-0 focus:ring-0 focus:border-secondary transition-all font-body text-primary placeholder-on-surface-variant/40" id="login" name="login" placeholder="{{ $copy['loginPlaceholder'] }}" required type="text" autocomplete="username" />
                  <div class="absolute right-0 top-1/2 -translate-y-1/2 flex items-center px-2">
                    <span class="material-symbols-outlined text-on-surface-variant/50 text-xl" data-icon="badge">badge</span>
                  </div>
                </div>
              </div>
              <div class="space-y-2">
                <div class="flex justify-between items-end">
                  <label class="block text-xs font-bold tracking-widest uppercase text-outline" for="password">{{ $copy['passwordLabel'] }}</label>
                  <a class="text-xs font-bold text-secondary hover:underline transition-all" href="/password/reset">{{ __('Forgot?') }}</a>
                </div>
                <div class="relative group">
                  <input class="w-full bg-surface-container-highest border-0 border-b border-outline-variant/20 py-4 px-0 focus:ring-0 focus:border-secondary transition-all font-body text-primary placeholder-on-surface-variant/40" id="password" name="password" placeholder="{{ $copy['passwordPlaceholder'] }}" required type="password" autocomplete="current-password" />
                  <div class="absolute right-0 top-1/2 -translate-y-1/2 flex items-center px-2">
                    <span class="material-symbols-outlined text-on-surface-variant/50 text-xl" data-icon="lock">lock</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center justify-between py-2">
                <label class="flex items-center gap-3 cursor-pointer group">
                  <div class="relative flex items-center">
                    <input class="peer h-5 w-5 rounded-lg border-outline-variant text-secondary focus:ring-secondary/20" type="checkbox" name="remember" />
                  </div>
                  <span class="text-sm text-on-surface-variant group-hover:text-primary transition-colors">{{ __('Keep me signed in for 30 days') }}</span>
                </label>
              </div>
              <button class="w-full py-5 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold tracking-widest uppercase text-sm rounded-lg hover:shadow-xl hover:shadow-primary/10 active:opacity-80 transition-all duration-300 flex justify-center items-center gap-2 group" type="submit">
                {{ $copy['submit'] }}
                <span class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform" data-icon="arrow_forward">arrow_forward</span>
              </button>
            </form>
            <footer class="mt-16 text-center">
              <p class="text-xs text-outline leading-relaxed max-w-xs mx-auto">
                {{ __('Unauthorized access is prohibited. All activity is logged for security and auditing purposes as per institutional policy.') }}
              </p>
            </footer>
          </div>
        </section>
      </main>
      @include('partials.footer')

    .demo-block {
      margin-top: 16px;
      padding: 16px;
      border: 1px solid rgba(196, 198, 207, 0.24);
      border-radius: var(--radius-lg);
      background: rgba(243, 244, 245, 0.72);
    }

    .demo-env-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 10px;
      border-radius: 999px;
      font-size: 10px;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
      background: rgba(245, 158, 11, 0.1);
      color: #b45309;
      border: 1px solid rgba(245, 158, 11, 0.2);
      margin-bottom: 12px;
    }

    .demo-block-title {
      font-size: 11px;
      font-weight: 800;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .16em;
      margin: 0 0 5px;
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
      gap: 12px;
    }

    .demo-card {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 13px;
      border: 1px solid rgba(196, 198, 207, 0.3);
      border-radius: var(--radius-lg);
      background: rgba(255,255,255,.9);
      cursor: pointer;
      transition: transform .18s ease, background .18s ease, border-color .18s ease, box-shadow .18s ease;
      text-align: left;
      color: var(--text);
    }

    .demo-card:hover {
      border-color: rgba(0,30,64,.18);
      background: rgba(243, 244, 245, 0.95);
      transform: translateY(-1px);
      box-shadow: 0 8px 20px rgba(25, 28, 29, .04);
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

    .support-note {
      margin-top: 16px;
      padding-top: 14px;
      border-top: 1px solid rgba(196, 198, 207, 0.22);
      display: grid;
      gap: 6px;
    }

    .support-note strong {
      color: var(--primary-deep);
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.14em;
    }

    .support-note p {
      margin: 0;
      color: var(--muted);
      font-size: 12px;
      line-height: 1.6;
    }

    .support-note-status {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      width: fit-content;
      padding: 6px 10px;
      border-radius: 999px;
      background: rgba(19, 105, 109, 0.08);
      color: var(--secondary);
      font-size: 11px;
      font-weight: 700;
    }

    .support-note-status-dot {
      width: 7px;
      height: 7px;
      border-radius: 999px;
      background: var(--secondary);
    }

    .footer {
      width: 100%;
      border-top: 1px solid rgba(196, 198, 207, 0.18);
      background: rgba(248, 249, 250, 0.88);
      padding: 20px 24px 24px;
    }

    .footer-inner {
      width: min(100%, var(--container));
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }

    .footer-copy {
      display: flex;
      flex-direction: column;
      gap: 4px;
      color: rgba(68, 71, 78, 0.82);
    }

    .footer-copy strong {
      font-family: 'Newsreader', Georgia, serif;
      font-size: 0.95rem;
      font-weight: 500;
    }

    .footer-copy span {
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.16em;
    }

    .footer-links {
      display: flex;
      flex-wrap: wrap;
      justify-content: flex-end;
      gap: 28px;
    }

    .footer-links a {
      color: rgba(68, 71, 78, 0.8);
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.14em;
      transition: color 0.18s ease;
    }

    .footer-links a:hover {
      color: var(--primary-deep);
    }

    @media (max-width: 768px) {
      .header {
        padding-top: 24px;
      }

      .main {
        padding: 8px 16px 34px;
      }

      .auth-card {
        padding: 24px 20px 20px;
      }

      .footer-inner {
        flex-direction: column;
        align-items: flex-start;
      }

      .footer-links {
        justify-content: flex-start;
        gap: 18px;
      }
    }

    @media (max-width: 640px) {
      .demo-cards {
        grid-template-columns: 1fr;
      }

      .divider {
        gap: 10px;
      }

      .divider span {
        font-size: 9px;
      }
    }
  </style>
</head>
<body>
  <div class="page">
    <header class="header">
      <div class="header-inner">
        <div class="brand">{{ $copy['brand'] }}</div>
        <div class="eyebrow">
          <span class="eyebrow-dot" aria-hidden="true"></span>
          <span>{{ $copy['eyebrow'] }}</span>
        </div>
      </div>
    </header>

    <main class="main">
      <div class="auth-shell">
        <section class="auth-card">
          <div class="intro">
            <h1>{{ $copy['hero'] }}</h1>
            <p>{{ $copy['lead'] }}</p>
          </div>

          <div class="sso-banner" aria-hidden="true">
            <span class="sso-icon">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
                <path d="M12 3 3 7v2h18V7l-9-4Zm-7 8v6H3v2h18v-2h-2v-6h-2v6h-3v-6h-2v6H9v-6H7Z"/>
              </svg>
            </span>
            <span>{{ $copy['ssoBanner'] }}</span>
          </div>

          <div class="divider">
            <span>{{ $copy['divider'] }}</span>
          </div>

          <form id="login-form" novalidate>
            <div class="field">
              <label class="label" for="login">{{ $copy['loginLabel'] }}</label>
              <input class="input" id="login" name="login" type="text" placeholder="{{ $copy['loginPlaceholder'] }}" autocomplete="username" required />
            </div>

            <div class="field">
              <label class="label" for="password">{{ $copy['passwordLabel'] }}</label>
              <input class="input" id="password" name="password" type="password" placeholder="{{ $copy['passwordPlaceholder'] }}" autocomplete="current-password" required />
            </div>

            <div class="submit-wrap">
              <button id="submit-btn" type="submit" class="submit">{{ $copy['submit'] }}</button>
            </div>
            <div id="form-message" class="message" aria-live="polite"></div>
          </form>

          @if(!empty($demoEnabled) && !empty($demoIdentities))
          <div class="demo-block" id="demo-login-block">
            <span class="demo-env-badge">{{ $copy['demoEnv'] }}</span>
            <p class="demo-block-title">{{ $copy['demoTitle'] }}</p>
            <p class="demo-block-subtitle">{{ $copy['demoSub'] }}</p>
            <div class="demo-cards">
              @foreach($demoIdentities as $identity)
              <button type="button" class="demo-card" data-demo-slug="{{ $identity['slug'] }}" onclick="demoLogin('{{ $identity['slug'] }}', this)">
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

          <div class="support-note" aria-label="Access context">
            <strong>{{ $copy['accessLabel'] }}</strong>
            <span class="support-note-status">
              <span class="support-note-status-dot" aria-hidden="true"></span>
              <span>{{ $copy['statusValue'] }}</span>
            </span>
            <p>{{ $copy['accessValue'] }}</p>
          </div>
        </section>
      </div>
    </main>

    <footer class="footer">
      <div class="footer-inner">
        <div class="footer-copy">
          <strong>{{ $copy['footerLegal'] }}</strong>
          <span>{{ $copy['footerMeta'] }}</span>
        </div>
        <nav class="footer-links" aria-label="Footer navigation">
          @foreach($copy['footerLinks'] as $link)
            <a href="{{ $withLang($link['href']) }}">{{ $link['label'] }}</a>
          @endforeach
        </nav>
      </div>
    </footer>
  </div>

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
