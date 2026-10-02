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
            ['key' => 'school_motto',              'value' => 'Serbisyong Tapat, Karunungang Wagas',    'group' => 'school'],
['key' => 'school_principal_name',     'value' => 'Dr. Marilou T. Santiago',                'group' => 'school'],
['key' => 'school_region',             'value' => 'Region IV-A (CALABARZON)',               'group' => 'school'],
['key' => 'school_division',           'value' => 'Division of Dasmariñas City',            'group' => 'school'],
['key' => 'school_district',           'value' => 'Dasmariñas East',                        'group' => 'school'],
['key' => 'school_id_number',          'value' => '305412',                                 'group' => 'school'],
['key' => 'current_school_year',       'value' => '2026-2027',                              'group' => 'academic'],
['key' => 'current_semester',          'value' => '1st Semester',                           'group' => 'academic'],
['key' => 'weight_written_work',       'value' => '25',                                     'group' => 'academic'],
['key' => 'weight_performance_task',   'value' => '50',                                     'group' => 'academic'],
['key' => 'weight_quarterly_exam',     'value' => '25',                                     'group' => 'academic'],
['key' => 'attendance_late_grace_min', 'value' => '15',                                     'group' => 'academic'],
['key' => 'student_portal_enabled',    'value' => '1',                                      'group' => 'system'],
['key' => 'teacher_portal_enabled',    'value' => '1',                                      'group' => 'system'],
['key' => 'parent_portal_enabled',     'value' => '0',                                      'group' => 'system'],
['key' => 'maintenance_mode',          'value' => '0',                                      'group' => 'system'],
['key' => 'login_attempts_limit',      'value' => '5',                                      'group' => 'system'],
['key' => 'session_timeout_minutes',   'value' => '120',                                    'group' => 'system'],
['key' => 'backup_retention_days',     'value' => '30',                                     'group' => 'system'],
['key' => 'support_email',             'value' => 'support@salawagshs.edu.ph',              'group' => 'system'],
['key' => 'support_phone',             'value' => '+63 46 555 1234',                        'group' => 'system'],
        ];

        foreach ($defaults as $row) {
            SystemSetting::updateOrCreate(['key' => $row['key']], $row);
        }
    }
}