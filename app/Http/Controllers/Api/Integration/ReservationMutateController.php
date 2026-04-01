<?php

namespace App\Http\Controllers\Api\Integration;

use App\Http\Controllers\Controller;
use App\Services\Library\IntegrationReservationMutationException;
use App\Services\Library\IntegrationReservationWriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservationMutateController extends Controller
{
    public function __construct(
        private readonly IntegrationReservationWriteService $service,
    ) {}

    public function approve(Request $request, string $id): JsonResponse
    {
        if (! Str::isUuid($id)) {
            return $this->errorResponse(
                request: $request,
                status: 400,
                errorCode: 'invalid_request',
                reasonCode: 'invalid_reservation_id',
                message: 'Reservation id must be a valid UUID.',
            );
        }

        $idempotencyKey = trim((string) $request->header('Idempotency-Key', ''));
        if ($idempotencyKey === '') {
            return $this->errorResponse(
                request: $request,
                status: 400,
                errorCode: 'invalid_request',
                reasonCode: 'missing_idempotency_key',
                message: 'Missing Idempotency-Key header.',
            );
        }

        try {
            $result = $this->service->approve(
                reservationId: $id,
                idempotencyKey: $idempotencyKey,
                context: $this->context($request),
            );

            return response()->json($result['body'], $result['status']);
        } catch (IntegrationReservationMutationException $e) {
            return $this->errorResponse(
                request: $request,
                status: $e->httpStatus,
                errorCode: $e->errorCode,
                reasonCode: $e->reasonCode,
                message: $e->getMessage(),
            );
        }
    }

    public function reject(Request $request, string $id): JsonResponse
    {
        if (! Str::isUuid($id)) {
            return $this->errorResponse(
                request: $request,
                status: 400,
                errorCode: 'invalid_request',
                reasonCode: 'invalid_reservation_id',
                message: 'Reservation id must be a valid UUID.',
            );
        }

        $idempotencyKey = trim((string) $request->header('Idempotency-Key', ''));
        if ($idempotencyKey === '') {
            return $this->errorResponse(
                request: $request,
                status: 400,
                errorCode: 'invalid_request',
                reasonCode: 'missing_idempotency_key',
                message: 'Missing Idempotency-Key header.',
            );
        }

        $cancelOrigin = strtoupper(trim((string) $request->input('cancel_origin', '')));
        $cancelReasonCode = strtoupper(trim((string) $request->input('cancel_reason_code', '')));

        if ($cancelOrigin === '' || $cancelReasonCode === '') {
            return $this->errorResponse(
                request: $request,
                status: 400,
                errorCode: 'invalid_request',
                reasonCode: 'missing_cancel_reason_or_origin',
                message: 'cancel_origin and cancel_reason_code are required for reject.',
            );
        }

        if ($cancelOrigin !== 'OPERATOR_REJECT') {
            return $this->errorResponse(
                request: $request,
                status: 400,
                errorCode: 'invalid_request',
                reasonCode: 'invalid_cancel_origin',
                message: 'cancel_origin must be OPERATOR_REJECT for reject command.',
            );
        }

        try {
            $result = $this->service->reject(
                reservationId: $id,
                idempotencyKey: $idempotencyKey,
                cancelOrigin: $cancelOrigin,
                cancelReasonCode: $cancelReasonCode,
                context: $this->context($request),
            );

            return response()->json($result['body'], $result['status']);
        } catch (IntegrationReservationMutationException $e) {
            return $this->errorResponse(
                request: $request,
                status: $e->httpStatus,
                errorCode: $e->errorCode,
                reasonCode: $e->reasonCode,
                message: $e->getMessage(),
            );
        }
    }

    /**
     * @return array{authenticatedClientRef:string, operatorId:string, requestId:string, correlationId:string}
     */
    private function context(Request $request): array
    {
        return [
            'authenticatedClientRef' => (string) $request->attributes->get('integration.authenticated_client_ref'),
            'operatorId' => trim((string) $request->header('X-Operator-Id', '')),
            'requestId' => (string) $request->attributes->get('integration.request_id'),
            'correlationId' => (string) $request->attributes->get('integration.correlation_id'),
        ];
    }

    private function errorResponse(
        Request $request,
        int $status,
        string $errorCode,
        string $reasonCode,
        string $message,
    ): JsonResponse {
        return response()->json([
            'error' => [
                'error_code' => $errorCode,
                'reason_code' => $reasonCode,
                'message' => $message,
            ],
            'request_id' => $request->attributes->get('integration.request_id'),
            'correlation_id' => $request->attributes->get('integration.correlation_id'),
            'timestamp' => now()->toISOString(),
        ], $status);
    }
}
