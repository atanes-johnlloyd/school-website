<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;

use App\Http\Controllers\Teacher\ClassController as TeacherClassController;
use App\Http\Controllers\Student\ClassController as StudentClassController;
use App\Http\Controllers\Auth\PasswordChangeController;

// ─────────────────────────────────────────────────────────────
// Public / Landing Page
// ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    return Inertia::render('Student/Home');
});

// ─────────────────────────────────────────────────────────────
// Role-based Dashboard Redirect
// ─────────────────────────────────────────────────────────────
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('admin'))   return redirect()->route('admin.dashboard');
    if ($user->hasRole('teacher')) return redirect()->route('teacher.dashboard');
    if ($user->hasRole('student')) return redirect()->route('student.dashboard');

    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// ─────────────────────────────────────────────────────────────
// Admin
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'password.changed', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Reference data CRUD
        Route::apiResource('school-years', \App\Http\Controllers\Admin\SchoolYearController::class);
        Route::put('school-years/{school_year}/activate',
            [\App\Http\Controllers\Admin\SchoolYearController::class, 'activate'])
            ->name('school-years.activate');

        Route::apiResource('terms', \App\Http\Controllers\Admin\TermController::class);
        Route::apiResource('tracks', \App\Http\Controllers\Admin\TrackController::class);
        Route::apiResource('strands', \App\Http\Controllers\Admin\StrandController::class);
        Route::apiResource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
        Route::apiResource('rooms', \App\Http\Controllers\Admin\RoomController::class);
        Route::apiResource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
        Route::apiResource('students', \App\Http\Controllers\Admin\StudentController::class);
        Route::apiResource('sections', \App\Http\Controllers\Admin\SectionController::class);

        Route::post('sections/{section}/enroll',
            [\App\Http\Controllers\Admin\SectionController::class, 'enrollStudent'])
            ->name('sections.enroll');
        Route::delete('sections/{section}/students/{student}',
            [\App\Http\Controllers\Admin\SectionController::class, 'removeStudent'])
            ->name('sections.students.remove');

        Route::post('students/{student}/reset-password',
            [\App\Http\Controllers\Admin\StudentController::class, 'resetPassword'])
            ->name('students.reset-password');

        Route::get('enrollments', [\App\Http\Controllers\Admin\EnrollmentController::class, 'index'])
            ->name('enrollments.index');
        Route::put('enrollments/{enrollment}/approve',
            [\App\Http\Controllers\Admin\EnrollmentController::class, 'approve'])
            ->name('enrollments.approve');
        Route::put('enrollments/{enrollment}/reject',
            [\App\Http\Controllers\Admin\EnrollmentController::class, 'reject'])
            ->name('enrollments.reject');
            });

// ─────────────────────────────────────────────────────────────
// Teacher
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'password.changed', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        Route::get('/classes', [TeacherClassController::class, 'index'])->name('classes.index');
        Route::get('/classes/{classroom}', [TeacherClassController::class, 'show'])->name('classes.show');

        // Quiz Hub & Exam Bank Route
        Route::get('/quizzes', function () {
            return Inertia::render('Teacher/QuizHub/Index');
        })->name('quizzes.index');

        // Gradebook
        Route::get('/gradebook', function () {
            return Inertia::render('Teacher/Gradebook/Index');
        })->name('gradebook.index');

        // Daily Attendance
        Route::get('/attendance', function () {
            return Inertia::render('Teacher/DailyAttendance/Index');
        })->name('attendance.index');

        // Assignments & Tasks
        Route::get('/tasks', function () {
            return Inertia::render('Teacher/Assignments/Index');
        })->name('tasks.index');

        // DepEd LR Resources
        Route::get('/resources', function () {
            return Inertia::render('Teacher/Lessons/Index');
        })->name('resources.index');

        // Assignments
        Route::get(
            '/classes/{classroom}/assignments',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'index']
        )
            ->name('classes.assignments.index');
        Route::get(
            '/classes/{classroom}/assignments/create',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'create']
        )
            ->name('classes.assignments.create');
        Route::post(
            '/classes/{classroom}/assignments',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'store']
        )
            ->name('classes.assignments.store');

        Route::get(
            '/assignments/{assignment}',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'show']
        )
            ->name('assignments.show');
        Route::get(
            '/assignments/{assignment}/edit',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'edit']
        )
            ->name('assignments.edit');
        Route::put(
            '/assignments/{assignment}',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'update']
        )
            ->name('assignments.update');
        Route::delete(
            '/assignments/{assignment}',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'destroy']
        )
            ->name('assignments.destroy');

        // Grading
        Route::put(
            '/submissions/{submission}/grade',
            [\App\Http\Controllers\Teacher\SubmissionController::class, 'grade']
        )
            ->name('submissions.grade');

        // Materials (Lessons)
        Route::get(
            '/classes/{classroom}/lessons',
            [\App\Http\Controllers\Teacher\LessonController::class, 'index']
        )
            ->name('classes.lessons.index');
        Route::get(
            '/classes/{classroom}/lessons/create',
            [\App\Http\Controllers\Teacher\LessonController::class, 'create']
        )
            ->name('classes.lessons.create');
        Route::post(
            '/classes/{classroom}/lessons',
            [\App\Http\Controllers\Teacher\LessonController::class, 'store']
        )
            ->name('classes.lessons.store');

        Route::get(
            '/lessons/{lesson}',
            [\App\Http\Controllers\Teacher\LessonController::class, 'show']
        )
            ->name('lessons.show');
        Route::get(
            '/lessons/{lesson}/edit',
            [\App\Http\Controllers\Teacher\LessonController::class, 'edit']
        )
            ->name('lessons.edit');
        Route::put(
            '/lessons/{lesson}',
            [\App\Http\Controllers\Teacher\LessonController::class, 'update']
        )
            ->name('lessons.update');
        Route::delete(
            '/lessons/{lesson}',
            [\App\Http\Controllers\Teacher\LessonController::class, 'destroy']
        )
            ->name('lessons.destroy');

        // Announcements
        Route::get(
            '/classes/{classroom}/announcements',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'index']
        )
            ->name('classes.announcements.index');
        Route::post(
            '/classes/{classroom}/announcements',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'store']
        )
            ->name('classes.announcements.store');

        Route::get(
            '/announcements/{announcement}',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'show']
        )
            ->name('announcements.show');
        Route::put(
            '/announcements/{announcement}',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'update']
        )
            ->name('announcements.update');
        Route::delete(
            '/announcements/{announcement}',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'destroy']
        )
            ->name('announcements.destroy');
        Route::put(
            '/announcements/{announcement}/pin',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'togglePin']
        )
            ->name('announcements.toggle-pin');

        // Downloads
        Route::get(
            '/submissions/{submission}/download',
            [\App\Http\Controllers\Teacher\SubmissionController::class, 'download']
        )
            ->name('submissions.download');
        Route::get(
            '/lesson-attachments/{attachment}/download',
            [\App\Http\Controllers\Teacher\LessonController::class, 'downloadAttachment']
        )
            ->name('lesson-attachments.download');

        Route::get('/classes/{classroom}/gradebook',
            [\App\Http\Controllers\Teacher\GradebookController::class, 'show'])
            ->name('classes.gradebook.show');

        // Attendance
        Route::get('/classes/{classroom}/attendance',
            [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])
            ->name('classes.attendance.index');
        Route::get('/classes/{classroom}/attendance/{date}',
            [\App\Http\Controllers\Teacher\AttendanceController::class, 'session'])
            ->name('classes.attendance.session');
        Route::post('/classes/{classroom}/attendance/{date}',
            [\App\Http\Controllers\Teacher\AttendanceController::class, 'mark'])
            ->name('classes.attendance.mark');
        Route::get('/classes/{classroom}/attendance/student/{student}',
            [\App\Http\Controllers\Teacher\AttendanceController::class, 'studentHistory'])
            ->name('classes.attendance.student');

        // ─── Question Bank ───
        Route::get('/questions',
            [\App\Http\Controllers\Teacher\QuestionBankController::class, 'index'])
            ->name('questions.index');
        Route::post('/questions',
            [\App\Http\Controllers\Teacher\QuestionBankController::class, 'store'])
            ->name('questions.store');
        Route::post('/questions/import-csv',
            [\App\Http\Controllers\Teacher\QuestionBankController::class, 'importCsv'])
            ->name('questions.import-csv');
        Route::get('/questions/{question}',
            [\App\Http\Controllers\Teacher\QuestionBankController::class, 'show'])
            ->name('questions.show');
        Route::put('/questions/{question}',
            [\App\Http\Controllers\Teacher\QuestionBankController::class, 'update'])
            ->name('questions.update');
        Route::delete('/questions/{question}',
            [\App\Http\Controllers\Teacher\QuestionBankController::class, 'destroy'])
            ->name('questions.destroy');

        // ─── Quizzes ───
        Route::get('/classes/{classroom}/quizzes',
            [\App\Http\Controllers\Teacher\QuizController::class, 'index'])
            ->name('classes.quizzes.index');
        Route::post('/classes/{classroom}/quizzes',
            [\App\Http\Controllers\Teacher\QuizController::class, 'store'])
            ->name('classes.quizzes.store');

        Route::get('/quizzes/{quiz}',
            [\App\Http\Controllers\Teacher\QuizController::class, 'show'])
            ->name('quizzes.show');
        Route::put('/quizzes/{quiz}',
            [\App\Http\Controllers\Teacher\QuizController::class, 'update'])
            ->name('quizzes.update');
        Route::delete('/quizzes/{quiz}',
            [\App\Http\Controllers\Teacher\QuizController::class, 'destroy'])
            ->name('quizzes.destroy');
        Route::put('/quizzes/{quiz}/publish',
            [\App\Http\Controllers\Teacher\QuizController::class, 'togglePublish'])
            ->name('quizzes.toggle-publish');

        Route::post('/quizzes/{quiz}/attach-questions',
            [\App\Http\Controllers\Teacher\QuizController::class, 'attachQuestions'])
            ->name('quizzes.attach-questions');
        Route::delete('/quizzes/{quiz}/questions/{question}',
            [\App\Http\Controllers\Teacher\QuizController::class, 'detachQuestion'])
            ->name('quizzes.detach-question');
        Route::put('/quizzes/{quiz}/questions/reorder',
            [\App\Http\Controllers\Teacher\QuizController::class, 'reorderQuestions'])
            ->name('quizzes.reorder-questions');

        // Quiz submissions + grading
        Route::get('/quizzes/{quiz}/submissions',
            [\App\Http\Controllers\Teacher\QuizSubmissionController::class, 'index'])
            ->name('quizzes.submissions.index');
        Route::get('/quiz-attempts/{attempt}',
            [\App\Http\Controllers\Teacher\QuizSubmissionController::class, 'show'])
            ->name('quiz-attempts.show');
        Route::put('/quiz-answers/{answer}/grade',
            [\App\Http\Controllers\Teacher\QuizSubmissionController::class, 'gradeAnswer'])
            ->name('quiz-answers.grade');
        Route::put('/quizzes/{quiz}/bulk-grade',
            [\App\Http\Controllers\Teacher\QuizSubmissionController::class, 'bulkGrade'])
            ->name('quizzes.bulk-grade');
    });

// ─────────────────────────────────────────────────────────────
// Student
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'password.changed', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

        // Classes Catalog Route
        Route::get('/classes', [StudentClassController::class, 'index'])->name('classes.index');

        // 👈 ADD THIS MISSING ROUTE HERE:
        Route::get('/classes/{classroom}', [StudentClassController::class, 'show'])->name('classes.show');

        Route::get('/assessments', function () {
            return Inertia::render('Student/Assessments/Index');
        })->name('assessments.index');

        Route::get('/grades', function () {
            return Inertia::render('Student/Grades/Index');
        })->name('grades.index');

        Route::get('/schedule', function () {
            return Inertia::render('Student/Schedule/Index');
        })->name('schedule.index');

        Route::get('/quizhub', function () {
            return Inertia::render('Student/QuizHub/Index');
        })->name('quizhub.index');

        Route::get('/studentrecords', function () {
            return Inertia::render('Student/StudentRecords/Index');
        })->name('studentrecords.index');

        Route::get(
            '/classes/{classroom}/assignments',
            [\App\Http\Controllers\Student\AssignmentController::class, 'index']
        )
            ->name('classes.assignments.index');

        Route::get(
            '/assignments/{assignment}',
            [\App\Http\Controllers\Student\AssignmentController::class, 'show']
        )
            ->name('assignments.show');
        Route::post(
            '/assignments/{assignment}/submit',
            [\App\Http\Controllers\Student\AssignmentController::class, 'submit']
        )
            ->middleware('throttle:10,1')
            ->name('assignments.submit');

        Route::get(
            '/classes/{classroom}/lessons',
            [\App\Http\Controllers\Student\LessonController::class, 'index']
        )
            ->name('classes.lessons.index');
        Route::get(
            '/lessons/{lesson}',
            [\App\Http\Controllers\Student\LessonController::class, 'show']
        )
            ->name('lessons.show');

        // Announcements
        Route::get(
            '/classes/{classroom}/announcements',
            [\App\Http\Controllers\Student\AnnouncementController::class, 'index']
        )
            ->name('classes.announcements.index');
        Route::get(
            '/announcements/{announcement}',
            [\App\Http\Controllers\Student\AnnouncementController::class, 'show']
        )
            ->name('announcements.show');
        Route::get(
            '/announcements',
            [\App\Http\Controllers\Student\AnnouncementController::class, 'feed']
        )
            ->name('announcements.feed');

        // Downloads
        Route::get(
            '/assignments/{assignment}/submission/file',
            [\App\Http\Controllers\Student\AssignmentController::class, 'downloadSubmission']
        )
            ->name('assignments.submission.download');
        Route::get(
            '/lesson-attachments/{attachment}/download',
            [\App\Http\Controllers\Teacher\LessonController::class, 'downloadAttachment']
        )
            ->name('lesson-attachments.download');

        Route::get('/grades',
            [\App\Http\Controllers\Student\GradeController::class, 'index'])
            ->name('grades.index');
        Route::get('/classes/{classroom}/grades',
            [\App\Http\Controllers\Student\GradeController::class, 'show'])
            ->name('classes.grades.show');

        // Attendance
        Route::get('/attendance',
            [\App\Http\Controllers\Student\AttendanceController::class, 'index'])
            ->name('attendance.index');
        Route::get('/classes/{classroom}/attendance',
            [\App\Http\Controllers\Student\AttendanceController::class, 'show'])
            ->name('classes.attendance.show');
            });

// ─────────────────────────────────────────────────────────────
// User Profile & System Utilities
// ─────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/password/change', [PasswordChangeController::class, 'show'])
        ->name('password.change');
    Route::put('/password/change', [PasswordChangeController::class, 'update'])
        ->name('password.change.update');
});

// ─────────────────────────────────────────────────────────────
// Messaging (shared by all authenticated users)
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'password.changed'])->group(function () {
    Route::get('/messages',
        [\App\Http\Controllers\MessageController::class, 'index'])
        ->name('messages.index');
    Route::get('/messages/create',
        [\App\Http\Controllers\MessageController::class, 'create'])
        ->name('messages.create');
    Route::post('/messages',
        [\App\Http\Controllers\MessageController::class, 'store'])
        ->name('messages.store');
    Route::get('/messages/{conversation}',
        [\App\Http\Controllers\MessageController::class, 'show'])
        ->name('messages.show');
    Route::post('/messages/{conversation}',
        [\App\Http\Controllers\MessageController::class, 'reply'])
        ->name('messages.reply');
});

require __DIR__ . '/auth.php';
