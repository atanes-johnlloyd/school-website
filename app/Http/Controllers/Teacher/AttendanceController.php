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
                'date'    => $row->attendance_date,
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
}