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
          'title' => 'Карта знаний — Digital Library',
          'hero_eyebrow' => 'Навигационная структура',
          'hero_title' => 'Карта знаний',
          'hero_body' => 'Кураторский мост к академическому репозиторию КазТБУ. Организованный через Универсальную десятичную классификацию, этот хаб помогает исследователям проходить по дисциплинам с институциональной точностью.',
          'hero_quote' => '«Системная организация — первый шаг к открытию.»',
          'disciplines_title' => 'Академические дисциплины',
          'disciplines_body' => 'Основные академические маршруты, выстроенные по глобальным библиографическим стандартам.',
          'volume_label' => 'Общий объём каталога',
          'volume_value' => '482 000+ материалов',
          'browse_all_title' => 'Просмотреть все архивы',
          'browse_all_body' => 'Ищите по всем подразделениям и историческим архивам, используя полный спектр UDC.',
          'launch_catalog' => 'Запустить каталог',
          'workflow_title' => 'Исследовательский маршрут',
          'workflow' => [
              ['number' => '01', 'title' => 'Выберите направление', 'body' => 'Определите домен исследования через маршрут UDC или университетские теги.'],
              ['number' => '02', 'title' => 'Откройте каталог', 'body' => 'Просматривайте основные публикации, курсовые материалы и актуальные издания по теме.'],
              ['number' => '03', 'title' => 'Уточните результат', 'body' => 'Фильтруйте выдачу по репозиторию, году, языку или конкретному подразделению.'],
              ['number' => '04', 'title' => 'Соберите shortlist', 'body' => 'Сохраняйте нужные материалы для библиографии, курса или исследовательского запроса.'],
          ],
          'metadata_title' => 'Институциональные метаданные',
          'metadata_body' => 'Открывайте контент, связанный со структурой университета. Эти теги помогают находить локальные проекты, диссертации и профильные коллекции.',
          'status_label' => 'Статус обновления',
          'status_text' => 'Репозиторий синхронизирован с живыми факультетскими, кафедральными и специализационными записями.',
          'resources_cta' => 'Открыть ресурсы',
          'pathways_title' => 'Институциональные маршруты',
          'pathways_body' => 'Исследуйте коллекцию через структуру университета. Каждый факультет ведёт к профильным UDC-кодам и актуальным изданиям.',
          'pathways_count' => '4 факультета',
          'faculties' => [
              [
                  'icon' => 'science',
                  'udc_code' => '66',
                  'udc_label' => 'UDC 66–67',
                  'title' => 'Технологический факультет',
                  'subtitle' => 'Прикладные науки, дизайн и стандартизация',
                  'depts' => ['Технология и стандартизация', 'Технология лёгкой промышленности и дизайна', 'Социально-гуманитарные дисциплины'],
              ],
              [
                  'icon' => 'trending_up',
                  'udc_code' => '33',
                  'udc_label' => 'UDC 33',
                  'title' => 'Факультет экономики и бизнеса',
                  'subtitle' => 'Экономика, финансы и управление',
                  'depts' => ['Туризм и сервис', 'Экономика и управление', 'Финансы и учёт'],
              ],
              [
                  'icon' => 'memory',
                  'udc_code' => '004',
                  'udc_label' => 'UDC 004 / 62',
                  'title' => 'Факультет инжиниринга и ИТ',
                  'subtitle' => 'Инженерия и информационные технологии',
                  'depts' => ['Информационные технологии', 'Компьютерная инженерия и автоматизация', 'Химия, химическая технология и экология'],
              ],
              [
                  'icon' => 'shield',
                  'udc_code' => '355',
                  'udc_label' => 'UDC 355',
                  'title' => 'Военная кафедра',
                  'subtitle' => 'Военная подготовка и оборонные дисциплины',
                  'depts' => [],
              ],
          ],
          'bridge' => [
              ['title' => 'Библиотечный каталог', 'body' => 'Доступ к основной базе данных с расширенным поиском и UDC-фильтрами.', 'cta' => 'Войти в базу', 'url' => $withLang('/catalog')],
              ['title' => 'Научные ресурсы', 'body' => 'Навигаторы по цитированию, библиотечным базам и академическим платформам.', 'cta' => 'Открыть гиды', 'url' => $withLang('/resources')],
              ['title' => 'Личный shortlist', 'body' => 'Возвращайтесь к сохранённым подборкам и рабочим спискам литературы.', 'cta' => 'Открыть shortlist', 'url' => $withLang('/shortlist')],
          ],
          'metadata' => [
              'loading' => 'Загружаем живые метаданные...',
              'items' => 'записей',
              'status' => 'Загружено :count живых академических тегов из репозитория.',
              'fallback' => 'Показана локальная карта тегов, пока API метаданных недоступен.',
              'error' => 'Не удалось загрузить живые теги. Доступна базовая навигационная карта.',
          ],
          'fallback_chips' => [
              ['label' => 'Факультет технологий', 'count' => '1.2k', 'query' => 'технология'],
              ['label' => 'Дизайн и архитектура', 'count' => '842', 'query' => 'архитектура'],
              ['label' => 'Кафедра автоматизации', 'count' => '411', 'query' => 'автоматизация'],
              ['label' => 'Информационные системы', 'count' => '2.9k', 'query' => 'информационные системы'],
              ['label' => 'Экономическая теория', 'count' => '1.1k', 'query' => 'экономическая теория'],
              ['label' => 'Биотехнологические исследования', 'count' => '654', 'query' => 'биотехнология'],
              ['label' => 'Прикладная математика', 'count' => '312', 'query' => 'прикладная математика'],
              ['label' => 'Государственное управление', 'count' => '188', 'query' => 'государственное управление'],
              ['label' => 'История Казахстана', 'count' => '920', 'query' => 'история казахстана'],
          ],
      ],
      'kk' => [
          'title' => 'Білім картасы — Digital Library',
          'hero_eyebrow' => 'Навигация құрылымы',
          'hero_title' => 'Білім картасы',
          'hero_body' => 'ҚазТБУ академиялық репозиторийіне апаратын кураторлық көпір. Әмбебап ондық жіктеу арқылы құрылған бұл хаб зерттеушілерге пәндер арасында институционалдық дәлдікпен жүруге мүмкіндік береді.',
          'hero_quote' => '«Жүйелі ұйымдастыру — жаңалық ашудың алғашқы қадамы.»',
          'disciplines_title' => 'Академиялық пәндер',
          'disciplines_body' => 'Әлемдік библиографиялық стандарттар бойынша құрылған негізгі академиялық бағыттар.',
          'volume_label' => 'Каталогтың жалпы көлемі',
          'volume_value' => '482 000+ материал',
          'browse_all_title' => 'Барлық архивтерді шолу',
          'browse_all_body' => 'ӘОЖ-дың толық спектрі арқылы барлық бөлімдер мен тарихи архивтер бойынша іздеңіз.',
          'launch_catalog' => 'Каталогты іске қосу',
          'workflow_title' => 'Зерттеу маршруты',
          'workflow' => [
              ['number' => '01', 'title' => 'Бағытты таңдаңыз', 'body' => 'Зерттеу доменін ӘОЖ маршруты немесе университет тегтері арқылы анықтаңыз.'],
              ['number' => '02', 'title' => 'Каталогты ашыңыз', 'body' => 'Негізгі жарияланымдарды, курстық материалдарды және тақырып бойынша өзекті басылымдарды қараңыз.'],
              ['number' => '03', 'title' => 'Нәтижені нақтылаңыз', 'body' => 'Репозиторий, жыл, тіл немесе нақты бөлім бойынша сүзгілерді қолданыңыз.'],
              ['number' => '04', 'title' => 'Shortlist жинаңыз', 'body' => 'Библиография, курс немесе зерттеу сұранысы үшін материалдарды сақтаңыз.'],
          ],
          'metadata_title' => 'Институционалдық метадеректер',
          'metadata_body' => 'Университет құрылымына қатысты контентті ашыңыз. Бұл тегтер жергілікті жобаларды, диссертацияларды және профильдік топтамаларды табуға көмектеседі.',
          'status_label' => 'Жаңарту күйі',
          'status_text' => 'Репозиторий тірі факультет, кафедра және мамандандыру жазбаларымен синхрондалған.',
          'resources_cta' => 'Ресурстарды ашу',
          'pathways_title' => 'Институционалдық маршруттар',
          'pathways_body' => 'Топтаманы университет құрылымы арқылы зерттеңіз. Әр факультет профильдік ӘОЖ кодтарына және өзекті басылымдарға апарады.',
          'pathways_count' => '4 факультет',
          'faculties' => [
              [
                  'icon' => 'science',
                  'udc_code' => '66',
                  'udc_label' => 'UDC 66–67',
                  'title' => 'Технологиялық факультет',
                  'subtitle' => 'Қолданбалы ғылымдар, дизайн және стандарттау',
                  'depts' => ['Технология және стандарттау', 'Жеңіл өнеркәсіп технологиясы және дизайн', 'Әлеуметтік-гуманитарлық пәндер'],
              ],
              [
                  'icon' => 'trending_up',
                  'udc_code' => '33',
                  'udc_label' => 'UDC 33',
                  'title' => 'Экономика және бизнес факультеті',
                  'subtitle' => 'Экономика, қаржы және басқару',
                  'depts' => ['Туризм және сервис', 'Экономика және басқару', 'Қаржы және есеп'],
              ],
              [
                  'icon' => 'memory',
                  'udc_code' => '004',
                  'udc_label' => 'UDC 004 / 62',
                  'title' => 'Инжиниринг және АТ факультеті',
                  'subtitle' => 'Инженерия және ақпараттық технологиялар',
                  'depts' => ['Ақпараттық технологиялар', 'Компьютерлік инженерия және автоматтандыру', 'Химия, химиялық технология және экология'],
              ],
              [
                  'icon' => 'shield',
                  'udc_code' => '355',
                  'udc_label' => 'UDC 355',
                  'title' => 'Әскери кафедра',
                  'subtitle' => 'Әскери дайындық және қорғаныс пәндері',
                  'depts' => [],
              ],
          ],
          'bridge' => [
              ['title' => 'Кітапхана каталогы', 'body' => 'Кеңейтілген іздеу және ӘОЖ сүзгілері бар негізгі дерекқорға қолжетімділік.', 'cta' => 'Дерекқорға өту', 'url' => $withLang('/catalog')],
              ['title' => 'Ғылыми ресурстар', 'body' => 'Дәйексөздеу, кітапханалық базалар және академиялық платформалар бойынша гидтер.', 'cta' => 'Гидтерді көру', 'url' => $withLang('/resources')],
              ['title' => 'Жеке shortlist', 'body' => 'Сақталған іріктемелер мен жұмыс әдебиет тізімдеріне қайта оралыңыз.', 'cta' => 'Shortlist ашу', 'url' => $withLang('/shortlist')],
          ],
          'metadata' => [
              'loading' => 'Тірі метадеректер жүктелуде...',
              'items' => 'жазба',
              'status' => 'Репозиторийден :count тірі академиялық тег жүктелді.',
              'fallback' => 'Метадеректер API қолжетімсіз болғанда жергілікті тег картасы көрсетіледі.',
              'error' => 'Тірі тегтерді жүктеу мүмкін болмады. Негізгі навигациялық карта қолжетімді.',
          ],
          'fallback_chips' => [
              ['label' => 'Технология факультеті', 'count' => '1.2k', 'query' => 'технология'],
              ['label' => 'Дизайн және сәулет', 'count' => '842', 'query' => 'архитектура'],
              ['label' => 'Автоматтандыру кафедрасы', 'count' => '411', 'query' => 'автоматизация'],
              ['label' => 'Ақпараттық жүйелер', 'count' => '2.9k', 'query' => 'информационные системы'],
              ['label' => 'Экономикалық теория', 'count' => '1.1k', 'query' => 'экономическая теория'],
              ['label' => 'Биотехнология зерттеулері', 'count' => '654', 'query' => 'биотехнология'],
              ['label' => 'Қолданбалы математика', 'count' => '312', 'query' => 'прикладная математика'],
              ['label' => 'Мемлекеттік басқару', 'count' => '188', 'query' => 'государственное управление'],
              ['label' => 'Қазақстан тарихы', 'count' => '920', 'query' => 'история казахстана'],
          ],
      ],
      'en' => [
          'title' => 'Academic Discovery Hub — Digital Library',
          'hero_eyebrow' => 'Navigation Framework',
          'hero_title' => 'The Map of Knowledge',
          'hero_body' => 'A curated bridge to the Kazakh University of Technology and Business scholarly repository. Organized through the Universal Decimal Classification, this hub enables researchers to traverse disciplines with institutional precision.',
          'hero_quote' => '“Systematic organization is the first step toward discovery.”',
          'disciplines_title' => 'Scholarly Disciplines',
          'disciplines_body' => 'Primary academic pathways mapped to global bibliographic standards.',
          'volume_label' => 'Total Catalog Volume',
          'volume_value' => '482,000+ Assets',
          'browse_all_title' => 'Browse All Archives',
          'browse_all_body' => 'Search across all departments and historical archives using the full UDC spectrum.',
          'launch_catalog' => 'Launch Catalog',
          'workflow_title' => 'Research Workflow',
          'workflow' => [
              ['number' => '01', 'title' => 'Choose direction', 'body' => 'Identify your research domain via the UDC pathway or faculty tags.'],
              ['number' => '02', 'title' => 'Explore catalog', 'body' => 'Browse curated high-impact works and recent faculty publications.'],
              ['number' => '03', 'title' => 'Refine results', 'body' => 'Filter by institutional repository, year, language, or specific department.'],
              ['number' => '04', 'title' => 'Build shortlist', 'body' => 'Save bookmarks into your academic working set for citation management.'],
          ],
          'metadata_title' => 'Institutional Metadata',
          'metadata_body' => 'Access content specific to the university structure. These tags help readers find local research projects, dissertations, and disciplinary collections.',
          'status_label' => 'Update Status',
          'status_text' => 'Repository synced with live faculty, department, and specialization records.',
          'resources_cta' => 'View Resources',
          'pathways_title' => 'Institutional Pathways',
          'pathways_body' => 'Explore the collection through the university\'s academic structure. Each faculty links to relevant UDC codes and current publications.',
          'pathways_count' => '4 Faculties',
          'faculties' => [
              [
                  'icon' => 'science',
                  'udc_code' => '66',
                  'udc_label' => 'UDC 66–67',
                  'title' => 'Faculty of Technology',
                  'subtitle' => 'Applied Sciences, Design & Standardization',
                  'depts' => ['Technology & Standardization', 'Light Industry Technology & Design', 'Humanities & Social Sciences'],
              ],
              [
                  'icon' => 'trending_up',
                  'udc_code' => '33',
                  'udc_label' => 'UDC 33',
                  'title' => 'Faculty of Economics & Business',
                  'subtitle' => 'Economics, Finance & Management',
                  'depts' => ['Tourism & Service Management', 'Economics & Management', 'Finance & Accounting'],
              ],
              [
                  'icon' => 'memory',
                  'udc_code' => '004',
                  'udc_label' => 'UDC 004 / 62',
                  'title' => 'Faculty of Engineering & IT',
                  'subtitle' => 'Engineering & Information Technology',
                  'depts' => ['Information Technology', 'Computer Engineering & Automation', 'Chemistry, Chemical Technology & Ecology'],
              ],
              [
                  'icon' => 'shield',
                  'udc_code' => '355',
                  'udc_label' => 'UDC 355',
                  'title' => 'Military Department',
                  'subtitle' => 'Military Training & Defence Studies',
                  'depts' => [],
              ],
          ],
          'bridge' => [
              ['title' => 'Library Catalog', 'body' => 'Access the main database with advanced query builders and UDC filters.', 'cta' => 'Enter Database', 'url' => $withLang('/catalog')],
              ['title' => 'Research Resources', 'body' => 'Guides for citation formatting, licensed databases, and bibliography tools.', 'cta' => 'View Guides', 'url' => $withLang('/resources')],
              ['title' => 'Personal Shortlist', 'body' => 'Retrieve saved collections and working reading lists from the public library flow.', 'cta' => 'Open Shortlist', 'url' => $withLang('/shortlist')],
          ],
          'metadata' => [
              'loading' => 'Loading live metadata...',
              'items' => 'records',
              'status' => 'Loaded :count live academic tags from the repository.',
              'fallback' => 'Showing the local tag map while live institutional metadata is unavailable.',
              'error' => 'Unable to load live tags. The fallback discovery map remains available.',
          ],
          'fallback_chips' => [
              ['label' => 'Faculty of Technology', 'count' => '1.2k', 'query' => 'technology'],
              ['label' => 'Design & Architecture', 'count' => '842', 'query' => 'design architecture'],
              ['label' => 'Dept. of Automation', 'count' => '411', 'query' => 'automation'],
              ['label' => 'Information Systems', 'count' => '2.9k', 'query' => 'information systems'],
              ['label' => 'Economic Theory', 'count' => '1.1k', 'query' => 'economic theory'],
              ['label' => 'Biotechnology Research', 'count' => '654', 'query' => 'biotechnology'],
              ['label' => 'Applied Mathematics', 'count' => '312', 'query' => 'applied mathematics'],
              ['label' => 'Public Administration', 'count' => '188', 'query' => 'public administration'],
              ['label' => 'History of Kazakhstan', 'count' => '920', 'query' => 'history of kazakhstan'],
          ],
      ],
  ][$lang];

  $cards = [
      'ru' => [
          ['icon' => 'terminal', 'udc_code' => '004', 'udc' => 'UDC 004', 'title' => 'Вычисления и информатика', 'body' => 'Продвинутые исследования алгоритмических систем, программной инженерии и цифровой инфраструктуры.', 'tags' => ['Кибербезопасность', 'AI Systems', 'Big Data']],
          ['icon' => 'trending_up', 'udc_code' => '33', 'udc' => 'UDC 33', 'title' => 'Экономика и менеджмент', 'body' => 'Анализ рыночных структур, бизнес-логистики и национального экономического развития.', 'tags' => ['Логистика', 'Аудит', 'Governance']],
          ['icon' => 'gavel', 'udc_code' => '34', 'udc' => 'UDC 34', 'title' => 'Юридические науки', 'body' => 'Конституционные рамки, международное право и исследования права в контексте Центральной Азии.', 'tags' => ['Гражданское право', 'Дипломатия', 'Этика']],
          ['icon' => 'engineering', 'udc_code' => '62', 'udc' => 'UDC 62', 'title' => 'Инженерия и технологии', 'body' => 'Материаловедение, механика и инновационные технологические приложения.', 'tags' => ['Робототехника', 'Механика', 'Автоматизация']],
          ['icon' => 'biotech', 'udc_code' => '50', 'udc' => 'UDC 50', 'title' => 'Естественные науки', 'body' => 'Биологические исследования, химическая инженерия и экологическая устойчивость.', 'tags' => ['Экология', 'Генетика', 'Химия']],
      ],
      'kk' => [
          ['icon' => 'terminal', 'udc_code' => '004', 'udc' => 'UDC 004', 'title' => 'Есептеу және информатика', 'body' => 'Алгоритмдік жүйелер, бағдарламалық инженерия және цифрлық инфрақұрылым бойынша терең зерттеулер.', 'tags' => ['Киберқауіпсіздік', 'AI Systems', 'Big Data']],
          ['icon' => 'trending_up', 'udc_code' => '33', 'udc' => 'UDC 33', 'title' => 'Экономика және менеджмент', 'body' => 'Нарық құрылымдарын, бизнес-логистиканы және ұлттық экономикалық дамуды институционалдық талдау.', 'tags' => ['Логистика', 'Аудит', 'Governance']],
          ['icon' => 'gavel', 'udc_code' => '34', 'udc' => 'UDC 34', 'title' => 'Құқықтық ғылымдар', 'body' => 'Орталық Азия кеңістігіндегі конституциялық модельдер, халықаралық құқық және құқықтану зерттеулері.', 'tags' => ['Азаматтық құқық', 'Дипломатия', 'Этика']],
          ['icon' => 'engineering', 'udc_code' => '62', 'udc' => 'UDC 62', 'title' => 'Инженерия және технология', 'body' => 'Материалтану, механикалық инженерия және инновациялық технологиялық қолданбалар.', 'tags' => ['Робототехника', 'Механика', 'Автоматтандыру']],
          ['icon' => 'biotech', 'udc_code' => '50', 'udc' => 'UDC 50', 'title' => 'Жаратылыстану ғылымдары', 'body' => 'Биологиялық зерттеулер, химиялық инженерия және экологиялық тұрақтылық тақырыптары.', 'tags' => ['Экология', 'Генетика', 'Химия']],
      ],
      'en' => [
          ['icon' => 'terminal', 'udc_code' => '004', 'udc' => 'UDC 004', 'title' => 'Computing & Informatics', 'body' => 'Advanced research in algorithmic systems, software engineering, and digital infrastructure.', 'tags' => ['Cybersecurity', 'AI Systems', 'Big Data']],
          ['icon' => 'trending_up', 'udc_code' => '33', 'udc' => 'UDC 33', 'title' => 'Economics & Management', 'body' => 'Institutional analysis of market structures, business logistics, and national economic development.', 'tags' => ['Logistics', 'Audit', 'Governance']],
          ['icon' => 'gavel', 'udc_code' => '34', 'udc' => 'UDC 34', 'title' => 'Legal Sciences', 'body' => 'Constitutional frameworks, international law, and jurisprudential studies across Central Asia.', 'tags' => ['Civil Law', 'Diplomacy', 'Ethics']],
          ['icon' => 'engineering', 'udc_code' => '62', 'udc' => 'UDC 62', 'title' => 'Engineering & Tech', 'body' => 'Materials science, mechanical engineering, and innovative technological applications.', 'tags' => ['Robotics', 'Mechanics', 'Automation']],
          ['icon' => 'biotech', 'udc_code' => '50', 'udc' => 'UDC 50', 'title' => 'Natural Sciences', 'body' => 'Biological research, chemical engineering, and environmental sustainability studies.', 'tags' => ['Ecology', 'Genetics', 'Chemistry']],
      ],
  ][$lang];
@endphp

@section('title', $copy['title'])

@section('content')
<div id="discover-page" class="discover-export-page">
  <section id="discover-hero" class="discover-export-hero">
    <div class="discover-shell">
      <div class="discover-hero-grid">
        <div class="discover-hero-copy">
          <span class="discover-kicker">{{ $copy['hero_eyebrow'] }}</span>
          <h1>{{ $copy['hero_title'] }}</h1>
          <p>{{ $copy['hero_body'] }}</p>
          <div class="discover-hero-actions">
            <a href="{{ $withLang('/catalog') }}" class="discover-btn discover-btn-primary">{{ $copy['launch_catalog'] }}</a>
            <a href="{{ $withLang('/resources') }}" class="discover-btn discover-btn-secondary">{{ $copy['resources_cta'] }}</a>
          </div>
        </div>

        <div class="discover-hero-visual-wrap">
          <div class="discover-hero-visual" aria-hidden="true">
            <span class="discover-visual-chip chip-one">UDC 004</span>
            <span class="discover-visual-chip chip-two">UDC 33</span>
            <span class="discover-visual-chip chip-three">UDC 62</span>
            <div class="discover-visual-orbit orbit-one"></div>
            <div class="discover-visual-orbit orbit-two"></div>
          </div>
          <div class="discover-quote-card">{{ $copy['hero_quote'] }}</div>
        </div>
      </div>
    </div>
  </section>

  <section id="discover-disciplines" class="discover-disciplines-section">
    <div class="discover-shell">
      <div class="discover-section-head">
        <div>
          <h2>{{ $copy['disciplines_title'] }}</h2>
          <p>{{ $copy['disciplines_body'] }}</p>
        </div>
        <div class="discover-volume-panel">
          <span>{{ $copy['volume_label'] }}</span>
          <strong>{{ $copy['volume_value'] }}</strong>
        </div>
      </div>

      <div class="discover-card-grid">
        @foreach ($cards as $card)
          <article class="discover-lane-card">
            <div class="discover-card-top">
              <span class="discover-udc-pill">{{ $card['udc'] }}</span>
              <span class="material-symbols-outlined discover-card-icon">{{ $card['icon'] }}</span>
            </div>
            <h3>{{ $card['title'] }}</h3>
            <p>{{ $card['body'] }}</p>
            <div class="discover-tag-list">
              @foreach ($card['tags'] as $tag)
                <span class="discover-tag">{{ $tag }}</span>
              @endforeach
            </div>
            <a href="{{ $withLang('/catalog', ['udc' => $card['udc_code'], 'sort' => 'title']) }}" class="discover-inline-link">
              {{ $copy['launch_catalog'] }}
              <span class="material-symbols-outlined">arrow_forward</span>
            </a>
          </article>
        @endforeach

        <article class="discover-lane-card discover-lane-card-featured">
          <div>
            <span class="material-symbols-outlined discover-featured-icon">explore</span>
            <h3>{{ $copy['browse_all_title'] }}</h3>
            <p>{{ $copy['browse_all_body'] }}</p>
          </div>
          <div class="discover-featured-action">
            <a href="{{ $withLang('/catalog') }}" class="discover-btn discover-btn-teal">{{ $copy['launch_catalog'] }}</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <section id="discover-pathways" class="discover-pathways-section" data-test-id="institutional-pathways">
    <div class="discover-shell">
      <div class="discover-section-head">
        <div>
          <h2>{{ $copy['pathways_title'] }}</h2>
          <p>{{ $copy['pathways_body'] }}</p>
        </div>
        <div class="discover-volume-panel">
          <span>{{ $copy['volume_label'] }}</span>
          <strong>{{ $copy['pathways_count'] }}</strong>
        </div>
      </div>

      <div class="discover-faculty-grid">
        @foreach ($copy['faculties'] as $faculty)
          <article class="discover-faculty-card">
            <div class="discover-faculty-icon-bg" aria-hidden="true">
              <span class="material-symbols-outlined">{{ $faculty['icon'] }}</span>
            </div>
            <div class="discover-faculty-header">
              <div>
                <h3>{{ $faculty['title'] }}</h3>
                <p class="discover-faculty-subtitle">{{ $faculty['subtitle'] }}</p>
              </div>
              <span class="discover-udc-pill">{{ $faculty['udc_label'] }}</span>
            </div>
            @if (!empty($faculty['depts']))
              <ul class="discover-dept-list">
                @foreach ($faculty['depts'] as $dept)
                  <li>
                    <a href="{{ $withLang('/catalog', ['udc' => $faculty['udc_code'], 'q' => $dept]) }}">
                      <span>{{ $dept }}</span>
                      <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                  </li>
                @endforeach
              </ul>
            @endif
            <a href="{{ $withLang('/catalog', ['udc' => $faculty['udc_code']]) }}" class="discover-inline-link">
              {{ $copy['launch_catalog'] }}
              <span class="material-symbols-outlined">arrow_forward</span>
            </a>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section id="discover-workflow" class="discover-workflow-section">
    <div class="discover-shell">
      <h2 class="discover-centered-title">{{ $copy['workflow_title'] }}</h2>
      <div class="discover-workflow-grid">
        @foreach ($copy['workflow'] as $step)
          <article class="discover-workflow-card">
            <div class="discover-step-number">{{ $step['number'] }}</div>
            <h4>{{ $step['title'] }}</h4>
            <p>{{ $step['body'] }}</p>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section id="discover-metadata" class="discover-metadata-section">
    <div class="discover-shell discover-metadata-grid">
      <div class="discover-metadata-copy">
        <h2>{{ $copy['metadata_title'] }}</h2>
        <p>{{ $copy['metadata_body'] }}</p>

        <div class="discover-status-card">
          <span>{{ $copy['status_label'] }}</span>
          <strong id="metadata-status-text">{{ $copy['status_text'] }}</strong>
        </div>
      </div>

      <div class="discover-metadata-cloud">
        <div id="subjects-loading" class="discover-loading-text">{{ $copy['metadata']['loading'] }}</div>
        <div id="discover-metadata-chips" class="discover-metadata-chips">
          @foreach ($copy['fallback_chips'] as $chip)
            <a href="{{ $withLang('/catalog', ['q' => $chip['query'], 'sort' => 'title']) }}" class="discover-meta-chip">
              <span>{{ $chip['label'] }}</span>
              <span class="discover-meta-count">{{ $chip['count'] }}</span>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <section id="discover-bridge" class="discover-bridge-section">
    <div class="discover-shell">
      <div class="discover-bridge-panel">
        @foreach ($copy['bridge'] as $bridgeCard)
          <article class="discover-bridge-card">
            <h3>{{ $bridgeCard['title'] }}</h3>
            <p>{{ $bridgeCard['body'] }}</p>
            <a href="{{ $bridgeCard['url'] }}">{{ $bridgeCard['cta'] }}</a>
          </article>
        @endforeach
      </div>
    </div>
  </section>
</div>
@endsection

@section('head')
<style>
  .discover-export-page {
    background: #f8f9fa;
    color: #191c1d;
  }

  .discover-shell {
    max-width: 1536px;
    margin: 0 auto;
    padding: 0 48px;
  }

  .discover-export-hero {
    padding: 56px 0 48px;
  }

  .discover-hero-grid {
    display: grid;
    grid-template-columns: minmax(0, 7fr) minmax(320px, 5fr);
    gap: 48px;
    align-items: center;
  }

  .discover-kicker {
    display: block;
    margin-bottom: 16px;
    color: #006a6a;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .24em;
    text-transform: uppercase;
  }

  .discover-hero-copy h1 {
    margin: 0 0 24px;
    font-family: 'Newsreader', serif;
    font-size: clamp(3rem, 5vw, 4.75rem);
    line-height: 1.05;
    color: #000613;
  }

  .discover-hero-copy p {
    margin: 0;
    max-width: 720px;
    color: #43474e;
    font-size: 1.1rem;
    line-height: 1.8;
  }

  .discover-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 28px;
  }

  .discover-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    transition: transform .25s ease, opacity .2s ease, background .2s ease;
  }

  .discover-btn:hover {
    transform: translateY(-1px);
  }

  .discover-btn-primary {
    background: linear-gradient(135deg, #000613, #001f3f);
    color: #ffffff;
  }

  .discover-btn-secondary {
    background: #edeeef;
    color: #000613;
  }

  .discover-btn-teal {
    background: #006a6a;
    color: #ffffff;
  }

  .discover-hero-visual-wrap {
    position: relative;
    padding-bottom: 24px;
  }

  .discover-hero-visual {
    position: relative;
    aspect-ratio: 1 / 1;
    border-radius: 16px;
    overflow: hidden;
    background:
      radial-gradient(circle at 20% 20%, rgba(255,255,255,.22), transparent 0 24%),
      linear-gradient(135deg, rgba(0,6,19,.95), rgba(71,96,131,.72)),
      repeating-linear-gradient(90deg, rgba(255,255,255,.08) 0 1px, transparent 1px 38px),
      repeating-linear-gradient(0deg, rgba(255,255,255,.08) 0 1px, transparent 1px 38px);
    box-shadow: 0 20px 50px rgba(0, 6, 19, .14);
    isolation: isolate;
  }

  .discover-hero-visual::before,
  .discover-hero-visual::after {
    content: '';
    position: absolute;
    border: 1px solid rgba(255, 255, 255, .18);
    border-radius: 14px;
    transform: rotate(-7deg);
  }

  .discover-hero-visual::before {
    inset: 14% 14% 20% 12%;
  }

  .discover-hero-visual::after {
    inset: 30% 18% 12% 24%;
    transform: rotate(11deg);
  }

  .discover-visual-orbit {
    position: absolute;
    border-radius: 999px;
    border: 1px solid rgba(147, 242, 242, .28);
  }

  .orbit-one {
    inset: 18% 16% 22% 20%;
  }

  .orbit-two {
    inset: 34% 26% 16% 12%;
  }

  .discover-visual-chip {
    position: absolute;
    z-index: 2;
    padding: 7px 10px;
    border-radius: 999px;
    background: rgba(255,255,255,.12);
    color: #d4e3ff;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
  }

  .chip-one { top: 16%; left: 12%; }
  .chip-two { top: 48%; right: 12%; }
  .chip-three { bottom: 16%; left: 22%; }

  .discover-quote-card {
    position: absolute;
    left: -24px;
    bottom: 0;
    max-width: 280px;
    padding: 22px 24px;
    border-radius: 12px;
    background: #001f3f;
    color: #afc8f0;
    font-family: 'Newsreader', serif;
    font-size: 1.15rem;
    font-style: italic;
    line-height: 1.45;
    box-shadow: 0 18px 36px rgba(0, 6, 19, .2);
  }

  .discover-disciplines-section {
    padding: 72px 0;
    background: #f3f4f5;
  }

  .discover-section-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 24px;
    margin-bottom: 48px;
  }

  .discover-section-head h2,
  .discover-centered-title,
  .discover-metadata-copy h2 {
    margin: 0 0 12px;
    font-family: 'Newsreader', serif;
    font-size: clamp(2.1rem, 3vw, 3rem);
    color: #000613;
  }

  .discover-section-head p,
  .discover-metadata-copy p {
    margin: 0;
    color: #43474e;
    line-height: 1.75;
  }

  .discover-volume-panel {
    text-align: right;
    flex-shrink: 0;
  }

  .discover-volume-panel span {
    display: block;
    margin-bottom: 6px;
    color: #74777f;
    font-size: 12px;
    font-weight: 700;
  }

  .discover-volume-panel strong {
    display: block;
    color: #000613;
    font-family: 'Newsreader', serif;
    font-size: 2rem;
  }

  .discover-card-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 28px;
  }

  .discover-lane-card {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 100%;
    padding: 32px;
    border-radius: 16px;
    background: #ffffff;
    transition: background .35s ease, transform .35s ease;
  }

  .discover-lane-card:hover {
    background: #e7e8e9;
    transform: translateY(-2px);
  }

  .discover-card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 24px;
  }

  .discover-udc-pill {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 999px;
    background: #90efef;
    color: #006a6a;
    font-size: 12px;
    font-weight: 800;
  }

  .discover-card-icon {
    color: #74777f;
  }

  .discover-lane-card h3,
  .discover-lane-card-featured h3,
  .discover-bridge-card h3 {
    margin: 0 0 10px;
    font-family: 'Newsreader', serif;
    font-size: 2rem;
    color: #000613;
  }

  .discover-lane-card p,
  .discover-bridge-card p {
    margin: 0 0 20px;
    color: #43474e;
    font-size: 14px;
    line-height: 1.75;
  }

  .discover-tag-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
  }

  .discover-tag {
    padding: 4px 8px;
    background: #edeeef;
    color: #74777f;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .1em;
    text-transform: uppercase;
  }

  .discover-inline-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #006a6a;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
  }

  .discover-inline-link .material-symbols-outlined {
    font-size: 18px;
    transition: transform .25s ease;
  }

  .discover-inline-link:hover .material-symbols-outlined {
    transform: translateX(3px);
  }

  .discover-lane-card-featured {
    background: #000613;
    color: #ffffff;
  }

  .discover-lane-card-featured h3 {
    color: #ffffff;
  }

  .discover-lane-card-featured p {
    color: #afc8f0;
  }

  .discover-featured-icon {
    margin-bottom: 18px;
    color: #93f2f2;
    font-size: 42px;
  }

  .discover-featured-action {
    margin-top: 20px;
  }

  .discover-workflow-section {
    padding: 72px 0;
    border-bottom: 1px solid #e7e8e9;
  }

  .discover-centered-title {
    margin-bottom: 56px;
    text-align: center;
  }

  .discover-workflow-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 28px;
  }

  .discover-workflow-card {
    position: relative;
    padding-top: 20px;
  }

  .discover-step-number {
    position: absolute;
    top: -18px;
    left: -4px;
    z-index: -1;
    font-family: 'Newsreader', serif;
    font-size: 3.75rem;
    font-style: italic;
    color: #e1e3e4;
  }

  .discover-workflow-card h4 {
    margin: 0 0 10px;
    font-size: 1.2rem;
    font-weight: 800;
    color: #000613;
  }

  .discover-workflow-card p {
    margin: 0;
    color: #43474e;
    font-size: 14px;
    line-height: 1.75;
  }

  .discover-metadata-section {
    padding: 72px 0;
    background: #ffffff;
  }

  .discover-metadata-grid {
    display: grid;
    grid-template-columns: minmax(280px, 4fr) minmax(0, 8fr);
    gap: 48px;
    align-items: start;
  }

  .discover-status-card {
    margin-top: 24px;
    padding: 18px 20px;
    background: #f3f4f5;
    border-left: 4px solid #006a6a;
    border-radius: 10px;
  }

  .discover-status-card span {
    display: block;
    margin-bottom: 6px;
    color: #006a6a;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .18em;
    text-transform: uppercase;
  }

  .discover-status-card strong {
    color: #000613;
    font-size: 14px;
    line-height: 1.6;
  }

  .discover-loading-text {
    margin-bottom: 14px;
    color: #74777f;
    font-size: 13px;
    font-weight: 700;
  }

  .discover-metadata-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
  }

  .discover-meta-chip {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 999px;
    background: #edeeef;
    color: #000613;
    font-size: 14px;
    font-weight: 800;
    text-decoration: none;
    transition: background .25s ease, transform .25s ease;
  }

  .discover-meta-chip:hover {
    background: #90efef;
    transform: translateY(-1px);
  }

  .discover-meta-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    padding: 2px 8px;
    border-radius: 999px;
    background: #ffffff;
    color: #006a6a;
    font-size: 11px;
    font-weight: 800;
  }

  .discover-bridge-section {
    padding: 56px 0 80px;
  }

  .discover-bridge-panel {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 28px;
    padding: 36px;
    border-radius: 20px;
    background: #001f3f;
  }

  .discover-bridge-card h3 {
    color: #d4e3ff;
  }

  .discover-bridge-card p {
    color: rgba(212, 227, 255, .8);
  }

  .discover-bridge-card a {
    color: #93f2f2;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .14em;
    text-decoration: none;
    text-transform: uppercase;
    border-bottom: 2px solid #93f2f2;
    padding-bottom: 4px;
  }

  /* ── Institutional Pathways ─────────────────────────────────── */
  .discover-pathways-section {
    padding: 72px 0;
    background: #ffffff;
  }

  .discover-faculty-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 28px;
  }

  .discover-faculty-card {
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 32px;
    border-radius: 16px;
    background: #f3f4f5;
    overflow: hidden;
    transition: background .3s ease, transform .3s ease;
  }

  .discover-faculty-card:hover {
    background: #eef5f5;
    transform: translateY(-2px);
  }

  .discover-faculty-icon-bg {
    position: absolute;
    top: 0;
    right: 0;
    padding: 20px;
    pointer-events: none;
    opacity: .05;
    transition: opacity .4s ease;
  }

  .discover-faculty-card:hover .discover-faculty-icon-bg {
    opacity: .1;
  }

  .discover-faculty-icon-bg .material-symbols-outlined {
    font-size: 100px;
    font-variation-settings: 'FILL' 0;
  }

  .discover-faculty-header {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
  }

  .discover-faculty-header h3 {
    margin: 0 0 6px;
    font-family: 'Newsreader', serif;
    font-size: 1.6rem;
    line-height: 1.2;
    color: #000613;
  }

  .discover-faculty-subtitle {
    margin: 0;
    color: #74777f;
    font-size: 13px;
    line-height: 1.5;
  }

  .discover-dept-list {
    position: relative;
    z-index: 1;
    list-style: none;
    margin: 0 0 20px;
    padding: 16px 0 0;
    border-top: 1px solid #e7e8e9;
    flex: 1;
  }

  .discover-dept-list li + li {
    border-top: 1px solid #e7e8e9;
  }

  .discover-dept-list a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    color: #000613;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: color .2s ease;
  }

  .discover-dept-list a:hover {
    color: #006a6a;
  }

  .discover-dept-list a .material-symbols-outlined {
    font-size: 16px;
    color: #74777f;
    transition: transform .2s ease, color .2s ease;
  }

  .discover-dept-list a:hover .material-symbols-outlined {
    transform: translateX(3px);
    color: #006a6a;
  }

  .discover-faculty-card > .discover-inline-link {
    position: relative;
    z-index: 1;
    margin-top: auto;
  }

  @media (max-width: 1100px) {
    .discover-hero-grid,
    .discover-metadata-grid,
    .discover-card-grid,
    .discover-workflow-grid,
    .discover-bridge-panel {
      grid-template-columns: 1fr 1fr;
    }

    .discover-hero-copy {
      grid-column: 1 / -1;
    }
  }

  @media (max-width: 780px) {
    .discover-shell {
      padding: 0 20px;
    }

    .discover-export-hero,
    .discover-disciplines-section,
    .discover-pathways-section,
    .discover-workflow-section,
    .discover-metadata-section,
    .discover-bridge-section {
      padding-top: 44px;
      padding-bottom: 44px;
    }

    .discover-hero-grid,
    .discover-card-grid,
    .discover-workflow-grid,
    .discover-metadata-grid,
    .discover-bridge-panel,
    .discover-faculty-grid {
      grid-template-columns: 1fr;
    }

    .discover-section-head {
      flex-direction: column;
      align-items: flex-start;
      margin-bottom: 32px;
    }

    .discover-volume-panel {
      text-align: left;
    }

    .discover-quote-card {
      position: relative;
      left: 0;
      margin-top: -20px;
    }

    .discover-lane-card,
    .discover-bridge-panel {
      padding: 24px;
    }
  }
</style>
@endsection

@section('scripts')
<script>
  const DISCOVER_LANG = @json($lang);
  const DISCOVER_I18N = @json($copy['metadata']);

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
    const cloud = document.getElementById('discover-metadata-chips');
    const status = document.getElementById('metadata-status-text');

    try {
      const response = await fetch('/api/v1/subjects', { headers: { Accept: 'application/json' } });
      if (!response.ok) {
        throw new Error('Metadata API error');
      }

      const data = await response.json();
      const items = [
        ...(data.faculties || []).map((item) => ({ ...item, kind: 'faculty' })),
        ...(data.departments || []).map((item) => ({ ...item, kind: 'department' })),
        ...(data.specializations || []).map((item) => ({ ...item, kind: 'specialization' })),
      ]
        .sort((left, right) => (right.documentCount || 0) - (left.documentCount || 0))
        .slice(0, 18);

      if (items.length > 0) {
        cloud.innerHTML = items.map((item) => {
          const href = withLang('/catalog', {
            subject_id: item.id,
            subject_label: item.label,
            sort: 'title',
          });

          return `<a href="${href}" class="discover-meta-chip ${item.kind}" title="${item.documentCount} ${DISCOVER_I18N.items}"><span>${item.label}</span><span class="discover-meta-count">${item.documentCount}</span></a>`;
        }).join('');

        if (status) {
          status.textContent = DISCOVER_I18N.status.replace(':count', String(items.length));
        }
      } else if (status) {
        status.textContent = DISCOVER_I18N.fallback;
      }

      loading.style.display = 'none';
    } catch (error) {
      loading.textContent = DISCOVER_I18N.error;
      if (status) {
        status.textContent = DISCOVER_I18N.fallback;
      }
      console.warn('Discover metadata load failed:', error);
    }
  })();
</script>
@endsection
