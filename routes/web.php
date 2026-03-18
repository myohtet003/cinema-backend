<?php

use App\Http\Controllers\Admin\CinemaController;
use App\Http\Controllers\Admin\MovieController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\ScreenController;
use App\Http\Controllers\Admin\SeatRowController;
use App\Http\Controllers\Admin\ShowtimeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Booking;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'home'])->name('home');

Route::get('/movie/{id}', [UserController::class, 'showMovie'])->name('movie.show');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cinemas', [UserController::class, 'index'])->name('cinemas');
Route::get('/schedule/{cinema}', [UserController::class, 'showSchedule'])->name('schedule.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('cinemas', CinemaController::class);
    Route::resource('screens', ScreenController::class);
    Route::resource('screens.seat_rows', SeatRowController::class);
    Route::resource('movies', MovieController::class);
    Route::resource('showtimes', ShowtimeController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('payment_methods', PaymentMethodController::class);

    Route::get('/payments/{booking}/create', [PaymentController::class, 'paymentPage'])->name('payments.create');

    Route::post('payment/process', [PaymentController::class, 'store'])->name('payments.store');
});
// Ensure this matches your controller path

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::patch('/admin/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('admin.bookings.approve');
    Route::patch('/admin/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('admin.bookings.reject');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/bookings/private', [BookingController::class, 'storePrivate'])->name('bookings.storePrivate');
});

require __DIR__ . '/auth.php';
