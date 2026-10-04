<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class PasswordChangeController extends Controller
{
    /**
     * Show the change-password screen.
     * Reached when `password.changed` middleware detects must_change_password = true,
     * or manually by an authenticated user.
     */
    public function show(Request $request): Response
    {
        return Inertia::render('Auth/ChangePassword', [
            'mustChange' => (bool) $request->user()->must_change_password,
        ]);
    }

    /**
     * Save the new password and clear the must_change_password flag.
     *
     * No `current_password` check — the user is either:
     *   (a) mid-login with a temporary password (just authenticated), or
     *   (b) an already-authenticated user reaching /password/change manually.
     * In both cases, session auth is proof of identity.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->forceFill([
            'password'             => Hash::make($validated['password']),
            'must_change_password' => false,
            'reset_token'          => null,
            'reset_expiry'         => null,
        ])->save();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Password updated. Welcome!');
    }
}