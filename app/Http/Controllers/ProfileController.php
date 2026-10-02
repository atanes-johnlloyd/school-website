<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user  = $request->user();
        $roles = $user->getRoleNames();

        $roleLabel = 'User';
        $roleInfo  = [];

        if ($roles->contains('admin')) {
            $user->load('adminPosition:id,name,description');
            $roleLabel = $user->adminPosition?->name ?? 'Administrator';
            $roleInfo = [
                ['label' => 'Position',    'value' => $user->adminPosition?->name,        'icon' => 'user'],
                ['label' => 'Description', 'value' => $user->adminPosition?->description, 'icon' => 'info'],
            ];
        } elseif ($roles->contains('teacher')) {
            $teacher = $user->teacher()
                ->with(['user:id,name'])
                ->first();

            $roleLabel = 'Teacher';
            $roleInfo = [
                ['label' => 'Employee No.',    'value' => $teacher?->employee_no,   'icon' => 'user'],
                ['label' => 'Department',      'value' => $teacher?->department,    'icon' => 'school'],
                ['label' => 'Specialization',  'value' => $teacher?->specialization,'icon' => 'award'],
                ['label' => 'Date Hired',      'value' => $teacher?->date_hired?->toFormattedDateString(), 'icon' => 'calendar'],
                ['label' => 'Contact Number',  'value' => $teacher?->contact_number,'icon' => 'send'],
                ['label' => 'Status',          'value' => $teacher?->is_active ? 'Active' : 'Inactive', 'icon' => 'check-circle'],
            ];
        } elseif ($roles->contains('student')) {
            $student = $user->student()
                ->with([
                    'enrollments' => fn ($q) => $q->where('status', 'enrolled')
                        ->with(['section.strand', 'section.adviser.user:id,name', 'schoolYear:id,label'])
                        ->latest('enrolled_at'),
                ])
                ->first();

            $current = $student?->enrollments?->first();
            $section = $current?->section;

            $roleLabel = 'Student';
            $roleInfo = [
                ['label' => 'LRN',              'value' => $student?->lrn,                              'icon' => 'user'],
                ['label' => 'Grade Level',      'value' => $section?->grade_level ? 'Grade ' . $section->grade_level : null, 'icon' => 'academic-cap'],
                ['label' => 'Section',          'value' => $section?->name,                              'icon' => 'school'],
                ['label' => 'Strand',           'value' => $section?->strand?->name,                     'icon' => 'book-open'],
                ['label' => 'Adviser',          'value' => $section?->adviser?->user?->name,             'icon' => 'user-check'],
                ['label' => 'Contact Number',   'value' => $student?->contact_number,                    'icon' => 'send'],
                ['label' => 'School Year',      'value' => $current?->schoolYear?->label,                'icon' => 'calendar'],
                ['label' => 'Status',           'value' => $student?->status ? ucfirst($student->status) : null, 'icon' => 'check-circle'],
            ];
        }

        return Inertia::render('Profile/Edit', [
            'roleLabel' => $roleLabel,
            'roleInfo'  => $roleInfo,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
