<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarberController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegisterController;



Route::apiResource('barbers', BarberController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('bookings', BookingController::class);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);
