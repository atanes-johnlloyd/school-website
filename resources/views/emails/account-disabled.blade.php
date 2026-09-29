@extends('emails.layout', ['subject' => 'Account Disabled - Salawag SHS', 'accentColor' => '#dc2626'])

@section('content')
    <img src="{{ $message->embed(public_path('images/reject.png')) }}" width="120" alt="Disabled" style="margin-bottom:20px;">
    <h2 style="color:#dc2626;">⛔ Account Disabled</h2>
    <h3>Hello, {{ $full_name }}</h3>
    <p>Your administrator account on the <strong>Enrollment Management Portal</strong> has been <strong>disabled</strong>.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Disable Information</td></tr>
        <tr><td>Disabled By</td><td>{{ $admin_name }}</td></tr>
        <tr><td>Date</td><td>{{ $date }}</td></tr>
        <tr><td>Reason</td><td>{{ $reason }}</td></tr>
    </table>

    <div class="notice-box" style="background:#fef2f2;border-left-color:#dc2626;">
        <p style="color:#991b1b;"><strong>⚠️ You can no longer access the portal.</strong></p>
        <p style="color:#78350f;font-size:14px;">If you believe this was done in error, please contact your system administrator.</p>
    </div>
@endsection