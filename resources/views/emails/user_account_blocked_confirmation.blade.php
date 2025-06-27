<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Account Blocked</title>
    </head>
    <body>
        <style>
            html, body {
                font-family: Poppins, Helvetica, sans-serif;
                padding: 0;
                margin: 0;
            }
        </style>

        <div style="font-family: Poppins, Helvetica, sans-serif; line-height: 1.5; font-size: 12px !important; color: #2f3044; min-height: 100%; margin: 0; padding: 0; width: 100%; background-color: #edf2f7;">
            <br />
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; margin: 0 auto; padding: 0; max-width: 600px;">
                <tbody>
                <tr>
                    <td align="center" style="padding: 40px;">
                        <a href="{{ config('app.url') }}" rel="noopener" target="_blank">
                            <img alt="Logo" src="{{ asset('storage/logo/logo-dark.png') }}" height="45px" width="80%" />
                        </a>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <div style="margin: 0 20px; padding: 40px; background-color: #ffffff; border-radius: 6px;">
                            <div style="padding-bottom: 30px; font-size: 18px;">
                                <strong>Hello {{ $user->first_name }},</strong>
                            </div>

                            <div style="padding-bottom: 40px;">
                                <div style="margin-bottom: 20px; padding: 20px; background: linear-gradient(135deg, #e53935 0%, #d32f2f 100%); color: white; border-radius: 8px; text-align: center;">
                                    <h2 style="margin: 0 0 10px 0; font-size: 24px;">Account Blocked</h2>
                                    <p style="margin: 0; font-size: 16px;">Your account has been temporarily restricted.</p>
                                </div>

                                <p>We regret to inform you that your account has been blocked due to a violation of our terms of service.</p>

                                <p><strong>Reason:</strong> {{ $reason }}</p>

                                <p>If you believe this was done in error or would like more information, please contact our support team.</p>

                                <p>
                                    <a href="mailto:{{ config('settings.site.email') }}" style="display: inline-block; padding: 12px 24px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; font-weight: 500;">Contact Support</a>
                                </p>

                                <div style="margin-top: 25px; padding: 15px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; color: #856404;">
                                    <p style="margin: 0; font-size: 13px;">
                                        <strong>Tip:</strong> Keeping your account secure and adhering to platform rules ensures uninterrupted access.
                                    </p>
                                </div>
                            </div>

                            <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0;"></div>

                            <div style="padding-bottom: 10px;">
                                Kind regards, <br />
                                The {{ config('app.name') }} Team.
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="font-size: 13px; padding: 20px; color: #6d6e7c;">
                        <p>{{ config('settings.site.address') }}</p>
                        <p>Copyright © <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
