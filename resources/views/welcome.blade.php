@php
    $pageLang = request()->query('lang', 'ru');
    $pageLang = in_array($pageLang, ['kk', 'ru', 'en'], true) ? $pageLang : 'ru';

    $withLang = function (string $path, array $query = []) use ($pageLang): string {
        if ($pageLang !== 'ru' && ! array_key_exists('lang', $query)) {
            $query = array_merge($query, ['lang' => $pageLang]);
        }

        $queryString = http_build_query($query);

        return $path . ($queryString !== '' ? ('?' . $queryString) : '');
    };

    $copy = [
        'ru' => [
            'title' => 'Цифровая библиотека КазУТБ — The Academic Curator',
            'skip' => 'Перейти к основному содержанию',
            'brand' => 'The Academic Curator',
            'brandSub' => 'Цифровая библиотека КазУТБ',
            'nav' => [
                'catalog' => 'Каталог',
                'resources' => 'Ресурсы',
                'account' => 'Кабинет',
            ],
            'actions' => [
                'shortlist' => 'Подборка',
                'login' => 'Войти',
            ],
            'hero' => [
                'eyebrow' => 'АКАДЕМИЧЕСКИЙ ДОСТУП',
                'titleBefore' => 'Знание,',
                'titleAccent' => 'собранное',
                'titleAfter' => 'для исследователя.',
                'lead' => 'Цифровая библиотека КазУТБ объединяет университетский фонд, архивы, внешние академические платформы и рабочие инструменты преподавателя в одном спокойном институциональном интерфейсе.',
                'placeholder' => 'Искать книги, авторов, ISBN, УДК или ключевые слова…',
                'submit' => 'Explore',
            ],
            'metrics' => [
                ['icon' => 'check_circle', 'label' => '2.5M+ цифровых материалов'],
                ['icon' => 'check_circle', 'label' => '800K печатных изданий'],
                ['icon' => 'check_circle', 'label' => 'Университетский доступ'],
            ],
            'cards' => [
                [
                    'icon' => 'location_on',
                    'kicker' => 'Внутренняя сеть',
                    'title' => 'Университетские архивы',
                    'body' => 'Диссертации, труды преподавателей, редкие технические материалы и локальные коллекции университета.',
                    'href' => '/resources',
                    'variant' => 'wide',
                ],
                [
                    'icon' => 'cloud_done',
                    'kicker' => 'Удалённый доступ',
                    'title' => 'Цифровой портал',
                    'body' => 'Круглосуточный доступ к оцифрованным книгам, статьям и электронным подборкам из любой точки.',
                    'href' => '/catalog',
                    'variant' => 'primary',
                ],
                [
                    'icon' => 'language',
                    'kicker' => 'Лицензионные базы',
                    'title' => 'Глобальная сеть',
                    'body' => 'Интегрированный доступ к Scopus, Springer, Web of Science и другим научным платформам.',
                    'href' => '/resources',
                    'variant' => 'muted',
                ],
            ],
            'browse' => [
                'eyebrow' => 'Классификационная система',
                'title' => 'Просмотр по направлениям',
                'cta' => 'Открыть навигацию по УДК',
            ],
            'subjects' => [
                ['icon' => 'architecture', 'label' => 'Технические науки', 'code' => 'UDC 62'],
                ['icon' => 'account_balance', 'label' => 'Право и политика', 'code' => 'UDC 34'],
                ['icon' => 'biotech', 'label' => 'Естественные науки', 'code' => 'UDC 5'],
                ['icon' => 'history_edu', 'label' => 'Гуманитарный блок', 'code' => 'UDC 008'],
                ['icon' => 'monitoring', 'label' => 'Экономика', 'code' => 'UDC 33'],
                ['icon' => 'menu_book', 'label' => 'Справочные издания', 'code' => 'UDC 030'],
            ],
            'quote' => '«Библиотека — это сердце университета; наша задача — направлять знание к каждому студенту, преподавателю и исследователю.»',
            'institution' => [
                'eyebrow' => 'Университетская среда',
                'title' => 'Казахский университет технологии и бизнеса имени К. Кулажанова',
                'body' => 'Цифровая трансформация библиотеки соединяет классическую библиографическую дисциплину, современный поиск и быстрый доступ к ресурсам для учебы, преподавания и исследований.',
                'facts' => [
                    ['value' => '1996', 'label' => 'Год основания'],
                    ['value' => 'Elite', 'label' => 'Институциональная аккредитация'],
                ],
            ],
            'footer' => [
                'caption' => 'Институциональная библиотечная система',
                'links' => ['Политика доступа', 'Условия использования', 'Контакт библиотекаря'],
            ],
        ],
        'kk' => [
            'title' => 'ҚазУТБ цифрлық кітапханасы — The Academic Curator',
            'skip' => 'Негізгі мазмұнға өту',
            'brand' => 'The Academic Curator',
            'brandSub' => 'ҚазУТБ цифрлық кітапханасы',
            'nav' => [
                'catalog' => 'Каталог',
                'resources' => 'Ресурстар',
                'account' => 'Кабинет',
            ],
            'actions' => [
                'shortlist' => 'Тізім',
                'login' => 'Кіру',
            ],
            'hero' => [
                'eyebrow' => 'АКАДЕМИЯЛЫҚ ҚОЛЖЕТІМ',
                'titleBefore' => 'Білім,',
                'titleAccent' => 'ұқыппен',
                'titleAfter' => 'зерттеуші үшін жинақталған.',
                'lead' => 'ҚазУТБ цифрлық кітапханасы университет қорын, архивтерді, сыртқы академиялық платформаларды және оқытушыға арналған жұмыс құралдарын бір тыныш институционалдық интерфейске біріктіреді.',
                'placeholder' => 'Кітаптар, авторлар, ISBN, ӘОЖ немесе кілт сөздер бойынша іздеу…',
                'submit' => 'Explore',
            ],
            'metrics' => [
                ['icon' => 'check_circle', 'label' => '2.5M+ цифрлық материал'],
                ['icon' => 'check_circle', 'label' => '800K баспа қоры'],
                ['icon' => 'check_circle', 'label' => 'Университеттік кіру'],
            ],
            'cards' => [
                [
                    'icon' => 'location_on',
                    'kicker' => 'Ішкі желі',
                    'title' => 'Университет архивтері',
                    'body' => 'Диссертациялар, оқытушылар еңбектері, сирек техникалық материалдар және университеттің жергілікті топтамалары.',
                    'href' => '/resources',
                    'variant' => 'wide',
                ],
                [
                    'icon' => 'cloud_done',
                    'kicker' => 'Қашықтан қолжетім',
                    'title' => 'Цифрлық портал',
                    'body' => 'Кітаптарға, мақалаларға және электрондық топтамаларға тәулік бойы кез келген жерден қолжетім.',
                    'href' => '/catalog',
                    'variant' => 'primary',
                ],
                [
                    'icon' => 'language',
                    'kicker' => 'Лицензиялық базалар',
                    'title' => 'Ғаламдық желі',
                    'body' => 'Scopus, Springer, Web of Science және басқа ғылыми платформаларға біріктірілген қолжетім.',
                    'href' => '/resources',
                    'variant' => 'muted',
                ],
            ],
            'browse' => [
                'eyebrow' => 'Жіктеу жүйесі',
                'title' => 'Бағыттар бойынша қарау',
                'cta' => 'ӘОЖ навигациясын ашу',
            ],
            'subjects' => [
                ['icon' => 'architecture', 'label' => 'Техникалық ғылымдар', 'code' => 'UDC 62'],
                ['icon' => 'account_balance', 'label' => 'Құқық және саясат', 'code' => 'UDC 34'],
                ['icon' => 'biotech', 'label' => 'Жаратылыстану ғылымдары', 'code' => 'UDC 5'],
                ['icon' => 'history_edu', 'label' => 'Гуманитарлық блок', 'code' => 'UDC 008'],
                ['icon' => 'monitoring', 'label' => 'Экономика', 'code' => 'UDC 33'],
                ['icon' => 'menu_book', 'label' => 'Анықтамалық басылымдар', 'code' => 'UDC 030'],
            ],
            'quote' => '«Кітапхана — университеттің жүрегі; біздің міндетіміз — білімді әр студентке, оқытушыға және зерттеушіге жеткізу.»',
            'institution' => [
                'eyebrow' => 'Университеттік орта',
                'title' => 'Қ. Құлажанов атындағы Қазақ технология және бизнес университеті',
                'body' => 'Кітапхананың цифрлық трансформациясы классикалық библиографиялық тәртіпті, заманауи іздеуді және оқу мен зерттеуге қажетті ресурстарға жедел қолжетімділікті біріктіреді.',
                'facts' => [
                    ['value' => '1996', 'label' => 'Құрылған жылы'],
                    ['value' => 'Elite', 'label' => 'Институционалдық аккредиттеу'],
                ],
            ],
            'footer' => [
                'caption' => 'Институционалдық кітапхана жүйесі',
                'links' => ['Қолжетім саясаты', 'Пайдалану шарттары', 'Кітапханашымен байланыс'],
            ],
        ],
        'en' => [
            'title' => 'KazUTB Digital Library — The Academic Curator',
            'skip' => 'Skip to main content',
            'brand' => 'The Academic Curator',
            'brandSub' => 'KazUTB Digital Library',
            'nav' => [
                'catalog' => 'Catalog',
                'resources' => 'Resources',
                'account' => 'Account',
            ],
            'actions' => [
                'shortlist' => 'Shortlist',
                'login' => 'Sign in',
            ],
            'hero' => [
                'eyebrow' => 'ACADEMIC ACCESS',
                'titleBefore' => 'Knowledge,',
                'titleAccent' => 'Curated',
                'titleAfter' => 'for the Scholar.',
                'lead' => 'KazUTB Digital Library brings together the university collection, archives, licensed research platforms, and teacher workflows in one calm institutional interface.',
                'placeholder' => 'Search titles, authors, ISBN, UDC, or keywords…',
                'submit' => 'Explore',
            ],
            'metrics' => [
                ['icon' => 'check_circle', 'label' => '2.5M+ Digital Assets'],
                ['icon' => 'check_circle', 'label' => '800K Physical Volumes'],
                ['icon' => 'check_circle', 'label' => 'Institutional Login'],
            ],
            'cards' => [
                [
                    'icon' => 'location_on',
                    'kicker' => 'Internal Network',
                    'title' => 'University Archives',
                    'body' => 'Exclusive access to KazUTB theses, local publications, rare technical materials, and internal academic collections.',
                    'href' => '/resources',
                    'variant' => 'wide',
                ],
                [
                    'icon' => 'cloud_done',
                    'kicker' => 'Remote Access',
                    'title' => 'Digital Portal',
                    'body' => 'Round-the-clock access to digitized books, journals, and curated academic materials from anywhere.',
                    'href' => '/catalog',
                    'variant' => 'primary',
                ],
                [
                    'icon' => 'language',
                    'kicker' => 'Licensed Platforms',
                    'title' => 'Global Network',
                    'body' => 'Integrated access to Scopus, Springer, Web of Science, and other scholarly resources.',
                    'href' => '/resources',
                    'variant' => 'muted',
                ],
            ],
            'browse' => [
                'eyebrow' => 'Classification System',
                'title' => 'Browse by subject',
                'cta' => 'View the UDC navigation',
            ],
            'subjects' => [
                ['icon' => 'architecture', 'label' => 'Technical Sciences', 'code' => 'UDC 62'],
                ['icon' => 'account_balance', 'label' => 'Law & Politics', 'code' => 'UDC 34'],
                ['icon' => 'biotech', 'label' => 'Natural Sciences', 'code' => 'UDC 5'],
                ['icon' => 'history_edu', 'label' => 'Humanities', 'code' => 'UDC 008'],
                ['icon' => 'monitoring', 'label' => 'Economics', 'code' => 'UDC 33'],
                ['icon' => 'menu_book', 'label' => 'Reference', 'code' => 'UDC 030'],
            ],
            'quote' => '“The library is the heart of the university; our mission is to direct knowledge toward every student, teacher, and researcher.”',
            'institution' => [
                'eyebrow' => 'University context',
                'title' => 'Kazakh University of Technology and Business named after K. Kulazhanov',
                'body' => 'The library’s digital transformation connects classical cataloging discipline, modern search, and reliable access to resources for teaching, study, and research.',
                'facts' => [
                    ['value' => '1996', 'label' => 'Foundation year'],
                    ['value' => 'Elite', 'label' => 'Institutional accreditation'],
                ],
            ],
            'footer' => [
                'caption' => 'Institutional library system',
                'links' => ['Access policy', 'Terms of use', 'Contact librarian'],
            ],
        ],
    ][$pageLang];
@endphp
<!DOCTYPE html>
<html lang="{{ $pageLang }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $copy['title'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Manrope:wght@400;500;600;700;800&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        :root {
            --background: #f8f9fa;
            --surface: #ffffff;
            --surface-low: #f3f4f5;
            --surface-high: #e7e8e9;
            --surface-variant: #edeeef;
            --text: #191c1d;
            --muted: #43474f;
            --outline: #c3c6d1;
            --primary: #001e40;
            --primary-strong: #003366;
            --secondary: #14696d;
            --secondary-soft: #a3ecf0;
            --tertiary: #c5a059;
            --shadow-soft: 0 12px 32px rgba(25, 28, 29, 0.04);
            --shadow-card: 0 8px 24px rgba(25, 28, 29, 0.03);
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-full: 999px;
            --wrap: 1280px;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            background: var(--background);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 1.15rem;
            line-height: 1;
        }

        .wrap {
            width: min(calc(100% - 32px), var(--wrap));
            margin: 0 auto;
        }

        .skip-link {
            position: absolute;
            left: 16px;
            top: -48px;
            padding: 10px 14px;
            background: var(--primary);
            color: #fff;
            border-radius: var(--radius-sm);
            z-index: 100;
        }

        .skip-link:focus { top: 16px; }

        .homepage-shell {
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .homepage-shell::before,
        .homepage-shell::after {
            content: '';
            position: absolute;
            pointer-events: none;
            z-index: 0;
        }

        .homepage-shell::before {
            top: 0;
            right: 0;
            width: 32%;
            height: 540px;
            background: linear-gradient(270deg, rgba(0, 30, 64, 0.05), rgba(0, 30, 64, 0));
        }

        .homepage-shell::after {
            left: -8%;
            bottom: 320px;
            width: 420px;
            height: 220px;
            filter: blur(48px);
            background: rgba(20, 105, 109, 0.10);
        }

        .home-topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.82);
            border-bottom: 1px solid rgba(195, 198, 209, 0.45);
        }

        .home-topbar .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            min-height: 76px;
        }

        .brand-lockup {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(0, 30, 64, 0.08), rgba(20, 105, 109, 0.16));
            color: var(--primary);
        }

        .brand-copy {
            display: grid;
            gap: 2px;
        }

        .brand-copy strong {
            font-family: 'Newsreader', serif;
            font-size: 1.5rem;
            font-weight: 500;
            letter-spacing: -0.02em;
            color: var(--primary);
        }

        .brand-copy span {
            font-size: 0.76rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .home-nav {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .home-nav a {
            font-size: 0.95rem;
            font-weight: 600;
            color: #5b616c;
            padding-bottom: 4px;
            transition: color 0.2s ease;
        }

        .home-nav a.active {
            color: var(--primary);
            border-bottom: 2px solid var(--secondary);
        }

        .home-nav a:hover { color: var(--secondary); }

        .home-tools {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .locale-switcher {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px;
            border: 1px solid rgba(195, 198, 209, 0.6);
            border-radius: var(--radius-full);
            background: rgba(255, 255, 255, 0.96);
        }

        .locale-switcher a {
            min-width: 36px;
            padding: 8px 10px;
            border-radius: var(--radius-full);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #66707c;
        }

        .locale-switcher a.active {
            color: var(--primary);
            background: rgba(20, 105, 109, 0.10);
        }

        .icon-button,
        .account-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 42px;
            border: 1px solid rgba(195, 198, 209, 0.45);
            background: rgba(255, 255, 255, 0.95);
            color: #5b616c;
            border-radius: var(--radius-full);
            transition: transform 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        .icon-button {
            width: 42px;
        }

        .account-link {
            padding: 0 14px;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .icon-button:hover,
        .account-link:hover {
            transform: translateY(-1px);
            color: var(--primary);
            border-color: rgba(20, 105, 109, 0.36);
        }

        .mobile-toggle {
            display: none;
            width: 42px;
            height: 42px;
            border: 1px solid rgba(195, 198, 209, 0.45);
            border-radius: 50%;
            background: #fff;
            color: var(--primary);
        }

        .home-main {
            position: relative;
            z-index: 1;
        }

        .hero-section {
            padding: 82px 0 54px;
        }

        .hero-inner {
            max-width: 980px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-eyebrow {
            display: inline-block;
            margin-bottom: 20px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--secondary);
        }

        .hero-title {
            margin: 0;
            font-family: 'Newsreader', serif;
            font-weight: 500;
            letter-spacing: -0.04em;
            line-height: 0.98;
            font-size: clamp(2.9rem, 7vw, 5.1rem);
            color: var(--primary);
        }

        .hero-title em {
            font-style: italic;
            color: var(--primary-strong);
        }

        .hero-lead {
            margin: 22px auto 0;
            max-width: 760px;
            font-size: 1.08rem;
            line-height: 1.78;
            color: var(--muted);
        }

        .hero-search-shell {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 34px auto 0;
            padding: 8px;
            max-width: 760px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: var(--radius-full);
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(195, 198, 209, 0.45);
        }

        .hero-search-icon {
            padding-left: 16px;
            color: #8a919d;
        }

        .hero-search-shell input {
            flex: 1;
            min-width: 0;
            border: 0;
            background: transparent;
            font: inherit;
            color: var(--text);
            font-size: 1rem;
            padding: 14px 6px;
            outline: none;
        }

        .hero-search-shell input::placeholder {
            color: #8a919d;
        }

        .hero-search-shell button {
            border: 0;
            border-radius: var(--radius-full);
            padding: 14px 24px;
            background: var(--primary);
            color: #fff;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .hero-search-shell button:hover {
            background: var(--primary-strong);
        }

        .metrics-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 18px 28px;
            margin-top: 28px;
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #767d88;
        }

        .metrics-row span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .metrics-row .material-symbols-outlined {
            font-size: 1rem;
            color: var(--secondary);
        }

        .entry-section {
            padding: 18px 0 56px;
        }

        .entry-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .entry-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 16px;
            padding: 26px;
            min-height: 250px;
            border-radius: var(--radius-lg);
            background: var(--surface);
            box-shadow: var(--shadow-card);
            transition: transform 0.22s ease, background 0.22s ease, border-color 0.22s ease;
        }

        .entry-card:hover {
            transform: translateY(-2px);
        }

        .entry-card--wide {
            grid-column: span 2;
            border-bottom: 2px solid rgba(20, 105, 109, 0);
        }

        .entry-card--wide:hover {
            border-bottom-color: var(--secondary);
        }

        .entry-card--primary {
            background: var(--primary);
            color: #fff;
        }

        .entry-card--muted {
            background: var(--surface-low);
        }

        .entry-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
        }

        .entry-top .material-symbols-outlined {
            font-size: 2rem;
            color: var(--secondary);
        }

        .entry-card--primary .entry-top .material-symbols-outlined {
            color: #a7d9dc;
        }

        .entry-kicker {
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #8a919d;
        }

        .entry-card--primary .entry-kicker {
            color: rgba(255, 255, 255, 0.72);
        }

        .entry-card h3 {
            margin: 0 0 10px;
            font-family: 'Newsreader', serif;
            font-weight: 500;
            font-size: clamp(1.55rem, 2.6vw, 2rem);
            letter-spacing: -0.03em;
            color: var(--primary);
        }

        .entry-card--primary h3 {
            color: #fff;
        }

        .entry-card p {
            margin: 0;
            line-height: 1.7;
            font-size: 0.96rem;
            color: var(--muted);
        }

        .entry-card--primary p {
            color: rgba(255, 255, 255, 0.78);
        }

        .subject-section {
            background: var(--surface-low);
            padding: 88px 0;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: end;
            margin-bottom: 36px;
        }

        .section-head span {
            display: block;
            margin-bottom: 10px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--secondary);
        }

        .section-head h2 {
            margin: 0;
            font-family: 'Newsreader', serif;
            font-size: clamp(2.1rem, 4vw, 3.3rem);
            font-weight: 500;
            line-height: 1.02;
            letter-spacing: -0.03em;
            color: var(--primary);
        }

        .section-head a {
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--secondary);
            border-bottom: 1px solid rgba(20, 105, 109, 0.26);
            padding-bottom: 4px;
        }

        .subject-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 14px;
        }

        .subject-card {
            display: grid;
            justify-items: center;
            gap: 12px;
            padding: 22px 14px;
            background: var(--surface);
            border-radius: var(--radius-md);
            text-align: center;
            box-shadow: var(--shadow-card);
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .subject-card:hover {
            transform: translateY(-2px);
            background: #fbfdfd;
        }

        .subject-icon {
            width: 50px;
            height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--background);
            color: var(--primary);
        }

        .subject-card strong {
            font-size: 0.92rem;
            line-height: 1.45;
            color: var(--primary);
        }

        .subject-card small {
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #7b828c;
        }

        .trust-section {
            padding: 88px 0 92px;
        }

        .trust-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 36px;
            align-items: stretch;
        }

        .quote-panel {
            position: relative;
            min-height: 380px;
            border-radius: var(--radius-lg);
            overflow: hidden;
            background:
                linear-gradient(160deg, rgba(0, 30, 64, 0.88), rgba(0, 30, 64, 0.66)),
                radial-gradient(circle at top left, rgba(197, 160, 89, 0.32), transparent 40%),
                radial-gradient(circle at bottom right, rgba(20, 105, 109, 0.24), transparent 36%),
                #0f223f;
        }

        .quote-panel::before {
            content: '';
            position: absolute;
            inset: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.22);
            border-bottom: 1px solid rgba(255, 255, 255, 0.22);
        }

        .quote-copy {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            text-align: center;
            color: #fff;
        }

        .quote-copy p {
            max-width: 520px;
            margin: 0;
            font-family: 'Newsreader', serif;
            font-size: clamp(1.55rem, 2.6vw, 2.15rem);
            font-style: italic;
            line-height: 1.45;
            letter-spacing: -0.02em;
        }

        .institution-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 22px;
        }

        .institution-panel .material-symbols-outlined {
            font-size: 3.2rem;
            color: var(--secondary);
        }

        .institution-panel span.kicker {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--secondary);
        }

        .institution-panel h2 {
            margin: 0;
            font-family: 'Newsreader', serif;
            font-size: clamp(1.95rem, 3.6vw, 3rem);
            line-height: 1.08;
            font-weight: 500;
            letter-spacing: -0.03em;
            color: var(--primary);
        }

        .institution-panel p {
            margin: 0;
            font-size: 1rem;
            line-height: 1.8;
            color: var(--muted);
        }

        .fact-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 8px;
        }

        .fact-card {
            padding: 16px 0;
        }

        .fact-card strong {
            display: block;
            font-family: 'Newsreader', serif;
            font-size: 2rem;
            font-weight: 500;
            color: var(--primary);
        }

        .fact-card span {
            display: block;
            margin-top: 4px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #7b828c;
        }

        .home-footer {
            border-top: 1px solid rgba(195, 198, 209, 0.45);
            padding: 26px 0 38px;
        }

        .home-footer .wrap {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
        }

        .footer-brand {
            display: grid;
            gap: 6px;
        }

        .footer-brand strong {
            font-family: 'Newsreader', serif;
            font-size: 1.2rem;
            font-weight: 500;
            font-style: italic;
            color: var(--primary);
        }

        .footer-brand span {
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #7b828c;
        }

        .footer-links {
            display: flex;
            gap: 22px;
            flex-wrap: wrap;
            justify-content: end;
        }

        .footer-links a {
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #7b828c;
        }

        .footer-links a:first-child {
            text-decoration: underline;
            text-decoration-color: rgba(20, 105, 109, 0.46);
            text-underline-offset: 3px;
        }

        @media (max-width: 1100px) {
            .entry-grid,
            .subject-grid,
            .trust-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .subject-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 860px) {
            .home-nav {
                display: none;
            }

            .mobile-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .hero-section {
                padding-top: 54px;
            }

            .hero-search-shell {
                flex-wrap: wrap;
                padding: 12px;
                border-radius: 18px;
            }

            .hero-search-shell input {
                width: 100%;
                padding: 8px 4px 12px;
            }

            .hero-search-shell button {
                width: 100%;
            }

            .section-head {
                flex-direction: column;
                align-items: start;
            }

            .entry-grid,
            .trust-grid,
            .subject-grid {
                grid-template-columns: 1fr;
            }

            .entry-card--wide {
                grid-column: auto;
            }

            .home-footer .wrap {
                flex-direction: column;
                align-items: start;
            }

            .footer-links {
                justify-content: start;
            }
        }

        @media (max-width: 640px) {
            .home-topbar .wrap {
                min-height: 72px;
            }

            .brand-copy strong {
                font-size: 1.2rem;
            }

            .brand-copy span,
            .account-link {
                display: none;
            }

            .home-tools {
                gap: 8px;
            }
        }
    </style>
</head>
<body data-homepage-stitch-reset>
    <a class="skip-link" href="#home-main">{{ $copy['skip'] }}</a>

    <div class="homepage-shell">
        <header class="home-topbar" aria-label="Homepage navigation">
            <div class="wrap">
                <div class="brand-lockup">
                    <a class="brand-mark" href="{{ $withLang('/') }}" aria-label="{{ $copy['title'] }}">
                        <span class="material-symbols-outlined">local_library</span>
                    </a>
                    <div class="brand-copy">
                        <strong>{{ $copy['brand'] }}</strong>
                        <span>{{ $copy['brandSub'] }}</span>
                    </div>
                </div>

                <nav class="home-nav" aria-label="Primary">
                    <a class="active" href="{{ $withLang('/catalog') }}">{{ $copy['nav']['catalog'] }}</a>
                    <a href="{{ $withLang('/resources') }}">{{ $copy['nav']['resources'] }}</a>
                    <a href="{{ $withLang('/account') }}">{{ $copy['nav']['account'] }}</a>
                </nav>

                <div class="home-tools">
                    <div class="locale-switcher" data-locale-switcher>
                        @foreach (['kk' => 'KK', 'ru' => 'RU', 'en' => 'EN'] as $code => $label)
                            <a href="{{ $withLang('/', ['lang' => $code]) }}" class="{{ $pageLang === $code ? 'active' : '' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                    <a class="icon-button" href="{{ $withLang('/shortlist') }}" aria-label="{{ $copy['actions']['shortlist'] }}">
                        <span class="material-symbols-outlined">bookmarks</span>
                    </a>
                    <a class="account-link" href="{{ $withLang('/login') }}">
                        <span class="material-symbols-outlined">person</span>
                        {{ $copy['actions']['login'] }}
                    </a>
                    <button class="mobile-toggle" type="button" aria-label="Open menu">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                </div>
            </div>
        </header>

        <main class="home-main" id="home-main">
            <section class="hero-section">
                <div class="wrap hero-inner">
                    <span class="hero-eyebrow">{{ $copy['hero']['eyebrow'] }}</span>
                    <h1 class="hero-title">
                        {{ $copy['hero']['titleBefore'] }} <em>{{ $copy['hero']['titleAccent'] }}</em> {{ $copy['hero']['titleAfter'] }}
                    </h1>
                    <p class="hero-lead">{{ $copy['hero']['lead'] }}</p>

                    <form class="hero-search-shell" action="/catalog" method="GET" data-hero-search>
                        @if ($pageLang !== 'ru')
                            <input type="hidden" name="lang" value="{{ $pageLang }}">
                        @endif
                        <span class="material-symbols-outlined hero-search-icon">search</span>
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q', '') }}"
                            placeholder="{{ $copy['hero']['placeholder'] }}"
                            aria-label="{{ $copy['hero']['placeholder'] }}"
                        >
                        <button type="submit">{{ $copy['hero']['submit'] }}</button>
                    </form>

                    <div class="metrics-row" aria-label="Institutional library highlights">
                        @foreach ($copy['metrics'] as $metric)
                            <span>
                                <span class="material-symbols-outlined">{{ $metric['icon'] }}</span>
                                {{ $metric['label'] }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="entry-section">
                <div class="wrap">
                    <div class="entry-grid">
                        @foreach ($copy['cards'] as $card)
                            <a href="{{ $withLang($card['href']) }}" class="entry-card entry-card--{{ $card['variant'] }} {{ $card['variant'] === 'wide' ? 'entry-card--wide' : '' }}">
                                <div class="entry-top">
                                    <span class="material-symbols-outlined">{{ $card['icon'] }}</span>
                                    <span class="entry-kicker">{{ $card['kicker'] }}</span>
                                </div>
                                <div>
                                    <h3>{{ $card['title'] }}</h3>
                                    <p>{{ $card['body'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="subject-section" data-homepage-subjects>
                <div class="wrap">
                    <div class="section-head">
                        <div>
                            <span>{{ $copy['browse']['eyebrow'] }}</span>
                            <h2>{{ $copy['browse']['title'] }}</h2>
                        </div>
                        <a href="{{ $withLang('/discover') }}">{{ $copy['browse']['cta'] }}</a>
                    </div>

                    <div class="subject-grid">
                        @foreach ($copy['subjects'] as $subject)
                            <a class="subject-card" href="{{ $withLang('/discover') }}">
                                <span class="subject-icon material-symbols-outlined">{{ $subject['icon'] }}</span>
                                <strong>{{ $subject['label'] }}</strong>
                                <small>{{ $subject['code'] }}</small>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="trust-section">
                <div class="wrap trust-grid">
                    <div class="quote-panel">
                        <div class="quote-copy">
                            <p>{{ $copy['quote'] }}</p>
                        </div>
                    </div>

                    <div class="institution-panel">
                        <span class="material-symbols-outlined">school</span>
                        <span class="kicker">{{ $copy['institution']['eyebrow'] }}</span>
                        <h2>{{ $copy['institution']['title'] }}</h2>
                        <p>{{ $copy['institution']['body'] }}</p>

                        <div class="fact-grid">
                            @foreach ($copy['institution']['facts'] as $fact)
                                <div class="fact-card">
                                    <strong>{{ $fact['value'] }}</strong>
                                    <span>{{ $fact['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="home-footer">
            <div class="wrap">
                <div class="footer-brand">
                    <strong>{{ $copy['brand'] }}</strong>
                    <span>© {{ now()->year }} {{ $copy['footer']['caption'] }}</span>
                </div>
                <nav class="footer-links" aria-label="Footer">
                    @foreach ($copy['footer']['links'] as $footerLink)
                        <a href="{{ $withLang('/resources') }}">{{ $footerLink }}</a>
                    @endforeach
                </nav>
            </div>
        </footer>
    </div>
</body>
</html>
