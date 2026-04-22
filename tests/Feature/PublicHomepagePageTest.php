<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

/**
 * Phase 3.1 — Public homepage (welcome.blade.php) feature coverage.
 *
 * Homepage is a public surface (no auth gate); the view is the canonical port
 * of `docs/design-exports/Enhanced Homepage/`. These tests lock down:
 *   - guest access and canonical copy markers for the main homepage sections
 *   - authenticated navbar state (session('library.user') → "Sign out" /
 *     "Выйти" / "Шығу" label replaces the guest "Sign in" CTA)
 *   - staff (librarian/admin) can still view the public homepage without
 *     being bounced into their shells
 *   - the authenticated Member Workspace card routes to /dashboard (canonical
 *     member shell) rather than the transitional /account surface.
 */
class PublicHomepagePageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('demo_auth.enabled', true);
        $this->withoutMiddleware([VerifyCsrfToken::class, ValidateCsrfToken::class]);
    }

    private function loginAs(string $identitySlug): void
    {
        $identity = config("demo_auth.identities.{$identitySlug}");

        $this->get('/login');
        $this->post('/login', [
            '_token' => csrf_token(),
            'login' => $identity['login'],
            'password' => $identity['password'],
            'device_name' => 'phpunit',
        ]);
    }

    public function test_guest_can_view_homepage(): void
    {
        // Pin locale to EN for deterministic copy assertions across all tests.
        $response = $this->get('/?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
        // Hero — "The Scholarly Commons".
        $response->assertSee('Scholarly', false);
        // Canonical section markers from the Enhanced Homepage export.
        $response->assertSee('Trending Topics', false);
        $response->assertSee('Operational Hours', false);
        $response->assertSee('News &amp; Announcements', false);
        $response->assertSee('Pulse of', false);
        $response->assertSee('Institutional Repository', false);
        $response->assertSee('Getting Started', false);
        $response->assertSee('Research Tools', false);
    }

    public function test_homepage_hero_search_posts_to_catalog(): void
    {
        $response = $this->get('/?lang=en');

        $response->assertOk();
        // Hero search form submits to the canonical /catalog surface.
        // The withLang helper preserves the ?lang=en query on the action URL.
        $response->assertSee('action="/catalog?lang=en"', false);
        $response->assertSee('name="q"', false);
    }

    public function test_homepage_never_reintroduces_athenaeum_or_old_brand(): void
    {
        $response = $this->get('/?lang=en');

        $response->assertOk();
        $response->assertDontSee('Athenaeum', false);
        $response->assertDontSee('KazUTB Digital Library', false);
    }

    public function test_authenticated_reader_sees_member_workspace_routing_to_dashboard(): void
    {
        $this->loginAs('student');

        $response = $this->get('/?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
        // Authenticated navbar state from partials/navbar.blade.php (EN locale).
        $response->assertSee('Sign out', false);
        // Member Workspace card now routes to the canonical /dashboard surface
        // (Phase 2a/2b). The ?lang=en query is preserved by the withLang helper.
        $response->assertSee('href="/dashboard?lang=en"', false);
    }

    public function test_authenticated_teacher_can_view_homepage(): void
    {
        $this->loginAs('teacher');

        $response = $this->get('/?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
        $response->assertSee('Sign out', false);
    }

    public function test_librarian_can_view_public_homepage(): void
    {
        $this->loginAs('librarian');

        $response = $this->get('/?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
    }

    public function test_admin_can_view_public_homepage(): void
    {
        $this->loginAs('admin');

        $response = $this->get('/?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
    }

    public function test_latest_arrivals_section_renders_for_guest(): void
    {
        // Phase 3-D.1 — Latest Arrivals enrichment.
        // Verify the new section renders on the public homepage for guests.
        $response = $this->get('/?lang=en');

        $response->assertOk();
        // Section header and subtitle.
        $response->assertSee('Latest Arrivals', false);
        $response->assertSee('Recently Added Materials', false);
        // Grid container with test ID.
        $response->assertSee('data-test-id="latest-arrivals-grid"', false);
        // Verify at least one seeded item renders.
        $response->assertSee('Artificial Intelligence in Power Grid Management', false);
        // CTA button/link text.
        $response->assertSee('Explore Catalog', false);
    }

    public function test_latest_arrivals_section_renders_for_authenticated_user(): void
    {
        $this->loginAs('student');

        $response = $this->get('/?lang=en');

        $response->assertOk();
        $response->assertSee('Latest Arrivals', false);
        $response->assertSee('Recently Added Materials', false);
        $response->assertSee('data-test-id="latest-arrivals-grid"', false);
    }

    public function test_latest_arrivals_displays_multiple_items(): void
    {
        $response = $this->get('/?lang=en');

        $response->assertOk();
        // Verify all 3 seeded items are present.
        $response->assertSee('Artificial Intelligence in Power Grid Management', false);
        $response->assertSee('Digital Transformation in Architecture: European Case Studies', false);
        $response->assertSee('Circular Economy for Emerging Markets', false);
    }

    public function test_latest_arrivals_displays_metadata(): void
    {
        $response = $this->get('/?lang=en');

        $response->assertOk();
        // Verify author names are displayed.
        $response->assertSee('Amalina K.B.', false);
        $response->assertSee('Seysenov Z.M.', false);
        // Verify material types are displayed.
        $response->assertSee('Monograph', false);
        $response->assertSee('Dissertation', false);
    }

    public function test_latest_arrivals_cta_links_to_catalog(): void
    {
        $response = $this->get('/?lang=en');

        $response->assertOk();
        // Verify CTAs link to the catalog search with item title as query.
        $response->assertSee('/catalog?q=', false);
    }

    public function test_latest_arrivals_supports_russian_locale(): void
    {
        $response = $this->get('/?lang=ru');

        $response->assertOk();
        // Russian title: "Последние поступления"
        $response->assertSee('Последние поступления', false);
        // Russian subtitle: "Недавно добавленные материалы"
        $response->assertSee('Недавно добавленные материалы', false);
        // Russian seeded items.
        $response->assertSee('Искусственный интеллект в управлении энергосетями', false);
    }

    public function test_latest_arrivals_supports_kazakh_locale(): void
    {
        $response = $this->get('/?lang=kk');

        $response->assertOk();
        // Kazakh title: "Соңғы поступления"
        $response->assertSee('Соңғы поступления', false);
        // Kazakh subtitle: "Жақында қосылған материалдар"
        $response->assertSee('Жақында қосылған материалдар', false);
    }

    public function test_latest_arrivals_placed_between_stats_and_repository(): void
    {
        // Verify the section placement in page flow.
        $response = $this->get('/?lang=en');

        $response->assertOk();
        // Verify canonical sections remain in expected order.
        $pattern = '/Pulse of.*Latest Arrivals.*Institutional Repository/is';
        $this->assertMatchesRegularExpression($pattern, $response->getContent());
    }
}
