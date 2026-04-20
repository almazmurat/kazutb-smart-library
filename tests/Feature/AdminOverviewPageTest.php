<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminOverviewPageTest extends TestCase
{
    private function staffSession(string $role = 'admin'): array
    {
        return [
            'library.user' => [
                'id' => 'admin-1',
                'name' => 'Demo Admin',
                'email' => 'admin@example.com',
                'login' => 'admin01',
                'ad_login' => 'admin01',
                'role' => $role,
            ],
            'library.crm_token' => 'test-admin-token',
            'library.authenticated_at' => now()->toIso8601String(),
        ];
    }

    public function test_admin_overview_redirects_guests_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login?redirect=%2Fadmin');
    }

    public function test_admin_routes_reject_non_admin_staff_and_readers(): void
    {
        foreach (['librarian', 'reader', ''] as $role) {
            foreach (['/admin', '/admin/users', '/admin/logs', '/admin/news', '/admin/feedback', '/admin/settings', '/admin/reports'] as $uri) {
                $this->withSession($this->staffSession($role))->get($uri)->assertForbidden();
            }
        }
    }

    public function test_admin_overview_renders_for_admin_session(): void
    {
        $response = $this->withSession($this->staffSession('admin'))->get('/admin');

        $response->assertOk()
            ->assertSee('Admin Portal', false)
            ->assertSee('Governance Overview', false)
            ->assertSee('Platform Health', false)
            ->assertSee('Governance Queue', false)
            ->assertSee('User &amp; Role Management', false)
            ->assertSee('Reports &amp; Analytics', false);
    }

    public function test_admin_follow_on_placeholders_render_for_admin_session(): void
    {
        $this->withSession($this->staffSession('admin'))->get('/admin/users')->assertOk()->assertSee('User &amp; Role Management', false);
        $this->withSession($this->staffSession('admin'))->get('/admin/logs')->assertOk()->assertSee('Governance &amp; Logs', false);
        $this->withSession($this->staffSession('admin'))->get('/admin/news')->assertOk()->assertSee('News Management', false);
        $this->withSession($this->staffSession('admin'))->get('/admin/feedback')->assertOk()->assertSee('Feedback Inbox', false);
        $this->withSession($this->staffSession('admin'))->get('/admin/settings')->assertOk()->assertSee('System Settings', false);
        $this->withSession($this->staffSession('admin'))->get('/admin/reports')->assertOk()->assertSee('Reports &amp; Analytics', false);
    }
}
