<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $barbers = Barber::where('status', 'active')->get();
        return view('admin.bookings.create', compact('users', 'barbers'));
    }

    public function getAvailableBarbers(Request $request)
    {
        $date = $request->input('date');
        $time = $request->input('time');

        if (!$date || !$time) {
            return response()->json(['barbers' => []]);
        }

        $availableBarbers = Barber::getAvailableBarbers($date, $time);

        return response()->json([
            'barbers' => $availableBarbers->map(function ($barber) {
                return [
                    'id' => $barber->id,
                    'name' => $barber->name,
                    'specialty' => $barber->specialty
                ];
            })
        ]);
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

        // Check if barber is available at the requested time
        $barber = Barber::find($validated['barber_id']);
        if (!$barber->isAvailableAt($validated['booking_date'], $validated['booking_time'])) {
            return back()->withErrors(['barber_id' => 'Barber tidak tersedia pada waktu yang dipilih.'])->withInput();
        }

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

        // Check if barber is available at the requested time (exclude current booking)
        $barber = Barber::find($validated['barber_id']);
        $conflictingBooking = $barber->bookings()
            ->where('id', '!=', $booking->id)
            ->where('booking_date', $validated['booking_date'])
            ->where('booking_time', $validated['booking_time'])
            ->whereIn('status', ['confirmed', 'done'])
            ->first();

        if ($conflictingBooking) {
            return back()->withErrors(['barber_id' => 'Barber tidak tersedia pada waktu yang dipilih.'])->withInput();
        }

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

    public function showProofOfPayment($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking->proof_of_payment) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }

        $path = storage_path('app/public/' . $booking->proof_of_payment);

        if (!file_exists($path)) {
            abort(404, 'File bukti pembayaran tidak ditemukan');
        }

        return response()->file($path);
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,done,cancelled'
        ]);

        $booking->update(['status' => $request->status]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah menjadi ' . ucfirst($request->status),
                'status' => $request->status
            ]);
        }

        return redirect()->back()->with('success', 'Status berhasil diubah');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|in:unpaid,paid'
        ]);

        $booking->update(['payment_status' => $request->payment_status]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status pembayaran berhasil diubah menjadi ' . ucfirst($request->payment_status),
                'payment_status' => $request->payment_status
            ]);
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diubah');
    }
}
