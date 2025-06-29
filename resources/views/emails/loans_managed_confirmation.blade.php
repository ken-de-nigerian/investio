@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>
            @switch($loan->status)
                @case('disbursed')
                    Loan Disbursement Confirmation
                    @break
                @case('approved')
                    Loan Approval Notification
                    @break
                @case('rejected')
                    Loan Application Update
                    @break
                @case('pending')
                    Loan Application Received
                    @break
                @case('completed')
                    Loan Completion Confirmation
                    @break
                @default
                    Loan Status Update
            @endswitch
        </title>
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
                                <strong>
                                    @switch($loan->status)
                                        @case('disbursed')
                                            Loan Disbursement Confirmation
                                            @break
                                        @case('approved')
                                            Loan Approval Notification
                                            @break
                                        @case('rejected')
                                            Loan Application Update
                                            @break
                                        @case('pending')
                                            Loan Application Received
                                            @break
                                        @case('completed')
                                            Loan Completion Confirmation
                                            @break
                                        @default
                                            Loan Status Update
                                    @endswitch
                                </strong>
                            </div>
                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif; font-size: 14px;">
                                <p>Dear {{ $loan->user->first_name }},</p>

                                @switch($loan->status)
                                    @case('disbursed')
                                        <p>We are pleased to inform you that your loan has been disbursed to your account.</p>
                                        @break
                                    @case('approved')
                                        <p>Congratulations! Your loan application has been approved. The funds will be disbursed to your account shortly.</p>
                                        @break
                                    @case('rejected')
                                        <p>We regret to inform you that your loan application has been declined after careful review.</p>
                                        @break
                                    @case('pending')
                                        <p>Thank you for submitting your loan application. We have received your request and it is currently under review.</p>
                                        @break
                                    @case('completed')
                                        <p>Congratulations! You have successfully completed your loan repayment. Thank you for being a valued customer.</p>
                                        @break
                                    @default
                                        <p>We wanted to update you on the status of your loan application.</p>
                                @endswitch

                                @if(in_array($loan->status, ['disbursed', 'approved', 'completed']))
                                    <p><strong>Loan Details:</strong></p>
                                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                                        <tr style="background: #f0f0f0;">
                                            <td style="padding: 8px; border-bottom: 1px solid #eeeeee;"><strong>Loan Amount</strong></td>
                                            <td style="padding: 8px; border-bottom: 1px solid #eeeeee;">${{ number_format($loan->loan_amount, 2) }}</td>
                                        </tr>
                                        @if($loan->status === 'disbursed' && $loan->disbursed_at)
                                            <tr>
                                                <td style="padding: 8px; border-bottom: 1px solid #eeeeee;"><strong>Disbursed On</strong></td>
                                                <td style="padding: 8px; border-bottom: 1px solid #eeeeee;">{{ $loan->disbursed_at->format('F j, Y, g:i A T') }}</td>
                                            </tr>
                                        @endif
                                        @if($loan->status === 'approved' && $loan->approved_at)
                                            <tr>
                                                <td style="padding: 8px; border-bottom: 1px solid #eeeeee;"><strong>Approved On</strong></td>
                                                <td style="padding: 8px; border-bottom: 1px solid #eeeeee;">{{ $loan->approved_at->format('F j, Y, g:i A T') }}</td>
                                            </tr>
                                        @endif
                                        @if($loan->status === 'completed' && $loan->completed_at)
                                            <tr>
                                                <td style="padding: 8px; border-bottom: 1px solid #eeeeee;"><strong>Completed On</strong></td>
                                                <td style="padding: 8px; border-bottom: 1px solid #eeeeee;">{{ $loan->completed_at->format('F j, Y, g:i A T') }}</td>
                                            </tr>
                                        @endif
                                        @if(in_array($loan->status, ['disbursed', 'approved']) && $loan->next_due_date)
                                            <tr style="background: #f0f0f0;">
                                                <td style="padding: 8px; border-bottom: 1px solid #eeeeee;"><strong>
                                                        @if($loan->status === 'disbursed')
                                                            First Due Date
                                                        @else
                                                            Expected First Due Date
                                                        @endif
                                                    </strong></td>
                                                <td style="padding: 8px; border-bottom: 1px solid #eeeeee;">{{ $loan->next_due_date->format('F j, Y') }}</td>
                                            </tr>
                                        @endif
                                        @if(in_array($loan->status, ['disbursed', 'approved', 'completed']) && $loan->monthly_emi)
                                            <tr>
                                                <td style="padding: 8px; border-bottom: 1px solid #eeeeee;"><strong>Monthly EMI</strong></td>
                                                <td style="padding: 8px; border-bottom: 1px solid #eeeeee;">${{ number_format($loan->monthly_emi, 2) }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                @endif

                                @switch($loan->status)
                                    @case('disbursed')
                                        @if($loan->next_due_date)
                                            <p>Please ensure timely repayment starting from {{ $loan->next_due_date->format('F j, Y') }}. Contact our support team at {{ config('settings.site.email') }} for any questions.</p>
                                        @else
                                            <p>Please ensure timely repayment as per your loan schedule. Contact our support team at {{ config('settings.site.email') }} for any questions.</p>
                                        @endif
                                        @break
                                    @case('approved')
                                        <p>You will receive another notification once the funds are disbursed to your account. If you have any questions, please contact our support team at {{ config('settings.site.email') }}.</p>
                                        @break
                                    @case('rejected')
                                        <p>If you would like to understand the reasons for this decision or discuss alternative options, please contact our support team at {{ config('settings.site.email') }}.</p>
                                        @break
                                    @case('pending')
                                        <p>We will notify you once a decision has been made. The review process typically takes 1-3 business days. For any questions, please contact our support team at {{ config('settings.site.email') }}.</p>
                                        @break
                                    @case('completed')
                                        <p>Thank you for choosing {{ config('app.name') }} for your lending needs. We look forward to serving you again in the future. If you have any questions, please contact our support team at {{ config('settings.site.email') }}.</p>
                                        @break
                                    @default
                                        <p>For any questions regarding your loan, please contact our support team at {{ config('settings.site.email') }}.</p>
                                @endswitch

                                @if($loan->status !== 'rejected')
                                    <p>
                                        <a href="{{ route('user.loan.show', $loan->id) }}" role="button" style="display: inline-block; padding: 10px 20px; background: #00725b; color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">
                                            @if($loan->status === 'completed')
                                                View Loan Summary
                                            @else
                                                View Loan Details
                                            @endif
                                        </a>
                                    </p>
                                @endif
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
