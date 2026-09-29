@extends('emails.layout', ['subject' => 'Document Resubmission Required - Salawag SHS'])

@section('content')
    <img src="{{ $message->embed(public_path('images/info.png')) }}" width="120" alt="Info" style="margin-bottom:20px;">
    <h2 style="color:#f59e0b;">📄 Action Required</h2>
    <h3>We need clearer copies of your documents</h3>
    <p>We have reviewed your application but some of the uploaded documents are unclear or unreadable. Please resubmit the required documents through the link below.</p>

    <p style="text-align:center;margin:25px 0;">
        <a href="{{ $resubmit_link }}" class="btn">📤 Resubmit Documents</a>
    </p>
    <p style="color:#6b7280;font-size:12px;">This link is unique to your application. Do not share it.</p>

    <table class="info-table">
        <tr class="header-row"><td colspan="2">📋 Application Details</td></tr>
        <tr><td>Student Name</td><td>{{ $student_name }}</td></tr>
        <tr><td>Control Number</td><td>{{ $control_number }}</td></tr>
        <tr><td>Strand</td><td>{{ $strand }}</td></tr>
    </table>
@endsection