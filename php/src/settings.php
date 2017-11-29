<?php

/** @var \Dotenv\Dotenv $dotenv */
$dotenv = new Dotenv\Dotenv(__DIR__ . "/..", (getenv("TRAVIS") == true) ? ".env.example" : ".env");
$dotenv->load();

return [
    'settings' => [
        // If you put HelloSlim3 in production, change it to false
        'displayErrorDetails' => true,

        // Renderer settins: where are the templates???
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings: where are the logs???
        'logger' => [
            'name' => 'infojapos',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        'db' => [
            'host'     => getenv('DB_HOST'),
            'dbname'   => getenv('DB_NAME'),
            'user'     => getenv('DB_USER'),
            'password' => getenv('DB_PASS'),
        ],
        
        'admin_users' => [
            getenv('ADMIN_USER') => getenv('ADMIN_PASS')
        ],
    ],
];
