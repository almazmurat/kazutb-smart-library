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
            ->assertSee('id="resources-pathways"', false)
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
            ->assertSee('Institutional Support for Researchers')
            ->assertSee('Tailored Pathways')
            ->assertSee('For Students')
            ->assertSee('For Faculty & Teachers')
            ->assertSee('For Researchers');
    }

    public function test_resources_page_displays_tailored_pathways_section(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('data-test-id="resources-pathways"', false)
            ->assertSee('data-test-id="pathway-students"', false)
            ->assertSee('data-test-id="pathway-faculty"', false)
            ->assertSee('data-test-id="pathway-researchers"', false);
    }

    public function test_resources_page_displays_core_databases_label(): void
    {
        $response = $this->get('/resources');

        $response
            ->assertOk()
            ->assertSee('data-test-id="core-databases-label"', false)
            ->assertSee('Институциональные подписки', false);
    }

    public function test_resources_page_tailored_pathways_trilingual(): void
    {
        // English
        $response = $this->get('/resources?lang=en');
        $response->assertSee('Essential textbooks, study guides, citation tools')
            ->assertSee('Course material curation, syllabus integration')
            ->assertSee('Advanced data sets, peer-reviewed indices');

        // Russian
        $response = $this->get('/resources?lang=ru');
        $response->assertSee('Учебные пособия, справочники, инструменты цитирования')
            ->assertSee('Кураторство материалов курса');

        // Kazakh
        $response = $this->get('/resources?lang=kk');
        $response->assertSee('Ынамдалған бағыттар');
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

    public function test_resources_page_preserves_section_order(): void
    {
        $response = $this->get('/resources');

        $response->assertOk();
        
        // Verify order: pathways > filter bar > grid > support
        $pathwaysPos = strpos($response->getContent(), 'id="resources-pathways"');
        $filterPos = strpos($response->getContent(), 'id="resources-filter-bar"');
        $gridPos = strpos($response->getContent(), 'data-resource-grid');
        $supportPos = strpos($response->getContent(), 'id="resource-support-section"');

        $this->assertLessThan($filterPos, $pathwaysPos, 'Pathways section should come before filter bar');
        $this->assertLessThan($gridPos, $filterPos, 'Filter bar should come before grid');
        $this->assertLessThan($supportPos, $gridPos, 'Grid should come before support section');
    }
}


