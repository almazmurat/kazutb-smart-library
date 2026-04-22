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
            ->assertSee('id="discover-pathways"', false)
            ->assertSee('id="discover-workflow"', false)
            ->assertSee('id="discover-metadata"', false)
            ->assertSee('id="discover-bridge"', false)
            ->assertDontSee('href="#"', false);
    }

    public function test_discover_page_institutional_pathways_section(): void
    {
        $response = $this->get('/discover');

        $response
            ->assertOk()
            ->assertSee('data-test-id="institutional-pathways"', false)
            ->assertSee('Институциональные маршруты')
            ->assertSee('Технологический факультет')
            ->assertSee('Факультет экономики и бизнеса')
            ->assertSee('Факультет инжиниринга и ИТ')
            ->assertSee('Военная кафедра')
            ->assertSee('UDC 66–67')
            ->assertSee('UDC 33')
            ->assertSee('UDC 004 / 62')
            ->assertSee('UDC 355');
    }

    public function test_discover_page_pathways_department_links_point_to_catalog(): void
    {
        $response = $this->get('/discover');

        $response
            ->assertOk()
            ->assertSee('/catalog?udc=66', false)
            ->assertSee('/catalog?udc=33', false)
            ->assertSee('/catalog?udc=004', false)
            ->assertSee('/catalog?udc=355', false);
    }

    public function test_discover_page_pathways_tri_lingual_kk(): void
    {
        $response = $this->get('/discover?lang=kk');

        $response
            ->assertOk()
            ->assertSee('Институционалдық маршруттар')
            ->assertSee('Технологиялық факультет')
            ->assertSee('Экономика және бизнес факультеті')
            ->assertSee('Инжиниринг және АТ факультеті')
            ->assertSee('Әскери кафедра');
    }

    public function test_discover_page_pathways_tri_lingual_en(): void
    {
        $response = $this->get('/discover?lang=en');

        $response
            ->assertOk()
            ->assertSee('Institutional Pathways')
            ->assertSee('Faculty of Technology')
            ->assertSee('Faculty of Economics & Business')
            ->assertSee('Faculty of Engineering & IT')
            ->assertSee('Military Department');
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
