<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    // 登録可能

    public function test_new_users_can_register()
    {
        $response = $this->post('/register', [
            'rm_id' => '12345678',
            'is_admin' => null,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    //is_adminが'1'でも登録可能 

    public function test_new_users_can_register_with_isadmin_1()
    {
        $response = $this->post('/register', [
            'rm_id' => '12345678',
            'is_admin' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    // 登録不可
    // rm_idが7桁では登録不可

    public function test_new_users_can_not_register_with_short_rmid()
    {
        $response = $this->post('/register', [
            'rm_id' => '1234567',
            'is_admin' => null,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
    }

    // rm_idが9桁以上では登録不可

    public function test_new_users_can_not_register_with_long_rmid()
    {
        $response = $this->post('/register', [
            'rm_id' => '123456789',
            'is_admin' => null,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
    }

    // rm_idが全角数字では登録不可

    public function test_new_users_can_not_register_with_rmid_string()
    {
        $response = $this->post('/register', [
            'rm_id' => '１２３４５６７８',
            'is_admin' => null,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
    }

    // メールアドレスに@が含まれなければ登録不可

    public function test_new_users_can_not_register_with_no_emailadress()
    {
        $response = $this->post('/register', [
            'rm_id' => '12345678',
            'is_admin' => null,
            'name' => 'Test User',
            'email' => 'testexample.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
    }
}
