<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\SeatLock;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Showtime $showtime)
    {
        $seatIds = $request->seat_ids ?? session('locked_seat_ids', []);

        if (empty($seatIds)) {
            return redirect()->back()->with('error', 'No seats selected.');
        }

        $seats = Seat::with('seatRow')->whereIn('id', $seatIds)->get();
        $totalPrice = $seats->sum(fn($seat) => $seat->seatRow->price);

        return view('bookings.create', compact('showtime', 'seats', 'totalPrice'));
    }

    // Store booking and redirect to payment
    public function store(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'selected_seats' => 'required|string',
        ]);

        $seatIds = collect(explode(',', $request->selected_seats))->map(fn($id) => (int)$id)->unique()->values();

        if ($seatIds->isEmpty()) {
            return back()->withErrors('No seats selected.');
        }

        $showtime = Showtime::findOrFail($request->showtime_id);

        try {
            $booking = DB::transaction(function () use ($seatIds, $showtime) {

                // 1️⃣ Check if seats are already booked
                $alreadyBooked = BookingSeat::whereIn('seat_id', $seatIds)
                    ->whereHas('booking', fn($q) => $q->where('showtime_id', $showtime->id)->whereIn('status', ['paid', 'confirmed']))
                    ->exists();
                if ($alreadyBooked) abort(409, 'One or more seats are already booked.');

                // 2️⃣ Check if seats are locked
                $locked = SeatLock::where('showtime_id', $showtime->id)
                    ->whereIn('seat_id', $seatIds)
                    ->where('expires_at', '>', now())
                    ->exists();
                if ($locked) return back()->with('error', 'One or more seats are temporarily locked.');

                // 3️⃣ Calculate total price
                $totalPrice = Seat::whereIn('id', $seatIds)->with('seatRow')->get()->sum(fn($seat) => $seat->seatRow->price);

                // 4️⃣ Create booking
                $booking = Booking::create([
                    'user_id' => auth()->id(),
                    'showtime_id' => $showtime->id,
                    'booking_type' => 'public',
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                ]);

                // 5️⃣ Lock seats for 10 minutes
                foreach ($seatIds as $seatId) {
                    SeatLock::create([
                        'showtime_id' => $showtime->id,
                        'seat_id' => $seatId,
                        'user_id' => auth()->id(),
                        'expires_at' => now()->addMinutes(10),
                    ]);
                }

                // 6️⃣ Save booking seats
                foreach ($seatIds as $seatId) {
                    BookingSeat::create([
                        'booking_id' => $booking->id,
                        'seat_id' => $seatId,
                    ]);
                }

                return $booking;
            });

            return redirect()->route('payments.create', $booking->id)
                ->with('success', 'Seats reserved. Please complete payment.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
