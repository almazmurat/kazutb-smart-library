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
          'title' => 'KazUTB Digital Library | Главная',
          'lead' => 'Единый цифровой вход в фонд КазТБУ: каталог, лицензированные ресурсы и академические сервисы в спокойном университетском интерфейсе.',
          'search_placeholder' => 'Поиск по названию, автору, ISBN или УДК...',
          'search_cta' => 'Открыть каталог',
          'trending' => 'Популярные темы:',
          'cards' => [
              ['icon' => 'auto_stories', 'title' => 'Научные ресурсы', 'body' => 'Лицензированные базы данных, журналы и электронные материалы для учёбы и исследований.', 'cta' => 'Перейти к ресурсам', 'href' => $withLang('/resources'), 'tone' => 'light'],
              ['icon' => 'history_edu', 'title' => 'Навигация по фонду', 'body' => 'Тематическая навигация по фонду, цифровым коллекциям и библиотечным маршрутам.', 'cta' => 'Открыть раздел', 'href' => $withLang('/discover'), 'tone' => 'soft'],
              ['icon' => 'workspace_premium', 'title' => 'Кабинет читателя', 'body' => 'Подборка литературы, сохранённые материалы и переход в личный кабинет.', 'cta' => session('library.user') ? 'Открыть кабинет' : 'Войти в кабинет', 'href' => session('library.user') ? $withLang('/account') : $withLang('/login'), 'tone' => 'dark'],
          ],
          'repo_kicker' => 'Университетская библиотека',
          'repo_title_prefix' => 'Академическая',
          'repo_title_accent' => 'цифровая',
          'repo_title_suffix' => 'среда',
          'repo_body' => 'Платформа объединяет каталог, доступ к электронным ресурсам и читательские сервисы в одном маршруте для студентов и преподавателей.',
          'points' => [
              ['icon' => 'verified', 'title' => 'Библиографическая точность', 'body' => 'Описание записей и навигация поддерживаются библиотечным контуром верификации.'],
              ['icon' => 'public', 'title' => 'Практический доступ', 'body' => 'Каталог, ресурсы и кабинет работают как единый университетский маршрут.'],
          ],
      ],
      'kk' => [
          'title' => 'KazUTB Digital Library | Басты бет',
          'lead' => 'КазТБУ қорының бірыңғай цифрлық кіру нүктесі: каталог, лицензиялық ресурстар және академиялық сервистер бір университеттік интерфейсте.',
          'search_placeholder' => 'Атауы, авторы, ISBN немесе ӘОЖ бойынша іздеу...',
          'search_cta' => 'Каталогты ашу',
          'trending' => 'Танымал тақырыптар:',
          'cards' => [
              ['icon' => 'auto_stories', 'title' => 'Ғылыми ресурстар', 'body' => 'Оқу мен зерттеуге арналған лицензиялық дерекқорлар, журналдар және электрондық материалдар.', 'cta' => 'Ресурстарды ашу', 'href' => $withLang('/resources'), 'tone' => 'light'],
              ['icon' => 'history_edu', 'title' => 'Қор навигациясы', 'body' => 'Қор, цифрлық коллекциялар және кітапханалық маршруттар бойынша тақырыптық бағдар.', 'cta' => 'Бөлімді ашу', 'href' => $withLang('/discover'), 'tone' => 'soft'],
              ['icon' => 'workspace_premium', 'title' => 'Оқырман кабинеті', 'body' => 'Әдебиеттер топтамасы, сақталған материалдар және жеке кабинетке өту.', 'cta' => session('library.user') ? 'Кабинетті ашу' : 'Кіру', 'href' => session('library.user') ? $withLang('/account') : $withLang('/login'), 'tone' => 'dark'],
          ],
          'repo_kicker' => 'Университет кітапханасы',
          'repo_title_prefix' => 'Академиялық',
          'repo_title_accent' => 'цифрлық',
          'repo_title_suffix' => 'орта',
          'repo_body' => 'Платформа каталогты, электрондық ресурстарға қолжетімділікті және оқырман сервистерін студенттер мен оқытушыларға бір маршрутқа біріктіреді.',
          'points' => [
              ['icon' => 'verified', 'title' => 'Библиографиялық дәлдік', 'body' => 'Жазбалар сипаттамасы мен навигациясы кітапханалық тексеру контурымен жүргізіледі.'],
              ['icon' => 'public', 'title' => 'Практикалық қолжетімділік', 'body' => 'Каталог, ресурстар және кабинет бірыңғай университеттік маршрут ретінде жұмыс істейді.'],
          ],
      ],
      'en' => [
          'title' => 'KazUTB Digital Library | Home',
          'lead' => 'A unified digital entry into the KazUTB holdings, licensed resources, and academic services in one calm university interface.',
          'search_placeholder' => 'Search by title, author, ISBN, or UDC...',
          'search_cta' => 'Search the Catalog',
          'trending' => 'Trending Topics:',
          'cards' => [
              ['icon' => 'auto_stories', 'title' => 'Research Resources', 'body' => 'Licensed databases, journals, and electronic materials for study and research.', 'cta' => 'Explore resources', 'href' => $withLang('/resources'), 'tone' => 'light'],
              ['icon' => 'history_edu', 'title' => 'Collection Navigation', 'body' => 'Subject routes, digital collections, and practical entry points into the holdings.', 'cta' => 'Open discover', 'href' => $withLang('/discover'), 'tone' => 'soft'],
              ['icon' => 'workspace_premium', 'title' => 'Reader Account', 'body' => 'Shortlists, saved materials, and a direct route into the personal account space.', 'cta' => session('library.user') ? 'Open account' : 'Sign in to workspace', 'href' => session('library.user') ? $withLang('/account') : $withLang('/login'), 'tone' => 'dark'],
          ],
          'repo_kicker' => 'University Library',
          'repo_title_prefix' => 'A Calm',
          'repo_title_accent' => 'Digital',
          'repo_title_suffix' => 'Environment',
          'repo_body' => 'The platform connects catalog access, electronic resources, and reader services in one route for students and faculty.',
          'points' => [
              ['icon' => 'verified', 'title' => 'Bibliographic accuracy', 'body' => 'Record descriptions and navigation are maintained through library verification workflows.'],
              ['icon' => 'public', 'title' => 'Practical access', 'body' => 'Catalog, resources, and account routes work together as one university path.'],
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
@section('body_class', 'bg-surface text-on-surface antialiased')

@section('head')
<style>
  .homepage-export a { text-decoration: none; }
</style>
@endsection

@section('content')
<div class="homepage-export">
  <section data-homepage-stitch-reset class="relative min-h-[716px] flex flex-col items-center justify-center px-6 pt-20 pb-32 overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-surface-container-low to-surface"></div>
    <div class="absolute top-0 right-0 w-1/2 h-full -z-10 opacity-10 pointer-events-none hidden md:block">
      <img alt="Minimalist library" class="w-full h-full object-cover" src="/images/news/default-library.jpg" />
    </div>
    <div class="max-w-4xl w-full text-center space-y-8">
      <h1 class="font-headline text-5xl md:text-7xl text-primary leading-tight tracking-tight">
        The <span class="serif-italic">Scholarly</span> Commons
      </h1>
      <p class="font-body text-lg md:text-xl text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
        {{ $copy['lead'] }}
      </p>

      <div data-hero-search class="mt-12 w-full max-w-2xl mx-auto">
        <form action="{{ $withLang('/catalog') }}" method="get" class="bg-surface-container-lowest rounded-xl p-2 flex flex-col sm:flex-row items-stretch sm:items-center shadow-sm group focus-within:ring-1 focus-within:ring-secondary/30 transition-all">
          @if($lang !== 'ru')
            <input type="hidden" name="lang" value="{{ $lang }}" />
          @endif
          <span class="material-symbols-outlined px-4 py-2 sm:py-0 text-outline" data-icon="search">search</span>
          <input class="w-full bg-transparent border-none focus:ring-0 font-body text-on-surface placeholder:text-outline/60 py-4" name="q" placeholder="{{ $copy['search_placeholder'] }}" type="search" aria-label="{{ $copy['search_placeholder'] }}" />
          <button class="bg-primary hover:bg-primary-container text-on-primary px-8 py-3 rounded-lg font-body font-medium transition-all ml-0 sm:ml-2 whitespace-nowrap" type="submit">
            {{ $copy['search_cta'] }}
          </button>
        </form>
        <div class="mt-4 flex flex-wrap justify-center gap-4">
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
            <p class="font-body {{ $card['tone'] === 'dark' ? 'text-on-primary/70' : 'text-on-surface-variant' }} leading-relaxed">
              {{ $card['body'] }}
            </p>
          </div>
          <div class="flex items-center {{ $card['tone'] === 'dark' ? 'text-on-primary' : 'text-secondary' }} font-medium group-hover:gap-2 transition-all">
            <span>{{ $card['cta'] }}</span>
            <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
          </div>
        </a>
      @endforeach
    </div>
  </section>

  <section class="max-w-screen-2xl mx-auto px-8 pb-32 flex flex-col md:flex-row items-center gap-20">
    <div class="w-full md:w-1/2">
      <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl">
        <img alt="Scientific journal" class="w-full h-full object-cover" src="/images/news/author-visit.jpg" />
      </div>
    </div>
    <div class="w-full md:w-1/2 space-y-6">
      <span class="text-xs font-label text-secondary uppercase tracking-[0.2em] font-bold">{{ $copy['repo_kicker'] }}</span>
      <h2 class="font-headline text-4xl text-primary">{{ $copy['repo_title_prefix'] }} <span class="serif-italic">{{ $copy['repo_title_accent'] }}</span> {{ $copy['repo_title_suffix'] }}</h2>
      <p class="font-body text-lg text-on-surface-variant leading-relaxed">
        {{ $copy['repo_body'] }}
      </p>
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
</div>
@endsection
