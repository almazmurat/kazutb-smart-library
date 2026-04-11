<?php

namespace Tests\Feature\Api;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountReservationsTest extends TestCase
{
    private bool $useLivePgsql = false;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->canUseLivePgsql()) {
            $this->useLivePgsql = true;
            Config::set('database.default', 'pgsql');
            DB::purge('pgsql');
            DB::connection('pgsql')->beginTransaction();
        }
    }

    protected function tearDown(): void
    {
        if ($this->useLivePgsql) {
            DB::connection('pgsql')->rollBack();
        }

        parent::tearDown();
    }

    private function canUseLivePgsql(): bool
    {
        try {
            DB::connection('pgsql')->getPdo();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function requireLivePgsql(): void
    {
        if (! $this->useLivePgsql) {
            $this->markTestSkipped('Live PostgreSQL not available.');
        }
    }

    public function test_reservations_route_exists(): void
    {
        $response = $this->getJson('/api/v1/account/reservations');

        $this->assertNotEquals(404, $response->status(), 'Route should exist');
        $this->assertNotEquals(405, $response->status(), 'Route should accept GET');
    }

    public function test_reservations_requires_auth(): void
    {
        $response = $this->getJson('/api/v1/account/reservations');

        $response->assertStatus(401);
        $response->assertJson(['authenticated' => false]);
    }

    public function test_reservations_returns_empty_for_unknown_user(): void
    {
        $this->requireLivePgsql();

        $session = [
            'library.user' => [
                'id' => 'eeeeeeee-0000-0000-0000-555555555555',
                'role' => 'reader',
                'email' => 'unknown-reservation-test@nonexistent.example',
                'name' => 'No CRM User',
            ],
        ];

        $response = $this->withSession($session)
            ->getJson('/api/v1/account/reservations');

        $response->assertOk();
        $response->assertJson(['authenticated' => true, 'data' => []]);
    }

    public function test_reservations_returns_data_for_known_user(): void
    {
        $this->requireLivePgsql();

        $crmUser = DB::connection('pgsql')
            ->table('public.User')
            ->whereExists(function ($query): void {
                $query->select(DB::raw(1))
                    ->from('public.Reservation')
                    ->whereColumn('public.Reservation.userId', 'public.User.id');
            })
            ->first();

        if ($crmUser === null) {
            $this->markTestSkipped('No CRM user with reservations found.');
        }

        $session = [
            'library.user' => [
                'id' => $crmUser->id,
                'role' => 'reader',
                'email' => $crmUser->email,
                'name' => $crmUser->fullName ?? 'Test',
            ],
        ];

        $response = $this->withSession($session)
            ->getJson('/api/v1/account/reservations');

        $response->assertOk();
        $response->assertJsonStructure([
            'authenticated',
            'data' => [
                '*' => ['id', 'status', 'reservedAt', 'expiresAt', 'book'],
            ],
            'meta' => ['crmUserId', 'total'],
        ]);

        $data = $response->json('data');
        $this->assertNotEmpty($data, 'User should have reservations');

        $first = $data[0];
        $this->assertArrayHasKey('status', $first);
        $this->assertContains($first['status'], ['PENDING', 'READY', 'FULFILLED', 'CANCELLED', 'EXPIRED']);
        $this->assertArrayHasKey('book', $first);
    }

    public function test_reservations_filters_by_status(): void
    {
        $this->requireLivePgsql();

        $crmUser = DB::connection('pgsql')
            ->table('public.User')
            ->whereExists(function ($query): void {
                $query->select(DB::raw(1))
                    ->from('public.Reservation')
                    ->whereColumn('public.Reservation.userId', 'public.User.id');
            })
            ->first();

        if ($crmUser === null) {
            $this->markTestSkipped('No CRM user with reservations found.');
        }

        $session = [
            'library.user' => [
                'id' => $crmUser->id,
                'role' => 'reader',
                'email' => $crmUser->email,
                'name' => $crmUser->fullName ?? 'Test',
            ],
        ];

        $response = $this->withSession($session)
            ->getJson('/api/v1/account/reservations?status=CANCELLED');

        $response->assertOk();

        $data = $response->json('data');
        foreach ($data as $reservation) {
            $this->assertEquals('CANCELLED', $reservation['status']);
        }
    }

    public function test_reservations_includes_book_snapshot(): void
    {
        $this->requireLivePgsql();

        $crmUser = DB::connection('pgsql')
            ->table('public.User')
            ->whereExists(function ($query): void {
                $query->select(DB::raw(1))
                    ->from('public.Reservation')
                    ->whereColumn('public.Reservation.userId', 'public.User.id');
            })
            ->first();

        if ($crmUser === null) {
            $this->markTestSkipped('No CRM user with reservations found.');
        }

        $session = [
            'library.user' => [
                'id' => $crmUser->id,
                'role' => 'reader',
                'email' => $crmUser->email,
                'name' => $crmUser->fullName ?? 'Test',
            ],
        ];

        $response = $this->withSession($session)
            ->getJson('/api/v1/account/reservations');

        $response->assertOk();

        $data = $response->json('data');
        if (! empty($data)) {
            $first = $data[0];
            $this->assertArrayHasKey('book', $first);
            $this->assertArrayHasKey('title', $first['book']);
        }
    }

    public function test_account_page_renders_reservations_section(): void
    {
        $response = $this->withSession([
            'library.user' => [
                'id' => 'u-test-1', 'name' => 'Test', 'email' => 'test@example.com',
                'login' => 'test01', 'ad_login' => 'test01', 'role' => 'reader',
            ],
            'library.crm_token' => 'test-token',
            'library.authenticated_at' => now()->toIso8601String(),
        ])->get('/account?lang=ru');

        $response->assertOk();
        $response->assertSee('Мои бронирования');
        $response->assertSee('reservations-grid');
        $response->assertSee('account/reservations');
    }
}
