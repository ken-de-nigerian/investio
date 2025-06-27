@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Password Reset Confirmation</title>
    </head>
    <body>
        <style>
            html,
            body {
                font-family: Poppins, Helvetica, sans-serif;
                padding: 0;
                margin: 0;
            }
        </style>

        <div style="font-family: Poppins, Helvetica, sans-serif; line-height: 1.5; font-weight: normal; font-size: 12px !important; color: #2f3044; min-height: 100%; margin: 0; padding: 0; width: 100%; background-color: #edf2f7;">
            <br />
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; margin: 0 auto; padding: 0; max-width: 600px;">
                <tbody>
                <tr>
                    <td align="center" valign="center" style="text-align: center; padding: 40px;">
                        <a href="{{ config('app.url') }}" rel="noopener" target="_blank">
                            <img alt="{{ config('app.name') }} Logo" src="{{ asset('storage/logo/logo-dark.png') }}" height="45px" width="80%" />
                        </a>
                    </td>
                </tr>

                <tr>
                    <td align="left" valign="center">
                        <div style="text-align: left; margin: 0 20px; padding: 40px; background-color: #ffffff; border-radius: 6px;">
                            <!--begin:Email content-->
                            <div style="padding-bottom: 30px; font-size: 18px;">
                                <strong>Password Reset Confirmation</strong>
                            </div>

                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif;">
                                <p>Hello {{ $user->first_name }},</p>

                                <p>Your password for {{ config('app.name') }} has been successfully reset.</p>

                                <p><strong>Account Security Details:</strong></p>
                                <ul style="margin-left: 0; padding-left: 0;">
                                    <li style="margin-bottom: 8px;"><strong>Reset Time:</strong> {{ Carbon::now()->format('F j, Y \a\t g:i A') }}</li>
                                    <li style="margin-bottom: 8px;"><strong>IP Address:</strong> {{ $ip ?? 'Not available' }}</li>
                                    <li style="margin-bottom: 8px;"><strong>Device:</strong> {{ $device ?? 'Not available' }}</li>
                                </ul>

                                <p style="margin-top: 25px;">
                                    <a href="{{ config('app.url') }}/login" style="display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: 500;">Login to Your Account</a>
                                </p>

                                <p style="color: #c0392b; font-weight: bold; margin-top: 25px;">
                                    If you didn't request this password reset, please secure your account immediately.
                                </p>
                            </div>

                            <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0;"></div>

                            <!--end:Email content-->
                            <div style="padding-bottom: 10px;">
                                Kind regards, <br />
                                The {{ config('app.name') }} Team
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="center" style="font-size: 13px; text-align: center; padding: 20px; color: #6d6e7c;">
                        <p>{{ config('settings.site.address', 'Contact support for address details') }}</p>
                        <p>Copyright © <a href="{{ config('app.url') }}" rel="noopener" target="_blank">{{ config('app.name') }}</a>.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
