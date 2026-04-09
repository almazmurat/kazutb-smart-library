<?php

namespace Tests\Feature\Api;

use App\Services\Ai\TwentyFirstBridgeService;
use Illuminate\Support\Facades\Config;
use RuntimeException;
use Tests\TestCase;

class InternalAiAssistantTest extends TestCase
{
    // ── Helpers ───────────────────────────────────────────────────

    private function staffSession(string $role = 'librarian'): array
    {
        return [
            'library.user' => [
                'id' => 'staff-ai-1',
                'name' => 'AI Test Staff',
                'email' => 'staff-ai@digital-library.test',
                'login' => 'staff_ai',
                'ad_login' => 'staff_ai',
                'role' => $role,
            ],
        ];
    }

    // ── Access control ────────────────────────────────────────────

    public function test_token_endpoint_rejects_unauthenticated_guests(): void
    {
        $response = $this->postJson('/api/v1/internal/ai-assistant/token');

        $response->assertForbidden();
    }

    public function test_session_endpoint_rejects_unauthenticated_guests(): void
    {
        $response = $this->postJson('/api/v1/internal/ai-assistant/session');

        $response->assertForbidden();
    }

    public function test_thread_endpoint_rejects_unauthenticated_guests(): void
    {
        $response = $this->postJson('/api/v1/internal/ai-assistant/thread', [
            'sandboxId' => 'sb-abc123',
        ]);

        $response->assertForbidden();
    }

    public function test_endpoints_reject_reader_role(): void
    {
        $response = $this->withSession($this->staffSession('reader'))
            ->postJson('/api/v1/internal/ai-assistant/token');

        $response->assertForbidden();
    }

    public function test_admin_role_is_also_allowed(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createToken')
            ->once()
            ->andReturn(['token' => 'tok-admin', 'expiresAt' => '2030-01-01T00:00:00Z']);

        $response = $this->withSession($this->staffSession('admin'))
            ->postJson('/api/v1/internal/ai-assistant/token');

        $response->assertOk()
            ->assertJsonPath('token', 'tok-admin');
    }

    // ── token endpoint ────────────────────────────────────────────

    public function test_token_returns_token_and_expires_at_on_success(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createToken')
            ->once()
            ->andReturn(['token' => 'tok-xyz-1234', 'expiresAt' => '2030-06-01T12:00:00Z']);

        $response = $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/token');

        $response->assertOk()
            ->assertJsonStructure(['token', 'expiresAt'])
            ->assertJsonPath('token', 'tok-xyz-1234')
            ->assertJsonPath('expiresAt', '2030-06-01T12:00:00Z');
    }

    public function test_token_passes_agent_slug_and_user_id_to_bridge(): void
    {
        Config::set('services.twentyfirst.agent', 'library-helper-agent');

        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createToken')
            ->once()
            ->withArgs(function (array $payload): bool {
                return ($payload['agent'] ?? '') === 'library-helper-agent'
                    && ($payload['userId'] ?? '') === 'staff-ai-1';
            })
            ->andReturn(['token' => 'tok-ok', 'expiresAt' => null]);

        $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/token')
            ->assertOk();
    }

    public function test_token_returns_500_when_bridge_throws_runtime_exception(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createToken')
            ->once()
            ->andThrow(new RuntimeException('API_KEY_21ST is not configured.'));

        $response = $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/token');

        $response->assertStatus(500)
            ->assertJsonPath('error', 'twentyfirst_bridge_failed')
            ->assertJsonPath('success', false)
            ->assertJsonStructure(['error', 'message', 'success']);
    }

    public function test_token_returns_null_fields_when_bridge_response_lacks_them(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createToken')
            ->once()
            ->andReturn([]);  // no 'token' or 'expiresAt' keys

        $response = $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/token');

        $response->assertOk()
            ->assertJsonPath('token', null)
            ->assertJsonPath('expiresAt', null);
    }

    // ── session endpoint ──────────────────────────────────────────

    public function test_session_returns_agent_sandbox_and_thread_on_success(): void
    {
        Config::set('services.twentyfirst.agent', 'frontend-dev-agent');

        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createSession')
            ->once()
            ->andReturn([
                'sandboxId' => 'sb-abc-123',
                'threadId' => 'thr-456',
            ]);

        $response = $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/session', [
                'name' => 'My Library Session',
            ]);

        $response->assertOk()
            ->assertJsonStructure(['agent', 'sandboxId', 'threadId'])
            ->assertJsonPath('agent', 'frontend-dev-agent')
            ->assertJsonPath('sandboxId', 'sb-abc-123')
            ->assertJsonPath('threadId', 'thr-456');
    }

    public function test_session_uses_default_name_when_not_provided(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createSession')
            ->once()
            ->withArgs(function (array $payload): bool {
                return ($payload['name'] ?? '') === 'Frontend Chat';
            })
            ->andReturn(['sandboxId' => 'sb-1', 'threadId' => 'thr-1']);

        $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/session')
            ->assertOk();
    }

    public function test_session_returns_500_when_bridge_throws(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createSession')
            ->once()
            ->andThrow(new RuntimeException('Bridge execution failed.'));

        $response = $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/session');

        $response->assertStatus(500)
            ->assertJsonPath('error', 'twentyfirst_bridge_failed')
            ->assertJsonPath('success', false);
    }

    public function test_session_passes_user_id_and_agent_to_bridge(): void
    {
        Config::set('services.twentyfirst.agent', 'test-agent');

        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createSession')
            ->once()
            ->withArgs(function (array $payload): bool {
                return ($payload['agent'] ?? '') === 'test-agent'
                    && ($payload['userId'] ?? '') === 'staff-ai-1';
            })
            ->andReturn(['sandboxId' => 'sb-x', 'threadId' => 'thr-x']);

        $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/session')
            ->assertOk();
    }

    // ── thread endpoint ───────────────────────────────────────────

    public function test_thread_requires_sandbox_id(): void
    {
        $response = $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/thread', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['sandboxId']);
    }

    public function test_thread_rejects_sandbox_id_over_255_chars(): void
    {
        $response = $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/thread', [
                'sandboxId' => str_repeat('x', 256),
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['sandboxId']);
    }

    public function test_thread_returns_thread_id_on_success(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createThread')
            ->once()
            ->andReturn(['threadId' => 'thr-new-789']);

        $response = $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/thread', [
                'sandboxId' => 'sb-abc-123',
            ]);

        $response->assertOk()
            ->assertJsonStructure(['threadId'])
            ->assertJsonPath('threadId', 'thr-new-789');
    }

    public function test_thread_uses_provided_name(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createThread')
            ->once()
            ->withArgs(function (array $payload): bool {
                return ($payload['sandboxId'] ?? '') === 'sb-abc-123'
                    && ($payload['name'] ?? '') === 'My Thread Name';
            })
            ->andReturn(['threadId' => 'thr-1']);

        $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/thread', [
                'sandboxId' => 'sb-abc-123',
                'name' => 'My Thread Name',
            ])
            ->assertOk();
    }

    public function test_thread_uses_default_name_when_not_provided(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createThread')
            ->once()
            ->withArgs(function (array $payload): bool {
                return ($payload['name'] ?? '') === 'New frontend thread';
            })
            ->andReturn(['threadId' => 'thr-2']);

        $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/thread', [
                'sandboxId' => 'sb-xyz',
            ])
            ->assertOk();
    }

    public function test_thread_returns_500_when_bridge_throws(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createThread')
            ->once()
            ->andThrow(new RuntimeException('Node process timed out.'));

        $response = $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/thread', [
                'sandboxId' => 'sb-abc-123',
            ]);

        $response->assertStatus(500)
            ->assertJsonPath('error', 'twentyfirst_bridge_failed')
            ->assertJsonPath('message', 'Node process timed out.')
            ->assertJsonPath('success', false);
    }

    public function test_thread_returns_null_thread_id_when_bridge_response_missing(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createThread')
            ->once()
            ->andReturn([]);  // missing threadId key

        $response = $this->withSession($this->staffSession())
            ->postJson('/api/v1/internal/ai-assistant/thread', [
                'sandboxId' => 'sb-abc-123',
            ]);

        $response->assertOk()
            ->assertJsonPath('threadId', null);
    }

    // ── user identifier resolution ────────────────────────────────

    public function test_user_identifier_falls_back_to_login_when_id_absent(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createToken')
            ->once()
            ->withArgs(function (array $payload): bool {
                return ($payload['userId'] ?? '') === 'staff_login';
            })
            ->andReturn(['token' => 't', 'expiresAt' => null]);

        // Session user without 'id', but with 'login'
        $this->withSession([
            'library.user' => [
                'login' => 'staff_login',
                'email' => 'staff@test.com',
                'role' => 'librarian',
            ],
        ])->postJson('/api/v1/internal/ai-assistant/token')->assertOk();
    }

    public function test_user_identifier_falls_back_to_email_when_id_and_login_absent(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createToken')
            ->once()
            ->withArgs(function (array $payload): bool {
                return ($payload['userId'] ?? '') === 'staff@test.com';
            })
            ->andReturn(['token' => 't', 'expiresAt' => null]);

        $this->withSession([
            'library.user' => [
                'email' => 'staff@test.com',
                'role' => 'librarian',
            ],
        ])->postJson('/api/v1/internal/ai-assistant/token')->assertOk();
    }

    public function test_user_identifier_uses_fallback_when_all_fields_empty(): void
    {
        $bridge = $this->mock(TwentyFirstBridgeService::class);
        $bridge->shouldReceive('createToken')
            ->once()
            ->withArgs(function (array $payload): bool {
                return ($payload['userId'] ?? '') === 'library-staff';
            })
            ->andReturn(['token' => 't', 'expiresAt' => null]);

        $this->withSession([
            'library.user' => [
                'role' => 'librarian',
            ],
        ])->postJson('/api/v1/internal/ai-assistant/token')->assertOk();
    }
}
