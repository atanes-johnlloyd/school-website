<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $payload = [
            'school' => [
                'name'    => SystemSetting::get('school_name', 'Salawag Senior High School'),
                'address' => SystemSetting::get('school_address', 'Dasmariñas, Cavite'),
                'email'   => SystemSetting::get('school_email', ''),
                'phone'   => SystemSetting::get('school_phone', ''),
            ],
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Site/Contact', $payload);
    }
}