<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Salawag Senior High School' }}</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f6f8; font-family: Arial, Helvetica, sans-serif; }
        .header { background: linear-gradient(135deg, #1b5e20, #717c88); padding: 40px 20px 30px; text-align: center; color: #ffffff; }
        .footer { background: #1b5e20; padding: 25px; text-align: center; color: #ffffff; }
        .logo-circle {
            width: 140px; height: 140px; border-radius: 50%;
            border: 4px solid #ffffff; padding: 10px;
            background: rgba(255,255,255,0.15);
            display: inline-block; overflow: hidden;
            box-shadow: 0 0 30px rgba(0,0,0,0.2);
            margin: 0 auto 15px;
        }
        .logo-circle img { width: 100%; height: 100%; object-fit: contain; display: block; }
        h1 { margin: 10px 0 0; font-size: 26px; letter-spacing: 2px; color: #ffffff; }
        h2 { color: {{ $accentColor ?? '#1b5e20' }}; font-size: 28px; margin: 0 0 15px; }
        h3 { margin: 0 0 10px; font-size: 20px; color: #1b5e20; }
        p { color: #555555; line-height: 1.7; font-size: 15px; }
        .info-table { width: 100%; border: 1px solid #d9e8d9; border-radius: 10px; border-collapse: collapse; overflow: hidden; margin: 20px 0; }
        .info-table td { padding: 15px; border-bottom: 1px solid #d9e8d9; font-size: 15px; }
        .info-table td:first-child { font-weight: bold; width: 35%; background: #f8fff8; }
        .info-table .header-row td { background: #1b5e20; color: #ffffff; padding: 15px; font-size: 18px; font-weight: bold; text-align: center; }
        .btn {
            display: inline-block; background: #1b5e20; color: #ffffff !important;
            padding: 12px 32px; border-radius: 8px;
            text-decoration: none; font-weight: 600; margin: 10px 0;
        }
        .notice-box {
            background: #fef3c7; border-left: 5px solid #f59e0b;
            border-radius: 8px; padding: 18px; margin: 20px 0; text-align: left;
        }
        .notice-box p { margin: 5px 0; color: #78350f; }
        .action-box {
            background: #f8fff8; border-left: 5px solid #1b5e20;
            border-radius: 8px; padding: 18px; margin: 20px 0; text-align: left;
        }
        .action-box ol { margin: 0; padding-left: 20px; color: #555555; line-height: 2; }
        .status-badge {
            padding: 4px 14px; border-radius: 9999px;
            font-size: 13px; font-weight: 600; display: inline-block;
            background: #dcfce7; color: #1b5e20;
        }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f6f8;padding:20px 0;">
        <tr>
            <td align="center">
                <table width="650" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);">
                    <tr>
                        <td class="header">
                            <div class="logo-circle">
                                <img src="{{ $message->embed(public_path('images/crest.png')) }}" alt="School Logo">
                            </div>
                            <h1>SALAWAG SENIOR HIGH SCHOOL</h1>
                            <p style="margin:8px 0 0;color:#ffffff;opacity:0.9;">Excellence • Integrity • Service</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:50px 30px 40px;text-align:center;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">
                            <p style="margin:0;color:#ffffff;font-size:14px;">© {{ date('Y') }} Salawag Senior High School. All rights reserved.</p>
                            <p style="margin:5px 0 0;color:#ffffff;font-size:12px;opacity:0.7;">This is an automated message. Please do not reply to this email.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>