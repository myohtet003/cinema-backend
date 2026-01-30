<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'type',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];


    public function screens()
    {
        return $this->hasMany(Screen::class);
    }

    // This allows you to get showtimes directly through screens
    public function showtimes()
    {
        return $this->hasManyThrough(Showtime::class, Screen::class);
    }
}
