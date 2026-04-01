<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Library\CirculationLoanWriteService;
use App\Services\Library\CirculationWriteException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InternalCirculationController extends Controller
{
    public function checkout(Request $request, CirculationLoanWriteService $service): JsonResponse
    {
        $validated = $request->validate([
            'reader_id' => ['required', 'uuid'],
            'copy_id' => ['required', 'uuid'],
            'due_at' => ['nullable', 'date'],
            'actor_user_id' => ['nullable', 'uuid'],
            'request_id' => ['nullable', 'string', 'max:128'],
            'correlation_id' => ['nullable', 'string', 'max:128'],
        ]);

        try {
            $result = $service->checkout(
                readerId: (string) $validated['reader_id'],
                copyId: (string) $validated['copy_id'],
                dueAt: isset($validated['due_at']) ? (string) $validated['due_at'] : null,
                context: [
                    'actorUserId' => isset($validated['actor_user_id']) ? (string) $validated['actor_user_id'] : null,
                    'requestId' => isset($validated['request_id']) ? (string) $validated['request_id'] : null,
                    'correlationId' => isset($validated['correlation_id']) ? (string) $validated['correlation_id'] : null,
                    'actorType' => 'staff_operator',
                ],
            );
        } catch (CirculationWriteException $exception) {
            return response()->json([
                'error' => $exception->errorCode(),
                'message' => $exception->getMessage(),
                'success' => false,
            ], $exception->httpStatus());
        }

        return response()->json([
            'success' => true,
        ] + $result, 201);
    }

    public function returnCopy(Request $request, CirculationLoanWriteService $service): JsonResponse
    {
        $validated = $request->validate([
            'copy_id' => ['required', 'uuid'],
            'actor_user_id' => ['nullable', 'uuid'],
            'request_id' => ['nullable', 'string', 'max:128'],
            'correlation_id' => ['nullable', 'string', 'max:128'],
        ]);

        try {
            $result = $service->returnCopy(
                copyId: (string) $validated['copy_id'],
                context: [
                    'actorUserId' => isset($validated['actor_user_id']) ? (string) $validated['actor_user_id'] : null,
                    'requestId' => isset($validated['request_id']) ? (string) $validated['request_id'] : null,
                    'correlationId' => isset($validated['correlation_id']) ? (string) $validated['correlation_id'] : null,
                    'actorType' => 'staff_operator',
                ],
            );
        } catch (CirculationWriteException $exception) {
            return response()->json([
                'error' => $exception->errorCode(),
                'message' => $exception->getMessage(),
                'success' => false,
            ], $exception->httpStatus());
        }

        return response()->json([
            'success' => true,
        ] + $result);
    }
}
