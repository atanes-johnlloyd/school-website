<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TemplateMail;

class LoginTwoFactorController extends Controller
{
    /**
     * Show the OTP entry card.
     */
    public function show(Request $request): Response|RedirectResponse
    {
        if (! $request->session()->has('auth.2fa.user_id')) {
            return redirect()->route('login');
        }

        return Inertia::render('Auth/TwoFactorChallenge', [
            'email' => User::find($request->session()->get('auth.2fa.user_id'))?->email,
        ]);
    }

    /**
     * Verify the submitted code and issue the real session.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('auth.2fa.user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if (! $user) {
            $request->session()->forget(['auth.2fa.user_id', 'auth.2fa.remember', 'auth.2fa.created_at']);
            return redirect()->route('login');
        }

        // Hard 15-minute cap on the entire challenge, independent of the
        // code's own 10-min expiry. Prevents an attacker who stole the
        // session cookie from stalling indefinitely.
        $createdAt = $request->session()->get('auth.2fa.created_at', 0);
        if (now()->timestamp - $createdAt > 15 * 60) {
            $request->session()->forget(['auth.2fa.user_id', 'auth.2fa.remember', 'auth.2fa.created_at']);
            return redirect()->route('login')->withErrors([
                'code' => 'Your verification window expired. Please sign in again.',
            ]);
        }

        if (! $user->verifyTwoFactorCode($request->input('code'))) {
            return back()->withErrors([
                'code' => 'That code is invalid or has expired. Please try again.',
            ]);
        }

        // Code passed — finally log them in for real.
        Auth::login($user, (bool) $request->session()->pull('auth.2fa.remember', false));

        $request->session()->forget(['auth.2fa.user_id', 'auth.2fa.created_at']);
        $request->session()->regenerate();

        return match (true) {
            $user->hasRole('admin')   => redirect()->route('admin.dashboard'),
            $user->hasRole('teacher') => redirect()->route('teacher.dashboard'),
            $user->hasRole('student') => redirect()->route('student.dashboard'),
            default                   => redirect()->route('dashboard'),
        };
    }

    /**
     * Resend a fresh code. Rate-limited by the route's throttle middleware.
     */
    public function resend(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('auth.2fa.user_id');
        $user   = $userId ? User::find($userId) : null;

        if (! $user) {
            return redirect()->route('login');
        }

        $plain = $user->generateTwoFactorCode();

        try {
            \Mail::to($user->email)->send(new \App\Mail\TemplateMail(
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
            \Log::warning('[MAIL] Login OTP resend failed', [
                'user_id' => $user->id, 'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'A new code has been sent to your email.');
    }
}