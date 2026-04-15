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
            ->assertSee('Академическая навигация');
    }

    public function test_discover_page_has_structured_navigation_sections_and_safe_links(): void
    {
        $response = $this->get('/discover');

        $response
            ->assertOk()
            ->assertSee('id="discover-page"', false)
            ->assertSee('id="discover-pathways"', false)
            ->assertSee('id="discover-workflow"', false)
            ->assertSee('id="discover-cta"', false)
            ->assertDontSee('href="#"', false);
    }

    public function test_discover_page_supports_english_locale_copy(): void
    {
        $response = $this->get('/discover?lang=en');

        $response
            ->assertOk()
            ->assertSee('Academic navigation')
            ->assertSee('Open catalog')
            ->assertSee('Open draft list');
    }
}
