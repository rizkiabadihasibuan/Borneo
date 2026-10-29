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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Eksternal Pemantauan Karhutla & Cuaca
    |--------------------------------------------------------------------------
    */
    'firms' => [
        'map_key' => env('NASA_FIRMS_MAP_KEY'),
        'source' => env('NASA_FIRMS_SOURCE', 'VIIRS_SNPP_NRT'),
        'use_open_feed' => (bool) env('NASA_FIRMS_USE_OPEN_FEED', true),
        'cache_ttl' => (int) env('NASA_FIRMS_CACHE_TTL', 1800),
    ],

    'bmkg' => [
        'api_url' => env('BMKG_API_URL', 'https://api.bmkg.go.id'),
        'cache_ttl' => (int) env('BMKG_CACHE_TTL', 3600),
    ],

    'news' => [
        'cache_ttl' => (int) env('NEWS_CACHE_TTL', 900),
        'default_query' => env('NEWS_DEFAULT_QUERY', 'karhutla kalimantan'),
    ],

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Business Platform / Cloud API (Meta Official)
    |--------------------------------------------------------------------------
    */
    'whatsapp' => [
        'base_url' => env('WHATSAPP_API_BASE_URL', 'https://graph.facebook.com/v21.0'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'app_secret' => env('WHATSAPP_APP_SECRET'),
        'verify_token' => env('WHATSAPP_VERIFY_TOKEN'),
        'template_name' => env('WHATSAPP_DISPATCH_TEMPLATE', 'laporan_karhutla_disposisi'),
        'timeout' => (int) env('WHATSAPP_TIMEOUT', 15),
    ],

];
