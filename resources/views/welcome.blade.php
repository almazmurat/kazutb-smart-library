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
          'title' => 'KazUTB Smart Library | Главная',
          'brand' => 'KazUTB Smart Library',
          'hero_prefix' => 'Научное',
          'hero_accent' => 'академическое',
          'hero_suffix' => 'пространство',
          'lead' => 'Кураторский цифровой вход в исследовательскую экосистему КазТБУ, архивы и институциональную память университета.',
          'search_placeholder' => 'Поиск по коллекции по названию, автору или DOI...',
          'search_cta' => 'Искать в каталоге',
          'trending' => 'Популярные темы:',
          'cards' => [
              ['icon' => 'auto_stories', 'title' => 'Научные ресурсы', 'body' => 'Доступ к журналам, статьям и зарегистрированным технологическим материалам университета.', 'cta' => 'Открыть коллекцию', 'href' => $withLang('/resources'), 'tone' => 'light'],
              ['icon' => 'history_edu', 'title' => 'Архив', 'body' => 'Оцифрованные рукописи, университетские хроники и редкие региональные публикации.', 'cta' => 'Открыть архив', 'href' => $withLang('/discover'), 'tone' => 'soft'],
              ['icon' => 'workspace_premium', 'title' => 'Рабочее пространство', 'body' => 'Сохранение подборок, работа с библиографией и прямой вход в личный кабинет читателя.', 'cta' => session('library.user') ? 'Открыть кабинет' : 'Войти в пространство', 'href' => session('library.user') ? $withLang('/dashboard') : $withLang('/login'), 'tone' => 'dark'],
          ],
          'hours_title' => 'Часы работы',
          'hours_note' => 'В летний период график может меняться. Уточняйте режим в календаре университета.',
          'hours' => [
              ['name' => 'Главный читальный зал', 'label' => 'Тихая зона и основной фонд', 'time' => '08:00 — 21:00', 'days' => 'ПН — СБ', 'tone' => 'soft'],
              ['name' => 'Институциональный архив', 'label' => 'Спецколлекции и ограниченный доступ', 'time' => '09:00 — 17:00', 'days' => 'ПН — ПТ'],
              ['name' => 'Digital Innovation Lab', 'label' => 'Техподдержка и рабочие станции', 'time' => '08:00 — 20:00', 'days' => 'ЕЖЕДНЕВНО'],
          ],
          'news_title' => 'Новости и объявления',
          'news_all' => 'Все обновления',
          'news' => [
              ['tag' => 'Событие', 'date' => '12 марта 2024', 'title' => 'Симпозиум по устойчивому промышленному дизайну 2024', 'body' => 'КазТБУ объединяет экспертов для обсуждения циркулярной экономики и технологических практик региона.', 'href' => $withLang('/about'), 'tagTone' => 'secondary', 'image' => asset('images/news/author-visit.jpg')],
              ['tag' => 'Обновление', 'date' => '08 марта 2024', 'title' => 'Расширен доступ к коллекциям Scopus и Web of Science', 'body' => 'Новые подписки дают студентам и преподавателям больше академических источников через библиотечный портал.', 'href' => $withLang('/resources'), 'tagTone' => 'primary', 'image' => asset('images/news/ai-workshop.jpg')],
          ],
          'stats_title' => 'Пульс',
          'stats_accent' => 'инноваций',
          'stats_body' => 'Ключевые показатели растущего институционального репозитория и исследовательской сети.',
          'stats_cta' => 'Открыть сведения',
          'stats_href' => $withLang('/about'),
          'stats' => [
              ['value' => '42.8k', 'label' => 'Цифровые ресурсы', 'note' => 'Рост: +14% год к году'],
              ['value' => '1.2M', 'label' => 'Годовые просмотры', 'note' => 'Активное использование'],
              ['value' => '650+', 'label' => 'Патенты преподавателей', 'note' => 'Технологические работы'],
          ],
          'repo_kicker' => 'Институциональный репозиторий',
          'repo_title_prefix' => 'Наследие',
          'repo_title_accent' => 'технологического',
          'repo_title_suffix' => 'совершенства',
          'repo_body' => 'KazUTB Smart Library хранит и упорядочивает интеллектуальный результат университета, объединяя инженерные, бизнес- и технологические направления в одном маршруте.',
          'points' => [
              ['icon' => 'verified', 'title' => 'Проверенные источники', 'body' => 'Каждый материал проходит метаданные и библиотечную верификацию.'],
              ['icon' => 'public', 'title' => 'Глобальная интеграция', 'body' => 'Платформа связана с международными исследовательскими сетями и DOI-сервисами.'],
          ],
          'guides' => [
              ['title' => 'Начало работы', 'links' => [
                  ['label' => 'Регистрация читательского билета', 'href' => $withLang('/about')],
                  ['label' => 'Удалённый доступ через VPN', 'href' => $withLang('/resources')],
                  ['label' => 'Поиск по OPAC', 'href' => $withLang('/catalog')],
              ]],
              ['title' => 'Инструменты исследования', 'links' => [
                  ['label' => 'Гиды по стилям цитирования', 'href' => $withLang('/shortlist')],
                  ['label' => 'Антиплагиат и проверка', 'href' => $withLang('/resources')],
                  ['label' => 'Планы управления данными', 'href' => $withLang('/about')],
              ]],
              ['title' => 'Поддержка', 'links' => [
                  ['label' => 'Межбиблиотечный обмен', 'href' => $withLang('/contacts')],
                  ['label' => 'Передать дипломную работу', 'href' => $withLang('/contacts')],
                  ['label' => 'Техническая поддержка', 'href' => $withLang('/contacts')],
              ]],
          ],
          'contact_title' => 'Прямая связь',
          'contact_name' => 'Дежурный библиотекарь',
          'contact_body' => 'Чат доступен в часы работы главного зала.',
          'contact_cta' => 'Связаться',
      ],
      'kk' => [
          'title' => 'KazUTB Smart Library | Басты бет',
          'brand' => 'KazUTB Smart Library',
          'hero_prefix' => 'Ғылыми',
          'hero_accent' => 'академиялық',
          'hero_suffix' => 'кеңістік',
          'lead' => 'КазТБУ зерттеу экожүйесіне, архивтеріне және институционалдық жадына ашылатын цифрлық қақпа.',
          'search_placeholder' => 'Жинақтан атау, автор немесе DOI бойынша іздеу...',
          'search_cta' => 'Каталогтан іздеу',
          'trending' => 'Танымал тақырыптар:',
          'cards' => [
              ['icon' => 'auto_stories', 'title' => 'Ғылыми ресурстар', 'body' => 'Университет журналдарына, мақалаларына және технологиялық материалдарына қолжетімділік.', 'cta' => 'Коллекцияны ашу', 'href' => $withLang('/resources'), 'tone' => 'light'],
              ['icon' => 'history_edu', 'title' => 'Архив', 'body' => 'Цифрланған қолжазбалар, университет шежіресі және сирек аймақтық басылымдар.', 'cta' => 'Архивті ашу', 'href' => $withLang('/discover'), 'tone' => 'soft'],
              ['icon' => 'workspace_premium', 'title' => 'Жұмыс кеңістігі', 'body' => 'Іріктемелерді сақтау, библиографиямен жұмыс істеу және жеке кабинетке тікелей өту.', 'cta' => session('library.user') ? 'Кабинетті ашу' : 'Кеңістікке кіру', 'href' => session('library.user') ? $withLang('/dashboard') : $withLang('/login'), 'tone' => 'dark'],
          ],
          'hours_title' => 'Жұмыс уақыты',
          'hours_note' => 'Жазғы кезеңде кесте өзгеруі мүмкін. Университет күнтізбесін тексеріңіз.',
          'hours' => [
              ['name' => 'Негізгі оқу залы', 'label' => 'Тыныш оқу және негізгі қор', 'time' => '08:00 — 21:00', 'days' => 'ДС — СБ', 'tone' => 'soft'],
              ['name' => 'Институционалдық архив', 'label' => 'Арнайы қор және шектеулі қолжетімділік', 'time' => '09:00 — 17:00', 'days' => 'ДС — ЖМ'],
              ['name' => 'Digital Innovation Lab', 'label' => 'Техқолдау және терминалдар', 'time' => '08:00 — 20:00', 'days' => 'КҮН САЙЫН'],
          ],
          'news_title' => 'Жаңалықтар мен хабарландырулар',
          'news_all' => 'Барлық жаңарту',
          'news' => [
              ['tag' => 'Оқиға', 'date' => '12 наурыз 2024', 'title' => 'Тұрақты индустриялық дизайн симпозиумы 2024', 'body' => 'КазТБУ сарапшыларды біріктіріп, өңірдегі технологиялық және циркулярлық экономика болашағын талқылайды.', 'href' => $withLang('/about'), 'tagTone' => 'secondary', 'image' => asset('images/news/author-visit.jpg')],
              ['tag' => 'Жаңарту', 'date' => '08 наурыз 2024', 'title' => 'Scopus және Web of Science қорларына кеңейтілген қолжетімділік', 'body' => 'Жаңа жазылымдар студенттер мен оқытушыларға көбірек академиялық ресурс ұсынады.', 'href' => $withLang('/resources'), 'tagTone' => 'primary', 'image' => asset('images/news/ai-workshop.jpg')],
          ],
          'stats_title' => 'Инновация',
          'stats_accent' => 'ырғағы',
          'stats_body' => 'Өсіп келе жатқан институционалдық репозиторий мен зерттеу желісінің негізгі метрикалары.',
          'stats_cta' => 'Толығырақ',
          'stats_href' => $withLang('/about'),
          'stats' => [
              ['value' => '42.8k', 'label' => 'Цифрлық ресурстар', 'note' => 'Өсу: +14%'],
              ['value' => '1.2M', 'label' => 'Жылдық қаралым', 'note' => 'Белсенді қолдану'],
              ['value' => '650+', 'label' => 'Оқытушылар патенттері', 'note' => 'Технологиялық еңбектер'],
          ],
          'repo_kicker' => 'Институционалдық репозиторий',
          'repo_title_prefix' => 'Технологиялық',
          'repo_title_accent' => 'жетістіктің',
          'repo_title_suffix' => 'мұрасы',
          'repo_body' => 'KazUTB Smart Library университеттің зияткерлік нәтижелерін біріктіріп, инженерлік, бизнес және технология салаларын бір жүйеге келтіреді.',
          'points' => [
              ['icon' => 'verified', 'title' => 'Тексерілген дереккөздер', 'body' => 'Әрбір материал метадерек және кітапханалық тексеруден өтеді.'],
              ['icon' => 'public', 'title' => 'Жаһандық интеграция', 'body' => 'Платформа халықаралық зерттеу желілерімен және DOI қызметтерімен байланысты.'],
          ],
          'guides' => [
              ['title' => 'Бастапқы қадамдар', 'links' => [
                  ['label' => 'Оқырман билетін тіркеу', 'href' => $withLang('/about')],
                  ['label' => 'VPN арқылы қашықтан қолжетімділік', 'href' => $withLang('/resources')],
                  ['label' => 'OPAC арқылы іздеу', 'href' => $withLang('/catalog')],
              ]],
              ['title' => 'Зерттеу құралдары', 'links' => [
                  ['label' => 'Дәйексөз стильдері', 'href' => $withLang('/shortlist')],
                  ['label' => 'Антиплагиат тексеруі', 'href' => $withLang('/resources')],
                  ['label' => 'Деректерді басқару жоспары', 'href' => $withLang('/about')],
              ]],
              ['title' => 'Қолдау', 'links' => [
                  ['label' => 'Кітапханааралық алмасу', 'href' => $withLang('/contacts')],
                  ['label' => 'Диплом жұмысын тапсыру', 'href' => $withLang('/contacts')],
                  ['label' => 'Техникалық көмек', 'href' => $withLang('/contacts')],
              ]],
          ],
          'contact_title' => 'Тікелей байланыс',
          'contact_name' => 'Кезекші кітапханашы',
          'contact_body' => 'Чат негізгі оқу залының уақытында қолжетімді.',
          'contact_cta' => 'Байланысу',
      ],
      'en' => [
          'title' => 'KazUTB Smart Library | Home',
          'brand' => 'KazUTB Smart Library',
          'hero_prefix' => 'The',
          'hero_accent' => 'Scholarly',
          'hero_suffix' => 'Commons',
          'lead' => 'A curated digital gateway to the Kazakh University of Technology and Business research ecosystem, archives, and institutional memory.',
          'search_placeholder' => 'Search the collection by title, author, or DOI...',
          'search_cta' => 'Search the Catalog',
          'trending' => 'Trending Topics:',
          'cards' => [
              ['icon' => 'auto_stories', 'title' => 'Scholarly Resources', 'body' => 'Access journals, peer-reviewed papers, and technological materials registered by KazUTB faculty.', 'cta' => 'Explore collection', 'href' => $withLang('/resources'), 'tone' => 'light'],
              ['icon' => 'history_edu', 'title' => 'The Archive', 'body' => 'A historical repository with digitized manuscripts, university chronicles, and rare regional publications.', 'cta' => 'Open repository', 'href' => $withLang('/discover'), 'tone' => 'soft'],
              ['icon' => 'workspace_premium', 'title' => 'Member Workspace', 'body' => 'Manage citations, save bibliographies, and move directly into the reader account environment.', 'cta' => session('library.user') ? 'Open workspace' : 'Sign into workspace', 'href' => session('library.user') ? $withLang('/dashboard') : $withLang('/login'), 'tone' => 'dark'],
          ],
          'hours_title' => 'Operational Hours',
          'hours_note' => 'Summer hours may vary. Please check the institutional calendar for public holidays.',
          'hours' => [
              ['name' => 'Main Reading Room', 'label' => 'Quiet Study & General Collection', 'time' => '08:00 — 21:00', 'days' => 'MON — SAT', 'tone' => 'soft'],
              ['name' => 'Institutional Archive', 'label' => 'Restricted Access & Special Collections', 'time' => '09:00 — 17:00', 'days' => 'MON — FRI'],
              ['name' => 'Digital Innovation Lab', 'label' => 'Technical Support & Terminals', 'time' => '08:00 — 20:00', 'days' => 'DAILY'],
          ],
          'news_title' => 'News & Announcements',
          'news_all' => 'View all updates',
          'news' => [
              ['tag' => 'Event', 'date' => 'March 12, 2024', 'title' => '2024 International Symposium on Sustainable Industrial Design', 'body' => 'KazUTB hosts global experts to discuss the future of circular economy and industrial innovation across Central Asia.', 'href' => $withLang('/about'), 'tagTone' => 'secondary', 'image' => asset('images/news/author-visit.jpg')],
              ['tag' => 'System Update', 'date' => 'March 08, 2024', 'title' => 'Expanded Access to Scopus & Web of Science Collections', 'body' => 'New subscription tiers provide broader academic access for undergraduate and faculty research through the portal.', 'href' => $withLang('/resources'), 'tagTone' => 'primary', 'image' => asset('images/news/ai-workshop.jpg')],
          ],
          'stats_title' => 'The Pulse of',
          'stats_accent' => 'Innovation',
          'stats_body' => 'Real-time metrics from our growing institutional repository and research network.',
          'stats_cta' => 'Open annual overview',
          'stats_href' => $withLang('/about'),
          'stats' => [
              ['value' => '42.8k', 'label' => 'Digital Resources', 'note' => 'Growth: +14% YoY'],
              ['value' => '1.2M', 'label' => 'Annual Downloads', 'note' => 'Active impact globally'],
              ['value' => '650+', 'label' => 'Faculty Patents', 'note' => 'Registered technological works'],
          ],
          'repo_kicker' => 'Institutional Repository',
          'repo_title_prefix' => 'A Legacy of',
          'repo_title_accent' => 'Technological',
          'repo_title_suffix' => 'Excellence',
          'repo_body' => 'The KazUTB Smart Library serves as the custodian of the university’s intellectual output, connecting engineering, business, and technology disciplines in one academic route.',
          'points' => [
              ['icon' => 'verified', 'title' => 'Verified Sources', 'body' => 'Every document is metadata-validated for academic integrity.'],
              ['icon' => 'public', 'title' => 'Global Integration', 'body' => 'Seamlessly connected to international research networks and DOI registries.'],
          ],
          'guides' => [
              ['title' => 'Getting Started', 'links' => [
                  ['label' => 'Library Card Registration', 'href' => $withLang('/about')],
                  ['label' => 'VPN for Remote Access', 'href' => $withLang('/resources')],
                  ['label' => 'Searching the OPAC', 'href' => $withLang('/catalog')],
              ]],
              ['title' => 'Research Tools', 'links' => [
                  ['label' => 'Citation Style Guides', 'href' => $withLang('/shortlist')],
                  ['label' => 'Anti-Plagiarism Checks', 'href' => $withLang('/resources')],
                  ['label' => 'Data Management Plans', 'href' => $withLang('/about')],
              ]],
              ['title' => 'Support', 'links' => [
                  ['label' => 'Interlibrary Loans', 'href' => $withLang('/contacts')],
                  ['label' => 'Submit a Thesis', 'href' => $withLang('/contacts')],
                  ['label' => 'Technical Support Desk', 'href' => $withLang('/contacts')],
              ]],
          ],
          'contact_title' => 'Direct Contact',
          'contact_name' => 'Librarian-on-Duty',
          'contact_body' => 'Live support is available during the main reading room hours.',
          'contact_cta' => 'Start chat',
      ],
  ][$lang];

  $topicLinks = [
      ['label' => 'Economic Reform', 'href' => $withLang('/catalog', ['udc' => '33'])],
      ['label' => 'Sustainable Tech', 'href' => $withLang('/catalog', ['udc' => '62'])],
      ['label' => 'Central Asian History', 'href' => $withLang('/catalog', ['udc' => '008'])],
  ];
@endphp

@section('title', $copy['title'])
@section('body_class', 'bg-surface text-on-surface antialiased')

@section('head')
<style>
  .homepage-export a { text-decoration: none; }
  .stats-card {
      background: linear-gradient(135deg, #001f3f 0%, #000613 100%);
      position: relative;
      overflow: hidden;
  }
  .stats-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at center, rgba(0, 106, 106, 0.1) 0%, transparent 70%);
      pointer-events: none;
  }
</style>
@endsection

@section('content')
<div class="homepage-export">
  <section data-homepage-stitch-reset class="relative min-h-[716px] flex flex-col items-center justify-center px-6 pt-20 pb-32 overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-surface-container-low to-surface"></div>
    <div class="absolute top-0 right-0 w-1/2 h-full -z-10 opacity-10 pointer-events-none hidden md:block">
      <img alt="Minimalist library" class="w-full h-full object-cover" src="{{ asset('images/news/default-library.jpg') }}" />
    </div>

    <div class="max-w-4xl w-full text-center space-y-8">
      <div id="hero-campus-mark" class="sr-only" aria-hidden="true">
        <img src="{{ asset('logo.png') }}" alt="{{ $copy['brand'] }}" class="campus-mark__logo" loading="eager" decoding="async" />
        <span>{{ $copy['brand'] }}</span>
      </div>

      <h1 class="font-headline text-5xl md:text-7xl text-primary leading-tight tracking-tight">
        {{ $copy['hero_prefix'] }} <span class="serif-italic">{{ $copy['hero_accent'] }}</span> {{ $copy['hero_suffix'] }}
      </h1>

      <p class="font-body text-lg md:text-xl text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
        {{ $copy['lead'] }}
      </p>

      <div data-hero-search class="mt-12 w-full max-w-2xl mx-auto">
        <form id="heroSearch" action="{{ $withLang('/catalog') }}" method="get" class="hero-search-bar bg-surface-container-lowest rounded-xl p-2 flex flex-col sm:flex-row items-stretch sm:items-center shadow-sm group focus-within:ring-1 focus-within:ring-secondary/30 transition-all">
          @if($lang !== 'ru')
            <input type="hidden" name="lang" value="{{ $lang }}" />
          @endif
          <span class="material-symbols-outlined px-4 py-2 sm:py-0 text-outline" data-icon="search">search</span>
          <input class="w-full bg-transparent border-none focus:ring-0 font-body text-on-surface placeholder:text-outline/60 py-4" name="q" placeholder="{{ $copy['search_placeholder'] }}" type="search" aria-label="{{ $copy['search_placeholder'] }}" />
          <button class="bg-primary hover:bg-primary-container text-on-primary px-8 py-3 rounded-lg font-body font-medium transition-all ml-0 sm:ml-2 whitespace-nowrap" type="submit">
            {{ $copy['search_cta'] }}
          </button>
        </form>

        <div id="hero-quick-links" class="mt-4 flex flex-wrap justify-center gap-4">
          <span class="text-xs font-label text-outline uppercase tracking-widest">{{ $copy['trending'] }}</span>
          @foreach($topicLinks as $topic)
            <a class="text-xs font-label text-secondary hover:underline decoration-secondary/30" href="{{ $topic['href'] }}">{{ $topic['label'] }}</a>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section data-homepage-subjects class="max-w-screen-2xl mx-auto px-8 pb-32">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      @foreach($copy['cards'] as $card)
        <a href="{{ $card['href'] }}" class="group {{ $card['tone'] === 'light' ? 'bg-surface-container-lowest' : ($card['tone'] === 'soft' ? 'bg-surface-container-low' : 'bg-primary') }} p-10 rounded-xl transition-all hover:bg-surface-container-high cursor-pointer flex flex-col justify-between h-[400px] {{ $card['tone'] === 'dark' ? 'hover:opacity-95 text-on-primary' : '' }}">
          <div>
            <div class="w-12 h-12 rounded-full {{ $card['tone'] === 'light' ? 'bg-primary-container/10' : ($card['tone'] === 'soft' ? 'bg-secondary/10' : 'bg-on-primary/10') }} flex items-center justify-center mb-8">
              <span class="material-symbols-outlined {{ $card['tone'] === 'dark' ? 'text-on-primary' : ($card['tone'] === 'soft' ? 'text-secondary' : 'text-primary') }}" data-icon="{{ $card['icon'] }}">{{ $card['icon'] }}</span>
            </div>
            <h3 class="font-headline text-2xl {{ $card['tone'] === 'dark' ? 'text-on-primary' : 'text-primary' }} mb-4">{{ $card['title'] }}</h3>
            <p class="font-body {{ $card['tone'] === 'dark' ? 'text-on-primary/70' : 'text-on-surface-variant' }} leading-relaxed">{{ $card['body'] }}</p>
          </div>
          <div class="flex items-center {{ $card['tone'] === 'dark' ? 'text-on-primary' : 'text-secondary' }} font-medium group-hover:gap-2 transition-all">
            <span>{{ $card['cta'] }}</span>
            <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
          </div>
        </a>
      @endforeach
    </div>
  </section>

  <section class="max-w-screen-2xl mx-auto px-8 pb-32">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
      <div class="lg:col-span-5 space-y-8">
        <div class="flex items-center gap-3">
          <span class="material-symbols-outlined text-secondary" data-icon="schedule">schedule</span>
          <h2 class="font-headline text-3xl text-primary">{{ $copy['hours_title'] }}</h2>
        </div>
        <div class="bg-surface-container-lowest rounded-xl overflow-hidden border border-outline-variant/30">
          <div class="divide-y divide-outline-variant/20">
            @foreach($copy['hours'] as $hour)
              <div class="p-6 flex justify-between items-center {{ ($hour['tone'] ?? null) === 'soft' ? 'bg-surface-container-low/50' : '' }}">
                <div>
                  <h4 class="font-body font-bold text-primary">{{ $hour['name'] }}</h4>
                  <p class="text-xs text-on-surface-variant uppercase tracking-wider">{{ $hour['label'] }}</p>
                </div>
                <div class="text-right">
                  <p class="font-body text-secondary font-semibold">{{ $hour['time'] }}</p>
                  <p class="text-[10px] text-outline">{{ $hour['days'] }}</p>
                </div>
              </div>
            @endforeach
          </div>
          <div class="p-4 bg-primary/5 text-center">
            <p class="text-xs font-body italic text-primary/70">{{ $copy['hours_note'] }}</p>
          </div>
        </div>
      </div>

      <div class="lg:col-span-7 space-y-8">
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-secondary" data-icon="newspaper">newspaper</span>
            <h2 class="font-headline text-3xl text-primary">{{ $copy['news_title'] }}</h2>
          </div>
          <a class="text-sm font-label text-secondary hover:underline" href="{{ $withLang('/about') }}">{{ $copy['news_all'] }}</a>
        </div>

        <div class="space-y-6">
          @foreach($copy['news'] as $item)
            <a href="{{ $item['href'] }}" class="flex gap-6 group cursor-pointer">
              <div class="flex-shrink-0 w-24 h-24 rounded-lg bg-surface-container-high overflow-hidden">
                <img alt="{{ $item['title'] }}" class="w-full h-full object-cover transition-transform group-hover:scale-105" src="{{ $item['image'] }}" />
              </div>
              <div class="space-y-2">
                <div class="flex items-center gap-3 flex-wrap">
                  <span class="px-2 py-0.5 {{ $item['tagTone'] === 'secondary' ? 'bg-secondary/10 text-secondary' : 'bg-primary/10 text-primary' }} text-[10px] font-bold rounded uppercase">{{ $item['tag'] }}</span>
                  <span class="text-xs text-outline">{{ $item['date'] }}</span>
                </div>
                <h3 class="font-headline text-xl text-primary group-hover:text-secondary transition-colors">{{ $item['title'] }}</h3>
                <p class="font-body text-sm text-on-surface-variant line-clamp-2">{{ $item['body'] }}</p>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="max-w-screen-2xl mx-auto px-8 pb-32">
    <div class="stats-card rounded-2xl p-12 text-on-primary">
      <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-12 items-center">
        <div class="space-y-4">
          <h2 class="font-headline text-4xl text-white">{{ $copy['stats_title'] }} <br><span class="serif-italic text-secondary-fixed">{{ $copy['stats_accent'] }}</span></h2>
          <p class="font-body text-white/60 leading-relaxed">{{ $copy['stats_body'] }}</p>
          <div class="pt-4">
            <a href="{{ $copy['stats_href'] }}" class="inline-flex px-6 py-2 border border-white/20 hover:bg-white/10 rounded-full text-xs font-label transition-all">{{ $copy['stats_cta'] }}</a>
          </div>
        </div>

        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-8">
          @foreach($copy['stats'] as $stat)
            <div class="flex flex-col gap-2">
              <div class="text-5xl font-headline font-bold text-white tabular-nums tracking-tighter">{{ $stat['value'] }}</div>
              <div class="flex items-center gap-2">
                <div class="h-[2px] w-8 bg-secondary-fixed"></div>
                <span class="text-xs font-label uppercase tracking-widest text-white/50">{{ $stat['label'] }}</span>
              </div>
              <p class="text-[11px] text-white/40 mt-1">{{ $stat['note'] }}</p>
            </div>
          @endforeach
        </div>
      </div>

      <div class="absolute bottom-0 right-0 w-1/3 h-full opacity-20 pointer-events-none">
        <svg class="w-full h-full" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
          <path d="M44.7,-76.4C58.1,-69.2,69.2,-58.1,76.4,-44.7C83.5,-31.4,86.7,-15.7,85.6,-0.6C84.5,14.5,79.1,28.9,71.2,42.1C63.2,55.3,52.7,67.3,39.6,74.9C26.5,82.5,10.8,85.7,-4.1,82.8C-19,79.9,-33.1,70.9,-45.1,60.8C-57.1,50.7,-67,39.6,-73.4,26.5C-79.8,13.4,-82.7,-1.8,-79.8,-15.7C-76.9,-29.6,-68.2,-42.2,-57,-51C-45.7,-59.8,-31.9,-64.8,-18.2,-69.9C-4.5,-75,9.1,-80.2,24.1,-81.4C39.1,-82.6,55.5,-79.8,44.7,-76.4Z" fill="white" transform="translate(100 100)"></path>
        </svg>
      </div>
    </div>
  </section>

  <section class="max-w-screen-2xl mx-auto px-8 pb-32 flex flex-col md:flex-row items-center gap-20">
    <div class="w-full md:w-1/2">
      <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl">
        <img alt="Scientific journal" class="w-full h-full object-cover" src="{{ asset('images/news/campus-library.jpg') }}" />
      </div>
    </div>
    <div class="w-full md:w-1/2 space-y-6">
      <span class="text-xs font-label text-secondary uppercase tracking-[0.2em] font-bold">{{ $copy['repo_kicker'] }}</span>
      <h2 class="font-headline text-4xl text-primary">{{ $copy['repo_title_prefix'] }} <span class="serif-italic">{{ $copy['repo_title_accent'] }}</span> {{ $copy['repo_title_suffix'] }}</h2>
      <p class="font-body text-lg text-on-surface-variant leading-relaxed">{{ $copy['repo_body'] }}</p>
      <div class="pt-4 flex flex-col gap-4">
        @foreach($copy['points'] as $point)
          <div class="flex items-start gap-4">
            <span class="material-symbols-outlined text-secondary" data-icon="{{ $point['icon'] }}">{{ $point['icon'] }}</span>
            <div>
              <h4 class="font-bold text-primary font-body">{{ $point['title'] }}</h4>
              <p class="text-sm text-on-surface-variant">{{ $point['body'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="max-w-screen-2xl mx-auto px-8 pb-32 border-t border-outline-variant/20 pt-16">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
      @foreach($copy['guides'] as $group)
        <div class="space-y-4">
          <h5 class="font-label text-xs uppercase tracking-widest font-bold text-primary">{{ $group['title'] }}</h5>
          <ul class="space-y-2">
            @foreach($group['links'] as $link)
              <li><a class="text-sm text-on-surface-variant hover:text-secondary transition-colors" href="{{ $link['href'] }}">{{ $link['label'] }}</a></li>
            @endforeach
          </ul>
        </div>
      @endforeach

      <div class="space-y-4">
        <h5 class="font-label text-xs uppercase tracking-widest font-bold text-primary">{{ $copy['contact_title'] }}</h5>
        <div class="p-4 bg-surface-container-low rounded-lg border border-outline-variant/10">
          <p class="text-sm font-bold text-primary">{{ $copy['contact_name'] }}</p>
          <p class="text-xs text-on-surface-variant mt-1 mb-3">{{ $copy['contact_body'] }}</p>
          <a href="{{ $withLang('/contacts') }}" class="inline-flex w-full justify-center py-2 bg-secondary text-white text-xs font-bold rounded hover:opacity-90 transition-all">{{ $copy['contact_cta'] }}</a>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
