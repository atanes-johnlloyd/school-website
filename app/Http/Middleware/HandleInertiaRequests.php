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
                'user' => (function () use ($request) {
                    $user = $request->user();
                    if (! $user) return null;

                    $user->load('adminPosition:id,name');

                    if ($user->hasRole('teacher')) {
                        $user->load('teacher:id,user_id,employee_no,department');
                    }

                    return $user;
                })(),
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
            'activeSchoolYear' => fn () => $request->user()
            ? \App\Models\SchoolYear::where('is_active', true)->value('label')
            : null,
            'notificationCount' => fn () => $request->user()
                ? $request->user()->unreadNotifications()->count()
                : 0,

            'notifications' => fn () => $request->user()
                ? $request->user()
                    ->notifications()
                    ->latest()
                    ->limit(10)
                    ->get()
                    ->map(fn ($n) => [
                        'id'         => $n->id,
                        'title'      => $n->data['title'] ?? 'Notification',
                        'body'       => $n->data['body'] ?? '',
                        'url'        => $n->data['url'] ?? null,
                        'kind'       => $n->data['kind'] ?? 'info',
                        'read_at'    => $n->read_at?->toIso8601String(),
                        'created_at' => $n->created_at->diffForHumans(),
                    ])
                    ->values()
                : [],
            
        ];
    }
}
