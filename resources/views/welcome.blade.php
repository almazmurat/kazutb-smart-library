@extends('layouts.public', ['activePage' => 'home'])

@php
  $lang = app()->getLocale();
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';

  $withLang = function (string $path, array $query = []) use ($lang): string {
      $normalizedPath = '/' . ltrim($path, '/');
      if ($normalizedPath === '//') {
          $normalizedPath = '/';
      }

      if ($lang !== 'ru' && ! array_key_exists('lang', $query)) {
          $query['lang'] = $lang;
      }

      $query = array_filter($query, static fn ($value) => $value !== null && $value !== '');

      return $normalizedPath . ($query ? ('?' . http_build_query($query)) : '');
  };

  $copy = [
      'ru' => [
          'title' => 'Digital Library - Главная',
          'hero_title' => 'The Scholarly Commons',
          'hero_lead' => 'Спокойный цифровой вход в библиотеку КазТБУ: каталог, академические материалы и университетские ресурсы в одном маршруте.',
          'search_placeholder' => 'Искать книги, авторов, ISBN, УДК или ключевые слова',
          'search_cta' => 'Открыть каталог',
          'trending' => 'Популярные темы:',
          'cards' => [
              [
                  'title' => 'Scholarly Resources',
                  'body' => 'Доступ к лицензируемым научным платформам, базам данных и библиотечным ресурсам для учебных и исследовательских задач.',
                  'cta' => 'Перейти к ресурсам',
                  'href' => $withLang('/resources'),
                  'tone' => 'light',
              ],
              [
                  'title' => 'The Archive',
                  'body' => 'Навигация по цифровым коллекциям и тематическим направлениям университета без декоративных витрин и лишнего шума.',
                  'cta' => 'Открыть раздел Discover',
                  'href' => $withLang('/discover'),
                  'tone' => 'soft',
              ],
              [
                  'title' => 'Member Workspace',
                  'body' => 'Личный маршрут для работы с подборками, сохранением литературы и переходом в кабинет читателя.',
                  'cta' => session('library.user') ? 'Открыть кабинет' : 'Войти в кабинет',
                  'href' => session('library.user') ? $withLang('/account') : $withLang('/login'),
                  'tone' => 'dark',
              ],
          ],
          'repo_kicker' => 'Institutional Repository',
          'repo_title' => 'A Legacy of Technological Excellence',
          'repo_body' => 'Digital Library КазТБУ поддерживает единый доступ к печатному фонду, цифровым материалам и внешним академическим ресурсам, чтобы студенты и преподаватели работали в одном понятном сценарии.',
          'points' => [
              ['title' => 'Проверенные записи', 'body' => 'Библиографические записи и описания проходят библиотечную валидацию.'],
              ['title' => 'Единая университетская навигация', 'body' => 'Каталог, ресурсы и кабинет связаны в единый академический маршрут.'],
          ],
      ],
      'kk' => [
          'title' => 'Digital Library - Басты бет',
          'hero_title' => 'The Scholarly Commons',
          'hero_lead' => 'КазТБУ кітапханасына арналған жинақы цифрлық кіру нүктесі: каталог, академиялық материалдар және университет ресурстары бір бағытта.',
          'search_placeholder' => 'Кітап, автор, ISBN, ӘОЖ немесе кілт сөз бойынша іздеу',
          'search_cta' => 'Каталогты ашу',
          'trending' => 'Танымал тақырыптар:',
          'cards' => [
              [
                  'title' => 'Scholarly Resources',
                  'body' => 'Оқу және зерттеу міндеттеріне арналған лицензиялық платформаларға, дерекқорларға және кітапхана ресурстарына қолжетімділік.',
                  'cta' => 'Ресурстар бөліміне өту',
                  'href' => $withLang('/resources'),
                  'tone' => 'light',
              ],
              [
                  'title' => 'The Archive',
                  'body' => 'Университеттің цифрлық коллекциялары мен тақырыптық бағыттарын артық безендірусіз шолуға арналған навигация.',
                  'cta' => 'Discover бөлімін ашу',
                  'href' => $withLang('/discover'),
                  'tone' => 'soft',
              ],
              [
                  'title' => 'Member Workspace',
                  'body' => 'Іріктемелермен жұмыс, әдебиетті сақтау және оқырман кабинетіне өтуге арналған жеке маршрут.',
                  'cta' => session('library.user') ? 'Кабинетті ашу' : 'Кіру',
                  'href' => session('library.user') ? $withLang('/account') : $withLang('/login'),
                  'tone' => 'dark',
              ],
          ],
          'repo_kicker' => 'Institutional Repository',
          'repo_title' => 'A Legacy of Technological Excellence',
          'repo_body' => 'КазТБУ Digital Library баспа қорына, цифрлық материалдарға және сыртқы академиялық ресурстарға бірыңғай қолжетімділік береді.',
          'points' => [
              ['title' => 'Тексерілген жазбалар', 'body' => 'Библиографиялық жазбалар кітапханалық валидациядан өтеді.'],
              ['title' => 'Бірыңғай университеттік навигация', 'body' => 'Каталог, ресурстар және кабинет бір академиялық маршрутқа біріктірілген.'],
          ],
      ],
      'en' => [
          'title' => 'Digital Library - Home',
          'hero_title' => 'The Scholarly Commons',
          'hero_lead' => 'A calm digital entry to the KazTBU Library: catalog access, academic materials, and university resources in one practical route.',
          'search_placeholder' => 'Search by title, author, ISBN, UDC, or keyword',
          'search_cta' => 'Search the Catalog',
          'trending' => 'Trending topics:',
          'cards' => [
              [
                  'title' => 'Scholarly Resources',
                  'body' => 'Access licensed research platforms, databases, and library resources used for coursework and university research.',
                  'cta' => 'Explore resources',
                  'href' => $withLang('/resources'),
                  'tone' => 'light',
              ],
              [
                  'title' => 'The Archive',
                  'body' => 'Browse university digital collections and subject routes without decorative filler or unsupported claims.',
                  'cta' => 'Open Discover',
                  'href' => $withLang('/discover'),
                  'tone' => 'soft',
              ],
              [
                  'title' => 'Member Workspace',
                  'body' => 'A focused route for shortlist work, saving literature, and moving into the reader account experience.',
                  'cta' => session('library.user') ? 'Open account' : 'Sign in to workspace',
                  'href' => session('library.user') ? $withLang('/account') : $withLang('/login'),
                  'tone' => 'dark',
              ],
          ],
          'repo_kicker' => 'Institutional Repository',
          'repo_title' => 'A Legacy of Technological Excellence',
          'repo_body' => 'KazTBU Digital Library maintains unified access to print holdings, digital materials, and external academic resources for students and faculty.',
          'points' => [
              ['title' => 'Verified records', 'body' => 'Bibliographic records and descriptions are validated through library workflows.'],
              ['title' => 'Unified university navigation', 'body' => 'Catalog, resources, and account routes work as one academic path.'],
          ],
      ],
  ][$lang];

  $topicLinks = [
      ['label' => 'Economic Reform', 'href' => $withLang('/catalog', ['udc' => '33'])],
      ['label' => 'Sustainable Tech', 'href' => $withLang('/catalog', ['udc' => '62'])],
      ['label' => 'Central Asian History', 'href' => $withLang('/catalog', ['udc' => '008'])],
  ];
@endphp

@section('title', $copy['title'])
@section('body_class', 'homepage-canonical')

@section('head')
  <style>
    .homepage-canonical {
      background: #f8f9fa;
      color: #191c1d;
    }

    .homepage-canonical .page-main {
      padding: 0;
    }

    .home-wrap {
      max-width: 1536px;
      margin: 0 auto;
      padding: 0 2rem;
    }

    .hero {
      position: relative;
      min-height: 680px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 5rem 0 7rem;
      overflow: hidden;
      background: linear-gradient(180deg, #f3f4f5 0%, #f8f9fa 100%);
    }

    .hero::after {
      content: '';
      position: absolute;
      inset: 0 0 0 50%;
      background-image: linear-gradient(180deg, rgba(248, 249, 250, 0.55) 0%, rgba(248, 249, 250, 0.82) 100%), url('/images/news/default-library.jpg');
      background-size: cover;
      background-position: center;
      opacity: 0.22;
      pointer-events: none;
    }

    .hero-content {
      position: relative;
      max-width: 860px;
      text-align: center;
      z-index: 1;
    }

    .hero h1 {
      font-family: 'Newsreader', serif;
      font-size: clamp(2.75rem, 6.7vw, 5.4rem);
      font-weight: 500;
      line-height: 1.05;
      margin: 0;
      color: #000613;
      letter-spacing: -0.02em;
    }

    .hero h1 span {
      font-style: italic;
    }

    .hero p {
      margin: 1.5rem auto 0;
      max-width: 700px;
      font-size: clamp(1.05rem, 1.9vw, 1.38rem);
      line-height: 1.6;
      color: #43474e;
    }

    .search-shell {
      margin: 2.7rem auto 0;
      max-width: 860px;
      padding: 0.55rem;
      border-radius: 0.82rem;
      background: #ffffff;
      box-shadow: 0 8px 30px rgba(15, 23, 42, 0.08);
      border: 1px solid #e1e3e4;
    }

    .search-form {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .search-mark {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 2.5rem;
      color: #74777f;
      font-size: 1.1rem;
    }

    .search-input {
      flex: 1;
      border: none;
      background: transparent;
      font: 500 1rem/1.35 'Manrope', sans-serif;
      color: #191c1d;
      min-width: 140px;
      padding: 0.85rem 0;
    }

    .search-input:focus {
      outline: none;
    }

    .search-button {
      border: none;
      border-radius: 0.56rem;
      background: #000613;
      color: #ffffff;
      font: 600 1rem/1 'Manrope', sans-serif;
      padding: 0.95rem 1.4rem;
      white-space: nowrap;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .search-button:hover {
      background: #001f3f;
    }

    .trending {
      margin-top: 0.95rem;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 0.6rem 1.3rem;
      font-size: 0.76rem;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #74777f;
    }

    .trending a {
      color: #006a6a;
      text-decoration: none;
      letter-spacing: 0;
      text-transform: none;
      font-size: 0.78rem;
      font-weight: 500;
    }

    .trending a:hover {
      text-decoration: underline;
    }

    .quick-nav {
      padding: 0 0 7.5rem;
      margin-top: -0.85rem;
    }

    .quick-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 2rem;
    }

    .quick-card {
      min-height: 400px;
      border-radius: 0.8rem;
      padding: 2.45rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      text-decoration: none;
      transition: transform 0.22s ease, background 0.22s ease;
    }

    .quick-card:hover {
      transform: translateY(-2px);
    }

    .quick-card--light {
      background: #ffffff;
      color: #191c1d;
    }

    .quick-card--soft {
      background: #f3f4f5;
      color: #191c1d;
    }

    .quick-card--dark {
      background: #000613;
      color: #ffffff;
    }

    .card-icon {
      width: 3rem;
      height: 3rem;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      margin-bottom: 1.7rem;
    }

    .quick-card--light .card-icon {
      background: rgba(0, 31, 63, 0.08);
      color: #000613;
    }

    .quick-card--soft .card-icon {
      background: rgba(0, 106, 106, 0.1);
      color: #006a6a;
    }

    .quick-card--dark .card-icon {
      background: rgba(255, 255, 255, 0.1);
      color: #ffffff;
    }

    .quick-card h3 {
      margin: 0;
      font: 500 2rem/1.2 'Newsreader', serif;
      letter-spacing: -0.01em;
    }

    .quick-card p {
      margin: 1.1rem 0 0;
      font-size: 1.1rem;
      line-height: 1.65;
      color: inherit;
      opacity: 0.88;
    }

    .card-cta {
      font-weight: 600;
      margin-top: 2rem;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
      color: inherit;
    }

    .info {
      padding: 0 0 7.5rem;
    }

    .info-grid {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
      gap: 4rem;
      align-items: center;
    }

    .info-media {
      border-radius: 1rem;
      overflow: hidden;
      box-shadow: 0 18px 45px rgba(15, 23, 42, 0.17);
      aspect-ratio: 4 / 3;
    }

    .info-media img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .info-copy .kicker {
      display: inline-block;
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.22em;
      color: #006a6a;
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .info-copy h2 {
      margin: 0;
      font: 500 clamp(2rem, 4vw, 3.6rem) / 1.15 'Newsreader', serif;
      color: #000613;
      letter-spacing: -0.01em;
    }

    .info-copy h2 span {
      font-style: italic;
    }

    .info-copy > p {
      margin: 1.6rem 0 0;
      font-size: 1.12rem;
      line-height: 1.72;
      color: #43474e;
    }

    .point-list {
      margin: 2.1rem 0 0;
      display: grid;
      gap: 1.1rem;
    }

    .point-item {
      display: grid;
      grid-template-columns: 1.45rem auto;
      gap: 0.8rem;
      align-items: start;
    }

    .point-dot {
      width: 1.25rem;
      height: 1.25rem;
      border-radius: 50%;
      margin-top: 0.2rem;
      background: #006a6a;
      color: #ffffff;
      font-size: 0.72rem;
      line-height: 1.25rem;
      text-align: center;
      font-weight: 700;
    }

    .point-item strong {
      display: block;
      font-size: 1.1rem;
      color: #000613;
      margin-bottom: 0.2rem;
    }

    .point-item p {
      margin: 0;
      color: #43474e;
      line-height: 1.6;
      font-size: 0.98rem;
    }

    @media (max-width: 1140px) {
      .quick-grid {
        grid-template-columns: 1fr;
      }

      .info-grid {
        grid-template-columns: 1fr;
        gap: 2.5rem;
      }
    }

    @media (max-width: 900px) {
      .home-wrap {
        padding: 0 1.1rem;
      }

      .hero {
        min-height: 620px;
        padding: 4rem 0 5.3rem;
      }

      .hero::after {
        inset: 50% 0 0;
      }

      .search-form {
        flex-wrap: wrap;
      }

      .search-button {
        width: 100%;
      }

      .quick-nav,
      .info {
        padding-bottom: 5rem;
      }

      .quick-card {
        min-height: 340px;
        padding: 1.75rem;
      }
    }
  </style>
@endsection

@section('content')
  <section class="hero">
    <div class="home-wrap">
      <div class="hero-content">
        <h1>The <span>Scholarly</span> Commons</h1>
        <p>{{ $copy['hero_lead'] }}</p>

        <div class="search-shell">
          <form class="search-form" action="{{ $withLang('/catalog') }}" method="get">
            @if($lang !== 'ru')
              <input type="hidden" name="lang" value="{{ $lang }}">
            @endif
            <span class="search-mark" aria-hidden="true">⌕</span>
            <input
              type="search"
              class="search-input"
              name="q"
              placeholder="{{ $copy['search_placeholder'] }}"
              aria-label="{{ $copy['search_placeholder'] }}"
            >
            <button type="submit" class="search-button">{{ $copy['search_cta'] }}</button>
          </form>
          <div class="trending">
            <span>{{ $copy['trending'] }}</span>
            @foreach($topicLinks as $topic)
              <a href="{{ $topic['href'] }}">{{ $topic['label'] }}</a>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="quick-nav">
    <div class="home-wrap">
      <div class="quick-grid">
        @foreach($copy['cards'] as $card)
          <a href="{{ $card['href'] }}" class="quick-card quick-card--{{ $card['tone'] }}" aria-label="{{ $card['title'] }}">
            <div>
              <span class="card-icon" aria-hidden="true">✦</span>
              <h3>{{ $card['title'] }}</h3>
              <p>{{ $card['body'] }}</p>
            </div>
            <span class="card-cta">{{ $card['cta'] }} →</span>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  <section class="info">
    <div class="home-wrap">
      <div class="info-grid">
        <div class="info-media">
          <img src="/images/news/author-visit.jpg" alt="KazTBU Library material">
        </div>

        <div class="info-copy">
          <span class="kicker">{{ $copy['repo_kicker'] }}</span>
          <h2>A Legacy of <span>Technological</span> Excellence</h2>
          <p>{{ $copy['repo_body'] }}</p>

          <div class="point-list">
            @foreach($copy['points'] as $point)
              <div class="point-item">
                <span class="point-dot" aria-hidden="true">✓</span>
                <div>
                  <strong>{{ $point['title'] }}</strong>
                  <p>{{ $point['body'] }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
