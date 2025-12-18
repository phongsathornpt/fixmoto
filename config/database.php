<?php

return [
    'driver' => Env::get('DB_CONNECTION', 'sqlite'),
    'host' => Env::get('DB_HOST', 'localhost'),
    'database' => Env::get('DB_NAME', 'fixmoto'),
    'username' => Env::get('DB_USER', 'root'),
    'password' => Env::get('DB_PASS', ''),
    'charset' => Env::get('DB_CHARSET', 'utf8'),
    'sqlite' => [
        'database' => Env::get('DB_DATABASE', __DIR__ . '/../database/database.sqlite'),
    ],
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
