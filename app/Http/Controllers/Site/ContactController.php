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

        return \Inertia\Inertia::render('Site/Contact', [
            'emailVisible' => SystemSetting::contactEmailVisible(),
            'schoolEmail'  => SystemSetting::get('school_email', ''),
            'schoolPhone'  => SystemSetting::get('school_phone', ''),
            'schoolAddress'=> SystemSetting::get('school_address', ''),
        ]);
    }
}