<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicShellTest extends TestCase
{
    public function test_homepage_exposes_stitch_aligned_search_and_subject_sections(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('data-homepage-stitch-reset', false)
            ->assertSee('data-hero-search', false)
            ->assertSee('data-homepage-subjects', false)
            ->assertSee('The Academic Curator');
    }

    public function test_resources_page_uses_accessible_shared_public_shell(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('class="site-shell"', false)
            ->assertSee('href="#main-content"', false)
            ->assertSee('id="main-content"', false)
            ->assertSee('aria-label="Основная навигация сайта"', false);
    }

    public function test_resources_page_exposes_language_switcher(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('data-locale-switcher', false)
            ->assertSee('?lang=kk', false)
            ->assertSee('?lang=en', false);
    }

    public function test_contacts_page_can_render_in_english(): void
    {
        $response = $this->get('/contacts?lang=en');

        $response
            ->assertOk()
            ->assertSee('<html lang="en">', false)
            ->assertSee('About the library and contacts');
    }
}
