<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Code</title>
</head>

<body
    style="margin:0;padding:0;background:#151719;font-family:Arial,'Khmer OS Battambang','Noto Sans Khmer',sans-serif;color:#eef1f5;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
        style="background:#151719;padding:40px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                    style="max-width:560px;background:#1f2025;border:1px solid #2d3037;border-radius:14px;overflow:hidden;box-shadow:0 18px 48px rgba(0,0,0,0.35);">
                    <tr>
                        <td style="padding:28px 40px 24px;border-bottom:1px solid #2e3138;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td width="58" valign="middle">
                                        <div
                                            style="width:56px;height:56px;border:1px solid #3b3f48;background:#25272d;text-align:center;line-height:56px;">
                                            <img src="{{ asset('backend/dist/img/spilogo.png') }}" alt="LMS"
                                                width="34" height="34"
                                                style="display:inline-block;vertical-align:middle;border:0;max-width:34px;max-height:34px;">
                                        </div>
                                    </td>
                                    <td valign="middle" style="padding-left:16px;">
                                        <div style="font-size:17px;font-weight:700;color:#f4f6fb;line-height:1.35;">
                                            មជ្ឈមណ្ឌលប្រព័ន្ធសិក្សា</div>
                                        <div style="font-size:13px;color:#8f96a3;line-height:1.4;">LMS System</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:38px 40px 22px;">
                            <h1 style="margin:0 0 24px;font-size:20px;line-height:1.7;font-weight:700;color:#f5f7fb;">
                                គោរពជូន លោក/លោកស្រី {{ $user->name }},
                            </h1>

                            <p style="margin:0 0 28px;font-size:16px;line-height:1.9;color:#aeb4bf;">
                                សូមប្រើលេខកូដសម្ងាត់ខាងក្រោមនេះដើម្បីបញ្ជាក់ការចូលប្រើប្រាស់គណនីរបស់អ្នក។
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                                style="margin:0 0 32px;">
                                <tr>
                                    <td align="center"
                                        style="background:#242629;border:1px solid #383c43;border-radius:10px;padding:28px 18px;">
                                        <div
                                            style="font-size:36px;line-height:1;font-weight:700;letter-spacing:14px;color:#ffffff;text-indent:14px;">
                                            {{ $otp }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                                style="margin:0 0 18px;background:#222429;border-left:3px solid #4a4e57;">
                                <tr>
                                    <td style="padding:18px 20px;font-size:14px;line-height:1.9;color:#aeb4bf;">
                                        <strong style="color:#eef1f5;">រយៈពេលសុពលភាព</strong>
                                        លេខកូដនេះនឹងផុតកំណត់ក្នុងរយៈពេល {{ $expires }} នាទី។ បន្ទាប់ពីផុតកំណត់
                                        សូមស្នើសុំលេខកូដថ្មីម្តងទៀត។
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                                style="margin:0 0 28px;background:#222429;border-left:3px solid #4a4e57;">
                                <tr>
                                    <td style="padding:18px 20px;font-size:14px;line-height:1.9;color:#aeb4bf;">
                                        <strong style="color:#eef1f5;">រក្សាសុវត្ថិភាព</strong>
                                        សូមកុំចែករំលែកលេខកូដនេះទៅកាន់អ្នកផ្សេង។ ក្រុមការងារ LMS
                                        មិនស្នើសុំលេខកូដរបស់អ្នកឡើយ។
                                    </td>
                                </tr>
                            </table>

                            <p
                                style="margin:0 0 18px;font-size:15px;line-height:1.8;color:#ef7777;font-weight:700;font-style:italic;">
                                ប្រសិនបើអ្នកមិនបានស្នើសុំ OTP នេះទេ សូមមិនអើពើសារនេះ ឬទាក់ទងក្រុមជំនួយរបស់ LMS។
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td
                            style="padding:18px 40px 28px;color:#777f8d;font-size:12px;line-height:1.6;border-top:1px solid #2e3138;">
                            © {{ date('Y') }} LMS System - Security Team
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
