<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExternalResourcePageTest extends TestCase
{
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
            ->assertSee('Электронная библиотека КазУТБ', false)
            ->assertSee('фонд библиотеки университета', false)
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

    public function test_for_teachers_page_renders_successfully(): void
    {
        $response = $this->get('/for-teachers');

        $response
            ->assertOk()
            ->assertSee('Преподавателям', false)
            ->assertSee('teacher-ext-resources', false)
            ->assertSee('/api/v1/external-resources', false);
    }

    public function test_for_teachers_page_has_existing_features(): void
    {
        $response = $this->get('/for-teachers');

        $response
            ->assertOk()
            ->assertSee('Подборка литературы для силлабуса', false)
            ->assertSee('href="/shortlist"', false)
            ->assertSee('href="/catalog"', false)
            ->assertSee('href="/discover"', false);
    }

    public function test_shortlist_page_still_renders(): void
    {
        $response = $this->get('/shortlist');

        $response
            ->assertOk()
            ->assertSee('Подборка литературы', false)
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

    public function test_about_page_still_renders(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
    }
}
