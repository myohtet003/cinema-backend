<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cinema;
use Illuminate\Http\Request;

class CinemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Remove .get() and call paginate() directly
        $cinemas = Cinema::latest()->paginate(5);

        return view('admin.cinemas.index', compact('cinemas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cinemas.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'type' => 'required|in:public,private,mixed',
            'status' => 'boolean',
        ]);

        Cinema::create($validated);

        return redirect()->route('cinemas.index')->with('success', 'Cinema created successfully!');
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
    public function edit(Cinema $cinema)
    {
        // $cinema is automatically fetched by Laravel's Route Model Binding
        return view('admin.cinemas.edit', compact('cinema'));
    }

    public function update(Request $request, Cinema $cinema)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'type' => 'required|in:public,private,mixed',
            'status' => 'boolean',
        ]);

        $cinema->update($validated);

        return redirect()->route('cinemas.index')->with('success', 'Cinema updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cinema = Cinema::findOrFail($id);
        $cinema->delete();

        return redirect()->route('cinemas.index')->with('success', 'Cinema deleted successfully!');
    }
}
