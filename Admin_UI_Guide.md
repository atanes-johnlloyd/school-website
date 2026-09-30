Admin System — Functional Spec
No UI, no colors, no layout. Just what exists, what it does, how it flows.

1. Navigation Structure
Sidebar (13 primary tabs)
text
Dashboard

MANAGEMENT
  Applications         (container — 4 sub-tabs)
  Students
  Teachers
  Admin Users

ACADEMICS
  School Years
  Terms
  Sections
  Curriculum           (container — 3 sub-tabs)
  Rooms

CONTENT
  School News
  Contact Messages

SYSTEM
  Reports
  Settings
  Audit Logs
Applications Container — Sub-tabs
text
Applications
├─ All Applications
├─ Entrance Exams
├─ Exam Records
└─ Enrollments
Curriculum Container — Sub-tabs
text
Curriculum
├─ Tracks
├─ Strands
└─ Subjects
Sidebar Visibility Rules
Each tab is gated by one or more permissions. If a user lacks the permission, the tab is not rendered.

Tab	Requires
Dashboard	role:admin
Applications (container)	manage-enrollment
Students	manage-students
Teachers	manage-teachers
Admin Users	manage-users
School Years	manage-school-years
Terms	manage-school-years
Sections	manage-sections
Curriculum (container)	manage-tracks OR manage-strands OR manage-subjects
Rooms	manage-rooms
School News	manage-announcements
Contact Messages	role:admin
Reports	view-reports
Settings	manage-settings
Audit Logs	view-audit-log
Container behavior: For Applications and Curriculum, the parent tab is visible if the user has any sub-permission. Individual sub-tabs are hidden if the user lacks their specific permission.

Example: A user with only manage-subjects:

Sidebar shows: Dashboard, Curriculum

Curriculum container opens but only shows the Subjects sub-tab

Tracks and Strands sub-tabs are hidden

2. Permission Model
Roles (Spatie)
admin — base role for all admin users

teacher — separate role group

student — separate role group

Every admin user has the admin role plus a specific position that grants permissions.

Admin Positions
Position	Grants
System Admin	All 19 permissions
Registrar	manage-students, manage-enrollment, manage-sections, view-reports
Curriculum Coordinator	manage-tracks, manage-strands, manage-subjects, manage-classes, assign-teachers, manage-class-schedules, manage-teachers, view-reports
School Head	view-reports, view-audit-log, manage-announcements
Staff	view-reports
Permission Assignment Rule
When an admin user is created or their position is changed:

The position's default_permissions array is read

Those permissions are synced directly to the user via syncPermissions()

The admin role itself grants no direct permissions

This means a user's effective permissions come entirely from their position.

Route Protection
Route middleware: Every admin route is protected by permission:xxx middleware

UI gating: Sidebar items and action buttons check auth.can before rendering

Belt and suspenders: The UI hiding is a UX convenience — the middleware is the actual gate

Special Permissions
Dashboard: Only requires role:admin — every admin sees it, even with zero position permissions

Contact Messages: Only requires role:admin — no specific permission exists for it

Curriculum container: Uses role_or_permission middleware for the container itself

3. Data Model Reference
The admin system works with these backend entities:

Entity	Table	Purpose
Applicant	applicants	Pre-enrollment pipeline
ApplicantDocument	applicant_documents	Uploaded docs per applicant
ApplicantContact	applicant_contacts	Guardian/emergency contacts
EntranceExam	entrance_exams	Scheduled exams
EntranceExamResult	entrance_exam_results	One applicant's result per exam
Student	students	Converted applicant, active learner
Enrollment	enrollments	Student-to-section-per-SY registration
Teacher	teachers	Faculty accounts
User	users	All accounts (admins, teachers, students)
AdminPosition	admin_positions	Admin subtypes with permissions
Section	sections	Class groupings (e.g., "STEM 12-A")
Track	tracks	Academic / Technical-Professional
Strand	strands	STEM, ABM, HUMSS, etc.
Subject	subjects	Curriculum subjects
Room	rooms	Physical spaces
SchoolYear	school_years	e.g., "2026-2027"
Term	terms	Semesters/quarters per SY
Announcement	announcements	Class or school-wide posts
ContactMessage	contact_messages	Public form submissions
SystemSetting	system_settings	Global config key-values
AuditLog	audit_logs	Change history
4. Entity Lifecycles
Applicant Lifecycle
text
pending
  ↓ (admin reviews)
under_review
  ↓ (admin approves or rejects)
approved  ──OR──  rejected
  ↓                  (end)
  ↓ (assigned to exam)
  ↓ (exam result recorded: Passed)
enrolled
Also possible: needs_resubmission — set by admin when documents are unusable. Applicant can re-upload and status returns to under_review.

Trigger events:

On approve → sends approval email (with or without exam assignment)

On reject → sends rejection email with reason

On request_resubmission → sends resubmission email with status link

On exam result = Passed → runs ApplicantConversionService → creates User + Student + Enrollment

On exam result = Failed / Absent → sends appropriate email

Enrollment Lifecycle
text
pending  (created by conversion with no section assigned)
  ↓ (admin assigns section + approves)
enrolled
  ↓ (student leaves)
dropped  /  transferred  /  completed
Unique constraint: One enrollment per student per school year (soft-delete aware).

Entrance Exam Lifecycle
text
Upcoming → Ongoing (during 4-hour session window) → Completed
         ↘ Cancelled (at any time before completion)
Cancel behavior: All assigned applicants are moved to the next available exam, or unassigned if none exists.

Exam Result Lifecycle
text
Pending → Passed / Failed / Absent / For Interview
Results can only be recorded when the exam is Ongoing or Completed. Cannot be recorded for future exams.

5. Page Specifications
5.1 Dashboard
Route: GET /admin/dashboard
Permission: role:admin

Purpose
Executive overview. Shows the school's operational state at a glance.

Data Displayed
Total applicants for current SY

Pending review count

Enrolled students count

Upcoming exam (next one) — name + date

Total sections + occupancy %

Unread contact messages count

Applicant status breakdown (chart data)

Monthly application trend (chart data)

5 most recent applications

3 next upcoming exams

Action items list

Actions
Click any KPI → navigates to filtered list page

Click recent applicant → navigates to applicant detail

Click upcoming exam → navigates to exam detail

Click action item → navigates to relevant page

Backend Status
Admin\DashboardController@index is a stub. Needs to return the stats listed above.

Loading/Empty/Error
Loading: skeleton on all cards

Empty: "0" on KPIs, "Everything's clear" on action items

Error: show retry button on failed sections

5.2 All Applications (Sub-tab 1)
Route: GET /admin/applicants
Permission: manage-enrollment

Purpose
The primary work surface for processing incoming applications.

Data Displayed
Table with columns: Reference #, Full Name, LRN, Strand, Grade, Applied Date, Status.

Counts per status shown as clickable filters.

Actions
Search — reference, LRN, first/last name, email

Filter by status — pending / under_review / approved / rejected / enrolled / needs_resubmission

Filter by school year — defaults to active

Filter by strand

Reset filters

Row click → navigate to Applicant Detail

Export CSV → triggers GET /admin/exports/applicants

Pagination
20 per page. Filters preserved on page change.

Empty State
"No applicants match your filters."

5.3 Applicant Detail
Route: GET /admin/applicants/{id}
Permission: manage-enrollment

Purpose
Review a single applicant and take action.

Data Displayed
Full applicant info (personal, address, previous school, desired program)

Contacts (father, mother, guardian, emergency)

Documents list with verification status

Exam status (score, result, exam name, date)

Activity timeline (from audit logs)

Actions
Available actions depend on current status:

Current status	Available actions
pending	Mark Under Review, Approve, Reject, Request Resubmission, Assign to Exam
under_review	Approve, Reject, Request Resubmission, Assign to Exam
approved	Assign to Exam, Record Result (if exam assigned)
rejected	View only
needs_resubmission	Wait for resubmission
enrolled	View only (redirect to Student Detail available)
Document actions (per document, always available):

Download

Verify (with remarks)

Reject (with remarks)

Approval modal behavior:

Optional note field

Warns if no exam is available for auto-assignment

On confirm → sends approval email, updates status

Reject modal behavior:

Reason field required

On confirm → sends rejection email, updates status

Request Resubmission modal behavior:

Reason field required

On confirm → sends resubmission email with link to status page

Navigation
Back to Applicants list

If enrolled: link to Student Detail page

5.4 Entrance Exams (Sub-tab 2)
Route: GET /admin/entrance-exams
Permission: manage-enrollment

Purpose
Schedule and manage entrance exams.

Data Displayed
Table: Exam Name, Date, Time, Venue, Grade Level, Track, Applicant Count (X/max), Status, Remaining Capacity.

KPI cards: Total, Upcoming, Ongoing, Completed, Cancelled.

Actions
Search by exam name

Filter by grade level (11 / 12 / All)

Filter by status (Upcoming/Ongoing/Completed/Cancelled)

Filter by school year

New Exam → opens create form

Row click → Exam Detail

Edit → edit form

Cancel → confirm dialog explaining consequences

Delete → blocked if applicants assigned

Create/Edit Form
Fields: School Year, Track (nullable), Exam Name, Exam Date, Exam Time, Venue, Max Capacity, Grade Level.

Validation: Exam Date must be today or later (for new exams).

Auto-assignment: If exam date is 7+ days away, approved applicants without an assigned exam are auto-assigned.

Cancel Behavior
Confirm dialog content must state:

How many applicants will be moved to another exam

How many will be unassigned if no other exam is available

On confirm → moves applicants → updates status → sends notification emails.

Empty State
"No exams scheduled."

5.5 Exam Detail
Route: GET /admin/entrance-exams/{id}
Permission: manage-enrollment

Purpose
Manage the roster of applicants assigned to a specific exam and record their results.

Data Displayed
Exam metadata (name, date, time, venue, grade level, track, capacity)

Capacity meter (X assigned / max)

Assigned applicants table: Reference #, Name, LRN, Grade, Strand, Score, Result, Remarks, Recorded At

Actions
Edit Exam → edit form

Cancel Exam → same as list view

Add Applicants → opens picker with eligible applicants (approved, not yet assigned, no final result)

Remove Applicant → per-row action

Record Result — inline editing on Score, Result, Remarks columns

Export Roster → CSV

Inline Result Recording
Score: numeric input, editable
Result: dropdown (Pending / Passed / Failed / Absent / For Interview)
Remarks: text input

On save:

If result changes to Passed → conversion service runs → creates User + Student + Enrollment

Toast notification: "Applicant converted to student. Enrolled in Section X."

Email sent to applicant

Timestamp: Recorded At and Recorded By set on first save.

Add Applicants Modal
Search + filter eligible applicants

Multi-select

Warns if adding exceeds capacity

On confirm → creates EntranceExamResult rows with Pending status

Sends notification email to each new assignee

Empty State
"No applicants assigned yet. Click 'Add Applicants' to begin."

5.6 Exam Records (Sub-tab 3)
Route: GET /admin/exam-records
Permission: manage-enrollment

Status: New page — controller index() method needs to be added.

Purpose
Global list of all recorded exam results across all exams. For searching and reporting.

Data Displayed
Table: Applicant, Reference #, LRN, Exam Name, Date, Score, Result, Recorded At.

KPI cards: Total Records, Passed, Failed, Absent, Pending, Pass Rate %.

Actions
Filter by exam

Filter by result (all / Passed / Failed / Absent / Pending / For Interview)

Filter by grade level

Filter by strand

Filter by date range

Search by name, LRN, reference #

Row click → navigates to Applicant Detail

Edit Result → navigates to Exam Detail focused on that row

Export CSV

Empty State
"No exam records yet. Record results from the Entrance Exams tab."

Backend Work Needed
Add to Admin\EntranceExamResultController:

php
public function index(Request $request)
{
    // filters: exam_id, result, grade_level, strand_id, date_from, date_to, search
    // returns: paginated results + stats
}
Add route in web.php:

php
Route::get('/exam-records', [EntranceExamResultController::class, 'index'])
    ->name('exam-records.index');
5.7 Enrollments (Sub-tab 4)
Route: GET /admin/enrollments
Permission: manage-enrollment

Purpose
Manage student-to-section assignment approvals. Catches enrollments created without a section (e.g., from converted applicants when no section had capacity).

Data Displayed
Table: Student (LRN + name), School Year, Section (or "Unassigned"), Status, Enrolled At, Enrolled By.

Actions
Filter by status (pending / enrolled / dropped / transferred / completed)

Filter by school year

Filter by section

Approve (pending only) → sets status to enrolled, records approver + timestamp

Reject (pending only) → sets status to dropped

Assign Section (unassigned only) → opens section picker

Row click → Student Detail

Bulk Approve → multi-select then approve all

Assign Section Modal
Pick from available sections (matching student's strand + grade level)

Shows capacity of each section

On confirm → updates enrollment, attaches student to all classes in that section

Empty State
"No enrollments match your filters."

5.8 Students
Route: GET /admin/students
Permission: manage-students

Purpose
Manage student accounts.

Data Displayed
Table: LRN, Name, Email, Sex, Grade Level (from current SY), Section, Status.

Actions
Search by name, email, LRN

Filter by status (active / graduated / dropped_out / transferred_out)

New Student → full-page form

Row click → Student Detail

Edit → edit form

Reset Password → generates temp password → modal with copyable value + "Send via email" option

Deactivate → soft-deactivate (soft delete + disable user account)

Export CSV

Create Form Fields
Account: Name, Email, Password (auto-generate option)
Personal: LRN (12 digits), Sex, Date of Birth
Contact: Contact Number
Address: House/Street, Barangay, Municipality, Province, Zip

Note: New student accounts get must_change_password = true.

Deactivate Behavior
Confirm dialog: "This will disable their login. Academic records remain intact."

On confirm:

Update student.status to transferred_out

Update user.status to disabled

Soft-delete the student record

Reset Password Behavior
Generates 16-char temp password. Modal displays it with copy button. Optionally sends via email.

Empty State
"No students match your filters."

5.9 Student Detail
Route: GET /admin/students/{id}
Permission: manage-students

Purpose
Full profile of a single student.

Data Displayed
Tabs:

Personal Info — DOB, Sex, Contact, Address

Enrollment History — per school year: SY label, section, status, enrolled at

Grades — per class: subject, WW/PT/QE scores, final grade, remarks

Attendance — summary + monthly grid

Documents — uploaded docs (COR, Form 137, etc.)

Actions
Edit student info

Reset Password

Deactivate

Download Report Card → PDF download via GET /reports/students/{id}/report-card

Back to Students list

Navigation
From Enrollments tab: opens Student Detail for that enrollment

5.10 Teachers
Route: GET /admin/teachers
Permission: manage-teachers

Purpose
Manage faculty accounts.

Data Displayed
Table: Employee #, Name, Email, Department, Specialization, Active Status, Must Change Password indicator.

Actions
Search by name, email, employee #, department

Filter by active status

New Teacher → full-page form

Edit

Reset Password → same behavior as students

Deactivate → disable user + soft-delete teacher

Export CSV

Create Form Fields
Account: Name, Email, Password, Admin Position (optional — lets teacher also act as admin)
Personal: Sex, Date of Birth, Contact Number
Employment: Employee Number, Date Hired, Department, Specialization

Note: New teacher accounts get must_change_password = true.

5.11 Admin Users
Route: GET /admin/users
Permission: manage-users

Purpose
Manage admin/staff accounts and their permissions.

Data Displayed
Table: Name, Email, Position, Status, Disabled Reason (if disabled), Created Date.

KPI cards: Total, Active, Disabled.

Actions
Search by name or email

Filter by status

Filter by position

New Admin → full-page form

View → Admin User Detail

Edit

Toggle Status → enable/disable

Revoke Admin → remove admin role + disable

Create Form Fields
Name

Email (unique)

Admin Position (required — determines permissions)

Auto-generated: Password, reset token, sent via email.

On create: Position's permissions are synced to the new user.

Toggle Status Behavior
Disable:

Requires reason (modal)

Updates user status to disabled

Sends disable email with reason

Enable:

Confirm dialog

Updates status to active, clears reason

Sends reactivation email

Guards
The UI must hide/disable these actions:

Cannot disable yourself

Cannot disable user ID 1 (primary admin)

Cannot revoke your own admin access

Cannot revoke user ID 1

If action is attempted (via API), backend returns 422.

Revoke Admin Behavior
Updates status to disabled

Records disabled reason

Removes admin role

Syncs empty permissions

Confirmation dialog warns this is destructive

5.12 Admin User Detail
Route: GET /admin/users/{id}
Permission: manage-users

Purpose
Full profile of an admin account, showing their effective permissions.

Data Displayed
Account info (name, email, status, created, last updated)

Position

Roles assigned

Effective permissions — list of permission names

Recent activity (from audit logs, optional)

Actions
Edit

Toggle Status

Revoke Admin

Back to list

5.13 School Years
Route: GET /admin/school-years
Permission: manage-school-years

Purpose
Manage academic years.

Data Displayed
Table: Label, Start Date, End Date, Active Status.

Actions
New School Year → modal

Edit

Activate → deactivates all others, activates this one

Delete → blocked if SY has terms, sections, or enrollments

Create Form Fields
Label (unique, e.g., "2026-2027")

Start Date

End Date (after start)

Is Active (toggle)

Activating one SY automatically deactivates others (single active SY rule).

Activate Confirmation
Warns which SY will be deactivated. On confirm → updates all rows.

Delete Blocked Behavior
If SY has linked records → returns 422 → toast: "Cannot delete. Year has linked terms, sections, or enrollments."

5.14 Terms
Route: GET /admin/terms
Permission: manage-school-years

Purpose
Manage semesters/quarters within school years.

Data Displayed
Table: Name, School Year, Start Date, End Date, Active Status.

Actions
Filter by school year (defaults to active)

New Term → modal

Edit

Activate → deactivates all other terms globally

Delete → blocked if term has linked classes

Create Form Fields
School Year (dropdown)

Name (e.g., "1st Semester", "Q1")

Start Date

End Date (after start)

Is Active (toggle)

Single active term rule applies globally across all school years.

5.15 Sections
Route: GET /admin/sections
Permission: manage-sections

Purpose
Manage class sections (e.g., "STEM 12-A") and student assignment.

Data Displayed
Table/grid: Name, Grade Level, Strand, School Year, Adviser, Capacity (X/max), Enrolled Count.

Occupancy indicator per section.

Actions
Filter by school year

Filter by grade level

Filter by strand

Search by name

New Section → modal

Edit

View Roster → Section Detail

Enroll Student → opens student picker

Delete → blocked if students enrolled

Create Form Fields
School Year

Strand (nullable)

Grade Level (11/12)

Name (unique within SY + grade)

Adviser (dropdown, optional)

Max Capacity (default 40)

Enroll Student Modal
Search students not yet enrolled in this SY

Shows grade level and strand to help match

Multi-select

On confirm → creates Enrollment + attaches student to all classes in the section

Capacity check: if adding would exceed, warn before confirming

Section Detail
Header: name, grade, strand, adviser, SY, capacity meter

Enrolled students table: LRN, Name, Email, Enrolled At, Remove action

Remove Student → soft-deletes enrollment, detaches from all section classes

Sub-tabs: Classes (all classes in this section), Schedule

5.16 Tracks (Curriculum Sub-tab 1)
Route: GET /admin/tracks
Permission: manage-tracks

Purpose
Manage SHS tracks (Academic, Technical-Professional).

Data Displayed
Grid: Code, Name, Description, Strand Count, Active Status.

Actions
New Track → modal (3 fields)

Edit

Manage Image → upload/replace/remove track image

Manage Meta → set icon name and color

Toggle Active

Delete → blocked if track has strands

Create Form Fields
Code (unique, max 10)

Name

Description

Is Active

Image Modal
Drop zone for upload

Current preview

Remove button

Hint: "Recommended 800×800"

Meta Modal
Icon (Bootstrap icon name)

Color (hex value)

5.17 Strands (Curriculum Sub-tab 2)
Route: GET /admin/strands
Permission: manage-strands

Purpose
Manage strands (STEM, ABM, HUMSS, etc.). Legacy calls these "Elective Clusters".

Data Displayed
Grid: Code, Name, Parent Track, Subject Count, Section Count, Active Status.

Actions
Filter by track

New Strand → modal

Edit

Manage Image

Manage Meta

Toggle Active

Delete → blocked if strand has subjects or sections

Create Form Fields
Track (required)

Code (unique)

Name

Description

Is Active

5.18 Subjects (Curriculum Sub-tab 3)
Route: GET /admin/subjects
Permission: manage-subjects

Purpose
Manage curriculum subjects.

Data Displayed
Table: Image thumb, Code, Name, Strand, Grade Level, Hours, Is Core, Prerequisite, Active Status.

Actions
Filter by strand

Filter by grade level (11 / 12 / Both)

Filter by is_core (toggle)

Search by name or code

New Subject → full-page form

Edit

Manage Image

Manage Meta (icon, color)

Delete → blocked if used in classes

Create Form Fields
Code (unique)

Name

Description

Strand (nullable for core subjects)

Grade Level (11 / 12 / both)

Hours (default 80)

Is Core (toggle)

Prerequisite Subject (dropdown, optional)

Is Active

Validation: Prerequisite cannot be the subject itself.

5.19 Rooms
Route: GET /admin/rooms
Permission: manage-rooms

Purpose
Manage physical rooms.

Data Displayed
Table: Code, Name, Building, Floor, Capacity, Type, Active Status.

Actions
Filter by type

Filter by building

New Room → modal

Edit

Delete → blocked if used in class schedules

Create Form Fields
Code (unique)

Name

Building (nullable)

Floor (nullable)

Capacity (default 40)

Type (dropdown: regular / laboratory / workshop / lecture / computer_lab / science_lab)

Is Active

5.20 School News
Route: GET /admin/school-news
Permission: manage-announcements

Purpose
Manage school-wide announcements (visible to public + all users).

Data Displayed
Grid: Image, Title, Author, Published Date, Pin status, Draft/Published status.

Actions
Filter by status (Published / Draft / Pinned)

New Announcement → full-page form

Edit

Toggle Publish

Toggle Pin

Delete

Create Form Fields
Title

Body (rich text)

Image (optional upload)

Is Pinned (toggle)

Is Published (toggle)

Expires At (date picker, optional)

Behavior: Drafts have published_at = null. Published sets it to now.

5.21 Contact Messages
Route: GET /admin/contact-messages
Permission: role:admin

Purpose
Inbox for messages from the public contact form.

Data Displayed
Table: From (name + email), Subject, Message Preview, Received Date, Read Status.

Actions
Filter by read status (All / Unread / Read)

Search across name, email, subject, message

Row click → Message Detail

Toggle Read → mark as read/unread

Delete

Message Detail
From: name, email (clickable mailto)

Subject

Full message body

Received timestamp

Auto-behavior: Opening a message marks it as read.

Empty State
"No messages."

5.22 Reports
Route: GET /admin/reports
Permission: view-reports

Purpose
Analytics dashboard across all modules.

Data Displayed
13 sections:

Applicant Statistics — total, pending, under review, approved, rejected, enrolled

Student Statistics — total, active, graduated, dropped, transferred

Teacher Statistics — total, active, inactive

Section Statistics — sections, capacity, enrolled, available, occupancy %

Exam Statistics — total, upcoming, ongoing, completed, cancelled

Exam Result Summary — total results, passed, failed, absent, pending, pass rate %

Monthly Trend — applications per month across SY

Strand Distribution — applicants grouped by strand

Section Occupancy — each section's fill %

Grade Summary — total graded, passing, failing, average, passing rate

Attendance Summary — records, present, absent, late, excused

Recent Applications — 10 most recent

Recent Enrollments — 10 most recent

Upcoming Exams — next 5

Actions
Filter by school year (defaults to active)

Filter by track (optional)

Export CSV — dropdown for: enrollments, applicants, students, teachers

Backend Status
Admin\ReportController@index is fully implemented. Returns all sections.

5.23 Settings
Route: GET /admin/settings
Permission: manage-settings

Purpose
Edit system-wide configuration.

Data Displayed
Grouped settings:

School: name, address, email, phone, principal name

Academic: passing grade %, default max class size

System: announcement auto-hide days, max file upload MB, contact email visible flag

Actions
Save Changes → bulk updates all settings

Reset [group] → per-group reset to defaults

Behavior
Settings stored as key-value pairs in system_settings table

Cache invalidated on update

Unknown keys silently ignored on update (whitelist enforcement)

5.24 Audit Logs
Route: GET /admin/audit-logs
Permission: view-audit-log

Purpose
View history of all model changes for compliance and debugging.

Data Displayed
Table: Date/Time, User, Action, Model Type, Model ID, IP Address.

Actions
Filter by action (created / updated / deleted / restored)

Filter by user

Filter by model type

Filter by date range

Search across action, model, IP, user name

Row click → Log Detail

Log Detail
User info

Full model namespace and ID

Old Values (formatted view)

New Values (formatted view)

IP Address

User Agent

Full timestamp

Notes
old_values should have sensitive fields (passwords, tokens) redacted by the Auditable trait

Audit logs are read-only

Pagination: 30 per page

6. Modal vs Page Decision Rules
Use a modal when:

Form has ≤6 fields

Action is a simple confirmation

Content is a single-purpose quick edit

User will return to the same list afterward

Use a full page when:

Form has >6 fields

Content has rich editing (rich text, multi-step)

Form has multiple sections with grouping

User will navigate away from the list context

Modal actions (per page):

Delete confirmations (all pages)

Toggle status (with reason)

Image upload

Meta edit (icon + color)

Quick create: Track, Strand, Room, School Year, Term

Enroll student to section

Page actions:

Create/Edit Student

Create/Edit Teacher

Create/Edit Admin User

Create/Edit Subject

Create/Edit School News

Applicant Detail

Exam Detail

Section Detail

Student Detail

Admin User Detail

7. Notification Patterns
Inline Form Errors
Displayed below field. Red text with error message. Field gets red border. aria-invalid="true".

Toasts (Transient)
Auto-dismiss after 4 seconds. Max 3 stacked.

Used for:

Successful actions: "Applicant approved"

Info messages: "Applicant converted to student and enrolled in Section X"

Failed actions that aren't field-specific: "Cannot delete — section has enrolled students"

Alert Banners (Page-Level)
Sticky at top of content. Persists until dismissed.

Used for:

Warning conditions: "You have unsaved changes"

Long-running notifications: "Import in progress"

Confirmation Modals
Blocking. Not dismissible by backdrop click.

Used for:

Destructive actions (delete, revoke, disable)

Actions that send email

Actions that affect other users

Must state: what will change, what's reversible, what's not.

8. Loading, Empty, Error States
Loading
Tables: skeleton rows (5-8 rows with animate-pulse)

Card grids: skeleton cards matching real dimensions

Buttons submitting: spinner + disabled

Full page loading: page-level skeleton

Empty
Every list has a specific empty message:

Applicants: "No applicants match your filters."

Exams: "No exams scheduled."

Exam Records: "No exam records yet."

Enrollments: "No enrollments match your filters."

Students: "No students match your filters."

Teachers: "No teachers match your filters."

Users: "No admin users match your filters."

Sections: "No sections yet."

Tracks: "Add your first track."

Strands: "No strands yet for this track."

Subjects: "No subjects yet."

Rooms: "No rooms yet."

School News: "No announcements yet."

Contact Messages: "No messages."

Error
404: "The [entity] doesn't exist or was deleted."

403: "You don't have permission to access this page."

500: "Something went wrong. Try again." + Retry button

Network: offline banner

9. Concurrency and Race Conditions
Applicant Conversion
Two admins clicking "Approve + Passed" simultaneously → race condition. Backend uses lockForUpdate() on the applicant row inside the transaction. Second click returns "Already converted."

Quiz Retake
Covered in teacher spec. Not applicable here.

Section Enrollment
Two admins enrolling students into the same section simultaneously → capacity check might allow overshooting. Backend uses transaction + capacity check.

Exam Auto-Assignment
Two admins creating exams 7+ days out → both auto-assign applicants. First exam wins, second auto-assignment filters out already-assigned applicants.

10. Bulk Operations
Currently supported:

Enrollments: bulk approve (multi-select)

Exports: full dataset CSV

Not currently supported:

Bulk delete (by design — usually risky)

Bulk edit

Bulk assign

If needed later, they follow the pattern: select rows → action button appears → confirm modal shows count → execute → toast with result.

11. Search and Filter Persistence
All list pages preserve filters:

Search text

Filter dropdowns

Pagination page

Sort direction (where applicable)

Implemented via Inertia's router.get with preserveState: true.

URL reflects current filters (e.g., /admin/applicants?status=pending&search=reyes&page=2). Users can bookmark or share filtered URLs.

12. Permission Edge Cases
User with Zero Position Permissions
If an admin exists with admin_position_id = null and no direct permissions:

They see only Dashboard and Contact Messages (both gated by role:admin only)

Everything else returns 403

This is intentional — some admins might exist for policy reasons but not need module access.

User with Only Sub-Tab Permission
An admin with only manage-subjects:

Sidebar shows: Dashboard, Curriculum

Curriculum container opens on Subjects sub-tab

Tracks and Strands sub-tabs are hidden

Direct URL to /admin/tracks returns 403

Direct URL Access Without Permission
Middleware returns 403. Frontend shows a permission error page with "Return to Dashboard" button.

Permission Changes Take Effect Immediately
Spatie caches permissions for 24 hours. On user update, cache is invalidated. If cache isn't clearing, app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions() needs to run.

13. Backend Endpoints Summary
Complete list of admin endpoints with method, route, controller, permission.

Applications
Method	Route	Controller	Permission
GET	/admin/applicants	ApplicantController@index	manage-enrollment
GET	/admin/applicants/{id}	ApplicantController@show	manage-enrollment
PUT	/admin/applicants/{id}/under-review	markUnderReview	manage-enrollment
PUT	/admin/applicants/{id}/approve	approve	manage-enrollment
PUT	/admin/applicants/{id}/reject	reject	manage-enrollment
PUT	/admin/applicants/{id}/request-resubmission	requestResubmission	manage-enrollment
GET	/admin/applicant-documents/{id}/download	downloadDocument	manage-enrollment
PUT	/admin/applicant-documents/{id}/verify	verifyDocument	manage-enrollment
Entrance Exams
Method	Route	Controller	Permission
GET	/admin/entrance-exams	index	manage-enrollment
POST	/admin/entrance-exams	store	manage-enrollment
GET	/admin/entrance-exams/{id}	show	manage-enrollment
PUT	/admin/entrance-exams/{id}	update	manage-enrollment
DELETE	/admin/entrance-exams/{id}	destroy	manage-enrollment
PUT	/admin/entrance-exams/{id}/cancel	cancel	manage-enrollment
GET	/admin/entrance-exams/{id}/eligible-applicants	eligibleApplicants	manage-enrollment
POST	/admin/entrance-exams/{id}/assign	assign	manage-enrollment
POST	/admin/entrance-exams/{id}/remove-applicant	removeApplicant	manage-enrollment
Exam Records
Method	Route	Controller	Permission
GET	/admin/exam-records	EntranceExamResultController@index	manage-enrollment
PUT	/admin/exam-results/{id}	update	manage-enrollment
Enrollments
Method	Route	Controller	Permission
GET	/admin/enrollments	EnrollmentController@index	manage-enrollment
PUT	/admin/enrollments/{id}/approve	approve	manage-enrollment
PUT	/admin/enrollments/{id}/reject	reject	manage-enrollment
Students
Method	Route	Controller	Permission
GET	/admin/students	StudentController@index	manage-students
POST	/admin/students	store	manage-students
GET	/admin/students/{id}	show	manage-students
PUT	/admin/students/{id}	update	manage-students
DELETE	/admin/students/{id}	destroy	manage-students
POST	/admin/students/{id}/reset-password	resetPassword	manage-students
Teachers
Method	Route	Controller	Permission
GET	/admin/teachers	TeacherController@index	manage-teachers
POST	/admin/teachers	store	manage-teachers
GET	/admin/teachers/{id}	show	manage-teachers
PUT	/admin/teachers/{id}	update	manage-teachers
DELETE	/admin/teachers/{id}	destroy	manage-teachers
Admin Users
Method	Route	Controller	Permission
GET	/admin/users	UserController@index	manage-users
POST	/admin/users	store	manage-users
GET	/admin/users/{id}	show	manage-users
PUT	/admin/users/{id}	update	manage-users
PUT	/admin/users/{id}/toggle-status	toggleStatus	manage-users
DELETE	/admin/users/{id}	destroy	manage-users
School Years / Terms
Method	Route	Controller	Permission
GET/POST/PUT/DELETE	/admin/school-years/*	SchoolYearController	manage-school-years
PUT	/admin/school-years/{id}/activate	activate	manage-school-years
GET/POST/PUT/DELETE	/admin/terms/*	TermController	manage-school-years
Sections
Method	Route	Controller	Permission
GET/POST/PUT/DELETE	/admin/sections/*	SectionController	manage-sections
POST	/admin/sections/{id}/enroll	enrollStudent	manage-sections
DELETE	/admin/sections/{id}/students/{sid}	removeStudent	manage-sections
Curriculum
Method	Route	Controller	Permission
GET/POST/PUT/DELETE	/admin/tracks/*	TrackController	manage-tracks
POST/DELETE	/admin/tracks/{id}/image	TrackImageController	manage-tracks
GET/POST/PUT/DELETE	/admin/strands/*	StrandController	manage-strands
POST/DELETE	/admin/strands/{id}/image	StrandImageController	manage-strands
GET/POST/PUT/DELETE	/admin/subjects/*	SubjectController	manage-subjects
POST/DELETE	/admin/subjects/{id}/image	SubjectImageController	manage-subjects
PUT	/admin/subjects/{id}/meta	setMeta	manage-subjects
Rooms
Method	Route	Controller	Permission
GET/POST/PUT/DELETE	/admin/rooms/*	RoomController	manage-rooms
School News
Method	Route	Controller	Permission
GET/POST/PUT/DELETE	/admin/school-news/*	SchoolWideAnnouncementController	manage-announcements
Contact Messages
Method	Route	Controller	Permission
GET	/admin/contact-messages	ContactController@index	role:admin
GET	/admin/contact-messages/{id}	show	role:admin
PUT	/admin/contact-messages/{id}/read	toggleRead	role:admin
DELETE	/admin/contact-messages/{id}	destroy	role:admin
Reports / Exports
Method	Route	Controller	Permission
GET	/admin/reports	ReportController@index	view-reports
GET	/admin/exports/enrollments	ExportController@enrollments	view-reports
GET	/admin/exports/applicants	ExportController@applicants	view-reports
GET	/admin/exports/students	ExportController@students	view-reports
GET	/admin/exports/teachers	ExportController@teachers	view-reports
Settings
Method	Route	Controller	Permission
GET	/admin/settings	SystemSettingController@index	manage-settings
PUT	/admin/settings	update	manage-settings
POST	/admin/settings/reset	reset	manage-settings
Audit Logs
Method	Route	Controller	Permission
GET	/admin/audit-logs	AuditLogController@index	view-audit-log
GET	/admin/audit-logs/{id}	show	view-audit-log
Report Cards
Method	Route	Controller	Permission
GET	/reports/students/{id}/report-card	ExportController@reportCard	auth (admin or self)
14. Backend Work Required
Before all admin pages are fully functional:

Item	Priority
Extend Admin\DashboardController@index to return stats	Required for Dashboard
Add EntranceExamResultController@index for Exam Records tab	Required for new sub-tab
Add exam-records.index route	Required for new sub-tab
Sync position.default_permissions → user permissions on create/update	Required for permission system
Remove admin role's permissions	Required for permission system
Sync existing admins from positions	One-time data fix
Redact sensitive fields in Auditable trait	Security concern
Everything else is complete and consuming-ready.

That's the complete functional spec for the admin system. No design. Just behavior, navigation, data flow, and rules.