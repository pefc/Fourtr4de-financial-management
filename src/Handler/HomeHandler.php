<?php 

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Psr\Container\ContainerInterface;
use Hybridauth\Hybridauth;
use Hybridauth\Exception\Exception;
use Hybridauth\HttpClient;
use Hybridauth\Storage\Session;
use Slim\Flash\Messages;
// use PDO;

class HomeHandler implements RequestHandlerInterface
{
    private $logger;
    private $twig;
    private $container;
    private $flash;
    // private $pdo;
    private $mailer;

    public function __construct(LoggerInterface $logger, \Twig\Environment $twig, ContainerInterface $container, \Slim\Flash\Messages $flash, MailHandler $mailer)//, PDO $pdo,)
    {
        $this->logger = $logger;
        $this->twig = $twig;
        $this->container = $container;
        $this->flash = $flash;
        // $this->pdo = $pdo;
        $this->mailer = $mailer;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {        
        $response = new Response();
        $response->getBody()->write(
            $this->twig->render('home.twig')
        );
        return $response;
    }
}
