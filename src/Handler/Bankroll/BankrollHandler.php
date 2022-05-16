<?php 

declare(strict_types=1);

namespace App\Handler\Bankroll;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Psr\Container\ContainerInterface;
use Slim\Flash\Messages;
use PDO;
use App\Handler\CashFlow\CashFlowHandler;


class BankrollHandler implements RequestHandlerInterface
{
    private $logger;
    private $container;
    private $flash;
    private $pdo;

    public function __construct(LoggerInterface $logger, ContainerInterface $container, \Slim\Flash\Messages $flash, PDO $pdo)
    {
        $this->logger = $logger;
        $this->container = $container;
        $this->flash = $flash;
        $this->pdo = $pdo;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {        
        $response = new Response();
        return $response;
    }

    public function saveBankroll(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $response = new Response();

        try 
        {
            if ( empty(trim($data['initial_bankroll'])) || empty(trim($data['yield'])) || empty(trim($data['stop_win'])) || empty(trim($data['stop_loss'])) )
                throw new \Exception('Todos os campos obrigatórios devem ser preenchidos.'); 
                
            if ( (float)$data['initial_bankroll'] < 0.75 || (float)$data['yield'] < 0.1 || (float)$data['stop_win'] < 0.1 || (float)$data['stop_loss'] < 0.1 )
                throw new \Exception('Os valores informados não são válidos.');   
        }
        catch ( \Exception $e )
        {
            $this->flash->addMessage('error', $e->getMessage());
            return $response
                    ->withStatus(302)
                    ->withHeader('Location', $routeParser->urlFor('dashboard')); 
        }

        try
        {
            $stmt = $this->pdo->prepare("SELECT id, created_at FROM bankroll WHERE users_id = :userId AND status = 'A' LIMIT 1");
            if ( $stmt->execute(['userId' => $_SESSION['user']['id']]) === false )
            {
                $this->logger->error("Erro ao buscar informações da banca do usuário");
                throw new \Exception("Não foi possível recupera as informações da sua banca.");
            }

            $bankrollData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( empty($bankrollData[0]['id']) )
            {
                $sql = "
                INSERT INTO 
                    bankroll (initial_bankroll, 
                    yield, 
                    stop_win, 
                    stop_loss,
                    users_id)
                VALUES (:initialBankroll, 
                        :yield, 
                        :stopWin, 
                        :stopLoss,
                        :userId)";	
                $arrData = [
                    'initialBankroll' => (float)$data['initial_bankroll'],
                    'yield' => (float)$data['yield'],
                    'stopWin' => (float)$data['stop_win'],
                    'stopLoss' => (float)$data['stop_loss'],
                    'userId' => $_SESSION['user']['id'],
                ];
                $stmt = $this->pdo->prepare($sql);
                if ( $stmt->execute($arrData) === false )
                {
                    $this->logger->error("Erro ao cadastrar configurações da banca");
                    throw new \Exception("Não foi possível cadastrar as configurações da banca.");
                }

                $this->flash->addMessage('success', 'Configurações da banca cadastradas com sucesso.');
            }
            else
            {
                $sql = "
                UPDATE
                    bankroll
                SET
                    initial_bankroll = :initialBankroll,
                    yield = :yield,
                    stop_win = :stopWin,
                    stop_loss = :stopLoss,
                    created_at = :createdAt,
                    updated_at = now()
                WHERE
                    users_id = :userId AND
                    id = :bankrollId";	
                $arrData = [
                    'initialBankroll' => (float)$data['initial_bankroll'],
                    'yield' => (float)$data['yield'],
                    'stopWin' => (float)$data['stop_win'],
                    'stopLoss' => (float)$data['stop_loss'],
                    'createdAt' => $bankrollData[0]['created_at'],
                    'userId' => $_SESSION['user']['id'],
                    'bankrollId' => $bankrollData[0]['id'],
                ];
                $stmt = $this->pdo->prepare($sql);
                if ( $stmt->execute($arrData) === false )
                {
                    $this->logger->error("Erro ao cadastrar configurações da banca");
                    throw new \Exception("Não foi possível cadastrar as configurações da banca.");
                }

                $this->flash->addMessage('success', 'Configurações da banca alteradas com sucesso.');
            }
            (new CashFlowHandler($this->logger, $this->container, $this->pdo))->saveCashFlow();
        }
        catch ( \Execption $e )
        {
            $this->flash->addMessage('error', $e->getMessage());
        }
        return $response
            ->withStatus(302)
            ->withHeader('Location', $routeParser->urlFor('dashboard')); 
    }


    public function deleteBankroll(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $response = new Response();

        try 
        {
            $stmt = $this->pdo->prepare("SELECT id FROM bankroll WHERE users_id = :userId AND status = 'A' LIMIT 1");
            if ( $stmt->execute(['userId' => $_SESSION['user']['id']]) === false )
            {
                $this->logger->error("Erro ao buscar informações da banca do usuário");
                throw new \Exception("Não foi possível recupera as informações da sua banca.");
            }

            $bankrollData = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( empty($bankrollData[0]['id']) )
                throw new \Exception("Ainda não existe banca cadastrada para esta conta.");

            $sql = "
            DELETE FROM 
                deposits 
            WHERE 
                bankroll_id = :bankrollId";	
            $arrData = [
                'bankrollId' => $bankrollData[0]['id'],
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao excluir informações dos depósitos do usuário");
                throw new \Exception("Não foi possível excluir todos os registros.");
            }

            $sql = "
            DELETE FROM 
                withdrawals 
            WHERE 
                bankroll_id = :bankrollId";	
            $arrData = [
                'bankrollId' => $bankrollData[0]['id'],
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao excluir informações dos saques do usuário");
                throw new \Exception("Não foi possível excluir todos os registros.");
            }

            $sql = "
            DELETE FROM 
                cash_flow 
            WHERE 
                bankroll_id = :bankrollId";	
            $arrData = [
                'bankrollId' => $bankrollData[0]['id'],
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao excluir informações do fluxo de caixa do usuário");
                throw new \Exception("Não foi possível excluir todos os registros.");
            }

            $sql = "
            DELETE FROM 
                operations 
            WHERE 
                bankroll_id = :bankrollId";	
            $arrData = [
                'bankrollId' => $bankrollData[0]['id'],
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao excluir informações das operações do usuário");
                throw new \Exception("Não foi possível excluir todos os registros.");
            }

            $sql = "
            DELETE FROM 
                bankroll 
            WHERE 
                id = :bankrollId";	
            $arrData = [
                'bankrollId' => $bankrollData[0]['id'],
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao excluir informações da banca do usuário");
                throw new \Exception("Não foi possível excluir todos os registros.");
            }

            $this->flash->addMessage('success', 'Registros excluídos com sucesso.');
        }
        catch ( \Execption $e )
        {
            $this->flash->addMessage('error', $e->getMessage());
        }

        return $response
            ->withStatus(302)
            ->withHeader('Location', $routeParser->urlFor('dashboard')); 
    }
}