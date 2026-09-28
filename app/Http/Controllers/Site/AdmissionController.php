<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use App\Models\Subject;
use App\Models\SystemSetting;
use App\Models\Track;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        // Public admission requirements + programs offered
        $tracks = Track::where('is_active', true)
            ->with(['strands' => fn ($q) => $q->where('is_active', true)])
            ->get()
            ->map(fn (Track $t) => [
                'id'      => $t->id,
                'code'    => $t->code,
                'name'    => $t->name,
                'icon'    => $t->icon,
                'color'   => $t->color,
                'strands' => $t->strands->map(fn (Strand $s) => [
                    'id'   => $s->id,
                    'code' => $s->code,
                    'name' => $s->name,
                ]),
            ]);

        $payload = [
            'school' => [
                'name'    => SystemSetting::get('school_name', 'Salawag Senior High School'),
                'email'   => SystemSetting::get('school_email', ''),
                'phone'   => SystemSetting::get('school_phone', ''),
            ],
            'tracks' => $tracks,
            'requirements' => [
                // Hardcoded for now — could be settings-driven later
                'Form 138 (Report Card)',
                'Form 137 (Permanent Record)',
                'PSA Birth Certificate',
                'Good Moral Certificate',
                '2x2 ID Picture (2 copies)',
            ],
            // Will be replaced by real workflow in Sprint 15
            'application_opens' => 'June 1',
            'application_closes' => 'August 31',
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Site/Admission', $payload);
    }
}