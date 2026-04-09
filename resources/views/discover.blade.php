@extends('layouts.public', ['activePage' => 'discover'])

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
          'title' => 'Навигация по темам — Digital Library',
          'hero_eyebrow' => 'Академическая навигация',
          'hero_title' => 'Ищите по теме, дисциплине и исследовательской задаче',
          'hero_lead' => 'Начните с актуальной структуры направлений и сразу переходите в каталог с реальной доступностью, фильтрами и библиографической детализацией.',
          'hero_catalog' => 'Открыть каталог',
          'hero_resources' => 'Научные ресурсы',
          'aside_eyebrow' => 'Как пользоваться',
          'aside_title' => 'От общего направления к точной записи',
          'aside_body' => 'Эта страница работает как спокойный Stitch-подобный слой навигации: структурно, без декоративного шума и с прямым выходом в поиск.',
          'aside_steps' => [
              'Выберите факультет, кафедру или специализацию.',
              'Откройте связанные результаты каталога.',
              'Уточните выдачу по году, языку и доступности.',
              'Сохраните нужные позиции в подборку для курса или силлабуса.',
          ],
          'panel_eyebrow' => 'Структурированные входы',
          'panel_title' => 'Живая тематическая навигация',
          'panel_body' => 'Группы загружаются из реального subject API, поэтому навигация остаётся привязанной к библиотечному каталогу, а не к искусственной промо-таксономии.',
          'loading' => 'Загрузка направлений...',
          'pathways_eyebrow' => 'Тематические маршруты',
          'pathways_title' => 'Типовые академические направления',
          'pathways_body' => 'Используйте эти спокойные точки входа, когда тема шире одной специализации.',
          'workflow_eyebrow' => 'Учебный сценарий',
          'workflow_title' => 'Используйте навигацию для подготовки силлабуса',
          'workflow_body' => 'Держите маршрут внутри реального библиотечного процесса, а не внутри отдельной рекламной воронки.',
          'banner_title' => 'Реальная структура каталога, а не синтетическая витрина',
          'banner_body' => 'Тематический слой связан с библиотечными метаданными и обновляется по мере улучшения классификации фонда.',
          'cta_title' => 'Нужен более широкий маршрут поиска?',
          'cta_body' => 'Перейдите в основной каталог для полного поиска по фонду или откройте подборку преподавателя, если собираете список литературы для курса.',
          'cta_shortlist' => 'Открыть подборку',
          'groups' => [
              'faculties' => 'Факультеты',
              'departments' => 'Кафедры',
              'specializations' => 'Специальности',
              'items' => 'изданий',
              'error' => 'Не удалось загрузить направления. Используйте поиск по ключевым словам ниже.',
          ],
      ],
      'kk' => [
          'title' => 'Тақырыптық навигация — Digital Library',
          'hero_eyebrow' => 'Академиялық навигация',
          'hero_title' => 'Тақырып, пән және зерттеу сұранысы бойынша іздеңіз',
          'hero_lead' => 'Бағыттардың өзекті құрылымынан бастап, нақты қолжетімділік, сүзгілер және библиографиялық мәліметтер бар каталогқа бірден өтіңіз.',
          'hero_catalog' => 'Каталогты ашу',
          'hero_resources' => 'Ғылыми ресурстар',
          'aside_eyebrow' => 'Қолдану жолы',
          'aside_title' => 'Жалпы бағыттан нақты жазбаға дейін',
          'aside_body' => 'Бұл бет Stitch стиліндегі тыныш навигациялық қабат ретінде жұмыс істейді: құрылымды, артық безендірусіз және іздеуге тікелей шығумен.',
          'aside_steps' => [
              'Факультетті, кафедраны немесе мамандандыруды таңдаңыз.',
              'Каталогтағы сәйкес нәтижелерді ашыңыз.',
              'Нәтижені жыл, тіл және қолжетімділік бойынша нақтылаңыз.',
              'Қажетті позицияларды курс немесе силлабус үшін іріктемеге сақтаңыз.',
          ],
          'panel_eyebrow' => 'Құрылымды кіру нүктелері',
          'panel_title' => 'Тірі тақырыптық навигация',
          'panel_body' => 'Топтар нақты subject API-дан жүктеледі, сондықтан бұл навигация жарнамалық қабырғаға емес, кітапхана каталогына сүйенеді.',
          'loading' => 'Бағыттар жүктелуде...',
          'pathways_eyebrow' => 'Тақырыптық бағыттар',
          'pathways_title' => 'Жиі қолданылатын академиялық маршруттар',
          'pathways_body' => 'Тақырып бір ғана мамандандырудан кең болғанда осы тыныш кіру нүктелерін пайдаланыңыз.',
          'workflow_eyebrow' => 'Оқу сценарийі',
          'workflow_title' => 'Силлабус дайындау үшін навигацияны пайдаланыңыз',
          'workflow_body' => 'Маршрутты жеке промо-воронкада емес, нақты кітапханалық процесте ұстаңыз.',
          'banner_title' => 'Синтетикалық витрина емес, нақты каталог құрылымы',
          'banner_body' => 'Тақырыптық қабат кітапхана метадеректерімен байланысты және қордың жіктелуі жақсарған сайын жаңарып отырады.',
          'cta_title' => 'Іздеудің кеңірек жолы керек пе?',
          'cta_body' => 'Қор бойынша толық іздеу үшін негізгі каталогқа өтіңіз немесе курс үшін әдебиет тізімін жинап жатсаңыз, іріктемені ашыңыз.',
          'cta_shortlist' => 'Іріктемені ашу',
          'groups' => [
              'faculties' => 'Факультеттер',
              'departments' => 'Кафедралар',
              'specializations' => 'Мамандандырулар',
              'items' => 'басылым',
              'error' => 'Бағыттарды жүктеу мүмкін болмады. Төмендегі кілт сөздер арқылы іздеуді пайдаланыңыз.',
          ],
      ],
      'en' => [
          'title' => 'Subject navigation — Digital Library',
          'hero_eyebrow' => 'Academic discovery',
          'hero_title' => 'Browse by subject, teaching area, and research need',
          'hero_lead' => 'Start from the live subject structure, then move directly into the catalog with real availability, filters, and bibliographic detail.',
          'hero_catalog' => 'Open catalog',
          'hero_resources' => 'Research resources',
          'aside_eyebrow' => 'How to use it',
          'aside_title' => 'Move from a broad topic to the exact record',
          'aside_body' => 'This page works like a calm Stitch-derived subject layer: structured, search-first, and free from brochure-style noise.',
          'aside_steps' => [
              'Choose a faculty, department, or specialization.',
              'Open the related catalog results.',
              'Refine the result set by year, language, and availability.',
              'Save selected items into shortlist for course support.',
          ],
          'panel_eyebrow' => 'Structured entry points',
          'panel_title' => 'Live subject navigation',
          'panel_body' => 'These groups are loaded from the real subject API, so the discovery surface stays tied to the actual catalog instead of a synthetic marketing taxonomy.',
          'loading' => 'Loading subject groups...',
          'pathways_eyebrow' => 'Subject pathways',
          'pathways_title' => 'Common academic routes',
          'pathways_body' => 'Use these quiet starting points when the topic is broader than one formal specialization.',
          'workflow_eyebrow' => 'Teaching workflow',
          'workflow_title' => 'Use discovery for syllabus support',
          'workflow_body' => 'Keep the path close to the real library workflow instead of a separate promotional funnel.',
          'banner_title' => 'Real catalog structure, not a synthetic browse wall',
          'banner_body' => 'The subject surface is connected to real library metadata and refreshes as classification coverage improves across the collection.',
          'cta_title' => 'Need a broader search path?',
          'cta_body' => 'Move into the main catalog for full discovery or open the teaching shortlist when you are building a reading list for a course.',
          'cta_shortlist' => 'Open shortlist',
          'groups' => [
              'faculties' => 'Faculties',
              'departments' => 'Departments',
              'specializations' => 'Specializations',
              'items' => 'records',
              'error' => 'Unable to load the subject map. Use the keyword routes below instead.',
          ],
      ],
  ][$lang];

  $cards = [
      'ru' => [
          ['meta' => 'Технические науки', 'udc' => 'UDC 004', 'title' => 'Информатика и цифровые системы', 'body' => 'Программирование, базы данных, сети, информационные системы, кибербезопасность и ИИ.', 'keywords' => [['q' => 'программирование', 'label' => 'Программирование'], ['q' => 'информационные системы', 'label' => 'Информационные системы'], ['q' => 'базы данных', 'label' => 'Базы данных']]],
          ['meta' => 'Экономика', 'udc' => 'UDC 33', 'title' => 'Экономика, финансы и менеджмент', 'body' => 'Микро- и макроэкономика, бухгалтерский учёт, финансы, маркетинг и бизнес-планирование.', 'keywords' => [['q' => 'экономика', 'label' => 'Экономика'], ['q' => 'менеджмент', 'label' => 'Менеджмент'], ['q' => 'финансы', 'label' => 'Финансы']]],
          ['meta' => 'Право и политика', 'udc' => 'UDC 34', 'title' => 'Право и юриспруденция', 'body' => 'Гражданское, уголовное, административное, трудовое и международное право в едином маршруте.', 'keywords' => [['q' => 'право', 'label' => 'Право'], ['q' => 'юриспруденция', 'label' => 'Юриспруденция'], ['q' => 'гражданское право', 'label' => 'Гражданское право']]],
          ['meta' => 'Образование', 'udc' => 'UDC 37', 'title' => 'Педагогика и методика преподавания', 'body' => 'Дидактика, instructional design, психология обучения и академическая методология.', 'keywords' => [['q' => 'педагогика', 'label' => 'Педагогика'], ['q' => 'методика преподавания', 'label' => 'Методика преподавания'], ['q' => 'образование', 'label' => 'Образование']]],
          ['meta' => 'Инженерия', 'udc' => 'UDC 62', 'title' => 'Инженерные и производственные технологии', 'body' => 'Автоматизация, электрические системы, транспорт, машиностроение и промышленные процессы.', 'keywords' => [['q' => 'инженерия', 'label' => 'Инженерия'], ['q' => 'автоматизация', 'label' => 'Автоматизация'], ['q' => 'машиностроение', 'label' => 'Машиностроение']]],
          ['meta' => 'Естественные науки', 'udc' => 'UDC 5', 'title' => 'Наука, химия и экология', 'body' => 'Маршруты по математике, физике, химии, биологии, экологии и пищевым технологиям.', 'keywords' => [['q' => 'математика', 'label' => 'Математика'], ['q' => 'химия', 'label' => 'Химия'], ['q' => 'экология', 'label' => 'Экология']]],
      ],
      'kk' => [
          ['meta' => 'Техникалық ғылымдар', 'udc' => 'UDC 004', 'title' => 'Информатика және цифрлық жүйелер', 'body' => 'Бағдарламалау, дерекқорлар, желілер, ақпараттық жүйелер, киберқауіпсіздік және ЖИ.', 'keywords' => [['q' => 'программирование', 'label' => 'Бағдарламалау'], ['q' => 'информационные системы', 'label' => 'Ақпараттық жүйелер'], ['q' => 'базы данных', 'label' => 'Дерекқорлар']]],
          ['meta' => 'Экономика', 'udc' => 'UDC 33', 'title' => 'Экономика, қаржы және менеджмент', 'body' => 'Микро және макроэкономика, бухгалтерлік есеп, қаржы, маркетинг және бизнес-жоспарлау.', 'keywords' => [['q' => 'экономика', 'label' => 'Экономика'], ['q' => 'менеджмент', 'label' => 'Менеджмент'], ['q' => 'финансы', 'label' => 'Қаржы']]],
          ['meta' => 'Құқық және саясат', 'udc' => 'UDC 34', 'title' => 'Құқық және юриспруденция', 'body' => 'Азаматтық, қылмыстық, әкімшілік, еңбек және халықаралық құқық бір бағытта ұсынылады.', 'keywords' => [['q' => 'право', 'label' => 'Құқық'], ['q' => 'юриспруденция', 'label' => 'Юриспруденция'], ['q' => 'гражданское право', 'label' => 'Азаматтық құқық']]],
          ['meta' => 'Білім беру', 'udc' => 'UDC 37', 'title' => 'Педагогика және оқыту әдістемесі', 'body' => 'Дидактика, оқыту дизайны, оқу психологиясы және академиялық әдістеме.', 'keywords' => [['q' => 'педагогика', 'label' => 'Педагогика'], ['q' => 'методика преподавания', 'label' => 'Оқыту әдістемесі'], ['q' => 'образование', 'label' => 'Білім беру']]],
          ['meta' => 'Инженерия', 'udc' => 'UDC 62', 'title' => 'Инженерлік және өндірістік технологиялар', 'body' => 'Автоматтандыру, электр жүйелері, көлік, машина жасау және өндірістік процестер.', 'keywords' => [['q' => 'инженерия', 'label' => 'Инженерия'], ['q' => 'автоматизация', 'label' => 'Автоматтандыру'], ['q' => 'машиностроение', 'label' => 'Машина жасау']]],
          ['meta' => 'Жаратылыстану ғылымдары', 'udc' => 'UDC 5', 'title' => 'Ғылым, химия және экология', 'body' => 'Математика, физика, химия, биология, экология және тағам технологиялары бағыттары.', 'keywords' => [['q' => 'математика', 'label' => 'Математика'], ['q' => 'химия', 'label' => 'Химия'], ['q' => 'экология', 'label' => 'Экология']]],
      ],
      'en' => [
          ['meta' => 'Technical Sciences', 'udc' => 'UDC 004', 'title' => 'Computer science and digital systems', 'body' => 'Programming, databases, networking, information systems, cybersecurity, and AI.', 'keywords' => [['q' => 'программирование', 'label' => 'Programming'], ['q' => 'информационные системы', 'label' => 'Information systems'], ['q' => 'базы данных', 'label' => 'Databases']]],
          ['meta' => 'Economics', 'udc' => 'UDC 33', 'title' => 'Economics, finance, and management', 'body' => 'Micro and macroeconomics, accounting, finance, marketing, and business planning.', 'keywords' => [['q' => 'экономика', 'label' => 'Economics'], ['q' => 'менеджмент', 'label' => 'Management'], ['q' => 'финансы', 'label' => 'Finance']]],
          ['meta' => 'Law & Policy', 'udc' => 'UDC 34', 'title' => 'Law and jurisprudence', 'body' => 'Civil, criminal, administrative, labor, and international law in one discovery lane.', 'keywords' => [['q' => 'право', 'label' => 'Law'], ['q' => 'юриспруденция', 'label' => 'Jurisprudence'], ['q' => 'гражданское право', 'label' => 'Civil law']]],
          ['meta' => 'Education', 'udc' => 'UDC 37', 'title' => 'Pedagogy and teaching method', 'body' => 'Didactics, instructional design, learning psychology, and academic methodology.', 'keywords' => [['q' => 'педагогика', 'label' => 'Pedagogy'], ['q' => 'методика преподавания', 'label' => 'Teaching method'], ['q' => 'образование', 'label' => 'Education']]],
          ['meta' => 'Engineering', 'udc' => 'UDC 62', 'title' => 'Engineering and industrial technologies', 'body' => 'Automation, electrical systems, transport, machinery, and industrial processes.', 'keywords' => [['q' => 'инженерия', 'label' => 'Engineering'], ['q' => 'автоматизация', 'label' => 'Automation'], ['q' => 'машиностроение', 'label' => 'Machinery']]],
          ['meta' => 'Natural Sciences', 'udc' => 'UDC 5', 'title' => 'Science, chemistry, and the environment', 'body' => 'Mathematics, physics, chemistry, biology, ecology, and food technology routes.', 'keywords' => [['q' => 'математика', 'label' => 'Mathematics'], ['q' => 'химия', 'label' => 'Chemistry'], ['q' => 'экология', 'label' => 'Ecology']]],
      ],
  ][$lang];

  $workflow = [
      'ru' => [
          ['title' => 'Выберите нужное направление', 'body' => 'Начните с живой группы факультета или специализации, чтобы поиск шёл от академической структуры, которой уже пользуется библиотека.'],
          ['title' => 'Уточните результаты каталога', 'body' => 'В каталоге сузьте выдачу по языку, году и доступности под конкретный курс или исследовательскую задачу.'],
          ['title' => 'Проверьте доступ к электронным ресурсам', 'body' => 'Через раздел ресурсов проверьте, находится ли материал в локальной коллекции, лицензионной платформе или в контролируемом цифровом канале.'],
          ['title' => 'Сохраните итог в подборку', 'body' => 'Перенесите выбранные позиции в подборку, чтобы экспортировать или согласовать список литературы для курса.'],
      ],
      'kk' => [
          ['title' => 'Қажетті бағытты таңдаңыз', 'body' => 'Іздеу кітапхана қазірдің өзінде қолданатын академиялық құрылымнан басталуы үшін факультет не мамандандыру тобынан бастаңыз.'],
          ['title' => 'Каталог нәтижесін нақтылаңыз', 'body' => 'Каталогта нақты курс немесе зерттеу міндеті үшін нәтижені тіл, жыл және қолжетімділік бойынша тарылтыңыз.'],
          ['title' => 'Электрондық қолжетімділікті тексеріңіз', 'body' => 'Ресурстар бөлімі арқылы материалдың жергілікті қорда, лицензиялық платформада немесе бақыланатын цифрлық арнада тұрғанын тексеріңіз.'],
          ['title' => 'Қорытындыны іріктемеге сақтаңыз', 'body' => 'Таңдалған позицияларды іріктемеге өткізіп, курсқа арналған әдебиет тізімін экспорттаңыз немесе келісіңіз.'],
      ],
      'en' => [
          ['title' => 'Choose the right subject lane', 'body' => 'Start with the live faculty or specialization group so the search begins from the academic structure already used by the library.'],
          ['title' => 'Refine the catalog results', 'body' => 'In the catalog, narrow the result set by language, year, and availability for the exact course or research need.'],
          ['title' => 'Check digital access and databases', 'body' => 'Use the resources area to verify whether the material lives in the local collection, a licensed platform, or a controlled digital channel.'],
          ['title' => 'Save the final set into shortlist', 'body' => 'Move the chosen items into shortlist so the reading list can be exported or shared as part of course preparation.'],
      ],
  ][$lang];
@endphp

@section('title', $copy['title'])

@section('content')
  <section class="page-hero discover-hero">
    <div class="container discover-hero-shell">
      <div>
        <div class="eyebrow eyebrow--cyan">{{ $copy['hero_eyebrow'] }}</div>
        <h1>{{ $copy['hero_title'] }}</h1>
        <p>{{ $copy['hero_lead'] }}</p>
      </div>
      <div class="discover-hero-actions">
        <a href="{{ $withLang('/catalog') }}" class="btn btn-primary">{{ $copy['hero_catalog'] }}</a>
        <a href="{{ $withLang('/resources') }}" class="btn btn-ghost">{{ $copy['hero_resources'] }}</a>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container discover-layout">
      <aside class="discover-rail">
        <div class="eyebrow eyebrow--violet">{{ $copy['aside_eyebrow'] }}</div>
        <h2>{{ $copy['aside_title'] }}</h2>
        <p>{{ $copy['aside_body'] }}</p>
        <ul class="discover-rail-list">
          @foreach($copy['aside_steps'] as $step)
            <li>{{ $step }}</li>
          @endforeach
        </ul>
      </aside>

      <div class="discover-main">
        <div class="discover-panel">
          <div class="eyebrow">{{ $copy['panel_eyebrow'] }}</div>
          <h2>{{ $copy['panel_title'] }}</h2>
          <p>{{ $copy['panel_body'] }}</p>

          <div id="subjects-loading" class="discover-loading">{{ $copy['loading'] }}</div>
          <div id="subjects-container" style="display:none;">
            <div id="faculties-section" class="subjects-group"></div>
            <div id="departments-section" class="subjects-group"></div>
            <div id="specializations-section" class="subjects-group"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head">
        <div>
          <div class="eyebrow eyebrow--green">{{ $copy['pathways_eyebrow'] }}</div>
          <h2>{{ $copy['pathways_title'] }}</h2>
          <p>{{ $copy['pathways_body'] }}</p>
        </div>
      </div>

      <div class="discover-grid">
        @foreach($cards as $card)
          <article class="discover-card">
            <div class="discover-card-meta">{{ $card['meta'] }} <span>{{ $card['udc'] }}</span></div>
            <h3>{{ $card['title'] }}</h3>
            <p>{{ $card['body'] }}</p>
            <div class="discover-keywords">
              @foreach($card['keywords'] as $keyword)
                <a href="{{ $withLang('/catalog', ['q' => $keyword['q']]) }}" class="keyword-chip">{{ $keyword['label'] }}</a>
              @endforeach
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head section-head-centered">
        <div>
          <div class="eyebrow eyebrow--violet">{{ $copy['workflow_eyebrow'] }}</div>
          <h2>{{ $copy['workflow_title'] }}</h2>
          <p>{{ $copy['workflow_body'] }}</p>
        </div>
      </div>

      <div class="workflow-steps">
        @foreach($workflow as $index => $step)
          <div class="workflow-step">
            <div class="step-marker">{{ $index + 1 }}</div>
            <div class="step-content">
              <h3>{{ $step['title'] }}</h3>
              <p>{{ $step['body'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="info-banner">
        <div class="info-banner-icon">i</div>
        <div class="info-banner-text">
          <h3>{{ $copy['banner_title'] }}</h3>
          <p>{{ $copy['banner_body'] }}</p>
        </div>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <h2>{{ $copy['cta_title'] }}</h2>
      <p>{{ $copy['cta_body'] }}</p>
      <div class="cta-buttons">
        <a href="{{ $withLang('/catalog') }}" class="btn btn-primary">{{ $copy['hero_catalog'] }}</a>
        <a href="{{ $withLang('/shortlist') }}" class="btn btn-ghost">{{ $copy['cta_shortlist'] }}</a>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .discover-hero {
    padding-bottom: 16px;
  }

  .discover-hero-shell {
    display: flex;
    align-items: end;
    justify-content: space-between;
    gap: 24px;
    text-align: left;
    animation: discoverReveal .5s cubic-bezier(0.2, 0.8, 0.2, 1) both;
  }

  .discover-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .discover-layout {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 24px;
    align-items: start;
  }

  .discover-rail {
    position: sticky;
    top: var(--shell-sticky-offset);
    padding: 22px;
    border-radius: var(--radius-xl);
    background: linear-gradient(180deg, rgba(255,255,255,.96), rgba(243,244,245,.94));
    border: 1px solid rgba(195, 198, 209, 0.42);
    box-shadow: var(--shadow-soft);
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .discover-rail:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(0,30,64,.12);
  }

  .discover-rail h2 {
    margin: 0 0 10px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.75rem;
    color: var(--blue);
    line-height: 1.1;
  }

  .discover-rail p {
    margin: 0 0 16px;
    color: var(--muted);
    line-height: 1.7;
    font-size: 14px;
  }

  .discover-rail-list {
    margin: 0;
    padding-left: 18px;
    color: var(--muted);
    display: grid;
    gap: 10px;
    line-height: 1.65;
    font-size: 14px;
  }

  .discover-rail-list a {
    color: var(--blue);
    font-weight: 700;
  }

  .discover-panel {
    padding: 24px;
    border-radius: var(--radius-xl);
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    border: 1px solid var(--border);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
    transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .discover-panel:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 16px 34px rgba(25, 28, 29, 0.05);
    border-color: rgba(0,30,64,.12);
  }

  .discover-panel h2 {
    margin: 0 0 8px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: clamp(1.8rem, 3vw, 2.5rem);
    color: var(--blue);
  }

  .discover-panel > p {
    margin: 0 0 18px;
    color: var(--muted);
    line-height: 1.75;
  }

  .discover-loading {
    padding: 30px 16px;
    text-align: center;
    color: var(--muted);
    background: linear-gradient(180deg, rgba(255,255,255,.96), rgba(243,244,245,.94));
    border-radius: var(--radius-lg);
    border: 1px dashed rgba(195,198,209,.7);
  }

  .discover-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 18px;
  }

  .discover-grid .discover-card:nth-child(3n + 2) {
    transform: translate3d(0, 10px, 0);
  }

  .discover-grid .discover-card:nth-child(3n + 2):hover {
    transform: translate3d(0, 6px, 0);
  }

  .discover-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), background .2s ease, border-color .2s ease, box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .discover-card:hover {
    background: rgba(243, 244, 245, 0.96);
    border-color: rgba(20, 105, 109, 0.22);
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
  }

  .discover-card-meta {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--muted);
  }

  .discover-card h3 {
    margin: 0;
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.35rem;
    color: var(--blue);
    line-height: 1.18;
  }

  .discover-card > p {
    margin: 0;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.7;
  }

  .discover-keywords {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .keyword-chip {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    background: rgba(0, 30, 64, 0.04);
    color: var(--blue);
    border: 1px solid rgba(195, 198, 209, 0.55);
    text-decoration: none;
    transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .15s ease, color .15s ease, border-color .15s ease;
  }

  .keyword-chip:hover {
    background: var(--blue);
    color: #fff;
    transform: translate3d(0, -1px, 0);
  }

  .workflow-steps {
    max-width: 820px;
    margin: 0 auto;
    display: grid;
    gap: 12px;
  }

  .workflow-step {
    display: grid;
    grid-template-columns: 42px 1fr;
    gap: 14px;
    align-items: start;
    padding: 18px;
    border-radius: var(--radius-lg);
    background: #fff;
    border: 1px solid var(--border);
    transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .workflow-step:nth-child(even) {
    transform: translate3d(12px, 0, 0);
  }

  .workflow-step:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 12px 26px rgba(25, 28, 29, 0.04);
    border-color: rgba(20,105,109,.18);
  }

  .step-marker {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 999px;
    background: rgba(20, 105, 109, 0.10);
    color: var(--cyan);
    font-weight: 800;
    font-size: 15px;
  }

  .step-content h3 {
    margin: 0 0 6px;
    font-size: 16px;
    font-weight: 800;
    color: var(--blue);
  }

  .step-content p {
    margin: 0;
    color: var(--muted);
    font-size: 14px;
    line-height: 1.7;
  }

  .step-content a {
    color: var(--blue);
    font-weight: 700;
  }

  .subjects-group {
    margin-bottom: 18px;
  }

  .subjects-group-title {
    font-size: 11px;
    font-weight: 800;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .14em;
    margin-bottom: 10px;
  }

  .subjects-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .subject-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: var(--radius-md);
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid var(--border);
    background: #fff;
    color: var(--blue);
    transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .2s ease, border-color .2s ease, box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .subject-chip:hover {
    background: rgba(243, 244, 245, 0.96);
    border-color: rgba(20, 105, 109, 0.22);
    box-shadow: 0 10px 20px rgba(25, 28, 29, 0.04);
    transform: translate3d(0, -1px, 0);
  }

  .subject-chip.faculty {
    background: rgba(0, 30, 64, 0.03);
    color: var(--blue);
  }

  .subject-chip.department {
    background: rgba(20, 105, 109, 0.04);
    color: var(--cyan);
  }

  .subject-chip.specialization {
    background: rgba(69, 48, 0, 0.05);
    color: #5d4201;
  }

  .subject-count {
    font-size: 11px;
    font-weight: 800;
    opacity: .7;
  }

  @media (max-width: 900px) {
    .discover-hero-shell,
    .discover-layout {
      grid-template-columns: 1fr;
      display: grid;
    }

    .discover-rail {
      position: static;
      top: auto;
    }

    .discover-grid .discover-card:nth-child(3n + 2),
    .workflow-step:nth-child(even) {
      transform: none;
    }
  }

  @media (max-width: 680px) {
    .discover-grid {
      grid-template-columns: 1fr;
    }

    .workflow-step {
      grid-template-columns: 1fr;
    }
  }
</style>
@endsection

@section('scripts')
<script>
  const DISCOVER_I18N = @json($copy['groups']);
  const DISCOVER_LANG = @json($lang);

  function withLang(path, query = {}) {
    const url = new URL(path, window.location.origin);
    Object.entries(query).forEach(([key, value]) => {
      if (value !== null && value !== undefined && value !== '') {
        url.searchParams.set(key, value);
      }
    });

    if (DISCOVER_LANG !== 'ru' && !url.searchParams.has('lang')) {
      url.searchParams.set('lang', DISCOVER_LANG);
    }

    return url.pathname + url.search;
  }

  (async function loadSubjectsForDiscover() {
    const loading = document.getElementById('subjects-loading');
    const container = document.getElementById('subjects-container');
    try {
      const res = await fetch('/api/v1/subjects', { headers: { Accept: 'application/json' } });
      if (!res.ok) throw new Error('API error');
      const data = await res.json();

      function renderGroup(containerId, title, items, kind) {
        if (!items || items.length === 0) return;
        const el = document.getElementById(containerId);
        const chips = items.map(item => {
          const url = withLang('/catalog', { subject_id: item.id, subject_label: item.label });
          const titleText = `${item.documentCount} ${DISCOVER_I18N.items}`;
          return `<a href="${url}" class="subject-chip ${kind}" title="${titleText}">${item.label} <span class="subject-count">${item.documentCount}</span></a>`;
        }).join('');
        el.innerHTML = `<div class="subjects-group-title">${title}</div><div class="subjects-chips">${chips}</div>`;
      }

      renderGroup('faculties-section', DISCOVER_I18N.faculties, data.faculties, 'faculty');
      renderGroup('departments-section', DISCOVER_I18N.departments, data.departments, 'department');
      renderGroup('specializations-section', DISCOVER_I18N.specializations, data.specializations, 'specialization');

      loading.style.display = 'none';
      container.style.display = 'block';
    } catch (e) {
      loading.textContent = DISCOVER_I18N.error;
      console.warn('Subjects load error:', e);
    }
  })();
</script>
@endsection
