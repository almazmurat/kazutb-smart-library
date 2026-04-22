<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

/**
 * Phase 3 Cluster B.3 — embedded location / fund rooms / visit notes inside
 * the Contacts variant of the shared about.blade.php view.
 *
 * Contract guarantees locked down here:
 *   - /contacts renders three new data-section markers:
 *       contacts-location, contacts-fund-rooms, contacts-visit-notes
 *   - /about (sibling variant of the same view) MUST NOT render them
 *   - all three room codes (1/200, 1/202, 1/203) render with their v1 labels
 *   - trilingual parity (ru/kk/en) for the new sections
 *   - existing contacts chrome (contacts-summary aside, librarian-on-duty,
 *     address, phone, email, opening hours) keeps rendering
 *   - no legacy brand drift
 */
class ContactsLocationFundRoomsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('demo_auth.enabled', true);
        $this->withoutMiddleware([VerifyCsrfToken::class, ValidateCsrfToken::class]);
    }

    public function test_contacts_page_returns_ok_and_renders_new_embedded_sections(): void
    {
        $response = $this->get('/contacts');

        $response->assertOk();
        $response->assertSee('data-section="contacts-location"', false);
        $response->assertSee('data-section="contacts-fund-rooms"', false);
        $response->assertSee('data-section="contacts-visit-notes"', false);
    }

    public function test_about_variant_does_not_render_location_fund_or_visit_sections(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertDontSee('data-section="contacts-location"', false);
        $response->assertDontSee('data-section="contacts-fund-rooms"', false);
        $response->assertDontSee('data-section="contacts-visit-notes"', false);
        // Room codes from the v1 set must not leak onto /about.
        $response->assertDontSee('data-room-code="1/200"', false);
        $response->assertDontSee('data-room-code="1/202"', false);
        $response->assertDontSee('data-room-code="1/203"', false);
    }

    public function test_all_three_room_codes_render_on_contacts(): void
    {
        $response = $this->get('/contacts');

        $response->assertOk();
        $response->assertSee('data-room-code="1/200"', false);
        $response->assertSee('data-room-code="1/202"', false);
        $response->assertSee('data-room-code="1/203"', false);
        // Each card exposes a fund-room slot marker.
        $response->assertSeeInOrder([
            'data-section="contacts-fund-rooms"',
            'data-room-code="1/200"',
            'data-room-code="1/202"',
            'data-room-code="1/203"',
        ], false);
    }

    public function test_contacts_location_exposes_branch_card_and_static_map_placeholder(): void
    {
        $response = $this->get('/contacts');

        $response->assertOk();
        $response->assertSee('data-branch-slot', false);
        $response->assertSee('data-test-id="contacts-location-map"', false);
        $response->assertSee('data-test-id="contacts-location-directions"', false);
    }

    public function test_contacts_visit_notes_link_routes_to_rules(): void
    {
        $response = $this->get('/contacts');

        $response->assertOk();
        $response->assertSee('data-test-id="contacts-visit-rules-link"', false);
        $response->assertSee('href="/rules"', false);
    }

    public function test_contacts_ru_variant_renders_new_sections_and_room_labels(): void
    {
        $response = $this->get('/contacts?lang=ru');

        $response->assertOk();
        $response->assertSee('Как нас найти', false);
        $response->assertSee('Фондовые комнаты', false);
        $response->assertSee('Перед визитом', false);
        $response->assertSee('Технологический фонд', false);
        $response->assertSee('Фонд колледжа', false);
        $response->assertSee('Экономический фонд библиотеки', false);
        $response->assertSee('Полные правила библиотеки', false);
    }

    public function test_contacts_kk_variant_renders_new_sections_and_room_labels(): void
    {
        $response = $this->get('/contacts?lang=kk');

        $response->assertOk();
        $response->assertSee('Бізді қалай табуға болады', false);
        $response->assertSee('Қор бөлмелері', false);
        $response->assertSee('Келер алдында', false);
        $response->assertSee('Технологиялық қор', false);
        $response->assertSee('Колледж қоры', false);
        $response->assertSee('Кітапхананың экономикалық қоры', false);
        // /rules link preserves lang=kk on kk variant.
        $response->assertSee('href="/rules?lang=kk"', false);
    }

    public function test_contacts_en_variant_renders_new_sections_and_room_labels(): void
    {
        $response = $this->get('/contacts?lang=en');

        $response->assertOk();
        $response->assertSee('How to find us', false);
        $response->assertSee('Fund rooms', false);
        $response->assertSee('Before you visit', false);
        $response->assertSee('Technology fund', false);
        $response->assertSee('College fund', false);
        $response->assertSee('Economic fund of the library', false);
        $response->assertSee('href="/rules?lang=en"', false);
    }

    public function test_existing_contacts_surface_still_renders(): void
    {
        $response = $this->get('/contacts?lang=en');

        $response->assertOk();
        // Hero aside contacts summary and Librarian-on-Duty still present.
        $response->assertSee('data-section="contacts-summary"', false);
        $response->assertSee('data-section="librarian-on-duty"', false);
        // Real contact channels.
        $response->assertSee('37A Kayym Mukhamedkhanov Street, Astana', false);
        $response->assertSee('+7 (7172) 64-58-58', false);
        $response->assertSee('library@kazutb.edu.kz', false);
        $response->assertSee('Opening hours', false);
    }

    public function test_contacts_no_legacy_brand_drift_in_new_sections(): void
    {
        $response = $this->get('/contacts?lang=en');

        $response->assertOk();
        $response->assertDontSee('Athenaeum', false);
        $response->assertDontSee('KazUTB Digital Library', false);
    }

    public function test_new_sections_render_in_contract_order_after_mission(): void
    {
        $response = $this->get('/contacts?lang=en');

        $response->assertOk();
        $response->assertSeeInOrder([
            'data-section="librarian-on-duty"',
            'data-section="about-mission"',
            'data-section="contacts-location"',
            'data-section="contacts-fund-rooms"',
            'data-section="contacts-visit-notes"',
            'data-section="catalog-cta"',
        ], false);
    }
}
