<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    public function index()
    {
        return response()->json(Barber::all());
    }

    public function show($id)
    {
        $barber = Barber::findOrFail($id);
        return response()->json($barber);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'photo' => 'nullable|string|max:255',
            'specialty' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        $barber = Barber::create($validated);
        return response()->json($barber, 201);
    }

    public function update(Request $request, $id)
    {
        $barber = Barber::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'photo' => 'nullable|string|max:255',
            'specialty' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:active,inactive',
        ]);
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
