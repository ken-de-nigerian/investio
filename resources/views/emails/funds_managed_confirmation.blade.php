<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Funds {{ ucfirst($type) }} Confirmation</title>
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
                                <strong>Hello {{ $user->first_name }}</strong>
                            </div>

                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif;">
                                <div style="margin-bottom: 20px; padding: 20px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border-radius: 8px; text-align: center;">
                                    <h2 style="margin: 0 0 10px 0; font-size: 24px;">
                                        {{ $type === 'deposit' ? 'Deposit Confirmed' : 'Withdrawal Processed' }}
                                    </h2>
                                    <p style="margin: 0; font-size: 16px;">
                                        Your fund {{ $type }} of ${{ number_format($amount, 2) }} has been {{ $type === 'deposit' ? 'successfully added' : 'processed' }}.
                                    </p>
                                </div>

                                <p><strong>Transaction Details:</strong></p>
                                <ul>
                                    <li>Amount: <strong>${{ number_format($amount, 2) }}</strong></li>
                                    <li>Type: <strong>{{ ucfirst($type) }}</strong></li>
                                    <li>Date: <strong>{{ now()->format('F j, Y \a\t g:i A') }}</strong></li>
                                    <li>Status: <strong>{{ $type === 'deposit' ? 'Completed' : 'Processed' }}</strong></li>
                                </ul>

                                <div style="margin: 20px 0; padding: 15px; background-color: #e2e3e5; border: 1px solid #d6d8db; border-radius: 5px; color: #383d41;">
                                    <p style="margin: 0;">
                                        {{ $type === 'deposit'
                                            ? 'You can now use your funds for investments or transfers.'
                                            : 'Please allow up to 24 hours for the funds to reflect in your destination account.' }}
                                    </p>
                                </div>

                                <p>
                                    <a href="{{ route('user.wallet') }}" style="display: inline-block; padding: 12px 24px; background: #4a90e2; color: white; text-decoration: none; border-radius: 5px; font-weight: 500;">View Wallet</a>
                                </p>
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
