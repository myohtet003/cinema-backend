<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Models\Screen;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // ScreenController.php
    public function index()
    {
        // Eager load 'cinema' relationship
        $screens = Screen::with('cinema')->latest()->paginate(10);

        return view('admin.screens.index', compact('screens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cinemas = Cinema::select('id', 'name', 'city', 'type')->get();
        return view('admin.screens.create', compact('cinemas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $cinema = Cinema::findOrFail($request->cinema_id);

    //     // dd($request->all());

    //     $request->validate([
    //         'cinema_id' => 'required',
    //         'screen_type' => [
    //             'required',
    //             function ($attribute, $value, $fail) use ($cinema) {
    //                 if ($cinema->type === 'private' && $value === 'public') {
    //                     $fail('A Private Cinema cannot have a Public Screen.');
    //                 }
    //             },
    //         ],
    //         // ... other rules
    //     ]);

    //     Screen::create($request->all());
    //     return redirect()->route('screens.index')->with('success', "Screen '{$request->name}' created successfully!");
    // }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'cinema_id'   => 'required|exists:cinemas,id',
            'screen_type' => 'required|in:public,private',
            'room_type'   => 'nullable|required_if:screen_type,private|in:2p,4p,6p',
            'capacity'    => 'nullable|required_if:screen_type,public|integer|min:1',
            'status'      => 'required|boolean',
            'price'       => 'nullable|required_if:screen_type,private|numeric', // Changed from room_price to price
        ]);

        // Create the Screen
        $screen = Screen::create($validated);

        // If it's private, save the price to the related table
        if ($request->screen_type === 'private' && $request->filled('price')) {
            $screen->privateRoomPrice()->create([
                'price' => $request->price
            ]);
        }

        return redirect()->route('screens.index')->with('success', 'Screen created successfully!');
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
    public function edit(Screen $screen)
    {
        $cinemas = Cinema::all();
        return view('admin.screens.edit', compact('screen', 'cinemas'));
    }

    /**
     * Update the specified resource in storage.
     */

    // public function update(Request $request, Screen $screen)
    // {
    //     // 1. Validation
    //     $validated = $request->validate([
    //         'cinema_id'   => 'required|exists:cinemas,id',
    //         'name'        => 'required|string|max:255',
    //         'screen_type' => 'required|in:public,private',
    //         'room_type'   => 'required_if:screen_type,private|nullable|in:2p,4p,6p',
    //         'capacity'    => 'required_if:screen_type,public|nullable|integer|min:1',
    //         'status'      => 'required|boolean',
    //     ]);

    //     // 2. Logic: Enforce Cinema/Screen Type Consistency
    //     $cinema = Cinema::findOrFail($request->cinema_id);
    //     if ($cinema->type === 'private' && $request->screen_type === 'public') {
    //         return back()
    //             ->withErrors(['screen_type' => 'A Private Cinema cannot contain a Public Screen.'])
    //             ->withInput();
    //     }

    //     // 3. Logic: Clear irrelevant fields based on type
    //     // If it's private, capacity should be null. If public, room_type should be null.
    //     if ($validated['screen_type'] === 'private') {
    //         $validated['capacity'] = null;
    //     } else {
    //         $validated['room_type'] = null;
    //     }

    //     // 4. Update the record
    //     $screen->update($validated);

    //     // 5. Redirect with success message
    //     return redirect()
    //         ->route('screens.index')
    //         ->with('success', "Screen '{$screen->name}' updated successfully!");
    // }

    public function update(Request $request, Screen $screen)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'cinema_id'   => 'required|exists:cinemas,id',
            'screen_type' => 'required|in:public,private',
            'room_type'   => 'nullable|required_if:screen_type,private|in:2p,4p,6p',
            'capacity'    => 'nullable|required_if:screen_type,public|integer|min:1',
            'status'      => 'required|boolean',
            'price'       => 'nullable|required_if:screen_type,private|numeric', // Matches form name="price"
        ]);

        // Update Screen details
        $screen->update($validated);

        if ($request->screen_type === 'private') {
            // Sync the private price
            $screen->privateRoomPrice()->updateOrCreate(
                ['screen_id' => $screen->id],
                ['price' => $request->price]
            );
        } else {
            // Delete price if type was changed to public
            $screen->privateRoomPrice()->delete();
        }

        return redirect()->route('screens.index')->with('success', 'Screen updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $screen = Screen::findOrFail($id);
        $screen->delete();

        return redirect()->route('screens.index')->with('success', "Screen '{$screen->name}' deleted successfully!");
    }
}
