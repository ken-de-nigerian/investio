<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>KYC Approved</title>
    </head>
    <body>
        <style>
            html, body {
                font-family: Poppins, Helvetica, sans-serif;
                margin: 0;
                padding: 0;
            }
        </style>

        <div style="font-family: Poppins, Helvetica, sans-serif; font-size: 12px; color: #2f3044; background-color: #edf2f7;">
            <br />
            <table align="center" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; margin: auto;">
                <tr>
                    <td align="center" style="padding: 40px;">
                        <a href="{{ config('app.url') }}">
                            <img src="{{ asset('storage/logo/logo-dark.png') }}" alt="Logo" height="45px" width="80%" />
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div style="margin: 0 20px; padding: 40px; background-color: #ffffff; border-radius: 6px;">
                            <div style="font-size: 18px; margin-bottom: 20px;">
                                <strong>Hello {{ $user->first_name }},</strong>
                            </div>

                            <div style="padding: 20px; background: linear-gradient(135deg, #38b2ac 0%, #3182ce 100%); color: white; border-radius: 8px; text-align: center; margin-bottom: 20px;">
                                <h2 style="margin: 0 0 10px 0; font-size: 24px;">✅ KYC Approved!</h2>
                                <p style="margin: 0; font-size: 16px;">Your identity has been successfully verified.</p>
                            </div>

                            <p>Thank you for submitting your KYC documents. We’ve reviewed and approved your verification request.</p>

                            <p>You now have full access to all platform features including investments, withdrawals, and more.</p>

                            <p>
                                <a href="{{ route('user.dashboard') }}" style="display:inline-block;padding:12px 24px;background:#28a745;color:white;text-decoration:none;border-radius:5px;font-weight:500;">
                                    Go to Dashboard
                                </a>
                            </p>

                            <div style="margin-top: 25px; padding: 15px; background-color: #e9f7ef; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724;">
                                <p style="margin: 0; font-size: 13px;">
                                    If you have any questions, feel free to contact our support team.
                                </p>
                            </div>

                            <div style="border-bottom: 1px solid #eeeeee; margin: 25px 0;"></div>

                            <div>Kind regards, <br />The {{ config('app.name') }} Team.</div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="font-size: 13px; padding: 20px; color: #6d6e7c;">
                        <p>{{ config('settings.site.address') }}</p>
                        <p>Copyright © <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>.</p>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
