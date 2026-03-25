<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_new_users_can_register_and_join_club_with_default_level(): void
    {
        $response = $this->post('/register', [
            'name' => 'Club User',
            'email' => 'club@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'join_club' => 1,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'club@example.com')->firstOrFail();

        $this->assertTrue($user->is_club_member);
        $this->assertNotNull($user->membership_joined_at);
        $this->assertSame('bronze', $user->membership_level);
        $this->assertSame(5, $user->membership_discount_percent);
        $this->assertSame(0, $user->membership_total_spent);
    }
}
