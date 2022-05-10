<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Handler\HomeHandler;
use App\Handler\Auth\AuthHandler;
use App\Handler\Users\UsersHandler;
use App\Middleware\PreLoadMiddleware;
use App\Middleware\CheckNoAuthenticatedMiddleware;
use App\Middleware\CheckAuthenticatedMiddleware;

return function (App $app) {

    $app->get('/', AuthHandler::class)->setName('formLogin')->add(PreLoadMiddleware::class)->add(CheckAuthenticatedMiddleware::class);

    
    $app->group('/auth', function (RouteCollectorProxy $groupAuth) {
        $groupAuth->post('/login', AuthHandler::class.':loginAccount')->setName('actionLoginAccount');
        $groupAuth->get('/logout', AuthHandler::class.':logoutAccount')->setName('logoutAccount');
    });
    

    $app->group('/account', function (RouteCollectorProxy $groupUsers) {
        $groupUsers->post('/new', UsersHandler::class.':saveUser')->setName('actionNewUser');

        $groupUsers->get('/activate/{token}', UsersHandler::class.':activateUser')->setName('activateUser');
        $groupUsers->get('/no-active/{token}', UsersHandler::class.':formNoActiveUser')->setName('formNoActiveUser');
        $groupUsers->post('/resend-activation/{token}', UsersHandler::class.':resendActivation')->setName('actionResendActivation');

        $groupUsers->post('/forgot-password', UsersHandler::class.':forgotPassword')->setName('actionForgotPassword');
        $groupUsers->get('/reset-password/{token}', UsersHandler::class.':formResetPassword')->setName('formResetPassword');
        $groupUsers->post('/save-new-password/{token}', UsersHandler::class.':saveNewPassword')->setName('actionResetPassword');
    })->add(PreLoadMiddleware::class)->add(CheckAuthenticatedMiddleware::class);


    $app->group('/management', function (RouteCollectorProxy $groupManagement) {
        $groupManagement->get('', HomeHandler::class)->setName('managementHome');
        $groupManagement->get('/edit/{id}', UsersHandler::class.':editUser')->setName('formEditUser');
    })->add(PreLoadMiddleware::class)->add(CheckNoAuthenticatedMiddleware::class);
};
