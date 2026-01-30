<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Fetch bookings belonging only to the authenticated user
        // We eager load 'showtime.movie' and 'bookingSeats.seat' for performance
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['showtime.movie', 'bookingSeats.seat.seatRow'])
            ->latest() // Show newest bookings first
            ->paginate(10);

        return view('dashboard', compact('bookings'));
    }
}
