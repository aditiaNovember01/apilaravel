<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BarberController as AdminBarberController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('barbers', AdminBarberController::class);
    Route::resource('users', AdminUserController::class);
    Route::resource('bookings', AdminBookingController::class);
});
