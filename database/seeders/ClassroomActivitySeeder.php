<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\AttendanceRecord;
use App\Models\ClassRoom;
use App\Models\ClassStudent;
use App\Models\Grade;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ClassroomActivitySeeder extends Seeder
{
    public function run(): void
    {
        $adminId   = User::where('email', 'atanes.johnlloyd@ncst.edu.ph')->value('id') ?? 1;
        $teacher   = User::where('email', 'maria.rivera@deped.gov.ph')->first();
        $student   = User::where('email', 'rosario.cruz0@student.deped.gov.ph')->first();

        $rooms    = Room::pluck('id')->all();
        $classes  = ClassRoom::with('section')->take(12)->get();

        if ($classes->isEmpty()) {
            $this->command->warn('No classes found — run DemoDataSeeder first.');
            return;
        }

        // ─── CLASS SCHEDULES ────────────────────────────────────
        $days   = ['monday','tuesday','wednesday','thursday','friday'];
        $slots  = [['07:30:00','08:30:00'], ['08:30:00','09:30:00'], ['10:00:00','11:00:00'], ['13:00:00','14:00:00']];

        foreach ($classes as $i => $class) {
            $day  = $days[$i % count($days)];
            $slot = $slots[$i % count($slots)];
            \DB::table('class_schedules')->updateOrInsert(
                ['class_id' => $class->id, 'day_of_week' => $day, 'time_start' => $slot[0]],
                [
                    'room_id'   => $rooms[$i % count($rooms)],
                    'time_end'  => $slot[1],
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ]
            );
        }

        // ─── ANNOUNCEMENTS ──────────────────────────────────────
        $announcements = [
            ['Enrollment for S.Y. 2026–2027 is now open', 'Enrollment will run until June 30. Please bring your Form 138, PSA birth certificate, and 2x2 ID photos.', true, true,  $adminId, null],
            ['First Quarter Examination Schedule',       'Quarterly exams will be held from October 12–16. Check your class schedule for room assignments.',        false, true, $adminId, null],
            ['No classes on August 21 (Ninoy Aquino Day)', 'Malacañang has declared August 21 a regular holiday. No synchronous or asynchronous classes.',         false, true, $adminId, null],
            ['Grade 11 STEM — Lab Safety Orientation',   'All STEM students must attend the lab safety orientation on Friday, 1:00 PM at the Science Lab.',        false, false, $teacher?->id, $classes->first()->id],
            ['Grade 12 ICT — Capstone Proposal Due',     'Submit your capstone project proposal to your adviser by Friday, 5:00 PM.',                             false, false, $teacher?->id, $classes->skip(1)->first()->id],
        ];
        foreach ($announcements as [$title, $body, $pinned, $schoolWide, $author, $classId]) {
            Announcement::create([
                'created_by'      => $author,
                'class_id'        => $classId,
                'title'           => $title,
                'body'            => $body,
                'is_pinned'       => $pinned,
                'is_school_wide'  => $schoolWide,
                'published_at'    => now()->subDays(random_int(1, 20)),
                'expires_at'      => now()->addDays(random_int(15, 45)),
            ]);
        }

        // ─── ASSIGNMENTS + SUBMISSIONS ──────────────────────────
        $categories = ['written_work','performance_task','quarterly_exam'];

        foreach ($classes->take(6) as $cIdx => $class) {
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
                    'instructions' => 'Read the instructions in the attached module and submit on or before the deadline.',
                    'due_at'       => now()->addDays(7 + $catIdx * 5),
                    'points'       => 100,
                    'allow_late'   => true,
                    'is_published' => true,
                ]);

                // Grade a handful of students with a mix of statuses.
                foreach (array_slice($enrolled, 0, 6) as $sIdx => $studentId) {
                    $status = match ($sIdx % 4) {
                        0 => 'graded',
                        1 => 'submitted',
                        2 => 'late',
                        default => 'not_submitted',
                    };

                    AssignmentSubmission::create([
                        'assignment_id' => $assignment->id,
                        'student_id'    => $studentId,
                        'submitted_at'  => $status === 'not_submitted' ? null : now()->subDays(random_int(1, 5)),
                        'file_path'     => $status === 'not_submitted' ? null : "submissions/a{$assignment->id}/s{$studentId}.pdf",
                        'text_content'  => $status === 'not_submitted' ? null : 'Please see attached file for my submission.',
                        'status'        => $status,
                        'grade'         => $status === 'graded' ? random_int(75, 98) : null,
                        'feedback'      => $status === 'graded' ? 'Good work — see comments in the margins.' : null,
                        'graded_by'     => $status === 'graded' ? $teacher?->id : null,
                        'graded_at'     => $status === 'graded' ? now()->subDays(1) : null,
                    ]);
                }
            }
        }

        // ─── ATTENDANCE (last 5 school days for a few classes) ──
        $attendanceDays = collect(range(1, 5))->map(fn ($d) => now()->subWeekdays($d)->toDateString());
        foreach ($classes->take(4) as $class) {
            $enrolled = ClassStudent::where('class_id', $class->id)->pluck('student_id')->all();
            foreach ($attendanceDays as $date) {
                foreach ($enrolled as $sIdx => $studentId) {
                    AttendanceRecord::updateOrCreate(
                        ['class_id' => $class->id, 'student_id' => $studentId, 'attendance_date' => $date],
                        [
                            'status'    => match ($sIdx % 10) {
                                0, 7 => 'absent',
                                3    => 'late',
                                9    => 'excused',
                                default => 'present',
                            },
                            'notes'     => $sIdx % 10 === 9 ? 'Medical certificate on file.' : null,
                            'marked_by' => $teacher?->id,
                        ]
                    );
                }
            }
        }

        // ─── GRADES ─────────────────────────────────────────────
        foreach ($classes->take(8) as $class) {
            $enrolled = ClassStudent::where('class_id', $class->id)->pluck('student_id')->all();
            foreach ($enrolled as $studentId) {
                $ww = random_int(70, 98);
                $pt = random_int(70, 98);
                $qe = random_int(70, 98);
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
                        'finalized_by'            => $teacher?->id,
                        'finalized_at'            => now()->subDays(2),
                    ]
                );
            }
        }

        // ─── QUIZZES + QUESTIONS + ATTEMPTS ─────────────────────
        if ($teacher) {
            foreach ($classes->take(3) as $class) {
                $quiz = Quiz::create([
                    'class_id'                 => $class->id,
                    'title'                    => 'Quiz 1 — ' . $class->subject->name,
                    'description'              => 'A short formative assessment covering the first two modules.',
                    'instructions'             => 'Read each item carefully. You have 30 minutes.',
                    'time_limit_minutes'       => 30,
                    'attempts_allowed'         => 1,
                    'shuffle_questions'        => true,
                    'shuffle_options'          => true,
                    'passing_score'            => 75,
                    'available_from'           => now()->subDays(3),
                    'available_until'          => now()->addDays(3),
                    'is_published'             => true,
                    'show_score_immediately'   => true,
                    'show_correct_answers'     => true,
                    'show_explanations'        => true,
                ]);

                for ($q = 1; $q <= 5; $q++) {
                    $question = Question::create([
                        'teacher_id'    => $teacher->id,
                        'subject_id'    => $class->subject_id,
                        'category'      => 'Quiz 1',
                        'type'          => 'multiple_choice',
                        'question_text' => "Sample question {$q} for {$class->subject->name}.",
                        'points'        => 1,
                        'explanation'   => "This is the explanation for question {$q}.",
                    ]);

                    foreach (['A','B','C','D'] as $oIdx => $label) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => "Option {$label}",
                            'is_correct'  => $oIdx === 0,
                            'position'    => $oIdx,
                        ]);
                    }

                    QuizQuestion::create([
                        'quiz_id'     => $quiz->id,
                        'question_id' => $question->id,
                        'position'    => $q,
                    ]);
                }

                // A couple of attempts per quiz
                $enrolled = ClassStudent::where('class_id', $class->id)->pluck('student_id')->take(3);
                foreach ($enrolled as $sIdx => $studentId) {
                    QuizAttempt::create([
                        'quiz_id'        => $quiz->id,
                        'student_id'     => $studentId,
                        'attempt_number' => 1,
                        'started_at'     => now()->subDays(1)->subMinutes(35),
                        'expires_at'     => now()->subDays(1),
                        'submitted_at'   => now()->subDays(1)->subMinutes(2),
                        'score'          => random_int(60, 100),
                        'status'         => 'graded',
                        'warning_count'  => 0,
                    ]);
                }
            }
        }

        // ─── CONTACT MESSAGES ───────────────────────────────────
        $messages = [
            ['Juan Dela Cruz', 'juan.delacruz@example.com', 'Inquiry about enrollment requirements', 'Good day! I would like to ask about the requirements for incoming Grade 11 students.', false],
            ['Maria Santos',   'maria.santos@example.com',  'Request for transcript of records',     'Hello, I would like to request a copy of my TOR. What is the process?',                true],
            ['Pedro Reyes',    'pedro.reyes@example.com',   'Schedule of entrance exam',             'When is the next entrance exam for transferees?',                                      false],
        ];
        foreach ($messages as [$name, $email, $subject, $body, $read]) {
            \App\Models\ContactMessage::create([
                'name'    => $name,
                'email'   => $email,
                'subject' => $subject,
                'message' => $body,
                'is_read' => $read,
            ]);
        }

        $this->command->info('✅ Classroom activity seeded (schedules, announcements, assignments, attendance, grades, quizzes, messages).');
    }
}