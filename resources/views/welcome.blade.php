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
          'trust_title' => 'Kazakh University of Technology and Business',
          'trust_body' => 'We connect classical cataloging with modern discovery so students and researchers can move naturally between print holdings, digital collections, and licensed resources.',
          'trust_stats' => [['value' => '2003', 'label' => 'Digital Library system'], ['value' => '24/7', 'label' => 'access to e-resources']],
          'trust_actions' => ['catalog' => 'Catalog', 'resources' => 'Resources', 'shortlist' => 'Shortlist'],
      ],
  ][$lang];

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
    padding: clamp(72px, 8vw, 118px) 0 28px;
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
    padding: clamp(28px, 3.6vw, 48px);
    text-align: left;
    position: relative;
    overflow: hidden;
    border-radius: 28px;
    border: 1px solid rgba(195, 198, 209, 0.5);
    background: linear-gradient(180deg, rgba(255,255,255,.92), rgba(244,246,247,.9));
    box-shadow: 0 22px 54px rgba(25, 28, 29, 0.06);
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
    top: -36px;
    right: -22px;
    width: 170px;
    height: 170px;
    background: radial-gradient(circle, rgba(20,105,109,.10), transparent 70%);
  }

  .landing-shell::after {
    left: -28px;
    bottom: -42px;
    width: 150px;
    height: 150px;
    background: radial-gradient(circle, rgba(0,30,64,.08), transparent 70%);
  }

  .landing-shell > * {
    position: relative;
    z-index: 1;
  }

  .landing-hero-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.18fr) minmax(360px, 520px);
    gap: clamp(48px, 6vw, 92px);
    align-items: start;
    text-align: left;
  }

  .landing-intro {
    min-width: 0;
    max-width: 760px;
    padding-right: clamp(10px, 1.4vw, 28px);
  }

  .landing-kicker {
    display: inline-block;
    margin-bottom: 12px;
    color: var(--cyan);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .18em;
  }

  .landing-curator {
    margin-bottom: 12px;
    color: var(--blue);
    font-family: 'Newsreader', Georgia, serif;
    font-style: italic;
    font-size: 1.2rem;
  }

  .landing-identity-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    width: fit-content;
    margin-bottom: 14px;
    padding: 8px 12px;
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
    max-width: 11ch;
    margin: 0 0 22px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(4rem, 6.8vw, 6.3rem);
    line-height: .9;
    letter-spacing: -.055em;
    text-wrap: balance;
    color: var(--blue);
  }

  .landing-copy {
    margin: 0;
    max-width: 760px;
    color: var(--muted);
    font-size: clamp(18px, 1.45vw, 22px);
    line-height: 1.68;
  }

  .landing-campus-panel {
    display: grid;
    gap: 18px;
    justify-items: center;
    align-content: start;
    padding-top: 18px;
  }

  .hero-campus-mark {
    position: relative;
    width: min(420px, 100%);
    aspect-ratio: 1;
    border-radius: 50%;
    padding: 18px;
    overflow: hidden;
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.94) 0%, rgba(190,221,224,.92) 34%, rgba(0,51,102,.95) 100%);
    box-shadow: 0 24px 54px rgba(0, 30, 64, 0.14);
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
    transform: scale(1.08);
    transform-origin: center;
  }

  .landing-campus-note {
    width: min(100%, 390px);
    margin-top: 2px;
    padding: 16px 18px;
    border-radius: 20px;
    background: rgba(255,255,255,.82);
    border: 1px solid rgba(195,198,209,.55);
    box-shadow: 0 10px 24px rgba(25,28,29,.04);
    text-align: left;
  }

  .landing-campus-note strong {
    display: block;
    margin-bottom: 4px;
    color: var(--blue);
    font-size: 15px;
  }

  .landing-campus-note p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.6;
  }

  .landing-search {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 12px;
    max-width: 980px;
    margin: 40px 0 0;
    padding: 8px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(195, 198, 209, 0.55);
    box-shadow: 0 12px 32px rgba(25, 28, 29, 0.04);
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
    box-shadow: 0 18px 40px rgba(25, 28, 29, 0.06);
    border-color: rgba(0, 30, 64, 0.12);
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
    padding: 18px 22px;
    font: inherit;
    font-size: 16px;
    outline: none;
  }

  .landing-search .btn {
    border-radius: 999px;
    min-height: 58px;
    padding-inline: 32px;
  }

  .hero-quick-links {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-top: 24px;
  }

  .hero-quick-link {
    display: grid;
    gap: 8px;
    min-height: 110px;
    padding: 18px 20px;
    text-align: left;
    border-radius: 20px;
    background: rgba(255,255,255,.78);
    border: 1px solid rgba(195,198,209,.55);
    box-shadow: 0 10px 24px rgba(25,28,29,.035);
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
    font-size: 18px;
    line-height: 1.25;
  }

  .landing-stats {
    display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;
    gap: 10px 12px;
    margin-top: 24px;
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
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.76);
    border: 1px solid rgba(195,198,209,.55);
    box-shadow: 0 8px 20px rgba(25,28,29,.03);
  }

  .landing-feature-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) repeat(2, minmax(0, 1fr));
    gap: 22px;
    perspective: 1400px;
  }

  .landing-feature-grid .feature-entry:nth-child(2) {
    transform: translate3d(0, 12px, 0);
  }

  .landing-feature-grid .feature-entry:nth-child(2):hover {
    transform: translate3d(0, 4px, 0) rotateX(0.8deg);
  }

  .feature-entry {
    min-height: 300px;
    padding: 30px;
    border-radius: var(--radius-xl);
    border: 1px solid var(--border);
    background: #fff;
    text-align: left;
    box-shadow: var(--shadow-soft);
    position: relative;
    overflow: hidden;
    transform-style: preserve-3d;
    transition: transform 280ms cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 280ms cubic-bezier(0.2, 0.8, 0.2, 1), border-color 160ms cubic-bezier(0.2, 0.8, 0.2, 1), background 160ms cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .feature-entry::after {
    content: '';
    position: absolute;
    inset: -35% 48% 50% -8%;
    background: radial-gradient(circle, rgba(255,255,255,.52), transparent 72%);
    opacity: 0;
    transform: translate3d(-12px, 12px, 0);
    transition: transform 280ms cubic-bezier(0.2, 0.8, 0.2, 1), opacity 160ms cubic-bezier(0.2, 0.8, 0.2, 1);
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
    right: -24px;
    bottom: -22px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background-image: url('/logo.png');
    background-size: cover;
    background-position: center;
    opacity: .12;
    filter: grayscale(1) brightness(1.9);
    pointer-events: none;
  }

  .feature-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 54px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--muted);
  }

  .feature-entry h3 {
    margin: 0 0 8px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(2rem, 3.4vw, 2.7rem);
    color: var(--blue);
  }

  .feature-entry p {
    margin: 0;
    color: var(--muted);
    line-height: 1.78;
    font-size: 15px;
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
    background: var(--bg-soft);
    border-radius: var(--radius-xl);
    padding: clamp(34px, 4.6vw, 54px);
  }

  .subject-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 24px;
    margin-bottom: 28px;
  }

  .subject-head h2 {
    margin: 0;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(2.3rem, 4.4vw, 3.4rem);
    color: var(--blue);
  }

  .subject-link {
    color: var(--cyan);
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .12em;
  }

  .subject-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }

  .subject-card {
    padding: 24px 22px;
    border-radius: var(--radius-lg);
    background: #fff;
    border: 1px solid var(--border);
    text-align: left;
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
    margin-bottom: 8px;
    color: var(--blue);
    font-size: 18px;
    line-height: 1.28;
  }

  .subject-card span {
    color: var(--muted);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: .08em;
    font-weight: 700;
  }

  .trust-section {
    display: grid;
    grid-template-columns: minmax(0, 1.02fr) minmax(0, .98fr);
    gap: 36px;
    align-items: stretch;
  }

  .quote-panel {
    padding: 42px;
    border-radius: var(--radius-xl);
    background: linear-gradient(180deg, rgba(0, 30, 64, 0.92), rgba(0, 51, 102, 0.9));
    color: #fff;
    display: grid;
    align-items: center;
    min-height: 380px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 18px 38px rgba(25, 28, 29, 0.08);
    transition: transform 280ms cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 280ms cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .quote-panel::after {
    content: '';
    position: absolute;
    inset: -30% auto auto -10%;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,.14), transparent 70%);
    transition: transform 420ms cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .quote-panel:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 22px 42px rgba(25, 28, 29, 0.10);
  }

  .quote-panel:hover::after {
    transform: translate3d(12px, 10px, 0);
  }

  .quote-panel p {
    margin: 0;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(2rem, 3.2vw, 2.8rem);
    line-height: 1.22;
    font-style: italic;
  }

  .trust-copy {
    padding: 18px 0;
  }

  .trust-copy h2 {
    margin: 0 0 16px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(2.4rem, 4.6vw, 3.5rem);
    color: var(--blue);
  }

  .trust-copy p {
    margin: 0 0 24px;
    color: var(--muted);
    line-height: 1.78;
    font-size: 17px;
  }

  .trust-stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }

  .trust-stat {
    padding: 20px;
    border-radius: var(--radius-lg);
    background: #fff;
    border: 1px solid var(--border);
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
    font-size: 28px;
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
    gap: 10px;
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
    padding: clamp(74px, 8vw, 116px) 0;
    animation: fadeInUp 0.45s var(--ease-premium) both;
  }

  @media (max-width: 1200px) {
    .homepage-band,
    .homepage-band--hero {
      width: min(100% - 32px, 1280px);
    }

    .landing-hero-grid {
      grid-template-columns: minmax(0, 1fr) minmax(320px, 420px);
      gap: 38px;
    }

    .hero-quick-links,
    .subject-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 980px) {
    .landing-hero-grid,
    .landing-feature-grid,
    .subject-grid,
    .trust-section,
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

    .landing-stats {
      justify-content: center;
    }

    .landing-campus-note {
      max-width: 420px;
      text-align: center;
    }

    .landing-feature-grid .feature-entry:nth-child(2) {
      transform: none;
    }
  }

  @media (max-width: 720px) {
    .landing-search,
    .subject-head {
      grid-template-columns: 1fr;
      display: grid;
      text-align: left;
    }

    .landing-search {
      border-radius: 8px;
    }

    .hero-quick-link {
      padding: 11px 12px;
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
            <div class="eyebrow eyebrow--cyan">Система классификации</div>
            <h2>Просмотр по темам</h2>
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
    <div class="container homepage-band trust-section">
      <div class="quote-panel">
        <p>{{ $copy['quote'] }}</p>
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
@endsection
