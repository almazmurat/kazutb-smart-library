<?php

namespace Tests\Feature;

use Tests\TestCase;

class CatalogPageTest extends TestCase
{
    public function test_catalog_page_renders_successfully(): void
    {
        $response = $this->get('/catalog?lang=ru');

        $response
            ->assertOk()
            ->assertSee('Каталог книг', false)
            ->assertSee('/api/v1/catalog-db', false)
            ->assertSee('id="language-chips"', false)
            ->assertSee('id="year-chips"', false)
            ->assertSee('id="filter-available-only"', false)
            ->assertSee('id="sort-select"', false)
            ->assertSee('ISBN', false)
            ->assertSee('УДК', false);
    }

    public function test_catalog_page_has_functional_filter_chips(): void
    {
        $response = $this->get('/catalog');

        $response
            ->assertOk()
            ->assertSee('data-lang="ru"', false)
            ->assertSee('data-lang="kk"', false)
            ->assertSee('data-lang="en"', false)
            ->assertSee('data-year="2025"', false)
            ->assertSee('data-year="2024"', false)
            ->assertSee('data-year="older"', false);
    }

    public function test_catalog_page_sends_filters_to_api(): void
    {
        $response = $this->get('/catalog');

        $response
            ->assertOk()
            ->assertSee('params.set(\'language\'', false)
            ->assertSee('params.set(\'sort\'', false)
            ->assertSee('year_from', false)
            ->assertSee('year_to', false)
            ->assertSee('available_only', false);
    }

    public function test_catalog_page_uses_canonical_catalog_db_endpoint_only(): void
    {
        $response = $this->get('/catalog');

        $response
            ->assertOk()
            ->assertSee('/api/v1/catalog-db', false)
            ->assertDontSee('/api/v1/catalog?', false)
            ->assertDontSee('/api/v1/catalog-external', false);
    }
}
