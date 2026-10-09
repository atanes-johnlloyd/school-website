<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use App\Support\PasswordPolicy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user()?->load('adminPosition:id,name'),
                'roles' => $request->user()?->getRoleNames() ?? [],
                'can' => $request->user()
                    ? $request->user()->getAllPermissions()->pluck('name')->mapWithKeys(fn ($p) => [$p => true])
                    : [],
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'info'    => fn () => $request->session()->get('info'),
                'warning' => fn () => $request->session()->get('warning'),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'unreadContactCount' => fn () => $request->user()?->hasRole('admin')
            ? \App\Models\ContactMessage::where('is_read', false)->count()
            : 0,
            'passwordRule' => PasswordPolicy::toArray(),
            'activeSchoolYear' => fn () => $request->user() && $request->user()->hasRole('admin')
            ? \App\Models\SchoolYear::where('is_active', true)->value('label')
            : null,
        ];
    }
}
