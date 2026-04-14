@extends('layouts.public')

@php
  $lang = request()->query('lang', 'ru');
  $lang = in_array($lang, ['kk', 'ru', 'en'], true) ? $lang : 'ru';
  $routeWithLang = static function (string $path, array $query = []) use ($lang): string {
      if ($lang !== 'ru' && ! array_key_exists('lang', $query)) {
          $query['lang'] = $lang;
      }

      $queryString = http_build_query(array_filter($query, static fn ($value) => $value !== null && $value !== ''));

      return $path . ($queryString !== '' ? ('?' . $queryString) : '');
  };

  $copy = [
      'ru' => [
          'title' => 'О библиотеке — Digital Library',
          'hero_eyebrow' => 'О библиотеке',
          'hero_title' => 'КазТБУ Digital Library',
          'hero_body' => 'Публичная страница о библиотеке и о новой цифровой платформе: что это за система, как она помогает читателям и как ориентироваться по ключевым сервисам.',
          'hero_primary' => 'Открыть каталог',
          'hero_secondary' => 'Научные ресурсы',
            'hero_surfaces' => [
              'Каталог и карточки книг',
              'Читательский кабинет и бронирования',
              'Shortlist и учебная работа',
              'Цифровые материалы и внешние ресурсы',
            ],
          'highlight_label' => 'Справка и ориентация',
          'highlight_title' => 'Единая точка входа в библиотечную экосистему',
          'highlight_body' => 'Платформа объединяет каталог, личный кабинет, shortlist, цифровые материалы, внешние лицензированные ресурсы и будущие рабочие поверхности библиотекаря и администратора.',
            'highlight_points' => [
              'единый public layer без ложных экранов',
              'академическая навигация и реальные маршруты поиска',
              'доступ к фонду, цифровым материалам и research support в одной системе',
            ],
          'mission_eyebrow' => 'Миссия и роль',
          'mission_title' => 'Новая цифровая основа университетской библиотеки',
          'mission_body' => 'KazUTB Digital Library создаётся не как витринный сайт, а как реальная библиотечная система: с каталогом, данными фонда, управляемым цифровым доступом, reader-facing сервисами и рабочими модулями для библиотечных процессов.',
          'facts' => [
              ['value' => 'UDC-first', 'label' => 'академическая навигация'],
              ['value' => 'PostgreSQL', 'label' => 'новая база библиотечных данных'],
              ['value' => 'Hybrid', 'label' => 'печатный, цифровой и лицензионный фонд'],
          ],
          'pillars_eyebrow' => 'Что уже есть в публичном слое',
          'pillars_title' => 'Реальные сервисы для читателя и преподавателя',
          'pillars_body' => 'Публичная часть платформы уже работает как связная система, а не как набор декоративных экранов.',
          'pillars' => [
              ['title' => 'Каталог и карточки книг', 'body' => 'Поиск, UDC-навигация, библиографические детали, наличие экземпляров и переходы к цифровым материалам.'],
              ['title' => 'Черновик списка литературы', 'body' => 'Рабочая зона для сбора книг и внешних ресурсов перед подготовкой учебного или исследовательского списка.'],
              ['title' => 'Безопасный доступ', 'body' => 'Вход выполняется внутри библиотеки, а CRM остаётся соседней auth/API-плоскостью, а не владельцем продукта.'],
          ],
            'story_eyebrow' => 'Фонд, доступ и поддержка',
            'story_title' => 'Библиотека как связанная цифровая среда',
            'story_body' => 'Страница должна объяснять не только кто мы, но и как устроен вход в библиотечную среду: от поиска и маршрутов по УДК до controlled access, shortlist и дальнейшей работы с источниками.',
            'story_cards' => [
              ['kicker' => 'Коллекции', 'title' => 'Печатный, цифровой и лицензируемый фонд', 'body' => 'Платформа не смешивает все источники в один абстрактный список: она различает реальные экземпляры, локальные цифровые материалы и внешние исследовательские платформы.'],
              ['kicker' => 'Доступ', 'title' => 'Честный маршрут от sign-in до чтения', 'body' => 'Личный кабинет, controlled viewer и reader-facing сервисы работают внутри библиотечной системы, а не уводят пользователя в чужую оболочку.'],
              ['kicker' => 'Поддержка', 'title' => 'Ориентация для читателя, преподавателя и staff', 'body' => 'Публичный слой даёт понятный вход, а дальше маршрутизирует пользователя к каталогу, shortlist, ресурсам и следующим operational surfaces.'],
            ],
          'project_eyebrow' => 'Проект и развитие',
          'project_title' => 'Что означает цифровая библиотечная платформа',
          'project_body' => 'Проект постепенно заменяет старую инфраструктуру и закладывает основу для качества данных, аналитики, библиотечных операций, контролируемого цифрового доступа и будущих AI-слоёв.',
          'project_cards' => [
              ['title' => 'Данные и качество фонда', 'body' => 'После переноса в PostgreSQL основной задачей остаются выверка метаданных, снижение дублей и точная связь между записью и экземплярами.'],
              ['title' => 'Внешние и лицензионные ресурсы', 'body' => 'Платформа различает собственный фонд, локальные цифровые материалы и лицензионные платформы вроде IPR SMART с ограничениями по доступу.'],
              ['title' => 'Следующие модули', 'body' => 'Далее развиваются member dashboard, secure access flows, librarian panel, admin panel и более глубокий advanced catalog / book details слой.'],
          ],
            'progress_eyebrow' => 'Траектория платформы',
            'progress_title' => 'Как собирается новая библиотечная система',
            'progress_steps' => [
              ['label' => '01', 'title' => 'Каталог и discovery foundation', 'body' => 'Новый public layer начинает с поиска, библиографии, УДК-навигации и реальных маршрутов к фонду.'],
              ['label' => '02', 'title' => 'Reader-facing доступ', 'body' => 'Личный кабинет, controlled access и короткие пользовательские сценарии собираются как часть одной библиотечной системы.'],
              ['label' => '03', 'title' => 'Рабочие академические потоки', 'body' => 'Shortlist, research support и ориентирование по ресурсам становятся честными рабочими поверхностями, а не декоративными страницами.'],
              ['label' => '04', 'title' => 'Внутренние библиотечные зоны', 'body' => 'Следом развиваются librarian и admin surfaces, quality workflows, circulation и deeper operational layers.'],
            ],
          'orientation_eyebrow' => 'Ориентация по сервисам',
          'orientation_title' => 'Куда идти с конкретной задачей',
          'orientation' => [
              ['label' => 'Ищу литературу по теме', 'body' => 'Начните с академической навигации по UDC, затем переходите в каталог с реальными фильтрами.', 'href' => '/discover', 'action' => 'Открыть навигацию'],
              ['label' => 'Собираю рабочий список', 'body' => 'Используйте shortlist для черновика списка литературы и дальнейшего экспорта.', 'href' => '/shortlist', 'action' => 'Открыть shortlist'],
              ['label' => 'Нужен доступ к базам и платформам', 'body' => 'Откройте раздел ресурсов, чтобы увидеть локальные и внешние электронные источники.', 'href' => '/resources', 'action' => 'Открыть ресурсы'],
          ],
          'updates_eyebrow' => 'Новости и контекст',
          'updates_title' => 'Что важно знать пользователю уже сейчас',
          'updates' => [
              ['title' => 'Платформа развивается как основная библиотечная система', 'body' => 'Это не временная оболочка поверх старого каталога, а новая operational база библиотеки.'],
              ['title' => 'Внешние ресурсы становятся частью общей навигации', 'body' => 'Лицензионные платформы учитываются как часть реальной knowledge-access инфраструктуры, а не как список внешних ссылок.'],
              ['title' => 'Публичные экраны становятся честнее', 'body' => 'Навигация и страницы постепенно очищаются от ложных affordance и декоративных сценариев без рабочего бэкенда.'],
          ],
          'contact_eyebrow' => 'Контакты и режим',
          'contact_title' => 'Как связаться с библиотекой',
          'contact_body' => 'Если нужна офлайн-помощь, справка по фонду или уточнение по доступу, используйте реальные контакты библиотеки.',
          'contacts' => [
              ['label' => 'Телефон', 'value' => '+7 (7172) 64-58-58', 'href' => 'tel:+77172645858'],
              ['label' => 'Email', 'value' => 'library@digital-library.demo', 'href' => 'mailto:library@digital-library.demo'],
              ['label' => 'Адрес', 'value' => 'Астана, ул. Кайыма Мухамедханова, 37A'],
          ],
          'hours_title' => 'Режим работы',
          'hours' => [
              ['label' => 'Понедельник – Пятница', 'value' => '09:00 – 18:00'],
              ['label' => 'Суббота', 'value' => '10:00 – 14:00'],
              ['label' => 'Воскресенье', 'value' => 'Выходной'],
          ],
            'cta_eyebrow' => 'Начать работу',
            'cta_title' => 'Переходите сразу к реальному рабочему маршруту',
            'cta_body' => 'Если вам не нужна справка о библиотеке, переходите прямо к нужной поверхности: в каталог, academic navigation, shortlist или внешние ресурсы.',
            'cta_links' => [
              ['label' => 'Каталог', 'href' => '/catalog'],
              ['label' => 'Навигация по УДК', 'href' => '/discover'],
              ['label' => 'Shortlist', 'href' => '/shortlist'],
              ['label' => 'Ресурсы', 'href' => '/resources'],
            ],
      ],
      'kk' => [
          'title' => 'Кітапхана туралы — Digital Library',
          'hero_eyebrow' => 'Кітапхана туралы',
          'hero_title' => 'KazTBU Digital Library',
          'hero_body' => 'Кітапхана мен жаңа цифрлық платформа туралы ашық бет: бұл қандай жүйе, ол оқырманға қалай көмектеседі және негізгі сервистер бойынша қалай бағдарлануға болады.',
          'hero_primary' => 'Каталогты ашу',
          'hero_secondary' => 'Ғылыми ресурстар',
            'hero_surfaces' => [
              'Каталог және кітап карточкалары',
              'Оқырман кабинеті және броньдар',
              'Shortlist және оқу жұмысы',
              'Цифрлық материалдар мен сыртқы ресурстар',
            ],
          'highlight_label' => 'Анықтама және бағдар',
          'highlight_title' => 'Кітапхана экожүйесіне бірыңғай кіру нүктесі',
          'highlight_body' => 'Платформа каталогты, жеке кабинетті, shortlist-ті, цифрлық материалдарды, сыртқы лицензиялық ресурстарды және болашақ кітапханашы мен әкімші модульдерін біріктіреді.',
            'highlight_points' => [
              'жалған экрандарсыз бірыңғай public layer',
              'академиялық навигация және нақты іздеу маршруттары',
              'қор, цифрлық материалдар және research support бір жүйеде',
            ],
          'mission_eyebrow' => 'Миссия және рөл',
          'mission_title' => 'Университет кітапханасының жаңа цифрлық негізі',
          'mission_body' => 'KazUTB Digital Library витриналық сайт ретінде емес, нақты кітапханалық жүйе ретінде жасалып жатыр: каталогпен, қор деректерімен, басқарылатын цифрлық қолжетімділікпен, оқырман сервистерімен және кітапханалық процестерге арналған жұмыс модульдерімен.',
          'facts' => [
              ['value' => 'UDC-first', 'label' => 'академиялық навигация'],
              ['value' => 'PostgreSQL', 'label' => 'кітапхана деректерінің жаңа базасы'],
              ['value' => 'Hybrid', 'label' => 'баспа, цифрлық және лицензиялық қор'],
          ],
          'pillars_eyebrow' => 'Қоғамдық қабатта не бар',
          'pillars_title' => 'Оқырман мен оқытушыға арналған нақты сервистер',
          'pillars_body' => 'Платформаның ашық бөлігі қазірдің өзінде сәндік экрандар жиыны емес, өзара байланысты жүйе ретінде жұмыс істейді.',
          'pillars' => [
              ['title' => 'Каталог және кітап карточкалары', 'body' => 'Іздеу, ӘОЖ-навигация, библиографиялық деректер, даналардың қолжетімділігі және цифрлық материалдарға өту.'],
              ['title' => 'Әдебиет тізімінің жұмыс нұсқасы', 'body' => 'Оқу не зерттеу тізімін дайындамас бұрын кітаптар мен сыртқы ресурстарды жинауға арналған жұмыс аймағы.'],
              ['title' => 'Қауіпсіз қолжетімділік', 'body' => 'Кіру кітапхана ішінде жүреді, ал CRM өнім иесі емес, тек көршілес auth/API қабаты болып қалады.'],
          ],
            'story_eyebrow' => 'Қор, қолжетімділік және қолдау',
            'story_title' => 'Кітапхана байланысқан цифрлық орта ретінде',
            'story_body' => 'Бұл бет тек “кімбіз” деген сұраққа жауап бермейді, сонымен қатар іздеу, ӘОЖ навигациясы, controlled access және shortlist арасындағы нақты кітапханалық маршрутты түсіндіреді.',
            'story_cards' => [
              ['kicker' => 'Жинақ', 'title' => 'Баспа, цифрлық және лицензиялық қор', 'body' => 'Платформа барлық дереккөздерді бір абстрактілі тізімге қоспайды: нақты даналар, жергілікті цифрлық материалдар және сыртқы ғылыми платформалар бөлек көрсетіледі.'],
              ['kicker' => 'Қолжетімділік', 'title' => 'Sign-in-нан оқуға дейінгі шынайы маршрут', 'body' => 'Жеке кабинет, controlled viewer және оқырман сервистері кітапхана жүйесінің ішінде қалады және пайдаланушыны бөтен қабыққа жібермейді.'],
              ['kicker' => 'Қолдау', 'title' => 'Оқырман, оқытушы және staff үшін бағдар', 'body' => 'Қоғамдық қабат түсінікті кіреберіс береді де, ары қарай пайдаланушыны каталогқа, shortlist-ке, ресурстарға және operational surfaces-ке жібереді.'],
            ],
          'project_eyebrow' => 'Жоба және даму',
          'project_title' => 'Цифрлық кітапхана платформасы нені білдіреді',
          'project_body' => 'Жоба ескі инфрақұрылымды кезең-кезеңімен алмастырып, дерек сапасы, аналитика, кітапхана операциялары, басқарылатын цифрлық қолжетімділік және болашақ AI-қабаттары үшін негіз құрады.',
          'project_cards' => [
              ['title' => 'Деректер және қор сапасы', 'body' => 'PostgreSQL-ге көшкеннен кейін негізгі міндеттер метадеректерді тексеру, дубльдерді азайту және жазба мен даналар арасындағы байланысты нақтылау болып қалады.'],
              ['title' => 'Сыртқы және лицензиялық ресурстар', 'body' => 'Платформа жеке қорды, жергілікті цифрлық материалдарды және IPR SMART секілді лицензиялық платформаларды бөлек көрсетеді.'],
              ['title' => 'Келесі модульдер', 'body' => 'Келесі кезекте member dashboard, secure access flows, librarian panel, admin panel және тереңірек advanced catalog / book details қабаты дамиды.'],
          ],
            'progress_eyebrow' => 'Платформа траекториясы',
            'progress_title' => 'Жаңа кітапхана жүйесі қалай жиналып жатыр',
            'progress_steps' => [
              ['label' => '01', 'title' => 'Каталог және discovery foundation', 'body' => 'Жаңа public layer іздеу, библиография, ӘОЖ навигациясы және нақты қор маршруттарынан басталады.'],
              ['label' => '02', 'title' => 'Reader-facing қолжетімділік', 'body' => 'Жеке кабинет, controlled access және қысқа пайдаланушы сценарийлері бір кітапхана жүйесінің бөлігі ретінде құрылады.'],
              ['label' => '03', 'title' => 'Академиялық жұмыс ағындары', 'body' => 'Shortlist, research support және ресурстар бойынша бағдар сәндік бет емес, нақты жұмыс қабатына айналады.'],
              ['label' => '04', 'title' => 'Ішкі кітапхана аймақтары', 'body' => 'Келесі кезеңде librarian және admin surfaces, quality workflows, circulation және deeper operational layers дамиды.'],
            ],
          'orientation_eyebrow' => 'Сервистер бойынша бағдар',
          'orientation_title' => 'Нақты міндетпен қайда бару керек',
          'orientation' => [
              ['label' => 'Тақырып бойынша әдебиет іздеймін', 'body' => 'Алдымен ӘОЖ бойынша академиялық навигацияны ашып, содан кейін нақты сүзгілері бар каталогқа өтіңіз.', 'href' => '/discover', 'action' => 'Навигацияны ашу'],
              ['label' => 'Жұмыс тізімін жинап жатырмын', 'body' => 'Әдебиет тізімінің жұмыс нұсқасы мен келесі экспорт үшін shortlist қолданыңыз.', 'href' => '/shortlist', 'action' => 'Shortlist ашу'],
              ['label' => 'Платформалар мен базаларға қолжетімділік керек', 'body' => 'Жергілікті және сыртқы электрондық көздерді көру үшін ресурстар бөліміне өтіңіз.', 'href' => '/resources', 'action' => 'Ресурстарды ашу'],
          ],
          'updates_eyebrow' => 'Жаңалықтар және контекст',
          'updates_title' => 'Пайдаланушыға қазір білу маңызды нәрселер',
          'updates' => [
              ['title' => 'Платформа негізгі кітапханалық жүйе ретінде дамып жатыр', 'body' => 'Бұл ескі каталогтың үстіндегі уақытша қабық емес, кітапхананың жаңа operational негізі.'],
              ['title' => 'Сыртқы ресурстар ортақ навигацияның бір бөлігіне айналады', 'body' => 'Лицензиялық платформалар сыртқы сілтемелер тізімі емес, нақты knowledge-access инфрақұрылымының бөлігі ретінде қаралады.'],
              ['title' => 'Қоғамдық экрандар шынайырақ болып келеді', 'body' => 'Навигация мен беттер жұмыс істемейтін сәндік сценарийлерден біртіндеп тазарады.'],
          ],
          'contact_eyebrow' => 'Байланыс және кесте',
          'contact_title' => 'Кітапханамен қалай байланысуға болады',
          'contact_body' => 'Офлайн көмек, қор бойынша анықтама немесе қолжетімділік сұрақтары үшін кітапхананың нақты байланыс арналарын қолданыңыз.',
          'contacts' => [
              ['label' => 'Телефон', 'value' => '+7 (7172) 64-58-58', 'href' => 'tel:+77172645858'],
              ['label' => 'Email', 'value' => 'library@digital-library.demo', 'href' => 'mailto:library@digital-library.demo'],
              ['label' => 'Мекенжай', 'value' => 'Астана, Қайым Мұхамедханов көшесі, 37A'],
          ],
          'hours_title' => 'Жұмыс уақыты',
          'hours' => [
              ['label' => 'Дүйсенбі – Жұма', 'value' => '09:00 – 18:00'],
              ['label' => 'Сенбі', 'value' => '10:00 – 14:00'],
              ['label' => 'Жексенбі', 'value' => 'Демалыс'],
          ],
            'cta_eyebrow' => 'Жұмысты бастау',
            'cta_title' => 'Бірден нақты маршрутқа өтіңіз',
            'cta_body' => 'Егер кітапхана туралы анықтама жеткілікті болса, келесі қадамға өтіңіз: каталогқа, ӘОЖ навигациясына, shortlist-ке немесе ресурстарға.',
            'cta_links' => [
              ['label' => 'Каталог', 'href' => '/catalog'],
              ['label' => 'ӘОЖ навигациясы', 'href' => '/discover'],
              ['label' => 'Shortlist', 'href' => '/shortlist'],
              ['label' => 'Ресурстар', 'href' => '/resources'],
            ],
      ],
      'en' => [
          'title' => 'About Library — Digital Library',
          'hero_eyebrow' => 'About Library',
          'hero_title' => 'KazTBU Digital Library',
          'hero_body' => 'A public page about the library and the new digital platform: what the system is, how it helps readers, and where to go across the key services.',
          'hero_primary' => 'Open catalog',
          'hero_secondary' => 'Research resources',
            'hero_surfaces' => [
              'Catalog and book records',
              'Member account and reservations',
              'Shortlist and course work',
              'Digital materials and external resources',
            ],
          'highlight_label' => 'Orientation and support',
          'highlight_title' => 'One public entry point into the library ecosystem',
          'highlight_body' => 'The platform brings together the catalog, member account, shortlist, digital materials, external licensed resources, and future librarian/admin work areas.',
            'highlight_points' => [
              'one public layer without fake surfaces',
              'academic navigation and real discovery routes',
              'collection access, digital materials, and research support in one system',
            ],
          'mission_eyebrow' => 'Mission and role',
          'mission_title' => 'The new digital foundation of the university library',
          'mission_body' => 'KazUTB Digital Library is being built not as a brochure website but as a real library system with catalog data, controlled digital access, reader services, and operational modules for library workflows.',
          'facts' => [
              ['value' => 'UDC-first', 'label' => 'academic navigation'],
              ['value' => 'PostgreSQL', 'label' => 'new library data foundation'],
              ['value' => 'Hybrid', 'label' => 'print, digital, and licensed collections'],
          ],
          'pillars_eyebrow' => 'What is already live in the public layer',
          'pillars_title' => 'Real reader and teaching-facing services',
          'pillars_body' => 'The public side of the platform already behaves like a connected product rather than a set of decorative screens.',
          'pillars' => [
              ['title' => 'Catalog and book records', 'body' => 'Search, UDC navigation, bibliographic detail, copy availability, and links into digital materials.'],
              ['title' => 'Draft reading list', 'body' => 'A working area for gathering books and external resources before building a teaching or research list.'],
              ['title' => 'Secure access', 'body' => 'Authentication stays inside the library while CRM remains a neighboring auth/API layer, not the product owner.'],
          ],
            'story_eyebrow' => 'Collections, access, and support',
            'story_title' => 'The library as a connected digital environment',
            'story_body' => 'The page should explain not only who the library is, but how the digital environment works: from search and UDC routes to controlled access, shortlist, and research support.',
            'story_cards' => [
              ['kicker' => 'Collections', 'title' => 'Print, digital, and licensed holdings', 'body' => 'The platform does not flatten everything into one abstract repository: it distinguishes physical copies, local digital materials, and external scholarly platforms.'],
              ['kicker' => 'Access', 'title' => 'An honest route from sign-in to reading', 'body' => 'Member access, the controlled viewer, and reader-facing services stay inside the library product instead of handing people off to a different shell.'],
              ['kicker' => 'Support', 'title' => 'Orientation for readers, faculty, and staff', 'body' => 'The public layer gives a clear entry point, then routes users into catalog, shortlist, resources, and the broader library platform.'],
            ],
          'project_eyebrow' => 'Project and growth',
          'project_title' => 'What the digital library platform means',
          'project_body' => 'The project is gradually replacing legacy infrastructure and building the base for data quality, analytics, operational workflows, controlled digital access, and future AI-assisted layers.',
          'project_cards' => [
              ['title' => 'Data and collection quality', 'body' => 'After the move into PostgreSQL, metadata verification, duplicate reduction, and accurate record-to-copy relations remain critical priorities.'],
              ['title' => 'External and licensed resources', 'body' => 'The platform distinguishes owned holdings, local digital materials, and licensed platforms such as IPR SMART.'],
              ['title' => 'Next modules', 'body' => 'The next major areas include member dashboard flows, secure access, librarian panel, admin panel, and deeper advanced catalog / book detail work.'],
          ],
            'progress_eyebrow' => 'Platform progression',
            'progress_title' => 'How the new library system is being assembled',
            'progress_steps' => [
              ['label' => '01', 'title' => 'Catalog and discovery foundation', 'body' => 'The public layer starts from search, bibliography, UDC routes, and real entry points into the holdings.'],
              ['label' => '02', 'title' => 'Reader-facing access', 'body' => 'Member account, controlled access, and short reader journeys are built as part of one coherent library product.'],
              ['label' => '03', 'title' => 'Academic working flows', 'body' => 'Shortlist, research support, and resource orientation become honest working surfaces rather than decorative pages.'],
              ['label' => '04', 'title' => 'Internal library zones', 'body' => 'Next come librarian and admin surfaces, quality workflows, circulation, and deeper operational modules.'],
            ],
          'orientation_eyebrow' => 'Service orientation',
          'orientation_title' => 'Where to go for a concrete task',
          'orientation' => [
              ['label' => 'I need literature by topic', 'body' => 'Start with UDC-based academic navigation, then move into the catalog with real filters.', 'href' => '/discover', 'action' => 'Open discovery'],
              ['label' => 'I am building a working list', 'body' => 'Use shortlist as the draft layer before copying or exporting the reading list.', 'href' => '/shortlist', 'action' => 'Open shortlist'],
              ['label' => 'I need access to platforms and databases', 'body' => 'Open the resources area to review local and external electronic sources.', 'href' => '/resources', 'action' => 'Open resources'],
          ],
          'updates_eyebrow' => 'Updates and context',
          'updates_title' => 'What matters to a user right now',
          'updates' => [
              ['title' => 'The platform is growing into the main library system', 'body' => 'It is not a temporary shell over the old catalog but the new operational base of the library.'],
              ['title' => 'External resources are part of one discovery picture', 'body' => 'Licensed platforms are treated as part of the real knowledge-access infrastructure, not just a list of links.'],
              ['title' => 'Public screens are becoming more honest', 'body' => 'Navigation and pages are being cleaned up so they stop implying behaviors without real backend support.'],
          ],
          'contact_eyebrow' => 'Contacts and hours',
          'contact_title' => 'How to reach the library',
          'contact_body' => 'Use the real library channels when you need in-person help, collection guidance, or clarification on access.',
          'contacts' => [
              ['label' => 'Phone', 'value' => '+7 (7172) 64-58-58', 'href' => 'tel:+77172645858'],
              ['label' => 'Email', 'value' => 'library@digital-library.demo', 'href' => 'mailto:library@digital-library.demo'],
              ['label' => 'Address', 'value' => '37A Kayym Mukhamedkhanov Street, Astana'],
          ],
          'hours_title' => 'Opening hours',
          'hours' => [
              ['label' => 'Monday – Friday', 'value' => '09:00 – 18:00'],
              ['label' => 'Saturday', 'value' => '10:00 – 14:00'],
              ['label' => 'Sunday', 'value' => 'Closed'],
          ],
            'cta_eyebrow' => 'Start working',
            'cta_title' => 'Move directly into a real library route',
            'cta_body' => 'If the orientation is enough, continue straight into the route you need: catalog, UDC navigation, shortlist, or external resources.',
            'cta_links' => [
              ['label' => 'Catalog', 'href' => '/catalog'],
              ['label' => 'UDC navigation', 'href' => '/discover'],
              ['label' => 'Shortlist', 'href' => '/shortlist'],
              ['label' => 'Resources', 'href' => '/resources'],
            ],
      ],
  ][$lang];
@endphp

@section('title', $copy['title'])

@section('content')
  <section class="page-hero about-hero">
    <div class="container about-hero-shell">
      <div>
        <div class="eyebrow eyebrow--cyan">{{ $copy['hero_eyebrow'] }}</div>
        <h1>{{ $copy['hero_title'] }}</h1>
        <p>{{ $copy['hero_body'] }}</p>
        <div class="about-hero-actions">
          <a href="{{ $routeWithLang('/catalog') }}" class="btn btn-primary">{{ $copy['hero_primary'] }}</a>
          <a href="{{ $routeWithLang('/resources') }}" class="btn btn-ghost">{{ $copy['hero_secondary'] }}</a>
        </div>
        <div class="about-surface-list">
          @foreach($copy['hero_surfaces'] as $surface)
            <span>{{ $surface }}</span>
          @endforeach
        </div>
      </div>

      <div class="about-hero-side">
        <aside class="about-highlight">
          <span>{{ $copy['highlight_label'] }}</span>
          <strong>{{ $copy['highlight_title'] }}</strong>
          <p>{{ $copy['highlight_body'] }}</p>
          <ul class="about-highlight-points">
            @foreach($copy['highlight_points'] as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        </aside>

        <div class="about-hero-visual" aria-hidden="true">
          <div class="about-hero-visual__panel about-hero-visual__panel--primary">
            <span>Digital Library</span>
            <strong>KazTBU</strong>
          </div>
          <div class="about-hero-visual__row">
            <div class="about-hero-visual__panel">
              <span>Catalog</span>
              <strong>UDC-first</strong>
            </div>
            <div class="about-hero-visual__panel">
              <span>Access</span>
              <strong>Reader routes</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container about-grid">
      <div>
        <div class="eyebrow">{{ $copy['mission_eyebrow'] }}</div>
        <h2 class="heading-xl">{{ $copy['mission_title'] }}</h2>
        <p class="text-body" style="margin: 0;">{{ $copy['mission_body'] }}</p>
      </div>

      <div class="about-facts">
        @foreach($copy['facts'] as $fact)
          <div>
            <strong>{{ $fact['value'] }}</strong>
            <span>{{ $fact['label'] }}</span>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head">
        <div>
          <div class="eyebrow eyebrow--violet">{{ $copy['pillars_eyebrow'] }}</div>
          <h2>{{ $copy['pillars_title'] }}</h2>
          <p>{{ $copy['pillars_body'] }}</p>
        </div>
      </div>

      <div class="about-card-grid">
        @foreach($copy['pillars'] as $card)
          <article class="about-card">
            <h3>{{ $card['title'] }}</h3>
            <p>{{ $card['body'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head">
        <div>
          <div class="eyebrow eyebrow--cyan">{{ $copy['story_eyebrow'] }}</div>
          <h2>{{ $copy['story_title'] }}</h2>
          <p>{{ $copy['story_body'] }}</p>
        </div>
      </div>

      <div class="about-story-grid">
        @foreach($copy['story_cards'] as $card)
          <article class="about-story-card">
            <span>{{ $card['kicker'] }}</span>
            <h3>{{ $card['title'] }}</h3>
            <p>{{ $card['body'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="page-section page-section--tone">
    <div class="container">
      <div class="section-head">
        <div>
          <div class="eyebrow eyebrow--green">{{ $copy['project_eyebrow'] }}</div>
          <h2>{{ $copy['project_title'] }}</h2>
          <p>{{ $copy['project_body'] }}</p>
        </div>
      </div>

      <div class="about-card-grid">
        @foreach($copy['project_cards'] as $card)
          <article class="about-card about-card--project">
            <h3>{{ $card['title'] }}</h3>
            <p>{{ $card['body'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head">
        <div>
          <div class="eyebrow eyebrow--violet">{{ $copy['progress_eyebrow'] }}</div>
          <h2>{{ $copy['progress_title'] }}</h2>
        </div>
      </div>

      <div class="progress-grid">
        @foreach($copy['progress_steps'] as $step)
          <article class="progress-card">
            <span>{{ $step['label'] }}</span>
            <h3>{{ $step['title'] }}</h3>
            <p>{{ $step['body'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container about-two-col">
      <div>
        <div class="section-head">
          <div>
            <div class="eyebrow eyebrow--violet">{{ $copy['orientation_eyebrow'] }}</div>
            <h2>{{ $copy['orientation_title'] }}</h2>
          </div>
        </div>

        <div class="orientation-stack">
          @foreach($copy['orientation'] as $item)
            <article class="orientation-card">
              <span>{{ $item['label'] }}</span>
              <p>{{ $item['body'] }}</p>
              <a href="{{ $routeWithLang($item['href']) }}">{{ $item['action'] }}</a>
            </article>
          @endforeach
        </div>
      </div>

      <div>
        <div class="section-head">
          <div>
            <div class="eyebrow eyebrow--cyan">{{ $copy['updates_eyebrow'] }}</div>
            <h2>{{ $copy['updates_title'] }}</h2>
          </div>
        </div>

        <div class="updates-stack">
          @foreach($copy['updates'] as $item)
            <article class="update-card">
              <h3>{{ $item['title'] }}</h3>
              <p>{{ $item['body'] }}</p>
            </article>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container about-two-col">
      <div class="contact-card contact-card--primary">
        <div class="eyebrow eyebrow--green">{{ $copy['contact_eyebrow'] }}</div>
        <h2>{{ $copy['contact_title'] }}</h2>
        <p>{{ $copy['contact_body'] }}</p>
        <div class="contact-list">
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
      </div>

      <div class="contact-card">
        <div class="eyebrow eyebrow--violet">{{ $copy['hours_title'] }}</div>
        <div class="hours-list">
          @foreach($copy['hours'] as $item)
            <div class="contact-row">
              <span>{{ $item['label'] }}</span>
              <strong>{{ $item['value'] }}</strong>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="about-cta-band">
        <div>
          <div class="eyebrow eyebrow--green">{{ $copy['cta_eyebrow'] }}</div>
          <h2>{{ $copy['cta_title'] }}</h2>
          <p>{{ $copy['cta_body'] }}</p>
        </div>
        <div class="about-cta-links">
          @foreach($copy['cta_links'] as $link)
            <a href="{{ $routeWithLang($link['href']) }}">{{ $link['label'] }}</a>
          @endforeach
        </div>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .about-hero-shell {
    display: grid;
    grid-template-columns: 1.2fr 360px;
    gap: 24px;
    align-items: stretch;
    text-align: left;
    animation: aboutReveal .45s cubic-bezier(0.2, 0.8, 0.2, 1) both;
  }

  .about-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 18px;
  }

  .about-surface-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 18px;
  }

  .about-surface-list span,
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

  .about-hero-side {
    display: grid;
    gap: 16px;
  }

  .about-highlight,
  .about-card,
  .about-story-card,
  .progress-card,
  .orientation-card,
  .update-card,
  .contact-card,
  .about-facts div {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
    transition: transform .24s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .24s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
  }

  .about-highlight:hover,
  .about-card:hover,
  .orientation-card:hover,
  .update-card:hover,
  .contact-card:hover,
  .about-facts div:hover {
    transform: translate3d(0, -2px, 0);
    box-shadow: 0 14px 28px rgba(25, 28, 29, 0.05);
    border-color: rgba(20,105,109,.18);
  }

  .about-highlight {
    padding: 22px;
    display: grid;
    align-content: start;
    gap: 10px;
  }

  .about-highlight-points {
    margin: 4px 0 0;
    padding-left: 18px;
    display: grid;
    gap: 8px;
    color: var(--muted);
    line-height: 1.55;
  }

  .about-hero-visual {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 16px;
    background:
      radial-gradient(circle at top right, rgba(20,105,109,.12), transparent 36%),
      linear-gradient(145deg, rgba(0,30,64,.96), rgba(12,52,88,.92));
    color: #fff;
    display: grid;
    gap: 12px;
    min-height: 220px;
  }

  .about-hero-visual__row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
  }

  .about-hero-visual__panel {
    min-height: 88px;
    border-radius: var(--radius-md);
    padding: 14px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.14);
    display: grid;
    align-content: end;
    gap: 6px;
  }

  .about-hero-visual__panel--primary {
    min-height: 110px;
  }

  .about-hero-visual__panel span {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: rgba(255,255,255,.72);
  }

  .about-hero-visual__panel strong {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.45rem;
    line-height: 1.08;
  }

  .about-highlight span,
  .orientation-card span,
  .contact-row span {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--cyan);
  }

  .about-highlight strong {
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.85rem;
    line-height: 1.08;
    color: var(--blue);
  }

  .about-highlight p,
  .about-card p,
  .orientation-card p,
  .update-card p,
  .contact-card p {
    margin: 0;
    color: var(--muted);
    line-height: 1.72;
  }

  .about-grid,
  .about-two-col {
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 24px;
    align-items: start;
  }

  .about-facts {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
  }

  .about-facts div {
    padding: 16px;
  }

  .about-facts strong {
    display: block;
    margin-bottom: 4px;
    color: var(--blue);
    font-size: 1.35rem;
  }

  .about-facts span {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--muted);
  }

  .about-card-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }

  .about-story-grid,
  .progress-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
  }

  .about-card {
    padding: 20px;
  }

  .about-story-card,
  .progress-card {
    padding: 20px;
  }

  .about-story-card span,
  .progress-card span {
    display: inline-flex;
    margin-bottom: 10px;
    color: var(--cyan);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
  }

  .about-card h3,
  .about-story-card h3,
  .progress-card h3,
  .update-card h3 {
    margin: 0 0 8px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.4rem;
    color: var(--blue);
    line-height: 1.15;
  }

  .page-section--tone {
    background: linear-gradient(180deg, rgba(255,255,255,0), rgba(20,105,109,.03));
  }

  .orientation-stack,
  .updates-stack {
    display: grid;
    gap: 12px;
  }

  .orientation-card,
  .update-card,
  .contact-card {
    padding: 20px;
  }

  .orientation-card a {
    display: inline-flex;
    margin-top: 12px;
    color: var(--blue);
    font-size: 13px;
    font-weight: 800;
    text-decoration: none;
  }

  .orientation-card a:hover {
    color: var(--cyan);
  }

  .contact-card h2 {
    margin: 10px 0 10px;
    font-family: 'Newsreader', Georgia, serif;
    font-size: 1.7rem;
    color: var(--blue);
  }

  .contact-list,
  .hours-list {
    display: grid;
    gap: 10px;
    margin-top: 16px;
  }

  .contact-row {
    display: grid;
    gap: 4px;
    padding: 12px 0;
    border-top: 1px solid rgba(195,198,209,.45);
  }

  .contact-row:first-child {
    border-top: 0;
    padding-top: 0;
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

  .about-cta-band {
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 24px;
    background: linear-gradient(135deg, rgba(0,30,64,.98), rgba(14,54,87,.94));
    color: #fff;
    display: grid;
    grid-template-columns: 1.1fr .9fr;
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
    line-height: 1.7;
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
    .about-grid,
    .about-two-col,
    .about-card-grid,
    .about-story-grid,
    .progress-grid,
    .about-cta-band {
      grid-template-columns: 1fr;
    }

    .about-facts {
      grid-template-columns: 1fr;
    }

    .about-cta-links {
      justify-content: flex-start;
    }
  }
</style>
@endsection
