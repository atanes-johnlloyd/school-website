<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'sysadmin@test.com')->first()
              ?? User::where('email', 'atanes.johnlloyd@ncst.edu.ph')->first();

        $actions = [
            ['login',                 'User',       null,  null,                          null],
            ['logout',                'User',       null,  null,                          null],
            ['create-student',        'Student',    12,    null,                          ['lrn' => '123456789012', 'name' => 'Alysa Bahringer']],
            ['update-teacher',        'Teacher',    3,     ['department' => 'Sciences'],  ['department' => 'Mathematics']],
            ['create-class',          'ClassRoom',  47,    null,                          ['subject' => 'General Science', 'section' => 'Grade 11 - STEM']],
            ['delete-announcement',   'Announcement', 2,   ['title' => 'Old notice'],     null],
            ['assign-teacher',        'ClassRoom',  47,    ['teacher_id' => null],        ['teacher_id' => 4]],
            ['publish-grades',        'Grade',      120,   ['is_finalized' => false],     ['is_finalized' => true]],
            ['create-school-year',    'SchoolYear', 1,     null,                          ['label' => '2026-2027']],
            ['update-system-setting', 'SystemSetting', 5,  ['value' => ''],               ['value' => '75']],
            ['backup-database',       'System',     null,  null,                          ['size_mb' => 42.7]],
            ['reset-password',        'User',       19,    null,                          ['email' => 'teacher@test.com']],
        ];

        foreach ($actions as $i => [$action, $type, $targetId, $old, $new]) {
            AuditLog::create([
                'user_id'        => $admin?->id,
                'action'         => $action,
                'auditable_type' => $type ? "App\\Models\\{$type}" : null,
                'auditable_id'   => $targetId,
                'old_values'     => $old,
                'new_values'     => $new,
                'ip_address'     => '127.0.0.1',
                'user_agent'     => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at'     => now()->subMinutes(15 * ($i + 1)),
            ]);
        }

        $this->command->info('✅ Audit log seeded: ' . count($actions) . ' entries');
    }
}