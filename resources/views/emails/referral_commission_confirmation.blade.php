<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Referral Commission Earned</title>
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
                                <strong>Hello {{ $referral['referrer']['first_name'] }}</strong>
                            </div>

                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif;">
                                <div style="margin-bottom: 20px; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px; text-align: center;">
                                    <h2 style="margin: 0 0 10px 0; font-size: 24px;">🎉 Congratulations!</h2>
                                    <p style="margin: 0; font-size: 16px;">You've earned a referral commission!</p>
                                </div>

                                <p>Great news! One of your referrals has made an investment and you've earned a commission.</p>

                                <p><strong>Commission Details:</strong></p>
                                <ul>
                                    <li>Referral: <strong>{{ $referral['referred_user']['first_name'] }} {{ $referral['referred_user']['last_name'] }}</strong></li>
                                    <li>Investment Amount: <strong>${{ number_format($referral['investment_amount'], 2) }}</strong></li>
                                    <li>Commission Rate: <strong>{{ $referral['percent'] }}%</strong></li>
                                    <li>Commission Earned: <strong>${{ number_format($referral['amount'], 2) }}</strong></li>
                                    <li>Date: <strong>{{ now()->format('F j, Y \a\t g:i A') }}</strong></li>
                                </ul>

                                <div style="margin: 20px 0; padding: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; color: #155724;">
                                    <p style="margin: 0; font-weight: 500;">
                                        💰 Your commission of <strong>${{ number_format($referral['amount'], 2) }}</strong> has been added to your account balance.
                                    </p>
                                </div>

                                <p>Keep sharing your referral link to earn more commissions when your friends invest!</p>

                                <p>
                                    <a href="{{ route('user.referrals') }}" style="display: inline-block; padding: 12px 24px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: 500; margin-right: 10px;">View Referrals</a>
                                    <a href="{{ route('user.wallet') }}" style="display: inline-block; padding: 12px 24px; background: #4a90e2; color: white; text-decoration: none; border-radius: 5px; font-weight: 500;">Check Balance</a>
                                </p>

                                <div style="margin-top: 25px; padding: 15px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; color: #856404;">
                                    <p style="margin: 0; font-size: 13px;">
                                        <strong>Tip:</strong> Share your referral link with more friends to maximize your earning potential. The more people you refer, the more you earn!
                                    </p>
                                </div>
                            </div>

                            <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0;"></div>

                            <!--end:Email content-->
                            <div style="padding-bottom: 10px;">
                                Kind regards, <br />
                                The {{ config('app.name') }} Team.
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
