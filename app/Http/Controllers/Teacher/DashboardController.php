<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacher = $request->user()->teacher;

        if (! $teacher) {
            abort(403, 'No teacher profile linked to this account.');
        }

        $activeTerm = Term::where('is_active', true)->first();

        $stats = [
            'classes' => ClassRoom::where('teacher_id', $teacher->id)
                ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
                ->count(),
        ];

        return Inertia::render('Teacher/Dashboard', [
            'stats'      => $stats,
            'activeTerm' => $activeTerm?->name,
        ]);
    }
}