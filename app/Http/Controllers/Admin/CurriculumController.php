<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use App\Models\Subject;
use App\Models\Track;
use Inertia\Inertia;
use Inertia\Response;

class CurriculumController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Curriculum/Index', [
            'tracks' => Track::withCount('strands')
                ->orderBy('code')
                ->get(),

            'strands' => Strand::with(['track:id,code,name'])
                ->withCount(['subjects', 'sections'])
                ->orderBy('code')
                ->get(),

            'subjects' => Subject::with(['strand:id,code,name', 'prerequisite:id,code,name'])
                ->orderBy('code')
                ->get(),

            'prerequisites' => Subject::select('id', 'code', 'name')
                ->orderBy('code')
                ->get(),
        ]);
    }
}