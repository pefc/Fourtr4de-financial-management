<?php 

declare(strict_types=1);

namespace App\Handler\Auth;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Psr\Container\ContainerInterface;
use Slim\Flash\Messages;
use PDO;
use App\Handler\Mailer\MailHandler;


class AuthHandler implements RequestHandlerInterface
{
    private $logger;
    private $twig;
    private $container;
    private $flash;
    private $pdo;
    private $mailer;

    public function __construct(LoggerInterface $logger, \Twig\Environment $twig, ContainerInterface $container, \Slim\Flash\Messages $flash, MailHandler $mailer, PDO $pdo)
    {
        $this->logger = $logger;
        $this->twig = $twig;
        $this->container = $container;
        $this->flash = $flash;
        $this->pdo = $pdo;
        $this->mailer = $mailer;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {        
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $args = array();
        $args['actionNewUser'] = $routeParser->urlFor('actionNewUser');
        $args['actionForgotPassword'] = $routeParser->urlFor('actionForgotPassword');
        $args['actionAuth'] = $routeParser->urlFor('actionAuth');

        $args['idTerms'] = $_SESSION['idTerms'];
        $args['descriptionTerms'] = $_SESSION['descriptionTerms'];
        $args['descriptionPolices'] = $_SESSION['descriptionPolices'];

        $response = new Response();
        $response->getBody()->write(
            $this->twig->render('public/index.twig', $args)
        );
        return $response;
    }
}
