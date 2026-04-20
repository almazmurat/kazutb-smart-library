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

    abort_unless(in_array($role, ['librarian', 'admin'], true), 403);

    return view($view, [
        'internalStaffUser' => $user,
    ]);
};

$adminView = static function (Request $request, string $view, array $data = []) {
    return view($view, array_merge([
        'internalStaffUser' => $request->session()->get('library.user'),
    ], $data));
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

Route::post('/login', function (Request $request) {
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

                return redirect('/account');
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

        return redirect('/account');
    }

    return back()->withErrors(['login' => 'Invalid credentials or authentication failed.']);
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

Route::prefix('admin')->middleware(['library.auth', 'admin.staff'])->name('admin.')->group(function () use ($adminView) {
    Route::get('/', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.overview');
    })->name('overview');

    Route::get('/users', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.placeholder', [
            'title' => 'User & Role Management',
            'description' => 'Phase 1 continuation surface for reader, staff, and permission governance.',
        ]);
    })->name('users');

    Route::get('/logs', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.placeholder', [
            'title' => 'Governance & Logs',
            'description' => 'Phase 1 continuation surface for audit visibility, operational logs, and policy review.',
        ]);
    })->name('logs');

    Route::get('/news', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.placeholder', [
            'title' => 'News Management',
            'description' => 'Phase 2 management surface for institutional announcements and controlled publishing.',
        ]);
    })->name('news');

    Route::get('/feedback', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.placeholder', [
            'title' => 'Feedback Inbox',
            'description' => 'Phase 2 management surface for incoming reader requests and issue triage.',
        ]);
    })->name('feedback');

    Route::get('/settings', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.placeholder', [
            'title' => 'System Settings',
            'description' => 'Phase 2 management surface for operational configuration and platform governance.',
        ]);
    })->name('settings');

    Route::get('/reports', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.placeholder', [
            'title' => 'Reports & Analytics',
            'description' => 'Phase 3 analytical surface for circulation, holdings, stewardship, and institutional reporting.',
        ]);
    })->name('reports');
});

// SPA shell — React Router handles client-side routing under /app
Route::get('/app/{any?}', function () {
    return view('spa');
})->where('any', '.*');
