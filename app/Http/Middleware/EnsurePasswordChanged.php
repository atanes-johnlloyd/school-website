<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Skip if not logged in, or password already changed
        if (! $user || ! $user->must_change_password) {
            return $next($request);
        }

        // Allow these routes so the user CAN change their password
        if ($request->routeIs(
            'password.change',
            'password.change.update',
            'logout',
        )) {
            return $next($request);
        }

        return redirect()
            ->route('password.change')
            ->with('warning', 'You must change your password before continuing.');
    }
}