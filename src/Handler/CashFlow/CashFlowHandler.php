<?php 

declare(strict_types=1);

namespace App\Handler\CashFlow;

use Psr\Log\LoggerInterface;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;


class CashFlowHandler
{
    private $logger;
    private $container;
    private $pdo;

    public function __construct(LoggerInterface $logger, ContainerInterface $container, PDO $pdo)
    {
        $this->logger = $logger;
        $this->container = $container;
        $this->pdo = $pdo;
    }


    public function saveCashFlow()
    {
        try 
        {
            // CONFIGURAÇÕES DA BANCA
            $stmt = $this->pdo->prepare("SELECT id, initial_bankroll FROM bankroll WHERE users_id = :userId AND status = 'A' LIMIT 1");
            $stmt->execute(['userId' => $_SESSION['user']['id']]);
            $bankrollData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ( empty($bankrollData[0]['id']) )
                throw new \Exception("Erro ao buscar informações da banca do usuário no fluxo de caixa");                


            // TOTAL DE DEPÓSITOS
            if ( !empty($bankrollData[0]['id']) )
            {
                $stmt = $this->pdo->prepare("SELECT sum(value) as result FROM deposits WHERE bankroll_id = :bankrollId AND status = 'A'");
                $stmt->execute(['bankrollId' => $bankrollData[0]['id']]);
                $depositsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $totalDeposits = $depositsData[0]['result'] ? (float)$depositsData[0]['result'] : 0.0;
            }
            else
                $totalDeposits = 0.0;



            // TOTAL DE SAQUES
            if ( !empty($bankrollData[0]['id']) )
            {
                $stmt = $this->pdo->prepare("SELECT sum(value) as result FROM withdrawals WHERE bankroll_id = :bankrollId AND status = 'A'");
                $stmt->execute(['bankrollId' => $bankrollData[0]['id']]);
                $withdrawalsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $totalWithdrawals = $withdrawalsData[0]['result'] ? (float)$withdrawalsData[0]['result'] : 0.0;
            }
            else
                $totalWithdrawals = 0.0;



            // TOTAL DE LUCRO
            if ( !empty($bankrollData[0]['id']) )
            {
                $stmt = $this->pdo->prepare("SELECT sum(result) as result FROM operations WHERE bankroll_id = :bankrollId AND status = 'A'");
                $stmt->execute(['bankrollId' => $bankrollData[0]['id']]);
                $withdrawalsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $totalYield = (float)$withdrawalsData[0]['result'];
            }
            else
                $totalYield = 0.0;


            $result = ((float)$bankrollData[0]['initial_bankroll']+$totalDeposits+$totalYield)-$totalWithdrawals;
            $sql = "
            INSERT INTO 
            cash_flow 
                (bankroll, 
                deposits, 
                withdrawals,
                operations,
                result,
                bankroll_id)
            VALUES 
                (:bankroll, 
                :deposits, 
                :withdrawals,
                :operations,
                :result,
                :bankrollId)";	
            $arrData = [
                'bankroll' => (float)$bankrollData[0]['initial_bankroll'],
                'deposits' => $totalDeposits,
                'withdrawals' => $totalWithdrawals,
                'operations' => $totalYield,
                'result' => $result,
                'bankrollId' => $bankrollData[0]['id']
            ];
            $stmt = $this->pdo->prepare($sql);
            if ( $stmt->execute($arrData) === false )
                throw new \Exception("Erro ao salvar o fluxo de caixa");

            return true;
        }
        catch ( \Exception $e )
        {
            return false;
        }
    }
}