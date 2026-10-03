<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\Strand;
use App\Models\Subject;
use App\Models\SystemSetting;
use App\Models\Track;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AcademicsController extends Controller
{
    public function index(Request $request)
    {
        $tracks = Track::where('is_active', true)
            ->with([
                'strands' => fn ($q) => $q
                    ->where('is_active', true)
                    ->orderBy('code'),
            ])
            ->orderBy('code')
            ->get()
            ->map(fn (Track $t) => [
                'id'          => $t->id,
                'code'        => $t->code,
                'name'        => $t->name,
                'description' => $t->description,
                'icon'        => $t->icon,
                'color'       => $t->color,
                'strands'     => $t->strands->map(fn (Strand $s) => [
                    'id'          => $s->id,
                    'code'        => $s->code,
                    'name'        => $s->name,
                    'description' => $s->description,
                    'icon'        => $s->icon,
                    'color'       => $s->color,
                    'track_id'    => $s->track_id,
                ]),
            ]);

        $subjects = Subject::where('is_active', true)
            ->with([
                'strand:id,code,name,track_id',
                'strand.track:id,code,name',
                'prerequisite:id,code,name',
            ])
            ->orderBy('code')
            ->get()
            ->map(function (Subject $s) {
                // Friendly grade label
                $gradeLabel = match ($s->grade_level) {
                    '11'    => 'Grade 11',
                    '12'    => 'Grade 12',
                    'both'  => 'Grade 11–12',
                    default => '—',
                };

                // Track scope for filter comparison (ACAD / TECHPRO / null)
                $trackCode = $s->strand?->track?->code;

                // Prerequisites as a single string (nullable)
                $prerequisite = $s->prerequisite
                    ? "{$s->prerequisite->code} — {$s->prerequisite->name}"
                    : 'None';

                return [
                    'id'            => $s->id,
                    'code'          => $s->code,
                    'title'         => $s->name,
                    'description'   => $s->description,

                    'strand'        => $s->strand?->name ?? 'Core Curriculum',
                    'strand_id'     => $s->strand_id,
                    'track_code'    => $trackCode,
                    'track_name'    => $s->strand?->track?->name ?? 'All Tracks',

                    'grade_level'   => $s->grade_level,       // '11' | '12' | 'both'
                    'grade_label'   => $gradeLabel,
                    'hours'         => (int) $s->hours,
                    'weeklyHours'   => $s->hours . ' hrs',
                    'category'      => $s->is_core ? 'Core' : 'Specialized',
                    'is_core'       => (bool) $s->is_core,
                    'prerequisite'  => $prerequisite,

                    // Live PDF route — generates on the fly, no stored file needed
                    'downloadLink'  => route('site.academics.syllabus', $s->id),
                ];
            });

        $payload = [
            'school'   => [
                'name'    => SystemSetting::get('school_name', 'Salawag Senior High School'),
                'address' => SystemSetting::get('school_address', 'Dasmariñas, Cavite'),
            ],
            'tracks'   => $tracks,
            'subjects' => $subjects,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Site/Academics/Academics', $payload);
    }

    /**
     * Generate a subject syllabus PDF on demand.
     */
    public function syllabus(Subject $subject)
    {
        abort_unless($subject->is_active, 404);

        $subject->load(['strand.track:id,name', 'prerequisite:id,code,name']);

        $activeYear = SchoolYear::where('is_active', true)->first();

        $pdf = Pdf::loadView('pdf.subject-syllabus', [
            'subject'     => $subject,
            'school'      => [
                'name'    => SystemSetting::get('school_name', 'Salawag Senior High School'),
                'address' => SystemSetting::get('school_address', 'Dasmariñas, Cavite'),
                'email'   => SystemSetting::get('school_email', ''),
            ],
            'schoolYear'  => $activeYear?->label,
            'generatedAt' => now(),
        ])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isRemoteEnabled'      => false,
                'isHtml5ParserEnabled' => true,
                'defaultFont'          => 'DejaVu Sans',
            ]);

        $filename = str_replace(' ', '-', strtolower($subject->code)) . '-syllabus.pdf';

        return $pdf->download($filename);
    }
}