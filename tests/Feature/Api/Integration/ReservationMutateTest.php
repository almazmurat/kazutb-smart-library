<?php

namespace Tests\Feature\Api\Integration;

use App\Services\Library\IntegrationReservationMutationException;
use App\Services\Library\IntegrationReservationWriteService;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ReservationMutateTest extends TestCase
{
    /** @var array<string, string> */
    private array $headers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->headers = [
            'Authorization' => 'Bearer integration-test-token',
            'X-Request-Id' => 'req-mut-001',
            'X-Correlation-Id' => 'corr-mut-001',
            'X-Source-System' => 'crm',
            'X-Operator-Id' => 'crm-op-99',
            'X-Operator-Roles' => 'reservations.approve,reservations.reject',
            'X-Operator-Org-Context' => '{"branch_id":"f84341eb-1010-45be-b93e-6c94cd9cea8a"}',
            'Idempotency-Key' => 'idem-001',
        ];
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_approve_success(): void
    {
        $id = '4327164d-49ae-48ad-98c5-cff27c3aa8fc';
        $this->bindApproveResult($id, [
            'status' => 200,
            'body' => [
                'data' => [
                    'id' => $id,
                    'status' => 'READY',
                    'processed_at' => '2026-04-01T20:00:00.000000Z',
                    'updated_at' => '2026-04-01T20:00:00.000000Z',
                ],
                'request_id' => 'req-mut-001',
                'correlation_id' => 'corr-mut-001',
                'timestamp' => '2026-04-01T20:00:00.000000Z',
            ],
            'replayed' => false,
        ]);

        $response = $this->withHeaders($this->headers)
            ->postJson("/api/integration/v1/reservations/{$id}/approve");

        $response->assertOk()
            ->assertJsonPath('data.status', 'READY')
            ->assertJsonPath('request_id', 'req-mut-001');
    }

    public function test_reject_success(): void
    {
        $id = '4327164d-49ae-48ad-98c5-cff27c3aa8fc';

        /** @var MockInterface&IntegrationReservationWriteService $mock */
        $mock = Mockery::mock(IntegrationReservationWriteService::class);
        $mock->shouldReceive('reject')
            ->once()
            ->with(
                $id,
                'idem-001',
                'OPERATOR_REJECT',
                'ITEM_NOT_ELIGIBLE',
                Mockery::type('array')
            )
            ->andReturn([
                'status' => 200,
                'body' => [
                    'data' => [
                        'id' => $id,
                        'status' => 'CANCELLED',
                        'cancel_origin' => 'OPERATOR_REJECT',
                        'cancel_reason_code' => 'ITEM_NOT_ELIGIBLE',
                    ],
                    'request_id' => 'req-mut-001',
                    'correlation_id' => 'corr-mut-001',
                    'timestamp' => '2026-04-01T20:00:00.000000Z',
                ],
                'replayed' => false,
            ]);

        $this->app->instance(IntegrationReservationWriteService::class, $mock);

        $response = $this->withHeaders($this->headers)->postJson(
            "/api/integration/v1/reservations/{$id}/reject",
            [
                'cancel_origin' => 'OPERATOR_REJECT',
                'cancel_reason_code' => 'ITEM_NOT_ELIGIBLE',
            ]
        );

        $response->assertOk()
            ->assertJsonPath('data.status', 'CANCELLED')
            ->assertJsonPath('data.cancel_origin', 'OPERATOR_REJECT');
    }

    public function test_invalid_transition_returns_conflict(): void
    {
        $id = '4327164d-49ae-48ad-98c5-cff27c3aa8fc';

        /** @var MockInterface&IntegrationReservationWriteService $mock */
        $mock = Mockery::mock(IntegrationReservationWriteService::class);
        $mock->shouldReceive('approve')
            ->once()
            ->andThrow(new IntegrationReservationMutationException(
                errorCode: 'conflict',
                reasonCode: 'invalid_state_transition',
                message: 'Approve is only allowed when reservation status is PENDING.',
                httpStatus: 409,
            ));

        $this->app->instance(IntegrationReservationWriteService::class, $mock);

        $response = $this->withHeaders($this->headers)
            ->postJson("/api/integration/v1/reservations/{$id}/approve");

        $response->assertStatus(409)
            ->assertJsonPath('error.error_code', 'conflict')
            ->assertJsonPath('error.reason_code', 'invalid_state_transition');
    }

    public function test_missing_idempotency_key_returns_400(): void
    {
        $id = '4327164d-49ae-48ad-98c5-cff27c3aa8fc';
        $headers = $this->headers;
        unset($headers['Idempotency-Key']);

        $response = $this->withHeaders($headers)
            ->postJson("/api/integration/v1/reservations/{$id}/approve");

        $response->assertStatus(400)
            ->assertJsonPath('error.reason_code', 'missing_idempotency_key');
    }

    public function test_invalid_operator_org_context_returns_400_before_mutation(): void
    {
        $id = '4327164d-49ae-48ad-98c5-cff27c3aa8fc';
        $headers = $this->headers;
        $headers['X-Operator-Org-Context'] = '{"branch_id":"main"}';

        $response = $this->withHeaders($headers)
            ->postJson("/api/integration/v1/reservations/{$id}/approve");

        $response->assertStatus(400)
            ->assertJsonPath('error.reason_code', 'invalid_operator_org_context');
    }

    public function test_replay_same_payload_returns_success(): void
    {
        $id = '4327164d-49ae-48ad-98c5-cff27c3aa8fc';
        $this->bindApproveResult($id, [
            'status' => 200,
            'body' => [
                'data' => [
                    'id' => $id,
                    'status' => 'READY',
                ],
                'request_id' => 'req-mut-001',
                'correlation_id' => 'corr-mut-001',
                'timestamp' => '2026-04-01T20:00:00.000000Z',
            ],
            'replayed' => true,
        ]);

        $response = $this->withHeaders($this->headers)
            ->postJson("/api/integration/v1/reservations/{$id}/approve");

        $response->assertOk()
            ->assertJsonPath('data.status', 'READY');
    }

    public function test_same_key_different_payload_returns_conflict(): void
    {
        $id = '4327164d-49ae-48ad-98c5-cff27c3aa8fc';

        /** @var MockInterface&IntegrationReservationWriteService $mock */
        $mock = Mockery::mock(IntegrationReservationWriteService::class);
        $mock->shouldReceive('reject')
            ->once()
            ->andThrow(new IntegrationReservationMutationException(
                errorCode: 'conflict',
                reasonCode: 'idempotency_key_reused_with_different_payload',
                message: 'The same Idempotency-Key was used with a different semantic payload.',
                httpStatus: 409,
            ));

        $this->app->instance(IntegrationReservationWriteService::class, $mock);

        $response = $this->withHeaders($this->headers)->postJson(
            "/api/integration/v1/reservations/{$id}/reject",
            [
                'cancel_origin' => 'OPERATOR_REJECT',
                'cancel_reason_code' => 'POLICY_VIOLATION',
            ]
        );

        $response->assertStatus(409)
            ->assertJsonPath('error.reason_code', 'idempotency_key_reused_with_different_payload');
    }

    public function test_reject_missing_reason_or_origin_returns_400(): void
    {
        $id = '4327164d-49ae-48ad-98c5-cff27c3aa8fc';

        $response = $this->withHeaders($this->headers)
            ->postJson("/api/integration/v1/reservations/{$id}/reject", [
                'cancel_origin' => 'OPERATOR_REJECT',
            ]);

        $response->assertStatus(400)
            ->assertJsonPath('error.reason_code', 'missing_cancel_reason_or_origin');
    }

    /**
     * @param array{status:int, body:array<string,mixed>, replayed:bool} $result
     */
    private function bindApproveResult(string $id, array $result): void
    {
        /** @var MockInterface&IntegrationReservationWriteService $mock */
        $mock = Mockery::mock(IntegrationReservationWriteService::class);
        $mock->shouldReceive('approve')
            ->once()
            ->with($id, 'idem-001', Mockery::type('array'))
            ->andReturn($result);

        $this->app->instance(IntegrationReservationWriteService::class, $mock);
    }
}
