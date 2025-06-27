<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Investment Liquidation - Admin Notification</title>
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
                                <strong>New Liquidation Request</strong>
                            </div>

                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif;">
                                <p>Hello Admin,</p>
                                <p>A user has submitted a new investment liquidation request that requires review and processing.</p>

                                <p><strong>User Information:</strong></p>
                                <ul>
                                    <li>Name: <strong>{{ $investment->user->first_name }} {{ $investment->user->last_name }}</strong></li>
                                    <li>Email: <strong>{{ $investment->user->email }}</strong></li>
                                    <li>User ID: <strong>#{{ $investment->user->id }}</strong></li>
                                </ul>

                                <p><strong>Investment Details:</strong></p>
                                <ul>
                                    <li>Investment ID: <strong>#{{ $investment->id }}</strong></li>
                                    <li>Investment Plan: <strong>{{ $investment->investment_plan->name ?? 'N/A' }}</strong></li>
                                    <li>Original Amount: <strong>${{ number_format($investment->amount, 2) }}</strong></li>
                                    <li>Expected Profit: <strong>${{ number_format($investment->expected_profit, 2) }}</strong></li>
                                    <li>Liquidated Amount: <strong>${{ number_format($liquidation_amount, 2) }}</strong></li>
                                    <li>Investment Period: <strong>{{ $investment->start_date->format('M d, Y') }} - {{ $investment->end_date->format('M d, Y') }}</strong></li>
                                    <li>Liquidation Date: <strong>{{ now()->format('M d, Y H:i:s') }}</strong></li>
                                    <li>Request Status: <strong>Pending Review</strong></li>
                                </ul>

                                @if($investment->end_date > now())
                                    <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; padding: 15px; margin: 20px 0;">
                                        <p style="margin: 0; color: #721c24;"><strong>Early Liquidation Request:</strong> User is requesting to liquidate {{ $investment->end_date->diffForHumans() }} before maturity. This may involve penalties or reduced returns.</p>
                                    </div>
                                @else
                                    <div style="background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px; padding: 15px; margin: 20px 0;">
                                        <p style="margin: 0; color: #155724;"><strong>Maturity Liquidation Request:</strong> This investment has reached full maturity and user is entitled to full returns including profits.</p>
                                    </div>
                                @endif

                                <p><strong>Action Required:</strong></p>
                                <ul>
                                    <li>Review the liquidation request details</li>
                                    <li>Verify user account and investment status</li>
                                    <li>Process the liquidation and credit user account</li>
                                    <li>Send confirmation to user once completed</li>
                                </ul>

                                <p>
                                    <a href="{{ route('admin.investments.show', $investment->id) }}" style="display: inline-block; padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">Process Request</a>
                                    <a href="{{ route('admin.users.show', $investment->user->id) }}" style="display: inline-block; padding: 10px 20px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">View User</a>
                                    <a href="{{ route('admin.investments') }}" style="display: inline-block; padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">All Investments</a>
                                </p>
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
