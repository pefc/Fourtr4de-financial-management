<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

use App\Middleware\PreLoadMiddleware;
use App\Middleware\CheckNoAuthenticatedMiddleware;
use App\Middleware\RedirectAuthenticatedMiddleware;

use App\Handler\HomeHandler;
use App\Handler\Auth\AuthHandler;
use App\Handler\Users\UsersHandler;
use App\Handler\Bankroll\BankrollHandler;
use App\Handler\Operations\OperationsHandler;
use App\Handler\Deposits\DepositsHandler;
use App\Handler\Withdrawals\WithdrawalsHandler;


return function (App $app) {

    $app->get('/', AuthHandler::class)->setName('formLogin')->add(PreLoadMiddleware::class)->add(RedirectAuthenticatedMiddleware::class);

    
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
    })->add(PreLoadMiddleware::class)->add(RedirectAuthenticatedMiddleware::class);


    $app->group('/management', function (RouteCollectorProxy $groupManagement) {
        $groupManagement->get('', HomeHandler::class)->setName('managementHome');
        // $groupManagement->get('/account/edit/{id}', UsersHandler::class.':editUser')->setName('formEditUser');

        $groupManagement->group('/bankroll', function (RouteCollectorProxy $groupBankroll) {
            $groupBankroll->post('/save', BankrollHandler::class.':saveBankroll')->setName('actionRegisterBankroll');
            $groupBankroll->post('/delete', BankrollHandler::class.':deleteBankroll')->setName('actionDeleteBankroll');
        });

        $groupManagement->post('/operation/save', OperationsHandler::class.':saveOperation')->setName('actionSaveOperation');

        $groupManagement->post('/deposit/save', DepositsHandler::class.':saveDeposit')->setName('actionSaveDeposit');

        $groupManagement->post('/withdrawal/save', WithdrawalsHandler::class.':saveWithdrawal')->setName('actionSaveWithdrawal');

    })->add(PreLoadMiddleware::class)->add(CheckNoAuthenticatedMiddleware::class);
};
