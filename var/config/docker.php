<?php

/**
 * Config
 */
return [

    /*
    |--------------------------------------------------------------------------
    | App Settings
    |--------------------------------------------------------------------------
    */
    'settings' => [
        'route_cache' => '/var/cache/routes.php',
        /*
        |--------------------------------------------------------------------------
        | Database Config
        |--------------------------------------------------------------------------
        */
        'db' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'localhost'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', 'password'),
            'database' => env('DB_DATABASE', 'vagrant'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]
    ],

    'dev_mode' => true,
    'app_env' => 'vagrant',
    'force_ssl' => false,
    'run_dir' => '/var/www/html',
    'app_url' => 'vagrant.local',
    'encryption_key' => '__encryption_key__',

    /*
    |--------------------------------------------------------------------------
    | Log
    |--------------------------------------------------------------------------
    */
    'logs' => [
        'log_folder' => '/var/logs',
        'log_level' => \Psr\Log\LogLevel::DEBUG,
        'std_out' => true
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis
    |--------------------------------------------------------------------------
    */
    'redis' => [
        'host' => env('REDIS_HOST', 'localhost:6379'),
        'max_requests' => env('REDIS_MAX_REQUESTS', 20),
        'expire' => env('REDIS_EXPIRE', 10),
    ],

    /*
   |--------------------------------------------------------------------------
   | AWS
   |--------------------------------------------------------------------------
   */
    'aws' => [
        /*
        |--------------------------------------------------------------------------
        | S3
        |--------------------------------------------------------------------------
        */
        's3' => [
            'bucket' => 'bucket',
            'config' => [
                'credentials' => [
                    'key' => '__KEY__',
                    'secret' => '__SECRET__'
                ],
                'region' => 'us-east-1',
                'version' => 'latest'
            ]
        ],

        /*
        |--------------------------------------------------------------------------
        | sqs
        |--------------------------------------------------------------------------
        */
        'sqs' => [
            'config' => [
                'credentials' => [
                    'key' => '__KEY__',
                    'secret' => '__SECRET__'
                ],
                'region' => 'us-east-1',
                'version' => 'latest'
            ],
            'queue' => 'http://localhost:4100/queue/vagrant',
        ]
    ]
];
