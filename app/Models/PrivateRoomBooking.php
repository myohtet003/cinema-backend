<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateRoomBooking extends Model
{
    protected $fillable = ['booking_id', 'screen_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }
}
