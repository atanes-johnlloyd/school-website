@extends('emails.layout', ['subject' => 'Admission Accepted - Salawag SHS', 'accentColor' => '#1b5e20'])

@section('content')
    <img src="{{ $message->embed(public_path('images/check.png')) }}" width="120" alt="Success" style="margin-bottom:20px;">
    <h2>🎉 Congratulations!</h2>
    <h3>You have been accepted!</h3>
    <p>We are pleased to inform you that you have been officially accepted to <strong>Salawag Senior High School.</strong></p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Admission Information</td></tr>
        <tr><td>Student Name</td><td>{{ $student_name }}</td></tr>
        <tr><td>Control Number</td><td>{{ $control_number }}</td></tr>
        <tr><td>Examination Schedule</td><td>{{ $schedule }}</td></tr>
        <tr><td>Assigned Room</td><td>{{ $room ?: 'TBA' }}</td></tr>
        <tr><td>Strand</td><td>{{ $strand }}</td></tr>
        <tr><td>School Year</td><td>{{ $school_year }}</td></tr>
    </table>

    <div class="action-box">
        <h3>📌 Enrollment Instructions</h3>
        <ol>
            <li>Present this email together with your Control Number during your scheduled enrollment.</li>
            <li>Bring your original Report Card (SF9), Good Moral Certificate, PSA Birth Certificate, and other required documents.</li>
            <li>Arrive at least <strong>30 minutes</strong> before your scheduled time.</li>
            <li>Proceed directly to your assigned room upon arrival.</li>
            <li>Wear proper school attire and follow all school policies during enrollment.</li>
        </ol>
    </div>
@endsection