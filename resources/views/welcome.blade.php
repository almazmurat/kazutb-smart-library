@extends('layouts.public', ['activePage' => 'home'])

@php
  $lang = app()->getLocale();
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';

  $withLang = function (string $path, array $query = []) use ($lang): string {
      if ($lang !== 'ru' && ! array_key_exists('lang', $query)) {
          $query['lang'] = $lang;
      }

      $queryString = http_build_query(array_filter($query, static fn ($value) => $value !== null && $value !== ''));
      return $path . ($queryString !== '' ? ('?' . $queryString) : '');
  };

  $copy = [
      'ru' => [
          'title' => 'Digital Library — Главная',
          'eyebrow' => 'Единый академический доступ',
          'hero' => 'Библиотека КазТБУ для учёбы, поиска и академической работы.',
          'lead' => 'Цифровой вход в университетский фонд, электронные коллекции и научные ресурсы — всё в одном современном маршруте.',
          'identity_badge' => 'Библиотека КазТБУ',
          'identity_note' => 'Официальная библиотека университета: каталог, цифровые коллекции и сервисы для студентов и преподавателей.',
          'logo_alt' => 'Логотип КазТБУ',
          'campus_mark_eyebrow' => 'Официальный логотип',
          'campus_mark_title' => 'КазТБУ',
          'campus_mark_label' => 'Знак университета',
          'search_placeholder' => 'Искать книги, авторов, ISBN, УДК или ключевые слова',
          'search_cta' => 'Открыть каталог',
          'summary_kicker' => 'Платформа',
          'subject_eyebrow' => 'Система классификации',
          'subject_title' => 'Просмотр по темам',
            'hours_eyebrow' => 'Практическая информация',
            'hours_title' => 'Часы работы и локации',
            'hours_body' => 'Проверяйте ключевые библиотечные точки на сегодня: когда открыт основной сервис, где работает читальный маршрут и когда доступно бронирование.',
            'hours_today_label' => 'Сегодня',
            'hours_cta' => 'Смотреть все часы',
            'hours_visual_eyebrow' => 'На кампусе',
            'hours_visual_title' => 'Спокойная навигация по библиотечным точкам КазТБУ',
            'hours_visual_body' => 'Основные сервисы сгруппированы так, чтобы студент и преподаватель быстро понимали, куда идти за фондом, консультацией или бронированием.',
              'news_eyebrow' => 'Библиотечная жизнь',
              'news_title' => 'Новости библиотеки',
              'news_title_accent' => 'и академические события',
              'news_body' => 'Подборка заметных обновлений, открытых встреч и исследовательских маршрутов, которые делают библиотеку живой частью кампуса.',
              'news_cta' => 'Все новости',
              'workshops_title' => 'Семинары и события',
              'workshops_cta' => 'Все события',
              'news_filters' => ['Все', 'Анонсы', 'События'],
              'news_items' => [
                ['tag' => 'Анонсы', 'date' => '09.04.2026', 'title' => 'Визит автора Сырыма Бактыгереулы в библиотеку КазТБУ', 'body' => 'Открытая встреча с обсуждением современной казахской публицистики, университетского чтения и библиотечных маршрутов для студентов.'],
                ['tag' => 'События', 'date' => '08.04.2026', 'title' => 'Classics Caravan: curated week of classical literature', 'body' => 'Небольшой цикл чтений и выставок, объединяющий фонд классической литературы, рекомендательные списки и навигацию по каталогу.'],
              ],
              'workshops_items' => [
                ['title' => 'Research skills for first-year students', 'time' => '11:00-11:40', 'date' => '15 Apr 2026'],
                ['title' => 'Engineering research and learning with ACM', 'time' => '15:00-16:00', 'date' => '15 Apr 2026'],
                ['title' => 'Exploring AI tools for literature search', 'time' => '17:00-18:00', 'date' => '16 Apr 2026'],
                ['title' => 'Zotero: efficient reference management', 'time' => '12:00-13:00', 'date' => '16 Apr 2026'],
              ],
            'hours_rows' => [
              ['label' => 'Главная библиотека', 'hours' => '09:00-18:00', 'meta' => 'Основной читальный маршрут и выдача'],
              ['label' => 'Технологическая библиотека', 'hours' => '09:00-17:30', 'meta' => 'Профильный фонд и предметные консультации'],
              ['label' => 'Часы бронирования', 'hours' => '10:00-17:00', 'meta' => 'Подтверждение заявок и работа с подборками'],
            ],
          'summary_title' => 'Что доступно уже сейчас',
          'summary_points' => [
              'поиск по каталогу с доступностью и фильтрами',
              'кабинет читателя, бронирования и защищённый вход',
              'рабочая подборка для преподавателя и силлабуса',
              'лицензионные ресурсы и контролируемые цифровые материалы',
          ],
          'path_heading' => 'Основные маршруты',
          'path_copy' => 'Каждый вход ведёт в реальный библиотечный сценарий — без декоративного шума и лишнего маркетинга.',
          'collection_heading' => 'Как устроен фонд',
          'collection_copy' => 'Платформа чётко различает печатный фонд, локальные цифровые материалы и внешние лицензируемые ресурсы, чтобы читатель понимал, что можно взять, открыть или запросить.',
          'service_heading' => 'Маршруты по задачам',
          'service_copy' => 'Работайте по реальному сценарию: открыть каталог, перейти к базе данных, собрать список литературы или связаться с библиотекой.',
          'cta_title' => 'Начните с маршрута, который нужен сейчас',
          'cta_copy' => 'Для поиска по фонду откройте каталог. Для платформ и баз данных — раздел ресурсов. Для учебных подборок — раздел подборки.',
          'stats' => ['✓ единый каталог и цифровые коллекции', '✓ личный кабинет, бронирования и продления', '✓ безопасный институциональный вход'],
          'feature_cards' => [
              ['meta_left' => 'Университетские архивы', 'meta_right' => 'Внутренняя сеть', 'title' => 'Университетские архивы', 'body' => 'Доступ к диссертациям, локальным изданиям и специализированным академическим материалам.'],
              ['meta_left' => 'Цифровая коллекция', 'meta_right' => 'Удалённый доступ', 'title' => 'Цифровая коллекция', 'body' => 'Книги, журналы и исследовательские материалы доступны круглосуточно из единого библиотечного интерфейса.'],
              ['meta_left' => 'Глобальная сеть', 'meta_right' => 'Лицензия', 'title' => 'Глобальная сеть', 'body' => 'Подключение к подписным платформам, базам данных и проверенным внешним научным источникам.'],
          ],
          'subject_link' => 'Открыть полный каталог УДК',
          'subject_cards' => [
              ['title' => 'Технические науки', 'udc' => 'UDC 62'],
              ['title' => 'Право и политика', 'udc' => 'UDC 34'],
              ['title' => 'Естественные науки', 'udc' => 'UDC 5'],
              ['title' => 'Гуманитарные науки', 'udc' => 'UDC 008'],
              ['title' => 'Экономика', 'udc' => 'UDC 33'],
              ['title' => 'Справочные издания', 'udc' => 'UDC 030'],
          ],
          'quote' => '«Библиотека — сердце университета; наша задача — направлять знание к каждому студенту и исследователю.»',
          'quote_panel_eyebrow' => 'Институциональная память',
          'trust_title' => 'Казахский университет технологии и бизнеса',
          'trust_body' => 'Мы соединяем классическое каталогизирование и современную навигацию, чтобы студенты и исследователи свободно переходили между печатным фондом, цифровыми коллекциями и лицензируемыми ресурсами.',
          'trust_stats' => [['value' => '2003', 'label' => 'система Digital Library'], ['value' => '24/7', 'label' => 'доступ к электронным ресурсам']],
          'trust_actions' => ['catalog' => 'Каталог', 'resources' => 'Ресурсы', 'shortlist' => 'Подборка'],
      ],
      'kk' => [
          'title' => 'Digital Library — Басты бет',
          'eyebrow' => 'Бірыңғай академиялық қолжетімділік',
          'hero' => 'КазТБУ кітапханасы оқу, іздеу және академиялық жұмысқа арналған бірыңғай орта.',
          'lead' => 'Университет қоры, цифрлық коллекциялар және ғылыми ресурстар бір заманауи әрі жинақы маршрутқа біріктірілді.',
          'identity_badge' => 'КазТБУ кітапханасы',
          'identity_note' => 'Университеттің ресми кітапханасы: каталог, цифрлық коллекциялар және студенттер мен оқытушыларға арналған сервистер.',
          'logo_alt' => 'КазТБУ логотипі',
          'campus_mark_eyebrow' => 'Ресми логотип',
          'campus_mark_title' => 'КазТБУ',
          'campus_mark_label' => 'Университет белгісі',
          'search_placeholder' => 'Кітап, автор, ISBN, ӘОЖ немесе кілт сөз бойынша іздеу',
          'search_cta' => 'Каталогты ашу',
          'summary_kicker' => 'Платформа',
          'subject_eyebrow' => 'Классификация жүйесі',
          'subject_title' => 'Тақырыптар бойынша шолу',
            'hours_eyebrow' => 'Практикалық ақпарат',
            'hours_title' => 'Жұмыс уақыты мен локациялар',
            'hours_body' => 'Бүгінгі негізгі кітапханалық нүктелерді жылдам тексеріңіз: басты сервис қашан ашық, оқу маршруты қайда жұмыс істейді және броньдау уақыты қандай.',
            'hours_today_label' => 'Бүгін',
            'hours_cta' => 'Барлық уақытты көру',
            'hours_visual_eyebrow' => 'Кампус ішінде',
            'hours_visual_title' => 'КазТБУ кітапханалық нүктелері бойынша жинақы навигация',
            'hours_visual_body' => 'Негізгі сервистер студент пен оқытушы қор, кеңес немесе бронь үшін қайда бару керегін бірден түсінетіндей етіп топтастырылған.',
              'news_eyebrow' => 'Кітапхана өмірі',
              'news_title' => 'Кітапхана жаңалықтары',
              'news_title_accent' => 'және академиялық оқиғалар',
              'news_body' => 'Кітапхананы кампус өмірінің тірі бөлігіне айналдыратын маңызды жаңалықтар, ашық кездесулер және зерттеу маршруттары бір жерде жиналған.',
              'news_cta' => 'Барлық жаңалықтар',
              'workshops_title' => 'Семинарлар мен оқиғалар',
              'workshops_cta' => 'Барлық оқиғалар',
              'news_filters' => ['Барлығы', 'Анонстар', 'Оқиғалар'],
              'news_items' => [
                ['tag' => 'Анонстар', 'date' => '09.04.2026', 'title' => 'Сырым Бактыгереулының КазТБУ кітапханасына сапары', 'body' => 'Қазіргі қазақ публицистикасы, университеттік оқу және студенттерге арналған кітапхана маршруттары туралы ашық кездесу.'],
                ['tag' => 'Оқиғалар', 'date' => '08.04.2026', 'title' => 'Classics Caravan: classical literature week', 'body' => 'Классикалық әдебиет қоры, ұсыныс тізімдері және каталог навигациясын біріктіретін шағын оқу және көрме циклі.'],
              ],
              'workshops_items' => [
                ['title' => 'First-year students research skills', 'time' => '11:00-11:40', 'date' => '15 Apr 2026'],
                ['title' => 'Engineering research with ACM', 'time' => '15:00-16:00', 'date' => '15 Apr 2026'],
                ['title' => 'AI tools for literature search', 'time' => '17:00-18:00', 'date' => '16 Apr 2026'],
                ['title' => 'Zotero: reference management', 'time' => '12:00-13:00', 'date' => '16 Apr 2026'],
              ],
            'hours_rows' => [
              ['label' => 'Негізгі кітапхана', 'hours' => '09:00-18:00', 'meta' => 'Негізгі оқу маршруты және кітап беру'],
              ['label' => 'Технологиялық кітапхана', 'hours' => '09:00-17:30', 'meta' => 'Бейіндік қор және пәндік кеңестер'],
              ['label' => 'Броньдау уақыты', 'hours' => '10:00-17:00', 'meta' => 'Өтінімдерді растау және іріктемемен жұмыс'],
            ],
          'summary_title' => 'Қазірдің өзінде не қолжетімді',
          'summary_points' => [
              'қолжетімділік пен сүзгілері бар каталог іздеуі',
              'оқырман кабинеті, броньдар және қауіпсіз кіру',
              'оқытушыға арналған оқу іріктемесі мен силлабус жұмыс ағыны',
              'лицензиялық ресурстар мен бақыланатын цифрлық материалдар',
          ],
          'path_heading' => 'Негізгі маршруттар',
          'path_copy' => 'Әр кіру нүктесі артық безендірусіз шынайы кітапханалық сценарийге апарады.',
          'collection_heading' => 'Қор қалай ұйымдастырылған',
          'collection_copy' => 'Платформа баспа қорын, жергілікті цифрлық материалдарды және сыртқы лицензиялық ресурстарды анық бөліп көрсетеді, сондықтан оқырман не алуға, не ашуға немесе не сұратуға болатынын түсінеді.',
          'service_heading' => 'Міндетке сай маршруттар',
          'service_copy' => 'Нақты сценариймен жұмыс істеңіз: каталогты ашу, дерекқорға өту, әдебиет тізімін жинау немесе кітапханамен байланысу.',
          'cta_title' => 'Қазір қажет бағыттан бастаңыз',
          'cta_copy' => 'Қор бойынша іздеу үшін каталогты ашыңыз. Платформалар мен базалар үшін — ресурстар бөлімі. Оқу іріктемелері үшін — іріктеме бөлімі.',
          'stats' => ['✓ бірыңғай каталог және цифрлық коллекциялар', '✓ оқырман кабинеті, бронь және ұзарту', '✓ қауіпсіз институционалдық кіру'],
          'feature_cards' => [
              ['meta_left' => 'Университет архиві', 'meta_right' => 'Ішкі желі', 'title' => 'Университет архиві', 'body' => 'Диссертацияларға, жергілікті басылымдарға және арнайы академиялық материалдарға қолжетімділік.'],
              ['meta_left' => 'Цифрлық коллекция', 'meta_right' => 'Қашықтан қолжетімділік', 'title' => 'Цифрлық коллекция', 'body' => 'Кітаптар, журналдар және зерттеу материалдары бірыңғай кітапханалық интерфейсте тәулік бойы қолжетімді.'],
              ['meta_left' => 'Ғаламдық желі', 'meta_right' => 'Лицензия', 'title' => 'Ғаламдық желі', 'body' => 'Жазылым платформаларына, дерекқорларға және сенімді сыртқы ғылыми көздерге қосылу.'],
          ],
          'subject_link' => 'ӘОЖ толық каталогын ашу',
          'subject_cards' => [
              ['title' => 'Техникалық ғылымдар', 'udc' => 'UDC 62'],
              ['title' => 'Құқық және саясат', 'udc' => 'UDC 34'],
              ['title' => 'Жаратылыстану ғылымдары', 'udc' => 'UDC 5'],
              ['title' => 'Гуманитарлық ғылымдар', 'udc' => 'UDC 008'],
              ['title' => 'Экономика', 'udc' => 'UDC 33'],
              ['title' => 'Анықтамалық қор', 'udc' => 'UDC 030'],
          ],
          'quote' => '«Кітапхана — университеттің жүрегі; біздің міндетіміз — білімді әр студент пен зерттеушіге жеткізу.»',
          'quote_panel_eyebrow' => 'Институционалдық жад',
          'trust_title' => 'Қазақ технология және бизнес университеті',
          'trust_body' => 'Біз классикалық каталогтауды заманауи навигациямен біріктіріп, студенттер мен зерттеушілердің баспа қор, цифрлық коллекциялар және лицензиялық ресурстар арасында еркін қозғалуына жағдай жасаймыз.',
          'trust_stats' => [['value' => '2003', 'label' => 'Digital Library жүйесі'], ['value' => '24/7', 'label' => 'электрондық ресурстарға қолжетімділік']],
          'trust_actions' => ['catalog' => 'Каталог', 'resources' => 'Ресурстар', 'shortlist' => 'Іріктеме'],
      ],
      'en' => [
          'title' => 'Digital Library — Home',
          'eyebrow' => 'Unified academic access',
          'hero' => 'KazTBU Library for study, search, and academic work.',
          'lead' => 'A calm digital entry into the university holdings, electronic collections, and research resources — kept clear and practical.',
          'identity_badge' => 'KazTBU Library',
          'identity_note' => 'The university’s official library surface for catalog search, digital collections, and academic services.',
          'logo_alt' => 'KazTBU logo',
          'campus_mark_eyebrow' => 'Official logo',
          'campus_mark_title' => 'KazTBU',
          'campus_mark_label' => 'University mark',
          'search_placeholder' => 'Search books, authors, ISBN, UDC, or keywords',
          'search_cta' => 'Open catalog',
          'summary_kicker' => 'Platform',
          'subject_eyebrow' => 'Classification system',
          'subject_title' => 'Browse by subject',
            'hours_eyebrow' => 'Practical information',
            'hours_title' => 'See hours and locations',
            'hours_body' => 'Check the main library touchpoints for today: when the core service is open, where the reading route is active, and when booking support is available.',
            'hours_today_label' => 'Today',
            'hours_cta' => 'View all hours',
            'hours_visual_eyebrow' => 'Across campus',
            'hours_visual_title' => 'A calmer way to read the KazTBU library footprint',
            'hours_visual_body' => 'Core service points are grouped so students and faculty can quickly understand where to go for holdings, guidance, or booking support.',
              'news_eyebrow' => 'Library life',
              'news_title' => 'Library news',
              'news_title_accent' => 'and workshops',
              'news_body' => 'A curated stream of updates, open sessions, and research-facing activity that makes the library feel active rather than static.',
              'news_cta' => 'All news',
              'workshops_title' => 'Workshops and events',
              'workshops_cta' => 'All events',
              'news_filters' => ['All', 'Announcements', 'Events'],
              'news_items' => [
                ['tag' => 'Announcements', 'date' => '09 Apr 2026', 'title' => 'Author Syrym Baktygereuly visits the KazTBU Library', 'body' => 'An open conversation on contemporary Kazakh writing, campus reading culture, and how the library can support student discovery.'],
                ['tag' => 'Events', 'date' => '08 Apr 2026', 'title' => 'Classics Caravan: a celebration of classical literature', 'body' => 'A compact program of readings and themed displays connecting classical holdings, recommendation lists, and the catalog route.'],
              ],
              'workshops_items' => [
                ['title' => 'Research skills for first-year students', 'time' => '11:00-11:40', 'date' => '15 Apr 2026'],
                ['title' => 'Engineering research and learning with ACM', 'time' => '15:00-16:00', 'date' => '15 Apr 2026'],
                ['title' => 'Exploring AI tools for literature search', 'time' => '17:00-18:00', 'date' => '16 Apr 2026'],
                ['title' => 'Zotero: efficient reference management', 'time' => '12:00-13:00', 'date' => '16 Apr 2026'],
              ],
            'hours_rows' => [
              ['label' => 'Main Library', 'hours' => '09:00-18:00', 'meta' => 'Primary reading route and circulation support'],
              ['label' => 'Technology Library', 'hours' => '09:00-17:30', 'meta' => 'Discipline-focused holdings and guidance'],
              ['label' => 'Booking Hours', 'hours' => '10:00-17:00', 'meta' => 'Reservation confirmation and shortlist support'],
            ],
          'summary_title' => 'What is already available',
          'summary_points' => [
              'catalog search with live availability and filters',
              'reader account, reservations, and secure access',
              'a teaching shortlist for syllabus preparation',
              'licensed resources and controlled digital materials',
          ],
          'path_heading' => 'Core routes',
          'path_copy' => 'Every entry point leads into a real library workflow instead of brochure-style filler.',
          'collection_heading' => 'How the collection is structured',
          'collection_copy' => 'The platform clearly separates print holdings, local digital materials, and external licensed resources so readers know what can be borrowed, opened, or requested.',
          'service_heading' => 'Task-based routes',
          'service_copy' => 'Work by intent: open the catalog, move into a database, build a reading list, or contact the library team.',
          'cta_title' => 'Start with the route you need now',
          'cta_copy' => 'Open the catalog for holdings search, resources for research platforms, or shortlist for course-support work.',
          'stats' => ['✓ unified catalog and digital collections', '✓ reader account, reservations, and renewals', '✓ secure institutional sign-in'],
          'feature_cards' => [
              ['meta_left' => 'University Archives', 'meta_right' => 'Internal Network', 'title' => 'University Archives', 'body' => 'Access to theses, local publications, and specialized academic materials.'],
              ['meta_left' => 'Digital Collection', 'meta_right' => 'Remote Access', 'title' => 'Digital Collection', 'body' => 'Books, journals, and research materials remain available around the clock in one library surface.'],
              ['meta_left' => 'Global Network', 'meta_right' => 'Licensed', 'title' => 'Global Network', 'body' => 'Connection to subscribed platforms, research databases, and trusted external scholarly sources.'],
          ],
          'subject_link' => 'Open the full UDC catalog',
          'subject_cards' => [
              ['title' => 'Technical Sciences', 'udc' => 'UDC 62'],
              ['title' => 'Law & Politics', 'udc' => 'UDC 34'],
              ['title' => 'Natural Sciences', 'udc' => 'UDC 5'],
              ['title' => 'Humanities', 'udc' => 'UDC 008'],
              ['title' => 'Economics', 'udc' => 'UDC 33'],
              ['title' => 'Reference Works', 'udc' => 'UDC 030'],
          ],
          'quote' => '“The library is the heart of the university; our role is to move knowledge toward every student and researcher.”',
          'quote_panel_eyebrow' => 'Institutional memory',
          'trust_title' => 'Kazakh University of Technology and Business',
          'trust_body' => 'We connect classical cataloging with modern discovery so students and researchers can move naturally between print holdings, digital collections, and licensed resources.',
          'trust_stats' => [['value' => '2003', 'label' => 'Digital Library system'], ['value' => '24/7', 'label' => 'access to e-resources']],
          'trust_actions' => ['catalog' => 'Catalog', 'resources' => 'Resources', 'shortlist' => 'Shortlist'],
      ],
  ][$lang];

    $today = now();
    $weekdayMap = [
      'ru' => ['понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота', 'воскресенье'],
      'kk' => ['дүйсенбі', 'сейсенбі', 'сәрсенбі', 'бейсенбі', 'жұма', 'сенбі', 'жексенбі'],
      'en' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
    ];
    $monthMap = [
      'ru' => [1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'],
      'kk' => [1 => 'қаңтар', 'ақпан', 'наурыз', 'сәуір', 'мамыр', 'маусым', 'шілде', 'тамыз', 'қыркүйек', 'қазан', 'қараша', 'желтоқсан'],
      'en' => [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    ];
    $weekday = $weekdayMap[$lang][$today->dayOfWeekIso - 1] ?? $weekdayMap['en'][$today->dayOfWeekIso - 1];
    $month = $monthMap[$lang][$today->month] ?? $monthMap['en'][$today->month];
    $todayLine = match ($lang) {
      'ru' => $copy['hours_today_label'] . ' · ' . $weekday . ', ' . $today->day . ' ' . $month,
      'kk' => $copy['hours_today_label'] . ' · ' . $weekday . ', ' . $today->day . ' ' . $month,
      default => $copy['hours_today_label'] . ' · ' . $weekday . ', ' . $today->day . ' ' . $month,
    };

  $pathCards = [
      'ru' => [
          ['title' => 'Каталог', 'body' => 'Поиск по фонду с доступностью, ISBN и быстрым переходом в карточку.', 'href' => '/catalog', 'tag' => 'Навигация'],
          ['title' => 'Ресурсы', 'body' => 'Лицензионные платформы, локальные цифровые материалы и честные примечания по доступу.', 'href' => '/resources', 'tag' => 'Доступ'],
          ['title' => 'Кабинет', 'body' => 'Выдачи, бронирования, продления и статусы учётной записи читателя.', 'href' => '/account', 'tag' => 'Читатель'],
          ['title' => 'Подборка литературы', 'body' => 'Рабочий маршрут для преподавателя и подготовки библиографии по реальному каталогу.', 'href' => '/shortlist', 'tag' => 'Преподаватель'],
      ],
      'kk' => [
          ['title' => 'Каталог', 'body' => 'Қор бойынша іздеу, қолжетімділік, ISBN және карточкаға жылдам өту.', 'href' => '/catalog', 'tag' => 'Навигация'],
          ['title' => 'Ресурстар', 'body' => 'Лицензиялық платформалар, жергілікті цифрлық материалдар және қолжетімділік туралы нақты ескертпелер.', 'href' => '/resources', 'tag' => 'Қолжетімділік'],
          ['title' => 'Кабинет', 'body' => 'Оқырманға арналған берілімдер, броньдар, ұзартулар және кабинет күйлері.', 'href' => '/account', 'tag' => 'Оқырман'],
          ['title' => 'Әдебиет іріктемесі', 'body' => 'Нақты каталог негізінде библиография дайындауға арналған оқытушы жұмыс ағыны.', 'href' => '/shortlist', 'tag' => 'Оқытушы'],
      ],
      'en' => [
          ['title' => 'Catalog', 'body' => 'Search the holdings with live availability, ISBN detail, and a direct route into the record page.', 'href' => '/catalog', 'tag' => 'Discovery'],
          ['title' => 'Resources', 'body' => 'Licensed platforms, local digital materials, and clear access notes in one research surface.', 'href' => '/resources', 'tag' => 'Access'],
          ['title' => 'Account', 'body' => 'Loans, reservations, renewals, and account states for authenticated readers.', 'href' => '/account', 'tag' => 'Reader'],
          ['title' => 'Shortlist', 'body' => 'A faculty workflow for building a practical bibliography from the live catalog.', 'href' => '/shortlist', 'tag' => 'Faculty'],
      ],
  ][$lang];

  $collectionCards = [
      'ru' => [
          ['title' => 'Печатный фонд', 'body' => 'Книги и экземпляры с проверкой наличия, мест хранения и маршрутом к бронированию.'],
          ['title' => 'Локальные цифровые материалы', 'body' => 'Контролируемый доступ к внутренним материалам без открытой раздачи файлов.'],
          ['title' => 'Лицензионные ресурсы', 'body' => 'Внешние платформы и научные базы, аккуратно отделённые от собственного фонда.'],
      ],
      'kk' => [
          ['title' => 'Баспа қоры', 'body' => 'Қолжетімділік, сақтау орны және броньдау маршруты көрсетілген кітаптар мен даналар.'],
          ['title' => 'Жергілікті цифрлық материалдар', 'body' => 'Ішкі материалдарға файлдарды ашық таратусыз бақыланатын қолжетімділік.'],
          ['title' => 'Лицензиялық ресурстар', 'body' => 'Сыртқы платформалар мен ғылыми базалар негізгі қордан анық бөлініп көрсетіледі.'],
      ],
      'en' => [
          ['title' => 'Print holdings', 'body' => 'Books and copies with live availability, storage location, and a direct route to reservation.'],
          ['title' => 'Local digital materials', 'body' => 'Controlled access to internal materials without open file distribution.'],
          ['title' => 'Licensed resources', 'body' => 'External platforms and research databases presented clearly without blending them into the main holdings.'],
      ],
  ][$lang];

  $serviceRows = [
      'ru' => [
          ['label' => 'Поиск по фонду', 'title' => 'Нужно быстро найти книгу или ISBN', 'body' => 'Откройте каталог и используйте фильтры по доступности, языку, году и сортировке.', 'href' => '/catalog', 'action' => 'Каталог'],
          ['label' => 'Доступ к исследованиям', 'title' => 'Нужна внешняя база данных или электронный ресурс', 'body' => 'Перейдите в раздел ресурсов, чтобы понять источник доступа и формат использования.', 'href' => '/resources', 'action' => 'Ресурсы'],
          ['label' => 'Учебный маршрут', 'title' => 'Нужно собрать литературу для курса или силлабуса', 'body' => 'Используйте подборку как рабочую зону для курса и последующего экспорта.', 'href' => '/shortlist', 'action' => 'Подборка'],
          ['label' => 'Поддержка', 'title' => 'Нужно уточнить условия, часы работы или связаться с библиотекой', 'body' => 'Откройте контакты и используйте реальные каналы связи вместо промо-блоков.', 'href' => '/contacts', 'action' => 'Контакты'],
      ],
      'kk' => [
          ['label' => 'Қор бойынша іздеу', 'title' => 'Кітапты не ISBN-ді тез табу керек', 'body' => 'Каталогты ашып, қолжетімділік, тіл, жыл және сұрыптау сүзгілерін қолданыңыз.', 'href' => '/catalog', 'action' => 'Каталог'],
          ['label' => 'Зерттеу қолжетімділігі', 'title' => 'Сыртқы дерекқор немесе электрондық ресурс қажет', 'body' => 'Қолжетімділік көзін және пайдалану форматын түсіну үшін ресурстар бөліміне өтіңіз.', 'href' => '/resources', 'action' => 'Ресурстар'],
          ['label' => 'Оқу маршруты', 'title' => 'Курс немесе силлабус үшін әдебиет жинау керек', 'body' => 'Іріктемені жұмыс аймағы және кейінгі экспорт үшін пайдаланыңыз.', 'href' => '/shortlist', 'action' => 'Іріктеме'],
          ['label' => 'Қолдау', 'title' => 'Шарттарды, жұмыс уақытын немесе байланыс арналарын нақтылау керек', 'body' => 'Промо-блоктардың орнына контактілерді ашып, нақты байланыс арналарын пайдаланыңыз.', 'href' => '/contacts', 'action' => 'Байланыс'],
      ],
      'en' => [
          ['label' => 'Holdings search', 'title' => 'Need a book or ISBN quickly', 'body' => 'Open the catalog and use filters for availability, language, year, and sorting.', 'href' => '/catalog', 'action' => 'Catalog'],
          ['label' => 'Research access', 'title' => 'Need an external database or electronic resource', 'body' => 'Open the resources area to understand the access channel and usage format.', 'href' => '/resources', 'action' => 'Resources'],
          ['label' => 'Teaching workflow', 'title' => 'Need to assemble a course or syllabus reading list', 'body' => 'Use shortlist as the working area for course support and later export.', 'href' => '/shortlist', 'action' => 'Shortlist'],
          ['label' => 'Support', 'title' => 'Need library hours, policies, or a contact route', 'body' => 'Open the contacts page and use the real service channels instead of generic promo blocks.', 'href' => '/contacts', 'action' => 'Contacts'],
      ],
  ][$lang];
@endphp

@section('title', $copy['title'])

@section('head')
<style>
  .landing-hero {
    padding: clamp(18px, 2.4vw, 28px) 0 14px;
  }

  .homepage-band {
    width: min(100% - 40px, 1460px);
    margin: 0 auto;
  }

  .homepage-band--hero {
    width: min(100% - 40px, 1520px);
  }

  .landing-shell {
    max-width: none;
    margin: 0 auto;
    padding: clamp(22px, 2.6vw, 34px);
    text-align: left;
    position: relative;
    overflow: hidden;
    border-radius: 26px;
    border: 1px solid rgba(204, 211, 221, 0.45);
    background: linear-gradient(180deg, rgba(255,255,255,.94), rgba(247,249,250,.9));
    box-shadow: 0 18px 42px rgba(25, 28, 29, 0.045);
    animation: landingReveal .55s cubic-bezier(0.2, 0.8, 0.2, 1) both;
  }

  .landing-shell::before,
  .landing-shell::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    opacity: .8;
  }

  .landing-shell::before {
    top: -34px;
    right: -18px;
    width: 146px;
    height: 146px;
    background: radial-gradient(circle, rgba(20,105,109,.08), transparent 70%);
  }

  .landing-shell::after {
    left: -22px;
    bottom: -36px;
    width: 128px;
    height: 128px;
    background: radial-gradient(circle, rgba(0,30,64,.055), transparent 70%);
  }

  .landing-shell > * {
    position: relative;
    z-index: 1;
  }

  .landing-hero-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.08fr) minmax(250px, 330px);
    gap: clamp(18px, 2.8vw, 32px);
    align-items: start;
    text-align: left;
  }

  .landing-intro {
    min-width: 0;
    max-width: 760px;
    padding-right: clamp(2px, .45vw, 8px);
  }

  .landing-kicker {
    display: inline-block;
    margin-bottom: 8px;
    color: var(--cyan);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .18em;
  }

  .landing-curator {
    margin-bottom: 8px;
    color: var(--blue);
    font-family: 'Newsreader', Georgia, serif;
    font-style: italic;
    font-size: 1.08rem;
  }

  .landing-identity-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    width: fit-content;
    margin-bottom: 10px;
    padding: 6px 10px;
    border-radius: 999px;
    background: rgba(0, 30, 64, 0.06);
    border: 1px solid rgba(0, 30, 64, 0.08);
    color: var(--blue);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .landing-title {
    max-width: 16ch;
    margin: 0 0 12px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(2.85rem, 4.35vw, 4.35rem);
    line-height: .98;
    letter-spacing: -.032em;
    text-wrap: balance;
    color: var(--blue);
  }

  .landing-copy {
    margin: 0;
    max-width: 640px;
    color: var(--muted);
    font-size: clamp(15.5px, 1vw, 18px);
    line-height: 1.66;
  }

  .landing-campus-panel {
    display: grid;
    gap: 10px;
    justify-items: center;
    align-content: start;
    padding-top: 10px;
  }

  .hero-campus-mark {
    position: relative;
    width: min(248px, 100%);
    aspect-ratio: 1;
    border-radius: 50%;
    padding: 12px;
    overflow: hidden;
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.95) 0%, rgba(204,229,231,.84) 34%, rgba(10,63,108,.84) 100%);
    box-shadow: 0 12px 28px rgba(0, 30, 64, 0.08);
  }

  .hero-campus-mark::before,
  .hero-campus-mark::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    inset: 10%;
    border: 1px solid rgba(255,255,255,.24);
  }

  .hero-campus-mark::after {
    inset: auto 18% 16% 18%;
    height: 12px;
    border: 0;
    background: rgba(255,255,255,.18);
    filter: blur(12px);
  }

  .campus-mark__inner {
    position: relative;
    z-index: 1;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: grid;
    place-items: center;
    overflow: hidden;
    padding: 8px;
    background: rgba(255,255,255,.92);
    border: 1px solid rgba(255,255,255,.72);
    box-shadow: inset 0 0 0 1px rgba(0, 30, 64, 0.05);
  }

  .campus-mark__logo-shell {
    width: 100%;
    height: 100%;
    aspect-ratio: 1;
    border-radius: 50%;
    overflow: hidden;
    background: transparent;
    border: 0;
    box-shadow: none;
  }

  .campus-mark__logo {
    display: block;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: contain;
    object-position: center;
    transform: scale(1.03);
    transform-origin: center;
  }

  .landing-campus-note {
    width: min(100%, 288px);
    margin-top: 2px;
    padding: 10px 12px;
    border-radius: 18px;
    background: rgba(255,255,255,.72);
    border: 1px solid rgba(204,211,221,.48);
    box-shadow: 0 6px 14px rgba(25,28,29,.028);
    text-align: left;
  }

  .landing-campus-note strong {
    display: block;
    margin-bottom: 4px;
    color: var(--blue);
    font-size: 14px;
  }

  .landing-campus-note p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.56;
  }

  .landing-search {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 10px;
    max-width: 760px;
    margin: 18px auto 0;
    padding: 8px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(195, 198, 209, 0.62);
    box-shadow: 0 16px 36px rgba(25, 28, 29, 0.055);
    backdrop-filter: blur(18px);
    position: relative;
    overflow: hidden;
    transition: transform 280ms cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 280ms cubic-bezier(0.2, 0.8, 0.2, 1), border-color 160ms cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .landing-search::after {
    content: '';
    position: absolute;
    inset: -30% auto -30% -10%;
    width: 30%;
    background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,.45) 50%, transparent 100%);
    opacity: 0;
    transform: translate3d(-16px, 0, 0);
    transition: transform 420ms cubic-bezier(0.2, 0.8, 0.2, 1), opacity 160ms cubic-bezier(0.2, 0.8, 0.2, 1);
    pointer-events: none;
  }

  .landing-search:focus-within {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 20px 44px rgba(25, 28, 29, 0.08);
    border-color: rgba(0, 30, 64, 0.16);
  }

  .landing-search:focus-within::after {
    opacity: 1;
    transform: translate3d(18px, 0, 0);
  }

  .landing-search input {
    width: 100%;
    border: 0;
    background: transparent;
    color: var(--text);
    padding: 15px 22px;
    font: inherit;
    font-size: 15px;
    outline: none;
  }

  .landing-search .btn {
    border-radius: 999px;
    min-height: 52px;
    padding-inline: 26px;
  }

  .hero-quick-links {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
    margin-top: 14px;
  }

  .hero-quick-link {
    display: grid;
    gap: 6px;
    min-height: 84px;
    padding: 14px 16px;
    text-align: left;
    border-radius: 18px;
    background: rgba(255,255,255,.72);
    border: 1px solid rgba(203,209,219,.48);
    box-shadow: 0 8px 18px rgba(25,28,29,.028);
    transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .16s cubic-bezier(0.2, 0.8, 0.2, 1), background .16s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .hero-quick-link:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 16px 32px rgba(25,28,29,.05);
    border-color: rgba(20,105,109,.18);
    background: rgba(255,255,255,.96);
  }

  .hero-quick-link span {
    color: var(--muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
  }

  .hero-quick-link strong {
    color: var(--blue);
    font-size: 16px;
    line-height: 1.22;
  }

  .landing-stats {
    display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;
    gap: 10px 12px;
    margin-top: 12px;
    color: var(--muted);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
  }

  .landing-stats span {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 11px;
    border-radius: 999px;
    background: rgba(255,255,255,.68);
    border: 1px solid rgba(203,209,219,.5);
    box-shadow: 0 6px 16px rgba(25,28,29,.026);
  }

  .landing-feature-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1.06fr) minmax(0, 1fr);
    gap: 16px;
    perspective: 1400px;
  }

  .landing-feature-grid .feature-entry:nth-child(2) {
    transform: translate3d(0, 8px, 0);
  }

  .landing-feature-grid .feature-entry:nth-child(2):hover {
    transform: translate3d(0, 4px, 0) rotateX(0.8deg);
  }

  .feature-entry {
    min-height: 214px;
    padding: 22px 22px 20px;
    border-radius: var(--radius-xl);
    border: 1px solid rgba(205, 211, 220, 0.58);
    background: linear-gradient(180deg, rgba(255,255,255,.99), rgba(248,250,251,.94));
    text-align: left;
    box-shadow: 0 10px 22px rgba(25, 28, 29, 0.03);
    position: relative;
    overflow: hidden;
    transform-style: preserve-3d;
    transition: transform 280ms cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 280ms cubic-bezier(0.2, 0.8, 0.2, 1), border-color 160ms cubic-bezier(0.2, 0.8, 0.2, 1), background 160ms cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .feature-entry::after {
    transform: translate3d(0, -3px, 0) rotateX(0.6deg);
    box-shadow: 0 16px 30px rgba(25, 28, 29, 0.05);
    inset: -35% 48% 50% -8%;
    background: radial-gradient(circle, rgba(255,255,255,.52), transparent 72%);
    opacity: 0;
    transform: translate3d(-12px, 12px, 0);
    background: linear-gradient(165deg, #08264a 0%, #0a3765 56%, #165b8f 100%);
    pointer-events: none;
  }

  .feature-entry:hover {
    transform: translate3d(0, -4px, 0) rotateX(0.8deg);
    box-shadow: 0 18px 38px rgba(25, 28, 29, 0.06);
    border-color: rgba(0, 30, 64, 0.12);
  }

  .feature-entry:hover::after {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }

  .feature-entry--primary {
    background: linear-gradient(160deg, #001e40 0%, #003c73 58%, #0e5f9d 100%);
    color: #fff;
    border-color: transparent;
  }

  .feature-entry--primary::before {
    content: '';
    position: absolute;
    right: -16px;
    bottom: -18px;
    width: 132px;
    height: 132px;
    border-radius: 50%;
    background-image: url('/logo.png');
    background-size: cover;
    background-position: center;
    opacity: .08;
    filter: grayscale(1) brightness(1.9);
    pointer-events: none;
  }

  .feature-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--muted);
  }

  .feature-entry h3 {
    margin: 0 0 8px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(1.7rem, 2.4vw, 2.15rem);
    color: var(--blue);
    line-height: 1.06;
  }

  .feature-entry p {
    margin: 0;
    color: var(--muted);
    line-height: 1.7;
    font-size: 14px;
    max-width: 24rem;
  }

  .feature-entry.feature-entry--primary .feature-meta {
    color: rgba(255,255,255,.92);
  }

  .feature-entry.feature-entry--primary h3 {
    color: #ffffff;
  }

  .feature-entry.feature-entry--primary p {
    color: rgba(245,249,255,.96);
  }

  .subject-block {
    background: transparent;
    border-radius: 0;
    padding: 0;
  }

  .subject-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 18px;
    margin-bottom: 18px;
  }

  .subject-head h2 {
    margin: 0;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(2rem, 3.6vw, 2.9rem);
    color: var(--blue);
  }

  .subject-link {
    color: var(--cyan);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .12em;
  }

  .subject-grid {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 12px;
  }

  .subject-card {
    padding: 18px 14px 16px;
    border-radius: 18px;
    background: linear-gradient(180deg, rgba(255,255,255,.96), rgba(249,251,252,.9));
    border: 1px solid rgba(205, 211, 220, 0.52);
    text-align: center;
    box-shadow: 0 8px 20px rgba(25,28,29,.024);
    transition: transform 220ms cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 220ms cubic-bezier(0.2, 0.8, 0.2, 1), border-color 160ms cubic-bezier(0.2, 0.8, 0.2, 1), background 160ms cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .subject-card:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(20, 105, 109, 0.18);
    background: rgba(255,255,255,.98);
  }

  .subject-card strong {
    display: block;
    margin-bottom: 6px;
    color: var(--blue);
    font-size: 15px;
    line-height: 1.24;
  }

  .subject-card span {
    color: var(--muted);
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .12em;
    font-weight: 700;
  }

  .hours-section {
    display: grid;
    grid-template-columns: minmax(0, 1.05fr) minmax(300px, .95fr);
    gap: 22px;
    align-items: stretch;
  }

  .hours-copy {
    border: 1px solid rgba(205, 211, 220, 0.52);
    border-radius: var(--radius-xl);
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(249,250,251,.94));
    padding: 24px 26px;
    box-shadow: 0 10px 24px rgba(25, 28, 29, 0.032);
    display: grid;
    gap: 14px;
  }

  .hours-head {
    display: grid;
    gap: 10px;
    max-width: 44rem;
  }

  .hours-head h2 {
    margin: 0;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(2rem, 3.6vw, 2.9rem);
    line-height: 1.02;
    color: var(--blue);
  }

  .hours-head p {
    margin: 0;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.66;
    max-width: 36rem;
  }

  .hours-date-line {
    display: inline-flex;
    align-items: center;
    width: fit-content;
    min-height: 34px;
    padding: 0 11px;
    border-radius: 999px;
    background: rgba(20, 105, 109, 0.06);
    color: var(--cyan);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .hours-list {
    display: grid;
    gap: 0;
  }

  .hours-row {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 14px;
    align-items: start;
    padding: 13px 0;
    border-top: 1px solid rgba(195, 198, 209, 0.55);
  }

  .hours-row:first-child {
    border-top: 0;
    padding-top: 2px;
  }

  .hours-row strong {
    display: block;
    margin-bottom: 4px;
    color: var(--blue);
    font-size: 16px;
    line-height: 1.25;
  }

  .hours-row p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.56;
    max-width: 28rem;
  }

  .hours-time {
    color: var(--blue);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    white-space: nowrap;
    padding-top: 2px;
  }

  .hours-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    width: fit-content;
    margin-top: 4px;
    color: var(--cyan);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .hours-link::after {
    content: '↗';
    font-size: 13px;
    line-height: 1;
  }

  .hours-visual {
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(195, 198, 209, 0.6);
    border-radius: var(--radius-xl);
    min-height: 292px;
    background:
      linear-gradient(180deg, rgba(255,255,255,.52), rgba(243,247,249,.72)),
      linear-gradient(180deg, rgba(7, 26, 47, 0.06), rgba(7, 26, 47, 0.18)),
      url('/trust-panel-library.svg');
    background-size: cover;
    background-position: center;
    box-shadow: 0 10px 24px rgba(25, 28, 29, 0.032);
    display: grid;
    align-items: end;
  }

  .hours-visual::before {
    content: '';
    position: absolute;
    inset: 20px 22px auto auto;
    width: 126px;
    height: 126px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,.16), rgba(255,255,255,0) 68%);
    pointer-events: none;
  }

  .hours-visual::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(245,249,250,.02), rgba(11, 29, 48, .16) 62%, rgba(11, 29, 48, .28) 100%);
    pointer-events: none;
  }

  .hours-visual-inner {
    position: relative;
    z-index: 1;
    padding: 22px;
    display: grid;
    gap: 12px;
  }

  .hours-visual-copy {
    display: grid;
    gap: 8px;
    max-width: 22rem;
  }

  .hours-visual-copy p {
    margin: 0;
    color: rgba(238,244,247,.88);
    font-size: 14px;
    line-height: 1.62;
  }

  .hours-visual-copy h3 {
    margin: 0;
    color: #ffffff;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(1.6rem, 2.6vw, 2.05rem);
    line-height: 1.04;
  }

  .hours-markers {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
    max-width: 26rem;
  }

  .hours-marker {
    display: grid;
    gap: 4px;
    padding: 11px 11px 10px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(12px);
  }

  .hours-marker span {
    color: rgba(233,240,244,.78);
    font-size: 10px;
    letter-spacing: .12em;
    text-transform: uppercase;
    font-weight: 800;
  }

  .hours-marker strong {
    color: #ffffff;
    font-size: 13px;
    line-height: 1.35;
  }

  .news-section {
    display: grid;
    grid-template-columns: minmax(0, 1.08fr) minmax(280px, .72fr);
    gap: 22px;
    align-items: start;
  }

  .news-stack {
    display: grid;
    gap: 18px;
  }

  .news-head {
    display: flex;
    justify-content: space-between;
    align-items: end;
    gap: 18px;
  }

  .news-head-main {
    display: grid;
    gap: 8px;
    max-width: 44rem;
  }

  .news-head-main h2 {
    margin: 0;
    color: var(--blue);
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(2.05rem, 3.8vw, 2.95rem);
    line-height: 1.04;
  }

  .news-head-main h2 span {
    color: #58708a;
    font-style: italic;
    font-weight: 500;
  }

  .news-head-main p {
    margin: 0;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.66;
    max-width: 38rem;
  }

  .news-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-items: center;
  }

  .news-filter-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 30px;
    padding: 0 12px;
    border-radius: 999px;
    border: 1px solid rgba(205, 211, 220, 0.52);
    background: rgba(255,255,255,.78);
    color: #617182;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .news-filter-chip.is-active {
    background: var(--blue);
    border-color: var(--blue);
    color: #fff;
  }

  .news-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
  }

  .news-card {
    display: grid;
    gap: 14px;
    align-content: start;
  }

  .news-card-media {
    position: relative;
    min-height: 228px;
    border-radius: 24px;
    overflow: hidden;
    border: 1px solid rgba(205, 211, 220, 0.52);
    background:
      linear-gradient(180deg, rgba(255,255,255,.08), rgba(11,29,48,.16)),
      radial-gradient(circle at top left, rgba(255,255,255,.22), transparent 36%),
      linear-gradient(135deg, rgba(20,105,109,.18), rgba(11,29,48,.28)),
      url('/trust-panel-library.svg');
    background-size: cover;
    background-position: center;
    box-shadow: 0 14px 30px rgba(25, 28, 29, 0.04);
  }

  .news-card-media::after {
    content: '';
    position: absolute;
    inset: auto 0 0 0;
    height: 44%;
    background: linear-gradient(180deg, rgba(8,18,32,0), rgba(8,18,32,.12) 28%, rgba(8,18,32,.42) 100%);
  }

  .news-card-media--alt {
    background:
      linear-gradient(180deg, rgba(255,255,255,.06), rgba(11,29,48,.12)),
      radial-gradient(circle at top right, rgba(255,255,255,.2), transparent 34%),
      linear-gradient(135deg, rgba(180,143,82,.16), rgba(11,29,48,.28)),
      url('/trust-panel-library.svg');
    background-size: cover;
    background-position: center;
  }

  .news-card-badge {
    position: absolute;
    left: 16px;
    bottom: 16px;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    min-height: 28px;
    padding: 0 10px;
    border-radius: 999px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.16);
    color: #fff;
    backdrop-filter: blur(10px);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .news-card-copy {
    display: grid;
    gap: 8px;
  }

  .news-card-date {
    color: #7b8897;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
  }

  .news-card h3 {
    margin: 0;
    color: var(--blue);
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(1.5rem, 2vw, 1.9rem);
    line-height: 1.14;
    text-wrap: balance;
  }

  .news-card p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.65;
  }

  .news-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    width: fit-content;
    color: var(--cyan);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .news-link::after {
    content: '↗';
    line-height: 1;
  }

  .events-rail {
    border: 1px solid rgba(205, 211, 220, 0.54);
    border-radius: 24px;
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(248,250,251,.93));
    box-shadow: 0 14px 30px rgba(25,28,29,.035);
    padding: 22px 20px 20px;
    display: grid;
    gap: 16px;
    align-content: start;
  }

  .events-rail-head {
    display: grid;
    gap: 8px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(205, 211, 220, 0.42);
  }

  .events-rail-head h3 {
    margin: 0;
    color: var(--blue);
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(1.9rem, 3vw, 2.45rem);
    line-height: 1.02;
  }

  .events-list {
    display: grid;
    gap: 12px;
  }

  .event-item {
    display: grid;
    gap: 4px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(205, 211, 220, 0.38);
  }

  .event-item:last-child {
    padding-bottom: 0;
    border-bottom: 0;
  }

  .event-item strong {
    color: #1a6e78;
    font-size: 15px;
    line-height: 1.36;
  }

  .event-item span {
    color: #7b8897;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .event-item p {
    margin: 0;
    color: #627283;
    font-size: 12px;
    line-height: 1.54;
  }

  .events-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: fit-content;
    justify-self: end;
    color: var(--blue);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .events-link::after {
    content: '→';
    line-height: 1;
  }

  .trust-section {
    display: grid;
    grid-template-columns: minmax(0, .92fr) minmax(0, 1.08fr);
    gap: 20px;
    align-items: stretch;
  }

  .quote-panel {
    padding: 0;
    border-radius: var(--radius-xl);
      background-color: #101c28;
      background-image: url('/trust-panel-reading-room.jpg');
    background-size: cover;
      background-position: center 34%;
    color: #fff;
    display: flex;
    align-items: flex-end;
    min-height: 318px;
    position: relative;
    overflow: hidden;
    isolation: isolate;
    border: 1px solid rgba(211, 221, 228, 0.2);
    box-shadow: 0 16px 32px rgba(18, 24, 29, 0.1);
    transition: transform 280ms cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 280ms cubic-bezier(0.2, 0.8, 0.2, 1), border-color 160ms cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .quote-panel::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(7, 15, 24, 0.24) 0%, rgba(7, 15, 24, 0.34) 34%, rgba(7, 16, 25, 0.52) 72%, rgba(6, 14, 23, 0.68) 100%);
    z-index: 0;
  }

  .quote-panel::after {
    content: '';
    position: absolute;
    inset: 0;
    background:
      radial-gradient(circle at 82% 18%, rgba(255, 244, 214, 0.12), transparent 28%),
      linear-gradient(180deg, rgba(5, 12, 20, 0.04) 0%, rgba(5, 12, 20, 0.12) 24%, rgba(5, 12, 20, 0.42) 100%);
    transition: transform 420ms cubic-bezier(0.2, 0.8, 0.2, 1);
    z-index: 0;
  }

  .quote-panel:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 24px 48px rgba(18, 24, 29, 0.16);
    border-color: rgba(211, 221, 228, 0.26);
  }

  .quote-panel:hover::after {
    transform: translate3d(0, -4px, 0);
  }

  .quote-panel__inner {
    position: relative;
    z-index: 1;
    width: min(100% - 28px, 31rem);
    margin: 18px;
    max-width: 31rem;
    padding: 22px 24px 20px;
    display: grid;
    gap: 12px;
    border: 1px solid rgba(255,255,255,.12);
    background: linear-gradient(180deg, rgba(7, 15, 24, 0.46), rgba(7, 15, 24, 0.62));
    backdrop-filter: blur(3px);
    box-shadow: 0 14px 30px rgba(8, 14, 21, 0.16);
  }

  .quote-panel__eyebrow {
    margin: 0;
    color: rgba(235, 241, 242, 0.78);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .18em;
    text-transform: uppercase;
  }

  .quote-panel__inner::before {
    content: '';
    width: 52px;
    height: 1px;
    background: linear-gradient(90deg, rgba(229, 237, 240, 0.76), rgba(229, 237, 240, 0));
  }

  .quote-panel__quote {
    margin: 0;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(1.28rem, 1.9vw, 1.66rem);
    line-height: 1.38;
    font-style: normal;
    letter-spacing: -.016em;
    max-width: 20ch;
    text-wrap: balance;
    text-shadow: 0 2px 12px rgba(3, 8, 13, 0.32);
  }

  .trust-copy {
    padding: 8px 0 10px;
    align-self: center;
  }

  .trust-copy h2 {
    margin: 0 0 14px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(2.1rem, 3.8vw, 3rem);
    color: var(--blue);
    line-height: 1.02;
  }

  .trust-copy p {
    margin: 0 0 18px;
    color: var(--muted);
    line-height: 1.72;
    font-size: 15px;
    max-width: 31rem;
  }

  .trust-stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 16px;
  }

  .trust-stat {
    padding: 14px;
    border-radius: var(--radius-lg);
    background: linear-gradient(180deg, rgba(255,255,255,.96), rgba(248,250,251,.92));
    border: 1px solid rgba(205, 211, 220, 0.56);
    transition: transform 220ms cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 220ms cubic-bezier(0.2, 0.8, 0.2, 1), border-color 160ms cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .trust-stat:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(20, 105, 109, 0.18);
  }

  .trust-stat strong {
    display: block;
    margin-bottom: 6px;
    color: var(--blue);
    font-size: 24px;
  }

  .trust-stat span {
    color: var(--muted);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
  }

  .trust-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .trust-actions .btn {
    min-height: 46px;
    padding-inline: 20px;
  }

  @keyframes landingReveal {
    from {
      opacity: 0;
      transform: translate3d(0, 14px, 0);
    }

    to {
      opacity: 1;
      transform: translate3d(0, 0, 0);
    }
  }

  .page-section {
    position: relative;
    padding: clamp(54px, 5.8vw, 82px) 0;
    animation: fadeInUp 0.45s var(--ease-premium) both;
  }

  @media (max-width: 1200px) {
    .homepage-band,
    .homepage-band--hero {
      width: min(100% - 32px, 1280px);
    }

    .landing-hero-grid {
      grid-template-columns: minmax(0, 1fr) minmax(320px, 420px);
      gap: 24px;
    }

    .hero-quick-links,
    .subject-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }
  }

  @media (max-width: 980px) {
    .landing-hero-grid,
    .landing-feature-grid,
    .subject-grid,
    .trust-section,
    .hours-section,
    .news-section,
    .hero-quick-links {
      grid-template-columns: 1fr;
    }

    .landing-hero-grid,
    .landing-intro {
      text-align: center;
    }

    .landing-identity-chip {
      margin-inline: auto;
    }

    .landing-title,
    .landing-copy {
      margin-inline: auto;
    }

    .landing-search {
      margin-inline: auto;
    }

    .landing-campus-panel {
      padding-top: 8px;
    }

    .landing-stats {
      justify-content: center;
    }

    .landing-campus-note {
      max-width: 420px;
      text-align: center;
    }

    .hours-visual {
      min-height: 260px;
    }

    .news-grid {
      grid-template-columns: 1fr;
    }

    .landing-feature-grid .feature-entry:nth-child(2) {
      transform: none;
    }

    .quote-panel {
      min-height: 308px;
    }

    .quote-panel__inner {
      max-width: 38rem;
      width: min(100% - 24px, 38rem);
      padding: 24px;
    }
  }

  @media (max-width: 720px) {
    .landing-search,
    .subject-head {
      grid-template-columns: 1fr;
      display: grid;
      text-align: left;
    }

    .hours-row,
    .hours-markers {
      grid-template-columns: 1fr;
    }

    .hours-copy,
    .hours-visual-inner,
    .events-rail {
      padding: 18px;
    }

    .landing-search {
      border-radius: 8px;
      margin-top: 20px;
    }

    .hero-quick-link {
      padding: 11px 12px;
    }

    .subject-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .news-head {
      align-items: start;
      flex-direction: column;
    }

    .quote-panel {
      min-height: 264px;
      background-position: center top;
    }

    .quote-panel__inner {
      padding: 24px;
    }

    .quote-panel__quote {
      max-width: none;
      font-size: clamp(1.45rem, 6.8vw, 1.9rem);
    }
  }
</style>
@endsection

@section('content')
  <section class="landing-hero" data-homepage-stitch-reset>
    <div class="container homepage-band homepage-band--hero">
      <div class="landing-shell">
        <div class="landing-hero-grid">
          <div class="landing-intro">
            <div class="landing-kicker">{{ $copy['eyebrow'] }}</div>
            <div class="landing-curator">{{ __('ui.brand.title') }}</div>
            <div class="landing-identity-chip">{{ $copy['identity_badge'] }}</div>
            <h1 class="landing-title">{{ $copy['hero'] }}</h1>
            <p class="landing-copy">{{ $copy['lead'] }}</p>
          </div>

          <aside class="landing-campus-panel" aria-label="{{ $copy['identity_badge'] }}">
            <div class="hero-campus-mark">
              <div class="campus-mark__inner">
                <div class="campus-mark__logo-shell">
                  <img src="{{ asset('logo.png') }}" alt="{{ $copy['logo_alt'] }}" class="campus-mark__logo" loading="eager" decoding="async">
                </div>
              </div>
            </div>
            <div class="landing-campus-note">
              <strong>{{ $copy['identity_badge'] }}</strong>
              <p>{{ $copy['identity_note'] }}</p>
            </div>
          </aside>
        </div>

        <form id="heroSearch" class="landing-search hero-search-bar" action="/catalog" method="get" data-hero-search>
          @if($lang !== 'ru')
            <input type="hidden" name="lang" value="{{ $lang }}">
          @endif
          <input type="search" name="q" placeholder="{{ $copy['search_placeholder'] }}" aria-label="{{ $copy['search_placeholder'] }}">
          <button type="submit" class="btn btn-primary">{{ $copy['search_cta'] }}</button>
        </form>

        <div class="hero-quick-links" aria-label="Quick routes">
          @foreach($pathCards as $card)
            <a href="{{ $withLang($card['href']) }}" class="hero-quick-link">
              <span>{{ $card['tag'] }}</span>
              <strong>{{ $card['title'] }}</strong>
            </a>
          @endforeach
        </div>

        <div class="landing-stats" aria-label="Library highlights">
          @foreach($copy['stats'] as $stat)
            <span>{{ $stat }}</span>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container homepage-band">
      <div class="landing-feature-grid">
        @foreach($copy['feature_cards'] as $index => $feature)
          <article class="feature-entry{{ $index === 1 ? ' feature-entry--primary' : '' }}">
            <div class="feature-meta"><span>{{ $feature['meta_left'] }}</span><span>{{ $feature['meta_right'] }}</span></div>
            <h3>{{ $feature['title'] }}</h3>
            <p>{{ $feature['body'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container homepage-band">
      <div class="subject-block">
        <div class="subject-head">
          <div>
            <div class="eyebrow eyebrow--cyan">{{ $copy['subject_eyebrow'] }}</div>
            <h2>{{ $copy['subject_title'] }}</h2>
          </div>
          <a href="{{ $withLang('/discover') }}" class="subject-link">{{ $copy['subject_link'] }}</a>
        </div>

        <div class="subject-grid" data-homepage-subjects>
          @foreach($copy['subject_cards'] as $subject)
            <article class="subject-card"><strong>{{ $subject['title'] }}</strong><span>{{ $subject['udc'] }}</span></article>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container homepage-band hours-section">
      <div class="hours-copy">
        <div class="hours-head">
          <div class="eyebrow eyebrow--cyan">{{ $copy['hours_eyebrow'] }}</div>
          <h2>{{ $copy['hours_title'] }}</h2>
          <p>{{ $copy['hours_body'] }}</p>
        </div>

        <div class="hours-date-line">{{ $todayLine }}</div>

        <div class="hours-list">
          @foreach($copy['hours_rows'] as $row)
            <article class="hours-row">
              <div>
                <strong>{{ $row['label'] }}</strong>
                <p>{{ $row['meta'] }}</p>
              </div>
              <div class="hours-time">{{ $row['hours'] }}</div>
            </article>
          @endforeach
        </div>

        <a href="{{ $withLang('/contacts') }}" class="hours-link">{{ $copy['hours_cta'] }}</a>
      </div>

      <aside class="hours-visual" aria-label="{{ $copy['hours_title'] }}">
        <div class="hours-visual-inner">
          <div class="hours-visual-copy">
            <div class="eyebrow eyebrow--green">{{ $copy['hours_visual_eyebrow'] }}</div>
            <h3>{{ $copy['hours_visual_title'] }}</h3>
            <p>{{ $copy['hours_visual_body'] }}</p>
          </div>

          <div class="hours-markers">
            <div class="hours-marker">
              <span>{{ ['ru' => 'Локация', 'kk' => 'Локация', 'en' => 'Location'][$lang] }}</span>
              <strong>{{ ['ru' => 'Главный кампус', 'kk' => 'Негізгі кампус', 'en' => 'Main campus'][$lang] }}</strong>
            </div>
            <div class="hours-marker">
              <span>{{ ['ru' => 'Сервис', 'kk' => 'Сервис', 'en' => 'Service'][$lang] }}</span>
              <strong>{{ ['ru' => 'Каталог и консультации', 'kk' => 'Каталог және кеңес', 'en' => 'Catalog and guidance'][$lang] }}</strong>
            </div>
            <div class="hours-marker">
              <span>{{ ['ru' => 'Маршрут', 'kk' => 'Маршрут', 'en' => 'Route'][$lang] }}</span>
              <strong>{{ ['ru' => 'Фонд, читальный зал, бронирование', 'kk' => 'Қор, оқу залы, бронь', 'en' => 'Holdings, reading room, booking'][$lang] }}</strong>
            </div>
            <div class="hours-marker">
              <span>{{ ['ru' => 'Контакт', 'kk' => 'Байланыс', 'en' => 'Contact'][$lang] }}</span>
              <strong>{{ ['ru' => 'Через страницу контактов', 'kk' => 'Байланыс беті арқылы', 'en' => 'Via the contacts page'][$lang] }}</strong>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </section>

  <section class="page-section">
    <div class="container homepage-band trust-section">
      <div class="quote-panel">
        <div class="quote-panel__inner">
          <p class="quote-panel__eyebrow">{{ $copy['quote_panel_eyebrow'] }}</p>
          <p class="quote-panel__quote">{{ $copy['quote'] }}</p>
        </div>
      </div>

      <div class="trust-copy">
        <div class="eyebrow eyebrow--green">{{ $copy['summary_kicker'] }}</div>
        <h2>{{ $copy['trust_title'] }}</h2>
        <p>{{ $copy['trust_body'] }}</p>

        <div class="trust-stats">
          @foreach($copy['trust_stats'] as $stat)
            <div class="trust-stat"><strong>{{ $stat['value'] }}</strong><span>{{ $stat['label'] }}</span></div>
          @endforeach
        </div>

        <div class="trust-actions">
          <a href="{{ $withLang('/catalog') }}" class="btn btn-primary">{{ $copy['trust_actions']['catalog'] }}</a>
          <a href="{{ $withLang('/resources') }}" class="btn btn-ghost">{{ $copy['trust_actions']['resources'] }}</a>
          <a href="{{ $withLang('/shortlist') }}" class="btn btn-ghost">{{ $copy['trust_actions']['shortlist'] }}</a>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container homepage-band news-section">
      <div class="news-stack">
        <div class="news-head">
          <div class="news-head-main">
            <div class="eyebrow eyebrow--cyan">{{ $copy['news_eyebrow'] }}</div>
            <h2>{{ $copy['news_title'] }} <span>{{ $copy['news_title_accent'] }}</span></h2>
            <p>{{ $copy['news_body'] }}</p>
          </div>

          <div class="news-filters" aria-label="{{ $copy['news_title'] }}">
            @foreach($copy['news_filters'] as $index => $filter)
              <span class="news-filter-chip{{ $index === 0 ? ' is-active' : '' }}">{{ $filter }}</span>
            @endforeach
          </div>
        </div>

        <div class="news-grid">
          @foreach($copy['news_items'] as $index => $item)
            <article class="news-card">
              <div class="news-card-media{{ $index === 1 ? ' news-card-media--alt' : '' }}">
                <span class="news-card-badge">{{ $item['tag'] }}</span>
              </div>
              <div class="news-card-copy">
                <span class="news-card-date">{{ $item['date'] }}</span>
                <h3>{{ $item['title'] }}</h3>
                <p>{{ $item['body'] }}</p>
              </div>
            </article>
          @endforeach
        </div>

        <a href="{{ $withLang('/resources') }}" class="news-link">{{ $copy['news_cta'] }}</a>
      </div>

      <aside class="events-rail" aria-label="{{ $copy['workshops_title'] }}">
        <div class="events-rail-head">
          <h3>{{ $copy['workshops_title'] }}</h3>
        </div>

        <div class="events-list">
          @foreach($copy['workshops_items'] as $item)
            <article class="event-item">
              <strong>{{ $item['title'] }}</strong>
              <span>{{ $item['time'] }} · {{ $item['date'] }}</span>
            </article>
          @endforeach
        </div>

        <a href="{{ $withLang('/contacts') }}" class="events-link">{{ $copy['workshops_cta'] }}</a>
      </aside>
    </div>
  </section>
@endsection
