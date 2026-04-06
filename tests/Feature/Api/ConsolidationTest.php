<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Tests\TestCase;

class ConsolidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    private function withAuthSession(array $overrides = []): static
    {
        $defaults = [
            'id' => 'test-user-1',
            'name' => 'Тест Тестов',
            'email' => 'test@kazutb.kz',
            'role' => 'reader',
        ];

        return $this->withSession(['library.user' => array_merge($defaults, $overrides)]);
    }

    // ═══════════════════════════════════════════════════════════
    // 1. Removed pages redirect correctly
    // ═══════════════════════════════════════════════════════════

    public function test_services_redirects_to_homepage(): void
    {
        $response = $this->get('/services');
        $response->assertRedirect('/');
        $response->assertStatus(301);
    }

    public function test_news_redirects_to_homepage(): void
    {
        $response = $this->get('/news');
        $response->assertRedirect('/');
        $response->assertStatus(301);
    }

    public function test_about_redirects_to_contacts(): void
    {
        $response = $this->get('/about');
        $response->assertRedirect('/contacts');
        $response->assertStatus(301);
    }

    // ═══════════════════════════════════════════════════════════
    // 2. Surviving pages still render
    // ═══════════════════════════════════════════════════════════

    public function test_homepage_renders(): void
    {
        $response = $this->get('/');
        $response->assertOk();
    }

    public function test_catalog_renders(): void
    {
        $response = $this->get('/catalog');
        $response->assertOk();
        $response->assertSee('Каталог');
    }

    public function test_contacts_renders_with_about_content(): void
    {
        $response = $this->get('/contacts');
        $response->assertOk();
        $response->assertSee('О библиотеке и контакты');
        $response->assertSee('Миссия');
        $response->assertSee('Более 50 000');
        $response->assertSee('library@kazutb.kz');
    }

    public function test_resources_renders(): void
    {
        $response = $this->get('/resources');
        $response->assertOk();
    }

    public function test_for_teachers_renders(): void
    {
        $response = $this->get('/for-teachers');
        $response->assertOk();
        $response->assertSee('Преподавателям');
    }

    public function test_login_renders(): void
    {
        $response = $this->get('/login');
        $response->assertOk();
    }

    // ═══════════════════════════════════════════════════════════
    // 3. Navbar no longer references removed pages
    // ═══════════════════════════════════════════════════════════

    public function test_contacts_page_has_no_services_nav_link(): void
    {
        $response = $this->get('/contacts');
        $response->assertOk();
        $response->assertDontSee('href="/services"', false);
        $response->assertDontSee('href="/news"', false);
        $response->assertDontSee('href="/about"', false);
    }

    public function test_navbar_has_for_teachers_link(): void
    {
        $response = $this->get('/contacts');
        $response->assertOk();
        $response->assertSee('href="/for-teachers"', false);
        $response->assertSee('Преподавателям');
    }

    // ═══════════════════════════════════════════════════════════
    // 4. Role-aware account
    // ═══════════════════════════════════════════════════════════

    public function test_teacher_account_shows_workbench(): void
    {
        $response = $this->withAuthSession(['profile_type' => 'teacher'])
            ->get('/account');

        $response->assertOk();
        $response->assertSee('workbench-section', false);
        $response->assertSee('Подборка литературы');
        $response->assertSee('📚 Преподаватель');
    }

    public function test_student_account_shows_quick_actions(): void
    {
        $response = $this->withAuthSession(['profile_type' => 'student'])
            ->get('/account');

        $response->assertOk();
        $response->assertSee('Быстрые действия');
        $response->assertSee('🎓 Студент');
        // Workbench section HTML element must not be rendered
        $response->assertDontSee('id="workbench-section"', false);
    }

    public function test_librarian_account_renders(): void
    {
        $response = $this->withAuthSession(['role' => 'librarian'])
            ->get('/account');

        $response->assertOk();
        $response->assertSee('📖 Библиотекарь');
    }

    public function test_admin_account_renders(): void
    {
        $response = $this->withAuthSession(['role' => 'admin'])
            ->get('/account');

        $response->assertOk();
        $response->assertSee('🛡️ Администратор');
    }

    public function test_default_reader_account_shows_quick_actions(): void
    {
        $response = $this->withAuthSession()
            ->get('/account');

        $response->assertOk();
        $response->assertSee('Быстрые действия');
        // Workbench section HTML element must not be rendered for non-teacher
        $response->assertDontSee('id="workbench-section"', false);
    }

    // ═══════════════════════════════════════════════════════════
    // 5. Catalog UX improvements
    // ═══════════════════════════════════════════════════════════

    public function test_catalog_has_clear_filters(): void
    {
        $response = $this->get('/catalog');
        $response->assertOk();
        $response->assertSee('clear-filters-btn', false);
        $response->assertSee('clearAllFilters', false);
    }

    public function test_catalog_has_mobile_filter_toggle(): void
    {
        $response = $this->get('/catalog');
        $response->assertOk();
        $response->assertSee('mobile-filter-toggle', false);
        $response->assertSee('toggleFilters', false);
    }

    public function test_catalog_has_filter_badge(): void
    {
        $response = $this->get('/catalog');
        $response->assertOk();
        $response->assertSee('filter-count-badge', false);
        $response->assertSee('updateFilterBadge', false);
    }

    // ═══════════════════════════════════════════════════════════
    // 6. No regressions — auth flows
    // ═══════════════════════════════════════════════════════════

    public function test_unauthenticated_account_redirects_to_login(): void
    {
        $response = $this->get('/account');
        $response->assertRedirect('/login?redirect=%2Faccount');
    }

    public function test_authenticated_login_redirects_to_account(): void
    {
        $response = $this->withAuthSession()
            ->get('/login');
        $response->assertRedirect('/account');
    }

    public function test_account_summary_api_still_works(): void
    {
        $response = $this->withAuthSession()
            ->getJson('/api/v1/account/summary');
        $response->assertOk();
        $response->assertJsonPath('authenticated', true);
    }

    // ═══════════════════════════════════════════════════════════
    // 7. For-teachers page no longer links to /services
    // ═══════════════════════════════════════════════════════════

    public function test_for_teachers_has_no_services_link(): void
    {
        $response = $this->get('/for-teachers');
        $response->assertOk();
        $response->assertDontSee('href="/services"', false);
    }

    // ═══════════════════════════════════════════════════════════
    // 8. Profile type in demo auth config
    // ═══════════════════════════════════════════════════════════

    public function test_demo_auth_config_has_profile_types(): void
    {
        $identities = config('demo_auth.identities');
        $this->assertNotNull($identities);

        $this->assertEquals('student', $identities['student']['profile_type'] ?? null);
        $this->assertEquals('teacher', $identities['teacher']['profile_type'] ?? null);
    }
}
