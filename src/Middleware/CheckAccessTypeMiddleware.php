<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use Slim\Psr7\Response;
use Psr\Container\ContainerInterface;

class CheckAccessTypeMiddleware
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

        if ( $_SERVER['HTTP_HOST'] == $this->container->get('settings')['site_host'] )
        {
            return $response
                ->withStatus(200)
                ->withHeader('Location', $routeParser->urlFor('formCadastro'));
        }

        $response = $handler->handle($request);
        return $response;
    }
}