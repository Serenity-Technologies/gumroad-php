<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gumroad Access Token
    |--------------------------------------------------------------------------
    |
    | Your Gumroad API access token. You can generate this from your Gumroad
    | application settings page.
    |
    */
    
    'access_token' => env('GUMROAD_ACCESS_TOKEN', ''),
    
    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Gumroad API. This should normally not be changed.
    |
    */
    
    'base_url' => env('GUMROAD_BASE_URL', 'https://api.gumroad.com/v2'),
    
    /*
    |--------------------------------------------------------------------------
    | HTTP Client Options
    |--------------------------------------------------------------------------
    |
    | Configuration options for the underlying HTTP client.
    |
    */
    
    'http' => [
        'timeout' => 30,
        'connect_timeout' => 10,
    ],
];