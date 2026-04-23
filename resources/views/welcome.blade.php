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

  $chrome = [
      'ru' => [
          'title'                    => 'Главная — Библиотека КазТБУ',
          'hero_kicker'              => 'Цифровой куратор',
          'hero_h1'                  => 'Открывайте знания,',
          'hero_h1_accent'           => 'управляйте источниками.',
          'hero_lead'                => 'Единая точка доступа к академическим коллекциям, научным архивам и цифровым ресурсам КазТБУ.',
          'search_placeholder'       => 'Поиск по каталогу, авторам, УДК…',
          'search_cta'               => 'Найти',
          'trending'                 => 'Актуальные темы:',
          'hero_img_alt'             => 'Читальный зал библиотеки КазТБУ',
          'stats_archives_label'     => 'Архивных материалов',
          'stats_archives_value'     => '120 000+',
          'stats_scholars_label'     => 'Активных читателей',
          'stats_scholars_value'     => '8 400+',
          'collections_heading'      => 'Избранные коллекции',
          'collections_lead'         => 'Тематические подборки по ключевым дисциплинам КазТБУ',
          'collections_all_cta'      => 'Все коллекции',
          'collection_featured_badge'   => 'Основной фонд',
          'collection_featured_title'   => 'Академические ресурсы',
          'collection_featured_body'    => 'Монографии, диссертации и периодика по профильным направлениям университета: инженерия, экономика, право.',
          'collection_featured_cta'     => 'Открыть каталог',
          'collection_featured_img_alt' => 'Читальный зал библиотеки КазТБУ',
          'collection_tile1_title'   => 'Прикладные науки',
          'collection_tile1_body'    => 'Технические и инженерные дисциплины (УДК 5)',
          'collection_tile1_img_alt' => 'Семинар ИИ',
          'collection_tile2_title'   => 'Экономика и право',
          'collection_tile2_body'    => 'Правовые и экономические исследования (УДК 33)',
          'collection_tile2_img_alt' => 'Классический фонд',
          'services_heading'         => 'Научные сервисы',
          'services_lead'            => 'Инструменты и ресурсы для академической работы',
          'services'                 => [
              ['icon' => 'library_books', 'title' => 'Справочное обслуживание', 'body' => 'Помощь с поиском источников, составлением библиографических списков и тематических подборок.', 'cta' => 'Задать вопрос'],
              ['icon' => 'school',        'title' => 'Учебные коллекции',      'body' => 'Рекомендуемая литература по учебным программам: учебники, методические пособия и статьи.', 'cta' => 'Каталог программ'],
              ['icon' => 'workspace_premium', 'title' => 'Личный кабинет',    'body' => 'Управление займами, бронированием и персональными списками чтения.',                       'cta' => session('library.user') ? 'Открыть кабинет' : 'Войти'],
          ],
          'identity_brand' => 'Библиотека КазТБУ',
      ],
      'kk' => [
          'title'                    => 'Басты бет — КазТБУ Кітапханасы',
          'hero_kicker'              => 'Цифрлық куратор',
          'hero_h1'                  => 'Білімді ашыңыз,',
          'hero_h1_accent'           => 'дереккөздерді басқарыңыз.',
          'hero_lead'                => 'КазТБУ академиялық жинақтарына, ғылыми мұрағаттар мен цифрлық ресурстарға бірыңғай кіру нүктесі.',
          'search_placeholder'       => 'Каталог, авторлар, ӘЖЖ бойынша іздеу…',
          'search_cta'               => 'Іздеу',
          'trending'                 => 'Өзекті тақырыптар:',
          'hero_img_alt'             => 'КазТБУ кітапханасының оқу залы',
          'stats_archives_label'     => 'Мұрағат материалдары',
          'stats_archives_value'     => '120 000+',
          'stats_scholars_label'     => 'Белсенді оқырмандар',
          'stats_scholars_value'     => '8 400+',
          'collections_heading'      => 'Таңдаулы жинақтар',
          'collections_lead'         => 'КазТБУ негізгі пәндері бойынша тақырыптық іріктемелер',
          'collections_all_cta'      => 'Барлық жинақтар',
          'collection_featured_badge'   => 'Негізгі қор',
          'collection_featured_title'   => 'Академиялық ресурстар',
          'collection_featured_body'    => 'Университеттің профильді бағыттары: инженерия, экономика, құқық бойынша монографиялар, диссертациялар және мерзімді басылымдар.',
          'collection_featured_cta'     => 'Каталогты ашу',
          'collection_featured_img_alt' => 'КазТБУ кітапханасының оқу залы',
          'collection_tile1_title'   => 'Қолданбалы ғылымдар',
          'collection_tile1_body'    => 'Техникалық және инженерлік пәндер (ӘЖЖ 5)',
          'collection_tile1_img_alt' => 'AI семинары',
          'collection_tile2_title'   => 'Экономика және құқық',
          'collection_tile2_body'    => 'Құқықтық және экономикалық зерттеулер (ӘЖЖ 33)',
          'collection_tile2_img_alt' => 'Классикалық қор',
          'services_heading'         => 'Ғылыми сервистер',
          'services_lead'            => 'Академиялық жұмыс үшін құралдар мен ресурстар',
          'services'                 => [
              ['icon' => 'library_books', 'title' => 'Анықтамалық қызмет',  'body' => 'Дереккөздерді іздеуге, библиографиялық тізімдер мен тақырыптық іріктемелер жасауға көмек.', 'cta' => 'Сұрақ қою'],
              ['icon' => 'school',        'title' => 'Оқу жинақтары',       'body' => 'Оқу бағдарламалары бойынша ұсынылған әдебиет: оқулықтар, әдістемелік құралдар және мақалалар.', 'cta' => 'Бағдарламалар каталогы'],
              ['icon' => 'workspace_premium', 'title' => 'Жеке кабинет',   'body' => 'Қарыздарды, броньдарды және жеке оқу тізімдерін басқару.',                                        'cta' => session('library.user') ? 'Кабинетті ашу' : 'Кіру'],
          ],
          'identity_brand' => 'КазТБУ Кітапханасы',
      ],
      'en' => [
          'title'                    => 'Home — KazUTB Smart Library',
          'hero_kicker'              => 'Digital Curator',
          'hero_h1'                  => 'Discover Knowledge,',
          'hero_h1_accent'           => 'Curate Your Sources.',
          'hero_lead'                => 'A single gateway to KazUTB\'s academic collections, scholarly archives, and digital resources.',
          'search_placeholder'       => 'Search by title, author, UDC…',
          'search_cta'               => 'Search',
          'trending'                 => 'Trending topics:',
          'hero_img_alt'             => 'KazUTB Library Reading Room',
          'stats_archives_label'     => 'Archived Materials',
          'stats_archives_value'     => '120,000+',
          'stats_scholars_label'     => 'Active Readers',
          'stats_scholars_value'     => '8,400+',
          'collections_heading'      => 'Curated Collections',
          'collections_lead'         => 'Thematic selections across KazUTB\'s key disciplines',
          'collections_all_cta'      => 'All Collections',
          'collection_featured_badge'   => 'Core Collection',
          'collection_featured_title'   => 'Academic Resources',
          'collection_featured_body'    => 'Monographs, dissertations and periodicals across KazUTB\'s flagship disciplines: engineering, economics, and law.',
          'collection_featured_cta'     => 'Open Catalog',
          'collection_featured_img_alt' => 'KazUTB Library Reading Room',
          'collection_tile1_title'   => 'Applied Sciences',
          'collection_tile1_body'    => 'Technical & engineering disciplines (UDC 5)',
          'collection_tile1_img_alt' => 'AI Workshop',
          'collection_tile2_title'   => 'Economics &amp; Law',
          'collection_tile2_body'    => 'Legal and economic research (UDC 33)',
          'collection_tile2_img_alt' => 'Classics Collection',
          'services_heading'         => 'Scholarly Services',
          'services_lead'            => 'Tools and resources for academic work',
          'services'                 => [
              ['icon' => 'library_books',    'title' => 'Reference Services',  'body' => 'Expert help with source discovery, bibliography building, and subject-specific reading lists.', 'cta' => 'Ask a Question'],
              ['icon' => 'school',           'title' => 'Course Collections',  'body' => 'Recommended reading aligned with academic programmes: textbooks, guides, and articles.',       'cta' => 'Browse Programmes'],
              ['icon' => 'workspace_premium','title' => 'Member Workspace',    'body' => 'Manage your loans, reservations, and personal reading lists.',                                  'cta' => session('library.user') ? 'Open Workspace' : 'Sign In'],
          ],
          'identity_brand' => 'KazUTB Smart Library',
      ],
  ];

  $copy = $chrome[$lang];

  $topicLinks = [
      'ru' => [
          ['label' => 'Экономическая реформа',    'href' => $withLang('/catalog', ['udc' => '33'])],
          ['label' => 'Устойчивые технологии',    'href' => $withLang('/catalog', ['udc' => '62'])],
          ['label' => 'История Центральной Азии', 'href' => $withLang('/catalog', ['udc' => '008'])],
      ],
      'kk' => [
          ['label' => 'Экономикалық реформа',  'href' => $withLang('/catalog', ['udc' => '33'])],
          ['label' => 'Тұрақты технологиялар', 'href' => $withLang('/catalog', ['udc' => '62'])],
          ['label' => 'Орта Азия тарихы',      'href' => $withLang('/catalog', ['udc' => '008'])],
      ],
      'en' => [
          ['label' => 'Economic Reform',       'href' => $withLang('/catalog', ['udc' => '33'])],
          ['label' => 'Sustainable Tech',      'href' => $withLang('/catalog', ['udc' => '62'])],
          ['label' => 'Central Asian History', 'href' => $withLang('/catalog', ['udc' => '008'])],
      ],
  ];
  $topics = $topicLinks[$lang];

  $serviceHrefs = [
      $withLang('/contacts'),
      $withLang('/contacts'),
      session('library.user') ? $withLang('/dashboard') : $withLang('/login'),
  ];

  $serviceIconBg = [
      'bg-tertiary-fixed/30 text-on-tertiary-fixed-variant',
      'bg-primary-fixed/30 text-on-primary-fixed-variant',
      'bg-secondary-container/30 text-on-secondary-container',
  ];
@endphp

@section('title', $copy['title'])

@section('head')
<style>
.homepage-canonical__hero-img {
    position: absolute; inset: 0; width: 100%; height: 100%;
    object-fit: cover; mix-blend-mode: multiply; opacity: .9;
}
.homepage-canonical__bento-img {
    position: absolute; inset: 0; width: 100%; height: 100%;
    object-fit: cover; transition: transform .7s;
}
.homepage-canonical__bento-tile:hover .homepage-canonical__bento-img { transform: scale(1.05); }
.homepage-canonical__bento-tile:hover { transform: translateY(-4px); }
.homepage-canonical__stats-card {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}
</style>
@endsection

@section('content')
<div data-section="homepage-canonical-page">

  {{-- ── Hidden institutional identity mark (accessibility / test wiring) ── --}}
  <div id="hero-campus-mark" class="sr-only" aria-hidden="true">
    <img class="campus-mark__logo" src="/images/logo.png" alt="{{ $copy['identity_brand'] }}">
    <span>{{ $copy['identity_brand'] }}</span>
  </div>

  {{-- ══════════════════════════════════════════════════════════════
       SECTION 1 — HERO
       ════════════════════════════════════════════════════════════ --}}
  <section data-section="homepage-canonical-hero"
           class="w-full max-w-7xl mx-auto px-6 md:px-12 py-16 md:py-24 mb-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

      {{-- Left column: copy + search --}}
      <div class="lg:col-span-7 flex flex-col items-start justify-center">

        <span data-test-id="homepage-canonical-kicker"
              class="text-secondary font-semibold text-sm tracking-widest uppercase mb-6 font-label">
          {{ $copy['hero_kicker'] }}
        </span>

        <h1 class="text-[3.5rem] leading-[1.1] font-display font-medium text-primary mb-8 pr-4 -ml-1">
          {{ $copy['hero_h1'] }}<br>
          <span class="italic text-primary/80">{{ $copy['hero_h1_accent'] }}</span>
        </h1>

        <p class="text-on-surface-variant max-w-2xl mb-12 leading-relaxed font-body">
          {{ $copy['hero_lead'] }}
        </p>

        {{-- Search widget --}}
        <form id="heroSearch"
              data-test-id="homepage-canonical-search"
              class="hero-search-bar w-full max-w-xl bg-surface-container-highest p-2 rounded-xl flex items-center shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-b border-outline-variant/20 focus-within:border-secondary transition-colors"
              action="{{ $withLang('/catalog') }}"
              method="get">
          <span class="material-symbols-outlined text-outline ml-4 mr-2">search</span>
          <input class="w-full bg-transparent border-none focus:ring-0 text-on-surface placeholder:text-outline font-body text-base py-3"
                 type="text"
                 name="q"
                 placeholder="{{ $copy['search_placeholder'] }}">
          <button type="submit"
                  class="bg-gradient-to-r from-primary to-primary-container text-on-primary px-6 py-3 rounded-lg font-medium text-sm hover:opacity-90 transition-opacity ml-2 whitespace-nowrap">
            {{ $copy['search_cta'] }}
          </button>
        </form>

        {{-- Topic quick-links (KazUTB UDC-first discovery truth) --}}
        <div id="hero-quick-links" class="mt-6 flex flex-wrap gap-3 items-center">
          <span class="text-sm text-on-surface-variant">{{ $copy['trending'] }}</span>
          @foreach ($topics as $link)
            <a href="{{ $link['href'] }}"
               class="text-sm text-secondary hover:underline transition-colors">
              {{ $link['label'] }}
            </a>
          @endforeach
        </div>

      </div>{{-- /left --}}

      {{-- Right column: image + stats card --}}
      <div class="lg:col-span-5 relative h-[500px] w-full rounded-2xl overflow-hidden bg-surface-container-low">
        <img src="/images/news/campus-library.jpg"
             alt="{{ $copy['hero_img_alt'] }}"
             class="homepage-canonical__hero-img absolute inset-0 w-full h-full object-cover opacity-90">
        <div class="absolute inset-0 bg-gradient-to-t from-primary-container/80 to-transparent"></div>

        {{-- Floating stats card --}}
        <div data-test-id="homepage-canonical-hero-stats"
             class="homepage-canonical__stats-card absolute bottom-8 left-8 right-8 bg-surface-container-lowest/90 p-6 rounded-xl shadow-[0_20px_40px_rgb(0,0,0,0.08)]">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-xs text-on-surface-variant uppercase tracking-wider mb-1">
                {{ $copy['stats_archives_label'] }}
              </p>
              <p class="text-xl font-bold text-primary">{{ $copy['stats_archives_value'] }}</p>
            </div>
            <div class="w-px h-12 bg-outline-variant/30"></div>
            <div>
              <p class="text-xs text-on-surface-variant uppercase tracking-wider mb-1">
                {{ $copy['stats_scholars_label'] }}
              </p>
              <p class="text-xl font-bold text-primary">{{ $copy['stats_scholars_value'] }}</p>
            </div>
          </div>
        </div>
      </div>{{-- /right --}}

    </div>
  </section>

  {{-- ══════════════════════════════════════════════════════════════
       SECTION 2 — CURATED COLLECTIONS BENTO
       ════════════════════════════════════════════════════════════ --}}
  <section data-section="homepage-canonical-collections"
           class="w-full max-w-7xl mx-auto px-6 md:px-12 py-16 mb-20">

    <div class="flex justify-between items-end mb-12">
      <div>
        <h2 data-test-id="homepage-canonical-collections-heading"
            class="text-[1.75rem] font-headline text-primary mb-2">
          {{ $copy['collections_heading'] }}
        </h2>
        <p class="text-on-surface-variant">{{ $copy['collections_lead'] }}</p>
      </div>
      <a href="{{ $withLang('/discover') }}"
         class="hidden md:flex items-center text-secondary font-medium hover:text-secondary-container transition-colors group">
        {{ $copy['collections_all_cta'] }}
        <span class="material-symbols-outlined ml-2 group-hover:translate-x-1 transition-transform">arrow_forward</span>
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-auto md:h-[600px]">

      {{-- Large featured tile --}}
      <a href="{{ $withLang('/discover') }}"
         data-test-id="homepage-canonical-bento-featured"
         class="homepage-canonical__bento-tile md:col-span-2 md:row-span-2 relative rounded-2xl overflow-hidden group bg-surface-container-low transition-transform duration-500 block">
        <img src="/images/news/campus-library.jpg"
             alt="{{ $copy['collection_featured_img_alt'] }}"
             class="homepage-canonical__bento-img absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/40 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-8 w-full">
          <span class="inline-block px-3 py-1 bg-secondary/20 backdrop-blur-md text-on-secondary rounded-full text-xs font-semibold tracking-wide uppercase mb-4 border border-secondary/30">
            {{ $copy['collection_featured_badge'] }}
          </span>
          <h3 class="text-[1.75rem] font-headline text-surface-container-lowest mb-3">
            {{ $copy['collection_featured_title'] }}
          </h3>
          <p class="text-surface-variant text-sm max-w-md mb-6 opacity-90 line-clamp-3">
            {{ $copy['collection_featured_body'] }}
          </p>
          <span class="inline-block bg-surface-container-lowest text-primary px-5 py-2.5 rounded-lg font-medium text-sm hover:bg-surface-variant transition-colors">
            {{ $copy['collection_featured_cta'] }}
          </span>
        </div>
      </a>

      {{-- Small tile 1 — Applied Sciences / UDC 5 --}}
      <a href="{{ $withLang('/catalog', ['udc' => '5']) }}"
         data-test-id="homepage-canonical-bento-tile-1"
         class="homepage-canonical__bento-tile relative rounded-2xl overflow-hidden group bg-surface-container-low transition-transform duration-500 h-[288px] md:h-auto block">
        <img src="/images/news/ai-workshop.jpg"
             alt="{{ $copy['collection_tile1_img_alt'] }}"
             class="homepage-canonical__bento-img absolute inset-0 w-full h-full object-cover opacity-80 transition-transform duration-700 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-6 w-full">
          <h4 class="font-headline text-surface-container-lowest mb-2 text-lg">
            {{ $copy['collection_tile1_title'] }}
          </h4>
          <p class="text-surface-variant text-sm line-clamp-2">{{ $copy['collection_tile1_body'] }}</p>
        </div>
      </a>

      {{-- Small tile 2 — Economics & Law / UDC 33 --}}
      <a href="{{ $withLang('/catalog', ['udc' => '33']) }}"
         data-test-id="homepage-canonical-bento-tile-2"
         class="homepage-canonical__bento-tile relative rounded-2xl overflow-hidden group bg-surface-container-low transition-transform duration-500 h-[288px] md:h-auto block">
        <img src="/images/news/classics-event.jpg"
             alt="{{ $copy['collection_tile2_img_alt'] }}"
             class="homepage-canonical__bento-img absolute inset-0 w-full h-full object-cover opacity-80 transition-transform duration-700 group-hover:scale-105">
        <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-6 w-full">
          <h4 class="font-headline text-surface-container-lowest mb-2 text-lg">
            {{ $copy['collection_tile2_title'] }}
          </h4>
          <p class="text-surface-variant text-sm line-clamp-2">{{ $copy['collection_tile2_body'] }}</p>
        </div>
      </a>

    </div>
  </section>

  {{-- ══════════════════════════════════════════════════════════════
       SECTION 3 — SCHOLARLY SERVICES
       ════════════════════════════════════════════════════════════ --}}
  <section data-section="homepage-canonical-services"
           class="w-full bg-surface-container-low py-24 mb-20">
    <div class="max-w-7xl mx-auto px-6 md:px-12">

      <div class="text-center mb-16 max-w-2xl mx-auto">
        <h2 data-test-id="homepage-canonical-services-heading"
            class="text-[1.75rem] font-headline text-primary mb-4">
          {{ $copy['services_heading'] }}
        </h2>
        <p class="text-on-surface-variant">{{ $copy['services_lead'] }}</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach ($copy['services'] as $i => $service)
          <div class="bg-surface-container-lowest p-8 rounded-2xl shadow-[0_4px_24px_rgb(0,0,0,0.02)] hover:bg-surface-container-high transition-colors duration-300">
            <div class="w-12 h-12 {{ $serviceIconBg[$i] }} rounded-xl flex items-center justify-center mb-6">
              <span class="material-symbols-outlined">{{ $service['icon'] }}</span>
            </div>
            <h3 class="text-lg font-semibold text-primary mb-3">{{ $service['title'] }}</h3>
            <p class="text-on-surface-variant text-sm leading-relaxed mb-6">{{ $service['body'] }}</p>
            <a href="{{ $serviceHrefs[$i] }}"
               class="text-secondary font-medium text-sm flex items-center group">
              {{ $service['cta'] }}
              <span class="material-symbols-outlined text-[18px] ml-1 group-hover:translate-x-1 transition-transform">arrow_right_alt</span>
            </a>
          </div>
        @endforeach
      </div>

    </div>
  </section>

</div>{{-- /homepage-canonical-page --}}
@endsection
