<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TemplateMail;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // `authenticate()` above logs the user in already. If they don't
        // need 2FA, we're done. If they do, we immediately log them back
        // out and hold their identity in the session until the OTP passes.
        $user = $request->user();

        if (! $user->requiresTwoFactor()) {
            return $this->redirectByRole($request, $user);
        }

        // Roll back the login — the user is *not* authenticated yet.
        Auth::guard('web')->logout();

        $plain = $user->generateTwoFactorCode();

        // Send the code. Reuses the TemplateMail wrapper so it inherits
        // the [MAIL] Sent/Failed logging you already have.
        try {
            Mail::to($user->email)->send(new TemplateMail(
                templateKey: 'login-otp',
                placeholders: [
                    'full_name'  => $user->name,
                    'code'       => $plain,
                    'expires_in' => '10 minutes',
                    'ip_address' => $request->ip(),
                ],
                emailSubject: 'Your Login Code - Salawag SHS',
            ));
        } catch (\Throwable $e) {
            Log::warning('[MAIL] Login OTP failed', [
                'user_id' => $user->id,
                'to'      => $user->email,
                'error'   => $e->getMessage(),
            ]);
        }

        // Hold identity in the session for the challenge page.
        $request->session()->put([
            'auth.2fa.user_id'    => $user->id,
            'auth.2fa.remember'   => $request->boolean('remember'),
            'auth.2fa.created_at' => now()->timestamp,
        ]);

        $request->session()->regenerate();

        return redirect()->route('login.verify');
    }

    /**
     * Central place for role → dashboard routing.
     */
    protected function redirectByRole(Request $request, User $user): RedirectResponse
    {
        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        return match (true) {
            $user->hasRole('admin')   => redirect()->route('admin.dashboard'),
            $user->hasRole('teacher') => redirect()->route('teacher.dashboard'),
            $user->hasRole('student') => redirect()->route('student.dashboard'),
            default                   => redirect()->route('dashboard'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}