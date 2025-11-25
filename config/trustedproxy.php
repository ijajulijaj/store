<?php

use Symfony\Component\HttpFoundation\Request;

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxy IP Addresses
    |--------------------------------------------------------------------------
    */

    'proxies' => '*', // Trust all Azure proxies

    /*
    |--------------------------------------------------------------------------
    | Trusted Headers
    |--------------------------------------------------------------------------
    */

    'headers' => Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PROTO
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PREFIX,
];
