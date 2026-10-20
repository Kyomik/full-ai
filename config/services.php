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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'indonesia_regions' => [
        'base_url' => env('INDONESIA_REGIONS_BASE_URL', 'http://www.emsifa.com/api-wilayah-indonesia/api'),
        'connect_timeout' => (int) env('INDONESIA_REGIONS_CONNECT_TIMEOUT', 4),
        'timeout' => (int) env('INDONESIA_REGIONS_TIMEOUT', 7),
        'retry_times' => (int) env('INDONESIA_REGIONS_RETRY_TIMES', 1),
        'retry_delay' => (int) env('INDONESIA_REGIONS_RETRY_DELAY', 200),
        'cache_days' => (int) env('INDONESIA_REGIONS_CACHE_DAYS', 7),
    ],

];
