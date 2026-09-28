<?php

namespace Tests\Feature;

use App\Models\AttendanceRecord;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    protected function teacherAndClass(): array
    {
        $teacher = User::where('email', 'teacher@test.com')->first();
        $classroom = ClassRoom::where('teacher_id', $teacher->teacher->id)->first();
        return [$teacher, $classroom];
    }

    public function test_teacher_can_view_empty_attendance_index(): void
    {
        [$teacher, $classroom] = $this->teacherAndClass();

        $this->actingAs($teacher)
            ->getJson(route('teacher.classes.attendance.index', $classroom->id))
            ->assertOk()
            ->assertJsonStructure([
                'classroom', 'sessions', 'enrolled_students',
            ]);
    }

    public function test_teacher_can_fetch_session_roster(): void
    {
        [$teacher, $classroom] = $this->teacherAndClass();

        $response = $this->actingAs($teacher)
            ->getJson(route('teacher.classes.attendance.session', [
                'classroom' => $classroom->id,
                'date'      => '2026-09-18',
            ]));

        $response->assertOk()
                 ->assertJsonStructure([
                     'classroom',
                     'date',
                     'is_marked',
                     'students' => [['id', 'name', 'lrn', 'status', 'notes']],
                 ]);

        $this->assertFalse($response->json('is_marked'));
    }

    public function test_teacher_can_bulk_mark_attendance(): void
    {
        [$teacher, $classroom] = $this->teacherAndClass();

        $studentIds = $classroom->students()->pluck('students.id')->all();
        $this->assertGreaterThan(0, count($studentIds));

        $records = collect($studentIds)->map(fn ($id, $i) => [
            'student_id' => $id,
            'status'     => $i === 0 ? 'absent' : 'present',
            'notes'      => $i === 0 ? 'Sick' : null,
        ])->all();

        $response = $this->actingAs($teacher)->postJson(
            route('teacher.classes.attendance.mark', [
                'classroom' => $classroom->id,
                'date'      => '2026-09-18',
            ]),
            ['records' => $records]
        );

        $response->assertOk();
        $this->assertSame(count($studentIds), AttendanceRecord::count());

        // First student should be absent
        $first = AttendanceRecord::where('student_id', $studentIds[0])->first();
        $this->assertSame('absent', $first->status);
        $this->assertSame('Sick', $first->notes);
    }

    public function test_teacher_can_update_existing_attendance(): void
    {
        [$teacher, $classroom] = $this->teacherAndClass();
        $studentId = $classroom->students()->first()->id;

        $url = route('teacher.classes.attendance.mark', [
            'classroom' => $classroom->id,
            'date'      => '2026-09-18',
        ]);

        // Mark present
        $this->actingAs($teacher)->postJson($url, [
            'records' => [['student_id' => $studentId, 'status' => 'present']],
        ])->assertOk();

        // Change to absent
        $this->actingAs($teacher)->postJson($url, [
            'records' => [['student_id' => $studentId, 'status' => 'absent', 'notes' => 'No show']],
        ])->assertOk();

        $this->assertSame(
            'absent',
            AttendanceRecord::where('student_id', $studentId)->first()->status
        );
        $this->assertSame(1, AttendanceRecord::where('student_id', $studentId)->count());
    }

    public function test_teacher_cannot_mark_student_from_other_class(): void
    {
        [$teacher, $classroom] = $this->teacherAndClass();

        $otherStudent = \App\Models\Student::whereDoesntHave('classes', function ($q) use ($classroom) {
            $q->where('classes.id', $classroom->id);
        })->first();

        if (! $otherStudent) {
            $this->markTestSkipped('No unenrolled student for test');
        }

        $this->actingAs($teacher)->postJson(
            route('teacher.classes.attendance.mark', [
                'classroom' => $classroom->id,
                'date'      => '2026-09-18',
            ]),
            ['records' => [['student_id' => $otherStudent->id, 'status' => 'present']]]
        )->assertStatus(422);
    }

    public function test_invalid_date_is_rejected(): void
    {
        [$teacher, $classroom] = $this->teacherAndClass();

        $this->actingAs($teacher)
            ->getJson(route('teacher.classes.attendance.session', [
                'classroom' => $classroom->id,
                'date'      => 'not-a-date',
            ]))
            ->assertStatus(422);
    }

    public function test_teacher_can_view_student_history(): void
    {
        [$teacher, $classroom] = $this->teacherAndClass();
        $studentId = $classroom->students()->first()->id;

        // Add 3 records
        foreach (['2026-09-16', '2026-09-17', '2026-09-18'] as $i => $date) {
            AttendanceRecord::create([
                'class_id'        => $classroom->id,
                'student_id'      => $studentId,
                'attendance_date' => $date,
                'status'          => ['present', 'late', 'absent'][$i],
                'marked_by'       => $teacher->id,
            ]);
        }

        $response = $this->actingAs($teacher)->getJson(
            route('teacher.classes.attendance.student', [
                'classroom' => $classroom->id,
                'student'   => $studentId,
            ])
        );

        $response->assertOk();
        $this->assertSame(3, $response->json('summary.total_sessions'));
        $this->assertSame(1, $response->json('summary.present'));
        $this->assertSame(1, $response->json('summary.late'));
        $this->assertSame(1, $response->json('summary.absent'));
    }

    public function test_student_can_view_own_attendance_overview(): void
    {
        $student = User::where('email', 'student@test.com')->first();

        $response = $this->actingAs($student)
            ->getJson(route('student.attendance.index'));

        $response->assertOk()
                 ->assertJsonStructure([
                     'active_term',
                     'classes' => [['class_id', 'subject', 'total_sessions', 'present', 'attendance_rate']],
                 ]);
    }

    public function test_student_can_view_class_attendance_detail(): void
    {
        $student = User::where('email', 'student@test.com')->first();
        $classroom = $student->student->classes()->first();

        // Add a few attendance records
        foreach (['2026-09-17', '2026-09-18'] as $date) {
            AttendanceRecord::create([
                'class_id'        => $classroom->id,
                'student_id'      => $student->student->id,
                'attendance_date' => $date,
                'status'          => 'present',
            ]);
        }

        $response = $this->actingAs($student)
            ->getJson(route('student.classes.attendance.show', $classroom->id));

        $response->assertOk();
        $this->assertSame(2, $response->json('summary.total_sessions'));
        $this->assertSame(2, $response->json('summary.present'));
    }

    public function test_student_cannot_view_other_class_attendance(): void
    {
        $student = User::where('email', 'student@test.com')->first();
        $otherClass = ClassRoom::whereDoesntHave('students', function ($q) use ($student) {
            $q->where('students.id', $student->student->id);
        })->first();

        $this->actingAs($student)
            ->getJson(route('student.classes.attendance.show', $otherClass->id))
            ->assertForbidden();
    }

    public function test_teacher_cannot_access_another_teachers_class_attendance(): void
    {
        $teacher = User::where('email', 'teacher@test.com')->first();
        $otherClass = ClassRoom::where('teacher_id', '!=', $teacher->teacher->id)->first();

        $this->actingAs($teacher)
            ->getJson(route('teacher.classes.attendance.index', $otherClass->id))
            ->assertForbidden();
    }
}