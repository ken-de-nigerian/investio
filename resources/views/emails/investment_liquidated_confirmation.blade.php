<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Investment Liquidation Confirmation</title>
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
                                <strong>Hello {{ $investment->user->first_name }}</strong>
                            </div>

                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif;">
                                <p>Your investment liquidation request has been successfully received and is being processed.</p>

                                <p><strong>Investment Details:</strong></p>
                                <ul>
                                    <li>Investment Plan: <strong>{{ $investment->investment_plan->name ?? 'N/A' }}</strong></li>
                                    <li>Original Amount: <strong>${{ number_format($investment->amount, 2) }}</strong></li>
                                    <li>Liquidated Amount: <strong>${{ number_format($liquidation_amount, 2) }}</strong></li>
                                    <li>Liquidation Type: <strong>{{ $investment->end_date > now() ? 'Early Liquidation' : 'Maturity Liquidation' }}</strong></li>
                                    <li>Investment Duration: <strong>{{ $investment->start_date->format('M d, Y') }} - {{ $investment->end_date->format('M d, Y') }}</strong></li>
                                    <li>Status: <strong>{{ ucfirst($investment->status) }}</strong></li>
                                    <li>Request Date: <strong>{{ now()->format('M d, Y H:i:s') }}</strong></li>
                                </ul>

                                @if($investment->end_date > now())
                                    <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 15px; margin: 20px 0;">
                                        <p style="margin: 0; color: #856404;"><strong>Early Liquidation Request:</strong> You have requested to liquidate this investment before maturity. Please note that early liquidation may result in reduced returns or penalties.</p>
                                    </div>
                                @else
                                    <div style="background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; padding: 15px; margin: 20px 0;">
                                        <p style="margin: 0; color: #155724;"><strong>Maturity Liquidation Request:</strong> Your investment has reached maturity and you will receive the full expected returns upon processing.</p>
                                    </div>
                                @endif

                                <p>Your liquidation request is currently being reviewed by our team. We will process your request and the funds will be credited to your account balance once approved.</p>

                                <p>If you did not initiate this liquidation request, please contact our support team immediately.</p>

                                <p>
                                    <a href="{{ route('user.investment.list') }}" style="display: inline-block; padding: 10px 20px; background: #4a90e2; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">View Investments</a>
                                    <a href="{{ route('user.wallet') }}" style="display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;">View Wallet</a>
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
