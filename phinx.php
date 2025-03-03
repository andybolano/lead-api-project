<?php

return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => getenv('DB_HOST') ?: '127.0.0.1',
            'name' => getenv('DB_DATABASE') ?: 'lead_db',
            'user' => getenv('DB_USERNAME') ?: 'root',
            'pass' => getenv('DB_PASSWORD') ?: 'root',
            'port' => '3306',
            'charset' => 'utf8mb4',
        ],
    ],
    'version_order' => 'creation'
];
