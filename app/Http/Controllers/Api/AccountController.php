<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Library\AccountSummaryReadService;
use App\Services\Library\CirculationLoanReadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function loans(Request $request, CirculationLoanReadService $loanService): JsonResponse
    {
        $user = $request->session()->get('library.user');

        if (! is_array($user)) {
            return response()->json([
                'authenticated' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $readerId = $this->resolveReaderId($user);

        if ($readerId === null) {
            return response()->json([
                'authenticated' => true,
                'data' => [],
                'message' => 'No linked reader profile found.',
            ]);
        }

        $status = $request->query('status');
        $validStatuses = ['active', 'returned'];
        $status = in_array($status, $validStatuses, true) ? $status : null;

        $loans = $loanService->findLoansByReader($readerId, $status);

        return response()->json([
            'authenticated' => true,
            'data' => $loans,
            'meta' => [
                'readerId' => $readerId,
                'total' => count($loans),
            ],
        ]);
    }

    private function resolveReaderId(array $sessionUser): ?string
    {
        $email = mb_strtolower(trim((string) ($sessionUser['email'] ?? '')));
        $adLogin = mb_strtolower(trim((string) ($sessionUser['ad_login'] ?? '')));

        $identifiers = array_values(array_filter(array_unique([$email, $adLogin]), fn (string $v) => $v !== ''));

        if ($identifiers === []) {
            return null;
        }

        $readerId = DB::table('app.readers as r')
            ->join('app.reader_contacts as rc', 'rc.reader_id', '=', 'r.id')
            ->where('rc.contact_type', 'EMAIL')
            ->where(function ($query) use ($identifiers): void {
                foreach ($identifiers as $value) {
                    $query->orWhere('rc.value_normalized_key', $value);
                }
            })
            ->orderByDesc('r.registration_at')
            ->value('r.id');

        return is_string($readerId) ? $readerId : null;
    }
}
