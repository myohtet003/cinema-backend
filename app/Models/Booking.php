<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $fillable = [
        'user_id',
        'showtime_id',
        'booking_type',
        'total_price',
        'status',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // For Public Bookings
    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'booking_seats');
    }

    // For Private Bookings
    public function privateRoom()
    {
        return $this->hasOne(PrivateRoomBooking::class);
    }
}
