<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('local');
    }

    public function test_student_can_submit_with_file(): void
    {
        $classroom = ClassRoom::first();
        $studentUser = User::where('id', $classroom->students->first()->user_id)->first();

        $assignment = Assignment::create([
            'class_id'     => $classroom->id,
            'title'        => 'File Upload Test',
            'due_at'       => now()->addDays(2),
            'points'       => 100,
            'is_published' => true,
            'allow_late'   => true,
        ]);

        $file = UploadedFile::fake()->create('essay.pdf', 500, 'application/pdf');

        $response = $this->actingAs($studentUser)->post(
            route('student.assignments.submit', $assignment->id),
            ['file' => $file]
        );

        $response->assertRedirect();

        $submission = $assignment->submissions()->first();
        $this->assertNotNull($submission->file_path);
        Storage::disk('local')->assertExists($submission->file_path);
    }

    public function test_file_type_is_validated(): void
    {
        $classroom = ClassRoom::first();
        $studentUser = User::where('id', $classroom->students->first()->user_id)->first();

        $assignment = Assignment::create([
            'class_id'     => $classroom->id,
            'title'        => 'Test',
            'due_at'       => now()->addDays(2),
            'points'       => 100,
            'is_published' => true,
            'allow_late'   => true,
        ]);

        $badFile = UploadedFile::fake()->create('malware.exe', 100);

        $this->actingAs($studentUser)->post(
            route('student.assignments.submit', $assignment->id),
            ['file' => $badFile]
        )->assertSessionHasErrors('file');
    }

    public function test_teacher_can_download_student_submission(): void
    {
        $classroom = ClassRoom::first();
        $teacher = User::find($classroom->teacher->user_id);
        $studentUser = User::where('id', $classroom->students->first()->user_id)->first();

        $assignment = Assignment::create([
            'class_id'     => $classroom->id,
            'title'        => 'Download Test',
            'due_at'       => now()->addDays(2),
            'points'       => 100,
            'is_published' => true,
            'allow_late'   => true,
        ]);

        $file = UploadedFile::fake()->create('answer.pdf', 200, 'application/pdf');

        $this->actingAs($studentUser)->post(
            route('student.assignments.submit', $assignment->id),
            ['file' => $file]
        );

        $submission = $assignment->submissions()->first();
        $this->assertNotNull($submission->file_path);

        $response = $this->actingAs($teacher)->get(
            route('teacher.submissions.download', $submission->id)
        );

        $response->assertOk();
        $response->assertDownload();
    }
}