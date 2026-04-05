<?php

namespace Tests\Feature;

use Tests\TestCase;

class SpaCatalogWiringTest extends TestCase
{
    public function test_spa_catalog_component_uses_canonical_catalog_db_api_path(): void
    {
        $source = file_get_contents(base_path('resources/js/spa/pages/CatalogPage.jsx'));

        $this->assertIsString($source);
        $this->assertStringContainsString("api(`/catalog-db?", $source);
        $this->assertStringNotContainsString('/catalog/search?', $source);
    }

    public function test_spa_catalog_component_links_to_canonical_book_route(): void
    {
        $source = file_get_contents(base_path('resources/js/spa/pages/CatalogPage.jsx'));

        $this->assertIsString($source);
        $this->assertStringContainsString('/book/${encodeURIComponent(identifier)}', $source);
    }
}
