<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>KYC Rejected</title>
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

                            <div style="padding: 20px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px; text-align: center; margin-bottom: 20px;">
                                <h2 style="margin: 0 0 10px 0; font-size: 24px;">❌ KYC Rejected</h2>
                                <p style="margin: 0; font-size: 16px;">We could not verify your identity.</p>
                            </div>

                            <p>Unfortunately, your KYC verification has been rejected for the following reason:</p>

                            <div style="margin: 15px 0; padding: 15px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; color: #856404;">
                                <p style="margin: 0;"><strong>Reason:</strong> {{ $rejection_reason }}</p>
                            </div>

                            <p>Please review your submitted documents and resubmit them for verification.</p>

                            <p>
                                <a href="{{ route('user.kyc') }}" style="display:inline-block;padding:12px 24px;background:#e83e8c;color:white;text-decoration:none;border-radius:5px;font-weight:500;">
                                    Re-submit KYC
                                </a>
                            </p>

                            <div style="margin-top: 25px; padding: 15px; background-color: #fdf2e9; border: 1px solid #f5c6cb; border-radius: 5px; color: #856404;">
                                <p style="margin: 0; font-size: 13px;">
                                    Ensure that all documents are clear, valid, and match the details you provided.
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
