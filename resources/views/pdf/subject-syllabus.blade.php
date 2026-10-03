<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>{{ $subject->code }} Syllabus</title>
<style>
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 11px;
        color: #2a2a2a;
        margin: 0;
        padding: 32px;
        line-height: 1.5;
    }
    .header {
        border-bottom: 3px solid #004d08;
        padding-bottom: 14px;
        margin-bottom: 22px;
    }
    .school-name {
        font-size: 19px;
        font-weight: bold;
        color: #004d08;
        margin: 0;
    }
    .school-addr {
        font-size: 10px;
        color: #666;
        margin-top: 3px;
    }
    .doc-title {
        font-size: 13px;
        font-weight: bold;
        color: #004d08;
        text-align: center;
        margin: 26px 0 22px;
        letter-spacing: 2px;
        text-transform: uppercase;
    }
    .subject-hero {
        background: #f5f4ed;
        padding: 16px 18px;
        border-left: 5px solid #004d08;
        margin-bottom: 24px;
    }
    .subject-code {
        font-family: 'DejaVu Sans Mono', monospace;
        font-size: 11px;
        font-weight: bold;
        color: #004d08;
        letter-spacing: 1px;
    }
    .subject-title {
        font-size: 16px;
        font-weight: bold;
        color: #1a1a1a;
        margin-top: 5px;
    }
    table.meta {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }
    table.meta td {
        padding: 8px 6px;
        vertical-align: top;
        border-bottom: 1px solid #f0f0f0;
    }
    td.label {
        font-weight: bold;
        color: #777;
        width: 22%;
        text-transform: uppercase;
        font-size: 9px;
        letter-spacing: 0.6px;
    }
    td.value {
        font-weight: 500;
        color: #1a1a1a;
        width: 28%;
    }
    .description-label {
        font-weight: bold;
        color: #004d08;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .description-body {
        background: #fafafa;
        padding: 14px 16px;
        border: 1px solid #e8e8e8;
        border-radius: 4px;
        font-size: 11px;
        line-height: 1.6;
        color: #333;
        margin-bottom: 30px;
    }
    .signature-block {
        margin-top: 60px;
        width: 260px;
    }
    .signature-line {
        border-top: 1px solid #333;
        padding-top: 5px;
        text-align: center;
        font-size: 10px;
        color: #333;
    }
    .signature-caption {
        text-align: center;
        font-size: 9px;
        color: #888;
        margin-top: 2px;
    }
    .footer {
        position: fixed;
        bottom: 20px;
        left: 32px;
        right: 32px;
        border-top: 1px solid #e5e5e5;
        padding-top: 10px;
        font-size: 9px;
        color: #999;
        text-align: center;
    }
</style>
</head>
<body>

<div class="header">
    <p class="school-name">{{ $school['name'] }}</p>
    <p class="school-addr">
        {{ $school['address'] }}@if(!empty($school['email'])) &nbsp;•&nbsp; {{ $school['email'] }}@endif
    </p>
</div>

<div class="doc-title">Subject Syllabus Summary</div>

<div class="subject-hero">
    <div class="subject-code">{{ $subject->code }}</div>
    <div class="subject-title">{{ $subject->name }}</div>
</div>

<table class="meta">
    <tr>
        <td class="label">Strand</td>
        <td class="value">{{ $subject->strand?->name ?? 'Core Curriculum' }}</td>
        <td class="label">Track</td>
        <td class="value">{{ $subject->strand?->track?->name ?? 'All Tracks' }}</td>
    </tr>
    <tr>
        <td class="label">Grade Level</td>
        <td class="value">
            @if ($subject->grade_level === 'both')
                Grade 11 – 12
            @else
                Grade {{ $subject->grade_level }}
            @endif
        </td>
        <td class="label">Total Hours</td>
        <td class="value">{{ $subject->hours }} hours</td>
    </tr>
    <tr>
        <td class="label">Category</td>
        <td class="value">{{ $subject->is_core ? 'Core Subject' : 'Specialized Subject' }}</td>
        <td class="label">Prerequisite</td>
        <td class="value">
            @if ($subject->prerequisite)
                {{ $subject->prerequisite->code }} — {{ $subject->prerequisite->name }}
            @else
                None
            @endif
        </td>
    </tr>
</table>

<div class="description-label">Course Description</div>
<div class="description-body">
    @if (!empty($subject->description))
        {{ $subject->description }}
    @else
        No description is currently on record for this subject. Please consult the Curriculum
        Coordinator for detailed course content, learning competencies, and assessment criteria.
    @endif
</div>

<div class="signature-block">
    <div class="signature-line">Curriculum Coordinator</div>
    <div class="signature-caption">{{ $school['name'] }}</div>
</div>

<div class="footer">
    Generated {{ $generatedAt->format('F d, Y \a\t h:i A') }}
    @if ($schoolYear) &nbsp;•&nbsp; School Year {{ $schoolYear }} @endif
    <br>
    This is a system-generated document. For the official syllabus, contact the Registrar's Office.
</div>

</body>
</html>