<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Barber;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'barber'])->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $users = User::all();
        $barbers = Barber::all();
        return view('admin.bookings.create', compact('users', 'barbers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'barber_id' => 'required|exists:barbers,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
            'status' => 'required|in:pending,confirmed,done,cancelled',
            'amount' => 'required|numeric',
            'payment_status' => 'required|in:unpaid,paid',
            'proof_of_payment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('proof_of_payment')) {
            $validated['proof_of_payment'] = $request->file('proof_of_payment')->store('bookings', 'public');
        }
        Booking::create($validated);
        return redirect()->route('admin.bookings.index')->with('success', 'Booking created successfully.');
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'barber'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $users = User::all();
        $barbers = Barber::all();
        return view('admin.bookings.edit', compact('booking', 'users', 'barbers'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'barber_id' => 'required|exists:barbers,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
            'status' => 'required|in:pending,confirmed,done,cancelled',
            'amount' => 'required|numeric',
            'payment_status' => 'required|in:unpaid,paid',
            'proof_of_payment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->hasFile('proof_of_payment')) {
            $validated['proof_of_payment'] = $request->file('proof_of_payment')->store('bookings', 'public');
        } else {
            $validated['proof_of_payment'] = $booking->proof_of_payment;
        }
        $booking->update($validated);
        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
