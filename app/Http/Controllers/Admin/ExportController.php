<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\ClassRoom;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Term;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    /**
     * Export class list as CSV.
     */
    public function classList(Request $request, ClassRoom $classroom): StreamedResponse
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $classroom->load(['subject', 'section', 'term', 'students.user']);

        $filename = sprintf(
            'class-list-%s-%s.csv',
            str_replace(' ', '-', $classroom->section?->name ?? 'section'),
            now()->format('Y-m-d')
        );

        return $this->csvResponse($filename, ['LRN', 'Last Name', 'First Name', 'Email'], function ($handle) use ($classroom) {
            foreach ($classroom->students as $student) {
                fputcsv($handle, [
                    $student->lrn,
                    $student->user?->name,
                    '',
                    $student->user?->email,
                ]);
            }
        });
    }

    /**
     * Export gradebook as CSV (students × assignments).
     */
    public function gradebook(Request $request, ClassRoom $classroom): StreamedResponse
    {
        abort_unless($classroom->isTaughtBy($request->user()), 403);

        $classroom->load(['subject', 'section', 'term']);

        $assignments = $classroom->assignments()
            ->where('is_published', true)
            ->orderBy('due_at')
            ->get(['id', 'title', 'category', 'points']);

        $students = $classroom->students()->with('user:id,name')->orderBy('lrn')->get();

        // Build grade matrix
        $grades = Grade::where('class_id', $classroom->id)
            ->get()
            ->keyBy('student_id');

        $filename = sprintf(
            'gradebook-%s-%s.csv',
            str_replace(' ', '-', $classroom->section?->name ?? 'class'),
            now()->format('Y-m-d')
        );

        return $this->csvResponse($filename, array_merge(
            ['LRN', 'Student Name'],
            $assignments->pluck('title')->toArray(),
            ['Written Work', 'Performance Task', 'Quarterly Exam', 'Final Grade', 'Remarks']
        ), function ($handle) use ($students, $assignments, $grades) {
            foreach ($students as $student) {
                $grade = $grades->get($student->id);

                $row = [$student->lrn, $student->user?->name];

                foreach ($assignments as $assignment) {
                    $submission = \App\Models\AssignmentSubmission::where('assignment_id', $assignment->id)
                        ->where('student_id', $student->id)
                        ->first();
                    $row[] = $submission?->grade ?? '';
                }

                $row[] = $grade?->written_work_score ?? '';
                $row[] = $grade?->performance_task_score ?? '';
                $row[] = $grade?->quarterly_exam_score ?? '';
                $row[] = $grade?->final_grade ?? '';
                $row[] = $grade?->remarks ?? '';

                fputcsv($handle, $row);
            }
        });
    }

    /**
     * Export all enrollments for a school year.
     */
    public function enrollments(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'status'         => ['nullable', 'in:enrolled,pending,dropped'],
        ]);

        $activeYear   = SchoolYear::where('is_active', true)->first();
        $schoolYearId = $validated['school_year_id'] ?? $activeYear?->id;

        $query = Enrollment::query()
            ->with(['student.user:id,name', 'section.strand:id,name', 'section:id,name,strand_id'])
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when(! empty($validated['status']), fn ($q) => $q->where('status', $validated['status']))
            ->orderBy('enrolled_at');

        $filename = 'enrollments-' . now()->format('Y-m-d') . '.csv';

        return $this->csvResponse(
            $filename,
            ['LRN', 'Student Name', 'Section', 'Strand', 'Status', 'Enrolled At'],
            function ($handle) use ($query) {
                $query->chunk(500, function ($rows) use ($handle) {
                    foreach ($rows as $e) {
                        fputcsv($handle, [
                            $e->student?->lrn,
                            $e->student?->user?->name,
                            $e->section?->name,
                            $e->section?->strand?->name,
                            $e->status,
                            $e->enrolled_at?->toDateTimeString(),
                        ]);
                    }
                });
            }
        );
    }

    /**
     * Export all applicants for a school year.
     */
    public function applicants(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'status'         => ['nullable', 'string'],
        ]);

        $activeYear   = SchoolYear::where('is_active', true)->first();
        $schoolYearId = $validated['school_year_id'] ?? $activeYear?->id;

        $query = Applicant::query()
            ->with('strand:id,name')
            ->when($schoolYearId, fn ($q) => $q->where('school_year_id', $schoolYearId))
            ->when(! empty($validated['status']), fn ($q) => $q->where('status', $validated['status']))
            ->orderBy('submitted_at');

        $filename = 'applicants-' . now()->format('Y-m-d') . '.csv';

        return $this->csvResponse(
            $filename,
            ['Reference #', 'LRN', 'Full Name', 'Email', 'Contact', 'Grade Level', 'Strand', 'Status', 'Submitted At'],
            function ($handle) use ($query) {
                $query->chunk(500, function ($rows) use ($handle) {
                    foreach ($rows as $a) {
                        fputcsv($handle, [
                            $a->reference_number,
                            $a->lrn,
                            $a->full_name,
                            $a->email,
                            $a->contact_number,
                            $a->desired_grade_level,
                            $a->strand?->name,
                            $a->status,
                            $a->submitted_at?->toDateTimeString(),
                        ]);
                    }
                });
            }
        );
    }

    /**
     * Export all students.
     */
    public function students(Request $request): StreamedResponse
    {
        $query = Student::query()
            ->with('user:id,name')
            ->orderBy('lrn');

        $filename = 'students-' . now()->format('Y-m-d') . '.csv';

        return $this->csvResponse(
            $filename,
            ['LRN', 'Name', 'Email', 'Sex', 'Date of Birth', 'Status'],
            function ($handle) use ($query) {
                $query->chunk(500, function ($rows) use ($handle) {
                    foreach ($rows as $s) {
                        fputcsv($handle, [
                            $s->lrn,
                            $s->user?->name,
                            $s->user?->email,
                            $s->sex,
                            $s->date_of_birth?->toDateString(),
                            $s->status,
                        ]);
                    }
                });
            }
        );
    }

    /**
     * Export all teachers.
     */
    public function teachers(Request $request): StreamedResponse
    {
        $query = Teacher::query()
            ->with('user:id,name,email')
            ->orderBy('employee_no');

        $filename = 'teachers-' . now()->format('Y-m-d') . '.csv';

        return $this->csvResponse(
            $filename,
            ['Employee #', 'Name', 'Email', 'Sex', 'Department', 'Specialization', 'Active'],
            function ($handle) use ($query) {
                $query->chunk(500, function ($rows) use ($handle) {
                    foreach ($rows as $t) {
                        fputcsv($handle, [
                            $t->employee_no,
                            $t->user?->name,
                            $t->user?->email,
                            $t->sex,
                            $t->department,
                            $t->specialization,
                            $t->is_active ? 'Yes' : 'No',
                        ]);
                    }
                });
            }
        );
    }

    // ─── Helper ───────────────────────────────────────────

    /**
     * Stream a CSV download.
     */
    protected function csvResponse(string $filename, array $headers, callable $writer): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $writer) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel to recognize UTF-8
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, $headers);
            $writer($handle);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Download a PDF report card for a single student.
     * Access: admin any student, student only their own.
     */
    public function reportCard(Request $request, Student $student)
    {
        $this->authorizeReportCardAccess($request, $student);

        $activeYear = SchoolYear::where('is_active', true)->first();
        $activeTerm = Term::where('is_active', true)->first();

        $student->load(['user:id,name,email', 'enrollments.section.strand', 'enrollments.schoolYear']);

        // Get all grades for this student in the active term
        $grades = Grade::query()
            ->with([
                'classroom.subject:id,code,name',
                'classroom.teacher.user:id,name',
            ])
            ->where('student_id', $student->id)
            ->when($activeTerm, fn ($q) => $q->whereHas(
                'classroom',
                fn ($sq) => $sq->where('term_id', $activeTerm->id)
            ))
            ->get();

        // Compute general average from finalized grades
        $generalAverage = $grades->whereNotNull('final_grade')->avg('final_grade');
        $generalAverage = $generalAverage !== null ? round($generalAverage, 2) : null;

        // Determine remarks
        $passing = \App\Models\SystemSetting::passingGrade();

        $overallRemarks = null;
        if ($generalAverage !== null) {
            $overallRemarks = match (true) {
                $generalAverage >= 90               => 'Outstanding',
                $generalAverage >= 85               => 'Very Satisfactory',
                $generalAverage >= 80               => 'Satisfactory',
                $generalAverage >= $passing         => 'Fairly Satisfactory',
                default                             => 'Did Not Meet Expectations',
            };
        }

        $data = [
            'student'        => $student,
            'enrollment'     => $student->enrollments->first(),
            'grades'         => $grades,
            'schoolYear'     => $activeYear,
            'term'           => $activeTerm,
            'generalAverage' => $generalAverage,
            'overallRemarks' => $overallRemarks,
            'school'         => [
                'name'    => \App\Models\SystemSetting::get('school_name', 'Salawag Senior High School'),
                'address' => \App\Models\SystemSetting::get('school_address', 'Dasmariñas, Cavite'),
                'email'   => \App\Models\SystemSetting::get('school_email', ''),
                'phone'   => \App\Models\SystemSetting::get('school_phone', ''),
                'principal_name' => \App\Models\SystemSetting::get('principal_name', ''),
            ],
            'generatedAt'    => now(),
        ];

        $pdf = Pdf::loadView('pdf.report-card', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

        $filename = 'report-card-' . $student->lrn . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Authorization: admin can access any student, student only their own.
     */
    protected function authorizeReportCardAccess(Request $request, Student $student): void
    {
        $user = $request->user();

        // Admin: any student
        if ($user->hasRole('admin')) {
            return;
        }

        // Student: only themselves
        if ($user->student && $user->student->id === $student->id) {
            return;
        }

        abort(403);
    }
}