<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::with('bookings.user')->get();
        return view('admin.barbers.index', compact('barbers'));
    }

    public function create()
    {
        return view('admin.barbers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specialty' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('barbers', 'public');
        }
        Barber::create($validated);
        return redirect()->route('admin.barbers.index')->with('success', 'Barber created successfully.');
    }

    public function show($id)
    {
        $barber = Barber::findOrFail($id);
        return view('admin.barbers.show', compact('barber'));
    }

    public function edit($id)
    {
        $barber = Barber::findOrFail($id);
        return view('admin.barbers.edit', compact('barber'));
    }

    public function update(Request $request, $id)
    {
        $barber = Barber::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specialty' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('barbers', 'public');
        } else {
            $validated['photo'] = $barber->photo;
        }
        $barber->update($validated);
        return redirect()->route('admin.barbers.index')->with('success', 'Barber updated successfully.');
    }

    public function destroy($id)
    {
        $barber = Barber::findOrFail($id);
        $barber->delete();
        return redirect()->route('admin.barbers.index')->with('success', 'Barber deleted successfully.');
    }

    public function schedule($id)
    {
        $barber = Barber::with(['bookings.user'])->findOrFail($id);

        // Get bookings for the next 7 days
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(7);

        // Tampilkan semua booking (confirmed dan done) untuk jadwal lengkap
        $bookings = $barber->bookings()
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->whereIn('status', ['confirmed', 'done'])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get()
            ->groupBy('booking_date');

        return view('admin.barbers.schedule', compact('barber', 'bookings', 'startDate', 'endDate'));
    }
}
