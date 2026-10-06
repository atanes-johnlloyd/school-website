@extends('emails.layout', ['subject' => 'Contribution Approved', 'accentColor' => '#1b5e20'])

@section('content')
    <img src="{{ $message->embed(public_path('images/check.png')) }}" width="120" alt="Approved" style="margin-bottom:20px;">
    <h2>✅ Guardian Approved</h2>
    <p>Hi <strong>{{ $student_name }}</strong>,</p>
    <p>Your guardian has approved the contribution for <strong>{{ $contribution }}</strong>.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">💰 Payment Details</td></tr>
        <tr><td>Contribution</td><td>{{ $contribution }}</td></tr>
        <tr><td>Amount</td><td><strong>{{ $amount }}</strong></td></tr>
        <tr><td>Status</td><td><span class="status-badge">Ready to Pay</span></td></tr>
    </table>

    <p style="text-align:center;margin:25px 0;">
        <a href="{{ $pay_link }}" class="btn">Proceed to Pay</a>
    </p>
@endsection