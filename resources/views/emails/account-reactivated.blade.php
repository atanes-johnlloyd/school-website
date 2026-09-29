@extends('emails.layout', ['subject' => 'Account Reactivated - Salawag SHS'])

@section('content')
    <img src="{{ $message->embed(public_path('images/check.png')) }}" width="120" alt="Reactivated" style="margin-bottom:20px;">
    <h2>✅ Account Reactivated</h2>
    <h3>Hello, {{ $full_name }}</h3>
    <p>Your administrator account on the <strong>Enrollment Management Portal</strong> has been <strong>re-enabled</strong>.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Reactivation Information</td></tr>
        <tr><td>Reactivated By</td><td>{{ $admin_name }}</td></tr>
        <tr><td>Date</td><td>{{ $date }}</td></tr>
    </table>

    <div class="action-box">
        <p style="color:#166534;font-weight:bold;">✅ You can now log in to the portal again.</p>
        <p style="color:#14532d;font-size:14px;">If you have any questions, contact your system administrator.</p>
    </div>
@endsection