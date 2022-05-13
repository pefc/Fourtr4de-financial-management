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

        $_SESSION['menus'] = array();
        if ( !empty($_SESSION['user']['id']) )
        {
            $mainMenu = array();
            $accountMenu = array();

            $mainMenu[] = array(
                'href' => '#',
                'targetModal' => '#betsModal',
                'icon' => 'money-bill-transfer',
                'title' => 'Apostas'
            );
    
            $mainMenu[] = array(
                'href' => '#',
                'targetModal' => '#depositModal',
                'icon' => 'file-invoice-dollar',
                'title' => 'Depósitos'
            );
    
            $mainMenu[] = array(
                'href' => '#',
                'targetModal' => '#withdrawalModal',
                'icon' => 'hand-holding-dollar',
                'title' => 'Saques'
            );
    
            $mainMenu[] = array(
                'href' => '#',
                'targetModal' => '#projectionModal',
                'icon' => 'chart-line',
                'title' => 'Projeção'
            );
    
            $mainMenu[] = array(
                'href' => '#',
                'targetModal' => '',
                'icon' => 'receipt',
                'title' => 'Hstórico'
            );

            $mainMenu[] = array(
                'href' => '#',
                'targetModal' => '#configModal',
                'icon' => 'cog',
                'title' => 'Configurações da Banca'
            );

            $accountMenu[] = array(
                'href' => '#',
                'targetModal' => '#myAccountModal',
                'title' => 'Minha conta',
                'line' => '<li><hr class="border-t border-dotted  mx-10 border-gray-400"></li>'
            );

            $accountMenu[] = array(
                'href' => '#',
                'targetModal' => '',
                'title' => 'Pagamento',
                'line' => '<li><hr class="border-t border-dotted  mx-10 border-gray-400"></li>'
            );

            $accountMenu[] = array(
                'href' => $routeParser->urlFor('logoutAccount'),
                'targetModal' => '',
                'title' => 'Sair',
                'line' => ''
            );
            

            $_SESSION['pageData'] = [
                'menus' => [
                    'mainMenu' => $mainMenu, 
                    'accountMenu' => $accountMenu
                ], 
                'user' => [
                    'name' => $_SESSION['user']['name']
                ]
            ];
        }
        

        $response = $handler->handle($request);
        return $response;
    }
}