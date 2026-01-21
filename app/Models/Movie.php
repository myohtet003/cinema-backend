<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
        'poster',
        'status'
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
