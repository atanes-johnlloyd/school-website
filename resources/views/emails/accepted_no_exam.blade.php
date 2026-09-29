@extends('emails.layout', ['subject' => 'Admission Accepted - Salawag SHS'])

@section('content')
    <img src="{{ $message->embed(public_path('images/check.png')) }}" width="120" alt="Success" style="margin-bottom:20px;">
    <h2>🎉 Congratulations!</h2>
    <h3>You have been accepted!</h3>
    <p>We are pleased to inform you that you have been officially accepted to <strong>Salawag Senior High School.</strong></p>
    <p>Your entrance exam schedule will be sent to you once it has been set. Please wait for further instructions.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Admission Information</td></tr>
        <tr><td>Student Name</td><td>{{ $student_name }}</td></tr>
        <tr><td>Control Number</td><td>{{ $control_number }}</td></tr>
        <tr><td>Strand</td><td>{{ $strand }}</td></tr>
        <tr><td>School Year</td><td>{{ $school_year }}</td></tr>
    </table>

    <div class="notice-box">
        <p><strong>⏳ Waiting for Exam Schedule</strong></p>
        <p style="font-size:14px;">Your application is approved, but an entrance exam date has not yet been set. You will receive a separate email with your exam details once the schedule is finalized.</p>
    </div>

    <div class="action-box">
        <h3>📌 Next Steps</h3>
        <ol>
            <li>Wait for the email containing your entrance exam schedule.</li>
            <li>Prepare the required documents (Report Card, Good Moral, PSA Birth Certificate).</li>
            <li>Check your email regularly for updates.</li>
        </ol>
    </div>
@endsection