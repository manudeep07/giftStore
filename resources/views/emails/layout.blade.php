<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? config('app.name') }}</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f1f5f9;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="background:#0f172a;padding:28px 32px;">
                            <p style="margin:0;font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:#94a3b8;">CustomGift</p>
                            <p style="margin:8px 0 0;font-size:22px;font-weight:600;color:#ffffff;">{{ $headline ?? config('app.name') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            @yield('body')
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#f8fafc;padding:20px 32px;border-top:1px solid #e2e8f0;">
                            <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">
                                This message was sent from your Gift Store on localhost. Check Mailtrap to preview emails during development.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
