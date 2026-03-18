<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Showtime;
use App\Services\SeatAvailabilityService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home()
    {
        $movies = Movie::where('status', 'now_showing')->get();
        return view('welcome', compact('movies'));
    }

    public function showMovie(Request $request, $id, SeatAvailabilityService $seatService)
    {
        session(['url.intended' => request()->fullUrl()]);
        $movie = Movie::findOrFail($id);

        // 1. Get the selected date, default to today
        $selectedDate = $request->query('date', now()->format('Y-m-d'));

        // 2. Get showtimes specifically for the selected day
        // We filter "future only" by comparing the full start_time timestamp against now()
        $dayShowtimes = Showtime::with(['screen.cinema', 'screen.privateRoomPrice'])
            ->where('movie_id', $id)
            ->whereDate('show_date', $selectedDate)
            ->where('start_time', '>', now()) // This now correctly compares full date + time
            ->orderBy('start_time', 'asc')
            ->get();

        // 3. Get the seat map for the selected showtime
        $selectedShowtimeId = $request->query('showtime');

        // Find the requested showtime, or default to the first available one for that day
        $currentShowtime = $dayShowtimes->where('id', $selectedShowtimeId)->first()
            ?? $dayShowtimes->first();

        $seatMap = $currentShowtime ? $seatService->get($currentShowtime) : [];

        return view('movie-details', compact(
            'movie',
            'dayShowtimes',
            'seatMap',
            'selectedDate',
            'currentShowtime'
        ));
    }

    public function index()
    {
        // Fetch all active cinemas
        $cinemas = \App\Models\Cinema::where('status', true)->get();

        return view('cinema', compact('cinemas'));
    }

    public function showSchedule($id)
    {
        // 1. Find the cinema
        $cinema = \App\Models\Cinema::findOrFail($id);

        // 2. Fetch movies that have showtimes in this cinema's screens
        // We use whereHas to filter movies by their relationship to showtimes -> screens
        $movies = \App\Models\Movie::whereHas('showtimes.screen', function ($query) use ($id) {
            $query->where('cinema_id', $id);
        })
            ->with(['showtimes' => function ($query) use ($id) {
                // Only load showtimes for THIS cinema's screens
                $query->whereHas('screen', function ($q) use ($id) {
                    $q->where('cinema_id', $id);
                })->orderBy('start_time', 'asc');
            }])
            ->get();

        return view('schedule', compact('cinema', 'movies'));
    }
}
