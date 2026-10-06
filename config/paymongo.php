<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PayMongo Configuration
    |--------------------------------------------------------------------------
    */

    'enabled' => env('PAYMONGO_ENABLED', true),

    'public_key' => env('PAYMONGO_PUBLIC_KEY'),
    'secret_key' => env('PAYMONGO_SECRET_KEY'),
    'webhook_secret' => env('PAYMONGO_WEBHOOK_SECRET'),

    'base_url' => env('PAYMONGO_BASE_URL', 'https://api.paymongo.com/v1'),

    /*
    |--------------------------------------------------------------------------
    | Enabled payment methods
    |--------------------------------------------------------------------------
    | Options: gcash, paymaya, card, grab_pay, bilid, shopeepay
    */
    'payment_methods' => array_filter(array_map(
        'trim',
        explode(',', env('PAYMONGO_PAYMENT_METHODS', 'gcash,paymaya,card'))
    )),

    /*
    |--------------------------------------------------------------------------
    | Currency & session lifetime
    |--------------------------------------------------------------------------
    */
    'currency' => 'PHP',
    'checkout_ttl_minutes' => 60,

];