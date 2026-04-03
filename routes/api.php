<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BridgeController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\InternalAiAssistantController;
use App\Http\Controllers\Api\InternalCopyReadController;
use App\Http\Controllers\Api\InternalCopyWriteController;
use App\Http\Controllers\Api\InternalCirculationController;
use App\Http\Controllers\Api\InternalReviewController;
use App\Http\Controllers\Api\LibraryController;
use App\Http\Controllers\Api\LandingController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\Integration\DocumentManagementController;
use App\Http\Controllers\Api\Integration\ReservationReadController;
use App\Http\Controllers\Api\Integration\ReservationMutateController;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

Route::middleware('web')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/v1/account/summary', [AccountController::class, 'summary']);
    Route::get('/v1/me', [AuthController::class, 'me']);
    Route::post('/v1/logout', [AuthController::class, 'logout']);
});

Route::prefix('v1')->group(function (): void {
    Route::prefix('internal/circulation')
        ->middleware([
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            'internal.circulation.staff',
        ])
        ->group(function (): void {
        Route::get('/loans/{loanId}', [InternalCirculationController::class, 'showLoan']);
        Route::get('/copies/{copyId}/active-loan', [InternalCirculationController::class, 'showActiveLoanForCopy']);
        Route::get('/readers/{readerId}/loans', [InternalCirculationController::class, 'listReaderLoans']);
        Route::post('/checkouts', [InternalCirculationController::class, 'checkout']);
        Route::post('/returns', [InternalCirculationController::class, 'returnCopy']);
    });

    Route::prefix('internal')
        ->middleware([
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            'internal.circulation.staff',
        ])
        ->group(function (): void {
            Route::get('/copies/{copyId}', [InternalCopyReadController::class, 'show']);
            Route::get('/documents/{documentId}/copies', [InternalCopyReadController::class, 'listByDocument']);
            Route::post('/copies', [InternalCopyWriteController::class, 'store']);
            Route::patch('/copies/{copyId}', [InternalCopyWriteController::class, 'patch']);
            Route::post('/copies/{copyId}/retire', [InternalCopyWriteController::class, 'retire']);
            Route::get('/review/copies', [InternalReviewController::class, 'copyQueue']);
            Route::get('/review/copies-summary', [InternalReviewController::class, 'copySummary']);
            Route::post('/review/copies/{copyId}/resolve', [InternalReviewController::class, 'resolveCopy']);
            Route::get('/review/documents', [InternalReviewController::class, 'documentQueue']);
            Route::get('/review/documents-summary', [InternalReviewController::class, 'documentSummary']);
            Route::post('/review/documents/{documentId}/flag', [InternalReviewController::class, 'flagDocument']);
            Route::post('/review/documents/{documentId}/resolve', [InternalReviewController::class, 'resolveDocument']);
            Route::get('/review/readers', [InternalReviewController::class, 'readerQueue']);
            Route::get('/review/readers-summary', [InternalReviewController::class, 'readerSummary']);
            Route::post('/review/readers/{readerId}/resolve', [InternalReviewController::class, 'resolveReader']);
            Route::get('/review/triage-summary', [InternalReviewController::class, 'triageSummary']);
            Route::get('/review/triage-reason-codes', [InternalReviewController::class, 'triageReasonCodes']);
        });

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

    Route::prefix('internal/ai-assistant')
        ->middleware([
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            'internal.circulation.staff',
        ])
        ->group(function (): void {
            Route::post('/token', [InternalAiAssistantController::class, 'token']);
            Route::post('/session', [InternalAiAssistantController::class, 'session']);
            Route::post('/thread', [InternalAiAssistantController::class, 'thread']);
        });
});

Route::prefix('integration/v1')
    ->middleware(['integration.boundary'])
    ->group(function (): void {
        // Technical boundary endpoint for infrastructure verification only.
        Route::get('/_boundary/ping', function (Request $request) {
            return response()->json([
                'ok' => true,
                'context' => [
                    'authenticated_client_ref' => $request->attributes->get('integration.authenticated_client_ref'),
                    'source_system' => $request->attributes->get('integration.source_system'),
                    'request_id' => $request->attributes->get('integration.request_id'),
                    'correlation_id' => $request->attributes->get('integration.correlation_id'),
                ],
            ]);
        });

        // Future external integration endpoints (reservation shell v1):
        // Read-only external reservation endpoints (shell v1, phase 1):
        Route::get('/reservations', [ReservationReadController::class, 'index']);
        Route::get('/reservations/{id}', [ReservationReadController::class, 'show']);

        // Mutate endpoints (shell v1, phase 2): approve / reject only.
        Route::post('/reservations/{id}/approve', [ReservationMutateController::class, 'approve']);
        Route::post('/reservations/{id}/reject', [ReservationMutateController::class, 'reject']);

        // Book management v1 phase 1 (document metadata only).
        Route::get('/documents', [DocumentManagementController::class, 'index']);
        Route::get('/documents/{id}', [DocumentManagementController::class, 'show']);
        Route::post('/documents', [DocumentManagementController::class, 'store']);
        Route::patch('/documents/{id}', [DocumentManagementController::class, 'patch']);
        Route::post('/documents/{id}/archive', [DocumentManagementController::class, 'archive']);
    });

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
