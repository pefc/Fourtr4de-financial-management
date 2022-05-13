<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use Slim\Psr7\Response;
use Psr\Container\ContainerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Flash\Messages;
use PDO;

class CheckNoAuthenticatedMiddleware
{

    private $container;
    private $flash;
    private $pdo;

    public function __construct(ContainerInterface $container, \Slim\Flash\Messages $flash, PDO $pdo)
    {
        $this->container = $container;
        $this->flash = $flash;
        $this->pdo = $pdo;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): Response
    {
        $response = new Response();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        if ( empty($_COOKIE['token']) || empty($_SESSION['user']['id']) )
        {
            return $response
                ->withStatus(302)
                ->withHeader('Location', $routeParser->urlFor('logoutAccount'));
        }
        else
        {
            $key = $this->container->get('settings')['secret_key'];
            $decoded = JWT::decode($_COOKIE['token'], new Key($key, 'HS256'));
            
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE token = :userToken AND status = 'A' LIMIT 1");
            $stmt->execute(['userToken' => $decoded->user]);
            $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ( empty($userData[0]['id']) )
            {
                return $response
                    ->withStatus(302)
                    ->withHeader('Location', $routeParser->urlFor('logoutAccount'));
            }
        }

        $response = $handler->handle($request);
        return $response;
    }
}