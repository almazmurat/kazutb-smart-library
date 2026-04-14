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
        'title' => 'Академическая навигация — Digital Library',
          'hero_eyebrow' => 'Академическая навигация',
          'hero_title' => 'Ищите по теме, дисциплине и исследовательской задаче',
        'hero_lead' => 'Начните с UDC-направления, затем переходите в реальный каталог с доступностью, библиографией, копиями и дальнейшими фильтрами.',
          'hero_catalog' => 'Открыть каталог',
          'hero_resources' => 'Научные ресурсы',
          'aside_eyebrow' => 'Как пользоваться',
        'aside_title' => 'Начинайте с UDC, а не с витринной таксономии',
        'aside_body' => 'Эта страница не подменяет каталог. Она помогает быстро выбрать академическое направление, перейти в каталог по UDC и затем уточнить поиск по реальным библиотечным данным.',
          'aside_steps' => [
          'Выберите UDC-маршрут, который ближе всего к вашей теме или дисциплине.',
          'Откройте каталог уже с применённым UDC-фильтром и сортировкой.',
          'Уточните выдачу по доступности, языку, году и библиографическим признакам.',
          'Сохраните нужные позиции в черновик списка литературы или перейдите к внешним ресурсам.',
          ],
        'panel_eyebrow' => 'Вторичный слой метаданных',
        'panel_title' => 'Академические термины, уже присутствующие в каталоге',
        'panel_body' => 'Ниже показаны реальные факультетные, кафедральные и специализационные термины из библиотечных записей. Они помогают уточнять поиск, но основной backbone discovery остаётся UDC.',
          'loading' => 'Загрузка направлений...',
        'pathways_eyebrow' => 'UDC-маршруты',
        'pathways_title' => 'Академические направления с прямым входом в каталог',
        'pathways_body' => 'Каждый маршрут сразу ведёт в живой каталог с UDC-фильтром. Ключевые слова ниже помогают сузить поиск внутри этого же направления.',
        'workflow_eyebrow' => 'Рабочий маршрут',
        'workflow_title' => 'От академической темы к реальному списку литературы',
        'workflow_body' => 'Используйте UDC как первую точку входа, затем переходите к уточнению, проверке доступности и сборке чернового списка литературы.',
        'banner_title' => 'UDC задаёт основной каркас академического discovery',
        'banner_body' => 'Факультеты, кафедры и специализации полезны как поддерживающие термины, но навигация платформы строится вокруг UDC, библиографии, наличия и типа ресурса.',
          'cta_title' => 'Нужен более широкий маршрут поиска?',
        'cta_body' => 'Откройте основной каталог для полного поиска по фонду или перейдите в черновик списка литературы, если уже собираете материалы для курса, темы или исследования.',
        'cta_shortlist' => 'Открыть черновик списка',
        'card_cta' => 'Открыть маршрут в каталоге',
          'groups' => [
          'faculties' => 'Факультетные термины',
          'departments' => 'Кафедральные термины',
          'specializations' => 'Термины специализаций',
          'items' => 'записей',
          'error' => 'Не удалось загрузить терминологическую карту. Используйте UDC-маршруты и ключевые слова ниже.',
          ],
      ],
      'kk' => [
        'title' => 'Академиялық навигация — Digital Library',
          'hero_eyebrow' => 'Академиялық навигация',
          'hero_title' => 'Тақырып, пән және зерттеу сұранысы бойынша іздеңіз',
        'hero_lead' => 'ӘОЖ бағытымен бастап, қолжетімділігі, библиографиясы, даналары және қосымша сүзгілері бар нақты каталогқа өтіңіз.',
          'hero_catalog' => 'Каталогты ашу',
          'hero_resources' => 'Ғылыми ресурстар',
          'aside_eyebrow' => 'Қолдану жолы',
        'aside_title' => 'Витриналық таксономиядан емес, ӘОЖ-дан бастаңыз',
        'aside_body' => 'Бұл бет каталогты алмастырмайды. Ол академиялық бағытты тез таңдап, каталогқа ӘОЖ бойынша өтіп, іздеуді нақты кітапханалық деректермен дәлдеуге көмектеседі.',
          'aside_steps' => [
          'Тақырыпқа немесе пәнге сәйкес келетін ӘОЖ-маршрутты таңдаңыз.',
          'Каталогты ӘОЖ сүзгісі мен сұрыптауы бірге қолданылған күйде ашыңыз.',
          'Нәтижені қолжетімділік, тіл, жыл және библиографиялық белгілер бойынша нақтылаңыз.',
          'Қажетті позицияларды әдебиет тізімінің жұмыс нұсқасына сақтаңыз не сыртқы ресурстарға өтіңіз.',
          ],
        'panel_eyebrow' => 'Метадеректің қосымша қабаты',
        'panel_title' => 'Каталогта қазірдің өзінде бар академиялық терминдер',
        'panel_body' => 'Төменде кітапхана жазбаларынан алынған нақты факультет, кафедра және мамандандыру терминдері көрсетіледі. Олар іздеуді нақтылайды, бірақ discovery-дің негізгі қаңқасы ӘОЖ болып қалады.',
          'loading' => 'Бағыттар жүктелуде...',
        'pathways_eyebrow' => 'ӘОЖ-маршруттар',
        'pathways_title' => 'Каталогқа тікелей апаратын академиялық бағыттар',
        'pathways_body' => 'Әр маршрут бірден ӘОЖ сүзгісімен тірі каталогқа апарады. Төмендегі кілт сөздер сол бағыттың ішінде іздеуді тарылтады.',
        'workflow_eyebrow' => 'Жұмыс маршруты',
        'workflow_title' => 'Академиялық тақырыптан нақты әдебиет тізіміне дейін',
        'workflow_body' => 'Алдымен ӘОЖ-ды таңдаңыз, содан кейін қолжетімділікті тексеріп, іздеуді нақтылап, әдебиет тізімінің жұмыс нұсқасын жинаңыз.',
        'banner_title' => 'Академиялық discovery-дің негізгі қаңқасын ӘОЖ береді',
        'banner_body' => 'Факультет, кафедра және мамандандыру терминдері пайдалы, бірақ платформа навигациясы ӘОЖ, библиография, қолжетімділік және ресурс түрі төңірегінде құрылады.',
          'cta_title' => 'Іздеудің кеңірек жолы керек пе?',
        'cta_body' => 'Қор бойынша толық іздеу үшін негізгі каталогқа өтіңіз немесе курс, тақырып не зерттеу үшін материал жинап жатсаңыз, әдебиет тізімінің жұмыс нұсқасын ашыңыз.',
        'cta_shortlist' => 'Жұмыс нұсқасын ашу',
        'card_cta' => 'Маршрутты каталогта ашу',
          'groups' => [
          'faculties' => 'Факультет терминдері',
          'departments' => 'Кафедра терминдері',
          'specializations' => 'Мамандандыру терминдері',
          'items' => 'жазба',
          'error' => 'Терминологиялық картаны жүктеу мүмкін болмады. Төмендегі ӘОЖ-маршруттар мен кілт сөздерді қолданыңыз.',
          ],
      ],
      'en' => [
        'title' => 'Academic navigation — Digital Library',
        'hero_eyebrow' => 'Academic navigation',
          'hero_title' => 'Browse by subject, teaching area, and research need',
        'hero_lead' => 'Start from a UDC direction, then move into the live catalog with real availability, bibliographic detail, copies, and follow-up filters.',
          'hero_catalog' => 'Open catalog',
          'hero_resources' => 'Research resources',
          'aside_eyebrow' => 'How to use it',
        'aside_title' => 'Start with UDC, not a brochure taxonomy',
        'aside_body' => 'This page does not replace the catalog. It helps readers choose an academic direction quickly, enter the catalog with a UDC filter, and then refine the search around real library data.',
          'aside_steps' => [
          'Choose the UDC route closest to the topic, discipline, or research task.',
          'Open the catalog with the UDC filter and sort already applied.',
          'Refine the result set by availability, language, year, and bibliographic cues.',
          'Save selected items into the draft reading list or continue into external resources.',
          ],
        'panel_eyebrow' => 'Secondary metadata layer',
        'panel_title' => 'Academic terms already present in the catalog',
        'panel_body' => 'These faculty, department, and specialization terms are pulled from real library records. They help narrow the search, but UDC remains the main discovery backbone.',
          'loading' => 'Loading subject groups...',
        'pathways_eyebrow' => 'UDC routes',
        'pathways_title' => 'Academic lanes with a direct catalog entry point',
        'pathways_body' => 'Each route opens the live catalog with a UDC filter. The keyword chips narrow the search inside the same direction.',
        'workflow_eyebrow' => 'Working route',
        'workflow_title' => 'From an academic topic to a usable reading list',
        'workflow_body' => 'Use UDC as the first step, then refine by real holdings and availability before collecting materials into a draft bibliography.',
        'banner_title' => 'UDC provides the primary discovery structure',
        'banner_body' => 'Faculty and specialization labels are useful support terms, but the platform is designed around UDC, bibliography, availability, and resource type.',
          'cta_title' => 'Need a broader search path?',
        'cta_body' => 'Move into the main catalog for full collection discovery or open the draft reading list when you are already gathering sources for a course, topic, or research task.',
        'cta_shortlist' => 'Open draft list',
        'card_cta' => 'Open route in catalog',
          'groups' => [
          'faculties' => 'Faculty terms',
          'departments' => 'Department terms',
          'specializations' => 'Specialization terms',
              'items' => 'records',
          'error' => 'Unable to load the terminology map. Use the UDC routes and keyword chips below instead.',
          ],
      ],
  ][$lang];

  $cards = [
      'ru' => [
        ['meta' => 'UDC backbone', 'udc_code' => '004', 'udc' => 'UDC 004', 'title' => 'Информатика и цифровые системы', 'body' => 'Программирование, базы данных, сети, информационные системы, кибербезопасность и цифровая инфраструктура.', 'keywords' => [['q' => 'программирование', 'label' => 'Программирование'], ['q' => 'информационные системы', 'label' => 'Информационные системы'], ['q' => 'базы данных', 'label' => 'Базы данных']]],
        ['meta' => 'UDC backbone', 'udc_code' => '33', 'udc' => 'UDC 33', 'title' => 'Экономика, финансы и менеджмент', 'body' => 'Экономическая теория, управленческие дисциплины, финансы, маркетинг и прикладная аналитика.', 'keywords' => [['q' => 'экономика', 'label' => 'Экономика'], ['q' => 'менеджмент', 'label' => 'Менеджмент'], ['q' => 'финансы', 'label' => 'Финансы']]],
        ['meta' => 'UDC backbone', 'udc_code' => '34', 'udc' => 'UDC 34', 'title' => 'Право и нормативная среда', 'body' => 'Гражданское, уголовное, административное, трудовое и международное право с выходом к живым записям фонда.', 'keywords' => [['q' => 'право', 'label' => 'Право'], ['q' => 'юриспруденция', 'label' => 'Юриспруденция'], ['q' => 'гражданское право', 'label' => 'Гражданское право']]],
        ['meta' => 'UDC backbone', 'udc_code' => '37', 'udc' => 'UDC 37', 'title' => 'Педагогика и методика преподавания', 'body' => 'Дидактика, instructional design, психология обучения, силлабусы и методические материалы.', 'keywords' => [['q' => 'педагогика', 'label' => 'Педагогика'], ['q' => 'методика преподавания', 'label' => 'Методика преподавания'], ['q' => 'образование', 'label' => 'Образование']]],
        ['meta' => 'UDC backbone', 'udc_code' => '62', 'udc' => 'UDC 62', 'title' => 'Инженерия и производственные технологии', 'body' => 'Автоматизация, электрические системы, транспорт, машиностроение, материалы и прикладные технологии.', 'keywords' => [['q' => 'инженерия', 'label' => 'Инженерия'], ['q' => 'автоматизация', 'label' => 'Автоматизация'], ['q' => 'машиностроение', 'label' => 'Машиностроение']]],
        ['meta' => 'UDC backbone', 'udc_code' => '5', 'udc' => 'UDC 5', 'title' => 'Естественные науки, химия и экология', 'body' => 'Математика, физика, химия, биология, экология и смежные дисциплины для базовой академической подготовки.', 'keywords' => [['q' => 'математика', 'label' => 'Математика'], ['q' => 'химия', 'label' => 'Химия'], ['q' => 'экология', 'label' => 'Экология']]],
      ],
      'kk' => [
        ['meta' => 'UDC backbone', 'udc_code' => '004', 'udc' => 'UDC 004', 'title' => 'Информатика және цифрлық жүйелер', 'body' => 'Бағдарламалау, дерекқорлар, желілер, ақпараттық жүйелер, киберқауіпсіздік және цифрлық инфрақұрылым.', 'keywords' => [['q' => 'программирование', 'label' => 'Бағдарламалау'], ['q' => 'информационные системы', 'label' => 'Ақпараттық жүйелер'], ['q' => 'базы данных', 'label' => 'Дерекқорлар']]],
        ['meta' => 'UDC backbone', 'udc_code' => '33', 'udc' => 'UDC 33', 'title' => 'Экономика, қаржы және менеджмент', 'body' => 'Экономикалық теория, басқару пәндері, қаржы, маркетинг және қолданбалы талдау.', 'keywords' => [['q' => 'экономика', 'label' => 'Экономика'], ['q' => 'менеджмент', 'label' => 'Менеджмент'], ['q' => 'финансы', 'label' => 'Қаржы']]],
        ['meta' => 'UDC backbone', 'udc_code' => '34', 'udc' => 'UDC 34', 'title' => 'Құқық және нормативтік орта', 'body' => 'Азаматтық, қылмыстық, әкімшілік, еңбек және халықаралық құқық бағыттары қордағы нақты жазбаларға апарады.', 'keywords' => [['q' => 'право', 'label' => 'Құқық'], ['q' => 'юриспруденция', 'label' => 'Юриспруденция'], ['q' => 'гражданское право', 'label' => 'Азаматтық құқық']]],
        ['meta' => 'UDC backbone', 'udc_code' => '37', 'udc' => 'UDC 37', 'title' => 'Педагогика және оқыту әдістемесі', 'body' => 'Дидактика, instructional design, оқу психологиясы, силлабус және әдістемелік материалдар.', 'keywords' => [['q' => 'педагогика', 'label' => 'Педагогика'], ['q' => 'методика преподавания', 'label' => 'Оқыту әдістемесі'], ['q' => 'образование', 'label' => 'Білім беру']]],
        ['meta' => 'UDC backbone', 'udc_code' => '62', 'udc' => 'UDC 62', 'title' => 'Инженерлік және өндірістік технологиялар', 'body' => 'Автоматтандыру, электр жүйелері, көлік, машина жасау, материалдар және қолданбалы технологиялар.', 'keywords' => [['q' => 'инженерия', 'label' => 'Инженерия'], ['q' => 'автоматизация', 'label' => 'Автоматтандыру'], ['q' => 'машиностроение', 'label' => 'Машина жасау']]],
        ['meta' => 'UDC backbone', 'udc_code' => '5', 'udc' => 'UDC 5', 'title' => 'Жаратылыстану ғылымдары, химия және экология', 'body' => 'Математика, физика, химия, биология, экология және базалық академиялық дайындыққа қажет аралас пәндер.', 'keywords' => [['q' => 'математика', 'label' => 'Математика'], ['q' => 'химия', 'label' => 'Химия'], ['q' => 'экология', 'label' => 'Экология']]],
      ],
      'en' => [
        ['meta' => 'UDC backbone', 'udc_code' => '004', 'udc' => 'UDC 004', 'title' => 'Computer science and digital systems', 'body' => 'Programming, databases, networking, information systems, cybersecurity, and digital infrastructure.', 'keywords' => [['q' => 'программирование', 'label' => 'Programming'], ['q' => 'информационные системы', 'label' => 'Information systems'], ['q' => 'базы данных', 'label' => 'Databases']]],
        ['meta' => 'UDC backbone', 'udc_code' => '33', 'udc' => 'UDC 33', 'title' => 'Economics, finance, and management', 'body' => 'Economic theory, management disciplines, finance, marketing, and applied analytics.', 'keywords' => [['q' => 'экономика', 'label' => 'Economics'], ['q' => 'менеджмент', 'label' => 'Management'], ['q' => 'финансы', 'label' => 'Finance']]],
        ['meta' => 'UDC backbone', 'udc_code' => '34', 'udc' => 'UDC 34', 'title' => 'Law and the regulatory environment', 'body' => 'Civil, criminal, administrative, labor, and international law with a direct path to live records.', 'keywords' => [['q' => 'право', 'label' => 'Law'], ['q' => 'юриспруденция', 'label' => 'Jurisprudence'], ['q' => 'гражданское право', 'label' => 'Civil law']]],
        ['meta' => 'UDC backbone', 'udc_code' => '37', 'udc' => 'UDC 37', 'title' => 'Pedagogy and teaching method', 'body' => 'Didactics, instructional design, learning psychology, syllabi, and teaching-method resources.', 'keywords' => [['q' => 'педагогика', 'label' => 'Pedagogy'], ['q' => 'методика преподавания', 'label' => 'Teaching method'], ['q' => 'образование', 'label' => 'Education']]],
        ['meta' => 'UDC backbone', 'udc_code' => '62', 'udc' => 'UDC 62', 'title' => 'Engineering and industrial technologies', 'body' => 'Automation, electrical systems, transport, machinery, materials, and applied industrial processes.', 'keywords' => [['q' => 'инженерия', 'label' => 'Engineering'], ['q' => 'автоматизация', 'label' => 'Automation'], ['q' => 'машиностроение', 'label' => 'Machinery']]],
        ['meta' => 'UDC backbone', 'udc_code' => '5', 'udc' => 'UDC 5', 'title' => 'Natural sciences, chemistry, and environment', 'body' => 'Mathematics, physics, chemistry, biology, ecology, and adjacent foundations for academic study.', 'keywords' => [['q' => 'математика', 'label' => 'Mathematics'], ['q' => 'химия', 'label' => 'Chemistry'], ['q' => 'экология', 'label' => 'Ecology']]],
      ],
  ][$lang];

  $workflow = [
      'ru' => [
        ['title' => 'Выберите UDC-направление', 'body' => 'Начните с маршрута, который отражает реальную академическую тему: вычисления, экономика, право, педагогика, инженерия или естественные науки.'],
        ['title' => 'Откройте каталог уже в нужной зоне', 'body' => 'Каждый маршрут ведёт в каталог с UDC-фильтром, поэтому вы не начинаете поиск с пустого экрана.'],
        ['title' => 'Уточните по доступности и типу ресурса', 'body' => 'Проверьте наличие экземпляров, язык, год, ISBN, а при необходимости перейдите в лицензионные и внешние ресурсы.'],
        ['title' => 'Соберите рабочий список литературы', 'body' => 'Добавьте подходящие записи в shortlist, чтобы получить черновой список для курса, темы или исследовательской задачи.'],
      ],
      'kk' => [
        ['title' => 'ӘОЖ бағытын таңдаңыз', 'body' => 'Есептеу, экономика, құқық, педагогика, инженерия немесе жаратылыстану секілді нақты академиялық тақырыптан бастаңыз.'],
        ['title' => 'Каталогты бірден дұрыс аймақта ашыңыз', 'body' => 'Әр маршрут каталогты ӘОЖ сүзгісімен ашады, сондықтан іздеу бос экраннан басталмайды.'],
        ['title' => 'Қолжетімділік пен ресурс түрін нақтылаңыз', 'body' => 'Даналардың бар-жоғын, тілді, жылды, ISBN-ды тексеріп, қажет болса сыртқы және лицензиялық ресурстарға өтіңіз.'],
        ['title' => 'Әдебиет тізімінің жұмыс нұсқасын жинаңыз', 'body' => 'Курс, тақырып немесе зерттеу үшін керек жазбаларды shortlist-ке сақтаңыз.'],
      ],
      'en' => [
        ['title' => 'Choose the UDC direction', 'body' => 'Start from a real academic lane such as computing, economics, law, pedagogy, engineering, or natural sciences.'],
        ['title' => 'Open the catalog in the right zone', 'body' => 'Each route opens the catalog with a UDC filter so the search does not begin from an empty state.'],
        ['title' => 'Refine by availability and resource type', 'body' => 'Check copies, language, year, ISBN, and move into licensed or external resources when the topic requires it.'],
        ['title' => 'Build a working draft bibliography', 'body' => 'Save the right records into shortlist so the material set can support a course, topic, or research task.'],
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
                <a href="{{ $withLang('/catalog', ['udc' => $card['udc_code'], 'q' => $keyword['q'], 'sort' => 'title']) }}" class="keyword-chip">{{ $keyword['label'] }}</a>
              @endforeach
            </div>
            <a href="{{ $withLang('/catalog', ['udc' => $card['udc_code'], 'sort' => 'title']) }}" class="discover-card-cta">{{ $copy['card_cta'] }}</a>
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

  .discover-card-cta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 4px;
    padding: 11px 14px;
    border-radius: var(--radius-md);
    border: 1px solid rgba(195, 198, 209, 0.55);
    background: rgba(0, 30, 64, 0.03);
    color: var(--blue);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .06em;
    text-transform: uppercase;
    text-decoration: none;
    transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s ease, border-color .18s ease, color .18s ease;
  }

  .discover-card-cta:hover {
    background: var(--blue);
    color: #fff;
    border-color: var(--blue);
    transform: translate3d(0, -1px, 0);
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
