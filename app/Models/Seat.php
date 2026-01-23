<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['seat_row_id', 'seat_number'];

    public function seatRow()
    {
        return $this->belongsTo(SeatRow::class, 'seat_row_id');
    }

    public function locks()
    {
        return $this->hasMany(SeatLock::class);
    }

    // Check if a seat is currently held by someone
    public function isLocked()
    {
        return $this->locks()->where('expires_at', '>', now())->exists();
    } 
 
}
