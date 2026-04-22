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
          // Cluster B.4 — collection / fund narrative + institutional directory (About variant only).
          'collection_eyebrow' => 'Коллекция и фонд',
          'collection_title' => 'Что читатель найдёт в фонде библиотеки',
          'collection_body' => 'Фонд KazUTB Smart Library собран вокруг реальной академической программы университета: инженерия и технологии, экономика и менеджмент, социально-гуманитарные дисциплины и специальные материалы колледжа. Ниже — публичные ориентиры для читателя, не внутренняя отчётность.',
          'collection_areas' => [
              [
                  'slug' => 'technology',
                  'icon' => '🔧',
                  'title' => 'Инженерия и технологии',
                  'body' => 'Инженерные дисциплины, информатика, прикладная математика и технологические направления — основа технологического фонда университета.',
              ],
              [
                  'slug' => 'economy',
                  'icon' => '📊',
                  'title' => 'Экономика, менеджмент и право',
                  'body' => 'Экономические и управленческие дисциплины, финансы, право и смежные общественные науки — профильный фонд экономического направления.',
              ],
              [
                  'slug' => 'humanities',
                  'icon' => '📚',
                  'title' => 'Социально-гуманитарные дисциплины',
                  'body' => 'Языки, история, педагогика и общеобразовательные материалы, которые поддерживают преподавание и исследование за пределами профильных программ.',
              ],
              [
                  'slug' => 'college',
                  'icon' => '🎓',
                  'title' => 'Учебные материалы колледжа',
                  'body' => 'Учебная литература, справочные издания и методические материалы для образовательных программ колледжа, включая предвузовскую подготовку.',
              ],
          ],
          'directory_eyebrow' => 'Институциональный справочник',
          'directory_title' => 'Куда идти дальше',
          'directory_body' => 'Короткий справочник по ключевым институциональным страницам библиотеки — правилам, руководству и контактам.',
          'directory_rows' => [
              [
                  'slug' => 'rules',
                  'title' => 'Правила библиотеки',
                  'body' => 'Правила пользования фондом, цифровыми материалами и читательскими залами.',
                  'href' => '/rules',
                  'cta' => 'Открыть правила',
              ],
              [
                  'slug' => 'leadership',
                  'title' => 'Руководство библиотеки',
                  'body' => 'Состав и зоны ответственности руководства библиотеки университета.',
                  'href' => '/leadership',
                  'cta' => 'Открыть раздел',
              ],
              [
                  'slug' => 'contacts',
                  'title' => 'Контакты и расположение',
                  'body' => 'Адрес, режим работы, фондовые комнаты и способы связаться с библиотекой.',
                  'href' => '/contacts',
                  'cta' => 'Открыть контакты',
              ],
          ],
          // Cluster B.5 — canonical /about (docs/design-exports/about_library_canonical). About variant only.
          'canon_hero_eyebrow' => 'Учреждение',
          'canon_hero_title_a' => 'Сохраняем знание.',
          'canon_hero_title_b' => 'Поддерживаем исследования.',
          'canon_hero_body' => 'KazUTB Smart Library — университетская библиотека, соединяющая академическую традицию и цифровой сервис, чтобы поддерживать учебную и исследовательскую работу читателей университета.',
          'canon_hero_image_alt' => 'Читательский зал университетской библиотеки с книжными стеллажами и столами для работы',
          'canon_hero_badge_title' => 'Институциональная библиотека',
          'canon_hero_badge_body' => 'Поддержка академических программ кампуса и колледжа КазУТБ.',
          'canon_mission_title' => 'Наша миссия',
          'canon_mission_body' => 'Собирать, сохранять и открывать доступ к учебным и научным материалам, необходимым программам университета. Мы поддерживаем среду сосредоточенной академической работы, где цифровые сервисы остаются незаметными, а читатель и работа с материалами — в центре.',
          'canon_mission_cta' => 'Открыть каталог',
          'canon_stats' => [
              ['value' => '3', 'label' => 'Комнаты фонда'],
              ['value' => '3', 'label' => 'Языка интерфейса'],
          ],
          'canon_collection_header_title' => 'Профиль коллекции',
          'canon_collection_header_body' => 'Коллекция выстроена вокруг реальной академической программы КазУТБ и покрывает четыре ключевых направления.',
          'canon_directory_title' => 'Институциональный справочник',
          'canon_areas_material_icons' => [
              'technology' => 'science',
              'economy' => 'business_center',
              'humanities' => 'history_edu',
              'college' => 'school',
          ],
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
          // Cluster B.4 — collection / fund narrative + institutional directory (About variant only).
          'collection_eyebrow' => 'Жинақ және қор',
          'collection_title' => 'Оқырман кітапхана қорынан нені табады',
          'collection_body' => 'KazUTB Smart Library қоры университеттің нақты академиялық бағдарламасы айналасында жиналған: инженерия және технологиялар, экономика және менеджмент, әлеуметтік-гуманитарлық пәндер және колледждің арнайы материалдары. Төменде оқырманға арналған көпшілік бағдарлар берілген — ішкі есеп емес.',
          'collection_areas' => [
              [
                  'slug' => 'technology',
                  'icon' => '🔧',
                  'title' => 'Инженерия және технологиялар',
                  'body' => 'Инженерлік пәндер, информатика, қолданбалы математика және технологиялық бағыттар — университеттің технологиялық қорының негізі.',
              ],
              [
                  'slug' => 'economy',
                  'icon' => '📊',
                  'title' => 'Экономика, менеджмент және құқық',
                  'body' => 'Экономикалық және басқарушылық пәндер, қаржы, құқық және іргелес қоғамдық ғылымдар — экономикалық бағыттың бейіндік қоры.',
              ],
              [
                  'slug' => 'humanities',
                  'icon' => '📚',
                  'title' => 'Әлеуметтік-гуманитарлық пәндер',
                  'body' => 'Тілдер, тарих, педагогика және жалпы білім беру материалдары — бейіндік бағдарламалардан тыс оқыту мен зерттеуді қолдайды.',
              ],
              [
                  'slug' => 'college',
                  'icon' => '🎓',
                  'title' => 'Колледж оқу материалдары',
                  'body' => 'Колледждің білім беру бағдарламаларына арналған оқу әдебиеті, анықтамалық басылымдар мен әдістемелік материалдар, оның ішінде жоғары оқу алды дайындық.',
              ],
          ],
          'directory_eyebrow' => 'Институционалдық анықтамалық',
          'directory_title' => 'Әрі қарай қайда өту керек',
          'directory_body' => 'Кітапхананың негізгі институционалдық беттеріне — ережелерге, басшылыққа және байланысқа — қысқаша анықтамалық.',
          'directory_rows' => [
              [
                  'slug' => 'rules',
                  'title' => 'Кітапхана ережелері',
                  'body' => 'Қорды, цифрлық материалдарды және оқу залдарын пайдалану ережелері.',
                  'href' => '/rules',
                  'cta' => 'Ережелерді ашу',
              ],
              [
                  'slug' => 'leadership',
                  'title' => 'Кітапхана басшылығы',
                  'body' => 'Университет кітапханасы басшылығының құрамы және жауапкершілік аймақтары.',
                  'href' => '/leadership',
                  'cta' => 'Бөлімді ашу',
              ],
              [
                  'slug' => 'contacts',
                  'title' => 'Байланыс және орналасу',
                  'body' => 'Мекенжай, жұмыс режимі, қор бөлмелері және кітапханамен байланысу жолдары.',
                  'href' => '/contacts',
                  'cta' => 'Байланысты ашу',
              ],
          ],
          // Cluster B.5 — canonical /about (docs/design-exports/about_library_canonical). About variant only.
          'canon_hero_eyebrow' => 'Мекеме',
          'canon_hero_title_a' => 'Білімді сақтаймыз.',
          'canon_hero_title_b' => 'Зерттеуді қолдаймыз.',
          'canon_hero_body' => 'KazUTB Smart Library — академиялық дәстүр мен цифрлық қызметті біріктіріп, университет оқырмандарының оқу мен зерттеу жұмысын қолдайтын университет кітапханасы.',
          'canon_hero_image_alt' => 'Көп қабатты сөрелері мен оқу үстелдері бар университет кітапханасының оқу залы',
          'canon_hero_badge_title' => 'Институционалдық кітапхана',
          'canon_hero_badge_body' => 'KazUTB университеті мен колледжінің академиялық бағдарламаларын қолдау.',
          'canon_mission_title' => 'Біздің миссиямыз',
          'canon_mission_body' => 'Университет бағдарламаларына қажет оқу және зерттеу материалдарын жинау, сақтау және қолжетімді ету. Біз цифрлық қызмет байқалмай тұратын, академиялық жұмыстың өзі бірінші орынға шығатын ортаны қолдаймыз.',
          'canon_mission_cta' => 'Каталогты ашу',
          'canon_stats' => [
              ['value' => '3', 'label' => 'Қор бөлмелері'],
              ['value' => '3', 'label' => 'Интерфейс тілдері'],
          ],
          'canon_collection_header_title' => 'Қор профилі',
          'canon_collection_header_body' => 'Қор KazUTB академиялық бағдарламасының төңірегінде құрылған және төрт негізгі бағытты қамтиды.',
          'canon_directory_title' => 'Институционалдық анықтамалық',
          'canon_areas_material_icons' => [
              'technology' => 'science',
              'economy' => 'business_center',
              'humanities' => 'history_edu',
              'college' => 'school',
          ],
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
          // Cluster B.4 — collection / fund narrative + institutional directory (About variant only).
          'collection_eyebrow' => 'Collection and fund',
          'collection_title' => 'What readers find in the library collection',
          'collection_body' => 'The KazUTB Smart Library collection is built around the university\'s real academic programme: engineering and technology, economics and management, social sciences and humanities, and the dedicated college materials. The areas below are public reader-facing signposts — not internal stock accounting.',
          'collection_areas' => [
              [
                  'slug' => 'technology',
                  'icon' => '🔧',
                  'title' => 'Engineering and technology',
                  'body' => 'Engineering disciplines, computer science, applied mathematics, and technology tracks — the core of the university\'s technology fund.',
              ],
              [
                  'slug' => 'economy',
                  'icon' => '📊',
                  'title' => 'Economics, management and law',
                  'body' => 'Economics and management disciplines, finance, law, and adjacent social sciences — the profile fund for the economic track.',
              ],
              [
                  'slug' => 'humanities',
                  'icon' => '📚',
                  'title' => 'Social sciences and humanities',
                  'body' => 'Languages, history, pedagogy, and general-education materials that support teaching and research beyond the profile programmes.',
              ],
              [
                  'slug' => 'college',
                  'icon' => '🎓',
                  'title' => 'College teaching materials',
                  'body' => 'Textbooks, reference works, and teaching methodology for the college programmes, including pre-university preparation.',
              ],
          ],
          'directory_eyebrow' => 'Institutional directory',
          'directory_title' => 'Where to go next',
          'directory_body' => 'A short directory of the library\'s key institutional pages — rules, leadership, and contacts.',
          'directory_rows' => [
              [
                  'slug' => 'rules',
                  'title' => 'Library rules',
                  'body' => 'Rules for using the collection, digital materials, and the reading rooms.',
                  'href' => '/rules',
                  'cta' => 'Open the rules',
              ],
              [
                  'slug' => 'leadership',
                  'title' => 'Library leadership',
                  'body' => 'The leadership team of the university library and their areas of responsibility.',
                  'href' => '/leadership',
                  'cta' => 'Open the section',
              ],
              [
                  'slug' => 'contacts',
                  'title' => 'Contacts and location',
                  'body' => 'Address, opening hours, fund rooms, and ways to reach the library.',
                  'href' => '/contacts',
                  'cta' => 'Open contacts',
              ],
          ],
          // Cluster B.5 — canonical /about (docs/design-exports/about_library_canonical). About variant only.
          'canon_hero_eyebrow' => 'Institution',
          'canon_hero_title_a' => 'Preserving Knowledge.',
          'canon_hero_title_b' => 'Supporting Research.',
          'canon_hero_body' => 'The KazUTB Smart Library is the university library that brings together academic tradition and digital service to support the teaching and research work of the university\'s readers.',
          'canon_hero_image_alt' => 'Reading room of the university library with shelves and study desks',
          'canon_hero_badge_title' => 'Institutional library',
          'canon_hero_badge_body' => 'Supporting the academic programmes of the KazUTB university and college campus.',
          'canon_mission_title' => 'Our Mission',
          'canon_mission_body' => 'To collect, preserve and provide access to the teaching and research materials that the university\'s programmes rely on. We support an environment of focused academic work where digital services step back and the reader\'s work with materials comes first.',
          'canon_mission_cta' => 'Open catalog',
          'canon_stats' => [
              ['value' => '3', 'label' => 'Fund rooms'],
              ['value' => '3', 'label' => 'Interface languages'],
          ],
          'canon_collection_header_title' => 'The Collection Profile',
          'canon_collection_header_body' => 'The collection is built around the university\'s real academic programme and covers four core areas.',
          'canon_directory_title' => 'Institutional Directory',
          'canon_areas_material_icons' => [
              'technology' => 'science',
              'economy' => 'business_center',
              'humanities' => 'history_edu',
              'college' => 'school',
          ],
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
@if($activePage === 'contacts')
  {{-- Contacts variant: existing shell kept intact (shared hero + contacts branches + catalog-cta). --}}
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
@else
  {{-- Cluster B.5 — canonical-exact /about rebuild per docs/design-exports/about_library_canonical.
       Deliberately replaces the old About shell (shared page-hero about-hero with
       contacts aside, about-mission card grid, librarian-on-duty CTA band, the
       Cluster B.4 embedded sections and the trailing catalog-cta). The Contacts
       variant is unaffected. Markers: about-canonical-hero, about-canonical-mission-stats,
       about-canonical-collection, about-canonical-directory. --}}
  <div class="about-canonical">
    <section class="about-canonical__section about-canonical__hero" data-section="about-canonical-hero">
      <div class="about-canonical__hero-copy">
        <div class="about-canonical__eyebrow">
          <span class="material-symbols-outlined" aria-hidden="true">account_balance</span>
          <span>{{ $copy['canon_hero_eyebrow'] }}</span>
        </div>
        <h1 class="about-canonical__display">
          {{ $copy['canon_hero_title_a'] }}<br>
          <span class="about-canonical__display-italic">{{ $copy['canon_hero_title_b'] }}</span>
        </h1>
        <p class="about-canonical__lead">{{ $copy['canon_hero_body'] }}</p>
      </div>
      <div class="about-canonical__hero-media">
        <div class="about-canonical__hero-frame" aria-hidden="true">
          <div class="about-canonical__hero-frame-inner">
            <span class="material-symbols-outlined about-canonical__hero-frame-glyph" aria-hidden="true">local_library</span>
          </div>
          <div class="about-canonical__hero-frame-overlay"></div>
        </div>
        <div class="about-canonical__hero-badge" data-test-id="about-canonical-hero-badge">
          <p class="about-canonical__hero-badge-title">{{ $copy['canon_hero_badge_title'] }}</p>
          <p class="about-canonical__hero-badge-body">{{ $copy['canon_hero_badge_body'] }}</p>
        </div>
      </div>
    </section>

    <section class="about-canonical__section about-canonical__bento" data-section="about-canonical-mission-stats">
      <div class="about-canonical__mission-card">
        <div>
          <h2 class="about-canonical__mission-title">{{ $copy['canon_mission_title'] }}</h2>
          <p class="about-canonical__mission-body">{{ $copy['canon_mission_body'] }}</p>
        </div>
        <div class="about-canonical__mission-cta-row">
          <a class="about-canonical__mission-cta" href="{{ $routeWithLang('/catalog') }}" data-test-id="about-canonical-mission-cta">
            {{ $copy['canon_mission_cta'] }}
            <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
          </a>
        </div>
      </div>
      <div class="about-canonical__stats-card">
        <div class="about-canonical__stats-glow" aria-hidden="true"></div>
        <div class="about-canonical__stats-inner">
          @foreach($copy['canon_stats'] as $stat)
            <div class="about-canonical__stat" data-stat-slot>
              <p class="about-canonical__stat-value">{{ $stat['value'] }}</p>
              <p class="about-canonical__stat-label">{{ $stat['label'] }}</p>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="about-canonical__section about-canonical__collection" data-section="about-canonical-collection">
      <div class="about-canonical__collection-head">
        <h2 class="about-canonical__collection-title">{{ $copy['canon_collection_header_title'] }}</h2>
        <p class="about-canonical__collection-body">{{ $copy['canon_collection_header_body'] }}</p>
      </div>
      <div class="about-canonical__collection-grid">
        @foreach($copy['collection_areas'] as $area)
          <article class="about-canonical__area" data-collection-area data-area-slug="{{ $area['slug'] }}">
            <div class="about-canonical__area-icon" aria-hidden="true">
              <span class="material-symbols-outlined">{{ $copy['canon_areas_material_icons'][$area['slug']] ?? 'menu_book' }}</span>
            </div>
            <h3 class="about-canonical__area-title">{{ $area['title'] }}</h3>
            <p class="about-canonical__area-body">{{ $area['body'] }}</p>
          </article>
        @endforeach
      </div>
    </section>

    <section class="about-canonical__section about-canonical__directory" data-section="about-canonical-directory">
      <h2 class="about-canonical__directory-title">{{ $copy['canon_directory_title'] }}</h2>
      <div class="about-canonical__directory-list">
        @foreach($copy['directory_rows'] as $row)
          <div class="about-canonical__directory-row" data-directory-slot data-directory-slug="{{ $row['slug'] }}">
            <div class="about-canonical__directory-col about-canonical__directory-col--title">
              <h3>{{ $row['title'] }}</h3>
            </div>
            <div class="about-canonical__directory-col about-canonical__directory-col--body">
              <p>{{ $row['body'] }}</p>
            </div>
            <div class="about-canonical__directory-col about-canonical__directory-col--action">
              <a
                class="about-canonical__directory-arrow"
                href="{{ $routeWithLang($row['href']) }}"
                aria-label="{{ $row['cta'] }}"
                data-test-id="about-canonical-directory-link-{{ $row['slug'] }}"
              >
                <span class="material-symbols-outlined" aria-hidden="true">north_east</span>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    </section>
  </div>
@endif
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

  /* Cluster B.4 — embedded collection / fund narrative + institutional directory. */
  .about-collection-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    margin-top: 20px;
  }

  .about-collection-card {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 22px;
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .about-collection-card__icon {
    font-size: 1.6rem;
    line-height: 1;
  }

  .about-collection-card h3 {
    margin: 4px 0 0;
    font-family: var(--font-head, 'Newsreader', serif);
    font-size: 1.1rem;
    color: var(--ink, #191c1d);
  }

  .about-collection-card p {
    margin: 0;
    color: var(--muted, #43474e);
    line-height: 1.6;
    font-size: .95rem;
  }

  .about-directory-list {
    list-style: none;
    margin: 20px 0 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .about-directory-row {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    gap: 16px;
    padding: 18px 22px;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    transition: border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .about-directory-row:hover {
    border-color: rgba(0,31,63,.35);
    box-shadow: 0 8px 24px rgba(25,28,29,.05);
  }

  .about-directory-row__copy h3 {
    margin: 0 0 4px;
    font-family: var(--font-head, 'Newsreader', serif);
    font-size: 1.05rem;
    color: var(--ink, #191c1d);
  }

  .about-directory-row__copy p {
    margin: 0;
    color: var(--muted, #43474e);
    line-height: 1.55;
    font-size: .93rem;
  }

  .about-directory-row__cta {
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
    white-space: nowrap;
  }

  .about-directory-row__cta:hover {
    border-color: rgba(0,31,63,.4);
    color: var(--teal, #006a6a);
  }

  @media (max-width: 960px) {
    .about-collection-grid {
      grid-template-columns: 1fr;
    }

    .about-directory-row {
      grid-template-columns: 1fr;
    }

    .about-directory-row__cta {
      justify-self: flex-start;
    }
  }

  /* Cluster B.5 — canonical-exact /about (docs/design-exports/about_library_canonical).
     Scoped under .about-canonical so /contacts styles remain untouched. */
  .about-canonical {
    max-width: 1280px;
    margin: 0 auto;
    padding: 96px 16px 96px;
    color: #191c1d;
    font-family: 'Manrope', sans-serif;
  }

  @media (min-width: 768px) {
    .about-canonical {
      padding: 128px 24px 96px;
    }
  }

  @media (min-width: 1024px) {
    .about-canonical {
      padding-left: 32px;
      padding-right: 32px;
    }
  }

  .about-canonical__section {
    margin-bottom: 128px;
  }

  .about-canonical__section:last-child {
    margin-bottom: 80px;
  }

  /* Hero — 3/5 + 2/5 split, eyebrow + display heading + lead + image with badge */
  .about-canonical__hero {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 48px;
    margin-top: 48px;
  }

  @media (min-width: 768px) {
    .about-canonical__hero {
      flex-direction: row;
      margin-top: 80px;
    }
  }

  .about-canonical__hero-copy {
    position: relative;
    z-index: 10;
    display: flex;
    flex-direction: column;
    gap: 32px;
    width: 100%;
  }

  @media (min-width: 768px) {
    .about-canonical__hero-copy {
      width: 60%;
    }
  }

  .about-canonical__eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #006a6a;
    font-family: 'Manrope', sans-serif;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 4px;
  }

  .about-canonical__eyebrow .material-symbols-outlined {
    font-size: 16px;
  }

  .about-canonical__display {
    font-family: 'Newsreader', serif;
    font-weight: 300;
    font-size: 48px;
    line-height: 1.05;
    color: #000613;
    letter-spacing: -0.02em;
    margin: 0 0 0 -2px;
  }

  @media (min-width: 768px) {
    .about-canonical__display {
      font-size: 72px;
    }
  }

  .about-canonical__display-italic {
    font-style: italic;
    color: #001f3f;
  }

  .about-canonical__lead {
    font-family: 'Manrope', sans-serif;
    font-size: 18px;
    line-height: 1.65;
    color: #43474e;
    max-width: 640px;
    margin: 0;
  }

  @media (min-width: 768px) {
    .about-canonical__lead {
      font-size: 20px;
    }
  }

  .about-canonical__hero-media {
    position: relative;
    width: 100%;
  }

  @media (min-width: 768px) {
    .about-canonical__hero-media {
      width: 40%;
    }
  }

  .about-canonical__hero-frame {
    position: relative;
    aspect-ratio: 4 / 5;
    background: #e7e8e9;
    border-radius: 12px;
    overflow: hidden;
    z-index: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .about-canonical__hero-frame-inner {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #e1e3e4 0%, #d4e3ff 60%, #afc8f0 100%);
    mix-blend-mode: multiply;
    opacity: 0.9;
  }

  .about-canonical__hero-frame-glyph {
    font-size: 96px !important;
    color: #001f3f;
    opacity: 0.55;
  }

  .about-canonical__hero-frame-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,6,19,0.4), transparent);
  }

  .about-canonical__hero-badge {
    position: absolute;
    bottom: -32px;
    left: -32px;
    max-width: 280px;
    background: #ffffff;
    padding: 24px;
    border-radius: 8px;
    box-shadow: 0 24px 48px rgba(0,6,19,0.04);
    display: none;
  }

  @media (min-width: 1024px) {
    .about-canonical__hero-badge {
      display: block;
    }
  }

  .about-canonical__hero-badge-title {
    font-family: 'Newsreader', serif;
    font-size: 22px;
    color: #000613;
    margin: 0 0 8px;
  }

  .about-canonical__hero-badge-body {
    font-family: 'Manrope', sans-serif;
    font-size: 13px;
    color: #43474e;
    line-height: 1.5;
    margin: 0;
  }

  /* Mission + Stats bento — 2/3 light card + 1/3 dark stats card */
  .about-canonical__bento {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
  }

  @media (min-width: 768px) {
    .about-canonical__bento {
      grid-template-columns: 2fr 1fr;
    }
  }

  .about-canonical__mission-card {
    background: #f3f4f5;
    border-radius: 12px;
    padding: 32px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 48px;
  }

  @media (min-width: 768px) {
    .about-canonical__mission-card {
      padding: 48px;
    }
  }

  .about-canonical__mission-title {
    font-family: 'Newsreader', serif;
    font-size: 30px;
    color: #000613;
    margin: 0 0 24px;
  }

  .about-canonical__mission-body {
    font-family: 'Manrope', sans-serif;
    font-size: 18px;
    line-height: 1.7;
    color: #43474e;
    max-width: 720px;
    margin: 0;
  }

  .about-canonical__mission-cta-row {
    display: flex;
    gap: 16px;
    margin-top: 32px;
  }

  .about-canonical__mission-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    border: 0;
    border-bottom: 1px solid rgba(196,198,207,0.35);
    padding: 0 0 4px;
    font-family: 'Manrope', sans-serif;
    font-weight: 500;
    color: #006a6a;
    text-decoration: none;
    transition: border-color 0.2s ease;
  }

  .about-canonical__mission-cta:hover {
    border-bottom-color: #006a6a;
  }

  .about-canonical__mission-cta .material-symbols-outlined {
    font-size: 16px;
  }

  .about-canonical__stats-card {
    position: relative;
    background: #000613;
    color: #ffffff;
    border-radius: 12px;
    padding: 32px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    overflow: hidden;
  }

  @media (min-width: 768px) {
    .about-canonical__stats-card {
      padding: 48px;
    }
  }

  .about-canonical__stats-glow {
    position: absolute;
    top: 0;
    right: 0;
    width: 256px;
    height: 256px;
    background: #001f3f;
    border-radius: 9999px;
    filter: blur(48px);
    margin: -80px -80px 0 0;
    opacity: 0.5;
  }

  .about-canonical__stats-inner {
    position: relative;
    z-index: 10;
    display: flex;
    flex-direction: column;
    gap: 32px;
  }

  .about-canonical__stat-value {
    font-family: 'Newsreader', serif;
    font-weight: 300;
    font-size: 48px;
    line-height: 1;
    margin: 0 0 8px;
    color: #ffffff;
  }

  .about-canonical__stat-label {
    font-family: 'Manrope', sans-serif;
    font-size: 13px;
    color: #6f88ad;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    margin: 0;
  }

  /* Collection Profile — 4-column icon+heading+body grid */
  .about-canonical__collection-head {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: baseline;
    gap: 16px;
    margin-bottom: 48px;
  }

  @media (min-width: 768px) {
    .about-canonical__collection-head {
      flex-direction: row;
    }
  }

  .about-canonical__collection-title {
    font-family: 'Newsreader', serif;
    font-size: 36px;
    color: #000613;
    margin: 0;
  }

  .about-canonical__collection-body {
    font-family: 'Manrope', sans-serif;
    font-size: 15px;
    color: #43474e;
    max-width: 420px;
    margin: 0;
    line-height: 1.55;
  }

  .about-canonical__collection-grid {
    display: grid;
    grid-template-columns: 1fr;
    column-gap: 32px;
    row-gap: 64px;
  }

  @media (min-width: 768px) {
    .about-canonical__collection-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (min-width: 1024px) {
    .about-canonical__collection-grid {
      grid-template-columns: repeat(4, 1fr);
    }
  }

  .about-canonical__area {
    cursor: default;
  }

  .about-canonical__area-icon {
    width: 48px;
    height: 48px;
    border-radius: 9999px;
    background: #e7e8e9;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    transition: background-color 0.5s ease, color 0.5s ease;
  }

  .about-canonical__area:hover .about-canonical__area-icon {
    background: #90efef;
  }

  .about-canonical__area-icon .material-symbols-outlined {
    font-size: 24px;
    color: #000613;
    transition: color 0.5s ease;
  }

  .about-canonical__area:hover .about-canonical__area-icon .material-symbols-outlined {
    color: #006e6e;
  }

  .about-canonical__area-title {
    font-family: 'Newsreader', serif;
    font-size: 22px;
    color: #000613;
    margin: 0 0 12px;
  }

  .about-canonical__area-body {
    font-family: 'Manrope', sans-serif;
    font-size: 13px;
    color: #43474e;
    line-height: 1.6;
    margin: 0;
  }

  /* Institutional Directory — asymmetric list with NE arrow circles */
  .about-canonical__directory {
    background: #f3f4f5;
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 80px;
  }

  @media (min-width: 768px) {
    .about-canonical__directory {
      padding: 64px;
    }
  }

  .about-canonical__directory-title {
    font-family: 'Newsreader', serif;
    font-size: 30px;
    color: #000613;
    margin: 0 0 48px;
  }

  .about-canonical__directory-list {
    display: flex;
    flex-direction: column;
    gap: 48px;
  }

  .about-canonical__directory-row {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 24px;
  }

  @media (min-width: 768px) {
    .about-canonical__directory-row {
      flex-direction: row;
      align-items: center;
      gap: 64px;
    }
  }

  .about-canonical__directory-col--title {
    width: 100%;
  }

  @media (min-width: 768px) {
    .about-canonical__directory-col--title {
      width: 25%;
    }
  }

  .about-canonical__directory-col--title h3 {
    font-family: 'Newsreader', serif;
    font-size: 22px;
    color: #000613;
    margin: 0;
  }

  .about-canonical__directory-col--body {
    width: 100%;
  }

  @media (min-width: 768px) {
    .about-canonical__directory-col--body {
      width: 50%;
    }
  }

  .about-canonical__directory-col--body p {
    font-family: 'Manrope', sans-serif;
    font-size: 15px;
    color: #43474e;
    line-height: 1.55;
    margin: 0;
  }

  .about-canonical__directory-col--action {
    width: 100%;
    display: flex;
    justify-content: flex-start;
  }

  @media (min-width: 768px) {
    .about-canonical__directory-col--action {
      width: 25%;
      justify-content: flex-end;
    }
  }

  .about-canonical__directory-arrow {
    width: 40px;
    height: 40px;
    border-radius: 9999px;
    border: 1px solid rgba(196,198,207,0.35);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #000613;
    text-decoration: none;
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
  }

  .about-canonical__directory-arrow:hover {
    background: #000613;
    color: #ffffff;
    border-color: #000613;
  }

  .about-canonical__directory-arrow .material-symbols-outlined {
    font-size: 16px;
  }
</style>
@endsection
