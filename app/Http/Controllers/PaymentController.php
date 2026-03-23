<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function paymentPage(Booking $booking)
    {
        if (auth()->user()->role !== 'admin' && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking->id)
                ->with('error', 'This booking is already paid or expired.');
        }

        $booking->load(['bookingSeats.seat.seatRow', 'showtime.movie', 'showtime.screen.cinema']);
        $paymentMethods = PaymentMethod::where('status', 1)->get();

        return view('admin.payments.create', compact('booking', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            // Validate that the ID exists in your payment_methods table
            'payment_method_id' => 'required|exists:payment_methods,id',
            'transaction_id' => 'required|string|min:6|unique:payments,transaction_id',
            'amount' => 'required|integer|min:0',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if (auth()->user()->role !== 'admin' && $booking->user_id !== auth()->id()) {
            abort(403);
        }

        // 1. Safety check
        if ($booking->status !== 'pending') {
            return back()->with('error', 'This booking is no longer available for payment.');
        }

        // 2. Create the payment record
        $payment = $booking->payment()->create([
            'payment_method_id' => $request->payment_method_id, // Store the ID relationship
            'transaction_id'    => $request->transaction_id,
            'amount'            => $request->amount,
            'status'            => 'pending',
        ]);

        // 3. Update the booking status 
        // Usually, we keep booking as 'pending' until the Admin approves the Transaction ID
        $booking->update(['status' => 'pending']);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Payment submitted successfully! Please wait for administrator verification.');
    }
}
