<?php

namespace App\Services;

use App\Models\BookingSeat;
use App\Models\SeatLock;
use App\Models\Showtime;

class SeatAvailabilityService
{
    public function getSeatStatuses(Showtime $showtime): array
    {
        SeatLock::where('showtime_id', $showtime->id)
            ->where('expires_at', '<=', now())
            ->delete();

        $bookedSeatIds = BookingSeat::whereHas('booking', function ($q) use ($showtime) {
            $q->where('showtime_id', $showtime->id)
                ->where(function ($query) {
                    $query->whereIn('status', ['paid'])
                        ->orWhere(function ($pendingWithPayment) {
                            $pendingWithPayment->where('status', 'pending')
                                ->whereHas('payment');
                        });
                });
        })->pluck('seat_id')->all();

        $lockedSeatIds = SeatLock::where('showtime_id', $showtime->id)
            ->where('expires_at', '>', now())
            ->pluck('seat_id')
            ->all();

        $statuses = [];

        foreach ($bookedSeatIds as $seatId) {
            $statuses[$seatId] = 'booked';
        }

        foreach ($lockedSeatIds as $seatId) {
            if (! isset($statuses[$seatId])) {
                $statuses[$seatId] = 'locked';
            }
        }

        return $statuses;
    }

    public function get(Showtime $showtime)
    {
        $showtime->load('screen.seatRows.seats');
        $statuses = $this->getSeatStatuses($showtime);

        return $showtime->screen->seatRows->map(function ($row) use ($statuses) {
            return [
                'row' => $row,
                'seats' => $row->seats->map(function ($seat) use ($statuses) {
                    return [
                        'model' => $seat,
                        'status' => $statuses[$seat->id] ?? 'available',
                    ];
                }),
            ];
        });
    }
}
