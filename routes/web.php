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

// AFTER:
Route::get('/', function () {
    return Inertia::render('Student/Home');
});

// ─────────────────────────────────────────────────────────────
// Role-based dashboard redirect
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
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // ...User Management, Enrollment Management, etc.
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
    });

// ─────────────────────────────────────────────────────────────
// Student
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'password.changed', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

        Route::get('/classes', [StudentClassController::class, 'index'])->name('classes.index');
        Route::get('/classes/{classroom}', [StudentClassController::class, 'show'])->name('classes.show');
    });

// ─────────────────────────────────────────────────────────────
// Profile (Breeze)
// ─────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Teacher ────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'password.changed', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        Route::get('/classes', [TeacherClassController::class, 'index'])->name('classes.index');
        Route::get('/classes/{classroom}', [TeacherClassController::class, 'show'])->name('classes.show');

        // Assignments nested under class
        Route::get('/classes/{classroom}/assignments',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'index'])
            ->name('classes.assignments.index');
        Route::get('/classes/{classroom}/assignments/create',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'create'])
            ->name('classes.assignments.create');
        Route::post('/classes/{classroom}/assignments',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'store'])
            ->name('classes.assignments.store');

        // Single assignment
        Route::get('/assignments/{assignment}',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'show'])
            ->name('assignments.show');
        Route::get('/assignments/{assignment}/edit',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'edit'])
            ->name('assignments.edit');
        Route::put('/assignments/{assignment}',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'update'])
            ->name('assignments.update');
        Route::delete('/assignments/{assignment}',
            [\App\Http\Controllers\Teacher\AssignmentController::class, 'destroy'])
            ->name('assignments.destroy');

        // Grading
        Route::put('/submissions/{submission}/grade',
            [\App\Http\Controllers\Teacher\SubmissionController::class, 'grade'])
            ->name('submissions.grade');

        // ── Materials (Lessons) ──
        Route::get('/classes/{classroom}/lessons',
            [\App\Http\Controllers\Teacher\LessonController::class, 'index'])
            ->name('classes.lessons.index');
        Route::get('/classes/{classroom}/lessons/create',
            [\App\Http\Controllers\Teacher\LessonController::class, 'create'])
            ->name('classes.lessons.create');
        Route::post('/classes/{classroom}/lessons',
            [\App\Http\Controllers\Teacher\LessonController::class, 'store'])
            ->name('classes.lessons.store');

        Route::get('/lessons/{lesson}',
            [\App\Http\Controllers\Teacher\LessonController::class, 'show'])
            ->name('lessons.show');
        Route::get('/lessons/{lesson}/edit',
            [\App\Http\Controllers\Teacher\LessonController::class, 'edit'])
            ->name('lessons.edit');
        Route::put('/lessons/{lesson}',
            [\App\Http\Controllers\Teacher\LessonController::class, 'update'])
            ->name('lessons.update');
        Route::delete('/lessons/{lesson}',
            [\App\Http\Controllers\Teacher\LessonController::class, 'destroy'])
            ->name('lessons.destroy');

        // ── Announcements ──
        Route::get('/classes/{classroom}/announcements',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'index'])
            ->name('classes.announcements.index');
        Route::post('/classes/{classroom}/announcements',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'store'])
            ->name('classes.announcements.store');

        Route::get('/announcements/{announcement}',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'show'])
            ->name('announcements.show');
        Route::put('/announcements/{announcement}',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'update'])
            ->name('announcements.update');
        Route::delete('/announcements/{announcement}',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'destroy'])
            ->name('announcements.destroy');
        Route::put('/announcements/{announcement}/pin',
            [\App\Http\Controllers\Teacher\AnnouncementController::class, 'togglePin'])
            ->name('announcements.toggle-pin');

        Route::get('/submissions/{submission}/download',
            [\App\Http\Controllers\Teacher\SubmissionController::class, 'download'])
            ->name('submissions.download');
        Route::get('/lesson-attachments/{attachment}/download',
            [\App\Http\Controllers\Teacher\LessonController::class, 'downloadAttachment'])
            ->name('lesson-attachments.download');
    });

// ─── Student ────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'password.changed', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/classes', [StudentClassController::class, 'index'])->name('classes.index');
        Route::get('/classes/{classroom}', [StudentClassController::class, 'show'])->name('classes.show');

        Route::get('/classes/{classroom}/assignments',
            [\App\Http\Controllers\Student\AssignmentController::class, 'index'])
            ->name('classes.assignments.index');

        Route::get('/assignments/{assignment}',
            [\App\Http\Controllers\Student\AssignmentController::class, 'show'])
            ->name('assignments.show');
        Route::post('/assignments/{assignment}/submit',
            [\App\Http\Controllers\Student\AssignmentController::class, 'submit'])
            ->middleware('throttle:10,1')  // 10 submissions per minute
            ->name('assignments.submit');
        Route::get('/classes/{classroom}/lessons',
            [\App\Http\Controllers\Student\LessonController::class, 'index'])->name('classes.lessons.index');
        Route::get('/lessons/{lesson}',
            [\App\Http\Controllers\Student\LessonController::class, 'show'])->name('lessons.show');

        // ── Announcements ──
        Route::get('/classes/{classroom}/announcements',
            [\App\Http\Controllers\Student\AnnouncementController::class, 'index'])
            ->name('classes.announcements.index');
        Route::get('/announcements/{announcement}',
            [\App\Http\Controllers\Student\AnnouncementController::class, 'show'])
            ->name('announcements.show');
        Route::get('/announcements',
            [\App\Http\Controllers\Student\AnnouncementController::class, 'feed'])
            ->name('announcements.feed');

        Route::get('/assignments/{assignment}/submission/file',
            [\App\Http\Controllers\Student\AssignmentController::class, 'downloadSubmission'])
            ->name('assignments.submission.download');

        Route::get('/lesson-attachments/{attachment}/download',
            [\App\Http\Controllers\Teacher\LessonController::class, 'downloadAttachment'])
            ->name('lesson-attachments.download');
    });

    // Materials
    Route::get('/classes/{classroom}/lessons',
        [\App\Http\Controllers\Teacher\LessonController::class, 'index'])->name('classes.lessons.index');
    Route::get('/classes/{classroom}/lessons/create',
        [\App\Http\Controllers\Teacher\LessonController::class, 'create'])->name('classes.lessons.create');
    Route::post('/classes/{classroom}/lessons',
        [\App\Http\Controllers\Teacher\LessonController::class, 'store'])->name('classes.lessons.store');

    Route::get('/lessons/{lesson}',
        [\App\Http\Controllers\Teacher\LessonController::class, 'show'])->name('lessons.show');
    Route::get('/lessons/{lesson}/edit',
        [\App\Http\Controllers\Teacher\LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}',
        [\App\Http\Controllers\Teacher\LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}',
        [\App\Http\Controllers\Teacher\LessonController::class, 'destroy'])->name('lessons.destroy');
    
Route::middleware('auth')->group(function () {
    Route::get('/password/change', [PasswordChangeController::class, 'show'])
        ->name('password.change');
    Route::put('/password/change', [PasswordChangeController::class, 'update'])
        ->name('password.change.update');
});
require __DIR__.'/auth.php';