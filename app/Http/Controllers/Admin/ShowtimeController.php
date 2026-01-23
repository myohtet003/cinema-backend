<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Screen;
use App\Models\SeatLock;
use App\Models\Showtime;
use App\Services\SeatAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowtimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load movie, screen, and cinema for better performance
        $showtimes = Showtime::with(['movie', 'screen.cinema'])
            ->orderBy('show_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        return view('admin.showtimes.index', compact('showtimes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $movies = Movie::where('status', 'now_showing')->get();
        $screens = Screen::with('cinema')->get();
        return view('admin.showtimes.create', compact('movies', 'screens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'screen_id' => 'required|exists:screens,id',
            'show_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Add logic here to check if the screen is already busy during this time range

        Showtime::create($request->all());

        return redirect()->route('showtimes.index')->with('success', 'Showtime scheduled!');
    }

    /**
     * Display the specified resource.
     */
    // ShowtimeController.php
    public function show(Showtime $showtime, SeatAvailabilityService $seatService)
    {
        $showtime->load(['movie', 'screen']);

        $seatMap = $seatService->get($showtime);

        return view('admin.showtimes.show', compact('showtime', 'seatMap'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $showtime = Showtime::with(['movie', 'screen.cinema'])->findOrFail($id);
        $movies = Movie::where('status', 'now_showing')->get();
        $screens = Screen::with('cinema')->get();
        return view('admin.showtimes.edit', compact('showtime', 'movies', 'screens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Showtime $showtime)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'screen_id' => 'required|exists:screens,id',
            'show_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Check for schedule overlaps here (optional but recommended)

        $showtime->update($validated);

        return redirect()
            ->route('showtimes.index')
            ->with('success', 'Schedule updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $showtime = Showtime::findOrFail($id);
        $showtime->delete();
        return redirect()->route('showtimes.index')->with('success', 'Showtime deleted successfully!');
    }



    public function lockSeats(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'seat_ids' => 'required|array|min:1'
        ]);

        DB::transaction(function () use ($request) {

            $exists = SeatLock::where('showtime_id', $request->showtime_id)
                ->whereIn('seat_id', $request->seat_ids)
                ->where('expires_at', '>', now())
                ->exists();

            if ($exists) {
                abort(409);
            }

            foreach ($request->seat_ids as $seatId) {
                SeatLock::create([
                    'showtime_id' => $request->showtime_id,
                    'seat_id' => $seatId,
                    'user_id' => auth()->id(),
                    'expires_at' => now()->addMinutes(2),
                ]);
            }
        });

        return response()->json([
            'redirect' => route('bookings.create', $request->showtime_id)
        ]);
    }
}
