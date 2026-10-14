<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\AttendanceRecord;
use App\Models\ClassModule;
use App\Models\ClassRoom;
use App\Models\ClassStudent;
use App\Models\ContactMessage;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Grade;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Models\Message;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Term;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomActivitySeeder extends Seeder
{
    public function run(): void
    {
        $admin   = User::where('email', 'sysadmin@salawagshs.deped.gov.ph')->first();
        $teacher = User::where('email', 'jocelyn.fernandez@salawagshs.deped.gov.ph')->first();
        $student = User::where('email', 'rosario.reyes@gmail.com')->first();

        if (! $admin || ! $teacher || ! $student) {
            $this->command->error('  ! Missing required users — run earlier seeders first.');
            return;
        }

        $this->seedClassStudents();
        $this->seedAttendanceAndGrades($admin);
        $this->seedModulesAndLessons();
        $this->seedAssignments($teacher);
        $this->seedQuizzes($teacher);
        $this->seedAnnouncements($admin);
        $this->seedContactMessages();
        $this->seedConversations($admin, $teacher, $student);
        $this->seedAuditLogs($admin);

        $this->seedPlaceholderFiles();

        $this->command->info('✔ Classroom activity seeded.');
        $this->command->info('   class_students:        ' . ClassStudent::count());
        $this->command->info('   attendance_records:    ' . AttendanceRecord::count());
        $this->command->info('   grades:                ' . Grade::count());
        $this->command->info('   assignments:           ' . Assignment::count());
        $this->command->info('   quizzes:               ' . Quiz::count());
        $this->command->info('   announcements:         ' . Announcement::count());
    }

    /* =================================================================
     | SHARED TERM RESOLVER
     |-----------------------------------------------------------------
     | Works for both the old 2-semester calendar and the new 3-term
     | calendar. Prefers the active term; falls back to the earliest.
     * ================================================================= */
    protected function activeTerm(): ?Term
    {
        $year = SchoolYear::where('is_active', true)->first();
        if (! $year) {
            return null;
        }

        return Term::where('school_year_id', $year->id)
            ->where('is_active', true)
            ->first()
            ?? Term::where('school_year_id', $year->id)
                ->orderBy('start_date')
                ->first();
    }

    /* =================================================================
     | 1. CLASS_STUDENTS
     *================================================================= */
    protected function seedClassStudents(): void
    {
        $enrollments = DB::table('enrollments')->get();

        foreach ($enrollments as $en) {
            if (! $en->section_id) {
                continue;
            }

            $classIds = ClassRoom::where('section_id', $en->section_id)->pluck('id');

            foreach ($classIds as $classId) {
                ClassStudent::updateOrCreate(
                    ['class_id' => $classId, 'student_id' => $en->student_id],
                    [
                        'status'      => $en->status === 'completed' ? 'completed' : 'active',
                        'enrolled_at' => $en->enrolled_at ?? now(),
                    ]
                );
            }
        }
    }

    /* =================================================================
     | 2. ATTENDANCE + GRADES
     *================================================================= */
    protected function seedAttendanceAndGrades(?User $admin): void
    {
        $term = $this->activeTerm();
        if (! $term) {
            return;
        }

        $classes = ClassRoom::where('term_id', $term->id)
            ->with('section')
            ->get();

        // Last 25 weekdays — attendance horizon.
        $days = collect();
        $cursor = Carbon::now();
        while ($days->count() < 25) {
            if (! $cursor->isWeekend()) {
                $days->push($cursor->toDateString());
            }
            $cursor->subDay();
        }

        foreach ($classes as $class) {
            $studentIds = ClassStudent::where('class_id', $class->id)
                ->pluck('student_id')
                ->all();

            if (empty($studentIds)) {
                continue;
            }

            foreach ($days as $date) {
                foreach ($studentIds as $sIdx => $studentId) {
                    $status = match (($studentId + $sIdx + crc32($date)) % 25) {
                        0, 1   => 'absent',
                        2      => 'late',
                        3      => 'excused',
                        default => 'present',
                    };

                    AttendanceRecord::updateOrCreate(
                        [
                            'class_id'        => $class->id,
                            'student_id'      => $studentId,
                            'attendance_date' => $date,
                        ],
                        [
                            'status'    => $status,
                            'notes'     => $status === 'excused' ? 'Medical certificate on file.' : null,
                            'marked_by' => $class->teacher_id
                                ? Teacher::find($class->teacher_id)?->user_id
                                : $admin?->id,
                        ]
                    );
                }
            }

            foreach ($studentIds as $studentId) {
                $seed = $class->id * 31 + $studentId;
                $ww   = 78 + ($seed % 18);
                $pt   = 80 + (($seed >> 3) % 16);
                $qe   = 76 + (($seed >> 5) % 20);
                $final = round(($ww * 0.25) + ($pt * 0.50) + ($qe * 0.25), 2);

                Grade::updateOrCreate(
                    ['class_id' => $class->id, 'student_id' => $studentId],
                    [
                        'written_work_score'     => $ww,
                        'performance_task_score' => $pt,
                        'quarterly_exam_score'   => $qe,
                        'final_grade'            => $final,
                        'remarks'                => $final >= 75 ? 'passed' : 'failed',
                        'is_finalized'           => true,
                        'finalized_by'           => $class->teacher_id
                            ? Teacher::find($class->teacher_id)?->user_id
                            : $admin?->id,
                        'finalized_at'           => Carbon::now()->subDays(7),
                    ]
                );
            }
        }

        // Historical grades for graduated cohorts — final year only.
        $gradStudents = Student::where('status', 'graduated')->pluck('id')->all();
        if (empty($gradStudents)) {
            return;
        }

        $histYears = ['2024-2025', '2025-2026'];
        foreach ($histYears as $label) {
            $year = SchoolYear::where('label', $label)->first();
            if (! $year) {
                continue;
            }
            $histTerm = Term::where('school_year_id', $year->id)
                ->where('name', '1st Semester')
                ->first();
            if (! $histTerm) {
                continue;
            }

            $histClasses = ClassRoom::where('term_id', $histTerm->id)->get();

            foreach ($histClasses as $class) {
                foreach ($gradStudents as $studentId) {
                    $seed = $class->id * 17 + $studentId + crc32($label);
                    $ww   = 80 + ($seed % 15);
                    $pt   = 82 + (($seed >> 2) % 14);
                    $qe   = 79 + (($seed >> 4) % 18);
                    $final = round(($ww * 0.25) + ($pt * 0.50) + ($qe * 0.25), 2);

                    Grade::updateOrCreate(
                        ['class_id' => $class->id, 'student_id' => $studentId],
                        [
                            'written_work_score'     => $ww,
                            'performance_task_score' => $pt,
                            'quarterly_exam_score'   => $qe,
                            'final_grade'            => $final,
                            'remarks'                => $final >= 75 ? 'passed' : 'failed',
                            'is_finalized'           => true,
                            'finalized_by'           => $admin?->id,
                            'finalized_at'           => Carbon::parse($year->end_date),
                        ]
                    );
                }
            }
        }
    }

    /* =================================================================
     | 3. MODULES + LESSONS
     *================================================================= */
    protected function seedModulesAndLessons(): void
    {
        $term = $this->activeTerm();
        if (! $term) {
            return;
        }

        $classes = ClassRoom::where('term_id', $term->id)
            ->with('subject')
            ->take(15)
            ->get();

        foreach ($classes as $class) {
            $subjectName = $class->subject?->name ?? 'Subject';

            foreach ([1, 2] as $m) {
                $module = ClassModule::updateOrCreate(
                    ['class_id' => $class->id, 'position' => $m - 1],
                    [
                        'title'        => "Module {$m}: " . ($m === 1 ? 'Foundations' : 'Applications') . " of {$subjectName}",
                        'description'  => "Covers key concepts, worked examples, and formative activities for {$subjectName}.",
                        'is_published' => true,
                    ]
                );

                for ($l = 1; $l <= 3; $l++) {
                    $lesson = Lesson::updateOrCreate(
                        ['class_id' => $class->id, 'position' => ($m - 1) * 3 + ($l - 1)],
                        [
                            'class_module_id' => $module->id,
                            'title'           => "Lesson {$m}.{$l} — " . $this->lessonTitle($l),
                            'body'            => "In this lesson, learners will explore {$this->lessonTopic($l)}. Review the attached handout and complete the practice items at the end of the section.",
                            'thumbnail_path'  => "lessons/{$class->id}-m{$m}l{$l}.jpg",
                            'is_published'    => true,
                        ]
                    );

                    LessonAttachment::updateOrCreate(
                        ['lesson_id' => $lesson->id, 'file_name' => "Lesson_{$m}_{$l}_Handout.pdf"],
                        [
                            'file_path' => "lessons/{$class->id}/l{$lesson->id}-handout.pdf",
                            'mime_type' => 'application/pdf',
                            'file_size' => 200_000 + ($lesson->id * 137) % 1_500_000,
                        ]
                    );

                    LessonAttachment::updateOrCreate(
                        ['lesson_id' => $lesson->id, 'file_name' => "Lesson_{$m}_{$l}_Slides.pptx"],
                        [
                            'file_path' => "lessons/{$class->id}/l{$lesson->id}-slides.pptx",
                            'mime_type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'file_size' => 800_000 + ($lesson->id * 213) % 5_000_000,
                        ]
                    );
                }
            }
        }
    }

    /* =================================================================
     | 4. ASSIGNMENTS + SUBMISSIONS
     *================================================================= */
    protected function seedAssignments(?User $teacher): void
    {
        $term = $this->activeTerm();
        if (! $term) {
            return;
        }

        $classes = ClassRoom::where('term_id', $term->id)
            ->with('subject')
            ->take(15)
            ->get();

        $assignmentPlan = [
            ['written_work',     'Written Work #1 — Concept Review',    -14, true],
            ['written_work',     'Written Work #2 — Applied Exercises', -7,  true],
            ['performance_task', 'Performance Task — Group Presentation', -3, true],
            ['performance_task', 'Performance Task — Individual Output', 4,  true],
            ['quarterly_exam',   'Quarterly Examination',                 12, false],
        ];

        foreach ($classes as $class) {
            $studentIds = ClassStudent::where('class_id', $class->id)->pluck('student_id')->all();
            $subjectName = $class->subject?->name ?? 'Subject';

            foreach ($assignmentPlan as $idx => [$cat, $titleSuffix, $dueOffset, $isPublished]) {
                $assignment = Assignment::updateOrCreate(
                    ['class_id' => $class->id, 'title' => "{$titleSuffix} — {$subjectName}"],
                    [
                        'category'     => $cat,
                        'instructions' => 'Read the attached module and submit on or before the deadline. Late submissions are accepted with a penalty.',
                        'due_at'       => Carbon::now()->addDays($dueOffset),
                        'points'       => 100,
                        'allow_late'   => true,
                        'is_published' => $isPublished,
                    ]
                );

                foreach ($studentIds as $sIdx => $studentId) {
                    if ($cat === 'quarterly_exam') {
                        AssignmentSubmission::updateOrCreate(
                            ['assignment_id' => $assignment->id, 'student_id' => $studentId],
                            ['status' => 'not_submitted']
                        );
                        continue;
                    }

                    $status = match (($studentId + $idx + $sIdx) % 10) {
                        0       => 'not_submitted',
                        1       => 'late',
                        2, 3    => 'submitted',
                        default => 'graded',
                    };

                    $submittedAt = in_array($status, ['submitted', 'graded', 'late'], true)
                        ? Carbon::now()->subDays(2 + ($studentId % 5))
                        : null;

                    AssignmentSubmission::updateOrCreate(
                        ['assignment_id' => $assignment->id, 'student_id' => $studentId],
                        [
                            'submitted_at' => $submittedAt,
                            'file_path'    => $submittedAt ? "submissions/a{$assignment->id}/s{$studentId}.pdf" : null,
                            'text_content' => $submittedAt ? 'Please see the attached file for my submission.' : null,
                            'status'       => $status,
                            'grade'        => $status === 'graded' ? 78 + ($studentId % 20) : null,
                            'feedback'     => $status === 'graded' ? 'Well organized. See margin notes for improvement areas.' : null,
                            'graded_by'    => $status === 'graded' ? $teacher?->id : null,
                            'graded_at'    => $status === 'graded' ? Carbon::now()->subDay() : null,
                        ]
                    );
                }
            }
        }
    }

    /* =================================================================
     | 5. QUIZZES + QUESTIONS + ATTEMPTS + ANSWERS
     *================================================================= */
    protected function seedQuizzes(?User $teacher): void
    {
        $term = $this->activeTerm();
        if (! $term) {
            return;
        }

        $classes = ClassRoom::where('term_id', $term->id)
            ->with(['subject', 'teacher'])
            ->take(10)
            ->get();

        foreach ($classes as $cIdx => $class) {
            $teacherProfile = $class->teacher;
            if (! $teacherProfile) {
                continue;
            }

            $subjectName = $class->subject?->name ?? 'Subject';

            $quiz = Quiz::updateOrCreate(
                ['class_id' => $class->id, 'title' => "Quiz 1 — {$subjectName}"],
                [
                    'class_module_id'        => null,
                    'category'               => 'written_work',
                    'description'            => 'A short formative assessment covering Module 1.',
                    'instructions'           => 'Read each item carefully. You have 30 minutes.',
                    'time_limit_minutes'     => 30,
                    'attempts_allowed'       => 1,
                    'shuffle_questions'      => true,
                    'shuffle_options'        => true,
                    'passing_score'          => 75,
                    'available_from'         => Carbon::now()->subDays(5),
                    'available_until'        => Carbon::now()->addDays(5),
                    'is_published'           => true,
                    'show_score_immediately' => true,
                    'show_correct_answers'   => true,
                    'show_explanations'      => true,
                ]
            );

            $questions = collect();
            for ($q = 1; $q <= 5; $q++) {
                $question = Question::updateOrCreate(
                    [
                        'teacher_id'    => $teacherProfile->id,
                        'question_text' => "Q{$q} for {$subjectName}: Sample stem assessing Module 1 knowledge.",
                    ],
                    [
                        'subject_id'  => $class->subject_id,
                        'category'    => "Quiz 1 — {$subjectName}",
                        'type'        => 'multiple_choice',
                        'points'      => 2,
                        'explanation' => "This tests understanding of the fundamental concept covered in Module 1, Lesson {$q}.",
                    ]
                );

                for ($o = 0; $o < 4; $o++) {
                    QuestionOption::updateOrCreate(
                        ['question_id' => $question->id, 'position' => $o],
                        [
                            'option_text' => $this->optionText($o),
                            'is_correct'  => $o === 0,
                        ]
                    );
                }

                QuizQuestion::updateOrCreate(
                    ['quiz_id' => $quiz->id, 'question_id' => $question->id],
                    ['position' => $q]
                );

                $questions->push($question);
            }

            $studentIds = ClassStudent::where('class_id', $class->id)->pluck('student_id')->all();

            foreach ($studentIds as $studentId) {
                $correctCount = 3 + (($studentId + $cIdx) % 3);

                $attempt = QuizAttempt::updateOrCreate(
                    ['quiz_id' => $quiz->id, 'student_id' => $studentId],
                    [
                        'attempt_number'   => 1,
                        'started_at'       => Carbon::now()->subHours(3),
                        'expires_at'       => Carbon::now()->subHours(2),
                        'submitted_at'     => Carbon::now()->subHours(2)->subMinutes(5),
                        'submitted_reason' => 'manual',
                        'status'           => 'graded',
                        'warning_count'    => 0,
                        'score'            => $correctCount * 2,
                    ]
                );

                foreach ($questions as $qi => $question) {
                    $correctOption = QuestionOption::where('question_id', $question->id)
                        ->where('is_correct', true)->first();
                    $wrongOption = QuestionOption::where('question_id', $question->id)
                        ->where('is_correct', false)->first();

                    $isCorrect = $qi < $correctCount;

                    QuizAnswer::updateOrCreate(
                        ['quiz_attempt_id' => $attempt->id, 'question_id' => $question->id],
                        [
                            'question_option_id' => $isCorrect ? $correctOption?->id : $wrongOption?->id,
                            'is_correct'         => $isCorrect,
                            'points_awarded'     => $isCorrect ? 2 : 0,
                        ]
                    );
                }
            }
        }
    }

    /* =================================================================
     | 6. ANNOUNCEMENTS
     *================================================================= */
    protected function seedAnnouncements(?User $admin): void
    {
        if (! $admin) {
            return;
        }

        $schoolWide = [
            ['Enrollment for S.Y. 2026–2027 is Now Open', 'urgent', true, -3, 45,
             'Enrollment runs until June 30, 2026. Incoming Grade 11 and Grade 12 transferees should bring Form 138, PSA birth certificate, and 2x2 ID photos. Walk-in applicants welcome at the Registrar\'s Office from 8 AM to 4 PM.'],
            ['Opening of Classes — August 3, 2026', 'important', true, -8, 30,
             'The first day of S.Y. 2026–2027 will be on August 3, 2026. Flag ceremony at 7:00 AM. Class advisers will meet their sections after the assembly.'],
            ['First Quarter Examination Schedule', 'important', true, -2, 20,
             'The quarterly exams for the 1st Term will be held on October 12–16, 2026. Coordinate with your class advisers for room assignments. Bring your own pens, calculators (for Math/Science), and school ID.'],
            ['No Classes on August 21 (Ninoy Aquino Day)', 'normal', false, -1, 25,
             'Malacañang has declared August 21, 2026 a regular holiday. No synchronous or asynchronous classes will take place. Regular schedule resumes August 22.'],
            ['Brigada Eskwela 2026', 'normal', false, -15, 10,
             'Join us on August 1–5, 2026 for the annual Brigada Eskwela. Parents, alumni, and community volunteers are welcome. Bring your own cleaning materials if possible.'],
            ['SHS Voucher Program Orientation', 'normal', false, -5, 15,
             'Grade 10 completers and transferees are invited to a voucher orientation on July 5, 2026, 9 AM at the school gymnasium. Discusses DepEd SHS-VP coverage and requirements.'],
        ];

        foreach ($schoolWide as [$title, $priority, $pinned, $pubOffset, $expOffset, $body]) {
            Announcement::updateOrCreate(
                ['title' => $title, 'created_by' => $admin->id],
                [
                    'class_id'       => null,
                    'body'           => $body,
                    'image_path'     => 'announcements/' . md5($title) . '.jpg',
                    'is_pinned'      => $pinned,
                    'is_school_wide' => true,
                    'priority'       => $priority,
                    'published_at'   => Carbon::now()->addDays($pubOffset),
                    'expires_at'     => Carbon::now()->addDays($expOffset),
                ]
            );
        }

        $term = $this->activeTerm();
        if (! $term) {
            return;
        }

        $classes = ClassRoom::where('term_id', $term->id)->with(['subject', 'teacher.user'])->get();

        foreach ($classes as $class) {
            $author = $class->teacher?->user ?? $admin;
            $subjectName = $class->subject?->name ?? 'Class';

            Announcement::updateOrCreate(
                ['class_id' => $class->id, 'title' => "Welcome to {$subjectName}"],
                [
                    'created_by'     => $author->id,
                    'body'           => "Please review the course outline and prepare your notebooks for our first session. All activities, quizzes, and assignments will be posted here. Let's have a productive term!",
                    'image_path'     => null,
                    'is_pinned'      => true,
                    'is_school_wide' => false,
                    'priority'       => 'normal',
                    'published_at'   => Carbon::now()->subDays(6),
                    'expires_at'     => Carbon::now()->addDays(60),
                ]
            );

            if ($class->id % 2 === 0) {
                Announcement::updateOrCreate(
                    ['class_id' => $class->id, 'title' => "Reminder: Submit Pending Requirements"],
                    [
                        'created_by'     => $author->id,
                        'body'           => 'A few of you still have missing submissions. Please coordinate with me during consultation hours or send a message through the LMS.',
                        'image_path'     => null,
                        'is_pinned'      => false,
                        'is_school_wide' => false,
                        'priority'       => 'important',
                        'published_at'   => Carbon::now()->subDays(2),
                        'expires_at'     => Carbon::now()->addDays(14),
                    ]
                );
            }
        }
    }

    /* =================================================================
     | 7. CONTACT MESSAGES
     *================================================================= */
    protected function seedContactMessages(): void
    {
        $messages = [
            ['Juan Dela Cruz',   'juan.delacruz@gmail.com',   'Inquiry about enrollment requirements',
             'Good day! I would like to ask about the requirements for incoming Grade 11 students for S.Y. 2026-2027. My daughter studies in Paliparan National High School.', false],
            ['Maria Santos',     'maria.santos@gmail.com',    'Request for Transcript of Records',
             'Hello, I would like to request a copy of my TOR. What is the process and are there any fees? Thank you.', true],
            ['Pedro Reyes',      'pedro.reyes@gmail.com',     'Schedule of entrance exam',
             'When is the next entrance exam for transferees? My son is from another division and would like to transfer to Salawag SHS.', false],
            ['Ana Villanueva',   'ana.villanueva@gmail.com',  'Inquiry on SHS Voucher coverage',
             'Is the SHS Voucher Program still available for private school completers this year? How do we apply?', true],
            ['Rogelio Bautista', 'rogelio.bautista@gmail.com','Request for school calendar',
             'Please send me a copy of the school calendar for SY 2026-2027 including holidays and exam weeks.', false],
            ['Luzviminda Tan',   'luz.tan@gmail.com',         'Concern about student portal access',
             'My daughter cannot log in to her student portal. Password reset is not working. Please advise.', false],
            ['Carlo Mendoza',    'carlo.mendoza@techph.com',  'Partnership inquiry — industry linkage',
             'We are a local IT company interested in offering internship slots for ICT strand students. Who can we coordinate with?', true],
            ['Ferdinand Lim',    'ferdie.lim@gmail.com',      'Concern about class schedule',
             'My son is in Grade 11 and I noticed his schedule has classes on Friday. Is Friday really a full day? His previous school only had classes Monday to Thursday.', true],
            ['Josefina Aquino',  'josefina.aquino@gmail.com', 'Walk-in enrollment inquiry',
             'Good afternoon. Can we still walk in to enroll tomorrow or do we need to submit online first? Thank you.', false],
        ];

        foreach ($messages as [$name, $email, $subject, $body, $read]) {
            ContactMessage::updateOrCreate(
                ['email' => $email, 'subject' => $subject],
                [
                    'name'    => $name,
                    'message' => $body,
                    'is_read' => $read,
                ]
            );
        }
    }

    /* =================================================================
     | 8. CONVERSATIONS + MESSAGES
     *================================================================= */
    protected function seedConversations(?User $admin, ?User $teacher, ?User $student): void
    {
        if (! $admin || ! $teacher || ! $student) {
            return;
        }

        $c1 = Conversation::updateOrCreate(['subject' => 'Question about the upcoming quarterly exam']);
        $this->attachParticipant($c1, $teacher);
        $this->attachParticipant($c1, $student);

        $this->say($c1, $student, "Ma'am, will the quarterly exam cover all 5 modules or just the last two?", -180);
        $this->say($c1, $teacher, "It covers Modules 1 and 2 only. I uploaded a reviewer under Lessons. Please review it this weekend.", -175);
        $this->say($c1, $student, "Thank you, Ma'am! I'll review it tonight.", -170);

        $c2 = Conversation::updateOrCreate(['subject' => 'Submission of Q1 grade sheets']);
        $this->attachParticipant($c2, $admin);
        $this->attachParticipant($c2, $teacher);

        $this->say($c2, $admin, "Hi Ma'am Fernandez, reminder to submit your Q1 grade sheets by Friday EOD.", -300);
        $this->say($c2, $teacher, "Noted. I'm done with two sections; the third is still being finalized.", -290);
        $this->say($c2, $admin, "Perfect. Attach the PDF in the Gradebook export when ready.", -285);

        $teacher2 = User::where('email', 'antonio.cruz@salawagshs.deped.gov.ph')->first();
        if ($teacher2) {
            $c3 = Conversation::updateOrCreate(['subject' => 'Inter-class quiz collaboration']);
            $this->attachParticipant($c3, $teacher);
            $this->attachParticipant($c3, $teacher2);

            $this->say($c3, $teacher, 'Would you like to co-develop a 20-item formative quiz for the STEM sections?', -90);
            $this->say($c3, $teacher2, 'Sure! Let\'s sync next Wednesday during free period.', -85);
        }
    }

    protected function attachParticipant(Conversation $conversation, User $user): void
    {
        ConversationParticipant::updateOrCreate(
            ['conversation_id' => $conversation->id, 'user_id' => $user->id],
            ['last_read_at' => Carbon::now()->subMinutes(30)]
        );
    }

    protected function say(Conversation $conversation, User $sender, string $body, int $minutesAgo): void
    {
        Message::updateOrCreate(
            ['conversation_id' => $conversation->id, 'sender_id' => $sender->id, 'body' => $body],
            [
                'created_at' => Carbon::now()->addMinutes($minutesAgo),
                'updated_at' => Carbon::now()->addMinutes($minutesAgo),
            ]
        );
    }

    /* =================================================================
     | 9. AUDIT LOGS
     *================================================================= */
    protected function seedAuditLogs(?User $admin): void
    {
        if (! $admin) {
            return;
        }

        $actions = [
            ['login',                 null,                     null,                          null],
            ['create-student',        'App\Models\Student',     12,                            ['lrn' => '136200001234', 'name' => 'Rosario Cruz Reyes']],
            ['update-teacher',        'App\Models\Teacher',     3,                             ['department' => 'Sciences', 'specialization' => 'General Science']],
            ['create-class',          'App\Models\ClassRoom',   47,                            ['subject' => 'CORE-MATH', 'section' => 'STEM 11-A']],
            ['delete-announcement',   'App\Models\Announcement', 2,                            null],
            ['assign-teacher',        'App\Models\ClassRoom',   47,                            ['teacher_id' => 4]],
            ['publish-grades',        'App\Models\Grade',       120,                           ['is_finalized' => true]],
            ['create-school-year',    'App\Models\SchoolYear',  1,                             ['label' => '2026-2027']],
            ['update-system-setting', 'App\Models\SystemSetting', 5,                           ['value' => '75']],
            ['convert-applicant',     'App\Models\Applicant',   21,                            ['student_id' => 1, 'status' => 'enrolled']],
            ['reset-password',        'App\Models\User',        19,                            ['email' => 'teacher@test.com']],
            ['approve-contribution',  'App\Models\Contribution', 3,                            ['is_published' => true]],
            ['logout',                null,                     null,                          null],
        ];

        foreach ($actions as $i => [$action, $type, $targetId, $newValues]) {
            DB::table('audit_logs')->updateOrInsert(
                [
                    'user_id' => $admin->id,
                    'action'  => $action,
                    'created_at' => Carbon::now()->subMinutes(15 * ($i + 1)),
                ],
                [
                    'auditable_type' => $type,
                    'auditable_id'   => $targetId,
                    'old_values'     => null,
                    'new_values'     => $newValues ? json_encode($newValues) : null,
                    'ip_address'     => '192.168.1.' . (10 + $i),
                    'user_agent'     => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
                ]
            );
        }
    }

    /* =================================================================
     | CONTENT HELPERS
     *================================================================= */
    protected function lessonTitle(int $n): string
    {
        return match ($n) {
            1 => 'Introduction and Vocabulary',
            2 => 'Core Concepts and Worked Examples',
            default => 'Practice and Application',
        };
    }

    protected function lessonTopic(int $n): string
    {
        return match ($n) {
            1 => 'the foundational terminology of the subject',
            2 => 'the underlying principles and their applications',
            default => 'real-world problem solving and critical thinking',
        };
    }

    protected function optionText(int $position): string
    {
        return match ($position) {
            0 => 'The correct answer — this option accurately describes the concept.',
            1 => 'A plausible distractor that misapplies the concept.',
            2 => 'A common misconception among first-time learners.',
            default => 'A partially correct statement that misses a key condition.',
        };
    }

    /**
     * Write a tiny placeholder PDF for every seeded submission / lesson
     * attachment so Storage::exists() returns true and downloads work.
     */
    protected function seedPlaceholderFiles(): void
    {
        // Minimal valid PDF bytes — a blank page. Enough for a browser
        // to open it or a download to succeed.
        $pdf = "%PDF-1.4\n"
            . "1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n"
            . "2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n"
            . "3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 200 200]>>endobj\n"
            . "xref\n0 4\n0000000000 65535 f \n"
            . "0000000009 00000 n \n"
            . "0000000052 00000 n \n"
            . "0000000101 00000 n \n"
            . "trailer<</Size 4/Root 1 0 R>>\nstartxref\n160\n%%EOF";

        // Submissions used by the assignment + quiz loops
        $assignments = Assignment::all();
        foreach ($assignments as $a) {
            $students = ClassStudent::where('class_id', $a->class_id)->pluck('student_id');
            foreach ($students as $sid) {
                $path = "submissions/a{$a->id}/s{$sid}.pdf";
                \Storage::disk('local')->put($path, $pdf);
            }
        }

        // Lightweight assignments for other classes
        $otherAssignments = Assignment::whereNotIn(
            'class_id',
            ClassStudent::select('class_id')->distinct()->pluck('class_id')
        )->get();
        foreach ($otherAssignments as $a) {
            $students = ClassStudent::where('class_id', $a->class_id)->pluck('student_id')->take(4);
            foreach ($students as $sid) {
                $path = "submissions/a{$a->id}/s{$sid}.pdf";
                \Storage::disk('local')->put($path, $pdf);
            }
        }

        // Lesson attachments
        foreach (Lesson::all() as $lesson) {
            foreach (['pdf', 'pptx'] as $ext) {
                $path = "lessons/{$lesson->class_id}/l{$lesson->id}-{$ext}." . $ext;
                \Storage::disk('local')->put($path, $pdf);
            }
        }
    }
}