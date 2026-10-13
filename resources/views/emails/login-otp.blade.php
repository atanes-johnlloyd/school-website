@extends('emails.layout', ['subject' => 'Your Login Code - Salawag SHS', 'accentColor' => '#1b5e20'])

@section('content')
    <img src="{{ $message->embed(public_path('images/info.png')) }}" width="120" alt="Login Code" style="margin-bottom:20px;">
    <h2>🔐 Your Login Code</h2>
    <h3>Hello, {{ $full_name }}</h3>
    <p>You are attempting to sign in to the <strong>Salawag Senior High School Portal</strong>. Use the code below to complete your login.</p>

    <div style="background:#f8fff8;border-left:5px solid #1b5e20;border-radius:8px;padding:25px;margin:25px 0;text-align:center;">
        <p style="margin:0 0 8px;font-size:12px;text-transform:uppercase;letter-spacing:2px;color:#1b5e20;font-weight:700;">Your 6-Digit Code</p>
        <div style="font-size:38px;font-weight:800;letter-spacing:12px;color:#1b5e20;font-family:'Courier New',monospace;">
            {{ $code }}
        </div>
    </div>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Login Attempt Details</td></tr>
        <tr><td>Account</td><td>{{ $full_name }}</td></tr>
        <tr><td>Requested At</td><td>{{ now()->format('F d, Y \a\t g:i A') }}</td></tr>
        <tr><td>IP Address</td><td>{{ $ip_address }}</td></tr>
        <tr><td>Expires In</td><td><strong>{{ $expires_in }}</strong></td></tr>
    </table>

    <div class="notice-box" style="background:#fef2f2;border-left-color:#dc2626;">
        <p style="color:#991b1b;"><strong>⚠️ Didn't request this?</strong></p>
        <p style="color:#991b1b;font-size:14px;">If you did not attempt to log in, someone else may have your password. Change your password immediately and contact the system administrator.</p>
    </div>
@endsection