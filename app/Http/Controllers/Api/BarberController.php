<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::all()->map(function ($barber) {
            $barber->photo = $barber->photo ? asset('storage/' . $barber->photo) : null;
            return $barber;
        });
        return response()->json($barbers);
    }

    public function show($id)
    {
        $barber = Barber::findOrFail($id);
        $barber->photo = $barber->photo;
        return response()->json($barber);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,svg',
            'specialty' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        $data = $validated;
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('barbers', 'public');
        }
        $barber = Barber::create($data);
        return response()->json($barber, 201);
    }

    public function update(Request $request, $id)
    {
        $barber = Barber::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,svg',
            'specialty' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:active,inactive',
        ]);
        $data = $validated;
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('barbers', 'public');
        }
        $barber->update($validated);
        return response()->json($barber);
    }

    public function destroy($id)
    {
        $barber = Barber::findOrFail($id);
        $barber->delete();
        return response()->json(null, 204);
    }
}
