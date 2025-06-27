@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>New KYC Submission Received</title>
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
                                <strong>New KYC Submission Received</strong>
                            </div>
                            <div style="padding-bottom: 40px; font-family: Poppins, Helvetica, sans-serif; font-size: 14px;">
                                <p>Dear Admin,</p>
                                <p>A new KYC submission requires your review.</p>
                                <p><strong>User Details:</strong></p>
                                <ul>
                                    <li><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</li>
                                    <li><strong>Email:</strong> {{ $user->email }}</li>
                                    <li><strong>Phone:</strong> {{ $user->phone_number }}</li>
                                </ul>
                                <p><strong>Submission Details:</strong></p>
                                <ul>
                                    <li><strong>Submission ID:</strong> {{ $kyc->id }}</li>
                                    <li><strong>Submitted On:</strong> {{ $kyc->created_at->format('F j, Y, g:i A T') }}</li>
                                    <li><strong>ID Proof Type:</strong> {{ ucfirst(str_replace('_', ' ', $kyc->id_proof_type)) }}</li>
                                    <li><strong>Address Proof Type:</strong> {{ ucfirst(str_replace('_', ' ', $kyc->address_proof_type)) }}</li>
                                </ul>
                                <p>Please review this submission at your earliest convenience.</p>
                                <p>
                                    <a href="{{ route('admin.kyc') }}" role="button" style="display: inline-block; padding: 10px 20px; background: #4299e1; color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">Review Submission</a>
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
