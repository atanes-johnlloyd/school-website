<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
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
    /* ═══════════════ INDEX — page shell with filter options ═══════════════ */
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

    /* ═══════════════ LIST — JSON for the Vue table ═══════════════ */
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

    /* ═══════════════ SHOW — full detail ═══════════════ */
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

    /* ═══════════════ STORE ═══════════════ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', Password::defaults()],
            'employee_no'    => ['required', 'string', 'max:50', 'unique:teachers,employee_no'],
            'sex'            => ['nullable', 'in:male,female'],
            'date_of_birth'  => ['nullable', 'date', 'before:today'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'date_hired'     => ['nullable', 'date'],
            'department'     => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'is_active'      => ['boolean'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'                 => $validated['name'],
                'email'                => $validated['email'],
                'password'             => Hash::make($validated['password']),
                'must_change_password' => true,
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

        return redirect()->back()->with('success', 'Faculty member created successfully.');
    }

    /* ═══════════════ UPDATE ═══════════════ */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($teacher->user_id)],
            'employee_no'    => ['required', 'string', 'max:50', Rule::unique('teachers', 'employee_no')->ignore($teacher->id)],
            'sex'            => ['nullable', 'in:male,female'],
            'date_of_birth'  => ['nullable', 'date', 'before:today'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'date_hired'     => ['nullable', 'date'],
            'department'     => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'is_active'      => ['boolean'],
        ]);

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

        return redirect()->back()->with('success', 'Faculty profile updated successfully.');
    }

    /* ═══════════════ DESTROY ═══════════════ */
    public function destroy(Teacher $teacher)
    {
        $teacher->update(['is_active' => false]);
        $teacher->delete();

        return redirect()->back()->with('success', 'Faculty member deactivated.');
    }

    /* ═══════════════ RESET PASSWORD ═══════════════ */
    public function resetPassword(Teacher $teacher)
    {
        $temp = Str::random(16);
        $teacher->user->update([
            'password'             => Hash::make($temp),
            'must_change_password' => true,
        ]);

        return redirect()->back()->with('success', "Password reset to temporary key: {$temp}");
    }

    /* ═══════════════ EXPORT ═══════════════ */
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

    /* ═══════════════ IMPORT ═══════════════ */
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

                $email = trim((string) $data['email']);
                $empNo = trim((string) $data['employee_no']);

                if (User::where('email', $email)->exists()) {
                    throw new \RuntimeException("Email '{$email}' already exists.");
                }
                if (Teacher::where('employee_no', $empNo)->exists()) {
                    throw new \RuntimeException("Employee number '{$empNo}' already exists.");
                }

                DB::transaction(function () use ($data, $email, $empNo) {
                    $user = User::create([
                        'name'                 => trim((string) $data['name']),
                        'email'                => $email,
                        'password'             => Hash::make(Str::random(16)),
                        'must_change_password' => true,
                    ]);
                    if (method_exists($user, 'assignRole')) $user->assignRole('teacher');

                    Teacher::create([
                        'user_id'        => $user->id,
                        'employee_no'    => $empNo,
                        'sex'            => filled($data['sex']            ?? null) ? strtolower(trim($data['sex'])) : null,
                        'date_of_birth'  => filled($data['date_of_birth']  ?? null) ? $data['date_of_birth']          : null,
                        'contact_number' => filled($data['contact_number'] ?? null) ? trim($data['contact_number'])   : null,
                        'date_hired'     => filled($data['date_hired']     ?? null) ? $data['date_hired']             : null,
                        'department'     => filled($data['department']     ?? null) ? trim($data['department'])       : null,
                        'specialization' => filled($data['specialization'] ?? null) ? trim($data['specialization'])   : null,
                        'is_active'      => strtolower(trim((string) ($data['is_active'] ?? 'yes'))) !== 'no',
                    ]);
                });

                $created++;
            } catch (\Throwable $e) {
                $errors[] = "Row {$rowNum}: " . $e->getMessage();
            }
        }

        fclose($handle);

        return response()->json([
            'message' => "Imported {$created} faculty member(s)."
                . (count($errors) ? ' ' . count($errors) . ' row(s) skipped.' : ''),
            'created' => $created,
            'errors'  => $errors,
        ]);
    }
}