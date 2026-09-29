@extends('emails.layout', ['subject' => 'Entrance Exam Schedule - Salawag SHS'])

@section('content')
    <img src="{{ $message->embed(public_path('images/check.png')) }}" width="120" alt="Success" style="margin-bottom:20px;">
    <h2>📅 Exam Schedule</h2>
    <h3>Your entrance exam has been confirmed</h3>
    <p>Dear <strong>{{ $student_name }}</strong>, your entrance exam is scheduled. Please take note of the details below.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Exam Details <span style="float:right;font-size:12px;">{{ $status }}</span></td></tr>
        <tr><td>Exam Name</td><td>{{ $exam_name }}</td></tr>
        <tr><td>Exam ID</td><td>{{ $exam_id }}</td></tr>
        <tr><td>School Year</td><td>{{ $school_year }}</td></tr>
        <tr><td>Grade Level</td><td>{{ $grade_level }}</td></tr>
        <tr><td>Track</td><td>{{ $track_name }}</td></tr>
        <tr><td>Schedule</td><td><strong>{{ $exam_date }} at {{ $exam_time }}</strong></td></tr>
        <tr><td>Venue</td><td>{{ $venue }}</td></tr>
        <tr><td>Total Applicants</td><td>{{ $applicant_count }}</td></tr>
    </table>
@endsection