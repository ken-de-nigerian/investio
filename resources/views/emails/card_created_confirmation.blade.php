<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Virtual Card Created</title>
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
                            <img alt="{{ config('app.name') }} Logo" src="{{ asset('storage/logo/logo-dark.png') }}" height="45px" width="80%" />
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
                                <p>Your virtual card has been successfully created and is ready to use!</p>

                                <p><strong>Card Details:</strong></p>
                                <ul>
                                    <li>Card Type: <strong>{{ $card->card_type }}</strong></li>
                                    <li>Card Number: <strong>•••• •••• •••• {{ substr($card->card_number, -4) }}</strong></li>
                                    <li>Card Holder: <strong>{{ $card->card_name }}</strong></li>
                                    <li>Expiration: <strong>{{ $card->card_expiration }}</strong></li>
                                    <li>Serial Key: <strong>{{ $card->serial_key }}</strong></li>
                                    <li>Initial Balance: <strong>{{ number_format($card->card_balance, 2) }} USD</strong></li>
                                    <li>Status: <strong>{{ ucfirst($card->card_status) }}</strong></li>
                                </ul>

                                <p style="margin-top: 20px;">
                                    <a href="{{ route('user.profile', ['tab' => 'cards']) }}" style="display: inline-block; padding: 10px 20px; background: #4a90e2; color: white; text-decoration: none; border-radius: 5px;">View Your Card</a>
                                </p>

                                <p style="color: #c0392b; font-weight: bold; margin-top: 25px;">
                                    For security reasons, never share your full card details with anyone.
                                </p>
                            </div>

                            <div style="border-bottom: 1px solid #eeeeee; margin: 15px 0;"></div>

                            <!--end:Email content-->
                            <div style="padding-bottom: 10px;">
                                Kind regards, <br />
                                The {{ config('app.name') }} Team
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
