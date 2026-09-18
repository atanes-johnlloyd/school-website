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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
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
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // ...User Management, Enrollment Management, etc.
    });

// ─────────────────────────────────────────────────────────────
// Teacher
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:teacher'])
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
Route::middleware(['auth', 'role:student'])
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
Route::middleware(['auth', 'role:teacher'])
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
    });

// ─── Student ────────────────────────────────────────────
Route::middleware(['auth', 'role:student'])
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
            ->name('assignments.submit');
    });
    
require __DIR__.'/auth.php';