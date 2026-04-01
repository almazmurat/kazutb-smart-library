<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIntegrationBoundary
{
    /**
     * @var array<int, string>
     */
    private array $requiredHeaders = [
        'X-Request-Id',
        'X-Correlation-Id',
        'X-Source-System',
        'X-Operator-Id',
        'X-Operator-Roles',
        'X-Operator-Org-Context',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $token = (string) $request->bearerToken();
        if (trim($token) === '') {
            return $this->errorResponse(
                request: $request,
                status: 401,
                errorCode: 'auth_failed',
                reasonCode: 'missing_bearer_token',
                message: 'Missing bearer token in Authorization header.'
            );
        }

        foreach ($this->requiredHeaders as $header) {
            $value = (string) $request->header($header, '');
            if (trim($value) === '') {
                return $this->errorResponse(
                    request: $request,
                    status: 400,
                    errorCode: 'invalid_request',
                    reasonCode: 'missing_required_header',
                    message: 'Missing required header: ' . $header,
                );
            }
        }

        $sourceSystem = mb_strtolower(trim((string) $request->header('X-Source-System')));
        if ($sourceSystem !== 'crm') {
            return $this->errorResponse(
                request: $request,
                status: 400,
                errorCode: 'invalid_request',
                reasonCode: 'invalid_source_system',
                message: 'X-Source-System must be crm.',
            );
        }

        $requestId = trim((string) $request->header('X-Request-Id'));
        $correlationId = trim((string) $request->header('X-Correlation-Id'));

        $request->attributes->set('integration.authenticated_client_ref', 'token:' . substr(hash('sha256', $token), 0, 16));
        $request->attributes->set('integration.source_system', $sourceSystem);
        $request->attributes->set('integration.request_id', $requestId);
        $request->attributes->set('integration.correlation_id', $correlationId);

        $response = $next($request);

        $response->headers->set('X-Request-Id', $requestId);
        $response->headers->set('X-Correlation-Id', $correlationId);

        return $response;
    }

    private function errorResponse(
        Request $request,
        int $status,
        string $errorCode,
        string $reasonCode,
        string $message
    ): JsonResponse {
        $requestId = trim((string) $request->header('X-Request-Id', ''));
        if ($requestId === '') {
            $requestId = (string) str()->uuid();
        }

        $correlationId = trim((string) $request->header('X-Correlation-Id', ''));
        if ($correlationId === '') {
            $correlationId = $requestId;
        }

        return response()->json([
            'error' => [
                'error_code' => $errorCode,
                'reason_code' => $reasonCode,
                'message' => $message,
            ],
            'request_id' => $requestId,
            'correlation_id' => $correlationId,
            'timestamp' => now()->toISOString(),
        ], $status, [
            'X-Request-Id' => $requestId,
            'X-Correlation-Id' => $correlationId,
        ]);
    }
}
