<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for Midtrans such as server key,
    | client key, production mode, and additional settings for transaction
    | security and environment.
    |
    */

    'server_key'     => env('MIDTRANS_SERVER_KEY', ''),
    'client_key'     => env('MIDTRANS_CLIENT_KEY', ''),
    'is_production'  => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized'   => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds'         => env('MIDTRANS_IS_3DS', true),
];
