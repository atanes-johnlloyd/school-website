<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ScheduleException;
use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\ClassSchedule;
use App\Models\Enrollment;
use App\Models\Room;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Strand;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Term;
use App\Models\ClassStudent;
use App\Services\ScheduleGenerator;
use App\Support\AuditContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SectionController extends Controller
{
    public function __construct(protected ScheduleGenerator $generator) {}

    /* ═══════════════════════════════════════════════════════════════
     | INDEX
     |═══════════════════════════════════════════════════════════════ */

    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Sections/Index', [
            'strands' => Strand::select('id', 'code', 'name')->orderBy('code')->get(),

            'teachers' => Teacher::with(['user:id,name', 'sections:id,adviser_id,name'])
                ->where('is_active', true)
                ->get()
                ->map(fn ($t) => [
                    'id'                    => $t->id,
                    'name'                  => $t->user?->name,
                    'advising_section_id'   => $t->sections->first()?->id,
                    'advising_section_name' => $t->sections->first()?->name,
                ])
                ->filter(fn ($t) => $t['name'])
                ->values(),

            'schoolYears' => SchoolYear::select('id', 'label', 'is_active')
                ->orderByDesc('start_date')->get(),

            'homeRooms' => Room::where('type', 'regular')
                ->where('is_active', true)
                ->orderBy('code')
                ->get(['id', 'code', 'name']),

            'defaultMaxCapacity' => \App\Models\SystemSetting::maxClassSize(),
        ]);
    }

    /* ═══════════════════════════════════════════════════════════════
     | LIST
     |═══════════════════════════════════════════════════════════════ */

    public function list(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => ['nullable', 'integer', 'exists:school_years,id'],
            'strand_id'      => ['nullable', 'integer', 'exists:strands,id'],
            'grade_level'    => ['nullable', 'in:11,12'],
            'search'         => ['nullable', 'string', 'max:100'],
            'per_page'       => ['nullable', 'integer', 'min:10', 'max:100'],
            'sort_by'        => ['nullable', 'in:name,grade_level,strand,capacity,enrolled'],
            'sort_dir'       => ['nullable', 'in:asc,desc'],
        ]);

        $sortBy  = $validated['sort_by']  ?? 'name';
        $sortDir = $validated['sort_dir'] ?? 'asc';

        $schoolYearId = $validated['school_year_id']
            ?? SchoolYear::where('is_active', true)->value('id');

        $query = Section::query()
            ->with(['schoolYear:id,label', 'strand:id,code,name', 'adviser.user:id,name'])
            ->withCount(['enrollments as enrolled_count' => fn ($q) => $q->where('status', 'enrolled')]);

        if ($schoolYearId) $query->where('school_year_id', $schoolYearId);
        if (! empty($validated['strand_id']))   $query->where('strand_id', $validated['strand_id']);
        if (! empty($validated['grade_level'])) $query->where('grade_level', $validated['grade_level']);
        if (! empty($validated['search']))      $query->where('name', 'like', '%' . $validated['search'] . '%');

        switch ($sortBy) {
            case 'strand':
                $query->leftJoin('strands', 'strands.id', '=', 'sections.strand_id')
                    ->orderBy('strands.code', $sortDir)->select('sections.*');
                break;
            case 'capacity': $query->orderBy('max_capacity', $sortDir); break;
            case 'enrolled': $query->orderBy('enrolled_count', $sortDir); break;
            default:         $query->orderBy($sortBy, $sortDir);
        }

        $sections = $query->paginate($validated['per_page'] ?? 10);

        $sections->getCollection()->transform(fn (Section $s) => [
            'id'                 => $s->id,
            'name'               => $s->name,
            'grade_level'        => $s->grade_level,
            'strand_id'          => $s->strand_id,
            'strand'             => $s->strand?->name,
            'strand_code'        => $s->strand?->code,
            'school_year'        => $s->schoolYear?->label,
            'school_year_id'     => $s->school_year_id,
            'adviser'            => $s->adviser?->user?->name,
            'adviser_id'         => $s->adviser_id,
            'home_room_id'       => $s->home_room_id,
            'max_capacity'       => $s->max_capacity,
            'enrolled_count'     => $s->enrolled_count,
            'is_fully_scheduled' => $s->is_fully_scheduled,
        ]);

        $allSectionsQuery = Section::query();
        if ($schoolYearId) $allSectionsQuery->where('school_year_id', $schoolYearId);
        $allSections = $allSectionsQuery->get();
        $totalCapacity = $allSections->sum('max_capacity');
        $totalEnrolled = Enrollment::whereIn('section_id', $allSections->pluck('id'))
            ->where('status', 'enrolled')->count();

        return response()->json([
            'sections' => $sections,
            'filters'  => [
                'school_year_id' => $schoolYearId,
                'strand_id'      => $validated['strand_id']  ?? null,
                'grade_level'    => $validated['grade_level'] ?? null,
                'search'         => $validated['search']      ?? null,
            ],
            'sort'   => ['by' => $sortBy, 'dir' => $sortDir],
            'counts' => [
                'total_sections' => $allSections->count(),
                'total_capacity' => $totalCapacity,
                'total_enrolled' => $totalEnrolled,
                'occupancy_rate' => $totalCapacity > 0 ? round(($totalEnrolled / $totalCapacity) * 100, 1) : 0,
            ],
        ]);
    }

    /* ═══════════════════════════════════════════════════════════════
     | NEXT NAME
     |═══════════════════════════════════════════════════════════════ */

    public function nextName(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => ['required', 'integer', 'exists:school_years,id'],
            'strand_id'      => ['required', 'integer', 'exists:strands,id'],
            'grade_level'    => ['required', 'in:11,12'],
        ]);

        $strand = Strand::find($validated['strand_id']);
        if (! $strand) return response()->json(['name' => null]);

        $prefix = $strand->code . ' ' . $validated['grade_level'] . '-';

        $existingNames = Section::where('school_year_id', $validated['school_year_id'])
            ->where('grade_level', $validated['grade_level'])
            ->where('strand_id', $validated['strand_id'])
            ->pluck('name');

        $usedLetters = [];
        foreach ($existingNames as $name) {
            if (! str_starts_with($name, $prefix)) continue;
            $suffix = substr($name, strlen($prefix));
            if (preg_match('/^([A-Z])$/', $suffix, $m)) $usedLetters[] = $m[1];
        }

        for ($i = 0; $i < 26; $i++) {
            $letter = chr(65 + $i);
            if (! in_array($letter, $usedLetters, true)) {
                return response()->json(['name' => $prefix . $letter, 'prefix' => $prefix]);
            }
        }

        return response()->json(['name' => $prefix . (count($existingNames) + 1), 'prefix' => $prefix]);
    }

    /* ═══════════════════════════════════════════════════════════════
     | SHOW
     |═══════════════════════════════════════════════════════════════ */

    public function show(Request $request, Section $section)
    {
        $section->load([
            'schoolYear:id,label',
            'strand:id,code,name',
            'adviser.user:id,name',
            'homeRoom:id,code,name,building',
        ]);

        $terms = Term::where('school_year_id', $section->school_year_id)
            ->orderBy('start_date')
            ->get(['id', 'name', 'is_active']);

        $termGrids = [];
        foreach ($terms as $term) {
            $classes = ClassRoom::where('section_id', $section->id)
                ->where('term_id', $term->id)
                ->with([
                    'subject:id,code,name',
                    'teacher.user:id,name',
                    'schedules:id,class_id,room_id,day_of_week,time_start,time_end',
                    'schedules.room:id,code,name',
                ])
                ->get();

            $termGrids[$term->id] = [
                'term'    => ['id' => $term->id, 'name' => $term->name, 'is_active' => $term->is_active],
                'classes' => $classes->map(fn ($c) => [
                    'id'           => $c->id,
                    'subject'      => $c->subject?->name,
                    'subject_code' => $c->subject?->code,
                    'teacher_id'   => $c->teacher_id,
                    'teacher'      => $c->teacher?->user?->name,
                    'is_scheduled' => $c->schedules->isNotEmpty(),
                    'schedules'    => $c->schedules->map(fn ($s) => [
                        'id'          => $s->id,
                        'room_id'     => $s->room_id,
                        'room'        => $s->room?->name,
                        'day_of_week' => $s->day_of_week,
                        'time_start'  => $s->time_start,
                        'time_end'    => $s->time_end,
                    ]),
                ]),
            ];
        }

        $sectionData = [
            'id'                 => $section->id,
            'name'               => $section->name,
            'grade_level'        => $section->grade_level,
            'strand_id'          => $section->strand_id,
            'strand'             => $section->strand?->code,
            'strand_name'        => $section->strand?->name,
            'school_year'        => $section->schoolYear?->label,
            'school_year_id'     => $section->school_year_id,
            'adviser'            => $section->adviser?->user?->name,
            'adviser_id'         => $section->adviser_id,
            'home_room_id'       => $section->home_room_id,
            'home_room'          => $section->homeRoom?->name,
            'max_capacity'       => $section->max_capacity,
            'is_fully_scheduled' => $section->is_fully_scheduled,
        ];

        $enrolledCount = Enrollment::where('section_id', $section->id)
            ->where('status', 'enrolled')->count();

        if ($request->wantsJson()) {
            $students = Enrollment::where('section_id', $section->id)
                ->where('status', 'enrolled')
                ->with('student.user:id,name,email')
                ->orderBy('enrolled_at')
                ->get()
                ->map(fn ($e) => [
                    'id'          => $e->id,
                    'student_id'  => $e->student_id,
                    'lrn'         => $e->student?->lrn,
                    'name'        => $e->student?->user?->name,
                    'email'       => $e->student?->user?->email,
                    'enrolled_at' => $e->enrolled_at?->toIso8601String(),
                ]);

            $classesList = ClassRoom::where('section_id', $section->id)
                ->with(['subject:id,code,name', 'teacher.user:id,name'])
                ->get()
                ->map(fn ($c) => [
                    'id'           => $c->id,
                    'subject'      => $c->subject?->name,
                    'subject_code' => $c->subject?->code,
                    'teacher'      => $c->teacher?->user?->name,
                ]);

            return response()->json([
                'section'  => array_merge($sectionData, ['enrolled_count' => $enrolledCount]),
                'terms'    => $termGrids,
                'enrolled' => $enrolledCount,
                'students' => $students,
                'classes'  => $classesList,
            ]);
        }

        $template = config("schedule_templates.grade_{$section->grade_level}");

        return Inertia::render('Admin/Sections/Show', [
            'section'         => $sectionData,
            'terms'           => $termGrids,
            'enrolled'        => $enrolledCount,
            'slots'           => $template['slots'],
            'recessAfterSlot' => $template['recess_after_slot'],
            'dayOrder'        => $template['days'],
        ]);
    }

    /* ═══════════════════════════════════════════════════════════════
     | STORE
     |═══════════════════════════════════════════════════════════════ */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year_id' => ['required', 'exists:school_years,id'],
            'strand_id'      => ['required', 'exists:strands,id'],
            'grade_level'    => ['required', 'in:11,12'],
            'name'           => [
                'required', 'string', 'max:100',
                Rule::unique('sections')->where(fn ($q) =>
                    $q->where('school_year_id', $request->school_year_id)
                      ->where('grade_level', $request->grade_level)
                ),
            ],
            'adviser_id'   => ['nullable', 'exists:teachers,id'],
            'home_room_id' => ['nullable', 'exists:rooms,id'],
            'max_capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        if (! empty($validated['adviser_id'])) {
            $clash = Section::where('adviser_id', $validated['adviser_id'])->first();
            if ($clash) {
                return response()->json([
                    'message' => "This teacher is already the adviser of Section \"{$clash->name}\".",
                    'errors'  => ['adviser_id' => ["Already advising \"{$clash->name}\"."]],
                ], 422);
            }
        }

        if (empty($validated['home_room_id'])) {
            $validated['home_room_id'] = Room::where('type', 'regular')
                ->where('is_active', true)
                ->orderBy('id')
                ->value('id');
        }

        try {
            $section = DB::transaction(function () use ($validated) {
                $section = Section::create($validated);

                $terms = Term::where('school_year_id', $section->school_year_id)
                    ->orderBy('start_date')->get();

                foreach ($terms as $term) {
                    $this->generator->generateForSectionTerm($section, $term);
                }

                return $section;
            });

            AuditContext::wrap('create_section_with_schedule', fn () => null, ['section_id' => $section->id]);

        } catch (ScheduleException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors'  => ['schedule' => [$e->getMessage()]],
            ], 409);
        } catch (\Throwable $e) {
            Log::error('Section create failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed: ' . $e->getMessage()], 500);
        }

        return response()->json(['section' => $section->fresh()], 201);
    }

    /* ═══════════════════════════════════════════════════════════════
     | UPDATE
     |═══════════════════════════════════════════════════════════════ */

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'strand_id'    => ['nullable', 'exists:strands,id'],
            'name'         => [
                'sometimes', 'string', 'max:100',
                Rule::unique('sections')->where(fn ($q) =>
                    $q->where('school_year_id', $section->school_year_id)
                      ->where('grade_level', $section->grade_level)
                )->ignore($section->id),
            ],
            'adviser_id'   => ['nullable', 'exists:teachers,id'],
            'home_room_id' => ['nullable', 'exists:rooms,id'],
            'max_capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        if (! empty($validated['adviser_id'])) {
            $clash = Section::where('adviser_id', $validated['adviser_id'])
                ->where('id', '!=', $section->id)->first();
            if ($clash) {
                return response()->json([
                    'message' => "This teacher is already the adviser of Section \"{$clash->name}\".",
                    'errors'  => ['adviser_id' => ["Already advising \"{$clash->name}\"."]],
                ], 422);
            }
        }

        AuditContext::wrap('update_section', function () use ($section, $validated) {
            $section->update($validated);
        });

        return response()->json(['section' => $section->fresh()]);
    }

    /* ═══════════════════════════════════════════════════════════════
     | DESTROY
     |═══════════════════════════════════════════════════════════════ */

    public function destroy(Section $section)
    {
        if ($section->enrollments()->where('status', 'enrolled')->exists()) {
            return response()->json(['message' => 'Cannot delete: section has enrolled students.'], 422);
        }

        AuditContext::wrap('delete_section', function () use ($section) {
            DB::transaction(function () use ($section) {
                $this->wipeSectionClasses($section);
                $section->delete();
            });
        });

        return response()->json(['message' => 'Section deleted.']);
    }

    /* ═══════════════════════════════════════════════════════════════
     | REGENERATE
     |═══════════════════════════════════════════════════════════════ */

    public function regenerate(Section $section)
    {
        try {
            DB::transaction(function () use ($section) {
                $oldClassIds = ClassRoom::withTrashed()
                    ->where('section_id', $section->id)
                    ->pluck('id');

                $sectionSourceStudentIds = ClassStudent::whereIn('class_id', $oldClassIds)
                    ->where('source', 'section')
                    ->pluck('student_id')
                    ->unique()
                    ->values()
                    ->all();

                $this->wipeSectionClasses($section);

                $terms = Term::where('school_year_id', $section->school_year_id)
                    ->orderBy('start_date')->get();

                foreach ($terms as $term) {
                    $this->generator->generateForSectionTerm($section, $term);
                }

                $newClassIds = ClassRoom::where('section_id', $section->id)->pluck('id');
                foreach ($newClassIds as $classId) {
                    foreach ($sectionSourceStudentIds as $studentId) {
                        ClassStudent::updateOrCreate(
                            ['class_id' => $classId, 'student_id' => $studentId],
                            ['status' => 'active', 'source' => 'section', 'enrolled_at' => now()]
                        );
                    }
                }
            });

            AuditContext::wrap('regenerate_section_schedule', fn () => null, ['section_id' => $section->id]);

        } catch (ScheduleException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors'  => ['schedule' => [$e->getMessage()]],
            ], 409);
        } catch (\Throwable $e) {
            Log::error('Section regenerate failed', [
                'section_id' => $section->id,
                'error'      => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Regeneration failed: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Schedule regenerated.']);
    }

    /* ═══════════════════════════════════════════════════════════════
     | UNPLACE — remove one class's schedule rows
     |═══════════════════════════════════════════════════════════════ */

    public function unschedule(Section $section, ClassRoom $classroom)
    {
        abort_unless($classroom->section_id === $section->id, 404);

        AuditContext::wrap('unschedule_class', function () use ($classroom) {
            ClassSchedule::where('class_id', $classroom->id)->delete();
        }, ['classroom_id' => $classroom->id]);

        return response()->json(['message' => 'Class removed from schedule.']);
    }

    /* ═══════════════════════════════════════════════════════════════
     | AVAILABLE SLOTS / RESCHEDULE
     |═══════════════════════════════════════════════════════════════ */

    public function availableSlots(Section $section, ClassRoom $classroom)
    {
        abort_unless($classroom->section_id === $section->id, 404);
        return response()->json(['candidates' => $this->generator->availableSlotsFor($classroom)]);
    }

    public function reschedule(Request $request, Section $section, ClassRoom $classroom)
    {
        abort_unless($classroom->section_id === $section->id, 404);

        $validated = $request->validate([
            'slot_index' => ['required', 'integer', 'min:0', 'max:5'],
            'days'       => ['required', 'array', 'min:1'],
            'days.*'     => ['in:monday,tuesday,wednesday,thursday,friday'],
        ]);

        try {
            DB::transaction(function () use ($classroom, $validated) {
                $this->generator->applyPlacement($classroom, (int) $validated['slot_index'], $validated['days']);
            });

            AuditContext::wrap('reschedule_class', fn () => null, [
                'classroom_id' => $classroom->id,
                'slot_index'   => $validated['slot_index'],
                'days'         => $validated['days'],
            ]);
        } catch (ScheduleException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors'  => ['schedule' => [$e->getMessage()]],
            ], 409);
        }

        return response()->json(['message' => 'Schedule updated.']);
    }

        /* ═══════════════════════════════════════════════════════════════
     | EDIT OPTIONS — teachers + rooms with busy state
     |═══════════════════════════════════════════════════════════════ */

    public function editClassOptions(Section $section, ClassRoom $classroom)
    {
        abort_unless($classroom->section_id === $section->id, 404);

        $classroom->load(['subject', 'schedules', 'section']);

        $blocks = $classroom->schedules
            ->map(fn ($s) => ['day' => $s->day_of_week, 'start' => $s->time_start, 'end' => $s->time_end])
            ->unique(fn ($b) => "{$b['day']}|{$b['start']}|{$b['end']}")
            ->values()
            ->all();

        $termId = $classroom->term_id;

        $teachers = Teacher::with('user:id,name')
            ->where('is_active', true)
            ->get()
            ->map(function ($t) use ($classroom, $blocks, $termId) {
                $busy = empty($blocks) ? false : ClassSchedule::where('class_id', '!=', $classroom->id)
                    ->whereHas('classroom', fn ($q) => $q
                        ->where('teacher_id', $t->id)
                        ->where('term_id', $termId))
                    ->where(function ($q) use ($blocks) {
                        foreach ($blocks as $b) {
                            $q->orWhere(function ($qq) use ($b) {
                                $qq->where('day_of_week', $b['day'])
                                   ->where('time_start', '<', $b['end'])
                                   ->where('time_end', '>', $b['start']);
                            });
                        }
                    })
                    ->exists();

                return [
                    'id'         => $t->id,
                    'name'       => $t->user?->name,
                    'is_current' => (int) $classroom->teacher_id === (int) $t->id,
                    'is_busy'    => $busy,
                ];
            })
            ->filter(fn ($t) => $t['name'])
            ->values();

        $def      = $this->subjectDefForClassroom($classroom);
        $roomType = $def['room_type'] ?? 'regular';

        $rooms = Room::where('is_active', true)
            ->where('type', $roomType)
            ->orderBy('code')
            ->get()
            ->map(function ($r) use ($classroom, $blocks, $termId) {
                $busy = empty($blocks) ? false : ClassSchedule::where('room_id', $r->id)
                    ->where('class_id', '!=', $classroom->id)
                    ->whereHas('classroom', fn ($q) => $q->where('term_id', $termId))
                    ->where(function ($q) use ($blocks) {
                        foreach ($blocks as $b) {
                            $q->orWhere(function ($qq) use ($b) {
                                $qq->where('day_of_week', $b['day'])
                                   ->where('time_start', '<', $b['end'])
                                   ->where('time_end', '>', $b['start']);
                            });
                        }
                    })
                    ->exists();

                return [
                    'id'         => $r->id,
                    'code'       => $r->code,
                    'name'       => $r->name,
                    'is_current' => $classroom->schedules->contains('room_id', $r->id),
                    'is_busy'    => $busy,
                ];
            });

        return response()->json([
            'classroom' => [
                'id'           => $classroom->id,
                'subject'      => $classroom->subject?->name,
                'subject_code' => $classroom->subject?->code,
                'teacher_id'   => $classroom->teacher_id,
                'room_id'      => $classroom->schedules->first()?->room_id,
                'room_type'    => $roomType,
            ],
            'teachers' => $teachers,
            'rooms'    => $rooms,
        ]);
    }

    /* ═══════════════════════════════════════════════════════════════
     | UPDATE CLASS — change teacher or room without moving the slot
     |═══════════════════════════════════════════════════════════════ */

    public function updateClass(Request $request, Section $section, ClassRoom $classroom)
    {
        abort_unless($classroom->section_id === $section->id, 404);

        $validated = $request->validate([
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'room_id'    => ['nullable', 'integer', 'exists:rooms,id'],
        ]);

        // Prevent taking a class away from a teacher who advises that class's section.
        if (array_key_exists('teacher_id', $validated)) {
            $currentTeacherId = $classroom->teacher_id;
            $newTeacherId     = $validated['teacher_id'];

            if ($currentTeacherId !== null && (int) $currentTeacherId !== (int) $newTeacherId) {
                $classroom->load('section:id,adviser_id');
                if ((int) $classroom->section?->adviser_id === (int) $currentTeacherId) {
                    return response()->json([
                        'message' => 'Cannot reassign: the current teacher is the adviser of this class\'s section.',
                        'errors'  => ['teacher_id' => ['Current teacher is the section adviser.']],
                    ], 422);
                }
            }
        }

        $classroom->load(['schedules', 'subject', 'section']);

        $blocks = $classroom->schedules
            ->map(fn ($s) => ['day' => $s->day_of_week, 'start' => $s->time_start, 'end' => $s->time_end])
            ->unique(fn ($b) => "{$b['day']}|{$b['start']}|{$b['end']}")
            ->values()
            ->all();

        $termId = $classroom->term_id;

        if (! empty($validated['teacher_id']) && ! empty($blocks)) {
            $conflict = ClassSchedule::where('class_id', '!=', $classroom->id)
                ->whereHas('classroom', fn ($q) => $q
                    ->where('teacher_id', $validated['teacher_id'])
                    ->where('term_id', $termId))
                ->where(function ($q) use ($blocks) {
                    foreach ($blocks as $b) {
                        $q->orWhere(function ($qq) use ($b) {
                            $qq->where('day_of_week', $b['day'])
                               ->where('time_start', '<', $b['end'])
                               ->where('time_end', '>', $b['start']);
                        });
                    }
                })
                ->exists();

            if ($conflict) {
                return response()->json([
                    'message' => 'Selected teacher is already teaching at this time.',
                    'errors'  => ['teacher_id' => ['Teacher has a conflicting class.']],
                ], 422);
            }
        }

        if (! empty($validated['room_id']) && ! empty($blocks)) {
            $conflict = ClassSchedule::where('class_id', '!=', $classroom->id)
                ->where('room_id', $validated['room_id'])
                ->whereHas('classroom', fn ($q) => $q->where('term_id', $termId))
                ->where(function ($q) use ($blocks) {
                    foreach ($blocks as $b) {
                        $q->orWhere(function ($qq) use ($b) {
                            $qq->where('day_of_week', $b['day'])
                               ->where('time_start', '<', $b['end'])
                               ->where('time_end', '>', $b['start']);
                        });
                    }
                })
                ->exists();

            if ($conflict) {
                return response()->json([
                    'message' => 'Selected room is already booked at this time.',
                    'errors'  => ['room_id' => ['Room has a conflicting class.']],
                ], 422);
            }
        }

        AuditContext::wrap('update_class_details', function () use ($classroom, $validated) {
            DB::transaction(function () use ($classroom, $validated) {
                if (array_key_exists('teacher_id', $validated)) {
                    $classroom->update(['teacher_id' => $validated['teacher_id']]);
                }
                if (! empty($validated['room_id'])) {
                    ClassSchedule::where('class_id', $classroom->id)
                        ->update(['room_id' => $validated['room_id']]);
                }
            });
        }, ['classroom_id' => $classroom->id]);

        return response()->json(['message' => 'Class updated.']);
    }

    /* ═══════════════════════════════════════════════════════════════
     | HELPER — resolve a subject's curriculum definition
     |═══════════════════════════════════════════════════════════════ */

    protected function subjectDefForClassroom(ClassRoom $classroom): ?array
    {
        $subject = $classroom->subject ?? Subject::find($classroom->subject_id);
        if (! $subject) return null;

        $grade  = $classroom->section?->grade_level;
        $config = config('curriculum.subjects');

        foreach ($config as $key => $def) {
            if ((string) $def['grade'] !== (string) $grade) continue;
            $resolved = $def['subject_code'] ?? preg_replace('/-(11|12)$/', '', $key);
            if ($resolved === $subject->code) return $def;
        }
        return null;
    }

    /* ═══════════════════════════════════════════════════════════════
     | ENROLL / REMOVE STUDENTS
     |═══════════════════════════════════════════════════════════════ */

    public function eligibleStudents(Request $request, Section $section)
    {
        $validated = $request->validate(['search' => ['nullable', 'string', 'max:100']]);

        $query = Student::with('user:id,name,email')
            ->whereDoesntHave('enrollments', fn ($q) =>
                $q->where('school_year_id', $section->school_year_id)->where('status', 'enrolled'))
            ->where('status', 'active');

        if (! empty($validated['search'])) {
            $s = $validated['search'];
            $query->where(function ($q) use ($s) {
                $q->where('lrn', 'like', "%{$s}%")
                  ->orWhereHas('user', fn ($uq) => $uq
                      ->where('name', 'like', "%{$s}%")
                      ->orWhere('email', 'like', "%{$s}%"));
            });
        }

        $students = $query->orderBy('lrn')->limit(100)->get()->map(fn ($s) => [
            'id'    => $s->id,
            'lrn'   => $s->lrn,
            'name'  => $s->user?->name,
            'email' => $s->user?->email,
        ]);

        $currentCount = Enrollment::where('section_id', $section->id)
            ->where('status', 'enrolled')->count();

        return response()->json([
            'students' => $students,
            'capacity' => [
                'max'       => $section->max_capacity,
                'current'   => $currentCount,
                'remaining' => max(0, ($section->max_capacity ?? 0) - $currentCount),
            ],
        ]);
    }

    public function enrollStudent(Request $request, Section $section)
    {
        if (! $section->is_fully_scheduled) {
            return response()->json([
                'message' => 'Section schedule is incomplete. Place all classes before enrolling students.',
            ], 422);
        }

        $validated = $request->validate(['student_id' => ['required', 'exists:students,id']]);

        $student = Student::findOrFail($validated['student_id']);

        $currentCount = Enrollment::where('section_id', $section->id)->where('status', 'enrolled')->count();
        if ($section->max_capacity !== null && $currentCount >= $section->max_capacity) {
            return response()->json(['message' => 'Section is at maximum capacity.'], 422);
        }

        $existing = Enrollment::where('student_id', $student->id)
            ->where('school_year_id', $section->school_year_id)
            ->where('status', 'enrolled')->first();

        if ($existing) {
            return response()->json(['message' => 'Student already enrolled for this school year.'], 422);
        }

        AuditContext::wrap('enroll_in_section', function () use ($student, $section, $request) {
            DB::transaction(function () use ($student, $section, $request) {
                Enrollment::create([
                    'student_id'     => $student->id,
                    'school_year_id' => $section->school_year_id,
                    'section_id'     => $section->id,
                    'status'         => 'enrolled',
                    'enrolled_at'    => now(),
                    'enrolled_by'    => $request->user()?->id,
                ]);

                ClassRoom::where('section_id', $section->id)->each(function ($class) use ($student) {
                    ClassStudent::updateOrCreate(
                        ['class_id' => $class->id, 'student_id' => $student->id],
                        ['status' => 'active', 'source' => 'section', 'enrolled_at' => now()]
                    );
                });
            });
        }, ['student_id' => $student->id]);

        return response()->json(['message' => 'Student enrolled in section.'], 201);
    }

    public function removeStudent(Section $section, Student $student)
    {
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('section_id', $section->id)
            ->where('status', 'enrolled')->first();

        if (! $enrollment) {
            return response()->json(['message' => 'Student is not enrolled in this section.'], 422);
        }

        AuditContext::wrap('remove_from_section', function () use ($section, $student, $enrollment) {
            DB::transaction(function () use ($section, $student, $enrollment) {
                $enrollment->update(['status' => 'dropped']);

                ClassRoom::where('section_id', $section->id)->each(function ($class) use ($student) {
                    $class->students()->detach($student->id);
                });
            });
        }, ['student_id' => $student->id]);

        return response()->json(['message' => 'Student removed from section.']);
    }

    /* ═══════════════════════════════════════════════════════════════
     | HELPERS
     |═══════════════════════════════════════════════════════════════ */

    protected function wipeSectionClasses(Section $section): void
    {
        $classIds = ClassRoom::withTrashed()
            ->where('section_id', $section->id)
            ->pluck('id');

        if ($classIds->isEmpty()) return;

        ClassSchedule::whereIn('class_id', $classIds)->delete();

        ClassRoom::withTrashed()->whereIn('id', $classIds)->forceDelete();
    }

    protected function electiveSubjectsForCluster(string $grade, ?string $clusterCode): array
    {
        $config = config('curriculum.subjects');
        $out    = [];

        foreach ($config as $key => $def) {
            if ((string) $def['grade'] !== (string) $grade) continue;
            if ($def['clusters'] === '*') continue;
            if (! in_array($clusterCode, $def['clusters'], true)) continue;

            $subjectCode = $def['subject_code'] ?? preg_replace('/-(11|12)$/', '', $key);
            $subject = Subject::where('code', $subjectCode)->first();
            if (! $subject) continue;

            $out[] = [
                'id'        => $subject->id,
                'code'      => $subjectCode,
                'room_type' => $def['room_type'],
            ];
        }

        return $out;
    }
}