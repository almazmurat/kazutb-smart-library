<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

/**
 * Phase 3.3 — Public News detail (/news/{slug}).
 *
 * /news/{slug} renders resources/views/news/show.blade.php extending
 * layouts.public, looking up seeded articles by slug from the shared
 * $newsSeedProvider closure in routes/web.php.
 */
class PublicNewsDetailPageTest extends TestCase
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

    public function test_guest_can_view_featured_news_detail(): void
    {
        $response = $this->get('/news/global-symposium-archival-integrity?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
        // Back-to-news affordance.
        $response->assertSee('Back to news', false);
        $response->assertSee('href="/news?lang=en"', false);
        // Headline and category chip.
        $response->assertSee('Global Symposium on Archival Integrity Concludes in Astana', false);
        $response->assertSee('Featured Report', false);
        // Editorial body markers.
        $response->assertSee('Programme themes', false);
        $response->assertSee('Outcomes and next steps', false);
        // Inline CTA.
        $response->assertSee('Open the repository', false);
        // Related articles block.
        $response->assertSee('Related articles', false);
        $response->assertSee('Integration of the 19th-Century Eurasian Manuscripts', false);
    }

    public function test_guest_can_view_secondary_news_detail(): void
    {
        $response = $this->get('/news/eurasian-manuscripts-integration?lang=en');

        $response->assertOk();
        $response->assertSee('Integration of the 19th-Century Eurasian Manuscripts', false);
        $response->assertSee('Collection Updates', false);
        $response->assertSee('Open the catalog', false);
        // Related list excludes the current article, so it should include the featured one.
        $response->assertSee('Global Symposium on Archival Integrity Concludes in Astana', false);
    }

    public function test_unknown_news_slug_returns_404(): void
    {
        $response = $this->get('/news/does-not-exist?lang=en');

        $response->assertNotFound();
    }

    public function test_news_detail_does_not_reintroduce_legacy_brand(): void
    {
        $response = $this->get('/news/global-symposium-archival-integrity?lang=en');

        $response->assertOk();
        $response->assertDontSee('Athenaeum', false);
        $response->assertDontSee('Curator Archive', false);
        $response->assertDontSee('KazUTB Digital Library', false);
    }

    public function test_authenticated_reader_can_view_news_detail(): void
    {
        $this->loginAs('student');

        $response = $this->get('/news/global-symposium-archival-integrity?lang=en');

        $response->assertOk();
        $response->assertSee('KazUTB Smart Library', false);
        $response->assertSee('Sign out', false);
        $response->assertSee('Back to news', false);
    }

    public function test_librarian_can_view_news_detail(): void
    {
        $this->loginAs('librarian');

        $this->get('/news/global-symposium-archival-integrity?lang=en')
            ->assertOk()
            ->assertSee('KazUTB Smart Library', false);
    }

    public function test_admin_can_view_news_detail(): void
    {
        $this->loginAs('admin');

        $this->get('/news/global-symposium-archival-integrity?lang=en')
            ->assertOk()
            ->assertSee('KazUTB Smart Library', false);
    }
}
