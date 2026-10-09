@extends('emails.layout', ['subject' => $subject ?? 'Reply from Salawag Senior High School'])

@section('content')
    <img src="{{ $message->embed(public_path('images/info.png')) }}" width="120" alt="Message" style="margin-bottom:20px;">
    <h2>Reply to Your Inquiry</h2>
    <h3>Hello, {{ $recipient_name }}</h3>

    <p>Thank you for contacting Salawag Senior High School. Here is our response to your inquiry:</p>

    <div style="background:#f8fff8;border-left:5px solid #1b5e20;border-radius:8px;padding:18px;margin:20px 0;text-align:left;">
        <p style="color:#1b5e20;margin:0 0 8px;"><strong>Your original message:</strong></p>
        <p style="color:#555555;font-size:14px;font-style:italic;margin:0;">{{ $original_excerpt }}</p>
    </div>

    <div style="background:#ffffff;border:1px solid #d9e8d9;border-radius:8px;padding:20px;margin:20px 0;text-align:left;">
        <p style="color:#555555;line-height:1.7;font-size:15px;white-space:pre-line;margin:0;">{{ $reply_body }}</p>
    </div>

    <p style="color:#6b7280;font-size:13px;margin-top:30px;">
        You can reply directly to this email or visit our contact page for further inquiries.
    </p>
@endsection