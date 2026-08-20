<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Verification Code</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f0eb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f0eb; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="520" cellpadding="0" cellspacing="0" style="background-color: #1a1208; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.15);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 32px 40px 20px; text-align: center; border-bottom: 1px solid rgba(191,155,48,0.2);">
                            <h1 style="margin: 0; font-family: Georgia, 'Times New Roman', serif; font-size: 28px; color: #BF9B30; letter-spacing: 1px;">
                                LorDane's Place
                            </h1>
                            <p style="margin: 6px 0 0; font-size: 10px; letter-spacing: 3px; color: rgba(255,255,255,0.4); text-transform: uppercase;">
                                PLACE • EVENT VENUE
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px 40px;">
                            <p style="margin: 0 0 16px; font-size: 16px; color: rgba(255,255,255,0.85); line-height: 1.6;">
                                Hi <strong style="color: #ffffff;">{{ $userName }}</strong>,
                            </p>
                            <p style="margin: 0 0 24px; font-size: 15px; color: rgba(255,255,255,0.7); line-height: 1.6;">
                                Use the verification code below to complete your email verification. This code is valid for <strong style="color: #BF9B30;">5 minutes</strong>.
                            </p>

                            <!-- OTP Code -->
                            <div style="text-align: center; margin: 28px 0;">
                                <div style="display: inline-block; background: rgba(191,155,48,0.1); border: 2px solid rgba(191,155,48,0.3); border-radius: 12px; padding: 20px 40px;">
                                    <span style="font-size: 36px; font-weight: 700; letter-spacing: 12px; color: #BF9B30; font-family: 'Courier New', monospace;">
                                        {{ $otp }}
                                    </span>
                                </div>
                            </div>

                            <p style="margin: 24px 0 0; font-size: 14px; color: rgba(255,255,255,0.5); line-height: 1.6;">
                                If you didn't create an account at LorDane's Place, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 40px 28px; border-top: 1px solid rgba(191,155,48,0.15); text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: rgba(255,255,255,0.3);">
                                &copy; {{ date('Y') }} LorDane's Place. All rights reserved.
                            </p>
                            <p style="margin: 8px 0 0; font-size: 11px; color: rgba(255,255,255,0.2);">
                                This is an automated message — please do not reply.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
