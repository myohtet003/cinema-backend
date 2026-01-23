<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->get();
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'required|string|min:8|max:20', // Enforces at least 8 characters
            'status' => 'required|boolean',
            'remark' => 'nullable|string',
            'photo'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('payment-methods', 'public');
            $validated['photo'] = $path;
        }

        PaymentMethod::create($validated);

        return redirect()->route('payment_methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone'  => 'required|string|min:8|max:20',
            'status' => 'required|boolean',
            'remark' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($paymentMethod->photo) {
                Storage::disk('public')->delete($paymentMethod->photo);
            }

            $path = $request->file('photo')->store('payment-methods', 'public');
            $validated['photo'] = $path;
        }

        $paymentMethod->update($validated);

        return redirect()->route('payment_methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->photo) {
            Storage::disk('public')->delete($paymentMethod->photo);
        }

        $paymentMethod->delete();

        return redirect()->route('payment_methods.index')
            ->with('success', 'Payment method deleted.');
    }
}
