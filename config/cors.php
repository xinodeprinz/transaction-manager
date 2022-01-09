<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
            'api/*', 
            'sanctum/csrf-cookie', 
            '/api/payment',

            '/api/account/create',
            '/api/account/login',
            '/api/account/user/delete/{id}',
            '/api/account/block_unblock/{id}',
            '/api/account/user',
            '/api/account/logout',
            '/api/account/pro/create',
            '/api/account/pro/details',
            '/api/account/expired',
            '/api/account/employee/create',
            '/api/account/employees',
            '/api/account/employee/delete/{id}',
            '/api/account/username',
            '/api/account/user/{username}',

            '/api/category/create',
            '/api/category/all',
            '/api/category/products/{id}',
            '/api/category/delete/{id}',
            '/api/category/change/{id}',

            '/api/product/create',
            '/api/product/id/{id}',
            '/api/product/name/{name}',
            '/api/product/all',
            '/api/product/update/quantity/{id}',
            '/api/product/update/unit-price/{id}',
            '/api/product/update/selling-price/{id}',
            '/api/product/delete/{id}',
            '/api/product/expenses',

            '/api/sale/sell/{id}',
            '/api/sale/all',
            '/api/sale/expenses',
        ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
