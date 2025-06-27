@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Loan Repayment Confirmation</title>
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
                                <strong>Loan Repayment Received</strong>
                            </div>
                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif; font-size: 14px;">
                                <p>Dear {{ $loan->user->first_name }},</p>
                                <p>We have successfully received your loan repayment of <strong>${{ number_format($amount, 2) }}</strong> for the loan with title <strong>#{{ $loan->title }}</strong>.</p>
                                <p><strong>Payment Summary:</strong></p>
                                <ul>
                                    <li><strong>Loan Amount:</strong> ${{ number_format($loan->loan_amount, 2) }}</li>
                                    <li><strong>Total Paid EMIs:</strong> {{ $loan->paid_emi }} of {{ $loan->tenure_months }}</li>
                                    <li><strong>Next Due Date:</strong> @if ($loan->next_due_date) {{ Carbon::parse($loan->next_due_date)->format('F j, Y') }} @else N/A @endif</li>
                                    <li><strong>Status:</strong> {{ ucfirst($loan->status) }}</li>
                                </ul>
                                <p>Thank you for staying on track with your repayments. Feel free to contact our support team if you have any questions.</p>
                                <p>
                                    <a href="{{ route('user.loan.show', $loan->id) }}" role="button" style="display: inline-block; padding: 10px 20px; background: #2f855a; color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">View Loan Details</a>
                                </p>
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
