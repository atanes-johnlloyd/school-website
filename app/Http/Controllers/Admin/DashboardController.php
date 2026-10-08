<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\ContactMessage;
use App\Models\Enrollment;
use App\Models\EntranceExam;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Term;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $activeYear = SchoolYear::where('is_active', true)->first();
        $activeTerm = Term::where('is_active', true)->first();

        $applicants = Applicant::query()
            ->when($activeYear, fn ($q) => $q->where('school_year_id', $activeYear->id));

        $pending      = (clone $applicants)->where('status', 'pending')->count();
        $underReview  = (clone $applicants)->where('status', 'under_review')->count();
        $approved     = (clone $applicants)->where('status', 'approved')->count();
        $enrolledApps = (clone $applicants)->where('status', 'enrolled')->count();

        $sectionsQuery = Section::query()
            ->when($activeYear, fn ($q) => $q->where('school_year_id', $activeYear->id));

        $totalCapacity = (clone $sectionsQuery)->sum('max_capacity');
        $totalEnrolled = Enrollment::where('status', 'enrolled')
            ->when($activeYear, fn ($q) => $q->where('school_year_id', $activeYear->id))
            ->count();

        $stats = [
            'applicants_total'       => (clone $applicants)->count(),
            'applicants_pending'     => $pending,
            'applicants_under_review'=> $underReview,
            'applicants_approved'    => $approved,
            'applicants_enrolled'    => $enrolledApps,
            Student::where('status', 'active')
                ->when($activeYear, fn ($q) => $q->where('school_year_id', $activeYear->id))
                ->count(),
            'teachers_active'        => Teacher::where('is_active', true)->count(),
            'sections_count'         => (clone $sectionsQuery)->count(),
            'total_capacity'         => $totalCapacity,
            'total_enrolled'         => $totalEnrolled,
            'occupancy_rate'         => $totalCapacity > 0
                ? round(($totalEnrolled / $totalCapacity) * 100, 1)
                : 0,
            'unread_messages'        => ContactMessage::where('is_read', false)->count(),
        ];

        $recentApplications = (clone $applicants)
            ->with('strand:id,name')
            ->latest('submitted_at')
            ->limit(5)
            ->get()
            ->map(fn ($a) => [
                'id'               => $a->id,
                'reference_number' => $a->reference_number,
                'name'             => $a->full_name,
                'strand'           => $a->strand?->name,
                'status'           => $a->status,
                'submitted_at'     => $a->submitted_at?->toIso8601String(),
            ]);

        $upcomingExams = EntranceExam::query()
            ->with('track:id,name')
            ->where('status', '!=', 'Cancelled')
            ->whereRaw("CONCAT(exam_date, ' ', exam_time) > ?", [now()])
            ->orderBy('exam_date')
            ->orderBy('exam_time')
            ->limit(3)
            ->get()
            ->map(fn ($e) => [
                'id'              => $e->id,
                'exam_name'       => $e->exam_name,
                'exam_date'       => $e->exam_date?->toDateString(),
                'exam_time'       => $e->exam_time,
                'venue'           => $e->venue,
                'track'           => $e->track?->name,
                'applicant_count' => $e->results()->count(),
                'max_capacity'    => $e->max_capacity,
            ]);

        $monthlyTrend = (clone $applicants)
            ->whereNotNull('submitted_at')
            ->where('submitted_at', '>=', now()->subMonths(6))
            ->get(['submitted_at'])
            ->groupBy(fn ($a) => $a->submitted_at->format('Y-m'))
            ->map(fn ($g, $k) => ['month' => $k, 'count' => $g->count()])
            ->sortKeys()
            ->values()
            ->all();

        $actionItems = [];
        if ($pending > 0) {
            $actionItems[] = [
                'type'  => 'pending',
                'label' => "{$pending} application(s) pending review",
                'url'   => route('admin.applicants.index', ['status' => 'pending']),
            ];
        }
        if ($approved > 0) {
            $actionItems[] = [
                'type'  => 'approved',
                'label' => "{$approved} approved applicant(s) awaiting exam",
                'url'   => route('admin.applicants.index', ['status' => 'approved']),
            ];
        }
        if ($stats['unread_messages'] > 0) {
            $actionItems[] = [
                'type'  => 'messages',
                'label' => "{$stats['unread_messages']} unread contact message(s)",
                'url'   => route('admin.contact-messages.index'),
            ];
        }

        return Inertia::render('Admin/Dashboard', [
            'stats'              => $stats,
            'recentApplications' => $recentApplications,
            'upcomingExams'      => $upcomingExams,
            'monthlyTrend'       => $monthlyTrend,
            'actionItems'        => $actionItems,
            'activeYear'         => $activeYear?->label,
            'activeTerm'         => $activeTerm?->name,
        ]);
    }
}