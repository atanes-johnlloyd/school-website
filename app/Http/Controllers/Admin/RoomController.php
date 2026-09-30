<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    /* ═══════════════ INDEX — page shell ═══════════════ */
    public function index(): Response
    {
        return Inertia::render('Admin/Rooms/Index', [
            'buildings' => Room::whereNotNull('building')
                ->distinct()
                ->orderBy('building')
                ->pluck('building'),
        ]);
    }

    /* ═══════════════ LIST — JSON for the Vue table ═══════════════ */
    public function list(Request $request)
    {
        $validated = $request->validate([
            'type'     => ['nullable', 'in:regular,laboratory,workshop,lecture,computer_lab,science_lab'],
            'building' => ['nullable', 'string', 'max:255'],
            'search'   => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'  => ['nullable', 'in:code,name,building,capacity,type,is_active'],
            'sort_dir' => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'code';
        $sortDir = $validated['sort_dir'] ?? 'asc';

        $query = Room::query();

        if (! empty($validated['type'])) {
            $query->where('type', $validated['type']);
        }
        if (! empty($validated['building'])) {
            $query->where('building', $validated['building']);
        }
        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('code', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%")
                  ->orWhere('building', 'like', "%{$s}%");
            });
        }

        $query->orderBy($sortBy, $sortDir);

        $rooms = $query->paginate($validated['per_page'] ?? 15);

        return response()->json([
            'rooms' => $rooms,
            'filters' => [
                'type'     => $validated['type']     ?? null,
                'building' => $validated['building'] ?? null,
                'search'   => $validated['search']   ?? null,
            ],
            'sort' => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => [
                'total'    => Room::count(),
                'active'   => Room::where('is_active', true)->count(),
                'inactive' => Room::where('is_active', false)->count(),
            ],
        ]);
    }

    /* ═══════════════ STORE ═══════════════ */
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

        $validated['capacity'] = $validated['capacity'] ?? 40;
        $validated['is_active'] = $validated['is_active'] ?? true;

        $room = Room::create($validated);

        return response()->json(['room' => $room], 201);
    }

    /* ═══════════════ SHOW ═══════════════ */
    public function show(Room $room)
    {
        return response()->json(['room' => $room]);
    }

    /* ═══════════════ UPDATE ═══════════════ */
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

    /* ═══════════════ DESTROY ═══════════════ */
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