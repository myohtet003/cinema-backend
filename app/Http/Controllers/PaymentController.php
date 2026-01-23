<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function paymentPage(Booking $booking)
    {
        // Only allow unpaid bookings
        if ($booking->status !== 'pending') {
            return redirect()->route('showtimes.show', $booking->showtime_id)
                ->with('error', 'This booking is already paid or expired.');
        }

        return view('admin.payments.create', compact('booking'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|in:kbzpay,ayapay,wavepay',
            'transaction_id' => 'nullable|string',
            'amount' => 'required|integer|min:0',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking is already paid or expired.');
        }

        // Create payment
        $payment = $booking->payment()->create([
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'amount' => $request->amount,
            'status' => 'pending', // could update automatically after callback
        ]);

        // Optionally, mark booking as paid immediately for testing
        // $booking->update(['status' => 'paid']);

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Payment created! Complete your payment via selected method.');
    }
}
