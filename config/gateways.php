<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wallet Addresses Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file stores the wallet addresses used for payment
    | gateways or crypto transactions. The addresses are loaded from the
    | environment file and can be managed centrally for flexibility and
    | security. Ensure the JSON structure in your .env file is valid.
    |
    */

    'wallet_addresses' => json_decode(env('WALLET_ADDRESSES', '[]'), true),
];
