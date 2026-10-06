@extends('emails.layout', ['subject' => 'Contribution Consent Expired', 'accentColor' => '#f59e0b'])

@section('content')
    <h2 style="color:#f59e0b;">⌛ Consent Link Expired</h2>
    <p>The consent link for <strong>{{ $contribution }}</strong> has expired.</p>
    <p>If you still want to respond, please ask your child to request a new consent email from their dashboard.</p>
@endsection