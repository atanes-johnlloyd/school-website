<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('user:id,name,email,must_change_password');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('lrn', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return response()->json([
            'students' => $query->orderBy('lrn')->paginate(20),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', Password::defaults()],
            'lrn'            => ['required', 'string', 'size:12', 'unique:students,lrn'],
            'sex'            => ['required', 'in:male,female'],
            'date_of_birth'  => ['required', 'date', 'before:today'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'house_street'   => ['nullable', 'string', 'max:255'],
            'barangay'       => ['nullable', 'string', 'max:255'],
            'municipality'   => ['nullable', 'string', 'max:255'],
            'province'       => ['nullable', 'string', 'max:255'],
            'zip_code'       => ['nullable', 'string', 'size:4'],
        ]);

        $student = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'                 => $validated['name'],
                'email'                => $validated['email'],
                'password'             => Hash::make($validated['password']),
                'must_change_password' => true,
            ]);
            $user->assignRole('student');

            return Student::create([
                'user_id'        => $user->id,
                'lrn'            => $validated['lrn'],
                'sex'            => $validated['sex'],
                'date_of_birth'  => $validated['date_of_birth'],
                'contact_number' => $validated['contact_number'] ?? null,
                'house_street'   => $validated['house_street'] ?? null,
                'barangay'       => $validated['barangay'] ?? null,
                'municipality'   => $validated['municipality'] ?? null,
                'province'       => $validated['province'] ?? null,
                'zip_code'       => $validated['zip_code'] ?? null,
                'status'         => 'active',
            ]);
        });

        return response()->json([
            'message' => 'Student created. They must change password on first login.',
            'student' => $student->load('user:id,name,email'),
        ], 201);
    }

    public function show(Student $student)
    {
        return response()->json([
            'student' => $student->load([
                'user',
                'enrollments.section.strand',
                'enrollments.schoolYear',
            ]),
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name'           => ['sometimes', 'string', 'max:255'],
            'email'          => ['sometimes', 'email', 'max:255',
                                 Rule::unique('users', 'email')->ignore($student->user_id)],
            'lrn'            => ['sometimes', 'string', 'size:12',
                                 Rule::unique('students', 'lrn')->ignore($student->id)],
            'sex'            => ['sometimes', 'in:male,female'],
            'date_of_birth'  => ['sometimes', 'date', 'before:today'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'house_street'   => ['nullable', 'string', 'max:255'],
            'barangay'       => ['nullable', 'string', 'max:255'],
            'municipality'   => ['nullable', 'string', 'max:255'],
            'province'       => ['nullable', 'string', 'max:255'],
            'zip_code'       => ['nullable', 'string', 'size:4'],
            'status'         => ['sometimes', 'in:active,graduated,dropped_out,transferred_out'],
        ]);

        DB::transaction(function () use ($validated, $student) {
            if (isset($validated['name']) || isset($validated['email'])) {
                $student->user->update(array_filter([
                    'name'  => $validated['name'] ?? null,
                    'email' => $validated['email'] ?? null,
                ]));
            }
            unset($validated['name'], $validated['email']);
            $student->update($validated);
        });

        return response()->json(['student' => $student->fresh()->load('user:id,name,email')]);
    }

    public function destroy(Student $student)
    {
        $student->update(['status' => 'transferred_out']);
        $student->delete();

        return response()->json(['message' => 'Student deactivated.']);
    }

    /**
     * Admin-triggered password reset — issues a new temp password.
     */
    public function resetPassword(Student $student)
    {
        $temp = \Str::random(16);
        $student->user->update([
            'password'             => Hash::make($temp),
            'must_change_password' => true,
        ]);

        return response()->json([
            'message'       => 'Password reset. User must change on next login.',
            'temp_password' => $temp,   // in a real system: send via email
        ]);
    }
}