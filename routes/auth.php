<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('login');

    // ── Forgot password (custom PasswordRecovery.vue) ──
    Route::get('forgot-password', function () {
        return Inertia::render('Auth/PasswordRecovery');
    })->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:3,1')
        ->name('password.email');

    // ── Email confirmation landing (custom ConfirmationEmail.vue) ──
    Route::get('confirmation-email', function () {
        return Inertia::render('Auth/ConfirmationEmail');
    })->name('confirmation.email');

    // ── Reset password link (custom NewPassword.vue) ──
    Route::get('reset-password/{token}', function ($token) {
        return Inertia::render('Auth/NewPassword', [
            'token' => $token,
            'email' => request('email'),
        ]);
    })->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    // ── Email verification ──
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // ── Change password (custom ChangePassword.vue) ──
    Route::get('password/change', [PasswordChangeController::class, 'show'])
        ->name('password.change');

    Route::put('password/change', [PasswordChangeController::class, 'update'])
        ->name('password.change.update');

    // ── Logout ──
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});