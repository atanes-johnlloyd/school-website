@extends('emails.layout', ['subject' => 'Congratulations! You Passed the Exam - Salawag SHS'])

@section('content')
    <img src="{{ $message->embed(public_path('images/check.png')) }}" width="120" alt="Success" style="margin-bottom:20px;">
    <h2>🎉 Congratulations!</h2>
    <h3>You Passed the Exam!</h3>
    <p>You have passed the entrance examination and been officially accepted as a student of <strong>Salawag Senior High School.</strong></p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📊 Exam Result</td></tr>
        <tr><td>Your Score</td><td>{{ $exam_score }} / 100</td></tr>
        <tr><td>Passing Score</td><td>{{ $passing_score }} / 100</td></tr>
        <tr><td>Result</td><td><span class="status-badge">✅ {{ $exam_result }}</span></td></tr>
    </table>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Student Information</td></tr>
        <tr><td>Student Name</td><td>{{ $student_name }}</td></tr>
        <tr><td>Control Number</td><td>{{ $control_number }}</td></tr>
        <tr><td>Grade Level</td><td>{{ $grade_level }}</td></tr>
        <tr><td>Strand</td><td>{{ $strand }}</td></tr>
        <tr><td>Section</td><td>{{ $section }}</td></tr>
        <tr><td>Adviser</td><td>{{ $adviser }}</td></tr>
        <tr><td>School Year</td><td>{{ $school_year }}</td></tr>
        <tr><td>Status</td><td><span class="status-badge">✅ Enrolled</span></td></tr>
    </table>

    <div class="action-box">
        <h3>📌 Next Steps</h3>
        <ol>
            <li>Prepare for the upcoming school year.</li>
            <li>Complete any additional requirements (if any).</li>
            <li>Attend the orientation on the scheduled date.</li>
            <li>Stay updated via email and the school website.</li>
        </ol>
    </div>
@endsection