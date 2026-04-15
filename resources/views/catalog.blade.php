@extends('layouts.public', ['activePage' => 'catalog'])

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

  $q = (string) request()->query('q', '');
  $language = (string) request()->query('language', 'all');
  $yearFrom = (string) request()->query('year_from', '1950');
  $yearTo = (string) request()->query('year_to', '2024');
  $availableOnly = request()->boolean('available_only');
  $physicalOnly = request()->boolean('physical_only');
  $institution = (string) request()->query('institution', '');
  $sort = (string) request()->query('sort', 'relevance');
  $imagePool = [
      '/images/news/default-library.jpg',
      '/images/news/classics-event.jpg',
      '/images/news/campus-library.jpg',
      '/images/news/author-visit.jpg',
  ];

  $copy = [
      'ru' => [
          'title' => 'Каталог книг — Digital Library',
          'heading' => 'Каталог книг',
          'search_placeholder' => 'Поиск по названию, автору, ISBN или теме...',
          'advanced' => 'Расширенный',
          'seed_summary' => 'Просмотр академических материалов университета.',
          'filters' => 'Фильтры',
          'material_type' => 'Тип материала',
          'publication_date' => 'Год издания',
          'language' => 'Язык',
          'collection' => 'Коллекция',
          'available_only' => 'Только доступные экземпляры',
          'physical_only' => 'Только с физическим фондом',
          'clear_filters' => 'Сбросить фильтры',
          'showing' => 'Показаны',
          'of' => 'из',
          'results_for' => 'результатов по запросу',
          'sort_by' => 'Сортировка',
          'sort_options' => [
              'relevance' => 'Релевантность',
              'title' => 'По названию',
              'year_desc' => 'Сначала новые',
              'year_asc' => 'Сначала старые',
          ],
          'types' => ['Книги и e-books', 'Научные журналы', 'Диссертации и работы'],
          'collections' => ['Инженерия и технологии (1,204)', 'Бизнес и экономика (892)', 'Гуманитарный фонд (432)', '+ Показать ещё 12'],
          'institution_options' => [
              '' => 'Все подразделения',
              'technology_library' => 'Технологическая библиотека',
              'economic_library' => 'Экономическая библиотека',
              'college_library' => 'Библиотека колледжа',
          ],
          'sample_items' => [
              [
                  'badge' => 'Электронный ресурс',
                  'badge_style' => 'bg-secondary-container text-on-secondary-container',
                  'title' => 'Advances in Computational Fluid Dynamics: A Multidisciplinary Approach',
                  'meta' => 'Dr. Almas Kurmanbayev · 2023 · Cambridge University Press',
                  'body' => 'Междисциплинарное исследование вычислительной гидродинамики и её инженерных применений.',
                  'primary_cta' => 'Читать онлайн',
                  'status' => 'Мгновенный доступ',
                  'status_style' => 'text-secondary',
                  'icon' => 'visibility',
                  'image' => '/images/news/default-library.jpg',
              ],
              [
                  'badge' => 'Печатный экземпляр',
                  'badge_style' => 'bg-surface-container-highest text-on-surface-variant',
                  'title' => 'Economic Transformations in Post-Soviet Kazakhstan',
                  'meta' => 'Saule Tleubayeva · 2021 · KazUTB Press',
                  'body' => 'Аналитический обзор структурных реформ и технологической адаптации в экономике Казахстана.',
                  'primary_cta' => 'Найти на полке',
                  'status' => 'Этаж 3, стеллаж B-12',
                  'status_style' => 'text-on-surface-variant',
                  'icon' => 'library_books',
                  'image' => '/images/news/classics-event.jpg',
              ],
              [
                  'badge' => 'Архив',
                  'badge_style' => 'bg-secondary-container text-on-secondary-container',
                  'title' => 'Historical Archives of Industrial Design in Central Asia',
                  'meta' => 'Various Contributors · 1985–1995 · Institutional Archive',
                  'body' => 'Цифровая коллекция чертежей и проектной документации по промышленному дизайну региона.',
                  'primary_cta' => 'Запросить просмотр',
                  'status' => 'Требуется специальный доступ',
                  'status_style' => 'text-error',
                  'icon' => 'history_edu',
                  'image' => '/images/news/campus-library.jpg',
              ],
          ],
          'ui' => [
              'electronic' => 'Электронный ресурс',
              'physical' => 'Печатный экземпляр',
              'archive' => 'Архив',
              'read' => 'Читать онлайн',
              'locate' => 'Найти на полке',
              'request' => 'Запросить просмотр',
              'cite' => 'Цитировать',
              'isbn' => 'ISBN',
              'udc' => 'УДК',
              'available' => 'Мгновенный доступ',
              'permission' => 'Требуется специальный доступ',
              'empty' => 'По выбранным фильтрам материалы не найдены.',
              'results_for' => 'результатов по запросу',
              'copies' => 'Экземпляры',
              'fallback_loaded' => 'Каталог загружен с сохранённой выдачей.',
          ],
      ],
      'kk' => [
          'title' => 'Кітаптар каталогы — Digital Library',
          'heading' => 'Институционалдық каталог',
          'search_placeholder' => 'Атауы, авторы, ISBN немесе тақырып бойынша іздеу...',
          'advanced' => 'Кеңейтілген',
          'seed_summary' => 'Университеттің академиялық материалдарын шолу.',
          'filters' => 'Сүзгілер',
          'material_type' => 'Материал түрі',
          'publication_date' => 'Жарияланған жылы',
          'language' => 'Тіл',
          'collection' => 'Коллекция',
          'available_only' => 'Тек қолжетімді даналар',
          'physical_only' => 'Тек физикалық қоры бар',
          'clear_filters' => 'Сүзгілерді тазарту',
          'showing' => 'Көрсетіліп жатыр',
          'of' => 'барлығы',
          'results_for' => 'нәтиже',
          'sort_by' => 'Сұрыптау',
          'sort_options' => [
              'relevance' => 'Өзектілік',
              'title' => 'Атауы бойынша',
              'year_desc' => 'Жаңа алдымен',
              'year_asc' => 'Ескі алдымен',
          ],
          'types' => ['Кітаптар мен e-books', 'Ғылыми журналдар', 'Диссертациялар мен жұмыстар'],
          'collections' => ['Инженерия және технология (1,204)', 'Бизнес және экономика (892)', 'Гуманитарлық қор (432)', '+ Тағы 12 бөлім'],
          'institution_options' => [
              '' => 'Барлық бөлімдер',
              'technology_library' => 'Технологиялық кітапхана',
              'economic_library' => 'Экономикалық кітапхана',
              'college_library' => 'Колледж кітапханасы',
          ],
          'sample_items' => [
              [
                  'badge' => 'Электрондық ресурс',
                  'badge_style' => 'bg-secondary-container text-on-secondary-container',
                  'title' => 'Advances in Computational Fluid Dynamics: A Multidisciplinary Approach',
                  'meta' => 'Dr. Almas Kurmanbayev · 2023 · Cambridge University Press',
                  'body' => 'Есептеу гидродинамикасы мен оның инженерлік қолданылуы туралы пәнаралық зерттеу.',
                  'primary_cta' => 'Онлайн оқу',
                  'status' => 'Бірден қолжетімді',
                  'status_style' => 'text-secondary',
                  'icon' => 'visibility',
                  'image' => '/images/news/default-library.jpg',
              ],
              [
                  'badge' => 'Баспа данасы',
                  'badge_style' => 'bg-surface-container-highest text-on-surface-variant',
                  'title' => 'Economic Transformations in Post-Soviet Kazakhstan',
                  'meta' => 'Saule Tleubayeva · 2021 · KazUTB Press',
                  'body' => 'Қазақстан экономикасындағы құрылымдық реформалар мен технологиялық бейімделуге арналған шолу.',
                  'primary_cta' => 'Сөреден табу',
                  'status' => '3-қабат, B-12 сөресі',
                  'status_style' => 'text-on-surface-variant',
                  'icon' => 'library_books',
                  'image' => '/images/news/classics-event.jpg',
              ],
              [
                  'badge' => 'Архив',
                  'badge_style' => 'bg-secondary-container text-on-secondary-container',
                  'title' => 'Historical Archives of Industrial Design in Central Asia',
                  'meta' => 'Various Contributors · 1985–1995 · Institutional Archive',
                  'body' => 'Өнеркәсіптік дизайн бойынша сызбалар мен жобалық құжаттаманың цифрлық коллекциясы.',
                  'primary_cta' => 'Қарауды сұрау',
                  'status' => 'Арнайы рұқсат қажет',
                  'status_style' => 'text-error',
                  'icon' => 'history_edu',
                  'image' => '/images/news/campus-library.jpg',
              ],
          ],
          'ui' => [
              'electronic' => 'Электрондық ресурс',
              'physical' => 'Баспа данасы',
              'archive' => 'Архив',
              'read' => 'Онлайн оқу',
              'locate' => 'Сөреден табу',
              'request' => 'Қарауды сұрау',
              'cite' => 'Дәйексөз',
              'isbn' => 'ISBN',
              'udc' => 'ӘОЖ',
              'available' => 'Бірден қолжетімді',
              'permission' => 'Арнайы рұқсат қажет',
              'empty' => 'Таңдалған сүзгілер бойынша материал табылмады.',
              'results_for' => 'нәтиже',
              'copies' => 'Даналар',
              'fallback_loaded' => 'Каталог сақталған нәтижелермен жүктелді.',
          ],
      ],
      'en' => [
          'title' => 'Catalog — Digital Library',
          'heading' => 'Institutional Catalog',
          'search_placeholder' => 'Search by title, author, ISBN, or subject...',
          'advanced' => 'Advanced',
          'seed_summary' => 'Viewing scholarly items across university collections.',
          'filters' => 'Filters',
          'material_type' => 'Material Type',
          'publication_date' => 'Publication Date',
          'language' => 'Language',
          'collection' => 'Collection',
          'available_only' => 'Available in library',
          'physical_only' => 'Physical holdings only',
          'clear_filters' => 'Clear filters',
          'showing' => 'Showing',
          'of' => 'of',
          'results_for' => 'results for',
          'sort_by' => 'Sort by',
          'sort_options' => [
              'relevance' => 'Relevance',
              'title' => 'Title',
              'year_desc' => 'Newest First',
              'year_asc' => 'Oldest First',
          ],
          'types' => ['Books & E-books', 'Academic Journals', 'Theses & Dissertations'],
          'collections' => ['Engineering & Tech (1,204)', 'Business & Economics (892)', 'Humanities (432)', '+ View 12 more'],
          'institution_options' => [
              '' => 'All divisions',
              'technology_library' => 'Technology Library',
              'economic_library' => 'Economics Library',
              'college_library' => 'College Library',
          ],
          'sample_items' => [
              [
                  'badge' => 'Electronic Resource',
                  'badge_style' => 'bg-secondary-container text-on-secondary-container',
                  'title' => 'Advances in Computational Fluid Dynamics: A Multidisciplinary Approach',
                  'meta' => 'Dr. Almas Kurmanbayev · 2023 · Cambridge University Press',
                  'body' => 'A multidisciplinary study of computational fluid dynamics and its engineering applications.',
                  'primary_cta' => 'Read Online',
                  'status' => 'Immediate Access',
                  'status_style' => 'text-secondary',
                  'icon' => 'visibility',
                  'image' => '/images/news/default-library.jpg',
              ],
              [
                  'badge' => 'Physical Copy',
                  'badge_style' => 'bg-surface-container-highest text-on-surface-variant',
                  'title' => 'Economic Transformations in Post-Soviet Kazakhstan',
                  'meta' => 'Saule Tleubayeva · 2021 · KazUTB Press',
                  'body' => 'An analytical review of structural reforms and technological adaptation in Kazakhstan’s economy.',
                  'primary_cta' => 'Locate on Shelf',
                  'status' => 'Floor 3, Stack B-12',
                  'status_style' => 'text-on-surface-variant',
                  'icon' => 'library_books',
                  'image' => '/images/news/classics-event.jpg',
              ],
              [
                  'badge' => 'Archive',
                  'badge_style' => 'bg-secondary-container text-on-secondary-container',
                  'title' => 'Historical Archives of Industrial Design in Central Asia',
                  'meta' => 'Various Contributors · 1985–1995 · Institutional Archive',
                  'body' => 'A digitized collection of industrial design drawings and project documentation.',
                  'primary_cta' => 'Request Viewing',
                  'status' => 'Special Permission Required',
                  'status_style' => 'text-error',
                  'icon' => 'history_edu',
                  'image' => '/images/news/campus-library.jpg',
              ],
          ],
          'ui' => [
              'electronic' => 'Electronic Resource',
              'physical' => 'Physical Copy',
              'archive' => 'Archive',
              'read' => 'Read Online',
              'locate' => 'Locate on Shelf',
              'request' => 'Request Viewing',
              'cite' => 'Cite Item',
              'isbn' => 'ISBN',
              'udc' => 'UDC',
              'available' => 'Immediate Access',
              'permission' => 'Special Permission Required',
              'empty' => 'No items match the selected filters.',
              'results_for' => 'results for',
              'copies' => 'Copies',
              'fallback_loaded' => 'Catalog loaded using the preserved result set.',
          ],
      ],
  ][$lang];

  $langSuffix = $lang === 'ru' ? '' : ('?lang=' . $lang);
  $initialCatalog = is_array($catalogBootstrap ?? null) ? $catalogBootstrap : ['data' => [], 'meta' => []];
  $initialResults = is_array($initialCatalog['data'] ?? null) ? $initialCatalog['data'] : [];
  $initialMeta = is_array($initialCatalog['meta'] ?? null) ? $initialCatalog['meta'] : [];
  $initialTotal = (int) ($initialMeta['total'] ?? count($initialResults));
  $initialPage = max((int) ($initialMeta['page'] ?? 1), 1);
  $initialPerPage = max((int) ($initialMeta['per_page'] ?? max(count($initialResults), 10)), 1);
  $initialFrom = $initialTotal > 0 ? (($initialPage - 1) * $initialPerPage) + 1 : 0;
  $initialTo = $initialTotal > 0 ? min($initialPage * $initialPerPage, $initialTotal) : 0;
  $initialQueryLabel = $q !== '' ? $q : strtoupper($language === '' ? 'all' : $language);
@endphp

@section('title', $copy['title'])
@section('body_class', 'bg-surface text-on-background min-h-screen flex flex-col')

@section('head')
<style>
  .catalog-export .font-newsreader { font-family: 'Newsreader', serif; }
  .catalog-export .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    vertical-align: middle;
  }
  .catalog-export .line-clamp-2 {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    overflow: hidden;
  }
  @media (max-width: 767px) {
    #catalog-filters {
      display: none;
    }
    #catalog-filters.open {
      display: block;
    }
  }
</style>
@endsection

@section('content')
<div class="catalog-export flex-grow w-full max-w-screen-2xl mx-auto px-4 md:px-8 pt-12 pb-24">
  <section class="mb-16">
    <div class="max-w-4xl">
      <h1 class="text-5xl font-newsreader font-medium text-primary mb-6 tracking-tight">{{ $copy['heading'] }}</h1>
      <div class="relative group">
        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
          <span class="material-symbols-outlined text-outline" data-icon="search">search</span>
        </div>
        <input id="catalog-search-input" value="{{ $q }}" class="w-full pl-14 pr-6 py-5 bg-surface-container-highest border-b border-outline-variant/20 focus:border-secondary focus:ring-0 text-lg font-body placeholder:text-on-surface-variant/50 transition-all" placeholder="{{ $copy['search_placeholder'] }}" type="text" />
        <div class="absolute inset-y-0 right-4 flex items-center gap-2">
          <button type="button" onclick="toggleFilters()" class="px-4 py-2 text-secondary font-semibold hover:bg-secondary-container rounded-lg transition-colors">{{ $copy['advanced'] }}</button>
        </div>
      </div>
      <p id="catalog-summary-text" class="mt-4 text-on-surface-variant text-sm font-label italic">{{ $copy['seed_summary'] }}</p>
    </div>
  </section>

  <button id="mobile-filter-toggle" type="button" onclick="toggleFilters()" class="md:hidden mb-6 inline-flex items-center gap-2 px-4 py-2 border border-outline-variant/30 rounded-lg text-sm font-semibold text-primary bg-white">
    <span class="material-symbols-outlined text-base">tune</span>
    <span>{{ $copy['filters'] }}</span>
    <span id="filter-count-badge" class="text-secondary">0</span>
  </button>

  <div class="flex flex-col md:flex-row gap-12">
    <aside id="catalog-filters" class="w-full md:w-64 flex-shrink-0 space-y-10">
      <div>
        <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface-variant mb-6">{{ $copy['material_type'] }}</h3>
        <ul class="space-y-3">
          @foreach($copy['types'] as $index => $type)
            <li class="flex items-center gap-3 cursor-pointer group">
              @if($index === 1)
                <span class="w-4 h-4 border-2 border-secondary bg-secondary rounded-sm flex items-center justify-center">
                  <span class="material-symbols-outlined text-[10px] text-white font-bold">check</span>
                </span>
                <span class="text-sm text-on-surface font-bold">{{ $type }}</span>
              @else
                <span class="w-4 h-4 border-2 border-outline-variant rounded-sm group-hover:border-secondary transition-colors"></span>
                <span class="text-sm text-on-surface font-medium">{{ $type }}</span>
              @endif
            </li>
          @endforeach
        </ul>
      </div>

      <div>
        <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface-variant mb-6">{{ $copy['publication_date'] }}</h3>
        <div class="space-y-4">
          <div class="h-1 bg-surface-container-high relative rounded-full">
            <div class="absolute inset-y-0 left-1/4 right-0 bg-secondary rounded-full"></div>
            <div class="absolute left-1/4 -top-1.5 w-4 h-4 bg-white border-2 border-secondary rounded-full shadow-sm"></div>
            <div class="absolute right-0 -top-1.5 w-4 h-4 bg-white border-2 border-secondary rounded-full shadow-sm"></div>
          </div>
          <div class="flex justify-between text-xs font-label text-on-surface-variant">
            <span>1950</span>
            <span class="font-bold text-on-surface">2024</span>
          </div>
          <div class="grid grid-cols-2 gap-2">
            <input id="year-from-input" class="w-full bg-white border border-outline-variant/30 px-3 py-2 text-sm rounded-lg" value="{{ $yearFrom }}" placeholder="1950" type="number" />
            <input id="year-to-input" class="w-full bg-white border border-outline-variant/30 px-3 py-2 text-sm rounded-lg" value="{{ $yearTo }}" placeholder="2024" type="number" />
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface-variant mb-6">{{ $copy['language'] }}</h3>
        <div id="language-chips" class="flex flex-wrap gap-2">
          <button type="button" data-lang="all" class="px-3 py-2 rounded-lg border text-sm font-medium {{ $language === 'all' ? 'bg-primary text-white border-primary' : 'border-outline-variant/30 bg-white text-on-surface' }}">ALL</button>
          <button type="button" data-lang="ru" class="px-3 py-2 rounded-lg border text-sm font-medium {{ $language === 'ru' ? 'bg-primary text-white border-primary' : 'border-outline-variant/30 bg-white text-on-surface' }}">RU</button>
          <button type="button" data-lang="kk" class="px-3 py-2 rounded-lg border text-sm font-medium {{ $language === 'kk' ? 'bg-primary text-white border-primary' : 'border-outline-variant/30 bg-white text-on-surface' }}">KK</button>
          <button type="button" data-lang="en" class="px-3 py-2 rounded-lg border text-sm font-medium {{ $language === 'en' ? 'bg-primary text-white border-primary' : 'border-outline-variant/30 bg-white text-on-surface' }}">EN</button>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-bold uppercase tracking-widest text-on-surface-variant mb-6">{{ $copy['collection'] }}</h3>
        <select id="institution-select" class="w-full bg-white border border-outline-variant/30 px-3 py-2 text-sm rounded-lg">
          @foreach($copy['institution_options'] as $value => $label)
            <option value="{{ $value }}" @selected($institution === $value)>{{ $label }}</option>
          @endforeach
        </select>
        <ul class="space-y-3 mt-4">
          @foreach($copy['collections'] as $collection)
            <li class="text-sm text-on-surface-variant hover:text-secondary cursor-pointer {{ str_contains($collection, '+') ? 'font-bold' : '' }}">{{ $collection }}</li>
          @endforeach
        </ul>
      </div>

      <div class="pt-6 border-t border-outline-variant/10 space-y-4">
        <label class="flex items-center gap-3 cursor-pointer">
          <input id="filter-available-only" @checked($availableOnly) class="w-5 h-5 rounded-md border-outline-variant text-secondary focus:ring-secondary/20" type="checkbox" />
          <span class="text-sm font-medium">{{ $copy['available_only'] }}</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
          <input id="filter-physical-only" @checked($physicalOnly) class="w-5 h-5 rounded-md border-outline-variant text-secondary focus:ring-secondary/20" type="checkbox" />
          <span class="text-sm font-medium">{{ $copy['physical_only'] }}</span>
        </label>
        <button id="clear-filters-btn" type="button" onclick="clearAllFilters()" class="w-full px-4 py-2 text-sm font-semibold border border-outline-variant/30 rounded-lg hover:bg-surface-container-low transition-colors">
          {{ $copy['clear_filters'] }}
        </button>
      </div>
    </aside>

    <div class="flex-grow">
      <div class="flex flex-col md:flex-row justify-between md:items-baseline gap-4 mb-12 border-b border-outline-variant/10 pb-4">
        <div id="catalog-results-count" class="text-on-surface-variant text-sm font-label">{{ $copy['showing'] }} <span class="text-on-surface font-bold">{{ $initialFrom }}-{{ $initialTo }}</span> {{ $copy['of'] }} <span class="font-bold">{{ $initialTotal }}</span> {{ $copy['results_for'] }} <span class="font-medium">“{{ $initialQueryLabel }}”</span></div>
        <div class="flex items-center gap-6">
          <span class="text-xs font-bold uppercase tracking-tighter text-on-surface-variant">{{ $copy['sort_by'] }}</span>
          <select id="sort-select" class="bg-transparent border-none text-sm font-bold text-on-surface focus:ring-0 py-0 pr-8 cursor-pointer">
            @foreach($copy['sort_options'] as $value => $label)
              <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div id="catalog-results-list" class="space-y-16">
        @forelse($initialResults as $index => $record)
          @php
            $title = $record['title']['display'] ?? 'Untitled';
            $author = $record['primaryAuthor'] ?? 'KazTBU Digital Library';
            $year = $record['publicationYear'] ?? '—';
            $publisher = $record['publisher']['name'] ?? 'Digital Library';
            $isbn = $record['isbn']['raw'] ?? '—';
            $udc = $record['udc']['raw'] ?? '—';
            $subtitle = $record['title']['subtitle'] ?? ($record['source'] ?? $title);
            $availableCopies = (int) ($record['copies']['available'] ?? 0);
            $totalCopies = (int) ($record['copies']['total'] ?? 0);
            $kind = $totalCopies > 0 ? ($availableCopies > 0 ? 'physical' : 'archive') : 'electronic';
            $badgeStyle = $kind === 'physical' ? 'bg-surface-container-highest text-on-surface-variant' : 'bg-secondary-container text-on-secondary-container';
            $badgeLabel = $kind === 'physical' ? $copy['ui']['physical'] : ($kind === 'archive' ? $copy['ui']['archive'] : $copy['ui']['electronic']);
            $primaryLabel = $kind === 'physical' ? $copy['ui']['locate'] : ($kind === 'archive' ? $copy['ui']['request'] : $copy['ui']['read']);
            $icon = $kind === 'physical' ? 'library_books' : ($kind === 'archive' ? 'history_edu' : 'visibility');
            $servicePoint = $record['availability']['locations'][0]['servicePoint']['name'] ?? null;
            $statusStyle = $kind === 'archive' ? 'text-error' : ($kind === 'electronic' ? 'text-secondary' : 'text-on-surface-variant');
            $status = $kind === 'archive' ? $copy['ui']['permission'] : ($servicePoint ?: $copy['ui']['available']);
            $detailHref = $isbn !== '—' ? $withLang('/book/' . rawurlencode($isbn)) : $withLang('/catalog');
          @endphp
          <article class="flex flex-col sm:flex-row gap-8 group catalog-item">
            <div class="w-full sm:w-32 h-44 flex-shrink-0 bg-surface-container-low overflow-hidden rounded shadow-sm group-hover:shadow-md transition-shadow">
              <img class="w-full h-full object-cover" src="{{ $imagePool[$index % count($imagePool)] }}" alt="{{ $title }}" />
            </div>
            <div class="flex-grow">
              <div class="flex justify-between items-start gap-4">
                <div>
                  <span class="inline-block px-2 py-0.5 {{ $badgeStyle }} text-[10px] font-bold uppercase tracking-wider rounded mb-2">{{ $badgeLabel }}</span>
                  <a href="{{ $detailHref }}" class="block text-2xl font-newsreader font-semibold text-primary mb-1 group-hover:text-secondary transition-colors cursor-pointer">{{ $title }}</a>
                  <p class="text-on-surface-variant font-medium mb-3">{{ $author }} · {{ $year }} · {{ $publisher }}</p>
                </div>
                <button type="button" class="material-symbols-outlined text-on-surface-variant hover:text-secondary cursor-pointer">bookmark</button>
              </div>
              <p class="text-on-surface-variant text-sm line-clamp-2 mb-4 max-w-2xl leading-relaxed">{{ $subtitle }}</p>
              <div class="flex flex-wrap gap-3 text-xs text-on-surface-variant mb-6">
                <span><strong>{{ $copy['ui']['isbn'] }}:</strong> {{ $isbn }}</span>
                <span><strong>{{ $copy['ui']['udc'] }}:</strong> {{ $udc !== '' ? $udc : '—' }}</span>
                <span><strong>{{ $copy['ui']['copies'] }}:</strong> {{ $availableCopies }} / {{ $totalCopies }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                <a href="{{ $detailHref }}" class="text-sm font-bold text-secondary flex items-center gap-2 group/btn">
                  <span class="material-symbols-outlined text-lg">{{ $icon }}</span>
                  <span>{{ $primaryLabel }}</span>
                </a>
                <button type="button" class="text-sm font-medium text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2" onclick="copyCitation(@js($title))">
                  <span class="material-symbols-outlined text-lg">description</span>
                  <span>{{ $copy['ui']['cite'] }}</span>
                </button>
                <div class="sm:ml-auto text-xs {{ $statusStyle }} font-bold flex items-center gap-1">
                  <span class="w-2 h-2 rounded-full {{ $statusStyle === 'text-secondary' ? 'bg-secondary' : ($statusStyle === 'text-error' ? 'bg-error' : 'bg-outline') }}"></span>
                  {{ $status }}
                </div>
              </div>
            </div>
          </article>
        @empty
          <p class="text-on-surface-variant text-sm">{{ $copy['ui']['empty'] }}</p>
        @endforelse
      </div>

      <div class="mt-24 pt-12 border-t border-outline-variant/10 flex justify-center">
        <nav class="flex items-center gap-2" aria-label="Catalog pagination">
          <button type="button" class="w-10 h-10 flex items-center justify-center rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">chevron_left</span>
          </button>
          <button type="button" class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary text-white font-bold">1</button>
          <button type="button" class="w-10 h-10 flex items-center justify-center rounded-lg text-on-surface font-medium hover:bg-surface-container-high">2</button>
          <button type="button" class="w-10 h-10 flex items-center justify-center rounded-lg text-on-surface font-medium hover:bg-surface-container-high">3</button>
          <span class="px-2 text-on-surface-variant">...</span>
          <button type="button" class="w-10 h-10 flex items-center justify-center rounded-lg text-on-surface font-medium hover:bg-surface-container-high">42</button>
          <button type="button" class="w-10 h-10 flex items-center justify-center rounded-lg text-on-surface-variant hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">chevron_right</span>
          </button>
        </nav>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  const API_ENDPOINT = '/api/v1/catalog-db';
  const LANG_SUFFIX = @json($langSuffix);
  const uiCopy = @json($copy['ui']);
  const SORT_API_MAP = {
    relevance: 'popular',
    title: 'title',
    year_desc: 'newest',
    year_asc: 'newest',
    popular: 'popular',
    newest: 'newest',
    author: 'author'
  };

  function toggleFilters() {
    const panel = document.getElementById('catalog-filters');
    panel?.classList.toggle('open');
  }

  function updateFilterBadge() {
    let active = 0;
    if (document.getElementById('filter-available-only')?.checked) active++;
    if (document.getElementById('filter-physical-only')?.checked) active++;
    if ((document.getElementById('institution-select')?.value || '') !== '') active++;
    if ((document.getElementById('year-from-input')?.value || '') !== '1950') active++;
    if ((document.getElementById('year-to-input')?.value || '') !== '2024') active++;
    if ((window.catalogState?.language || 'all') !== 'all') active++;

    const badge = document.getElementById('filter-count-badge');
    if (badge) badge.textContent = String(active);
  }

  function clearAllFilters() {
    window.catalogState = {
      q: '',
      language: 'all',
      sort: 'relevance',
      yearFrom: '1950',
      yearTo: '2024',
      availableOnly: false,
      physicalOnly: false,
      institution: ''
    };

    document.getElementById('catalog-search-input').value = '';
    document.getElementById('year-from-input').value = '1950';
    document.getElementById('year-to-input').value = '2024';
    document.getElementById('filter-available-only').checked = false;
    document.getElementById('filter-physical-only').checked = false;
    document.getElementById('institution-select').value = '';
    document.getElementById('sort-select').value = 'relevance';

    document.querySelectorAll('#language-chips button').forEach((button) => {
      button.className = button.dataset.lang === 'all'
        ? 'px-3 py-2 rounded-lg border text-sm font-medium bg-primary text-white border-primary'
        : 'px-3 py-2 rounded-lg border text-sm font-medium border-outline-variant/30 bg-white text-on-surface';
    });

    updateFilterBadge();
    loadCatalog();
  }

  function copyCitation(title) {
    navigator.clipboard?.writeText(title).catch(() => {});
  }

  function escapeHtml(value) {
    return String(value ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function detailHref(isbn) {
    if (!isbn || isbn === '—') {
      return '/catalog' + LANG_SUFFIX;
    }
    return '/book/' + encodeURIComponent(isbn) + LANG_SUFFIX;
  }

  function buildStatus(item, kind) {
    const location = item?.availability?.locations?.[0];
    if (kind === 'archive') return uiCopy.permission;
    if (kind === 'physical') {
      const servicePoint = location?.servicePoint?.name || 'Library';
      return servicePoint;
    }
    return uiCopy.available;
  }

  function buildCard(item, index) {
    const imagePool = [
      '/images/news/default-library.jpg',
      '/images/news/classics-event.jpg',
      '/images/news/campus-library.jpg',
      '/images/news/author-visit.jpg'
    ];

    const title = item?.title?.display || 'Untitled';
    const author = item?.primaryAuthor || 'KazUTB Library';
    const year = item?.publicationYear || '—';
    const publisher = item?.publisher?.name || 'Digital Library';
    const isbn = item?.isbn?.raw || '—';
    const udc = item?.udc?.raw || '—';
    const copies = item?.copies?.available ?? 0;
    const total = item?.copies?.total ?? 0;
    const typeCycle = ['electronic', 'physical', 'archive'];
    const kind = typeCycle[index % typeCycle.length];
    const badgeClass = kind === 'physical' ? 'bg-surface-container-highest text-on-surface-variant' : 'bg-secondary-container text-on-secondary-container';
    const primaryLabel = kind === 'physical' ? uiCopy.locate : (kind === 'archive' ? uiCopy.request : uiCopy.read);
    const primaryIcon = kind === 'physical' ? 'library_books' : (kind === 'archive' ? 'history_edu' : 'visibility');
    const badgeLabel = kind === 'physical' ? uiCopy.physical : (kind === 'archive' ? uiCopy.archive : uiCopy.electronic);
    const statusLabel = buildStatus(item, kind);
    const statusClass = kind === 'archive' ? 'text-error' : (kind === 'electronic' ? 'text-secondary' : 'text-on-surface-variant');
    const statusDot = kind === 'archive' ? 'bg-error' : (kind === 'electronic' ? 'bg-secondary' : 'bg-outline');
    const description = item?.title?.subtitle || item?.source || '';

    return `
      <article class="flex flex-col sm:flex-row gap-8 group catalog-item">
        <div class="w-full sm:w-32 h-44 flex-shrink-0 bg-surface-container-low overflow-hidden rounded shadow-sm group-hover:shadow-md transition-shadow">
          <img class="w-full h-full object-cover" src="${imagePool[index % imagePool.length]}" alt="${escapeHtml(title)}" />
        </div>
        <div class="flex-grow">
          <div class="flex justify-between items-start gap-4">
            <div>
              <span class="inline-block px-2 py-0.5 ${badgeClass} text-[10px] font-bold uppercase tracking-wider rounded mb-2">${escapeHtml(badgeLabel)}</span>
              <a href="${detailHref(isbn)}" class="block text-2xl font-newsreader font-semibold text-primary mb-1 group-hover:text-secondary transition-colors cursor-pointer">${escapeHtml(title)}</a>
              <p class="text-on-surface-variant font-medium mb-3">${escapeHtml(author)} · ${escapeHtml(year)} · ${escapeHtml(publisher)}</p>
            </div>
            <button type="button" class="material-symbols-outlined text-on-surface-variant hover:text-secondary cursor-pointer" aria-label="Bookmark">bookmark</button>
          </div>
          <p class="text-on-surface-variant text-sm line-clamp-2 mb-4 max-w-2xl leading-relaxed">${escapeHtml(description || title)}</p>
          <div class="flex flex-wrap gap-3 text-xs text-on-surface-variant mb-6">
            <span><strong>${uiCopy.isbn}:</strong> ${escapeHtml(isbn)}</span>
            <span><strong>${uiCopy.udc}:</strong> ${escapeHtml(udc)}</span>
            <span><strong>${escapeHtml(uiCopy.copies)}:</strong> ${escapeHtml(copies)} / ${escapeHtml(total)}</span>
          </div>
          <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
            <a href="${detailHref(isbn)}" class="text-sm font-bold text-secondary flex items-center gap-2 group/btn">
              <span class="material-symbols-outlined text-lg">${primaryIcon}</span>
              <span>${escapeHtml(primaryLabel)}</span>
            </a>
            <button type="button" class="text-sm font-medium text-on-surface-variant hover:text-primary transition-colors flex items-center gap-2" onclick="copyCitation(${JSON.stringify(title)})">
              <span class="material-symbols-outlined text-lg">description</span>
              <span>${escapeHtml(uiCopy.cite)}</span>
            </button>
            <div class="sm:ml-auto text-xs ${statusClass} font-bold flex items-center gap-1">
              <span class="w-2 h-2 rounded-full ${statusDot}"></span>
              ${escapeHtml(statusLabel)}
            </div>
          </div>
        </div>
      </article>
    `;
  }

  function syncLanguageButtons() {
    document.querySelectorAll('#language-chips button').forEach((button) => {
      const isActive = button.dataset.lang === window.catalogState.language;
      button.className = isActive
        ? 'px-3 py-2 rounded-lg border text-sm font-medium bg-primary text-white border-primary'
        : 'px-3 py-2 rounded-lg border text-sm font-medium border-outline-variant/30 bg-white text-on-surface';
    });
  }

  function syncUrl(params) {
    const base = new URL(window.location.href);
    base.search = params.toString();
    window.history.replaceState({}, '', base.toString());
  }

  async function loadCatalog() {
    const params = new URLSearchParams();
    if (window.catalogState.q) params.set('q', window.catalogState.q);
    if (window.catalogState.language && window.catalogState.language !== 'all') params.set('language', window.catalogState.language);
    if (window.catalogState.yearFrom) params.set('year_from', window.catalogState.yearFrom);
    if (window.catalogState.yearTo) params.set('year_to', window.catalogState.yearTo);
    if (window.catalogState.availableOnly) params.set('available_only', '1');
    if (window.catalogState.physicalOnly) params.set('physical_only', '1');
    if (window.catalogState.institution) params.set('institution', window.catalogState.institution);
    if (@json($lang) !== 'ru') params.set('lang', @json($lang));
    params.set('sort', SORT_API_MAP[window.catalogState.sort] || 'popular');
    params.set('limit', '10');

    const container = document.getElementById('catalog-results-list');
    const count = document.getElementById('catalog-results-count');
    const summary = document.getElementById('catalog-summary-text');

    try {
      const response = await fetch(`${API_ENDPOINT}?${params.toString()}`, { headers: { Accept: 'application/json' } });
      const payload = await response.json();

      if (!response.ok) {
        throw new Error(payload?.message || 'Catalog request failed');
      }

      const data = Array.isArray(payload?.data) ? payload.data : [];
      const meta = payload?.meta || {};

      if (window.catalogState.sort === 'year_asc') {
        data.sort((left, right) => (Number(left?.publicationYear || 0) - Number(right?.publicationYear || 0)));
      }

      if (container) {
        container.innerHTML = data.length
          ? data.map((item, index) => buildCard(item, index)).join('')
          : `<p class="text-on-surface-variant text-sm">${escapeHtml(uiCopy.empty)}</p>`;
      }

      const total = Number(meta.total || data.length || 0);
      const perPage = Number(meta.per_page || data.length || 0);
      const currentPage = Number(meta.page || 1);
      const fromValue = total > 0 ? ((currentPage - 1) * perPage) + 1 : 0;
      const toValue = total > 0 ? Math.min(currentPage * perPage, total) : 0;
      const queryLabel = window.catalogState.q || (document.querySelector('#language-chips button.bg-primary')?.textContent || 'Catalog');

      if (count) {
        count.innerHTML = `${@json($copy['showing'])} <span class="text-on-surface font-bold">${fromValue}-${toValue}</span> ${@json($copy['of'])} <span class="font-bold">${total}</span> ${uiCopy.results_for} <span class="font-medium">“${escapeHtml(queryLabel)}”</span>`;
      }

      if (summary) {
        summary.textContent = `${total.toLocaleString()} ${uiCopy.results_for}.`;
      }
    } catch (error) {
      console.error('Catalog load failed:', error);
      if (summary) {
        summary.textContent = uiCopy.fallback_loaded;
      }
    }

    updateFilterBadge();
    syncLanguageButtons();
    syncUrl(params);
  }

  window.catalogState = {
    q: @json($q),
    language: @json($language),
    sort: @json($sort),
    yearFrom: @json($yearFrom),
    yearTo: @json($yearTo),
    availableOnly: @json($availableOnly),
    physicalOnly: @json($physicalOnly),
    institution: @json($institution),
  };

  document.getElementById('catalog-search-input')?.addEventListener('change', (event) => {
    window.catalogState.q = event.target.value.trim();
    loadCatalog();
  });

  document.getElementById('year-from-input')?.addEventListener('change', (event) => {
    window.catalogState.yearFrom = event.target.value.trim();
    loadCatalog();
  });

  document.getElementById('year-to-input')?.addEventListener('change', (event) => {
    window.catalogState.yearTo = event.target.value.trim();
    loadCatalog();
  });

  document.getElementById('filter-available-only')?.addEventListener('change', (event) => {
    window.catalogState.availableOnly = event.target.checked;
    loadCatalog();
  });

  document.getElementById('filter-physical-only')?.addEventListener('change', (event) => {
    window.catalogState.physicalOnly = event.target.checked;
    loadCatalog();
  });

  document.getElementById('institution-select')?.addEventListener('change', (event) => {
    window.catalogState.institution = event.target.value;
    loadCatalog();
  });

  document.getElementById('sort-select')?.addEventListener('change', (event) => {
    window.catalogState.sort = event.target.value;
    loadCatalog();
  });

  document.querySelectorAll('#language-chips button').forEach((button) => {
    button.addEventListener('click', () => {
      window.catalogState.language = button.dataset.lang || 'all';
      loadCatalog();
    });
  });

  updateFilterBadge();
  syncLanguageButtons();
  loadCatalog();
</script>
@endsection
