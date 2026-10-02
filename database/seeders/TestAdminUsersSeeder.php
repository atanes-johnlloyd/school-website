<?php

namespace Database\Seeders;

use App\Models\AdminPosition;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestAdminUsersSeeder extends Seeder
{
    /**
     * Creates one ready-to-login admin account per AdminPosition so every
     * role-limited dashboard can be demonstrated without hand-editing
     * permission tables. Password for all: "password".
     */
    public function run(): void
    {
        // [email, name, position]
        $accounts = [
            ['sysadmin@test.com',    'Test System Admin',   'System Admin'],
            ['registrar@test.com',   'Test Registrar',      'Registrar'],
            ['curriculum@test.com',  'Test Curriculum',     'Curriculum Coordinator'],
            ['schoolhead@test.com',  'Test School Head',    'School Head'],
            ['staff@test.com',       'Test Staff',          'Staff'],
        ];

        foreach ($accounts as [$email, $name, $positionName]) {
            $position = AdminPosition::where('name', $positionName)->first();

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'                 => $name,
                    'password'             => Hash::make('password'),
                    'email_verified_at'    => now(),
                    'must_change_password' => false,
                    'admin_position_id'    => $position?->id,
                ]
            );

            $user->syncRoles(['admin']);

            if ($position) {
                // Attach only the permissions this position prescribes.
                $user->syncPermissions($position->default_permissions);
            }

            $this->command->info("   • {$email}  →  {$positionName}");
        }

        $this->command->info('✅ Test admin accounts ready (password: "password")');
    }
}