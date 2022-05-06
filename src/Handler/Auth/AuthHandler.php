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

        $stmt = $this->pdo->prepare("SELECT id, description FROM terms_polices WHERE type = 'T' and status = 'A' ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $terms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->pdo->prepare("SELECT id, description FROM terms_polices WHERE type = 'P' and status = 'A' ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $polices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $args['idTerms'] = $terms[0]["id"];
        $args['descriptionTerms'] = $terms[0]["description"];

        $args['descriptionPolices'] = $polices[0]["description"];

        $response = new Response();
        $response->getBody()->write(
            $this->twig->render('public/index.twig', $args)
        );
        return $response;
    }
}
