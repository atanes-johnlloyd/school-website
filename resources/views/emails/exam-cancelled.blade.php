@extends('emails.layout', ['subject' => 'Exam Cancelled - Salawag SHS', 'accentColor' => '#dc2626'])

@section('content')
    <img src="{{ $message->embed(public_path('images/reject.png')) }}" width="120" alt="Cancelled" style="margin-bottom:20px;">
    <h2 style="color:#dc2626;">❌ Exam Cancelled</h2>
    <h3 style="color:#dc2626;">We regret to inform you</h3>
    <p>Dear <strong>{{ $student_name }}</strong>, the entrance exam "<strong>{{ $exam_name }}</strong>" has been cancelled.</p>
    <p>You will be notified when a new exam date is set. Please wait for further instructions.</p>

    <div class="notice-box" style="background:#fef2f2;border-left-color:#dc2626;">
        <p style="color:#991b1b;"><strong>📌 Next Steps:</strong> Once a new exam is scheduled, you will receive an email with the updated details. If you have any concerns, please contact our admissions office.</p>
    </div>
@endsection