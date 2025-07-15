<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::all();
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
            'photo' => 'nullable|string|max:255',
            'specialty' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
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
            'photo' => 'nullable|string|max:255',
            'specialty' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        $barber->update($validated);
        return redirect()->route('admin.barbers.index')->with('success', 'Barber updated successfully.');
    }

    public function destroy($id)
    {
        $barber = Barber::findOrFail($id);
        $barber->delete();
        return redirect()->route('admin.barbers.index')->with('success', 'Barber deleted successfully.');
    }
}
