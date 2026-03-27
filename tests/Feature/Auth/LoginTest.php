<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_email(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'secret',
            'ad_login' => 'ad_login',
            'role' => 'employee',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'secret',
            'device_name' => 'web',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.email', 'user@example.com')
            ->assertJsonPath('user.ad_login', 'ad_login')
            ->assertJsonPath('user.role', 'employee');

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_user_can_login_with_login_field(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'secret',
            'ad_login' => 'ad_login',
            'role' => 'employee',
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'ad_login',
            'password' => 'secret',
            'device_name' => 'web',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.email', 'user@example.com')
            ->assertJsonPath('user.ad_login', 'ad_login')
            ->assertJsonPath('user.role', 'employee');

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }
}
