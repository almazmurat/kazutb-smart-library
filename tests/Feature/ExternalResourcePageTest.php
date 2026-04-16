<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExternalResourcePageTest extends TestCase
{
    public function test_resources_page_uses_curated_access_panels(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('resource-hero-panels', false)
            ->assertSee('resource-policy-note', false)
            ->assertSee('resource-access-matrix', false);
    }

    public function test_resources_page_renders_successfully(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('Электронные ресурсы', false)
            ->assertSee('Внешние лицензированные ресурсы', false)
            ->assertSee('/api/v1/external-resources', false);
    }

    public function test_resources_page_has_local_catalog_section(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('Digital Library', false)
            ->assertSee('основной библиотечный фонд', false)
            ->assertSee('href="/catalog"', false);
    }

    public function test_resources_page_has_filter_bar(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('ext-filter-bar', false)
            ->assertSee('ext-resources-grid', false);
    }

    public function test_resources_page_has_access_modes_section(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('Режимы доступа', false)
            ->assertSee('Из кампуса', false)
            ->assertSee('Удалённо', false)
            ->assertSee('Открытый доступ', false);
    }

    public function test_resources_page_has_shortlist_integration(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('addExtToShortlist', false)
            ->assertSee('/api/v1/shortlist', false);
    }

    public function test_for_teachers_redirects_to_resources(): void
    {
        $response = $this->get('/for-teachers');

        $response
            ->assertRedirect('/resources')
            ->assertStatus(301);
    }

    public function test_resources_page_has_faculty_support_actions(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('Подборка литературы', false)
            ->assertSee('href="/shortlist"', false)
            ->assertDontSee('href="/for-teachers"', false);
    }

    public function test_shortlist_page_still_renders(): void
    {
        $response = $this->get('/shortlist');

        $response
            ->assertOk()
            ->assertSee('data-shortlist-page', false)
            ->assertSee('shortlist-loading', false);
    }

    public function test_catalog_page_still_renders(): void
    {
        $response = $this->get('/catalog');

        $response->assertOk();
    }

    public function test_welcome_page_still_renders(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_about_page_renders_from_public_shell(): void
    {
        $response = $this->get('/about');

        $response
            ->assertOk()
            ->assertSee('class="site-shell"', false)
            ->assertSee('КазТБУ Digital Library');
    }
}
