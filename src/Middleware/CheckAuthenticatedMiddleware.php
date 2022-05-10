<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use Slim\Psr7\Response;
use Psr\Container\ContainerInterface;

class CheckAuthenticatedMiddleware
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): Response
    {
        $response = new Response();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        if ( !empty($_SESSION['user']['id']) )
        {            
            return $response
                ->withStatus(302)
                ->withHeader('Location', $routeParser->urlFor('managementHome'));
        }

        $response = $handler->handle($request);
        return $response;
    }
}