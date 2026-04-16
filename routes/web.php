use Illuminate\Support\Facades\Http;
Route::post('/login', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'email' => ['nullable', 'string', 'email', 'required_without:login'],
        'login' => ['nullable', 'string', 'required_without:email'],
        'password' => ['required', 'string'],
        'device_name' => ['nullable', 'string', 'max:255'],
    ]);

    $loginIdentifier = $validated['login'] ?? $validated['email'] ?? 'unknown';
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
        return redirect('/account');
    }
    return back()->withErrors(['login' => 'Invalid credentials or authentication failed.']);
});
<?php

use App\Services\Library\BookDetailReadService;
use App\Services\Library\CatalogReadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$internalStaffView = static function (Request $request, string $view) {
    $user = $request->session()->get('library.user');
    $role = is_array($user) ? mb_strtolower(trim((string) ($user['role'] ?? ''))) : '';

    abort_unless(in_array($role, ['librarian', 'admin'], true), 403);

    return view($view, [
        'internalStaffUser' => $user,
    ]);
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
            ];
        }
    }

    $copy = [
        'ru' => [
            'title' => 'Безопасный вход — Digital Library',
            'brand' => 'KazUTB Digital Library',
            'eyebrow' => 'Защищённый институциональный доступ',
            'hero' => 'Вход в библиотечную систему',
            'lead' => 'Авторизуйтесь, чтобы открыть личный кабинет, проверить выдачи, управлять бронированиями и переходить к контролируемым цифровым материалам.',
            'loginLabel' => 'Логин или Email',
            'loginPlaceholder' => 'Например: student01 или mail@example.com',
            'passwordLabel' => 'Пароль',
            'passwordPlaceholder' => 'Введите пароль',
            'submit' => 'Продолжить',
            'eyebrow' => 'Защищённый институциональный доступ',
            'accessValue' => 'Сессия продолжается внутри библиотеки, а проверка учётных данных идёт через CRM API.',
            'footerLegal' => '© 2024 КазТБУ Digital Library. Все права защищены.',
        ],
        'kk' => [
            'title' => 'Қауіпсіз кіру — Digital Library',
            'brand' => 'KazUTB Digital Library',
            'eyebrow' => 'Қауіпсіз институционалдық қолжетімділік',
            'hero' => 'Кітапхана жүйесіне кіру',
            'lead' => 'Жеке кабинетке кіру, берілімдерді тексеру, броньдарды басқару және бақыланатын цифрлық материалдарға өту үшін авторизациядан өтіңіз.',
            'loginLabel' => 'Логин немесе Email',
            'loginPlaceholder' => 'Мысалы: student01 немесе mail@example.com',
            'passwordLabel' => 'Құпиясөз',
            'passwordPlaceholder' => 'Құпиясөзді енгізіңіз',
            'submit' => 'Жалғастыру',
            'eyebrow' => 'Қауіпсіз институционалдық қолжетімділік',
            'accessValue' => 'Сессия кітапхана ішінде жалғасады, ал тіркелгі деректерін тексеру CRM API арқылы жүреді.',
            'footerLegal' => '© 2024 КазТБУ Digital Library. Барлық құқықтар қорғалған.',
        ],
        'en' => [
            'title' => 'Secure access — Digital Library',
            'brand' => 'KazUTB Digital Library',
            'eyebrow' => 'Secure institutional access',
            'hero' => 'Sign in to the library system',
            'lead' => 'Authenticate to open your account, review loans, manage reservations, and move into controlled digital materials.',
            'loginLabel' => 'Login or email',
            'loginPlaceholder' => 'Example: student01 or mail@example.com',
            'passwordLabel' => 'Password',
            'passwordPlaceholder' => 'Enter your password',
            'submit' => 'Continue',
            'eyebrow' => 'Secure institutional access',
            'accessValue' => 'The session stays inside the library interface while credentials are verified through the CRM API.',
            'footerLegal' => '© 2024 KazUTB Digital Library. All rights reserved.',
        ],
    ];
    return view('auth', [
        'demoEnabled' => $demoEnabled,
        'demoIdentities' => $demoIdentities,
        'copy' => $copy,
        'lang' => $lang,
    ]);
});

// Consolidated pages: /services and /news removed — redirects to prevent 404s.
Route::get('/services', fn () => redirect('/', 301));
Route::get('/news', fn () => redirect('/', 301));
Route::get('/about', function () {
    return view('about', ['activePage' => 'about']);
});

Route::get('/contacts', function () {
    return view('about', ['activePage' => 'about']);
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

Route::prefix('internal')->group(function () use ($internalStaffView) {
    Route::get('/dashboard', function (Request $request) use ($internalStaffView) {
        return $internalStaffView($request, 'internal-dashboard');
    });
    Route::get('/review', function (Request $request) use ($internalStaffView) {
        return $internalStaffView($request, 'internal-review');
    });
    Route::get('/stewardship', function (Request $request) use ($internalStaffView) {
        return $internalStaffView($request, 'internal-stewardship');
    });
    Route::get('/circulation', function (Request $request) use ($internalStaffView) {
        return $internalStaffView($request, 'internal-circulation');
    });
    Route::get('/ai-chat', function (Request $request) use ($internalStaffView) {
        return $internalStaffView($request, 'internal-ai-chat');
    });
});

// SPA shell — React Router handles client-side routing under /app
Route::get('/app/{any?}', function () {
    return view('spa');
})->where('any', '.*');
