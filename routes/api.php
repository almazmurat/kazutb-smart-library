<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\LandingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('v1')->group(function (): void {
    Route::get('/catalog', [CatalogController::class, 'index']);
    Route::get('/catalog-external', [CatalogController::class, 'proxy']);
    Route::get('/catalog/{isbn}', [BookController::class, 'show']);
    Route::get('/landing', [LandingController::class, 'index']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
