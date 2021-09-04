<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    // OKのパターン

    // ログインページアクセス

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    // ログインテスト

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'rm_id' => $user->rm_id,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    // NGのパターン

    // rm_idが違う場合

    public function test_users_can_not_authenticate_with_invalid_rm_id()
    {
        $user = User::factory()->create();

        $user->rm_id = 12345678;
        $user->save();

        $user->rm_id = 87654321;

        $this->post('/login', [
            'rm_id' => $user->rm_id,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    // パスワードが違うとログインできない

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'rm_id' => $user->rm_id,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
