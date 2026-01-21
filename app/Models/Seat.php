<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['seat_row_id', 'seat_number'];

    public function row()
    {
        return $this->belongsTo(SeatRow::class, 'seat_row_id');
    }
}
