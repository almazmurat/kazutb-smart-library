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
      'formSub' => 'Қолданыстағы университет логинін немесе email мен құпиясөзді енгізіңіз. Сәтті кіргеннен кейін оқырман кабинетіне өтесіз.',
      'loginLabel' => 'Логин немесе Email',
      'loginPlaceholder' => 'Мысалы: student01 немесе mail@example.com',
      'passwordLabel' => 'Құпиясөз',
      'passwordPlaceholder' => 'Құпиясөзді енгізіңіз',
      'submit' => 'Жалғастыру',
      'statusLabel' => 'Жүйе күйі',
      'statusValue' => 'CRM аутентификация контуры: онлайн',
      'accessLabel' => 'Қолжетімділік контуры',
      'accessValue' => 'Сессия кітапхана ішінде жалғасады, ал тіркелгі деректерін тексеру CRM API арқылы жүреді.',
      'footerLegal' => '© 2024 КазТБУ Digital Library. Барлық құқықтар қорғалған.',
      'footerMeta' => 'Institutional Resource Center v4.2.0',
      'footerLinks' => [
        ['label' => 'Байланыс', 'href' => '/contacts'],
        ['label' => 'Ресурстар', 'href' => '/resources'],
        ['label' => 'Каталог', 'href' => '/catalog'],
      ],
      'demoTitle' => 'Жедел кіру',
      'demoSub' => 'Таңдалған рөлмен бірден кіру үшін карточканы басыңыз.',
      'demoEnv' => 'Dev / Demo',
    ],
    'en' => [
      'title' => 'Secure access — Digital Library',
      'brand' => 'KazUTB Digital Library',
      'eyebrow' => 'Secure institutional access',
      'hero' => 'Sign in to the library system',
      'lead' => 'Authenticate to open your account, review loans, manage reservations, and move into controlled digital materials.',
      'ssoBanner' => 'University single sign-on gateway',
      'divider' => 'or use credentials',
      'formTitle' => 'Sign in',
      'formSub' => 'Use your current university login or email and password. After a successful sign-in you will be redirected to the member account.',
      'loginLabel' => 'Login or email',
      'loginPlaceholder' => 'Example: student01 or mail@example.com',
      'passwordLabel' => 'Password',
      'passwordPlaceholder' => 'Enter your password',
      'submit' => 'Continue',
      'statusLabel' => 'System status',
      'statusValue' => 'CRM identity gateway: online',
      'accessLabel' => 'Access layer',
      'accessValue' => 'The session stays inside the library interface while credentials are verified through the CRM API.',
      'footerLegal' => '© 2024 KazUTB Digital Library. All rights reserved.',
      'footerMeta' => 'Institutional Resource Center v4.2.0',
      'footerLinks' => [
        ['label' => 'Contacts', 'href' => '/contacts'],
        ['label' => 'Resources', 'href' => '/resources'],
        ['label' => 'Catalog', 'href' => '/catalog'],
      ],
      'demoTitle' => 'Quick sign-in',
      'demoSub' => 'Choose a card to sign in instantly with the selected role.',
      'demoEnv' => 'Dev / Demo',
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
  <style>
    :root {
      --surface: #f8f9fa;
      --surface-low: #f3f4f5;
      --surface-lowest: #ffffff;
      --surface-high: #e7e8e9;
      --outline: #74777f;
      --outline-variant: #c4c6cf;
      --text: #191c1d;
      --muted: #44474e;
      --primary: #000511;
      --primary-deep: #001e40;
      --secondary: #13696d;
      --tertiary: #f7bd48;
      --danger: #ba1a1a;
      --success: #13696d;
      --shadow: 0 14px 32px rgba(25, 28, 29, 0.05);
      --shadow-soft: 0 8px 20px rgba(25, 28, 29, 0.04);
      --radius-lg: 4px;
      --radius-xl: 8px;
      --container: 560px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: 'Manrope', system-ui, sans-serif;
      color: var(--text);
      background:
        radial-gradient(circle at top left, rgba(19, 105, 109, 0.04), transparent 26%),
        radial-gradient(circle at right 12%, rgba(0, 30, 64, 0.04), transparent 20%),
        linear-gradient(180deg, #fcfcfc 0%, var(--surface) 100%);
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    button,
    input {
      font: inherit;
    }

    .page {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .header {
      width: 100%;
      padding: 28px 24px 8px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .header-inner {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      text-align: center;
    }

    .brand {
      font-family: 'Newsreader', Georgia, serif;
      font-size: clamp(1.85rem, 3.4vw, 2.3rem);
      font-weight: 600;
      letter-spacing: -0.04em;
      color: var(--primary-deep);
    }

    .eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 12px;
      border-radius: 4px;
      background: rgba(255, 255, 255, 0.88);
      border: 1px solid rgba(196, 198, 207, 0.35);
      color: var(--secondary);
      font-size: 9px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.16em;
    }

    .eyebrow-dot {
      width: 8px;
      height: 8px;
      border-radius: 999px;
      background: var(--secondary);
      box-shadow: 0 0 0 3px rgba(19, 105, 109, 0.12);
    }

    .main {
      flex: 1;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 10px 24px 40px;
    }

    .auth-shell {
      width: min(100%, var(--container));
      display: flex;
      flex-direction: column;
      gap: 0;
    }

    .auth-card {
      background: rgba(255, 255, 255, 0.95);
      border: 1px solid rgba(196, 198, 207, 0.2);
      box-shadow: var(--shadow);
      border-radius: var(--radius-xl);
      padding: 32px 34px 24px;
    }

    .intro {
      margin-bottom: 22px;
      display: grid;
      gap: 10px;
      text-align: center;
    }

    .intro h1 {
      margin: 0;
      font-family: 'Newsreader', Georgia, serif;
      font-size: clamp(2rem, 3.2vw, 2.4rem);
      font-weight: 500;
      letter-spacing: -0.05em;
      color: var(--primary-deep);
    }

    .intro p {
      margin: 0;
      color: var(--muted);
      font-size: 0.95rem;
      line-height: 1.65;
    }

    .sso-banner {
      width: 100%;
      border: 1px solid rgba(196, 198, 207, 0.22);
      display: flex;
      align-items: center;
      justify-content: flex-start;
      gap: 12px;
      padding: 13px 14px;
      border-radius: var(--radius-lg);
      background: linear-gradient(180deg, rgba(243, 244, 245, 0.96), rgba(255, 255, 255, 0.96));
      color: var(--primary-deep);
      font-size: 0.76rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      box-shadow: none;
    }

    .sso-icon {
      width: 18px;
      height: 18px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 14px;
      margin: 14px 0 20px;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: rgba(196, 198, 207, 0.35);
    }

    .divider span {
      color: var(--outline);
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.18em;
      white-space: nowrap;
    }

    .field {
      margin-bottom: 16px;
    }

    .label {
      display: block;
      margin: 0 0 8px 1px;
      font-size: 10px;
      font-weight: 800;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.18em;
    }

    .input {
      width: 100%;
      border: 0;
      border-bottom: 1px solid var(--outline);
      background: transparent;
      color: var(--text);
      border-radius: 0;
      padding: 13px 1px;
      outline: none;
      font-size: 0.94rem;
      box-shadow: none;
      transition: border-color 0.18s ease, background 0.18s ease;
    }

    .input::placeholder {
      color: rgba(116, 119, 127, 0.58);
    }

    .input:focus {
      border-color: var(--secondary);
      box-shadow: none;
      background: rgba(255, 255, 255, 0.4);
    }

    .submit-wrap {
      padding-top: 8px;
    }

    .submit {
      width: 100%;
      border: 1px solid rgba(0, 30, 64, 0.18);
      background: linear-gradient(135deg, var(--primary-deep), #173c68);
      color: #ffffff;
      border-radius: var(--radius-lg);
      padding: 15px 18px;
      font-size: 0.83rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      cursor: pointer;
      transition: background 0.18s ease, border-color 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
      box-shadow: var(--shadow-soft);
    }

    .submit:hover:not(:disabled) {
      background: linear-gradient(135deg, #052751, #1d497a);
      border-color: rgba(0, 30, 64, 0.3);
      transform: translateY(-1px);
    }

    .submit:disabled {
      opacity: 0.65;
      cursor: wait;
    }

    .message {
      margin-top: 16px;
      padding: 12px 14px;
      border-radius: var(--radius-lg);
      font-size: 12px;
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

    .status-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
      padding: 0 12px;
    }

    .status-card {
      display: flex;
      flex-direction: column;
      gap: 8px;
      min-height: 76px;
    }

    .status-card--accent {
      padding-left: 12px;
      border-left: 1px solid rgba(247, 189, 72, 0.8);
    }

    .status-label {
      color: rgba(174, 126, 0, 0.95);
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.18em;
    }

    .status-card:not(.status-card--accent) .status-label {
      color: var(--muted);
    }

    .status-line {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--muted);
      font-size: 12px;
      line-height: 1.5;
    }

    .status-dot {
      width: 6px;
      height: 6px;
      border-radius: 999px;
      background: var(--secondary);
      flex-shrink: 0;
    }

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
