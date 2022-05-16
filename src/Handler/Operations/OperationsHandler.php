<?php 

declare(strict_types=1);

namespace App\Handler\Operations;

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


class OperationsHandler implements RequestHandlerInterface
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

    public function saveOperation(ServerRequestInterface $request): ResponseInterface
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

            if ( empty(trim($data['bet_at'])) || $data['value_bet'] == '' || empty($data['odd']) || empty($data['operation_result']) || empty($data['operation_credit']) )
                throw new \Exception('Todos os campos obrigatórios devem ser preenchidos.'); 
                
            if ( strtotime($data['bet_at']) > strtotime(date("Y-m-d H:i:s")) )
                throw new \Exception('A data da operação não pode ser maior que data atual.');
                
            $created_at = new \DateTime($bankrollData[0]['created_at']);
            if ( strtotime($data['bet_at']) < strtotime($created_at->format('Y-m-d')) )
                throw new \Exception('A data da operação deverá ser maior que data de craição da sua banca.');
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
            $value_bet = (float)$data['value_bet'];

            // GREEN = 1 / RED = 2
            if ( (int)$data['operation_result'] == 1 )
            {
                // COM CRÉDITO = 2 / SEM CRÉDITO = 1
                if ( (int)$data['operation_credit'] == 2 )
                {
                    $returnedValue = ((float)$data['odd']*$value_bet)-$value_bet;
                    $result = $returnedValue;
                }
                elseif ( (int)$data['operation_credit'] == 1 )
                {
                    $returnedValue = (float)$data['odd']*$value_bet;
                    $result = $returnedValue-$value_bet;
                }
            }
            else
            {
                $returnedValue = 0;
                $result = $returnedValue-$value_bet;
            }


            $sql = "
            INSERT INTO 
                operations 
                (bet_at, 
                value_bet, 
                returned_value, 
                result,
                odd,
                operation_code,
                with_credit,
                bankroll_id)
            VALUES 
                (:betAt, 
                :valueBet, 
                :returnedValue, 
                :result,
                :odd,
                :operationCode,
                :withCredit,
                :bankrollId)";	
            $arrData = [
                'betAt' => $data['bet_at'],
                'valueBet' => (float)$data['value_bet'],
                'returnedValue' => $returnedValue,
                'result' => $result,
                'odd' => (float)$data['odd'],
                'operationCode' => $data['operation_code'],
                'withCredit' => (int)$data['operation_credit'] == 2 ? 1 : 0,
                'bankrollId' => $bankrollData[0]['id']
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
            {
                $this->logger->error("Erro ao registrar operação");
                throw new \Exception("Não foi possível regitrar a operação.");
            }

            $this->flash->addMessage('success', 'Operação registrada com sucesso.');

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
}