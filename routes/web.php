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

use App\Http\Controllers\Admin\SchoolYearController;
use App\Http\Controllers\Webhook\PaymongoWebhookController;

// ─────────────────────────────────────────────────────────────
// Public / Landing Pages
// ─────────────────────────────────────────────────────────────
Route::get('/', [\App\Http\Controllers\Site\HomeController::class, 'index'])->name('home');

Route::get('/academics', [\App\Http\Controllers\Site\AcademicsController::class, 'index'])->name('site.academics');

Route::get(
    '/academics/syllabus/{subject}',
    [\App\Http\Controllers\Site\AcademicsController::class, 'syllabus']
)->name('site.academics.syllabus');

Route::get('/admission', [\App\Http\Controllers\Site\AdmissionController::class, 'index'])->name('site.admissions');

Route::get('/about-us', [\App\Http\Controllers\Site\AboutController::class, 'index'])->name('about-us');

Route::get('/faculty-staff', [\App\Http\Controllers\Site\FacultyStaffController::class, 'index'])->name('faculty-staff');

// Application submission
Route::prefix('site')->name('site.')->group(function () {
    Route::post('/admission/apply', [\App\Http\Controllers\Site\ApplicationController::class, 'store'])
        ->middleware('throttle:3,1')
        ->name('admission.apply');
    Route::get('/admission/status/{reference}', [\App\Http\Controllers\Site\ApplicationController::class, 'status'])
        ->name('admission.status');
});

// ─────────────────────────────────────────────────────────────
// PayMongo Webhook (public, signature-verified)
// ─────────────────────────────────────────────────────────────
Route::post('/webhooks/paymongo', [PaymongoWebhookController::class, 'handle'])
    ->name('webhooks.paymongo');

// ─────────────────────────────────────────────────────────────
// Guardian Payment (public, token-based — no auth required)
// ─────────────────────────────────────────────────────────────
Route::prefix('guardian/pay')->name('guardian.pay.')->group(function () {
    Route::get('/return', [\App\Http\Controllers\Guardian\PayController::class, 'returnFromCheckout'])
        ->name('return');
    Route::get('/status/{reference}', [\App\Http\Controllers\Guardian\PayController::class, 'pollStatus'])
        ->name('status');
    Route::get('/{token}', [\App\Http\Controllers\Guardian\PayController::class, 'show'])
        ->name('show');
    Route::post('/{token}/pay-online', [\App\Http\Controllers\Guardian\PayController::class, 'payOnline'])
        ->name('pay-online');
    Route::post('/{token}/pay-cash', [\App\Http\Controllers\Guardian\PayController::class, 'payCash'])
        ->name('pay-cash');
    Route::post('/{token}/decline', [\App\Http\Controllers\Guardian\PayController::class, 'decline'])
        ->name('decline');
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

        // ─── School Years & Terms ───
        Route::middleware('permission:manage-school-years')->group(function () {
            Route::get('/school-years/list', [SchoolYearController::class, 'list'])->name('school-years.list');
            Route::apiResource('school-years', SchoolYearController::class);
            Route::put('school-years/{school_year}/activate', [SchoolYearController::class, 'activate'])->name('school-years.activate');
            Route::apiResource('terms', \App\Http\Controllers\Admin\TermController::class);
        });

        // ─── Tracks ───
        Route::middleware('permission:manage-tracks')->group(function () {
            Route::apiResource('tracks', \App\Http\Controllers\Admin\TrackController::class);
            Route::post('/tracks/{track}/image', [\App\Http\Controllers\Admin\TrackImageController::class, 'update'])->name('tracks.image.update');
            Route::delete('/tracks/{track}/image', [\App\Http\Controllers\Admin\TrackImageController::class, 'destroy'])->name('tracks.image.destroy');
        });

        // ─── Strands ───
        Route::middleware('permission:manage-strands')->group(function () {
            Route::apiResource('strands', \App\Http\Controllers\Admin\StrandController::class);
            Route::post('/strands/{strand}/image', [\App\Http\Controllers\Admin\StrandImageController::class, 'update'])->name('strands.image.update');
            Route::delete('/strands/{strand}/image', [\App\Http\Controllers\Admin\StrandImageController::class, 'destroy'])->name('strands.image.destroy');
        });

        // ─── Subjects ───
        Route::middleware('permission:manage-subjects')->group(function () {
            Route::apiResource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
            Route::post('/subjects/{subject}/image', [\App\Http\Controllers\Admin\SubjectImageController::class, 'update'])->name('subjects.image.update');
            Route::delete('/subjects/{subject}/image', [\App\Http\Controllers\Admin\SubjectImageController::class, 'destroy'])->name('subjects.image.destroy');
            Route::put('/subjects/{subject}/meta', [\App\Http\Controllers\Admin\SubjectImageController::class, 'setMeta'])->name('subjects.meta.update');
        });

        // ─── Curriculum ───
        Route::middleware('permission:manage-tracks|manage-strands|manage-subjects')->group(function () {
            Route::get('/curriculum', [\App\Http\Controllers\Admin\CurriculumController::class, 'index'])->name('curriculum.index');
        });

        // ─── Rooms ───
        Route::middleware('permission:manage-rooms')->group(function () {
            Route::get('/rooms/list', [\App\Http\Controllers\Admin\RoomController::class, 'list'])->name('rooms.list');
            Route::apiResource('rooms', \App\Http\Controllers\Admin\RoomController::class);
        });

        // ─── Teachers ───
        Route::middleware('permission:manage-teachers')->group(function () {
            Route::get('/teachers/list', [\App\Http\Controllers\Admin\TeacherController::class, 'list'])->name('teachers.list');
            Route::get('/teachers/export', [\App\Http\Controllers\Admin\TeacherController::class, 'export'])->name('teachers.export');
            Route::post('/teachers/import', [\App\Http\Controllers\Admin\TeacherController::class, 'import'])->name('teachers.import');
            Route::apiResource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
            Route::post('teachers/{teacher}/reset-password', [\App\Http\Controllers\Admin\TeacherController::class, 'resetPassword'])->name('teachers.reset-password');
        });

        // ─── Students ───
        Route::middleware('permission:manage-students')->group(function () {
            Route::get('/students/list', [\App\Http\Controllers\Admin\StudentController::class, 'list'])->name('students.list');
            Route::get('/students/export', [\App\Http\Controllers\Admin\StudentController::class, 'export'])->name('students.export');
            Route::post('/students/import', [\App\Http\Controllers\Admin\StudentController::class, 'import'])->name('students.import');
            Route::apiResource('students', \App\Http\Controllers\Admin\StudentController::class);
            Route::post('students/{student}/reset-password', [\App\Http\Controllers\Admin\StudentController::class, 'resetPassword'])->name('students.reset-password');
        });

        // ─── Sections ───
        Route::middleware('permission:manage-sections')->group(function () {
            Route::get('/sections/list', [\App\Http\Controllers\Admin\SectionController::class, 'list'])->name('sections.list');
            Route::get('/sections/{section}/eligible-students', [\App\Http\Controllers\Admin\SectionController::class, 'eligibleStudents'])->name('sections.eligible-students');
            Route::apiResource('sections', \App\Http\Controllers\Admin\SectionController::class);
            Route::post('sections/{section}/enroll', [\App\Http\Controllers\Admin\SectionController::class, 'enrollStudent'])->name('sections.enroll');
            Route::delete('sections/{section}/students/{student}', [\App\Http\Controllers\Admin\SectionController::class, 'removeStudent'])->name('sections.students.remove');
        });

        // ─── Enrollments ───
        Route::middleware('permission:manage-enrollment')->group(function () {
            Route::get('/enrollments', [\App\Http\Controllers\Admin\EnrollmentController::class, 'index'])->name('enrollments.index');
            Route::get('/enrollments/list', [\App\Http\Controllers\Admin\EnrollmentController::class, 'list'])->name('enrollments.list');
            Route::put('enrollments/{enrollment}/approve', [\App\Http\Controllers\Admin\EnrollmentController::class, 'approve'])->name('enrollments.approve');
            Route::put('enrollments/{enrollment}/reject', [\App\Http\Controllers\Admin\EnrollmentController::class, 'reject'])->name('enrollments.reject');
            Route::put('enrollments/{enrollment}/assign-section', [\App\Http\Controllers\Admin\EnrollmentController::class, 'assignSection'])->name('enrollments.assign-section');
        });

        // ─── Applicants ───
        Route::middleware('permission:manage-enrollment')->group(function () {
            Route::get('/applicants', [\App\Http\Controllers\Admin\ApplicantController::class, 'index'])->name('applicants.index');
            Route::get('/applicants/list', [\App\Http\Controllers\Admin\ApplicantController::class, 'list'])->name('applicants.list');
            Route::get('/applicants/export', [\App\Http\Controllers\Admin\ApplicantController::class, 'export'])->name('applicants.export');
            Route::post('/applicants/import', [\App\Http\Controllers\Admin\ApplicantController::class, 'import'])->name('applicants.import');
            Route::put('/applicants/{applicant}/release', [\App\Http\Controllers\Admin\ApplicantController::class, 'release'])->name('applicants.release');
            Route::get('/applicants/{applicant}', [\App\Http\Controllers\Admin\ApplicantController::class, 'show'])->name('applicants.show');
            Route::put('/applicants/{applicant}/under-review', [\App\Http\Controllers\Admin\ApplicantController::class, 'markUnderReview'])->name('applicants.under-review');
            Route::put('/applicants/{applicant}/approve', [\App\Http\Controllers\Admin\ApplicantController::class, 'approve'])->name('applicants.approve');
            Route::put('/applicants/{applicant}/reject', [\App\Http\Controllers\Admin\ApplicantController::class, 'reject'])->name('applicants.reject');
            Route::put('/applicants/{applicant}/request-resubmission', [\App\Http\Controllers\Admin\ApplicantController::class, 'requestResubmission'])->name('applicants.request-resubmission');
            Route::get('/applicant-documents/{document}/download', [\App\Http\Controllers\Admin\ApplicantController::class, 'downloadDocument'])->name('applicant-documents.download');
            Route::put('/applicant-documents/{document}/verify', [\App\Http\Controllers\Admin\ApplicantController::class, 'verifyDocument'])->name('applicant-documents.verify');
            Route::post('/applicants', [\App\Http\Controllers\Admin\ApplicantController::class, 'store'])->name('applicants.store');
            Route::put('/applicants/{applicant}', [\App\Http\Controllers\Admin\ApplicantController::class, 'update'])->name('applicants.update');
        });

        // ─── Entrance Exams ───
        Route::middleware('permission:manage-enrollment')->group(function () {
            Route::get('/entrance-exams', [\App\Http\Controllers\Admin\EntranceExamController::class, 'index'])->name('entrance-exams.index');
            Route::get('/entrance-exams/list', [\App\Http\Controllers\Admin\EntranceExamController::class, 'list'])->name('entrance-exams.list');
            Route::post('/entrance-exams', [\App\Http\Controllers\Admin\EntranceExamController::class, 'store'])->name('entrance-exams.store');
            Route::get('/entrance-exams/{exam}', [\App\Http\Controllers\Admin\EntranceExamController::class, 'show'])->name('entrance-exams.show');
            Route::put('/entrance-exams/{exam}', [\App\Http\Controllers\Admin\EntranceExamController::class, 'update'])->name('entrance-exams.update');
            Route::delete('/entrance-exams/{exam}', [\App\Http\Controllers\Admin\EntranceExamController::class, 'destroy'])->name('entrance-exams.destroy');
            Route::put('/entrance-exams/{exam}/cancel', [\App\Http\Controllers\Admin\EntranceExamController::class, 'cancel'])->name('entrance-exams.cancel');
            Route::get('/entrance-exams/{exam}/eligible-applicants', [\App\Http\Controllers\Admin\EntranceExamController::class, 'eligibleApplicants'])->name('entrance-exams.eligible');
            Route::post('/entrance-exams/{exam}/assign', [\App\Http\Controllers\Admin\EntranceExamController::class, 'assign'])->name('entrance-exams.assign');
            Route::post('/entrance-exams/{exam}/remove-applicant', [\App\Http\Controllers\Admin\EntranceExamController::class, 'removeApplicant'])->name('entrance-exams.remove-applicant');
            Route::put('/exam-results/{result}', [\App\Http\Controllers\Admin\EntranceExamResultController::class, 'update'])->name('exam-results.update');
            Route::put('/entrance-exams/{exam}/results/bulk', [\App\Http\Controllers\Admin\EntranceExamResultController::class, 'bulkUpdate'])->name('exam-results.bulk-update');
        });

        // ─── Exam Records ───
        Route::middleware('permission:manage-enrollment')->group(function () {
            Route::get('/exam-records', [\App\Http\Controllers\Admin\EntranceExamResultController::class, 'index'])->name('exam-records.index');
            Route::get('/exam-records/list', [\App\Http\Controllers\Admin\EntranceExamResultController::class, 'list'])->name('exam-records.list');
        });

        // ─── Contact Messages ───
        Route::get('/contact-messages/list', [\App\Http\Controllers\ContactController::class, 'list'])->name('contact-messages.list');
        Route::get('/contact-messages', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact-messages.index');
        Route::get('/contact-messages/{contactMessage}', [\App\Http\Controllers\ContactController::class, 'show'])->name('contact-messages.show');
        Route::put('/contact-messages/{contactMessage}/read', [\App\Http\Controllers\ContactController::class, 'toggleRead'])->name('contact-messages.toggle-read');
        Route::delete('/contact-messages/{contactMessage}', [\App\Http\Controllers\ContactController::class, 'destroy'])->name('contact-messages.destroy');
        Route::post('/contact-messages/{contactMessage}/reply',
            [\App\Http\Controllers\ContactController::class, 'reply'])
            ->name('contact-messages.reply');

        // ─── School-wide Announcements ───
        Route::middleware('permission:manage-announcements')->group(function () {
            Route::get('/school-news/list', [\App\Http\Controllers\Admin\SchoolWideAnnouncementController::class, 'list'])->name('school-news.list');
            Route::get('/school-news/create', [\App\Http\Controllers\Admin\SchoolWideAnnouncementController::class, 'create'])->name('school-news.create');
            Route::get('/school-news', [\App\Http\Controllers\Admin\SchoolWideAnnouncementController::class, 'index'])->name('school-news.index');
            Route::post('/school-news', [\App\Http\Controllers\Admin\SchoolWideAnnouncementController::class, 'store'])->name('school-news.store');
            Route::get('/school-news/{announcement}/edit', [\App\Http\Controllers\Admin\SchoolWideAnnouncementController::class, 'edit'])->name('school-news.edit');
            Route::put('/school-news/{announcement}/publish', [\App\Http\Controllers\Admin\SchoolWideAnnouncementController::class, 'togglePublish'])->name('school-news.toggle-publish');
            Route::put('/school-news/{announcement}/pin', [\App\Http\Controllers\Admin\SchoolWideAnnouncementController::class, 'togglePin'])->name('school-news.toggle-pin');
            Route::get('/school-news/{announcement}', [\App\Http\Controllers\Admin\SchoolWideAnnouncementController::class, 'show'])->name('school-news.show');
            Route::put('/school-news/{announcement}', [\App\Http\Controllers\Admin\SchoolWideAnnouncementController::class, 'update'])->name('school-news.update');
            Route::delete('/school-news/{announcement}', [\App\Http\Controllers\Admin\SchoolWideAnnouncementController::class, 'destroy'])->name('school-news.destroy');
        });

        // ─── Admin Users ───
        Route::middleware('permission:manage-users')->group(function () {
            Route::get('/users/list', [\App\Http\Controllers\Admin\UserController::class, 'list'])->name('users.list');
            Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
            Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
            Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
            Route::put('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
            Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        });

        // ─── Settings ───
        // Route::middleware('permission:manage-settings')->group(function () {
        //     Route::get('/settings', [\App\Http\Controllers\Admin\SystemSettingController::class, 'index'])->name('settings.index');
        //     Route::put('/settings', [\App\Http\Controllers\Admin\SystemSettingController::class, 'update'])->name('settings.update');
        //     Route::post('/settings/reset', [\App\Http\Controllers\Admin\SystemSettingController::class, 'reset'])->name('settings.reset');
        // });

        // ─── Audit Logs ───
        Route::middleware('permission:view-audit-log')->group(function () {
            Route::get('/audit-logs/list', [\App\Http\Controllers\Admin\AuditLogController::class, 'list'])->name('audit-logs.list');
            Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
            Route::get('/audit-logs/{auditLog}', [\App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('audit-logs.show');
        });

        // ─── Reports ───
        Route::middleware('permission:view-reports')->group(function () {
            Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
            Route::get('/reports/data', [\App\Http\Controllers\Admin\ReportController::class, 'data'])->name('reports.data');
            Route::get('/exports/enrollments', [\App\Http\Controllers\Admin\ExportController::class, 'enrollments'])->name('exports.enrollments');
            Route::get('/exports/applicants', [\App\Http\Controllers\Admin\ExportController::class, 'applicants'])->name('exports.applicants');
            Route::get('/exports/students', [\App\Http\Controllers\Admin\ExportController::class, 'students'])->name('exports.students');
            Route::get('/exports/teachers', [\App\Http\Controllers\Admin\ExportController::class, 'teachers'])->name('exports.teachers');
        });

        // ─── Contributions (NEW) ───
        Route::middleware('permission:manage-contributions')->group(function () {
            Route::get('/contributions',        [\App\Http\Controllers\Admin\ContributionController::class, 'index'])->name('contributions.index');
            Route::get('/contributions/create', [\App\Http\Controllers\Admin\ContributionController::class, 'create'])->name('contributions.create');
            Route::post('/contributions',       [\App\Http\Controllers\Admin\ContributionController::class, 'store'])->name('contributions.store');

            // ▼ NEW: JSON detail endpoint for the modal — MUST come before {contribution}
            Route::get('/contributions/{contribution}/detail',
                [\App\Http\Controllers\Admin\ContributionController::class, 'detail'])->name('contributions.detail');

            Route::get('/contributions/{contribution}',
                [\App\Http\Controllers\Admin\ContributionController::class, 'show'])->name('contributions.show');

            // ▼ NEW: admin update (edit modal needs this — teacher route won't work from admin)
            Route::put('/contributions/{contribution}',
                [\App\Http\Controllers\Admin\ContributionController::class, 'update'])->name('contributions.update');

            Route::delete('/contributions/{contribution}',
                [\App\Http\Controllers\Admin\ContributionController::class, 'destroy'])->name('contributions.destroy');

            Route::post('/contributions/assignments/{assignment}/override-authorize',
                [\App\Http\Controllers\Admin\ContributionController::class, 'overrideAuthorize'])->name('contributions.override-authorize');
            Route::post('/contributions/assignments/{assignment}/notify-guardian',
                [\App\Http\Controllers\Admin\ContributionController::class, 'notifyGuardian'])->name('contributions.notify-guardian');
            Route::post('/contributions/assignments/{assignment}/mark-cash-received',
                [\App\Http\Controllers\Admin\ContributionController::class, 'markCashReceived'])->name('contributions.mark-cash-received');
        });
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

        Route::get('/quizzes', [\App\Http\Controllers\Teacher\QuizController::class, 'hub'])->name('quizzes.index');

        Route::get('/gradebook', [\App\Http\Controllers\Teacher\GradebookController::class, 'index'])->name('gradebook.index');

        Route::get('/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'overview'])->name('attendance.index');

        Route::get('/tasks', [\App\Http\Controllers\Teacher\AssignmentController::class, 'allTasks'])->name('tasks.index');

        Route::get('/announcements', [\App\Http\Controllers\Teacher\AnnouncementController::class, 'allAnnouncements'])->name('announcements.index');

        Route::get('/resources', [\App\Http\Controllers\Teacher\LessonController::class, 'allResources'])->name('resources.index');

        // ─── Assignments ───
        Route::get('/classes/{classroom}/assignments', [\App\Http\Controllers\Teacher\AssignmentController::class, 'index'])->name('classes.assignments.index');
        Route::get('/classes/{classroom}/assignments/create', [\App\Http\Controllers\Teacher\AssignmentController::class, 'create'])->name('classes.assignments.create');
        Route::post('/classes/{classroom}/assignments', [\App\Http\Controllers\Teacher\AssignmentController::class, 'store'])->name('classes.assignments.store');

        Route::get('/assignments/{assignment}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'show'])->name('assignments.show');
        Route::get('/assignments/{assignment}/edit', [\App\Http\Controllers\Teacher\AssignmentController::class, 'edit'])->name('assignments.edit');
        Route::put('/assignments/{assignment}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'update'])->name('assignments.update');
        Route::delete('/assignments/{assignment}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'destroy'])->name('assignments.destroy');

        Route::put('/submissions/{submission}/grade', [\App\Http\Controllers\Teacher\SubmissionController::class, 'grade'])->name('submissions.grade');

        // ─── Lessons ───
        Route::get('/classes/{classroom}/lessons', [\App\Http\Controllers\Teacher\LessonController::class, 'index'])->name('classes.lessons.index');
        Route::get('/classes/{classroom}/lessons/create', [\App\Http\Controllers\Teacher\LessonController::class, 'create'])->name('classes.lessons.create');
        Route::post('/classes/{classroom}/lessons', [\App\Http\Controllers\Teacher\LessonController::class, 'store'])->name('classes.lessons.store');

        Route::get('/lessons/{lesson}', [\App\Http\Controllers\Teacher\LessonController::class, 'show'])->name('lessons.show');
        Route::get('/lessons/{lesson}/edit', [\App\Http\Controllers\Teacher\LessonController::class, 'edit'])->name('lessons.edit');
        Route::put('/lessons/{lesson}', [\App\Http\Controllers\Teacher\LessonController::class, 'update'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [\App\Http\Controllers\Teacher\LessonController::class, 'destroy'])->name('lessons.destroy');

        // ─── Announcements ───
        Route::get('/classes/{classroom}/announcements', [\App\Http\Controllers\Teacher\AnnouncementController::class, 'index'])->name('classes.announcements.index');
        Route::post('/classes/{classroom}/announcements', [\App\Http\Controllers\Teacher\AnnouncementController::class, 'store'])->name('classes.announcements.store');

        Route::get('/announcements/{announcement}', [\App\Http\Controllers\Teacher\AnnouncementController::class, 'show'])->name('announcements.show');
        Route::put('/announcements/{announcement}', [\App\Http\Controllers\Teacher\AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [\App\Http\Controllers\Teacher\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
        Route::put('/announcements/{announcement}/pin', [\App\Http\Controllers\Teacher\AnnouncementController::class, 'togglePin'])->name('announcements.toggle-pin');

        // ─── Downloads ───
        Route::get('/submissions/{submission}/download', [\App\Http\Controllers\Teacher\SubmissionController::class, 'download'])->name('submissions.download');
        Route::get('/lesson-attachments/{attachment}/download', [\App\Http\Controllers\Teacher\LessonController::class, 'downloadAttachment'])->name('lesson-attachments.download');

        Route::get('/classes/{classroom}/gradebook', [\App\Http\Controllers\Teacher\GradebookController::class, 'show'])->name('classes.gradebook.show');

        // ─── Attendance ───
        Route::get('/classes/{classroom}/attendance', [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('classes.attendance.index');
        Route::get('/classes/{classroom}/attendance/{date}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'session'])->name('classes.attendance.session');
        Route::post('/classes/{classroom}/attendance/{date}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'mark'])->name('classes.attendance.mark');
        Route::get('/classes/{classroom}/attendance/student/{student}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'studentHistory'])->name('classes.attendance.student');

        // ─── Question Bank ───
        Route::get('/questions', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'index'])->name('questions.index');
        Route::post('/questions', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'store'])->name('questions.store');
        Route::post('/questions/import-csv', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'importCsv'])->name('questions.import-csv');
        Route::get('/questions/{question}', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'show'])->name('questions.show');
        Route::put('/questions/{question}', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'update'])->name('questions.update');
        Route::delete('/questions/{question}', [\App\Http\Controllers\Teacher\QuestionBankController::class, 'destroy'])->name('questions.destroy');

        // ─── Quizzes ───
        Route::get('/classes/{classroom}/quizzes', [\App\Http\Controllers\Teacher\QuizController::class, 'index'])->name('classes.quizzes.index');
        Route::post('/classes/{classroom}/quizzes', [\App\Http\Controllers\Teacher\QuizController::class, 'store'])->name('classes.quizzes.store');

        Route::get('/quizzes/{quiz}', [\App\Http\Controllers\Teacher\QuizController::class, 'show'])->name('quizzes.show');
        Route::put('/quizzes/{quiz}', [\App\Http\Controllers\Teacher\QuizController::class, 'update'])->name('quizzes.update');
        Route::delete('/quizzes/{quiz}', [\App\Http\Controllers\Teacher\QuizController::class, 'destroy'])->name('quizzes.destroy');
        Route::put('/quizzes/{quiz}/publish', [\App\Http\Controllers\Teacher\QuizController::class, 'togglePublish'])->name('quizzes.toggle-publish');

        Route::post('/quizzes/{quiz}/attach-questions', [\App\Http\Controllers\Teacher\QuizController::class, 'attachQuestions'])->name('quizzes.attach-questions');
        Route::delete('/quizzes/{quiz}/questions/{question}', [\App\Http\Controllers\Teacher\QuizController::class, 'detachQuestion'])->name('quizzes.detach-question');
        Route::put('/quizzes/{quiz}/questions/reorder', [\App\Http\Controllers\Teacher\QuizController::class, 'reorderQuestions'])->name('quizzes.reorder-questions');

        // ─── Quiz submissions ───
        Route::get('/quizzes/{quiz}/submissions', [\App\Http\Controllers\Teacher\QuizSubmissionController::class, 'index'])->name('quizzes.submissions.index');
        Route::get('/quiz-attempts/{attempt}', [\App\Http\Controllers\Teacher\QuizSubmissionController::class, 'show'])->name('quiz-attempts.show');
        Route::put('/quiz-answers/{answer}/grade', [\App\Http\Controllers\Teacher\QuizSubmissionController::class, 'gradeAnswer'])->name('quiz-answers.grade');
        Route::put('/quizzes/{quiz}/bulk-grade', [\App\Http\Controllers\Teacher\QuizSubmissionController::class, 'bulkGrade'])->name('quizzes.bulk-grade');

        Route::post('/quizzes/{quiz}/students/{student}/grant-retake', [\App\Http\Controllers\Teacher\QuizSubmissionController::class, 'grantRetake'])->name('quizzes.grant-retake');
        Route::delete('/quizzes/{quiz}/students/{student}/grant-retake', [\App\Http\Controllers\Teacher\QuizSubmissionController::class, 'revokeRetake'])->name('quizzes.revoke-retake');

        // ─── Contributions (NEW) ───
        Route::get('/contributions', [\App\Http\Controllers\Teacher\ContributionController::class, 'index'])
            ->name('contributions.index');
        Route::get('/contributions/create', [\App\Http\Controllers\Teacher\ContributionController::class, 'create'])
            ->name('contributions.create');
        Route::post('/contributions', [\App\Http\Controllers\Teacher\ContributionController::class, 'store'])
            ->name('contributions.store');
        Route::get('/contributions/{contribution}', [\App\Http\Controllers\Teacher\ContributionController::class, 'show'])
            ->name('contributions.show');
        Route::put('/contributions/{contribution}', [\App\Http\Controllers\Teacher\ContributionController::class, 'update'])
            ->name('contributions.update');
        Route::delete('/contributions/{contribution}', [\App\Http\Controllers\Teacher\ContributionController::class, 'destroy'])
            ->name('contributions.destroy');

        Route::post('/contributions/assignments/{assignment}/notify-guardian',
            [\App\Http\Controllers\Teacher\ContributionController::class, 'notifyGuardian'])
            ->name('contributions.notify-guardian');
        Route::post('/contributions/assignments/{assignment}/mark-cash-received',
            [\App\Http\Controllers\Teacher\ContributionController::class, 'markCashReceived'])
            ->name('contributions.mark-cash-received');
        Route::post('/contributions/assignments/{assignment}/reject-cash',
            [\App\Http\Controllers\Teacher\ContributionController::class, 'rejectCash'])
            ->name('contributions.reject-cash');
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

        Route::get('/assessments', [\App\Http\Controllers\Student\AssessmentController::class, 'index'])->name('assessments.index');

        Route::get('/schedule', [\App\Http\Controllers\Student\ScheduleController::class, 'index'])->name('schedule.index');

        Route::get('/quizhub', [\App\Http\Controllers\Student\QuizHubController::class, 'index'])->name('quizhub.index');

        Route::get('/studentrecords', [\App\Http\Controllers\Student\StudentRecordsController::class, 'index'])->name('studentrecords.index');

        // ─── Assignments ───
        Route::get('/classes/{classroom}/assignments', [\App\Http\Controllers\Student\AssignmentController::class, 'index'])->name('classes.assignments.index');
        Route::get('/assignments/{assignment}', [\App\Http\Controllers\Student\AssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/assignments/{assignment}/submit', [\App\Http\Controllers\Student\AssignmentController::class, 'submit'])
            ->middleware('throttle:10,1')
            ->name('assignments.submit');

        // ─── Lessons ───
        Route::get('/classes/{classroom}/lessons', [\App\Http\Controllers\Student\LessonController::class, 'index'])->name('classes.lessons.index');
        Route::get('/lessons/{lesson}', [\App\Http\Controllers\Student\LessonController::class, 'show'])->name('lessons.show');

        // ─── Announcements ───
        Route::get('/classes/{classroom}/announcements', [\App\Http\Controllers\Student\AnnouncementController::class, 'index'])->name('classes.announcements.index');
        Route::get('/announcements/{announcement}', [\App\Http\Controllers\Student\AnnouncementController::class, 'show'])->name('announcements.show');
        Route::get('/announcements', [\App\Http\Controllers\Student\AnnouncementController::class, 'feed'])->name('announcements.feed');

        // ─── Downloads ───
        Route::get('/assignments/{assignment}/submission/file', [\App\Http\Controllers\Student\AssignmentController::class, 'downloadSubmission'])->name('assignments.submission.download');
        Route::get('/lesson-attachments/{attachment}/download', [\App\Http\Controllers\Student\LessonController::class, 'downloadAttachment'])->name('lesson-attachments.download');

        // ─── Grades ───
        Route::get('/grades', [\App\Http\Controllers\Student\GradeController::class, 'index'])->name('grades.index');
        Route::get('/classes/{classroom}/grades', [\App\Http\Controllers\Student\GradeController::class, 'show'])->name('classes.grades.show');

        // ─── Attendance ───
        Route::get('/attendance', [\App\Http\Controllers\Student\AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/{classroom}', [\App\Http\Controllers\Student\AttendanceController::class, 'show'])->name('attendance.show');

        // ─── Quizzes ───
        Route::get('/classes/{classroom}/quizzes', [\App\Http\Controllers\Student\QuizController::class, 'index'])->name('classes.quizzes.index');
        Route::get('/quizzes/{quiz}', [\App\Http\Controllers\Student\QuizController::class, 'show'])->name('quizzes.show');
        Route::post('/quizzes/{quiz}/start', [\App\Http\Controllers\Student\QuizController::class, 'start'])
            ->middleware('throttle:10,1')
            ->name('quizzes.start');

        Route::get('/quiz-attempts/{attempt}', [\App\Http\Controllers\Student\QuizController::class, 'active'])->name('quiz-attempts.active');
        Route::post('/quiz-attempts/{attempt}/answer', [\App\Http\Controllers\Student\QuizController::class, 'answer'])
            ->middleware('throttle:120,1')
            ->name('quiz-attempts.answer');
        Route::post('/quiz-attempts/{attempt}/warning', [\App\Http\Controllers\Student\QuizController::class, 'warning'])->name('quiz-attempts.warning');
        Route::post('/quiz-attempts/{attempt}/submit', [\App\Http\Controllers\Student\QuizController::class, 'submit'])->name('quiz-attempts.submit');
        Route::get('/quiz-attempts/{attempt}/result', [\App\Http\Controllers\Student\QuizController::class, 'result'])->name('quiz-attempts.result');

        // ─── Contributions (read-only status board — NEW) ───
        Route::get('/contributions', [\App\Http\Controllers\Student\ContributionController::class, 'index'])
            ->name('contributions.index');
        Route::get('/contributions/{assignment}', [\App\Http\Controllers\Student\ContributionController::class, 'show'])
            ->name('contributions.show');
        Route::post('/contributions/{assignment}/notify-guardian', [\App\Http\Controllers\Student\ContributionController::class, 'notifyGuardian'])
            ->middleware('throttle:3,10')
            ->name('contributions.notify-guardian');
    });

// ─────────────────────────────────────────────────────────────
// User Profile & System Utilities
// ─────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/password/change', [PasswordChangeController::class, 'show'])->name('password.change');
    Route::put('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.update');
});

// ─────────────────────────────────────────────────────────────
// Messaging
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'password.changed'])->group(function () {
    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [\App\Http\Controllers\MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{conversation}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}', [\App\Http\Controllers\MessageController::class, 'reply'])->name('messages.reply');
});

// ─────────────────────────────────────────────────────────────
// Avatar
// ─────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/profile/avatar', [\App\Http\Controllers\Profile\AvatarController::class, 'update'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [\App\Http\Controllers\Profile\AvatarController::class, 'destroy'])->name('profile.avatar.destroy');
});

// ─────────────────────────────────────────────────────────────
// Public Website
// ─────────────────────────────────────────────────────────────
Route::prefix('site')->name('site.')->group(function () {
    Route::get('/home', [\App\Http\Controllers\Site\HomeController::class, 'index'])->name('home');
    Route::get('/about', [\App\Http\Controllers\Site\AboutController::class, 'index'])->name('about');
    Route::get('/news', [\App\Http\Controllers\Site\NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{announcement}', [\App\Http\Controllers\Site\NewsController::class, 'show'])->name('news.show');
    Route::get('/contact', [\App\Http\Controllers\Site\ContactController::class, 'index'])->name('contact');
    Route::get('/admission', [\App\Http\Controllers\Site\AdmissionController::class, 'index'])->name('admission');
    Route::post('/admission/apply', [\App\Http\Controllers\Site\ApplicationController::class, 'store'])
        ->middleware('throttle:3,1')
        ->name('admission.apply');

    Route::get('/admission/status/{reference}', [\App\Http\Controllers\Site\ApplicationController::class, 'status'])->name('admission.status');
});

// ─────────────────────────────────────────────────────────────
// Shared Exports (teacher + admin)
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'password.changed'])
    ->prefix('exports')
    ->name('exports.')
    ->group(function () {
        Route::get('/class-list/{classroom}', [\App\Http\Controllers\Admin\ExportController::class, 'classList'])->name('class-list');
        Route::get('/gradebook/{classroom}', [\App\Http\Controllers\Admin\ExportController::class, 'gradebook'])->name('gradebook');
    });

// ─────────────────────────────────────────────────────────────
// Report Cards (admin + student)
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'password.changed'])
    ->prefix('reports')
    ->name('reports.')
    ->group(function () {
        Route::get('/students/{student}/report-card', [
            \App\Http\Controllers\Admin\ExportController::class,
            'reportCard',
        ])->name('students.report-card');
    });

// ─────────────────────────────────────────────────────────────
// School Years (legacy duplicate block — kept for compatibility)
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/school-years', [SchoolYearController::class, 'index'])->name('school-years.index');
    Route::post('/school-years', [SchoolYearController::class, 'store'])->name('school-years.store');
    Route::put('/school-years/{schoolYear}', [SchoolYearController::class, 'update'])->name('school-years.update');
    Route::delete('/school-years/{schoolYear}', [SchoolYearController::class, 'destroy'])->name('school-years.destroy');
    Route::post('/school-years/{schoolYear}/set-active', [SchoolYearController::class, 'setActive'])->name('school-years.set-active');
});

require __DIR__ . '/auth.php';