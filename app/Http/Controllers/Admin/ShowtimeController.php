<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Screen;
use App\Models\Showtime;
use Illuminate\Http\Request;

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
}
