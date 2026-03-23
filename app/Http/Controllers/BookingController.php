<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\PrivateRoomBooking;
use App\Models\SeatLock;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     * Admin sees all bookings; regular users see only their own.
     */
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $bookings = Booking::with(['user', 'showtime.movie', 'showtime.screen', 'bookingSeats.seat.seatRow', 'privateRoom', 'payment'])
                ->latest()
                ->paginate(15);

            return view('admin.bookings.index', compact('bookings'));
        }

        // Eager load showtime, movie, and seats to avoid N+1 query issues
        $bookings = Booking::with(['showtime.movie', 'bookingSeats.seat.seatRow'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Showtime $showtime)
    {
        $seatIds = $request->seat_ids ?? session('locked_seat_ids', []);

        if (empty($seatIds)) {
            return redirect()->back()->with('error', 'No seats selected.');
        }

        if ($showtime->start_time < now()) {
            return redirect()->route('movies.index')
                ->with('error', 'This showtime has already passed and is no longer available.');
        }

        $seats = Seat::with('seatRow')->whereIn('id', $seatIds)->get();
        $totalPrice = $seats->sum(fn($seat) => $seat->seatRow->price);

        return view('bookings.create', compact('showtime', 'seats', 'totalPrice'));
    }

    // Store public (seat-based) booking and redirect to payment
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

                // 1. Check if seats are already booked
                $alreadyBooked = BookingSeat::whereIn('seat_id', $seatIds)
                    ->whereHas('booking', fn($q) => $q->where('showtime_id', $showtime->id)->whereIn('status', ['paid', 'confirmed']))
                    ->exists();
                if ($alreadyBooked) abort(409, 'One or more seats are already booked.');

                // 2. Check if seats are locked
                $locked = SeatLock::where('showtime_id', $showtime->id)
                    ->whereIn('seat_id', $seatIds)
                    ->where('expires_at', '>', now())
                    ->exists();
                if ($locked) return back()->with('error', 'One or more seats are temporarily locked.');

                // 3. Calculate total price
                $totalPrice = Seat::whereIn('id', $seatIds)->with('seatRow')->get()->sum(fn($seat) => $seat->seatRow->price);

                // 4. Create booking
                $booking = Booking::create([
                    'user_id' => auth()->id(),
                    'showtime_id' => $showtime->id,
                    'booking_type' => 'public',
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                ]);

                // 5. Lock seats for 10 minutes
                foreach ($seatIds as $seatId) {
                    SeatLock::create([
                        'showtime_id' => $showtime->id,
                        'seat_id' => $seatId,
                        'user_id' => auth()->id(),
                        'expires_at' => now()->addMinutes(10),
                    ]);
                }

                // 6. Save booking seats
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

    // Store private room booking and redirect to payment
    public function storePrivate(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
        ]);

        $showtime = Showtime::with('screen.privateRoomPrice')->findOrFail($request->showtime_id);

        if ($showtime->screen->screen_type !== 'private') {
            return back()->with('error', 'This showtime is not for a private room.');
        }

        $privateRoomPrice = $showtime->screen->privateRoomPrice;
        if (! $privateRoomPrice) {
            return back()->with('error', 'No pricing configured for this private room.');
        }

        // Check if this showtime is already booked as a private room
        $alreadyBooked = Booking::where('showtime_id', $showtime->id)
            ->where('booking_type', 'private')
            ->whereIn('status', ['pending', 'paid'])
            ->exists();

        if ($alreadyBooked) {
            return back()->with('error', 'This private room is already reserved for the selected showtime.');
        }

        try {
            $booking = DB::transaction(function () use ($showtime, $privateRoomPrice) {
                $booking = Booking::create([
                    'user_id'      => auth()->id(),
                    'showtime_id'  => $showtime->id,
                    'booking_type' => 'private',
                    'total_price'  => $privateRoomPrice->price,
                    'status'       => 'pending',
                ]);

                PrivateRoomBooking::create([
                    'booking_id' => $booking->id,
                    'screen_id'  => $showtime->screen_id,
                ]);

                return $booking;
            });

            return redirect()->route('payments.create', $booking->id)
                ->with('success', 'Private room reserved. Please complete payment.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        if (auth()->user()->role !== 'admin' && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['showtime.movie', 'showtime.screen.cinema', 'bookingSeats.seat.seatRow', 'payment.paymentMethod', 'privateRoom.screen']);
        return view('bookings.show', compact('booking'));
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
        $booking = Booking::findOrFail($id);

        if (auth()->user()->role !== 'admin' && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }

    public function approve(Booking $booking)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        try {
            DB::transaction(function () use ($booking) {
                $booking->update(['status' => 'paid']);

                if ($booking->payment) {
                    $booking->payment->update(['status' => 'success']);
                }
            });

            return back()->with('success', 'Booking #' . $booking->id . ' has been approved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong during approval.');
        }
    }

    public function reject(Booking $booking)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        try {
            DB::transaction(function () use ($booking) {
                $booking->update([
                    'status' => 'cancelled',
                ]);

                if ($booking->payment) {
                    $booking->payment->update([
                        'status' => 'failed',
                    ]);
                }
            });

            return back()->with('success', 'Booking rejected. The user will see this as "Cancelled".');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject booking.');
        }
    }
}
