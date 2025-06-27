@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>New Domestic Transfer Alert</title>
    </head>
    <body>
        <style>
            html, body {
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
                            <img alt="{{ config('app.name') }} Logo" src="{{ asset('storage/logo/logo-dark.png') }}" height="45px" width="80%" />
                        </a>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="center">
                        <div style="text-align: left; margin: 0 20px; padding: 40px; background-color: #ffffff; border-radius: 6px;">
                            <div style="padding-bottom: 30px; font-size: 18px;">
                                <strong style="color: #007bff;">New Domestic Wire Transfer</strong>
                            </div>
                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif;">
                                <p>Dear Admin,</p>
                                <p>A user has just initiated a new transfer.</p>

                                <p><strong>Transfer Details:</strong></p>
                                <ul>
                                    <li>User: <strong>{{ $transfer->user->first_name }} {{ $transfer->user->last_name }} ({{ $transfer->user->email }})</strong></li>
                                    <li>Reference ID: <strong>{{ $transfer->reference_id }}</strong></li>
                                    <li>Amount: <strong style="color: #28a745;">${{ number_format($transfer->amount, 2) }}</strong></li>
                                    <li>Bank: <strong>{{ $transfer->bank_name }}</strong></li>
                                    <li>Account Name: <strong>{{ $transfer->acct_name }}</strong></li>
                                    <li>Account Number: <strong>{{ $transfer->account_number }}</strong></li>
                                    <li>Account Type: <strong>{{ $transfer->acct_type }}</strong></li>
                                    <li>Routing Number: <strong>{{ $transfer->acct_routing }}</strong></li>
                                    <li>SWIFT Code: <strong>{{ $transfer->acct_swift }}</strong></li>
                                    <li>Country: <strong>{{ $transfer->acct_country }}</strong></li>
                                    <li>Status: <strong>{{ ucfirst($transfer->trans_status) }}</strong></li>
                                    <li>Remarks: <strong>{{ $transfer->acct_remarks ?? 'N/A' }}</strong></li>
                                </ul>

                                <p style="color: #c0392b; font-weight: bold;">This is an automated alert. Please act if necessary.</p>
                            </div>
                            <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0;"></div>
                            <div style="padding-bottom: 10px;">
                                Kind regards, <br />
                                The {{ config('app.name') }} Team.
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="center" style="font-size: 13px; text-align: center; padding: 20px; color: #6d6e7c;">
                        <p>{{ config('settings.site.address', 'Contact support for address details') }}</p>
                        <p>Copyright © <a href="{{ config('app.url') }}" rel="noopener" target="_blank">{{ config('app.name') }}</a>.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
