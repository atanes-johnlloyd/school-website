<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionsByGroup = [
            // Granted via the "admin" role. Individual admins are then
            // narrowed down to a subset of these via their admin_position
            // preset (see AdminPositionSeeder) or hand-picked per user —
            // this list is the ceiling, not what every admin gets.
            'admin' => [
                'manage-users',
                'manage-students',
                'manage-teachers',
                'manage-enrollment',
                'manage-school-years',
                'manage-tracks',
                'manage-strands',
                'manage-subjects',
                'manage-sections',
                'manage-rooms',
                'manage-classes',
                'assign-teachers',
                'manage-class-schedules',
                'view-reports',
                'view-audit-log',
                'manage-settings',
                'manage-announcements',
                'manage-backups',
                'manage-contributions',   // ← NEW
            ],
            'teacher' => [
                'view-own-classes',
                'manage-own-content',
                'manage-own-quizzes',
                'manage-own-question-bank',
                'manage-own-assignments',
                'grade-submissions',
                'manage-own-gradebook',
                'manage-own-announcements',
                'view-own-schedule',
                'send-messages',
                'manage-own-contributions',   // ← NEW
            ],
            'student' => [
                'view-own-courses',
                'take-quizzes',
                'submit-assignments',
                'view-own-grades',
                'view-own-schedule',
                'view-own-report-card',
                'view-announcements',
                'send-messages',
                'pay-contributions',          // ← NEW
            ],
            'shared' => [
                'view-own-profile',
                'update-own-profile',
            ],
        ];

        $allPermissions = array_merge(...array_values($permissionsByGroup));

        foreach ($allPermissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // Exactly 3 portal-determining roles.
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // The `admin` role is a *portal marker* only — it determines which
        // dashboard a user lands on, not what they can do. Actual permissions
        // come exclusively from the user's AdminPosition preset (granted as
        // direct user permissions in TestAdminUsersSeeder / admin user
        // management). Keeping this role empty is what makes the "ceiling"
        // pattern in AdminPositionSeeder actually work.
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([]);   // ← was: the full permission list

        $teacher->syncPermissions([
            ...$permissionsByGroup['teacher'],
            ...$permissionsByGroup['shared'],
        ]);

        $student->syncPermissions([
            ...$permissionsByGroup['student'],
            ...$permissionsByGroup['shared'],
        ]);

        // No parent portal — remove the role and any users' assignment to
        // it if it was seeded previously. Safe to run repeatedly.
        if ($parent = Role::where('name', 'parent')->where('guard_name', 'web')->first()) {
            $parent->users()->each(fn ($user) => $user->removeRole($parent));
            $parent->delete();
        }
    }
}
