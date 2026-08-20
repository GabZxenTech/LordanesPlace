<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your New Email</title>
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
                            <p style="margin: 0 0 20px; font-size: 15px; color: rgba(255,255,255,0.7); line-height: 1.6;">
                                An administrator requested to change the email address on your account from
                                <strong style="color: #ffffff;">{{ $oldEmail }}</strong> to
                                <strong style="color: #BF9B30;">{{ $newEmail }}</strong>.
                            </p>
                            <p style="margin: 0 0 24px; font-size: 15px; color: rgba(255,255,255,0.7); line-height: 1.6;">
                                Since you are the owner of this new address, please confirm the change below. The current email will keep working until this is confirmed. This link is valid for <strong style="color: #BF9B30;">24 hours</strong>.
                            </p>

                            <!-- CTA -->
                            <div style="text-align: center; margin: 28px 0;">
                                <a href="{{ $confirmUrl }}" style="display: inline-block; background: #BF9B30; color: #1a1208; font-weight: 700; font-size: 15px; letter-spacing: 1px; text-decoration: none; padding: 14px 36px; border-radius: 8px;">
                                    CONFIRM EMAIL CHANGE
                                </a>
                            </div>

                            <p style="margin: 24px 0 0; font-size: 14px; color: rgba(255,255,255,0.5); line-height: 1.6;">
                                If you did not expect this, you can safely ignore this email — no change will be made unless this link is clicked, and you may want to let LorDane's Place know.
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
