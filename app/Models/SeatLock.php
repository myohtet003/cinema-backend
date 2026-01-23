<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatLock extends Model
{
    //
    protected $fillable = [
        'showtime_id',
        'seat_id',
        'user_id',
        'expires_at',
    ];
}
