<?php

namespace Tests\Feature;

use Tests\TestCase;

class ShortlistPageTest extends TestCase
{
    public function test_shortlist_page_renders_successfully(): void
    {
        $response = $this->get('/shortlist');

        $response
            ->assertOk()
            ->assertSee('Подборка литературы', false)
            ->assertSee('/api/v1/shortlist', false)
            ->assertSee('shortlist-loading', false)
            ->assertSee('shortlist-empty', false)
            ->assertSee('bibliography-text', false);
    }

    public function test_shortlist_page_has_catalog_link(): void
    {
        $response = $this->get('/shortlist');

        $response
            ->assertOk()
            ->assertSee('href="/catalog"', false)
            ->assertSee('href="/for-teachers"', false);
    }

    public function test_shortlist_page_has_copy_and_clear_actions(): void
    {
        $response = $this->get('/shortlist');

        $response
            ->assertOk()
            ->assertSee('copyBibliography()', false)
            ->assertSee('clearShortlist()', false);
    }

    public function test_shortlist_page_has_format_selector(): void
    {
        $response = $this->get('/shortlist');

        $response
            ->assertOk()
            ->assertSee('bib-format', false)
            ->assertSee('numbered', false)
            ->assertSee('grouped', false)
            ->assertSee('syllabus', false);
    }

    public function test_shortlist_page_has_print_button(): void
    {
        $response = $this->get('/shortlist');

        $response
            ->assertOk()
            ->assertSee('window.print()', false);
    }

    public function test_shortlist_page_has_export_api_call(): void
    {
        $response = $this->get('/shortlist');

        $response
            ->assertOk()
            ->assertSee('loadExport()', false)
            ->assertSee('/export?format=', false);
    }

    public function test_shortlist_page_has_grouped_sections(): void
    {
        $response = $this->get('/shortlist');

        $response
            ->assertOk()
            ->assertSee('shortlist-books-section', false)
            ->assertSee('shortlist-external-section', false);
    }

    public function test_shortlist_page_has_resources_link(): void
    {
        $response = $this->get('/shortlist');

        $response
            ->assertOk()
            ->assertSee('href="/resources"', false);
    }
}
