<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'merchant_id' => env('PAYTABS_MERCHANT_ID'),
    'server_key'  => env('PAYTABS_SERVER_KEY'),
    'base_url'    => env('PAYTABS_BASE_URL', 'https://secure.paytabs.com'),

    
    'tryoto' => [
        'base_url' => env('TRYOTO_BASE_URL', 'https://staging-api.tryoto.com'),
        'refresh_token' => env('TRYOTO_REFRESH_TOKEN', 'AMf-vBz6Hq1Bjats_V-QYXs9nFdteH6xS3R46Pyx6CKJ27R_z1XdXX5NF9HwN2WzDczUV1X4eKYflEXkZSF5Xmb4ZEQ0hQL9wyuB8Wtxm1PtEVa5ec5XonbEYFNtYa6aD7oIrZ_2oSwMimpSzhofgUCLCLQVqomPzxzitK870CGdNKL_5gEeHTavWtA1EWvXC3kwAxNFXvSVQMHIXujv7sxZ44u9ozCR4Q'),
        'webhook_secret' => env('TRYOTO_WEBHOOK_SECRET'),
    ],


    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
    ],
    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_SERVICE_CALLBACK'),
    ],
    
    'paytabs' => [
        'server_key' => env('PAYTABS_SERVER_KEY'),
        'profile_id' => env('PAYTABS_PROFILE_ID'),
        'merchant_id' => env('PAYTABS_MERCHANT_ID'),
        'base_url' => env('PAYTABS_BASE_URL'),
        'callback_url' => env('PAYTABS_CALLBACK_URL'),
        'return_url' => env('PAYTABS_RETURN_URL'),
    ],

];
