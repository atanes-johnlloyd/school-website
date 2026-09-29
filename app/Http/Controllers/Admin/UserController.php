<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Http\Requests\Admin\ToggleUserStatusRequest;
use App\Models\AdminPosition;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct(protected NotificationService $notifications) {}

    /**
     * List admin/staff users (role=admin), with counts.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status'      => ['nullable', 'in:active,disabled'],
            'position_id' => ['nullable', 'integer', 'exists:admin_positions,id'],
            'search'      => ['nullable', 'string', 'max:100'],
        ]);

        $query = User::role('admin')
            ->with('adminPosition:id,name')
            ->latest();

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

        return response()->json([
            'users' => $query->get()->map(fn (User $u) => [
                'id'              => $u->id,
                'name'            => $u->name,
                'email'           => $u->email,
                'avatar_url'      => $u->avatar_url,
                'status'          => $u->status,
                'disabled_reason' => $u->disabled_reason,
                'admin_position'  => $u->adminPosition?->name,
                'created_at'      => $u->created_at?->toIso8601String(),
            ]),
            'stats' => [
                'total'    => User::role('admin')->count(),
                'active'   => User::role('admin')->where('status', 'active')->count(),
                'disabled' => User::role('admin')->where('status', 'disabled')->count(),
            ],
            'positions' => AdminPosition::select('id', 'name')->orderBy('name')->get(),
        ]);
    }

    /**
     * Create a new admin account with temp password + reset token.
     */
    public function store(StoreAdminUserRequest $request)
    {
        $tempPassword = 'Admin@' . Str::random(8);
        $resetToken   = bin2hex(random_bytes(32));

        $user = DB::transaction(function () use ($request, $tempPassword, $resetToken) {
            $user = User::create([
                'name'                 => $request->validated('name'),
                'email'                => $request->validated('email'),
                'password'             => Hash::make($tempPassword),
                'must_change_password' => true,
                'email_verified_at'    => now(),
                'admin_position_id'    => $request->validated('admin_position_id'),
                'status'               => 'active',
                'reset_token'          => $resetToken,
                'reset_expiry'         => now()->addHours(24),
            ]);
            $user->assignRole('admin');

            return $user;
        });

        // Send account-created email
        $loginLink = url("/admin/login?token={$resetToken}");

        $this->notifications->send(
            $user->email,
            'Your Admin Account - Salawag Senior High School',
            'account-created',
            [
                'full_name'        => $user->name,
                'username'         => $user->email,
                'default_password' => $tempPassword,
                'login_link'       => $loginLink,
            ]
        );

        return response()->json([
            'message' => 'Admin user created. Credentials email sent.',
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    public function show(Request $request, User $user)
    {
        abort_unless($user->hasRole('admin'), 404);

        $user->load('adminPosition:id,name');

        return response()->json([
            'user' => [
                'id'               => $user->id,
                'name'             => $user->name,
                'email'            => $user->email,
                'avatar_url'       => $user->avatar_url,
                'status'           => $user->status,
                'disabled_reason'  => $user->disabled_reason,
                'admin_position_id'=> $user->admin_position_id,
                'admin_position'   => $user->adminPosition?->name,
                'roles'            => $user->getRoleNames(),
                'permissions'      => $user->getAllPermissions()->pluck('name'),
                'last_login_at'    => $user->updated_at?->toIso8601String(),
                'created_at'       => $user->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function update(UpdateAdminUserRequest $request, User $user)
    {
        abort_unless($user->hasRole('admin'), 404);

        $user->update($request->validated());

        return response()->json([
            'message' => 'User updated.',
            'user'    => $user->fresh()->load('adminPosition:id,name'),
        ]);
    }

    /**
     * Toggle disable/enable, send corresponding email.
     */
    public function toggleStatus(ToggleUserStatusRequest $request, User $user)
    {
        abort_unless($user->hasRole('admin'), 404);

        // Cannot disable yourself
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'You cannot disable your own account.'], 422);
        }

        // Cannot disable the primary admin (id=1)
        if ($user->id === 1) {
            return response()->json(['message' => 'Cannot disable the primary admin.'], 422);
        }

        $isDisabling = $user->status === 'active';
        $reason      = $request->validated('reason');

        $user->update([
            'status'          => $isDisabling ? 'disabled' : 'active',
            'disabled_reason' => $isDisabling ? $reason : null,
        ]);

        // Send email
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

    /**
     * Soft-remove admin access (revert role instead of hard delete).
     */
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