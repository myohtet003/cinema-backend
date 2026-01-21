<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatRow extends Model
{
    protected $fillable = ['screen_id', 'row_name', 'price'];

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
