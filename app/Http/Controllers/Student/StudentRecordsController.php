<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentRecordsController extends Controller
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $student = $user->student;
        abort_unless($student, 403);

        // ─── Current enrollment + school info ─────────────
        $enrollment = Enrollment::with(['section.strand.track', 'schoolYear', 'section.adviser.user'])
            ->where('student_id', $student->id)
            ->where('status', 'enrolled')
            ->latest('enrolled_at')
            ->first();

        $section = $enrollment?->section;
        $strand  = $section?->strand;
        $track   = $strand?->track;

        // ─── Terms for selector ───────────────────────────
        $terms = Term::with('schoolYear:id,label')
            ->orderByDesc('start_date')
            ->get()
            ->map(fn ($t) => [
                'id'     => $t->id,
                'name'   => $t->name,
                'label'  => ($t->schoolYear?->label ? 'S.Y. ' . $t->schoolYear->label . ' • ' : '') . $t->name,
                'is_active' => (bool) $t->is_active,
            ]);

        $activeTerm = $terms->firstWhere('is_active', true) ?? $terms->first();

        // ─── Selected term (default: active) ──────────────
        $selectedTermId = (int) $request->input('term_id', $activeTerm['id'] ?? null);
        $selectedTerm   = $terms->firstWhere('id', $selectedTermId) ?? $activeTerm;

        // ─── COR data: classes this student is in, selected term ────
        $corClasses = ClassRoom::query()
            ->when($selectedTerm, fn ($q) => $q->where('term_id', $selectedTerm['id']))
            ->whereHas('students', fn ($q) => $q->where('students.id', $student->id))
            ->with([
                'subject:id,code,name,hours',
                'teacher.user:id,name',
                'schedules' => fn ($q) => $q->orderByRaw(
                    "FIELD(day_of_week, 'monday','tuesday','wednesday','thursday','friday','saturday')"
                )->orderBy('time_start'),
                'schedules.room:id,code,name',
            ])
            ->get()
            ->map(function (ClassRoom $c) {
                $schedules = $c->schedules;

                // Under the fixed-slot DepEd SHS schedule, every meeting of a subject
                // happens at the same time and room, only the day changes. Collapse
                // into a single line: "Mon, Tue, Wed, Thu" / "07:00 – 08:00" / "Room 101".
                // If times or rooms differ across days (rotation), fall back to a
                // bullet-separated list of the distinct values.
                $days = $schedules->pluck('day_of_week')->unique()->map(
                    fn ($d) => ucfirst($d)
                );

                $times = $schedules->map(
                    fn ($s) => substr($s->time_start, 0, 5) . ' – ' . substr($s->time_end, 0, 5)
                )->unique();

                $rooms = $schedules->map(fn ($s) => $s->room?->name)->filter()->unique();

                $dayShort = $schedules->pluck('day_of_week')->unique()->map(
                    fn ($d) => ucfirst(substr($d, 0, 3))
                );

                $collapsed = $times->count() === 1 && $rooms->count() <= 1;

                return [
                    'id'      => $c->id,
                    'code'    => $c->subject?->code,
                    'title'   => $c->subject?->name,
                    'units'   => $c->subject?->hours ? round($c->subject->hours / 40, 1) : 3.0,
                    'teacher' => $c->teacher?->user?->name,

                    'days'    => $schedules->isEmpty()
                        ? '—'
                        : ($collapsed ? $dayShort->implode(', ') : $days->implode(' • ')),

                    'time'    => $schedules->isEmpty()
                        ? '—'
                        : ($collapsed ? $times->first() : $times->implode(' • ')),

                    'room'    => $rooms->isEmpty()
                        ? '—'
                        : ($rooms->count() === 1 ? $rooms->first() : $rooms->implode(' / ')),
                ];
            });

        // ─── Grades for Form 138 (all terms, subject-level) ────
        $academicGrades = Grade::query()
            ->where('student_id', $student->id)
            ->with(['classroom.subject:id,code,name', 'classroom.term:id,name,school_year_id', 'classroom.term.schoolYear:id,label'])
            ->get()
            ->map(fn (Grade $g) => [
                'class_id'    => $g->class_id,
                'code'        => $g->classroom?->subject?->code,
                'title'       => $g->classroom?->subject?->name,
                'term'        => $g->classroom?->term?->name,
                'school_year' => $g->classroom?->term?->schoolYear?->label,
                'written_work'=> $g->written_work_score,
                'performance' => $g->performance_task_score,
                'exam'        => $g->quarterly_exam_score,
                'final'       => $g->final_grade,
                'remarks'     => $g->remarks,
                'is_finalized'=> (bool) $g->is_finalized,
            ]);

        // General weighted average from finalized grades
        $finalized = $academicGrades->whereNotNull('final');
        $gwa = $finalized->count() > 0
            ? round($finalized->avg('final'), 2)
            : null;

        // ─── Attendance monthly breakdown (all records) ────
        $rawAttendance = AttendanceRecord::query()
            ->where('student_id', $student->id)
            ->orderByDesc('attendance_date')
            ->get();

        $monthlyAttendance = $rawAttendance
            ->groupBy(fn ($r) => Carbon::parse($r->attendance_date)->format('Y-m'))
            ->map(function ($group, $key) {
                $date = Carbon::createFromFormat('Y-m', $key);
                return [
                    'month'       => $date->format('F Y'),
                    'schoolDays'  => $group->count(),
                    'presents'    => $group->where('status', 'present')->count(),
                    'absences'    => $group->where('status', 'absent')->count(),
                    'tardy'       => $group->where('status', 'late')->count(),
                    'excused'     => $group->where('status', 'excused')->count(),
                ];
            })
            ->sortKeysDesc()
            ->values();

        // ─── Attendance overall summary ────
        $totalRecords = $rawAttendance->count();
        $presents     = $rawAttendance->where('status', 'present')->count();
        $late         = $rawAttendance->where('status', 'late')->count();
        $excused      = $rawAttendance->where('status', 'excused')->count();
        $denominator  = $totalRecords - $excused;
        $rate = $denominator > 0 ? round((($presents + $late) / $denominator) * 100, 1) : null;

        $payload = [
            'student' => [
                'name'         => $user->name,
                'lrn'          => $student->lrn,
                'grade_level'  => $section?->grade_level ? 'Grade ' . $section->grade_level : null,
                'section'      => $section?->name,
                'adviser'      => $section?->adviser?->user?->name,
                'strand'       => $strand?->name,
                'track'        => $track?->name,
                'school_year'  => $enrollment?->schoolYear?->label,
                'status'       => $enrollment ? 'Officially Enrolled' : 'Not Enrolled',
            ],
            'terms'               => $terms,
            'selected_term_id'    => $selectedTerm['id'] ?? null,
            'cor_classes'         => $corClasses,
            'total_units'         => $corClasses->sum('units'),
            'academic_grades'     => $academicGrades,
            'general_average'     => $gwa,
            'monthly_attendance'  => $monthlyAttendance,
            'attendance_summary'  => [
                'total_records' => $totalRecords,
                'presents'      => $presents,
                'absences'      => $rawAttendance->where('status', 'absent')->count(),
                'tardy'         => $late,
                'excused'       => $excused,
                'rate'          => $rate,
            ],
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Student/StudentRecords/Index', $payload);
    }
}