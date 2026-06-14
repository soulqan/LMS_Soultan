<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_redirect_route_redirects_correctly(): void
    {
        $response = $this->get(route('auth.google.redirect'));

        // Socialite::redirect() returns a redirect response to Google. We check that it redirects.
        $this->assertTrue($response->isRedirect());
        $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
    }

    public function test_google_callback_authenticates_existing_user_by_google_id(): void
    {
        $user = User::factory()->create([
            'google_id' => 'google-id-123',
            'role' => User::ROLE_STUDENT,
        ]);

        $mockUser = $this->createMock(\Laravel\Socialite\Contracts\User::class);
        $mockUser->method('getId')->willReturn('google-id-123');

        $mockProvider = $this->createMock(\Laravel\Socialite\Contracts\Provider::class);
        $mockProvider->method('user')->willReturn($mockUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($mockProvider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_google_callback_links_and_authenticates_existing_user_by_email(): void
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
            'google_id' => null,
            'role' => User::ROLE_STUDENT,
        ]);

        $mockUser = $this->createMock(\Laravel\Socialite\Contracts\User::class);
        $mockUser->method('getId')->willReturn('google-id-456');
        $mockUser->method('getEmail')->willReturn('existing@example.com');
        $mockUser->method('getName')->willReturn('Existing User');

        $mockProvider = $this->createMock(\Laravel\Socialite\Contracts\Provider::class);
        $mockProvider->method('user')->willReturn($mockUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($mockProvider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'google_id' => 'google-id-456',
        ]);
    }

    public function test_google_callback_registers_new_user_with_student_role(): void
    {
        $mockUser = $this->createMock(\Laravel\Socialite\Contracts\User::class);
        $mockUser->method('getId')->willReturn('google-id-789');
        $mockUser->method('getEmail')->willReturn('new@example.com');
        $mockUser->method('getName')->willReturn('New Google User');
        $mockUser->method('getNickname')->willReturn(null);

        $mockProvider = $this->createMock(\Laravel\Socialite\Contracts\Provider::class);
        $mockProvider->method('user')->willReturn($mockUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($mockProvider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('home'));
        
        $this->assertDatabaseHas('users', [
            'email' => 'new@example.com',
            'name' => 'New Google User',
            'google_id' => 'google-id-789',
            'role' => User::ROLE_STUDENT,
            'password' => null,
        ]);

        $user = User::query()->where('email', 'new@example.com')->first();
        $this->assertAuthenticatedAs($user);
    }

    public function test_google_registered_user_with_null_password_cannot_login_with_password(): void
    {
        $user = User::factory()->create([
            'email' => 'google-user@example.com',
            'google_id' => 'google-id-999',
            'password' => null,
            'role' => User::ROLE_STUDENT,
        ]);

        // Try standard login with a guessed password
        $response = $this->post(route('login.store'), [
            'email' => 'google-user@example.com',
            'password' => 'somepassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
