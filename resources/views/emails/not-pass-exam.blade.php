@extends('emails.layout', ['subject' => 'Exam Result - Salawag SHS', 'accentColor' => '#dc2626'])

@section('content')
    <img src="{{ $message->embed(public_path('images/reject.png')) }}" width="120" alt="Failed" style="margin-bottom:20px;">
    <h2 style="color:#dc2626;">❌ Exam Result</h2>
    <h3 style="color:#dc2626;">We regret to inform you</h3>
    <p>You did not meet the required passing score for the <strong>Salawag Senior High School</strong> entrance examination.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📊 Exam Result</td></tr>
        <tr><td>Your Score</td><td>{{ $exam_score }} / 100</td></tr>
        <tr><td>Passing Score</td><td>{{ $passing_score }} / 100</td></tr>
        <tr><td>Result</td><td><span class="status-badge" style="background:#fee2e2;color:#dc2626;">FAILED</span></td></tr>
    </table>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Applicant Information</td></tr>
        <tr><td>Student Name</td><td>{{ $student_name }}</td></tr>
        <tr><td>Control Number</td><td>{{ $control_number }}</td></tr>
        <tr><td>Exam Taken</td><td>{{ $exam_name }}</td></tr>
        <tr><td>Strand</td><td>{{ $strand }}</td></tr>
    </table>

    <div class="action-box">
        <h3>📌 What You Can Do</h3>
        <ol>
            <li>Review and prepare for the next entrance examination.</li>
            <li>Consider other tracks or strands that may suit your skills.</li>
            <li>Visit our admissions office for guidance and counseling.</li>
            <li>Re-apply for the next school year with better preparation.</li>
        </ol>
    </div>
@endsection