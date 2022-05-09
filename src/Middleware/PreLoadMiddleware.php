<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use Slim\Psr7\Response;
use Psr\Container\ContainerInterface;
use PDO;

class PreLoadMiddleware
{

    private $container;
    private $pdo;

    public function __construct(ContainerInterface $container, PDO $pdo)
    {
        $this->container = $container;
        $this->pdo = $pdo;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): Response
    {
        $response = new Response();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        if ( empty($_SESSION['descriptionTerms']) || empty($_SESSION['descriptionPolices']) )
        {
            $stmt = $this->pdo->prepare("SELECT id, description FROM terms_polices WHERE type = 'T' and status = 'A' ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            $terms = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $this->pdo->prepare("SELECT id, description FROM terms_polices WHERE type = 'P' and status = 'A' ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            $polices = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $_SESSION['idTerms'] = $terms[0]["id"];
            $_SESSION['descriptionTerms'] = $terms[0]["description"];
            $_SESSION['descriptionPolices'] = $polices[0]["description"];
        }

        $response = $handler->handle($request);
        return $response;
    }
}