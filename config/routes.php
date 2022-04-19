<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
// use App\Middleware\CheckAccessTypeMiddleware;
use App\Handler\HomeHandler;
use App\Handler\LoginHandler;

return function (App $app) {

    $app->any('/home', HomeHandler::class)->setName('home');

    $app->any('/', LoginHandler::class)->setName('login');

};
