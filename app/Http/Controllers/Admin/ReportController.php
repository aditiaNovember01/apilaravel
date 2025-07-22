<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function bookingReport(Request $request)
    {
        $type = $request->get('type', 'daily'); // daily, monthly, yearly
        $date = $request->get('date', Carbon::today()->toDateString());

        if ($type === 'daily') {
            $bookings = Booking::with(['user', 'barber'])
                ->whereDate('booking_date', $date)
                ->get();
        } elseif ($type === 'monthly') {
            $bookings = Booking::with(['user', 'barber'])
                ->whereMonth('booking_date', Carbon::parse($date)->month)
                ->whereYear('booking_date', Carbon::parse($date)->year)
                ->get();
        } else { // yearly
            $bookings = Booking::with(['user', 'barber'])
                ->whereYear('booking_date', Carbon::parse($date)->year)
                ->get();
        }

        return view('admin.reports.booking', compact('bookings', 'type', 'date'));
    }

    public function financeReport(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today()->toDateString());

        if ($type === 'daily') {
            $bookings = Booking::where('payment_status', 'paid')
                ->whereDate('booking_date', $date)
                ->get();
        } elseif ($type === 'monthly') {
            $bookings = Booking::where('payment_status', 'paid')
                ->whereMonth('booking_date', Carbon::parse($date)->month)
                ->whereYear('booking_date', Carbon::parse($date)->year)
                ->get();
        } else { // yearly
            $bookings = Booking::where('payment_status', 'paid')
                ->whereYear('booking_date', Carbon::parse($date)->year)
                ->get();
        }

        $total = $bookings->sum('amount');
        return view('admin.reports.finance', compact('bookings', 'type', 'date', 'total'));
    }
}
