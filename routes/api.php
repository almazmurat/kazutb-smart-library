<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BridgeController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\LibraryController;
use App\Http\Controllers\Api\LandingController;
use App\Http\Controllers\Api\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/v1/account/summary', [AccountController::class, 'summary']);
    Route::get('/v1/me', [AuthController::class, 'me']);
    Route::post('/v1/logout', [AuthController::class, 'logout']);
});

Route::prefix('v1')->group(function (): void {
    Route::get('/bridge/summary', [BridgeController::class, 'summary']);
    Route::get('/bridge/users', [BridgeController::class, 'users']);
    Route::get('/bridge/copies', [BridgeController::class, 'copies']);
    Route::get('/bridge/books', [BridgeController::class, 'books']);
    Route::get('/book-db/{isbn}', [BookController::class, 'dbShow']);
    Route::get('/catalog-db', [CatalogController::class, 'dbIndex']);
    Route::get('/library/health-summary', [LibraryController::class, 'healthSummary']);
    Route::get('/review/issues', [ReviewController::class, 'issues']);
    Route::get('/review/issues-summary', [ReviewController::class, 'issuesSummary']);
    Route::get('/catalog', [CatalogController::class, 'index']);
    Route::get('/catalog-external', [CatalogController::class, 'proxy']);
    Route::get('/catalog/{isbn}', [BookController::class, 'show']);
    Route::get('/landing', [LandingController::class, 'index']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
