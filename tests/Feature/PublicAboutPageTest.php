<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

/**
 * Phase 3.2 — Public About + Contacts consolidation feature coverage.
 *
 * /about and /contacts both render resources/views/about.blade.php but the
 * route passes a different $activePage value, which flips section ordering
 * (contacts-first vs. about-first) and adjusts the hero eyebrow/title.
 *
 * These tests lock down:
 *   - both routes return 200 to guests and contain the canonical section markers
 *   - brand is unified to "KazUTB Smart Library"; no Athenaeum or legacy brand drift
 *   - the Librarian-on-Duty block routes guests to /login and authenticated
 *     readers to /dashboard (canonical member shell)
 *   - auth-aware navbar toggles Sign in / Sign out via session('library.user')
 */
class PublicAboutPageTest extends TestCase
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

    public function test_guest_can_view_about_page(): void
    {
        $response = $this->get('/about?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
        // About-first ordering on /about.
        $response->assertSee('About the Library', false);
        $response->assertSee('The institutional library of KazUTB', false);
        // Mission points markers.
        $response->assertSee('Catalog &amp; collection', false);
        $response->assertSee('Scholarly archive', false);
        // Contacts summary remains visible in hero aside.
        $response->assertSee('Opening hours', false);
        $response->assertSee('Address', false);
        // Librarian-on-Duty block.
        $response->assertSee('Librarian-on-Duty', false);
        // Catalog CTA.
        $response->assertSee('Open catalog', false);
    }

    public function test_guest_can_view_contacts_page(): void
    {
        $response = $this->get('/contacts?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
        // Contacts-first ordering on /contacts: hero title flips.
        $response->assertSee('Reach the library', false);
        $response->assertSee('How to reach the library', false);
        // Full contact + hours block.
        $response->assertSee('37A Kayym Mukhamedkhanov Street, Astana', false);
        $response->assertSee('+7 (7172) 64-58-58', false);
        $response->assertSee('library@kazutb.edu.kz', false);
        $response->assertSee('Opening hours', false);
        // Librarian-on-Duty block.
        $response->assertSee('Librarian-on-Duty', false);
    }

    public function test_about_surface_never_reintroduces_athenaeum_or_legacy_brand(): void
    {
        $about = $this->get('/about?lang=en');
        $contacts = $this->get('/contacts?lang=en');

        $about->assertOk();
        $contacts->assertOk();

        // Regression guard scoped to the view under test (about.blade.php).
        // The shared layouts.public footer still references legacy "KazTBU
        // Digital Library" strings — that is out of scope for Phase 3.2 and
        // belongs to a future layout-level pass, so we only lock down the
        // tokens owned by this view.
        foreach ([$about, $contacts] as $response) {
            $response->assertDontSee('Athenaeum', false);
            $response->assertDontSee('KazUTB Digital Library', false);
        }
    }

    public function test_guest_librarian_on_duty_routes_to_login(): void
    {
        $response = $this->get('/contacts?lang=en');

        $response->assertOk();
        // Guest navbar state.
        $response->assertSee('Sign in', false);
        // Librarian-on-Duty CTA routes unauthenticated visitors to /login.
        $response->assertSee('href="/login?lang=en"', false);
        $response->assertDontSee('href="/dashboard?lang=en"', false);
    }

    public function test_authenticated_reader_librarian_on_duty_routes_to_dashboard(): void
    {
        $this->loginAs('student');

        $response = $this->get('/contacts?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
        // Authenticated navbar state.
        $response->assertSee('Sign out', false);
        // Librarian-on-Duty CTA routes authenticated readers to the canonical
        // /dashboard member shell.
        $response->assertSee('href="/dashboard?lang=en"', false);
    }

    public function test_authenticated_reader_can_view_about_page(): void
    {
        $this->loginAs('student');

        $response = $this->get('/about?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
        $response->assertSee('Sign out', false);
        // On /about the Librarian-on-Duty block still routes to /dashboard
        // for authenticated readers.
        $response->assertSee('href="/dashboard?lang=en"', false);
    }

    public function test_librarian_can_view_public_about_and_contacts(): void
    {
        $this->loginAs('librarian');

        $this->get('/about?lang=en')->assertOk()->assertSee('KazUTB Smart Library', false);
        $this->get('/contacts?lang=en')->assertOk()->assertSee('KazUTB Smart Library', false);
    }

    public function test_admin_can_view_public_about_and_contacts(): void
    {
        $this->loginAs('admin');

        $this->get('/about?lang=en')->assertOk()->assertSee('KazUTB Smart Library', false);
        $this->get('/contacts?lang=en')->assertOk()->assertSee('KazUTB Smart Library', false);
    }
}
