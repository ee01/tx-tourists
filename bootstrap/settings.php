<?php

declare(strict_types=1);

use function App\env;

return [
    'app' => [
        'name'   => env('APP_NAME', 'Slim 4 Starter'),
        'env'    => env('APP_ENV', 'production'),
        'debug'  => env('APP_DEBUG', false),
        'locale' => 'en',
    ],
    'db' => [
        'host'     => env('DB_HOST', 'localhost'),
        'database' => env('DB_DATABASE', 'your_database_name'),
        'username' => env('DB_USERNAME', 'your_username'),
        'password' => env('DB_PASSWORD', 'your_password'),
        'charset'  => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'   => '',
    ],
    'view' => [
        'path'  => '../resources/views',
        'cache' => false,
    ],
];
