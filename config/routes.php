<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Handler\HomeHandler;
use App\Handler\Auth\AuthHandler;
use App\Handler\Users\UsersHandler;
use App\Middleware\PreLoadMiddleware;

return function (App $app) {

    $app->any('/home', HomeHandler::class)->setName('actionAuth')->add(PreLoadMiddleware::class);

    $app->get('/', AuthHandler::class)->setName('formLogin')->add(PreLoadMiddleware::class);;

    $app->group('/account', function (RouteCollectorProxy $groupUsers) {
        $groupUsers->post('/new', UsersHandler::class.':saveUser')->setName('actionNewUser');

        $groupUsers->get('/activate/{token}', UsersHandler::class.':activateUser')->setName('activateUser');
        $groupUsers->get('/no-active/{token}', UsersHandler::class.':formNoActiveUser')->setName('formNoActiveUser');
        $groupUsers->post('/resend-activation/{token}', UsersHandler::class.':resendActivation')->setName('actionResendActivation');

        $groupUsers->get('/edit/{id}', UsersHandler::class.':editUser')->setName('formEditUser');

        $groupUsers->post('/forgot-password', UsersHandler::class.':forgotPassword')->setName('actionForgotPassword');
        $groupUsers->get('/reset-password/{token}', UsersHandler::class.':formResetPassword')->setName('formResetPassword');
        $groupUsers->post('/save-new-password/{token}', UsersHandler::class.':saveNewPassword')->setName('actionResetPassword');
    })->add(PreLoadMiddleware::class);



    $app->get('/monitor', HomeHandler::class.':monitor')->setName('monitor');
};
