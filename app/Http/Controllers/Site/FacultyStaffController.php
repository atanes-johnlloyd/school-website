<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FacultyStaffController extends Controller
{
    public function index(Request $request)
    {
        $faculty = Teacher::where('is_active', true)
            ->where('is_publicly_visible', true)
            ->get()
            ->map(fn ($t) => [
                'id'            => $t->id,
                'name'          => $t->full_name,
                'title'         => $t->position,
                'roleSubtitle'  => $t->specialization,
                'category'      => $t->department,   // adjust according to your DB
                'categoryLabel' => strtoupper($t->department),
                'room'          => $t->room ?? 'TBA',
                'image'         => $t->photo_url,
                'email'         => $t->email,
                'consultation'  => $t->consultation_hours,
                'advisory'      => $t->advisory_class,
                'specialization'=> $t->specialization,
                // add badgeClass, badgeTagStyle, dotStyle, buttonClass, buttonLabel as needed
            ]);

        $payload = [
            'school'  => [
                'name'    => SystemSetting::get('school_name', 'Salawag Senior High School'),
                'address' => SystemSetting::get('school_address', 'Dasmariñas, Cavite'),
                'email'   => SystemSetting::get('school_email', ''),
                'phone'   => SystemSetting::get('school_phone', ''),
            ],
            'faculty' => $faculty,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Site/FacultyStaff/FacultyStaff', $payload);
    }
}