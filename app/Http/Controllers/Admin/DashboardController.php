<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\User;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $barbers_count = Barber::count();
        $users_count = User::count();
        $bookings_count = Booking::count();
        return view('admin.dashboard.index', compact('barbers_count', 'users_count', 'bookings_count'));
    }
}
