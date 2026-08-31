<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_user_can_login_and_request_a_remember_cookie(): void
    {
        $user = User::factory()->create(['password' => 'una-clave-segura']);

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'una-clave-segura',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(collect($response->headers->getCookies())->contains(
            fn ($cookie) => str_starts_with($cookie->getName(), 'remember_web_')
        ));
    }

    public function test_invalid_credentials_do_not_authenticate(): void
    {
        $user = User::factory()->create();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'incorrecta',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
