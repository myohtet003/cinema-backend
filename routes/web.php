<?php

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\ScreenController;
use App\Http\Controllers\Admin\SeatRowController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('cinemas', CinemaController::class);
    Route::resource('screens', ScreenController::class);
    Route::resource('screens.seat_rows', SeatRowController::class);
    Route::resource('movies', MovieController::class);
    Route::resource('showtimes', ShowtimeController::class);
});


require __DIR__.'/auth.php';
