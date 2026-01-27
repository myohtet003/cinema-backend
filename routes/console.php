<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Showtime;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// This will run every night at midnight to delete old shows
Schedule::call(function () {
    Showtime::where('start_time', '<', now()->subDays(1))->delete();
})->daily();