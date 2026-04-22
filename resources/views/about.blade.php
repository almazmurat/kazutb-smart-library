@extends('layouts.public')

@php
  $lang = request()->query('lang', 'ru');
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';
  $activePage = $activePage ?? 'about';
  $sessionUser = session('library.user');
  $isAuthenticated = ! empty($sessionUser);
  $workspaceHref = $isAuthenticated ? '/dashboard' : '/login';

  $routeWithLang = static function (string $path, array $query = []) use ($lang): string {
      if ($lang !== 'ru' && ! array_key_exists('lang', $query)) {
          $query['lang'] = $lang;
      }

      $queryString = http_build_query(array_filter($query, static fn ($value) => $value !== null && $value !== ''));

      return $path . ($queryString !== '' ? ('?' . $queryString) : '');
  };

  $copy = [
      'ru' => [
          'title' => $activePage === 'contacts'
              ? 'Контакты — KazUTB Smart Library'
              : 'О библиотеке — KazUTB Smart Library',
          'brand' => 'KazUTB Smart Library',
          'hero_eyebrow' => $activePage === 'contacts' ? 'Контакты' : 'О библиотеке',
          'hero_title' => $activePage === 'contacts'
              ? 'Связаться с библиотекой'
              : 'KazUTB Smart Library',
          'hero_body' => 'KazUTB Smart Library объединяет каталог, тематическую навигацию, доступ к печатным и цифровым материалам и рабочие маршруты для читателей и преподавателей университета.',
          'hero_body_secondary' => 'Отсюда можно быстро перейти к фонду, научным ресурсам и ключевым сервисам библиотеки без лишней навигации.',
          'hero_primary' => 'Открыть каталог',
          'hero_secondary' => 'Научные ресурсы',
          'about_eyebrow' => 'Миссия и фонд',
          'about_title' => 'Институциональная библиотека КазУТБ',
          'about_body' => 'Библиотека служит учебной и исследовательской деятельности университета: ведёт институциональный фонд, поддерживает каталог и электронные материалы, сохраняет научный архив и обеспечивает контролируемый доступ читателей и преподавателей.',
          'about_points' => [
              ['title' => 'Каталог и фонд', 'body' => 'Единый каталог печатных и цифровых материалов с тематической навигацией по УДК и быстрым переходом к записям.'],
              ['title' => 'Научный архив', 'body' => 'Институциональный репозиторий с проверенными научными работами и долговременным хранением.'],
              ['title' => 'Доступ и сопровождение', 'body' => 'Читательские маршруты, подборки, уведомления и поддержка библиотекарем в каждом ключевом сценарии.'],
          ],
          'about_cta' => 'Перейти в каталог',
          'contacts_eyebrow' => 'Контакты и режим',
          'contacts_title' => 'Как связаться с библиотекой',
          'contacts_body' => 'Если нужна помощь с поиском, доступом или работой с материалами, используйте реальные каналы связи университетской библиотеки.',
          'contacts' => [
              ['label' => 'Адрес', 'value' => 'Астана, ул. Кайыма Мухамедханова, 37A'],
              ['label' => 'Телефон (кампус)', 'value' => '+7 (7172) 64-58-58', 'href' => 'tel:+77172645858'],
              ['label' => 'Email', 'value' => 'library@kazutb.edu.kz', 'href' => 'mailto:library@kazutb.edu.kz'],
          ],
          'hours_title' => 'Режим работы',
          'hours' => [
              ['label' => 'Понедельник – Пятница', 'value' => '09:00 – 20:00'],
              ['label' => 'Суббота', 'value' => '10:00 – 16:00'],
              ['label' => 'Воскресенье', 'value' => 'Выходной'],
          ],
          'duty_eyebrow' => 'Дежурный библиотекарь',
          'duty_title' => 'Librarian-on-Duty',
          'duty_body' => $isAuthenticated
              ? 'Продолжите работу в личном кабинете — история, заявки и сообщения библиотекарю доступны в рабочей панели читателя.'
              : 'Войдите под университетской учётной записью, чтобы написать библиотекарю, посмотреть заявки и продолжить работу в личном кабинете.',
          'duty_cta' => $isAuthenticated ? 'Открыть личный кабинет' : 'Войти в систему',
          'cta_eyebrow' => 'Начать работу',
          'cta_title' => 'Перейдите в каталог',
          'cta_body' => 'Каталог — основная точка входа: поиск, тематическая навигация и доступ к фонду библиотеки.',
          'cta_link' => 'Открыть каталог',
          // Cluster B.3 — embedded location / fund rooms / visit notes (Contacts variant only).
          'location_eyebrow' => 'Как нас найти',
          'location_title' => 'Расположение и вход в библиотеку',
          'location_body' => 'Библиотека КазУТБ находится в главном учебном корпусе университета в Астане. Ниже — точный адрес, рабочий вход и ориентир по этажу, где размещены читательские залы.',
          'location_branches' => [
              [
                  'slug' => 'main-building',
                  'label' => 'Главный корпус',
                  'address' => 'Астана, ул. Кайыма Мухамедханова, 37A',
                  'entrance' => 'Вход со стороны главного фасада; внутренняя навигация — по указателям «Библиотека».',
                  'floor_note' => 'Читательские залы и фондовые комнаты — на 2-м этаже корпуса 1.',
              ],
          ],
          'location_map_label' => 'Схема расположения',
          'location_map_caption' => 'Статическая схема v1 — актуальный маршрут и интерактивная карта появятся позже.',
          'location_directions_cta' => 'Построить маршрут',
          'fund_rooms_eyebrow' => 'Фондовые комнаты',
          'fund_rooms_title' => 'Где находятся фонды библиотеки',
          'fund_rooms_body' => 'В главном корпусе для читателей открыты три фондовые комнаты. Это ориентир для посещения: где работать с каким фондом и к кому обращаться за помощью.',
          'fund_rooms' => [
              [
                  'room' => '1/200',
                  'fund_label' => 'Технологический фонд',
                  'branch' => 'Главный корпус',
                  'floor' => '2-й этаж',
                  'short_description' => 'Технические и естественно-научные дисциплины: инженерия, информатика, прикладная математика, технологии.',
                  'access_note' => 'Открыт для читателей в рабочие часы библиотеки.',
              ],
              [
                  'room' => '1/202',
                  'fund_label' => 'Фонд колледжа',
                  'branch' => 'Главный корпус',
                  'floor' => '2-й этаж',
                  'short_description' => 'Материалы для образовательных программ колледжа: учебная литература, справочные издания, методика.',
                  'access_note' => 'Приоритетно обслуживаются читатели колледжа; университетским читателям — по запросу.',
              ],
              [
                  'room' => '1/203',
                  'fund_label' => 'Экономический фонд библиотеки',
                  'branch' => 'Главный корпус',
                  'floor' => '2-й этаж',
                  'short_description' => 'Экономика, менеджмент, финансы, право и смежные социальные науки.',
                  'access_note' => 'Открыт для читателей в рабочие часы библиотеки.',
              ],
          ],
          'visit_notes_eyebrow' => 'Перед визитом',
          'visit_notes_title' => 'Что важно знать до визита',
          'visit_notes_body' => 'Несколько практических рекомендаций, которые помогут спокойно и результативно работать в читательских залах.',
          'visit_notes_items' => [
              [
                  'title' => 'Документ, удостоверяющий личность',
                  'body' => 'При входе в библиотеку может потребоваться университетский идентификатор или документ, подтверждающий статус читателя.',
              ],
              [
                  'title' => 'Тихие зоны',
                  'body' => 'Читательские залы — это зоны тихой работы. Звонки и разговоры ведутся вне рабочих зон; устройства — в беззвучном режиме.',
              ],
              [
                  'title' => 'Доступность и поддержка',
                  'body' => 'Если нужна помощь с ориентированием, доступом к материалам или специальные условия — обратитесь к библиотекарю на стойке или напишите на указанную выше почту.',
              ],
          ],
          'visit_notes_rules_cta' => 'Полные правила библиотеки',
      ],
      'kk' => [
          'title' => $activePage === 'contacts'
              ? 'Байланыс — KazUTB Smart Library'
              : 'Кітапхана туралы — KazUTB Smart Library',
          'brand' => 'KazUTB Smart Library',
          'hero_eyebrow' => $activePage === 'contacts' ? 'Байланыс' : 'Кітапхана туралы',
          'hero_title' => $activePage === 'contacts'
              ? 'Кітапханамен байланысу'
              : 'KazUTB Smart Library',
          'hero_body' => 'KazUTB Smart Library — университеттің оқу және зерттеу қызметіне арналған каталог, тақырыптық навигация, баспа және цифрлық материалдарға қолжетімділік пен оқырман мен оқытушыға арналған негізгі маршруттарды біріктіретін институционалдық кітапхана.',
          'hero_body_secondary' => 'Осы беттен қорға, ғылыми ресурстарға және кітапхананың негізгі сервистеріне артық түсіндірмесіз тез өтуге болады.',
          'hero_primary' => 'Каталогты ашу',
          'hero_secondary' => 'Ғылыми ресурстар',
          'about_eyebrow' => 'Миссия және қор',
          'about_title' => 'ҚазУТБ институционалдық кітапханасы',
          'about_body' => 'Кітапхана университеттің оқу-зерттеу қызметіне қызмет етеді: институционалдық қорды жүргізеді, каталог пен электрондық материалдарды қолдайды, ғылыми мұрағатты сақтайды және оқырман мен оқытушы үшін бақыланатын қолжетімділікті қамтамасыз етеді.',
          'about_points' => [
              ['title' => 'Каталог және қор', 'body' => 'Баспа және цифрлық материалдардың бірыңғай каталогы: ӘОЖ бойынша тақырыптық навигация және қажет жазбаға тез өту.'],
              ['title' => 'Ғылыми мұрағат', 'body' => 'Тексерілген ғылыми еңбектер мен ұзақ мерзімді сақтауға арналған институционалдық репозиторий.'],
              ['title' => 'Қолжетімділік пен сүйемелдеу', 'body' => 'Оқырман маршруттары, жинақтар, хабарламалар және негізгі сценарийлерде кітапханашының қолдауы.'],
          ],
          'about_cta' => 'Каталогқа өту',
          'contacts_eyebrow' => 'Байланыс және кесте',
          'contacts_title' => 'Кітапханамен қалай байланысуға болады',
          'contacts_body' => 'Іздеу, қолжетімділік немесе материалдармен жұмыс бойынша көмек қажет болса, университет кітапханасының нақты байланыс арналарын пайдаланыңыз.',
          'contacts' => [
              ['label' => 'Мекенжай', 'value' => 'Астана, Қайым Мұхамедханов көшесі, 37A'],
              ['label' => 'Телефон (кампус)', 'value' => '+7 (7172) 64-58-58', 'href' => 'tel:+77172645858'],
              ['label' => 'Email', 'value' => 'library@kazutb.edu.kz', 'href' => 'mailto:library@kazutb.edu.kz'],
          ],
          'hours_title' => 'Жұмыс уақыты',
          'hours' => [
              ['label' => 'Дүйсенбі – Жұма', 'value' => '09:00 – 20:00'],
              ['label' => 'Сенбі', 'value' => '10:00 – 16:00'],
              ['label' => 'Жексенбі', 'value' => 'Демалыс'],
          ],
          'duty_eyebrow' => 'Кезекші кітапханашы',
          'duty_title' => 'Librarian-on-Duty',
          'duty_body' => $isAuthenticated
              ? 'Жеке кабинетте жұмысты жалғастырыңыз — тарих, өтінімдер және кітапханашыға хабарламалар оқырманның жұмыс панелінде қолжетімді.'
              : 'Кітапханашыға жазу, өтінімдерді қарау және жеке кабинетте жұмысты жалғастыру үшін университеттің есептік жазбасымен кіріңіз.',
          'duty_cta' => $isAuthenticated ? 'Жеке кабинетті ашу' : 'Жүйеге кіру',
          'cta_eyebrow' => 'Жұмысты бастау',
          'cta_title' => 'Каталогқа өтіңіз',
          'cta_body' => 'Каталог — негізгі кіру нүктесі: іздеу, тақырыптық навигация және кітапхана қорына қолжетімділік.',
          'cta_link' => 'Каталогты ашу',
          // Cluster B.3 — embedded location / fund rooms / visit notes (Contacts variant only).
          'location_eyebrow' => 'Бізді қалай табуға болады',
          'location_title' => 'Кітапхананың орналасуы және кіру',
          'location_body' => 'ҚазУТБ кітапханасы Астанадағы университеттің басты оқу корпусында орналасқан. Төменде нақты мекенжай, жұмыс кірісі және оқу залдары орналасқан қабат бойынша бағдар көрсетілген.',
          'location_branches' => [
              [
                  'slug' => 'main-building',
                  'label' => 'Басты корпус',
                  'address' => 'Астана, Қайым Мұхамедханов көшесі, 37A',
                  'entrance' => 'Басты фасад жағындағы кіріс; ішкі навигация — «Кітапхана» көрсеткіштері бойынша.',
                  'floor_note' => 'Оқу залдары мен қор бөлмелері — 1-корпустың 2-қабатында.',
              ],
          ],
          'location_map_label' => 'Орналасу сызбасы',
          'location_map_caption' => 'v1 нұсқасындағы статикалық сызба — өзекті маршрут пен интерактивті карта кейінірек қосылады.',
          'location_directions_cta' => 'Бағыт құру',
          'fund_rooms_eyebrow' => 'Қор бөлмелері',
          'fund_rooms_title' => 'Кітапхана қорлары қайда орналасқан',
          'fund_rooms_body' => 'Басты корпуста оқырмандарға үш қор бөлмесі ашық. Бұл — кіму қандай қормен жұмыс істейтінін және көмек үшін кімге жүгінетінін түсінуге арналған бағдар.',
          'fund_rooms' => [
              [
                  'room' => '1/200',
                  'fund_label' => 'Технологиялық қор',
                  'branch' => 'Басты корпус',
                  'floor' => '2-қабат',
                  'short_description' => 'Техникалық және жаратылыстану пәндері: инженерия, информатика, қолданбалы математика, технологиялар.',
                  'access_note' => 'Кітапхананың жұмыс сағаттарында оқырмандарға ашық.',
              ],
              [
                  'room' => '1/202',
                  'fund_label' => 'Колледж қоры',
                  'branch' => 'Басты корпус',
                  'floor' => '2-қабат',
                  'short_description' => 'Колледж білім беру бағдарламаларына арналған материалдар: оқу әдебиеті, анықтамалық басылымдар, әдістеме.',
                  'access_note' => 'Колледж оқырмандары басымдықпен қызмет көреді; университет оқырмандарына — сұраныс бойынша.',
              ],
              [
                  'room' => '1/203',
                  'fund_label' => 'Кітапхананың экономикалық қоры',
                  'branch' => 'Басты корпус',
                  'floor' => '2-қабат',
                  'short_description' => 'Экономика, менеджмент, қаржы, құқық және іргелес әлеуметтік ғылымдар.',
                  'access_note' => 'Кітапхананың жұмыс сағаттарында оқырмандарға ашық.',
              ],
          ],
          'visit_notes_eyebrow' => 'Келер алдында',
          'visit_notes_title' => 'Келуден бұрын нені ескеру қажет',
          'visit_notes_body' => 'Оқу залдарында тыныш және нәтижелі жұмыс істеуге көмектесетін бірнеше практикалық ұсыныс.',
          'visit_notes_items' => [
              [
                  'title' => 'Жеке куәлік',
                  'body' => 'Кітапханаға кірер кезде университет идентификаторы немесе оқырман мәртебесін растайтын құжат талап етілуі мүмкін.',
              ],
              [
                  'title' => 'Тыныш аймақтар',
                  'body' => 'Оқу залдары — тыныш жұмыс аймақтары. Қоңыраулар мен әңгімелер жұмыс аймағынан тыс жүргізіледі; құрылғылар — үнсіз режимде.',
              ],
              [
                  'title' => 'Қолжетімділік және қолдау',
                  'body' => 'Бағдар, материалдарға қолжетімділік немесе арнайы жағдайлар бойынша көмек қажет болса — стендтегі кітапханашыға жүгініңіз немесе жоғарыда көрсетілген поштаға жазыңыз.',
              ],
          ],
          'visit_notes_rules_cta' => 'Кітапхананың толық ережелері',
      ],
      'en' => [
          'title' => $activePage === 'contacts'
              ? 'Contacts — KazUTB Smart Library'
              : 'About — KazUTB Smart Library',
          'brand' => 'KazUTB Smart Library',
          'hero_eyebrow' => $activePage === 'contacts' ? 'Contacts' : 'About the Library',
          'hero_title' => $activePage === 'contacts'
              ? 'Reach the library'
              : 'KazUTB Smart Library',
          'hero_body' => 'KazUTB Smart Library is the institutional library of KazUTB: catalog search, subject navigation, access to print and digital materials, and core routes for readers and faculty converge on one surface.',
          'hero_body_secondary' => 'From here, people move quickly into the collection, the scholarly resources layer, and the everyday services the library runs.',
          'hero_primary' => 'Open catalog',
          'hero_secondary' => 'Research resources',
          'about_eyebrow' => 'Mission & collection',
          'about_title' => 'The institutional library of KazUTB',
          'about_body' => 'The library supports teaching and research at the university: it maintains the institutional collection, runs the catalog and digital materials layer, preserves the scholarly archive, and provides controlled access for readers and faculty.',
          'about_points' => [
              ['title' => 'Catalog & collection', 'body' => 'A single catalog for print and digital materials, with UDC subject navigation and direct routes to the records that matter.'],
              ['title' => 'Scholarly archive', 'body' => 'An institutional repository that preserves reviewed scholarly works and keeps long-term access open.'],
              ['title' => 'Access & stewardship', 'body' => 'Reader journeys, shortlists, notifications, and librarian support across every key scenario.'],
          ],
          'about_cta' => 'Go to the catalog',
          'contacts_eyebrow' => 'Contacts and hours',
          'contacts_title' => 'How to reach the library',
          'contacts_body' => 'Use the library contact details when you need help with search, access, or working with materials.',
          'contacts' => [
              ['label' => 'Address', 'value' => '37A Kayym Mukhamedkhanov Street, Astana'],
              ['label' => 'Phone (campus)', 'value' => '+7 (7172) 64-58-58', 'href' => 'tel:+77172645858'],
              ['label' => 'Email', 'value' => 'library@kazutb.edu.kz', 'href' => 'mailto:library@kazutb.edu.kz'],
          ],
          'hours_title' => 'Opening hours',
          'hours' => [
              ['label' => 'Monday – Friday', 'value' => '09:00 – 20:00'],
              ['label' => 'Saturday', 'value' => '10:00 – 16:00'],
              ['label' => 'Sunday', 'value' => 'Closed'],
          ],
          'duty_eyebrow' => 'Librarian-on-Duty',
          'duty_title' => 'Librarian-on-Duty',
          'duty_body' => $isAuthenticated
              ? 'Continue in the member workspace — history, reservations, and messages to the librarian are available from the reader dashboard.'
              : 'Sign in with your university account to message a librarian, review your reservations, and continue in the member workspace.',
          'duty_cta' => $isAuthenticated ? 'Open member workspace' : 'Sign in',
          'cta_eyebrow' => 'Start here',
          'cta_title' => 'Go to the catalog',
          'cta_body' => 'The catalog is the primary entry point: search, subject navigation, and access to the library collection.',
          'cta_link' => 'Open catalog',
          // Cluster B.3 — embedded location / fund rooms / visit notes (Contacts variant only).
          'location_eyebrow' => 'How to find us',
          'location_title' => 'Library location and entrance',
          'location_body' => 'The KazUTB Library is in the main university teaching building in Astana. Below are the exact address, the working entrance, and the floor where the reading rooms are located.',
          'location_branches' => [
              [
                  'slug' => 'main-building',
                  'label' => 'Main building',
                  'address' => '37A Kayym Mukhamedkhanov Street, Astana',
                  'entrance' => 'Use the main-facade entrance; follow the internal "Library" signage.',
                  'floor_note' => 'Reading rooms and fund rooms are on the 2nd floor of Building 1.',
              ],
          ],
          'location_map_label' => 'Location diagram',
          'location_map_caption' => 'Static v1 diagram — a live route and an interactive map will follow.',
          'location_directions_cta' => 'Get directions',
          'fund_rooms_eyebrow' => 'Fund rooms',
          'fund_rooms_title' => 'Where the library funds are located',
          'fund_rooms_body' => 'Three fund rooms in the main building are open to readers. This is a wayfinding reference: where to work with which fund, and who to ask for help on site.',
          'fund_rooms' => [
              [
                  'room' => '1/200',
                  'fund_label' => 'Technology fund',
                  'branch' => 'Main building',
                  'floor' => '2nd floor',
                  'short_description' => 'Technical and natural-science disciplines: engineering, computer science, applied mathematics, technology.',
                  'access_note' => 'Open to readers during library working hours.',
              ],
              [
                  'room' => '1/202',
                  'fund_label' => 'College fund',
                  'branch' => 'Main building',
                  'floor' => '2nd floor',
                  'short_description' => 'Materials for the college programs: textbooks, reference works, and teaching methodology.',
                  'access_note' => 'College readers served first; university readers served on request.',
              ],
              [
                  'room' => '1/203',
                  'fund_label' => 'Economic fund of the library',
                  'branch' => 'Main building',
                  'floor' => '2nd floor',
                  'short_description' => 'Economics, management, finance, law, and related social sciences.',
                  'access_note' => 'Open to readers during library working hours.',
              ],
          ],
          'visit_notes_eyebrow' => 'Before you visit',
          'visit_notes_title' => 'What to know before you visit',
          'visit_notes_body' => 'A few practical notes that make it easier to work in the reading rooms calmly and productively.',
          'visit_notes_items' => [
              [
                  'title' => 'ID requirement',
                  'body' => 'You may be asked for a university ID or another document that confirms your reader status when you enter the library.',
              ],
              [
                  'title' => 'Quiet zones',
                  'body' => 'Reading rooms are quiet-work zones. Please take calls and conversations outside the working areas and keep devices on silent.',
              ],
              [
                  'title' => 'Accessibility and support',
                  'body' => 'If you need help with wayfinding, access to materials, or specific accommodations, ask the librarian at the desk or write to the email shown above.',
              ],
          ],
          'visit_notes_rules_cta' => 'Full library rules',
      ],
  ][$lang];
@endphp

@section('title', $copy['title'])

@section('content')
  <section class="page-hero about-hero">
    <div class="container about-hero-shell">
      <div class="about-hero-copy">
        <div class="eyebrow eyebrow--cyan">{{ $copy['hero_eyebrow'] }}</div>
        <h1>{{ $copy['hero_title'] }}</h1>
        <p>{{ $copy['hero_body'] }}</p>
        <p class="about-hero-secondary">{{ $copy['hero_body_secondary'] }}</p>
        <div class="about-hero-actions">
          <a href="{{ $routeWithLang('/catalog') }}" class="btn btn-primary">{{ $copy['hero_primary'] }}</a>
          <a href="{{ $routeWithLang('/resources') }}" class="btn btn-ghost">{{ $copy['hero_secondary'] }}</a>
        </div>
        <div class="about-hero-media" aria-hidden="true">
          <div class="about-hero-media__chips">
            <span>{{ $copy['brand'] }}</span>
            <span>Catalog</span>
            <span>Archive</span>
          </div>
        </div>
      </div>

      <aside class="about-contact-card" data-section="contacts-summary">
        <span>{{ $copy['contacts_eyebrow'] }}</span>
        <strong>{{ $copy['contacts_title'] }}</strong>
        <p>{{ $copy['contacts_body'] }}</p>
        <div class="about-contact-list">
          @foreach($copy['contacts'] as $item)
            <div class="contact-row">
              <span>{{ $item['label'] }}</span>
              @if(!empty($item['href']))
                <a href="{{ $item['href'] }}">{{ $item['value'] }}</a>
              @else
                <strong>{{ $item['value'] }}</strong>
              @endif
            </div>
          @endforeach
        </div>
        <div class="about-hours-list">
          <div class="contact-row contact-row--heading">
            <span>{{ $copy['hours_title'] }}</span>
          </div>
          @foreach($copy['hours'] as $item)
            <div class="contact-row">
              <span>{{ $item['label'] }}</span>
              <strong>{{ $item['value'] }}</strong>
            </div>
          @endforeach
        </div>
      </aside>
    </div>
  </section>

  @if($activePage === 'contacts')
    {{-- /contacts surface: Librarian-on-Duty block rendered first, then mission narrative. --}}
    <section class="page-section" data-section="librarian-on-duty">
      <div class="container">
        <div class="about-cta-band">
          <div>
            <div class="eyebrow eyebrow--green">{{ $copy['duty_eyebrow'] }}</div>
            <h2>{{ $copy['duty_title'] }}</h2>
            <p>{{ $copy['duty_body'] }}</p>
          </div>
          <div class="about-cta-links">
            <a href="{{ $routeWithLang($workspaceHref) }}">{{ $copy['duty_cta'] }}</a>
          </div>
        </div>
      </div>
    </section>

    <section class="page-section" data-section="about-mission">
      <div class="container">
        <div class="section-head">
          <div>
            <div class="eyebrow eyebrow--violet">{{ $copy['about_eyebrow'] }}</div>
            <h2>{{ $copy['about_title'] }}</h2>
            <p>{{ $copy['about_body'] }}</p>
          </div>
        </div>

        <div class="about-card-grid">
          @foreach($copy['about_points'] as $card)
            <article class="about-card">
              <h3>{{ $card['title'] }}</h3>
              <p>{{ $card['body'] }}</p>
            </article>
          @endforeach
        </div>
      </div>
    </section>
  @else
    {{-- /about surface: mission narrative first, then Librarian-on-Duty block. --}}
    <section class="page-section" data-section="about-mission">
      <div class="container">
        <div class="section-head">
          <div>
            <div class="eyebrow eyebrow--violet">{{ $copy['about_eyebrow'] }}</div>
            <h2>{{ $copy['about_title'] }}</h2>
            <p>{{ $copy['about_body'] }}</p>
          </div>
        </div>

        <div class="about-card-grid">
          @foreach($copy['about_points'] as $card)
            <article class="about-card">
              <h3>{{ $card['title'] }}</h3>
              <p>{{ $card['body'] }}</p>
            </article>
          @endforeach
        </div>
      </div>
    </section>

    <section class="page-section" data-section="librarian-on-duty">
      <div class="container">
        <div class="about-cta-band">
          <div>
            <div class="eyebrow eyebrow--green">{{ $copy['duty_eyebrow'] }}</div>
            <h2>{{ $copy['duty_title'] }}</h2>
            <p>{{ $copy['duty_body'] }}</p>
          </div>
          <div class="about-cta-links">
            <a href="{{ $routeWithLang($workspaceHref) }}">{{ $copy['duty_cta'] }}</a>
          </div>
        </div>
      </div>
    </section>
  @endif

  @if($activePage === 'contacts')
    {{-- Cluster B.3 — embedded location / fund rooms / visit notes, only on /contacts. --}}
    <section class="page-section" data-section="contacts-location">
      <div class="container">
        <div class="section-head">
          <div>
            <div class="eyebrow eyebrow--cyan">{{ $copy['location_eyebrow'] }}</div>
            <h2>{{ $copy['location_title'] }}</h2>
            <p>{{ $copy['location_body'] }}</p>
          </div>
        </div>

        <div class="contacts-location-grid">
          <div class="contacts-location-branches">
            @foreach($copy['location_branches'] as $branch)
              <article class="contacts-location-card" data-branch-slot data-branch-slug="{{ $branch['slug'] }}">
                <h3>{{ $branch['label'] }}</h3>
                <p class="contacts-location-address">{{ $branch['address'] }}</p>
                <p class="contacts-location-entrance">{{ $branch['entrance'] }}</p>
                <p class="contacts-location-floor">{{ $branch['floor_note'] }}</p>
                <a class="contacts-location-cta" href="https://www.google.com/maps/search/?api=1&amp;query={{ urlencode($branch['address']) }}" target="_blank" rel="noopener noreferrer" data-test-id="contacts-location-directions">
                  {{ $copy['location_directions_cta'] }}
                </a>
              </article>
            @endforeach
          </div>

          <figure class="contacts-location-map" aria-label="{{ $copy['location_map_label'] }}" data-test-id="contacts-location-map">
            <div class="contacts-location-map__canvas" role="img" aria-hidden="true">
              <span class="contacts-location-map__pin">📍</span>
              <span class="contacts-location-map__label">{{ $copy['location_map_label'] }}</span>
            </div>
            <figcaption>{{ $copy['location_map_caption'] }}</figcaption>
          </figure>
        </div>
      </div>
    </section>

    <section class="page-section" data-section="contacts-fund-rooms">
      <div class="container">
        <div class="section-head">
          <div>
            <div class="eyebrow eyebrow--teal">{{ $copy['fund_rooms_eyebrow'] }}</div>
            <h2>{{ $copy['fund_rooms_title'] }}</h2>
            <p>{{ $copy['fund_rooms_body'] }}</p>
          </div>
        </div>

        <div class="contacts-fund-grid">
          @foreach($copy['fund_rooms'] as $room)
            <article class="contacts-fund-card" data-fund-room-slot data-room-code="{{ $room['room'] }}">
              <div class="contacts-fund-card__head">
                <span class="contacts-fund-card__room">{{ $room['room'] }}</span>
                <span class="contacts-fund-card__floor">{{ $room['floor'] }}</span>
              </div>
              <h3 class="contacts-fund-card__label">{{ $room['fund_label'] }}</h3>
              <p class="contacts-fund-card__branch">{{ $room['branch'] }}</p>
              <p class="contacts-fund-card__desc">{{ $room['short_description'] }}</p>
              <p class="contacts-fund-card__access">{{ $room['access_note'] }}</p>
            </article>
          @endforeach
        </div>
      </div>
    </section>

    <section class="page-section" data-section="contacts-visit-notes">
      <div class="container">
        <div class="section-head">
          <div>
            <div class="eyebrow eyebrow--violet">{{ $copy['visit_notes_eyebrow'] }}</div>
            <h2>{{ $copy['visit_notes_title'] }}</h2>
            <p>{{ $copy['visit_notes_body'] }}</p>
          </div>
        </div>

        <ul class="contacts-visit-list">
          @foreach($copy['visit_notes_items'] as $note)
            <li class="contacts-visit-item">
              <h3>{{ $note['title'] }}</h3>
              <p>{{ $note['body'] }}</p>
            </li>
          @endforeach
        </ul>

        <div class="contacts-visit-footer">
          <a class="contacts-visit-rules-cta" href="{{ $routeWithLang('/rules') }}" data-test-id="contacts-visit-rules-link">
            {{ $copy['visit_notes_rules_cta'] }}
          </a>
        </div>
      </div>
    </section>
  @endif

  <section class="page-section" data-section="catalog-cta">
    <div class="container">
      <div class="about-cta-band">
        <div>
          <div class="eyebrow eyebrow--blue">{{ $copy['cta_eyebrow'] }}</div>
          <h2>{{ $copy['cta_title'] }}</h2>
          <p>{{ $copy['cta_body'] }}</p>
        </div>
        <div class="about-cta-links">
          <a href="{{ $routeWithLang('/catalog') }}">{{ $copy['cta_link'] }}</a>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .about-hero-shell {
    display: grid;
    grid-template-columns: minmax(0, 1.25fr) minmax(280px, 360px);
    gap: 28px;
    align-items: stretch;
    text-align: left;
    animation: aboutReveal .45s cubic-bezier(0.2, 0.8, 0.2, 1) both;
  }

  .about-hero-copy {
    max-width: 760px;
  }

  .about-hero-copy h1 {
    margin-bottom: 14px;
  }

  .about-hero-copy p {
    margin: 0;
    color: var(--muted);
    line-height: 1.72;
    max-width: 62ch;
  }

  .about-hero-secondary {
    margin-top: 12px;
  }

  .about-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 18px;
  }

  .about-hero-media {
    position: relative;
    min-height: 196px;
    margin-top: 24px;
    border: 1px solid rgba(195,198,209,.5);
    border-radius: var(--radius-lg);
    overflow: hidden;
    background:
      linear-gradient(180deg, rgba(255,255,255,.08), rgba(255,255,255,.02)),
      linear-gradient(135deg, rgba(0,30,64,.06), rgba(20,105,109,.03)),
      url('{{ asset('trust-panel-library.svg') }}') center/cover no-repeat;
    box-shadow: inset 0 1px 0 rgba(255,255,255,.42);
  }

  .about-hero-media::after {
    content: '';
    position: absolute;
    inset: auto 0 0;
    height: 58%;
    background: linear-gradient(180deg, rgba(255,255,255,0), rgba(248,249,250,.94));
  }

  .about-hero-media__chips {
    position: absolute;
    left: 18px;
    right: 18px;
    bottom: 18px;
    z-index: 1;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  .about-hero-media__chips span {
    display: inline-flex;
    align-items: center;
    min-height: 32px;
    padding: 0 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.84);
    border: 1px solid rgba(195,198,209,.58);
    color: var(--blue);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    box-shadow: 0 8px 18px rgba(25,28,29,.04);
  }

  .about-cta-links a {
    display: inline-flex;
    align-items: center;
    padding: 9px 12px;
    border-radius: var(--radius-md);
    border: 1px solid rgba(195,198,209,.55);
    background: rgba(255,255,255,.84);
    color: var(--blue);
    font-size: 12px;
    font-weight: 700;
  }

  .about-card,
  .about-contact-card {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .about-card:hover,
  .about-contact-card:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(20,105,109,.18);
  }

  .about-contact-card {
    padding: 22px;
    display: grid;
    align-content: start;
    gap: 10px;
  }

  .about-contact-card > span,
  .contact-row span {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--cyan);
  }

  .about-contact-card > strong {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.7rem;
    line-height: 1.08;
    color: var(--blue);
  }

  .about-contact-card > p,
  .about-card p {
    margin: 0;
    color: var(--muted);
    line-height: 1.68;
  }

  .about-contact-list,
  .about-hours-list {
    display: grid;
    gap: 10px;
  }

  .contact-row {
    display: grid;
    gap: 4px;
    padding-top: 12px;
    border-top: 1px solid rgba(195,198,209,.45);
  }

  .contact-row--heading {
    padding-top: 4px;
  }

  .contact-row strong,
  .contact-row a {
    color: var(--text);
    font-weight: 700;
    text-decoration: none;
  }

  .contact-row a:hover {
    color: var(--blue);
  }

  .section-head {
    margin-bottom: 16px;
  }

  .section-head p {
    margin: 6px 0 0;
    color: var(--muted);
  }

  .about-card-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }

  .about-card {
    padding: 22px;
  }

  .about-card h3 {
    margin: 0 0 10px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.45rem;
    color: var(--blue);
    line-height: 1.12;
  }

  .page-section {
    padding-top: 28px;
    padding-bottom: 28px;
  }

  .about-cta-band {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 24px;
    background: linear-gradient(135deg, rgba(0,30,64,.98), rgba(14,54,87,.94));
    color: #fff;
    display: grid;
    grid-template-columns: 1.05fr .95fr;
    gap: 24px;
    align-items: center;
  }

  .about-cta-band h2 {
    margin: 10px 0 8px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: 2rem;
    line-height: 1.02;
    color: #fff;
  }

  .about-cta-band p {
    margin: 0;
    color: rgba(255,255,255,.76);
    line-height: 1.68;
  }

  .about-cta-links {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: flex-end;
  }

  .about-cta-links a {
    background: rgba(255,255,255,.12);
    border-color: rgba(255,255,255,.16);
    color: #fff;
    text-decoration: none;
  }

  @keyframes aboutReveal {
    from {
      opacity: 0;
      transform: translate3d(0, 18px, 0);
    }
    to {
      opacity: 1;
      transform: translate3d(0, 0, 0);
    }
  }

  @media (max-width: 960px) {
    .about-hero-shell,
    .about-card-grid,
    .about-cta-band {
      grid-template-columns: 1fr;
    }

    .about-cta-links {
      justify-content: flex-start;
    }
  }

  /* Cluster B.3 — embedded location / fund rooms / visit notes. */
  .contacts-location-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1.15fr);
    gap: 24px;
    margin-top: 20px;
  }

  .contacts-location-branches {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .contacts-location-card {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 22px;
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
  }

  .contacts-location-card h3 {
    margin: 0 0 8px;
    font-family: var(--font-head, 'Newsreader', serif);
    font-size: 1.25rem;
    color: var(--ink, #191c1d);
  }

  .contacts-location-address,
  .contacts-location-entrance,
  .contacts-location-floor {
    margin: 0 0 6px;
    color: var(--muted, #43474e);
    line-height: 1.6;
    font-size: .95rem;
  }

  .contacts-location-cta {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 10px;
    padding: 8px 12px;
    border-radius: var(--radius-md);
    border: 1px solid rgba(195,198,209,.55);
    background: rgba(255,255,255,.9);
    color: var(--teal, #006a6a);
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
  }

  .contacts-location-cta:hover {
    border-color: rgba(0,106,106,.45);
    color: var(--blue, #001f3f);
  }

  .contacts-location-map {
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .contacts-location-map__canvas {
    position: relative;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    min-height: 280px;
    background:
      repeating-linear-gradient(0deg, rgba(0,106,106,.06) 0 1px, transparent 1px 40px),
      repeating-linear-gradient(90deg, rgba(0,106,106,.06) 0 1px, transparent 1px 40px),
      linear-gradient(135deg, rgba(0,30,64,.05), rgba(20,105,109,.06));
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: var(--blue, #001f3f);
  }

  .contacts-location-map__pin {
    font-size: 1.8rem;
  }

  .contacts-location-map__label {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--teal, #006a6a);
  }

  .contacts-location-map figcaption {
    color: var(--muted, #43474e);
    font-size: .85rem;
    line-height: 1.5;
  }

  .contacts-fund-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin-top: 20px;
  }

  .contacts-fund-card {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px;
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .contacts-fund-card__head {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(195,198,209,.45);
  }

  .contacts-fund-card__room {
    font-family: var(--font-head, 'Newsreader', serif);
    font-size: 1.4rem;
    color: var(--blue, #001f3f);
    font-weight: 700;
    letter-spacing: .02em;
  }

  .contacts-fund-card__floor {
    display: inline-flex;
    padding: 3px 10px;
    border-radius: 999px;
    background: rgba(0,106,106,.08);
    color: var(--teal, #006a6a);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
  }

  .contacts-fund-card__label {
    margin: 4px 0 0;
    font-family: var(--font-head, 'Newsreader', serif);
    font-size: 1.05rem;
    color: var(--ink, #191c1d);
  }

  .contacts-fund-card__branch {
    margin: 0;
    font-size: .85rem;
    color: var(--muted, #43474e);
    font-weight: 600;
  }

  .contacts-fund-card__desc {
    margin: 4px 0 0;
    color: var(--muted, #43474e);
    font-size: .95rem;
    line-height: 1.55;
  }

  .contacts-fund-card__access {
    margin: auto 0 0;
    padding-top: 8px;
    border-top: 1px solid rgba(195,198,209,.45);
    color: var(--teal, #006a6a);
    font-size: .85rem;
    font-weight: 600;
  }

  .contacts-visit-list {
    list-style: none;
    margin: 20px 0 0;
    padding: 0;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }

  .contacts-visit-item {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
  }

  .contacts-visit-item h3 {
    margin: 0 0 6px;
    font-family: var(--font-head, 'Newsreader', serif);
    font-size: 1.05rem;
    color: var(--ink, #191c1d);
  }

  .contacts-visit-item p {
    margin: 0;
    color: var(--muted, #43474e);
    line-height: 1.6;
    font-size: .95rem;
  }

  .contacts-visit-footer {
    margin-top: 18px;
  }

  .contacts-visit-rules-cta {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 14px;
    border-radius: var(--radius-md);
    border: 1px solid rgba(195,198,209,.55);
    background: rgba(255,255,255,.9);
    color: var(--blue, #001f3f);
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
  }

  .contacts-visit-rules-cta:hover {
    border-color: rgba(0,31,63,.4);
    color: var(--teal, #006a6a);
  }

  @media (max-width: 960px) {
    .contacts-location-grid,
    .contacts-fund-grid,
    .contacts-visit-list {
      grid-template-columns: 1fr;
    }
  }
</style>
@endsection
