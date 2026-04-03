<?php

namespace Tests\Feature;

use Tests\TestCase;

class InternalDashboardPageTest extends TestCase
{
    public function test_internal_dashboard_page_renders_successfully(): void
    {
        $response = $this->get('/internal/dashboard');

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
