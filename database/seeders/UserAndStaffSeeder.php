<?php

namespace Database\Seeders;

use App\Models\AdminPosition;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserAndStaffSeeder
 * ------------------
 * Creates the administrative and teaching staff for Salawag SHS.
 *
 * Depends on: ReferenceDataSeeder (for admin_positions + roles/permissions).
 *
 * Pattern (matches the working app):
 *   • Every staff user gets ONE of three portal roles:
 *       - admin   (portal marker — ZERO permissions on the role)
 *       - teacher (carries teaching permissions)
 *   • Admin users get their REAL permissions from their
 *     admin_position.default_permissions, attached as DIRECT user
 *     permissions via syncPermissions(). The admin role itself is
 *     permission-less by design.
 *   • Teachers get the "teacher" role; their classroom-level access
 *     comes from the permissions baked into that role.
 *
 * Staff composition:
 *   • 1  System Admin   (the IT administrator who owns the platform)
 *   • 1  Registrar
 *   • 1  Curriculum Coordinator
 *   • 1  School Head (Principal)
 *   • 1  Staff (read-only reports access)
 *   • 18 Teachers — covering the 5 core subjects plus every cluster
 *                    of the Strengthened SHS Curriculum
 *
 * Total: 23 users (5 admins + 18 teachers).
 *
 * Demo password for every seeded account: password
 * must_change_password = false so seeded accounts can log in immediately.
 */
class UserAndStaffSeeder extends Seeder
{
    /** Demo password for every seeded account. */
    private const DEMO_PASSWORD = 'password';

    public function run(): void
    {
        $now = Carbon::now();

        $this->seedAdmins($now);
        $this->seedTeachers($now);

        $this->command->info('✔ Staff seeded: 5 admins + 18 teachers (23 users total).');
        $this->command->warn('  Demo password for all accounts: password');
    }

    /* =================================================================
     | 1. ADMINISTRATORS
     |-----------------------------------------------------------------
     | One user per admin_position. Each gets:
     |   • role  = 'admin'  (portal marker, no perms)
     |   • perms = admin_position.default_permissions  (direct user perms)
     * ================================================================= */
    protected function seedAdmins(Carbon $now): void
    {
        $admins = [
            [
                'name'        => 'Juan Carlo R. Mendoza',
                'email'       => 'sysadmin@salawagshs.deped.gov.ph',
                'position'    => 'System Admin',
                'avatar'      => 'staff/sysadmin-mendoza.jpg',
            ],
            [
                'name'        => 'Dr. Marilou T. Santiago',
                'email'       => 'principal@salawagshs.deped.gov.ph',
                'position'    => 'School Head',
                'avatar'      => 'staff/principal-santiago.jpg',
            ],
            [
                'name'        => 'Fernando L. Bautista',
                'email'       => 'coordinator@salawagshs.deped.gov.ph',
                'position'    => 'Curriculum Coordinator',
                'avatar'      => 'staff/coordinator-bautista.jpg',
            ],
            [
                'name'        => 'Cristina A. Delos Reyes',
                'email'       => 'registrar@salawagshs.deped.gov.ph',
                'position'    => 'Registrar',
                'avatar'      => 'staff/registrar-delosreyes.jpg',
            ],
            [
                'name'        => 'Elizabeth T. Soriano',
                'email'       => 'staff@salawagshs.deped.gov.ph',
                'position'    => 'Staff',
                'avatar'      => 'staff/staff-soriano.jpg',
            ],
            [
                'name'        => 'John Lloyd Atanes',
                'email'       => 'atanes.johnlloyd@ncst.edu.ph',
                'position'    => 'System Admin', // You can change this to 'Staff', 'Registrar', etc. depending on their role
                'avatar'      => 'staff/default-admin.jpg',
            ],
        ];

        foreach ($admins as $a) {
            $position = AdminPosition::where('name', $a['position'])->first();

            $user = User::updateOrCreate(
                ['email' => $a['email']],
                [
                    'name'                 => $a['name'],
                    'avatar_path'          => $a['avatar'],
                    'password'             => Hash::make(self::DEMO_PASSWORD),
                    'must_change_password' => false,
                    'status'               => 'active',
                    'email_verified_at'    => $now,
                    'admin_position_id'    => $position?->id,
                ]
            );

            $user->syncRoles(['admin']);

            // ⚠️ The admin role carries ZERO permissions by design.
            // Real permissions come from the position preset, attached
            // directly to this user.
            if ($position && ! empty($position->default_permissions)) {
                $perms = $position->default_permissions;

                // Handle both array and JSON-string storage.
                if (is_string($perms)) {
                    $perms = json_decode($perms, true) ?: [];
                }

                $user->syncPermissions($perms);
            }
        }
    }

    /* =================================================================
     | 2. TEACHERS
     |-----------------------------------------------------------------
     | Each teacher gets:
     |   • A users row (role = 'teacher')
     |   • A teachers profile row (employee_no, DOB, dept, specialization)
     |
     | Coverage (18 teachers):
     |   • 5 core-subject specialists (English/Comm, Math, Science, LCS, KASP)
     |   • 2 per major cluster (STEM, ICT, HOSPITALITY, ARTS-SOC-HUM,
     |     BUSINESS-ENTREP, INDUSTRIAL/INDUSTRIAL-ARTS, plus cross-coverage
     |     for the smaller TechPro clusters)
     |
     | is_publicly_visible = true so they show on the public faculty page.
     * ================================================================= */
    protected function seedTeachers(Carbon $now): void
    {
        $teachers = [
            // ─── Core subject specialists ────────────────────────
            [
                'name'            => 'Jocelyn P. Fernandez',
                'email'           => 'jocelyn.fernandez@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2015-001',
                'sex'             => 'female',
                'date_of_birth'   => '1985-03-22',
                'contact_number'  => '+63 917 214 8801',
                'date_hired'      => '2015-06-08',
                'department'      => 'English / Communication',
                'specialization'  => 'Effective Communication, Creative Writing, 21st Century Literature',
                'avatar'          => 'teachers/jocelyn-fernandez.jpg',
            ],
            [
                'name'            => 'Antonio D. Cruz',
                'email'           => 'antonio.cruz@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2016-014',
                'sex'             => 'male',
                'date_of_birth'   => '1982-04-17',
                'contact_number'  => '+63 917 214 8802',
                'date_hired'      => '2016-06-06',
                'department'      => 'Mathematics',
                'specialization'  => 'General Mathematics, Pre-Calculus, Basic Calculus',
                'avatar'          => 'teachers/antonio-cruz.jpg',
            ],
            [
                'name'            => 'Rebecca S. Luna',
                'email'           => 'rebecca.luna@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2017-032',
                'sex'             => 'female',
                'date_of_birth'   => '1986-09-30',
                'contact_number'  => '+63 917 214 8803',
                'date_hired'      => '2017-06-05',
                'department'      => 'Science',
                'specialization'  => 'General Science, Biology, Chemistry',
                'avatar'          => 'teachers/rebecca-luna.jpg',
            ],
            [
                'name'            => 'Benjamin T. Ocampo',
                'email'           => 'benjamin.ocampo@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2015-045',
                'sex'             => 'male',
                'date_of_birth'   => '1984-07-08',
                'contact_number'  => '+63 917 214 8804',
                'date_hired'      => '2015-06-08',
                'department'      => 'Life and Career Skills',
                'specialization'  => 'Life and Career Skills, Personal Development, Guidance',
                'avatar'          => 'teachers/benjamin-ocampo.jpg',
            ],
            [
                'name'            => 'Lourdes M. Santos',
                'email'           => 'lourdes.santos@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2014-057',
                'sex'             => 'female',
                'date_of_birth'   => '1976-11-25',
                'contact_number'  => '+63 917 214 8805',
                'date_hired'      => '2014-06-09',
                'department'      => 'Araling Panlipunan / KASP',
                'specialization'  => 'Kasaysayan at Lipunang Pilipino, UCSP, Philippine Politics',
                'avatar'          => 'teachers/lourdes-santos.jpg',
            ],

            // ─── STEM cluster ────────────────────────────────────
            [
                'name'            => 'Dr. Ramon G. Villareal',
                'email'           => 'ramon.villareal@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2013-068',
                'sex'             => 'male',
                'date_of_birth'   => '1978-08-19',
                'contact_number'  => '+63 917 214 8806',
                'date_hired'      => '2013-06-10',
                'department'      => 'STEM – Mathematics',
                'specialization'  => 'Pre-Calculus, Basic Calculus, Advanced Mathematics',
                'avatar'          => 'teachers/ramon-villareal.jpg',
            ],
            [
                'name'            => 'Marilou V. Aquino',
                'email'           => 'marilou.aquino@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2018-079',
                'sex'             => 'female',
                'date_of_birth'   => '1988-12-04',
                'contact_number'  => '+63 917 214 8807',
                'date_hired'      => '2018-06-04',
                'department'      => 'STEM – Sciences',
                'specialization'  => 'Biology 1-4, Chemistry 1-4, Research Methods',
                'avatar'          => 'teachers/marilou-aquino.jpg',
            ],

            // ─── ARTS-SOC-HUM cluster ────────────────────────────
            [
                'name'            => 'Ricardo M. Aquino',
                'email'           => 'ricardo.aquino@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2016-091',
                'sex'             => 'male',
                'date_of_birth'   => '1979-12-11',
                'contact_number'  => '+63 917 214 8808',
                'date_hired'      => '2016-06-06',
                'department'      => 'Humanities / Social Sciences',
                'specialization'  => 'World Religions, Philippine Politics, Community Engagement',
                'avatar'          => 'teachers/ricardo-aquino.jpg',
            ],
            [
                'name'            => 'Hannah Kris A. Bautista',
                'email'           => 'hannah.bautista@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2020-103',
                'sex'             => 'female',
                'date_of_birth'   => '1994-10-03',
                'contact_number'  => '+63 917 214 8809',
                'date_hired'      => '2020-06-01',
                'department'      => 'Arts and Design',
                'specialization'  => 'Visual Arts, Media Arts, Creative Industries',
                'avatar'          => 'teachers/hannah-bautista.jpg',
            ],

            // ─── BUSINESS-ENTREP cluster ─────────────────────────
            [
                'name'            => 'Elizabeth P. Reyes',
                'email'           => 'elizabeth.reyes@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2016-115',
                'sex'             => 'female',
                'date_of_birth'   => '1983-05-14',
                'contact_number'  => '+63 917 214 8810',
                'date_hired'      => '2016-06-06',
                'department'      => 'Business and Entrepreneurship',
                'specialization'  => 'Basic Accounting, Business Finance, Entrepreneurship',
                'avatar'          => 'teachers/elizabeth-reyes.jpg',
            ],

            // ─── SPORTS-WELLNESS cluster ─────────────────────────
            [
                'name'            => 'Daniel M. Lim',
                'email'           => 'daniel.lim@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2018-127',
                'sex'             => 'male',
                'date_of_birth'   => '1990-01-12',
                'contact_number'  => '+63 917 214 8811',
                'date_hired'      => '2018-06-04',
                'department'      => 'Sports / PE and Health',
                'specialization'  => 'Sports Science, Sports Coaching, PE and Health',
                'avatar'          => 'teachers/daniel-lim.jpg',
            ],

            // ─── ICT cluster ─────────────────────────────────────
            [
                'name'            => 'Mark Anthony V. Reyes',
                'email'           => 'mark.reyes@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2019-138',
                'sex'             => 'male',
                'date_of_birth'   => '1992-05-14',
                'contact_number'  => '+63 917 214 8812',
                'date_hired'      => '2019-06-03',
                'department'      => 'ICT',
                'specialization'  => 'Computer Systems Servicing NC II, Programming (Java) NC III',
                'avatar'          => 'teachers/mark-reyes.jpg',
            ],
            [
                'name'            => 'Christian L. Bautista',
                'email'           => 'christian.bautista@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2020-149',
                'sex'             => 'male',
                'date_of_birth'   => '1993-09-27',
                'contact_number'  => '+63 917 214 8813',
                'date_hired'      => '2020-06-01',
                'department'      => 'ICT',
                'specialization'  => 'Programming (.NET) NC III, Broadband Installation, Contact Center Services',
                'avatar'          => 'teachers/christian-bautista.jpg',
            ],

            // ─── HOSPITALITY cluster ─────────────────────────────
            [
                'name'            => 'Marilou G. Dela Cruz',
                'email'           => 'marilou.delacruz@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2017-160',
                'sex'             => 'female',
                'date_of_birth'   => '1983-08-19',
                'contact_number'  => '+63 917 214 8814',
                'date_hired'      => '2017-06-05',
                'department'      => 'Hospitality and Tourism',
                'specialization'  => 'Cookery NC II, Bread and Pastry Production NC II, FBS NC II',
                'avatar'          => 'teachers/marilou-delacruz.jpg',
            ],
            [
                'name'            => 'Ernesto B. Padilla',
                'email'           => 'ernesto.padilla@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2018-171',
                'sex'             => 'male',
                'date_of_birth'   => '1985-11-08',
                'contact_number'  => '+63 917 214 8815',
                'date_hired'      => '2018-06-04',
                'department'      => 'Hospitality and Tourism',
                'specialization'  => 'Bartending NC II, Housekeeping NC II, Events Management',
                'avatar'          => 'teachers/ernesto-padilla.jpg',
            ],

            // ─── Industrial / Automotive / Construction clusters ─
            [
                'name'            => 'Rodolfo B. Villamor',
                'email'           => 'rodolfo.villamor@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2015-182',
                'sex'             => 'male',
                'date_of_birth'   => '1980-02-06',
                'contact_number'  => '+63 917 214 8816',
                'date_hired'      => '2015-06-08',
                'department'      => 'Industrial Technologies',
                'specialization'  => 'Air-Conditioning Installation NC III, Electrical Installation, Photovoltaic Systems',
                'avatar'          => 'teachers/rodolfo-villamor.jpg',
            ],
            [
                'name'            => 'Mario C. Villanueva',
                'email'           => 'mario.villanueva@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2016-193',
                'sex'             => 'male',
                'date_of_birth'   => '1981-06-27',
                'contact_number'  => '+63 917 214 8817',
                'date_hired'      => '2016-06-06',
                'department'      => 'Automotive and Construction',
                'specialization'  => 'Automotive Servicing NC II, Carpentry NC II, Masonry NC II',
                'avatar'          => 'teachers/mario-villanueva.jpg',
            ],

            // ─── Agri-Fishery cluster ────────────────────────────
            [
                'name'            => 'Rogelio S. Fernandez',
                'email'           => 'rogelio.fernandez@salawagshs.deped.gov.ph',
                'employee_no'     => 'TCH-2017-204',
                'sex'             => 'male',
                'date_of_birth'   => '1979-10-12',
                'contact_number'  => '+63 917 214 8818',
                'date_hired'      => '2017-06-05',
                'department'      => 'Agri-Fishery',
                'specialization'  => 'Agricultural Crops Production NC II, Aquaculture NC II, Agro-Entrepreneurship',
                'avatar'          => 'teachers/rogelio-fernandez.jpg',
            ],
        ];

        foreach ($teachers as $t) {
            // Upsert the user.
            $user = User::updateOrCreate(
                ['email' => $t['email']],
                [
                    'name'                 => $t['name'],
                    'avatar_path'          => $t['avatar'],
                    'password'             => Hash::make(self::DEMO_PASSWORD),
                    'must_change_password' => false,
                    'status'               => 'active',
                    'email_verified_at'    => $now,
                ]
            );

            $user->syncRoles(['teacher']);

            // Upsert the teachers profile row.
            Teacher::updateOrCreate(
                ['employee_no' => $t['employee_no']],
                [
                    'user_id'             => $user->id,
                    'employee_no'         => $t['employee_no'],
                    'sex'                 => $t['sex'],
                    'date_of_birth'       => $t['date_of_birth'],
                    'contact_number'      => $t['contact_number'],
                    'date_hired'          => $t['date_hired'],
                    'department'          => $t['department'],
                    'specialization'      => $t['specialization'],
                    'is_active'           => true,
                    'is_publicly_visible' => true,
                ]
            );
        }
    }
}