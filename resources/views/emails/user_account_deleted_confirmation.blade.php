<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Account Deleted</title>
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
                                <div style="margin-bottom: 20px; padding: 20px; background: linear-gradient(135deg, #e53935 0%, #b71c1c 100%); color: white; border-radius: 8px; text-align: center;">
                                    <h2 style="margin: 0 0 10px 0; font-size: 24px;">Account Deleted</h2>
                                    <p style="margin: 0; font-size: 16px;">Your account has been permanently removed.</p>
                                </div>

                                <p>We’re writing to confirm that your account on {{ config('app.name') }} has been deleted.</p>

                                <p><strong>Reason:</strong> {{ $reason }}</p>

                                <p>If this action was unauthorized, please contact support immediately. Note that account deletions are typically irreversible.</p>

                                <p>
                                    <a href="mailto:{{ config('settings.site.email') }}" style="display: inline-block; padding: 12px 24px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; font-weight: 500;">Reach Support</a>
                                </p>

                                <div style="margin-top: 25px; padding: 15px; background-color: #e2e3e5; border: 1px solid #d6d8db; border-radius: 5px; color: #383d41;">
                                    <p style="margin: 0; font-size: 13px;">
                                        <strong>Note:</strong> You will no longer be able to access your data or services tied to this account.
                                    </p>
                                </div>
                            </div>

                            <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0;"></div>

                            <div style="padding-bottom: 10px;">
                                Sincerely,<br />
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
