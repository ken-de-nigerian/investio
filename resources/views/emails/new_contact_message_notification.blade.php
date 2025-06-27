<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>New Contact Message - Admin Notification</title>
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
                            <img alt="Logo" src="{{ asset('storage/logo/logo-dark.png') }}" height="45px" width="80%" />
                        </a>
                    </td>
                </tr>

                <tr>
                    <td align="left" valign="center">
                        <div style="text-align: left; margin: 0 20px; padding: 40px; background-color: #ffffff; border-radius: 6px;">
                            <!--begin:Email content-->
                            <div style="padding-bottom: 30px; font-size: 18px;">
                                <strong>📧 New Contact Message Received</strong>
                            </div>

                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif;">
                                <div style="background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                                    <p style="margin: 0; color: #0c5460;"><strong>💬 New Message:</strong> Someone has submitted a message through the contact form and is waiting for a response.</p>
                                </div>

                                <p><strong>Contact Information:</strong></p>
                                <ul>
                                    <li>Name: <strong>{{ $contactData['first_name'] }} {{ $contactData['last_name'] }}</strong></li>
                                    <li>Email: <strong><a href="mailto:{{ $contactData['email'] }}" style="color: #4a90e2; text-decoration: none;">{{ $contactData['email'] }}</a></strong></li>
                                    <li>Submitted: <strong>{{ now()->format('M d, Y \a\t g:i A') }}</strong></li>
                                </ul>

                                <p><strong>Message:</strong></p>
                                <div style="background-color: #f8f9fa; border-left: 4px solid #4a90e2; padding: 20px; margin: 15px 0; border-radius: 0 5px 5px 0;">
                                    <p style="margin: 0; line-height: 1.6; color: #2f3044;">{{ $contactData['message'] }}</p>
                                </div>

                                <div style="margin: 30px 0; text-align: center;">
                                    <a href="mailto:{{ $contactData['email'] }}?subject=Re: Your Message to {{ config('app.name') }}" style="display: inline-block; padding: 12px 24px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">Reply via Email</a>
                                    <a href="{{ route('admin.dashboard') }}" style="display: inline-block; padding: 12px 24px; background: #4a90e2; color: white; text-decoration: none; border-radius: 5px;">Admin Dashboard</a>
                                </div>

                                <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 15px; margin-top: 20px;">
                                    <p style="margin: 0; font-size: 11px; color: #856404;">
                                        <strong>📋 Action Items:</strong>
                                        <br>• Reply to the customer within 24 hours for best customer service
                                        <br>• Check if this is a support request that needs to be escalated
                                        <br>• Consider adding this contact to your mailing list if appropriate
                                    </p>
                                </div>

                                <div style="border-top: 1px solid #eeeeee; margin-top: 20px; padding-top: 15px;">
                                    <p style="font-size: 11px; color: #6c757d; margin: 0;">
                                        <strong>Quick Reply Template:</strong> You can use this as a starting point for your response:
                                    </p>
                                    <div style="background-color: #f1f3f4; padding: 10px; margin: 10px 0; border-radius: 3px; font-size: 11px; color: #5f6368;">
                                        "Hi {{ $contactData['first_name'] }},<br><br>
                                        Thank you for reaching out to {{ config('app.name') }}. We received your message and will get back to you shortly.<br><br>
                                        Best regards,<br>
                                        {{ config('app.name') }} Team"
                                    </div>
                                </div>
                            </div>

                            <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0;"></div>

                            <!--end:Email content-->
                            <div style="padding-bottom: 10px;">
                                Best regards, <br />
                                {{ config('app.name') }} System
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="center" style="font-size: 13px; text-align: center; padding: 20px; color: #6d6e7c;">
                        <p>{{ config('settings.site.address') }}</p>
                        <p>Copyright © <a href="{{ config('app.url') }}" rel="noopener" target="_blank">{{ config('app.name') }}</a>.</p>
                        <p style="font-size: 11px; margin-top: 10px;">
                            This is an automated admin notification for contact form submissions.
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
