<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;

        if (! $student) {
            abort(403, 'No student profile linked to this account.');
        }

        $activeTerm = Term::where('is_active', true)->first();

        $stats = [
            'classes' => ClassRoom::whereHas('students', fn ($q) => $q->where('students.id', $student->id))
                ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
                ->count(),
        ];

        return Inertia::render('Student/Dashboard', [
            'stats'      => $stats,
            'activeTerm' => $activeTerm?->name,
        ]);
    }
}