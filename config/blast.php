<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Disable Registration
    |--------------------------------------------------------------------------
    |
    | This option controls whether users can register for an account on your
    | application. If this is set to true, then the registration routes
    | will not be accessible and users will not be able to register.
    |
    */
    'disable_registration' => env('DISABLE_REGISTRATION', false),

    /*
    |--------------------------------------------------------------------------
    | Reserved Aliases
    |--------------------------------------------------------------------------
    |
    | This option controls which aliases are reserved and can't be used by users
    | when creating short links. This is useful for preventing users from
    | creating short links that might be confusing, inappropriate or for
    | reserving certain words for your own use. App routes are also
    | automatically reserved.
    |
    */
    'reserved_aliases' => [
        'admin',
        'administrator',
        'blast',
        'password',
        'settings',
        'shortlinks',
        'short-links',
        'url',
    ],
];
