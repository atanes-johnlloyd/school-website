<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Section;
use App\Models\Strand;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SystemSetting;
use App\Models\Teacher;
use App\Models\Track;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Live stats for hero section
        $stats = [
            'students' => Student::where('status', 'active')->count(),
            'sections' => Section::count(),
            'subjects' => Subject::where('is_active', true)->count(),
        ];

        // Tracks + strands for "Our Programs"
        $tracks = Track::where('is_active', true)
            ->with(['strands' => fn ($q) => $q->where('is_active', true)->limit(5)])
            ->get()
            ->map(fn (Track $t) => [
                'id'          => $t->id,
                'code'        => $t->code,
                'name'        => $t->name,
                'description' => $t->description,
                'icon'        => $t->icon,
                'image_url'   => $t->image_url,
                'color'       => $t->color,
                'strands'     => $t->strands->map(fn (Strand $s) => [
                    'id'    => $s->id,
                    'code'  => $s->code,
                    'name'  => $s->name,
                    'icon'  => $s->icon,
                    'color' => $s->color,
                ]),
            ]);

        // Latest 3 school-wide news items
        $latestNews = Announcement::query()
            ->schoolWide()
            ->published()
            ->active()
            ->ordered()
            ->limit(3)
            ->get()
            ->map(fn (Announcement $a) => [
                'id'              => $a->id,
                'title'           => $a->title,
                'body_preview'    => \Str::limit(strip_tags($a->body), 140),
                'image_url'       => $a->image_url,
                'published_at'    => $a->published_at?->toIso8601String(),
                'published_human' => $a->published_at?->diffForHumans(),
            ]);

        $payload = [
            'school' => [
                'name'    => SystemSetting::get('school_name', 'Salawag Senior High School'),
                'address' => SystemSetting::get('school_address', 'Dasmariñas, Cavite'),
                'email'   => SystemSetting::get('school_email', ''),
                'phone'   => SystemSetting::get('school_phone', ''),
            ],
            'stats'      => $stats,
            'tracks'     => $tracks,
            'latestNews' => $latestNews,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Site/Home', $payload);
    }
}