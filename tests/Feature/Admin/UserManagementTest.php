<?php

namespace Tests\Feature\Admin;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function admin(): User
    {
        return User::where('email', 'admin@test.com')->first();
    }

    public function test_admin_can_create_teacher(): void
    {
        $response = $this->actingAs($this->admin())->postJson(
            route('admin.teachers.store'),
            [
                'name'           => 'Ms. Maria Santos',
                'email'          => 'maria@school.test',
                'password'       => 'Str0ng!Temp2026',
                'employee_no'    => 'EMP-999',
                'sex'            => 'female',
                'date_of_birth'  => '1990-05-15',
                'contact_number' => '09171234567',
                'department'     => 'Sciences',
                'specialization' => 'Biology',
            ]
        );

        $response->assertCreated();

        $user = User::where('email', 'maria@school.test')->first();
        $this->assertTrue($user->hasRole('teacher'));
        $this->assertTrue($user->must_change_password);
        $this->assertNotNull($user->teacher);
    }

    public function test_admin_can_create_student(): void
    {
        $response = $this->actingAs($this->admin())->postJson(
            route('admin.students.store'),
            [
                'name'          => 'Juan Dela Cruz',
                'email'         => 'juan@student.test',
                'password'      => 'Str0ng!Temp2026',
                'lrn'           => '999888777666',
                'sex'           => 'male',
                'date_of_birth' => '2009-03-20',
            ]
        );

        $response->assertCreated();

        $user = User::where('email', 'juan@student.test')->first();
        $this->assertTrue($user->hasRole('student'));
        $this->assertTrue($user->must_change_password);
    }

    public function test_admin_can_reset_student_password(): void
    {
        $student = Student::first();

        $response = $this->actingAs($this->admin())
            ->postJson(route('admin.students.reset-password', $student->id));

        $response->assertOk();
        $this->assertTrue($student->fresh()->user->must_change_password);
    }
}