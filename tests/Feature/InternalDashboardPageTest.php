<?php

namespace Tests\Feature;

use Tests\TestCase;

class InternalDashboardPageTest extends TestCase
{
    private function staffSession(): array
    {
        return [
            'library.user' => [
                'id' => 'staff-1',
                'name' => 'Library Staff',
                'email' => 'staff@example.com',
                'login' => 'staff01',
                'ad_login' => 'staff01',
                'role' => 'librarian',
            ],
            'library.crm_token' => 'test-staff-token',
            'library.authenticated_at' => now()->toIso8601String(),
        ];
    }

    public function test_internal_dashboard_page_renders_successfully(): void
    {
        $response = $this->withSession($this->staffSession())->get('/internal/dashboard');

        $response
            ->assertOk()
            ->assertSee('Состояние библиотечной базы')
            ->assertSee('/internal/review', false)
            ->assertSee('Открыть список quality issues')
            ->assertSee('Data Stewardship')
            ->assertSee('/internal/stewardship', false)
            ->assertSee('Circulation Desk')
            ->assertSee('/internal/circulation', false)
            ->assertSee('/api/v1/library/health-summary', false)
            ->assertSee('/api/v1/review/issues-summary?top_limit=5', false);
    }
}
