@extends('emails.layout', ['subject' => 'Application Received - Salawag SHS', 'accentColor' => '#1b5e20'])

@section('content')
    <img src="{{ $message->embed(public_path('images/check.png')) }}" width="120" alt="Received" style="margin-bottom:20px;">
    <h2>📥 Application Received</h2>
    <h3>Thank you, {{ $student_name }}</h3>
    <p>We have successfully received your Senior High School application to <strong>Salawag Senior High School</strong>. Your application is now queued for review by our admissions office.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">🎫 Your Reference Number</td></tr>
        <tr>
            <td colspan="2" style="text-align:center;background:#f8fff8;padding:25px 15px;">
                <div style="font-size:28px;font-weight:800;letter-spacing:3px;color:#1b5e20;font-family:'Courier New',monospace;">
                    {{ $control_number }}
                </div>
                <p style="margin:10px 0 0;font-size:13px;color:#78350f;">
                    ⚠️ Please save this number. You will need it to track your application status.
                </p>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Application Details</td></tr>
        <tr><td>Student Name</td><td>{{ $student_name }}</td></tr>
        <tr><td>Strand Applied</td><td>{{ $strand }}</td></tr>
        <tr><td>School Year</td><td>{{ $school_year }}</td></tr>
        <tr><td>Date Submitted</td><td>{{ $submitted_at }}</td></tr>
        <tr><td>Status</td><td><span class="status-badge" style="background:#fef3c7;color:#92400e;">⏳ Pending Review</span></td></tr>
    </table>

    <p style="text-align:center;margin:25px 0;">
        <a href="{{ $tracking_link }}" class="btn">🔍 Track My Application</a>
    </p>

    <div class="notice-box">
        <p><strong>📌 What Happens Next?</strong></p>
        <p style="font-size:14px;">Our registrar will verify your submitted documents and LRN. Expect an update within <strong>2–3 working days</strong>. If anything is incomplete, we will notify you by email with instructions to resubmit.</p>
    </div>

    <div class="action-box">
        <h3>📌 Reminders</h3>
        <ol>
            <li>Keep this email — your reference number is on it.</li>
            <li>Check your inbox (and spam folder) for updates from us.</li>
            <li>Prepare originals of your PSA Birth Certificate, Form 137, and Good Moral Certificate for verification.</li>
            <li>Do not submit another application with the same LRN — it will be rejected as a duplicate.</li>
        </ol>
    </div>
@endsection