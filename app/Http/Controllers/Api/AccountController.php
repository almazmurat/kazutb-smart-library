<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Library\AccountSummaryReadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function summary(Request $request, AccountSummaryReadService $service): JsonResponse
    {
        $user = $request->session()->get('library.user');

        if (! is_array($user)) {
            return response()->json([
                'authenticated' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        return response()->json([
            'authenticated' => true,
            ...$service->summary($user),
        ]);
    }
}
