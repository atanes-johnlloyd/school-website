<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\ClassRoom;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * Overview: attendance summary for every class the student is enrolled in.
     */
    public function index(Request $request)
    {
        $student = $request->user()->student;
        abort_unless($student, 403);

        $activeTerm = Term::where('is_active', true)->first();

        $classrooms = $student->classroom()
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->with(['subject:id,code,name', 'section:id,name', 'teacher.user:id,name'])
            ->get();

        $rows = $classrooms->map(function (ClassRoom $classroom) use ($student) {
            $records = AttendanceRecord::query()
                ->forClass($classroom->id)
                ->forStudent($student->id)
                ->get(['status']);

            return array_merge([
                'class_id'     => $classroom->id,
                'subject'      => $classroom->subject?->name,
                'subject_code' => $classroom->subject?->code,
                'section'      => $classroom->section?->name,
                'teacher'      => $classroom->teacher?->user?->name,
            ], $this->summarize($records));
        });

        $payload = [
            'active_term' => $activeTerm?->name,
            'classes'     => $rows,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/Attendance/Index', $payload);
    }

    /**
     * Per-class detail — every session recorded with status.
     */
    public function show(Request $request, ClassRoom $classroom)
    {
        $student = $request->user()->student;
        abort_unless($student, 403);
        abort_unless($classroom->hasStudent($request->user()), 403);

        $records = AttendanceRecord::query()
            ->forClass($classroom->id)
            ->forStudent($student->id)
            ->orderByDesc('attendance_date')
            ->get()
            ->map(fn (AttendanceRecord $r) => [
                'date'   => $r->attendance_date->toDateString(),
                'status' => $r->status,
                'notes'  => $r->notes,
            ]);

        $payload = [
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
                'teacher' => $classroom->teacher?->user?->name,
            ],
            'summary' => $this->summarize($records),
            'records' => $records,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/Attendance/Show', $payload);
    }

    protected function summarize($records): array
    {
        $total   = $records->count();
        $present = $records->where('status', 'present')->count();
        $late    = $records->where('status', 'late')->count();
        $absent  = $records->where('status', 'absent')->count();
        $excused = $records->where('status', 'excused')->count();

        $denominator = $total - $excused;
        $rate = $denominator > 0
            ? round((($present + $late) / $denominator) * 100, 2)
            : null;

        return [
            'total_sessions'  => $total,
            'present'         => $present,
            'absent'          => $absent,
            'late'            => $late,
            'excused'         => $excused,
            'attendance_rate' => $rate,
        ];
    }
}
