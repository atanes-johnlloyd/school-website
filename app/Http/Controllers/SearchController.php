<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Applicant;
use App\Models\Assignment;
use App\Models\ClassRoom;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SearchController extends Controller
{
    /* ═══════════════════════════════════════════════════════ */
    /* FULL RESULTS — grouped page                             */
    /* ═══════════════════════════════════════════════════════ */
    public function index(Request $request)
    {
        $q    = trim((string) $request->input('q', ''));
        $user = $request->user();

        $groups = [];

        if (strlen($q) >= 2) {
            if ($user->hasRole('admin')) {
                $groups = $this->adminGroups($q);
            } elseif ($user->hasRole('teacher')) {
                $groups = $this->teacherGroups($q, $user->teacher);
            } elseif ($user->hasRole('student')) {
                $groups = $this->studentGroups($q, $user->student);
            }
        }

        $payload = [
            'query'  => $q,
            'groups' => $groups,
        ];

        return $request->wantsJson()
            ? response()->json($payload)
            : Inertia::render('Search/Index', $payload);
    }

    /* ═══════════════════════════════════════════════════════ */
    /* SUGGEST — flat typeahead list                           */
    /* ═══════════════════════════════════════════════════════ */
    public function suggest(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['items' => []]);
        }

        $user  = $request->user();
        $items = [];

        if ($user->hasRole('admin')) {
            $items = $this->adminSuggest($q);
        } elseif ($user->hasRole('teacher')) {
            $items = $this->teacherSuggest($q, $user->teacher);
        } elseif ($user->hasRole('student')) {
            $items = $this->studentSuggest($q, $user->student);
        }

        return response()->json(['items' => array_slice($items, 0, 8)]);
    }

    /* ═══════════════════════════════════════════════════════ */
    /* TEACHER                                                 */
    /* ═══════════════════════════════════════════════════════ */
    protected function teacherSuggest(string $q, ?Teacher $teacher): array
    {
        if (! $teacher) return [];

        $like      = "%{$q}%";
        $classIds  = ClassRoom::where('teacher_id', $teacher->id)->pluck('id')->all();
        $studentIds = DB::table('class_students')
            ->whereIn('class_id', $classIds)
            ->where('status', 'active')
            ->pluck('student_id')
            ->all();

        $out = [];

        /* Students */
        Student::with('user:id,name')
            ->whereIn('id', $studentIds)
            ->where(function ($w) use ($like) {
                $w->where('lrn', 'like', $like)
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like));
            })
            ->limit(5)->get()
            ->each(function ($s) use (&$out, $classIds) {
                $classId = DB::table('class_students')
                    ->where('student_id', $s->id)
                    ->whereIn('class_id', $classIds)
                    ->where('status', 'active')
                    ->value('class_id');

                $classroom = $classId
                    ? ClassRoom::with(['subject:id,name', 'section:id,name'])->find($classId)
                    : null;

                $out[] = [
                    'type'  => 'Student',
                    'title' => $s->user?->name,
                    'meta'  => $classroom
                        ? "LRN {$s->lrn} • {$classroom->subject?->name} ({$classroom->section?->name})"
                        : "LRN {$s->lrn}",
                    'url'   => $classroom
                        ? route('teacher.classes.show', [
                            'classroom' => $classroom->id,
                            'tab'       => 'students',
                            'highlight' => $s->id,
                        ])
                        : route('teacher.classes.index'),
                ];
            });

        /* Classes */
        ClassRoom::with(['subject:id,name,code', 'section:id,name'])
            ->whereIn('id', $classIds)
            ->where(function ($w) use ($like) {
                $w->whereHas('subject', function ($s) use ($like) {
                    $s->where('name', 'like', $like)->orWhere('code', 'like', $like);
                })->orWhereHas('section', fn ($s) => $s->where('name', 'like', $like));
            })
            ->limit(3)->get()
            ->each(function ($c) use (&$out) {
                $out[] = [
                    'type'  => 'Class',
                    'title' => $c->subject?->name,
                    'meta'  => $c->section?->name,
                    'url'   => route('teacher.classes.show', $c->id),
                ];
            });

        /* Assignments */
        Assignment::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('title', 'like', $like)
            ->limit(3)->get()
            ->each(function ($a) use (&$out) {
                $out[] = [
                    'type'  => 'Assignment',
                    'title' => $a->title,
                    'meta'  => $a->classroom?->subject?->name,
                    'url'   => route('teacher.assignments.show', $a->id),
                ];
            });

        /* Quizzes */
        Quiz::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('title', 'like', $like)
            ->limit(3)->get()
            ->each(function ($q) use (&$out) {
                $out[] = [
                    'type'  => 'Quiz',
                    'title' => $q->title,
                    'meta'  => $q->classroom?->subject?->name,
                    'url'   => route('teacher.quizzes.show', $q->id),
                ];
            });

        /* Lessons */
        Lesson::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('title', 'like', $like)
            ->limit(3)->get()
            ->each(function ($l) use (&$out) {
                $out[] = [
                    'type'  => 'Lesson',
                    'title' => $l->title,
                    'meta'  => $l->classroom?->subject?->name,
                    'url'   => route('teacher.lessons.show', $l->id),
                ];
            });

        return $out;
    }

    protected function teacherGroups(string $q, ?Teacher $teacher): array
    {
        if (! $teacher) return [];

        $like       = "%{$q}%";
        $classIds   = ClassRoom::where('teacher_id', $teacher->id)->pluck('id')->all();
        $studentIds = DB::table('class_students')
            ->whereIn('class_id', $classIds)
            ->where('status', 'active')
            ->pluck('student_id')
            ->all();

        $students = Student::with('user:id,name')
            ->whereIn('id', $studentIds)
            ->where(function ($w) use ($like) {
                $w->where('lrn', 'like', $like)
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like));
            })
            ->limit(12)->get()
            ->map(function ($s) use ($classIds) {
                $classId = DB::table('class_students')
                    ->where('student_id', $s->id)
                    ->whereIn('class_id', $classIds)
                    ->where('status', 'active')
                    ->value('class_id');

                $classroom = $classId ? ClassRoom::with(['subject:id,name', 'section:id,name'])->find($classId) : null;

                return [
                    'id'    => $s->id,
                    'title' => $s->user?->name,
                    'meta'  => $classroom
                        ? "LRN {$s->lrn} • {$classroom->subject?->name} ({$classroom->section?->name})"
                        : "LRN {$s->lrn}",
                    'url'   => $classroom
                        ? route('teacher.classes.show', ['classroom' => $classroom->id, 'tab' => 'students', 'highlight' => $s->id])
                        : route('teacher.classes.index'),
                ];
            });

        $classes = ClassRoom::with(['subject:id,name,code', 'section:id,name'])
            ->whereIn('id', $classIds)
            ->where(function ($w) use ($like) {
                $w->whereHas('subject', function ($s) use ($like) {
                    $s->where('name', 'like', $like)->orWhere('code', 'like', $like);
                })->orWhereHas('section', fn ($s) => $s->where('name', 'like', $like));
            })
            ->limit(12)->get()
            ->map(fn ($c) => [
                'id'    => $c->id,
                'title' => $c->subject?->name,
                'meta'  => $c->section?->name,
                'url'   => route('teacher.classes.show', $c->id),
            ]);

        $assignments = Assignment::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('title', 'like', $like)
            ->limit(12)->get()
            ->map(fn ($a) => [
                'id'    => $a->id,
                'title' => $a->title,
                'meta'  => $a->classroom?->subject?->name,
                'url'   => route('teacher.assignments.show', $a->id),
            ]);

        $quizzes = Quiz::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('title', 'like', $like)
            ->limit(12)->get()
            ->map(fn ($q) => [
                'id'    => $q->id,
                'title' => $q->title,
                'meta'  => $q->classroom?->subject?->name,
                'url'   => route('teacher.quizzes.show', $q->id),
            ]);

        $lessons = Lesson::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('title', 'like', $like)
            ->limit(12)->get()
            ->map(fn ($l) => [
                'id'    => $l->id,
                'title' => $l->title,
                'meta'  => $l->classroom?->subject?->name,
                'url'   => route('teacher.lessons.show', $l->id),
            ]);

        return $this->wrap([
            'Students'    => $students,
            'Classes'     => $classes,
            'Assignments' => $assignments,
            'Quizzes'     => $quizzes,
            'Lessons'     => $lessons,
        ]);
    }

    /* ═══════════════════════════════════════════════════════ */
    /* STUDENT                                                 */
    /* ═══════════════════════════════════════════════════════ */
    protected function studentSuggest(string $q, ?Student $student): array
    {
        if (! $student) return [];

        $like     = "%{$q}%";
        $classIds = DB::table('class_students')
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('class_id')
            ->all();

        $out = [];

        ClassRoom::with(['subject:id,name,code', 'section:id,name'])
            ->whereIn('id', $classIds)
            ->where(function ($w) use ($like) {
                $w->whereHas('subject', function ($s) use ($like) {
                    $s->where('name', 'like', $like)->orWhere('code', 'like', $like);
                })->orWhereHas('section', fn ($s) => $s->where('name', 'like', $like));
            })
            ->limit(4)->get()
            ->each(function ($c) use (&$out) {
                $out[] = [
                    'type'  => 'Class',
                    'title' => $c->subject?->name,
                    'meta'  => $c->section?->name,
                    'url'   => route('student.classes.show', $c->id),
                ];
            });

        Assignment::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->where('title', 'like', $like)
            ->limit(4)->get()
            ->each(function ($a) use (&$out) {
                $out[] = [
                    'type'  => 'Assignment',
                    'title' => $a->title,
                    'meta'  => $a->classroom?->subject?->name,
                    'url'   => route('student.assignments.show', $a->id),
                ];
            });

        Quiz::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->where('title', 'like', $like)
            ->limit(3)->get()
            ->each(function ($q) use (&$out) {
                $out[] = [
                    'type'  => 'Quiz',
                    'title' => $q->title,
                    'meta'  => $q->classroom?->subject?->name,
                    'url'   => route('student.quizzes.show', $q->id),
                ];
            });

        Lesson::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->where('title', 'like', $like)
            ->limit(3)->get()
            ->each(function ($l) use (&$out) {
                $out[] = [
                    'type'  => 'Lesson',
                    'title' => $l->title,
                    'meta'  => $l->classroom?->subject?->name,
                    'url'   => route('student.lessons.show', $l->id),
                ];
            });

        return $out;
    }

    protected function studentGroups(string $q, ?Student $student): array
    {
        if (! $student) return [];

        $like     = "%{$q}%";
        $classIds = DB::table('class_students')
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->pluck('class_id')
            ->all();

        $classes = ClassRoom::with(['subject:id,name,code', 'section:id,name'])
            ->whereIn('id', $classIds)
            ->where(function ($w) use ($like) {
                $w->whereHas('subject', function ($s) use ($like) {
                    $s->where('name', 'like', $like)->orWhere('code', 'like', $like);
                })->orWhereHas('section', fn ($s) => $s->where('name', 'like', $like));
            })
            ->limit(12)->get()
            ->map(fn ($c) => [
                'id'    => $c->id,
                'title' => $c->subject?->name,
                'meta'  => $c->section?->name,
                'url'   => route('student.classes.show', $c->id),
            ]);

        $assignments = Assignment::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->where('title', 'like', $like)
            ->limit(12)->get()
            ->map(fn ($a) => [
                'id'    => $a->id,
                'title' => $a->title,
                'meta'  => $a->classroom?->subject?->name,
                'url'   => route('student.assignments.show', $a->id),
            ]);

        $quizzes = Quiz::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->where('title', 'like', $like)
            ->limit(12)->get()
            ->map(fn ($q) => [
                'id'    => $q->id,
                'title' => $q->title,
                'meta'  => $q->classroom?->subject?->name,
                'url'   => route('student.quizzes.show', $q->id),
            ]);

        $lessons = Lesson::with('classroom.subject:id,name')
            ->whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->where('title', 'like', $like)
            ->limit(12)->get()
            ->map(fn ($l) => [
                'id'    => $l->id,
                'title' => $l->title,
                'meta'  => $l->classroom?->subject?->name,
                'url'   => route('student.lessons.show', $l->id),
            ]);

        $announcements = Announcement::whereIn('class_id', $classIds)
            ->where('is_published', true)
            ->where('title', 'like', $like)
            ->limit(12)->get()
            ->map(fn ($a) => [
                'id'    => $a->id,
                'title' => $a->title,
                'meta'  => 'Announcement',
                'url'   => route('student.announcements.show', $a->id),
            ]);

        return $this->wrap([
            'Classes'       => $classes,
            'Assignments'   => $assignments,
            'Quizzes'       => $quizzes,
            'Lessons'       => $lessons,
            'Announcements' => $announcements,
        ]);
    }

    /* ═══════════════════════════════════════════════════════ */
    /* ADMIN                                                   */
    /* ═══════════════════════════════════════════════════════ */
    protected function adminSuggest(string $q): array
    {
        $like = "%{$q}%";
        $out  = [];

        Student::with('user:id,name')
            ->where(function ($w) use ($like) {
                $w->where('lrn', 'like', $like)
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like));
            })
            ->limit(4)->get()
            ->each(function ($s) use (&$out) {
                $out[] = [
                    'type'  => 'Student',
                    'title' => $s->user?->name,
                    'meta'  => "LRN {$s->lrn}",
                    'url'   => route('admin.students.show', $s->id),
                ];
            });

        Teacher::with('user:id,name')
            ->where(function ($w) use ($like) {
                $w->where('employee_no', 'like', $like)
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like));
            })
            ->limit(3)->get()
            ->each(function ($t) use (&$out) {
                $out[] = [
                    'type'  => 'Faculty',
                    'title' => $t->user?->name,
                    'meta'  => "Employee {$t->employee_no}",
                    'url'   => route('admin.teachers.show', $t->id),
                ];
            });

        Applicant::where('reference_number', 'like', $like)
            ->orWhere('last_name', 'like', $like)
            ->orWhere('first_name', 'like', $like)
            ->limit(3)->get()
            ->each(function ($a) use (&$out) {
                $out[] = [
                    'type'  => 'Applicant',
                    'title' => trim("{$a->first_name} {$a->last_name}"),
                    'meta'  => "{$a->reference_number} • {$a->status}",
                    'url'   => route('admin.applicants.show', $a->id),
                ];
            });

        Section::with('strand:id,code,name')
            ->where('name', 'like', $like)
            ->limit(3)->get()
            ->each(function ($s) use (&$out) {
                $out[] = [
                    'type'  => 'Section',
                    'title' => $s->name,
                    'meta'  => $s->strand?->name,
                    'url'   => route('admin.sections.show', $s->id),
                ];
            });

        return $out;
    }

    protected function adminGroups(string $q): array
    {
        $like = "%{$q}%";

        $students = Student::with('user:id,name')
            ->where(function ($w) use ($like) {
                $w->where('lrn', 'like', $like)
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like));
            })
            ->limit(12)->get()
            ->map(fn ($s) => [
                'id'    => $s->id,
                'title' => $s->user?->name,
                'meta'  => "LRN {$s->lrn}",
                'url'   => route('admin.students.show', $s->id),
            ]);

        $teachers = Teacher::with('user:id,name')
            ->where(function ($w) use ($like) {
                $w->where('employee_no', 'like', $like)
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like));
            })
            ->limit(12)->get()
            ->map(fn ($t) => [
                'id'    => $t->id,
                'title' => $t->user?->name,
                'meta'  => "Employee {$t->employee_no}",
                'url'   => route('admin.teachers.show', $t->id),
            ]);

        $applicants = Applicant::where('reference_number', 'like', $like)
            ->orWhere('last_name', 'like', $like)
            ->orWhere('first_name', 'like', $like)
            ->limit(12)->get()
            ->map(fn ($a) => [
                'id'    => $a->id,
                'title' => trim("{$a->first_name} {$a->last_name}"),
                'meta'  => "{$a->reference_number} • {$a->status}",
                'url'   => route('admin.applicants.show', $a->id),
            ]);

        $sections = Section::with('strand:id,code,name')
            ->where('name', 'like', $like)
            ->limit(12)->get()
            ->map(fn ($s) => [
                'id'    => $s->id,
                'title' => $s->name,
                'meta'  => $s->strand?->name,
                'url'   => route('admin.sections.show', $s->id),
            ]);

        return $this->wrap([
            'Students'   => $students,
            'Faculty'    => $teachers,
            'Applicants' => $applicants,
            'Sections'   => $sections,
        ]);
    }

    /* ═══════════════════════════════════════════════════════ */
    /* HELPER                                                  */
    /* ═══════════════════════════════════════════════════════ */
    protected function wrap(array $groups): array
    {
        return collect($groups)
            ->filter(fn ($rows) => $rows->isNotEmpty())
            ->map(fn ($rows, $label) => [
                'label' => $label,
                'count' => $rows->count(),
                'items' => $rows->values()->all(),
            ])
            ->values()
            ->all();
    }
}