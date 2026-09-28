<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with('user:id,name,email,must_change_password,admin_position_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('employee_no', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        return response()->json([
            'teachers' => $query->orderBy('employee_no')->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'        => ['required', Password::defaults()],
            'employee_no'     => ['required', 'string', 'max:50', 'unique:teachers,employee_no'],
            'sex'             => ['nullable', 'in:male,female'],
            'date_of_birth'   => ['nullable', 'date'],
            'contact_number'  => ['nullable', 'string', 'max:20'],
            'date_hired'      => ['nullable', 'date'],
            'department'      => ['nullable', 'string', 'max:255'],
            'specialization'  => ['nullable', 'string', 'max:255'],
            'admin_position_id' => ['nullable', 'exists:admin_positions,id'],
        ]);

        $teacher = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'                 => $validated['name'],
                'email'                => $validated['email'],
                'password'             => Hash::make($validated['password']),
                'must_change_password' => true,   // forced on first login
                'admin_position_id'    => $validated['admin_position_id'] ?? null,
            ]);
            $user->assignRole('teacher');

            return Teacher::create([
                'user_id'        => $user->id,
                'employee_no'    => $validated['employee_no'],
                'sex'            => $validated['sex'] ?? null,
                'date_of_birth'  => $validated['date_of_birth'] ?? null,
                'contact_number' => $validated['contact_number'] ?? null,
                'date_hired'     => $validated['date_hired'] ?? null,
                'department'     => $validated['department'] ?? null,
                'specialization' => $validated['specialization'] ?? null,
                'is_active'      => true,
            ]);
        });

        return response()->json([
            'message' => 'Teacher created. They must change password on first login.',
            'teacher' => $teacher->load('user:id,name,email'),
        ], 201);
    }

    public function show(Teacher $teacher)
    {
        return response()->json([
            'teacher' => $teacher->load(['user', 'classes.subject', 'classes.section']),
        ]);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name'           => ['sometimes', 'string', 'max:255'],
            'email'          => ['sometimes', 'email', 'max:255',
                                 Rule::unique('users', 'email')->ignore($teacher->user_id)],
            'employee_no'    => ['sometimes', 'string', 'max:50',
                                 Rule::unique('teachers', 'employee_no')->ignore($teacher->id)],
            'sex'            => ['nullable', 'in:male,female'],
            'date_of_birth'  => ['nullable', 'date'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'date_hired'     => ['nullable', 'date'],
            'department'     => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'is_active'      => ['boolean'],
        ]);

        DB::transaction(function () use ($validated, $teacher) {
            if (isset($validated['name']) || isset($validated['email'])) {
                $teacher->user->update(array_filter([
                    'name'  => $validated['name'] ?? null,
                    'email' => $validated['email'] ?? null,
                ]));
            }
            unset($validated['name'], $validated['email']);
            $teacher->update($validated);
        });

        return response()->json(['teacher' => $teacher->fresh()->load('user:id,name,email')]);
    }

    public function destroy(Teacher $teacher)
    {
        // Soft-deactivate instead of hard delete
        $teacher->update(['is_active' => false]);
        $teacher->delete();

        return response()->json(['message' => 'Teacher deactivated.']);
    }
}