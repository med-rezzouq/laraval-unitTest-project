<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_login_with_email_and_password()
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'password'
        ])->assertOk();

        $this->assertArrayHasKey('token', $response->json());
    }


    public function test_if_user_email_is_not_availlable_then_it_return_error()
    {

        $this->postJson(route('user.login'), [
            'email' => 'Sarthak@gmail.com',
            'password' => 'password'
        ])->assertOk()->assertUnauthorized();
    }

    public function test_user_it_raise_error_if_password_is_incorrect()
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('user.login'), [
            'email' => $user->email,
            'password' => 'random'
        ])->assertUnauthorized();
    }
}
