<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

/**
 * Phase 3.3 — Public News index (/news).
 *
 * The legacy Route::get('/news', fn () => redirect('/', 301)) has been
 * reversed in Phase 3.3. /news now renders resources/views/news/index.blade.php
 * extending layouts.public, with a featured editorial lead + "Recent Updates"
 * grid seeded from a representative array in routes/web.php.
 */
class PublicNewsIndexPageTest extends TestCase
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

    public function test_legacy_news_redirect_is_reversed(): void
    {
        $response = $this->get('/news?lang=en');

        // Phase 3.3 reversed the legacy 301 /news -> / redirect.
        $response->assertOk();
        $response->assertStatus(200);
    }

    public function test_guest_can_view_news_index(): void
    {
        $response = $this->get('/news?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
        // Canonical intro block.
        $response->assertSee('KazUTB Smart Library news', false);
        // Editorial hero (Featured Report) markers.
        $response->assertSee('Featured Report', false);
        $response->assertSee('Read Full Coverage', false);
        // Recent Updates grid heading.
        $response->assertSee('Recent Updates', false);
        // Seeded articles exposed on the index.
        $response->assertSee('Global Symposium on Archival Integrity Concludes in Astana', false);
        $response->assertSee('Integration of the 19th-Century Eurasian Manuscripts', false);
        $response->assertSee('Expanded Digital Access for External Academic Partners', false);
    }

    public function test_news_index_links_to_detail_route_with_lang(): void
    {
        $response = $this->get('/news?lang=en');

        $response->assertOk();
        // Hero CTA preserves ?lang=en on the detail slug.
        $response->assertSee('href="/news/global-symposium-archival-integrity?lang=en"', false);
        // Grid cards also link to detail slugs.
        $response->assertSee('href="/news/eurasian-manuscripts-integration?lang=en"', false);
        $response->assertSee('href="/news/digital-access-partner-institutions?lang=en"', false);
    }

    public function test_news_index_uses_valid_local_image_assets(): void
    {
        $response = $this->get('/news?lang=en');

        $response->assertOk();
        $response->assertSee('/images/news/campus-library.jpg', false);
        $response->assertSee('/images/news/classics-event.jpg', false);
        $response->assertSee('/images/news/author-visit.jpg', false);
    }

    public function test_news_index_does_not_reintroduce_legacy_brand(): void
    {
        $response = $this->get('/news?lang=en');

        $response->assertOk();
        // View-scoped regression guard (see PublicAboutPageTest rationale:
        // layouts.public footer still carries legacy "KazTBU Digital Library"
        // drift that is out of scope until the layout-level pass).
        $response->assertDontSee('Athenaeum', false);
        $response->assertDontSee('Curator Archive', false);
        $response->assertDontSee('KazTBU Digital Library', false);
        $response->assertDontSee('KazUTB Digital Library', false);
    }

    public function test_authenticated_reader_can_view_news_index(): void
    {
        $this->loginAs('student');

        $response = $this->get('/news?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
        $response->assertSee('Recent Updates', false);
        $response->assertSee('Sign out', false);
    }

    public function test_librarian_can_view_news_index(): void
    {
        $this->loginAs('librarian');

        $this->get('/news?lang=en')->assertOk()->assertSee('KazUTB Smart Library', false);
    }

    public function test_admin_can_view_news_index(): void
    {
        $this->loginAs('admin');

        $this->get('/news?lang=en')->assertOk()->assertSee('KazUTB Smart Library', false);
    }
}
