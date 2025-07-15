<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BarberController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\AuthController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('barbers', BarberController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('bookings', BookingController::class);
Route::post('login', [AuthController::class, 'login']);
