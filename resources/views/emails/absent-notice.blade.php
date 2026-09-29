@extends('emails.layout', ['subject' => 'Absent from Exam - Salawag SHS', 'accentColor' => '#f59e0b'])

@section('content')
    <img src="{{ $message->embed(public_path('images/info.png')) }}" width="120" alt="Info" style="margin-bottom:20px;">
    <h2 style="color:#f59e0b;">⚠️ Absent from Exam</h2>
    <h3 style="color:#92400e;">We noticed your absence</h3>
    <p>You were marked <strong>absent</strong> for the <strong>{{ $exam_name }}</strong> at <strong>Salawag Senior High School</strong>.</p>

    <div class="notice-box">
        <p><strong>📌 Important Information</strong></p>
        <p>If you have a valid reason for your absence (e.g., medical emergency, family matters), please contact our admissions office as soon as possible. We may be able to schedule a makeup exam or provide alternative arrangements.</p>
    </div>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Exam Details</td></tr>
        <tr><td>Student Name</td><td>{{ $student_name }}</td></tr>
        <tr><td>Control Number</td><td>{{ $control_number }}</td></tr>
        <tr><td>Exam Name</td><td>{{ $exam_name }}</td></tr>
        <tr><td>Status</td><td><span class="status-badge" style="background:#fef3c7;color:#92400e;">Absent</span></td></tr>
    </table>
@endsection