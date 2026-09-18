I_CONTEXT_FRONTEND.md — Frontend Dev Context File
Save this in your project root (or resources/js/AI_CONTEXT_FRONTEND.md). Your frontend teammate hands this to any AI before asking questions.

markdown
# AI Context — Frontend (Salawag SHS School Website)

For: Frontend developer working on the Vue side of this project.
Purpose: Give this file to any AI before asking it to help with frontend code.
Last updated: 2026-09-18

---

## 1. What This Project Is

A school management website + LMS for **Salawag Senior High School**.
Built with Laravel + Inertia + Vue 3 + Tailwind CSS.

**Three roles:** Admin, Teacher, Student.
**Current focus:** The LMS (teacher ↔ student) side.

The backend is built separately. You (frontend) consume Inertia pages and props.
You do NOT write PHP, migrations, controllers, or routes — a teammate handles those.

---

## 2. Frontend Stack

- **Vue 3** with `<script setup>` (Composition API, no Options API)
- **Inertia.js v2** — bridges Laravel controllers to Vue pages. You never fetch JSON manually.
- **Tailwind CSS v4** — utility classes inline. No custom CSS files.
- **Vite** — dev server, hot reload
- **Ziggy** — provides `route('name', params)` helper in JS
- **VS Code** + **Tailwind CSS IntelliSense** extension recommended

**No** state management library (Pinia/Vuex). No Vue Router. Inertia handles all navigation.

---

## 3. How Inertia Works (Critical to Understand)

**You do not fetch data.** Laravel controllers pass props to Vue pages automatically.
User clicks <Link href="/teacher/classes/5">
→ Laravel routes it to a controller
→ Controller runs Inertia::render('Teacher/Classes/Show', ['classroom' => [...]])
→ Inertia loads resources/js/Pages/Teacher/Classes/Show.vue
→ The array becomes the Vue page's props
→ Vue renders

text

**Everything you need is in `props`.** No axios. No `fetch`. No API calls.

### Two things you must know

1. **Navigation uses `<Link>`, not `<a>`:**
   ```vue
   import { Link } from '@inertiajs/vue3'
   <Link :href="route('teacher.classes.index')">My Classes</Link>
Forms use useForm:

vue
import { useForm } from '@inertiajs/vue3'
const form = useForm({ title: '', due_at: '' })
form.post(route('teacher.classes.assignments.store', classroom.id))
After success, Laravel redirects and Inertia follows — the page reloads with new props.

4. File Structure (Frontend)
text
resources/js/
├── app.js                                    (Inertia entry — don't edit)
├── bootstrap.js                              (axios setup — don't edit)
├── ssr.js                                    (SSR entry — don't edit)
├── Layouts/
│   ├── AuthenticatedLayout.vue               (Breeze default, used by admin)
│   ├── GuestLayout.vue                       (Breeze default, used by login/register)
│   ├── TeacherLayout.vue                     (indigo theme, teacher nav)
│   └── StudentLayout.vue                     (emerald theme, student nav)
├── Components/                               (reusable pieces — Breeze defaults + yours)
│   ├── PrimaryButton.vue
│   ├── TextInput.vue
│   ├── Modal.vue
│   ├── InputError.vue
│   ├── InputLabel.vue
│   └── ...
└── Pages/                                    (one file = one route)
    ├── Welcome.vue
    ├── Dashboard.vue                         (role redirector)
    ├── Auth/                                 (Breeze)
    │   ├── Login.vue
    │   └── Register.vue
    ├── Profile/                              (Breeze)
    │   └── Edit.vue
    ├── Admin/
    │   └── Dashboard.vue                     (placeholder)
    ├── Teacher/
    │   ├── Dashboard.vue                     (class count)
    │   ├── Classes/
    │   │   ├── Index.vue                     (grid of class cards)
    │   │   └── Show.vue                      (tabs: Students/Assignments/Materials)
    │   ├── Assignments/
    │   │   ├── Index.vue                     (list per class)
    │   │   ├── Create.vue
    │   │   ├── Show.vue                      (submissions + grading modal)
    │   │   └── Edit.vue
    │   └── Lessons/                          (Materials)
    │       ├── Index.vue
    │       ├── Create.vue
    │       ├── Show.vue
    │       └── Edit.vue
    └── Student/
        ├── Dashboard.vue
        ├── Classes/
        │   ├── Index.vue
        │   └── Show.vue
        ├── Assignments/
        │   ├── Index.vue
        │   └── Show.vue                      (view + submit + see grade)
        └── Lessons/
            ├── Index.vue
            └── Show.vue
Rule: Folder path mirrors the controller's Inertia::render() string.
Controller says Inertia::render('Teacher/Assignments/Index') → file is resources/js/Pages/Teacher/Assignments/Index.vue.

5. Coding Conventions
Vue file structure
Always this order:

vue
<script setup>
import Layout from '@/Layouts/SomeLayout.vue'
import { Link, useForm, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'   // only if needed

// 1. Props
const props = defineProps({
    classroom: Object,
    assignments: Array,
})

// 2. Form (if any)
const form = useForm({ title: '' })

// 3. Local state (if any)
const active = ref(null)

// 4. Computed (if any)
const isOverdue = computed(() => ...)

// 5. Functions
function submit() {
    form.post(route('...'))
}
</script>

<template>
    <Layout>
        <template #header>
            <h2 class="...">Page title</h2>
        </template>
        <!-- body -->
    </Layout>
</template>
Props
Always declare defineProps({...}) — even if you won't use some props yet.

Prop names must match the controller's array keys EXACTLY. If Laravel sends classroom, you declare classroom. Not klass, not class.

Type hints: Object, Array, String, Number, Boolean.

Layouts
Every page must wrap its content in a layout:

vue
<TeacherLayout>   <!-- for teacher pages -->
<StudentLayout>   <!-- for student pages -->
<AuthenticatedLayout>   <!-- for admin/generic -->
Styling
Tailwind only. No <style> blocks, no custom CSS files.

Role colors:

Teacher → indigo (bg-indigo-600, text-indigo-600)

Student → emerald (bg-emerald-600, text-emerald-600)

Admin → gray (bg-gray-600)

Common patterns:

Card: bg-white rounded-lg shadow p-5 border border-gray-200

Primary button (teacher): bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-md

Primary button (student): bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-4 py-2 rounded-md

Table: wrap in bg-white rounded-lg shadow overflow-hidden, header row bg-gray-50

Status badges: text-xs px-2 py-1 rounded-full + colored bg/text

Empty states
Always handle empty arrays with a clear message + CTA:

vue
<div v-if="!items.length" class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
    No X yet. Click <span class="font-semibold text-indigo-600">+ New X</span> to get started.
</div>
Dates
Backend sends ISO 8601 strings (2026-09-25T23:59:00+08:00).

Display with new Date(iso).toLocaleString() or .toLocaleDateString().

Overdue detection: new Date(due_at) < new Date().

6. What's Available for You to Build
✅ Fully working pages (backend + Vue done)
Login, register, password reset, profile edit

/dashboard → redirects by role

Admin dashboard (placeholder)

Teacher dashboard (class count)

Teacher: my classes list, class detail with tabs + roster

Teacher: assignments list, create, show (grading), edit

Teacher: materials list, create, show, edit

Student dashboard (class count)

Student: my classes list, class detail

Student: assignments list, show + submit + view grade

Student: materials list, show

🟡 Partial (needs frontend polish)
Teacher dashboard — add "needs grading" widget

Student dashboard — add "upcoming deadlines" widget

Class Show pages — some tabs still disabled placeholders

❌ Not built yet (don't try to build)
Announcements

Grades / report card

Quizzes

Calendar

Attendance

Messaging

Notifications

Reports / analytics

If asked to build any of these, tell the requester they need backend first.

7. Endpoints You Can Navigate To
Use route('name', params) in JS. Here are the key ones:

Teacher routes
text
teacher.dashboard
teacher.classes.index
teacher.classes.show                {classroom}
teacher.classes.assignments.index   {classroom}
teacher.classes.assignments.create  {classroom}
teacher.classes.lessons.index       {classroom}
teacher.classes.lessons.create      {classroom}
teacher.assignments.show            {assignment}
teacher.assignments.edit            {assignment}
teacher.lessons.show                {lesson}
teacher.lessons.edit                {lesson}
Student routes
text
student.dashboard
student.classes.index
student.classes.show                {classroom}
student.classes.assignments.index   {classroom}
student.classes.lessons.index       {classroom}
student.assignments.show            {assignment}
student.lessons.show                {lesson}
Shared
text
dashboard
profile.edit
logout
Check with: php artisan route:list (backend teammate can run it for you, or read the shared API_CONTRACT.md).

8. Response Shapes (What Props You Get)
Teacher — Assignments Index
Props:

js
{
  classroom: { id, subject, section },
  assignments: [
    { id, title, due_at, points, is_published, allow_late, submissions_count }
  ]
}
Teacher — Assignment Show
js
{
  assignment: { id, title, instructions, due_at, points, allow_late, is_published, classroom_id, subject, section },
  submissions: [
    { id, student_id, student_name, status, submitted_at, grade, feedback, graded_at, text_content }
  ],
  stats: { total_students, submitted, graded }
}
Student — Assignment Show
js
{
  assignment: { id, title, instructions, due_at, points, allow_late, subject, section, teacher },
  submission: null | {
    id, text_content, status, submitted_at, grade, feedback, graded_at
  }
}
Lesson (Material) — same shape as assignment, replace fields with { id, title, body, is_published, created_at }.
Status enum values you'll see on submissions:

'not_submitted' — student hasn't submitted

'submitted' — submitted on time

'late' — submitted after deadline

'graded' — teacher has graded

9. Common Patterns
Page header (via layout slot)
vue
<template #header>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Page Title</h2>
            <p class="text-sm text-gray-500">Subtitle or context</p>
        </div>
        <div class="flex gap-3">
            <Link :href="route('...')" class="...">Action</Link>
        </div>
    </div>
</template>
Form with validation
vue
<script setup>
import { useForm } from '@inertiajs/vue3'
const form = useForm({ title: '', body: '' })
function submit() {
    form.post(route('...'), {
        onSuccess: () => form.reset(),
    })
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input v-model="form.title" type="text"
                   class="w-full border border-gray-300 rounded px-3 py-2" />
            <p v-if="form.errors.title" class="text-red-600 text-xs mt-1">{{ form.errors.title }}</p>
        </div>
        <button type="submit" :disabled="form.processing"
                class="bg-indigo-600 ... disabled:opacity-50">
            {{ form.processing ? 'Saving...' : 'Save' }}
        </button>
    </form>
</template>
form.errors.X is populated automatically from Laravel validation errors.

Publish/Draft badge
vue
<span class="text-xs px-2 py-1 rounded-full"
      :class="item.is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'">
    {{ item.is_published ? 'Published' : 'Draft' }}
</span>
Publish toggle (single PUT)
vue
import { router } from '@inertiajs/vue3'

function publish() {
    router.put(route('teacher.lessons.update', lesson.id), { is_published: true })
}
Confirm before delete
vue
function destroy() {
    if (! confirm('Delete this? Cannot be undone.')) return
    router.delete(route('teacher.lessons.destroy', lesson.id))
}
Modal
vue
<div v-if="active" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
        <!-- content -->
    </div>
</div>
10. Critical Rules (Do Not Break)
Prop names must match the controller's array keys. If backend sends classroom, you use classroom. Mismatch = undefined prop = white screen.

Use <Link> from @inertiajs/vue3, never <a href>. <a> causes full page reloads (loses Inertia state).

Use route('name', params) from Ziggy. Never hardcode URLs like /teacher/classes/5.

Never use axios or fetch. All data comes via props. All mutations go through useForm or router.

Always import what you use. Missing imports = silent failure or console error.

No <style> blocks. Tailwind only.

Test with npm run dev running. If Vite isn't running, HMR won't apply and pages won't update.

Hard-refresh (Ctrl+Shift+R) after backend changes. Vite picks up Vue changes automatically, but not Laravel route/prop changes without a refresh.

Don't edit app.js, bootstrap.js, or ssr.js. Those are entry points.

Don't touch routes/web.php or any PHP file. Coordinate with backend teammate.

11. Testing & Dev Environment
Three terminals
bash
php artisan serve                     # backend at localhost:8000
npm run dev                           # vite dev server
Get-Content storage\logs\laravel.log -Wait   # live log tailing
Open http://localhost:8000.

Test accounts (all password: password)
Role	Email
Admin	admin@test.com
Teacher	teacher@test.com
Student	student@test.com
Test the full loop
Log in as teacher@test.com → create an assignment in a class → publish it

Open incognito window → log in as student@test.com → submit the assignment

Back as teacher → grade the submission

Back as student → see the grade

When the page is white or broken
Open browser console (F12) — 99% of issues show here

Look for: Cannot read properties of undefined, Failed to resolve component: X, Page not found: ...

Check the exact error and the file/line number

Common causes:

Prop name mismatch (controller sends classroom, Vue expects klass)

Missing import (forgot import { Link } from '@inertiajs/vue3')

Page file at wrong path (case-sensitive folder names)

Vite not running

12. AI Workflow (How to Ask for Help)
Before asking AI, gather:
The exact error from browser console or storage\logs\laravel.log

The relevant Vue file content (paste it)

The controller's response shape (ask your backend teammate, or read API_CONTRACT.md)

What you expected vs. what happened

Good AI prompt template
text
[PROJECT CONTEXT]
I'm building the frontend for a Laravel + Inertia + Vue 3 + Tailwind school LMS.
Roles: admin, teacher, student. Currently on the teacher side.
Here is the project context file: [paste AI_CONTEXT_FRONTEND.md]

[CURRENT FILE]
[Paste the Vue file you're working on]

[PROBLEM]
When I click X, Y happens. I expected Z.
Error from console: [paste exact error]

[TASK]
Help me fix this OR add feature X to this page.

[CONSTRAINTS]
- Use <script setup> + Composition API
- Use <Link> and useForm from @inertiajs/vue3
- Tailwind only
- Follow the existing card/button/table patterns from this file
- Don't touch the backend
Things to ask AI to do
Scaffold a new Vue page following an existing pattern

Debug a white screen or console error

Convert a design mockup to Vue + Tailwind

Extract a repeated UI chunk into a Component

Add form validation display

Build a modal

Style improvements

Things NOT to ask AI to do
Write backend PHP (that's your teammate's job)

Modify routes or controllers

Change the database schema

Add npm packages without asking your teammate (some break Inertia)

13. Component Extraction Rule
If the same UI block appears in 2+ pages, extract it to resources/js/Components/.

Example candidates in this project:

Status badge for assignment (draft, published, submitted, late, graded)

Empty state block

Page header with back link

Submission row in grading table

Material card

Naming: PascalCase.vue in Components/.

14. Quick Reference — Imports You'll Use
js
import { Link, useForm, router, usePage } from '@inertiajs/vue3'
import { ref, computed, watch, onMounted } from 'vue'
import TeacherLayout from '@/Layouts/TeacherLayout.vue'
import StudentLayout from '@/Layouts/StudentLayout.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
usePage() gives access to global props (user, roles, flash messages):

js
const page = usePage()
page.props.auth.user.name
page.props.flash.success   // set by controller's ->with('success', '...')
Flash messages pattern:

vue
<div v-if="$page.props.flash?.success"
     class="bg-green-50 border border-green-200 text-green-800 p-3 rounded mb-4">
    {{ $page.props.flash.success }}
</div>
15. If You're an AI Reading This
You are helping a frontend developer on this project.

Their job is Vue pages + components + Tailwind.

They do NOT write PHP, controllers, routes, or migrations.

The backend is already wired with Inertia — data comes via props.

If a feature requires backend changes, say so and suggest they coordinate with their backend teammate.

When they paste a Vue file and ask for a fix or feature:

Follow the exact same patterns the file already uses (imports, layout, Tailwind classes)

Match the role color (indigo for teacher, emerald for student)

Use <Link>, useForm, router from @inertiajs/vue3 — never <a>, axios, or fetch

Keep <script setup> structure: imports → props → form → state → computed → functions

Handle empty arrays, loading states, and errors

Don't invent new endpoints — only use route names that exist in this file's section 7

If they report a bug:

Ask for the exact console error first

Check for the common causes in section 11

Most bugs are: prop name mismatch, missing import, wrong file path, or Vite not running

If they ask to build a feature not in section 6:

Politely explain it's not built on the backend yet

Suggest they tell their backend teammate what props they'd need

Offer to build the frontend skeleton now so it's ready when the backend catches up

text

---

## 📋 How to Share This

Send your teammate:

> **Frontend AI context file:** `AI_CONTEXT_FRONTEND.md` (in the project root).
> Paste its full contents at the top of every AI conversation you have.
> It tells the AI what your project is, what's built, what patterns to follow, and what NOT to do (like touching backend).
>
> Test accounts are in section 11. If you hit a white screen, section 11 has the debug checklist. If you need a new endpoint, ping me — the backend is my side.

That file plus the **existing `AI_CONTEXT.md`** (full-stack) gives both of you consistent AI support. 🚀