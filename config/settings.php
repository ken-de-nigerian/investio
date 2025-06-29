<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for email-related features in the admin panel.
    |
    */
    'site' => [
        'tech' => [
            'email' => 'tech@yourplatform.com'
        ],
        'email' => 'support@yourplatform.com',
        'phone' => '+1 (234) 567-8900',
        'address' => 'admin@site.com',
        'wallet_addresses' => env('WALLET_ADDRESSES', []),
    ],

    /*
    |--------------------------------------------------------------------------
    | Registration Settings
    |--------------------------------------------------------------------------
    |
    | This option controls whether a new user registration is enabled.
    | When disabled, the registration routes will be unavailable.
    |
    */
    'register' => [
        'enabled' => env('REGISTRATION_ENABLED', true),
    ],

    'login' => [
        'social_enabled' => env('SOCIAL_LOGIN_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for email-related features in the admin panel.
    |
    */
    'email_notification' => true,    // Enable/disable all email notifications
    'email_provider' => 'phpmailer',      // Default mailer service to use for all emails

    /*
    |--------------------------------------------------------------------------
    | Referral Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for email-related features in the admin panel.
    |
    */
    'referral' => [
        'enabled' => true,    // Enable/disable all email notifications
        'commission' => 5,      // Default mailer service to use for all emails
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency Settings
    |--------------------------------------------------------------------------
    |
    | Default currency configuration for financial transactions.
    |
    */
    'currency' => [
        'code' => 'USD',               // ISO currency code
        'symbol' => '$',               // Currency symbol
        'precision' => 2               // Decimal places to display
    ],


    /*
    |--------------------------------------------------------------------------
    | Loan Settings
    |--------------------------------------------------------------------------
    |
    | Configuration options related to loan requests, including interest rate,
    | repayment duration, penalties, and other relevant financial settings.
    |
    */
    'loan' => [
        'interest_rate' => 13.5,          // Annual interest rate (in percentage)
        'min_amount' => 100.00,           // Minimum loan amount allowed
        'max_amount' => 1000000.00,         // Maximum loan amount allowed
        'repayment_period' => 12,         // Default repayment period in months
        'grace_period' => 30,             // Days before repayment starts
        'late_fee' => 2.0,                // Late repayment fee (in percentage)
        'processing_fee' => 1.0,          // Loan processing fee (in percentage)
        'currency' => 'USD',              // Default currency for loan transactions
    ],
];
