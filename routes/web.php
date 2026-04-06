<?php

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

Route::get('/catalog', function () {
    return view('catalog');
});

Route::get('/book/{isbn}', function () {
    return view('book');
});

Route::get('/account', function (Request $request) {
    $user = $request->session()->get('library.user');
    if (! is_array($user)) {
        return redirect('/login?redirect=' . urlencode('/account'));
    }

    return view('account', ['sessionUser' => $user]);
});

Route::get('/login', function (Request $request) {
    if (is_array($request->session()->get('library.user'))) {
        return redirect('/account');
    }

    $demoEnabled = (bool) config('demo_auth.enabled');
    $demoIdentities = [];
    if ($demoEnabled) {
        foreach (config('demo_auth.identities', []) as $slug => $identity) {
            $demoIdentities[] = [
                'slug' => $slug,
                'label' => $identity['label'] ?? $slug,
                'description' => $identity['description'] ?? '',
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

// Consolidated pages: /services, /about, /news removed — redirects to prevent 404s.
Route::get('/services', fn () => redirect('/', 301));
Route::get('/news', fn () => redirect('/', 301));
Route::get('/about', fn () => redirect('/contacts', 301));

Route::get('/contacts', function () {
    return view('contacts', ['activePage' => 'contacts']);
});

Route::get('/resources', function () {
    return view('resources', ['activePage' => 'resources']);
});

Route::get('/for-teachers', function () {
    return view('for-teachers', ['activePage' => 'for-teachers']);
});

Route::get('/discover', function () {
    return view('discover', ['activePage' => 'discover']);
});

Route::get('/shortlist', function () {
    return view('shortlist', ['activePage' => '']);
});

Route::get('/internal/dashboard', function () {
    return view('internal-dashboard');
});

Route::get('/internal/review', function () {
    return view('internal-review');
});

Route::get('/internal/ai-chat', function (Request $request) use ($internalStaffView) {
    return $internalStaffView($request, 'internal-ai-chat');
});

// WS1 convergence freeze:
// Transitional reader route retained for controlled migration only.
// Do not add new callers; canonical public detail path is /book/{isbn}.
Route::get('/book/{isbn}/read', function ($isbn) {
    return view('reader', ['isbn' => $isbn]);
})->name('reader.transitional');

Route::prefix('internal')->group(function () use ($internalStaffView) {
    Route::get('/dashboard', function () {
        return view('internal-dashboard');
    });
    Route::get('/review', function () {
        return view('internal-review');
    });
    Route::get('/stewardship', function () {
        return view('internal-stewardship');
    });
    Route::get('/circulation', function () {
        return view('internal-circulation');
    });
    Route::get('/ai-chat', function (Request $request) use ($internalStaffView) {
        return $internalStaffView($request, 'internal-ai-chat');
    });
});

// SPA shell — React Router handles client-side routing under /app
Route::get('/app/{any?}', function () {
    return view('spa');
})->where('any', '.*');
