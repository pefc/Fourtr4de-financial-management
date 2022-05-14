<?php 

declare(strict_types=1);

namespace App\Handler\Deposits;

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


class DepositsHandler implements RequestHandlerInterface
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

    public function saveDeposit(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $response = new Response();

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
                $this->logger->error("Erro ao buscar informações da banca do usuário - ID");
                throw new \Exception("Não foi possível recupera as informações da sua banca.");
            }

            if ( empty(trim($data['deposited_at'])) || (float)$data['value'] <= 0.0 )
                throw new \Exception('Todos os campos obrigatórios devem ser preenchidos.'); 
                
            if ( strtotime($data['deposited_at']) > strtotime(date("Y-m-d H:i:s")) )
                throw new \Exception('A data do depósito não pode ser maior que data atual.');
            
            $created_at = new \DateTime($bankrollData[0]['created_at']);
            if ( strtotime($data['deposited_at']) < strtotime($created_at->format('Y-m-d')) )
                throw new \Exception('A data do depósito deverá ser maior que data de craição da sua banca.');
        }
        catch ( \Exception $e )
        {
            $this->flash->addMessage('error', $e->getMessage());
            return $response
                    ->withStatus(302)
                    ->withHeader('Location', $routeParser->urlFor('managementHome')); 
        }

        try
        {

            $sql = "
            INSERT INTO 
                deposits 
                (value, 
                deposited_at, 
                operation_code,
                bankroll_id)
            VALUES 
                (:value,
                :depositedAt,
                :operationCode,
                :bankrollId)";	
            $arrData = [
                'value' => (float)$data['value'],
                'depositedAt' => $data['deposited_at'],
                'operationCode' => $data['deposit_operation_code'],
                'bankrollId' => $bankrollData[0]['id']
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao registrar depósito");
                throw new \Exception("Não foi possível regitrar o depósito.");
            }

            $this->flash->addMessage('success', 'Depósito registrado com sucesso.');

            (new CashFlowHandler($this->logger, $this->container, $this->pdo))->saveCashFlow();
        }
        catch ( \Execption $e )
        {
            $this->flash->addMessage('error', $e->getMessage());
        }
        return $response
            ->withStatus(302)
            ->withHeader('Location', $routeParser->urlFor('managementHome')); 
    }
}