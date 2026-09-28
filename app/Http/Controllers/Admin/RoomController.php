<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index()
    {
        return response()->json(['rooms' => Room::orderBy('code')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'      => ['required', 'string', 'max:20', 'unique:rooms,code'],
            'name'      => ['required', 'string', 'max:255'],
            'building'  => ['nullable', 'string', 'max:255'],
            'floor'     => ['nullable', 'string', 'max:20'],
            'capacity'  => ['nullable', 'integer', 'min:1', 'max:500'],
            'type'      => ['required', 'in:regular,laboratory,workshop,lecture,computer_lab,science_lab'],
            'is_active' => ['boolean'],
        ]);

        return response()->json(['room' => Room::create($validated)], 201);
    }

    public function show(Room $room)
    {
        return response()->json(['room' => $room]);
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'code'      => ['sometimes', 'string', 'max:20',
                           Rule::unique('rooms', 'code')->ignore($room->id)],
            'name'      => ['sometimes', 'string', 'max:255'],
            'building'  => ['nullable', 'string', 'max:255'],
            'floor'     => ['nullable', 'string', 'max:20'],
            'capacity'  => ['nullable', 'integer', 'min:1', 'max:500'],
            'type'      => ['sometimes', 'in:regular,laboratory,workshop,lecture,computer_lab,science_lab'],
            'is_active' => ['boolean'],
        ]);

        $room->update($validated);

        return response()->json(['room' => $room->fresh()]);
    }

    public function destroy(Room $room)
    {
        if ($room->schedules()->exists()) {
            return response()->json([
                'message' => 'Cannot delete: room is used in class schedules.',
            ], 422);
        }

        $room->delete();

        return response()->json(['message' => 'Room deleted.']);
    }
}