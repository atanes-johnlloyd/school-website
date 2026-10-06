@extends('emails.layout', ['subject' => 'Approve Contribution Request', 'accentColor' => '#1b5e20'])

@section('content')
    <h2>👋 Hello {{ $guardian_name }},</h2>
    <p>Your child <strong>{{ $student_name }}</strong> has been asked to contribute to
       <strong>{{ $contribution }}</strong> at Salawag Senior High School.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Contribution Details</td></tr>
        <tr><td>Student</td><td>{{ $student_name }}</td></tr>
        <tr><td>Contribution</td><td><strong>{{ $contribution }}</strong></td></tr>
        @if($purpose)
        <tr><td>Purpose</td><td>{{ $purpose }}</td></tr>
        @endif
        <tr><td>Amount</td><td><strong>{{ $amount }}</strong></td></tr>
        <tr><td>Deadline</td><td>{{ $deadline }}</td></tr>
    </table>

    <p style="font-size:14px;">
        To allow your child to proceed with the payment, please approve below. If you have concerns,
        you may decline and your child will not be able to make this payment.
    </p>

    <p style="text-align:center;margin:30px 0;">
        <a href="{{ $approve_link }}" class="btn" style="background:#1b5e20;">✅ Approve</a>
        &nbsp;&nbsp;
        <a href="{{ $decline_link }}" class="btn" style="background:#dc2626;">❌ Decline</a>
    </p>

    <div class="notice-box">
        <p><strong>⏰ This link expires on {{ $expires_at }}.</strong></p>
        <p style="font-size:13px;">If you did not expect this, please contact the school at your earliest convenience.</p>
    </div>
@endsection