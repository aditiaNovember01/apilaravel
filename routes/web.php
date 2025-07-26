<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\BarberController as AdminBarberController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'admin.barbers.index' : 'login');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [\App\Http\Controllers\Admin\UserController::class, 'profile'])->name('profile');
    Route::resource('barbers', AdminBarberController::class);
    Route::get('barbers/{id}/schedule', [AdminBarberController::class, 'schedule'])->name('barbers.schedule');
    Route::resource('users', AdminUserController::class);
    Route::resource('bookings', AdminBookingController::class);
    Route::get('bookings/{id}/proof-of-payment', [AdminBookingController::class, 'showProofOfPayment'])->name('bookings.proof-of-payment');
    Route::patch('bookings/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::patch('bookings/{id}/payment-status', [AdminBookingController::class, 'updatePaymentStatus'])->name('bookings.update-payment-status');
    Route::get('bookings/available-barbers', [AdminBookingController::class, 'getAvailableBarbers'])->name('bookings.available-barbers');
    // Tambah route laporan
    Route::get('reports/booking', [\App\Http\Controllers\Admin\ReportController::class, 'bookingReport'])->name('reports.booking');
    Route::get('reports/finance', [\App\Http\Controllers\Admin\ReportController::class, 'financeReport'])->name('reports.finance');
});
