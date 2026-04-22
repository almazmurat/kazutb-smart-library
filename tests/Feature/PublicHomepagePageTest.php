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
}
