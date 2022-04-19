<?php

declare(strict_types=1);

use App\Error\Renderer\HtmlErrorRenderer;
use Slim\App;

return static function (App $app) {

    $app->add(
        function ($request, $next) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $this->get('flash')->__construct($_SESSION);
    
            return $next->handle($request);
        }
    );

    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(false, true, true);
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    $errorHandler->registerErrorRenderer('text/html', HtmlErrorRenderer::class);
};