<?php

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
        language: $request->filled('language') ? (string) $request->query('language') : null,
        page: max((int) $request->query('page', 1), 1),
        limit: 10,
        sort: $apiSortMap[$uiSort] ?? 'popular',
        yearFrom: $request->filled('year_from') && is_numeric((string) $request->query('year_from')) ? (int) $request->query('year_from') : null,
        yearTo: $request->filled('year_to') && is_numeric((string) $request->query('year_to')) ? (int) $request->query('year_to') : null,
        availableOnly: $request->boolean('available_only'),
        physicalOnly: $request->boolean('physical_only'),
        institution: $request->filled('institution') ? (string) $request->query('institution') : null,
    );

    if ($uiSort === 'year_asc' && is_array($catalogBootstrap['data'] ?? null)) {
        usort($catalogBootstrap['data'], static fn (array $left, array $right): int => ((int) ($left['publicationYear'] ?? 0)) <=> ((int) ($right['publicationYear'] ?? 0)));
    }

    return view('catalog', ['catalogBootstrap' => $catalogBootstrap]);
});

Route::get('/book/{isbn}', function () {
    return view('book');
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

    return view('auth', [
        'demoEnabled' => $demoEnabled,
        'demoIdentities' => $demoIdentities,
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
