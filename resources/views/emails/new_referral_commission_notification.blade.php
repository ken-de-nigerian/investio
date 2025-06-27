<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>New Referral Commission Notification</title>
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
                                <strong>Referral Commission Alert</strong>
                            </div>

                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif;">
                                <div style="margin-bottom: 20px; padding: 15px; background: linear-gradient(135deg, #ffa726 0%, #ff9800 100%); color: white; border-radius: 8px; text-align: center;">
                                    <h3 style="margin: 0 0 5px 0; font-size: 20px;">💰 New Referral Commission</h3>
                                    <p>Hello Admin,</p>
                                    <p style="margin: 0; font-size: 14px;">A referral commission has been processed</p>
                                </div>

                                <p>A referral commission has been automatically processed and paid out. Here are the details:</p>

                                <p><strong>Commission Details:</strong></p>
                                <ul>
                                    <li>Referrer: <strong>{{ $referral['referrer']['first_name'] }} {{ $referral['referrer']['last_name'] }}</strong></li>
                                    <li>Referrer Email: <strong>{{ $referral['referrer']['email'] }}</strong></li>
                                    <li>Referred User: <strong>{{ $referral['referred_user']['first_name'] }} {{ $referral['referred_user']['last_name'] }}</strong></li>
                                    <li>Referred User Email: <strong>{{ $referral['referred_user']['email'] }}</strong></li>
                                    <li>Investment Amount: <strong>${{ number_format($referral['investment_amount'], 2) }}</strong></li>
                                    <li>Commission Rate: <strong>{{ $referral['percent'] }}%</strong></li>
                                    <li>Commission Amount: <strong>${{ number_format($referral['amount'], 2) }}</strong></li>
                                    <li>Date Processed: <strong>{{ now()->format('F j, Y \a\t g:i A') }}</strong></li>
                                </ul>

                                <p><strong>Investment Information:</strong></p>
                                <ul>
                                    <li>Investment Plan: <strong>{{ $referral['investment']->plan->name }}</strong></li>
                                    <li>Investment ID: <strong>#{{ $referral['investment']->id }}</strong></li>
                                    <li>Expected Profit: <strong>${{ number_format($referral['investment']->expected_profit, 2) }}</strong></li>
                                </ul>

                                <div style="margin: 20px 0; padding: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724;">
                                    <p style="margin: 0; font-weight: 500;">
                                        ✅ Commission of <strong>${{ number_format($referral['amount'], 2) }}</strong> has been credited to referrer's account.
                                    </p>
                                </div>

                                <p>
                                    <a href="{{ route('admin.referrals') }}" style="display: inline-block; padding: 12px 24px; background: #4a90e2; color: white; text-decoration: none; border-radius: 5px; font-weight: 500; margin-right: 10px;">View All Referrals</a>
                                    <a href="{{ route('admin.users.show', $referral['referrer']['id']) }}" style="display: inline-block; padding: 12px 24px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: 500;">View Referrer</a>
                                </p>

                                <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; color: #856404;">
                                    <p style="margin: 0; font-size: 13px;">
                                        <strong>System Info:</strong> This commission was automatically calculated and processed based on your referral settings. Both users have been notified via email.
                                    </p>
                                </div>
                            </div>

                            <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0;"></div>

                            <!--end:Email content-->
                            <div style="padding-bottom: 10px;">
                                Best regards, <br />
                                {{ config('app.name') }} System.
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="center" style="font-size: 13px; text-align: center; padding: 20px; color: #6d6e7c;">
                        <p>{{ config('settings.site.address') }}</p>
                        <p>Copyright © <a href="{{ config('app.url') }}" rel="noopener" target="_blank">{{ config('app.name') }}</a>.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
