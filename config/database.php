<?php

return [

    'default' => env('DB_CONNECTION', 'pgsql'),

    'connections' => [

         'pgsql' => [
        'driver'   => 'pgsql',
        'host'     => env('DB_HOST', 'aws-0-ap-southeast-2.pooler.supabase.com'),
        'port'     => env('DB_PORT', '6543'), // port pooler Supabase
        'database' => env('DB_DATABASE', 'postgres'),
        'username' => env('DB_USERNAME', 'postgres.bfwyxlovlfiswcexdibt'), // sesuai user di dashboard
        'password' => env('DB_PASSWORD', 'Magangbni2026'), // password Supabase kamu
        'charset'  => 'utf8',
        'prefix'   => '',
        'schema'   => 'public',
        'sslmode'  => 'require',
        ],


    ],

];
