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
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


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
        $args['actionLoginAccount'] = $routeParser->urlFor('actionLoginAccount');

        $args['idTerms'] = $_SESSION['idTerms'];
        $args['descriptionTerms'] = $_SESSION['descriptionTerms'];
        $args['descriptionPolices'] = $_SESSION['descriptionPolices'];

        $response = new Response();
        $response->getBody()->write(
            $this->twig->render('public/index.twig', $args)
        );
        return $response;
    }

    public function loginAccount(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $response = new Response();

        if ( empty($data['identifier']) || empty($data['password'])  )
        {
            $this->flash->addMessage('error', 'Usuário não autorizado.');
            return $response
                ->withStatus(302)
                ->withHeader('Location', $routeParser->urlFor('formLogin'));
        }
        else
        {
            $stmt = $this->pdo->prepare("SELECT id, name, verified, token FROM users WHERE identifier = :formIdentifier AND password = :formPassword AND status = 'A' LIMIT 1");
            $stmt->execute(['formIdentifier' => $data['identifier'], 'formPassword' => sha1($data['password'])]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ( !empty($userData) )
            {
                if ( (bool)$userData[0]['verified'] === false )
                {
                    return $response
                    ->withStatus(302)
                    ->withHeader('Location', $routeParser->urlFor('formNoActiveUser', ['token' => $userData[0]['token'] ]));
                }

                $key = $this->container->get('settings')['secret_key'];

                $tokenToJwt = array(
                    "user" => $userData[0]['id'],
                    "name" => $userData[0]['name'],
                    "iss" => $this->container->get('settings')['site_url'],
                    "aud" => $this->container->get('settings')['site_url'],
                );

                $_SESSION['user'] = array('id' => $userData[0]['id'], 'name' => $userData[0]['name']);

                $jwt = JWT::encode($tokenToJwt, $key, 'HS256');

                setcookie("token", $jwt);
    
                return $response
                    ->withStatus(302)
                    ->withHeader('Location', $routeParser->urlFor('managementHome'));
            }
            else
            {
                $this->flash->addMessage('error', 'Usuário não autorizado.');
                return $response
                    ->withStatus(302)
                    ->withHeader('Location', $routeParser->urlFor('formLogin'));
            }
        }       
    }

    public function logoutAccount(ServerRequestInterface $request): ResponseInterface
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        unset($_COOKIE['token']);
        setcookie('token', "", time()-3600, "/"); 
        session_destroy();

        $response = new Response();
        return $response
            ->withStatus(302)
            ->withHeader('Location', $routeParser->urlFor('formLogin'));
    }
}
