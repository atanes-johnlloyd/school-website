<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\AssignmentSubmission;
use App\Models\AttendanceRecord;
use App\Models\Enrollment;
use App\Models\EntranceExam;
use App\Models\EntranceExamResult;
use App\Models\Grade;
use App\Models\Klass;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'track_id'       => ['nullable', 'integer', 'exists:tracks,id'],
        ]);

        $activeYear   = SchoolYear::where('is_active', true)->first();
        $schoolYearId = $validated['school_year_id'] ?? $activeYear?->id;
        $trackId      = $validated['track_id'] ?? null;

        return response()->json([
            'school_year_id' => $schoolYearId,
            'track_id'       => $trackId,
            'school_years'   => SchoolYear::select('id', 'label')->orderByDesc('label')->get(),
            'tracks'         => Track::select('id', 'code', 'name')->orderBy('name')->get(),
            'data'           => [
                'applicant_stats'   => $this->applicantStats($schoolYearId, $trackId),
                'student_stats'     => $this->studentStats($schoolYearId),
                'teacher_stats'     => $this->teacherStats(),
                'section_stats'     => $this->sectionStats($schoolYearId, $trackId),
                'exam_stats'        => $this->examStats($schoolYearId, $trackId),
                'exam_summary'      => $this->examResultSummary($schoolYearId),
                'monthly_trend'     => $this->monthlyTrend($schoolYearId, $trackId),
                'strand_distribution' => $this->strandDistribution($schoolYearId, $trackId),
                'section_occupancy' => $this->sectionOccupancy($schoolYearId, $trackId),
                'grade_summary'     => $this->gradeSummary($schoolYearId),
                'attendance_summary' => $this->attendanceSummary($schoolYearId),
                'recent_applications' => $this->recentApplications($schoolYearId, $trackId),
                'recent_enrollments'  => $this->recentEnrollments($schoolYearId, $trackId),
                'upcoming_exams'      => $this->upcomingExams($schoolYearId, $trackId),
            ],
        ]);
    }

    // ─── Applicant Stats ──────────────────────────────────

    protected function applicantStats(?int $schoolYearId, ?int $trackId): array
    {
        $query = Applicant::query()
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when($trackId, fn ($q) => $q->whereHas('strand', fn ($sq) => $sq->where('track_id', $trackId)));

        $total   = (clone $query)->count();
        $byStatus = (clone $query)
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'total'    => $total,
            'pending'            => $byStatus['pending'] ?? 0,
            'under_review'       => $byStatus['under_review'] ?? 0,
            'approved'           => $byStatus['approved'] ?? 0,
            'rejected'           => $byStatus['rejected'] ?? 0,
            'enrolled'           => $byStatus['enrolled'] ?? 0,
            'needs_resubmission' => $byStatus['needs_resubmission'] ?? 0,
        ];
    }

    // ─── Student Stats ────────────────────────────────────

    protected function studentStats(?int $schoolYearId): array
    {
        $total     = Student::count();
        $active    = Student::where('status', 'active')->count();
        $graduated = Student::where('status', 'graduated')->count();
        $dropped   = Student::where('status', 'dropped_out')->count();
        $transferred = Student::where('status', 'transferred_out')->count();

        $enrolled = $schoolYearId
            ? Enrollment::where('school_year_id', $schoolYearId)
                ->where('status', 'enrolled')
                ->distinct('student_id')
                ->count('student_id')
            : 0;

        return compact('total', 'active', 'graduated', 'dropped', 'transferred', 'enrolled');
    }

    // ─── Teacher Stats ────────────────────────────────────

    protected function teacherStats(): array
    {
        return [
            'total'    => Teacher::count(),
            'active'   => Teacher::where('is_active', true)->count(),
            'inactive' => Teacher::where('is_active', false)->count(),
        ];
    }

    // ─── Section Stats ────────────────────────────────────

    protected function sectionStats(?int $schoolYearId, ?int $trackId): array
    {
        $query = Section::query()
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when($trackId, fn ($q) => $q->whereHas('strand', fn ($sq) => $sq->where('track_id', $trackId)));

        $totalSections = (clone $query)->count();
        $totalCapacity = (clone $query)->sum('max_capacity');

        $sectionIds = (clone $query)->pluck('id');

        $enrolled = Enrollment::whereIn('section_id', $sectionIds)
            ->where('status', 'enrolled')
            ->count();

        $occupancy = $totalCapacity > 0 ? round(($enrolled / $totalCapacity) * 100, 1) : 0;

        return [
            'total_sections' => $totalSections,
            'total_capacity' => $totalCapacity,
            'enrolled'       => $enrolled,
            'available'      => max(0, $totalCapacity - $enrolled),
            'occupancy_rate' => $occupancy,
        ];
    }

    // ─── Exam Stats ───────────────────────────────────────

    protected function examStats(?int $schoolYearId, ?int $trackId): array
    {
        $exams = EntranceExam::query()
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when($trackId, fn ($q) => $q->where(fn ($sq) => $sq->whereNull('track_id')->orWhere('track_id', $trackId)))
            ->get();

        return [
            'total'     => $exams->count(),
            'upcoming'  => $exams->where('computed_status', 'Upcoming')->count(),
            'ongoing'   => $exams->where('computed_status', 'Ongoing')->count(),
            'completed' => $exams->where('computed_status', 'Completed')->count(),
            'cancelled' => $exams->where('status', 'Cancelled')->count(),
        ];
    }

    // ─── Exam Result Summary ──────────────────────────────

    protected function examResultSummary(?int $schoolYearId): array
    {
        $query = EntranceExamResult::query()
            ->when($schoolYearId, fn ($q) => $q->whereHas('exam', fn ($sq) => $sq->where('school_year_id', $schoolYearId)));

        $total   = (clone $query)->count();
        $passed  = (clone $query)->where('result', 'Passed')->count();
        $failed  = (clone $query)->where('result', 'Failed')->count();
        $absent  = (clone $query)->where('result', 'Absent')->count();
        $pending = (clone $query)->where('result', 'Pending')->count();

        $passRate = ($passed + $failed) > 0
            ? round(($passed / ($passed + $failed)) * 100, 1)
            : 0;

        return compact('total', 'passed', 'failed', 'absent', 'pending', 'passRate');
    }

    // ─── Monthly Trend ────────────────────────────────────

    protected function monthlyTrend(?int $schoolYearId, ?int $trackId): array
    {
        $query = Applicant::query()
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when($trackId, fn ($q) => $q->whereHas('strand', fn ($sq) => $sq->where('track_id', $trackId)));

        return $query
            ->select('submitted_at')
            ->orderBy('submitted_at')
            ->get()
            ->groupBy(fn (Applicant $a) => $a->submitted_at?->format('Y-m'))
            ->filter(fn ($group, $key) => $key !== '')
            ->map(fn ($group, $key) => [
                'month' => $key,
                'count' => $group->count(),
            ])
            ->values()
            ->toArray();
    }

    // ─── Strand Distribution ──────────────────────────────

    protected function strandDistribution(?int $schoolYearId, ?int $trackId): array
    {
        $query = Applicant::query()
            ->join('strands', 'strands.id', '=', 'applicants.strand_id')
            ->when($schoolYearId, fn ($q) => $q->where('applicants.school_year_id', $schoolYearId))
            ->when($trackId, fn ($q) => $q->where('strands.track_id', $trackId));

        return $query
            ->selectRaw('strands.name as strand, COUNT(*) as count')
            ->groupBy('strands.id', 'strands.name')
            ->orderByDesc('count')
            ->get()
            ->map(fn ($r) => ['strand' => $r->strand, 'count' => (int) $r->count])
            ->toArray();
    }

    // ─── Section Occupancy ────────────────────────────────

    protected function sectionOccupancy(?int $schoolYearId, ?int $trackId): array
    {
        $sections = Section::query()
            ->with(['strand:id,name', 'schoolYear:id,label'])
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when($trackId, fn ($q) => $q->whereHas('strand', fn ($sq) => $sq->where('track_id', $trackId)))
            ->orderBy('name')
            ->get();

        return $sections->map(function (Section $s) {
            $enrolled = Enrollment::where('section_id', $s->id)
                ->where('status', 'enrolled')
                ->count();

            $percentage = $s->max_capacity > 0
                ? round(($enrolled / $s->max_capacity) * 100, 1)
                : 0;

            return [
                'section'    => $s->name,
                'strand'     => $s->strand?->name,
                'capacity'   => $s->max_capacity,
                'enrolled'   => $enrolled,
                'percentage' => $percentage,
            ];
        })->toArray();
    }

    // ─── Grade Summary ────────────────────────────────────

    protected function gradeSummary(?int $schoolYearId): array
    {
        $grades = Grade::query()
            ->when($schoolYearId, fn ($q) => $q->whereHas('classroom.term', fn ($sq) => $sq->where('school_year_id', $schoolYearId)))
            ->whereNotNull('final_grade')
            ->get();

        $total = $grades->count();
        if ($total === 0) {
            return [
                'total_graded' => 0,
                'passing'      => 0,
                'failing'      => 0,
                'average'      => null,
                'passing_rate' => null,
            ];
        }

        $passing  = $grades->where('final_grade', '>=', 75)->count();
        $failing  = $total - $passing;
        $average  = round($grades->avg('final_grade'), 2);
        $passRate = round(($passing / $total) * 100, 1);

        return [
            'total_graded' => $total,
            'passing'      => $passing,
            'failing'      => $failing,
            'average'      => $average,
            'passing_rate' => $passRate,
        ];
    }

    // ─── Attendance Summary ───────────────────────────────

    protected function attendanceSummary(?int $schoolYearId): array
    {
        $query = AttendanceRecord::query()
            ->when($schoolYearId, fn ($q) => $q->whereHas('classroom.term', fn ($sq) => $sq->where('school_year_id', $schoolYearId)));

        return [
            'total_records' => (clone $query)->count(),
            'present'       => (clone $query)->where('status', 'present')->count(),
            'absent'        => (clone $query)->where('status', 'absent')->count(),
            'late'          => (clone $query)->where('status', 'late')->count(),
            'excused'       => (clone $query)->where('status', 'excused')->count(),
        ];
    }

    // ─── Recent Applications ──────────────────────────────

    protected function recentApplications(?int $schoolYearId, ?int $trackId, int $limit = 10): array
    {
        return Applicant::query()
            ->with(['strand:id,name'])
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when($trackId, fn ($q) => $q->whereHas('strand', fn ($sq) => $sq->where('track_id', $trackId)))
            ->latest('submitted_at')
            ->limit($limit)
            ->get()
            ->map(fn (Applicant $a) => [
                'reference_number' => $a->reference_number,
                'name'             => $a->full_name,
                'strand'           => $a->strand?->name,
                'status'           => $a->status,
                'submitted_at'     => $a->submitted_at?->toIso8601String(),
            ])
            ->toArray();
    }

    // ─── Recent Enrollments ───────────────────────────────

    protected function recentEnrollments(?int $schoolYearId, ?int $trackId, int $limit = 10): array
    {
        return Enrollment::query()
            ->with(['student.user:id,name', 'section:id,name,strand_id', 'section.strand:id,name'])
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when($trackId, fn ($q) => $q->whereHas('section.strand', fn ($sq) => $sq->where('track_id', $trackId)))
            ->where('status', 'enrolled')
            ->latest('enrolled_at')
            ->limit($limit)
            ->get()
            ->map(fn (Enrollment $e) => [
                'student_name' => $e->student?->user?->name,
                'section'      => $e->section?->name,
                'strand'       => $e->section?->strand?->name,
                'enrolled_at'  => $e->enrolled_at?->toIso8601String(),
            ])
            ->toArray();
    }

    // ─── Upcoming Exams ───────────────────────────────────

    protected function upcomingExams(?int $schoolYearId, ?int $trackId, int $limit = 5): array
    {
        return EntranceExam::query()
            ->with('track:id,name')
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when($trackId, fn ($q) => $q->where(fn ($sq) => $sq->whereNull('track_id')->orWhere('track_id', $trackId)))
            ->where('status', '!=', 'Cancelled')
            ->whereRaw("CONCAT(exam_date, ' ', exam_time) > ?", [now()])
            ->orderBy('exam_date')
            ->orderBy('exam_time')
            ->limit($limit)
            ->get()
            ->map(fn (EntranceExam $e) => [
                'id'              => $e->id,
                'exam_name'       => $e->exam_name,
                'exam_date'       => $e->exam_date?->toDateString(),
                'exam_time'       => $e->exam_time,
                'venue'           => $e->venue,
                'track'           => $e->track?->name,
                'applicant_count' => $e->results()->count(),
            ])
            ->toArray();
    }
}