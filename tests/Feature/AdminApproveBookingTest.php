<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Screen;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminApproveBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_approve_booking_even_if_membership_level_columns_are_missing(): void
    {
        $this->dropMembershipLevelColumnsIfExist();

        $member = User::factory()->create([
            'is_club_member' => true,
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $booking = Booking::create([
            'user_id' => $member->id,
            'showtime_id' => $this->createShowtime()->id,
            'booking_type' => 'public',
            'total_price' => 50000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.bookings.approve', $booking));

        $response->assertSessionHas('success');
        $booking->refresh();

        $this->assertSame('paid', $booking->status);
    }

    private function createShowtime(): Showtime
    {
        $cinema = Cinema::create([
            'name' => 'Approval Test Cinema',
            'address' => 'Address',
            'city' => 'Yangon',
            'type' => 'public',
            'status' => true,
        ]);

        $screen = Screen::create([
            'cinema_id' => $cinema->id,
            'name' => 'Hall 1',
            'screen_type' => 'public',
            'status' => true,
        ]);

        $movie = Movie::create([
            'title' => 'Approval Test Movie',
            'duration_minutes' => 120,
            'status' => 'now_showing',
        ]);

        return Showtime::create([
            'movie_id' => $movie->id,
            'screen_id' => $screen->id,
            'show_date' => now()->toDateString(),
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(3),
        ]);
    }

    private function dropMembershipLevelColumnsIfExist(): void
    {
        Schema::table('users', function ($table) {
            if (Schema::hasColumn('users', 'membership_level')) {
                $table->dropColumn('membership_level');
            }

            if (Schema::hasColumn('users', 'membership_discount_percent')) {
                $table->dropColumn('membership_discount_percent');
            }

            if (Schema::hasColumn('users', 'membership_total_spent')) {
                $table->dropColumn('membership_total_spent');
            }
        });
    }
}
