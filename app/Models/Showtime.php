<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    protected $fillable = [
        'movie_id',
        'screen_id',
        'show_date',
        'start_time',
        'end_time'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    // app/Models/Showtime.php
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    protected static function booted()
    {
        static::addGlobalScope('upcoming', function ($builder) {
            $builder->where('start_time', '>', now());
        });
    }
}

