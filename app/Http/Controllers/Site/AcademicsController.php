<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Models\Strand;
use App\Models\Subject;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AcademicsController extends Controller
{
    public function index(Request $request)
    {
        $tracks = Track::where('is_active', true)
            ->with(['strands' => fn ($q) => $q->where('is_active', true)])
            ->get()
            ->map(fn ($t) => [
                'id'          => $t->id,
                'code'        => $t->code,
                'name'        => $t->name,
                'description' => $t->description,
                'icon'        => $t->icon,
                'color'       => $t->color,
                'strands'     => $t->strands->map(fn ($s) => [
                    'id'   => $s->id,
                    'code' => $s->code,
                    'name' => $s->name,
                ]),
            ]);

        $subjects = Subject::where('is_active', true)
            ->with(['strand.track'])
            ->get()
            ->map(fn ($s) => [
                'code'          => $s->code,
                'title'         => $s->name,
                'strand'        => $s->strand?->name ?? $s->track?->name,
                'levelSem'      => $s->grade_level . ' • ' . $s->semester,
                'category'      => $s->category,
                'weeklyHours'   => $s->weekly_hours,
                'prerequisites' => $s->prerequisites,
                'fileSize'      => $s->syllabus_size ?? 'N/A',
                'downloadLink'  => $s->syllabus_url ?? '#',
            ]);

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
}