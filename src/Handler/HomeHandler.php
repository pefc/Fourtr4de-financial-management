<?php 

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Psr\Container\ContainerInterface;
use Slim\Flash\Messages;
use PDO;

class HomeHandler implements RequestHandlerInterface
{
    private $twig;
    private $flash;
    private $pdo;

    public function __construct(\Twig\Environment $twig, \Slim\Flash\Messages $flash, PDO $pdo)
    {
        $this->twig = $twig;
        $this->flash = $flash;
        $this->pdo = $pdo;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {        
        $response = new Response();
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        // CONFIGURAÇÕES DA BANCA
        $stmt = $this->pdo->prepare("SELECT id, initial_bankroll, yield, stop_win, stop_loss, created_at FROM bankroll WHERE users_id = :userId AND status = 'A' LIMIT 1");
        $stmt->execute(['userId' => $_SESSION['user']['id']]);
        $configurationsBankrollData = $stmt->fetchAll(PDO::FETCH_ASSOC);



        // FLUXO DE CAIXA - mais recente
        $cashFlowData = array();
        if ( !empty($configurationsBankrollData[0]['id']) )
        {
            $stmt = $this->pdo->prepare("SELECT * FROM cash_flow WHERE bankroll_id = :bankrollId ORDER BY id DESC LIMIT 1");
            $stmt->execute(['bankrollId' => $configurationsBankrollData[0]['id']]);
            $cashFlowData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // FLUXO DE CAIXA - ultimo dia
        $cashFlowLastDayData = array();
        if ( !empty($configurationsBankrollData[0]['id']) )
        {
            $stmt = $this->pdo->prepare("SELECT * FROM cash_flow WHERE bankroll_id = :bankrollId AND DATE(created_at) <> CURDATE() ORDER BY id DESC LIMIT 1");
            $stmt->execute(['bankrollId' => $configurationsBankrollData[0]['id']]);
            $cashFlowLastDayData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


        // BANCA ATUAL
        $bankroll = $cashFlowData[0]['result'] ? (float)$cashFlowData[0]['result'] : 0.0;

        // BANCA ANTERIOR
        $bankrollLastDay = $cashFlowLastDayData[0]['result'] ? (float)$cashFlowLastDayData[0]['result'] : 0.0;

        // BANCA INICIAL
        $initial_bankroll = !empty($configurationsBankrollData[0]['initial_bankroll']) ? (float)$configurationsBankrollData[0]['initial_bankroll'] : 0.0;

        // MARGEM DE LUCRO
        $yield = !empty($configurationsBankrollData[0]['yield']) ? (float)$configurationsBankrollData[0]['yield'] : 0.0;

        // LUCRO TOTAL ACUMLADO
        $accumulatedYield = $cashFlowData[0]['operations'] ? (float)$cashFlowData[0]['operations'] : 0.0;

        // LUCRO TOTAL ACUMLADO ANTERIOR
        $accumulatedYieldBefore = $cashFlowLastDayData[0]['operations'] ? (float)$cashFlowLastDayData[0]['operations'] : 0.0;

                

        // ICONE DO LUCRO ACUMULADO
        $colorAccumulatedYield = "yellow";
        $narrowAccumulatedYield = "minus";
        if ( $accumulatedYield > $accumulatedYieldBefore)
        {
            $colorAccumulatedYield = "green";
            $narrowAccumulatedYield = "caret-up";
        }
        elseif ( $accumulatedYield < $accumulatedYieldBefore)
        {
            $colorAccumulatedYield = "red";
            $narrowAccumulatedYield = "caret-down";
        }


        // STOP WIN
        $percentageStopWin = !empty($configurationsBankrollData[0]['stop_win']) ? (float)$configurationsBankrollData[0]['stop_win']/100 : 0.0;
        $stopWin = (($bankrollLastDay*$percentageStopWin)+$bankrollLastDay);


        
        // STOP LOSS
        $percentageStopLoss = !empty($configurationsBankrollData[0]['stop_loss']) ? (float)$configurationsBankrollData[0]['stop_loss']/100 : 0.0;
        $stopLoss = ($bankrollLastDay-($bankrollLastDay*$percentageStopLoss));



        // TOTAL DE DEPÓSITOS
        if ( !empty($configurationsBankrollData[0]['id']) )
        {
            $stmt = $this->pdo->prepare("SELECT sum(value) as result FROM deposits WHERE bankroll_id = :bankrollId AND status = 'A'");
            $stmt->execute(['bankrollId' => $configurationsBankrollData[0]['id']]);
            $depositsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalDeposits = (float)$depositsData[0]['result'];
        }
        else
            $totalDeposits = 0.0;



        // TOTAL DE SAQUES
        if ( !empty($configurationsBankrollData[0]['id']) )
        {
            $stmt = $this->pdo->prepare("SELECT sum(value) as result FROM withdrawals WHERE bankroll_id = :bankrollId AND status = 'A'");
            $stmt->execute(['bankrollId' => $configurationsBankrollData[0]['id']]);
            $withdrawalsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalWithdrawals = (float)$withdrawalsData[0]['result'];
        }
        else
            $totalWithdrawals = 0.0;



        // LUCRO DO ULTIMO DIA DE OPERAÇÃO
        if ( !empty($configurationsBankrollData[0]['id']) )
        {
            $stmt = $this->pdo->prepare("SELECT sum(result) as result FROM operations WHERE bankroll_id = :bankrollId AND status = 'A' AND bet_at = (select max(bet_at) from operations where bankroll_id = :bankrollId AND DATE(bet_at) <> CURDATE())");
            $stmt->execute(['bankrollId' => $configurationsBankrollData[0]['id']]);
            $dailyYieldLastDayData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dailyYieldLastDay = !empty($dailyYieldLastDayData[0]['result']) ? (float)$dailyYieldLastDayData[0]['result'] : 0.0;
        }
        else
            $dailyYieldLastDay = 0.0;


        // LUCRO DO DIA
        if ( !empty($configurationsBankrollData[0]['id']) )
        {
            $stmt = $this->pdo->prepare("SELECT sum(result) as result FROM operations WHERE bankroll_id = :bankrollId AND status = 'A' AND DATE(bet_at) = CURDATE()");
            $stmt->execute(['bankrollId' => $configurationsBankrollData[0]['id']]);
            $dailyYieldData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dailyYield = !empty($dailyYieldData[0]['result']) ? (float)$dailyYieldData[0]['result'] : 0.0;
        }
        else
            $dailyYield = 0.0;


        // ICONE DO LUCRO DO DIA
        $colorDailyYield = "yellow";
        $narrowDailyYield = "minus";
        if ( $dailyYield == 0)
        {
            $colorDailyYield = "yellow";
            $narrowDailyYield = "minus";
        }
        elseif ( $dailyYield > $dailyYieldLastDay)
        {
            $colorDailyYield = "green";
            $narrowDailyYield = "caret-up";
        }
        elseif ( $dailyYield < $dailyYieldLastDay)
        {
            $colorDailyYield = "red";
            $narrowDailyYield = "caret-down";
        }
        

        // META DO DIA
        $goal = ($bankrollLastDay*$yield)/100;
        $percenteGoal = $goal ? ($dailyYield*100)/$goal : 0;
        if ($percenteGoal >= 100)
            $percenteGoal = 100.0;
        if ($percenteGoal < 0)
            $percenteGoal = 0.0;



        // PORCENTAGEM DE CRESCIMENTO DA BANCA
        $percentBankrollYield = $initial_bankroll ? (($bankroll*100)/$initial_bankroll)-100 : 0;
        $colorPercentBankrollYield = "yellow";
        if ( $percentBankrollYield > 0)
            $colorPercentBankrollYield = "green";
        elseif ( $percentBankrollYield < 0)
            $colorPercentBankrollYield = "red";


        // TABELA DE GESTÃO DE APOSTAS
        $tableGales = null;
        if ( !empty($configurationsBankrollData[0]['id']) )
        {
            $percentage = 0.005;
            $tableGales = array();
            for ($i=0; $i < 16; $i++) { 
                $color = $i%2 ? 'bg-zinc-700' : '';
                $gale = $bankrollLastDay*$percentage;
                $valuePercentage = $percentage*100;
                $greensRequired = $gale ? $goal/$gale : 0;
                $tableGales[] = array(
                    'gale' => number_format($gale,2,",","."),
                    'valuePercentage' => number_format($valuePercentage,1,",","."),
                    'greensRequired' => ceil($greensRequired),
                    'color' => $color,
                );
                $percentage += 0.005;
            }
        }






                // if ( !empty($configurationsBankrollData[0]['id']) )
        // {
        //     $stmt = $this->pdo->prepare("SELECT sum(result) as result FROM operations WHERE bankroll_id = :bankrollId AND status = 'A' AND bet_at <= (select max(bet_at) from operations where bankroll_id = :bankrollId AND DATE(bet_at) <> CURDATE())");
        //     $stmt->execute(['bankrollId' => $configurationsBankrollData[0]['id']]);
        //     $accumulatedYieldLastDayData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //     $accumulatedYieldBefore = (float)$accumulatedYieldLastDayData[0]['result'];
        // }
        // else
            
        

        // LUCRO POR DIA - 10 DIAS
        if ( !empty($configurationsBankrollData[0]['id']) )
        {
            $stmt = $this->pdo->prepare("SELECT sum(result) as result, bet_at AS result_at FROM operations WHERE bankroll_id = :bankrollId AND status = 'A' GROUP BY bet_at ORDER BY bet_at DESC LIMIT 10");
            $stmt->execute(['bankrollId' => $configurationsBankrollData[0]['id']]);
            $yieldByDayData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


        // LUCRO TOTAL ACUMULADO POR DIA - ULTIMOS 10 DIAS
        // echo "<pre>";
        // $total = 0;
        // var_dump($yieldByDayData);
        // $accumulatedYieldDayByDayData = array();
        // foreach ($yieldByDayData as $data )
        // {
        //     $totalAccumulated += $data['result'];
        //     $accumulatedYieldDayByDayData[] = array('result' => $totalAccumulated, 'result_at' => $data['result_at']);
        // }
        // var_dump($accumulatedYieldDayByDayData);
        // die();



        
        // BANCA ATUAL
        // $bankroll = ($initial_bankroll+$accumulatedYield+$totalDeposits)-$totalWithdrawals;


        // BANCA DO DIA
        // $lastBankroll = ($initial_bankroll+$accumulatedYieldBefore+$totalDeposits)-$totalWithdrawals


        





        


        $args = [
            'configurationsBankroll' => array(
                'initial_bankroll' => !empty($configurationsBankrollData[0]['initial_bankroll']) ? $configurationsBankrollData[0]['initial_bankroll'] : '',
                'yield' => !empty($configurationsBankrollData[0]['yield']) ? $configurationsBankrollData[0]['yield'] : '',
                'stop_win' => !empty($configurationsBankrollData[0]['stop_win']) ? $configurationsBankrollData[0]['stop_win'] : '',
                'stop_loss' => !empty($configurationsBankrollData[0]['stop_loss']) ? $configurationsBankrollData[0]['stop_loss'] : '',
                'created_at' => !empty($configurationsBankrollData[0]['created_at']) ? $configurationsBankrollData[0]['created_at'] : date('Y-m-d'),
            ),
            
            'bankroll' => number_format($bankroll,2,",","."),

            'percentBankrollYield' => number_format($percentBankrollYield,2,",","."),
            'colorPercentBankrollYield' => $colorPercentBankrollYield,

            'dailyYield' => number_format($dailyYield,2,",","."),
            'colorDailyYield' => $colorDailyYield,
            'narrowDailyYield' => $narrowDailyYield,

            'accumulatedYield' => number_format($accumulatedYield,2,",","."),
            'colorAccumulatedYield' => $colorAccumulatedYield,
            'narrowAccumulatedYield' => $narrowAccumulatedYield,

            'yield' => number_format($yield,2,",","."),

            'goal' => number_format($goal,2,",","."),
            'percenteGoal' => round($percenteGoal,2),
            
            'stopWin' => number_format($stopWin,2,",","."),
            'stopLoss' => number_format($stopLoss,2,",","."),

            'tableGales' => $tableGales,


            'pageTitle' => "Dashboard",
            'pageData' => $_SESSION['pageData'],
            'actionRegisterBankroll' => $routeParser->urlFor('actionRegisterBankroll'),
            'actionDeleteBankroll' => $routeParser->urlFor('actionDeleteBankroll'),
            'actionSaveOperation' => $routeParser->urlFor('actionSaveOperation'),
            'actionSaveDeposit' => $routeParser->urlFor('actionSaveDeposit'),
            'actionSaveWithdrawal' => $routeParser->urlFor('actionSaveWithdrawal'),
        ];


        $response->getBody()->write(
            $this->twig->render('restricted/index.twig', $args)
        );
        return $response;
    }
}
