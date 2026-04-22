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

    public function test_resources_page_displays_featured_resource(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('IPR SMART', false)
            ->assertSee('resource-card--featured', false);
    }

    public function test_resources_page_displays_additional_resources_in_grid(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            // Check for secondary resources
            ->assertSee('Республиканская межвузовская электронная библиотека', false)
            ->assertSee('eLIBRARY.RU', false)
            ->assertSee('resource-card--small', false);
    }

    public function test_resources_page_has_category_filters(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('Электронная библиотека', false)
            ->assertSee('Научная база данных', false)
            ->assertSee('Открытый доступ', false)
            ->assertSee('Аналитика и СМИ', false);
    }

    public function test_resources_page_ctAs_have_proper_links(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('target="_blank"', false)
            ->assertSee('rel="noopener noreferrer"', false);
    }
}

