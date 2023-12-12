<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT time to live
    |--------------------------------------------------------------------------
    |
    | Specify the duration that the token will be valid for.
    | This is expressed in seconds.
    |
    */

    'ttl' => env('JWT_TTL', null),

    /*
    |--------------------------------------------------------------------------
    | Refresh time to live
    |--------------------------------------------------------------------------
    |
    | Specify the duration that the token can be refreshed within.
    | This is expressed in seconds.
    |
    */

    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // Optional: Refresh token duration

    /*
    |--------------------------------------------------------------------------
    | JWT hashing algorithm
    |--------------------------------------------------------------------------
    |
    | Specify the hashing algorithm that will be used to sign the token.
    |
    */

    'algo' => env('JWT_ALGO', 'HS256'),

    /*
    |--------------------------------------------------------------------------
    | Secret key
    |--------------------------------------------------------------------------
    |
    | Specify the secret key used for signing the token.
    |
    */

    'secret' => env('JWT_SECRET', 'JWT_SECRET=ENeleYZqBJOgKSUOQX3SHWQEk6Shx2Pnz1sSXW1d5W1jpc1KgZcPq3Rgjf6U8bPC'),

];

