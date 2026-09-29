@extends('emails.layout', ['subject' => 'Your Admin Account - Salawag SHS'])

@section('content')
    <img src="{{ $message->embed(public_path('images/info.png')) }}" width="120" alt="Account Created" style="margin-bottom:20px;">
    <h2>✅ Admin Account Created</h2>
    <h3>Hello, {{ $full_name }}</h3>
    <p>An administrator account has been created for you on the <strong>Salawag Senior High School Enrollment Management Portal</strong>.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">🔑 Your Login Credentials</td></tr>
        <tr><td>Username</td><td><strong>{{ $username }}</strong></td></tr>
        <tr><td>Temporary Password</td><td><code style="background:#f3f4f6;padding:4px 10px;border-radius:4px;color:#1b5e20;font-weight:bold;">{{ $default_password }}</code></td></tr>
    </table>

    <div class="notice-box">
        <p style="color:#92400e;"><strong>⚠️ Change your password immediately after login.</strong></p>
        <p style="color:#78350f;font-size:14px;">For security reasons, you will be redirected to a password change page when you first log in.</p>
    </div>

    <p style="text-align:center;margin:25px 0;">
        <a href="{{ $login_link }}" class="btn">Set Your Credentials</a>
    </p>

    <div class="action-box">
        <h3>🔒 Security Tips</h3>
        <ol>
            <li>Never share your password with anyone.</li>
            <li>Choose a strong, unique password.</li>
            <li>If you did not request this account, contact the system administrator immediately.</li>
        </ol>
    </div>
@endsection