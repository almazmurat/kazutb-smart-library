<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

/**
 * Phase 3 Cluster B.4 — embedded collection / fund narrative + institutional
 * directory inside the About variant of the shared about.blade.php view.
 *
 * Contract guarantees locked down here:
 *   - /about renders the two new data-section markers:
 *       about-collection-profile, about-institutional-directory
 *   - /contacts (sibling variant of the same view) MUST NOT render them
 *   - collection profile renders exactly four reader-facing coverage areas
 *   - institutional directory links to /rules, /leadership, /contacts
 *   - trilingual parity (ru/kk/en) for the new sections
 *   - existing About chrome (hero, about-mission, librarian-on-duty,
 *     catalog-cta) still renders
 *   - no legacy brand drift
 */
class AboutCollectionDirectoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('demo_auth.enabled', true);
        $this->withoutMiddleware([VerifyCsrfToken::class, ValidateCsrfToken::class]);
    }

    public function test_about_page_returns_ok_and_renders_new_embedded_sections(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('data-section="about-collection-profile"', false);
        $response->assertSee('data-section="about-institutional-directory"', false);
    }

    public function test_contacts_variant_does_not_render_collection_or_directory_sections(): void
    {
        $response = $this->get('/contacts');

        $response->assertOk();
        $response->assertDontSee('data-section="about-collection-profile"', false);
        $response->assertDontSee('data-section="about-institutional-directory"', false);
        $response->assertDontSee('data-collection-area', false);
        $response->assertDontSee('data-directory-slot', false);
    }

    public function test_collection_profile_renders_four_reader_facing_areas(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        // Four frozen v1 areas, each with a data-area-slug marker.
        $response->assertSee('data-area-slug="technology"', false);
        $response->assertSee('data-area-slug="economy"', false);
        $response->assertSee('data-area-slug="humanities"', false);
        $response->assertSee('data-area-slug="college"', false);
    }

    public function test_institutional_directory_links_to_rules_leadership_and_contacts(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('data-test-id="about-directory-link-rules"', false);
        $response->assertSee('data-test-id="about-directory-link-leadership"', false);
        $response->assertSee('data-test-id="about-directory-link-contacts"', false);
        $response->assertSee('href="/rules"', false);
        $response->assertSee('href="/leadership"', false);
        $response->assertSee('href="/contacts"', false);
    }

    public function test_about_ru_variant_renders_new_sections(): void
    {
        $response = $this->get('/about?lang=ru');

        $response->assertOk();
        $response->assertSee('Коллекция и фонд', false);
        $response->assertSee('Что читатель найдёт в фонде библиотеки', false);
        $response->assertSee('Инженерия и технологии', false);
        $response->assertSee('Экономика, менеджмент и право', false);
        $response->assertSee('Социально-гуманитарные дисциплины', false);
        $response->assertSee('Учебные материалы колледжа', false);
        $response->assertSee('Институциональный справочник', false);
        $response->assertSee('Правила библиотеки', false);
        $response->assertSee('Руководство библиотеки', false);
        $response->assertSee('Контакты и расположение', false);
    }

    public function test_about_kk_variant_renders_new_sections_and_preserves_lang_on_links(): void
    {
        $response = $this->get('/about?lang=kk');

        $response->assertOk();
        $response->assertSee('Жинақ және қор', false);
        $response->assertSee('Институционалдық анықтамалық', false);
        $response->assertSee('Кітапхана ережелері', false);
        $response->assertSee('Кітапхана басшылығы', false);
        $response->assertSee('Байланыс және орналасу', false);
        // Directory links preserve ?lang=kk on non-ru variants.
        $response->assertSee('href="/rules?lang=kk"', false);
        $response->assertSee('href="/leadership?lang=kk"', false);
        $response->assertSee('href="/contacts?lang=kk"', false);
    }

    public function test_about_en_variant_renders_new_sections_and_preserves_lang_on_links(): void
    {
        $response = $this->get('/about?lang=en');

        $response->assertOk();
        $response->assertSee('Collection and fund', false);
        $response->assertSee('What readers find in the library collection', false);
        $response->assertSee('Engineering and technology', false);
        $response->assertSee('Economics, management and law', false);
        $response->assertSee('Social sciences and humanities', false);
        $response->assertSee('College teaching materials', false);
        $response->assertSee('Institutional directory', false);
        $response->assertSee('Library rules', false);
        $response->assertSee('Library leadership', false);
        $response->assertSee('Contacts and location', false);
        $response->assertSee('href="/rules?lang=en"', false);
        $response->assertSee('href="/leadership?lang=en"', false);
        $response->assertSee('href="/contacts?lang=en"', false);
    }

    public function test_existing_about_surface_still_renders(): void
    {
        $response = $this->get('/about?lang=en');

        $response->assertOk();
        // Hero aside contacts summary and About mission still present.
        $response->assertSee('data-section="contacts-summary"', false);
        $response->assertSee('data-section="about-mission"', false);
        $response->assertSee('data-section="librarian-on-duty"', false);
        $response->assertSee('data-section="catalog-cta"', false);
        // Mission copy tokens (existing).
        $response->assertSee('The institutional library of KazUTB', false);
    }

    public function test_about_no_legacy_brand_drift_in_new_sections(): void
    {
        $response = $this->get('/about?lang=en');

        $response->assertOk();
        $response->assertDontSee('Athenaeum', false);
        $response->assertDontSee('KazUTB Digital Library', false);
    }

    public function test_new_sections_render_in_contract_order(): void
    {
        $response = $this->get('/about?lang=en');

        $response->assertOk();
        $response->assertSeeInOrder([
            'data-section="about-mission"',
            'data-section="librarian-on-duty"',
            'data-section="about-collection-profile"',
            'data-section="about-institutional-directory"',
            'data-section="catalog-cta"',
        ], false);
    }
}
