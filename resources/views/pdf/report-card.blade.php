<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Report Card - {{ $student->user?->name }}</title>
    <style>
        @page { margin: 15mm; }
        * { box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #1b5e20;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #1b5e20;
            letter-spacing: 1px;
        }
        .header h2 {
            margin: 3px 0;
            font-size: 13px;
            color: #4b5563;
            font-weight: normal;
        }
        .header .doc-title {
            margin-top: 8px;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1b5e20;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 3px 6px;
            vertical-align: top;
        }
        .info-table td.label {
            width: 18%;
            color: #6b7280;
            font-weight: bold;
        }
        .info-table td.value {
            width: 32%;
            color: #1f2937;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .grades-table th {
            background: #1b5e20;
            color: #ffffff;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .grades-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .grades-table tr:nth-child(even) td {
            background: #f9fafb;
        }
        .grades-table td.center {
            text-align: center;
        }
        .grades-table td.grade {
            font-weight: bold;
            color: #1b5e20;
            text-align: center;
        }
        .grades-table tr.summary-row td {
            background: #f0fdf4;
            font-weight: bold;
            border-top: 2px solid #1b5e20;
        }
        .remarks {
            margin-top: 15px;
            padding: 10px 12px;
            background: #f0fdf4;
            border-left: 4px solid #1b5e20;
        }
        .remarks table {
            width: 100%;
            border-collapse: collapse;
        }
        .remarks td {
            padding: 4px 6px;
            font-size: 12px;
        }
        .remarks td.label {
            width: 30%;
            color: #4b5563;
        }
        .remarks td.value {
            font-weight: bold;
            color: #1b5e20;
        }
        .signatures {
            margin-top: 40px;
            width: 100%;
        }
        .signatures table {
            width: 100%;
            border-collapse: collapse;
        }
        .signatures td {
            text-align: center;
            width: 50%;
            padding: 25px 10px 0;
        }
        .signatures .name {
            border-top: 1px solid #4b5563;
            padding-top: 5px;
            font-weight: bold;
            color: #1f2937;
        }
        .signatures .role {
            font-size: 10px;
            color: #6b7280;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }
        .no-grades {
            text-align: center;
            padding: 30px;
            color: #6b7280;
            font-style: italic;
        }
    </style>
</head>
<body>

    {{-- ═══════════════ HEADER ═══════════════ --}}
    <div class="header">
        <h1>{{ $school['name'] }}</h1>
        <h2>{{ $school['address'] }}</h2>
        @if($school['email'] || $school['phone'])
            <h2>{{ $school['email'] }} @if($school['email'] && $school['phone']) • @endif {{ $school['phone'] }}</h2>
        @endif
        <div class="doc-title">Official Report Card</div>
    </div>

    {{-- ═══════════════ STUDENT INFO ═══════════════ --}}
    <table class="info-table">
        <tr>
            <td class="label">Student Name:</td>
            <td class="value">{{ $student->user?->name }}</td>
            <td class="label">LRN:</td>
            <td class="value">{{ $student->lrn }}</td>
        </tr>
        <tr>
            <td class="label">Grade Level:</td>
            <td class="value">{{ $enrollment?->section?->grade_level ?? '—' }}</td>
            <td class="label">Section:</td>
            <td class="value">{{ $enrollment?->section?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Strand:</td>
            <td class="value">{{ $enrollment?->section?->strand?->name ?? '—' }}</td>
            <td class="label">Sex:</td>
            <td class="value">{{ ucfirst($student->sex ?? '—') }}</td>
        </tr>
        <tr>
            <td class="label">School Year:</td>
            <td class="value">{{ $schoolYear?->label ?? '—' }}</td>
            <td class="label">Term:</td>
            <td class="value">{{ $term?->name ?? '—' }}</td>
        </tr>
    </table>

    {{-- ═══════════════ GRADES TABLE ═══════════════ --}}
    @if($grades->isEmpty())
        <div class="no-grades">No finalized grades recorded for this term.</div>
    @else
        <table class="grades-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Code</th>
                    <th style="width: 34%;">Subject</th>
                    <th style="width: 12%; text-align: center;">Written Work</th>
                    <th style="width: 14%; text-align: center;">Performance Task</th>
                    <th style="width: 12%; text-align: center;">Quarterly Exam</th>
                    <th style="width: 10%; text-align: center;">Final</th>
                    <th style="width: 10%; text-align: center;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grades as $grade)
                    <tr>
                        <td>{{ $grade->classroom?->subject?->code ?? '—' }}</td>
                        <td>{{ $grade->classroom?->subject?->name ?? '—' }}</td>
                        <td class="center">{{ $grade->written_work_score !== null ? number_format((float) $grade->written_work_score, 2) : '—' }}</td>
                        <td class="center">{{ $grade->performance_task_score !== null ? number_format((float) $grade->performance_task_score, 2) : '—' }}</td>
                        <td class="center">{{ $grade->quarterly_exam_score !== null ? number_format((float) $grade->quarterly_exam_score, 2) : '—' }}</td>
                        <td class="grade">
                            {{ $grade->final_grade !== null ? number_format((float) $grade->final_grade, 2) : '—' }}
                        </td>
                        <td class="center">
                            @php
                                $fg = $grade->final_grade;
                                $rem = $fg === null ? '—'
                                    : ($fg >= 90 ? 'Outstanding'
                                    : ($fg >= 85 ? 'Very Satisfactory'
                                    : ($fg >= 80 ? 'Satisfactory'
                                    : ($fg >= 75 ? 'Fairly Satisfactory' : 'Failed'))));
                            @endphp
                            {{ $rem }}
                        </td>
                    </tr>
                @endforeach

                @if($generalAverage !== null)
                    <tr class="summary-row">
                        <td colspan="5" style="text-align: right;">General Average:</td>
                        <td class="grade">{{ number_format($generalAverage, 2) }}</td>
                        <td class="center">{{ $overallRemarks }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif

    {{-- ═══════════════ REMARKS BOX ═══════════════ --}}
    @if($generalAverage !== null)
        <div class="remarks">
            <table>
                <tr>
                    <td class="label">General Average:</td>
                    <td class="value">{{ number_format($generalAverage, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Overall Remarks:</td>
                    <td class="value">{{ $overallRemarks }}</td>
                </tr>
                <tr>
                    <td class="label">Status:</td>
                    <td class="value">{{ $generalAverage >= 75 ? 'PASSED' : 'FAILED' }}</td>
                </tr>
            </table>
        </div>
    @endif

    {{-- ═══════════════ SIGNATURES ═══════════════ --}}
    <div class="signatures">
        <table>
            <tr>
                <td>
                    <div class="name">{{ $enrollment?->section?->adviser?->user?->name ?? '____________________' }}</div>
                    <div class="role">Class Adviser</div>
                </td>
                <td>
                    <div class="name">{{ $school['principal_name'] ?: '____________________' }}</div>
                    <div class="role">School Principal</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ═══════════════ FOOTER ═══════════════ --}}
    <div class="footer">
        Generated on {{ $generatedAt->format('F d, Y \a\t h:i A') }}
        • This document is computer-generated and does not require a wet signature.
    </div>

</body>
</html>