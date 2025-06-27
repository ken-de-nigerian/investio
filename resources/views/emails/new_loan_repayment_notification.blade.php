@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>New Loan Repayment Notification</title>
    </head>
    <body>
        <style>
            html, body {
                font-family: Poppins, Helvetica, sans-serif;
                padding: 0;
                margin: 0;
            }
        </style>
        <div style="font-family: Poppins, Helvetica, sans-serif; line-height: 1.5; font-weight: normal; font-size: 12px; color: #2f3044; min-height: 100%; margin: 0; padding: 0; width: 100%; background-color: #edf2f7;">
            <br />
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; margin: 0 auto; padding: 0; max-width: 600px;">
                <tbody>
                <tr>
                    <td align="center" valign="center" style="text-align: center; padding: 40px;">
                        <a href="{{ config('app.url') }}" rel="noopener" target="_blank">
                            <img alt="{{ config('app.name') }} Logo" src="{{ asset('storage/logo/logo-dark.png') }}" height="45px" width="80%" style="display: block; max-width: 100%;" />
                        </a>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="center">
                        <div style="text-align: left; margin: 0 20px; padding: 40px; background-color: #ffffff; border-radius: 6px;">
                            <!--begin:Email content-->
                            <div style="padding-bottom: 30px; font-size: 18px;">
                                <strong>New Loan Repayment Received</strong>
                            </div>
                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif; font-size: 14px;">
                                <p>Hello Admin,</p>
                                <p><strong>{{ $loan->user->first_name }} {{ $loan->user->last_name }}</strong> has just made a repayment of <strong>${{ number_format($amount, 2) }}</strong> for loan <strong>#{{ $loan->title }}</strong>.</p>
                                <p><strong>Loan Details:</strong></p>
                                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                                    <tr style="background: #f0f0f0;">
                                        <td style="padding: 8px; border-bottom: 1px solid #eeeeee;"><strong>User Email</strong></td>
                                        <td style="padding: 8px; border-bottom: 1px solid #eeeeee;">{{ $loan->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; border-bottom: 1px solid #eeeeee;"><strong>Total Loan</strong></td>
                                        <td style="padding: 8px; border-bottom: 1px solid #eeeeee;">${{ number_format($loan->loan_amount, 2) }}</td>
                                    </tr>
                                    <tr style="background: #f0f0f0;">
                                        <td style="padding: 8px; border-bottom: 1px solid #eeeeee;"><strong>Paid EMIs</strong></td>
                                        <td style="padding: 8px; border-bottom: 1px solid #eeeeee;">{{ $loan->paid_emi }} / {{ $loan->tenure_months }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px; border-bottom: 1px solid #eeeeee;"><strong>Status</strong></td>
                                        <td style="padding: 8px; border-bottom: 1px solid #eeeeee;">{{ ucfirst($loan->status) }}</td>
                                    </tr>
                                </table>
                                <p>
                                    <a href="{{ route('admin.loans.show', $loan->id) }}" role="button" style="display: inline-block; padding: 10px 20px; background: #2f855a; color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">View Loan Details</a>
                                </p>
                                <p style="color: #c0392b; font-weight: bold;">This is an automated notification. No action is required unless the repayment details require attention.</p>
                            </div>
                            <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0;"></div>
                            <!--end:Email content-->
                            <div style="padding-bottom: 10px; font-size: 12px;">
                                Kind regards, <br />
                                The {{ config('app.name') }} Team.
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="center" style="font-size: 13px; text-align: center; padding: 20px; color: #6d6e7c;">
                        <p>{{ config('settings.site.address', 'Contact support for address details') }}</p>
                        <p>Copyright © {{ Carbon::now()->year }} <a href="{{ config('app.url') }}" rel="noopener" target="_blank">{{ config('app.name') }}</a>.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
