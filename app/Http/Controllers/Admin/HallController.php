<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hall;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function index()
    {
        $halls = Hall::orderBy('created_at', 'desc')->get()->map(function ($hall) {
            $hall->updateStatusBasedOnLectures(); // Update status before returning
            return $hall;
        });
        return response()->json($halls);
    }

    public function store(Request $request)
    {
        $request->validate([
            'hall_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'building' => 'required|string|max:255',
            'floor' => 'required|integer|min:1',
            'equipment' => 'nullable|string',
        ]);

        $hall = Hall::create([
            'hall_name' => $request->hall_name,
            'capacity' => $request->capacity,
            'building' => $request->building,
            'floor' => $request->floor,
            'equipment' => $request->equipment,
            'status' => 'available',
        ]);

        return response()->json($hall, 201);
    }

    public function show(Hall $hall)
    {
        return response()->json($hall);
    }

    public function update(Request $request, Hall $hall)
    {
        $request->validate([
            'hall_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'building' => 'required|string|max:255',
            'floor' => 'required|integer|min:1',
            'equipment' => 'nullable|string',
        ]);

        $hall->update($request->all());
        return response()->json($hall);
    }

    public function destroy(Hall $hall)
    {
        $hall->delete();
        return response()->json(['message' => 'Hall deleted successfully']);
    }
}
