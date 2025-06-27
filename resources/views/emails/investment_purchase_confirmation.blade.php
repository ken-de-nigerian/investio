<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Investment Confirmation</title>
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
                                <strong>Hello {{ $user->first_name }},</strong>
                            </div>

                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif;">
                                <p>Your investment in <strong>{{ $investment->plan->name }}</strong> has been successfully processed.</p>

                                <p><strong>Investment Details:</strong></p>
                                <ul>
                                    <li>Plan: <strong>{{ $investment->plan->name }}</strong></li>
                                    <li>Amount Invested: <strong>{{ number_format($investment->amount, 2) }} USD</strong></li>
                                    <li>Expected Profit: <strong>{{ number_format($investment->expected_profit, 2) }} USD</strong></li>
                                    <li>Total Return: <strong>{{ number_format($investment->amount + $investment->expected_profit, 2) }} USD</strong></li>
                                    <li>Start Date: <strong>{{ $investment->start_date->format('F j, Y') }}</strong></li>
                                    <li>Maturity Date: <strong>{{ $investment->end_date->format('F j, Y') }}</strong></li>
                                    <li>Investment ID: <strong>{{ $investment->id }}</strong></li>
                                </ul>

                                <p>Your funds will be locked until maturity on <strong>{{ $investment->end_date->format('F j, Y') }}</strong> as per your agreement.</p>

                                <p>
                                    <a href="{{ route('user.investment.show', $investment->id) }}" style="display: inline-block; padding: 10px 20px; background: #4a90e2; color: white; text-decoration: none; border-radius: 5px;">View Investment Details</a>
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
