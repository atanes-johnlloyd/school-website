@extends('emails.layout', ['subject' => 'Reset Your Password - Salawag SHS', 'accentColor' => '#1b5e20'])

@section('content')
    <img src="{{ $message->embed(public_path('images/info.png')) }}" width="120" alt="Password Reset" style="margin-bottom:20px;">
    <h2>🔐 Password Reset Request</h2>
    <h3>Hello, {{ $full_name }}</h3>
    <p>We received a request to reset the password for your <strong>Salawag Senior High School</strong> portal account. Click the button below to choose a new password.</p>

    <p style="text-align:center;margin:30px 0;">
        <a href="{{ $reset_link }}" class="btn">🔑 Reset Password</a>
    </p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Request Details</td></tr>
        <tr><td>Account Email</td><td>{{ $email }}</td></tr>
        <tr><td>Requested At</td><td>{{ $requested_at }}</td></tr>
        <tr><td>Link Expires In</td><td><strong>{{ $expires_in }} minutes</strong></td></tr>
        <tr><td>IP Address</td><td>{{ $ip_address }}</td></tr>
    </table>

    <div class="notice-box">
        <p><strong>⚠️ Didn't request this?</strong></p>
        <p style="font-size:14px;">If you did not request a password reset, you can safely ignore this email. Your password will remain unchanged.</p>
    </div>

    <div class="action-box">
        <h3>🔒 Security Tips</h3>
        <ol>
            <li>Never share this reset link with anyone.</li>
            <li>Choose a strong password (8+ characters with numbers & symbols).</li>
            <li>Use a unique password that you don't use on other sites.</li>
            <li>If you suspect unauthorized access, contact the system administrator immediately.</li>
        </ol>
    </div>

    <p style="color:#6b7280;font-size:12px;margin-top:30px;">
        If the button above doesn't work, copy and paste this link into your browser:<br>
        <a href="{{ $reset_link }}" style="color:#1b5e20;word-break:break-all;">{{ $reset_link }}</a>
    </p>
@endsection