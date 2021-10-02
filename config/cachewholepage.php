<?php
return [
    'name'          => 'cachewholepage',
    'cache_ttl'     => env('CACHE_WP_TTL', 10),
    'lock_ttl'      => 5,
    'usage_logging' => true,
    'excludes'      => [
        'admin',
        'login',
        'register',
        'password',
        'social_login',
    ],
];
