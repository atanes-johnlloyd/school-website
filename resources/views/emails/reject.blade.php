@extends('emails.layout', ['subject' => 'Application Status - Salawag SHS', 'accentColor' => '#dc2626'])

@section('content')
    <img src="{{ $message->embed(public_path('images/reject.png')) }}" width="120" alt="Rejected" style="margin-bottom:20px;">
    <h2 style="color:#dc2626;">❌ Application Status</h2>
    <h3 style="color:#dc2626;">We regret to inform you</h3>
    <p>Your application to <strong>Salawag Senior High School</strong> has been <span style="color:#dc2626;font-weight:bold;">rejected</span>.</p>

    <div class="notice-box" style="background:#fef2f2;border-left-color:#dc2626;">
        <p style="color:#991b1b;"><strong>📋 Reason for Rejection</strong></p>
        <p style="color:#991b1b;">{{ $reason }}</p>
        <p style="color:#991b1b;margin-top:10px;">If you have questions, please contact our admissions office.</p>
    </div>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Applicant Information</td></tr>
        <tr><td>Student Name</td><td>{{ $student_name }}</td></tr>
        <tr><td>Control Number</td><td>{{ $control_number }}</td></tr>
        <tr><td>Strand</td><td>{{ $strand }}</td></tr>
        <tr><td>School Year</td><td>{{ $school_year }}</td></tr>
        <tr><td>Status</td><td><span class="status-badge" style="background:#fee2e2;color:#dc2626;">Rejected</span></td></tr>
    </table>

    <div class="action-box">
        <h3>📌 What You Can Do</h3>
        <ol>
            <li>Review the reason for rejection and address any issues.</li>
            <li>Contact our admissions office for clarification or re-evaluation.</li>
            <li>Consider applying again in the next school year.</li>
        </ol>
    </div>
@endsection