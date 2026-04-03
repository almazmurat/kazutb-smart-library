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

Route::get('/account', function () {
    return view('account');
});

Route::get('/login', function () {
    return view('auth');
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

Route::get('/book/{isbn}/read', function ($isbn) {
    return view('reader', ['isbn' => $isbn]);
});

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
    Route::get('/ai-chat', function (Request $request) use ($internalStaffView) {
        return $internalStaffView($request, 'internal-ai-chat');
    });
});
