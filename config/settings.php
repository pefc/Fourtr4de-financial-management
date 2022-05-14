<?php

declare(strict_types=1);

use Monolog\Logger;

return static function(array $appEnv) {
    $settings =  [
        'app_env' => $appEnv['APP_ENV'],
        'di_compilation_path' => __DIR__ . '/../var/cache',
        'display_error_details' => false,
        'log_errors' => true,

        'site_url' => $appEnv['SITE_URL'],

        'secret_key' => $appEnv['SECRET_KET'],

        'logger' => [
            'name' => 'fourtr4de.management',
            'path' => 'php://stderr',
            'level' => Logger::DEBUG,
        ],

        "db" => [
            'host' => $appEnv['DB_HOST'],
            'port' => $appEnv['DB_PORT'],
            'database' => $appEnv['DB_DATABASE'],
            'username' => $appEnv['DB_USERNAME'],
            'password' => $appEnv['DB_PASSWORD'],
        ],

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
    ];

    

    if ($appEnv['APP_ENV'] === 'DEVELOPMENT')
        $settings['display_error_details'] = true;

    $settings['di_compilation_path'] = '';
    $settings['logger']['path'] = __DIR__ . '/../var/log/app.log';

    return $settings;
};
