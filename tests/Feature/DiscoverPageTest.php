<?php

namespace Tests\Feature;

use Tests\TestCase;

class DiscoverPageTest extends TestCase
{
    public function test_discover_page_renders_successfully(): void
    {
        $response = $this->get('/discover');

        $response
            ->assertOk()
            ->assertSee('Карта знаний')
            ->assertSee('Навигационная структура');
    }

    public function test_discover_page_has_export_sections_and_safe_links(): void
    {
        $response = $this->get('/discover');

        $response
            ->assertOk()
            ->assertSee('id="discover-page"', false)
            ->assertSee('id="discover-hero"', false)
            ->assertSee('id="discover-disciplines"', false)
            ->assertSee('id="discover-workflow"', false)
            ->assertSee('id="discover-metadata"', false)
            ->assertSee('id="discover-bridge"', false)
            ->assertDontSee('href="#"', false);
    }

    public function test_discover_page_supports_english_locale_copy(): void
    {
        $response = $this->get('/discover?lang=en');

        $response
            ->assertOk()
            ->assertSee('The Map of Knowledge')
            ->assertSee('Scholarly Disciplines')
            ->assertSee('Institutional Metadata')
            ->assertSee('Launch Catalog');
    }
}
