@extends('emails.layout', ['subject' => 'Contribution Declined by Guardian', 'accentColor' => '#dc2626'])

@section('content')
    <img src="{{ $message->embed(public_path('images/reject.png')) }}" width="120" alt="Declined" style="margin-bottom:20px;">
    <h2 style="color:#dc2626;">Contribution Not Approved</h2>
    <p>Hi <strong>{{ $student_name }}</strong>,</p>
    <p>Your guardian has declined the contribution for <strong>{{ $contribution }}</strong>.</p>

    @if($reason)
        <div class="notice-box" style="background:#fef2f2;border-left-color:#dc2626;">
            <p><strong>Reason:</strong> {{ $reason }}</p>
        </div>
    @endif

    <p style="font-size:14px;">If you believe this is a mistake, please discuss it with your guardian or your adviser.</p>
@endsection