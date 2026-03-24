<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Screen;
use App\Models\Seat;
use App\Models\SeatLock;
use App\Models\SeatRow;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeatStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_seat_status_endpoint_returns_booked_locked_and_available(): void
    {
        $user = User::factory()->create();
        $showtime = $this->createShowtimeWithSeats();
        [$seatA, $seatB, $seatC] = $showtime->screen->seatRows->first()->seats->values()->all();

        $booking = Booking::create([
            'user_id' => $user->id,
            'showtime_id' => $showtime->id,
            'booking_type' => 'public',
            'total_price' => 10000,
            'status' => 'pending',
        ]);

        BookingSeat::create([
            'booking_id' => $booking->id,
            'seat_id' => $seatA->id,
        ]);

        $method = PaymentMethod::create([
            'name' => 'KBZ Pay',
            'status' => true,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'payment_method_id' => $method->id,
            'transaction_id' => 'trx-status-test-1',
            'amount' => 10000,
            'status' => 'pending',
        ]);

        SeatLock::create([
            'showtime_id' => $showtime->id,
            'seat_id' => $seatB->id,
            'user_id' => $user->id,
            'expires_at' => now()->addMinutes(2),
        ]);

        $response = $this->getJson(route('showtimes.seat-status', $showtime));

        $response->assertOk()
            ->assertJsonPath('showtime_id', $showtime->id)
            ->assertJsonPath('seats.' . $seatA->id, 'booked')
            ->assertJsonPath('seats.' . $seatB->id, 'locked')
            ->assertJsonPath('seats.' . $seatC->id, 'available');
    }

    public function test_expired_lock_is_cleaned_and_reported_as_available(): void
    {
        $user = User::factory()->create();
        $showtime = $this->createShowtimeWithSeats();
        $seat = $showtime->screen->seatRows->first()->seats->first();

        SeatLock::create([
            'showtime_id' => $showtime->id,
            'seat_id' => $seat->id,
            'user_id' => $user->id,
            'expires_at' => now()->subMinute(),
        ]);

        $response = $this->getJson(route('showtimes.seat-status', $showtime));

        $response->assertOk()
            ->assertJsonPath('seats.' . $seat->id, 'available');

        $this->assertDatabaseMissing('seat_locks', [
            'showtime_id' => $showtime->id,
            'seat_id' => $seat->id,
        ]);
    }

    private function createShowtimeWithSeats(): Showtime
    {
        $cinema = Cinema::create([
            'name' => 'Cinema One',
            'address' => 'Downtown',
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

        $row = SeatRow::create([
            'screen_id' => $screen->id,
            'row_name' => 'A',
            'price' => 5000,
        ]);

        Seat::create(['seat_row_id' => $row->id, 'seat_number' => 1]);
        Seat::create(['seat_row_id' => $row->id, 'seat_number' => 2]);
        Seat::create(['seat_row_id' => $row->id, 'seat_number' => 3]);

        $movie = Movie::create([
            'title' => 'Realtime Test Movie',
            'duration_minutes' => 120,
            'status' => 'now_showing',
        ]);

        return Showtime::create([
            'movie_id' => $movie->id,
            'screen_id' => $screen->id,
            'show_date' => now()->toDateString(),
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(3),
        ])->load('screen.seatRows.seats');
    }
}
