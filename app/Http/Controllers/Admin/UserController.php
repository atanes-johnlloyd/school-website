<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPosition;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(protected NotificationService $notifications) {}

    /* ═══════════════ INDEX — page shell ═══════════════ */
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'positions' => AdminPosition::where('is_active', true)
                ->select('id', 'name', 'description')
                ->orderBy('name')
                ->get(),
        ]);
    }

    /* ═══════════════ LIST — JSON for the Vue table ═══════════════ */
    public function list(Request $request)
    {
        $validated = $request->validate([
            'status'      => ['nullable', 'in:active,disabled'],
            'position_id' => ['nullable', 'integer', 'exists:admin_positions,id'],
            'search'      => ['nullable', 'string', 'max:100'],
            'per_page'    => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'     => ['nullable', 'in:name,email,status,created_at'],
            'sort_dir'    => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'created_at';
        $sortDir = $validated['sort_dir'] ?? 'desc';

        $query = User::role('admin')
            ->with('adminPosition:id,name');

        if (! empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }
        if (! empty($validated['position_id'])) {
            $query->where('admin_position_id', $validated['position_id']);
        }
        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $query->orderBy($sortBy, $sortDir);

        $users = $query->paginate($validated['per_page'] ?? 10);

        $users->getCollection()->transform(fn (User $u) => [
            'id'              => $u->id,
            'name'            => $u->name,
            'email'           => $u->email,
            'avatar_url'      => $u->avatar_url,
            'status'          => $u->status ?? 'active',
            'disabled_reason' => $u->disabled_reason,
            'admin_position'  => $u->adminPosition?->name,
            'admin_position_id' => $u->admin_position_id,
            'is_primary'      => $u->id === 1,
            'created_at'      => $u->created_at?->toIso8601String(),
        ]);

        return response()->json([
            'users' => $users,
            'filters' => [
                'status'      => $validated['status']      ?? null,
                'position_id' => $validated['position_id'] ?? null,
                'search'      => $validated['search']      ?? null,
            ],
            'sort' => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => [
                'total'    => User::role('admin')->count(),
                'active'   => User::role('admin')->where('status', 'active')->count(),
                'disabled' => User::role('admin')->where('status', 'disabled')->count(),
            ],
        ]);
    }

    /* ═══════════════ SHOW ═══════════════ */
    public function show(Request $request, User $user)
    {
        abort_unless($user->hasRole('admin'), 404);

        $user->load('adminPosition:id,name,description');

        return response()->json([
            'user' => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'avatar_url'        => $user->avatar_url,
                'status'            => $user->status ?? 'active',
                'disabled_reason'   => $user->disabled_reason,
                'admin_position_id' => $user->admin_position_id,
                'admin_position'    => $user->adminPosition?->name,
                'admin_position_description' => $user->adminPosition?->description,
                'roles'             => $user->getRoleNames(),
                'permissions'       => $user->getAllPermissions()->pluck('name'),
                'is_primary'        => $user->id === 1,
                'created_at'        => $user->created_at?->toIso8601String(),
                'updated_at'        => $user->updated_at?->toIso8601String(),
            ],
        ]);
    }

    /* ═══════════════ STORE ═══════════════ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_position_id' => ['required', 'integer', 'exists:admin_positions,id'],
        ]);

        $tempPassword = 'Admin@' . Str::random(8);
        $resetToken   = bin2hex(random_bytes(32));

        $user = DB::transaction(function () use ($validated, $tempPassword, $resetToken) {
            $u = User::create([
                'name'                 => $validated['name'],
                'email'                => $validated['email'],
                'password'             => Hash::make($tempPassword),
                'must_change_password' => true,
                'email_verified_at'    => now(),
                'admin_position_id'    => $validated['admin_position_id'],
                'status'               => 'active',
                'reset_token'          => $resetToken,
                'reset_expiry'         => now()->addHours(24),
            ]);
            $u->assignRole('admin');

            $position = AdminPosition::find($validated['admin_position_id']);
            if ($position && $position->default_permissions) {
                $u->syncPermissions($position->default_permissions);
            }

            return $u;
        });

        // Send account-created email
        $this->notifications->send(
            $user->email,
            'Your Admin Account - Salawag Senior High School',
            'account-created',
            [
                'full_name'        => $user->name,
                'username'         => $user->email,
                'default_password' => $tempPassword,
                'login_link'       => url('/login'),
            ]
        );

        return response()->json([
            'message' => 'Admin user created. Credentials email sent.',
            'user'    => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
        ], 201);
    }

    /* ═══════════════ UPDATE ═══════════════ */
    public function update(Request $request, User $user)
    {
        abort_unless($user->hasRole('admin'), 404);

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255',
                                    Rule::unique('users', 'email')->ignore($user->id)],
            'admin_position_id' => ['required', 'integer', 'exists:admin_positions,id'],
        ]);

        DB::transaction(function () use ($validated, $user) {
            $user->update($validated);

            $position = AdminPosition::find($validated['admin_position_id']);
            $user->syncPermissions($position?->default_permissions ?? []);
        });

        return response()->json([
            'message' => 'User updated.',
            'user'    => $user->fresh()->load('adminPosition:id,name'),
        ]);
    }

    /* ═══════════════ TOGGLE STATUS ═══════════════ */
    public function toggleStatus(Request $request, User $user)
    {
        abort_unless($user->hasRole('admin'), 404);

        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot disable your own account.'], 422);
        }
        if ($user->id === 1) {
            return response()->json(['message' => 'Cannot disable the primary admin.'], 422);
        }

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $isDisabling = ($user->status ?? 'active') === 'active';
        $reason      = $validated['reason'] ?? null;

        $user->update([
            'status'          => $isDisabling ? 'disabled' : 'active',
            'disabled_reason' => $isDisabling ? $reason : null,
        ]);

        $adminName = $request->user()->name;
        $date      = now()->format('F d, Y \a\t h:i A');

        if ($isDisabling) {
            $this->notifications->send(
                $user->email,
                'Account Disabled - Salawag Senior High School',
                'account-disabled',
                [
                    'full_name'  => $user->name,
                    'admin_name' => $adminName,
                    'date'       => $date,
                    'reason'     => $reason ?? 'No reason provided',
                ]
            );
            return response()->json(['message' => 'User disabled. Email sent.', 'status' => 'disabled']);
        }

        $this->notifications->send(
            $user->email,
            'Account Reactivated - Salawag Senior High School',
            'account-reactivated',
            [
                'full_name'  => $user->name,
                'admin_name' => $adminName,
                'date'       => $date,
            ]
        );

        return response()->json(['message' => 'User reactivated. Email sent.', 'status' => 'active']);
    }

    /* ═══════════════ RESET PASSWORD ═══════════════ */
    public function resetPassword(Request $request, User $user)
    {
        abort_unless($user->hasRole('admin'), 404);

        $temp = 'Admin@' . Str::random(8);

        $user->update([
            'password'             => Hash::make($temp),
            'must_change_password' => true,
        ]);

        return response()->json([
            'message'  => 'Password reset. Share the temporary key with the user.',
            'temp_key' => $temp,
        ]);
    }

    /* ═══════════════ DESTROY (revoke admin access) ═══════════════ */
    public function destroy(Request $request, User $user)
    {
        abort_unless($user->hasRole('admin'), 404);

        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot remove your own admin access.'], 422);
        }
        if ($user->id === 1) {
            return response()->json(['message' => 'Cannot remove the primary admin.'], 422);
        }

        $user->update([
            'status'          => 'disabled',
            'disabled_reason' => 'Admin access revoked by ' . $request->user()->name,
        ]);
        $user->removeRole('admin');

        return response()->json(['message' => 'Admin access revoked.']);
    }
}