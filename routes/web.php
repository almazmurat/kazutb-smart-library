<?php

use App\Services\Library\BookDetailReadService;
use App\Services\Library\CatalogReadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Route changes are intentionally tracked by the vault hook automation.
// This file doubles as the final validation target for route-aware vault logging.
// Route-aware state snapshots are now part of the vault workflow.

$internalStaffView = static function (Request $request, string $view) {
    $user = $request->session()->get('library.user');
    $role = is_array($user) ? mb_strtolower(trim((string) ($user['role'] ?? ''))) : '';

    // Belt-and-braces: route group also has library.auth applied at the group level.
    abort_unless(in_array($role, ['librarian', 'admin'], true), 403);

    return view($view, [
        'internalStaffUser' => $user,
    ]);
};

// Role-based post-login landing destination.
// Canonical page map (PROJECT_CONTEXT §30) expects: admin -> /admin,
// librarian -> /librarian, member -> /dashboard (interim: /account).
$postLoginDestination = static function (array $user): string {
    $role = mb_strtolower(trim((string) ($user['role'] ?? '')));

    return match ($role) {
        'admin' => '/admin',
        'librarian' => '/librarian',
        default => '/account',
    };
};

$adminView = static function (Request $request, string $view, array $data = []) {
    return view($view, array_merge([
        'internalStaffUser' => $request->session()->get('library.user'),
    ], $data));
};

$librarianView = static function (Request $request, string $view, array $data = []) {
    return view($view, array_merge([
        'librarianStaffUser' => $request->session()->get('library.user'),
    ], $data));
};

$memberView = static function (Request $request, string $view, array $data = []) {
    return view($view, array_merge([
        'memberReader' => $request->session()->get('library.user'),
    ], $data));
};

// Phase 3.3 — seeded public news catalog.
// Representative content for the canonical /news index + /news/{slug} detail.
// The structure is deliberately DB-replaceable later: an array of article
// records keyed by `slug`, each carrying trilingual copy, metadata, and an
// optional long-form body for the detail route. Ordering of the index is
// controlled by $orderedSlugs (most recent first).
$newsSeedProvider = static function (): array {
    $articles = [
        'global-symposium-archival-integrity' => [
            'slug' => 'global-symposium-archival-integrity',
            'featured' => true,
            'published_at' => '2026-04-14',
            'published_display' => [
                'ru' => '14 апреля 2026',
                'kk' => '2026 жылғы 14 сәуір',
                'en' => 'April 14, 2026',
            ],
            'category' => [
                'ru' => 'Главный материал',
                'kk' => 'Басты материал',
                'en' => 'Featured Report',
            ],
            'title' => [
                'ru' => 'Международный симпозиум по целостности архивов прошёл в Астане',
                'kk' => 'Астанада мұрағат тұтастығы бойынша халықаралық симпозиум өтті',
                'en' => 'Global Symposium on Archival Integrity Concludes in Astana',
            ],
            'excerpt' => [
                'ru' => 'Исследователи и специалисты по цифровому сохранению обсудили задачи обеспечения исторической непрерывности в эпоху быстро меняющихся цифровых форматов.',
                'kk' => 'Зерттеушілер мен цифрлық сақтау мамандары жылдам өзгеретін цифрлық форматтар дәуіріндегі тарихи сабақтастықты қамтамасыз ету мәселесін талқылады.',
                'en' => 'Researchers and digital preservation specialists addressed the challenge of maintaining historical continuity in an era of rapidly evolving digital formats.',
            ],
            'hero' => [
                'image' => 'images/news/campus-library.jpg',
                'alt' => [
                    'ru' => 'Университетский читальный зал с участниками симпозиума',
                    'kk' => 'Университеттің оқу залы, симпозиум қатысушылары',
                    'en' => 'University reading room during the symposium',
                ],
            ],
            'body' => [
                'ru' => [
                    ['type' => 'lead', 'text' => 'KazUTB Smart Library провёл международный симпозиум по целостности архивов — площадку для обсуждения практик долговременного сохранения цифровых и гибридных коллекций в университетских библиотеках.'],
                    ['type' => 'h2', 'text' => 'Темы программы'],
                    ['type' => 'p', 'text' => 'Программа объединила библиотекарей, архивистов и исследователей. В центре обсуждения — связность метаданных, устойчивость форматов и контролируемый доступ к институциональному архиву.'],
                    ['type' => 'list', 'items' => [
                        ['term' => 'Метаданные и провенанс', 'text' => 'Сохранение контекста и источников при переводе коллекций в цифровую среду.'],
                        ['term' => 'Устойчивость форматов', 'text' => 'Долговременное хранение материалов и планирование миграций.'],
                        ['term' => 'Контролируемый доступ', 'text' => 'Политики читательского доступа к закрытым и лицензируемым материалам.'],
                    ]],
                    ['type' => 'h2', 'text' => 'Итоги и следующие шаги'],
                    ['type' => 'p', 'text' => 'По итогам симпозиума библиотека публикует методические рекомендации по работе с институциональным архивом и расширяет программу проверенных поступлений в научном репозитории университета.'],
                ],
                'kk' => [
                    ['type' => 'lead', 'text' => 'KazUTB Smart Library университет кітапханаларындағы цифрлық және гибридті жинақтарды ұзақ мерзімді сақтау тәжірибесін талқылауға арналған мұрағат тұтастығы жөніндегі халықаралық симпозиумды өткізді.'],
                    ['type' => 'h2', 'text' => 'Бағдарлама тақырыптары'],
                    ['type' => 'p', 'text' => 'Бағдарлама кітапханашылар, архивистер мен зерттеушілерді біріктірді. Негізгі тақырыптар: метадерек байланыстылығы, формат тұрақтылығы және институционалдық мұрағатқа бақыланатын қолжетімділік.'],
                    ['type' => 'list', 'items' => [
                        ['term' => 'Метадерек және провенанс', 'text' => 'Жинақтарды цифрлық ортаға көшіру кезіндегі контекст пен дереккөздерді сақтау.'],
                        ['term' => 'Формат тұрақтылығы', 'text' => 'Материалдарды ұзақ мерзімде сақтау және миграцияны жоспарлау.'],
                        ['term' => 'Бақыланатын қолжетімділік', 'text' => 'Шектеулі және лицензияланған материалдарға оқырман қолжетімділігінің саясаты.'],
                    ]],
                    ['type' => 'h2', 'text' => 'Қорытындылар және келесі қадамдар'],
                    ['type' => 'p', 'text' => 'Симпозиум қорытындысы бойынша кітапхана институционалдық мұрағатпен жұмысқа арналған әдістемелік ұсынымдарды жариялайды және университеттің ғылыми репозиторийіндегі тексерілген түсімдер бағдарламасын кеңейтеді.'],
                ],
                'en' => [
                    ['type' => 'lead', 'text' => 'KazUTB Smart Library hosted an international symposium on archival integrity — a working space to discuss long-term preservation practice for digital and hybrid collections in university libraries.'],
                    ['type' => 'h2', 'text' => 'Programme themes'],
                    ['type' => 'p', 'text' => 'The programme brought together librarians, archivists, and researchers. Central themes were metadata continuity, format resilience, and controlled access to the institutional archive.'],
                    ['type' => 'list', 'items' => [
                        ['term' => 'Metadata and provenance', 'text' => 'Preserving context and sources as collections move into digital environments.'],
                        ['term' => 'Format resilience', 'text' => 'Long-term preservation of materials and planned migrations across formats.'],
                        ['term' => 'Controlled access', 'text' => 'Reader access policies for restricted and licensed materials.'],
                    ]],
                    ['type' => 'h2', 'text' => 'Outcomes and next steps'],
                    ['type' => 'p', 'text' => 'Following the symposium the library is publishing guidance for working with the institutional archive and is expanding the reviewed-intake programme of the university scholarly repository.'],
                ],
            ],
            'cta' => [
                'ru' => ['heading' => 'Продолжить работу', 'body' => 'Перейдите в научный репозиторий KazUTB Smart Library, чтобы ознакомиться с проверенными публикациями.', 'label' => 'Открыть репозиторий', 'href' => '/discover'],
                'kk' => ['heading' => 'Жұмысты жалғастыру', 'body' => 'Тексерілген жарияланымдармен танысу үшін KazUTB Smart Library ғылыми репозиторийіне өтіңіз.', 'label' => 'Репозиторийді ашу', 'href' => '/discover'],
                'en' => ['heading' => 'Continue from here', 'body' => 'Open the KazUTB Smart Library scholarly repository to browse reviewed publications.', 'label' => 'Open the repository', 'href' => '/discover'],
            ],
        ],
        'eurasian-manuscripts-integration' => [
            'slug' => 'eurasian-manuscripts-integration',
            'featured' => false,
            'published_at' => '2026-04-10',
            'published_display' => [
                'ru' => '10 апреля 2026',
                'kk' => '2026 жылғы 10 сәуір',
                'en' => 'April 10, 2026',
            ],
            'category' => [
                'ru' => 'Обновления фонда',
                'kk' => 'Қор жаңартулары',
                'en' => 'Collection Updates',
            ],
            'title' => [
                'ru' => 'Цифровая интеграция евразийских рукописей XIX века',
                'kk' => 'XIX ғасырдың еуразиялық қолжазбаларының цифрлық интеграциясы',
                'en' => 'Integration of the 19th-Century Eurasian Manuscripts',
            ],
            'excerpt' => [
                'ru' => 'Более четырёх тысяч оцифрованных рукописей из центральных степных архивов индексированы и доступны для академического поиска в каталоге.',
                'kk' => 'Орталық дала мұрағаттарынан алынған төрт мыңнан астам цифрландырылған қолжазба академиялық іздеуге дайын және каталогта қолжетімді.',
                'en' => 'More than four thousand digitised manuscripts from the central steppe archives are indexed and available for academic search in the catalog.',
            ],
            'hero' => [
                'image' => 'images/news/classics-event.jpg',
                'alt' => [
                    'ru' => 'Стеллажи с архивными материалами',
                    'kk' => 'Мұрағат материалдарының сөрелері',
                    'en' => 'Shelves of archival materials',
                ],
            ],
            'body' => [
                'ru' => [
                    ['type' => 'lead', 'text' => 'Библиотека завершила базовый этап цифровой интеграции коллекции евразийских рукописей XIX века в институциональный архив KazUTB Smart Library.'],
                    ['type' => 'p', 'text' => 'Материалы прошли проверку метаданных, получили устойчивые идентификаторы и связаны с каталогом. Читатели и преподаватели могут обращаться к коллекции через обычный академический поиск.'],
                ],
                'kk' => [
                    ['type' => 'lead', 'text' => 'Кітапхана XIX ғасырдың еуразиялық қолжазбалар жинағын KazUTB Smart Library институционалдық мұрағатына цифрлық интеграциялаудың базалық кезеңін аяқтады.'],
                    ['type' => 'p', 'text' => 'Материалдар метадеректер тексеруінен өтті, тұрақты идентификаторларды алды және каталогпен байланыстырылды. Оқырмандар мен оқытушылар жинаққа қалыпты академиялық іздеу арқылы өте алады.'],
                ],
                'en' => [
                    ['type' => 'lead', 'text' => 'The library has completed the foundation stage of digital integration for the 19th-century Eurasian manuscript collection into the KazUTB Smart Library institutional archive.'],
                    ['type' => 'p', 'text' => 'Materials have been validated against metadata standards, assigned stable identifiers, and linked to the catalog. Readers and faculty can now reach the collection through ordinary academic search.'],
                ],
            ],
            'cta' => [
                'ru' => ['heading' => 'Открыть коллекцию', 'body' => 'Каталог KazUTB Smart Library содержит проиндексированные материалы — используйте тематическую навигацию по УДК.', 'label' => 'Открыть каталог', 'href' => '/catalog'],
                'kk' => ['heading' => 'Жинақты ашу', 'body' => 'KazUTB Smart Library каталогында индекстелген материалдар бар — ӘОЖ бойынша тақырыптық навигацияны пайдаланыңыз.', 'label' => 'Каталогты ашу', 'href' => '/catalog'],
                'en' => ['heading' => 'Open the collection', 'body' => 'The KazUTB Smart Library catalog contains the indexed materials — use UDC subject navigation to explore.', 'label' => 'Open the catalog', 'href' => '/catalog'],
            ],
        ],
        'digital-access-partner-institutions' => [
            'slug' => 'digital-access-partner-institutions',
            'featured' => false,
            'published_at' => '2026-04-05',
            'published_display' => [
                'ru' => '5 апреля 2026',
                'kk' => '2026 жылғы 5 сәуір',
                'en' => 'April 5, 2026',
            ],
            'category' => [
                'ru' => 'Цифровой доступ',
                'kk' => 'Цифрлық қолжетімділік',
                'en' => 'Digital Access',
            ],
            'title' => [
                'ru' => 'Расширение цифрового доступа для внешних академических партнёров',
                'kk' => 'Сыртқы академиялық серіктестер үшін цифрлық қолжетімділікті кеңейту',
                'en' => 'Expanded Digital Access for External Academic Partners',
            ],
            'excerpt' => [
                'ru' => 'Обновлены механизмы контролируемого доступа для партнёрских университетов — снижены барьеры для международных исследователей без ослабления политик библиотеки.',
                'kk' => 'Серіктес университеттер үшін бақыланатын қолжетімділік механизмдері жаңартылды — халықаралық зерттеушілер үшін тосқауылдар азайды, бірақ саясат босаңсымайды.',
                'en' => 'Controlled-access mechanisms for partner universities have been updated — lower the barrier for international researchers without weakening library policy.',
            ],
            'hero' => [
                'image' => 'images/news/author-visit.jpg',
                'alt' => [
                    'ru' => 'Читатель работает с цифровыми материалами',
                    'kk' => 'Оқырман цифрлық материалдармен жұмыс істеуде',
                    'en' => 'Reader working with digital materials',
                ],
            ],
            'body' => [
                'ru' => [
                    ['type' => 'lead', 'text' => 'Библиотека расширила программу цифрового доступа для партнёрских университетов. Политика доступа и журнал обращений остаются под полным контролем KazUTB Smart Library.'],
                    ['type' => 'p', 'text' => 'Обновлённые потоки доступа поддерживают существующие уровни контроля и работают только в рамках подтверждённых академических соглашений.'],
                ],
                'kk' => [
                    ['type' => 'lead', 'text' => 'Кітапхана серіктес университеттер үшін цифрлық қолжетімділік бағдарламасын кеңейтті. Қолжетімділік саясаты мен өтініш журналы толығымен KazUTB Smart Library бақылауында қалады.'],
                    ['type' => 'p', 'text' => 'Жаңартылған қолжетімділік ағындары қолданыстағы бақылау деңгейлерін қолдайды және тек расталған академиялық келісімдер шеңберінде жұмыс істейді.'],
                ],
                'en' => [
                    ['type' => 'lead', 'text' => 'The library has expanded its digital-access programme for partner universities. Access policy and the request journal remain fully under KazUTB Smart Library control.'],
                    ['type' => 'p', 'text' => 'Updated access flows respect existing control levels and operate only within confirmed academic agreements.'],
                ],
            ],
            'cta' => [
                'ru' => ['heading' => 'Проверить доступ', 'body' => 'Если вы представляете партнёрскую организацию, свяжитесь с библиотекой, чтобы уточнить маршруты доступа.', 'label' => 'Связаться с библиотекой', 'href' => '/contacts'],
                'kk' => ['heading' => 'Қолжетімділікті тексеру', 'body' => 'Серіктес ұйымның өкілі болсаңыз, қолжетімділік маршруттарын нақтылау үшін кітапханаға хабарласыңыз.', 'label' => 'Кітапханамен байланысу', 'href' => '/contacts'],
                'en' => ['heading' => 'Confirm your access', 'body' => 'If you represent a partner institution, contact the library to confirm the right access routes for your team.', 'label' => 'Contact the library', 'href' => '/contacts'],
            ],
        ],
    ];

    $orderedSlugs = [
        'global-symposium-archival-integrity',
        'eurasian-manuscripts-integration',
        'digital-access-partner-institutions',
    ];

    return ['articles' => $articles, 'ordered' => $orderedSlugs];
};

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalog', function (Request $request, CatalogReadService $catalogReadService) {
    $uiSort = (string) $request->query('sort', 'relevance');
    $materialType = (string) $request->query('material_type', 'all');
    $yearBounds = $catalogReadService->yearBounds();
    $apiSortMap = [
        'relevance' => 'popular',
        'title' => 'title',
        'year_desc' => 'newest',
        'year_asc' => 'newest',
        'popular' => 'popular',
        'newest' => 'newest',
        'author' => 'author',
    ];

    $catalogBootstrap = $catalogReadService->search(
        query: trim((string) $request->query('q', '')),
        title: $request->filled('title') ? trim((string) $request->query('title')) : null,
        author: $request->filled('author') ? trim((string) $request->query('author')) : null,
        publisher: $request->filled('publisher') ? trim((string) $request->query('publisher')) : null,
        isbn: $request->filled('isbn') ? trim((string) $request->query('isbn')) : null,
        udc: $request->filled('udc') ? trim((string) $request->query('udc')) : null,
        language: $request->filled('language') ? (string) $request->query('language') : null,
        page: max((int) $request->query('page', 1), 1),
        limit: 10,
        sort: $apiSortMap[$uiSort] ?? 'popular',
        yearFrom: $request->filled('year_from') && is_numeric((string) $request->query('year_from')) ? (int) $request->query('year_from') : $yearBounds['min'],
        yearTo: $request->filled('year_to') && is_numeric((string) $request->query('year_to')) ? (int) $request->query('year_to') : $yearBounds['max'],
        availableOnly: $request->boolean('available_only'),
        physicalOnly: $request->boolean('physical_only'),
        institution: $request->filled('institution') ? (string) $request->query('institution') : null,
    );

    if ($uiSort === 'year_asc' && is_array($catalogBootstrap['data'] ?? null)) {
        usort($catalogBootstrap['data'], static fn (array $left, array $right): int => ((int) ($left['publicationYear'] ?? 0)) <=> ((int) ($right['publicationYear'] ?? 0)));
    }

    if ($materialType !== 'all' && is_array($catalogBootstrap['data'] ?? null)) {
        $catalogBootstrap['data'] = array_values(array_filter(
            $catalogBootstrap['data'],
            static function (array $item) use ($materialType): bool {
                $subjects = array_map(
                    static fn (array $subject): string => mb_strtolower(trim((string) ($subject['label'] ?? ''))),
                    $item['classification'] ?? []
                );
                $subjectText = implode(' ', $subjects);
                $total = (int) ($item['copies']['total'] ?? 0);
                $available = (int) ($item['copies']['available'] ?? 0);
                $kind = str_contains($subjectText, 'диссер') || str_contains($subjectText, 'thesis') || str_contains($subjectText, 'archive')
                    ? 'archive'
                    : ($total > 0 ? ($available > 0 ? 'physical' : 'archive') : 'digital');

                return $kind === $materialType;
            }
        ));
        $catalogBootstrap['meta']['total'] = count($catalogBootstrap['data']);
        $catalogBootstrap['meta']['total_pages'] = 1;
        $catalogBootstrap['meta']['totalPages'] = 1;
        $catalogBootstrap['meta']['page'] = 1;
    }

    return view('catalog', [
        'catalogBootstrap' => $catalogBootstrap,
        'catalogYearBounds' => $yearBounds,
    ]);
});

Route::get('/book/{isbn}', function (Request $request, string $isbn, BookDetailReadService $bookDetailReadService) {
    return view('book', [
        'bookIsbn' => $isbn,
        'bookBootstrap' => $bookDetailReadService->findByIdentifier($isbn),
    ]);
});

Route::get('/digital-viewer/{materialId}', function (Request $request, string $materialId) {
    return view('digital-viewer', ['materialId' => $materialId]);
});

Route::get('/account', function (Request $request) {
    $user = $request->session()->get('library.user');
    $lang = $request->query('lang', 'ru');
    $lang = in_array($lang, ['ru', 'kk', 'en'], true) ? $lang : 'ru';
    $accountPath = $lang === 'ru' ? '/account' : ('/account?lang='.$lang);

    if (! is_array($user)) {
        $loginPath = '/login?redirect='.urlencode($accountPath);
        if ($lang !== 'ru') {
            $loginPath .= '&lang='.$lang;
        }

        return redirect($loginPath);
    }

    return view('account', ['sessionUser' => $user]);
});

Route::post('/login', function (Request $request) use ($postLoginDestination) {
    $validated = $request->validate([
        'email' => ['nullable', 'string', 'email', 'required_without:login'],
        'login' => ['nullable', 'string', 'required_without:email'],
        'password' => ['required', 'string'],
        'device_name' => ['nullable', 'string', 'max:255'],
    ]);

    $loginIdentifier = trim((string) ($validated['login'] ?? $validated['email'] ?? 'unknown'));

    if ((bool) config('demo_auth.enabled')) {
        foreach (config('demo_auth.identities', []) as $slug => $identity) {
            $demoLogin = trim((string) ($identity['login'] ?? ''));
            $demoQuickLogin = trim((string) ($identity['quick_fill_login'] ?? ''));
            $demoEmail = trim((string) ($identity['email'] ?? ''));
            $demoPassword = (string) ($identity['password'] ?? '');

            $identifierMatches = $loginIdentifier !== ''
                && in_array(mb_strtolower($loginIdentifier), array_filter([
                    $demoLogin !== '' ? mb_strtolower($demoLogin) : null,
                    $demoQuickLogin !== '' ? mb_strtolower($demoQuickLogin) : null,
                    $demoEmail !== '' ? mb_strtolower($demoEmail) : null,
                ]), true);

            if ($identifierMatches && $demoPassword !== '' && hash_equals($demoPassword, (string) $validated['password'])) {
                $user = [
                    'id' => (string) ($identity['id'] ?? ''),
                    'name' => (string) ($identity['name'] ?? ''),
                    'email' => (string) ($identity['email'] ?? ''),
                    'login' => (string) ($identity['login'] ?? ''),
                    'ad_login' => (string) ($identity['ad_login'] ?? ''),
                    'role' => (string) ($identity['role'] ?? 'reader'),
                    'title' => (string) ($identity['title'] ?? ''),
                    'phone_extension' => (string) ($identity['phone_extension'] ?? ''),
                    'profile_type' => (string) ($identity['profile_type'] ?? ''),
                ];

                $request->session()->regenerate();
                $request->session()->put('library.crm_token', 'demo-token-' . $slug);
                $request->session()->put('library.user', $user);
                $request->session()->put('library.authenticated_at', now()->toIso8601String());
                $request->session()->put('library.demo_identity', $slug);

                return redirect($postLoginDestination($user));
            }
        }
    }

    $authApiUrl = config('services.external_auth.login_url', 'http://crm.local/api/login');

    $response = Http::timeout(12)->acceptJson()->post($authApiUrl, [
        'email' => $validated['email'] ?? null,
        'login' => $validated['login'] ?? null,
        'password' => $validated['password'],
        'device_name' => $validated['device_name'] ?? 'web',
    ]);

    if ($response->successful()) {
        $payload = $response->json();
        $token = (string) ($payload['token'] ?? $payload['access_token'] ?? '');

        if ($token === '') {
            return back()->withErrors(['login' => 'Authentication service returned an unexpected response.']);
        }

        $rawUser = $payload['user'] ?? $payload['data']['user'] ?? [];
        $user = is_array($rawUser) ? $rawUser : [];

        $request->session()->regenerate();
        $request->session()->put('library.crm_token', $token);
        $request->session()->put('library.user', $user);
        $request->session()->put('library.authenticated_at', now()->toIso8601String());

        return redirect($postLoginDestination($user));
    }

    return back()->withErrors(['login' => 'Invalid credentials or authentication failed.']);
});

// Session-based sign-out used by the member shell (form POST + CSRF).
// Admin and librarian shells use `fetch('/api/v1/logout')` for their JS-driven
// header buttons; this web route exists for the member shell's form control and
// keeps sign-out working without JavaScript. Accepts both POST (canonical form
// submit) and GET (safety net for accidental navigation) — always clears the
// library session keys and redirects to /login.
Route::match(['POST', 'GET'], '/logout', function (Request $request) {
    $request->session()->forget([
        'library.user',
        'library.crm_token',
        'library.authenticated_at',
        'library.demo_identity',
    ]);
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::get('/login', function (Request $request) {
    $lang = $request->query('lang', 'ru');
    $lang = in_array($lang, ['ru', 'kk', 'en'], true) ? $lang : 'ru';

    if (is_array($request->session()->get('library.user'))) {
        return redirect($lang === 'ru' ? '/account' : ('/account?lang='.$lang));
    }

    $demoEnabled = (bool) config('demo_auth.enabled');
    $demoIdentities = [];
    if ($demoEnabled) {
        $demoTranslations = [
            'student' => [
                'ru' => ['label' => 'Студент', 'description' => 'Демо-доступ — поиск, каталог, подборка, кабинет'],
                'kk' => ['label' => 'Студент', 'description' => 'Демо-қолжетімділік — іздеу, каталог, іріктеме, кабинет'],
                'en' => ['label' => 'Student', 'description' => 'Demo access — search, catalog, shortlist, account'],
            ],
            'teacher' => [
                'ru' => ['label' => 'Преподаватель', 'description' => 'Демо-доступ — силлабус, подборка, рабочий стол'],
                'kk' => ['label' => 'Оқытушы', 'description' => 'Демо-қолжетімділік — силлабус, іріктеме, жұмыс аймағы'],
                'en' => ['label' => 'Faculty', 'description' => 'Demo access — syllabus, shortlist, teaching workspace'],
            ],
            'librarian' => [
                'ru' => ['label' => 'Библиотекарь', 'description' => 'Демо-доступ — выдача, возврат, фонд, рецензирование'],
                'kk' => ['label' => 'Кітапханашы', 'description' => 'Демо-қолжетімділік — беру, қайтару, қор, сараптау'],
                'en' => ['label' => 'Librarian', 'description' => 'Demo access — circulation, returns, holdings, review'],
            ],
            'admin' => [
                'ru' => ['label' => 'Администратор', 'description' => 'Демо-доступ — управление, настройки, отчёты'],
                'kk' => ['label' => 'Әкімші', 'description' => 'Демо-қолжетімділік — басқару, баптаулар, есептер'],
                'en' => ['label' => 'Administrator', 'description' => 'Demo access — management, settings, reports'],
            ],
        ];

        foreach (config('demo_auth.identities', []) as $slug => $identity) {
            $localizedIdentity = $demoTranslations[$slug][$lang] ?? null;

            $demoIdentities[] = [
                'slug' => $slug,
                'label' => $localizedIdentity['label'] ?? ($identity['label'] ?? $slug),
                'description' => $localizedIdentity['description'] ?? ($identity['description'] ?? ''),
                'icon' => $identity['icon'] ?? '👤',
                'role' => $identity['role'] ?? 'reader',
                'login' => $identity['login'] ?? '',
                'quickFillLogin' => $identity['quick_fill_login'] ?? ($identity['login'] ?? ''),
                'email' => $identity['email'] ?? '',
                'password' => $identity['password'] ?? '',
            ];
        }
    }

    $copy = [
        'ru' => [
            'title' => 'Secure Access | KazUTB Digital Library',
            'brand' => 'KazUTB Digital Library',
            'legacyHero' => 'Вход в библиотечную систему',
            'displayHeadline' => 'Preserving Knowledge, Empowering Research.',
            'lead' => 'Welcome to the official repository of the Kazakh University of Technology and Business. Access our curated collections of scientific journals, architectural archives, and economic research.',
            'accessHeading' => 'Secure Access',
            'accessValue' => 'Multi-factor authentication is active. Your connection to the digital repository is encrypted using institutional-grade protocols.',
            'supportHeading' => 'Support',
            'supportValue' => 'Encountering issues? Contact the IT help desk at support@kazutb.edu.kz for institutional access recovery.',
            'formTitle' => 'Access KazUTB Digital Library',
            'formSubtitle' => 'Sign in using your institutional credentials to explore our scientific archives.',
            'loginLabel' => 'Institutional ID',
            'loginPlaceholder' => 'Enter your faculty or student ID',
            'passwordLabel' => 'Password',
            'passwordPlaceholder' => '••••••••••••',
            'forgot' => 'Forgot?',
            'keepSigned' => 'Keep me signed in for 30 days',
            'submit' => 'Log in',
            'divider' => 'or access via',
            'sso' => 'Institutional SSO',
            'securityNotice' => 'Unauthorized access is prohibited. All activity is logged for security and auditing purposes as per institutional policy.',
            'demoTitle' => 'Быстрый вход',
            'demoSub' => 'Для проверки можно использовать подготовленные демо-профили.',
            'footerLegal' => '© 2024 KazUTB Digital Library. All rights reserved.',
            'footerLinks' => [
                ['label' => 'Institutional Access', 'href' => '/login'],
                ['label' => 'Privacy Policy', 'href' => '/about'],
                ['label' => 'Terms of Service', 'href' => '/about'],
                ['label' => 'Help Desk', 'href' => '/contacts'],
            ],
        ],
        'kk' => [
            'title' => 'Secure Access | KazUTB Digital Library',
            'brand' => 'KazUTB Digital Library',
            'legacyHero' => 'Кітапхана жүйесіне кіру',
            'displayHeadline' => 'Preserving Knowledge, Empowering Research.',
            'lead' => 'Welcome to the official repository of the Kazakh University of Technology and Business. Access our curated collections of scientific journals, architectural archives, and economic research.',
            'accessHeading' => 'Secure Access',
            'accessValue' => 'Multi-factor authentication is active. Your connection to the digital repository is encrypted using institutional-grade protocols.',
            'supportHeading' => 'Support',
            'supportValue' => 'Encountering issues? Contact the IT help desk at support@kazutb.edu.kz for institutional access recovery.',
            'formTitle' => 'Access KazUTB Digital Library',
            'formSubtitle' => 'Sign in using your institutional credentials to explore our scientific archives.',
            'loginLabel' => 'Institutional ID',
            'loginPlaceholder' => 'Enter your faculty or student ID',
            'passwordLabel' => 'Password',
            'passwordPlaceholder' => '••••••••••••',
            'forgot' => 'Forgot?',
            'keepSigned' => 'Keep me signed in for 30 days',
            'submit' => 'Log in',
            'divider' => 'or access via',
            'sso' => 'Institutional SSO',
            'securityNotice' => 'Unauthorized access is prohibited. All activity is logged for security and auditing purposes as per institutional policy.',
            'demoTitle' => 'Жедел кіру',
            'demoSub' => 'Тексеру үшін дайын демо-профильдерді қолдануға болады.',
            'footerLegal' => '© 2024 KazUTB Digital Library. All rights reserved.',
            'footerLinks' => [
                ['label' => 'Institutional Access', 'href' => '/login'],
                ['label' => 'Privacy Policy', 'href' => '/about'],
                ['label' => 'Terms of Service', 'href' => '/about'],
                ['label' => 'Help Desk', 'href' => '/contacts'],
            ],
        ],
        'en' => [
            'title' => 'Secure Access | KazUTB Digital Library',
            'brand' => 'KazUTB Digital Library',
            'legacyHero' => 'Sign in to the library system',
            'displayHeadline' => 'Preserving Knowledge, Empowering Research.',
            'lead' => 'Welcome to the official repository of the Kazakh University of Technology and Business. Access our curated collections of scientific journals, architectural archives, and economic research.',
            'accessHeading' => 'Secure Access',
            'accessValue' => 'Multi-factor authentication is active. Your connection to the digital repository is encrypted using institutional-grade protocols.',
            'supportHeading' => 'Support',
            'supportValue' => 'Encountering issues? Contact the IT help desk at support@kazutb.edu.kz for institutional access recovery.',
            'formTitle' => 'Access KazUTB Digital Library',
            'formSubtitle' => 'Sign in using your institutional credentials to explore our scientific archives.',
            'loginLabel' => 'Institutional ID',
            'loginPlaceholder' => 'Enter your faculty or student ID',
            'passwordLabel' => 'Password',
            'passwordPlaceholder' => '••••••••••••',
            'forgot' => 'Forgot?',
            'keepSigned' => 'Keep me signed in for 30 days',
            'submit' => 'Log in',
            'divider' => 'or access via',
            'sso' => 'Institutional SSO',
            'securityNotice' => 'Unauthorized access is prohibited. All activity is logged for security and auditing purposes as per institutional policy.',
            'demoTitle' => 'Quick access',
            'demoSub' => 'Prepared demo identities remain available for QA and review.',
            'footerLegal' => '© 2024 KazUTB Digital Library. All rights reserved.',
            'footerLinks' => [
                ['label' => 'Institutional Access', 'href' => '/login'],
                ['label' => 'Privacy Policy', 'href' => '/about'],
                ['label' => 'Terms of Service', 'href' => '/about'],
                ['label' => 'Help Desk', 'href' => '/contacts'],
            ],
        ],
    ];
    return view('auth', [
        'demoEnabled' => $demoEnabled,
        'demoIdentities' => $demoIdentities,
        'copy' => $copy,
        'lang' => $lang,
    ]);
})->name('login');

// Consolidated pages: /services removed — redirect to prevent 404s.
// Phase 3.3 — /news reinstated as the canonical public news index.
Route::get('/services', fn () => redirect('/', 301));

Route::get('/news', function () use ($newsSeedProvider) {
    $seed = $newsSeedProvider();
    $articles = array_map(
        static fn (string $slug) => $seed['articles'][$slug],
        $seed['ordered']
    );

    return view('news.index', [
        'activePage' => 'news',
        'newsArticles' => $articles,
    ]);
});

Route::get('/news/{slug}', function (string $slug) use ($newsSeedProvider) {
    $seed = $newsSeedProvider();
    abort_unless(isset($seed['articles'][$slug]), 404);

    $article = $seed['articles'][$slug];
    $related = array_values(array_filter(
        array_map(static fn (string $relatedSlug) => $seed['articles'][$relatedSlug], $seed['ordered']),
        static fn (array $candidate) => $candidate['slug'] !== $slug,
    ));

    return view('news.show', [
        'activePage' => 'news',
        'article' => $article,
        'relatedArticles' => $related,
    ]);
})->where('slug', '[a-z0-9-]+');

Route::get('/about', function () {
    return view('about', ['activePage' => 'about']);
});

Route::get('/contacts', function () {
    return view('about', ['activePage' => 'contacts']);
});

Route::get('/resources', function () {
    return view('resources', ['activePage' => 'resources']);
});

Route::get('/for-teachers', fn () => redirect('/resources', 301));

Route::get('/discover', function () {
    return view('discover', ['activePage' => 'discover']);
});

Route::get('/shortlist', function () {
    return view('shortlist', ['activePage' => 'shortlist']);
});

// WS1 convergence freeze:
// Transitional reader route retained for controlled migration only.
// Do not add new callers; canonical public detail path is /book/{isbn}.
Route::get('/book/{isbn}/read', function ($isbn) {
    return view('reader', ['isbn' => $isbn]);
})->name('reader.transitional');

// Phase 1.4 — transitional compatibility layer.
// Canonical destinations live under /librarian/*; these paths 301-redirect so that
// deep-links and bookmarks keep working while operational traffic migrates. The
// canonical /librarian/* routes enforce their own auth + role gating, so the
// redirects themselves are intentionally public.
Route::permanentRedirect('/internal/dashboard', '/librarian');
Route::permanentRedirect('/internal/circulation', '/librarian/circulation');
Route::permanentRedirect('/internal/stewardship', '/librarian/data-cleanup');

Route::prefix('internal')->middleware(['library.auth'])->group(function () use ($internalStaffView) {
    // Remaining transitional surfaces — no confirmed canonical /librarian/* destination yet.
    // /internal/review — "Quality Issues Overview" (DB-backed read-only review); candidate
    //   canonical target is undecided between /librarian/data-cleanup and /librarian/repository.
    // /internal/ai-chat — experimental staff AI assistant; no canonical surface in roadmap yet.
    Route::get('/review', function (Request $request) use ($internalStaffView) {
        return $internalStaffView($request, 'internal-review');
    });
    Route::get('/ai-chat', function (Request $request) use ($internalStaffView) {
        return $internalStaffView($request, 'internal-ai-chat');
    });
});

Route::prefix('librarian')->middleware(['library.auth', 'librarian.staff'])->name('librarian.')->group(function () use ($librarianView) {
    // Librarian Overview — canonical landing for library staff (PROJECT_CONTEXT §30).
    Route::get('/', function (Request $request) use ($librarianView) {
        return $librarianView($request, 'librarian.overview');
    })->name('overview');

    // Phase 1.2 — canonical librarian screens (ported from design exports).
    Route::get('/circulation', function (Request $request) use ($librarianView) {
        return $librarianView($request, 'librarian.circulation');
    })->name('circulation');

    Route::get('/data-cleanup', function (Request $request) use ($librarianView) {
        return $librarianView($request, 'librarian.data-cleanup');
    })->name('data-cleanup');

    Route::get('/repository', function (Request $request) use ($librarianView) {
        return $librarianView($request, 'librarian.repository');
    })->name('repository');
});

// Phase 2a — canonical member-facing shell for ordinary users (role='reader').
// Librarians and admins are rejected by the `member.reader` middleware and
// redirected to their own operational shells via the standard 403 flow.
// The transitional /account route is retained and unchanged for now.
Route::prefix('dashboard')->middleware(['library.auth', 'member.reader'])->name('member.')->group(function () use ($memberView) {
    Route::get('/', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.dashboard');
    })->name('dashboard');

    Route::get('/reservations', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.reservations');
    })->name('reservations');

    Route::get('/list', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.list');
    })->name('list');

    Route::get('/history', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.history');
    })->name('history');

    Route::get('/notifications', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.notifications');
    })->name('notifications');

    Route::get('/messages', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.messages');
    })->name('messages');
});

Route::prefix('admin')->middleware(['library.auth', 'admin.staff'])->name('admin.')->group(function () use ($adminView) {
    Route::get('/', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.overview');
    })->name('overview');

    Route::get('/users', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.users');
    })->name('users');

    Route::get('/logs', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.governance');
    })->name('logs');

    Route::get('/news', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.news');
    })->name('news');

    Route::get('/feedback', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.feedback');
    })->name('feedback');

    Route::get('/settings', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.settings');
    })->name('settings');

    Route::get('/reports', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.reports');
    })->name('reports');
});

// SPA shell — React Router handles client-side routing under /app
Route::get('/app/{any?}', function () {
    return view('spa');
})->where('any', '.*');
