<?php

namespace Tests\Feature;

use App\Models\User;
use App\Filament\Resources\Users\UserResource;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_registration_defaults_to_student_role(): void
    {
        $this->post(route('register.store'), [
            'name' => 'New Student',
            'email' => 'student@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
            ->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'email' => 'student@example.com',
            'role' => User::ROLE_STUDENT,
        ]);
    }

    public function test_only_admin_role_can_access_filament_panel(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);

        $panel = Filament::getPanel('admin');

        $this->assertTrue($admin->canAccessPanel($panel));
        $this->assertFalse($student->canAccessPanel($panel));
    }

    public function test_profile_page_requires_authentication(): void
    {
        $this->get(route('profile.show'))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get(route('profile.show'))
            ->assertOk()
            ->assertSee('Edit profile');
    }

    public function test_profile_page_can_be_updated(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ])
            ->assertRedirect(route('profile.show'))
            ->assertSessionHas('status', 'Profile updated successfully.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => User::ROLE_STUDENT,
        ]);
    }

    public function test_admin_user_resource_only_lists_admin_accounts(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);

        $visibleIds = UserResource::getEloquentQuery()->pluck('id');

        $this->assertTrue($visibleIds->contains($admin->id));
        $this->assertFalse($visibleIds->contains($student->id));
    }
}
