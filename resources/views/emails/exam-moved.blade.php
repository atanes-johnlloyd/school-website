@extends('emails.layout', ['subject' => 'Exam Schedule Updated - Salawag SHS', 'accentColor' => '#f59e0b'])

@section('content')
    <img src="{{ $message->embed(public_path('images/info.png')) }}" width="120" alt="Info" style="margin-bottom:20px;">
    <h2 style="color:#f59e0b;">📅 Exam Schedule Updated</h2>
    <h3>Your entrance exam has been moved</h3>
    <p>Dear <strong>{{ $student_name }}</strong>, your exam schedule has been changed due to administrative updates.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 New Exam Details</td></tr>
        <tr><td>Exam Name</td><td>{{ $exam_name }}</td></tr>
        <tr><td>Date & Time</td><td><strong>{{ $exam_date }} at {{ $exam_time }}</strong></td></tr>
        <tr><td>Venue</td><td>{{ $venue }}</td></tr>
    </table>

    <div class="notice-box">
        <p><strong>⚠️ Important:</strong> Please take note of your new exam schedule. If you have questions, contact our admissions office.</p>
    </div>
@endsection