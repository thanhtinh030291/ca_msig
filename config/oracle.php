<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_ORA_TNS', ''),
        'host'           => env('DB_ORA_HOST', ''),
        'port'           => env('DB_ORA_PORT', '1521'),
        'database'       => env('DB_ORA_DATABASE', ''),
        'username'       => env('DB_ORA_USERNAME', ''),
        'password'       => env('DB_ORA_PASSWORD', ''),
        'charset'        => env('DB_ORA_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_ORA_PREFIX', ''),
        'prefix_schema'  => env('DB_ORA_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_ORA_EDITION', 'ora$base'),
        'server_version' => env('DB_ORA_SERVER_VERSION', '11g'),
    ]
];

