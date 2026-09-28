<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with([
            'student.user:id,name,email',
            'section.strand:id,code,name',
            'schoolYear:id,label',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('school_year_id')) {
            $query->where('school_year_id', $request->input('school_year_id'));
        }
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->input('section_id'));
        }

        return response()->json([
            'enrollments' => $query->orderByDesc('created_at')->paginate(20),
        ]);
    }

    public function approve(Request $request, Enrollment $enrollment)
    {
        if ($enrollment->status !== 'pending') {
            return response()->json(['message' => 'Only pending enrollments can be approved.'], 422);
        }

        $enrollment->update([
            'status'      => 'enrolled',
            'enrolled_at' => now(),
            'enrolled_by' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Enrollment approved.', 'enrollment' => $enrollment]);
    }

    public function reject(Request $request, Enrollment $enrollment)
    {
        $enrollment->update(['status' => 'dropped']);

        return response()->json(['message' => 'Enrollment rejected.', 'enrollment' => $enrollment]);
    }
}