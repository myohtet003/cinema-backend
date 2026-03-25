<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipLevelTest extends TestCase
{
    use RefreshDatabase;

    public function test_join_membership_sets_default_level_and_discount(): void
    {
        $user = User::factory()->create([
            'is_club_member' => false,
            'membership_joined_at' => null,
            'membership_level' => null,
            'membership_discount_percent' => 0,
            'membership_total_spent' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('membership.join'));

        $response->assertRedirect(route('dashboard'));

        $user->refresh();

        $this->assertTrue($user->is_club_member);
        $this->assertSame('bronze', $user->membership_level);
        $this->assertSame(5, $user->membership_discount_percent);
        $this->assertSame(0, $user->membership_total_spent);
        $this->assertNotNull($user->membership_joined_at);
    }

    public function test_approve_booking_updates_total_spent_and_upgrades_level(): void
    {
        $member = User::factory()->create([
            'is_club_member' => true,
            'membership_joined_at' => now(),
            'membership_level' => 'bronze',
            'membership_discount_percent' => 5,
            'membership_total_spent' => 450000,
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $booking = Booking::create([
            'user_id' => $member->id,
            'showtime_id' => $this->createShowtime()->id,
            'booking_type' => 'public',
            'total_price' => 60000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.bookings.approve', $booking));

        $response->assertSessionHas('success');

        $member->refresh();
        $booking->refresh();

        $this->assertSame('paid', $booking->status);
        $this->assertSame(510000, $member->membership_total_spent);
        $this->assertSame('gold', $member->membership_level);
        $this->assertSame(15, $member->membership_discount_percent);
    }

    private function createShowtime()
    {
        $cinema = \App\Models\Cinema::create([
            'name' => 'Test Cinema',
            'address' => 'Address',
            'city' => 'Yangon',
            'type' => 'public',
            'status' => true,
        ]);

        $screen = \App\Models\Screen::create([
            'cinema_id' => $cinema->id,
            'name' => 'Hall A',
            'screen_type' => 'public',
            'status' => true,
        ]);

        $movie = \App\Models\Movie::create([
            'title' => 'Membership Test Movie',
            'duration_minutes' => 120,
            'status' => 'now_showing',
        ]);

        return \App\Models\Showtime::create([
            'movie_id' => $movie->id,
            'screen_id' => $screen->id,
            'show_date' => now()->toDateString(),
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(3),
        ]);
    }
}
