<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutController extends Controller
{
    public function index(Request $request)
    {
        $payload = [
            'school' => [
                'name'           => SystemSetting::get('school_name', 'Salawag Senior High School'),
                'address'        => SystemSetting::get('school_address', 'Dasmariñas, Cavite'),
                'email'          => SystemSetting::get('school_email', ''),
                'phone'          => SystemSetting::get('school_phone', ''),
                'principal_name' => SystemSetting::get('principal_name', ''),
            ],
            // Quick glance at faculty count for the "Our Team" section
            'faculty_count' => Teacher::where('is_active', true)->where('is_publicly_visible', true)->count(),
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Public/About', $payload);
    }
}