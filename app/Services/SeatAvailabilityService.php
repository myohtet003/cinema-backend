<?php

namespace App\Services;

use App\Models\BookingSeat;
use App\Models\SeatLock;
use App\Models\Showtime;

class SeatAvailabilityService
{
    public function get(Showtime $showtime)
    {
        $showtime->load('screen.seatRows.seats');

        // Seats that are already PAID / CONFIRMED
        $bookedSeatIds = BookingSeat::whereHas('booking', function ($q) use ($showtime) {
            $q->where('showtime_id', $showtime->id)
                ->where(function ($query) {
                    $query->whereIn('status', ['paid', 'confirmed'])
                        ->orWhere(function ($pendingWithPayment) {
                            $pendingWithPayment->where('status', 'pending')
                                ->whereHas('payment');
                        });
                });
        })->pluck('seat_id')->toArray();

        // Seats that are TEMP LOCKED (not expired)
        $lockedSeatIds = SeatLock::where('showtime_id', $showtime->id)
            ->where('expires_at', '>', now())
            ->pluck('seat_id')
            ->toArray();

        return $showtime->screen->seatRows->map(function ($row) use ($bookedSeatIds, $lockedSeatIds) {
            return [
                'row' => $row,
                'seats' => $row->seats->map(function ($seat) use ($bookedSeatIds, $lockedSeatIds) {

                    if (in_array($seat->id, $bookedSeatIds)) {
                        $status = 'booked';
                    } elseif (in_array($seat->id, $lockedSeatIds)) {
                        $status = 'locked';
                    } else {
                        $status = 'available';
                    }

                    return [
                        'model' => $seat,
                        'status' => $status,
                    ];
                }),
            ];
        });
    }
}
