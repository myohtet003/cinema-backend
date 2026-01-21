<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Screen;
use App\Models\Seat;
use App\Models\SeatRow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeatRowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // SeatRowController.php
    public function index(Screen $screen)
    {
        // Load the cinema relationship to ensure $screen->cinema->name works
        $screen->load('cinema');

        $seatRows = $screen->seatRows()->withCount('seats')->get();

        return view('admin.seat_rows.index', compact('screen', 'seatRows'));
    }

    public function create(Screen $screen)
    {
        $screen->load('cinema');

        if ($screen->screen_type !== 'public') {
            abort(403, 'Private rooms do not use row-based seat generation.');
        }

        return view('admin.seat_rows.create', compact('screen'));
    }

    public function store(Request $request, Screen $screen)
    {
        if ($screen->screen_type !== 'public') {
            abort(403);
        }

        $request->validate([
            'row_name' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'seat_count' => 'required|integer|min:1|max:100',
        ]);

        DB::transaction(function () use ($request, $screen) {
            $row = SeatRow::create([
                'screen_id' => $screen->id,
                'row_name' => $request->row_name,
                'price' => $request->price,
            ]);

            for ($i = 1; $i <= $request->seat_count; $i++) {
                Seat::create([
                    'seat_row_id' => $row->id,
                    'seat_number' => $i,
                    'status' => true // assuming you have a status column
                ]);
            }
        });

        return redirect()->route('screens.seat_rows.index', $screen)
            ->with('success', 'Row and seats created successfully');
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
    // public function edit(Screen $screen, SeatRow $seatRow)
    // {
    //     // Ensure the row actually belongs to the screen for security
    //     if ($seatRow->screen_id !== $screen->id) {
    //         abort(404);
    //     }

    //     $screen->load('cinema');
    //     return view('admin.seat_rows.edit', compact('screen', 'seatRow'));
    // }

    public function edit(Screen $screen, SeatRow $seatRow)
    {
        $screen->load('cinema');
        // Load all seats belonging to this row, ordered by seat number
        $seatRow->load(['seats' => function ($query) {
            $query->orderBy('seat_number', 'asc');
        }]);

        return view('admin.seat_rows.edit', compact('screen', 'seatRow'));
    }

    public function update(Request $request, Screen $screen, SeatRow $seatRow)
    {
        $validated = $request->validate([
            'row_name' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
        ]);

        // Update the row
        $seatRow->update($validated);

        return redirect()
            ->route('screens.seat_rows.index', $screen)
            ->with('success', "Row '{$seatRow->row_name}' updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Screen $screen, SeatRow $seatRow)
    {
        // Because of your database foreign keys (onDelete cascade), 
        // deleting the row will automatically delete the individual seats.
        $seatRow->delete();

        return back()->with('success', 'Row deleted successfully.');
    }
}
