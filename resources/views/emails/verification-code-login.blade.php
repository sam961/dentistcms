<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Verification Code</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 40px 20px; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px 8px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                DentistCMS
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 20px; color: #333333; font-size: 24px;">
                                Login Verification Required
                            </h2>

                            <p style="margin: 0 0 20px; color: #666666; font-size: 16px; line-height: 1.5;">
                                We detected a login attempt to your DentistCMS account. To ensure the security of your account, please enter the verification code below.
                            </p>

                            <p style="margin: 0 0 10px; color: #666666; font-size: 16px; line-height: 1.5;">
                                Your verification code is:
                            </p>

                            <!-- Verification Code Box -->
                            <div style="background-color: #f8f9fa; border: 2px solid #667eea; border-radius: 8px; padding: 30px; text-align: center; margin: 20px 0;">
                                <div style="font-size: 42px; font-weight: bold; letter-spacing: 8px; color: #667eea; font-family: 'Courier New', monospace;">
                                    {{ $verificationCode->code }}
                                </div>
                            </div>

                            <p style="margin: 20px 0; color: #666666; font-size: 14px; line-height: 1.5;">
                                <strong>This code will expire in {{ $verificationCode->expires_at->diffInMinutes(now()) }} minutes.</strong>
                            </p>

                            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
                                <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.5;">
                                    <strong>Security Notice:</strong> If you didn't attempt to log in, please change your password immediately and contact support.
                                </p>
                            </div>

                            <p style="margin: 0 0 10px; color: #666666; font-size: 14px; line-height: 1.5;">
                                <strong>Login Details:</strong>
                            </p>
                            <ul style="margin: 0; padding-left: 20px; color: #666666; font-size: 14px; line-height: 1.5;">
                                <li>Time: {{ $verificationCode->created_at->format('F j, Y g:i A') }}</li>
                                <li>IP Address: {{ request()->ip() }}</li>
                            </ul>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 40px; background-color: #f8f9fa; border-radius: 0 0 8px 8px; text-align: center;">
                            <p style="margin: 0; color: #999999; font-size: 12px;">
                                © {{ date('Y') }} DentistCMS. All rights reserved.
                            </p>
                            <p style="margin: 10px 0 0; color: #999999; font-size: 12px;">
                                This is an automated email, please do not reply.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
