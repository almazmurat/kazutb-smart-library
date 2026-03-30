<?php

namespace Tests\Feature;

use Tests\TestCase;

class InternalReviewPageTest extends TestCase
{
    public function test_internal_review_page_renders_successfully(): void
    {
        $response = $this->get('/internal/review');

        $response
            ->assertOk()
            ->assertSee('Quality Issues Overview')
            ->assertSee('/internal/dashboard', false)
            ->assertSee('Вернуться к dashboard')
            ->assertSee('/api/v1/review/issues', false)
            ->assertSee('Severity')
            ->assertSee('Status');
    }
}
