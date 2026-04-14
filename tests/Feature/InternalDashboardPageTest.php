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
            ->assertSee('data-librarian-workspace', false)
            ->assertSee('Операционная панель библиотеки')
            ->assertSee('/internal/review', false)
            ->assertSee('Review Queue')
            ->assertSee('/internal/stewardship', false)
            ->assertSee('Circulation Desk')
            ->assertSee('/internal/circulation', false)
            ->assertSee('/internal/ai-chat', false)
            ->assertSee('/api/v1/internal/review/triage-summary?top_limit=6', false)
            ->assertSee('/api/v1/internal/review/readers-summary?top_limit=5', false)
            ->assertSee('/api/v1/internal/reader-contacts/stats', false)
            ->assertSee('/api/v1/internal/review/stewardship-metrics', false)
            ->assertSee('/api/v1/internal/enrichment/stats', false)
            ->assertSee('Операционные заметки');
    }
}
