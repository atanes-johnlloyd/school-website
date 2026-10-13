<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Support\AuditContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Teachers/Index', [
            'departments' => Teacher::query()
                ->whereNotNull('department')
                ->distinct()
                ->orderBy('department')
                ->pluck('department'),
        ]);
    }

    /**
     * Shared validation rules for teacher create/update.
     * Pass a Teacher to skip unique checks against itself.
     */
    private function teacherRules(?Teacher $teacher = null): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                'regex:/^[\p{L}\s\'\-\.]+$/u',
            ],
            'email' => [
                'required', 'email:rfc,dns', 'max:255',
                $teacher
                    ? Rule::unique('users', 'email')->ignore($teacher->user_id)
                    : Rule::unique('users', 'email'),
            ],
            'password' => $teacher
                ? ['nullable', Password::defaults()]
                : ['required', Password::defaults()],

            'employee_no' => [
                'required', 'string', 'min:3', 'max:30',
                'regex:/^[A-Z0-9][A-Z0-9\-_]*$/i',   // alnum, dash, underscore; must start alnum
                $teacher
                    ? Rule::unique('teachers', 'employee_no')->ignore($teacher->id)
                    : Rule::unique('teachers', 'employee_no'),
            ],

            'sex'           => ['nullable', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            'date_hired'    => ['nullable', 'date', 'after:1950-01-01', 'before_or_equal:today'],

            'contact_number' => [
                'nullable', 'string',
                'regex:/^\d{7,11}$/',
            ],

            'department' => [
                'nullable', 'string', 'max:100',
                'regex:/^[\p{L}\s\-&\.]+$/u',       // letters, spaces, hyphens, ampersands, periods
            ],

            'specialization' => [
                'nullable', 'string', 'max:150',
                'regex:/^[\p{L}\p{N}\s\-\.\,\/\&]+$/u',
            ],

            'is_active' => ['boolean'],
        ];
    }

    /**
     * Human-readable messages for the shared rules.
     */
    private function teacherMessages(): array
    {
        return [
            'name.regex'              => 'Name may only contain letters, spaces, hyphens, apostrophes, and periods.',
            'employee_no.regex'       => 'Employee number may only contain letters, numbers, hyphens, and underscores.',
            'employee_no.min'         => 'Employee number must be at least 3 characters.',
            'contact_number.regex'    => 'Contact number must contain only digits (7–11 digits).',
            'department.regex'        => 'Department may only contain letters, spaces, hyphens, ampersands, and periods.',
            'specialization.regex'    => 'Specialization contains invalid characters.',
            'date_of_birth.before'    => 'Date of birth cannot be in the future.',
            'date_of_birth.after'     => 'Date of birth must be after 1900.',
            'date_hired.before_or_equal' => 'Date hired cannot be in the future.',
            'date_hired.after'        => 'Date hired must be after 1950.',
        ];
    }

    public function list(Request $request)
    {
        $validated = $request->validate([
            'status'     => ['nullable', 'in:active,inactive'],
            'department' => ['nullable', 'string', 'max:255'],
            'sex'        => ['nullable', 'in:male,female'],
            'search'     => ['nullable', 'string', 'max:100'],
            'per_page'   => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'    => ['nullable', 'in:employee_no,name,department,status,date_hired'],
            'sort_dir'   => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'employee_no';
        $sortDir = $validated['sort_dir'] ?? 'asc';

        $query = Teacher::query()->with([
            'user:id,name,email,avatar_path,status',
            'user.adminPosition:id,name',
        ]);

        if (! empty($validated['status'])) {
            $query->where('is_active', $validated['status'] === 'active');
        }
        if (! empty($validated['department'])) {
            $query->where('department', $validated['department']);
        }
        if (! empty($validated['sex'])) {
            $query->where('sex', $validated['sex']);
        }
        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('employee_no', 'like', "%{$s}%")
                  ->orWhere('department', 'like', "%{$s}%")
                  ->orWhere('specialization', 'like', "%{$s}%")
                  ->orWhereHas('user', fn ($uq) => $uq
                      ->where('name', 'like', "%{$s}%")
                      ->orWhere('email', 'like', "%{$s}%"));
            });
        }

        switch ($sortBy) {
            case 'name':
                $query->join('users', 'users.id', '=', 'teachers.user_id')
                      ->orderBy('users.name', $sortDir)
                      ->select('teachers.*');
                break;
            case 'status':
                $query->orderBy('is_active', $sortDir);
                break;
            default:
                $query->orderBy($sortBy, $sortDir);
        }

        $teachers = $query->paginate($validated['per_page'] ?? 10);

        $teachers->getCollection()->transform(fn (Teacher $t) => [
            'id'               => $t->id,
            'employee_no'      => $t->employee_no,
            'name'             => $t->user?->name,
            'email'            => $t->user?->email,
            'sex'              => $t->sex,
            'date_of_birth'    => $t->date_of_birth?->toDateString(),
            'contact_number'   => $t->contact_number,
            'date_hired'       => $t->date_hired?->toDateString(),
            'department'       => $t->department,
            'specialization'   => $t->specialization,
            'is_active'        => (bool) $t->is_active,
            'admin_position'   => $t->user?->adminPosition?->name,
        ]);

        return response()->json([
            'teachers' => $teachers,
            'filters'  => [
                'status'     => $validated['status']     ?? null,
                'department' => $validated['department'] ?? null,
                'sex'        => $validated['sex']        ?? null,
                'search'     => $validated['search']     ?? null,
            ],
            'sort'   => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => [
                'total'    => Teacher::count(),
                'active'   => Teacher::where('is_active', true)->count(),
                'inactive' => Teacher::where('is_active', false)->count(),
            ],
        ]);
    }

    public function show(Teacher $teacher)
    {
        $teacher->load([
            'user:id,name,email,avatar_path,status,admin_position_id',
            'user.adminPosition:id,name,description',
        ]);

        return response()->json([
            'teacher' => [
                'id'              => $teacher->id,
                'employee_no'     => $teacher->employee_no,
                'sex'             => $teacher->sex,
                'date_of_birth'   => $teacher->date_of_birth?->toDateString(),
                'contact_number'  => $teacher->contact_number,
                'date_hired'      => $teacher->date_hired?->toDateString(),
                'department'      => $teacher->department,
                'specialization'  => $teacher->specialization,
                'is_active'       => (bool) $teacher->is_active,
                'created_at'      => $teacher->created_at?->toIso8601String(),
                'user' => [
                    'id'             => $teacher->user->id,
                    'name'           => $teacher->user->name,
                    'email'          => $teacher->user->email,
                    'avatar'         => $teacher->user->avatar_path,
                    'status'         => $teacher->user->status,
                    'admin_position' => $teacher->user->adminPosition?->name,
                    'admin_position_description' => $teacher->user->adminPosition?->description,
                ],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->teacherRules(),
            $this->teacherMessages()
        );

        AuditContext::wrap('create_teacher', function () use ($validated) {
            DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name'                 => $validated['name'],
                    'email'                => $validated['email'],
                    'password'             => Hash::make($validated['password']),
                    'must_change_password' => true,
                    'email_verified_at'    => now(),
                    'status'               => 'active',
                ]);
                if (method_exists($user, 'assignRole')) $user->assignRole('teacher');

                Teacher::create([
                    'user_id'        => $user->id,
                    'employee_no'    => $validated['employee_no'],
                    'sex'            => $validated['sex']            ?? null,
                    'date_of_birth'  => $validated['date_of_birth']  ?? null,
                    'contact_number' => $validated['contact_number'] ?? null,
                    'date_hired'     => $validated['date_hired']     ?? null,
                    'department'     => $validated['department']     ?? null,
                    'specialization' => $validated['specialization'] ?? null,
                    'is_active'      => $validated['is_active']      ?? true,
                ]);
            });
        });

        return redirect()->back()->with('success', 'Faculty member created successfully.');
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate(
            $this->teacherRules($teacher),
            $this->teacherMessages()
        );

        AuditContext::wrap('update_teacher', function () use ($validated, $teacher) {
            DB::transaction(function () use ($validated, $teacher) {
                $teacher->user->update([
                    'name'  => $validated['name'],
                    'email' => $validated['email'],
                ]);

                $teacher->update([
                    'employee_no'    => $validated['employee_no'],
                    'sex'            => $validated['sex']            ?? null,
                    'date_of_birth'  => $validated['date_of_birth']  ?? null,
                    'contact_number' => $validated['contact_number'] ?? null,
                    'date_hired'     => $validated['date_hired']     ?? null,
                    'department'     => $validated['department']     ?? null,
                    'specialization' => $validated['specialization'] ?? null,
                    'is_active'      => $validated['is_active']      ?? $teacher->is_active,
                ]);
            });
        });

        return redirect()->back()->with('success', 'Faculty profile updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $name = $teacher->user?->name ?? 'Faculty member';

        AuditContext::wrap('deactivate_teacher', function () use ($teacher) {
            $teacher->update(['is_active' => false]);
            $teacher->user?->update(['status' => 'disabled']);
        });

        return redirect()->back()->with('success', "{$name} deactivated.");
    }

    public function restore(Teacher $teacher)
    {
        $name = $teacher->user?->name ?? 'Faculty member';

        AuditContext::wrap('restore_teacher', function () use ($teacher) {
            $teacher->update(['is_active' => true]);
            $teacher->user?->update(['status' => 'active']);
        });

        return redirect()->back()->with('success', "{$name} reactivated.");
    }

    public function resetPassword(Teacher $teacher)
    {
        $temp = Str::random(16);

        AuditContext::wrap('reset_teacher_password', function () use ($teacher, $temp) {
            $teacher->user->update([
                'password'             => Hash::make($temp),
                'must_change_password' => true,
            ]);
        });

        return redirect()->back()->with('success', "Password reset to temporary key: {$temp}");
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'status'     => ['nullable', 'in:active,inactive'],
            'department' => ['nullable', 'string', 'max:255'],
            'sex'        => ['nullable', 'in:male,female'],
            'search'     => ['nullable', 'string', 'max:100'],
        ]);

        $query = Teacher::query()->with('user:id,name,email');

        if (! empty($validated['status']))     $query->where('is_active', $validated['status'] === 'active');
        if (! empty($validated['department'])) $query->where('department', $validated['department']);
        if (! empty($validated['sex']))        $query->where('sex', $validated['sex']);
        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('employee_no', 'like', "%{$s}%")
                  ->orWhere('department', 'like', "%{$s}%")
                  ->orWhere('specialization', 'like', "%{$s}%")
                  ->orWhereHas('user', fn ($uq) => $uq
                      ->where('name', 'like', "%{$s}%")
                      ->orWhere('email', 'like', "%{$s}%"));
            });
        }

        $query->orderBy('employee_no');

        $filename = 'teachers-' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'employee_no', 'name', 'email', 'sex', 'date_of_birth', 'contact_number',
                'date_hired', 'department', 'specialization', 'is_active',
            ]);

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $t) {
                    fputcsv($out, [
                        $t->employee_no, $t->user?->name, $t->user?->email,
                        $t->sex, $t->date_of_birth?->toDateString(), $t->contact_number,
                        $t->date_hired?->toDateString(), $t->department, $t->specialization,
                        $t->is_active ? 'Yes' : 'No',
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:' . \App\Models\SystemSetting::maxFileUploadKb()],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        if (! $handle) {
            return response()->json(['message' => 'Could not read the uploaded file.'], 422);
        }

        $rawHeader = fgetcsv($handle);
        if (! $rawHeader) {
            fclose($handle);
            return response()->json(['message' => 'The CSV file is empty.'], 422);
        }
        $header = array_map(fn ($h) => Str::slug(trim((string) $h), '_'), $rawHeader);

        $required = ['employee_no', 'name', 'email'];
        $missing  = array_diff($required, $header);
        if (! empty($missing)) {
            fclose($handle);
            return response()->json([
                'message' => 'Missing required columns: ' . implode(', ', $missing),
            ], 422);
        }

        $created = 0;
        $errors  = [];
        $rowNum  = 1;

        AuditContext::wrap('bulk_import_teachers', function () use (&$created, &$errors, &$rowNum, $handle, $header, $required) {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNum++;
                if (empty(array_filter($row, fn ($v) => $v !== null && $v !== ''))) continue;

                $row  = array_pad($row, count($header), null);
                $row  = array_slice($row, 0, count($header));
                $data = array_combine($header, $row);

                try {
                    foreach ($required as $col) {
                        if (empty(trim((string) ($data[$col] ?? '')))) {
                            throw new \RuntimeException("Column '{$col}' is empty.");
                        }
                    }

                    $name  = trim((string) $data['name']);
                    $email = trim((string) $data['email']);
                    $empNo = trim((string) $data['employee_no']);

                    // ── Format validation (mirrors store/update) ────────────────
                    $rowErrors = [];

                    if (! preg_match('/^[\p{L}\s\'\-\.]+$/u', $name)) {
                        $rowErrors[] = 'name may only contain letters, spaces, hyphens, apostrophes, and periods';
                    }

                    if (! preg_match('/^[A-Z0-9][A-Z0-9\-_]*$/i', $empNo)) {
                        $rowErrors[] = 'employee number may only contain letters, numbers, hyphens, and underscores';
                    } elseif (strlen($empNo) < 3 || strlen($empNo) > 30) {
                        $rowErrors[] = 'employee number must be 3–30 characters';
                    }

                    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $rowErrors[] = 'email is not a valid address';
                    }

                    $contact = trim((string) ($data['contact_number'] ?? ''));
                    if ($contact !== '' && ! preg_match('/^\d{7,11}$/', $contact)) {
                        $rowErrors[] = 'contact number must contain only digits (7–11 digits)';
                    }

                    $dept = trim((string) ($data['department'] ?? ''));
                    if ($dept !== '' && ! preg_match('/^[\p{L}\s\-&\.]+$/u', $dept)) {
                        $rowErrors[] = 'department contains invalid characters';
                    }

                    $spec = trim((string) ($data['specialization'] ?? ''));
                    if ($spec !== '' && ! preg_match('/^[\p{L}\p{N}\s\-\.\,\/\&]+$/u', $spec)) {
                        $rowErrors[] = 'specialization contains invalid characters';
                    }

                    $dob = trim((string) ($data['date_of_birth'] ?? ''));
                    if ($dob !== '') {
                        $parsed = strtotime($dob);
                        if ($parsed === false) {
                            $rowErrors[] = 'date of birth is not a valid date';
                        } elseif ($parsed > time()) {
                            $rowErrors[] = 'date of birth cannot be in the future';
                        }
                    }

                    $hired = trim((string) ($data['date_hired'] ?? ''));
                    if ($hired !== '') {
                        $parsed = strtotime($hired);
                        if ($parsed === false) {
                            $rowErrors[] = 'date hired is not a valid date';
                        } elseif ($parsed > time()) {
                            $rowErrors[] = 'date hired cannot be in the future';
                        }
                    }

                    $sex = strtolower(trim((string) ($data['sex'] ?? '')));
                    if ($sex !== '' && ! in_array($sex, ['male', 'female'], true)) {
                        $rowErrors[] = "sex must be 'male' or 'female'";
                    }

                    if (! empty($rowErrors)) {
                        throw new \RuntimeException('Format issues: ' . implode('; ', $rowErrors) . '.');
                    }

                    // ── Uniqueness ───────────────────────────────────────────────
                    if (User::where('email', $email)->exists()) {
                        throw new \RuntimeException("Email '{$email}' already exists.");
                    }
                    if (Teacher::where('employee_no', $empNo)->exists()) {
                        throw new \RuntimeException("Employee number '{$empNo}' already exists.");
                    }

                    // ── Persist ──────────────────────────────────────────────────
                    DB::transaction(function () use ($data, $name, $email, $empNo, $contact, $dept, $spec, $dob, $hired, $sex) {
                        $user = User::create([
                            'name'                 => $name,
                            'email'                => $email,
                            'password'             => Hash::make(Str::random(16)),
                            'must_change_password' => true,
                            'email_verified_at'    => now(),
                            'status'               => 'active',
                        ]);
                        if (method_exists($user, 'assignRole')) $user->assignRole('teacher');

                        Teacher::create([
                            'user_id'        => $user->id,
                            'employee_no'    => $empNo,
                            'sex'            => $sex !== ''   ? $sex   : null,
                            'date_of_birth'  => $dob !== ''   ? $dob   : null,
                            'contact_number' => $contact !== '' ? $contact : null,
                            'date_hired'     => $hired !== '' ? $hired : null,
                            'department'     => $dept !== ''  ? $dept  : null,
                            'specialization' => $spec !== ''  ? $spec  : null,
                            'is_active'      => strtolower(trim((string) ($data['is_active'] ?? 'yes'))) !== 'no',
                        ]);
                    });

                    $created++;
                } catch (\Throwable $e) {
                    $errors[] = "Row {$rowNum}: " . $e->getMessage();
                }
            }
        });

        fclose($handle);

        return response()->json([
            'message' => "Imported {$created} faculty member(s)."
                . (count($errors) ? ' ' . count($errors) . ' row(s) skipped.' : ''),
            'created' => $created,
            'errors'  => $errors,
        ]);
    }
}