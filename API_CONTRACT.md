# API Contract — Salawag SHS School Website

**For:** Frontend developer
**Maintained by:** Backend team
**Last updated:** 2026-09-18

This file describes every endpoint the frontend can hit, what data it returns, and the exact response shapes. **Do not guess — check here.**

---

## 1. Authentication

### Test accounts (all password: `password`)

| Role | Email | Notes |
|---|---|---|
| Admin | `admin@test.com` | Placeholder dashboard only |
| Teacher | `teacher@test.com` | Owns 3 classes |
| Student | `student@test.com` | Enrolled in 7 classes |

### Login flow (Breeze default)

- `GET /login` → renders `Auth/Login.vue`
- `POST /login` with `{ email, password }` → redirects to `/dashboard`
- `/dashboard` redirects by role:
  - Admin → `/admin/dashboard`
  - Teacher → `/teacher/dashboard`
  - Student → `/student/dashboard`

### Must-change-password flow (IMPORTANT)

If `auth.user.must_change_password === true`:
- Every protected route redirects to `/password/change`
- User cannot access anything until they change password
- After change, `must_change_password` becomes `false` and normal flow resumes

**Frontend implications:**
- Check `$page.props.auth.user.must_change_password` — if true, show a banner
- Route `password.change` renders `Auth/ChangePassword.vue`
- Route `password.change.update` (PUT) submits the change

### Global shared props

Available on **every page** via `usePage().props`:

```js
{
  auth: {
    user: {
      id: 1,
      name: "John Doe",
      email: "teacher@test.com",
      email_verified_at: "2026-01-15T...",
      must_change_password: false,
      admin_position_id: null,
      created_at: "...",
      updated_at: "..."
    },
    roles: ["teacher"],           // array of role names
    can: { ... }                  // permission map
  },
  flash: {
    success: "Assignment created.",   // string or null
    error: null,
    warning: null
  }
}
Access in Vue:

vue
<script setup>
import { usePage } from '@inertiajs/vue3'
const page = usePage()
// page.props.auth.user.name
// page.props.auth.roles  // ['teacher']
// page.props.flash.success
</script>
2. Pagination Pattern (CRITICAL)
Any endpoint that returns a paginated list returns Laravel's paginator object:

json
{
  "classes": {                          // ← the prop name varies
    "data": [                           // ← the actual array lives here
      { "id": 1, ... },
      { "id": 2, ... }
    ],
    "current_page": 1,
    "first_page_url": "...",
    "from": 1,
    "last_page": 3,
    "last_page_url": "...",
    "links": [
      { "url": null, "label": "&laquo; Previous", "active": false },
      { "url": "/student/classes?page=1", "label": "1", "active": true },
      { "url": "/student/classes?page=2", "label": "2", "active": false },
      { "url": "/student/classes?page=2", "label": "Next &raquo;", "active": false }
    ],
    "next_page_url": "/student/classes?page=2",
    "path": "/student/classes",
    "per_page": 20,
    "prev_page_url": null,
    "to": 20,
    "total": 56
  }
}
Rule: always use propName.data for the array, propName.links for pagination.

Endpoints that paginate:

teacher.classes.index

student.classes.index

Endpoints that return plain arrays (no .data):

Assignments, Materials, Announcements, Submissions, Dashboard widgets

3. Route Reference
Shared
Route name	URL	Method	Renders
dashboard	/dashboard	GET	Redirects by role
profile.edit	/profile	GET	Profile/Edit.vue
profile.update	/profile	PATCH	—
profile.destroy	/profile	DELETE	—
logout	/logout	POST	—
password.change	/password/change	GET	Auth/ChangePassword.vue
password.change.update	/password/change	PUT	—
Teacher
Route name	URL	Method	Renders	Params
teacher.dashboard	/teacher/dashboard	GET	Teacher/Dashboard.vue	—
teacher.classes.index	/teacher/classes	GET	Teacher/Classes/Index.vue	—
teacher.classes.show	/teacher/classes/{classroom}	GET	Teacher/Classes/Show.vue	classroom
teacher.classes.assignments.index	/teacher/classes/{classroom}/assignments	GET	Teacher/Assignments/Index.vue	classroom
teacher.classes.assignments.create	/teacher/classes/{classroom}/assignments/create	GET	Teacher/Assignments/Create.vue	classroom
teacher.classes.assignments.store	/teacher/classes/{classroom}/assignments	POST	—	classroom
teacher.assignments.show	/teacher/assignments/{assignment}	GET	Teacher/Assignments/Show.vue	assignment
teacher.assignments.edit	/teacher/assignments/{assignment}/edit	GET	Teacher/Assignments/Edit.vue	assignment
teacher.assignments.update	/teacher/assignments/{assignment}	PUT	—	assignment
teacher.assignments.destroy	/teacher/assignments/{assignment}	DELETE	—	assignment
teacher.submissions.grade	/teacher/submissions/{submission}/grade	PUT	—	submission
teacher.submissions.download	/teacher/submissions/{submission}/download	GET	File download	submission
teacher.classes.lessons.index	/teacher/classes/{classroom}/lessons	GET	Teacher/Lessons/Index.vue	classroom
teacher.classes.lessons.create	/teacher/classes/{classroom}/lessons/create	GET	Teacher/Lessons/Create.vue	classroom
teacher.classes.lessons.store	/teacher/classes/{classroom}/lessons	POST	—	classroom
teacher.lessons.show	/teacher/lessons/{lesson}	GET	Teacher/Lessons/Show.vue	lesson
teacher.lessons.edit	/teacher/lessons/{lesson}/edit	GET	Teacher/Lessons/Edit.vue	lesson
teacher.lessons.update	/teacher/lessons/{lesson}	PUT	—	lesson
teacher.lessons.destroy	/teacher/lessons/{lesson}	DELETE	—	lesson
teacher.lesson-attachments.download	/teacher/lesson-attachments/{attachment}/download	GET	File download	attachment
teacher.classes.announcements.index	/teacher/classes/{classroom}/announcements	GET	Teacher/Announcements/Index.vue	classroom
teacher.classes.announcements.store	/teacher/classes/{classroom}/announcements	POST	—	classroom
teacher.announcements.show	/teacher/announcements/{announcement}	GET	Teacher/Announcements/Show.vue	announcement
teacher.announcements.update	/teacher/announcements/{announcement}	PUT	—	announcement
teacher.announcements.destroy	/teacher/announcements/{announcement}	DELETE	—	announcement
teacher.announcements.toggle-pin	/teacher/announcements/{announcement}/pin	PUT	—	announcement
Student
Route name	URL	Method	Renders	Params
student.dashboard	/student/dashboard	GET	Student/Dashboard.vue	—
student.classes.index	/student/classes	GET	Student/Classes/Index.vue	—
student.classes.show	/student/classes/{classroom}	GET	Student/Classes/Show.vue	classroom
student.classes.assignments.index	/student/classes/{classroom}/assignments	GET	Student/Assignments/Index.vue	classroom
student.assignments.show	/student/assignments/{assignment}	GET	Student/Assignments/Show.vue	assignment
student.assignments.submit	/student/assignments/{assignment}/submit	POST	—	assignment
student.assignments.submission.download	/student/assignments/{assignment}/submission/file	GET	File download	assignment
student.classes.lessons.index	/student/classes/{classroom}/lessons	GET	Student/Lessons/Index.vue	classroom
student.lessons.show	/student/lessons/{lesson}	GET	Student/Lessons/Show.vue	lesson
student.lesson-attachments.download	/student/lesson-attachments/{attachment}/download	GET	File download	attachment
student.classes.announcements.index	/student/classes/{classroom}/announcements	GET	Student/Announcements/Index.vue	classroom
student.announcements.show	/student/announcements/{announcement}	GET	Student/Announcements/Show.vue	announcement
student.announcements.feed	/student/announcements	GET	Student/Announcements/Feed.vue	—
In Vue, use route('name', params) — never hardcode URLs.

vue
<Link :href="route('teacher.classes.show', classroom.id)">View</Link>
For scalar params: route('teacher.classes.show', { classroom: 34 }) or route('teacher.classes.show', 34).

4. Response Shapes — Every Endpoint
Teacher Dashboard — teacher.dashboard
js
{
  stats: {
    classes: 3,
    students: 45,
    pending_grading: 8
  },
  needs_grading: [
    {
      id: 1,
      student_name: "Jane Cruz",
      assignment_id: 5,
      assignment: "Essay on Rizal",
      subject: "General Science",
      section: "Grade 11 - STEM",
      submitted_at: "2026-09-18T10:23:00+08:00",
      is_late: false
    }
  ],
  today_schedule: [
    {
      id: 1,
      subject: "Cookery",
      subject_code: "HOSP-COOK",
      section: "Grade 11 - HOSPITALITY",
      room: "Room 101",
      time_start: "08:00:00",
      time_end: "09:30:00"
    }
  ],
  active_term: "1st Semester",
  today: "Thursday, September 18, 2026"
}
Student Dashboard — student.dashboard
js
{
  stats: {
    classes: 7,
    pending_assignments: 3,
    grades_available: 12
  },
  upcoming_deadlines: [
    {
      id: 5,
      title: "Essay on Rizal",
      subject: "General Science",
      due_at: "2026-09-25T23:59:00+08:00",
      due_human: "in 7 days",
      days_left: 7,
      points: 100
    }
  ],
  recent_announcements: [
    {
      id: 3,
      title: "No class on Friday",
      body_preview: "Enjoy the long weekend.",
      is_pinned: true,
      author: "Ms. Cruz",
      subject: "Cookery",
      published_at: "2026-09-18T08:00:00+08:00",
      published_human: "2 hours ago"
    }
  ],
  active_term: "1st Semester"
}
Teacher Classes — teacher.classes.index
js
{
  classes: {
    data: [
      {
        id: 34,
        subject: "Cookery",
        subject_code: "HOSP-COOK",
        section: "Grade 11 - HOSPITALITY",
        grade_level: "11",
        strand: "Hospitality and Tourism",
        strand_code: "HOSPITALITY",
        term: "1st Semester",
        is_published: true,
        assignments_count: 5,
        students_count: 40
      }
    ],
    current_page: 1,
    last_page: 1,
    total: 3,
    links: [...]
  },
  activeTerm: "1st Semester",
  filters: {
    search: null,
    grade_level: null,
    strand_id: null,
    term_id: 1,
    is_published: null,
    sort: "section",
    direction: "asc",
    per_page: 20
  },
  filterOptions: {
    strands: [
      { id: 12, code: "HOSPITALITY", name: "Hospitality and Tourism" },
      ...
    ],
    terms: [
      { id: 1, name: "1st Semester" },
      { id: 2, name: "2nd Semester" }
    ]
  }
}
Query params supported: search, grade_level (11|12), strand_id, term_id, is_published (0|1), sort (subject|section|grade_level|created_at), direction (asc|desc), per_page (5-100).

Student Classes — student.classes.index
js
{
  classes: {
    data: [
      {
        id: 34,
        subject: "Cookery",
        subject_code: "HOSP-COOK",
        section: "Grade 11 - HOSPITALITY",
        grade_level: "11",
        strand: "Hospitality and Tourism",
        teacher: "Ms. Cruz",
        term: "1st Semester",
        assignments_count: 5,
        pending_assignments_count: 2
      }
    ],
    current_page: 1,
    last_page: 1,
    total: 7,
    links: [...]
  },
  activeTerm: "1st Semester",
  filters: { ... },
  filterOptions: { ... }
}
Same query params as teacher, plus sort supports teacher as an option.

Teacher Class Show — teacher.classes.show
js
{
  classroom: {
    id: 34,
    subject: "Cookery",
    subject_code: "HOSP-COOK",
    section: "Grade 11 - HOSPITALITY",
    grade_level: "11",
    term: "1st Semester"
  },
  students: [
    { id: 61, name: "Jane Cruz", lrn: "123456789012", email: "jane@example.com" }
  ]
}
Teacher Assignments Index — teacher.classes.assignments.index
js
{
  classroom: {
    id: 34,
    subject: "Cookery",
    section: "Grade 11 - HOSPITALITY"
  },
  assignments: [
    {
      id: 5,
      title: "Essay on Rizal",
      due_at: "2026-09-25T23:59:00+08:00",
      points: 100,
      is_published: true,
      allow_late: true,
      submissions_count: 3
    }
  ]
}
Teacher Assignment Show — teacher.assignments.show
js
{
  assignment: {
    id: 5,
    title: "Essay on Rizal",
    instructions: "Write 500 words.",
    due_at: "2026-09-25T23:59:00+08:00",
    points: 100,
    allow_late: true,
    is_published: true,
    classroom_id: 34,
    subject: "Cookery",
    section: "Grade 11 - HOSPITALITY"
  },
  submissions: [
    {
      id: 12,
      student_id: 61,
      student_name: "Jane Cruz",
      status: "submitted",       // 'submitted' | 'late' | 'graded'
      submitted_at: "2026-09-18T10:23:00+08:00",
      grade: null,
      feedback: null,
      graded_at: null,
      text_content: "My essay...",
      file_path: "submissions/34/5/uuid.pdf",     // null if no file
      has_file: true,
      download_url: "/teacher/submissions/12/download"
    }
  ],
  stats: {
    total_students: 40,
    submitted: 3,
    graded: 0
  }
}
Teacher Lessons Index — teacher.classes.lessons.index
js
{
  classroom: { id: 34, subject: "Cookery", section: "Grade 11 - HOSPITALITY" },
  lessons: [
    {
      id: 3,
      title: "Introduction to Cooking",
      body_preview: "Watch this video: https://youtube.com/...",  // first 120 chars
      position: 0,
      is_published: true,
      created_at: "2026-09-18T08:00:00+08:00"
    }
  ]
}
Teacher Lesson Show — teacher.lessons.show
js
{
  classroom: { id: 34, subject: "Cookery", section: "Grade 11 - HOSPITALITY" },
  lesson: {
    id: 3,
    title: "Introduction to Cooking",
    body: "Full text content here...",
    position: 0,
    is_published: true,
    created_at: "2026-09-18T08:00:00+08:00",
    attachments: [
      {
        id: 8,
        file_name: "recipe.pdf",
        file_size: 245321,
        mime_type: "application/pdf",
        download_url: "/teacher/lesson-attachments/8/download"
      }
    ]
  }
}
Teacher Announcements Index — teacher.classes.announcements.index
js
{
  classroom: { id: 34, subject: "Cookery", section: "Grade 11 - HOSPITALITY" },
  announcements: [
    {
      id: 7,
      title: "No class on Friday",
      body_preview: "Enjoy the long weekend...",
      is_pinned: true,
      is_published: true,
      published_at: "2026-09-18T08:00:00+08:00",
      expires_at: null,
      created_at: "2026-09-18T07:55:00+08:00"
    }
  ]
}
Student Assignment Show — student.assignments.show
js
{
  assignment: {
    id: 5,
    title: "Essay on Rizal",
    instructions: "Write 500 words.",
    due_at: "2026-09-25T23:59:00+08:00",
    points: 100,
    allow_late: true,
    subject: "Cookery",
    section: "Grade 11 - HOSPITALITY",
    teacher: "Ms. Cruz"
  },
  submission: null,   // OR:
  submission: {
    id: 12,
    text_content: "My essay...",
    status: "submitted",        // 'submitted' | 'late' | 'graded'
    submitted_at: "2026-09-18T10:23:00+08:00",
    grade: 95,                  // null until graded
    feedback: "Great work!",    // null until graded
    graded_at: "2026-09-18T12:00:00+08:00",
    has_file: true,
    download_url: "/student/assignments/5/submission/file"
  }
}
Student Lesson Show — student.lessons.show
Same shape as teacher's lesson show, plus teacher in classroom.

Student Announcements Feed — student.announcements.feed
js
{
  announcements: [
    {
      id: 7,
      title: "No class on Friday",
      body_preview: "Enjoy the long weekend...",
      is_pinned: true,
      author: "Ms. Cruz",
      subject: "Cookery",
      section: "Grade 11 - HOSPITALITY",
      published_at: "2026-09-18T08:00:00+08:00"
    }
  ]
}
Query param: ?limit=5 (default 5, max 20).

5. Form Submission Patterns
Standard form (Inertia useForm)
vue
<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    title: '',
    instructions: '',
    due_at: '',
    points: 100,
    allow_late: true,
    is_published: false,
})

function submit() {
    form.post(route('teacher.classes.assignments.store', classroom.id), {
        onSuccess: () => {
            // optional callback
        },
    })
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div>
            <input v-model="form.title" type="text" class="..." />
            <p v-if="form.errors.title" class="text-red-600 text-xs">{{ form.errors.title }}</p>
        </div>
        <button type="submit" :disabled="form.processing">
            {{ form.processing ? 'Saving...' : 'Save' }}
        </button>
    </form>
</template>
form.errors.field is auto-populated from 422 responses. form.processing is true while the request is in flight.

File upload form
vue
<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = useForm({
    text_content: '',
    file: null,
})

const fileInput = ref(null)

function handleFile(e) {
    form.file = e.target.files[0]
}

function submit() {
    form.post(route('student.assignments.submit', assignment.id), {
        forceFormData: true,   // ← REQUIRED for file uploads
    })
}
</script>

<template>
    <form @submit.prevent="submit">
        <textarea v-model="form.text_content" class="..."></textarea>
        <input type="file" @change="handleFile" accept=".pdf,.doc,.docx,.jpg,.png" />
        <button type="submit" :disabled="form.processing">Submit</button>
    </form>
</template>
Key rules for file uploads:

Use forceFormData: true — Inertia sends multipart/form-data instead of JSON

Files arrive as form.file (single) or form.attachments (array)

Max size: 10 MB

Allowed extensions: pdf, doc, docx, ppt, pptx, xls, xlsx, txt, jpg, jpeg, png, zip

Lesson attachments also allow: mp4, mp3

PUT with single field (publish toggle)
vue
<script setup>
import { router } from '@inertiajs/vue3'

function publish() {
    router.put(route('teacher.lessons.update', lesson.id), {
        is_published: true,
    })
}
</script>
Note: this only sends is_published, not the full record. The controller uses sometimes validation so it works.

DELETE
vue
function destroy() {
    if (! confirm('Delete this?')) return
    router.delete(route('teacher.lessons.destroy', lesson.id))
}
Custom redirect after success
js
form.post(route('...'), {
    onSuccess: (page) => {
        // do something with the response
        console.log('Redirected to:', page.url)
    },
})
6. Flash Messages
After a successful POST/PUT/DELETE, Laravel redirects and flashes a message.

Access via usePage():

vue
<script setup>
import { usePage } from '@inertiajs/vue3'
const page = usePage()
</script>

<template>
    <div v-if="page.props.flash?.success"
         class="bg-green-50 border border-green-200 text-green-800 p-3 rounded mb-4">
        {{ page.props.flash.success }}
    </div>
    <div v-if="page.props.flash?.error"
         class="bg-red-50 border border-red-200 text-red-800 p-3 rounded mb-4">
        {{ page.props.flash.error }}
    </div>
</template>
Or with $page in the template directly:

vue
<div v-if="$page.props.flash?.success" class="...">
    {{ $page.props.flash.success }}
</div>
Controllers send ->with('success', '...') or ->with('error', '...') or ->with('warning', '...').

7. Error Responses
Validation (422)
When form.post() fails validation, Inertia automatically populates form.errors. You don't need to handle the response.

vue
<p v-if="form.errors.title" class="text-red-600 text-xs">{{ form.errors.title }}</p>
Unauthorized (403)
Triggered by abort_unless(..., 403) in controllers. Inertia renders Laravel's 403 error page. Nothing special to handle.

Not Found (404)
Renders Laravel's 404 page. Nothing special.

Server Error (500)
Renders Laravel's 500 error page. In dev, shows a detailed stack trace with the exception.

8. Common Response Field Types
Field	Type	Notes
id	number	Always an integer
*_at (e.g., due_at, submitted_at)	string	ISO 8601, e.g. "2026-09-25T23:59:00+08:00". Parse with new Date(value)
*_human (e.g., due_human)	string	Human-readable, e.g. "in 7 days"
*_preview (e.g., body_preview)	string	First 120 chars of a longer text
points, grade, *_score	number	Decimal, e.g. 100.00, 95.5
*_count (e.g., submissions_count)	number	Integer
is_* (e.g., is_published)	boolean	Always true/false, not 1/0
status	enum	Depends on the endpoint (see below)
Status enums
Assignment submission status:

not_submitted — student hasn't submitted

submitted — submitted on time

late — submitted after deadline

graded — teacher has graded

Note: not_submitted isn't returned by the backend — it's a UI-only inference. If submission is null, treat it as not submitted.

9. Date & Time Handling
Backend sends ISO 8601 strings (e.g., "2026-09-25T23:59:00+08:00").

Parse with new Date():

js
new Date(iso).toLocaleString()       // "9/25/2026, 11:59:00 PM"
new Date(iso).toLocaleDateString()   // "9/25/2026"
new Date(iso).toLocaleTimeString()   // "11:59:00 PM"
Overdue check:

js
const isOverdue = new Date(due_at) < new Date()
Days left:

The backend already computes days_left for you where relevant (e.g., upcoming deadlines). Use it directly.

Timezones: all timestamps are in the school's timezone (Asia/Manila). Don't worry about conversion.

10. Layout & Role Colors
Every page must be wrapped in a layout:

vue
<TeacherLayout>...</TeacherLayout>   <!-- for teacher pages, indigo theme -->
<StudentLayout>...</StudentLayout>   <!-- for student pages, emerald theme -->
<AuthenticatedLayout>...</AuthenticatedLayout>   <!-- for admin -->
Colors:

Role	Primary color	Classes
Teacher	Indigo	bg-indigo-600, text-indigo-600, hover:bg-indigo-700
Student	Emerald	bg-emerald-600, text-emerald-600, hover:bg-emerald-700
Admin	Gray	bg-gray-600, text-gray-600
Common patterns:

vue
<!-- Card -->
<div class="bg-white rounded-lg shadow p-5 border border-gray-200">

<!-- Primary button (teacher) -->
<button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-md">

<!-- Primary button (student) -->
<button class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-4 py-2 rounded-md">

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Label</th></tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <tr class="hover:bg-gray-50"><td class="px-6 py-4 text-sm">Value</td></tr>
        </tbody>
    </table>
</div>

<!-- Empty state -->
<div v-if="!items.length" class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
    No items yet.
</div>

<!-- Status badge -->
<span class="text-xs px-2 py-1 rounded-full"
      :class="item.is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">
    {{ item.is_published ? 'Published' : 'Draft' }}
</span>
11. Endpoints NOT Built Yet (Don't Try to Use)
The following features exist in the database schema but have no backend endpoints:

❌ Grades report card (/student/grades, /teacher/gradebook)

❌ Attendance

❌ Quizzes

❌ Calendar

❌ Messaging / conversations

❌ Notifications

❌ Reports / analytics

❌ Admin CRUD (users, sections, subjects, enrollment oversight)

If a design requires one of these, coordinate with the backend team before building the UI.

12. Debugging Checklist
When a page breaks:

Open browser console (F12) — 99% of issues show there

Read the error carefully:

Cannot read properties of undefined → prop shape mismatch

Failed to resolve component: X → missing import

Page not found: ...vue → file path wrong

Route [name] not defined → Ziggy doesn't know the route (backend needs to add it)

Check the actual response in Network tab → click the request → Response tab

Confirm Vite is running — npm run dev should be active

Hard-refresh the browser: Ctrl+Shift+R

Most common bug: prop name mismatch
If the controller sends:

php
Inertia::render('Teacher/Classes/Show', ['classroom' => $data])
And Vue declares:

js
defineProps({ klass: Object })   // ← wrong name
The page renders white because klass is undefined.

Fix: always match controller array keys to defineProps keys exactly.

Most common bug #2: paginator not accessed via .data
If a list endpoint paginates:

js
classes: { data: [...], current_page: 1, ... }
And Vue does:

vue
<div v-for="c in classes">   <!-- wrong -->
Nothing renders because classes is an object, not an array.

Fix: v-for="c in classes.data".

13. Quick Reference — Imports
js
import { Link, useForm, router, usePage } from '@inertiajs/vue3'
import { ref, computed, watch, onMounted } from 'vue'

import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import StudentLayout from '@/Layouts/StudentLayout.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
14. When You Need a New Endpoint
If your UI needs data that no endpoint provides:

Write down exactly what you need: input params + expected response shape

Send to backend teammate with the route name you'd like

They build it + add it to this file

You consume it

Never try to build data-fetching without a backend endpoint. Inertia pages only get data through Inertia::render() props.

15. Where to Ask for Help
Route/response questions → check this file first, then ask backend

UI/Tailwind questions → look at existing .vue files for patterns

Bug that isn't obvious → screenshot + browser console error + paste the failing file

That's everything. Build against this contract; if something is wrong, tell the backend team so we can update this file.