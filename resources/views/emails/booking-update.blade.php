<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $heading }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f0eb; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f0eb; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="520" cellpadding="0" cellspacing="0" style="background-color: #1a1208; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.15); max-width: 100%;">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 32px 40px 20px; text-align: center; border-bottom: 1px solid rgba(191,155,48,0.2);">
                            <h1 style="margin: 0; font-family: Georgia, 'Times New Roman', serif; font-size: 28px; color: #BF9B30; letter-spacing: 1px;">
                                LDReserve
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
                            <h2 style="margin: 0 0 12px; font-family: Georgia, 'Times New Roman', serif; font-size: 22px; color: #BF9B30;">{{ $heading }}</h2>
                            <p style="margin: 0 0 24px; font-size: 15px; color: rgba(255,255,255,0.75); line-height: 1.6;">
                                {!! nl2br(e($body)) !!}
                            </p>

                            <!-- Booking summary -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background: rgba(191,155,48,0.08); border: 1px solid rgba(191,155,48,0.25); border-radius: 12px;">
                                <tr><td style="padding: 18px 22px;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size: 14px; color: rgba(255,255,255,0.75);">
                                        <tr>
                                            <td style="padding: 5px 0; color: rgba(255,255,255,0.45);">Booking #</td>
                                            <td align="right" style="padding: 5px 0; color: #ffffff; font-weight: 600;">{{ $details['number'] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0; color: rgba(255,255,255,0.45);">Package</td>
                                            <td align="right" style="padding: 5px 0; color: #ffffff; font-weight: 600;">{{ $details['package'] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0; color: rgba(255,255,255,0.45);">Event date</td>
                                            <td align="right" style="padding: 5px 0; color: #ffffff; font-weight: 600;">{{ $details['date'] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0; color: rgba(255,255,255,0.45);">Status</td>
                                            <td align="right" style="padding: 5px 0; color: #ffffff; font-weight: 600;">{{ $details['status'] }}</td>
                                        </tr>
                                        <tr><td colspan="2" style="padding: 8px 0 0;"><div style="border-top: 1px dashed rgba(191,155,48,0.3);"></div></td></tr>
                                        <tr>
                                            <td style="padding: 5px 0; color: rgba(255,255,255,0.45);">Total</td>
                                            <td align="right" style="padding: 5px 0; color: #ffffff; font-weight: 600;">{{ $details['total'] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0; color: rgba(255,255,255,0.45);">Paid so far</td>
                                            <td align="right" style="padding: 5px 0; color: #ffffff; font-weight: 600;">{{ $details['paid'] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0; color: rgba(255,255,255,0.45);">Remaining balance</td>
                                            <td align="right" style="padding: 5px 0; color: #BF9B30; font-weight: 700;">{{ $details['balance'] }}</td>
                                        </tr>
                                    </table>
                                </td></tr>
                            </table>

                            <!-- Button -->
                            <div style="text-align: center; margin: 28px 0 8px;">
                                <a href="{{ route('profile') }}" style="display: inline-block; background: #BF9B30; color: #1a1208; text-decoration: none; font-weight: 700; font-size: 14px; letter-spacing: 1px; padding: 13px 30px; border-radius: 8px;">VIEW MY BOOKING</a>
                            </div>
                            @if(!empty($details['receiptUrl']))
                                <div style="text-align: center; margin: 12px 0 8px;">
                                    <a href="{{ $details['receiptUrl'] }}" style="display: inline-block; border: 1px solid #BF9B30; color: #BF9B30; text-decoration: none; font-weight: 700; font-size: 14px; letter-spacing: 1px; padding: 12px 30px; border-radius: 8px;">DOWNLOAD RECEIPT</a>
                                </div>
                                <p style="margin: 8px 0 0; font-size: 12px; color: rgba(255,255,255,0.4); text-align: center; line-height: 1.5;">
                                    Tap the button to download your receipt (PDF), no login needed. It is also attached to this email.
                                </p>
                            @endif
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 40px 28px; border-top: 1px solid rgba(191,155,48,0.15); text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: rgba(255,255,255,0.3);">
                                &copy; {{ date('Y') }} LDReserve. All rights reserved.
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
