<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\MarkAttendanceRequest;
use App\Models\AttendanceRecord;
use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Term;

class AttendanceController extends Controller
{
    /**
     * List all attendance sessions (dates) for a class.
     */
    public function index(Request $request, ClassRoom $classroom)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $sessions = AttendanceRecord::query()
            ->forClass($classroom->id)
            ->select(
                'attendance_date',
                DB::raw("SUM(status = 'present') as present"),
                DB::raw("SUM(status = 'absent')  as absent"),
                DB::raw("SUM(status = 'late')    as late"),
                DB::raw("SUM(status = 'excused') as excused"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('attendance_date')
            ->orderByDesc('attendance_date')
            ->get()
            ->map(fn ($row) => [
                'date'    => $row->attendance_date instanceof \Carbon\Carbon
                                ? $row->attendance_date->toDateString()
                                : (string) $row->attendance_date, 
                'present' => (int) $row->present,
                'absent'  => (int) $row->absent,
                'late'    => (int) $row->late,
                'excused' => (int) $row->excused,
                'total'   => (int) $row->total,
            ]);

        $payload = [
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
            ],
            'sessions'          => $sessions,
            'enrolled_students' => $classroom->students()->count(),
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Teacher/Attendance/Index', $payload);
    }

    /**
     * Get the roster for a specific date, with any existing statuses.
     */
    public function session(Request $request, ClassRoom $classroom, string $date)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);
        abort_unless($this->isValidDate($date), 422);

        $students = $classroom->students()
            ->with('user:id,name')
            ->orderBy('lrn')
            ->get();

        $existing = AttendanceRecord::query()
            ->forClass($classroom->id)
            ->forDate($date)
            ->get()
            ->keyBy('student_id');

        $roster = $students->map(fn (Student $s) => [
            'id'     => $s->id,
            'name'   => $s->user?->name,
            'lrn'    => $s->lrn,
            'status' => $existing->get($s->id)?->status,
            'notes'  => $existing->get($s->id)?->notes,
        ]);

        $payload = [
            'classroom' => [
                'id'      => $classroom->id,
                'subject' => $classroom->subject?->name,
                'section' => $classroom->section?->name,
            ],
            'date'       => $date,
            'is_marked'  => $existing->isNotEmpty(),
            'students'   => $roster,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Teacher/Attendance/Session', $payload);
    }

    /**
     * Bulk-save attendance for a whole class on a date.
     */
    public function mark(MarkAttendanceRequest $request, ClassRoom $classroom, string $date)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);
        abort_unless($this->isValidDate($date), 422);

        // Security: every student must belong to this classroom
        $enrolledIds = $classroom->students()->pluck('students.id')->all();
        $submittedIds = collect($request->validated('records'))->pluck('student_id')->all();

        $invalid = array_diff($submittedIds, $enrolledIds);
        if (! empty($invalid)) {
            return response()->json([
                'message' => 'Some students do not belong to this class.',
                'invalid' => array_values($invalid),
            ], 422);
        }

        DB::transaction(function () use ($request, $classroom, $date) {
        foreach ($request->validated('records') as $r) {
            // Match by date-only using whereDate — immune to time-format mismatches
            $record = AttendanceRecord::query()
                ->where('class_id', $classroom->id)
                ->where('student_id', $r['student_id'])
                ->whereDate('attendance_date', $date)
                ->first();

            if ($record) {
                $record->update([
                    'status'    => $r['status'],
                    'notes'     => $r['notes'] ?? null,
                    'marked_by' => $request->user()->id,
                ]);
            } else {
                AttendanceRecord::create([
                    'class_id'        => $classroom->id,
                    'student_id'      => $r['student_id'],
                    'attendance_date' => $date,
                    'status'          => $r['status'],
                    'notes'           => $r['notes'] ?? null,
                    'marked_by'       => $request->user()->id,
                ]);
            }
        }
    });

        return response()->json([
            'message' => 'Attendance saved.',
            'date'    => $date,
            'count'   => count($submittedIds),
        ]);
    }

    /**
     * Per-student attendance history for this class.
     */
    public function studentHistory(Request $request, ClassRoom $classroom, Student $student)
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        // Ensure student belongs to the class
        $belongs = $classroom->students()->where('students.id', $student->id)->exists();
        abort_unless($belongs, 404);

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

        return response()->json([
            'student' => [
                'id'   => $student->id,
                'name' => $student->user?->name,
                'lrn'  => $student->lrn,
            ],
            'summary' => $this->summarize($records),
            'records' => $records,
        ]);
    }

    // ─── Helpers ───────────────────────────────────────

    protected function isValidDate(string $date): bool
    {
        return (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
            && strtotime($date) !== false;
    }

    protected function summarize($records): array
    {
        $total = $records->count();
        $present = $records->where('status', 'present')->count();
        $late    = $records->where('status', 'late')->count();
        $absent  = $records->where('status', 'absent')->count();
        $excused = $records->where('status', 'excused')->count();

        // Attendance rate = (present + late) / (total - excused)
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

    /**
     * Cross-class attendance overview — the teacher's landing hub.
     */
    public function overview(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 403);

        $activeTerm = Term::where('is_active', true)->first();

        $classrooms = ClassRoom::query()
            ->where('teacher_id', $teacher->id)
            ->when($activeTerm, fn ($q) => $q->where('term_id', $activeTerm->id))
            ->with([
                'subject:id,code,name',
                'section:id,name',
                'term:id,name',
                'schedules.room:id,code,name,building',
            ])
            ->orderBy('section_id')
            ->get();

        if ($classrooms->isEmpty()) {
            return Inertia::render('Teacher/DailyAttendance/Index', [
                'classrooms'      => [],
                'classroom'       => null,
                'date'            => null,
                'students'        => [],
                'stats'           => null,
                'weekly_sessions' => [],
                'today'           => now()->format('l, F j, Y'),
                'active_term'     => $activeTerm?->name,
            ]);
        }

        $selectedId = (int) $request->input('class_id', $classrooms->first()->id);
        $classroom  = $classrooms->firstWhere('id', $selectedId) ?? $classrooms->first();

        $date = $request->input('date', now()->toDateString());
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $date = now()->toDateString();
        }

        // Existing attendance for the selected date
        $existing = AttendanceRecord::query()
            ->forClass($classroom->id)
            ->forDate($date)
            ->get()
            ->keyBy('student_id');

        $students = $classroom->students()
            ->with('user:id,name')
            ->orderBy('lrn')
            ->get()
            ->map(fn ($s) => [
                'id'        => $s->id,
                'name'      => $s->user?->name,
                'lrn'       => $s->lrn,
                'sex'       => $s->sex,
                'status'    => $existing->get($s->id)?->status,
                'notes'     => $existing->get($s->id)?->notes,
                'marked_at' => $existing->get($s->id)?->updated_at?->toIso8601String(),
            ]);

        $byStatus = collect(['present', 'late', 'absent', 'excused'])
            ->mapWithKeys(fn ($k) => [$k => $existing->where('status', $k)->count()]);

        // Weekly trend (last 7 sessions with records)
        $weeklySessions = AttendanceRecord::query()
            ->forClass($classroom->id)
            ->select(
                'attendance_date',
                DB::raw("SUM(status = 'present') as present"),
                DB::raw("SUM(status = 'late')    as late"),
                DB::raw("SUM(status = 'absent')  as absent"),
                DB::raw("SUM(status = 'excused') as excused"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('attendance_date')
            ->orderByDesc('attendance_date')
            ->limit(7)
            ->get()
            ->map(fn ($r) => [
                'date'    => $r->attendance_date,
                'present' => (int) $r->present,
                'late'    => (int) $r->late,
                'absent'  => (int) $r->absent,
                'excused' => (int) $r->excused,
                'total'   => (int) $r->total,
            ])
            ->reverse()
            ->values();

        // Sessions this month (for the SF-2 footer counter)
        $monthlySessions = AttendanceRecord::query()
            ->forClass($classroom->id)
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->distinct('attendance_date')
            ->count('attendance_date');

        $firstSchedule = $classroom->schedules->first();

        return Inertia::render('Teacher/DailyAttendance/Index', [
            'classrooms' => $classrooms->map(fn ($c) => [
                'id'      => $c->id,
                'subject' => $c->subject?->name,
                'section' => $c->section?->name,
            ]),
            'classroom' => [
                'id'           => $classroom->id,
                'subject'      => $classroom->subject?->name,
                'subject_code' => $classroom->subject?->code,
                'section'      => $classroom->section?->name,
                'term'         => $classroom->term?->name,
                'room'         => $firstSchedule?->room?->name,
                'building'     => $firstSchedule?->room?->building,
                'schedule'     => $firstSchedule
                    ? substr($firstSchedule->time_start, 0, 5) . ' – ' . substr($firstSchedule->time_end, 0, 5)
                    : null,
            ],
            'date'    => $date,
            'students'=> $students,
            'stats'   => [
                'enrolled' => $students->count(),
                'present'  => (int) ($byStatus['present'] ?? 0),
                'late'     => (int) ($byStatus['late']    ?? 0),
                'excused'  => (int) ($byStatus['excused'] ?? 0),
                'absent'   => (int) ($byStatus['absent']  ?? 0),
                'unmarked' => max(0, $students->count() - $existing->count()),
                'marked'   => $existing->count(),
            ],
            'weekly_sessions'   => $weeklySessions,
            'monthly_sessions'  => $monthlySessions,
            'today'             => now()->format('l, F j, Y'),
            'active_term'       => $activeTerm?->name,
        ]);
    }
}