<?php

namespace Tests\Feature;

use Tests\TestCase;

class ResourcesPageTest extends TestCase
{
    public function test_resources_page_renders_successfully(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('Институциональные', false);
    }

    public function test_resources_page_has_exported_structure_and_safe_ctas(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('id="resources-page"', false)
            ->assertSee('id="resources-filter-bar"', false)
            ->assertSee('data-resource-grid', false)
            ->assertSee('id="resource-support-section"', false)
            ->assertDontSee('href="#"', false);
    }

    public function test_resources_page_supports_english_locale_copy(): void
    {
        $response = $this->get('/resources?lang=en');

        $response
            ->assertOk()
            ->assertSee('Global Research Tools')
            ->assertSee('Filter by Discipline')
            ->assertSee('Institutional Support for Researchers');
    }
}
