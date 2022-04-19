<?php

declare(strict_types=1);

use Monolog\Logger;

return static function(array $appEnv) {
    $settings =  [
        'app_env' => $appEnv['APP_ENV'],
        'di_compilation_path' => __DIR__ . '/../var/cache',
        'display_error_details' => false,
        'log_errors' => true,

        'logger' => [
            'name' => 'pp9787',
            'path' => 'php://stderr',
            'level' => Logger::DEBUG,
        ],

        // "db" => [
        //     'host' => $appEnv['DB_HOST'],
        //     'port' => $appEnv['DB_PORT'],
        //     'database' => $appEnv['DB_DATABASE'],
        //     'username' => $appEnv['DB_USERNAME'],
        //     'password' => $appEnv['DB_PASSWORD'],
        // ],

        'twig' => [
            'path_templates' => __DIR__ . '/../view',
            'path_cache' => false
        ],
        
        'smtp' => [
            'type' => $appEnv['SMTP_TYPE'],
            'host' => $appEnv['SMTP_HOST'],
            'port' => $appEnv['SMTP_PORT'],
            'username' => $appEnv['SMTP_USERNAME'],
            'password' => $appEnv['SMTP_PASSWORD'],
            'from' => $appEnv['SMTP_FROM'],
        ],

        'hybridauth' => [
            'callback' => $appEnv['SITE_IP'].'/login/callback',
            'providers' => [
                'Google' => [
                    'enabled' => true,
                    'keys' => [
                        'id' => $appEnv['GOOGLE_ID'],
                        'secret' => $appEnv['GOOGLE_SECRET'],
                    ]
                ],
                // 'Facebook' => [
                //     'enabled' => true, 
                //     'keys' => [
                //         'id' => $appEnv['FACEBOOK_ID'], 
                //         'secret' => $appEnv['FACEBOOK_SECRET']
                //     ]
                // ]
            ],
        ],
    ];

    

    if ($appEnv['APP_ENV'] === 'DEVELOPMENT')
        $settings['display_error_details'] = true;

    $settings['di_compilation_path'] = '';
    $settings['logger']['path'] = __DIR__ . '/../var/log/app.log';

    return $settings;
};
