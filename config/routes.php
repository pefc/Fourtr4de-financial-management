<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Handler\HomeHandler;
use App\Handler\Auth\AuthHandler;
use App\Handler\Users\UsersHandler;

return function (App $app) {

    $app->any('/home', HomeHandler::class)->setName('actionAuth');

    $app->get('/', AuthHandler::class)->setName('formLogin');

    $app->group('/users', function (RouteCollectorProxy $groupUsers) {
        $groupUsers->post('/new', UsersHandler::class.':saveUser')->setName('actionNewUser');
        $groupUsers->post('/edit/{id}', UsersHandler::class.':editUser')->setName('actionEditUser');
        $groupUsers->post('/forgot-password', UsersHandler::class.':forgotPassword')->setName('actionForgotPassword');
        $groupUsers->post('/activate/{tokenIdentifier}/{tokenEmail}', UsersHandler::class.':activateUser')->setName('activateUser');
        
    });


};
