<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'school_name',           'value' => 'Salawag Senior High School',          'group' => 'school'],
            ['key' => 'school_address',        'value' => 'Dasmariñas, Cavite',                  'group' => 'school'],
            ['key' => 'school_email',          'value' => 'info@salawagshs.edu.ph',              'group' => 'school'],
            ['key' => 'school_phone',          'value' => '',                                    'group' => 'school'],
            ['key' => 'principal_name',        'value' => '',                                    'group' => 'school'],
            ['key' => 'passing_grade',         'value' => '75',                                  'group' => 'academic'],
            ['key' => 'max_class_size',        'value' => '40',                                  'group' => 'academic'],
            ['key' => 'announcement_days',     'value' => '30',                                  'group' => 'system'],
            ['key' => 'max_file_upload_mb',    'value' => '10',                                  'group' => 'system'],
            ['key' => 'contact_email_visible', 'value' => '1',                                   'group' => 'system'],
        ];

        foreach ($defaults as $row) {
            SystemSetting::updateOrCreate(['key' => $row['key']], $row);
        }
    }
}