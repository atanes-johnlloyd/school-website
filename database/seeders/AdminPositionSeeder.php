<?php

namespace Database\Seeders;

use App\Models\AdminPosition;
use Illuminate\Database\Seeder;

class AdminPositionSeeder extends Seeder
{
    /**
     * Carried over from the old system's 7 admin job titles, trimmed to the
     * 5 that map to pages this site actually has. "Admissions Officer" and
     * "LIS Coordinator" aren't seeded — no admissions module or DepEd LIS
     * integration exists yet. Add them the same way, any time, with zero
     * migrations: just a new row here.
     */
    public function run(): void
    {
        $positions = [
            [
                'name' => 'System Admin',
                'description' => 'Full technical administrator — users, settings, backups, audit log.',
                'default_permissions' => [
                    'manage-users', 'manage-students', 'manage-teachers',
                    'manage-enrollment', 'manage-school-years', 'manage-tracks',
                    'manage-strands', 'manage-subjects', 'manage-sections',
                    'manage-rooms', 'manage-classes', 'assign-teachers',
                    'manage-class-schedules', 'view-reports', 'view-audit-log',
                    'manage-settings', 'manage-announcements', 'manage-backups',
                ],
            ],
            [
                'name' => 'Registrar',
                'description' => 'Manages student records, enrollment, and section rosters.',
                'default_permissions' => [
                    'manage-students', 'manage-enrollment', 'manage-sections',
                    'view-reports',
                ],
            ],
            [
                'name' => 'Curriculum Coordinator',
                'description' => 'Oversees curriculum, subjects, class offerings, and teacher assignment.',
                'default_permissions' => [
                    'manage-tracks', 'manage-strands', 'manage-subjects',
                    'manage-classes', 'assign-teachers', 'manage-class-schedules',
                    'manage-teachers', 'view-reports',
                ],
            ],
            [
                'name' => 'School Head',
                'description' => 'Principal/OIC — high-level oversight and school-wide communication.',
                'default_permissions' => [
                    'view-reports', 'view-audit-log', 'manage-announcements',
                ],
            ],
            [
                'name' => 'Staff',
                'description' => 'Read-only access to reports.',
                'default_permissions' => [
                    'view-reports',
                ],
            ],
        ];

        foreach ($positions as $position) {
            AdminPosition::updateOrCreate(
                ['name' => $position['name']],
                [
                    'description' => $position['description'],
                    'default_permissions' => $position['default_permissions'],
                    'is_active' => true,
                ]
            );
        }
    }
}
