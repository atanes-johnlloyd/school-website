@extends('emails.layout', ['subject' => 'Payment Receipt - Salawag SHS', 'accentColor' => '#1b5e20'])

@section('content')
    <img src="{{ $message->embed(public_path('images/check.png')) }}" width="120" alt="Receipt" style="margin-bottom:20px;">
    <h2>🧾 Payment Received</h2>
    <p>Hi <strong>{{ $recipient_name }}</strong>,</p>
    <p>Thank you — we have received the payment below.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Receipt Details</td></tr>
        <tr><td>Reference</td><td><strong>{{ $reference }}</strong></td></tr>
        <tr><td>Contribution</td><td>{{ $contribution }}</td></tr>
        <tr><td>Amount Paid</td><td><strong>{{ $amount }}</strong></td></tr>
        <tr><td>Method</td><td>{{ $method ?: '—' }}</td></tr>
        <tr><td>Paid At</td><td>{{ $paid_at }}</td></tr>
    </table>

    <p style="font-size:13px;color:#666;">
        Please save this email for your records. For any questions, contact the school office.
    </p>
@endsection