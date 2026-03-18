<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
    use HasFactory;

    protected $fillable = [
        'cinema_id',
        'name',
        'screen_type',
        'room_type',
        'capacity',
        'status',
    ];

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }

    public function seatRows()
    {
        return $this->hasMany(SeatRow::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    public function privateRoomPrice()
    {
        return $this->hasOne(PrivateRoomPrice::class);
    }
}
