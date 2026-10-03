<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\AttendanceRecord;
use App\Models\ClassRoom;
use App\Models\ClassStudent;
use App\Models\ContactMessage;
use App\Models\Grade;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomActivitySeeder extends Seeder
{
    public function run(): void
    {
        $admin   = User::where('email', 'admin@test.com')->first()
                 ?? User::where('email', 'atanes.johnlloyd@ncst.edu.ph')->first();
        $teacher = User::where('email', 'teacher@test.com')->first();
        $student = User::where('email', 'student@test.com')->first();

        if (! $teacher || ! $student || ! $teacher->teacher || ! $student->student) {
            $this->command->warn('⚠ Test users not found — run TestUsersSeeder first.');
            return;
        }

        $teacherProfile = $teacher->teacher;
        $studentProfile = $student->student;

        // The classes owned by the test teacher (student is enrolled in all).
        $testClasses = ClassRoom::with('subject')->where('teacher_id', $teacherProfile->id)->get();

        // Broader pool for admin dashboards / reports to have variety.
        $allClasses = ClassRoom::with('subject', 'section')->get();

        // ═══════════════════════════════════════════════════════════
        // SCHOOL-WIDE ANNOUNCEMENTS
        // ═══════════════════════════════════════════════════════════
        $schoolWide = [
            ['Enrollment for S.Y. 2026–2027 is now open',
             'Enrollment will run until June 30. Please bring your Form 138, PSA birth certificate, and 2x2 ID photos.',
             true, 'urgent'],
            ['First Quarter Examination Schedule',
             'Quarterly exams will be held from October 12–16. Check your class schedule for room assignments.',
             false, 'important'],
            ['No classes on August 21 (Ninoy Aquino Day)',
             'Malacañang has declared August 21 a regular holiday. No synchronous or asynchronous classes.',
             false, 'normal'],
        ];
        foreach ($schoolWide as [$title, $body, $pinned, $priority]) {
            Announcement::create([
                'created_by'     => $admin->id,
                'class_id'       => null,
                'title'          => $title,
                'body'           => $body,
                'is_pinned'      => $pinned,
                'is_school_wide' => true,
                'priority'       => $priority,
                'published_at'   => now()->subDays(rand(1, 10)),
                'expires_at'     => now()->addDays(rand(15, 45)),
            ]);
        }

        // Class-scoped announcements from the test teacher.
        foreach ($testClasses as $class) {
            Announcement::create([
                'created_by'     => $teacher->id,
                'class_id'       => $class->id,
                'title'          => 'Welcome to ' . $class->subject->name,
                'body'           => 'Please review the course outline and prepare your notebooks for our first session. All activities will be posted here.',
                'is_pinned'      => true,
                'is_school_wide' => false,
                'priority'       => 'normal',
                'published_at'   => now()->subDays(5),
                'expires_at'     => now()->addDays(30),
            ]);

            Announcement::create([
                'created_by'     => $teacher->id,
                'class_id'       => $class->id,
                'title'          => 'Reminder: Submit pending requirements',
                'body'           => 'A few of you still have missing submissions. Please coordinate with me during consultation hours.',
                'is_pinned'      => false,
                'is_school_wide' => false,
                'priority'       => 'important',
                'published_at'   => now()->subDays(2),
                'expires_at'     => now()->addDays(14),
            ]);
        }

        // ═══════════════════════════════════════════════════════════
        // ASSIGNMENTS + SUBMISSIONS
        // ═══════════════════════════════════════════════════════════
                $categories = ['written_work','performance_task','quarterly_exam'];
        $dueOffsets = [-10, -3, 5];   // WW past, PT recent past, QE upcoming

        foreach ($testClasses as $class) {
            $enrolled = ClassStudent::where('class_id', $class->id)->pluck('student_id')->all();

            foreach ($categories as $catIdx => $category) {
                $assignment = Assignment::create([
                    'class_id'     => $class->id,
                    'title'        => match ($category) {
                        'written_work'     => 'Written Work #' . ($catIdx + 1) . ' — ' . $class->subject->name,
                        'performance_task' => 'Performance Task — ' . $class->subject->name,
                        default            => 'Quarterly Examination — ' . $class->subject->name,
                    },
                    'category'     => $category,
                    'instructions' => 'Read the attached module and submit on or before the deadline.',
                    'due_at'       => now()->addDays($dueOffsets[$catIdx]),
                    'points'       => 100,
                    'allow_late'   => true,
                    'is_published' => true,
                ]);

                foreach ($enrolled as $sIdx => $studentId) {
                    $isTestStudent = $studentId === $studentProfile->id;

                    if ($isTestStudent) {
                        // Leave the upcoming QE unsubmitted so it shows on the
                        // dashboard's "Upcoming Deadlines" widget.
                        if ($category === 'quarterly_exam') {
                            continue;
                        }

                        AssignmentSubmission::updateOrCreate(
                            ['assignment_id' => $assignment->id, 'student_id' => $studentId],
                            [
                                'submitted_at' => now()->subDays(2),
                                'file_path'    => "submissions/a{$assignment->id}/s{$studentId}.pdf",
                                'text_content' => 'Please see attached file for my submission.',
                                'status'       => 'graded',
                                'grade'        => rand(85, 96),
                                'feedback'     => 'Excellent work — well organized and clearly presented.',
                                'graded_by'    => $teacher->id,
                                'graded_at'    => now()->subDay(),
                            ]
                        );
                        continue;
                    }

                    $status = match ($sIdx % 4) {
                        0 => 'graded',
                        1 => 'submitted',
                        2 => 'late',
                        default => 'not_submitted',
                    };

                    AssignmentSubmission::create([
                        'assignment_id' => $assignment->id,
                        'student_id'    => $studentId,
                        'submitted_at'  => $status === 'not_submitted' ? null : now()->subDays(rand(1, 5)),
                        'file_path'     => $status === 'not_submitted' ? null : "submissions/a{$assignment->id}/s{$studentId}.pdf",
                        'text_content'  => $status === 'not_submitted' ? null : 'Please see attached file for my submission.',
                        'status'        => $status,
                        'grade'         => $status === 'graded' ? rand(75, 98) : null,
                        'feedback'      => $status === 'graded' ? 'Good work — see comments in the margins.' : null,
                        'graded_by'     => $status === 'graded' ? $teacher->id : null,
                        'graded_at'     => $status === 'graded' ? now()->subDay() : null,
                    ]);
                }
            }
        }

        // Lightweight assignments for OTHER classes so admin reports aren't empty.
        $otherClasses = $allClasses->whereNotIn('id', $testClasses->pluck('id'))->take(12);
        foreach ($otherClasses as $class) {
            $enrolled = ClassStudent::where('class_id', $class->id)->pluck('student_id')->take(4);

            $assignment = Assignment::create([
                'class_id'     => $class->id,
                'title'        => 'Written Work — ' . $class->subject->name,
                'category'     => 'written_work',
                'instructions' => 'Complete the practice exercises in your workbook.',
                'due_at'       => now()->addDays(10),
                'points'       => 100,
                'allow_late'   => true,
                'is_published' => true,
            ]);

            foreach ($enrolled as $studentId) {
                AssignmentSubmission::create([
                    'assignment_id' => $assignment->id,
                    'student_id'    => $studentId,
                    'submitted_at'  => now()->subDays(rand(1, 4)),
                    'status'        => 'submitted',
                    'text_content'  => 'See attached.',
                ]);
            }
        }

        // ═══════════════════════════════════════════════════════════
        // ATTENDANCE — last 10 weekdays for the test teacher's classes
        // ═══════════════════════════════════════════════════════════
        $attendanceDays = collect(range(1, 10))
            ->map(fn ($d) => now()->subWeekdays($d)->toDateString());

        foreach ($testClasses as $class) {
            $enrolled = ClassStudent::where('class_id', $class->id)->pluck('student_id')->all();

            foreach ($attendanceDays as $date) {
                foreach ($enrolled as $sIdx => $studentId) {
                    $status = $studentId === $studentProfile->id
                        ? 'present'
                        : match ($sIdx % 10) {
                            0, 7 => 'absent',
                            3    => 'late',
                            9    => 'excused',
                            default => 'present',
                        };

                    AttendanceRecord::updateOrCreate(
                        ['class_id' => $class->id, 'student_id' => $studentId, 'attendance_date' => $date],
                        [
                            'status'    => $status,
                            'notes'     => $status === 'excused' ? 'Medical certificate on file.' : null,
                            'marked_by' => $teacher->id,
                        ]
                    );
                }
            }
        }

        // ═══════════════════════════════════════════════════════════
        // GRADES — populated per class for the test student's gradebook
        // ═══════════════════════════════════════════════════════════
        foreach ($testClasses as $class) {
            $enrolled = ClassStudent::where('class_id', $class->id)->pluck('student_id')->all();

            foreach ($enrolled as $studentId) {
                $isTestStudent = $studentId === $studentProfile->id;

                $ww = $isTestStudent ? rand(88, 96) : rand(70, 98);
                $pt = $isTestStudent ? rand(88, 96) : rand(70, 98);
                $qe = $isTestStudent ? rand(88, 96) : rand(70, 98);
                $final = round(($ww * 0.25) + ($pt * 0.50) + ($qe * 0.25), 2);

                Grade::updateOrCreate(
                    ['class_id' => $class->id, 'student_id' => $studentId],
                    [
                        'written_work_score'      => $ww,
                        'performance_task_score'  => $pt,
                        'quarterly_exam_score'    => $qe,
                        'final_grade'             => $final,
                        'remarks'                 => $final >= 75 ? 'passed' : 'failed',
                        'is_finalized'            => true,
                        'finalized_by'            => $teacher->id,
                        'finalized_at'            => now()->subDays(2),
                    ]
                );
            }
        }

        // ═══════════════════════════════════════════════════════════
        // QUIZZES + QUESTIONS + ATTEMPTS + ANSWERS (test classes)
        // ═══════════════════════════════════════════════════════════
                $quizCategories = ['written_work', 'performance_task', 'quarterly_exam'];

        foreach ($testClasses as $cIdx => $class) {
            $quizCategory = $quizCategories[$cIdx % 3];

            $quiz = Quiz::create([
                'class_id'               => $class->id,
                'category'               => $quizCategory,
                'title'                  => 'Quiz ' . ($cIdx + 1) . ' — ' . $class->subject->name,
                'description'            => 'A short formative assessment covering the first two modules.',
                'instructions'           => 'Read each item carefully. You have 30 minutes.',
                'time_limit_minutes'     => 30,
                'attempts_allowed'       => 1,
                'shuffle_questions'      => true,
                'shuffle_options'        => true,
                'passing_score'          => 75,
                'available_from'         => now()->subDays(3),
                'available_until'        => now()->addDays(3),
                'is_published'           => true,
                'show_score_immediately' => true,
                'show_correct_answers'   => true,
                'show_explanations'      => true,
            ]);

            $questions = collect();
            for ($q = 1; $q <= 5; $q++) {
                $question = Question::create([
                    'teacher_id'    => $teacherProfile->id,
                    'subject_id'    => $class->subject_id,
                    'category'      => 'Quiz ' . ($cIdx + 1),
                    'type'          => 'multiple_choice',
                    'question_text' => "Sample question {$q} for {$class->subject->name}.",
                    'points'        => 2,
                    'explanation'   => "This is the explanation for question {$q}.",
                ]);

                foreach (['A','B','C','D'] as $oIdx => $label) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => "Option {$label} for Q{$q}",
                        'is_correct'  => $oIdx === 0,
                        'position'    => $oIdx,
                    ]);
                }

                QuizQuestion::create([
                    'quiz_id'     => $quiz->id,
                    'question_id' => $question->id,
                    'position'    => $q,
                ]);

                $questions->push($question);
            }

            $enrolled = ClassStudent::where('class_id', $class->id)
                ->pluck('student_id')
                ->take(5);

            // Deterministic score for test student per class index so WW/PT/QE
            // averages differ visibly across subjects.
            $testStudentScores = [8, 9, 7, 10, 6]; // out of 10
            $testScore = $testStudentScores[$cIdx % 5];

            foreach ($enrolled as $studentId) {
                $isTestStudent = $studentId === $studentProfile->id;
                $score = $isTestStudent ? $testScore : rand(4, 10);

                $attempt = QuizAttempt::create([
                    'quiz_id'        => $quiz->id,
                    'student_id'     => $studentId,
                    'attempt_number' => 1,
                    'started_at'     => now()->subHours(2),
                    'expires_at'     => now()->subHour(),
                    'submitted_at'   => now()->subHour()->subMinutes(3),
                    'status'         => 'graded',
                    'warning_count'  => 0,
                    'score'          => $score,
                ]);

                $correctCount = (int) ($score / 2);   // 2 pts per question

                foreach ($questions as $qi => $question) {
                    $correctOption = $question->options()->where('is_correct', true)->first();
                    $wrongOption   = $question->options()->where('is_correct', false)->first();

                    $isCorrect = $qi < $correctCount;
                    $chosen    = $isCorrect ? $correctOption : $wrongOption;

                    QuizAnswer::create([
                        'quiz_attempt_id'    => $attempt->id,
                        'question_id'        => $question->id,
                        'question_option_id' => $chosen?->id,
                        'is_correct'         => $isCorrect,
                        'points_awarded'     => $isCorrect ? 2 : 0,
                    ]);
                }
            }
        }

        // ═══════════════════════════════════════════════════════════
        // CONTACT MESSAGES (admin inbox)
        // ═══════════════════════════════════════════════════════════
        $messages = [
            ['Juan Dela Cruz', 'juan.delacruz@example.com', 'Inquiry about enrollment requirements', 'Good day! I would like to ask about the requirements for incoming Grade 11 students.', false],
            ['Maria Santos',   'maria.santos@example.com',  'Request for transcript of records',     'Hello, I would like to request a copy of my TOR. What is the process?',                true],
            ['Pedro Reyes',    'pedro.reyes@example.com',   'Schedule of entrance exam',             'When is the next entrance exam for transferees?',                                      false],
        ];
        foreach ($messages as [$name, $email, $subject, $body, $read]) {
            ContactMessage::create([
                'name'    => $name,
                'email'   => $email,
                'subject' => $subject,
                'message' => $body,
                'is_read' => $read,
            ]);
        }

        $this->command->info('✅ Classroom activity seeded:');
        $this->command->info('   ' . $testClasses->count() . ' test-teacher classes fully populated');
        $this->command->info('   Announcements, assignments, attendance, grades, quizzes, messages');
    }
}