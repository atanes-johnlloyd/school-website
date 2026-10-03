<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
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

class StudentController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Students/Index', [
            'sections' => Section::query()
                ->orderBy('grade_level')
                ->orderBy('name')
                ->get(['id', 'name', 'grade_level', 'school_year_id']),
        ]);
    }

    public function list(Request $request)
    {
        $validated = $request->validate([
            'status'      => ['nullable', 'in:active,graduated,dropped_out,transferred_out'],
            'grade_level' => ['nullable', 'in:7,8,9,10,11,12'],
            'section_id'  => ['nullable', 'integer', 'exists:sections,id'],
            'search'      => ['nullable', 'string', 'max:100'],
            'per_page'    => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'     => ['nullable', 'in:lrn,name,status,created_at'],
            'sort_dir'    => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'created_at';
        $sortDir = $validated['sort_dir'] ?? 'desc';

        $query = Student::query()->with([
            'user:id,name,email,avatar_path,status',
            'enrollments' => fn ($q) => $q
                ->where('status', 'enrolled')
                ->with(['section:id,name,grade_level', 'schoolYear:id,label'])
                ->latest('enrolled_at'),
        ]);

        if (! empty($validated['status'])) $query->where('status', $validated['status']);

        if (! empty($validated['grade_level'])) {
            $query->whereHas('enrollments.section', fn ($q) =>
                $q->where('grade_level', $validated['grade_level'])
            );
        }
        if (! empty($validated['section_id'])) {
            $query->whereHas('enrollments', fn ($q) =>
                $q->where('section_id', $validated['section_id'])->where('status', 'enrolled')
            );
        }
        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('lrn', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('name', 'like', "%{$s}%")
                         ->orWhere('email', 'like', "%{$s}%");
                  })
                  ->orWhereHas('enrollments.section', fn ($sq) =>
                      $sq->where('name', 'like', "%{$s}%")
                  );
            });
        }

        switch ($sortBy) {
            case 'name':
                $query->join('users', 'users.id', '=', 'students.user_id')
                      ->orderBy('users.name', $sortDir)
                      ->select('students.*');
                break;
            default:
                $query->orderBy($sortBy, $sortDir);
        }

        $students = $query->paginate($validated['per_page'] ?? 10);

        $students->getCollection()->transform(function (Student $s) {
            $current = $s->enrollments->first();
            return [
                'id'               => $s->id,
                'lrn'              => $s->lrn,
                'name'             => $s->user?->name,
                'email'            => $s->user?->email,
                'grade_level'      => $current?->section?->grade_level,
                'section'          => $current?->section?->name,
                'section_id'       => $current?->section?->id,
                'school_year'      => $current?->schoolYear?->label,
                'guardian_name'    => $s->guardian_name,
                'guardian_contact' => $s->guardian_contact,
                'status'           => $s->status,
                'created_at'       => $s->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'students' => $students,
            'filters'  => [
                'status'      => $validated['status']      ?? null,
                'grade_level' => $validated['grade_level'] ?? null,
                'section_id'  => $validated['section_id']  ?? null,
                'search'      => $validated['search']      ?? null,
            ],
            'sort'   => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => [
                'total'           => Student::count(),
                'active'          => Student::where('status', 'active')->count(),
                'graduated'       => Student::where('status', 'graduated')->count(),
                'dropped_out'     => Student::where('status', 'dropped_out')->count(),
                'transferred_out' => Student::where('status', 'transferred_out')->count(),
            ],
        ]);
    }

    public function show(Student $student)
    {
        $student->load([
            'user:id,name,email,avatar_path,status,created_at',
            'enrollments.section.strand',
            'enrollments.schoolYear',
        ]);

        $current = $student->enrollments
            ->where('status', 'enrolled')
            ->sortByDesc('enrolled_at')
            ->first();

        $applicant = Applicant::where('converted_student_id', $student->id)
            ->with(['strand:id,code,name', 'schoolYear:id,label', 'contacts', 'documents'])
            ->first();

        return response()->json([
            'student' => [
                'id'               => $student->id,
                'lrn'              => $student->lrn,
                'sex'              => $student->sex,
                'date_of_birth'    => $student->date_of_birth?->toDateString(),
                'contact_number'   => $student->contact_number,
                'house_street'     => $student->house_street,
                'barangay'         => $student->barangay,
                'municipality'     => $student->municipality,
                'province'         => $student->province,
                'zip_code'         => $student->zip_code,
                'status'           => $student->status,
                'grade_level'      => $current?->section?->grade_level,
                'section'          => $current?->section?->name,
                'section_id'       => $current?->section?->id,
                'school_year'      => $current?->schoolYear?->label,
                'guardian_name'    => $student->guardian_name,
                'guardian_contact' => $student->guardian_contact,
                'created_at'       => $student->created_at?->toIso8601String(),
                'user' => [
                    'id'     => $student->user->id,
                    'name'   => $student->user->name,
                    'email'  => $student->user->email,
                    'avatar' => $student->user->avatar_path,
                    'status' => $student->user->status,
                ],
                'enrollments' => $student->enrollments->map(fn ($e) => [
                    'id'          => $e->id,
                    'status'      => $e->status,
                    'section'     => $e->section?->name,
                    'strand'      => $e->section?->strand?->name,
                    'grade_level' => $e->section?->grade_level,
                    'school_year' => $e->schoolYear?->label,
                    'enrolled_at' => $e->enrolled_at?->toIso8601String(),
                ]),
            ],
            'applicant' => $applicant ? [
                'id'                  => $applicant->id,
                'reference_number'    => $applicant->reference_number,
                'applicant_type'      => $applicant->applicant_type,
                'desired_grade_level' => $applicant->desired_grade_level,
                'strand'              => $applicant->strand?->name,
                'school_year'         => $applicant->schoolYear?->label,
                'status'              => $applicant->status,
                'submitted_at'        => $applicant->submitted_at?->toIso8601String(),
                'reviewed_at'         => $applicant->reviewed_at?->toIso8601String(),
                'contacts'            => $applicant->contacts->map(fn ($c) => [
                    'id' => $c->id, 'role' => $c->role, 'full_name' => $c->full_name,
                    'relationship' => $c->relationship, 'occupation' => $c->occupation,
                    'contact_number' => $c->contact_number, 'email' => $c->email,
                ]),
                'documents' => $applicant->documents->map(fn ($d) => [
                    'id' => $d->id, 'document_type' => $d->document_type,
                    'file_name' => $d->file_name, 'file_size' => $d->file_size,
                    'mime_type' => $d->mime_type, 'status' => $d->status,
                    'download_url' => $d->download_url,
                ]),
            ] : null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'         => ['required', Password::defaults()],
            'lrn'              => ['required', 'string', 'max:50', 'unique:students,lrn'],
            'section_id'       => ['nullable', 'integer', 'exists:sections,id'],
            'sex'              => ['nullable', 'in:male,female'],
            'date_of_birth'    => ['nullable', 'date'],
            'contact_number'   => ['nullable', 'string', 'max:20'],
            'guardian_name'    => ['nullable', 'string', 'max:255'],
            'guardian_contact' => ['nullable', 'string', 'max:20'],
        ]);

        AuditContext::wrap('create_student', function () use ($validated) {
            DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name'                 => $validated['name'],
                    'email'                => $validated['email'],
                    'password'             => Hash::make($validated['password']),
                    'must_change_password' => true,
                ]);
                if (method_exists($user, 'assignRole')) $user->assignRole('student');

                $student = Student::create([
                    'user_id'          => $user->id,
                    'lrn'              => $validated['lrn'],
                    'sex'              => $validated['sex']              ?? null,
                    'date_of_birth'    => $validated['date_of_birth']    ?? null,
                    'contact_number'   => $validated['contact_number']   ?? null,
                    'guardian_name'    => $validated['guardian_name']    ?? null,
                    'guardian_contact' => $validated['guardian_contact'] ?? null,
                    'status'           => 'active',
                ]);

                if (! empty($validated['section_id'])) {
                    $section = Section::findOrFail($validated['section_id']);
                    Enrollment::create([
                        'student_id'     => $student->id,
                        'school_year_id' => $section->school_year_id,
                        'section_id'     => $section->id,
                        'status'         => 'enrolled',
                        'enrolled_at'    => now(),
                    ]);
                }
            });
        });

        return redirect()->back()->with('success', 'Student registered successfully.');
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'lrn'              => ['required', 'string', 'max:50', Rule::unique('students', 'lrn')->ignore($student->id)],
            'section_id'       => ['nullable', 'integer', 'exists:sections,id'],
            'sex'              => ['nullable', 'in:male,female'],
            'date_of_birth'    => ['nullable', 'date'],
            'contact_number'   => ['nullable', 'string', 'max:20'],
            'guardian_name'    => ['nullable', 'string', 'max:255'],
            'guardian_contact' => ['nullable', 'string', 'max:20'],
            'status'           => ['nullable', 'in:active,graduated,dropped_out,transferred_out'],
        ]);

        AuditContext::wrap('update_student', function () use ($validated, $student) {
            DB::transaction(function () use ($validated, $student) {
                $student->user->update([
                    'name'  => $validated['name'],
                    'email' => $validated['email'],
                ]);

                $student->update([
                    'lrn'              => $validated['lrn'],
                    'sex'              => $validated['sex']              ?? null,
                    'date_of_birth'    => $validated['date_of_birth']    ?? null,
                    'contact_number'   => $validated['contact_number']   ?? null,
                    'guardian_name'    => $validated['guardian_name']    ?? null,
                    'guardian_contact' => $validated['guardian_contact'] ?? null,
                    'status'           => $validated['status']           ?? $student->status,
                ]);

                if (! empty($validated['section_id'])) {
                    $newSection = Section::findOrFail($validated['section_id']);
                    $current    = $student->enrollments()
                        ->where('status', 'enrolled')
                        ->latest('enrolled_at')
                        ->first();

                    if ($current) {
                        $current->update([
                            'section_id'     => $newSection->id,
                            'school_year_id' => $newSection->school_year_id,
                        ]);
                    } else {
                        Enrollment::create([
                            'student_id'     => $student->id,
                            'school_year_id' => $newSection->school_year_id,
                            'section_id'     => $newSection->id,
                            'status'         => 'enrolled',
                            'enrolled_at'    => now(),
                        ]);
                    }
                }
            });
        });

        return redirect()->back()->with('success', 'Student profile updated successfully.');
    }

    public function destroy(Student $student)
    {
        AuditContext::wrap('deactivate_student', function () use ($student) {
            $student->update(['status' => 'dropped_out']);
            $student->delete();
        });

        return redirect()->back()->with('success', 'Student deactivated successfully.');
    }

    public function resetPassword(Student $student)
    {
        $temp = Str::random(16);

        AuditContext::wrap('reset_student_password', function () use ($student, $temp) {
            $student->user->update([
                'password'             => Hash::make($temp),
                'must_change_password' => true,
            ]);
        });

        return redirect()->back()->with('success', "Password reset to temporary key: {$temp}");
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'status'      => ['nullable', 'in:active,graduated,dropped_out,transferred_out'],
            'grade_level' => ['nullable', 'in:7,8,9,10,11,12'],
            'section_id'  => ['nullable', 'integer', 'exists:sections,id'],
            'search'      => ['nullable', 'string', 'max:100'],
        ]);

        $query = Student::query()->with([
            'user:id,name,email',
            'enrollments' => fn ($q) => $q
                ->where('status', 'enrolled')
                ->with('section:id,name,grade_level')
                ->latest('enrolled_at'),
        ]);

        if (! empty($validated['status']))      $query->where('status', $validated['status']);
        if (! empty($validated['grade_level'])) {
            $query->whereHas('enrollments.section', fn ($q) =>
                $q->where('grade_level', $validated['grade_level'])
            );
        }
        if (! empty($validated['section_id'])) {
            $query->whereHas('enrollments', fn ($q) =>
                $q->where('section_id', $validated['section_id'])->where('status', 'enrolled')
            );
        }
        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('lrn', 'like', "%{$s}%")
                  ->orWhereHas('user', fn ($uq) => $uq
                      ->where('name', 'like', "%{$s}%")
                      ->orWhere('email', 'like', "%{$s}%"))
                  ->orWhereHas('enrollments.section', fn ($sq) =>
                      $sq->where('name', 'like', "%{$s}%")
                  );
            });
        }

        $query->orderBy('created_at', 'desc');

        $filename = 'students-' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'name', 'email', 'lrn', 'sex', 'date_of_birth', 'contact_number',
                'grade_level', 'section',
                'house_street', 'barangay', 'municipality', 'province', 'zip_code',
                'guardian_name', 'guardian_contact', 'status',
            ]);

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $s) {
                    $current = $s->enrollments->first();
                    fputcsv($out, [
                        $s->user?->name, $s->user?->email, $s->lrn, $s->sex,
                        $s->date_of_birth?->toDateString(),
                        $s->contact_number,
                        $current?->section?->grade_level, $current?->section?->name,
                        $s->house_street, $s->barangay, $s->municipality, $s->province, $s->zip_code,
                        $s->guardian_name, $s->guardian_contact, $s->status,
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
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

        $required = ['name', 'email', 'lrn'];
        $missing  = array_diff($required, $header);
        if (! empty($missing)) {
            fclose($handle);
            return response()->json([
                'message' => 'Missing required columns: ' . implode(', ', $missing),
            ], 422);
        }

        $sections = Section::get()->keyBy(fn ($s) => $s->grade_level . '|' . strtolower($s->name));

        $created = 0;
        $errors  = [];
        $rowNum  = 1;

        AuditContext::wrap('bulk_import_students', function () use (&$created, &$errors, &$rowNum, $handle, $header, $required, $sections) {
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
                    $lrn   = trim((string) $data['lrn']);

                    if (User::where('email', $email)->exists()) {
                        throw new \RuntimeException("Email '{$email}' already exists.");
                    }
                    if (Student::where('lrn', $lrn)->exists()) {
                        throw new \RuntimeException("LRN '{$lrn}' already exists.");
                    }

                    DB::transaction(function () use ($data, $email, $lrn, $sections) {
                        $user = User::create([
                            'name'                 => trim((string) $data['name']),
                            'email'                => $email,
                            'password'             => Hash::make(Str::random(16)),
                            'must_change_password' => true,
                        ]);
                        if (method_exists($user, 'assignRole')) $user->assignRole('student');

                        $student = Student::create([
                            'user_id'          => $user->id,
                            'lrn'              => $lrn,
                            'sex'              => filled($data['sex']              ?? null) ? strtolower(trim($data['sex'])) : null,
                            'date_of_birth'    => filled($data['date_of_birth']    ?? null) ? $data['date_of_birth']          : null,
                            'contact_number'   => filled($data['contact_number']   ?? null) ? trim($data['contact_number'])   : null,
                            'house_street'     => filled($data['house_street']     ?? null) ? trim($data['house_street'])     : null,
                            'barangay'         => filled($data['barangay']         ?? null) ? trim($data['barangay'])         : null,
                            'municipality'     => filled($data['municipality']     ?? null) ? trim($data['municipality'])     : null,
                            'province'         => filled($data['province']         ?? null) ? trim($data['province'])         : null,
                            'zip_code'         => filled($data['zip_code']         ?? null) ? trim($data['zip_code'])         : null,
                            'guardian_name'    => filled($data['guardian_name']    ?? null) ? trim($data['guardian_name'])    : null,
                            'guardian_contact' => filled($data['guardian_contact'] ?? null) ? trim($data['guardian_contact']) : null,
                            'status'           => 'active',
                        ]);

                        $g = trim((string) ($data['grade_level'] ?? ''));
                        $n = strtolower(trim((string) ($data['section'] ?? '')));
                        if ($g !== '' && $n !== '') {
                            $section = $sections["{$g}|{$n}"] ?? null;
                            if ($section) {
                                Enrollment::create([
                                    'student_id'     => $student->id,
                                    'school_year_id' => $section->school_year_id,
                                    'section_id'     => $section->id,
                                    'status'         => 'enrolled',
                                    'enrolled_at'    => now(),
                                ]);
                            }
                        }
                    });

                    $created++;
                } catch (\Throwable $e) {
                    $errors[] = "Row {$rowNum}: " . $e->getMessage();
                }
            }
        });

        fclose($handle);

        return response()->json([
            'message' => "Imported {$created} student(s)."
                . (count($errors) ? ' ' . count($errors) . ' row(s) skipped.' : ''),
            'created' => $created,
            'errors'  => $errors,
        ]);
    }
}