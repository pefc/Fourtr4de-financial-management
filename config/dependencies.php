<?php declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Settings\SettingsInterface;
use Slim\Flash\Messages;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;

return static function (ContainerBuilder $containerBuilder, array $settings) {
    $containerBuilder->addDefinitions([
        'settings' => $settings,

        'flash' => function () {
            $storage = [];
            return new Messages($storage);
        },

        LoggerInterface::class => function (ContainerInterface $c): Logger {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        \Twig\Environment::class => function (ContainerInterface $c) use ($settings): Environment {
            $twigSettings = $settings['twig'];
            $loader = new Twig\Loader\FilesystemLoader($twigSettings['path_templates']);
            $twig = new Twig\Environment($loader, [
                $twigSettings['path_cache']
            ]);
            
            $flash = $c->get('flash');
            $twig->addGlobal('flash', $flash);

            if ($settings['app_env'] === 'DEVELOPMENT') {
                $twig->enableDebug();
            }

            return $twig;
        },

        PDO::class => function (ContainerInterface $c, LoggerInterface $logger) {
            $settings = $c->get('settings');

            $host = $settings['db']['host'];
            $dbname = $settings['db']['database'];
            $username = $settings['db']['username'];
            $password = $settings['db']['password'];
            $port = $settings['db']['port'];
            try {
                $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
                return $conn;
            }
            catch( PDOException $Exception ) {
                $logger->error($Exception->getMessage().'; FILE: '.$Exception->getFile().'; LINE: '.$Exception->getLine());
                throw new $Exception( $Exception->getMessage());
            }
        },

        MailerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings')['smtp'];
            
            // smtp://user:pass@smtp.example.com:25
            $dsn = sprintf(
                '%s://%s:%s@%s:%s',
                $settings['type'],
                $settings['username'],
                $settings['password'],
                $settings['host'],
                $settings['port']
            );
    
            return new Mailer(Transport::fromDsn($dsn));
        },

    ]);
};


