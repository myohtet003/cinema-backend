<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateRoomPrice extends Model
{
    protected $fillable = ['screen_id', 'price'];

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }
}
